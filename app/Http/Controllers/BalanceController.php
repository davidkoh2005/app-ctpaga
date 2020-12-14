<?php

namespace App\Http\Controllers;

use App\Balance;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function show(Request $request)
    {
        $balances = Balance::where('user_id', $request->user()->id)
                            ->where('commerce_id', $request->commerce_id)->get();
        return response()->json(['statusCode' => 201,'data' => $balances]);
    }
}
