<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\Paid;
use App\User;
use App\Settings;
use App\Events\StatusDelivery;
use App\Events\AlarmUrgent;
use App\Events\NewNotification;
use App\Notifications\NotificationAdmin;

class DeliveryController extends Controller
{

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

        $url = "https://fcm.googleapis.com/fcm/send";
        $token = "fbSmxHKgTjmIBOIf7VG-m4:APA91bEyJON1P1VMmPu99H0D0PkfsANSHK07IzfBVFJ1G3ENGV3JH_-AtqgLM2QZHlxuCUzR83eWyJsi5Ro-9lCLGwgUXqvBemndpRXg0-OlxvfQaNz8hZ8y3ZAy8mPlXbbUvFg6-Afr";
        $serverKey = 'AAAAayyhqZo:APA91bFw9NTsl9lh5d6C_IQcd7mNz-YvPOlEIPstg88ttpC7wlesWLFX3Uh0aoWMkbcOVG_M7mfboZPlxKCiLy-lrQPEtsBL-7q7PA9LvPnul6UsaN5dB1IqjaJx8ux94eHu26m-bmC0';
        $title = "Notification title";
        $body = "Hello I am from Your php server";
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
