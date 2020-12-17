<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Admin;
use App\User;
use App\Picture;
use App\Balance;
use App\Commerce;
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
                ->orderBy('name', 'asc')
                ->orderBy('coin', 'desc')
                ->get();

        foreach ($balancesAll as $balance)
        {
            $pictures = Picture::where('user_id', $balance->user_id)
                            ->where('commerce_id', $balance->commerce_id)
                            ->orWhere('commerce_id', '=', null)->get();
            
            $count= 0;
            foreach($pictures as $picture)
            {
                if (in_array($picture->description, array('Selfie','RIF','Identification'))) {
                    $count +=1;
                }
            }

            if($count == 3)
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
        $commerce = Commerce::where("id", $id)->first();
        $user = User::where("id", $commerce->user_id)->first();
        $pictures = Picture::where('user_id', $user->id)
                            ->where('commerce_id', $commerce->id)
                            ->where('description', '<>','Profile')->get();

        $profile = Picture::where('user_id', $user->id)
                        ->where('commerce_id', '=', null)->first();

        $balance = Balance::where('user_id', $user->id)
                        ->where('commerce_id', $commerce->id)->first();

        $domain = $_SERVER['HTTP_HOST'];

        return view('admin.show', compact('domain','commerce', 'user', 'pictures', 'profile', 'balance'));
    }

}
