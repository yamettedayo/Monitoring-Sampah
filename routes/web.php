<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrashVolumeController;

Route::get('/', function () {
    return view('Home');
});

// Route::get('/monitoring', [TrashVolumeController::class, 'index']);
Route::get('/data-sampah', [TrashVolumeController::class, 'index']);
Route::get('/monitoring', [TrashVolumeController::class, 'monitoring']);
Route::get('/monitoring', [TrashVolumeController::class, 'monitoring'])->name('monitoring.index');
