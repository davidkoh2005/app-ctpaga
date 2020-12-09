<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discount;
use App\User;

class DiscountController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $discounts = Discount::where('user_id', $user->id)->get();
        return response()->json(['statusCode' => 201,'data' => $discounts]);
    }

    public function new(Request $request)
    {
        $user = $request->user();

        Discount::create([
            "user_id"       => $user->id,
            "code"          => $request->code,
            "percentage"    => $request->percentage,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Create discount correctly',
        ]);
    }

    public function update(Request $request)
    {
        Discount::where('id',$request->id)->update([
            "code"          => $request->code,
            "percentage"    => $request->percentage,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update discount correctly',
        ]);
    }

    public function delete(Request $request)
    {
        Discount::destroy($request->id);
        return response()->json([
            'statusCode' => 201,
            'message' => 'Delete discount correctly',
        ]);
    }
}
