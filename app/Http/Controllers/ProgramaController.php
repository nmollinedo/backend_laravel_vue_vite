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

class ProgramaController extends Controller
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
    public function funListarPrograma($id){
        $programa = DB::select("select * from clasificadores.programas where clasificadores.programas.plan_id=$id");
        return response()->json($programa, 200);
      
    }


    public function funListarPlanPrograma(){
        $programa = DB::select("select c.id ,c.clasificador, c.descripcion ,c.tipo_clasificador_id, c.vigente,c.marca_cofinanciador,c.cofinanciador,tc.tipo_clasificador
         from clasificadores.clasificador c inner join clasificadores.tipo_clasificador tc  
         on c.tipo_clasificador_id = tc.id 
         where tc.vigente <>0");
        return response()->json($programa, 200);
      
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
