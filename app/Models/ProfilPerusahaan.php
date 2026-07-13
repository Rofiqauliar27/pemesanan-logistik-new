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
        'pesan_whatsapp',
        'telepon',
        'email',
        'visi',
        'misi',
        'logo',
    ];
}