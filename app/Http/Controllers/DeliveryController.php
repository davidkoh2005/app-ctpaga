<?php

namespace App\Http\Controllers;

use AWS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Delivery;
use App\Paid;
use App\Sale;
use App\Commerce;
use App\User;
use App\Picture;
use App\Document;
use App\Deposits;
use App\Settings;
use App\Email;
use App\Cash;
use App\DeliveryCost;
use App\PaymentsBs;
use App\PaymentsZelle;
use App\Events\StatusDelivery;
use App\Events\AlarmUrgent;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\NewUser;
use App\Notifications\NewDelivery;
use App\Notifications\UserPaused;
use App\Notifications\UserRejected;
use App\Notifications\SendDeposits;
use App\Notifications\SendDepositsProcess;
use App\Notifications\PaymentCancel;
use App\Notifications\PaymentConfirm;
use App\Notifications\PaymentVerification;
use App\Notifications\NotificationAdmin;
use App\Notifications\NotificationCommerce;
use App\Notifications\PasswordResetSuccess;
use App\Notifications\PasswordResetRequest;
use App\Notifications\NotificationDelivery;
use App\Notifications\DeliveryProductClient;
use App\Notifications\DeliveryProductCommerce;
use App\Notifications\RetirementProductClient;
use App\Notifications\RetirementProductCommerce;
use App\Notifications\DeliveryProductClientInitial;
use App\Notifications\DeliveryProductCommerceInitial;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\ApiClient;

class DeliveryController extends Controller
{

    public function updateImg(Request $request)
    {
        $delivery = $request->user();
        $realImage = base64_decode($request->image);
        $date = Carbon::now()->format('Y-m-d');

        if($request->description == 'Profile')
            $url = '/Delivery/'.$delivery->id.'/storage/Profile-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
        
        if($request->urlPrevious != ''){
            $urlPrevius = substr($request->urlPrevious,8);
            \Storage::disk('public')->delete($urlPrevius);
        }
        \Storage::disk('public')->put($url,  $realImage);

        Picture::updateOrCreate([
            'user_id'=>$delivery->id,
            'description'=> $request->description,
            'type'=> 1,
        ],
        ['url' => '/storage'.$url]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update image correctly',
            'url' => '/storage'.$url,
        ]);
    }

    public function updateDocuments(Request $request)
    {
        $delivery = $request->user();

        $date = Carbon::now()->format('Y-m-d');
            
        if($request->description == 'License')
            $url = '/Delivery/'.$delivery->id.'/storage/License-'.Carbon::now()->format('d-m-Y_H-i-s').'.'.$request->type;
        else if($request->description == 'Driving License')
            $url = '/Delivery/'.$delivery->id.'/storage/Driving-License-'.Carbon::now()->format('d-m-Y_H-i-s').'.'.$request->type;
        else if($request->description == 'Civil Liability')
            $url = '/Delivery/'.$delivery->id.'/storage/Civil-Liability-'.Carbon::now()->format('d-m-Y_H-i-s').'.'.$request->type;
        else if($request->description == 'Selfie')
            $url = '/Delivery/'.$delivery->id.'/storage/Selfie-'.Carbon::now()->format('d-m-Y_H-i-s').'.'.$request->type;
        
        if($request->urlPrevious != ''){
            $urlPrevius = substr($request->urlPrevious,8);
            \Storage::disk('public')->delete($urlPrevius);
        }

        if($request->type == "jpg"){
            $realImage = base64_decode($request->image);
            \Storage::disk('public')->put($url,  $realImage);

        }else{
            $realFile = base64_decode($request->fileDocument);
            \Storage::disk('public')->put($url,  $realFile);
        }

        $document = Document::updateOrCreate([
            'delivery_id'=>$delivery->id,
            'description'=> $request->description,
        ],
        [
            'url' => '/storage'.$url,
        ]);

        $document->type = $request->type;
        $document->save();

        $count = count(Document::where('delivery_id', $delivery->id)->get());

        $emailsGet = Settings::where('name','Email Delivery')->first();

        if($emailsGet && $count == 4){
            $emails = json_decode($emailsGet->value);
            $messageAdmin = "el delivery ".$delivery->name." agrego todos los documentos correspondiente para el servicio de Delivery.";
            foreach($emails as $email){
                (new User)->forceFill([
                    'email' => $email,
                ])->notify(
                    new NotificationAdmin($messageAdmin)
                );
            } 
        }

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update documents correctly',
        ]); 
    }

    public function update(Request $request)
    {   
        $delivery = $request->user();
        Delivery::whereId($delivery->id)->update($request->all());
        
        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function showPaidAll(Request $request)
    {   
        $delivery = $request->user();

        $paids =Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                    ->leftJoin('pictures', 'pictures.commerce_id', '=', 'paids.commerce_id')
                    ->where('paids.statusDelivery',1)
                    ->whereNull('paids.idDelivery')
                    ->where('pictures.description','Profile')
                    ->where('pictures.type',0)
                    ->select('paids.id', 'paids.codeUrl', 'commerces.name', 'commerces.address', 'pictures.url')
                    ->orderBy('paids.id','ASC')
                    ->get();
    
        return response()->json([
            'statusCode' => 201,
            'data' => $paids
        ]);
    }

    public function updateColumnIdUrl()
    {

        $count = Delivery::where('idUrl', '')->count();

        while ($count > 0){
            $delivery = Delivery::where('idUrl', '')->first();
            $code = $this->randomCode();
            $delivery->idUrl = $code;
            $delivery->save();
            $count--;
        }

        return "Update Column Delivery";

    }

    public function randomCode()
    {
        $longitud = 6;
        do
        {
            $code = '';
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
            $max = strlen($pattern)-1;
            for($i=0;$i < $longitud;$i++) 
                $code .= $pattern{mt_rand(0,$max)};

            $statusCode = Delivery::where('idUrl', $code)->first();
        }
        while(!empty($statusCode));

        return $code;
    }

    public function showDelivery($idUrl)
    {
        $delivery = Delivery::where('idUrl',$idUrl)->first();
        $picture = Picture::where('user_id', $delivery->id)
                        ->where('commerce_id', '=', null)
                        ->where('type',1)->first();
        return view('profileDelivery', compact('delivery', 'picture'));
    }

    public function test()
    {

        /*
        $user = User::where('email', 'angelgoitia1995@gmail.com')->first();
        $deposits = Deposits::where('user_id', $user->id)->first();
        $paid = Paid::where('email', 'angelgoitia1995@gmail.com')->first();
        $commerce = Commerce::whereId($paid->commerce_id)->first();
        $sales = Sale::where('codeUrl', $paid->codeUrl)->get();

        $delivery = Delivery::where('email', 'angelgoitia1995@gmail.com')->first();

        $messageAdmin = $messageAdmin = " el delivery ".$delivery->name." entrego los productos de código de compra: ".$paid->codeUrl." a su destino.";

        /* (new User)->forceFill([
            'email' => $paid->email,
        ])->notify(
            new DeliveryProductClientInitial($commerce, $paid, $sales, $delivery)
        );  

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new DeliveryProductCommerceInitial($commerce, $paid, $sales, $delivery)
        );

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new NotificationCommerce($commerce, $paid->codeUrl, 0)
        );
        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new NotificationCommerce($commerce, $paid->codeUrl, 1)
        );
        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new NotificationCommerce($commerce, $paid->codeUrl, 2)
        );

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new UserRejected($user, 0)
        ); 

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new UserPaused($user, 0)
        ); 

        (new User)->forceFill([
            'email' => 'angelgoitia1995@gmail.com',
        ])->notify(
            new NotificationAdmin($messageAdmin)
        );

        (new User)->forceFill([
            'email' => $delivery->email,
        ])->notify(
            new NotificationDelivery("fue asignado el siguiente orden: ".$paid->codeUrl, $delivery)
        );

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new SendDeposits($user, $deposits)
        ); 

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new SendDepositsProcess($user, $deposits)
        ); 

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new PaymentCancel($paid->nameClient, $paid->codeUrl)
        );

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new PaymentVerification($paid->nameClient, $paid->codeUrl)
        );

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new PaymentConfirm($paid->nameClient, $paid->codeUrl)
        );

        (new User)->forceFill([
            'email' => $paid->email,
        ])->notify(
            new DeliveryProductClient($commerce, $paid, $sales)
        );  

        (new User)->forceFill([
            'email' => $paid->email,
        ])->notify(
            new DeliveryProductCommerce($commerce, $paid, $sales)
        );

        (new User)->forceFill([
            'email' => $paid->email,
        ])->notify(
            new RetirementProductClient($commerce, $paid, $sales)
        );  

        (new User)->forceFill([
            'email' => $paid->email,
        ])->notify(
            new RetirementProductCommerce($commerce, $paid, $sales)
        ); 

        $user->notify(new PasswordResetSuccess(0, $user));

        $user->notify(
            new PasswordResetRequest(Str::random(60), 0, $user)
        );

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new NewDelivery($user)
        ); 

        (new User)->forceFill([
            'email' => $user->email,
        ])->notify(
            new NewUser($user)
        );  */

        /* $url = "https://fcm.googleapis.com/fcm/send";
        $token = "cSjCw2o7RTukByosQ88K9h:APA91bHIvDDhHDYxgyV_ohr3BnHTS1rTexoB126-RmBO1xXcah-T4E5aqZH-gLP6_Mh1KW6Ii8aph73wkqjbrOCIrS4oDJTb2Kd5ntiXeyVMk2DMcQj_7mk6Tf-B9i5UVgXNaacDEmhU";
        $serverKey = 'AAAAfePilJc:APA91bFN28RaVDSoThRCrL1hsoECaoAEWnG5VcqLES2xRCtRoMYMl4YXbaHQ0MmWAVEzjNoQvLuvaRQRbPs7Rvv8CpoBeBTGVph9oFD7dKSI8D9VSKhY3NKKsBHBq90J74lGioJDQC92';
        $title = "Notification title";
        $body = "Hello I am from Your php server";
        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Send the request
        $response = curl_exec($ch);
        curl_close($ch);  */

    }

    
}
