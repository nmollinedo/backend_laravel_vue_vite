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

class EntidadController extends Controller
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
    public function funListar(){
        $producto = DB::select("select i.id ,i.codigo_presupuestario || '-' || i.nombre AS nombre,i.codigo_presupuestario ,i.estado_id ,i.usuario_id ,i.sigla, i.institucion_padre_id ,i.tipo_entidad_id 
		from clasificadores.instituciones i where i.codigo_presupuestario <> '-' and i.estado_id = 1");
        return response()->json($producto, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }
    
    public function funListarEjecutora($id){
        $entidad = DB::select("select * from clasificadores.instituciones where institucion_padre_id=$id and estado_id=1
union
select * from clasificadores.instituciones  where id=$id");
        return response()->json($entidad, 200);
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
