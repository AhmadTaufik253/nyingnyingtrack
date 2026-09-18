<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DevicePositionController;
use App\Http\Controllers\UserController;

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

use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mobile App Authentication Routes
Route::post('/login', [AuthController::class, 'apiLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'apiLogout']);
});

Route::post('/device/position', [DevicePositionController::class, 'receive']);
Route::get('/device/position', [DevicePositionController::class, 'receive']);
