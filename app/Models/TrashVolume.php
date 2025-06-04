<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TrashVolume extends Model
{
    //
    protected $table = 'trash_volumes'; // nama tabel kamu
    protected $fillable = ['volume', 'lokasi', 'waktu']; // sesuaikan dengan kolom tabel
}
