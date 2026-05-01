<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-size: 14px;
            margin: 20px;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul h3, .judul p {
            margin: 0;
        }

        .info-ringkas {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        @media print {
            .no-print {
                display: none !important;
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
            <button onclick="window.print()" class="btn btn-primary">Print Sekarang</button>
            <a href="{{ route('admin.pesanan.laporan') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="info-ringkas">
            <p><strong>Total Pesanan:</strong> {{ $totalPesanan }}</p>
            <p><strong>Total Pendapatan (Pesanan Selesai):</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
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
                    <th>Customer</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data pesanan</td>
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