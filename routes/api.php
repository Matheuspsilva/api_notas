<?php

use App\Http\Controllers\NotaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/notas/{remetente_id}', [NotaController::class, 'listarNotasRemetente']);
Route::get('/notas/{remetente_id}/total', [NotaController::class, 'valorTotalNotasRemetente']);
Route::get('/notas/{remetente_id}/total_entregado', [NotaController::class, 'valorTotalNotasRemetenteEntregado']);
Route::get('/notas/{remetente_id}/total_nao_entregado', [NotaController::class, 'valorTotalNotasRemetenteNaoEntregue']);

