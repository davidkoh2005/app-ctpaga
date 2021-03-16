<?php

namespace App\Http\Controllers;

use DB;
use Session;
use PDF;
use App\User;
use App\Bank;
use App\Paid;
use App\Sale;
use App\Rate;
use App\Admin;
use App\Email;
use App\Picture;
use App\Balance;
use App\Commerce;
use App\Deposits;
use App\Delivery;
use App\Product;
use App\Service;
use App\Settings;
use Carbon\Carbon;
use App\Notifications\UserPaused;
use App\Notifications\UserRejected;
use App\Notifications\PictureRemove;
use App\Notifications\ConfirmBank;
use App\Notifications\SendDeposits;
use App\Notifications\PostPurchase;
use App\Notifications\PaymentConfirm;
use App\Notifications\PaymentCancel;
use App\Notifications\NotificationDelivery;
use App\Events\SendCode;
use App\Events\StatusDelivery;
use App\Events\NewNotification;
use App\Http\Controllers\Controller;
use App\Exports\DepositsExport;
use App\Exports\RatesExport;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
   
    public function dashboard(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $totalShopping = 0;
        $totalShoppingUSD = 0;
        $totalShoppingBS = 0;
        $totalPendingUSD=0;
        $totalPendingBS=0;
        $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format('Y-m-d')."%")->get();

        foreach ($paidAll as $paid)
        {
            $totalShopping += 1;
            if($paid->coin == 0)
                if($paid->statusPayment == 2)
                    $totalShoppingUSD += floatval($paid->total);
                else
                    $totalPendingUSD += floatval($paid->total); 
            else
                if($paid->statusPayment == 2)
                    $totalShoppingBS += floatval($paid->total);
                else
                    $totalPendingBS += floatval($paid->total); 

        }

        $statusMenu = "dashboard";
        $idCommerce = 0;
        return view('auth.dashboard',compact("totalShopping", "totalShoppingUSD", "totalShoppingBS", "statusMenu", 'idCommerce', 'totalPendingUSD', 'totalPendingBS'));
    }

    public function dataGraphic(Request $request)
    {
        $month = Carbon::now()->format('m');
        $years = Carbon::now()->format('Y');
        $listDay= array();
        $count=1;
        for ($i = 0; $i < 7; $i++) {
            $totalShop = 0;
            $totalShopUSD = 0;
            $totalShopBS = 0;
            if(Auth::guard('admin')->check())
                $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(6-$i)->format('d'))."%")
                                ->where("statusPayment",2)->get();
            else
                $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(6-$i)->format('d'))."%")
                                ->where('user_id',Auth::guard('web')->id())
                                ->where('commerce_id',$request->commerce_id)
                                ->where("statusPayment",2)
                                ->get();
            
            foreach ($paidAll as $paid)
            {
                $totalShop += 1;
                
                if($paid->coin == 0)
                    $totalShopUSD += floatval($paid->total);
                else
                    $totalShopBS += floatval($paid->total);
            }
            $listDay[$i]['dia'] = Carbon::now()->subDay(6-$i)->format('d');
            $listDay[$i]['totalSales'] = $totalShop;
            $listDay[$i]['totalUSD'] = $totalShopUSD;
            $listDay[$i]['totalBS'] = $totalShopBS;
        }

        $listDayJson = json_encode($listDay);
        echo json_encode($listDay);
    }

    public function index(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $selectCoin=0;
        $searchStatus=0;

        $deposits = DB::table('deposits')
                ->join('commerces', 'commerces.id', '=', 'deposits.commerce_id')
                ->where('deposits.total', '>=', 1)
                ->where('commerces.confirmed', true)
                ->select('deposits.id', 'deposits.user_id', 'deposits.commerce_id', 'deposits.coin', 'deposits.total', 'deposits.status',
                'commerces.name')
                ->orderBy('commerces.name', 'asc');
        
        if($request->all()){
            $selectCoin= $request->selectCoin;
            $searchStatus = $request->searchStatus;
            $deposits = $deposits->where('deposits.coin', $selectCoin);
        }

        if(!empty($request->searchStatus))
            $deposits = $deposits->where('deposits.status', $searchStatus);
            
        $deposits = $deposits->get();
        $statusMenu = "balance";

        return view('admin.balance', compact('deposits',"statusMenu",'selectCoin','searchStatus'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('admin.dashboard'));
        }

        Session::flash('message', "El correo o la contraseña es incorrecta!");
        return Redirect::back();
    }


    public function logout(Request $request)
    {
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        }

        Auth::guard('web')->logout();
        $request->session()->flush();
        return redirect()->route('commerce.login');
    } 


    public function commercesShow($id)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $commerce = Commerce::where("id", $id)->first();
        $user = User::where("id", $commerce->user_id)->first();
        $pictures = Picture::where('user_id', $user->id)
                            ->where('commerce_id', $commerce->id)
                            ->where('description', '<>','Profile')->get();

        $selfie = Picture::where('user_id', $user->id)
                        ->where('commerce_id', '=', null)->first();

        $statusMenu = "commerces";
        return view('admin.commerceShow', compact('commerce', 'user', 'pictures', 'selfie','statusMenu'));
    }

    public function confirmedCommerce(Request $request)
    {
        $commerce = Commerce::where("id", $request->id)->first();
        $commerce->confirmed = $request->status == "true"?1:0;
        $commerce->save();

        $user = User::whereId($commerce->user_id)->first();

        if($request->status == "true"){
            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new ConfirmBank($user, $commerce)
            ); 
        }elseif($request->status == "false"){
            $sentEmail = Email::firstOrNew([
                'user_id'       => $user->id,
                'commerce_id'   => $commerce->id,
            ]);

            if(!$sentEmail->date || Carbon::parse($sentEmail->date)->format('Y-m-d') != Carbon::now()->format('Y-m-d')){
                (new User)->forceFill([
                    'email' => $user->email,
                ])->notify(
                    new PictureRemove($commerce)
                ); 
            }
            $sentEmail->date = Carbon::now();
            $sentEmail->save();
        }
        

        return response()->json([
            'status' => 201
        ]);
    }

    public function removePicture(Request $request)
    {
        $picture = Picture::where('id', $request->id)->first();
        $urlPrevius = substr($picture->url,8);

        $user = User::where('id', $picture->user_id)->first();

        \Storage::disk('public')->delete($urlPrevius);
        $picture->delete();

        $commerce = Commerce::where("id", $request->idCommerce)->first();
        $commerce->confirmed = false;
        $commerce->save();

        $sentEmail = Email::firstOrNew([
            'user_id'       => $user->id,
            'commerce_id'   => $commerce->id,
        ]);

        if(!$sentEmail->date || Carbon::parse($sentEmail->date)->format('Y-m-d') != Carbon::now()->format('Y-m-d')){
            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new PictureRemove($commerce)
            ); 

            $sentEmail->date = Carbon::now();

        }

        $sentEmail->save();

        return response()->json([
            'status' => 201
        ]);
    }

    public function saveDeposits(Request $request)
    {
        foreach ($request->selectId as $id)
        {
            $deposits = Deposits::where('id', $id)->first(); 
            $user = User::where('id', $deposits->user_id)->first(); 
                                
            $deposits->status = 3;
            $deposits->numRef = $request->numRef;
            
            $deposits->save();

            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new SendDeposits($user, $deposits)
            );

        }

        return response()->json([
            'status' => 201
        ]);
    }

    public function commerces(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $commerces = Commerce::whereNotNull("name")
                    ->whereNotNull("rif")
                    ->whereNotNull("address")
                    ->whereNotNull("phone")
                    ->whereNotNull("userUrl")
                    ->orderBy("name","asc")
                    ->orderBy("confirmed")->get();

        $statusMenu = "commerces";
        return view('admin.commerces', compact('commerces','statusMenu'));
    }



    public function transactions(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $searchNameCompany="";
        $searchNameClient="";
        $selectCoin="Selecionar Moneda";
        $selectPayment="Selecionar Tipo de Pago";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $idCommerce=0;
        $companyName = "";
        if($request->id){
            $idCommerce=intVal($request->id);
            
            $commerce = Commerce::whereId($idCommerce)->first();
            $companyName = $commerce->name;

            $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->where('paids.commerce_id', 'like', "%".$request->id. "%" )
                        ->orderBy('paids.id', 'desc');
        }else{
            $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->orderBy('paids.id', 'desc');
        }

        if($request->all()){
            $idCommerce = $idCommerce == 0? $request->idCommerce : $idCommerce;
            $searchNameCompany=$request->searchNameCompany;
            $searchNameClient=$request->searchNameClient;
            $selectCoin=$request->selectCoin;
            $selectPayment=$request->selectPayment;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }


        if(!empty($request->idCommerce))
            $transactions = $transactions->where('commerces.id', $request->idCommerce); 

        if(!empty($request->searchNameCompany))
            $transactions = $transactions->where('commerces.name', 'like', '%'.$request->searchNameCompany.'%' );
        
        if(!empty($request->searchNameClient))
            $transactions = $transactions->where('paids.nameClient', 'like', '%'.$request->searchNameClient.'%');

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda")
            $transactions = $transactions->where('paids.coin', $request->selectCoin);
        
        if(!empty($request->selectPayment) && $request->selectPayment != "Selecionar Tipo de Pago")
            $transactions = $transactions->where('paids.nameCompanyPayments', $request->selectPayment);

        $transactions = $transactions->whereDate('paids.created_at', ">=",$startDate)
                    ->whereDate('paids.created_at', "<=",$endDate)
                    ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.coin', 'paids.total',
                                    'paids.date', 'paids.nameCompanyPayments', 'paids.statusPayment', 'paids.codeUrl')
                    ->get();

        
        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.transactionsPDF', compact('transactions', 'today', 'idCommerce', 'companyName'))->setPaper('a4', 'landscape');
            return $pdf->download('ctpaga_transacciones.pdf');
        }elseif($request->statusFile == "EXCEL"){
            $today = Carbon::now()->format('Y-m-d');
            return Excel::download(new TransactionsExport($transactions, $today, $idCommerce, $companyName), 'ctpaga_transacciones.xlsx');
        }

        $statusMenu = "transactions";
        return view('admin.transactions', compact('transactions', 'searchNameCompany', 'searchNameClient', 'selectCoin', 'selectPayment', 'startDate', 'endDate', 'statusMenu','idCommerce', 'companyName'));
    }

    public function transactionsShow(Request $request)
    {
        $transaction = Paid::where('id', $request->id)->first();
        $sales = Sale::where('codeUrl', $transaction->codeUrl)->orderBy('name', 'asc')->get();
        $rate = $sales[0]->rate;
        $coinClient = $sales[0]->coinClient;
        $returnHTML=view('admin.dataProductService', compact('transaction','sales', 'rate', 'coinClient'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function changeStatus(Request $request)
    {
        foreach ($request->selectId as $id)
        {
            $deposits = Deposits::where('id', $id)->first();
            $deposits->status = $request->status;
            $deposits->numRef = "";
            $deposits->save();
        }
        
        return response()->json(array('status' => 201));
    }

    public function changeStatusPayment(Request $request)
    {
        foreach ($request->selectId as $id)
        {
            $transaction = Paid::where('id', $id)->first();
            $transaction->statusPayment = $request->status;
            $transaction->save();

            if($request->status == 2){

                $sales = Sale::where("codeUrl", $transaction->codeUrl)->get();
                $message="";
                foreach ($sales as $sale)
                {
                    if($sale->type == 0 && $sale->productService_id != 0){
                        $product = Product::where('id',$sale->productService_id)->first();
                        
                        if ($product->postPurchase)
                            $message .= "- ".$product->postPurchase."\n";

                        $product->stock -= $sale->quantity;
                        $product->save();
                    }

                    if($sale->type == 1 && $sale->productService_id != 0){
                        $service = Service::where('id',$sale->productService_id)->first();
                        
                        if($service->postPurchase)
                            $message .= "- ".$service->postPurchase."\n";
                    }

                }

                $commerce = Commerce::where('id',$transaction->commerce_id)->first();
                (new User)->forceFill([
                    'email' => $transaction->email,
                ])->notify(
                    new PaymentConfirm($transaction->nameClient, $transaction->codeUrl)
                );
            }elseif($request->status == 0){
                (new User)->forceFill([
                    'email' => $transaction->email,
                ])->notify(
                    new PaymentCancel($transaction->nameClient, $transaction->codeUrl)
                );
            }
        }
        
        return response()->json(array('status' => 201));
    }



    public function showPayment(Request $request)
    {
        $balance = null;
        $commerce = null;
        $user = null;
        $coin = "";    
        $bank = null;
        $selectId = $request->selectId;
        $selectCoin = $request->selectCoin;
        $statusSelect = true;
        $dataId = [];
        if($request->status == "true")
        {
            $statusID = true;
            $deposit = Deposits::where('id', $selectId[0])->first();
            $commerce = Commerce::where("id", $deposit->commerce_id)->first();
            $user = User::where("id", $commerce->user_id)->first();
            
            if($deposit->coin == 0)
                $coin = "USD";
            else
                $coin = "Bs";

            $bank = Bank::where('user_id', $user->id)
                        ->where('coin', $coin)->first();
            
            array_push($dataId, array(
                "id" => $deposit->id,
                "total" => $deposit->total,
            ));


        }else{
            $statusID = false;
            foreach ($selectId as $id)
            {
                $deposit = Deposits::where('id', $id)->first();
                if($deposit->coin != $selectCoin)
                    $statusSelect = false;

                array_push($dataId, array(
                    "id" => $deposit->id,
                    "total" => $deposit->total,
                ));
            }        

            if($statusSelect)
            {
                $returnHTML=view('admin.modal.dataPayment', compact('deposit', 'commerce', 'user', 'bank', 'statusID'))->render();
                return response()->json(array('html'=>$returnHTML, 'status' => 0));
            }else{
                $returnHTML=view('admin.modal.dataPayment', compact('deposit', 'commerce', 'user', 'bank', 'statusID'))->render();
                return response()->json(array('html'=>$returnHTML, 'status' => 1));
            }
        }

        $returnHTML=view('admin.modal.dataPayment', compact('deposit', 'commerce', 'user', 'bank', 'statusID'))->render();
        return response()->json(array('html'=>$returnHTML, 'status' => 0));
    }

    public function reportPayment(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $searchNameCompany="";
        $numRef="";
        $selectCoin="Selecionar Moneda";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        if($request->all()){
            $searchNameCompany=$request->searchNameCompany;
            $numRef=$request->numRef;
            $selectCoin=$request->selectCoin;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }

        $deposits = DB::table('deposits')
                ->join('commerces', 'commerces.id', '=', 'deposits.commerce_id')
                ->select('deposits.id', 'deposits.user_id', 'deposits.commerce_id', 'deposits.coin', 'deposits.total', 'deposits.numRef', 'deposits.date', 'commerces.name')
                ->where("deposits.status",3);
        if(!empty($request->searchNameCompany))
            $deposits = $deposits->where('commerces.name', 'like', "%" . $request->searchNameCompany . "%" );
        
        if(!empty($request->numRef))
            $deposits = $deposits->where('deposits.numRef', 'like', "%" . $request->numRef . "%" );

        if(!empty($request->searchNameClient))
            $deposits = $deposits->where('deposits.nameClient', 'like', "%" . $request->searchNameClient . "%" );

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda")
            $deposits = $deposits->where('deposits.coin', $request->selectCoin);
        
        $deposits = $deposits->whereDate('deposits.created_at', ">=",$startDate)
                        ->whereDate('deposits.created_at', "<=",$endDate);

        $deposits = $deposits->get();
        $today = Carbon::now()->format('Y-m-d');
        if($request->statusFile == "PDF"){
            $pdf = \PDF::loadView('report.depositsPDF', compact('deposits', 'today'));
            return $pdf->download('ctpaga_depositos.pdf');
        }elseif($request->statusFile == "EXCEL"){
            return Excel::download(new DepositsExport($deposits, $today), 'ctpaga_depositos.xlsx');
        }

        $statusMenu = "balance";
        return view('admin.reportPayment', compact('deposits', 'searchNameCompany', 'selectCoin', 'selectPayment', 'startDate', 'endDate', 'numRef', 'statusMenu', 'today'));
    }

    public function downloadTxt(Request $request)
    {
        if(file_exists("ctpaga.txt"))
            unlink("ctpaga.txt");
        
        $file=fopen("ctpaga.txt","a") or die("Problemas");
        fputs($file,"primera linea 1");
        fputs($file,"\n");
        fputs($file,"segunda linea 2");
        fputs($file,"\n");
        fputs($file,"tercera linea 3");
        fclose($file); 

        $url = 'http://'.$_SERVER['HTTP_HOST'].'/ctpaga.txt';
        return response()->json(array('url'=>$url, 'status' => 201));

    }

    public function delivery(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $searchNameCompany="";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $idCommerce=0;
        $companyName = "";
        $searchCodeUrl="";

        $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->orderBy('paids.statusDelivery', 'asc')
                        ->orderBy('paids.date', 'asc')
                        ->orderBy('paids.alarm', 'desc')
                        ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.selectShipping', 'paids.total',
                            'paids.date', 'paids.nameCompanyPayments', 'paids.idDelivery', 'paids.codeUrl', 'paids.alarm', 'paids.statusDelivery', 'paids.timeDelivery')
                        ->whereNotNull('paids.selectShipping')
                        ->where('paids.statusPayment',2);

        if($request->all()){
            $searchNameCompany=$request->searchNameCompany;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
            $searchCodeUrl = $request->searchCodeUrl;
        }

        if(!empty($request->searchNameCompany)){
            $transactions->where('commerces.name', 'like', "%" . $request->searchNameCompany . "%" );
        }


        if(!empty($request->searchCodeUrl)){
            $transactions->where('paids.codeUrl', 'like', "%" . $request->searchCodeUrl . "%" );
        }

        $transactions->whereDate('paids.date', ">=",$startDate);

        $transactions = $transactions->get();


        $statusMenu = "delivery";
        return view('admin.delivery', compact('transactions', 'searchNameCompany', 'searchNameClient', 'startDate', 'endDate', 'statusMenu','idCommerce', 'companyName', 'searchCodeUrl'));
    }


    public function deliverySendCode(Request $request)
    {   
        $paid = Paid::where("codeUrl",$request->codeUrl)->first();
        $paid->statusDelivery = 1;
        $paid->timeDelivery = Carbon::now()->addMinutes(10);
        $paid->save();
        
        return response()->json(array('status' => 201));
    }

    public function deliverySendCodeManual(Request $request)
    {   
        $delivery = Delivery::whereId($request->idDelivery)->first();

        if($delivery->statusAvailability && $delivery->codeUrlPaid == null){
            $delivery->statusAvailability = false;
            $delivery->codeUrlPaid = $request->codeUrl;
            $delivery->save();

            $paid = Paid::where("codeUrl",$request->codeUrl)->first();
            $paid->statusDelivery = 2;
            $paid->idDelivery = $request->idDelivery;
            $paid->save();

            $phone = '+'.app('App\Http\Controllers\Controller')->validateNum($paid->numberShipping);
            $fecha = Carbon::now()->format("d/m/Y");
            $message = "CTPaga Delivery le informa que ha realizado un pedido con el Nro ".$paid->codeUrl." con fecha de ".$fecha.", el cual será despachado en aproximadamente 1 hora.";
            $sms = AWS::createClient('sns');
            $sms->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SMSType'  => [
                        'DataType'    => 'String',
                        'StringValue' => 'Transactional',
                    ]
                ],
            ]); 

            (new User)->forceFill([
                'email' => $delivery->email,
            ])->notify(
                new NotificationDelivery("fue asignado el siguiente orden: ".$request->codeUrl, $delivery)
            );

            $this->sendFCM($delivery->token_fcm, "Tiene una orden asignado: ".$request->codeUrl);
            
            return response()->json(array('status' => 201, 'url' => route('admin.delivery')));
        }

        return response()->json(array('status' => 400));
    }

    public function saveAlarm(Request $request)
    {
        $paid = Paid::whereId($request->id)->first();
        $paid->alarm = Carbon::parse($request->dateAlarm)->format('Y-m-d H:m:s');
        $paid->save();
        return response()->json(array('status' => 201));
    }

    public function verifyAlarm(){
        $alarm = Paid::where("alarm", "<=", Carbon::now())->where("statusDelivery",0)->first();

        if($alarm){
            return response()->json(array('status' => 201));
        }else{
            return response()->json(array('status' => 401));
        }
    }

    public function showRate(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $user = $request->user();
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        
        if($request->all()){
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }

        $rates = Rate::where('user_id', Auth::guard('admin')->id())->orderBy('date', 'desc')
                     ->whereDate('created_at', ">=",$startDate)
                     ->whereDate('created_at', "<=",$endDate)
                     ->where('roleRate',0)->get();

        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.ratesPDF', compact('rates', 'startDate', 'endDate'));
            return $pdf->download('ctpaga_tasas.pdf');
        }elseif($request->statusFile == "EXCEL"){
            $today = Carbon::now()->format('Y-m-d');
            return Excel::download(new RatesExport($rates, null, null, $startDate, $endDate), 'ctpaga_tasas.xlsx');
        }
        
        $statusMenu = "rate";
        return view('admin.rate', compact('rates', 'statusMenu', 'startDate', 'endDate'));
    }

    public function newRate(Request $request)
    {
        
        $rate = app('App\Http\Controllers\Controller')->getPrice($request->rate);
        
        if(floatval($rate)>=1){
            Rate::create ([
                "user_id"   => Auth::guard('admin')->id(),
                "rate"      => $rate,
                "date"      => Carbon::now(),
                "roleRate"  => 0,
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'Create rate correctly',
            ]);
        }

        return response()->json([
            'status' => 401,
        ]);
    }


    public function authDelivery(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $deliveries = Delivery::orderBy("name")->get();
        $statusMenu="auth-delivery";

        return view('admin.authDelivery', compact('statusMenu','deliveries'));
    }

    public function changeStatusDelivery(Request $request)
    {
        $delivery = delivery::where("id", $request->id)->first();
        $delivery->status = $request->status;
        $delivery->save();

        if($request->status == 2)
            (new User)->forceFill([
                'email' => $delivery->email,
            ])->notify(
                new UserPaused($delivery, 1)
            );
        elseif($request->status == 3)
            (new User)->forceFill([
                'email' => $delivery->email,
            ])->notify(
                new UserRejected($delivery, 1)
            ); 

        return response()->json([
            'status' => 201
        ]);
    }

    public function settings(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $emailsAllPaid = "";
        $emailsAllDelivery = "";
        $statusPaidAll = "";

        $hoursInitial = "";
        $minInitial = "";
        $anteMeridiemInitial = "";
        $scheduleInitial = array('hours' => '', 'min' => '', 'anteMeridiem' => '');
        $scheduleFinal = array('hours' => '', 'min' => '', 'anteMeridiem' => '');

        
        $statusMenu="settings";

        $scheduleInitialGet = Settings::where("name", "Horario Inicial")->first(); 
        $scheduleFinalGet = Settings::where("name", "Horario Final")->first(); 
        $emailsAllPaid = Settings::where("name", "Email Transaccion")->first();
        $emailsAllDelivery = Settings::where("name", "Email Delivery")->first();
        $statusPaidAll = Settings::where("name", "Email Estado Pedido")->first();

        if($scheduleInitialGet && $scheduleFinalGet){
            $scheduleInitial = $this->getTime($scheduleInitialGet->value);
            $scheduleFinal = $this->getTime($scheduleFinalGet->value); 
        }

        return view('admin.settings', compact('statusMenu', 'emailsAllPaid', 'emailsAllDelivery', 'statusPaidAll', 'scheduleInitial', 'scheduleFinal'));
    }

    public function getTime($value)
    {
        $array = explode(":",$value);

        $hours = $array[0];

        $array = explode(" ",$array[1]);
        $min = $array[0];
        $anteMeridiem = $array[1];

        $result = array('hours' => $hours, 'min' => $min, 'anteMeridiem' => $anteMeridiem);

        return $result;
    }

    public function settingsSchedule(Request $request)
    {
        $shedule = $request->hoursInitial.":".$request->minInitial." ".$request->anteMeridiemInitial;
        $this->updateSettings("Horario Inicial", $shedule);

        $shedule = $request->hoursFinal.":".$request->minFinal." ".$request->anteMeridiemFinal;
        $this->updateSettings("Horario Final", $shedule);
        return redirect(route('admin.settings'));
    }

    public function settingsEmails(Request $request)
    {
        $this->updateSettings("Email Transaccion", $request->emailsAllPaid);
        $this->updateSettings("Email Delivery", $request->emailsAllDelivery);
        $this->updateSettings("Email Estado Pedido", $request->statusPaidAll);
        return redirect(route('admin.settings'));
    }

    public function updateSettings($name, $value)
    {
        Settings::updateOrCreate([
            'name' => $name,
        ],[
            'value' => $value,
        ]);
    }

    public function showDeliveryAjax(Request $request)
    {
        $deliveries = Delivery::where('statusAvailability', true)->get();

        if($deliveries)
            return response()->json(array('status'=>201, 'url' => route('admin.showDelivery', ['codeUrl' => $request->codeUrl]))); 
        else
            return response()->json(array('status'=>400)); 

    }

    public function showDelivery(Request $request)
    {
        $codeUrl = $request->codeUrl;
        $deliveries = Delivery::where('statusAvailability', true)->get();
        $paid = Paid::where('codeUrl',$codeUrl)->first();

        $commerce = Commerce::whereId($paid->commerce_id)->first();

        $statusMenu="delivery";
        return view('admin.showDelivery', compact('statusMenu', 'deliveries', 'commerce', 'codeUrl')); 

    }

    public function sendFCM($token,$message)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $token = $token;
        $serverKey = env('SERVER_KEY_FCM_DELIVERY');
        $title = "Ctpaga Aviso";
        $body = $message;
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

        curl_close($ch);
    }

    public function listUsers(Request $request)
    {
        
        $usersAll= User::with(['commerces' => function ($q) {
            $q->orderBy('id', 'asc');
        }])->get();

        $statusMenu = "users";
        return view('admin.listUsers', compact('statusMenu', 'usersAll'));
    }

    public function changeStatusUser(Request $request)
    {
        $user = User::whereId($request->id)->first();
        $user->status = $request->status;
        $user->save();

        if($request->status == 1)
            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new UserPaused($user, 0)
            );
        elseif($request->status == 2)
            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new UserRejected($user, 0)
            ); 

        $success = event(new NewNotification($request->id));

        return response()->json(array('status' => 201));
    }
}
