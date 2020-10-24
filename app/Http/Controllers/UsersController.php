<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Bank;

class UsersController extends Controller
{
    public function updateImg(Request $request)
    {
        $user = $request->user();
        $file = $request->file('image');
        $name = $file->getClientOriginalName();
        \Storage::disk('public')->put('/Users/'.$user->id.'/profile.jpg',  \File::get($file));
        return response()->json([
            'statusCode' => 201,
            'message' => 'Update image correctly'
        ]);
    }

    public function showProfile(Request $request)
    {
        $user = User::where('email', $request->email)->first(); 
        return view('users/userProfile', compact('user'));
    }

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
        $bank = Bank::updateOrCreate(['user_id'=>$request->user()->id, 'coin'=>$request->coin], $request->all());

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
