@extends('layouts.public')

@section('title', 'Pembayaran Pesanan')

@section('content')
@php
    $pesananUtama = $pesananUtama ?? $pesanan;
    $user = auth()->user();

    $groupOrderId = $groupOrderId ?? ($pesananUtama->group_order_id ?? $pesananUtama->order_id ?? '-');

    $alamatLengkapDiisi =
        !empty($user->name) &&
        !empty($user->email) &&
        !empty($user->telepon) &&
        !empty($user->alamat_lengkap) &&
        !empty($user->kelurahan) &&
        !empty($user->kecamatan) &&
        !empty($user->kabupaten) &&
        !empty($user->provinsi) &&
        !empty($user->kode_pos);

    $statusBayar = $pesananUtama->payment_status ?? 'belum_bayar';

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

    $totalJenisBarang = $pesanans->count();
    $totalJumlahItem = $pesanans->sum('jumlah');

    $tanggalPesanan = $pesananUtama && $pesananUtama->created_at
        ? $pesananUtama->created_at->format('d-m-Y H:i')
        : '-';

    $batasBayar = $pesananUtama && $pesananUtama->expired_at
        ? $pesananUtama->expired_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesananUtama && $pesananUtama->paid_at
        ? $pesananUtama->paid_at->format('d-m-Y H:i')
        : '-';

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
@endphp

<div class="checkout-payment-page">

    <div class="checkout-payment-header">
        <div>
            <h2>Pembayaran Pesanan</h2>
            <p>
                Periksa kembali item yang Anda checkout sebelum melanjutkan pembayaran.
            </p>
        </div>
    </div>

    <div class="checkout-payment-layout">

        <div class="checkout-items-card">
            <div class="checkout-card-header">
                <h4>Detail Item Checkout</h4>
                <small>{{ $totalJenisBarang }} jenis barang, {{ $totalJumlahItem }} item</small>
            </div>

            <div class="mb-3">
                <table class="table table-bordered align-middle">
                    <tr>
                        <th width="35%">Group Order ID</th>
                        <td>{{ $groupOrderId }}</td>
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
                        <th>Status Pembayaran</th>
                        <td>{{ $labelStatusBayar }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Pembayaran</th>
                        <td>{{ $tanggalBayar }}</td>
                    </tr>
                </table>
            </div>

            <div class="checkout-item-list">
                @foreach($pesanans as $item)
                    <div class="checkout-item-row">
                        <div class="checkout-item-number">
                            {{ $loop->iteration }}
                        </div>

                        <div class="checkout-item-info">
                            <h5>{{ $item->barang->nama_barang ?? '-' }}</h5>
                            <span>
                                Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="checkout-item-qty">
                            x{{ $item->jumlah }}
                        </div>

                        <div class="checkout-item-subtotal">
                            Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <aside class="checkout-summary-card">
            <h4>Ringkasan Pembayaran</h4>

            <div class="summary-group-id">
                <span>Group Order ID</span>
                <strong>{{ $groupOrderId }}</strong>
            </div>

            <div class="summary-line">
                <span>Total Jenis Barang</span>
                <strong>{{ $totalJenisBarang }} Barang</strong>
            </div>

            <div class="summary-line">
                <span>Total Item</span>
                <strong>{{ $totalJumlahItem }} Item</strong>
            </div>

            <div class="summary-line">
                <span>Status</span>
                <strong class="summary-status">
                    {{ $labelStatusBayar }}
                </strong>
            </div>

            <div class="summary-line">
                <span>Batas Bayar</span>
                <strong>{{ $batasBayar }}</strong>
            </div>

            <div class="summary-total">
                <span>Total Bayar</span>
                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
            </div>

            @if($sudahLunas)
                <button class="btn-pay-now" disabled>
                    Pesanan Sudah Lunas
                </button>
            @elseif($sudahExpired)
                <button class="btn-pay-now" disabled>
                    Pembayaran Tidak Tersedia
                </button>
            @elseif(!$alamatLengkapDiisi)
                <div class="alert alert-warning mb-3">
                    <strong>Alamat belum lengkap.</strong><br>
                    Silakan lengkapi alamat dan nomor telepon terlebih dahulu sebelum melakukan pembayaran.
                </div>

                <a href="{{ route('customer.profile.edit', ['redirect' => url()->current()]) }}" class="btn-pay-now text-center text-decoration-none">
                    Lengkapi Profil
                </a>

                <button type="button" class="btn-pay-now mt-2" disabled>
                    Bayar Sekarang
                </button>
            @else
                <button id="pay-button" class="btn-pay-now">
                    Bayar Sekarang
                </button>
            @endif

            <a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}" class="btn-payment-history">
                Kembali ke Pesanan Saya
            </a>

            <div class="summary-note">
                Pembayaran ini berlaku untuk seluruh item dalam satu pesanan.
                Batas pembayaran maksimal 24 jam setelah pesanan dibuat.
            </div>
        </aside>

    </div>

</div>
@endsection

@section('scripts')
@if(!$sudahLunas && !$sudahExpired && $alamatLengkapDiisi && $snapToken)
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