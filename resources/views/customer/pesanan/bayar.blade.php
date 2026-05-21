@extends('layouts.public')

@section('title', 'Pembayaran Pesanan')

@section('content')
@php
    $pesananUtama = $pesananUtama ?? $pesanan;
    $customer = auth()->user();

    $kodePesanan = $groupOrderId ?? ($pesananUtama->group_order_id ?? $pesananUtama->order_id ?? '-');

    $statusPesanan = $pesananUtama->status ?? 'pending';
    $statusBayar = $pesananUtama->payment_status ?? 'belum_bayar';

    $sudahBayar = in_array($statusBayar, [
        'sudah_bayar',
        'settlement',
        'paid',
        'capture',
    ]);

    $belumBayar = in_array($statusBayar, [
        'belum_bayar',
        'pending',
        'challenge',
    ]);

    $gagalBayar = in_array($statusBayar, [
        'failed',
        'gagal',
        'expire',
    ]);

    $tanggalPesanan = $pesananUtama->created_at
        ? $pesananUtama->created_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesananUtama->paid_at
        ? $pesananUtama->paid_at->format('d-m-Y H:i')
        : null;

    $batasBayar = $pesananUtama->expired_at
        ? $pesananUtama->expired_at->format('d-m-Y H:i')
        : '-';

    $alamatLengkap = collect([
        $customer->alamat_lengkap ?? null,
        $customer->kelurahan ?? null,
        $customer->kecamatan ?? null,
        $customer->kabupaten ?? null,
        $customer->provinsi ?? null,
        $customer->kode_pos ?? null,
    ])->filter()->implode(', ');

    $totalJenisBarang = $pesanans->count();
    $totalJumlahItem = $pesanans->sum('jumlah');

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

    $stepPesananDibuat = true;
    $stepPembayaran = $sudahBayar || in_array($statusPesanan, ['diproses', 'dikirim', 'selesai']);
    $stepDiproses = in_array($statusPesanan, ['diproses', 'dikirim', 'selesai']);
    $stepDikirim = in_array($statusPesanan, ['dikirim', 'selesai']);
    $stepSelesai = $statusPesanan === 'selesai';
@endphp

<div class="order-tracking-page">

    
    @if(session('error'))
        <div class="alert alert-danger order-alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="order-status-card">
    <div class="order-status-header">
        <span>Kode Pesanan</span>
        <strong>{{ $kodePesanan }}</strong>
    </div>

    <div class="order-status-line">

            <div class="order-status-step {{ $stepPesananDibuat ? 'active' : '' }}">
                <div class="step-circle">1</div>
                <strong>Pesanan Dibuat</strong>
                <span>{{ $tanggalPesanan }}</span>
            </div>

            <div class="step-connector {{ $stepPembayaran ? 'active' : '' }}"></div>

            <div class="order-status-step {{ $stepPembayaran ? 'active' : '' }}">
                <div class="step-circle">2</div>
                <strong>Pembayaran Dikonfirmasi</strong>
                <span>{{ $tanggalBayar ?? '-' }}</span>
            </div>

            <div class="step-connector {{ $stepDiproses ? 'active' : '' }}"></div>

            <div class="order-status-step {{ $stepDiproses ? 'active' : '' }}">
                <div class="step-circle">3</div>
                <strong>Diproses</strong>
                <span>Pesanan sedang disiapkan</span>
            </div>

            <div class="step-connector {{ $stepDikirim ? 'active' : '' }}"></div>

            <div class="order-status-step {{ $stepDikirim ? 'active' : '' }}">
                <div class="step-circle">4</div>
                <strong>Dikirim</strong>
                <span>Pesanan dalam pengiriman</span>
            </div>

            <div class="step-connector {{ $stepSelesai ? 'active' : '' }}"></div>

            <div class="order-status-step {{ $stepSelesai ? 'active' : '' }}">
                <div class="step-circle">5</div>
                <strong>Selesai</strong>
                <span>Pesanan selesai</span>
            </div>

        </div>
    </div>

    @if($belumBayar && !$gagalBayar)
        <div class="payment-warning-box">
            Selesaikan pembayaran sebelum <strong>{{ $batasBayar }}</strong>.
        </div>
    @endif

    @if($gagalBayar)
        <div class="payment-danger-box">
            Pembayaran tidak tersedia karena pesanan sudah gagal, dibatalkan, atau melewati batas pembayaran.
        </div>
    @endif

    <div class="order-detail-layout">

        <div class="order-detail-left">

            <div class="order-section-card">
                <h4>Alamat Pengiriman</h4>

                <div class="order-address-box">
                    <strong>{{ $customer->name ?? '-' }}</strong>

                    @if(!empty($customer->telepon))
                        <span>{{ $customer->telepon }}</span>
                    @endif

                    <p>
                        {{ $alamatLengkap ?: 'Alamat belum dilengkapi.' }}
                    </p>
                </div>
            </div>

            <div class="order-section-card">
                <div class="order-section-header">
                    <div>
                        <h4>Daftar Barang</h4>
                        <p>{{ $totalJenisBarang }} jenis barang, {{ $totalJumlahItem }} item</p>
                    </div>
                </div>

                <div class="order-product-list">
                    @foreach($pesanans as $item)
                        <div class="order-product-row">
                            <div class="order-product-image">
                                @if($item->barang && $item->barang->gambar)
                                    <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->barang->nama_barang }}">
                                @else
                                    <span>Produk</span>
                                @endif
                            </div>

                            <div class="order-product-info">
                                <h5>{{ $item->barang->nama_barang ?? '-' }}</h5>
                                <p>Jumlah: {{ $item->jumlah }} item</p>
                            </div>

                            <div class="order-product-price">
                                <span>Harga</span>
                                <strong>Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}</strong>
                            </div>

                            <div class="order-product-subtotal">
                                <span>Subtotal</span>
                                <strong>Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <aside class="order-payment-card">
            <h4>Ringkasan Pembayaran</h4>

            <div class="payment-summary-total">
                <span>Total Bayar</span>
                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
            </div>

            <div class="payment-summary-line">
                <span>Status Pembayaran</span>
                <strong>{{ $labelStatusBayar }}</strong>
            </div>

            <div class="payment-summary-line">
                <span>Status Pesanan</span>
                <strong>{{ ucfirst(str_replace('_', ' ', $statusPesanan)) }}</strong>
            </div>

            <div class="payment-summary-line">
                <span>Tanggal Pesanan</span>
                <strong>{{ $tanggalPesanan }}</strong>
            </div>

            @if($tanggalBayar)
                <div class="payment-summary-line">
                    <span>Tanggal Bayar</span>
                    <strong>{{ $tanggalBayar }}</strong>
                </div>
            @endif

            @if(!empty($pesananUtama->payment_type))
                <div class="payment-summary-line">
                    <span>Metode Pembayaran</span>
                    <strong>{{ strtoupper($pesananUtama->payment_type) }}</strong>
                </div>
            @endif

            @if($sudahBayar)
                <button class="btn-payment-disabled" disabled>
                    Pesanan Sudah Dibayar
                </button>
            @elseif($gagalBayar)
                <button class="btn-payment-disabled" disabled>
                    Pembayaran Tidak Tersedia
                </button>
            @else
                <button id="pay-button" class="btn-pay-marketplace">
                    Bayar Sekarang
                </button>
            @endif

            <a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}" class="btn-order-history">
                Lihat Pesanan Saya
            </a>
        </aside>

    </div>

</div>
@endsection

@section('scripts')
@if(!$sudahBayar && !$gagalBayar && $snapToken)
    <script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('pay-button');

            if (payButton) {
                payButton.addEventListener('click', function () {
                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result) {
                            window.location.href = "{{ route('customer.profile', ['tab' => 'pesanan']) }}";
                        },
                        onPending: function(result) {
                            window.location.href = "{{ route('customer.profile', ['tab' => 'pesanan', 'filter' => 'menunggu']) }}";
                        },
                        onError: function(result) {
                            window.location.href = "{{ route('customer.profile', ['tab' => 'pesanan', 'filter' => 'gagal']) }}";
                        },
                        onClose: function() {
                            alert("Popup pembayaran ditutup. Anda masih bisa membayar sebelum batas waktu berakhir.");
                        }
                    });
                });
            }
        });
    </script>
@endif
@endsection