<?php

namespace App\Http\Controllers;

use App\Models\TrashVolume;
use Illuminate\Http\Request;

class TrashVolumeController extends Controller
{
    public function index()
    {
        $data = TrashVolume::orderBy('waktu', 'desc')->get();
        return view('trash.index', compact('data'));
    }

    public function monitoring()
    {
        $data = TrashVolume::orderBy('waktu', 'desc')->get();
        return view('monitoring.index', compact('data'));
    }
}
