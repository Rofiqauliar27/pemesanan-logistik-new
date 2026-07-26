<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'kategori',
        'kategori_id',
        'satuan',
        'harga',
        'status',
        'deskripsi',
        'gambar',
    ];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function kategoriBeranda()
    {
        return $this->belongsTo(KategoriBeranda::class, 'kategori_id');
    }

    public function getKategoriAttribute($value)
    {
        if ($this->kategori_id && $this->relationLoaded('kategoriBeranda')) {
            return $this->kategoriBeranda?->nama ?? $value;
        }

        if ($this->kategori_id && $this->kategoriBeranda) {
            return $this->kategoriBeranda->nama;
        }

        return $value;
    }
}