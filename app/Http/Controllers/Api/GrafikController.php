<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrashLog;
use Carbon\Carbon;

class GrafikController extends Controller
{
    public function perLokasi($lokasi)
    {

        // $data = TrashLog::where('lokasi', $lokasi)
        //                 ->orderBy('waktu', 'desc')
        //                 ->limit(20)
        //                 ->get(['volume', 'waktu']);

        $data = TrashLog::where('lokasi', $lokasi)
                        ->orderBy('waktu', 'desc')
                        ->limit(20)
                        ->get()
                        ->map(function ($item) {
                            return [
                                'volume' => $item->volume,
                                // Format waktu ISO + offset zona WIB
                                'waktu'  => Carbon::parse($item->waktu)
                                                ->setTimezone('Asia/Jakarta')
                                                ->toIso8601String()
                            ];
                        });

        return response()->json($data);
    }
}
