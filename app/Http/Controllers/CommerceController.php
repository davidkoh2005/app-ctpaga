<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Paid;
use App\Commerce;
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
        
        $totalShopping = 0;
        $totalShoppingStripe = 0;
        $totalShoppingSitef = 0;

        $paidAll = Paid::where("date", 'like', "%".Carbon::now()->format('Y-m-d')."%")
                        ->whereId(Auth::guard('web')->id())->get();
        foreach ($paidAll as $paid)
        {
            $totalShopping += 1;
            if($paid->nameCompanyPayments == "Stripe")
                $totalShoppingStripe += floatval($paid->total);
            
            if($paid->nameCompanyPayments == "E-sitef")
                $totalShoppingSitef += floatval($paid->total);
        }

        $statusMenu = "dashboard";
        return view('auth.dashboard',compact("totalShopping", "totalShoppingStripe", "totalShoppingSitef", "statusMenu"));
        
    }
}
