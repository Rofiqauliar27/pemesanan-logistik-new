<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
    'user_id',
    'barang_id',
    'jumlah',
    'total_harga',
    'status',
    'stok_dikurangi',
    'catatan',
    'order_id',
    'group_order_id',
    'payment_status',
    'payment_type',
    'transaction_status',
    'snap_token',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}