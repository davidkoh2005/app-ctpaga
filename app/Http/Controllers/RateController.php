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
        $startDate = Carbon::now()->setDay(1)->subMonth(1)->format('Y-m-d');

        $user = $request->user();
        $rates = Rate::where('user_id', $user->id)->orderBy('created_at', 'desc')
                     ->where('roleRate',1)
                     ->whereDate('created_at', ">=",$startDate)->get();
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
