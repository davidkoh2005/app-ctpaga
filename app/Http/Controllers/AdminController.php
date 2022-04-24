<?php

namespace App\Http\Controllers;

use DB;
use Session;
use PDF;
use AWS;
use App\Cash;
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
use App\Document;
use App\Product;
use App\Service;
use App\Settings;
use App\SettingsBank;
use App\HistoryCash;
use App\DeliveryCost;
use App\PaymentsBs;
use App\PaymentsZelle;
use App\PaymentsBitcoin;
use App\Cryptocurrency;
use App\CryptocurrenciesDetail;
use Carbon\Carbon;
use App\Notifications\UserPaused;
use App\Notifications\UserRejected;
use App\Notifications\PictureRemove;
use App\Notifications\ConfirmBank;
use App\Notifications\SendDeposits;
use App\Notifications\SendDepositsProcess;
use App\Notifications\PostPurchase;
use App\Notifications\PaymentConfirm;
use App\Notifications\PaymentCancel;
use App\Notifications\NotificationDelivery;
use App\Notifications\NotificationCommerce;
use App\Notifications\PictureDocumentRemoveDelivery;
use App\Notifications\DeliveryProductClientInitial;
use App\Notifications\DeliveryProductCommerceInitial;
use App\Events\SendCode;
use App\Events\StatusDelivery;
use App\Events\NewNotification;
use App\Http\Controllers\Controller;
use App\Exports\DepositsExport;
use App\Exports\RatesExport;
use App\Exports\TransactionsExport;
use App\Exports\HistoryCashesExport;
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
                else if($paid->statusPayment == 1)
                    $totalPendingUSD += floatval($paid->total); 
            else
                if($paid->statusPayment == 2)
                    $totalShoppingBS += floatval($paid->total);
                else if($paid->statusPayment == 1)
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
                            ->where('description', '<>','Profile')
                            ->where('type',0)->get();

        $selfie = Picture::where('user_id', $user->id)
                        ->where('commerce_id', '=', null)
                        ->where('type',0)->first();

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
                'type'          => 1,
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
            'type'          => 1,
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
            $startDate=Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate=Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        }


        if(!empty($request->idCommerce))
            $transactions = $transactions->where('commerces.id', $request->idCommerce); 

        if(!empty($request->searchNameCompany)){
            $searchNameCompany = $request->searchNameCompany;
            $transactions = $transactions->where('commerces.name', 'like', '%'.$request->searchNameCompany.'%' );
        }
        
        if(!empty($request->searchNameClient)){
            $searchNameClient = $request->searchNameClient;
            $transactions = $transactions->where('paids.nameClient', 'like', '%'.$request->searchNameClient.'%');
        }

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda"){
            $selectCoin = $request->selectCoin;
            $transactions = $transactions->where('paids.coin', $request->selectCoin);
        }
        
        if(!empty($request->selectPayment) && $request->selectPayment != "Selecionar Tipo de Pago"){
            $selectPayment = $request->selectPayment;
            $transactions = $transactions->where('paids.nameCompanyPayments', $request->selectPayment);
        }

        $transactions = $transactions->whereDate('paids.created_at', ">=",$startDate)
                    ->whereDate('paids.created_at', "<=",$endDate)
                    ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.coin', 'paids.total',
                                    'paids.date', 'paids.nameCompanyPayments', 'paids.statusPayment', 'paids.codeUrl')
                    ->get();

        
        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.transactionsPDF', compact('transactions', 'today', 'idCommerce', 'companyName'))->setPaper('a4', 'landscape');
            return $pdf->download(env('APP_NAME').'_transacciones.pdf');
        }elseif($request->statusFile == "EXCEL"){
            $today = Carbon::now()->format('Y-m-d');
            return Excel::download(new TransactionsExport($transactions, $today, $idCommerce, $companyName), env('APP_NAME').'_transacciones.xlsx');
        }

        $statusMenu = "transactions";
        return view('admin.transactions', compact('transactions', 'searchNameCompany', 'searchNameClient', 'selectCoin', 'selectPayment', 'startDate', 'endDate', 'statusMenu','idCommerce', 'companyName'));
    }

    public function transactionsShow(Request $request)
    {
        $transaction = Paid::where('id', $request->id)->first();
        $delivery = Delivery::whereId($transaction->idDelivery)->first();
        $sales = Sale::where('codeUrl', $transaction->codeUrl)->orderBy('name', 'asc')->get();
        $rate = $sales[0]->rate;
        $coinClient = $sales[0]->coinClient;
        $returnHTML=view('admin.dataProductService', compact('transaction','sales', 'rate', 'coinClient', 'delivery'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function transactionsPayment(Request $request)
    {
        $payment =  $request->payment;
        if($payment == 'Transferencia' || $payment == 'Pago Móvil' ){
            $transactions = PaymentsBs::where('paid_id', $request->id)->get();
            $returnHTML=view('admin.showTransactions', compact('transactions', 'payment'))->render();
        }
        elseif($payment == 'Zelle'){
            $transaction = PaymentsZelle::where('paid_id', $request->id)->first();
            $returnHTML=view('admin.showTransactions', compact('transaction', 'payment'))->render();
        }
        elseif($payment == 'Bitcoin'){
            $transaction = Paid::join('payments_bitcoins', 'payments_bitcoins.paid_id', '=', 'paids.id')
                                ->where('paid_id', $request->id)->first();
            $returnHTML=view('admin.showTransactions', compact('transaction', 'payment'))->render();
        }

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

            if(intval($request->status) == 2){
                $user = User::whereId($deposits->user_id)->first();

                (new User)->forceFill([
                    'email' => $user->email,
                ])->notify(
                    new SendDepositsProcess($user, $deposits)
                ); 
            }
        }
        
        return response()->json(array('status' => 201));
    }

    public function changeStatusPayment(Request $request)
    {
        foreach ($request->selectId as $id)
        {
            $transaction = Paid::where('id', $id)->first();
            $transaction->statusPayment = $request->status;

            if($request->status == 2){
                $transaction->statusDelivery = 1;
                $transaction->statusPayment = 2;
                $transaction->timeDelivery = Carbon::now()->addMinutes(10);
            }

            $transaction->save();

            $commerce = Commerce::where('id',$transaction->commerce_id)->first();
            $user = User::where('id',$commerce->user_id)->first();

            if($request->status == 2){
                /* $sales = Sale::where("codeUrl", $transaction->codeUrl)->get();
                $message="";
                foreach ($sales as $sale)
                {
                    if($sale->type == 0 && $sale->productService_id != 0){
                        $product = Product::where('id',$sale->productService_id)->first();
                        
                        if ($product->postPurchase)
                            $message .= "- ".$product->postPurchase."\n";

                    }

                    if($sale->type == 1 && $sale->productService_id != 0){
                        $service = Service::where('id',$sale->productService_id)->first();
                        
                        if($service->postPurchase)
                            $message .= "- ".$service->postPurchase."\n";
                    }

                } */

                (new User)->forceFill([
                    'email' => $transaction->email,
                ])->notify(
                    new PaymentConfirm($transaction->nameClient, $transaction->codeUrl)
                );

                (new User)->forceFill([
                    'email' => $user->email,
                ])->notify(
                    new NotificationCommerce($commerce, $transaction->codeUrl, 0)
                );
            }elseif($request->status == 0){
                (new User)->forceFill([
                    'email' => $transaction->email,
                ])->notify(
                    new PaymentCancel($transaction->nameClient, $transaction->codeUrl)
                );

                (new User)->forceFill([
                    'email' => $user->email,
                ])->notify(
                    new NotificationCommerce($commerce, $transaction->codeUrl, 2)
                );
            }
        }
        
        return response()->json(array('status' => 201));
    }

    public function changeStatusPayDelivery(Request $request)
    {
        foreach ($request->selectId as $id)
        {
            $transaction = Paid::where('id', $id)->first();
            $transaction->statusPayDelivery = $request->status;
            $transaction->datePayDelivery = Carbon::now();
            $transaction->save();

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
            $startDate=Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate=Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
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
            return $pdf->download(env('APP_NAME').'_depositos.pdf');
        }elseif($request->statusFile == "EXCEL"){
            return Excel::download(new DepositsExport($deposits, $today), env('APP_NAME').'_depositos.xlsx');
        }

        $statusMenu = "balance";
        return view('admin.reportPayment', compact('deposits', 'searchNameCompany', 'selectCoin', 'selectPayment', 'startDate', 'endDate', 'numRef', 'statusMenu', 'today'));
    }

    public function downloadTxt(Request $request)
    {
        if(file_exists(env('APP_NAME').".txt"))
            unlink(env('APP_NAME').".txt");
        
        $file=fopen(env('APP_NAME').".txt","a") or die("Problemas");
        fputs($file,"primera linea 1");
        fputs($file,"\n");
        fputs($file,"segunda linea 2");
        fputs($file,"\n");
        fputs($file,"tercera linea 3");
        fclose($file); 

        $url = 'http://'.$_SERVER['HTTP_HOST'].'/'.env('APP_NAME').'.txt';
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
        $searchStatus = 0;

        $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->orderBy('paids.statusDelivery', 'asc')
                        ->orderBy('paids.date', 'asc')
                        ->orderBy('paids.alarm', 'desc')
                        ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.selectShipping', 'paids.total', 'paids.statusShipping',
                            'paids.date', 'paids.nameCompanyPayments', 'paids.idDelivery', 'paids.codeUrl', 'paids.alarm', 'paids.statusDelivery', 'paids.timeDelivery')
                        ->whereNotNull('paids.selectShipping')
                        ->where('paids.statusDelivery', '!=', 0)
                        ->where('paids.statusPayment',2)
                        ->where('paids.statusShipping','!=',2);

        if($request->all()){
            $searchStatus = $request->searchStatus;

            if($request->searchStatus == 1)
                $transactions->where('paids.statusDelivery',0);
            elseif($request->searchStatus == 2)
                $transactions->where('paids.statusDelivery',1)->whereNull('idDelivery')->where('paids.timeDelivery','>=',Carbon::now());
            elseif($request->searchStatus == 3)
                $transactions->where('paids.statusDelivery',1)->whereNull('idDelivery')->where('paids.timeDelivery','<=',Carbon::now());
            elseif($request->searchStatus == 4)
                $transactions->where('paids.statusShipping',0)->whereNotNull('idDelivery');
            elseif($request->searchStatus == 5)
                $transactions->where('paids.statusShipping',1)->whereNotNull('idDelivery');

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
        return view('admin.delivery', compact('transactions', 'searchNameCompany', 'searchNameClient', 'startDate', 'endDate', 'statusMenu','idCommerce', 'companyName', 'searchCodeUrl', 'searchStatus'));
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
        $delivery->statusAvailability = false;

        $listCodeUrl = json_decode($delivery->codeUrlPaid);
        array_push($listCodeUrl,$request->codeUrl);
        $delivery->codeUrlPaid = json_encode($listCodeUrl);
        $delivery->save();

        $paid = Paid::where("codeUrl",$request->codeUrl)->first();
        $paid->statusDelivery = 2;
        $paid->idDelivery = $request->idDelivery;
        $paid->save();

        $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
        $commerce = Commerce::whereId($paid->commerce_id)->first();
        $userCommerce = User::whereId($paid->user_id)->first();

        $phone = '+'.app('App\Http\Controllers\Controller')->validateNum($paid->numberShipping);
        $phoneCommerce = '+'.app('App\Http\Controllers\Controller')->validateNum($commerce->phone);
        $fecha = Carbon::now()->format("d/m/Y");
        $message = env('APP_NAME')." Delivery le informa que ha realizado un pedido con el Nro ".$paid->codeUrl." con fecha de ".$fecha.", el cual será despachado en aproximadamente 1 hora. Ver informacion de delivery: ".$urlDelivery."";
        
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

        $sms = AWS::createClient('sns');
        $sms->publish([
            'Message' => $message,
            'PhoneNumber' => $phoneCommerce,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType'  => [
                    'DataType'    => 'String',
                    'StringValue' => 'Transactional',
                ]
            ],
        ]); 

        (new User)->forceFill([
            'email' => $paid->email,
        ])->notify(
            new DeliveryProductClientInitial($commerce, $paid, $sales, $delivery)
        );  

        (new User)->forceFill([
            'email' => $userCommerce->email,
        ])->notify(
            new DeliveryProductCommerceInitial($commerce, $paid, $sales, $delivery)
        );

        (new User)->forceFill([
            'email' => $delivery->email,
        ])->notify(
            new NotificationDelivery("fue asignado el siguiente orden: ".$request->codeUrl, $delivery)
        );

        $this->sendFCM($delivery->token_fcm, "Tiene una orden asignado: ".$request->codeUrl);
        
        return response()->json(array('status' => 201, 'url' => route('admin.delivery')));

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
            $startDate=Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate=Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        }


        $rates = Rate::where('user_id', Auth::guard('admin')->id())->orderBy('date', 'desc')
                     ->whereDate('created_at', ">=",$startDate)
                     ->whereDate('created_at', "<=",$endDate)
                     ->where('roleRate',0)->get();
        

        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.ratesPDF', compact('rates', 'startDate', 'endDate'));
            return $pdf->download(env('APP_NAME').'_tasas.pdf');
        }elseif($request->statusFile == "EXCEL"){
            $today = Carbon::now()->format('Y-m-d');
            return Excel::download(new RatesExport($rates, null, null, $startDate, $endDate), env('APP_NAME').'_tasas.xlsx');
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
        $delivery->statusAvailability = 0;
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

        $scheduleInitialGet = Settings::where("name", "Horario Inicial")->first(); 
        $scheduleFinalGet = Settings::where("name", "Horario Final")->first(); 
        $emailsAllPaid = Settings::where("name", "Email Transaccion")->first();
        $emailsAllDelivery = Settings::where("name", "Email Delivery")->first();
        $statusPaidAll = Settings::where("name", "Email Estado Pedido")->first();

        if($scheduleInitialGet && $scheduleFinalGet){
            $scheduleInitial = $this->getTime($scheduleInitialGet->value);
            $scheduleFinal = $this->getTime($scheduleFinalGet->value); 
        }

        $transfers = SettingsBank::where('type',0)->get();
        $mobilePayments = SettingsBank::where('type',1)->get();

        $data = file_get_contents("json/listBanks.json");
        $listBanks = json_decode($data, true);

        $zelle = Settings::where('name','Zelle')->first();

        $listCryptocurrencies = Cryptocurrency::whereNotNull('address')->get();
        
        $statusMenu="settings";

        return view('admin.settings', compact('statusMenu', 'emailsAllPaid', 'emailsAllDelivery', 'statusPaidAll', 'scheduleInitial', 'scheduleFinal', 'listBanks', 'transfers', 'mobilePayments', 'zelle', 'listCryptocurrencies'));
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

    public function settingsCosts(Request $request)
    {
        $count = 0;
        foreach($request->listMunicipalities as $item)
        {
            $cost = app('App\Http\Controllers\Controller')->getPrice($request->listCost[$count]);
            $deliveryCost = DeliveryCost::firstOrNew([
                'state'             => $request->selectState,
                'municipalities'    => $item,
            ]);

            $deliveryCost->cost = floatval($cost);
            $deliveryCost->save();

            $count++;
        }
        return redirect(route('admin.settings'));
    }

    public function listCost()
    {
        $deliveryCosts = DeliveryCost::all();
        return response()->json(array('list'=>$deliveryCosts));
    }

    public function SettingsTransfers(Request $request)
    {
        if(!empty($request->allTransfer) && count($request->allTransfers)> 0){
            $exceptArray = array_merge(array_diff($request->allTransfers,$request->idTransfers), array_diff($request->idTransfers,$request->allTransfers));
            foreach ($exceptArray as $id) {
                SettingsBank::whereId($id)->delete();
            }
        }

        for ($i = 0; $i < count($request->bank); $i++) {
            if(!empty($request->idTransfers[$i])){
                $setttingBank = SettingsBank::whereId($request->idTransfers[$i])->first();
                $setttingBank->bank = $request->bank[$i];
                $setttingBank->idCard = $request->typeCard[$i].'-'.$request->idCard[$i];
                $setttingBank->accountName = $request->accountName[$i];
                $setttingBank->accountNumber = $request->accountNumber[$i];
                $setttingBank->accountType = $request->accountType[$i];
                $setttingBank->save();
            }else{
                $setttingBank = SettingsBank::create([
                    "type"              => 0,
                    "bank"              => $request->bank[$i],
                    "idCard"            => $request->typeCard[$i].'-'.$request->idCard[$i],
                    "accountName"       => $request->accountName[$i],
                    "accountNumber"     => $request->accountNumber[$i],
                    "accountType"       => $request->accountType[$i],
                ]);
            }
        }
        return redirect(route('admin.settings'));
    }

    public function SettingsMobile(Request $request)
    {
        if(!empty($request->allMobilePayments) && count($request->allMobilePayments)> 0){
            $exceptArray = array_merge(array_diff($request->allMobilePayments,$request->idMobile), array_diff($request->idMobile,$request->allMobilePayments));
            foreach ($exceptArray as $id) {
                SettingsBank::whereId($id)->delete();
            }
        }

        for ($i = 0; $i < count($request->bank); $i++) {
            if(!empty($request->idMobile[$i])){
                $setttingBank = SettingsBank::whereId($request->idMobile[$i])->first();
                $setttingBank->bank = $request->bank[$i];
                $setttingBank->idCard = $request->idCard[$i];
                $setttingBank->phone = $request->phone[$i];
                $setttingBank->save();
            }else{
                $setttingBank = SettingsBank::create([
                    "type"      => 1,
                    "bank"      => $request->bank[$i],
                    "idCard"    => $request->idCard[$i],
                    "phone"     => $request->phone[$i],
                ]);
            }
        }
        return redirect(route('admin.settings'));
    }

    public function SettingsZelle(Request $request)
    {
        $zelle = Settings::firstOrNew([
            'name'       => 'Zelle',
        ]);

        $zelle->value = $request->email;
        $zelle->save();

        return redirect(route('admin.settings'));
    }

    public function settingsCryptocurrencies(Request $request)
    {
        $cryptocurrency = Cryptocurrency::where('name',$request->crypto)->first();
        $cryptocurrency->address = $request->address;
        $cryptocurrency->publish = $request->switchPublish == null? 0 : 1;
        $cryptocurrency->save();

        if(!empty($request->allDetailsCryptocurrency) && count($request->allDetailsCryptocurrency)> 0 && !empty($request->idDetailsCryptocurrency) ){
            $exceptArray = array_merge(array_diff($request->allDetailsCryptocurrency,$request->idDetailsCryptocurrency), array_diff($request->idDetailsCryptocurrency,$request->allDetailsCryptocurrency));
            foreach ($exceptArray as $id) {
                CryptocurrenciesDetail::whereId($id)->delete();
            }
        }elseif(!empty($request->allDetailsCryptocurrency) && count($request->allDetailsCryptocurrency)> 0 && empty($request->idDetailsCryptocurrency) ){
            foreach ($request->allDetailsCryptocurrency as $id) {
                CryptocurrenciesDetail::whereId($id)->delete();
            }
        }

        if(!empty($request->detailsCryptocurrencyKey))
            for ($i = 0; $i < count($request->detailsCryptocurrencyKey); $i++) {
                if(!empty($request->idDetailsCryptocurrency[$i])){
                    $detailsCryptocurrency = CryptocurrenciesDetail::whereId($request->idDetailsCryptocurrency[$i])->first();
                    $detailsCryptocurrency->key = $request->detailsCryptocurrencyKey[$i];
                    $detailsCryptocurrency->value = $request->detailsCryptocurrencyValue[$i];
                    $detailsCryptocurrency->save();
                }else{
                    $detailsCryptocurrency = CryptocurrenciesDetail::create([
                        "cryptocurrencies_id"   => $cryptocurrency->id,
                        "key"                   => $request->detailsCryptocurrencyKey[$i],
                        "value"                 => $request->detailsCryptocurrencyValue[$i],
                    ]);
                }
            }
            
        return redirect(route('admin.settings'));
    }

    public function showWallet(Request $request)
    {
        $selectCryptoCurrency = Cryptocurrency::whereId($request->id)->first();
        $listCryptocurrencies = Cryptocurrency::orderBy('name', 'ASC')->get();
        $detailsCryptocurrencies = CryptocurrenciesDetail::where('cryptocurrencies_id', $request->id)->get();

        $returnHTML=view('admin.modal.wallet', compact('listCryptocurrencies', 'selectCryptoCurrency', 'detailsCryptocurrencies'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function showDelivery(Request $request)
    {
        $codeUrl = $request->codeUrl;
        $deliveries = Delivery::orderBy('statusAvailability','DESC')->get();
        $paid = Paid::where('codeUrl',$codeUrl)->first();

        $commerce = Commerce::whereId($paid->commerce_id)->first();

        $statusMenu="delivery";
        return view('admin.showDelivery', compact('statusMenu', 'deliveries', 'commerce', 'codeUrl')); 

    }

    public function sendFCM($token,$message)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = env('SERVER_KEY_FCM_DELIVERY');
        $title = "Aviso Importante";
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

    public function deliveryShow($id){
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $delivery = Delivery::whereId($id)->first();

        $selfie = Picture::where('user_id', $delivery->id)
                        ->where('commerce_id', '=', null)
                        ->where('type',1)->first();
                        
        $documents = Document::where('delivery_id', $delivery->id)
                            ->orderBy('type', 'DESC')->get();
                        
        $statusMenu="auth-delivery";
        return view('admin.deliveryShow', compact('delivery', 'selfie', 'documents', 'statusMenu'));
    }

    public function removePerfil(Request $request)
    {
        $picture = Picture::whereId($request->id)->first();
        $urlPrevius = substr($picture->url,8);

        $delivery = Delivery::whereId($picture->user_id)->first();
        $delivery->status = 0;
        $delivery->statusAvailability = 0;
        $delivery->save();

        \Storage::disk('public')->delete($urlPrevius);
        $picture->delete();

        $sentEmail = Email::firstOrNew([
            'user_id'       => $delivery->id,
            'commerce_id'   => 0,
            'type'          => 2,
        ]);

        if(!$sentEmail->date || Carbon::parse($sentEmail->date)->format('Y-m-d') != Carbon::now()->format('Y-m-d')){
            (new User)->forceFill([
                'email' => $delivery->email,
            ])->notify(
                new PictureDocumentRemoveDelivery($delivery)
            ); 

            $sentEmail->date = Carbon::now();

        }

        $sentEmail->save();

        return response()->json([
            'status' => 201
        ]);
    }

    public function removeDocumentDelivery(Request $request)
    {
        $document = Document::whereId($request->id)->first();
        $urlPrevius = substr($document->url,8);
        
        $delivery = Delivery::whereId($document->delivery_id)->first();
        $delivery->status = 0;
        $delivery->statusAvailability = 0;
        $delivery->save();

        \Storage::disk('public')->delete($urlPrevius);
        $document->delete();

        $sentEmail = Email::firstOrNew([
            'user_id'       => $delivery->id,
            'commerce_id'   => 0,
            'type'          => 2,
        ]);

        if(!$sentEmail->date || Carbon::parse($sentEmail->date)->format('Y-m-d') != Carbon::now()->format('Y-m-d')){
            (new User)->forceFill([
                'email' => $delivery->email,
            ])->notify(
                new PictureDocumentRemoveDelivery($delivery)
            ); 

            $sentEmail->date = Carbon::now();

        }

        $sentEmail->save();

        return response()->json([
            'status' => 201
        ]);
    }

    public function showBalanceDelivery(Request $request)
    {
        $balance = 0.00;
        $cashes = Cash::join('paids', 'paids.id', '=', 'cashes.paid_id')
                        ->where('cashes.delivery_id',$request->id)
                        ->where('cashes.status',0)
                        ->select('paids.total', 'paids.codeUrl')
                        ->get();
        
        $delivery = Delivery::whereId($request->id)->first();

        foreach($cashes as $cash){
            $balance += floatval($cash->total);
        }

        $returnHTML=view('admin.showBalanceDelivery', compact('cashes', 'balance', 'delivery'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function updatePaymentDelivery(Request $request)
    {
        $total = 0;
        $cashes = Cash::join('paids', 'paids.id', '=', 'cashes.paid_id')
                        ->join('deliveries', 'deliveries.id', '=', 'cashes.delivery_id')
                        ->where('cashes.delivery_id',$request->id)
                        ->where('cashes.status',0)
                        ->select('paids.total', 'paids.codeUrl', 'cashes.delivery_id')
                        ->get();

        foreach ($cashes as $cash){
            $total += floatval($cash->total);
        }

        HistoryCash::create([
            'delivery_id'   => $request->id,
            'total'         => $total,
            'date'          => Carbon::now(),
        ]);

        Cash::where('delivery_id', $request->id)
            ->where('status',0)->update([
            "status"  => 1,
        ]);

        $balance = 0.00;
        $cashes = Cash::join('paids', 'paids.id', '=', 'cashes.paid_id')
                        ->join('deliveries', 'deliveries.id', '=', 'cashes.delivery_id')
                        ->where('cashes.delivery_id',$request->id)
                        ->where('cashes.status',0)
                        ->select('paids.total', 'paids.codeUrl', 'cashes.delivery_id')
                        ->get();

        $delivery = Delivery::whereId($request->id)->first();

        foreach($cashes as $cash){
            $balance += floatval($cash->total);
        }

        $returnHTML=view('admin.showBalanceDelivery', compact('cashes', 'balance', 'delivery'))->render();
        return response()->json(array('status' => 201, 'html'=>$returnHTML));
    }

    public function historyCashes(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $idDelivery = 0;
        $searchNameDelivery="";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $histories = HistoryCash::join('deliveries', 'deliveries.id', '=', 'history_cashes.delivery_id');

        if($request->idDelivery){
            $idDelivery = $request->idDelivery;
            $histories = $histories->where('history_cashes.delivery_id', $idDelivery);
        }

        if(!empty($request->searchNameDelivery)){
            $searchNameDelivery=$request->searchNameDelivery;
            $transactions->where('deliveries.name', 'like', "%" . $request->searchNameDelivery   . "%" );
        }

        if(!empty($request->startDate) && !empty($request->startDate)){
            $startDate=Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate=Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d'); 
        }

        $histories = $histories->whereDate('history_cashes.created_at', ">=",$startDate)
                    ->whereDate('history_cashes.created_at', "<=",$endDate)
                    ->select('history_cashes.id', 'deliveries.name', 'history_cashes.total', 'history_cashes.date')
                    ->get();
 
        if($request->statusFile == "PDF"){
            $pdf = \PDF::loadView('report.historyCashesPDF', compact('histories', 'searchNameDelivery', 'startDate', 'endDate'));
            return $pdf->download(env('APP_NAME').'_historial.pdf');
        }elseif($request->statusFile == "EXCEL"){
            return Excel::download(new HistoryCashesExport($histories, $startDate, $endDate), env('APP_NAME').'_historial.xlsx');
        }

        if($request->idDelivery && count($histories) >0){
            $searchNameDelivery = $histories[0]->name;   
        }
        
        $statusMenu = "historyCashes";
        return view('admin.historyCashes', compact('histories', 'searchNameDelivery', 'startDate', 'endDate', 'statusMenu'));
    }

    public function historyPayDelivery(Request $request){
        $searchName="";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $searchStatus = 0;

        $orders = Paid::join('deliveries', 'deliveries.id', '=', 'paids.idDelivery')->where('statusDelivery',2);

        if(!empty($request->searchName)){
            $searchName = $request->searchName;
            $orders->where('deliveries.name', 'like', "%" . $request->searchName . "%" );
        }

        if(!empty($request->searchStatus)){
            $searchStatus = $request->searchStatus;
            $orders = $orders->where('paids.statusPayDelivery', $searchStatus);
        }

        if(!empty($request->startDate) && !empty($request->endDate)){
            $startDate = Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        }

        $orders = $orders->whereDate('paids.created_at', ">=",$startDate)
                    ->whereDate('paids.created_at', "<=",$endDate)
                    ->select('paids.id', 'paids.date', 'paids.codeUrl', 'paids.state', 'paids.municipalities', 'paids.statusPayDelivery', 'paids.datePayDelivery', 'deliveries.name')
                    ->get();

        $statusMenu = "historyPayDelivery";
        return view('admin.historyPayDelivery', compact('orders', 'searchName', 'searchStatus', 'startDate', 'endDate', 'statusMenu'));
    }

}
