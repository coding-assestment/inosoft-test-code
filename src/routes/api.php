<?php

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

Route::get('/vehicles/get-sales', [\App\Http\Controllers\Api\VehicleController::class, 'sales']);
Route::resource('vehicles', \App\Http\Controllers\Api\VehicleController::class);
Route::put('/vehicles/add-stock/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'addStock']);
Route::put('/vehicles/sale/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'sale']);
Route::put('/vehicles/hold/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'hold']);
