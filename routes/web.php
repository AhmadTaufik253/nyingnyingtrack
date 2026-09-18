<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FleetMapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObjectController;

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
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/api/users', [UserController::class, 'search']);
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('admin.users.toggle-active');
    Route::get('/admin/users/{user}/devices', [UserController::class, 'devices'])->name('admin.users.devices');
    // Route::post('/admin/users', [UserController::class, 'store']);
    // Route::get('/admin/users/{id}/edit', [UserController::class, 'edit']);
    // Route::put('/admin/users/{id}', [UserController::class, 'update']);
    // Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);

    Route::get('/admin/objects', [ObjectController::class, 'index'])->name('admin.objects');
    
    Route::get('/fleet-map', [FleetMapController::class, 'index'])->name('fleet.map');
    Route::get('/api/fleet/devices/{id}/logs', [FleetMapController::class, 'deviceLogs'])->name('fleet.devices.logs');
    Route::get('/api/fleet/devices', [FleetMapController::class, 'devices'])->name('fleet.devices');
    Route::put('/api/fleet/devices/{id}', [FleetMapController::class, 'update'])->name('fleet.devices.update');
    Route::get('/api/fleet/devices/{id}/history', [FleetMapController::class, 'deviceHistory'])->name('fleet.devices.history');

    Route::patch('/admin/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('admin.users.toggle-active');
    Route::get('/admin/users/{user}/devices', [UserController::class, 'devices'])->name('admin.users.devices');
});
