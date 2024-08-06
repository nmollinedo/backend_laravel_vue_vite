<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
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
    // controlador de recursos (API)
    Route::apiResource("usuario", UsuarioController::class);

});

Route::get("/no-autorizado", function (){
    return response()->json(["message" => "No esta autorizado para ver esta pagina"], 401);
})->name("login");