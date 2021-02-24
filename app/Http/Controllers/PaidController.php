<?php

namespace App\Http\Controllers;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Notifications\PostPurchase;
use App\Notifications\ShippingNotification;
use Carbon\Carbon;
use App\User;
use App\Sale;
use App\Paid;
use App\Rate;
use App\Commerce;
use App\Product;
use App\Balance;
use App\Shipping;
use Session;
use AWS;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\ApiClient;

class PaidController extends Controller
{

    private $apiContext;

    public function __construct()
    {
        $payPalConfig = Config::get('paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $payPalConfig['client_id'],
                $payPalConfig['secret']
            )
        );

        $this->apiContext->setConfig($payPalConfig['settings']);
    }

    public function formSubmit(Request $request)
    {
        $userUrl = $request->userUrl;
        $codeUrl = $request->codeUrl;
        $amount = str_replace(".","",$request->totalAll);
        $amount = str_replace(",",".",$amount);
        if($request->switchPay){
            $sales = Sale::where("codeUrl", $codeUrl)->get();
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
                "codeUrl"               => $codeUrl,
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
                "statusPayment"         => 2,
            ]);

            $balance = Balance::firstOrNew([
                'user_id'       => $user->id,
                "commerce_id"   => $commerce->id,
                "coin"          => $request->coinClient,
            ]);

            $balance->total += floatval($amount)-(floatval($amount)*0.05+0.35);
            $balance->save();

            $userUrl = $request->userUrl;

            $messageNotification['commerce_id'] = $commerce->id;
            $messageNotification['total'] = $amount;
            $messageNotification['coin'] = $request->coinClient;
            $success = event(new NewNotification($messageNotification));

            (new User)->forceFill([
                'email' => $request->email,
            ])->notify(
                new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
            );

            $status = true;
            return view('result', compact('userUrl', 'status'));
        }elseif($request->coinClient == 0 && $request->payment == "CARD"){

            $url = 'https://connect.squareupsandbox.com/v2/payments';
            $ch = curl_init($url);

            $jsonData = array(
                'amount_money' => array(
                    'amount' => intval(str_replace(".","",floatval($amount)*100)),
                    'currency' => "USD"
                ),
                "source_id" => $request->nonce,
                "idempotency_key" => $request->idempotency_key,
                "buyer_email_address" => $request->email,
                "note" => $codeUrl,
            );

            $jsonDataEncoded = json_encode($jsonData);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
                'Square-Version: 2021-01-21',
                "Content-Type: application/json",
                "Authorization: Bearer ".env('SQUARE_TOKEN')
            ));
            
            $resultTransaction = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if($resultTransaction && isset($resultTransaction['payment'])){
                if($resultTransaction['payment']['status'] == "COMPLETED"){
                    $sales = Sale::where("codeUrl", $codeUrl)->get();
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
                        "codeUrl"               => $codeUrl,
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
                        "nameCompanyPayments"   => "Square",
                        "date"                  => Carbon::now(),
                        "statusPayment"         => 2,
                        "refPayment"            => $resultTransaction['payment']['order_id']
                    ]);

                    $balance = Balance::firstOrNew([
                        'user_id'       => $user->id,
                        "commerce_id"   => $commerce->id,
                        "coin"          => $request->coinClient,
                    ]);

                    $rateAdmin = Rate::where("roleRate",0)->orderBy("created_at","desc")->first();

                    $balance->total += floatval($amount)-(floatval($amount)*0.1+0.35);
                    $balance->save();

                    $userUrl = $request->userUrl;

                    $messageNotification['commerce_id'] = $commerce->id;
                    $messageNotification['total'] = $amount;
                    $messageNotification['coin'] = $request->coinClient;
                    $success = event(new NewNotification($messageNotification));

                    (new User)->forceFill([
                        'email' => $request->email,
                    ])->notify(
                        new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
                    );

                    $status = true;
                    return view('result', compact('userUrl', 'status'));
                }else{
                    Session::flash('message', "¡Tu pago ha fallado!");
                    return Redirect::back();
                }
            }else{
                Session::flash('message', "¡Tu pago ha fallado!");
                return Redirect::back();
            }

        }elseif($request->coinClient == 0 && $request->payment == "PAYPAL"){

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amountPaypal = new Amount();
            $amountPaypal->setTotal($amount);
            $amountPaypal->setCurrency('USD');

            $transaction = new Transaction();
            $transaction->setAmount($amountPaypal);
            $transaction->setDescription('Compra a través de ctpaga');

            $callbackUrl = url('/pagar/estadoPaypal/');

            Session::put('request', $request->all());

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($callbackUrl)
                ->setCancelUrl($callbackUrl);

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions(array($transaction))
                ->setRedirectUrls($redirectUrls);

            try {
                $payment->create($this->apiContext);
                return redirect()->away($payment->getApprovalLink());
            } catch (PayPalConnectionException $ex) {
                echo $ex->getData();
            }
        }elseif($request->coinClient == 0 && $request->payment == "BITCOIN"){
            
            $sales = Sale::where("codeUrl", $codeUrl)->get();
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

            $commerce = Commerce::where('userUrl',$userUrl)->first();
            $user = User::where('id',$commerce->user_id)->first();
            
            if(strlen($request->priceShipping)>0){
                $priceShipping = $request->priceShipping;
            }else{
                $priceShipping = "0";
            }

            Paid::create([
                "user_id"               => $user->id,
                "commerce_id"           => $commerce->id,
                "codeUrl"               => $codeUrl,
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
                "nameCompanyPayments"   => "Bitcoin",
                "date"                  => Carbon::now(),
                "statusPayment"         => 1,
            ]);

            ApiClient::init(env('COINBASE_KEY'));


            $chargeData = [
                'name' => 'Pago Ctpaga',
                'description' => 'Transacción código: '.$codeUrl,
                'code' => $codeUrl,
                'local_price' => [
                    'amount' => $amount,
                    'currency' => 'USD'
                ],
                'pricing_type' => 'fixed_price'
            ];
            $chargeObj = Charge::create($chargeData);
            
            return redirect()->away($chargeObj->hosted_url);
        
        }elseif($request->coinClient == 1){
            
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
                $sales = Sale::where("codeUrl", $codeUrl)->get();
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
                    "codeUrl"               => $codeUrl,
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
                    "statusPayment"         => 2,
                    "date"                  => Carbon::now(),
                ]);

                $balance = Balance::firstOrNew([
                    'user_id'       => $user->id,
                    "commerce_id"   => $commerce->id,
                    "coin"          => $request->coinClient,
                ]);

                $rateAdmin = Rate::where("roleRate",0)->orderBy("created_at","desc")->first();

                $balance->total += floatval($amount)-(floatval($amount)*0.05+(0.35*floatval($rateAdmin->rate)));
                $balance->save();

                $userUrl = $request->userUrl;

                $messageNotification['commerce_id'] = $commerce->id;
                $messageNotification['total'] = $amount;
                $messageNotification['coin'] = $request->coinClient;
                $success = event(new NewNotification($messageNotification));

                (new User)->forceFill([
                    'email' => $request->email,
                ])->notify(
                    new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
                );

                $status = true;
                return view('result', compact('userUrl', 'status'));
            }else{
                Session::flash('message', "¡Tu pago ha fallado!");
                return Redirect::back();
            }
        }

    }

    public function statusPaypal(Request $request)
    {
        $requestForm = Session::get('request');
        Session::forget('request');
        
        $userUrl = $requestForm['userUrl'];
        $codeUrl = $requestForm['codeUrl'];
        $amount = str_replace(".","",$requestForm['totalAll']);
        $amount = str_replace(",",".",$amount);

        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
        $token = $request->input('token');

        if (!$paymentId || !$payerId || !$token) {
            Session::flash('message', "Lo sentimos! El pago a través de PayPal no se pudo realizar.");
            return redirect('/'.$userUrl.'/'.$codeUrl);
        }

        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        /** Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);
        if ($result->getState() === 'approved') {
            $sales = Sale::where("codeUrl", $codeUrl)->get();
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

            $commerce = Commerce::where('userUrl',$userUrl)->first();
            $user = User::where('id',$commerce->user_id)->first();
            
            if(strlen($requestForm['priceShipping'])>0){
                $priceShipping = $requestForm['priceShipping'];
            }else{
                $priceShipping = "0";
            }

            Paid::create([
                "user_id"               => $user->id,
                "commerce_id"           => $commerce->id,
                "codeUrl"               => $requestForm['codeUrl'],
                "nameClient"            => $requestForm['nameClient'],
                "total"                 => $amount,
                "coin"                  => $requestForm['coinClient'],
                "email"                 => $requestForm['email'],
                "nameShipping"          => $requestForm['name'],
                "numberShipping"        => $requestForm['number'],
                "addressShipping"       => $requestForm['address'],
                "detailsShipping"       => $requestForm['details'],
                "selectShipping"        => $requestForm['selectShipping'],
                "priceShipping"         => str_replace(",",".",$priceShipping),
                "percentage"            => $requestForm['percentageSelect'],
                "nameCompanyPayments"   => "PayPal",
                "date"                  => Carbon::now(),
                "statusPayment"         => 2,
                "refPayment"            => $result->getId(),
            ]);

            $balance = Balance::firstOrNew([
                'user_id'       => $user->id,
                "commerce_id"   => $commerce->id,
                "coin"          => $requestForm['coinClient'],
            ]);

            $balance->total += floatval($amount)-(floatval($amount)*0.1+0.35);
            $balance->save();

            $messageNotification['commerce_id'] = $commerce->id;
            $messageNotification['total'] = $amount;
            $messageNotification['coin'] = $requestForm['coinClient'];
            $success = event(new NewNotification($messageNotification));

            (new User)->forceFill([
                'email' => $requestForm['email'],
            ])->notify(
                new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
            );

            $status = true;
            return view('result', compact('userUrl', 'status'));
        }

        Session::flash('message', "Lo sentimos! El pago a través de PayPal no se pudo realizar.");
        return redirect('/'.$userUrl.'/'.$codeUrl);
    }

    public function show(Request $request)
    {
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');

        $user = $request->user();
        $paids = Paid::where('user_id', $user->id)
                    ->where('commerce_id', $request->commerce_id)
                    ->whereDate('created_at', ">=",$startDate)
                    ->orderBy('created_at', 'desc')->get();
        
        return response()->json(['statusCode' => 201,'data' => $paids]);
    }

    public function showPaidDelivery(Request $request)
    {
        $delivery = $request->user();
        $paids = Paid::where('codeUrl', $request->codeUrl)
                ->where("idDelivery",$delivery->id)->first();

        if($paids){
            $paids->idDelivery = $delivery->id;
            $paids->statusDelivery = 2;
            $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
            $commerce = Commerce::whereId($paids->commerce_id)->first();
            $paids->save();
            return response()->json(['statusCode' => 201,'data' =>['paid'=>$paids, 'commerce'=>$commerce, 'sales'=>$sales]]);
        }
        else
            return response()->json(['statusCode' => 401,'message' => "Error no esta disponible"]);

    }

    public function orderPaidDelivery(Request $request)
    {
        $delivery = $request->user();
        $paids = Paid::where('codeUrl', $request->codeUrl)
                ->whereNull("idDelivery")->first();

        if($paids && ($paids->idDelivery == null || $paids->idDelivery == $delivery->id)){
            $paids->idDelivery = $delivery->id;
            $paids->statusDelivery = 2;
            $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
            $commerce = Commerce::whereId($paids->commerce_id)->first();
            $paids->save();
            return response()->json(['statusCode' => 201,'data' =>['paid'=>$paids, 'commerce'=>$commerce, 'sales'=>$sales]]);
        }
        else
            return response()->json(['statusCode' => 401,'message' => "Este orden ya no se encuentra disponible"]);

    }

    public function changeStatus(Request $request)
    {
        $paids = Paid::where('codeUrl', $request->codeUrl)->first();
        $paids->statusShipping = $request->statusShipping;
        $phone = '+'.app('App\Http\Controllers\Controller')->validateNum($paids->numberShipping);
        if($request->statusShipping == 1){
            $message = "Delivery Ctpaga informa que los productos de código de compra: ".$paids->codeUrl." fue retirado desde la tienda llegará al destino no mas tardar de 1 hora.";
            (new User)->forceFill([
                'email' => $paids->email,
            ])->notify(
                new ShippingNotification($message)
            );

        }elseif($request->statusShipping == 2){
            $message = "Delivery Ctpaga informa que los productos de código de compra ".$paids->codeUrl." fue entregado a su destino.";
            $userCommerce = User::whereId($paids->user_id)->first();
    
            (new User)->forceFill([
                'email' => $userCommerce->email,
            ])->notify(
                new ShippingNotification($message)
            );

        }

        (new User)->forceFill([
            'email' => $paids->email,
        ])->notify(
            new ShippingNotification($message)
        );

        /* $url = 'mensajesms.com.ve/sms2/API/api.php?cel='.$phone.'&men='.str_replace(" ","%20",$message).'&u=demo&t=D3M04P1';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            "Content-Type: application/json",
        ));
        
        $resultSms = json_decode(curl_exec($ch), true);
        curl_close($ch); */

        $sms = AWS::createClient('sns');
        $sms->publish([
            'Message' => $message,
            'PhoneNumber' => $phone,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType'  => [
                    'DataType'    => 'String',
                    'StringValue' => 'Transactional',
                ]
            ],
        ]); 

        $paids->save();

        return response()->json(['statusCode' => 201,'data' =>['paid'=>$paids]]);

    }
}
