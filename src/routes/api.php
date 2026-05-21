<?php

use App\Http\Controllers\Api\CasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api.key')->group(function () {
    Route::post('/cas/execute', [CasController::class, 'execute']);
});
