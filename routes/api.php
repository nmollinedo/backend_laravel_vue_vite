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
Route::get('/programa/{id}',[ProgramaController::class,"funListarPrograma"]);
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
Route::get('/dictamen-mostrar/{id}',[DictamenController::class,"funMostrarFormulario"]);
Route::delete('/dictamen-eliminar/{id}',[DictamenController::class,"funEliminarFormulario"]);
Route::post('/guardar-dictamen/{id}',[DictamenController::class,"funGuardarFormulario"]);  
Route::post('/modificar-dictamen/{id}',[DictamenController::class,"funGuardarModificacion"]);  //funListarTipoDictamen   funEliminarCierre filtarTrasferencias 
Route::post('/modificar-dictamen-fecha/{id}',[DictamenController::class,"funGuardarModificacionFecha"]);
Route::post('/eliminar-cierre/{id}',[DictamenController::class,"funEliminarCierre"]);

Route::get('/filtrar-transferencia/{entidad_id}/{estado_id}',[TransferenciaController::class,"filtrarTrasferencias"]);
Route::get('/listar-transferencia/{id}',[TransferenciaController::class,"funListarTransferencia"]);
Route::delete('/eliminar-transferencia/{id}',[TransferenciaController::class,"funEliminar"]);
Route::get('/transferencia/{id}',[TransferenciaController::class,"buscarTrasferencia"]);
Route::get('/activar-cierre/{id}',[TransferenciaController::class,"funActivarCierre"]);
Route::get('/cierre-formulario/{id}',[TransferenciaController::class,"funCierreFormulario"]);
Route::get('/modificacion-problematica/{id}',[TransferenciaController::class,"funModificarProblematica"]);
Route::post('/guardar-problematica/{id}',[TransferenciaController::class,"funGuardarProblematica"]); 
Route::post('/guardar-localizacion/{id}',[TransferenciaController::class,"funGuardarLocalizacion"]); 
Route::post("/registrar-transferencia", [TransferenciaController::class, "funGuardar"]);
Route::post("/modificar-transferencia/{id}", [TransferenciaController::class, "funModificarTransferencia"]);
Route::get("/no-autorizado", function (){
    return response()->json(["message" => "No esta autorizado para ver esta pagina"], 401);
})->name("login");

