<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area_id',
        'description',
        'display_name',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
