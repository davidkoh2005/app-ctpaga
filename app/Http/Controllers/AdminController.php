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
use App\Notifications\PictureRemove;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $balances = array();
        if (false == Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $balancesAll = DB::table('balances')
                ->join('commerces', 'commerces.id', '=', 'balances.commerce_id')
                ->where('balances.total', '>=', 1)
                ->select('balances.id', 'balances.user_id', 'balances.commerce_id', 'balances.coin', 'balances.total',
                'commerces.name')
                ->orderBy('name', 'asc')
                ->orderBy('coin', 'desc')
                ->get();

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

        return view('admin.dashboard', compact('balances'));
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

        Session::flash('message', "El Correo o la contraseña es incorrecta!");
        return Redirect::back();
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    } 


    public function show($id)
    {
        $balance = Balance::where('id', $id)->first();
        $commerce = Commerce::where("id", $balance->commerce_id)->first();
        $user = User::where("id", $commerce->user_id)->first();
        $pictures = Picture::where('user_id', $user->id)
                            ->where('commerce_id', $commerce->id)
                            ->where('description', '<>','Profile')->get();

        $selfie = Picture::where('user_id', $user->id)
                        ->where('commerce_id', '=', null)->first();
        
        if($balance->coin == 0)
            $coin = "USD";
        else
            $coin = "Bs";

        $bank = Bank::where('user_id', $user->id)
                    ->where('coin', $coin)->first();

        return view('admin.show', compact('commerce', 'user', 'pictures', 'selfie', 'balance', 'bank'));
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
        $balance = Balance::where('id', $request->id)->first();
        $total = $request->total;
        $balance->total -= $request->total;
        $balance->save(); 

        Deposits::create([
            "user_id"       => $balance->user_id,
            "commerce_id"   => (int)$balance->commerce_id,
            "coin"          => $balance->coin,
            "total"         => $total,
            "numRef"        => $request->numRef
        ]);

        return response()->json([
            'status' => 201
        ]);
        
    }

    public function commerces(Request $request)
    {
        $commerces = Commerce::all()->sortBy("name");
        return view('admin.commerces', compact('commerces'));
    }

    public function commercesShow($id)
    {
        $commerce = Commerce::where('id', $id)->first();
        $user = User::where('id',$commerce->user_id)->first();
        $transactions = Paid::where('commerce_id', $id)->orderBy('date', 'asc')->get();

        return view('admin.commerceShow', compact('commerce', 'user', 'transactions'));
    }

    public function transactions(Request $request)
    {
        $searchNameCompany="";
        $searchNameClient="";
        $selectCoin="Selecionar Moneda";
        $selectPayment="Selecionar Tipo de Pago";
        $startDate="";
        $endDate="";

        if($request->all()){
            $searchNameCompany=$request->searchNameCompany;
            $searchNameClient=$request->searchNameClient;
            $selectCoin=$request->selectCoin;
            $selectPayment=$request->selectPayment;
            $startDate=$request->startDate;
            $endDate=$request->endDate;
        }

        $transactions = Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                        ->orderBy('paids.id', 'desc')
                        ->select('paids.id', 'commerces.name', 'paids.nameClient', 'paids.coin', 'paids.total',
                            'paids.date', 'paids.nameCompanyPayments');

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

        return view('admin.transactions', compact('transactions', 'searchNameCompany', 'searchNameClient', 'selectCoin', 'selectPayment', 'startDate', 'endDate'));
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
}
