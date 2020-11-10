<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipping;
use App\User;

class ShippingController extends Controller
{
    public function show(Request $request)
    {
        $shipping = Shipping::where('commerce_id', $request ->commerce_id)->get();
        return response()->json(['statusCode' => 201,'data' => $categories]);
    }

    public function new(Request $request)
    {
        $shipping = Shipping::firstOrCreate ($request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update Shipping correctly',
        ]);
    }
}
