<?php

namespace App\Http\Controllers;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Notifications\PostPurchase;
use App\Notifications\ShippingNotification;
use Carbon\Carbon;
use App\User;
use App\Sale;
use App\Paid;
use App\Commerce;
use App\Product;
use App\Balance;
use App\Shipping;
use Session;

class PaidController extends Controller
{
    public function formSubmit(Request $request)
    {
        $amount = str_replace(".","",$request->totalAll);
        $amount = str_replace(",",".",$amount);
        if($request->switchPay){
            $sales = Sale::where("codeUrl", $request->codeUrl)->get();
            $message="";
            foreach ($sales as $sale)
            {
                if($sale->type == 0 && $sale->productService_id != 0){
                    $product = Product::where('id',$sale->productService_id)->first();
                    
                    if ($product->postPurchase)
                        $message .= "- ".$product->postPurchase."\n";

                    $product->stock -= $sale->quantity;
                    $product->save();

                }

                if($sale->type == 1 && $sale->productService_id != 0){
                    $service = Service::where('id',$sale->productService_id)->first();
                    
                    if($service->postPurchase)
                        $message .= "- ".$service->postPurchase."\n";
                }

            }

            $commerce = Commerce::where('userUrl',$request->userUrl)->first();
            $user = User::where('id',$commerce->user_id)->first();

            if(strlen($request->priceShipping)>0){
                $priceShipping = $request->priceShipping;
            }else{
                $priceShipping = "0";
            }

            Paid::create([
                "user_id"               => $user->id,
                "commerce_id"           => $commerce->id,
                "codeUrl"               => $request->codeUrl,
                "nameClient"            => $request->nameClient,
                "total"                 => $amount,
                "coin"                  => $request->coinClient,
                "email"                 => $request->email,
                "nameShipping"          => $request->name,
                "numberShipping"        => $request->number,
                "addressShipping"       => $request->address,
                "detailsShipping"       => $request->details,
                "selectShipping"        => $request->selectShipping,
                "priceShipping"         => str_replace(",",".",$priceShipping),
                "percentage"            => $request->percentageSelect,
                "nameCompanyPayments"   => "Pago en Efectivo",
                "date"                  => Carbon::now(),
            ]);

            $userUrl = $request->userUrl;

            $messageNotification['commerce_id'] = $commerce->id;
            $messageNotification['total'] = $amount;
            $messageNotification['coin'] = $request->coinClient;
            $success = event(new NewNotification($messageNotification));

            (new User)->forceFill([
                'email' => $request->email,
            ])->notify(
                new PostPurchase($message, $userUrl, $commerce->name, $request->codeUrl)
            );

            return view('result', compact('userUrl'));
        }elseif($request->coinClient == 0){
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

                            $sales = Sale::where("codeUrl", $request->codeUrl)->get();
                            $message="";
                            foreach ($sales as $sale)
                            {
                                if($sale->type == 0 && $sale->productService_id != 0){
                                    $product = Product::where('id',$sale->productService_id)->first();
                                    
                                    if ($product->postPurchase)
                                        $message .= "- ".$product->postPurchase."\n";

                                    $product->stock -= $sale->quantity;
                                    $product->save();
                                }

                                if($sale->type == 1 && $sale->productService_id != 0){
                                    $service = Service::where('id',$sale->productService_id)->first();
                                    
                                    if($service->postPurchase)
                                        $message .= "- ".$service->postPurchase."\n";
                                }

                                $sale->statusSale = 1;
                                $sale->save();

                            }

                            $commerce = Commerce::where('userUrl',$request->userUrl)->first();
                            $user = User::where('id',$commerce->user_id)->first();
                            
                            if(strlen($request->priceShipping)>0){
                                $priceShipping = $request->priceShipping;
                            }else{
                                $priceShipping = "0";
                            }

                            Paid::create([
                                "user_id"               => $user->id,
                                "commerce_id"           => $commerce->id,
                                "codeUrl"               => $request->codeUrl,
                                "nameClient"            => $request->nameClient,
                                "total"                 => $amount,
                                "coin"                  => $request->coinClient,
                                "email"                 => $request->email,
                                "nameShipping"          => $request->name,
                                "numberShipping"        => $request->number,
                                "addressShipping"       => $request->address,
                                "detailsShipping"       => $request->details,
                                "selectShipping"        => $request->selectShipping,
                                "priceShipping"         => str_replace(",",".",$priceShipping),
                                "percentage"            => $request->percentageSelect,
                                "nameCompanyPayments"   => "Stripe",
                                "date"                  => Carbon::now(),
                            ]);

                            $balance = Balance::firstOrNew([
                                'user_id'       => $user->id,
                                "commerce_id"   => $commerce->id,
                                "coin"          => $request->coinClient,
                            ]);

                            $balance->total += floatval($amount);
                            $balance->save();

                            $userUrl = $request->userUrl;

                            $messageNotification['commerce_id'] = $commerce->id;
                            $messageNotification['total'] = $amount;
                            $messageNotification['coin'] = $request->coinClient;
                            $success = event(new NewNotification($messageNotification));

                            (new User)->forceFill([
                                'email' => $request->email,
                            ])->notify(
                                new PostPurchase($message, $userUrl, $commerce->name, $request->codeUrl)
                            );

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

            $url = 'https://esitef-homologacao.softwareexpress.com.br/e-sitef/api/v1/transactions';
            $ch = curl_init($url);
            $jsonData = array(
                'installments' => '1',
                'installment_type' => '4',
                'authorizer_id' => $request->typeCard,
                'amount' => $amount*100,
                'additional_data' => array(
                    'currency' => 'VEF'
                )
            );

            $jsonDataEncoded = json_encode($jsonData);


            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
                "Content-Type: application/json",
                "merchant_id: ".env('merchant_id'),
                "merchant_key: ".env('merchant_key')
            ));
            
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            $url = 'https://esitef-homologacao.softwareexpress.com.br/e-sitef/api/v1/payments/'.$result['payment']['nit'];
            $ch = curl_init($url);

            $jsonData = array(
                'card' => array(
                    'number' => $request->numberCard,
                    'expiry_date' => $request->dateMM . $request->dateMM,
                    'security_code' => $request->cardCVC
                )
            );

            $jsonDataEncoded = json_encode($jsonData);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
                "Content-Type: application/json",
                "merchant_id: ".env('merchant_id'),
                "merchant_key: ".env('merchant_key')
            ));
            
            $resultTransaction = json_decode(curl_exec($ch), true);
            curl_close($ch);


            if($resultTransaction['message'] == 'OK. Transaction successful'){
                $sales = Sale::where("codeUrl", $request->codeUrl)->get();
                $message="";
                foreach ($sales as $sale)
                {
                    if($sale->type == 0 && $sale->productService_id != 0){
                        $product = Product::where('id',$sale->productService_id)->first();
                        
                        if ($product->postPurchase)
                            $message .= "- ".$product->postPurchase."\n";

                        $product->stock -= $sale->quantity;
                        $product->save();
                    }

                    if($sale->type == 1 && $sale->productService_id != 0){
                        $service = Service::where('id',$sale->productService_id)->first();
                        
                        if($service->postPurchase)
                            $message .= "- ".$service->postPurchase."\n";
                    }

                    $sale->statusSale = 1;
                    $sale->save();
                }

                $commerce = Commerce::where('userUrl',$request->userUrl)->first();
                $user = User::where('id',$commerce->user_id)->first();

                if(strlen($request->priceShipping)>0){
                    $priceShipping = $request->priceShipping;
                }else{
                    $priceShipping = "0";
                }
                
                Paid::create([
                    "user_id"               => $user->id,
                    "commerce_id"           => $commerce->id,
                    "codeUrl"               => $request->codeUrl,
                    "nameClient"            => $request->nameClient,
                    "total"                 => $amount,
                    "coin"                  => $request->coinClient,
                    "email"                 => $request->email,
                    "nameShipping"          => $request->name,
                    "numberShipping"        => $request->number,
                    "addressShipping"       => $request->address,
                    "detailsShipping"       => $request->details,
                    "selectShipping"        => $request->selectShipping,
                    "priceShipping"         => str_replace(",",".",$priceShipping),
                    "percentage"            => $request->percentageSelect,
                    "nameCompanyPayments"   => "E-sitef",
                    "date"                  => Carbon::now(),
                ]);

                $balance = Balance::firstOrNew([
                    'user_id'       => $user->id,
                    "commerce_id"   => $commerce->id,
                    "coin"          => $request->coinClient,
                ]);

                $balance->total += floatval($amount);
                $balance->save();

                $userUrl = $request->userUrl;

                $messageNotification['commerce_id'] = $commerce->id;
                $messageNotification['total'] = $amount;
                $messageNotification['coin'] = $request->coinClient;
                $success = event(new NewNotification($messageNotification));

                (new User)->forceFill([
                    'email' => $request->email,
                ])->notify(
                    new PostPurchase($message, $userUrl, $commerce->name, $request->codeUrl)
                );

                return view('result', compact('userUrl'));
            }else{
                Session::flash('message', "¡Tu pago ha fallado!");
                return Redirect::back();
            }
        }

    }

    public function show(Request $request)
    {
        $user = $request->user();
        $paids = Paid::where('user_id', $user->id)
                    ->where('commerce_id', $request->commerce_id)
                    ->orderBy('created_at', 'desc')->get();
        
        return response()->json(['statusCode' => 201,'data' => $paids]);
    }

    public function showPaidDelivery(Request $request)
    {
        $paids = Paid::where('codeUrl', $request->codeUrl)->first();
        if($paids && $paids->statusShipping <2){
            $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
            $commerce = Commerce::whereId($paids->commerce_id)->first();
            return response()->json(['statusCode' => 201,'data' =>['paid'=>$paids, 'commerce'=>$commerce, 'sales'=>$sales]]);
        }else if($paids && $paids->statusShipping == 2)
            return response()->json(['statusCode' => 401,'message' => "Este Código de compra ya fue entregado los productos"]);

        return response()->json(['statusCode' => 401,'message' => "No se encuentra en nuestra base de datos"]);
    }

    public function changeStatus(Request $request)
    {
        $paids = Paid::where('codeUrl', $request->codeUrl)->first();
        $paids->statusShipping = $request->statusShipping;

        if($request->statusShipping == 1){
            (new User)->forceFill([
                'email' => $paids->email,
            ])->notify(
                new ShippingNotification("los productos fue retirado desde la tienda llegará al destino no mas tardar de 1 hora.")
            );
        }elseif($request->statusShipping == 2){
            $userCommerce = User::whereId($paids->user_id)->first();
            (new User)->forceFill([
                'email' => $paids->email,
            ])->notify(
                new ShippingNotification("los productos de Número de compra ".$paids->codeUrl." fue entregado a su destino.")
            );

            (new User)->forceFill([
                'email' => $userCommerce->email,
            ])->notify(
                new ShippingNotification("los productos de Número de compra ".$paids->codeUrl." fue entregado a su destino.")
            );
        }

        $paids->save();

        return response()->json(['statusCode' => 201,'data' =>['paid'=>$paids]]);

    }
}
