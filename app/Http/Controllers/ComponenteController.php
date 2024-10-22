<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="1.0",
 *      description="API CRUD Entidades"
 * )
 *
 * @OA\Server(url="http://localhost:8000")
 */

class ComponenteController extends Controller
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
    public function funListarComponente(){
        $componente = DB::select("select * from clasificadores.componente c where c.vigente='1'");
        return response()->json($componente, 200);
     
    }
    public function funListarComponenteId($id){
        $componente = DB::select("select tc.transferencia_id,tc.componente_id,tc.monto_aporte_local,tc.monto_cofinanciamiento,tc.monto_finan_externo,tc.monto_otros,
(select c.componente from clasificadores.componente c where c.id=tc.componente_id)as componente
from transferencia.transferencias_componente tc where tc.transferencia_id=$id");
        return response()->json($componente, 200);
     
    }
   
    public function funGuardarComponente(Request $request){
        $transferencia_id = $request->transferencia_id;
        $componente_id = $request->componente_id;
        $monto_aporte_local= $request->monto_aporte_local;
        $monto_cofinanciamiento = $request->monto_cofinanciamiento;
        $monto_finan_externo = $request->monto_finan_externo;
        $monto_otros = $request->monto_otros;
        
        DB::insert('INSERT INTO transferencia.transferencias_componente (transferencia_id,componente_id,
            monto_aporte_local, monto_cofinanciamiento, monto_finan_externo, monto_otros, 
            cod_usuario, fecha_registro, cod_usuario_modificacion, fecha_modificacion
        ) VALUES ( ?,?,?,?,?,?,1,NOW(),1,NOW()
        )',[$transferencia_id,$componente_id,$monto_aporte_local,$monto_cofinanciamiento,$monto_finan_externo,$monto_otros]);
        return response()->json(["message" => "Datos guardados correctamente"]);
    }

    public function funMostrar($identificador){
        
    }
    public function funModificarComponente($id,Request $request){
        $transferencia_id = $request->transferencia_id;
        $componente_id = $request->componente_id;
        $monto_aporte_local= $request->monto_aporte_local;
        $monto_cofinanciamiento = $request->monto_cofinanciamiento;
        $monto_finan_externo = $request->monto_finan_externo;
        $monto_otros = $request->monto_otros;
        DB::select("update transferencia.transferencias_componente set monto_aporte_local=$monto_aporte_local,monto_cofinanciamiento=$monto_cofinanciamiento,monto_finan_externo=$monto_finan_externo,monto_otros=$monto_otros where transferencia_id=$id and tc.componente_id=$componente_id");

        return response()->json(["message" => "Datos actualizados correctamente"]);
    }
    public function funEliminarComponente($transferencia_id,$componente_id){
        DB::select("delete from transferencia.transferencias_componente tc where tc.transferencia_id=$transferencia_id and tc.componente_id=$componente_id");

        return response()->json(["message" => "Datos eliminados correctamente"]);
    }
}
