<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Pesanan</title>
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
                <th>No</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Tanggal Pesanan</th>
                <th>Barang</th>
                <th>Total Item</th>
                <th>Total Pembayaran</th>
                <th>Status Pesanan</th>
                <th>Status Bayar</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pesanans as $item)
                @php
                    $items = $item->items ?? collect([$item]);

                    $jumlahJenisBarang = $item->total_barang ?? $items->count();
                    $totalJumlah = $item->total_jumlah ?? $items->sum('jumlah');
                    $totalGrup = $item->total_grup ?? $items->sum('total_harga');

                    $statusPesanan = ucfirst(str_replace('_', ' ', $item->status ?? '-'));
                    $statusBayarMentah = $item->payment_status ?? '-';

                    $labelStatusBayar = [
                        'belum_bayar' => 'Belum Bayar',
                        'pending' => 'Menunggu Pembayaran',
                        'challenge' => 'Menunggu Konfirmasi',
                        'sudah_bayar' => 'Sudah Bayar',
                        'settlement' => 'Sudah Bayar',
                        'paid' => 'Sudah Bayar',
                        'capture' => 'Sudah Bayar',
                        'failed' => 'Gagal',
                        'gagal' => 'Gagal',
                        'expire' => 'Expired',
                    ][$statusBayarMentah] ?? ucfirst(str_replace('_', ' ', $statusBayarMentah));

                    $tanggalPesanan = $item->created_at
                        ? $item->created_at->format('d-m-Y H:i')
                        : '-';

                    $tanggalBayar = $item->paid_at
                        ? $item->paid_at->format('d-m-Y H:i')
                        : '-';
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->group_order_id ?? $item->order_id ?? '-' }}</td>

                    <td>{{ $item->user->name ?? '-' }}</td>

                    <td>{{ $tanggalPesanan }}</td>

                    <td>
                        <strong>{{ $jumlahJenisBarang }} Barang</strong>
                        <br>

                        @foreach($items as $detail)
                            {{ $detail->barang->nama_barang ?? '-' }}
                            ({{ $detail->jumlah }} item)
                            {{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </td>

                    <td>{{ $totalJumlah }} Item</td>

                    <td>
                        Rp {{ number_format($totalGrup, 0, ',', '.') }}
                    </td>

                    <td>{{ $statusPesanan }}</td>

                    <td>{{ $labelStatusBayar }}</td>

                    <td>{{ $tanggalBayar }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
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