<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\User;
use App\Bank;
use App\Paid;
use App\Sale;
use App\Admin;
use App\Picture;
use App\Balance;
use App\Commerce;
use App\Deposits;
use Carbon\Carbon;
use App\Notifications\PictureRemove;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

        $selectCoin="Selecionar Moneda";
        $balances = array();

        $balancesAll = DB::table('balances')
                ->join('commerces', 'commerces.id', '=', 'balances.commerce_id')
                ->where('balances.total', '>=', 1)
                ->select('balances.id', 'balances.user_id', 'balances.commerce_id', 'balances.coin', 'balances.total',
                'commerces.name')
                ->orderBy('name', 'asc')
                ->orderBy('coin', 'desc');
        
        if($request->all()){
            $selectCoin= $request->selectCoin;
            $balancesAll = $balancesAll->where('balances.coin', $selectCoin);
        }
            
        $balancesAll = $balancesAll->get();

        foreach ($balancesAll as $balance)
        {
            $pictures = Picture::where('user_id', '=', $balance->user_id)
                            ->where('commerce_id', '=', null)
                            ->orwhere('commerce_id', $balance->commerce_id)->get();
            
            $count= 0;
            foreach($pictures as $picture)
            {
                if (in_array($picture->description, array('Selfie','RIF','Identification'))) {
                    $count +=1;
                }

            }
            
            if($balance->coin == 0)
                $coin = "USD";
            else
                $coin = "Bs";

            $bank = Bank::where('user_id', $balance->user_id)
                        ->where('coin', $coin)->first();

            if($count == 3 && $bank)
                $balances[] = $balance;
        }

        $statusMenu = "balance";

        return view('admin.balance', compact('balances',"statusMenu",'selectCoin'));
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

            $balance = Balance::where('id', $id['id'])->first();
            $balance->total -= floatval($id['total']);
            $balance->save(); 

            Deposits::create([
                "user_id"       => $balance->user_id,
                "commerce_id"   => (int)$balance->commerce_id,
                "coin"          => $balance->coin,
                "total"         => $id['total'],
                "numRef"        => $request->numRef,
                "date"          => Carbon::now(),
            ]);
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
        $startDate="";
        $endDate="";
        $idCommerce=0;

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
            $idCommerce = $request->idCommerce;
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

        if(!empty($request->startDate) && !empty($request->endDate))
            $transactions->where('paids.date', ">=",$request->startDate)
                        ->where('paids.date', "<=",$request->endDate);

        $transactions = $transactions->get();

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

    public function showPayment(Request $request)
    {
        $balance = null;
        $commerce = null;
        $user = null;
        $coin = "";    
        $bank = null;
        $selectId = $request->selectId;
        $dataId = [];
        if($request->status == "true")
        {
            $statusID = true;
            $balance = Balance::where('id', $selectId[0])->first();
            $commerce = Commerce::where("id", $balance->commerce_id)->first();
            $user = User::where("id", $commerce->user_id)->first();
            
            if($balance->coin == 0)
                $coin = "USD";
            else
                $coin = "Bs";

            $bank = Bank::where('user_id', $user->id)
                        ->where('coin', $coin)->first();
            
            $dataId = array(
                "id" => $balance->id,
                "total" => $balance->total,
            );
        }else{
            $statusID = false;
            $countUSD = 0;
            $countBS = 0;
            foreach ($selectId as $id)
            {
                $balance = Balance::where('id', $id)->first();
                if($balance->coin == 0)
                    $countUSD += 1;
                else
                    $countBS +=1;

                array_push($dataId, array(
                    "id" => $balance->id,
                    "total" => $balance->total,
                ));
            }
            Session::put('dataSelectID', $dataId);          

            if($countUSD == 0 && $countBS >0)
            {
                $returnHTML=view('admin.modal.dataPayment', compact('balance', 'commerce', 'user', 'bank', 'statusID'))->render();
                return response()->json(array('html'=>$returnHTML, 'status' => 0));
            }else{
                $returnHTML=view('admin.modal.dataPayment', compact('balance', 'commerce', 'user', 'bank', 'statusID'))->render();
                return response()->json(array('html'=>$returnHTML, 'status' => 1));
            }
        }

        Session::put('dataSelectID', $dataId);

        $returnHTML=view('admin.modal.dataPayment', compact('balance', 'commerce', 'user', 'bank', 'statusID'))->render();
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
        $startDate="";
        $endDate="";

        if($request->all()){
            $searchNameCompany=$request->searchNameCompany;
            $numRef=$request->numRef;
            $selectCoin=$request->selectCoin;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }

        $deposits = DB::table('deposits')
                ->join('commerces', 'commerces.id', '=', 'deposits.commerce_id')
                ->select('deposits.id', 'deposits.user_id', 'deposits.commerce_id', 'deposits.coin', 'deposits.total', 'deposits.numRef', 'deposits.date', 'commerces.name');

        if(!empty($request->searchNameCompany))
            $deposits->where('commerces.name', 'ilike', "%" . $request->searchNameCompany . "%" );
        
        if(!empty($request->numRef))
            $deposits->where('deposits.numRef', 'ilike', "%" . $request->numRef . "%" );

        if(!empty($request->searchNameClient))
            $deposits->where('deposits.nameClient', 'ilike', "%" . $request->searchNameClient . "%" );

        if(!empty($request->selectCoin) && $request->selectCoin != "Selecionar Moneda")
            $deposits->where('deposits.coin', $request->selectCoin);
        
        if(!empty($request->startDate) && !empty($request->endDate))
            $deposits->where('deposits.date', ">=",$request->startDate)
                        ->where('deposits.date', "<=",$request->endDate);

        $deposits = $deposits->get();
        
        $statusMenu = "reportPayment";
        return view('admin.reportPayment', compact('deposits', 'searchNameCompany', 'selectCoin', 'selectPayment', 'startDate', 'endDate', 'numRef', 'statusMenu'));
    }
}
