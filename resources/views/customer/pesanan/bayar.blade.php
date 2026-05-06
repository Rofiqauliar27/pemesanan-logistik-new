@extends('layouts.customer')

@section('title', 'Pembayaran Pesanan')

@section('content')
@php
    $pesananUtama = $pesananUtama ?? $pesanan;
    $pesanans = $pesanans ?? collect([$pesanan]);

    $total = $total ?? $pesanans->sum('total_harga');
    $snapToken = $snapToken ?? $pesananUtama->snap_token;
    $groupOrderId = $groupOrderId ?? ($pesananUtama->group_order_id ?? $pesananUtama->order_id);

    $customer = $pesananUtama->user ?? Auth::user();

    $alamatLengkap = collect([
        $customer->alamat_lengkap ?? null,
        $customer->kelurahan ?? null,
        $customer->kecamatan ?? null,
        $customer->kabupaten ?? null,
        $customer->provinsi ?? null,
        $customer->kode_pos ?? null,
    ])->filter()->implode(', ');

    $statusBayar = $pesananUtama->payment_status ?? 'belum_bayar';
    $statusPesanan = $pesananUtama->status ?? '-';

    $sudahLunas = in_array($statusBayar, [
        'sudah_bayar',
        'settlement',
        'paid',
        'capture',
    ]);

    $sudahExpired = in_array($statusBayar, [
        'expire',
        'failed',
        'gagal',
    ]);

    $labelStatusBayar = [
        'belum_bayar' => 'Belum Dibayar',
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

    $labelStatusPesanan = ucfirst(str_replace('_', ' ', $statusPesanan));

    $tanggalPesanan = $pesananUtama->created_at
        ? $pesananUtama->created_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesananUtama->paid_at
        ? $pesananUtama->paid_at->format('d-m-Y H:i')
        : '-';

    $batasBayar = $pesananUtama->expired_at
        ? $pesananUtama->expired_at->format('d-m-Y H:i')
        : '-';

    $statusBayarClass = $sudahLunas
        ? 'success'
        : ($sudahExpired ? 'danger' : 'warning');
@endphp

<style>
    .payment-page {
        padding: 22px;
    }

    .payment-hero {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .payment-hero h2 {
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
    }

    .payment-hero p {
        color: #6b7280;
        margin-bottom: 0;
    }

    .payment-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        gap: 20px;
        align-items: start;
    }

    .payment-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        margin-bottom: 20px;
    }

    .payment-card-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 16px;
    }

    .payment-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .payment-info-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .payment-info-item span {
        display: block;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .payment-info-item strong {
        font-size: 15px;
        color: #111827;
    }

    .payment-address-card {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
    }

    .payment-address-card h5 {
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .payment-address-card p {
        color: #4b5563;
        margin-bottom: 8px;
    }

    .payment-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
    }

    .payment-status-success {
        background: #dcfce7;
        color: #166534;
    }

    .payment-status-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .payment-status-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .payment-summary-total {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 18px;
        margin: 18px 0;
    }

    .payment-summary-total span {
        display: block;
        color: #2563eb;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .payment-summary-total strong {
        font-size: 26px;
        color: #111827;
        font-weight: 900;
    }

    .payment-action-group {
        display: grid;
        gap: 10px;
    }

    .btn-payment-main {
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 13px 16px;
        background: #16a34a;
        color: #ffffff;
        font-weight: 800;
        text-align: center;
        text-decoration: none;
    }

    .btn-payment-main:hover {
        background: #15803d;
        color: #ffffff;
    }

    .btn-payment-main:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }

    .btn-payment-back {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px 16px;
        background: #ffffff;
        color: #374151;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
    }

    .btn-payment-back:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .payment-note {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 16px;
    }

    .payment-table-total {
        background: #f8fafc;
        font-weight: 800;
    }

    @media (max-width: 992px) {
        .payment-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .payment-page {
            padding: 12px;
        }

        .payment-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="payment-page">

    <div class="payment-hero">
        <h2>Pembayaran Pesanan</h2>
        <p>Selesaikan pembayaran untuk melanjutkan proses pesanan Anda.</p>
    </div>

    <div class="payment-layout">

        <div>
            <div class="payment-card">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                    <div>
                        <h4 class="payment-card-title mb-1">Detail Pesanan</h4>
                        <p class="text-muted mb-0">
                            Order ID: <strong>{{ $groupOrderId ?? '-' }}</strong>
                        </p>
                    </div>

                    <span class="payment-status-badge payment-status-{{ $statusBayarClass }}">
                        {{ $labelStatusBayar }}
                    </span>
                </div>

                <div class="payment-info-grid">
                    <div class="payment-info-item">
                        <span>Tanggal Pesanan</span>
                        <strong>{{ $tanggalPesanan }}</strong>
                    </div>

                    <div class="payment-info-item">
                        <span>Batas Pembayaran</span>
                        <strong>{{ $batasBayar }}</strong>
                    </div>

                    <div class="payment-info-item">
                        <span>Status Pesanan</span>
                        <strong>{{ $labelStatusPesanan }}</strong>
                    </div>

                    <div class="payment-info-item">
                        <span>Status Pembayaran</span>
                        <strong>{{ $labelStatusBayar }}</strong>
                    </div>

                    <div class="payment-info-item">
                        <span>Metode Pembayaran</span>
                        <strong>{{ $pesananUtama->payment_type ?? '-' }}</strong>
                    </div>

                    <div class="payment-info-item">
                        <span>Tanggal Pembayaran</span>
                        <strong>{{ $tanggalBayar }}</strong>
                    </div>
                </div>

                @if(!empty($pesananUtama->catatan))
                    <div class="alert alert-info mt-3 mb-0">
                        <strong>Catatan:</strong> {{ $pesananUtama->catatan }}
                    </div>
                @endif
            </div>

            <div class="payment-card">
                <h4 class="payment-card-title">Alamat Pengiriman</h4>

                <div class="payment-address-card">
                    <h5>{{ $customer->name ?? '-' }}</h5>

                    <p>
                        {{ $customer->email ?? '-' }}

                        @if(!empty($customer->telepon))
                            | {{ $customer->telepon }}
                        @endif
                    </p>

                    <p>
                        {{ $alamatLengkap ?: 'Alamat belum dilengkapi.' }}
                    </p>

                    @if(!empty($customer->google_maps_link))
                        <a href="{{ $customer->google_maps_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            Buka Lokasi Google Maps
                        </a>
                    @endif
                </div>
            </div>

            <div class="payment-card">
                <h4 class="payment-card-title">Daftar Barang</h4>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60">No</th>
                                <th>Nama Barang</th>
                                <th width="100">Jumlah</th>
                                <th width="160">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($pesanans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                    <td>{{ $item->jumlah }} Item</td>
                                    <td>
                                        Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="payment-table-total">
                                <td colspan="3" class="text-end">Total Pembayaran</td>
                                <td>
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <aside>
            <div class="payment-card">
                <h4 class="payment-card-title">Informasi Pembayaran</h4>

                @if($sudahLunas)
                    <div class="alert alert-success">
                        Pembayaran pesanan ini sudah berhasil.
                    </div>
                @elseif($sudahExpired)
                    <div class="alert alert-danger">
                        Pesanan ini sudah expired atau gagal dibayar.
                    </div>
                @else
                    <div class="payment-note">
                        Selesaikan pembayaran sebelum batas waktu pembayaran berakhir.
                    </div>
                @endif

                <div class="payment-summary-total">
                    <span>Total Bayar</span>
                    <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                <ul class="mb-3 ps-3">
                    <li>Pilih metode pembayaran yang tersedia.</li>
                    <li>Selesaikan pembayaran melalui popup Midtrans.</li>
                    <li>Status akan diperbarui otomatis setelah pembayaran berhasil.</li>
                    <li>Batas pembayaran maksimal 24 jam setelah checkout.</li>
                </ul>

                <div class="payment-action-group">
                    @if($sudahLunas)
                        <button class="btn-payment-main" disabled>
                            Pesanan Sudah Lunas
                        </button>
                    @elseif($sudahExpired)
                        <button class="btn-payment-main" disabled>
                            Pembayaran Tidak Tersedia
                        </button>
                    @else
                        <button id="pay-button" class="btn-payment-main">
                            Bayar Sekarang
                        </button>
                    @endif

                    <a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}" class="btn-payment-back">
                        Kembali ke Pesanan Saya
                    </a>
                </div>
            </div>
        </aside>

    </div>
</div>
@endsection

@section('scripts')
    @if(!$sudahLunas && !$sudahExpired && $snapToken)
        <script
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}">
        </script>

        <script>
            document.getElementById('pay-button').onclick = function () {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        alert("Pembayaran berhasil");
                        window.location.href = "{{ route('customer.profile', ['tab' => 'pesanan']) }}";
                    },
                    onPending: function(result) {
                        alert("Pembayaran sedang menunggu penyelesaian");
                        window.location.href = "{{ route('customer.profile', ['tab' => 'pesanan', 'filter' => 'menunggu']) }}";
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal");
                        window.location.href = "{{ route('customer.profile', ['tab' => 'pesanan', 'filter' => 'gagal']) }}";
                    },
                    onClose: function() {
                        alert("Kamu menutup popup pembayaran");
                    }
                });
            };
        </script>
    @endif
@endsection