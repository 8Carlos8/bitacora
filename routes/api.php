<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaestroController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
//Maestro
Route::post('/maestro/register', [MaestroController::class, 'register']);
Route::post('/maestro/update', [MaestroController::class, 'update']);
Route::post('/maestro/delete', [MaestroController::class, 'delete']);
Route::post('/maestro/verMaestro', [MaestroController::class, 'verMaestro']);
Route::post('/maestro/listaMaestros', [MaestroController::class, 'listaMaestros']);

//Laboratorio
Route::post('/laboratorio/register', [LaboratorioController::class, 'register']);
Route::post('laboratorio/update', [LaboratorioController::class, 'update']);
Route::post('laboratorio/delete', [LaboratorioController::class, 'delete']);
Route::post('laboratorio/verLaboratorio', [LaboratorioController::class, 'verLaboratorio']);
Route::post('laboratorio/listaLaboratorio', [LaboratorioController::class, 'listaLaboratorio']);

//Registropdf
Route::get('/bitacora/reporte-pdf', [RegistroBitacoraController::class, 'generarReportePDF']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
