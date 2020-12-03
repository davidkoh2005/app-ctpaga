<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\User;
use App\Sale;
use App\Paid;
use App\Commerce;
use Session;

class PaidController extends Controller
{
    public function formSubmit(Request $request)
    {
        if($request->coinClient == 0){
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            try{
                $customer = \Stripe\Customer::create(array( 
                    'name'  => $request->name,
                    'email' => $request->email, 
                    'source'  => $request->stripeToken, 
                ));

            }catch(\Stripe\Exception\CardException $e) {  
                $error = "Tu tarjeta no tiene fondos suficientes";  
            }catch (\Stripe\Exception\ApiErrorException $e){
                $error = "Tu tarjeta fue rechazada. Esta transacción requiere autenticación.";
            }catch (Exception $e) {
                $error = $e->getMessage(); 
            }

            if(empty($error) && $customer){  
                $amount = str_replace(".","",$request->totalAll);
                $amount = str_replace(",",".",$amount);
                try {  
                    $charge = \Stripe\Charge::create( array(
                        'amount' => $amount*100, 
                        'currency' => 'usd',
                        'description' => $request->orderClient,
                        "customer" => $customer->id,
                    ));
                }catch(Exception $e) {  
                    $error = $e->getMessage();  
                } 
                 
                if(empty($error) && $charge){ 
                 
                    $chargeJson = $charge->jsonSerialize(); 
                 
                    if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){ 

                        $payment_status = $chargeJson['status']; 

                        if($payment_status == 'succeeded'){ 

                            Sale::where("codeUrl", $request->codeUrl)->update(['statusSale' => 1]);

                            $commerce = Commerce::where('userUrl',$request->userUrl)->first();
                            $user = User::find($commerce->user_id)->first();
                            
                            Paid::create([
                                "user_id"               => $user->id,
                                "commerce_id"           => $commerce->id,
                                "codeUrl"               => $request->codeUrl,
                                "nameClient"            => $request->nameClient,
                                "total"                 => $amount,
                                "coin"                  => $request->coinClient,
                                "email"                 => $request->email,
                                "nameShopping"          => $request->name,
                                "numberShopping"        => $request->number,
                                "addressShopping"       => $request->address,
                                "detailsShopping"       => $request->details,
                                "shipping_id"           => $request->shippings,
                                "percentage"            => $request->percentageSelect,
                                "nameCompanyPayments"   => "Stripe",
                            ]);

                            $userUrl = $request->userUrl;

                            return view('result', compact('userUrl'));
                        }else{ 
                            Session::flash('message', "¡Tu pago ha fallado!");
                            return Redirect::back();
                        } 
                    }else{ 
                        Session::flash('message', "¡La transacción ha fallado!");
                        return Redirect::back();
                    } 
                }else{
                    Session::flash('message', 'Error al crear la carga');
                    return Redirect::back();
                } 
            }else{  
                Session::flash('message', $error);
                return Redirect::back();  
            } 
        }else{
            // bs
        }

    }
}
