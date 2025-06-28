<?php

namespace App\Exports;

use App\Models\TrashVolume;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrashExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return TrashVolume::select('volume', 'lokasi', 'waktu')->get();
    }

    public function headings(): array
    {
        return ['Volume (Liter)', 'Lokasi', 'Waktu'];
    }
}
