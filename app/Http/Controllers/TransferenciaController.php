<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferenciaController extends Controller
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

    public function funListarTransferencia($id){
        $transferencia = DB::select("SELECT  
            tra.id, 
            tra.nombre_formal AS nombre_tpp, 
            tra.nombre_formal, 
            tra.codigo_tpp_formato AS codigo_tpp, 
            tra.objeto_trasferencia AS objeto, 
            tra.localizacion_trasferencia AS localizacion, 
            tra.nombre_original AS denominacion_convenio, 
            tra.fecha_inicio, 
            tra.fecha_termino, 
            tra.area_id, 
            tra.entidad_operadora_id, 
            tra.descripcion, 
            p.descrip_plan AS plan, 
            p2.descrip_programa AS programa, 
            tra.plan_id, 
            tra.programa_id, 
            tra.departamento_id AS departamento, 
            tra.municipio_id AS municipio, 
            tra.poblacion_id, 
            tra.cobertura, 
            tra.poblacion, 
            tra.entidad_ejecutora, 
            ei.estado_inversion AS estado, 
            tra.estado_id, 
            i.nombre AS entidad,
            ree.ear_id,
            ree.ee_id,
            tra.bloqueo_proyecto 
        FROM 
            transferencia.transferencias AS tra 
            LEFT JOIN clasificadores.planes p ON p.id = tra.plan_id 
            LEFT JOIN clasificadores.programas p2 ON p2.id = tra.programa_id 
            LEFT JOIN clasificadores.estado_inversion ei ON ei.id = tra.estado_id 
            LEFT JOIN transferencia.rel_transferencia_ear_ee rtee ON rtee.transferencia_id = tra.id
            LEFT JOIN transferencia.rel_ear_ee ree ON rtee.ear_ee_id = ree.id 
            LEFT JOIN clasificadores.instituciones i ON i.id = ree.ee_id
            
        WHERE 
            ree.ear_id = $id
            AND tra.estado_id IN (1, 2)
            -- Hacer opcional el estado de la institución
            AND (i.estado_id = 1 OR i.estado_id IS NULL)
        ORDER BY 
            tra.id DESC; 
        ");
        return response()->json($transferencia, 200);


    }

    

    public function funListarTransferenciaFormulario($id){
        $transferencia = DB::select("SELECT  
     distinct (tra.id),  
    tra.nombre_formal AS nombre_tpp, 
    tra.nombre_formal, 
    tra.codigo_tpp_formato AS codigo_tpp, 
    tra.objeto_trasferencia AS objeto, 
    tra.localizacion_trasferencia AS localizacion, 
    tra.nombre_original AS denominacion_convenio, 
    tra.fecha_inicio, 
    tra.fecha_termino, 
    tra.area_id, 
    tra.entidad_operadora_id, 
    tra.descripcion, 
    p.descrip_plan AS plan, 
    p2.descrip_programa AS programa, 
    tra.plan_id, 
    tra.programa_id, 
    tra.departamento_id AS departamento, 
    tra.municipio_id AS municipio, 
    tra.poblacion_id, 
    tra.cobertura, 
    tra.poblacion, 
    tra.entidad_ejecutora, 
    ei.estado_inversion AS estado, 
    tra.estado_id, 
    i.nombre AS entidad,
    ree.ear_id,
    ree.ee_id,
    tra.bloqueo_proyecto,
    dr.dictamen_id
FROM 
    transferencia.transferencias AS tra 
    LEFT JOIN clasificadores.planes p ON p.id = tra.plan_id 
    LEFT JOIN clasificadores.programas p2 ON p2.id = tra.programa_id 
    LEFT JOIN clasificadores.estado_inversion ei ON ei.id = tra.estado_id 
    LEFT JOIN transferencia.rel_transferencia_ear_ee rtee ON rtee.transferencia_id = tra.id
    LEFT JOIN transferencia.rel_ear_ee ree ON rtee.ear_ee_id = ree.id 
    LEFT JOIN clasificadores.instituciones i ON i.id = ree.ee_id
    left join transferencia.dictamenes dr on dr.transferencia_id = tra.id 
       
WHERE 
    ree.ear_id = $id
    AND tra.estado_id IN (1, 2)
    -- Hacer opcional el estado de la institución
    AND (i.estado_id = 1 OR i.estado_id IS NULL)
ORDER BY 
    tra.id DESC; 
");
        return response()->json($transferencia, 200);


    }
    /*
    public function funGuardar(Request $request){

        $nombre_formal = "$request->nombre_tpp";
        $objeto_trasferencia = "$request->objeto_transferencia";
        $localizacion_trasferencia = "$request->localizacion_trasferencia";
        $nombre_original = "$request->denominacion_convenio";
        $fecha_inicio_estimada =  "'27/08/2024'";
        $fecha_fin_estimada = "'27/08/2024'";
        $area_influencia_id = $request->id_area;
        $entidad_operadora = "$request->entidad_operadora";
        $prefijo_tpp = "TPP";
        $numero_fijo = "0047";

        DB::select("SELECT public.insertar_trans(?,?,?,?,?,?,?,?,?,?)",[
            $nombre_formal,
            $objeto_trasferencia,
            $localizacion_trasferencia,
            $nombre_original,
            $fecha_inicio_estimada,
            $fecha_fin_estimada,
            $area_influencia_id,
            $entidad_operadora,
            $prefijo_tpp,
            $numero_fijo]);

        return response()->json(["message" => "Usuario registrado correctamente"]);

    }
*/
public function funGuardar(Request $request)
{
    //dd($request->all());  // Esto imprimirá los datos recibidos y detendrá la ejecución

    // Validar los datos
    $validated = $request->validate([
        'nombre_tpp' => 'required|string|max:110',
        'objeto' => 'required|string',
        'localizacion' => 'required|string',
        'denominacion_convenio' => 'required|string|max:500',
        'id_area' => 'required|integer',
        'entidad_operadora_id' => 'required|integer',
        
    ]);

    // Asignar variables
    $nombre_formal = $validated['nombre_tpp'];
    $objeto_trasferencia = $validated['objeto'];
    //$objeto_trasferencia = "objeto_transferencia";
    $localizacion_trasferencia = $validated['localizacion'];
    $nombre_original = $validated['denominacion_convenio'];
    //$fecha_inicio_estimada = '27/08/2024';
    //$fecha_fin_estimada = '27/08/2024';
    $fecha_inicio_estimada = $request->fecha_inicio;
    $fecha_fin_estimada = $request->fecha_termino;
    $area_influencia_id = $validated['id_area'];
    $entidad_operadora_id = $validated['entidad_operadora_id'];
    $entidad_ejecutora = $request->entidad_ejecutora;
    $prefijo_tpp = "TPP";
    $codigo_presupuestario = $request->codigo_presupuestario;//"0047";

    // Llamar a la función almacenada
    DB::statement("SELECT transferencia.insertar_transferencia(?,?,?,?,?,?,?,?,?,?,?)", [
        $nombre_formal,
        $objeto_trasferencia,
        $localizacion_trasferencia,
        $nombre_original,
        $fecha_inicio_estimada,
        $fecha_fin_estimada,
        $area_influencia_id,
        $entidad_operadora_id,
        $entidad_ejecutora,
        $codigo_presupuestario,
        $prefijo_tpp
    ]);

    // Respuesta JSON
    return response()->json(["message" => "Datos guardados correctamente"]);
}


public function funGuardarProblematica($id, Request $request)
{
    // Validar los datos
    $validated = $request->validate([
        'plan_id' => 'required|integer',
        'programa_id' => 'required|integer',
        'descripcion' => 'required|string',
    ]);

    // Asignar variables
    $plan_id = $validated['plan_id'];
    $programa_id = $validated['programa_id'];
    $descripcion = $validated['descripcion'];

    // Usar consultas preparadas para evitar inyección SQL
    DB::table('transferencia.transferencias')
        ->where('id', $id)
        ->update([
            'plan_id' => $plan_id,
            'programa_id' => $programa_id,
            'descripcion' => $descripcion,
        ]);

    // Respuesta JSON
    return response()->json(["message" => "Datos guardados correctamente"]);
}
public function funGuardarLocalizacion($id, Request $request)
{
    // Validar los datos
    $validated = $request->validate([
        'departamento_id' => 'required|integer',
        'municipio_id' => 'required|integer',
        'poblacion_id' => 'required|integer',
  
        'poblacion' => 'required|integer',
    ]);

    // Asignar variables
    $departamento_id = $validated['departamento_id'];
    $municipio_id = $validated['municipio_id'];
    $poblacion_id = $validated['poblacion_id'];
    $cobertura = 0;
    $poblacion = $validated['poblacion'];
    // Usar consultas preparadas para evitar inyección SQL
    DB::table('transferencia.transferencias')
        ->where('id', $id)
        ->update([
            'departamento_id' => $departamento_id,
            'municipio_id' => $municipio_id,
            'poblacion_id' => $poblacion_id,
            'cobertura' => $cobertura,
            'poblacion' => $poblacion,
        ]);

    // Respuesta JSON
    return response()->json(["message" => "Datos guardados correctamente"]);
}



  /**
     * Buscar transferencia por id .
     */
    public function buscarTrasferencia(string $id)
    {
        $transferencia = DB::select("
        select id, nombre_formal as nombre_tpp, codigo_tpp_formato as codigo_tpp, objeto_trasferencia as objeto, localizacion_trasferencia as localizacion, nombre_original as denominacion_convenio
,fecha_inicio, fecha_termino, area_id, entidad_operadora_id,descripcion, (select p.descrip_plan from clasificadores.planes p where p.id=plan_id) as plan,(select p2.descrip_programa from clasificadores.programas p2  where p2.id=programa_id ) as programa
,plan_id,programa_id, departamento_id as departamento, municipio_id as municipio,poblacion_id,cobertura,poblacion,entidad_ejecutora 
from transferencia.transferencias where transferencia.transferencias.id =$id");
        return response()->json($transferencia, 200);
    }


    /**  Modificicar estado de trasferencia por id */
    public function funModificar($id,Request $request){
        $transferencia = DB::select("
        update transferencia.transferencias  set estado_id = 0 where transferencias.transferencias.id = $id
         ");
         return response()->json(["message" => "Trasferencia eliminada"]);


    }

      /**  Modificicar trasferencia por id */
      public function funModificarTransferencia($id,Request $request){
        print_r($request->all());
        $validated = $request->validate([
            'nombre_tpp' => 'required|string|max:255',
            'objeto' => 'required|string|max:500',
            'localizacion' => 'required|string|max:255',
            'denominacion_convenio' => 'required|string|max:110',
            'area_id' => 'required|integer',
            'entidad_operadora_id' => 'required|integer',
            'entidad_ejecutora' => 'required|string|max:255',
        ]);
         // Asignar variables
        $nombre_formal = $validated['nombre_tpp'];
        $objeto_trasferencia = $validated['objeto'];
        $localizacion_trasferencia = $validated['localizacion'];
        $nombre_original = $validated['denominacion_convenio'];
        // $fecha_inicio = '27/08/2024';
        // $fecha_termino = '27/08/2024';
        $fecha_inicio = $request->fecha_inicio;
        $fecha_termino = $request->fecha_termino;
        $area_influencia_id = $validated['area_id'];
        $entidad_operadora_id = $validated['entidad_operadora_id'];
        $entidad_ejecutora = $validated['entidad_ejecutora'];
        //$prefijo_tpp = "TPP";
        //$numero_fijo = "0047";
        //$transferencia = DB::select("SELECT public.actualizar_trans('Nombre', 'ObjetoModificado', 'Localizacionf', 'Nombre Originalf', '26/08/2024', '26/08/2024', 1, 'asdasd', 58)");
        // return response()->json(["message" => "Trasferencia actualizada"]);

         DB::statement("SELECT transferencia.actualizar_transferencia(?,?,?,?,?,?,?,?,?,?)", [
            $nombre_formal,
            $objeto_trasferencia,
            $localizacion_trasferencia,
            $nombre_original,
            $fecha_inicio,
            $fecha_termino,
            $area_influencia_id,
            $entidad_operadora_id,
            $entidad_ejecutora,
            $id
        ]);

        return response()->json(["message" => "Trasferencia modificada"]);

    }

       /**  Modificicar trasferencia problematica por id */
       /*public function funModificarProblematica($id,Request $request){
        $descripcion=$request->descripcion;

        $transferencia = DB::select("
        update transferencia.transferencias  set descripcion = $descripcion where transferencias.transferencias.id = $id
         ");
         return response()->json(["message" => "Trasferencia modificada"]);


    }*/

    public function funEliminar($id){
        $transferencia = DB::select("
        update transferencia.transferencias  set estado_id = 0 where transferencia.transferencias.id = $id
         ");
         return response()->json(["message" => "Trasferencia eliminada"]);
    }

    public function funActivarCierre($id){
        $transferencia = DB::select("
        update transferencia.transferencias  set bloqueo_proyecto = 0 where transferencia.transferencias.id = $id
         ");
         return response()->json(["message" => "Trasferencia Cierre activado"]);
    }

    public function funCierreFormulario($id){
        $transferencia = DB::select("
        update transferencia.transferencias  set estado_id = 2 where transferencia.transferencias.id = $id;
                 ");
         $transferencia = DB::select("
         
         update transferencia.dictamenes  set cierre_entidad = 1 where transferencia.dictamenes.transferencia_id = $id;
          ");
         return response()->json(["message" => "Transferencia Cierre formulario"]);
    }

    //filtrar proyectos
    public function filtrarTrasferencias($entidadId,$estado_id)
    {
                // Verificar si estado_id existe en la solicitud
                /*    if (!$request->has('estado_id')) {
                        return response()->json(['error' => 'estado_id es requerido'], 400);
                    }*/

                    //$estado_id = $request->get('estado_id');

                    // Aquí puedes imprimir el valor de estado_id para verificar que esté llegando
                    //\Log::info('estado_id recibido: ' . $estado_id);
                    //log('Estado_id recibido::'. $estado_id);
                //dd($estado_id); // Detendrá la ejecución y mostrará el valor
                $transferencia = DB::select("SELECT  
                tra.id, 
                tra.nombre_formal AS nombre_tpp, 
                tra.nombre_formal, 
                tra.codigo_tpp_formato AS codigo_tpp, 
                tra.objeto_trasferencia AS objeto, 
                tra.localizacion_trasferencia AS localizacion, 
                tra.nombre_original AS denominacion_convenio, 
                tra.fecha_inicio, 
                tra.fecha_termino, 
                tra.area_id, 
                tra.entidad_operadora_id, 
                tra.descripcion, 
                p.descrip_plan AS plan, 
                p2.descrip_programa AS programa, 
                tra.plan_id, 
                tra.programa_id, 
                tra.departamento_id AS departamento, 
                tra.municipio_id AS municipio, 
                tra.poblacion_id, 
                tra.cobertura, 
                tra.poblacion, 
                tra.entidad_ejecutora, 
                ei.estado_inversion AS estado, 
                tra.estado_id, 
                i.nombre AS entidad,
                ree.ear_id,
                ree.ee_id,
                tra.bloqueo_proyecto 
            FROM 
                transferencia.transferencias AS tra 
                LEFT JOIN clasificadores.planes p ON p.id = tra.plan_id 
                LEFT JOIN clasificadores.programas p2 ON p2.id = tra.programa_id 
                LEFT JOIN clasificadores.estado_inversion ei ON ei.id = tra.estado_id 
                LEFT JOIN transferencia.rel_transferencia_ear_ee rtee ON rtee.transferencia_id = tra.id
                LEFT JOIN transferencia.rel_ear_ee ree ON rtee.ear_ee_id = ree.id 
                LEFT JOIN clasificadores.instituciones i ON i.id = ree.ee_id
                
            WHERE 
                ree.ear_id = $entidadId
                AND tra.estado_id = $estado_id 
            ORDER BY 
                tra.id DESC; 
            ");
            //Log::info();
        return response()->json($transferencia, 200);
    }

}
