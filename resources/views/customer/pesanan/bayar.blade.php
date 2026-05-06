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

    $tanggalPesanan = $pesananUtama->created_at
        ? $pesananUtama->created_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesananUtama->paid_at
        ? $pesananUtama->paid_at->format('d-m-Y H:i')
        : '-';

    $batasBayar = $pesananUtama->expired_at
        ? $pesananUtama->expired_at->format('d-m-Y H:i')
        : '-';
@endphp

<div class="market-box">
    <h2 class="mb-1">Pembayaran Pesanan</h2>
    <p class="text-muted mb-0">
        Selesaikan pembayaran untuk melanjutkan proses pesanan Anda.
    </p>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="market-box">
            <h4 class="mb-3">Detail Pesanan</h4>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <tr>
                        <th width="35%">Order ID</th>
                        <td>{{ $groupOrderId ?? '-' }}</td>
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
                        <th>Status Pesanan</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $pesananUtama->status ?? '-')) }}</td>
                    </tr>

                    <tr>
                        <th>Status Pembayaran</th>
                        <td>{{ $labelStatusBayar }}</td>
                    </tr>

                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>{{ $pesananUtama->payment_type ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Pembayaran</th>
                        <td>{{ $tanggalBayar }}</td>
                    </tr>

                    <tr>
                        <th>Catatan</th>
                        <td>{{ $pesananUtama->catatan ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <h5 class="mb-3">Alamat Pengiriman</h5>

            <div class="border rounded p-3 mb-4 bg-light">
                <h6 class="mb-1 fw-bold">
                    {{ $customer->name ?? '-' }}
                </h6>

                <p class="mb-2 text-muted">
                    {{ $customer->email ?? '-' }}

                    @if(!empty($customer->telepon))
                        | {{ $customer->telepon }}
                    @endif
                </p>

                <p class="mb-2">
                    {{ $alamatLengkap ?: 'Alamat belum dilengkapi.' }}
                </p>

                @if(!empty($customer->google_maps_link))
                    <a href="{{ $customer->google_maps_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        Buka Lokasi Google Maps
                    </a>
                @endif
            </div>

            <h5 class="mb-3">Daftar Barang</h5>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
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

                                <td>{{ $item->jumlah }}</td>

                                <td>
                                    Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th colspan="3" class="text-end">Total Pembayaran</th>
                            <th>
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                @if($sudahLunas)
                    <button class="btn btn-secondary" disabled>
                        Pesanan Sudah Lunas
                    </button>
                @elseif($sudahExpired)
                    <button class="btn btn-danger" disabled>
                        Pembayaran Tidak Tersedia
                    </button>
                @else
                    <button id="pay-button" class="btn btn-success">
                        Bayar Sekarang
                    </button>
                @endif

                <a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}" class="btn btn-outline-secondary">
                    Kembali ke Pesanan Saya
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="market-box">
            <h4 class="mb-3">Informasi Pembayaran</h4>

            @if($sudahLunas)
                <div class="alert alert-success">
                    Pembayaran pesanan ini sudah berhasil.
                </div>
            @elseif($sudahExpired)
                <div class="alert alert-danger">
                    Pesanan ini sudah expired atau gagal dibayar.
                </div>
            @else
                <div class="alert alert-warning">
                    Selesaikan pembayaran sebelum batas waktu pembayaran berakhir.
                </div>
            @endif

            <ul class="mb-0">
                <li>Pilih metode pembayaran yang tersedia.</li>
                <li>Selesaikan pembayaran melalui popup Midtrans.</li>
                <li>Status akan diperbarui otomatis setelah pembayaran berhasil.</li>
                <li>Jika status belum berubah, refresh halaman beberapa saat lagi.</li>
                <li>Batas pembayaran maksimal 24 jam setelah checkout.</li>
            </ul>
        </div>
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