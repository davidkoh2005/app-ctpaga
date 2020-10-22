<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Bank;

class UsersController extends Controller
{
    public function update(Request $request)
    {   
        $user = $request->user();
        User::whereId($user->id)->update($request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function bankUser(Request $request)
    {   
        $bank = Bank::updateOrCreate(['user_id'=>$request->user()->id], $request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function testJson(Request $request)
    {   
        return response()->json($request->all());
    }
}
