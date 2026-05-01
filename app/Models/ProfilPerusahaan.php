<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    protected $fillable = [
        'nama_perusahaan',
        'bidang_usaha',
        'deskripsi',
        'alamat',
        'telepon',
        'email',
        'visi',
        'misi',
        'logo',
    ];
}