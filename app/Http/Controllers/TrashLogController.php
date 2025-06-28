<?php

namespace App\Http\Controllers;

use App\Models\TrashLog;
use Illuminate\Http\Request;
use App\Exports\TrashLogExport;
use Maatwebsite\Excel\Facades\Excel;

class TrashLogController extends Controller
{
    public function export()
    {
        return Excel::download(new TrashLogExport, 'riwayat_sampah.xlsx');
    }
}
