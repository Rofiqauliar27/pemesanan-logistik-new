@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

<div class="indo-page">

    <section class="indo-hero-layout">


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

<section class="popular-category-section">
    <div class="popular-category-card">
        <div class="popular-category-top">
            <div>
                <h2>Kategori Populer</h2>
                <p>Pilih kebutuhan produk berdasarkan kategori yang tersedia.</p>
            </div>

            <a href="{{ route('public.produk') }}">
                Lihat Semua
            </a>
        </div>

        <div class="popular-category-content">
            <div class="popular-category-banner">
                <div>
                    <h3>Temukan kebutuhan logistik Anda</h3>
                    <p>
                        Produk logistik, perlengkapan kapal, alat kebersihan,
                        safety equipment, dan kebutuhan usaha lainnya.
                    </p>

                    <a href="{{ route('public.produk') }}">
                        Cek Sekarang
                    </a>
                </div>

                <div class="popular-category-illustration">
                    📦
                </div>
            </div>

            <div class="popular-category-info">
                <div class="popular-info-header">
                    <h3>Belanja Lebih Mudah</h3>
                    <a href="{{ route('public.produk') }}">Lihat Semua</a>
                </div>

                <div class="popular-info-grid">
                    <div>
                        <strong>Produk</strong>
                        <span>Cari barang sesuai kebutuhan</span>
                    </div>

                    <div>
                        <strong>Keranjang</strong>
                        <span>Simpan produk sebelum checkout</span>
                    </div>

                    <div>
                        <strong>Pesanan</strong>
                        <span>Pantau status pesanan Anda</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="popular-category-list">
            @foreach($kategoriMenu as $kategori)
                <a href="{{ route('public.produk', ['kategori' => $kategori->nama]) }}" class="popular-category-pill">
                    <span class="popular-category-icon">
                        {{ $kategori->icon ?? '▦' }}
                    </span>

                    <span>
                        {{ $kategori->nama }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
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

                <div class="home-product-actions">
                    <a href="{{ route('public.produk.show', $barang->id) }}" class="home-btn-detail">
                        Detail
                    </a>

                    @auth
                        <form action="{{ route('customer.keranjang.store', ['barangId' => $barang->id]) }}" method="POST" class="home-cart-form">
                            @csrf

                            <button type="submit" class="home-btn-cart" title="Tambah ke Keranjang" aria-label="Tambah ke Keranjang">
                                <svg class="cart-icon-svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
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
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="home-btn-cart" title="Login untuk menambahkan ke keranjang" aria-label="Login untuk menambahkan ke keranjang">
                            <svg class="cart-icon-svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
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
    @empty
        <div class="empty-product-home">
            Belum ada produk.
        </div>
    @endforelse
</div>
    </section>

</div>

@endsection