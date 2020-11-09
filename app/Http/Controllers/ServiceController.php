<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Service;
use App\User;

class ServiceController extends Controller
{
    public function show(Request $request)
    {
        $products = Service::where('user_id', $request->user()->id)
                            ->where('commerce_id', $request->commerce_id)->orderBy('name', 'asc')->get();
        return response()->json(['statusCode' => 201,'data' => $products]);
    }

    public function new(Request $request)
    {
        $user = $request->user();
        $realImage = base64_decode($request->image);
        $name = str_replace(" ","_",$request->name);
        $url = '/Users/'.$user->id.'/storage/services/commerce_'.$request->commerce_id.'-'.$name.'.jpg';

        if ($request->coin == 0)
            $price = str_replace("$ ","",$request->price);
        else
            $price = str_replace("Bs ","",$request->price);

     
        \Storage::disk('public')->put($url,  $realImage);
        Service::create([
            "user_id"       => $user->id,
            "commerce_id"   => (int)$request->commerce_id,
            "url"           => '/storage'.$url,
            "name"          => $request->name,
            "price"         => $price,
            "coin"          => $request->coin,
            "description"   => $request->description,
            "categories"    => $request->categories,
            "publish"       => $request->publish,
            "postPurchase"  => $request->postPurchase,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update category correctly',
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($request->url == null) {
            $realImage = base64_decode($request->image);
            $name = str_replace(" ","_",$request->name);
            $url = '/Users/'.$user->id.'/storage/services/commerce_'.$request->commerce_id.'-'.$name.'.jpg';
            Storage::delete($request->url);
            \Storage::disk('public')->put($url,  $realImage);
            $url = '/storage'.$url;
        }else{
            $url = $request->url;
        }

        if ($request->coin == 0)
            $price = str_replace("$ ","",$request->price);
        else
            $price = str_replace("Bs ","",$request->price);

        
        Service::find($request->id)->update([
            "url"           => $url,
            "name"          => $request->name,
            "price"         => $price,
            "coin"          => $request->coin,
            "description"   => $request->description,
            "categories"    => $request->categories,
            "publish"       => $request->publish,
            "postPurchase"  => $request->postPurchase,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update category correctly',
        ]);
    }
}
