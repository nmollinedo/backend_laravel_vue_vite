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

    public function funListarPrograma2($entidad_id,$clasificador_id){
        $programa = DB::select("select rc.rel_clasificador ,rc.cod_clasificador ,rc.cod_clasificador_dependiente ,ec.entidad_id ,c.id ,c.clasificador ,c.descripcion ,c.tipo_clasificador_id 
                                from transferencia.rel_clasificador rc, clasificadores.entidad_clasificador ec,clasificadores.clasificador c 
                                where rc.rel_clasificador = ec.rel_clasificador and c.id = rc.cod_clasificador_dependiente 
                                and ec.entidad_id = $entidad_id
                                and rc.vigente = 1
                                and rc.cod_clasificador = $clasificador_id");
        return response()->json($programa, 200);
      
    }


    public function funListarPlanPrograma(){
        $programa = DB::select("select c.id ,c.clasificador, c.descripcion ,c.tipo_clasificador_id, c.vigente,c.marca_cofinanciador,c.cofinanciador,tc.tipo_clasificador
         from clasificadores.clasificador c inner join clasificadores.tipo_clasificador tc  
         on c.tipo_clasificador_id = tc.id 
         where c.vigente <> 0 order by id desc");
        return response()->json($programa, 200);
      
    }
    

 
    public function funGuardarPlanPrograma(Request $request){
        $clasificador = $request->clasificador;
        $descripcion = $request->descripcion;
        $tipo_clasificador_id = $request->tipo_clasificador_id;
        DB::insert("INSERT INTO clasificadores.clasificador
        (clasificador, descripcion, tipo_clasificador_id, vigente, marca_cofinanciador, cofinanciador)
        VALUES( ?, ?, ?, 1, 0, 0)",[$clasificador,$descripcion,$tipo_clasificador_id]);
        return response()->json(["message" => "Plan programa registrado correctamente"]);
    }
    public function funMostrar($identificador){
        
    }
    public function funModificarPlanPrograma($id,Request $request){
        $clasificador = $request->clasificador;
        $descripcion = $request->descripcion;
        $tipo_clasificador_id = $request->tipo_clasificador_id;
        DB::select("UPDATE clasificadores.clasificador
                    SET clasificador=?, descripcion=?, tipo_clasificador_id=?
                    WHERE id=$id
                    ",[$clasificador,$descripcion,$tipo_clasificador_id]);
        return response()->json(["message" => "Plan programa actualizado correctamente"]);
    }
    public function funEliminarPlanPrograma($id){
   
        DB::select("UPDATE clasificadores.clasificador
                    SET vigente=0
                    WHERE id=$id
                    ");
        return response()->json(["message" => "Plan programa eliminado correctamente"]);
    }
}
