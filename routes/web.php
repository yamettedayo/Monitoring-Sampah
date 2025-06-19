<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrashVolumeController;
use App\Models\TrashVolume;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    $tong = \App\Models\TrashVolume::all();
    $jumlahTong = $tong->count();
    $rataVolume = $tong->avg('volume') ?? 0;
    $tongPenuh = $tong->filter(fn ($item) => ($item->volume / 100) * 100 > 80)->count();

    // Buat label 7 hari terakhir
    $labels = collect(range(0, 6))->map(function ($i) {
        return Carbon::now()->subDays(6 - $i)->format('D');
    });

    // Ambil data volume per hari
    $values = collect($labels)->map(function ($labelDate) {
        return \App\Models\TrashVolume::whereDate('waktu', Carbon::parse($labelDate))->avg('volume') ?? 0;
    });

    return view('Home', compact('jumlahTong', 'rataVolume', 'tongPenuh', 'labels', 'values'));
});



Route::get('/data-sampah', [TrashVolumeController::class, 'index']);
Route::get('/monitoring', [TrashVolumeController::class, 'monitoring'])->name('monitoring.index');
Route::get('/trash/create', [TrashVolumeController::class, 'create'])->name('trash.create');
Route::post('/trash', [TrashVolumeController::class, 'store'])->name('trash.store');
Route::get('/trash/{id}/edit', [TrashVolumeController::class, 'edit'])->name('trash.edit');
Route::put('/trash/{id}', [TrashVolumeController::class, 'update'])->name('trash.update');
Route::delete('/trash/{id}', [TrashVolumeController::class, 'destroy'])->name('trash.destroy');



use App\Http\Controllers\Api\SensorController;

Route::get('/tes-api', function () {
    return view('tes-api');
});

Route::post('/tes-api', function (\Illuminate\Http\Request $request) {
    // Panggil controller langsung tanpa lewat jaringan
    return app(SensorController::class)->store($request);
});


// Route::post('/api/kirim-data', [SensorController::class, 'store']);


Route::get('/api/get-latest-data', function () {
    return TrashVolume::latest()->first();
});


Route::get('/monitoring/latest', function () {
    $item = TrashVolume::latest()->first();
    return view('monitoring._box', compact('item'));
});


Route::get('/api/latest-tong', function () {
    $data = TrashVolume::latest()->first();
    return response()->json([
        'volume' => $data->volume,
        'waktu' => $data->waktu,
        'lokasi' => $data->lokasi
    ]);
});