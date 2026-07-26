@extends('layouts.public')

@section('title', 'Produk')

@section('content')
<div class="catalog-page">

    <div class="catalog-hero">
        <div>
            <h1>Temukan Kebutuhan Logistik Perkapalan</h1>
            <p>
                Pilih produk logistik, perlengkapan kapal, alat kebersihan, safety equipment,
                dan kebutuhan operasional lainnya dengan mudah.
            </p>
        </div>

        <form action="{{ route('public.produk') }}" method="GET" class="catalog-search-box">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari produk yang Anda butuhkan...">

            <button type="submit">
                Cari
            </button>
        </form>
    </div>

    <div class="catalog-layout">

        <aside class="catalog-sidebar">
            <div class="catalog-sidebar-title">
                Kategori Produk
            </div>

            <a href="{{ route('public.produk') }}"
               class="catalog-category-link {{ request('kategori') ? '' : 'active' }}">
                Semua Produk
            </a>

            @foreach(($kategoris ?? $kategoriList ?? $kategoriProduk ?? []) as $kategori)
    <a href="{{ route('public.produk', ['kategori' => $kategori]) }}"
       class="catalog-category-link {{ request('kategori') == $kategori ? 'active' : '' }}">
        {{ $kategori }}
    </a>
@endforeach
        </aside>

        <main class="catalog-content">

            <div class="catalog-toolbar">
                <div>
                    <h2>Semua Produk</h2>
                    <p>Menampilkan {{ $barangs->count() }} produk</p>
                </div>

                @if(request('kategori') || request('search'))
                    <a href="{{ route('public.produk') }}" class="catalog-reset-btn">
                        Reset Filter
                    </a>
                @endif
            </div>

            @if($barangs->count() > 0)
                <div class="catalog-grid">
                    @foreach($barangs as $barang)
                        <div class="catalog-card">

                            <div class="catalog-image-wrapper">

    @if($barang->is_top)

        <div class="catalog-best-badge">
            ⭐ Produk Terlaris
        </div>

    @endif

    <a href="{{ route('public.produk.show', $barang->id) }}" class="catalog-image">

        @if($barang->gambar)

            <img src="{{ asset('storage/' . $barang->gambar) }}"
                 alt="{{ $barang->nama_barang }}">

        @else

            <div class="catalog-no-image">
                Tidak ada gambar
            </div>

        @endif

    </a>

</div>
                               
                            <div class="catalog-body">
                               
                                <a href="{{ route('public.produk.show', $barang->id) }}" class="catalog-product-name">
                                    {{ $barang->nama_barang }}
                                </a>

                            
                                <div class="catalog-price">
                                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                </div>

                                <div class="catalog-actions">
                                    <a href="{{ route('public.produk.show', $barang->id) }}" class="btn-catalog-detail">
                                        Detail
                                    </a>

                                    @auth

@if($barang->status == 'aktif')

<button
    type="button"
    class="btn-catalog-cart"
    title="Tambah ke Keranjang"
    data-bs-toggle="modal"
    data-bs-target="#cartModal{{ $barang->id }}">

    <svg class="cart-icon-svg" viewBox="0 0 24 24" fill="none">
        <path d="M6.2 6.5H20L18.6 13.2C18.45 13.95 17.78 14.5 17 14.5H9.1C8.32 14.5 7.65 13.95 7.5 13.2L5.8 4.8C5.7 4.35 5.3 4 4.83 4H3.5"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"/>
        <path d="M9.5 19.2C10.05 19.2 10.5 18.75 10.5 18.2C10.5 17.65 10.05 17.2 9.5 17.2C8.95 17.2 8.5 17.65 8.5 18.2C8.5 18.75 8.95 19.2 9.5 19.2Z"
              fill="currentColor"/>
        <path d="M17 19.2C17.55 19.2 18 18.75 18 18.2C18 17.65 17.55 17.2 17 17.2C16.45 17.2 16 17.65 16 18.2C16 18.75 16.45 19.2 17 19.2Z"
              fill="currentColor"/>
    </svg>

</button>

@else

<button
type="button"
class="btn-catalog-cart"
disabled
style="opacity:.5;cursor:not-allowed;"
title="Barang Tidak Tersedia">

<svg class="cart-icon-svg" viewBox="0 0 24 24" fill="none">

<path d="M6.2 6.5H20L18.6 13.2C18.45 13.95 17.78 14.5 17 14.5H9.1C8.32 14.5 7.65 13.95 7.5 13.2L5.8 4.8C5.7 4.35 5.3 4 4.83 4H3.5"
stroke="currentColor"
stroke-width="2"
stroke-linecap="round"
stroke-linejoin="round"/>

<path d="M9.5 19.2C10.05 19.2 10.5 18.75 10.5 18.2C10.5 17.65 10.05 17.2 9.5 17.2C8.95 17.2 8.5 17.65 8.5 18.2C8.5 18.75 8.95 19.2 9.5 19.2Z"
fill="currentColor"/>

<path d="M17 19.2C17.55 19.2 18 18.75 18 18.2C18 17.65 17.55 17.2 17 17.2C16.45 17.2 16 17.65 16 18.2C16 18.75 16.45 19.2 17 19.2Z"
fill="currentColor"/>

</svg>

</button>

@endif

@else

<a href="{{ route('login') }}"
class="btn-catalog-cart"
title="Login">

<svg class="cart-icon-svg" viewBox="0 0 24 24" fill="none">
    <path d="M6.2 6.5H20L18.6 13.2C18.45 13.95 17.78 14.5 17 14.5H9.1C8.32 14.5 7.65 13.95 7.5 13.2L5.8 4.8C5.7 4.35 5.3 4 4.83 4H3.5"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"/>
    <path d="M9.5 19.2C10.05 19.2 10.5 18.75 10.5 18.2C10.5 17.65 10.05 17.2 9.5 17.2C8.95 17.2 8.5 17.65 8.5 18.2C8.5 18.75 8.95 19.2 9.5 19.2Z"
          fill="currentColor"/>
    <path d="M17 19.2C17.55 19.2 18 18.75 18 18.2C18 17.65 17.55 17.2 17 17.2C16.45 17.2 16 17.65 16 18.2C16 18.75 16.45 19.2 17 19.2Z"
          fill="currentColor"/>
</svg>

</a>

@endauth
                                </div>
                            </div>
                        </div>
                <div class="modal fade" id="cartModal{{ $barang->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cart-product-modal">
            <div class="modal-header">
                <h5 class="modal-title">Tambah ke Keranjang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <form action="{{ route('customer.keranjang.store', ['barangId' => $barang->id]) }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="cart-modal-product">
                        <div class="cart-modal-image">
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}">
                            @else
                                <span>Produk</span>
                            @endif
                        </div>

                        <div>
                            <h6>{{ $barang->nama_barang }}</h6>
                            <p>{{ $barang->kategori ?? 'Tanpa Kategori' }}</p>
                            <strong>Rp {{ number_format($barang->harga, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="cart-modal-qty">
                                                <label>Jumlah</label>

                                                <div class="qty-control-modal">
                                                    <button type="button"
                                                            class="qty-minus"
                                                            onclick="decreaseHomeQty('homeQty{{ $barang->id }}')">
                                                        −
                                                    </button>

                                                    <input type="number"
                                                           id="homeQty{{ $barang->id }}"
                                                           name="jumlah"
                                                           value="1"
                                                           min="1"
                                                           max="{{ $barang->stok ?? 999 }}"
                                                           required>

                                                    <button type="button"
                                                            class="qty-plus"
                                                            onclick="increaseHomeQty('homeQty{{ $barang->id }}', {{ $barang->stok ?? 999 }})">
                                                        +
                                                    </button>
                                                </div>

@if($barang->status=='aktif')

<small class="text-success">
Status : Tersedia
</small>

@else

<small class="text-danger">
Status : Tidak Tersedia
</small>

@endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cart-cancel" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
    type="submit"
    class="btn-cart-submit js-fly-to-cart">
    Tambah ke Keranjang
</button>
                </div>
            </form>
        </div>
    </div>
</div>
                    @endforeach
                </div>

                <div class="catalog-pagination">
{{ $barangs->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="catalog-empty">
                    <h4>Produk tidak ditemukan</h4>
                    <p>Tidak ada produk yang sesuai dengan pencarian atau kategori ini.</p>
                    <a href="{{ route('public.produk') }}">Lihat Semua Produk</a>
                </div>
            @endif

        </main>
    </div>
</div>

<script>

    function increaseHomeQty(inputId, maxStock) {
        const input = document.getElementById(inputId);
        let value = parseInt(input.value || 1);

        if (value < maxStock) {
            input.value = value + 1;
        }
    }
function increaseQty(inputId){

const input=document.getElementById(inputId);

let value=parseInt(input.value||1);

input.value=value+1;

}

    function decreaseQty(inputId) {
        const input = document.getElementById(inputId);
        let value = parseInt(input.value || 1);

        if (value > 1) {
            input.value = value - 1;
        }
    }
</script>
@endsection