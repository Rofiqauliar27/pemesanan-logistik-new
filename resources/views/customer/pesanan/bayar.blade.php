@extends('layouts.customer')

@section('title', 'Pembayaran Pesanan')

@section('content')
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

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tr>
                            <th width="35%">Order ID</th>
                            <td>{{ $pesanan->order_id ?? '-' }}</td>
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
                            <th>Catatan</th>
                            <td>{{ $pesanan->catatan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @if($pesanan->payment_status == 'sudah_bayar')
                        <button class="btn btn-secondary" disabled>Pesanan Sudah Lunas</button>
                    @else
                        <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
                    @endif

                    <a href="{{ route('customer.profile', ['tab' => 'pembayaran']) }}" class="btn btn-outline-secondary">
                        Lihat Pembayaran Saya
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="market-box">
                <h4 class="mb-3">Informasi Pembayaran</h4>

                <div class="alert alert-warning">
                    Pastikan nominal pembayaran sesuai dengan total pesanan.
                </div>

                <ul class="mb-0">
                    <li>Pilih metode pembayaran yang tersedia.</li>
                    <li>Selesaikan pembayaran melalui popup Midtrans.</li>
                    <li>Status akan diperbarui otomatis setelah pembayaran berhasil.</li>
                    <li>Jika status belum berubah, refresh halaman beberapa saat lagi.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if($pesanan->payment_status != 'sudah_bayar')
        <script
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}">
        </script>

        <script>
            document.getElementById('pay-button').onclick = function () {
                window.snap.pay('{{ $pesanan->snap_token }}', {
                    onSuccess: function(result){
                        alert("Pembayaran berhasil");
                        window.location.href = "{{ route('customer.profile', ['tab' => 'pembayaran']) }}";
                    },
                    onPending: function(result){
                        alert("Pembayaran sedang menunggu penyelesaian");
                        window.location.href = "{{ route('customer.profile', ['tab' => 'pembayaran']) }}";
                    },
                    onError: function(result){
                        alert("Pembayaran gagal");
                    },
                    onClose: function(){
                        alert("Kamu menutup popup pembayaran");
                    }
                });
            };
        </script>
    @endif
@endsection