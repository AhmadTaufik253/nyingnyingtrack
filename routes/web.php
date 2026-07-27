<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FleetMapController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
    // return redirect('/fleet-map');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/fleet-map', [FleetMapController::class, 'index'])->name('fleet.map');
    Route::get('/fleet/devices/{id}/logs', [FleetController::class, 'deviceLogs']);
    Route::get('/api/fleet/devices', [FleetMapController::class, 'devices'])->name('fleet.devices');
    Route::get('/api/fleet/devices/{id}/history', [FleetMapController::class, 'deviceHistory'])->name('fleet.devices.history');
});
