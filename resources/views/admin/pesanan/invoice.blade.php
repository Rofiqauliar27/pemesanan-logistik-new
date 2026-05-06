<!DOCTYPE html>
<html lang="id">
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

        .judul h3,
        .judul p {
            margin: 0;
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
@php
    $pesananItems = $pesananItems ?? collect([$pesanan]);
    $totalGrup = $totalGrup ?? $pesananItems->sum('total_harga');
    $totalJumlah = $totalJumlah ?? $pesananItems->sum('jumlah');

    $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id ?? '-';

    $customer = $pesanan->user;

    $alamatLengkap = collect([
        $customer->alamat_lengkap ?? null,
        $customer->kelurahan ?? null,
        $customer->kecamatan ?? null,
        $customer->kabupaten ?? null,
        $customer->provinsi ?? null,
        $customer->kode_pos ?? null,
    ])->filter()->implode(', ');

    $tanggalPesanan = $pesanan->created_at
        ? $pesanan->created_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesanan->paid_at
        ? $pesanan->paid_at->format('d-m-Y H:i')
        : '-';

    $batasBayar = $pesanan->expired_at
        ? $pesanan->expired_at->format('d-m-Y H:i')
        : '-';

    $statusBayar = $pesanan->payment_status ?? '-';

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
    ][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));

    $statusPesanan = ucfirst(str_replace('_', ' ', $pesanan->status ?? '-'));
@endphp

<div class="container">
    <div class="judul mb-4">
        <h3>INVOICE PESANAN</h3>
        <p>CV Bintang Saida Teknik</p>
        <p>Sistem Pemesanan Logistik Perkapalan</p>
    </div>

    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">
            Cetak Sekarang
        </button>

        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <table class="table table-bordered mb-4">
        <tr>
            <th width="30%">Order ID</th>
            <td>{{ $groupOrderId }}</td>
        </tr>

        <tr>
            <th>Nama Customer</th>
            <td>{{ $customer->name ?? '-' }}</td>
        </tr>

        <tr>
            <th>Email Customer</th>
            <td>{{ $customer->email ?? '-' }}</td>
        </tr>

        <tr>
            <th>Status Pesanan</th>
            <td>{{ $statusPesanan }}</td>
        </tr>

        <tr>
            <th>Status Pembayaran</th>
            <td>{{ $labelStatusBayar }}</td>
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
            <th>Tanggal Pesanan</th>
            <td>{{ $tanggalPesanan }}</td>
        </tr>

        <tr>
            <th>Batas Pembayaran</th>
            <td>{{ $batasBayar }}</td>
        </tr>

        <tr>
            <th>Tanggal Pembayaran</th>
            <td>{{ $tanggalBayar }}</td>
        </tr>

        <tr>
            <th>Catatan</th>
            <td>{{ $pesanan->catatan ?? '-' }}</td>
        </tr>
    </table>

    <h5 class="mb-3">Alamat Pengiriman</h5>

    <div class="border rounded p-3 mb-4">
        <strong>{{ $customer->name ?? '-' }}</strong>
        <br>

        {{ $customer->email ?? '-' }}

        @if(!empty($customer->telepon))
            | {{ $customer->telepon }}
        @endif

        <br>

        {{ $alamatLengkap ?: 'Alamat belum dilengkapi.' }}

        @if(!empty($customer->google_maps_link))
            <br>
            Google Maps: {{ $customer->google_maps_link }}
        @endif
    </div>

    <h5 class="mb-3">Daftar Barang Pesanan</h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama Barang</th>
                <th width="120">Jumlah</th>
                <th width="180">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($pesananItems as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>

                    <td>{{ $item->jumlah }} Item</td>

                    <td>
                        Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <th colspan="2" class="text-end">Total</th>
                <th>{{ $totalJumlah }} Item</th>
                <th>
                    Rp {{ number_format($totalGrup, 0, ',', '.') }}
                </th>
            </tr>
        </tbody>
    </table>

    <div class="mt-5 text-end">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Admin CV Bintang Saida Teknik</strong></p>
    </div>
</div>

</body>
</html>