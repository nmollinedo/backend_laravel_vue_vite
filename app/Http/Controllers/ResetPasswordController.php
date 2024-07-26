<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request){
        $request->validate([
            "email" => "required|email|exists:users"
        ]);

        $token = Str::random(64);

        Mail::send('email.recuperar-password', ['token' => $token], function ($message) use ($request){
            $message->to($request->email);
            $message->subject('Recuperar Contraseña');
        });

        return response()->json(["mensaje" => "enviamos un correo con todas las instrucciones"]);

        // $status = Password::sendResetLink(
        //     $request->only("email")
        // );
// 
        // if($status == Password::RESET_LINK_SENT){
        //     return ["status" => __($status)];
        // }

    }

    public function changePassword(Request $request){

    }
}
