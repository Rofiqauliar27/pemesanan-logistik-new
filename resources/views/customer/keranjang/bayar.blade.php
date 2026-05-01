@extends('layouts.customer')

@section('title', 'Pembayaran Checkout Keranjang')

@section('content')
<div class="checkout-payment-page">

    <div class="checkout-payment-header">
        <div>
            <span>Checkout Keranjang</span>
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
                <small>{{ $pesanans->count() }} item dipilih</small>
            </div>

            <div class="checkout-item-list">
                @foreach($pesanans as $item)
                    <div class="checkout-item-row">
                        <div class="checkout-item-number">
                            {{ $loop->iteration }}
                        </div>

                        <div class="checkout-item-info">
                            <h5>{{ $item->barang->nama_barang }}</h5>
                            <span>
                                Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="checkout-item-qty">
                            x{{ $item->jumlah }}
                        </div>

                        <div class="checkout-item-subtotal">
                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
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
                <span>Total Item</span>
                <strong>{{ $pesanans->count() }} item</strong>
            </div>

            <div class="summary-line">
                <span>Status</span>
                <strong class="summary-status">Menunggu Pembayaran</strong>
            </div>

            <div class="summary-total">
                <span>Total Bayar</span>
                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
            </div>

            <button id="pay-button" class="btn-pay-now">
                Bayar Sekarang
            </button>

            <a href="{{ route('customer.profile', ['tab' => 'pembayaran']) }}" class="btn-payment-history">
                Lihat Pembayaran Saya
            </a>

            <div class="summary-note">
                Pembayaran ini hanya mencakup item yang Anda pilih saat checkout dari keranjang.
            </div>
        </aside>

    </div>

</div>
@endsection

@section('scripts')
<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
    document.getElementById('pay-button').onclick = function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                alert("Pembayaran berhasil");
                window.location.href = "{{ route('customer.profile', ['tab' => 'pembayaran']) }}";
            },
            onPending: function(result) {
                alert("Pembayaran sedang menunggu penyelesaian");
                window.location.href = "{{ route('customer.profile', ['tab' => 'pembayaran']) }}";
            },
            onError: function(result) {
                alert("Pembayaran gagal");
            },
            onClose: function() {
                alert("Kamu menutup popup pembayaran");
            }
        });
    };
</script>
@endsection