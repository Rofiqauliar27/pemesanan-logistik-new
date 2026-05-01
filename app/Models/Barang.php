<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'kategori',
        'satuan',
        'harga',
        'stok',
        'deskripsi',
        'gambar',

    ];
    public function pesanans()
{
    return $this->hasMany(Pesanan::class);
}
}