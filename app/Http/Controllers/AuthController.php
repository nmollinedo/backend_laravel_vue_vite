<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function funLogin(Request $request){
        // validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        // autenticamos
        if(!Auth::attempt($credenciales)){
            return response()->json(["message" => "Credenciales Incorrectas"], 401);
        }
        // obtener el usuario autenticado
        $usuario = $request->user();
        $usuario_id=$usuario->id;

        //$rol = DB::select('roles');

        $entidad = DB::select("select e.id,e.sigla,e.entidad,e.codigo_presupuestario from users u,entidad e where u.entidad_id = e.id and u.id=$usuario_id");
        $rol = DB::select("select u.usuario usuario,r.id, r.rol,r.descripcion,s.sigla,o.nombre 
                                from rbac.usuarios u, rbac.personas p, rbac.roles r ,rbac.sistemas s, rbac.opciones o, 
                                            rbac.sistemas_roles sr , rbac.sistemas_usuarios_roles sur, rbac.sistemas_roles_opciones sro 
                                where sur.rol_id = sro.rol_id 
                                and sur.sistema_id = sro.sistema_id 
                                and sur.usuario_id  = u.id 
                                and u.persona_id = p.id 
                                and sro.rol_id = r.id 
                                and sro.rol_id = sr.rol_id 
                                and sro.sistema_id = sr.sistema_id 
                                and sr.sistema_id =s.id 
                                and sro.opcion_id =o.id 
                                and u.id=$usuario_id");
        // generamos token
        $token = $usuario->createToken('Token auth')->plainTextToken;
        // respondemos
        return response()->json([
            "access_token" => $token,
            "usuario" => $usuario,
            "entidad" => $entidad,
            "rol" => $rol            
        ], 201);

    }

    public function funRegister(Request $request){
        // validar
        $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|same:c_password",
        ]);

        // guardar
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        
        // verificacion de cuenta por correo
        event(new Registered($usuario));

        // generar una respuesta
        return response()->json(["mensaje" => "Usuario Registrado"], 201);
    }

    public function funProfile(Request $request){
        // obtener el usuario autenticado
        $usuario = $request->user();
        $usuario_id=$usuario->id;
        $entidad = DB::select("select e.id,e.sigla,e.entidad,e.codigo_presupuestario from users u,entidad e where u.entidad_id = e.id and u.id=$usuario_id");
        if (!empty($entidad)) {
            $entidad = $entidad[0]; // como estamos usando select, accedemos al primer (y único) resultado
        }

        $rol = DB::select("select u.usuario usuario,r.id, r.rol,r.descripcion,s.sigla,o.nombre 
	from rbac.usuarios u, rbac.personas p, rbac.roles r ,rbac.sistemas s, rbac.opciones o, 
				  rbac.sistemas_roles sr , rbac.sistemas_usuarios_roles sur, rbac.sistemas_roles_opciones sro 
	where sur.rol_id = sro.rol_id 
	  and sur.sistema_id = sro.sistema_id 
	  and sur.usuario_id  = u.id 
	  and u.persona_id = p.id 
	  and sro.rol_id = r.id 
	  and sro.rol_id = sr.rol_id 
	  and sro.sistema_id = sr.sistema_id 
	  and sr.sistema_id =s.id 
	  and sro.opcion_id =o.id 
	  and u.id=$usuario_id");
        if (!empty($rol)) {
            $rol = $rol[0]; // como estamos usando select, accedemos al primer (y único) resultado
        }
    
        // agregar los datos de la entidad al objeto usuario
        $usuario->entidad = $entidad;
        $usuario->rol = $rol;
        return response()->json($usuario);
    }

    public function funLogout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json(["message" => "Logout"]);
    }

    public function verify($user_id, Request $request){
        if(!$request->hasValidSignature()){
            return response()->json(["message" => "URL EXPIRADO"], 401);
        }

        $user = User::findOrFail($user_id);

        if(!$user->hasVerifiedEmail()){
            $user->markEmailAsVerified();
        }

        // enviar un mensaje en json que indique que el correo ha sido verificado
        
        return redirect()->to('/');
    }

    public function resend(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return response()->json(["message" => "El Correo ya está verificado"], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(["message" => "Se ha enviar un email de verificación"]);
    }
}
