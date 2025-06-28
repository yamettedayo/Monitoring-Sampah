<?php

namespace App\Http\Controllers;

use App\Models\TrashVolume;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrashExport;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new TrashExport, 'data_sampah.xlsx');
    }
}
