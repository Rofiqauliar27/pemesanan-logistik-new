<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pesanan</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f2f2f2;
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 14px;
        }

        .invoice-page {
    width: 210mm;
    min-height: 297mm;
    margin: 0 auto;
    background: #fff;
    padding: 12mm 16mm;
    position: relative;
    overflow: hidden;
}

        .decor {
            position: absolute;
            left: -55mm;
            top: 20mm;
            width: 120mm;
            height: 120mm;
            background: #f1f1f1;
            transform: rotate(45deg);
            z-index: 0;
        }

        .decor-2 {
            position: absolute;
            left: -15mm;
            top: 75mm;
            width: 75mm;
            height: 75mm;
            background: #f7f7f7;
            transform: rotate(45deg);
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
        }

       .top-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 6mm;
}

        .company-left {
            width: 45%;
        }

        .company-right {
            width: 45%;
            text-align: right;
        }

        .invoice-logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
    margin-bottom: 4px;
}

.company-name {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 3px;
    line-height: 1.2;
}

.company-info {
    font-size: 9px;
    line-height: 1.3;
    color: #555;
}

        .invoice-title {
            font-size: 62px;
            letter-spacing: 4px;
            font-weight: 400;
            margin-bottom: 28px;
        }

        .invoice-meta {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

        .bill-to {
            width: 55%;
        }

        .date-box {
            width: 40%;
            text-align: right;
        }

        .meta-row {
    display: flex;
    margin-bottom: 5px;
    line-height: 1.3;
}

       .meta-label {
    width: 105px;
    font-weight: 700;
    font-size: 11px;
}

.meta-value {
    flex: 1;
    color: #444;
    font-size: 10px;
}

        .date-label {
            font-weight: 700;
            font-size: 16px;
            margin-right: 20px;
        }

        .date-value {
            color: #444;
            font-size: 15px;
        }

        .section-line {
            border-top: 1px dotted #333;
            margin: 10px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .items-table thead th {
            font-size: 11px;
            text-align: left;
            padding: 6px 0;
            border-bottom: 1px dotted #333;
            font-weight: 700;
        }

        .items-table tbody td {
            padding: 6px 0;
            font-size: 10px;
            color: #333;
            vertical-align: top;
        }

        .items-table .col-no {
            width: 12%;
            text-align: center;
        }

        .items-table .col-desc {
            width: 46%;
        }

        .items-table .col-qty {
            width: 14%;
            text-align: center;
        }

        .items-table .col-price {
            width: 14%;
            text-align: right;
        }

        .items-table .col-amount {
            width: 14%;
            text-align: right;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 8px;
            margin-bottom: 14px;
        }

        .total-label {
            font-size: 18px;
            font-weight: 700;
            margin-right: 40px;
        }

        .total-value {
            font-size: 18px;
            font-weight: 700;
        }

        .payment-info {
            width: 65%;
            margin-top: 20px;
            margin-bottom: 34px;
        }

        .payment-row {
            display: flex;
            margin-bottom: 10px;
        }

        .payment-label {
            width: 165px;
            font-size: 16px;
            font-weight: 700;
        }

        .payment-value {
            font-size: 15px;
            color: #444;
        }

        .footer-note {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-top: 25px;
        }

        .signature {
            text-align: right;
            margin-top: 35px;
        }

        .signature p {
            margin: 0;
        }

        .signature-space {
            height: 55px;
        }

        .no-print {
            width: 210mm;
            margin: 15px auto;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 145px;
            height: 42px;
            padding: 0 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            line-height: 1;
            font-family: Arial, Helvetica, sans-serif;
        }

        .btn-action:hover {
            opacity: 0.9;
            text-decoration: none;
        }

        .btn-print {
            background: #198754;
            color: #fff;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
        }

        @media print {
            body {
                background: #fff;
            }

            .no-print {
                display: none !important;
            }

            .invoice-page {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 25mm 22mm;
                box-shadow: none;
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
        ? $pesanan->created_at->format('d F Y')
        : '-';

    $tanggalPesananLengkap = $pesanan->created_at
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

<div class="no-print">
    <div class="action-buttons">
        <button onclick="window.print()" class="btn-action btn-print">
            Cetak Sekarang
        </button>

        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn-action btn-back">
            Kembali
        </a>
    </div>
</div>

<div class="invoice-page">
    <div class="decor"></div>
    <div class="decor-2"></div>

    <div class="content">
        <div class="top-header">
            <div class="company-left"></div>

            <div class="company-right">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="invoice-logo">

                <div class="company-name">CV Bintang Saida Teknik</div>
                <div class="company-info">
                    Sistem Pemesanan Logistik Perkapalan<br>
                    Telp: {{ $customer->telepon ?? '-' }}
                </div>
            </div>
        </div>

        <div class="invoice-meta">
            <div class="bill-to">
                <div class="meta-row">
                    <div class="meta-label">Invoice No:</div>
                    <div class="meta-value">{{ $groupOrderId }}</div>
                </div>

                <div class="meta-row">
                    <div class="meta-label">Bill to:</div>
                    <div class="meta-value">
                        <strong>{{ $customer->name ?? '-' }}</strong><br>
                        {{ $customer->email ?? '-' }}<br>
                        @if(!empty($customer->telepon))
                            Telp: {{ $customer->telepon }}<br>
                        @endif
                        {{ $alamatLengkap ?: 'Alamat belum dilengkapi.' }}
                    </div>
                </div>
            </div>

            <div class="date-box">
                <span class="date-label">Date:</span>
                <span class="date-value">{{ $tanggalPesanan }}</span>
            </div>
        </div>

        <div class="section-line"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-no">Item</th>
                    <th class="col-desc">Description</th>
                    <th class="col-qty">Qty</th>
                    <th class="col-price">Price</th>
                    <th class="col-amount">Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach($pesananItems as $item)
                    <tr>
                        <td class="col-no">{{ $loop->iteration }}.</td>
                        <td class="col-desc">{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td class="col-qty">{{ $item->jumlah }}</td>
                        <td class="col-price">
                            Rp {{ number_format(($item->total_harga ?? 0) / max(($item->jumlah ?? 1), 1), 0, ',', '.') }}
                        </td>
                        <td class="col-amount">
                            Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="section-line"></div>

        <div class="total-section">
            <div class="total-label">Total</div>
            <div class="total-value">
                Rp {{ number_format($totalGrup, 0, ',', '.') }}
            </div>
        </div>

        <div class="payment-info">
            <div class="payment-row">
                <div class="payment-label">Status Pesanan:</div>
                <div class="payment-value">{{ $statusPesanan }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Status Pembayaran:</div>
                <div class="payment-value">{{ $labelStatusBayar }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Metode Pembayaran:</div>
                <div class="payment-value">{{ $pesanan->payment_type ?? '-' }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Tanggal Pesanan:</div>
                <div class="payment-value">{{ $tanggalPesananLengkap }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Batas Pembayaran:</div>
                <div class="payment-value">{{ $batasBayar }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Tanggal Bayar:</div>
                <div class="payment-value">{{ $tanggalBayar }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Catatan:</div>
                <div class="payment-value">{{ $pesanan->catatan ?? '-' }}</div>
            </div>
        </div>

        <div class="signature">
            <p>Mengetahui,</p>
            <div class="signature-space"></div>
            <p><strong>Admin CV Bintang Saida Teknik</strong></p>
        </div>

        <div class="section-line"></div>

        <div class="footer-note">
            Jika terdapat pertanyaan terkait invoice ini, silakan hubungi admin CV Bintang Saida Teknik.
        </div>
    </div>
</div>

</body>
</html>