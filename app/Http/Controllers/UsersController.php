<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
use App\Bank;
use App\Picture;

class UsersController extends Controller
{

    public function updateImg(Request $request)
    {
        $user = $request->user();
        $realImage = base64_decode($request->image);
        $date = Carbon::now()->format('Y-m-d');
        
        if($request->description != 'Profile')
            $url = '/Users/'.$user->id.'/storage/'.$date.'_'.$request->description.'.jpg';
        else
            $url = '/Users/'.$user->id.'/storage/'.$request->description.'.jpg';

        \Storage::disk('public')->put($url,  $realImage);
        
        Picture::updateOrCreate(['user_id'=>$request->user()->id, 'description'=> $request->description], ['url' => '/storage'.$url]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update image correctly',
            'url' => '/storage'.$url,
        ]);
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
