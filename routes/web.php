<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FleetMapController;

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
    return redirect('/fleet-map');
});

Route::get('/fleet-map', [FleetMapController::class, 'index'])->name('fleet.map');
Route::get('/api/fleet/devices', [FleetMapController::class, 'devices'])->name('fleet.devices');
Route::get('/api/fleet/devices/{id}/history', [FleetMapController::class, 'deviceHistory'])->name('fleet.devices.history');
