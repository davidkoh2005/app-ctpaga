<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    public function updateCompany(Request $request)
    {   

        $user = $request->user();
        User::whereId($user->id)->update($request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data company correctly'
        ]);
    }

    public function testJson(Request $request)
    {   

        return response()->json($request->all());
    }
}
