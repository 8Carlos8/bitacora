<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaestroController;
use App\Http\Controllers\LaboratorioController;
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


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
