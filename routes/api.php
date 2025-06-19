<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Models\TrashVolume;
use App\Http\Controllers\Api\GrafikController;

Route::post('/kirim-data', [SensorController::class, 'store']);

// API real-time grafik per lokasi tong
// Route::get('/grafik/{lokasi}', function ($lokasi) {
//     return TrashVolume::where('lokasi', $lokasi)
//         ->orderBy('waktu', 'desc')
//         ->take(10)
//         ->get();
// });

// (Opsional) API ambil tong terakhir
Route::get('/latest-tong', function () {
    return TrashVolume::orderBy('waktu', 'desc')->first();
});

Route::get('/grafik/{lokasi}', [GrafikController::class, 'perLokasi']);
