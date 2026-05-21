<?php

use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\CasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api.key')->group(function () {
    Route::post('/cas/execute', [CasController::class, 'execute']);
    Route::get('/logs', [LogController::class, 'index']);
    Route::get('/logs/export', [LogController::class, 'export']);
});
