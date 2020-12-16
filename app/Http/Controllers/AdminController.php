<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Admin;
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
        if (false == Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $balances = DB::table('balances')
                ->join('commerces', 'commerces.id', '=', 'balances.commerce_id')
                ->where('balances.total', '>=', 1)
                ->orderBy('name', 'asc')
                ->orderBy('coin', 'desc')
                ->get();


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
        return view('admin.show');
    }

}
