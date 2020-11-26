<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipping;
use App\User;

class ShippingController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $shipping = Shipping::where('user_id', $user->id)->get();
        return response()->json(['statusCode' => 201,'data' => $shipping]);
    }

    public function new(Request $request)
    {
        $user = $request->user();
        $price = $request->price;
        if($price != "FREE")
            $price = app('App\Http\Controllers\Controller')->getPriceShipping($request->price);

        Shipping::create([
            "user_id"       => $user->id,
            "description"   => $request->description,
            "price"         => $price,
            "coin"          => $request->coin,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Create shipping correctly',
        ]);
    }

    public function update(Request $request)
    {
        $price = app('App\Http\Controllers\Controller')->getPriceShipping($request->price);

        Shipping::find($request->id)->update([
            "price"         => $price,
            "coin"          => $request->coin,
            "description"   => $request->description,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update shipping correctly',
        ]);
    }

    public function delete(Request $request)
    {
        Shipping::destroy($request->id);
        return response()->json([
            'statusCode' => 201,
            'message' => 'Delete shipping correctly',
        ]);
    }
}
