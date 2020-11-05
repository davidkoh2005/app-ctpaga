<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Product;
use App\User;

class ProductController extends Controller
{
    public function show(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)
                            ->where('commerce_id', $request->commerce_id)->orderBy('name', 'asc')->get();
        return response()->json(['statusCode' => 201,'data' => $products]);
    }

    public function new(Request $request)
    {
        $user = $request->user();
        $realImage = base64_decode($request->image);
        $name = str_replace(" ","_",$request->name);
        $url = '/Users/'.$user->id.'/storage/products/commerce_'.$request->commerce_id.'-'.$name.'.jpg';
        
        if ($request->coin == 0)
            $price = str_replace("$ ","",$request->price);
        else
            $price = str_replace("Bs ","",$request->price);

     
        \Storage::disk('public')->put($url,  $realImage);
        
        Product::create([
            "user_id"       => $user->id,
            "commerce_id"   => $request->commerce_id,
            "url"           => '/storage'+$url,
            "name"          => $request->name,
            "price"         => $price,
            "coin"          => $request->coin,
            "description"   => $request->description,
            "categories"    => $request->categories,
            "publish"       => $request->publish,
            "stock"      => $request->stock,
            "postPurchase"  => $request->postPurchase,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update category correctly',
        ]);
    }
}
