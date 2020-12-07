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
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
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
            'message' => 'Email send'
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
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
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->first();

        if (!$passwordReset){
            Session::flash('message', "Este token de restablecimiento de contraseña no es válido.");
            return view('updatePassword');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user){
            Session::flash('message', "No podemos encontrar un usuario con esa dirección de correo electrónico.");
            return view('updatePassword');
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));

        Session::flash('Succecs', "Guardado corractamente.");
        return view('updatePassword');

    }
}