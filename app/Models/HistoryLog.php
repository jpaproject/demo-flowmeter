<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    use HasFactory;

      // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'flowmeter',
        'totalizer',
        'velocity',
        'key',
    ];

    // Kolom-kolom yang harus dikonversi ke tipe data tertentu (opsional)
    protected $casts = [
        'flowmeter' => 'float',
        'totalizer' => 'float',
        'temperature' => 'float',
    ];
}
