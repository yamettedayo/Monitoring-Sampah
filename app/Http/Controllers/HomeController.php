<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TrashVolume;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Untuk panel statistik
        $tong = TrashVolume::all();
        $jumlahTong = $tong->count();
        $rataVolume = $tong->avg('volume') ?? 0;
        // $tongPenuh = $tong->filter(fn ($item) => ($item->volume / 100) * 100 > 80)->count();
        $tongPenuh = TrashVolume::where('volume', '>', 80)->count();


        // Ambil data grafik dari trash_logs
        $logs = DB::table('trash_logs')
            ->selectRaw('DATE(waktu) as tanggal, SUM(volume) as total_volume')
            ->where('waktu', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(waktu)'))
            ->orderBy('tanggal')
            ->get();

        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            // $labels[] = Carbon::now()->subDays($i)->isoFormat('dddd');
            $labels[] = Carbon::now()->subDays($i)->isoFormat('ddd, D MMM');
            $data = $logs->firstWhere('tanggal', $tanggal);
            $values[] = $data ? $data->total_volume : 0;
            $totalVolume7Hari = array_sum($values);
        }

        // return view('home', compact('jumlahTong', 'rataVolume', 'tongPenuh', 'labels', 'values'));
        return view('home', compact('jumlahTong', 'rataVolume', 'tongPenuh', 'labels', 'values', 'totalVolume7Hari'));

    }
}
