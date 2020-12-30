<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\NewUser;
use Carbon\Carbon;
use App\User;
use App\Bank;
use App\Picture;
use App\Commerce;
use DB;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'address'  => 'required|string',
            'phone'    => 'required|string',
        ]);
        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'address'  => $request->address,
            'phone'    => $request->phone,
        ]);

        $user->save();

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addYear(5);
        $token->save();

        Commerce::create(['user_id'=>$user->id]);

        (new User)->forceFill([
            'email' => $request->email,
        ])->notify(
            new NewUser()
        );
        
        return response()->json([
            'statusCode' => 201,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'message' => 'Successfully created user!'], 201);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'statusCode' => 401,
                'message' => 'Unauthorized',
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addYear(5);
        $token->save();

        return response()->json([
            'statusCode' => 201,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'statusCode' => 201,
            'message' => 'Successfully logged out'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string',
        ]);

        $user = $request->user();

        if (\Hash::check($request->current_password , $user->password)) {
 
            if (!\Hash::check($request->new_password , $user->password)) {
    
                $user->password = bcrypt($request->new_password);
                $user->save();

                $request->user()->token()->revoke();

                return response()->json([
                'statusCode' => 201,
                'message' => 'password updated successfully!'], 201);

            }else{

                return response()->json([
                    'statusCode' => 401,
                    'message' => 'la nueva contraseña no puede ser la misma que la contraseña anterior!'], 201);
            }

        }else{
            return response()->json([
                'statusCode' => 401,
                'message' => 'la contraseña es incorrecta!'], 201);
        }
    
    }

    public function user(Request $request)
    {
        $pictures = Picture::where('user_id', $request->user()->id)->get();
        $banks = Bank::where('user_id', $request->user()->id)->limit(2)->get();
        $commerces = Commerce::where('user_id', $request->user()->id)->orderBy('name', 'asc')->get();
        return response()->json(['statusCode' => 201,'data' => [$request->user(), 'banks'=> $banks, 'commerces'=> $commerces, 'pictures'=>$pictures ]]);
    }

    public function versionApp()
    {
        $version = DB::table("version")->get();
        return response()->json([
            'statusCode' => 201,
            'data' => $version,
        ]);
    }
}
