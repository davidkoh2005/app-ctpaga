<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Notifications\PostPurchase;
use App\Notifications\ShippingNotification;
use App\Notifications\NotificationCommerce;
use App\Notifications\NotificationAdmin;
use Carbon\Carbon;
use App\User;
use App\Sale;
use App\Paid;
use App\Rate;
use App\Commerce;
use App\Product;
use App\Service;
use App\Balance;
use App\Shipping;
use App\Settings;
use App\Delivery;
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
        if($request->coinClient == 0 && $request->payment == "EFECTIVO"){
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
                "nameCompanyPayments"   => "Pago en Efectivo",
                "date"                  => Carbon::now(),
                "statusPayment"         => 2,
            ]);

            $userUrl = $request->userUrl;

            if($request->coinClient == 0)
                $messageNotification = "Recibiste un pago de $ ".$amount;
            else
                $messageNotification = "Recibiste un pago de BS ".$amount;

            $this->sendFCM($user->token_fcm, $messageNotification);

            (new User)->forceFill([
                'email' => $request->email,
            ])->notify(
                new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
            );

            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new NotificationCommerce($codeUrl.' ha realizado el pago correctamente!')
            );

            $emailsGet = Settings::where('name','Email Delivery')->first();

            $emails = json_decode($emailsGet->value);
            $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
            foreach($emails as $email){
                (new User)->forceFill([
                    'email' => $email,
                ])->notify(
                    new NotificationAdmin($messageAdmin)
                );
            }

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

                    $userUrl = $request->userUrl;

                    if($request->coinClient == 0)
                        $messageNotification = "Recibiste un pago de $ ".$amount;
                    else
                        $messageNotification = "Recibiste un pago de BS ".$amount;

                    $this->sendFCM($user->token_fcm, $messageNotification);

                    (new User)->forceFill([
                        'email' => $request->email,
                    ])->notify(
                        new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
                    );

                    (new User)->forceFill([
                        'email' => $user->email,
                    ])->notify(
                        new NotificationCommerce($codeUrl.' ha realizado el pago correctamente!')
                    );

                    $emailsGet = Settings::where('name','Email Delivery')->first();

                    $emails = json_decode($emailsGet->value);
                    $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
                    foreach($emails as $email){
                        (new User)->forceFill([
                            'email' => $email,
                        ])->notify(
                            new NotificationAdmin($messageAdmin)
                        );
                    }

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

            
            $emailsGet = Settings::where('name','Email Transaccion')->first();

            $emails = json_decode($emailsGet->value);
            $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl." ,el pago esta en proceso de verificación.";
            foreach($emails as $email){
                (new User)->forceFill([
                    'email' => $email,
                ])->notify(
                    new NotificationAdmin($messageAdmin)
                );
            }

            
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

                $userUrl = $request->userUrl;

                if($request->coinClient == 0)
                    $messageNotification = "Recibiste un pago de $ ".$amount;
                else
                    $messageNotification = "Recibiste un pago de BS ".$amount;

                $this->sendFCM($user->token_fcm, $messageNotification);

                (new User)->forceFill([
                    'email' => $request->email,
                ])->notify(
                    new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
                );

                (new User)->forceFill([
                    'email' => $user->email,
                ])->notify(
                    new NotificationCommerce($codeUrl.' ha realizado el pago correctamente!')
                );

                $emailsGet = Settings::where('name','Email Delivery')->first();

                $emails = json_decode($emailsGet->value);
                $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
                foreach($emails as $email){
                    (new User)->forceFill([
                        'email' => $email,
                    ])->notify(
                        new NotificationAdmin($messageAdmin)
                    );
                }

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

        if ($result->getState() === 'approved') {
            $resultPayment=2;
        }else{
            $resultPayment=1;
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
            "statusPayment"         => $resultPayment,
            "refPayment"            => $result->getId(),
        ]);

        if ($result->getState() === 'approved') {

            (new User)->forceFill([
                'email' => $requestForm['email'],
            ])->notify(
                new PostPurchase($message, $userUrl, $commerce->name, $codeUrl)
            );

            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new NotificationCommerce($codeUrl.' ha realizado el pago correctamente!')
            );

            $emailsGet = Settings::where('name','Email Delivery')->first();

            $emails = json_decode($emailsGet->value);
            $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
            foreach($emails as $email){
                (new User)->forceFill([
                    'email' => $email,
                ])->notify(
                    new NotificationAdmin($messageAdmin)
                );
            }
    
        }else{
            (new User)->forceFill([
                'email' => $requestForm['email'],
            ])->notify(
                new PaymentVerification($message, $userUrl, $commerce->name, $codeUrl)
            );

            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new NotificationCommerce($codeUrl.' el pago esta en proceso de verificación!')
            );

            $emailsGet = Settings::where('name','Email Transaccion')->first();

            $emails = json_decode($emailsGet->value);
            $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl." ,el pago esta en proceso de verificación.";
            foreach($emails as $email){
                (new User)->forceFill([
                    'email' => $email,
                ])->notify(
                    new NotificationAdmin($messageAdmin)
                );
            }
    
        }

        if($requestForm['coinClient'] == 0)
            $messageNotification = "Recibiste un pago de $ ".$amount;
        else
            $messageNotification = "Recibiste un pago de BS ".$amount;

        $this->sendFCM($user->token_fcm, $messageNotification);

        $status = true;
        return view('result', compact('userUrl', 'status'));

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
        
        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        $scheduleInitialGet = Settings::where("name", "Horario Inicial")->first(); 
        $scheduleFinalGet = Settings::where("name", "Horario Final")->first();

        $now = Carbon::now();
        $sheduleInitial = Carbon::createFromFormat('g:i A', $scheduleInitialGet->value);
        $sheduleFinal = Carbon::createFromFormat('g:i A', $scheduleFinalGet->value);

        if($sheduleInitial->isBefore($now) && $sheduleFinal->isAfter($now)){
            $paids = Paid::where('codeUrl', $request->codeUrl)
                ->where("idDelivery",$delivery->id)->first();

            if($paids){
                $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
                $commerce = Commerce::whereId($paids->commerce_id)->first();
                return response()->json(['statusCode' => 201,'data' =>['paid'=>$paids, 'commerce'=>$commerce, 'sales'=>$sales]]);
            }
            else
                return response()->json(['statusCode' => 400,'message' => "Error no esta disponible"]);            
        }else        
            return response()->json(['statusCode' => 400,'message' => "Error de Servidor: El horario es de ".$scheduleInitialGet->value." hasta las ".$scheduleFinalGet->value]);


    }

    public function orderPaidDelivery(Request $request)
    {
        $delivery = $request->user();

        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        if($delivery->statusAvailability && $delivery->codeUrlPaid == null){

            $paids = Paid::where('codeUrl', $request->codeUrl)
                    ->whereNull("idDelivery")->first();

            $scheduleInitialGet = Settings::where("name", "Horario Inicial")->first(); 
            $scheduleFinalGet = Settings::where("name", "Horario Final")->first();

            $now = Carbon::now();
            $sheduleInitial = Carbon::createFromFormat('g:i A', $scheduleInitialGet->value);
            $sheduleFinal = Carbon::createFromFormat('g:i A', $scheduleFinalGet->value);

            if($sheduleInitial->isBefore($now)&& $sheduleFinal->isAfter($now))
                if($paids && ($paids->idDelivery == null || $paids->idDelivery == $delivery->id)){
                    $paids->idDelivery = $delivery->id;
                    $paids->statusDelivery = 2;
                    $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
                    $commerce = Commerce::whereId($paids->commerce_id)->first();
                    $paids->save();

                    $delivery->codeUrlPaid = $request->codeUrl;
                    $delivery->statusAvailability = 0;
                    $delivery->save();

                    return response()->json(['statusCode' => 201,'data' =>['paid'=>$paids, 'commerce'=>$commerce, 'sales'=>$sales]]);
                }
                else
                    return response()->json(['statusCode' => 400,'message' => "Este orden ya no se encuentra disponible"]);
            else        
                return response()->json(['statusCode' => 400,'message' => "Error de Servidor: El horario es de ".$scheduleInitialGet->value." hasta las ".$scheduleFinalGet->value]);
        
        }else
            return response()->json(['statusCode' => 400,'message' => "Tiene una orden pendiente"]);

    }

    public function changeStatus(Request $request)
    {
        $delivery = $request->user();
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

            $messageAdmin = " el delivery ".$delivery->name." retiro los productos de código de compra: ".$paids->codeUrl;

        }elseif($request->statusShipping == 2){
            $delivery = $request->user();
            $delivery->codeUrlPaid = null;
            $delivery->save();

            $balance = Balance::firstOrNew([
                'user_id'       => $paids->user_id,
                "commerce_id"   => $paids->commerce_id,
                "coin"          => $paids->coin,
            ]);

            if($paids->coin == 0){
                $balance->total += floatval($paids->total)-(floatval($paids->total)*0.05+0.35);
                $balance->save();
            }else{
                $rateAdmin = Rate::where("roleRate",0)->orderBy("created_at","desc")->first();
        
                $balance->total += floatval($$paids->total)-(floatval($$paids->total)*0.05+(0.35*floatval($rateAdmin->rate)));
                $balance->save();
            }

            $message = "Delivery Ctpaga informa que los productos de código de compra ".$paids->codeUrl." fue entregado a su destino.";
            $userCommerce = User::whereId($paids->user_id)->first();
    
            (new User)->forceFill([
                'email' => $userCommerce->email,
            ])->notify(
                new ShippingNotification($message)
            );

            $messageAdmin = " el delivery ".$delivery->name." entrego los productos de código de compra: ".$paids->codeUrl." a su destino.";

        }

        (new User)->forceFill([
            'email' => $paids->email,
        ])->notify(
            new ShippingNotification($message)
        );

        $emailsGet = Settings::where('name','Email Estado Pedido')->first();

        $emails = json_decode($emailsGet->value);
        
        foreach($emails as $email){
            (new User)->forceFill([
                'email' => $email,
            ])->notify(
                new NotificationAdmin($messageAdmin)
            );
        }

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

    public function sendFCM($token,$message)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $token = $token;
        $serverKey = env('SERVER_KEY_FCM');
        $title = "Nuevo Pago Recibido";
        $body = $message;
        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Send the request
        $response = curl_exec($ch);

        curl_close($ch);
    }
}
