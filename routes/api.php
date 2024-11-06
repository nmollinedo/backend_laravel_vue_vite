<?php

use App\Http\Controllers\AreaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComponenteController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DictamenController;
use App\Http\Controllers\EntidadController;
use App\Http\Controllers\InversionController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PoblacionController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TipoDictamenController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1/auth')->group(function(){

    Route::post('/login', [AuthController::class, "funLogin"]);
    Route::post('/register', [AuthController::class, "funRegister"]);

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/profile', [AuthController::class, "funProfile"]);
        Route::post('/logout', [AuthController::class, "funLogout"]);
    });
});

Route::post('reset-password', [ResetPasswordController::class, "resetPassword"]);
Route::post('change-password', [ResetPasswordController::class, "changePassword"]);

Route::get('email/verify/{id}', [AuthController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [AuthController::class, "resend"])->name("verification.resend")->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){

    Route::post("/usuario/asignar-persona", [UsuarioController::class, "asignarPersona"]);

    // controlador de recursos (API)
    Route::apiResource("usuario", UsuarioController::class);
    Route::apiResource("persona", PersonaController::class);

});


Route::get('/area',[AreaController::class,"funListar"]);
Route::get('/entidad',[EntidadController::class,"funListar"]);
Route::get('/entidad/{id}',[EntidadController::class,"funListarEjecutora"]);
Route::get('/plan',[PlanController::class,"funListarPlan"]);
Route::get('/plan/{id}',[PlanController::class,"funListarPlan2"]);
Route::get('/listar-clasificador',[PlanController::class,"funListarTipoClasificador"]);
Route::post('/guardar-plan-programa',[ProgramaController::class,"funGuardarPlanPrograma"]);
Route::post('/guardar-planes-programas',[ProgramaController::class,"funGuardarPlanProgramas"]);
Route::post('/modificar-plan-programa/{id}',[ProgramaController::class,"funModificarPlanPrograma"]);
Route::delete('/eliminar-plan-programa/{id}',[ProgramaController::class,"funEliminarPlanPrograma"]);
Route::get('/programa/{id}',[ProgramaController::class,"funListarPrograma"]);
Route::get('/listar-programa/{entidad_id}/{clasificador_id}',[ProgramaController::class,"funListarPrograma2"]);
Route::get('/plan-programa',[ProgramaController::class,"funListarPlanPrograma"]);
//Route::get('/editar-plan-programa',[ProgramaController::class,"funEditarPlanPrograma"]);
Route::get('/componente',[ComponenteController::class,"funListarComponente"]);
Route::get('/componente/{id}',[ComponenteController::class,"funListarComponenteId"]);
Route::post('/componente',[ComponenteController::class,"funGuardarComponente"]);
Route::delete('/eliminar-componente/{transferencia_id}/{componente_id}',[ComponenteController::class,"funEliminarComponente"]);
Route::post('/modificar-componente/{id}',[ComponenteController::class,"funModificarComponente"]);


Route::get('/departamento',[DepartamentoController::class,"funListarDepartamento"]);
Route::get('/municipio',[MunicipioController::class,"funListarMunicipio"]);
Route::get('/municipio/{id}',[MunicipioController::class,"funListarMunicipioDpto"]); 
Route::get('/poblacion',[PoblacionController::class,"funListarPoblacion"]);
Route::get('/poblacion/{id}',[PoblacionController::class,"funListarPoblacionMuni"]);

Route::get('/listar-tipo',[TipoDictamenController::class,"funListarTipoDictamen"]); //funListarTipoDictamen2 
Route::get('/listar-tipo2',[TipoDictamenController::class,"funListarTipoDictamen2"]);
Route::get('/dictamen-listar/{id}',[DictamenController::class,"funListarFormulario"]);
Route::get('/dictamen-listar-todo',[DictamenController::class,"funListarFormularioTodo"]);
Route::get('/dictamen-mostrar/{id}',[DictamenController::class,"funMostrarFormulario"]);
Route::get('/dictamen-mostrar-edit/{id}',[DictamenController::class,"funMostrarFormularioEdit"]);
Route::get('/dictamen-mostrar-fecha/{id}',[DictamenController::class,"funMostrarEditFecha"]);
Route::post('/guardar-dictamen',[DictamenController::class,"funGuardarDictamen"]);
Route::delete('/eliminar-dictamen-costo/{transferencia_id}/{componente_id}',[DictamenController::class,"funEliminarDictamenCosto"]);
Route::post('/guardar-formulario-costo',[DictamenController::class,"funGuardarFormularioComponenteCosto"]);

Route::delete('/dictamen-eliminar/{id}/{transferencia_id}',[DictamenController::class,"funEliminarFormulario"]);
Route::post('/guardar-dictamen/{id}',[DictamenController::class,"funGuardarFormulario"]);
Route::post('/cerrar-formulario-costo',[DictamenController::class,"funCerrarFormularioCosto"]); 
Route::post('/cerrar-formulario-costo-fecha',[DictamenController::class,"funCerrarFormularioCostoFecha"]); 
Route::post('/cerrar-formulario-fecha',[DictamenController::class,"funCerrarFormularioFecha"]);
Route::post('/modificar-dictamen/{id}',[DictamenController::class,"funGuardarModificacion"]);  //funModificarFormularioCosto
Route::post('/modificar-formulario-costo/{id}',[DictamenController::class,"funModificarFormularioCosto"]);
Route::post('/modificar-dictamen-fecha/{id}',[DictamenController::class,"funGuardarFecha"]);
Route::post('/modificar-dictamen-costo-fecha/{id}',[DictamenController::class,"funGuardarModCostoFecha"]);
Route::post('/modificar-edit-fecha/{id}',[DictamenController::class,"funGuardarEditFecha"]);
Route::post('/eliminar-cierre/{id}',[DictamenController::class,"funEliminarCierre"]);
Route::get('/verificar-formulario/{id}',[DictamenController::class,"funVerificarFormularioActivo"]);
Route::get('listar-formulario-costo/{id}',[DictamenController::class,"funListarFormularioComponenteId"]);

Route::get('/filtrar-transferencia/{entidad_id}/{estado_id}',[TransferenciaController::class,"filtrarTrasferencias"]);
Route::get('/listar-transferencia/{id}',[TransferenciaController::class,"funListarTransferencia"]);
Route::get('/listar-transferencia-formulario/{id}',[TransferenciaController::class,"funListarTransferenciaFormulario"]);
Route::delete('/eliminar-transferencia/{id}',[TransferenciaController::class,"funEliminar"]);
Route::get('/transferencia/{id}',[TransferenciaController::class,"buscarTrasferencia"]);
Route::get('/activar-cierre/{id}',[TransferenciaController::class,"funActivarCierre"]);
Route::get('/cierre-formulario/{id}',[TransferenciaController::class,"funCierreFormulario"]);
Route::get('/modificacion-problematica/{id}',[TransferenciaController::class,"funModificarProblematica"]);
Route::post('/guardar-problematica/{id}',[TransferenciaController::class,"funGuardarProblematica"]); 
Route::post('/guardar-localizacion/{id}',[TransferenciaController::class,"funGuardarLocalizacion"]); 
Route::post('/guardar-localizacion-punto/{id}',[TransferenciaController::class,"funGuardarLocalizacionPunto"]);
Route::get('/listar-punto/{id}',[TransferenciaController::class,"listarPunto"]);
Route::post("/registrar-transferencia", [TransferenciaController::class, "funGuardar"]);
Route::post("/modificar-transferencia/{id}", [TransferenciaController::class, "funModificarTransferencia"]);


Route::get('/reporte/{id}',[ReporteController::class,"funReporte"]);
Route::get("/no-autorizado", function (){
    return response()->json(["message" => "No esta autorizado para ver esta pagina"], 401);
})->name("login");

