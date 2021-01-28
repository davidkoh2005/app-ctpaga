<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Paid;
use App\Rate;
use App\Commerce;
use App\Deposits;
use App\Picture;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
        $totalShoppingStripe = 0;
        $totalShoppingSitef = 0;
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
            if($paid->nameCompanyPayments == "Stripe")
                $totalShoppingStripe += floatval($paid->total);
            
            if($paid->nameCompanyPayments == "E-sitef")
                $totalShoppingSitef += floatval($paid->total);
        }

        $statusMenu = "dashboard";
        return view('auth.dashboard',compact("totalShopping", "totalShoppingStripe", "totalShoppingSitef", "statusMenu", "commercesUser", "pictureUser", "commerceName", "idCommerce"));
        
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
        $startDate = "";
        $endDate = "";
        $idCommerce = 0;
        $companyName = "";
        $commerceName = "";

        if($request->all()){
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
                        'paids.date', 'paids.nameCompanyPayments');

        if($request->all()){
            $searchNameCompany=$request->searchNameCompany;
            $searchNameClient=$request->searchNameClient;
            $selectCoin=$request->selectCoin;
            $selectPayment=$request->selectPayment;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }


        if(!empty($request->idCommerce))
            $transactions->where('commerces.id', $idCommerce); 

        if(!empty($request->searchNameCompany))
            $transactions->where('commerces.name', 'ilike', "%" . $request->searchNameCompany . "%" );
        
        if(!empty($request->searchNameClient))
            $transactions->where('paids.nameClient', 'ilike', "%" . $request->searchNameClient . "%" );

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda")
            $transactions->where('paids.coin', $request->selectCoin);
        
        if(!empty($request->selectPayment) && $request->selectPayment != "Selecionar Tipo de Pago"){
            if($request->selectPayment == "Tienda Web")
                $transactions->where('paids.nameCompanyPayments', "Stripe")->orWhere('paids.nameCompanyPayments',  "E-sitef" );
            else
                $transactions->where('paids.nameCompanyPayments',  'ilike', "%" . $request->selectPayment . "%" );
        }

        if(!empty($request->startDate) && !empty($request->endDate))
            $transactions->where('paids.date', ">=",$request->startDate)
                        ->where('paids.date', "<=",$request->endDate);

        $transactions = $transactions->get();

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
        $startDate = "";
        $endDate = "";
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
                            ->where("date", ">=", Carbon::now()->subMonth(3));

        if($selectCoin == 0)
            $historyPaids = $historyPaids->where("nameCompanyPayments", "Stripe")->get();
        else
            $historyPaids = $historyPaids->where("nameCompanyPayments", "E-sitef")->get();

        $historyDeposits = Deposits::where("commerce_id", $idCommerce)
                                ->where("coin", $selectCoin)
                                ->select("date", "total", "numRef","status")
                                ->where("date", ">=", Carbon::now()->subMonth(3))
                                ->orderBy('date', 'asc')->get();

        $total = 0.00;
        if(count($historyPaids)>0)
        {
            $dateStart = Carbon::parse($historyPaids[0]->date)->subDays(1);
            $dateFinal = Carbon::parse($historyPaids[0]->date);
            $startDate = Carbon::parse($historyPaids[0]->date)->format('Y-m-d');
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
        
        
        $statusMenu = "depositHistory";
        return view('auth.depositHistory',compact("historyAll" ,'selectCoin', 'selectPayment', 'startDate', 'endDate' ,"statusMenu", "commercesUser", "pictureUser", "commerceName", "idCommerce"));
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
        $startDate = "";
        $endDate = "";
        
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
    

        $rates = Rate::where('user_id', Auth::guard('web')->id())->orderBy('date', 'desc');
        
        if(!empty($request->startDate) && !empty($request->endDate))
            $rates = $rates->where('date', ">=",$request->startDate)
                        ->where('date', "<=",$request->endDate);

        $rates = $rates->get();
        
        $statusMenu = "rateHistory";
        return view('auth.rateHistory',compact("rates", 'startDate', 'endDate' ,"statusMenu", "commercesUser", "pictureUser", "commerceName", "idCommerce")); 
    }
}
