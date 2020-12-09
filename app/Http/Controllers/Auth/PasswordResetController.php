<?php
namespace App\Http\Controllers\Auth;
use Session;
use App\User;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class PasswordResetController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
                'statusCode' => 404,
                'message' => 'Email error.'
            ], 404);

        
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );

        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );

        return response()->json([
            'statusCode' => 201,
            'message' => 'Email send'
        ]);
    }

    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset){
            Session::flash('message', "Este token de restablecimiento de contraseña no es válido.");
            return view('updatePassword');
        }
        


        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            Session::flash('message', "Este token de restablecimiento de contraseña no es válido.");
            return view('updatePassword');
        }
            
        return view('updatePassword', compact('token'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->first();

        if (!$passwordReset){
            Session::flash('error', "Este token de restablecimiento de contraseña no es válido.");
            return view('updatePassword');
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user){
            Session::flash('error', "No podemos encontrar un usuario con esa dirección de correo electrónico.");
            return view('updatePassword');
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));

        Session::flash('succecs', "Guardado corractamente.");
        return view('updatePassword');

    }
}