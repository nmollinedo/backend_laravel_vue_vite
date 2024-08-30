<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



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

    public function funListarTransferencia(){
        $transferencia = DB::select("
        SELECT * from transferencia.transferencias as tra where tra.estado_id=1 order by id asc");
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
        'nombre_tpp' => 'required|string|max:255',
        'objeto' => 'required|string|max:500',
        'localizacion' => 'required|string|max:255',
        'denominacion_convenio' => 'required|string|max:110',
        'id_area' => 'required|integer',
        'entidad_operadora' => 'required|string|max:255',
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
    $entidad_operadora = $validated['entidad_operadora'];
    $prefijo_tpp = "TPP";
    $numero_fijo = "0047";

    // Llamar a la función almacenada
    DB::statement("SELECT public.insertar_trans(?,?,?,?,?,?,?,?,?,?)", [
        $nombre_formal,
        $objeto_trasferencia,
        $localizacion_trasferencia,
        $nombre_original,
        $fecha_inicio_estimada,
        $fecha_fin_estimada,
        $area_influencia_id,
        $entidad_operadora,
        $prefijo_tpp,
        $numero_fijo
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
        select id, nombre_formal as nombre_tpp, codigo_tpp_formato as codigo_tpp, objeto_trasferencia as objeto, localizacion_trasferencia as localizacion, nombre_original as denominacion_convenio,fecha_inicio, fecha_termino, area_id, entidad_operadora from transferencia.transferencias where transferencia.transferencias.id =$id");
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
        $validated = $request->validate([
            'nombre_tpp' => 'required|string|max:255',
            'objeto' => 'required|string|max:500',
            'localizacion' => 'required|string|max:255',
            'denominacion_convenio' => 'required|string|max:110',
            'id_area' => 'required|integer',
            'entidad_operadora' => 'required|string|max:255',
        ]);
         // Asignar variables
        $nombre_formal = $validated['nombre_tpp'];
        $objeto_trasferencia = $validated['objeto'];
        $localizacion_trasferencia = $validated['localizacion'];
        $nombre_original = $validated['denominacion_convenio'];
        $fecha_inicio = '27/08/2024';
        $fecha_termino = '27/08/2024';
        //$fecha_inicio = $request->fecha_inicio;
        //$fecha_termino = $request->fecha_termino;
        $area_influencia_id = $validated['id_area'];
        $entidad_operadora = $validated['entidad_operadora'];
        //$prefijo_tpp = "TPP";
        //$numero_fijo = "0047";
        //$transferencia = DB::select("SELECT public.actualizar_trans('Nombre', 'ObjetoModificado', 'Localizacionf', 'Nombre Originalf', '26/08/2024', '26/08/2024', 1, 'asdasd', 58)");
        // return response()->json(["message" => "Trasferencia actualizada"]);

         DB::statement("SELECT public.actualizar_trans(?,?,?,?,?,?,?,?,?)", [
            $nombre_formal,
            $objeto_trasferencia,
            $localizacion_trasferencia,
            $nombre_original,
            $fecha_inicio,
            $fecha_termino,
            $area_influencia_id,
            $entidad_operadora,
            $id
        ]);
    
        return response()->json(["message" => "Trasferencia modificada"]);
        
    }

       /**  Modificicar trasferencia problematica por id */
       public function funModificarProblematica($id,Request $request){
        $descripcion=$request->descripcion;
        
        $transferencia = DB::select("
        update transferencia.transferencias  set descripcion = $descripcion where transferencias.transferencias.id = $id
         ");
         return response()->json(["message" => "Trasferencia modificada"]);

        
    }

    public function funEliminar($id){
        $transferencia = DB::select("
        update transferencia.transferencias  set estado_id = 0 where transferencia.transferencias.id = $id
         ");
         return response()->json(["message" => "Trasferencia eliminada"]);
    }
}
