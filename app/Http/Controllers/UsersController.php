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
use App\Commerce;

class UsersController extends Controller
{

    public function updateImg(Request $request)
    {
        $user = $request->user();
        $realImage = base64_decode($request->image);
        $date = Carbon::now()->format('Y-m-d');

        if($request->commerce_id == null)
            $commerce_id = 0;
        else
            $commerce_id = $request->commerce_id;
        
        if($request->description == 'Profile')
            $url = '/Users/'.$user->id.'/storage/'.$request->description.'.jpg';
        else if($request->commerce_id != 0)
            $url = '/Users/'.$user->id.'/storage/commercer/commerce_'.$request->commerce_id.'-'.$request->description.'.jpg';
        else
            $url = '/Users/'.$user->id.'/storage/'.$date.'_'.$request->description.'.jpg';


        \Storage::disk('public')->put($url,  $realImage);
        
        Picture::updateOrCreate(['user_id'=>$request->user()->id, 'description'=> $request->description, 'url' => '/storage'.$url, 'commerce_id' => $request->commerce_id]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update image correctly',
            'url' => '/storage'.$url,
        ]);
    }

    public function updateUser(Request $request)
    {   
        $user = $request->user();
        User::whereId($user->id)->update($request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function updateBankUser(Request $request)
    {   
        Bank::updateOrCreate(['user_id'=>$request->user()->id, 'coin'=>$request->coin], $request->all());

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function updateCommerceUser(Request $request)
    {   
        $user = $request->user();
        Commerce::updateOrCreate(['user_id'=>$request->user()->id, 'rif'=>$request->rif], $request->all());

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
