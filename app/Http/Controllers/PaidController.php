<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Sale;
use App\Commerce;
use App\Shipping;
use App\Discount;

class PaidController extends Controller
{
    public function formSubmit(Request $request)
    {

        if($request->coinClient == 0){
            \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));

            try{
                \Stripe\Charge::create( array(
                    'amount' => $request->totalAll,
                    'currency' => 'usd',
                    'description' => $request->orderClient,
                    'source' => $request->input ( 'stripeToken'),
                ));
                return $request->all();
            } catch ( \Exception $e ) {
                return "error";
            }
        }else{

        }

    }
}
