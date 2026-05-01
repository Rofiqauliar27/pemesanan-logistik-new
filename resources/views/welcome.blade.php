@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

<div class="indo-page">

    <section class="indo-hero-layout">
        <aside class="indo-category-box">
            <div class="category-title-row">
                <strong>KATEGORI</strong>
                <a href="{{ route('public.produk') }}">LIHAT SEMUA</a>
            </div>

           <div class="category-list">
    @foreach($kategoriMenu as $kategori)
        <div class="category-item category-item-with-products">
            <a href="{{ route('public.produk', ['kategori' => $kategori->nama]) }}" class="category-item-link">
                <div class="category-main">
                    <span class="category-icon">
                        {{ $kategori->icon ?? '▦' }}
                    </span>

                    <span>
                        {{ $kategori->nama }}
                    </span>
                </div>
            </a>

            <div class="category-mega product-mega">
                <div class="product-mega-header">
                    <h4>{{ $kategori->nama }}</h4>

                    <a href="{{ route('public.produk', ['kategori' => $kategori->nama]) }}">
                        Lihat Semua
                    </a>
                </div>

                @if(isset($produkKategori[$kategori->nama]) && $produkKategori[$kategori->nama]->count() > 0)
                    <div class="product-mega-grid">
                        @foreach($produkKategori[$kategori->nama] as $produk)
                            <a href="{{ route('public.produk.show', $produk->id) }}" class="product-mega-item">
                                @if($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_barang }}">
                                @else
                                    <div class="product-mega-no-image">
                                        Produk
                                    </div>
                                @endif

                                <div>
                                    <strong>{{ $produk->nama_barang }}</strong>
                                    <span>Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="product-mega-empty">
                        Belum ada produk di kategori ini.
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
        </aside>

        <div class="indo-main-slider">
            @if($mainBanners->count() > 0)
                <div id="homeMainBanner" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($mainBanners as $banner)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <a href="{{ $banner->link ?: '#' }}">
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#homeMainBanner" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#homeMainBanner" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @else
                <div class="default-main-banner">
                    <div>
                        <span>CV Bintang Saida Teknik</span>
                        <h1>Mau cari apa hari ini?</h1>
                        <p>
                            Cari produk logistik, kebutuhan kapal, bahan pokok,
                            sparepart, dan perlengkapan operasional.
                        </p>
                        <a href="{{ route('public.produk') }}">Cari Sekarang</a>
                    </div>
                </div>
            @endif
        </div>

        <aside class="indo-side-banner">
            @if($sideBanners->count() > 0)
                <div id="homeSideBanner" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($sideBanners as $banner)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <a href="{{ $banner->link ?: '#' }}">
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#homeSideBanner" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#homeSideBanner" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @else
                <div class="default-side-banner">
                    <h3>Promo Layanan</h3>
                    <p>Atur gambar iklan dari admin.</p>
                    <a href="{{ route('public.produk') }}">Lihat Produk</a>
                </div>
            @endif
        </aside>
    </section>

    <section class="home-product-section">
        <div class="home-product-header">
            <h2>CV Bintang Saida Teknik</h2>
            <a href="{{ route('public.produk') }}">Lihat Semua Produk</a>
        </div>

        <div class="home-product-grid">
            @forelse($barangs as $barang)
                <div class="home-product-card">
                    <a href="{{ route('public.produk.show', $barang->id) }}" class="product-image-area">
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}">
                        @else
                            <div class="no-product-image">
                                Produk
                            </div>
                        @endif
                    </a>

                    <div class="product-card-body">
                        <a href="{{ route('public.produk.show', $barang->id) }}" class="product-title">
                            {{ $barang->nama_barang }}
                        </a>

                        <div class="product-price">
                            Rp {{ number_format($barang->harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-product-home">
                    Belum ada produk.
                </div>
            @endforelse
        </div>
    </section>

</div>

@endsection