<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrashLog extends Model
{
    use HasFactory;

    protected $fillable = ['lokasi', 'volume', 'waktu'];
}
