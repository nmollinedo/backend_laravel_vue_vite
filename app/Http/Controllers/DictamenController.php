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
        $producto = DB::select("select * from transferencia.dictamenes where transferencia_id=$id");
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

    public function funGuardarDictamen($id, Request $request)
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
    public function funEliminar($id){
        
    }
}
