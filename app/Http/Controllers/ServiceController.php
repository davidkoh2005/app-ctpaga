<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Service;
use App\User;

class ServiceController extends Controller
{
    public function show(Request $request)
    {
        $services = Service::where('user_id', $request->user()->id)
                            ->where('commerce_id', $request->commerce_id)->orderBy('name', 'asc')->get();
        return response()->json(['statusCode' => 201,'data' => $services]);
    }

    public function new(Request $request)
    {
        $user = $request->user();
        $realImage = base64_decode($request->image);
        $name = str_replace(" ","_",$request->name);
        $url = '/Users/'.$user->id.'/storage/services/commerce_'.$request->commerce_id.'-'.$name.'-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';

        $price = app('App\Http\Controllers\Controller')->getPrice($request->price);
     
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
            'message' => 'Create service correctly',
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($request->url == null) {
            $realImage = base64_decode($request->image);
            $name = str_replace(" ","_",$request->name);
            $url = '/Users/'.$user->id.'/storage/services/commerce_'.$request->commerce_id.'-'.$name.'-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
            Storage::delete($request->url);
            \Storage::disk('public')->put($url,  $realImage);
            $url = '/storage'.$url;
        }else{
            $url = $request->url;
        }

        $price = app('App\Http\Controllers\Controller')->getPrice($request->price);
        
        Service::where('id',$request->id)->update([
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
            'message' => 'Update service correctly',
        ]);
    }

    public function delete(Request $request)
    {
        $url = substr($request->url, 8);
        Storage::delete("/public".$url);
        Service::destroy($request->id);
        return response()->json([
            'statusCode' => 201,
            'message' => 'delete service correctly',
        ]);
    }
}
