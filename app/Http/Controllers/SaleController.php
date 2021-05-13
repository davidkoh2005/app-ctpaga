<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\PostPurchase;
use App\Notifications\PaymentConfirm;
use Carbon\Carbon;
use Session;
use App\User;
use App\Sale;
use App\Rate;
use App\Paid;
use App\Picture;
use App\Commerce;
use App\Shipping;
use App\Discount;
use App\Product;
use App\Service;
use App\Category;
use App\Settings;
use App\DeliveryCost;
use App\SettingsBank;

class SaleController extends Controller
{
    public function index($userUrl, $codeUrl, $statusModification = 0)
    {
        $sales = Sale::where('codeUrl',$codeUrl)->orderBy('name', 'asc')->get();

        $commerce = Commerce::where('userUrl',$userUrl)->first();
        $user = User::where('id',$commerce->user_id)->first();
        
        if($user->status>0)
            return redirect()->route('welcome');

        if(count($sales) == 0 || !$commerce)
            return redirect()->route('welcome');
        else if($sales[0]->commerce_id != $commerce->id)
            return redirect()->route('welcome');
            
        if($sales[0]->statusSale == 1 || Carbon::now() > $sales[0]->expires_at)
            return redirect()->route('form.store', ['userUrl' => $userUrl]);

        $picture = Picture::where('commerce_id', $commerce->id)->where('type',0)->first();
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

        $transfers = SettingsBank::where('type',0)->get();
        $mobilePayments = SettingsBank::where('type',1)->get();

        $data = file_get_contents("json/listBanks.json");
        $listBanks = json_decode($data, true);

        $zelle = Settings::where('name','Zelle')->first();
    
        return view('multi-step-form', compact('userUrl','codeUrl', 'statusModification', 'commerce','picture', 'sales', 'nameClient', 'rate', 'coinClient', 'total', 'shippings', 'quantity', 'msg', 'transfers', 'mobilePayments', 'listBanks', 'zelle'));
    }

    public function new(Request $request)
    {
        $code = $this->randomCode();
        
        if($request->user_id){
            $user_id = $request->user_id;
            foreach($request->sales as $sale){
                Sale::create([
                    "user_id"               => $user_id,
                    "commerce_id"           => (int)$request->commerce_id,
                    "codeUrl"               => $code,
                    "type"                  => (int)$sale['type'],
                    "productService_id"     => (int)$sale['data'][0]['id'],
                    "name"                  => $sale['data'][0]['name'],
                    "price"                 => $sale['data'][0]['price'],
                    "nameClient"            => $request->nameClient,
                    "coinClient"            => (int)$request->coin,
                    "coin"                  => (int)$sale['data'][0]['coin'],
                    "quantity"              => (int)$sale['quantity'],
                    "rate"                  => $request->rate,
                    "statusShipping"        => $request->statusShipping? 1 : 0,
                    "descriptionShipping"   => $request->descriptionShipping,
                    "expires_at"            => Carbon::now()->addHour(6),
                    "image"                 => $sale['data'][0]['url'],
                ]);
            }

        }else{
            $totalPrice = 0;
            $user_id = $request->user()->id;

            foreach($request->sales as $sale){
                $url= "";

                if($sale['data']['id'] == 0){
                    $price = app('App\Http\Controllers\Controller')->getPriceAmount($sale['data']['price']);
                    $url = "";
                }
                else{
                    $price = app('App\Http\Controllers\Controller')->getPriceSales($sale['data']['price']);
                    $url = $sale['data']['url'];
                }
                
                Sale::create([
                    "user_id"               => $user_id,
                    "commerce_id"           => (int)$request->commerce_id,
                    "codeUrl"               => $code,
                    "type"                  => $sale['type'],
                    "productService_id"     => $sale['data']['id'],
                    "name"                  => $sale['data']['name'],
                    "price"                 => $price,
                    "nameClient"            => $request->nameClient,
                    "coinClient"            => $request->coin,
                    "coin"                  => $sale['data']['coin'],
                    "quantity"              => $sale['quantity'],
                    "rate"                  => $request->rate,
                    "statusShipping"        => $request->statusShipping,
                    "descriptionShipping"   => $request->descriptionShipping,
                    "expires_at"            => Carbon::now()->addHour(6),
                    "image"                 => $url,
                ]);
                $totalPrice += $this->exchangeRate($price, $request->rate, $sale['data']['coin'], $request->coin);
            }
        }


        if($request->userUrl)
            return response()->json([
                'url' => url($request->userUrl.'/'.$code.'/'.true)
            ]);
        elseif($request->email){
            $this->registerPaid($request->commerce_id, $code, $totalPrice, $request->nameClient, $request->coin, $request->email );
            return response()->json([
                'statusCode' => 201,
                'message' => 'Paid Registered Successfully',
            ]);
        }else
            return response()->json([
                'statusCode' => 201,
                'message' => 'Create sales correctly',
                'codeUrl' => $code,
            ]);  
    }

    public function exchangeRate($price, $rate, $coin, $coinClient){
        $result = 0;

        if($coin == 0 && $coinClient == 1)
            $result = (floatval($price) * $rate);
        else if($coin == 1 && $coinClient == 0)
            $result = (floatval($price) / $rate);
        else
            $result = (floatval($price));

        return $result;
    }

    public function registerPaid($commerce_id, $codeUrl, $totalPaid, $nameClient, $coinClient, $emailClient)
    {
        $sales = Sale::where("codeUrl", $codeUrl)->get();
        $commerce = Commerce::where('id', $commerce_id)->first();
        $message="";
        foreach ($sales as $sale)
        {
            if($sale->type == 0 && $sale->productService_id != 0){
                $product = Product::where('id',$sale->productService_id)->first();
                
                if ($product->postPurchase)
                    $message .= "- ".$product->postPurchase."\n";

                $product->stock -= $sale->quantity;
                $product->save();

            }

            if($sale->type == 1 && $sale->productService_id != 0){
                $service = Service::where('id',$sale->productService_id)->first();
                
                if($service->postPurchase)
                    $message .= "- ".$service->postPurchase."\n";
            }

            $sale->statusSale = 1;
            $sale->save();

        }

        $user = User::where('id',$commerce->user_id)->first();
    
        Paid::create([
            "user_id"               => $user->id,
            "commerce_id"           => $commerce->id,
            "codeUrl"               => $codeUrl,
            "nameClient"            => $nameClient,
            "total"                 => strval($totalPaid),
            "coin"                  => $coinClient,
            "email"                 => $emailClient,
            "nameShipping"          => null,
            "numberShipping"        => null,
            "state"                 => null,
            "municipalities"        => null,
            "addressShipping"       => null,
            "detailsShipping"       => null,
            "selectShipping"        => null,
            "priceShipping"         => null,
            "percentage"            => 0,
            "nameCompanyPayments"   => "Tienda Fisica",
            "date"                  => Carbon::now(),
            "statusShipping"        => 1,
        ]);

        $userUrl = $commerce->userUrl;

        (new User)->forceFill([
            'email' => $emailClient,
        ])->notify(
            new PaymentConfirm($nameClient, $codeUrl)
        );
    }

    public function indexStore($userUrl)
    {
        $coinClient = 1;
        $commerce = Commerce::where('userUrl',$userUrl)->first();
                
        if(!$commerce)
            return redirect()->route('welcome');
        
        $user = User::where('id', $commerce->user_id)->first();

        if($user->status>0)
            return redirect()->route('welcome');

        $statusShipping = $user->statusShipping;
        
        $picture = Picture::where('commerce_id', $commerce->id)->where('type',0)->first();    
        $shippings = Shipping::where('user_id', $user->id)->get();

        $rateUser = Rate::where('user_id', $user->id)->orderBy('date', 'desc')->first();
        $rate = $rateUser->rate;

        $services = Service::where('user_id', $user->id)
                            ->where('commerce_id', $commerce->id)
                            ->where('publish', 1)->orderBy('name', 'asc')->get();

        $whatsappNum = app('App\Http\Controllers\Controller')->validateNum($user->phone);

        $services = count($services);

        return view('store', compact('userUrl','commerce','picture','coinClient', 'shippings', 'rate', 'statusShipping', 'whatsappNum', 'services'));
    }

    public function validateNum($phone)
    {
        $phone = substr($phone, 1, strlen($phone));
        return "58".$phone;
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

        $user = User::where('id',$request->user_id)->first();

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

    public function modifysale(Request $request){
        Sale::where('id', $request->sale_id)->update([
            "quantity" => $request->quantity
        ]);

        return response()->json([
            'url' => url($request->userUrl.'/'.$request->codeUrl.'/'.true)
        ]);
    }

    public function removeSale(Request $request){
        Sale::where('id', $request->sale_id)->delete();

        return response()->json([
            'url' => url($request->userUrl.'/'.$request->codeUrl.'/'.true)
        ]);
    }

    public function showSales(Request $request){
        $sales = Sale::where('codeUrl',$request->codeUrl)->orderBy('name', 'asc')->get();
        return response()->json([
            'statusCode' => 201,
            'message' => 'Show Sale',
            'data'  => $sales,
        ]);
    }

    public function showMunicipalities(Request $request){
        $data = file_get_contents("json/municipalities.json");
        $states = json_decode($data, true);

        foreach($states as $key => $state){
            if($key == $request->states){
                $municipalities = DeliveryCost::where('state',$request->states)->where('cost','>=',1)
                                    ->select('municipalities')->get();
            }
        }

        return response()->json([
            'statusCode' => 201,
            'message' => 'Show municipalities',
            'data'  => $municipalities,
        ]);
    }
}
