<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBeranda extends Model
{
    protected $fillable = [
        'nama',
        'icon',
        'is_active',
        'sort_order',
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }
}