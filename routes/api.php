<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\RedirectController;
use App\Http\Controllers\RedirectStatsController;
use App\Http\Controllers\RedirectLogsController; 


// Rotas para redirecionar para a URL de destino
Route::get('/r/{redirect}', [RedirectController::class, 'redirectToDestination']);

Route::get('/api/redirects/{redirect}/stats', [RedirectStatsController::class, 'show']);


// Resource de redirects
Route::apiResource('/api/redirects', RedirectController::class);

// Rota para retornar as estatísticas de acesso do redirect
Route::get('/api/redirects/{redirect}/stats', [RedirectStatsController::class, 'show']);

// Rota para retornar os logs de acesso do redirect
Route::get('/api/redirects/{redirect}/logs', [RedirectLogsController::class, 'index']);

Route::get('/redirects', [RedirectController::class, 'index']);
Route::post('/redirects', [RedirectController::class, 'store']);

