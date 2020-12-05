<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Sale;
use App\Rate;
use App\Picture;
use App\Commerce;
use App\Shipping;
use App\Discount;
use App\Product;
use App\Service;
use App\Category;

class SaleController extends Controller
{
    public function index($userUrl, $codeUrl)
    {
        $sales = Sale::where('codeUrl',$codeUrl)->get();
        /* if($sales[0]->statusSale == 1)
            return redirect()->route('form.store', ['userUrl' => $userUrl]); */

        $commerce = Commerce::where('userUrl',$userUrl)->first();

        if(count($sales) == 0|| !$commerce)
            return redirect()->route('welcome');
        else if($sales[0]->commerce_id != $commerce->id)
            return redirect()->route('welcome');

        $user = User::find($commerce->user_id)->first();
        $picture = Picture::where('commerce_id', $commerce->id)->first();
        $shippings = Shipping::where('user_id', $commerce->user_id)->get();
        
        $nameClient = $sales[0]->nameClient;
        $rate = $sales[0]->rate;
        $coinClient = $sales[0]->coinClient;
        $total = 0.0;
        $product = 0;
        $service = 0;
        $quantity = 0;

        foreach($sales as $sale){
            $price = floatval($sale->price) * $sale->quantity;

            $quantity += $sale->quantity;
            if($sale->type == 0)
                $product +=$sale->quantity;
            else
                $service +=$sale->quantity;

            if($sale->coin == 0 && $sale->coinClient==1)
                $total+= $price * $rate;
            else if($sale->coin == 1 && $sale->coinClient==0)
                $total+= $price / $rate;
            else
                $total+= $price;
        }

        $total =  number_format($total, 2, ",", ".");

        if($product == 1)
            $msgProduct = "Producto";
        else if($product > 1)
            $msgProduct = "Productos";
        
        if ($service == 1)
            $msgService = "Servicio";
        else if ($service > 1)
            $msgService = "Servicios";
        
        if($product != 0 && $service != 0)
            $msg = $msgProduct+" y "+$msgService;
        else if($product != 0 && $service == 0)
            $msg = $msgProduct;
        else if($product == 0 && $service != 0)
            $msg = $msgService;

        return view('multi-step-form', compact('userUrl','codeUrl','commerce','picture', 'sales', 'nameClient', 'rate', 'coinClient', 'total', 'shippings', 'quantity', 'msg'));
    }

    public function new(Request $request)
    {
        $user = $request->user();
        $code = $this->randomCode();

        foreach($request->sales as $sale){
            $price = app('App\Http\Controllers\Controller')->getPriceSales($sale['data']['price']);
            
            Sale::create([
                "user_id"               => $user->id,
                "commerce_id"           => (int)$request->commerce_id,
                "codeUrl"               => $code,
                "type"                  => $sale['type'],
                "name"                  => $sale['data']['name'],
                "price"                 => $price,
                "nameClient"            => $request->nameClient,
                "coinClient"            => $request->coin,
                "coin"                  => $sale['data']['coin'],
                "quantity"              => $sale['quantity'],
                "rate"                  => $request->rate,
                "statusShipping"        => $request->statusShipping,
                "descriptionShipping"   => $request->descriptionShipping,
                "expires_at"            => Carbon::now()->format('Y-m-d 23:59:59'),
            ]);
        }

        return response()->json([
            'statusCode' => 201,
            'message' => 'Create sales correctly',
            'codeUrl' => $code,
        ]); 
    }

    public function indexStore($userUrl)
    {
        $coinClient = 1;
        $commerce = Commerce::where('userUrl',$userUrl)->first();
        $user = User::find($commerce->user_id)->first();

        if(!$commerce)
            return redirect()->route('welcome');
        
        $picture = Picture::where('commerce_id', $commerce->id)->first();    
        $shippings = Shipping::where('user_id', $user->id)->get();

        $rate = Rate::where('user_id', $user->id)->orderBy('date', 'desc')->first();
        $rate = $rate->rate;

        $products = Product::where('user_id', $user->id)
                            ->where('commerce_id', $commerce->id)
                            ->where('publish', 1)->orderBy('name', 'asc')->get();

        $services = Service::where('user_id', $user->id)
                            ->where('commerce_id', $commerce->id)
                            ->where('publish', 1)->orderBy('name', 'asc')->get();

        return view('store', compact('userUrl','commerce','picture','coinClient', 'shippings', 'rate'));
    }

    public function showCategories(Request $request)
    {   
        $categories = Category::where('commerce_id', $request->commerce_id)
                                ->where('type', $request->type)->get();

        $returnHTML=view('categories', compact('categories'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function showProductsServices(Request $request)
    {   
        $products = null;
        $services = null;
        $coinClient = $request->coinClient;

        $user = Commerce::find($request->commerce_id)->first();

        if ($request->type == 0){
            $products = Product::where('commerce_id', $request->commerce_id)
                                ->where('publish', 1)->orderBy('name', 'asc');

            if($request->category_select != null)
                $products = $products->where('categories','like',"%$request->category_select%");

            $products = $products->get();

        }else{
            $services = Service::where('commerce_id', $request->commerce_id)
                                ->where('publish', 1)->orderBy('name', 'asc');

            if($request->category_select != null)
                $services = $services->where('categories','like',"%$request->category_select%");

            $services = $services->get();

        }

        $rate = Rate::where('user_id', $user->id)->orderBy('date', 'desc')->first();
        $rate = $rate->rate;
        
        $returnHTML=view('productsServices', compact('products', 'services', 'rate', 'coinClient'))->render();
        return response()->json(array('html'=>$returnHTML));

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

    public function verifyDiscount(Request $request)
    {
        $discount = Discount::where("user_id",$request->user_id)->where("code", $request->input)->first();  
        if($discount)
            return $discount;
        else
            return "ERROR";
    }
}
