<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RulesPassword;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:users"
        ]);

        
        /*
        $token = Str::random(64);
        Mail::send('email.recuperar-password', ['token' => $token], function ($message) use ($request){
            $message->to($request->email);
            $message->subject('Recuperar Contraseña');
        });
        return response()->json(["mensaje" => "enviamos un correo con todas las instrucciones"]);
        */

        $status = Password::sendResetLink(
            $request->only("email")
        );

        if ($status == Password::RESET_LINK_SENT) {
            return ["status" => __($status)];
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            "token" => "required",
            "email" => "required|email",
            "password" => ["required", RulesPassword::default()]
        ]);

        $status = Password::reset(
            $request->only("email", "password", "password_confirmation", "token"),
            function (User $user, string $password){
                $user->forceFill([
                    "password" => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if($status === Password::PASSWORD_RESET) {
            return response(["message" => "La contraseña ha sido modificada!!!"]);
        }

        return response(["message" => __($status)], 500);
    }
}
