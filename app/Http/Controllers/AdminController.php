<?php

namespace App\Http\Controllers;

use DB;
use Session;
use PDF;
use App\User;
use App\Bank;
use App\Paid;
use App\Sale;
use App\Admin;
use App\Picture;
use App\Balance;
use App\Commerce;
use App\Deposits;
use App\Delivery;
use Carbon\Carbon;
use App\Notifications\PictureRemove;
use App\Events\SendCode;
use App\Events\StatusDelivery;
use App\Http\Controllers\Controller;
use App\Exports\DepositsExport;
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
        $totalShoppingStripe = 0;
        $totalShoppingSitef = 0;
        $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format('Y-m-d')."%")->get();
        foreach ($paidAll as $paid)
        {
            $totalShopping += 1;
            if($paid->nameCompanyPayments == "Stripe")
                $totalShoppingStripe += floatval($paid->total);
            
            if($paid->nameCompanyPayments == "E-sitef")
                $totalShoppingSitef += floatval($paid->total);
        }

        $statusMenu = "dashboard";
        $idCommerce = 0;
        return view('auth.dashboard',compact("totalShopping", "totalShoppingStripe", "totalShoppingSitef", "statusMenu", 'idCommerce'));
    }

    public function dataGraphic(Request $request)
    {
        $month = Carbon::now()->format('m');
        $years = Carbon::now()->format('Y');
        $listDay= array();
        $count=1;
        for ($i = 0; $i < 7; $i++) {
            $totalShop = 0;
            $totalShopStripe = 0;
            $totalShopSitef = 0;
            if(Auth::guard('admin')->check())
                $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(6-$i)->format('d'))."%")->get();
            else
                $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(6-$i)->format('d'))."%")
                                ->where('user_id',Auth::guard('web')->id())
                                ->where('commerce_id',$request->commerce_id)
                                ->get();
            
            foreach ($paidAll as $paid)
            {
                $totalShop += 1;
                if($paid->nameCompanyPayments == "Stripe")
                    $totalShopStripe += floatval($paid->total);
                
                if($paid->nameCompanyPayments == "E-sitef")
                    $totalShopSitef += floatval($paid->total);
            }
            $listDay[$i]['dia'] = Carbon::now()->subDay(6-$i)->format('d');
            $listDay[$i]['totalSales'] = $totalShop;
            $listDay[$i]['totalStripe'] = $totalShopStripe;
            $listDay[$i]['totalSitef'] = $totalShopSitef;
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

        $deposits = DB::table('deposits')
                ->join('commerces', 'commerces.id', '=', 'deposits.commerce_id')
                ->where('deposits.total', '>=', 1)
                ->select('deposits.id', 'deposits.user_id', 'deposits.commerce_id', 'deposits.coin', 'deposits.total', 'deposits.status',
                'commerces.name')
                ->orderBy('name', 'asc');
        
        if($request->all()){
            $selectCoin= $request->selectCoin;
            $deposits = $deposits->where('deposits.coin', $selectCoin);
        }
            
        $deposits = $deposits->get();
        $statusMenu = "balance";

        return view('admin.balance', compact('deposits',"statusMenu",'selectCoin'));
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


    public function show($id)
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
        return view('admin.show', compact('commerce', 'user', 'pictures', 'selfie','statusMenu'));
    }

    public function removePicture(Request $request)
    {
        $picture = Picture::where('id', $request->id)->first();
        $urlPrevius = substr($picture->url,8);

        $user = User::where('id', $picture->user_id)->first();

        \Storage::disk('public')->delete($urlPrevius);
        $picture->delete();

        $user->notify(
            new PictureRemove($request->reason)
        );

        return response()->json([
            'status' => 201
        ]);
    }

    public function saveDeposits(Request $request)
    {
        foreach (Session::get('dataSelectID') as $id)
        {
            $deposits = Deposits::where('id', $id['id'])->first(); 
            $deposits->status = 3;
            $deposits->numRef = $request->numRef;
            $deposits->save();
        }

        Session::forget('dataSelectID');

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

        $commerces = Commerce::all()
                    ->whereNotNull("name")
                    ->whereNotNull("rif")
                    ->whereNotNull("address")
                    ->whereNotNull("phone")
                    ->whereNotNull("userUrl")
                    ->sortBy("name");

        $statusMenu = "commerces";
        return view('admin.commerces', compact('commerces','statusMenu'));
    }

    public function commercesShow($id)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.dashboard'));
        }

        $commerce = Commerce::where('id', $id)->first();
        $user = User::where('id',$commerce->user_id)->first();
        $transactions = Paid::where('commerce_id', $id)->orderBy('date', 'asc')->get();

        $statusMenu = "commerces";
        return view('admin.commerceShow', compact('commerce', 'user', 'transactions', 'statusMenu'));
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
                        ->orderBy('paids.id', 'desc')
                        ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.coin', 'paids.total',
                            'paids.date', 'paids.nameCompanyPayments');
        }else{
            $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->orderBy('paids.id', 'desc')
                        ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.coin', 'paids.total',
                            'paids.date', 'paids.nameCompanyPayments');
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
            $transactions->where('commerces.id', $request->idCommerce); 

        if(!empty($request->searchNameCompany))
            $transactions->where('commerces.name', 'ilike', "%" . $request->searchNameCompany . "%" );
        
        if(!empty($request->searchNameClient))
            $transactions->where('paids.nameClient', 'ilike', "%" . $request->searchNameClient . "%" );

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda")
            $transactions->where('paids.coin', $request->selectCoin);
        
            if(!empty($request->selectPayment) && $request->selectPayment != "Selecionar Tipo de Pago")
            $transactions->where('paids.nameCompanyPayments',  'ilike', "%" . $request->selectPayment . "%" );

        $transactions->where('paids.date', ">=",$startDate)
            ->where('paids.date', "<=",$endDate);

        $transactions = $transactions->get();

        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.transactionsPDF', compact('transactions', 'today', 'idCommerce', 'companyName'));
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
        if($request->selectId == ""){
            foreach (Session::get('dataSelectID') as $id)
            {
                $deposits = Deposits::where('id', $id['id'])->first(); 
                $deposits->status = 3;
                $deposits->numRef = $request->numRef;
                $deposits->save();
            }
        }else{
            foreach ($request->selectId as $id)
            {
                $deposits = Deposits::where('id', $id)->first();
                $deposits->status = $request->status;
                $deposits->numRef = "";
                $deposits->save();
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

            Session::put('dataSelectID', $dataId);

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
            Session::put('dataSelectID', $dataId);          

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
            $deposits->where('commerces.name', 'ilike', "%" . $request->searchNameCompany . "%" );
        
        if(!empty($request->numRef))
            $deposits->where('deposits.numRef', 'ilike', "%" . $request->numRef . "%" );

        if(!empty($request->searchNameClient))
            $deposits->where('deposits.nameClient', 'ilike', "%" . $request->searchNameClient . "%" );

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda")
            $deposits->where('deposits.coin', $request->selectCoin);
        
        $deposits->where('deposits.date', ">=",$startDate)
                        ->where('deposits.date', "<=",$endDate);

        $deposits = $deposits->get();

        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.depositsPDF', compact('deposits', 'today'));
            return $pdf->download('ctpaga_depositos.pdf');
        }elseif($request->statusFile == "EXCEL"){
            $today = Carbon::now()->format('Y-m-d');
            return Excel::download(new DepositsExport($deposits, $today), 'ctpaga_depositos.xlsx');
        }

        $statusMenu = "balance";
        return view('admin.reportPayment', compact('deposits', 'searchNameCompany', 'selectCoin', 'selectPayment', 'startDate', 'endDate', 'numRef', 'statusMenu'));
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
        $searchNameClient="";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $idCommerce=0;
        $companyName = "";
        $code="";

        if($request->id){
            $idCommerce=intVal($request->id);
            
            $commerce = Commerce::whereId($idCommerce)->first();
            $companyName = $commerce->name;

            $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->where('paids.commerce_id', 'like', "%".$request->id. "%" )
                        ->orderBy('paids.id', 'desc')
                        ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.selectShipping', 'paids.total',
                            'paids.date', 'paids.nameCompanyPayments', 'paids.idDelivery', 'paids.alarm');
        }else{
            $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->orderBy('paids.id', 'desc')
                        ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.selectShipping', 'paids.total',
                            'paids.date', 'paids.nameCompanyPayments', 'paids.idDelivery', 'paids.codeUrl', 'paids.alarm');
        }

        if($request->all()){
            $idCommerce = $idCommerce == 0? $request->idCommerce : $idCommerce;
            $searchNameCompany=$request->searchNameCompany;
            $searchNameClient=$request->searchNameClient;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
            $code = $request->code;
        }


        if(!empty($request->idCommerce))
            $transactions->where('commerces.id', $request->idCommerce); 

        if(!empty($request->searchNameCompany))
            $transactions->where('commerces.name', 'ilike', "%" . $request->searchNameCompany . "%" );
        
        if(!empty($request->searchNameClient))
            $transactions->where('paids.nameClient', 'ilike', "%" . $request->searchNameClient . "%" );

        if(!empty($request->code))
            $transactions->where('paids.codeurl', 'ilike', "%" . $request->code . "%" );

        $transactions->where('paids.date', ">=",$startDate)
            ->where('paids.date', "<=",$endDate);

        $transactions = $transactions->get();

        $countDeliveries = Delivery::where("status", true)->get()->count();

        $statusMenu = "delivery";
        return view('admin.delivery', compact('transactions', 'searchNameCompany', 'searchNameClient', 'startDate', 'endDate', 'statusMenu','idCommerce', 'companyName', 'code', 'countDeliveries'));
    }

    public function deliverySendCode(Request $request)
    {   
        $paid = Paid::where("codeUrl",$request->codeUrl)->first();
        $delivery = delivery::where('status',true)->orderBy('updated_at', 'desc')->first();
        if($delivery){
            $notification['delivery_id'] = $delivery->id;
            $notification['codeUrl'] = $request->codeUrl;

            $paid->idDelivery = $delivery->id;
            $paid->save();

            $delivery->status = false;
            $delivery->save();

            $success = event(new SendCode($notification));
            return response()->json(array('status' => 201));
        }else{
            return response()->json(array('status' => 401));
        }
    }

}
