<?php

namespace App\Exports;

use App\Models\TrashLog;
use Maatwebsite\Excel\Concerns\FromCollection;

class TrashLogExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TrashLog::all();
    }
}
