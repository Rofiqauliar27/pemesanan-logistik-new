<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-size: 14px;
            margin: 20px;
            background: #fff;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul h3, .judul p {
            margin: 0;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="judul mb-4">
            <h3>INVOICE PESANAN</h3>
            <p>CV Bintang Saida Teknik</p>
            <p>Sistem Pemesanan Logistik Perkapalan</p>
        </div>

        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary">Cetak Sekarang</button>
            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-secondary">Kembali</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th width="30%">Order ID</th>
                <td>{{ $pesanan->order_id ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nama Customer</th>
                <td>{{ $pesanan->user->name }}</td>
            </tr>
            <tr>
                <th>Email Customer</th>
                <td>{{ $pesanan->user->email }}</td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td>{{ $pesanan->barang->nama_barang }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>{{ $pesanan->jumlah }}</td>
            </tr>
            <tr>
                <th>Total Harga</th>
                <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status Pesanan</th>
                <td>{{ $pesanan->status }}</td>
            </tr>
            <tr>
                <th>Status Pembayaran</th>
                <td>{{ $pesanan->payment_status }}</td>
            </tr>
            <tr>
                <th>Metode Pembayaran</th>
                <td>{{ $pesanan->payment_type ?? '-' }}</td>
            </tr>
            <tr>
                <th>Transaction Status</th>
                <td>{{ $pesanan->transaction_status ?? '-' }}</td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td>{{ $pesanan->catatan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Pesanan</th>
                <td>{{ $pesanan->created_at ? $pesanan->created_at->format('d-m-Y H:i') : '-' }}</td>
            </tr>
        </table>

        <div class="mt-5 text-end">
            <p>Mengetahui,</p>
            <br><br><br>
            <p><strong>Admin CV Bintang Saida Teknik</strong></p>
        </div>
    </div>

</body>
</html>