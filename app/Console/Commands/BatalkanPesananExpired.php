<?php

namespace App\Console\Commands;

use App\Models\Pesanan;
use Illuminate\Console\Command;

class BatalkanPesananExpired extends Command
{
    protected $signature = 'pesanan:batalkan-expired';

    protected $description = 'Membatalkan pesanan yang belum dibayar setelah melewati batas waktu pembayaran 24 jam';

    public function handle(): int
    {
        $pesanans = Pesanan::whereNotIn('payment_status', [
                'sudah_bayar',
                'settlement',
                'paid',
                'capture',
            ])
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->where('status', '!=', 'dibatalkan')
            ->get();

        if ($pesanans->isEmpty()) {
            $this->info('Tidak ada pesanan expired yang perlu dibatalkan.');
            return Command::SUCCESS;
        }

        $jumlah = $pesanans->count();

        Pesanan::whereIn('id', $pesanans->pluck('id'))->update([
            'status' => 'dibatalkan',
            'payment_status' => 'expire',
            'transaction_status' => 'expire',
        ]);

        $this->info($jumlah . ' data pesanan expired berhasil dibatalkan.');

        return Command::SUCCESS;
    }
}