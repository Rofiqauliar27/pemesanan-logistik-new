<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-size: 12px;
            margin: 20px;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul h3,
        .judul p {
            margin: 0;
        }

        .info-ringkas {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="judul">
        <h3>LAPORAN PESANAN</h3>
        <p>CV Bintang Saida Teknik</p>
        <p>Sistem Pemesanan Logistik Perkapalan</p>
    </div>

    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">
            Print Sekarang
        </button>

        <a href="{{ route('admin.pesanan.laporan') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="info-ringkas">
        <p><strong>Total Pesanan:</strong> {{ $totalPesanan }}</p>
        <p><strong>Total Pendapatan Sudah Bayar:</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ date('d-m-Y H:i:s') }}</p>
        <p><strong>Filter Tanggal Awal:</strong> {{ request('tanggal_awal') ?? '-' }}</p>
        <p><strong>Filter Tanggal Akhir:</strong> {{ request('tanggal_akhir') ?? '-' }}</p>
        <p><strong>Status Pesanan:</strong> {{ request('status') ?? '-' }}</p>
        <p><strong>Status Pembayaran:</strong> {{ request('payment_status') ?? '-' }}</p>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th width="40">No</th>
                <th>Order ID</th>
                <th>Tanggal Pesanan</th>
                <th>Customer</th>
                <th>Barang</th>
                <th>Total Bayar</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pesanans as $item)
                @php
                    $items = $item->items ?? collect([$item]);

                    $jumlahJenisBarang = $item->total_barang ?? $items->count();
                    $totalJumlah = $item->total_jumlah ?? $items->sum('jumlah');
                    $totalGrup = $item->total_grup ?? $items->sum('total_harga');

                    $statusPesanan = $item->status ?? '-';
                    $statusBayar = $item->payment_status ?? '-';

                    $tanggalPesanan = $item->created_at
                        ? $item->created_at->format('d-m-Y H:i')
                        : '-';

                    $sudahLunas = in_array($statusBayar, [
                        'sudah_bayar',
                        'settlement',
                        'paid',
                        'capture',
                    ]);

                    $belumLunas = in_array($statusBayar, [
                        'belum_bayar',
                        'pending',
                        'challenge',
                        'failed',
                        'gagal',
                        'expire',
                    ]);

                    if ($belumLunas) {
                        $statusTampil = [
                            'belum_bayar' => 'Belum Bayar',
                            'pending' => 'Menunggu Pembayaran',
                            'challenge' => 'Menunggu Konfirmasi',
                            'failed' => 'Gagal',
                            'gagal' => 'Gagal',
                            'expire' => 'Expired',
                        ][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));
                    } elseif ($sudahLunas && $statusPesanan == 'pending') {
                        $statusTampil = 'Sudah Bayar';
                    } else {
                        $statusTampil = ucfirst(str_replace('_', ' ', $statusPesanan));
                    }
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $item->group_order_id ?? $item->order_id ?? '-' }}
                    </td>

                    <td>
                        {{ $tanggalPesanan }}
                    </td>

                    <td>
                        {{ $item->user->name ?? '-' }}
                    </td>

                    <td>
                        {{ $jumlahJenisBarang }} Jenis Barang / {{ $totalJumlah }} Item
                    </td>

                    <td>
                        Rp {{ number_format($totalGrup, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ $statusTampil }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Tidak ada data pesanan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>