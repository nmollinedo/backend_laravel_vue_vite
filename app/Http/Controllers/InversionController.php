<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class InversionController extends Controller
{   
/**
     * @OA\Get(
     *     path="/api/inversion",
     *     tags={"Inversion"},
     *     summary="Get lista de inversiones",
     *     description="Retorna lista de inversiones",
     *     @OA\Response(
     *         response=200,
     *         description="Succesful operation"
     *      )
     * )
     */

    public function funListarTransferencia(){
        $inversion = DB::select("
        SELECT * from transferencias.transferencias");
 return response()->json([
    "status" => true,
    "message" => "information",
    "data" => $inversion
]);

    }
    public function funGuardar(Request $request){
      
      
        $tipo_proyecto_id = $request->tipo_proyecto_id;
        $codigo_tpp = $request->codigo_tpp;
        $codigo_tpp_formato = $request->codigo_tpp_formato;
        $nombre_formal = $request->nombre_formal;
        $accion_inversion_id = $request->accion_inversion_id;
        $objeto_trasferencia = $request->objeto_transferencia;
        $localizacion_trasferencia = $request->localizacion_trasferencia;
        $nombre_original = $request->nombre_original;
        $tipo_inversion_id = $request->tipo_inversion_id;
        $fecha_inicio_estimada = $request->fecha_inicio_estimada;
        $fecha_fin_estimada = $request->fecha_fin_estimada;
        $estado_inversion_id = $request->estado_inversion_id;
        $reglamento_id = $request->reglamento_id;
        $area_influencia_id = $request->area_influencia_id;
        $descripcion_problema = $request->descripcion_problema;
        $descripcion_solucion = $request->descripcion_solucion;
        $objetivo_general = $request->objetivo_general;
        $objetivo_especifico = $request->objetivo_especifico;
        $entidad_operadora = $request->entidad_operadora;
        $usuario_id = $request->usuario_id;
        $fecha_registro = $request->fecha_registro;
        $usuario_modificacion_id = $request->usuario_modificacion_id;
        $fecha_modificacion = $request->fecha_modificacion;
        $bloqueo_proyecto = $request->bloqueo_proyecto;
        DB::insert('
        insert into transferencias.transferencias
(
 
  tipo_proyecto_id,
  codigo_tpp,
  codigo_tpp_formato,
  nombre_formal,
  accion_inversion_id,
  objeto_trasferencia,
  localizacion_trasferencia,
  nombre_original,
  tipo_inversion_id,
  fecha_inicio_estimada,
  fecha_fin_estimada,
  estado_inversion_id,
  reglamento_id,
  area_influencia_id,
  descripcion_problema,
  descripcion_solucion,
  objetivo_general,
  objetivo_especifico,
  entidad_operadora,
  usuario_id,
  fecha_registro,
  usuario_modificacion_id,
  fecha_modificacion,
  bloqueo_proyecto
)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
        [
        $tipo_proyecto_id,
        $codigo_tpp,
        $codigo_tpp_formato,
        $nombre_formal,
        $accion_inversion_id,
        $objeto_trasferencia,
        $localizacion_trasferencia,
        $nombre_original,
        $tipo_inversion_id,
        $fecha_inicio_estimada,
        $fecha_fin_estimada,
        $estado_inversion_id,
        $reglamento_id,
        $area_influencia_id,
        $descripcion_problema,
        $descripcion_solucion,
        $objetivo_general,
        $objetivo_especifico,
        $entidad_operadora,
        $usuario_id,
        $fecha_registro,
        $usuario_modificacion_id,
        $fecha_modificacion,
        $bloqueo_proyecto]);
        return response()->json(["message" => "Usuario registrado correctamente"]);

    }

    public function funMostrar($identificador){
        $inversion = DB::select("
        SELECT DISTINCT(i.cod_inversion), CASE WHEN SUBSTRING(i.codigo_sisin,1,1) ='_' THEN CONCAT('  ',SUBSTRING(i.codigo_sisin,2,13)) ELSE i.codigo_sisin END AS codigo_sisin,
 i.nombre_formal, i.fecha_inicio_estimada, i.fecha_fin_estimada, i.cod_tipo_inversion, (ear.cod_tipo_entidad = 6,ear.sigla,ee.sigla) AS sigla_entidades,
 CONCAT('EAR: ', ear.sigla ,' - ', ear.entidad, ' EE: ', ee.sigla,' - ', ee.entidad) AS entidades, ree.cod_ear_ee, cei.estado_inversion AS estado, cei.cod_estado_inversion 
 FROM inversion AS i INNER JOIN rel_inversion_ear_ee AS riee ON i.cod_inversion = riee.cod_inversion 
 AND riee.relacion_visible = 1 
 INNER JOIN rel_ear_ee AS ree ON riee.cod_ear_ee = ree.cod_ear_ee 
 INNER JOIN entidad AS ear ON ree.cod_ear = ear.cod_entidad 
 INNER JOIN entidad AS ee ON ree.cod_ee = ee.cod_entidad 
 INNER JOIN clasif_estado_inversion AS cei ON i.cod_estado_inversion = cei.cod_estado_inversion 
 WHERE (ree.cod_ear = '29' or ree.cod_ee = '29' OR ree.cod_ee in (422,423,424,425,1080,29)) AND i.cod_estado_inversion not in (5,12) 
 and i.cod_inversion=$identificador");
        return $inversion;
    }
    public function funModificar($id,Request $request){
        
    }
    public function funEliminar($id){
        
    }
}
