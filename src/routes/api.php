<?php

use App\Http\Controllers\Api\CasController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\SimulationController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\DocsController;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::post('/cas/execute', [CasController::class, 'execute']);

    Route::get('/logs', [LogController::class, 'index']);
    Route::get('/logs/export', [LogController::class, 'export']);
});

Route::get('/docs/openapi', [DocsController::class, 'openapi']);
Route::get('/docs/pdf', [DocsController::class, 'pdf']);

Route::middleware(['api.key', EncryptCookies::class, 'anonymous.token'])->group(function () {
    Route::post('/simulations/inverted-pendulum', [SimulationController::class, 'invertedPendulum']);
    Route::post('/simulations/ball-beam', [SimulationController::class, 'ballBeam']);

    Route::get('/statistics', [StatisticsController::class, 'index']);
});
