<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="1.0",
 *      description="API CRUD Entidades"
 * )
 *
 * @OA\Server(url="http://localhost:8000")
 */

class ReporteController extends Controller
{

    public function funReporte($id){
        $transferencias = DB::select("select id, nombre_formal as nombre_tpp, codigo_tpp_formato as codigo_tpp, objeto_trasferencia as objeto, localizacion_trasferencia as localizacion, nombre_original as denominacion_convenio
                                    ,fecha_inicio, fecha_termino, (select descrip_area from clasificadores.areas a where a.id=area_id) as area, entidad_operadora_id,descripcion, (select p.descrip_plan from clasificadores.planes p where p.id=plan_id) as plan,(select p2.descrip_programa from clasificadores.programas p2  where p2.id=programa_id ) as programa
                                    ,plan_id,programa_id, departamento_id as departamento, municipio_id as municipio,poblacion_id,cobertura,poblacion,entidad_ejecutora , estado_id, fecha_registro, fecha_inicio,fecha_termino 
                                    from transferencia.transferencias where transferencia.transferencias.id =$id");
        //$pdf = Pdf::loadView('reporte.formulario', $produtos);
         // Datos de ejemplo para el reporte, ajusta según tus necesidades
            // Check if there's at least one result, then access the first item
            if (empty($transferencias)) {
                return back()->with('error', 'Transferencia not found.');
            }

            // Get the first record as an object
            $transferencia = $transferencias[0];
         $datos = [
            'codigo_tpp' =>$transferencia->codigo_tpp,
            'nombre_proyecto' => $transferencia->nombre_tpp,
            'denominacion' => $transferencia->denominacion_convenio,
            'entidad_ejecutora' => $transferencia->entidad_ejecutora,
            'fecha_registro' => $transferencia->fecha_registro,
            'area_influencia' => $transferencia->area,
            'duracion_inicio' => $transferencia->fecha_inicio,
            'duracion_termino' => $transferencia->fecha_termino,
            'problematicas' => 'Descripción detallada de la problemática.',
            'solucion' => 'Descripción de la solución.',
            'objetivo_general' => 'Mejorar la producción y productividad en el cultivo de la papa mediante la transferencia de Sistemas de Riego.',
            'objetivo_especifico' => 'Objetivos específicos del proyecto.',
            'costo_total' => 236048,
            'justificaciones' => [
                '¿Los recursos asignados corresponden a un gasto para transferencias de capital "transferencia público privadas"?',
                '¿La transferencia público privadas es para el financiamiento de un proyecto de inversión en ejecución?',
                '¿La entidad o el área responsable, es una unidad autorizada para realizar transferencias público privadas?',
            ],
            'fecha_documento_legal' => '01-12-2021',
            'fecha_resolucion' => '12-09-2022'
        ];

        //return view('reporte.reporte', compact('datos'));

        // Carga una vista y pasa los datos para el PDF
        $pdf = Pdf::loadView('reporte.reporte', compact('datos'));
       
        return $pdf->download('invoice.pdf');
        //return response()->json($producto, 200);
  

    }

    public function generarReporte()
    {
        // Ejemplo de datos, estos pueden ser datos reales de una consulta a la base de datos
        $data = [
            'title' => 'Reporte de Transferencias',
            'date' => date('m/d/Y'),
            'items' => [
                ['nombre' => 'Transferencia 1', 'monto' => 1000],
                ['nombre' => 'Transferencia 2', 'monto' => 2000],
            ],
        ];

        // Carga una vista y pasa los datos para el PDF
        $pdf = Pdf::loadView('reporte', $data);

        // Devuelve el PDF como respuesta para descargarlo o verlo en el navegador
        return $pdf->download('reporte.pdf');
        // O usar return $pdf->stream(); para abrir en el navegador
    }

}
