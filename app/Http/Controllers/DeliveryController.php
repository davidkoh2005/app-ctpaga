<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\Events\StatusDelivery;

class DeliveryController extends Controller
{
    public function update(Request $request)
    {   
        $delivery = $request->user();
        Delivery::whereId($delivery->id)->update($request->all());

        if($request->status)
            $success = event(new StatusDelivery());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function test(){
        $messageNotification = "probando";
        $success = event(new StatusDelivery($messageNotification));
        dd($success);
    }
}
