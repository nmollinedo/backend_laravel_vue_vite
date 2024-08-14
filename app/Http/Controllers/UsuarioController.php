<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   // el modelo User esta asociado a la tabla users
        $usuarios = User::with('persona', 'roles')->get(); // select * from users

        //DB::select("");

        // $tabla_pivot = DB::table("role_user")->join('users', 'users.id', "role_user.user_id")->where("user_id", "=", 2)->count();

        return response()->json($usuarios, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsuarioRequest $request)
    {
        // $nombre = "admin' or 1=1";// $request->name;
        // DB::insert("insert into users (name, email) values(?, ?)", [$nombre, $request->email]);
        /*
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);
        */

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = $request->password;
        $usuario->save();


        return response()->json(["message" => "Usuario registrado correctamente"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::find($id);

        return response()->json($usuario);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email,$id",
            "password" => "required"
        ]);

        $usuario = User::find($id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = $request->password;
        $usuario->update();

        return response()->json(["message" => "Usuario actualizado"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::find($id);
        $usuario->delete();
        return response()->json(["message" => "Usuario eliminado"]);

    }

    public function asignarPersona(Request $request){

        $request->validate([
            "nombres" => "required|max:50|min:2",
            "apellidos" => "required|max:50|min:2",
            "fecha_nac" => "required",
            "user_id" => "required"
        ]);

        $user_id = $request->user_id;

        // $user = User::find($user_id);

        $persona = new Persona();
        $persona->nombres = $request->nombres;
        $persona->apellidos = $request->apellidos;
        $persona->fecha_nac = $request->fecha_nac;
        $persona->user_id = $user_id;
        $persona->save();

        return response()->json(["mensaje" => "Persona Asignada a cuenta de Usuario"]);

    }
}
