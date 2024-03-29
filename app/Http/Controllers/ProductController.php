<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
        $url = '/Users/'.$user->id.'/storage/products/commerce_'.$request->commerce_id.'-'.$name.'-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';

        $price = app('App\Http\Controllers\Controller')->getPrice($request->price);
     
        \Storage::disk('public')->put($url,  $realImage);
        Product::create([
            "user_id"       => $user->id,
            "commerce_id"   => (int)$request->commerce_id,
            "url"           => '/storage'.$url,
            "name"          => $request->name,
            "price"         => $price,
            "coin"          => $request->coin,
            "description"   => $request->description,
            "categories"    => $request->categories,
            "publish"       => $request->publish,
            "stock"         => $request->stock,
            "postPurchase"  => $request->postPurchase,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Create product correctly',
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($request->url == null) {
            $realImage = base64_decode($request->image);
            $name = str_replace(" ","_",$request->name);
            $url = '/Users/'.$user->id.'/storage/products/commerce_'.$request->commerce_id.'-'.$name.'-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
            Storage::delete($request->url);
            \Storage::disk('public')->put($url,  $realImage);
            $url = '/storage'.$url;
        }else{
            $url = $request->url;
        }

        $price = app('App\Http\Controllers\Controller')->getPrice($request->price);
        
        Product::where('id',$request->id)->update([
            "url"           => $url,
            "name"          => $request->name,
            "price"         => $price,
            "coin"          => $request->coin,
            "description"   => $request->description,
            "categories"    => $request->categories,
            "publish"       => $request->publish,
            "stock"         => $request->stock,
            "postPurchase"  => $request->postPurchase,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update product correctly',
        ]);
    }

    public function delete(Request $request)
    {
        $url = substr($request->url, 8);
        Storage::delete("/public".$url);
        Product::destroy($request->id);
        return response()->json([
            'statusCode' => 201,
            'message' => 'Delete product correctly',
        ]); 
    }
}
