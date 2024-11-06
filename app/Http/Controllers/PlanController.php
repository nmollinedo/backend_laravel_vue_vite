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
        $plan = DB::select("select 'plan' as tipo_clasificador,c.id ,c.clasificador,c.descripcion from clasificadores.clasificador c 
                            where c.tipo_clasificador_id = 1 and c.vigente=1
                            order by c.id desc");
        return response()->json($plan, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }

    public function funListarPlan2($id){
        $plan = DB::select("select ec.entidad_id,ec.rel_clasificador,ec.vigente,c.id ,c.clasificador as descrip_plan,c.tipo_clasificador_id 
                            from clasificadores.entidad_clasificador ec,clasificadores.clasificador c
                            where ec.vigente=1 and ec.entidad_id=$id 
                            and ec.rel_clasificador = c.id 
                            and c.tipo_clasificador_id = 1");
        return response()->json($plan, 200);
   

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
    public function funModificarPlan($id,Request $request){

        
    }
    public function funEliminar($id){
        
    }
}
