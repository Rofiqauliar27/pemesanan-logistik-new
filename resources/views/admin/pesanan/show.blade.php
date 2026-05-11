@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
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
    $statusPesanan = $pesanan->status ?? '-';

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

    $labelStatusPesanan = ucfirst(str_replace('_', ' ', $statusPesanan));

    $statusPesananClass = match($statusPesanan) {
        'pending' => 'bg-secondary',
        'diproses' => 'bg-warning text-dark',
        'dikirim' => 'bg-primary',
        'selesai' => 'bg-success',
        'dibatalkan' => 'bg-danger',
        default => 'bg-dark',
    };

    $statusBayarClass = in_array($statusBayar, ['sudah_bayar', 'settlement', 'paid', 'capture'])
        ? 'bg-success'
        : (in_array($statusBayar, ['pending', 'challenge'])
            ? 'bg-warning text-dark'
            : (in_array($statusBayar, ['failed', 'gagal', 'expire'])
                ? 'bg-danger'
                : 'bg-secondary'));
@endphp

<style>
    .detail-page-title {
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .detail-page-subtitle {
        color: #6b7280;
        margin-bottom: 0;
    }

    .detail-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        padding: 24px;
        margin-bottom: 20px;
    }

    .detail-section-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 16px;
    }

    .detail-info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .detail-info-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .detail-info-item span {
        display: block;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .detail-info-item strong {
        font-size: 15px;
        color: #111827;
    }

    .detail-address-card {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
    }

    .detail-address-card h5 {
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .detail-address-card p {
        margin-bottom: 8px;
        color: #4b5563;
    }

    .detail-total-row {
        background: #f8fafc;
        font-weight: 800;
    }

    .detail-action-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .btn-detail-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 190px;
        height: 44px;
        padding: 0 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        line-height: 1;
        font-family: inherit;
        transition: 0.2s ease-in-out;
        white-space: nowrap;
    }

    .btn-detail-action:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-status-action {
        background-color: #ffc107;
        color: #111827;
    }

    .btn-invoice-action {
        background-color: #198754;
        color: #ffffff;
    }

    .btn-back-action {
        background-color: #6c757d;
        color: #ffffff;
    }

    @media (max-width: 992px) {
        .detail-info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .detail-info-grid {
            grid-template-columns: 1fr;
        }

        .detail-action-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-detail-action {
            width: 100%;
        }
    }
</style>

<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <h2>Detail Pesanan</h2>
            <p>Informasi lengkap pesanan customer berdasarkan grup checkout.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.pesanan.index') }}" class="btn-admin-secondary">
                Kembali
            </a>
        </div>
    </div>

    <div class="detail-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h3 class="detail-page-title">Detail Pesanan</h3>
                <p class="detail-page-subtitle">
                    Order ID: <strong>{{ $groupOrderId }}</strong>
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <span class="badge {{ $statusPesananClass }} px-3 py-2">
                    {{ $labelStatusPesanan }}
                </span>

                <span class="badge {{ $statusBayarClass }} px-3 py-2">
                    {{ $labelStatusBayar }}
                </span>
            </div>
        </div>

        <div class="detail-info-grid">
            <div class="detail-info-item">
                <span>Customer</span>
                <strong>{{ $customer->name ?? '-' }}</strong>
            </div>

            <div class="detail-info-item">
                <span>Email</span>
                <strong>{{ $customer->email ?? '-' }}</strong>
            </div>

            <div class="detail-info-item">
                <span>Total Pembayaran</span>
                <strong>Rp {{ number_format($totalGrup, 0, ',', '.') }}</strong>
            </div>

            <div class="detail-info-item">
                <span>Total Barang</span>
                <strong>{{ $pesananItems->count() }} Jenis / {{ $totalJumlah }} Item</strong>
            </div>

            <div class="detail-info-item">
                <span>Tanggal Pesanan</span>
                <strong>{{ $tanggalPesanan }}</strong>
            </div>

            <div class="detail-info-item">
                <span>Batas Pembayaran</span>
                <strong>{{ $batasBayar }}</strong>
            </div>

            <div class="detail-info-item">
                <span>Tanggal Pembayaran</span>
                <strong>{{ $tanggalBayar }}</strong>
            </div>

            <div class="detail-info-item">
                <span>Metode Pembayaran</span>
                <strong>{{ $pesanan->payment_type ?? '-' }}</strong>
            </div>

            <div class="detail-info-item">
                <span>Transaction Status</span>
                <strong>{{ $pesanan->transaction_status ?? '-' }}</strong>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <h4 class="detail-section-title">Alamat Pengiriman</h4>

        <div class="detail-address-card">
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

    <div class="detail-card">
        <h4 class="detail-section-title">Daftar Barang Pesanan</h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
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

                    <tr class="detail-total-row">
                        <td colspan="2" class="text-end">Total</td>
                        <td>{{ $totalJumlah }} Item</td>
                        <td>Rp {{ number_format($totalGrup, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if(!empty($pesanan->catatan))
            <div class="alert alert-info mt-3 mb-0">
                <strong>Catatan:</strong> {{ $pesanan->catatan }}
            </div>
        @endif
    </div>

    <div class="detail-card">
    <div class="detail-action-bar">
        @if(in_array($statusBayar, ['sudah_bayar', 'settlement', 'paid', 'capture']))
            <a href="{{ route('admin.pesanan.editStatus', $pesanan->id) }}" class="btn-detail-action btn-status-action">
                Ubah Status
            </a>
        @endif

        <a href="{{ route('admin.pesanan.invoice', $pesanan->id) }}" class="btn-detail-action btn-invoice-action">
            Cetak Invoice
        </a>

        <a href="{{ route('admin.pesanan.index') }}" class="btn-detail-action btn-back-action">
            Kembali ke Data Pesanan
        </a>
    </div>

</div>
@endsection