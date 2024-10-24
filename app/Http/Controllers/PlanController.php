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

class PlanController extends Controller
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
    public function funListarPlan(){
        $plan = DB::select('select * from clasificadores.planes');
        return response()->json($plan, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }
    

    public function funListarTipoClasificador(){
        $plan = DB::select('select tc.id,tc.tipo_clasificador,tc.vigente from clasificadores.tipo_clasificador tc where tc.vigente = 1');
        return response()->json($plan, 200);
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
