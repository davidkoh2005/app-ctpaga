<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\Paid;
use App\Events\StatusDelivery;
use App\Events\AlarmUrgent;
use Carbon\Carbon;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Illuminate\Support\Facades\Config;

class DeliveryController extends Controller
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

    public function update(Request $request)
    {   
        $delivery = $request->user();
        Delivery::whereId($delivery->id)->update($request->all());
        
        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function showPaidAll(Request $request)
    {   
        $delivery = $request->user();

        if(!$delivery->status){
            $request->user()->token()->revoke(); 
            return response()->json(['statusCode' => 401,'message' => "Unauthorized"]);
        }

        $paids =Paid::join('commerces', 'commerces.id', '=', 'paids.commerce_id')
                    ->leftJoin('pictures', 'pictures.commerce_id', '=', 'paids.commerce_id')
                    ->where('paids.statusDelivery',1)
                    ->whereNull('paids.idDelivery')
                    ->where('pictures.description','Profile')
                    ->select('paids.id', 'paids.codeUrl', 'commerces.name', 'commerces.address', 'pictures.url')
                    ->get();
    
        return response()->json([
            'statusCode' => 201,
            'data' => $paids
        ]);
    }

    public function test()
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amountPaypal = new Amount();
        $amountPaypal->setTotal(2);
        $amountPaypal->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amountPaypal);
        $transaction->setDescription('Compra a través de ctpaga');

        $callbackUrl = url('/pagar/estadoPaypal/');

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

        /* $userUrl ="test";
        $codeUrl = "test";
        $amount = 5.5;
        return view('gatewayBTC.example_basic', compact('userUrl', 'codeUrl', 'amount'));
        return view('test'); */
        //dd(Carbon::parse("13-02-2021 03:45 PM")->format('Y-m-d H:m:s'));
        /* $message = "Delivery Ctpaga informa que los productos de código de compra: ".$paids->codeUrl." fue retirado desde la tienda llegará al destino no mas tardar de 1 hora.";
        (new User)->forceFill([
            'email' => 'angelgoitia1995@gmail.com',
        ])->notify(
            new ShippingNotification($message)
        ); */

        /* $phone = "04129851722";
        $message = "los productos de código de compra: test123 fue retirado desde la tienda llegará al destino no mas tardar de 1 hora.";
        
        $url = 'mensajesms.com.ve/sms2/API/api.php?cel='.$phone.'&men='.str_replace(' ','_',$message).'&u=demo&t=DEM04P2';
        //$ch = curl_init($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        $resultSms = json_decode(curl_exec($ch), true);
        curl_close($ch);

        dd($resultSms);  */ 

        // $success = event(new AlarmUrgent());
        //$success = event(new StatusDelivery());
        // dd($success); 
    }

    
}
