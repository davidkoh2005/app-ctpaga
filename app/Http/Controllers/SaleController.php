<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Sale;
use App\Picture;
use App\Commerce;
use App\Shipping;

class SaleController extends Controller
{
    public function index($userUrl, $codeUrl)
    {
        $sales = Sale::where('codeUrl',$codeUrl)->get();
        $commerce = Commerce::where('userUrl',$userUrl)->first();

        if(count($sales) == 0|| !$commerce)
            return redirect()->route('welcome');
        else if($sales[0]->commerce_id != $commerce->id)
            return redirect()->route('welcome');

        $picture = Picture::where('commerce_id', $commerce->id)->first();
        $shippings = Shipping::where('user_id', $commerce->user_id)->get();
        
        $rate = $sales[0]->rate;
        $coinClient = $sales[0]->coinClient;
        $total = 0.0;

        foreach($sales as $sale){
            $price = floatval($sale->price) * $sale->quantity;

            if($sale->coin == 0 && $sale->coinClient==1)
                $total+= $price * $rate;
            else if($sale->coin == 1 && $sale->coinClient==0)
                $total+= $price / $rate;
            else
                $total+= $price;
        }

        return view('multi-step-form', compact('commerce','picture', 'sales', 'rate', 'coinClient', 'total', 'shippings'));
    }

    public function new(Request $request)
    {
        $user = $request->user();
        $code = $this->randomCode();

        foreach($request->sales as $sale){
            $price = app('App\Http\Controllers\Controller')->getPriceSales($sale['data']['price']);
            
            Sale::create([
                "user_id"       => $user->id,
                "commerce_id"   => (int)$request->commerce_id,
                "codeUrl"       => $code,
                "type"          => $sale['type'],
                "name"          => $sale['data']['name'],
                "price"         => $price,
                "nameClient"    => $request->nameClient,
                "coinClient"    => $request->coin,
                "coin"          => $sale['data']['coin'],
                "quantity"      => $sale['quantity'],
                "rate"          => $request->rate,
                "expires_at"    => Carbon::now()->format('Y-m-d 23:59:59'),
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
