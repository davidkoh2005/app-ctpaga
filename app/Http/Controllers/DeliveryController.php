<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;

class DeliveryController extends Controller
{
    public function update(Request $request)
    {   
        $delivery = $request->user();
        Delivery::whereId($delivery->id)->update($request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }
}
