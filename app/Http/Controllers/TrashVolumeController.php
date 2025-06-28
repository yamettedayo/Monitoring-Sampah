<?php

namespace App\Http\Controllers;

use App\Models\TrashVolume;
use App\Models\TrashLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrashVolumeController extends Controller
{
    public function index(Request $request)
    {
        // Tabel data terakhir
        $data = TrashVolume::orderBy('waktu', 'desc')->get();

        // Tabel log historis
        $query = TrashLog::query();

        if ($request->has('search')) {
            $query->where('lokasi', 'like', '%' . $request->search . '%');
        }

        $show = $request->input('show', 10);
        $logs = $query->orderByDesc('waktu')->paginate($show);

        return view('trash.index', compact('data', 'logs'));
    }

    public function monitoring()
    {
        $data = TrashVolume::orderBy('waktu', 'desc')->get();
        return view('monitoring.index', compact('data'));
    }

    public function create()
    {
        return view('trash.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string|max:255',
            'volume' => 'required|numeric',
        ]);

        TrashVolume::create([
            'lokasi' => $request->lokasi,
            'volume' => $request->volume,
            'waktu'  => now() // otomatis simpan waktu sekarang
        ]);

        return redirect()->route('monitoring.index')->with('success', 'Data tong berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $trash = TrashVolume::findOrFail($id);
        return view('trash.edit', compact('trash'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lokasi' => 'required|string|max:255',
            'volume' => 'required|numeric',
            'waktu' => 'required|date',
        ]);

        $trash = TrashVolume::findOrFail($id);
        $trash->update([
            'lokasi' => $request->lokasi,
            'volume' => $request->volume,
            'waktu'  => $request->waktu
        ]);

        return redirect()->route('monitoring.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $trash = TrashVolume::findOrFail($id);
        $trash->delete();

        return redirect()->route('monitoring.index')->with('success', 'Data berhasil dihapus.');
    }
}
