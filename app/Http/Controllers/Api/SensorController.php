<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrashVolume;
use App\Models\TrashLog;
use Carbon\Carbon;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0|max:100'
        ]);

        // Update atau buat data terakhir (untuk progress bar, dll)
        TrashVolume::updateOrCreate(
            ['lokasi' => $request->lokasi],
            [
                'volume' => $request->volume,
                'waktu'  => Carbon::now('Asia/Jakarta')
            ]
        );

        // Simpan log historis (untuk grafik)
        TrashLog::create([
            'lokasi' => $request->lokasi,
            'volume' => $request->volume,
            'waktu'  => Carbon::now('Asia/Jakarta')
        ]);

        return response()->json(['message' => 'Data berhasil disimpan'], 201);
    }
}
