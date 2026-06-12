<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

body{
    font-size:13px;
    margin:20px;
    color:#222;
}

.header-company{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:15px;
    margin-bottom:10px;
}

.logo-laporan{
    width:65px;
    height:65px;
    object-fit:contain;
}

.company-text{
    text-align:left;
}

.company-text h2{
    margin:0;
    font-weight:700;
    font-size:22px;
}

.company-text p{
    margin:0;
    font-size:13px;
}

.judul-laporan{
    text-align:center;
    font-weight:700;
    margin:20px 0;
}

.report-info{
    margin:25px 0;
}

.info-item{
    display:flex;
    margin-bottom:8px;
}

.info-item span{
    width:180px;
    font-weight:700;
}

.summary-box{
    width:600px;
    margin:25px auto;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid #ddd;
}

.summary-row strong{
    font-size:14px;
}

.summary-net{
    color:#198754;
    font-weight:700;
}

.table th,
.table td{
    vertical-align:middle;
}

.table thead th{
    background:#f1f3f5 !important;
    font-weight:700;
}

.section-title{
    font-size:16px;
    font-weight:700;
    margin-bottom:10px;
}

.signature{
    width:280px;
    margin-left:auto;
    margin-top:50px;
    text-align:center;
}

@media print{

    .no-print{
        display:none !important;
    }

    body{
        margin:0;
    }
}
    </style>
</head>
<body>

<div class="container-fluid">

    <div class="header-company">

        <img
            src="{{ asset('images/logo.png') }}"
            alt="Logo"
            class="logo-laporan">

        <div class="company-text">
            <h2>CV BINTANG SAIDA TEKNIK</h2>
            <p>Sistem Pemesanan Logistik Perkapalan</p>
        </div>

    </div>

    <hr>

    <h3 class="judul-laporan">
        LAPORAN PESANAN
    </h3>

    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">
            Print Sekarang
        </button>

        <a href="{{ route('admin.pesanan.laporan') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

<div class="report-info">

    <div class="info-item">
        <span>Periode</span>
        <strong>
            {{ request('tanggal_awal') ?? '-' }}
            s/d
            {{ request('tanggal_akhir') ?? '-' }}
        </strong>
    </div>

    <div class="info-item">
        <span>Status Pesanan</span>
        <strong>
            {{ request('status')
                ? ucfirst(request('status'))
                : 'Semua'
            }}
        </strong>
    </div>

    <div class="info-item">
        <span>Status Pembayaran</span>
        <strong>
            {{ request('payment_status')
                ? ucfirst(str_replace('_',' ',request('payment_status')))
                : 'Semua'
            }}
        </strong>
    </div>

    <div class="info-item">
        <span>Tanggal Cetak</span>
        <strong>{{ date('d-m-Y H:i:s') }}</strong>
    </div>

</div>

</div>

   <div class="summary-box">

    <div class="summary-row">
        <span>Total Pesanan</span>
        <strong>{{ $totalPesanan }}</strong>
    </div>

    <div class="summary-row">
        <span>Total Pendapatan</span>
        <strong>
            Rp {{ number_format($totalPendapatan,0,',','.') }}
        </strong>
    </div>

    <div class="summary-row">
        <span>Total Refund</span>
        <strong>
            Rp {{ number_format($totalRefund ?? 0,0,',','.') }}
        </strong>
    </div>

    <div class="summary-row summary-net">
        <span>Pendapatan Bersih</span>
        <strong>
            Rp {{ number_format(($totalPendapatan ?? 0)-($totalRefund ?? 0),0,',','.') }}
        </strong>
    </div>

</div>

<hr>

<div class="section-title">
    Detail Data Pesanan
</div>
    <hr class="mb-3">
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
{{ $totalJumlah }} Item
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

<div class="signature">
    <p>Banjarmasin, {{ date('d-m-Y') }}</p>

    <br><br><br>

    <strong>Admin CV Bintang Saida Teknik</strong>
</div>
    
</div>
<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>