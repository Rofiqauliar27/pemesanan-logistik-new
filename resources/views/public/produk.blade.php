@extends('layouts.public')

@section('title', 'Katalog Produk')

@section('content')

<div class="catalog-page">

    <div class="catalog-hero">
        <div>
            <span>Katalog Produk</span>
            <h1>Temukan Kebutuhan Logistik Perkapalan Anda</h1>
            <p>
                Pilih produk berdasarkan kategori, cari barang yang dibutuhkan,
                lalu lanjutkan ke detail produk atau tambahkan ke keranjang.
            </p>
        </div>

        <form action="{{ route('public.produk') }}" method="GET" class="catalog-search-box">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari nama produk, kategori, atau kebutuhan..." 
                value="{{ request('search') }}"
            >

            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif

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
               class="catalog-category-link {{ !request('kategori') ? 'active' : '' }}">
                Semua Produk
            </a>

            @foreach($kategoriList as $kategori)
                <a href="{{ route('public.produk', ['kategori' => $kategori, 'search' => request('search')]) }}"
                   class="catalog-category-link {{ request('kategori') == $kategori ? 'active' : '' }}">
                    {{ $kategori }}
                </a>
            @endforeach
        </aside>

        <main class="catalog-content">
            <div class="catalog-toolbar">
                <div>
                    <h2>
                        @if(request('kategori'))
                            {{ request('kategori') }}
                        @else
                            Semua Produk
                        @endif
                    </h2>

                    <p>
                        Menampilkan {{ $barangs->total() }} produk
                        @if(request('search'))
                            untuk pencarian "{{ request('search') }}"
                        @endif
                    </p>
                </div>

                <a href="{{ route('public.produk') }}" class="catalog-reset-btn">
                    Reset Filter
                </a>
            </div>

            @if($barangs->count() > 0)
                <div class="catalog-grid">
                    @foreach($barangs as $barang)
                        <div class="catalog-card">
                            <a href="{{ route('public.produk.show', $barang->id) }}" class="catalog-image">
                                @if($barang->gambar)
                                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}">
                                @else
                                    <div class="catalog-no-image">
                                        Produk
                                    </div>
                                @endif
                            </a>

                            <div class="catalog-body">
                                <div class="catalog-category-badge">
                                    {{ $barang->kategori ?? 'Tanpa Kategori' }}
                                </div>

                                <a href="{{ route('public.produk.show', $barang->id) }}" class="catalog-product-name">
                                    {{ $barang->nama_barang }}
                                </a>

                                <p class="catalog-desc">
                                    {{ \Illuminate\Support\Str::limit($barang->deskripsi ?? 'Produk logistik perkapalan.', 65) }}
                                </p>

                                <div class="catalog-price">
                                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                </div>

                                <div class="catalog-actions">
                                    <a href="{{ route('public.produk.show', $barang->id) }}" class="btn-catalog-detail">
                                        Detail
                                    </a>

                                    @auth
                                        @if(auth()->user()->role === 'customer')
                                            <form action="{{ route('customer.keranjang.store', $barang->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="jumlah" value="1">

                                                <button type="submit" class="btn-catalog-cart">
                                                    + Keranjang
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('public.produk.show', $barang->id) }}" class="btn-catalog-cart">
                                                Lihat
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn-catalog-cart">
                                            Login
                                        </a>
                                    @endauth
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
                    <p>Coba gunakan kata kunci lain atau pilih kategori berbeda.</p>
                    <a href="{{ route('public.produk') }}">Lihat semua produk</a>
                </div>
            @endif
        </main>

    </div>

</div>

@endsection