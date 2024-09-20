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

class DictamenController extends Controller
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
    public function funListarFormulario($id){
        $producto = DB::select("SELECT id, transferencia_id, ear_ee_id, etapa_id,tipo_dictamen_id,(select td.tipo_dictamen from clasificadores.tipo_dictamen td where td.id = tipo_dictamen_id) as tipo_dictamen, fecha_dictamen, tipo_cambio_costos_id, tipo_justificacion_id, justificacion, moneda_id, gestion_registro, informe_tecnico, informe_tecnico_fecha, informe_legal, informe_legal_fecha, resolucion, resolucion_fecha, mae, mae_cargo, mae_ci, mae_documento_designacion, responsable, responsable_ci, responsable_cargo, responsable_unidad, proyecto_fecha_inicio, proyecto_fecha_fin, etapa_fecha_inicio, etapa_fecha_fin, usuario_id, fecha_registro, usuario_modificacion_id, fecha_modificacion, cierre_entidad, usuario_cierre_id, fecha_cierre_dictamen, con_archivo, ruta_archivo, usuario_archivo_id, fecha_archivo, version_id
                                FROM transferencia.dictamenes where estado_id=1 and transferencia_id=$id");
        return response()->json($producto, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }

    public function funListarDictamenRegistro($id){
        $producto = DB::select("select * from transferencia.dictamenes_registros where transferencia_id=$id");
        return response()->json($producto, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }
    
    public function funListarEjecutora($id){
        $entidad = DB::select("select * from clasificadores.instituciones where institucion_padre_id=$id");
        return response()->json($entidad, 200);
        /*return response()->json([
            "status" => true,
            "message" => "information",
            "data" => $producto
        ]);*/

    }

    public function funGuardarFormulario($id, Request $request)
    {
        //if (!$request->has('tipo_dictamen_id')) {
        //    return response()->json(['error' => 'El campo tipo_dictamen_id es requerido'], 400);
        //}
        $trasferencia_id = $id;
        $tipo_dictamen_id = $request->etapa;
        $fecha_dictamen = $request->fecha_registro;
        $fecha_inicio_etapa = $request->fecha_inicio;
        $fecha_termino_etapa = $request->fecha_termino;
       
        $preguntas1 = $request->pregunta_1;  // Corregir nombre de la variable
        $preguntas2 = $request->pregunta_2;  // Corregir nombre de la variable
        $preguntas3 = $request->pregunta_3;  // Corregir nombre de la variable
        
        $result = DB::insert("INSERT INTO transferencia.dictamenes_registros
(transferencia_id, ear_ee_id, etapa_id, tipo_dictamen_id, etapa_estudio_id, pregunta_1, pregunta_2, pregunta_3, respaldo_pregunta_3, fecha_pregunta_3, pregunta_4, respaldo_pregunta_4, fecha_pregunta_4, pregunta_5, respaldo_pregunta_5, fecha_pregunta_5, pregunta_6, respaldo_pregunta_6, fecha_pregunta_6, nombre_original, descripcion_problema, descripcion_solucion, objetivo_general, objetivo_especifico)
VALUES( ?, 0, 0, 1, 0, ?, ?, ?, '', '12/09/2024', false, '', '12/09/2024', false, '', '12/09/2024', false, '', '12/09/2024', '', '', '', '', '');
                                 ",[
            $trasferencia_id,
           // $tipo_dictamen_id,
            $preguntas1,
            $preguntas2,
            $preguntas3
        ]
                                 );

                                 $result = DB::insert("INSERT INTO transferencia.dictamenes (dictamen_id,
                transferencia_id, ear_ee_id, etapa_id, tipo_dictamen_id, 
                fecha_dictamen, tipo_cambio_costos_id, tipo_justificacion_id, justificacion, moneda_id, 
                gestion_registro, informe_tecnico, informe_tecnico_fecha, informe_legal, informe_legal_fecha, 
                resolucion, resolucion_fecha, mae, mae_cargo, mae_ci, mae_documento_designacion, 
                responsable, responsable_ci, responsable_cargo, responsable_unidad, 
                proyecto_fecha_inicio, proyecto_fecha_fin, etapa_fecha_inicio, etapa_fecha_fin, 
                usuario_id, fecha_registro, usuario_modificacion_id, fecha_modificacion, 
                cierre_entidad, usuario_cierre_id, fecha_cierre_dictamen, con_archivo, ruta_archivo, 
                usuario_archivo_id, fecha_archivo, version_id
            ) VALUES (
                1,?, 1, 1,?, ?, 1, 1, 'justi', 1, 2024, 'informe', '12/09/2024', 'legal', '12/09/2024', 
                'resolucion', '13/09/2024', 'Mae', 'Mae Cargo', 'maeci', 'maedocDesig', 'responsable', 
                'responsableCi', 'resp cargo', 'resp unidad', '15/09/2024', '30/09/2024', ?, ?, 1, '12/09/2024', 
                0, '12/09/2024', 1, 1, '12/09/2024', 0, 'ruta', 0, '12/09/2024', 0
            )",[
                                             $trasferencia_id,
                                             $fecha_dictamen,
                                             $fecha_inicio_etapa,
                                             $fecha_termino_etapa
                                         ]
                                                                  );                         
        // Llamada a la primera función para recuperar el dictamen_id
       /* $result = DB::select("
            SELECT * FROM public.dictamen_registro(
                0::integer, 
                ?::integer, 
                1::integer, 
                1::integer, 
                1::integer, 
                1::integer, 
                ?::boolean, 
                ?::boolean, 
                ?::boolean, 
                'jjjjjj'::varchar, 
                '2024-09-10'::date, 
                1::boolean, 
                'lllllllllllllllll'::varchar, 
                '2024-09-10'::date, 
                1::boolean, 
                'ooooooooooo'::varchar, 
                '2024-09-10'::date, 
                1::boolean, 
                'kkkkkk'::varchar, 
                '2024-09-10'::date, 
                ''::varchar, 
                ''::varchar, 
                ''::varchar, 
                ''::varchar, 
                ''::varchar, 
                'A101'::varchar
            );
        ", [
            $trasferencia_id,
            $tipo_dictamen_id,
            $preguntas1,
            $preguntas2,
            $preguntas3
        ]);*/
    
        // Extraer el dictamen_id que está en el tercer campo del resultado
       // $dictamen_id = $result[0]->dictamen_registro[2];  // El índice 2 es el tercer campo (dictamen_id)
    /*
        // Llamada a la segunda función con el dictamen_id recuperado
        DB::select("
            SELECT transferencia.dictamen_insert(
                68::integer,
                1::integer, 
                1::integer, 
                1::integer,
                '18/09/2024'::date,
                1::integer,
                1::integer,
                'funcion'::varchar,
                1::integer,
                1212::integer,
                '121212'::varchar, 
                '18/09/2024'::date, 
                '1212'::varchar, 
                '18/09/2024'::date,
                '1212'::varchar, 
                '18/09/2024'::date, 
                '1212'::varchar, 
                '1212'::varchar, 
                '1221'::varchar, 
                'Documento'::varchar,
                'Responsable'::varchar,
                '11457'::varchar, 
                'responsable'::varchar,
                'responsable unidad'::varchar,
                '11/09/2024'::date,
                '11/09/2024'::date, 
                '11/09/2024'::date, 
                '11/09/2024'::date,
                1::integer, 
                '11/09/2024'::date,
                1::integer, 
                '11/09/2024'::date, 
                1::integer,
                1::integer,
                '11/09/2024'::date,
                1::integer,
                '1'::varchar, 
                1::integer, 
                '11/09/2024'::date, 
                1::integer, 
                'A101'::varchar
            )
        ", [
            $dictamen_id,  // Usar el dictamen_id recuperado
            $trasferencia_id,
            $fecha_dictamen,
            $fecha_inicio_etapa,
            $fecha_termino_etapa
        ]);*/
        return response()->json(["message" => "Datos guardados correctamente"]);
    }

    public function funGuardarDictamenRegistro($id, Request $request)
    {
        $fecha_dictamen = $request->fecha_dictamen;
        $fecha_inicio_etapa = $request->fecha_inicio_etapa;
        $fecha_termino_etapa = $request->fecha_termino_etapa;
        $tipo_dictamen = $request->tipo_dictamen;
    
        DB::insert("
            INSERT INTO transferencia.dictamenes (
                id, transferencia_id, ear_ee_id, etapa_id, tipo_dictamen_id, 
                fecha_dictamen, tipo_cambio_costos_id, tipo_justificacion_id, justificacion, moneda_id, 
                gestion_registro, informe_tecnico, informe_tecnico_fecha, informe_legal, informe_legal_fecha, 
                resolucion, resolucion_fecha, mae, mae_cargo, mae_ci, mae_documento_designacion, 
                responsable, responsable_ci, responsable_cargo, responsable_unidad, 
                proyecto_fecha_inicio, proyecto_fecha_fin, etapa_fecha_inicio, etapa_fecha_fin, 
                usuario_id, fecha_registro, usuario_modificacion_id, fecha_modificacion, 
                cierre_entidad, usuario_cierre_id, fecha_cierre_dictamen, con_archivo, ruta_archivo, 
                usuario_archivo_id, fecha_archivo, version_id
            ) VALUES (
                0, ?, 1, 1, ?, ?, 1, 1, 'justi', 1, 2024, 'informe', '12/09/2024', 'legal', '12/09/2024', 
                'resolucion', '13/09/2024', 'Mae', 'Mae Cargo', 'maeci', 'maedocDesig', 'responsable', 
                'responsableCi', 'resp cargo', 'resp unidad', '15/09/2024', '30/09/2024', ?, ?, 1, '12/09/2024', 
                0, '12/09/2024', 1, 1, '12/09/2024', 0, 'ruta', 0, '12/09/2024', 0
            )
        ", [
            $id,
            $tipo_dictamen,
            $fecha_dictamen,
            $fecha_inicio_etapa,
            $fecha_termino_etapa
        ]);
            // Respuesta JSON
    return response()->json(["message" => "Datos guardados correctamente"]);
    }

    public function funMostrar($identificador){
        
    }
    public function funModificar($id,Request $request){
        
    }
    public function funEliminarFormulario($id)
        {
            $formulario = DB::select("
            update transferencia.dictamenes  set estado_id = 0 where transferencia.dictamenes.id = $id
             ");
             return response()->json(["message" => "Formulario eliminado"]);
        }
    
}
