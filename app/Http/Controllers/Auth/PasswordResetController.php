<?php
namespace App\Http\Controllers\Auth;
use Session;
use App\User;
use App\Delivery;
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
                'message' => 'Este email no se encuentra registrado en nuestra base de datos.'
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
                new PasswordResetRequest($passwordReset->token, 0)
            );

        return response()->json([
            'statusCode' => 201,
            'message' => 'Email send'
        ]);
    }

    public function find($token)
    {
        $type = 0;
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset){
            Session::flash('message', "Este token de restablecimiento de contraseña no es válido.");
            return view('updatePassword', compact('token', 'type'));
        }
        


        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            Session::flash('message', "Este token de restablecimiento de contraseña no es válido.");
            return view('updatePassword', compact('token', 'type'));
        }
            
            
        return view('updatePassword', compact('token', 'type'));
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

    public function createDelivery(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $delivery = Delivery::where('email', $request->email)->first();

        if (!$delivery)
            return response()->json([
                'statusCode' => 404,
                'message' => 'Este email no se encuentra registrado en nuestra base de datos.'
            ], 404);

        
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $delivery->email],
            [
                'email' => $delivery->email,
                'token' => Str::random(60)
            ]
        );

        if ($delivery && $passwordReset)
            $delivery->notify(
                new PasswordResetRequest($passwordReset->token, 1)
            );

        return response()->json([
            'statusCode' => 201,
            'message' => 'Email send'
        ]);
    }

    public function findDelivery($token)
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

        $type = 1;
            
        return view('updatePassword', compact('token', 'type'));
    }

    public function resetDelivery(Request $request)
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

        $delivery = delivery::where('email', $passwordReset->email)->first();

        if (!$delivery){
            Session::flash('error', "No podemos encontrar un usuario con esa dirección de correo electrónico.");
            return view('updatePassword');
        }

        $delivery->password = bcrypt($request->password);
        $delivery->save();

        $passwordReset->delete();
        $delivery->notify(new PasswordResetSuccess($passwordReset));

        Session::flash('succecs', "Guardado corractamente.");
        return view('updatePassword');

    }
}