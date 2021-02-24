<?php

namespace App\Http\Controllers;

use Session;
use PDF;
use App\User;
use App\Paid;
use App\Rate;
use App\Commerce;
use App\Deposits;
use App\Picture;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Exports\DepositsCommerceExport;
use App\Exports\RatesExport;
use App\Exports\TransactionsCommerceExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Maatwebsite\Excel\Facades\Excel;

class CommerceController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('commerce.dashboard'));
        }

        Session::flash('message', "El correo o la contraseña es incorrecta!");
        return Redirect::back();
    }

    public function dashboard(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $commercesUser = Commerce::where('user_id', Auth::guard('web')->id())
                                ->orderBy('name', 'asc')->get();

        $totalShopping = 0;
        $totalShoppingUSD = 0;
        $totalShoppingBS = 0;
        $totalPendingUSD=0;
        $totalPendingBS=0;
        $idCommerce = 0;
        $commerceName = "";
        
        if($request->all()){
            $idCommerce = $request->commerceId;
        }else if(session()->get('commerce_id')){
            $idCommerce = session()->get('commerce_id');
        }else{
            $commerceFirst = Commerce::where('user_id', Auth::guard('web')->id())
                            ->orderBy('name', 'asc')->first();
            if($commerceFirst)
                $idCommerce = $commerceFirst->id;
        }

        session()->put('commerce_id', $idCommerce);

        $pictureUser = Picture::where('user_id', Auth::guard('web')->id())
                                ->where('commerce_id',$idCommerce)
                                ->where('description','Profile')->first();

        $commerceData = Commerce::whereId($idCommerce)->first();
        if($commerceData)
            $commerceName = $commerceData->name;


        $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format('Y-m-d')."%")
                        ->where("commerce_id", $idCommerce)->get();

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
        return view('auth.dashboard',compact("totalShopping", "totalShoppingUSD", "totalShoppingBS", "statusMenu", "commercesUser", "pictureUser", "commerceName", "idCommerce", "totalPendingUSD", "totalPendingBS"));
        
    }

    public function transactions(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $searchNameCompany="";
        $searchNameClient="";
        $selectCoin="Selecionar Moneda";
        $selectPayment="Selecionar Tipo de Pago";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $idCommerce = 0;
        $companyName = "";
        $commerceName = "";

        if($request->commerceId){
            $idCommerce = $request->commerceId;
        }else if(session()->get('commerce_id')){
            $idCommerce = session()->get('commerce_id');
        }

        session()->put('commerce_id', $idCommerce);
            
        $commerce = Commerce::whereId($idCommerce)->first();
        if($commerce){
            $companyName = $commerce->name;
            $commerceName = $commerce->name;
        }

        $commercesUser = Commerce::where('user_id', Auth::guard('web')->id())
                                ->orderBy('name', 'asc')->get();

        $pictureUser = Picture::where('user_id', Auth::guard('web')->id())
                                ->where('commerce_id',$idCommerce)
                                ->where('description','Profile')->first();

        $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                    ->where('paids.commerce_id', 'like', "%".$idCommerce. "%" )
                    ->orderBy('paids.id', 'desc')
                    ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.coin', 'paids.total',
                        'paids.date', 'paids.nameCompanyPayments','paids.codeUrl', 'paids.statusPayment');
        
        
        if($request->all() && !$request->commerceId){
            $searchNameCompany=$request->searchNameCompany;
            $searchNameClient=$request->searchNameClient;
            $selectCoin=$request->selectCoin;
            $selectPayment=$request->selectPayment;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }

        if(!empty($request->idCommerce))
            $transactions = $transactions->where('commerces.id', $idCommerce); 

        if(!empty($request->searchNameCompany))
            $transactions = $transactions->where('commerces.name', 'like', "%" . $request->searchNameCompany . "%" );
        
        if(!empty($request->searchNameClient))
            $transactions = $transactions->where('paids.nameClient', 'like', "%" . $request->searchNameClient . "%" );

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda")
            $transactions = $transactions->where('paids.coin', $request->selectCoin);
        
        if(!empty($request->selectPayment) && $request->selectPayment != "Selecionar Tipo de Pago"){
            if($request->selectPayment == "Tienda Web")
                $transactions = $transactions->where('paids.nameCompanyPayments', "!=", "Tienda Fisica")->Where('paids.nameCompanyPayments', "!=", "Pago en Efectivo" );
            else
                $transactions = $transactions->where('paids.nameCompanyPayments',  'like', "%" . $request->selectPayment . "%" );
        }

        $transactions = $transactions->whereDate('paids.created_at', ">=",$startDate)
                        ->whereDate('paids.created_at', "<=",$endDate);

        $transactions = $transactions->get();

        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.transactionsCommercePDF', compact('transactions', 'today', 'commerce', 'pictureUser', 'startDate', 'endDate'))->setPaper('a4', 'landscape');
            return $pdf->download('ctpaga_transacciones.pdf');
        }elseif($request->statusFile == "EXCEL"){
            $today = Carbon::now()->format('Y-m-d');
            return Excel::download(new TransactionsCommerceExport($transactions, $today, $commerce, $pictureUser, $startDate, $endDate), 'ctpaga_transacciones.xlsx');
        }

        $statusMenu = "transactions";
        return view('auth.transactions', compact('transactions', 'searchNameCompany', 'searchNameClient', 'selectCoin', 'selectPayment', 'startDate', 'endDate', 'statusMenu', "commercesUser", "pictureUser", "commerceName", 'idCommerce', 'companyName'));

    }

    public function depositHistory(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $selectCoin = 0;
        $idCommerce = 0;
        $companyName = "";
        $commerceName = "";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $startDateQuery = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $historyAll = array();
        
        if($request->all()){
            $selectCoin=$request->selectCoin? $request->selectCoin : 0;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }


        if($request->commerceId){
            $idCommerce = $request->commerceId;
        }else if(session()->get('commerce_id')){
            $idCommerce = session()->get('commerce_id');
        }else{
            $commerceFirst = Commerce::where('user_id', Auth::guard('web')->id())
                            ->orderBy('name', 'asc')->first();
            if($commerceFirst)
                $idCommerce = $commerceFirst->id;
        }


        session()->put('commerce_id', $idCommerce);
        
        $commercesUser = Commerce::where('user_id', Auth::guard('web')->id())
                                ->orderBy('name', 'asc')->get();

        $pictureUser = Picture::where('user_id', Auth::guard('web')->id())
                                ->where('commerce_id',$idCommerce)
                                ->where('description','Profile')->first();

        $commerceData = Commerce::whereId($idCommerce)->first();
        if($commerceData)
            $commerceName = $commerceData->name;
    
        $historyPaids = Paid::where("commerce_id", $idCommerce)
                            ->where("coin", $selectCoin)
                            ->orderBy('date', 'asc')
                            ->select("date", "total")
                            ->where("date", ">=", Carbon::now()->subMonth(4))
                            ->get();

        $historyDeposits = Deposits::where("commerce_id", $idCommerce)
                                ->where("coin", $selectCoin)
                                ->select("date", "total", "numRef","status")
                                ->where("date", ">=", Carbon::now()->subMonth(4))
                                ->orderBy('date', 'asc')->get();

        $total = 0.00;
        if(count($historyPaids)>0)
        {
            $dateStart = Carbon::parse($historyPaids[0]->date)->subDays(1);
            $dateFinal = Carbon::parse($historyPaids[0]->date);
            $startDateQuery = Carbon::parse($historyPaids[0]->date)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
            foreach ($historyPaids as $history)
            {

                if($dateFinal->diffInDays(Carbon::parse($history->date)) == 0 )
                {
                    $total += floatval($history->total); 
                }else{
                    array_push($historyAll, array("total"=>$total, "date" => $dateFinal->format('Y-m-d'), "numRef" => "", "status"=>0));
                    
                    foreach ($historyDeposits as $deposits)
                    {
                        $dateFinal = Carbon::parse($history->date);
                        if(Carbon::parse($deposits->date)->greaterThan($dateStart) && Carbon::parse($deposits->date)->lessThan($dateFinal))
                        {
                            array_push($historyAll, array("total"=>'-'.$deposits->total, "date" => $dateFinal->format('Y-m-d'), "numRef"=>$deposits->numRef, "status" =>$deposits->status));
                            $dateStart = Carbon::parse($history->date);
                        }
                    }

                    $total = floatval($history->total);
                }
            }

            array_push($historyAll, array("total"=>$total, "date" => $dateFinal->format('Y-m-d'), "numRef" => "", "status" =>0));
            foreach ($historyDeposits as $deposits)
            {
                if(Carbon::parse($deposits->date)->greaterThan($dateStart) && Carbon::parse($deposits->date)->lessThanOrEqualto(Carbon::now()))
                {
                    array_push($historyAll, array("total"=>'-'.$deposits->total, "date" => $dateFinal->format('Y-m-d'), "numRef"=>$deposits->numRef, "status"=>$deposits->status));
                }
            }
        }

        if($request->statusFile == "PDF"){
            $today = Carbon::now()->format('Y-m-d');
            $pdf = \PDF::loadView('report.depositsCommercePDF', compact("historyAll" ,'today' ,'selectCoin', 'startDate', 'endDate', "commerceData", 'pictureUser'));
            return $pdf->download('ctpaga_depositos.pdf');
        }elseif($request->statusFile == "EXCEL"){
            $today = Carbon::now()->format('Y-m-d');
            return Excel::download(new DepositsCommerceExport($historyAll, $today, $startDate, $endDate, $commerceData, $pictureUser), 'ctpaga_depositos.xlsx');
        }
        
        
        $statusMenu = "depositHistory";
        return view('auth.depositHistory',compact("historyAll" ,'selectCoin', 'startDate', 'endDate' ,"statusMenu", "commercesUser", "pictureUser", "commerceName", "idCommerce"));
    }

    public function rate(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('commerce.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $idCommerce = 0;
        $companyName = "";
        $commerceName = "";
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        
        if($request->all() && !$request->commerceId){
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }


        if($request->commerceId){
            $idCommerce = $request->commerceId;
        }else if(session()->get('commerce_id')){
            $idCommerce = session()->get('commerce_id');
        }else{
            $commerceFirst = Commerce::where('user_id', Auth::guard('web')->id())
                            ->orderBy('name', 'asc')->first();
            if($commerceFirst)
                $idCommerce = $commerceFirst->id;
        }


        session()->put('commerce_id', $idCommerce);
        
        $commercesUser = Commerce::where('user_id', Auth::guard('web')->id())
                                ->orderBy('name', 'asc')->get();

        $pictureUser = Picture::where('user_id', Auth::guard('web')->id())
                                ->where('commerce_id',$idCommerce)
                                ->where('description','Profile')->first();

        $commerceData = Commerce::whereId($idCommerce)->first();
        if($commerceData)
            $commerceName = $commerceData->name;
    

        $rates = Rate::where('user_id', Auth::guard('web')->id())->orderBy('date', 'desc')
                    ->whereDate('created_at', ">=",$startDate)
                    ->whereDate('created_at', "<=",$endDate)
                    ->where('roleRate',1)->get();

        if($request->statusFile == "PDF"){
            $pdf = \PDF::loadView('report.ratesPDF', compact('rates', 'commerceData', 'pictureUser', 'startDate', 'endDate'));
            return $pdf->download('ctpaga_tasas.pdf');
        }elseif($request->statusFile == "EXCEL"){
            return Excel::download(new RatesExport($rates, $commerceData, $pictureUser, $startDate, $endDate), 'ctpaga_tasas.xlsx');
        }

        $statusMenu = "rateHistory";
        return view('auth.rateHistory',compact("rates", 'startDate', 'endDate' ,"statusMenu", "commercesUser", "pictureUser", "commerceName", "idCommerce")); 
    }
}
