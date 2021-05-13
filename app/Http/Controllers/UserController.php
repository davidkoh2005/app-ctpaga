<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Bank;
use App\Picture;
use App\Commerce;

class UserController extends Controller
{

    public function updateImg(Request $request)
    {
        $user = $request->user();
        $realImage = base64_decode($request->image);
        $date = Carbon::now()->format('Y-m-d');

        if($request->commerce_id == '0'){
            $commerce = Commerce::create(['user_id' => $user->id]);
            $commerce_id = $commerce->id;
            $commerce->save();
        }else{
            $commerce_id = $request->commerce_id;
        }

        if($request->description == 'Profile')
            $url = '/Users/'.$user->id.'/storage/commercer/commerce_'.$commerce_id.'-'.$request->description.'-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
        elseif($request->description == 'RIF')
            $url = '/Users/'.$user->id.'/storage/commercer/commerce_'.$commerce_id.'-'.$request->description.'.jpg';
        else
            $url = '/Users/'.$user->id.'/storage/'.$date.'_'.$request->description.'.jpg';
        
        if($request->urlPrevious != ''){
            $urlPrevius = substr($request->urlPrevious,8);
            \Storage::disk('public')->delete($urlPrevius);
        }
        \Storage::disk('public')->put($url,  $realImage);

        if($request->description == "Selfie") 
            $commerce_id = null; 
        
        Picture::updateOrCreate([
            'user_id'=>$user->id,
            'description'=> $request->description, 
            'commerce_id' => $commerce_id,
            'type'=>0,
        ],
        ['url' => '/storage'.$url]);

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

    public function updateEmailUser(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::whereId(Auth::guard('web')->id())->first();
            $user->email = $request->newEmail;
            $user->save();

            return response()->json([
                'statusCode' => 201,
            ]);
        }

        return response()->json([
            'statusCode' => 401,
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

    public function createCommerce(Request $request)
    {
        Commerce::create(['user_id'=>$request->user()->id, 'name'=>$request->name, 'userUrl'=>$request->userUrl]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function updateCommerceUser(Request $request)
    {   
        if($request->commerce_id == '0'){
            $commerce = Commerce::create(['user_id' => $request->user()->id]);
            $commerce_id = $commerce->id;
            $commerce->save();
        }else{
            $commerce_id = $request->commerce_id;
        }

        Commerce::where('id',$request->commerce_id)->update([
            "rif"       => $request->rif,
            "name"      => $request->name,
            "address"   => $request->address,
            "phone"     => $request->phone,
            "userUrl"   => $request->userUrl,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Update data correctly'
        ]);
    }

    public function verifyUrlUser(Request $request)
    {   
        $status = Commerce::where("userUrl", $request->userUrl)->first();
        if(!$status){
            return response()->json([
                'statusCode' => 201,
            ]);
        }else{
            return response()->json([
                'statusCode' => 401,
            ]);
        }
    }

    public function deleteCommerceUser(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $commerce = Commerce::whereId($request->idCommerce)->first();
            $commerce->delete = 1;
            $commerce->save();

            return response()->json([
                'statusCode' => 201,
            ]);
        }

        return response()->json([
            'statusCode' => 401,
        ]);

    }
}
