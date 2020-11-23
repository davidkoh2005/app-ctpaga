<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Sale;

class SaleController extends Controller
{
    public function new(Request $request)
    {
        $user = $request->user();
        $code = $this->randomCode();

        foreach($request->sales as $sale){
            return response()->json($sale['data']['name']);
            $price = app('App\Http\Controllers\Controller')->getPrice($sale['data']['price']);
            
            Sale::create([
                "user_id"       => $user->id,
                "commerce_id"   => (int)$request->commerce_id,
                "codeUrl"       => $code,
                "name"          => $sale['data']['name'],
                "price"         => $price,
                "type"          => $sale['type'],
                "quantity"      => $sale['quantity'],
                "expires_at"    => Carbon::now()->format('Y-m-d 23:59')
            ]);
        }

        return response()->json([
            'statusCode' => 201,
            'message' => 'Create sales correctly',
            'codeUrl' => $code,
        ]); 
    }

    public function randomCode()
    {
        $longitud = 6;
        do
        {
            $code = '';
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
            $max = strlen($pattern)-1;
            for($i=0;$i < $longitud;$i++) 
                $code .= $pattern{mt_rand(0,$max)};

            $statusCode = Sale::where('codeUrl', $code)->first();
        }
        while(!empty($statusCode));

        return $code;
    }
}
