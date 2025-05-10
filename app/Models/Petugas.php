<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';

    protected $fillable = [
        'nama', 'username', 'password', 'email', 'no_hp',
        'foto_profil', 'status', 'role', 'dibuat_pada', 'terakhir_login',
    ];

    public $timestamps = false;

    protected $hidden = ['password'];
}

