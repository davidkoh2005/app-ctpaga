<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Rate;
use App\User;

class RateController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $rates = Rate::where('user_id', $user->id)->orderBy('date', 'desc')->get();
        return response()->json(['statusCode' => 201,'data' => $rates]);
    }

    public function new(Request $request)
    {
        $user = $request->user();
        
        $rate = app('App\Http\Controllers\Controller')->getPrice($request->rate);
        Rate::create ([
            "user_id"   => $user->id,
            "rate"      => $rate,
            "date"      => Carbon::now(),
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Create rate correctly',
            'data'  => $rate,
        ]);
    }
}
