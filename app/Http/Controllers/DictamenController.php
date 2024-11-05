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
        $formulario = DB::select("SELECT id, dictamen_id,transferencia_id, ear_ee_id, etapa_id,tipo_dictamen_id,(select td.descrip_tipo_dictamen from clasificadores.tipo_dictamen td where td.id = tipo_dictamen_id) as tipo_dictamen, fecha_dictamen, tipo_cambio_costos_id, tipo_justificacion_id, justificacion, moneda_id, gestion_registro, informe_tecnico, informe_tecnico_fecha, informe_legal, informe_legal_fecha, resolucion, resolucion_fecha, mae, mae_cargo, mae_ci, mae_documento_designacion, responsable,
        responsable_ci, responsable_cargo, responsable_unidad, proyecto_fecha_inicio, proyecto_fecha_fin, etapa_fecha_inicio, etapa_fecha_fin, usuario_id, fecha_registro, usuario_modificacion_id, 
        fecha_modificacion, cierre_entidad, usuario_cierre_id, fecha_cierre_dictamen, con_archivo, ruta_archivo, usuario_archivo_id, fecha_archivo, version_id,(select t.bloqueo_proyecto from transferencia.transferencias t where t.id=$id)
        FROM transferencia.dictamenes where estado_id=1 and transferencia_id=$id order by id desc");
        return response()->json($formulario, 200);
 
    }

    public function funListarFormularioTodo(){
        $formulario = DB::select("SELECT id, dictamen_id,transferencia_id, ear_ee_id, etapa_id,tipo_dictamen_id,(select td.descrip_tipo_dictamen from clasificadores.tipo_dictamen td where td.id = tipo_dictamen_id) as tipo_dictamen, fecha_dictamen, tipo_cambio_costos_id, tipo_justificacion_id, justificacion, moneda_id, gestion_registro, informe_tecnico, informe_tecnico_fecha, informe_legal, informe_legal_fecha, resolucion, resolucion_fecha, mae, mae_cargo, mae_ci, mae_documento_designacion, responsable,
        responsable_ci, responsable_cargo, responsable_unidad, proyecto_fecha_inicio, proyecto_fecha_fin, etapa_fecha_inicio, etapa_fecha_fin, usuario_id, fecha_registro, usuario_modificacion_id, 
        fecha_modificacion, cierre_entidad, usuario_cierre_id, fecha_cierre_dictamen, con_archivo, ruta_archivo, usuario_archivo_id, fecha_archivo, version_id
        FROM transferencia.dictamenes where estado_id=1");
        return response()->json($formulario, 200);
 
    }

    public function funListarDictamenRegistro($id){
        $producto = DB::select("select * from transferencia.dictamenes_registros where transferencia_id=$id");
        return response()->json($producto, 200);
    }
    
    public function funListarEjecutora($id){
        $entidad = DB::select("select * from clasificadores.instituciones where institucion_padre_id=$id");
        return response()->json($entidad, 200);
    }

    public function funVerificarFormularioActivo($id){
        $entidad = DB::select("select d.id,d.dictamen_id,d.transferencia_id,d.ear_ee_id,d.tipo_dictamen_id,d.cierre_entidad 
           from transferencia.dictamenes d where d.transferencia_id = $id and d.cierre_entidad = 0");
        return response()->json($entidad, 200);
    }

    public function funListarFormularioComponenteId($id){
        $componente = DB::select("select tdc.dictamen_id,tdc.transferencia_id,tdc.componente_id,tdc.monto_aporte_local,tdc.monto_cofinanciamiento,tdc.monto_finan_externo,tdc.monto_otros,
(select c.componente from clasificadores.componente c where c.id=tdc.componente_id)as componente
from transferencia.transferencias_dictamen_costos tdc where tdc.vigente=true and tdc.transferencia_id=$id");
        return response()->json($componente, 200);

    }

    public function funGuardarDictamen(Request $request){
        $dictamen_id = $request->dictamen_id;
        $transferencia_id = $request->transferencia_id;
        $componente_id = $request->componente_id;
        $monto_aporte_local= $request->monto_aporte_local;
        $monto_cofinanciamiento = $request->monto_cofinanciamiento;
        $monto_finan_externo = $request->monto_finan_externo;
        $monto_otros = $request->monto_otros;

        DB::insert(' INSERT INTO transferencia.transferencias_dictamen_costos (
           dictamen_id, transferencia_id, componente_id,
           monto_aporte_local, monto_cofinanciamiento, monto_finan_externo, monto_otros,
           usuario_id, fecha_registro,vigente
       ) 
       VALUES (
           ?, ?,?,
           ?, ?, ?, ?,
           1, now(),true
       );',[$dictamen_id,$transferencia_id,$componente_id,$monto_aporte_local,$monto_cofinanciamiento,$monto_finan_externo,$monto_otros]);
        return response()->json(["message" => "Datos guardados correctamente"]);
    }

    public function funEliminarDictamenCosto($transferencia_id,$componente_id){
        DB::select(" UPDATE transferencia.transferencias_dictamen_costos
       SET vigente = false
       where transferencia_id=$transferencia_id and componente_id=$componente_id");

        return response()->json(["message" => "Datos eliminados correctamente"]);
    }

    public function funGuardarFormularioComponenteCosto(Request $request){
        $dictamen_id = $request->dictamen_id;
        $transferencia_id = $request->transferencia_id;
        $componente_id = $request->componente_id;
        $monto_aporte_local= $request->monto_aporte_local;
        $monto_cofinanciamiento = $request->monto_cofinanciamiento;
        $monto_finan_externo = $request->monto_finan_externo;
        $monto_otros = $request->monto_otros;
/*
        DB::select('INSERT INTO transferencia.transferencias_dictamen_costos
(dictamen_id, transferencia_id, componente_id, monto_aporte_local, monto_cofinanciamiento, monto_finan_externo, monto_otros,vigente)
VALUES(?, ?, ?, ? , ?, ?, ?, ?)', [
            $dictamen_id, $transferencia_id, $componente_id, 
            $monto_aporte_local, $monto_cofinanciamiento, 
            $monto_finan_externo, $monto_otros, 
            true
        ]
    );
*/
        DB::select('
                SELECT * FROM transferencia.transferencias_formulario_componente(
                    ?::integer, ?::integer, 2, ?::integer, 
                    ?::numeric, ?::numeric, ?::numeric, ?::numeric, 
                    1, ?::date, 1, ?::date, ?::varchar
                )', [
                    $dictamen_id, $transferencia_id, $componente_id, 
                    $monto_aporte_local, $monto_cofinanciamiento, 
                    $monto_finan_externo, $monto_otros, 
                    '2024-09-11', '2024-09-11', 'A101'
                ]
            );

        return response()->json(["message" => "Datos guardados correctamente"]);
    }

    public function funModificarFormularioCosto($id,Request $request){
       
        $componente_id = $request->componente_id;
        $monto_aporte_local= $request->monto_aporte_local;
        $monto_cofinanciamiento = $request->monto_cofinanciamiento;
        $monto_finan_externo = $request->monto_finan_externo;
        $monto_otros = $request->monto_otros;
        DB::select("UPDATE transferencia.transferencias_dictamen_costos
                        SET monto_aporte_local=$monto_aporte_local,
                        monto_cofinanciamiento=$monto_cofinanciamiento, monto_finan_externo=$monto_finan_externo, monto_otros=$monto_otros 
                        where transferencia_id = $id  and componente_id = $componente_id and vigente=true
                    ");

        return response()->json(["message" => "Datos actualizados correctamente"]);
    }

    public function funCerrarFormularioCosto(Request $request){
        $id = $request->id;
        $transferencia_id = $request->transferencia_id;
      
        DB::select('
                SELECT * FROM transferencia.cerrar_formulario_costo(
                    ?::integer, ?::integer
                )', [
                    $id, $transferencia_id
                ]
            );

        return response()->json(["message" => "Datos guardados correctamente"]);
    }


    public function funCerrarFormularioCostoFecha(Request $request){
        $id = $request->id;
        $transferencia_id = $request->transferencia_id;
      
        DB::select('
                SELECT * FROM transferencia.cerrar_formulario_costo_fecha(
                    ?::integer, ?::integer
                )', [
                    $id, $transferencia_id
                ]
            );

        return response()->json(["message" => "Datos guardados correctamente"]);
    }

    public function funCerrarFormularioFecha(Request $request){
        $id = $request->id;
        $transferencia_id = $request->transferencia_id;
      
        DB::select('
                SELECT * FROM transferencia.cerrar_formulario_fecha(
                    ?::integer, ?::integer
                )', [
                    $id, $transferencia_id
                ]
            );

        return response()->json(["message" => "Datos guardados correctamente"]);
    }

    public function funGuardarFormulario($id, Request $request)
    
    {
        // Validar los datos recibidos
    $validated = $request->validate([
        'etapa' => 'required|integer',
        'fecha_registro' => 'required|date_format:d/m/Y',
        'fecha_inicio' => 'required|date_format:d/m/Y',
        'fecha_termino' => 'required|date_format:d/m/Y',
        'pregunta_1' => 'required|boolean',
        'pregunta_2' => 'required|boolean',
        'pregunta_3' => 'required|boolean',
        'respaldo_pregunta_3' => 'required|string',
        'fecha_pregunta_3' => 'required|date_format:d/m/Y'
    ]);

    $trasferencia_id = $id;
    $tipo_dictamen_id = $request->etapa;  // Usando $request->etapa
    $fecha_dictamen = $request->fecha_registro;
    $fecha_inicio = $request->fecha_inicio;
    $fecha_termino = $request->fecha_termino;
    $preguntas1 = $request->pregunta_1;
    $preguntas2 = $request->pregunta_2;
    $preguntas3 = $request->pregunta_3;
    $respaldo_preguntas_3 = $request->respaldo_pregunta_3;
    $fecha_preguntas_3 = $request->fecha_pregunta_3;
    $preguntas4 = $request->pregunta_4;
    $respaldo_preguntas_4 = $request->respaldo_pregunta_4;
    $fecha_preguntas_4 = $request->fecha_pregunta_4;
    $preguntas5 = $request->pregunta_5;
    $respaldo_preguntas_5 = $request->respaldo_pregunta_5;
    $fecha_preguntas_5 = $request->fecha_pregunta_5;
    $preguntas6 = $request->pregunta_6;
    $respaldo_preguntas_6 = $request->respaldo_pregunta_6;
    $fecha_preguntas_6 = $request->fecha_pregunta_6;
    $mae = $request->mae;
    $mae_cargo = $request->mae_cargo;
    $mae_ci = $request->mae_ci;
    $mae_documento_designacion = $request->mae_documento_designacion;
    $responsable = $request->responsable;
    $responsable_ci = $request->responsable_ci;
    $responsable_cargo = $request->responsable_cargo;
    $responsable_unidad = $request->responsable_unidad;


    // Llamada a la función de PostgreSQL
    $result = DB::select("
        SELECT * FROM transferencia.dictamen_registro(
            0::integer, 
            ?::integer, 
            1::integer, 
            1::integer, 
            ?::integer, 
            1::integer, 
            ?::boolean, 
            ?::boolean, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ''::varchar, 
            ''::varchar, 
            ''::varchar, 
            ''::varchar, 
            ''::varchar, 
            'A101'::varchar
        ) as codigo;
    ", [
        $trasferencia_id,
        $tipo_dictamen_id,
        $preguntas1,
        $preguntas2,
        $preguntas3,
        $respaldo_preguntas_3,
        $fecha_preguntas_3,
        $preguntas4,
        $respaldo_preguntas_4,
        $fecha_preguntas_4,
        $preguntas5,
        $respaldo_preguntas_5,
        $fecha_preguntas_5,
        $preguntas6,
        $respaldo_preguntas_6,
        $fecha_preguntas_6
    ]);
    //print_r($result);
    //dd($result);
        // Extraer el dictamen_id que está en el tercer campo del resultado
        $dictamen_id = $result[0]->codigo;  // El índice 2 es el tercer campo (dictamen_id)
    
        // Llamada a la segunda función con el dictamen_id recuperado
        DB::select("
            SELECT transferencia.dictamen_insert(
            ?::integer,
            ?::integer,
            1::integer, 
            1::integer, 
            1::integer,
            ?::date,
            1::integer,
            1::integer,
            'tareas'::varchar,
            1::integer,
            1212::integer,
            '121212'::varchar, 
            '18/09/2024'::date, 
            '1212'::varchar, 
            '18/09/2024'::date,
            '1212'::varchar, 
            '18/09/2024'::date, 
            ?::varchar, 
            ?::varchar, 
            ?::varchar, 
            ?::varchar,
            ?::varchar,
            ?::varchar, 
            ?::varchar,
            ?::varchar,
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
            'A101'::varchar)
        ", [
            $dictamen_id,  // Usar el dictamen_id recuperado
            $trasferencia_id,
            $fecha_dictamen,
            $mae,
            $mae_cargo,
            $mae_ci,
            $mae_documento_designacion,
            $responsable,
            $responsable_ci,
            $responsable_cargo,
            $responsable_unidad
        ]);
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

    public function funMostrarFormulario($id){
    /*    $formulario = DB::select("select d.id,d.dictamen_id,d.transferencia_id,d.ear_ee_id,d.etapa_id,d.tipo_dictamen_id,d.fecha_dictamen,d.tipo_justificacion_id,d.justificacion,d.informe_tecnico,d.informe_tecnico_fecha,
                        d.informe_tecnico_fecha,d.informe_legal ,d.informe_legal ,d.informe_legal_fecha ,d.resolucion ,d.resolucion_fecha ,d.mae ,d.mae_cargo,d.mae_ci ,d.mae_documento_designacion ,d.responsable ,
                        d.responsable_ci , d.responsable_cargo ,d.responsable_unidad ,d.proyecto_fecha_inicio ,d.proyecto_fecha_fin ,d.fecha_registro ,dr.pregunta_1 ,dr.pregunta_2 ,dr.pregunta_3 ,
                        dr.respaldo_pregunta_3 ,dr.fecha_pregunta_3 ,dr.pregunta_4 ,dr.respaldo_pregunta_4 ,dr.fecha_pregunta_4 ,dr.pregunta_5 ,dr.respaldo_pregunta_5 ,dr.fecha_pregunta_5 ,
                        dr.pregunta_6 ,dr.respaldo_pregunta_6 ,dr.fecha_pregunta_6 ,dr.nombre_original ,dr.descripcion_problema ,dr.descripcion_solucion 
                        from transferencia.dictamenes_registros dr, transferencia.dictamenes d where dr.id = d.dictamen_id and d.cierre_entidad=0 and d.dictamen_id =$id");*/
                        $formulario = DB::select("select * 
                        from transferencia.dictamenes_registros dr, transferencia.dictamenes d where dr.id = d.dictamen_id and d.dictamen_id =$id");                
        return response()->json($formulario, 200);
    }

    public function funMostrarFormularioEdit($id){
            $formulario = DB::select("select d.id,d.dictamen_id,d.transferencia_id,d.ear_ee_id,d.etapa_id,d.tipo_dictamen_id,d.fecha_dictamen,d.tipo_justificacion_id,d.justificacion,d.informe_tecnico,d.informe_tecnico_fecha,
                            d.informe_tecnico_fecha,d.informe_legal ,d.informe_legal ,d.informe_legal_fecha ,d.resolucion ,d.resolucion_fecha ,d.mae ,d.mae_cargo,d.mae_ci ,d.mae_documento_designacion ,d.responsable ,
                            d.responsable_ci , d.responsable_cargo ,d.responsable_unidad ,d.proyecto_fecha_inicio ,d.proyecto_fecha_fin ,d.fecha_registro ,dr.pregunta_1 ,dr.pregunta_2 ,dr.pregunta_3 ,
                            dr.respaldo_pregunta_3 ,dr.fecha_pregunta_3 ,dr.pregunta_4 ,dr.respaldo_pregunta_4 ,dr.fecha_pregunta_4 ,dr.pregunta_5 ,dr.respaldo_pregunta_5 ,dr.fecha_pregunta_5 ,
                            dr.pregunta_6 ,dr.respaldo_pregunta_6 ,dr.fecha_pregunta_6 ,dr.nombre_original ,dr.descripcion_problema ,dr.descripcion_solucion 
                            from transferencia.dictamenes_registros dr, transferencia.dictamenes d where dr.id = d.dictamen_id and d.cierre_entidad=0 and d.dictamen_id =$id");
                                           
            return response()->json($formulario, 200);
        }

    public function funMostrarEditFecha($id){
        $formulario = DB::select("select d.id ,d.dictamen_id ,d.transferencia_id ,d.tipo_dictamen_id,d.cierre_entidad, d.fecha_dictamen ,d.fecha_registro ,d.proyecto_fecha_inicio ,d.proyecto_fecha_fin,d.estado_id,
                                d.mae ,d.mae_cargo ,d.mae_ci ,d.mae_documento_designacion ,d.responsable,d.responsable_cargo, d.responsable_unidad,d.responsable_ci 
                                from transferencia.dictamenes_registros dr, transferencia.dictamenes d 
                                where dr.id = d.dictamen_id and d.dictamen_id =$id and cierre_entidad = 0");
        return response()->json($formulario, 200);
    }
    public function funGuardarModificacion($id,Request $request){
             // Validar los datos recibidos
    /*$validated = $request->validate([
        'etapa' => 'required|integer',
        'fecha_registro' => 'required|date_format:d/m/Y',
        'fecha_inicio' => 'required|date_format:d/m/Y',
        'fecha_termino' => 'required|date_format:d/m/Y',
        'pregunta_1' => 'required|boolean',
        'pregunta_2' => 'required|boolean',
        'pregunta_3' => 'required|boolean',
        'respaldo_pregunta_3' => 'required|string',
        'fecha_pregunta_3' => 'required|date_format:d/m/Y'
    ]);*/

    $dictamen_id = $id;
    $transferencia_id = $request->transferencia_id;
    $tipo_dictamen_id = $request->etapa;  // Usando $request->etapa
    $fecha_dictamen = $request->fecha_registro;
    $fecha_inicio = $request->fecha_inicio;
    $fecha_termino = $request->fecha_termino;
    $preguntas1 = $request->pregunta_1;
    $preguntas2 = $request->pregunta_2;
    $preguntas3 = $request->pregunta_3;
    $respaldo_preguntas_3 = $request->respaldo_pregunta_3;
    $fecha_preguntas_3 = $request->fecha_pregunta_3;
    $preguntas4 = $request->pregunta_4;
    $respaldo_preguntas_4 = $request->respaldo_pregunta_4;
    $fecha_preguntas_4 = $request->fecha_pregunta_4;
    $preguntas5 = $request->pregunta_5;
    $respaldo_preguntas_5 = $request->respaldo_pregunta_5;
    $fecha_preguntas_5 = $request->fecha_pregunta_5;
    $preguntas6 = $request->pregunta_6;
    $respaldo_preguntas_6 = $request->respaldo_pregunta_6;
    $fecha_preguntas_6 = $request->fecha_pregunta_6;
    $mae = $request->mae;
    $mae_cargo = $request->mae_cargo;
    $mae_ci = $request->mae_ci;
    $mae_documento_designacion = $request->mae_documento_designacion;
    $responsable = $request->responsable;
    $responsable_ci = $request->responsable_ci;
    $responsable_cargo = $request->responsable_cargo;
    $responsable_unidad = $request->responsable_unidad;


    // Llamada a la función de PostgreSQL
    $result = DB::select("
        SELECT * FROM transferencia.dictamen_registro(
            ?::integer, 
            0::integer, 
            1::integer, 
            1::integer, 
            ?::integer, 
            1::integer, 
            ?::boolean, 
            ?::boolean, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ?::boolean, 
            ?::varchar, 
            ?::date, 
            ''::varchar, 
            ''::varchar, 
            ''::varchar, 
            ''::varchar, 
            ''::varchar, 
            'M101'::varchar
        ) as codigo;
    ", [
        $dictamen_id,
        //$transferencia_id,
        $tipo_dictamen_id,
        $preguntas1,
        $preguntas2,
        $preguntas3,
        $respaldo_preguntas_3,
        $fecha_preguntas_3,
        $preguntas4,
        $respaldo_preguntas_4,
        $fecha_preguntas_4,
        $preguntas5,
        $respaldo_preguntas_5,
        $fecha_preguntas_5,
        $preguntas6,
        $respaldo_preguntas_6,
        $fecha_preguntas_6
    ]);

     // Llamada a la segunda función con el dictamen_id recuperado
     DB::select("
     SELECT transferencia.dictamen_insert(
     ?::integer,
     ?::integer,
     1::integer, 
     1::integer, 
     1::integer,
     ?::date,
     1::integer,
     1::integer,
     'tareas'::varchar,
     1::integer,
     1212::integer,
     '121212'::varchar, 
     '18/09/2024'::date, 
     '1212'::varchar, 
     '18/09/2024'::date,
     '1212'::varchar, 
     '18/09/2024'::date, 
     ?::varchar, 
     ?::varchar, 
     ?::varchar, 
     ?::varchar,
     ?::varchar,
     ?::varchar, 
     ?::varchar,
     ?::varchar,
     ?::date,
     ?::date, 
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
     'M101'::varchar)
 ", [
     $dictamen_id,  // Usar el dictamen_id recuperado
     $transferencia_id,
     $fecha_dictamen,
     $mae,
     $mae_cargo,
     $mae_ci,
     $mae_documento_designacion,
     $responsable,
     $responsable_ci,
     $responsable_cargo,
     $responsable_unidad,
     $fecha_inicio,
     $fecha_termino
 ]);
    return response()->json(["message" => "Formulario modificado"]);
    }


    public function funGuardarModCostoFecha($id,Request $request){
       

        $dictamen_id = $id;
        $transferencia_id =$request->transferencia_id;
        $tipo_dictamen_id = $request->tipo_dictamen_id;  // Usando $request->etapa
        $fecha_dictamen = $request->fecha_registro;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_termino = $request->fecha_termino;
        $preguntas1 = $request->pregunta_1;
        $preguntas2 = $request->pregunta_2;
        $preguntas3 = $request->pregunta_3;
        $respaldo_preguntas_3 = $request->respaldo_pregunta_3;
        $fecha_preguntas_3 = $request->fecha_pregunta_3;
        $preguntas4 = $request->pregunta_4;
        $respaldo_preguntas_4 = $request->respaldo_pregunta_4;
        $fecha_preguntas_4 = $request->fecha_pregunta_4;
        $preguntas5 = $request->pregunta_5;
        $respaldo_preguntas_5 = $request->respaldo_pregunta_5;
        $fecha_preguntas_5 = $request->fecha_pregunta_5;
        $preguntas6 = $request->pregunta_6;
        $respaldo_preguntas_6 = $request->respaldo_pregunta_6;
        $fecha_preguntas_6 = $request->fecha_pregunta_6;
        $mae = $request->mae;
        $mae_cargo = $request->mae_cargo;
        $mae_ci = $request->mae_ci;
        $mae_documento_designacion = $request->mae_documento_designacion;
        $responsable = $request->responsable;
        $responsable_ci = $request->responsable_ci;
        $responsable_cargo = $request->responsable_cargo;
        $responsable_unidad = $request->responsable_unidad;


        // Llamada a la segunda función con el dictamen_id recuperado
        DB::select("
        SELECT transferencia.dictamen_insert(
        ?::integer,
        ?::integer,
        1::integer, 
        1::integer, 
        ?::integer,
        ?::date,
        1::integer,
        1::integer,
        'tareas'::varchar,
        1::integer,
        1212::integer,
        '121212'::varchar, 
        '18/09/2024'::date, 
        '1212'::varchar, 
        '18/09/2024'::date,
        '1212'::varchar, 
        '18/09/2024'::date, 
        ?::varchar, 
        ?::varchar, 
        ?::varchar, 
        ?::varchar,
        ?::varchar,
        ?::varchar, 
        ?::varchar,
        ?::varchar,
        ?::date,
        ?::date, 
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
        'M103'::varchar)
        ", [
        $dictamen_id,  // Usar el dictamen_id recuperado
        $transferencia_id,
        $tipo_dictamen_id,
        $fecha_dictamen,
        $mae,
        $mae_cargo,
        $mae_ci,
        $mae_documento_designacion,
        $responsable,
        $responsable_ci,
        $responsable_cargo,
        $responsable_unidad,
        $fecha_inicio,
        $fecha_termino
        ]);
        return response()->json(["message" => "Formulario modificado"]);
        }

    public function funGuardarFecha($id,Request $request){
        // Validar los datos recibidos
        /*$validated = $request->validate([
        'etapa' => 'required|integer',
        'fecha_registro' => 'required|date_format:d/m/Y',
        'fecha_inicio' => 'required|date_format:d/m/Y',
        'fecha_termino' => 'required|date_format:d/m/Y',
        'pregunta_1' => 'required|boolean',
        'pregunta_2' => 'required|boolean',
        'pregunta_3' => 'required|boolean',
        'respaldo_pregunta_3' => 'required|string',
        'fecha_pregunta_3' => 'required|date_format:d/m/Y'
        ]);*/

        $dictamen_id = $id;
        $transferencia_id =$request->transferencia_id;
        $tipo_dictamen_id = $request->tipo_dictamen_id;  // Usando $request->etapa
        $fecha_dictamen = $request->fecha_registro;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_termino = $request->fecha_termino;
        $preguntas1 = $request->pregunta_1;
        $preguntas2 = $request->pregunta_2;
        $preguntas3 = $request->pregunta_3;
        $respaldo_preguntas_3 = $request->respaldo_pregunta_3;
        $fecha_preguntas_3 = $request->fecha_pregunta_3;
        $preguntas4 = $request->pregunta_4;
        $respaldo_preguntas_4 = $request->respaldo_pregunta_4;
        $fecha_preguntas_4 = $request->fecha_pregunta_4;
        $preguntas5 = $request->pregunta_5;
        $respaldo_preguntas_5 = $request->respaldo_pregunta_5;
        $fecha_preguntas_5 = $request->fecha_pregunta_5;
        $preguntas6 = $request->pregunta_6;
        $respaldo_preguntas_6 = $request->respaldo_pregunta_6;
        $fecha_preguntas_6 = $request->fecha_pregunta_6;
        $mae = $request->mae;
        $mae_cargo = $request->mae_cargo;
        $mae_ci = $request->mae_ci;
        $mae_documento_designacion = $request->mae_documento_designacion;
        $responsable = $request->responsable;
        $responsable_ci = $request->responsable_ci;
        $responsable_cargo = $request->responsable_cargo;
        $responsable_unidad = $request->responsable_unidad;


        // Llamada a la segunda función con el dictamen_id recuperado
        DB::select("
        SELECT transferencia.dictamen_insert(
        ?::integer,
        ?::integer,
        1::integer, 
        1::integer, 
        ?::integer,
        ?::date,
        1::integer,
        1::integer,
        'tareas'::varchar,
        1::integer,
        1212::integer,
        '121212'::varchar, 
        '18/09/2024'::date, 
        '1212'::varchar, 
        '18/09/2024'::date,
        '1212'::varchar, 
        '18/09/2024'::date, 
        ?::varchar, 
        ?::varchar, 
        ?::varchar, 
        ?::varchar,
        ?::varchar,
        ?::varchar, 
        ?::varchar,
        ?::varchar,
        ?::date,
        ?::date, 
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
        'M102'::varchar)
        ", [
        $dictamen_id,  // Usar el dictamen_id recuperado
        $transferencia_id,
        $tipo_dictamen_id,
        $fecha_dictamen,
        $mae,
        $mae_cargo,
        $mae_ci,
        $mae_documento_designacion,
        $responsable,
        $responsable_ci,
        $responsable_cargo,
        $responsable_unidad,
        $fecha_inicio,
        $fecha_termino
        ]);
        return response()->json(["message" => "Formulario modificado"]);
        }

    public function funGuardarEditFecha($id,Request $request){
                            // Validar los datos recibidos
                    /*$validated = $request->validate([
                    'etapa' => 'required|integer',
                    'fecha_registro' => 'required|date_format:d/m/Y',
                    'fecha_inicio' => 'required|date_format:d/m/Y',
                    'fecha_termino' => 'required|date_format:d/m/Y',
                    'pregunta_1' => 'required|boolean',
                    'pregunta_2' => 'required|boolean',
                    'pregunta_3' => 'required|boolean',
                    'respaldo_pregunta_3' => 'required|string',
                    'fecha_pregunta_3' => 'required|date_format:d/m/Y'
                    ]);*/

                    $dictamen_id = $id;
                    $transferencia_id =$request->transferencia_id;
                    $tipo_dictamen_id = $request->etapa;  // Usando $request->etapa
                    $fecha_dictamen = $request->fecha_registro;
                    $fecha_inicio = $request->fecha_inicio;
                    $fecha_termino = $request->fecha_termino;
                    $preguntas1 = $request->pregunta_1;
                    $preguntas2 = $request->pregunta_2;
                    $preguntas3 = $request->pregunta_3;
                    $respaldo_preguntas_3 = $request->respaldo_pregunta_3;
                    $fecha_preguntas_3 = $request->fecha_pregunta_3;
                    $preguntas4 = $request->pregunta_4;
                    $respaldo_preguntas_4 = $request->respaldo_pregunta_4;
                    $fecha_preguntas_4 = $request->fecha_pregunta_4;
                    $preguntas5 = $request->pregunta_5;
                    $respaldo_preguntas_5 = $request->respaldo_pregunta_5;
                    $fecha_preguntas_5 = $request->fecha_pregunta_5;
                    $preguntas6 = $request->pregunta_6;
                    $respaldo_preguntas_6 = $request->respaldo_pregunta_6;
                    $fecha_preguntas_6 = $request->fecha_pregunta_6;
                    $mae = $request->mae;
                    $mae_cargo = $request->mae_cargo;
                    $mae_ci = $request->mae_ci;
                    $mae_documento_designacion = $request->mae_documento_designacion;
                    $responsable = $request->responsable;
                    $responsable_ci = $request->responsable_ci;
                    $responsable_cargo = $request->responsable_cargo;
                    $responsable_unidad = $request->responsable_unidad;


                    // Llamada a la segunda función con el dictamen_id recuperado
                    DB::select("
                    SELECT transferencia.dictamen_insert(
                    ?::integer,
                    ?::integer,
                    1::integer, 
                    1::integer, 
                    ?::integer,
                    ?::date,
                    1::integer,
                    1::integer,
                    'tareas'::varchar,
                    1::integer,
                    1212::integer,
                    '121212'::varchar, 
                    '18/09/2024'::date, 
                    '1212'::varchar, 
                    '18/09/2024'::date,
                    '1212'::varchar, 
                    '18/09/2024'::date, 
                    ?::varchar, 
                    ?::varchar, 
                    ?::varchar, 
                    ?::varchar,
                    ?::varchar,
                    ?::varchar, 
                    ?::varchar,
                    ?::varchar,
                    ?::date,
                    ?::date, 
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
                    'M103'::varchar)
                    ", [
                    $dictamen_id,  // Usar el dictamen_id recuperado
                    $transferencia_id,
                    $tipo_dictamen_id,
                    $fecha_dictamen,
                    $mae,
                    $mae_cargo,
                    $mae_ci,
                    $mae_documento_designacion,
                    $responsable,
                    $responsable_ci,
                    $responsable_cargo,
                    $responsable_unidad,
                    $fecha_inicio,
                    $fecha_termino
                    ]);
                return response()->json(["message" => "Formulario modificado"]);
                }
    
    public function funEliminarFormulario($id,$transferencia_id)
        {
            $formulario = DB::select("select * from transferencia.borrar_formulario($id::integer, $transferencia_id::integer);
             ");
          /*   $formulario = DB::select("
            delete from transferencia.dictamenes_registros where transferencia.dictamenes_registros.id = $id
             ");*/
             return response()->json(["message" => "Formulario eliminado"]);
        }


        public function funEliminarCierre($id,Request $request)
        {   $transferencia_id = $request->transferencia_id;
            $valor=$transferencia_id;
            $formulario = DB::select("SELECT transferencia.eliminar_formulario($id::integer, $valor::integer); ");
          /*   $formulario = DB::select("
            delete from transferencia.dictamenes_registros  where transferencia.dictamenes_registros.id = $id
             ");
             $formulario = DB::select("
            update transferencia.transferencias  set estado_id = 1 where transferencia.transferencias.id = $valor
             ");*/
             return response()->json(["message" => "Cierre Formulario eliminado"]);
        }    
    
}
