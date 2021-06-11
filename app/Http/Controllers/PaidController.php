<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Notifications\PaymentVerification;
use App\Notifications\PaymentConfirm;
use App\Notifications\PostPurchase;
use App\Notifications\ShippingNotification;
use App\Notifications\NotificationCommerce;
use App\Notifications\NotificationAdmin;
use App\Notifications\DeliveryProductClient;
use App\Notifications\DeliveryProductCommerce;
use App\Notifications\DeliveryProductClientInitial;
use App\Notifications\DeliveryProductCommerceInitial;
use App\Notifications\RetirementProductClient;
use App\Notifications\RetirementProductCommerce;
use Carbon\Carbon;
use App\Cash;
use App\User;
use App\Sale;
use App\Paid;
use App\Rate;
use App\Picture;
use App\Commerce;
use App\Product;
use App\Service;
use App\Balance;
use App\Shipping;
use App\Settings;
use App\Delivery;
use App\DeliveryCost;
use App\PaymentsBs;
use App\PaymentsZelle;
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

                $sale->statusSale = 0;
                $sale->save();

            }

            $commerce = Commerce::where('userUrl',$request->userUrl)->first();
            $user = User::where('id',$commerce->user_id)->first();

            if(strlen($request->priceShipping)>0){
                $priceShipping = $request->priceShipping;
            }else{
                $priceShipping = "0";
            }

            $paid = Paid::create([
                "user_id"               => $user->id,
                "commerce_id"           => $commerce->id,
                "codeUrl"               => $codeUrl,
                "nameClient"            => $request->nameClient,
                "total"                 => $amount,
                "coin"                  => $request->coinClient,
                "email"                 => $request->email,
                "nameShipping"          => $request->name,
                "numberShipping"        => $request->number,
                "state"                 => $request->selectState,
                "municipalities"        => $request->selectMunicipalities,
                "addressShipping"       => $request->address,
                "detailsShipping"       => $request->details,
                "selectShipping"        => $request->selectShipping,
                "priceShipping"         => str_replace(",",".",str_replace(".","",$priceShipping)),
                "percentage"            => $request->percentageSelect,
                "nameCompanyPayments"   => "Pago en Efectivo",
                "date"                  => Carbon::now(),
                "statusPayment"         => 2,
                "timeDelivery"          => Carbon::now()->addMinutes(10),
            ]);

            $paid->statusDelivery = 1;
            $paid->save();

            $userUrl = $request->userUrl;

            if($request->coinClient == 0)
                $messageNotification = "Recibiste un pago de $ ".$amount;
            else
                $messageNotification = "Recibiste un pago de BS ".$amount;

            $this->sendFCM($user->token_fcm, $messageNotification);

            (new User)->forceFill([
                'email' => $request->email,
            ])->notify(
                new PaymentConfirm($request->nameClient, $codeUrl)
            );

            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new NotificationCommerce($commerce, $codeUrl, 0)
            );

            $emailsGet = Settings::where('name','Email Delivery')->first();

            if($emailsGet){
                $emails = json_decode($emailsGet->value);
                $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
                foreach($emails as $email){
                    (new User)->forceFill([
                        'email' => $email,
                    ])->notify(
                        new NotificationAdmin($messageAdmin)
                    );
                } 
            }

            $status = true;
            return view('result', compact('userUrl', 'status'));
        }elseif($request->coinClient == 0 && $request->payment == "CARD"){

            $url = env('SQUARE_API');
            $ch = curl_init($url);

            $jsonData = array(
                'amount_money' => array(
                    'amount' =>  floatval(str_replace(".","",floatval($amount)*100)),
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
                    
                    $paid = Paid::create([
                        "user_id"               => $user->id,
                        "commerce_id"           => $commerce->id,
                        "codeUrl"               => $codeUrl,
                        "nameClient"            => $request->nameClient,
                        "total"                 => $amount,
                        "coin"                  => $request->coinClient,
                        "email"                 => $request->email,
                        "nameShipping"          => $request->name,
                        "numberShipping"        => $request->number,
                        "state"                 => $request->selectState,
                        "municipalities"        => $request->selectMunicipalities,
                        "addressShipping"       => $request->address,
                        "detailsShipping"       => $request->details,
                        "selectShipping"        => $request->selectShipping,
                        "priceShipping"         => str_replace(",",".",str_replace(".","",$priceShipping)),
                        "percentage"            => $request->percentageSelect,
                        "nameCompanyPayments"   => "Square",
                        "date"                  => Carbon::now(),
                        "statusPayment"         => 2,
                        "refPayment"            => $resultTransaction['payment']['order_id'],
                        "timeDelivery"          => Carbon::now()->addMinutes(10),
                    ]);

                    $paid->statusDelivery = 1;
                    $paid->save();

                    $userUrl = $request->userUrl;

                    if($request->coinClient == 0)
                        $messageNotification = "Recibiste un pago de $ ".$amount;
                    else
                        $messageNotification = "Recibiste un pago de BS ".$amount;

                    $this->sendFCM($user->token_fcm, $messageNotification);

                    (new User)->forceFill([
                        'email' => $request->email,
                    ])->notify(
                        new PaymentConfirm($request->nameClient, $codeUrl)
                    );
                    

                    (new User)->forceFill([
                        'email' => $user->email,
                    ])->notify(
                        new NotificationCommerce($commerce, $codeUrl, 0)
                    );

                    $emailsGet = Settings::where('name','Email Delivery')->first();

                    if($emailsGet){
                        $emails = json_decode($emailsGet->value);
                        $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
                        foreach($emails as $email){
                            (new User)->forceFill([
                                'email' => $email,
                            ])->notify(
                                new NotificationAdmin($messageAdmin)
                            );
                        } 
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

            $paid = Paid::create([
                "user_id"               => $user->id,
                "commerce_id"           => $commerce->id,
                "codeUrl"               => $codeUrl,
                "nameClient"            => $request->nameClient,
                "total"                 => $amount,
                "coin"                  => $request->coinClient,
                "email"                 => $request->email,
                "nameShipping"          => $request->name,
                "numberShipping"        => $request->number,
                "state"                 => $request->selectState,
                "municipalities"        => $request->selectMunicipalities,
                "addressShipping"       => $request->address,
                "detailsShipping"       => $request->details,
                "selectShipping"        => $request->selectShipping,
                "priceShipping"         => str_replace(",",".",str_replace(".","",$priceShipping)),
                "percentage"            => $request->percentageSelect,
                "nameCompanyPayments"   => "PayPal",
                "date"                  => Carbon::now(),
                "statusPayment"         => 1,
            ]);

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amountPaypal = new Amount();
            $amountPaypal->setTotal($amount);
            $amountPaypal->setCurrency('USD');

            $transaction = new Transaction();
            $transaction->setAmount($amountPaypal);
            $transaction->setDescription('Compra a través de Ctpaga');

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
        }elseif($request->coinClient == 0 && $request->payment == "BITCOIN" || $request->payment == "ZELLE"  ){
            
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

            $paid = Paid::create([
                "user_id"               => $user->id,
                "commerce_id"           => $commerce->id,
                "codeUrl"               => $codeUrl,
                "nameClient"            => $request->nameClient,
                "total"                 => $amount,
                "coin"                  => $request->coinClient,
                "email"                 => $request->email,
                "nameShipping"          => $request->name,
                "numberShipping"        => $request->number,
                "state"                 => $request->selectState,
                "municipalities"        => $request->selectMunicipalities,
                "addressShipping"       => $request->address,
                "detailsShipping"       => $request->details,
                "selectShipping"        => $request->selectShipping,
                "priceShipping"         => str_replace(",",".",str_replace(".","",$priceShipping)),
                "percentage"            => $request->percentageSelect,
                "nameCompanyPayments"   => $request->payment == "BITCOIN"? "Bitcoin" : "Zelle",
                "date"                  => Carbon::now(),
                "statusPayment"         => 1,
            ]);

            (new User)->forceFill([
                'email' => $request->email,
            ])->notify(
                new PaymentVerification($request->nameClient, $codeUrl)
            );

            if($request->payment == "BITCOIN"){
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
            }else{
                PaymentsZelle::create([
                    "paid_id"       => $paid->id,
                    "nameAccount"   => $request->nameZelle,
                    "idConfirm"     => $request->idConfirmZelle,
                    "date_created"  => Carbon::now(),
                ]);
            }

            
            $emailsGet = Settings::where('name','Email Transaccion')->first();

            if($emailsGet){
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

            if($request->payment == "BITCOIN"){
                return redirect()->away($chargeObj->hosted_url);
            }else{
                $status = false;
                return view('result', compact('userUrl', 'status'));
            }
        
        }elseif($request->coinClient == 1){
            
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
            
            $paid = Paid::create([
                "user_id"               => $user->id,
                "commerce_id"           => $commerce->id,
                "codeUrl"               => $codeUrl,
                "nameClient"            => $request->nameClient,
                "total"                 => $amount,
                "coin"                  => $request->coinClient,
                "email"                 => $request->email,
                "nameShipping"          => $request->name,
                "numberShipping"        => $request->number,
                "state"                 => $request->selectState,
                "municipalities"        => $request->selectMunicipalities,
                "addressShipping"       => $request->address,
                "detailsShipping"       => $request->details,
                "selectShipping"        => $request->selectShipping,
                "priceShipping"         => str_replace(",",".",str_replace(".","",$priceShipping)),
                "percentage"            => $request->percentageSelect,
                "nameCompanyPayments"   => $request->payment == "TRANSFERENCIA"? "Transferencia" : "Pago Móvil",
                "statusPayment"         => 1,
                "date"                  => Carbon::now(),
            ]);

            $paid->save();

            foreach($request->amount as $key => $transaction){
                PaymentsBs::create([
                    "paid_id"       => $paid->id,
                    "type"          => $request->payment == "TRANSFERENCIA"? 0 : 1,
                    "bank"          => $request->bank[$key],
                    "transaction"   => $request->numTransfers[$key],
                    "amount"         => $request->amount[$key],
                    "date"          => $request->date[$key],
                    "date_created"  => Carbon::now(),
                ]);

            }

            $userUrl = $request->userUrl;

            if($request->coinClient == 0)
                $messageNotification = "Recibiste un pago de $ ".$amount;
            else
                $messageNotification = "Recibiste un pago de BS ".$amount;

            $this->sendFCM($user->token_fcm, $messageNotification);

            (new User)->forceFill([
                'email' => $request->email,
            ])->notify(
                new PaymentVerification($request->nameClient, $codeUrl)
            );

            $emailsGet = Settings::where('name','Email Delivery')->first();

            if($emailsGet){
                $emails = json_decode($emailsGet->value);
                $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
                foreach($emails as $email){
                    (new User)->forceFill([
                        'email' => $email,
                    ])->notify(
                        new NotificationAdmin($messageAdmin)
                    );
                } 
            }

            $status = true;
            return view('result', compact('userUrl', 'status'));
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
        
        $commerce = Commerce::where('userUrl',$userUrl)->first();
        $user = User::where('id',$commerce->user_id)->first();

        if ($result->getState() === 'approved') {
            $statusDelivery = 1;
            $timeDelivery = Carbon::now()->addMinutes(10);
            $resultPayment=2;
        }else{
            $statusDelivery = 0;
            $timeDelivery = NULL;
            $resultPayment=1;
        }

        $paid = Paid::where('codeUrl',$codeUrl)->first();
        $paid->statusPayment = $resultPayment;
        $paid->refPayment = $result->getId();
        $paid->timeDelivery = $timeDelivery;
        $paid->statusDelivery = $statusDelivery;
        $paid->save();

        if ($result->getState() === 'approved') {

            (new User)->forceFill([
                'email' => $requestForm['email'],
            ])->notify(
                new PaymentConfirm($requestForm['nameClient'], $codeUrl)
            );

            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new NotificationCommerce($commerce, $codeUrl, 0)
            );

            $emailsGet = Settings::where('name','Email Delivery')->first();

            if($emailsGet){
                $emails = json_decode($emailsGet->value);
                $messageAdmin = "se ha realizado un nuevo pedido con código de compra: ".$codeUrl;
                foreach($emails as $email){
                    (new User)->forceFill([
                        'email' => $email,
                    ])->notify(
                        new NotificationAdmin($messageAdmin)
                    );
                } 
            }

    
        }else{
            (new User)->forceFill([
                'email' => $requestForm['email'],
            ])->notify(
                new PaymentVerification($requestForm['nameClient'], $codeUrl)
            );

            (new User)->forceFill([
                'email' => $user->email,
            ])->notify(
                new NotificationCommerce($commerce, $codeUrl, 1)
            );

            $emailsGet = Settings::where('name','Email Transaccion')->first();

            if($emailsGet){
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
        $sheduleInitial = Carbon::createFromFormat('g:i A', '12:00 AM');
        $sheduleFinal = Carbon::createFromFormat('g:i A', '11:59 PM');
        $delivery = $request->user();
        
        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        $scheduleInitialGet = Settings::where("name", "Horario Inicial")->first(); 
        $scheduleFinalGet = Settings::where("name", "Horario Final")->first();

        $now = Carbon::now();

        if($scheduleInitialGet && $scheduleFinalGet){
            $sheduleInitial = Carbon::createFromFormat('g:i A', $scheduleInitialGet->value);
            $sheduleFinal = Carbon::createFromFormat('g:i A', $scheduleFinalGet->value);
        }

        if($sheduleInitial->isBefore($now) && $sheduleFinal->isAfter($now)){
            $paid = Paid::where('codeUrl', $request->codeUrl)
                ->where("idDelivery",$delivery->id)->first();

            if($paid){
                $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
                $commerce = Commerce::whereId($paid->commerce_id)->first();
                return response()->json(['statusCode' => 201,'data' =>['paid'=>$paid, 'commerce'=>$commerce, 'sales'=>$sales]]);
            }
            else
                return response()->json(['statusCode' => 400,'message' => "Error no esta disponible"]);            
        }else        
            return response()->json(['statusCode' => 400,'message' => "Error de Servidor: El horario es de ".$scheduleInitialGet->value." hasta las ".$scheduleFinalGet->value]);


    }

    public function orderPaidDelivery(Request $request)
    {
        $sheduleInitial = Carbon::createFromFormat('g:i A', '12:00 AM');
        $sheduleFinal = Carbon::createFromFormat('g:i A', '11:59 PM');
        $delivery = $request->user();

        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        if($delivery->statusAvailability && $delivery->codeUrlPaid == null){

            $paid = Paid::where('codeUrl', $request->codeUrl)
                    ->whereNull("idDelivery")->first();

            $scheduleInitialGet = Settings::where("name", "Horario Inicial")->first(); 
            $scheduleFinalGet = Settings::where("name", "Horario Final")->first();

            $now = Carbon::now();

            if($scheduleInitialGet && $scheduleFinalGet){
                $sheduleInitial = Carbon::createFromFormat('g:i A', $scheduleInitialGet->value);
                $sheduleFinal = Carbon::createFromFormat('g:i A', $scheduleFinalGet->value);
            }

            if($sheduleInitial->isBefore($now)&& $sheduleFinal->isAfter($now))
                if($paid && ($paid->idDelivery == null || $paid->idDelivery == $delivery->id)){
                    $paid->idDelivery = $delivery->id;
                    $paid->statusDelivery = 2;
                    $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
                    $commerce = Commerce::whereId($paid->commerce_id)->first();
                    $userCommerce = User::whereId($paid->user_id)->first();
                    $paid->save();
                    
                    $listCodeUrl = array();
                    array_push($listCodeUrl,$request->codeUrl);
                    $delivery->codeUrlPaid = json_encode($listCodeUrl);
                    $delivery->statusAvailability = 0;
                    $delivery->save();

                    $phone = '+'.app('App\Http\Controllers\Controller')->validateNum($paid->numberShipping);
                    $phoneCommerce = '+'.app('App\Http\Controllers\Controller')->validateNum($commerce->phone);
                    $fecha = Carbon::now()->format("d/m/Y");
                    $urlDelivery = url('/delivery/'.$delivery->idUrl);

                    $message = "Ctpaga Delivery le informa que ha realizado un pedido con el Nro ".$paid->codeUrl." con fecha de ".$fecha.", el cual será despachado en aproximadamente 1 hora. Ver informacion de delivery: ".$urlDelivery."";
                    $messageAdmin = " el delivery ".$delivery->name." tomo el pedido ".$paid->codeUrl." con fecha de ".$fecha.", el cual será despachado en aproximadamente 1 hora.";

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

                    $sms = AWS::createClient('sns');
                    $sms->publish([
                        'Message' => $message,
                        'PhoneNumber' => $phoneCommerce,
                        'MessageAttributes' => [
                            'AWS.SNS.SMS.SMSType'  => [
                                'DataType'    => 'String',
                                'StringValue' => 'Transactional',
                            ]
                        ],
                    ]); 

                    (new User)->forceFill([
                        'email' => $paid->email,
                    ])->notify(
                        new DeliveryProductClientInitial($commerce, $paid, $sales, $delivery)
                    );  
            
                    (new User)->forceFill([
                        'email' => $userCommerce->email,
                    ])->notify(
                        new DeliveryProductCommerceInitial($commerce, $paid, $sales, $delivery)
                    );

                    $emailsGet = Settings::where('name','Email Estado Pedido')->first();

                    if($emailsGet){
                        $emails = json_decode($emailsGet->value);
                        foreach($emails as $email){
                            (new User)->forceFill([
                                'email' => $email,
                            ])->notify(
                                new NotificationAdmin($messageAdmin)
                            );
                        } 
                    }

                    return response()->json(['statusCode' => 201,'data' =>['paid'=>$paid, 'commerce'=>$commerce, 'sales'=>$sales]]);
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
        $paid = Paid::where('codeUrl', $request->codeUrl)->first();
        $paid->statusShipping = intval($request->statusShipping);
        $paid->save();
        $phone = '+'.app('App\Http\Controllers\Controller')->validateNum($paid->numberShipping);
        
        $commerce = Commerce::whereId($paid->commerce_id)->first();
        $userCommerce = User::whereId($paid->user_id)->first();
        $sales = Sale::where('codeUrl', $request->codeUrl)->get();
        
        if(intval($request->statusShipping) == 1){
            $message = "Ctpaga Delivery le informa que el pedido ".$paid->codeUrl." fue retirado de la tienda ".$commerce->name." y será entregado a la brevedad posible. ";

            (new User)->forceFill([
                'email' => $paid->email,
            ])->notify(
                new RetirementProductClient($commerce, $paid, $sales)
            );

            (new User)->forceFill([
                'email' => $userCommerce->email,
            ])->notify(
                new RetirementProductCommerce($commerce, $paid, $sales)
            );

            $messageAdmin = " el delivery ".$delivery->name." retiro el producto del pedido ".$paid->codeUrl." en la tienda ".$commerce->name.".";

        }elseif(intval($request->statusShipping) == 2){
            $delivery = $request->user();

            $array = json_decode($delivery->codeUrlPaid);

            if (($key = array_search($request->codeUrl, $array)) !== false) {
                unset($array[$key]);
                $listCode = array_values($array);
            }

            if(count($array) == 0){
                $delivery->codeUrlPaid = null;
                $delivery->statusAvailability = 1;
            }
            else{
                $delivery->codeUrlPaid = json_encode($listCode);
                $delivery->statusAvailability = 0;
            }

            $delivery->save();

            $balance = Balance::firstOrNew([
                'user_id'       => $paid->user_id,
                "commerce_id"   => $paid->commerce_id,
                "coin"          => $paid->coin,
            ]);

            $costDelivery = 0;


            $listCost = DeliveryCost::select('cost')->where('state', $paid->state)
                                    ->where('municipalities', $paid->municipalities)
                                    ->first();

            if($listCost)
                $costDelivery = $listCost->cost;
            
            if($paid->coin == 0 && $paid->nameCompanyPayments != "PayPal"){
                $balance->total += floatval($paid->total)-(floatval($paid->total)*0.05+0.35)-$costDelivery;
            }else if($paid->coin == 0 && $paid->nameCompanyPayments == "PayPal"){
                $balance->total += floatval($paid->total)-(floatval($paid->total)*0.1+0.35)-$costDelivery;
            }else{
                $rateAdmin = Rate::where("roleRate",0)->orderBy("created_at","desc")->first();
        
                $balance->total += floatval($paid->total)-(floatval($paid->total)*0.05+(0.35*floatval($rateAdmin->rate)))-($costDelivery*floatval($rateAdmin->rate));
            }

            $balance->save();

            foreach ($sales as $sale)
            {
                $sale->statusSale = 1;
                $sale->save();
            }

            if($paid->nameCompanyPayments == "Pago en Efectivo"){
                Cash::create([
                    'delivery_id'  => $delivery->id,
                    "paid_id"      => $paid->id,
                ]);
            }

            $message = "Ctpaga Delivery le informa que el pedido ".$paid->codeUrl." fue entregado satisfactoriamente a ".$paid->nameShipping;
    
            (new User)->forceFill([
                'email' => $paid->email,
            ])->notify(
                new DeliveryProductClient($commerce, $paid, $sales)
            );  
    
            (new User)->forceFill([
                'email' => $userCommerce->email,
            ])->notify(
                new DeliveryProductCommerce($commerce, $paid, $sales)
            );

            $messageAdmin = " el delivery ".$delivery->name." entregó el pedido ".$paid->codeUrl." correctamente a ".$paid->nameShipping;

        }


        $emailsGet = Settings::where('name','Email Estado Pedido')->first();

        if($emailsGet){
            $emails = json_decode($emailsGet->value);
            foreach($emails as $email){
                (new User)->forceFill([
                    'email' => $email,
                ])->notify(
                    new NotificationAdmin($messageAdmin)
                );
            } 
        }

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

        return response()->json(['statusCode' => 201,'data' =>['paid'=>$paid]]);

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

    public function billing($codeUrl)
    {
        $paid = Paid::where('codeUrl',$codeUrl)->with('commerce')->first(); 
        $pictureUser = Picture::where('commerce_id',$paid->commerce->id)
                                ->where('description','Profile')
                                ->where('type',0)->first();
        $sales = Sale::where('codeUrl',$codeUrl)->get();
        $today = Carbon::now()->format('d/m/Y g:i A');
        
        $pdf = \PDF::loadView('report.billing', compact('paid', 'sales', 'today','pictureUser'));
        return $pdf->download('ctpaga_pedido.pdf');
    }
}
