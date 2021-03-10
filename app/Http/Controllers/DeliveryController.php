<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\Paid;
use App\Sale;
use App\Commerce;
use App\User;
use App\Settings;
use App\Events\StatusDelivery;
use App\Events\AlarmUrgent;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RetirementProductClient;

class DeliveryController extends Controller
{

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

        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        $paids =Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                    ->leftJoin('pictures', 'pictures.commerce_id', '=', 'paids.commerce_id')
                    ->where('paids.statusDelivery',1)
                    ->whereNull('paids.idDelivery')
                    ->where('pictures.description','Profile')
                    ->select('paids.id', 'paids.codeUrl', 'commerces.name', 'commerces.address', 'pictures.url')
                    ->get();
    
        return response()->json([
            'statusCode' => 201,
            'data' => $paids
        ]);
    }

    public function test()
    {
        $paid = Paid::where('codeUrl', 'k4xsus')->first();
        $commerce = Commerce::whereId($paid->commerce_id)->first();
        $sales = Sale::where('codeUrl', 'k4xsus')->get();

        (new User)->forceFill([
            'email' => $paid->email,
        ])->notify(
            new RetirementProductClient($commerce, $paid, $sales)
        ); 

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
