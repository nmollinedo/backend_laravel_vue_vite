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

    public function funReporte(){
        $produtos = DB::select("select i.id ,i.codigo_presupuestario || '-' || i.nombre AS nombre,i.codigo_presupuestario ,i.estado_id ,i.usuario_id ,i.sigla, i.institucion_padre_id ,i.tipo_entidad_id 
		from clasificadores.instituciones i where i.codigo_presupuestario <> '-' and i.estado_id = 1");
        $pdf = Pdf::loadView('reporte.formulario', $produtos);
        return $pdf->download('invoice.pdf');
        //return response()->json($producto, 200);
  

    }

}
