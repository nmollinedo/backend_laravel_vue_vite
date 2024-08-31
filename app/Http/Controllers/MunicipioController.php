<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="1.0",
 *      description="API CRUD Entidades"
 * )
 *
 * @OA\Server(url="http://localhost:8000")
 */

class MunicipioController extends Controller
{
/**
     * @OA\Get(
     *     path="/api/entidad",
     *     tags={"Entidad"},
     *     summary="Get lista de entidades",
     *     description="Retorna lista de entidades",
     *     @OA\Response(
     *         response=200,
     *         description="Succesful operation"
     *      )
     * )
     */
    public function funListarMunicipio(){
        $plan = DB::select('select * from clasificadores.municipios');
        return response()->json($plan, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }

    public function funListarMunicipioDpto($id){
        $programa = DB::select("select * from clasificadores.municipios where clasificadores.municipios.departamento_id=$id");
        return response()->json($programa, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }
    
    

    public function funGuardar(Request $request){
        $sigla = $request->sigla;
        $entidad = $request->entidad;
        DB::insert('insert into entidad(cod_entidad,sigla,entidad)values(?,?,?)',[$sigla,$entidad]);
    }

    public function funMostrar($identificador){
        
    }
    public function funModificar($id,Request $request){
        
    }
    public function funEliminar($id){
        
    }
}
