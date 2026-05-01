@extends('layouts.public')

@section('title', $barang->nama_barang)

@section('content')

<div class="product-detail-page">

    <div class="product-detail-card">
        <div class="product-detail-image">
            @if($barang->gambar)
                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}">
            @else
                <div class="detail-no-image">Produk</div>
            @endif
        </div>

        <div class="product-detail-info">
            <div class="detail-category">
                {{ $barang->kategori ?? 'Tanpa Kategori' }}
            </div>

            <h1>{{ $barang->nama_barang }}</h1>

            <div class="detail-price">
                Rp {{ number_format($barang->harga, 0, ',', '.') }}
            </div>

            <div class="detail-meta-modern">
    @if(isset($barang->stok) && $barang->stok > 0)
        <span class="meta-chip meta-status available">
            Tersedia
        </span>
    @else
        <span class="meta-chip meta-status unavailable">
            Stok Habis
        </span>
    @endif

    @if(isset($barang->stok))
        <span class="meta-chip meta-stock">
            Stok: {{ $barang->stok }}
        </span>
    @endif
</div>

            <div class="detail-order-section">
    @auth
        @if(auth()->user()->role === 'customer')
            <div class="detail-order-row">

                <form action="{{ route('customer.keranjang.store', $barang->id) }}" method="POST" class="detail-order-form">
                    @csrf

                    <div class="qty-box">
                        <label class="qty-label">Jumlah</label>

                        <div class="qty-control">
                            <button type="button" class="qty-btn qty-minus" id="qtyMinus">-</button>

                            <input 
                                type="number" 
                                name="jumlah" 
                                id="qtyInput"
                                class="qty-input" 
                                value="1" 
                                min="1"
                            >

                            <button type="button" class="qty-btn qty-plus" id="qtyPlus">+</button>
                        </div>
                    </div>

                    <button type="submit" class="btn-cart-mini">
                        <span class="cart-icon">🛒</span>
                        <span>Keranjang</span>
                    </button>
                </form>

                <a href="{{ route('customer.pesanan.create', $barang->id) }}" class="btn-buy-now-mini">
                    Pesan Sekarang
                </a>

            </div>
        @else
            <a href="{{ route('public.produk') }}" class="btn-buy-now-mini">
                Kembali ke Produk
            </a>
        @endif
    @else
        <div class="detail-order-row">
            <a href="{{ route('login') }}" class="btn-cart-mini">
                🛒 Login Dulu
            </a>

            <a href="{{ route('register') }}" class="btn-buy-now-mini">
                Daftar Customer
            </a>
        </div>
    @endauth
</div>

    <div class="product-description-card">
        <h3>Deskripsi Produk</h3>
        <p>
            {{ $barang->deskripsi ?? 'Belum ada deskripsi detail untuk produk ini.' }}
        </p>
    </div>

    @if($produkTerkait->count() > 0)
        <div class="related-product-section">
            <div class="related-header">
                <h3>Produk Terkait</h3>
                <a href="{{ route('public.produk') }}">Lihat Semua</a>
            </div>

            <div class="related-product-grid">
                @foreach($produkTerkait as $item)
                    <div class="related-product-card">
                        <a href="{{ route('public.produk.show', $item->id) }}" class="related-image">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}">
                            @else
                                <div class="detail-no-image">Produk</div>
                            @endif
                        </a>

                        <div class="related-body">
                            <a href="{{ route('public.produk.show', $item->id) }}">
                                {{ $item->nama_barang }}
                            </a>

                            <div>
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qtyInput = document.getElementById('qtyInput');
        const qtyMinus = document.getElementById('qtyMinus');
        const qtyPlus = document.getElementById('qtyPlus');

        if (qtyInput && qtyMinus && qtyPlus) {
            qtyMinus.addEventListener('click', function () {
                let current = parseInt(qtyInput.value) || 1;
                if (current > 1) {
                    qtyInput.value = current - 1;
                }
            });

            qtyPlus.addEventListener('click', function () {
                let current = parseInt(qtyInput.value) || 1;
                qtyInput.value = current + 1;
            });
        }
    });
</script>
@endsection