<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\Events\StatusDelivery;
use App\Events\AlarmUrgent;

class DeliveryController extends Controller
{
    public function update(Request $request)
    {   
        $delivery = $request->user();
        Delivery::whereId($delivery->id)->update($request->all());
        

        if(isset($request->status))
            $success = event(new StatusDelivery());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function test(){
        /* $phone = "04129851722";
        $message = "los productos de código de compra: test123 fue retirado desde la tienda llegará al destino no mas tardar de 1 hora.";
        
        $url = 'mensajesms.com.ve/sms2/API/api.php?cel=04129851722&men=test&u=demo&t=D3M04P1';
        //$ch = curl_init($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        $resultSms = json_decode(curl_exec($ch), true);
        curl_close($ch);

        dd($resultSms); */

        $success = event(new AlarmUrgent());
        // /* $success = event(new StatusDelivery()); */
        dd($success);
    }
}
