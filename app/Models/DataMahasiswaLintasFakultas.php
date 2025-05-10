<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/DataMahasiswaLintasFakultas.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMahasiswaLintasFakultas extends Model
{
    protected $table = 'data_mahasiswa_lintas_fakultas';

    protected $fillable = [
        'foto_mahasiswa', 'nama', 'nim', 'fakultas_asal',
        'tujuan_masuk', 'catatan', 'waktu', 'petugas_id',
    ];

    public $timestamps = false;

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }
}
