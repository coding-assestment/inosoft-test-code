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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::group([
 
    'middleware' => 'auth:api',
 
], function ($router) {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [\App\Http\Controllers\AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [\App\Http\Controllers\AuthController::class, 'me'])->name('me');
    
    Route::get('/vehicles/get-sales', [\App\Http\Controllers\Api\VehicleController::class, 'sales']);
    Route::resource('vehicles', \App\Http\Controllers\Api\VehicleController::class);
    Route::put('/vehicles/add-stock/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'addStock']);
    Route::put('/vehicles/sale/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'sale']);
    Route::put('/vehicles/hold/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'hold']);
});

