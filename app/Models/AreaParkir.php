<?php

// app/Models/AreaParkir.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    protected $table = 'area_parkir';

    protected $fillable = [
        'nama_area',
        'persentase_penuh',
    ];

    public $timestamps = false;
}


