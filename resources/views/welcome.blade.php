@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

<div class="indo-page">

    <section class="single-banner-section">
        @if($mainBanners->count() > 0)
            <div id="homeMainBanner" class="carousel slide single-banner-slider" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($mainBanners as $banner)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <a href="{{ $banner->link ?: '#' }}">
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>

                @if($mainBanners->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#homeMainBanner" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#homeMainBanner" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        @else
            <div class="default-main-banner">
                <div>
                    <span>CV Bintang Saida Teknik</span>
                    <h1>Solusi Produk & Logistik Dalam Satu Tempat</h1>
                    <p>
                        Temukan kebutuhan kapal, pergudangan, pengiriman,
                        dan perlengkapan operasional dengan lebih cepat dan rapi.
                    </p>
                    <a href="{{ route('public.produk') }}">Lihat Produk</a>
                </div>
            </div>
        @endif
    </section>

    <section class="popular-category-section">
        <div class="popular-category-card">
            <div class="popular-category-top">
                <div>
                    <h2>Kategori Pilihan</h2>
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

                    <div class="popular-category-illustration"></div>
                </div>

                <div class="popular-category-info">
                    <div class="popular-info-header">
                        <h3>Belanja Lebih Mudah</h3>
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
<div class="catalog-image-wrapper">

    @if($barang->is_top)
        <div class="catalog-best-badge">
            🔥 Produk Terlaris
        </div>
    @endif

    <a href="{{ route('public.produk.show', $barang->id) }}" class="product-image-area">

        @if($barang->gambar)
            <img src="{{ asset('storage/' . $barang->gambar) }}"
                 alt="{{ $barang->nama_barang }}">
        @else
            <div class="no-product-image">
                Produk
            </div>
        @endif

    </a>

</div>

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
                                @if(auth()->user()->role === 'customer')
                                    <button type="button"
                                            class="home-btn-cart"
                                            title="Tambah ke Keranjang"
                                            aria-label="Tambah ke Keranjang"
                                            data-bs-toggle="modal"
                                            data-bs-target="#homeCartModal{{ $barang->id }}">
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
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="home-btn-cart"
                                   title="Login untuk menambahkan ke keranjang"
                                   aria-label="Login untuk menambahkan ke keranjang">
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

                @auth
                    @if(auth()->user()->role === 'customer')
                        <div class="modal fade" id="homeCartModal{{ $barang->id }}" tabindex="-1" aria-hidden="true">
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

                                                @if($barang->status == 'aktif')
    <small class="text-success fw-semibold">
        Status : Tersedia
    </small>
@else
    <small class="text-danger fw-semibold">
        Status : Tidak Tersedia
    </small>
@endif
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn-cart-cancel" data-bs-dismiss="modal">
                                                Batal
                                            </button>

                                            <button type="submit" class="btn-cart-submit js-fly-to-cart">
    Tambah ke Keranjang
</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            @empty
                <div class="empty-product-home">
                    Belum ada produk.
                </div>
            @endforelse
        </div>
    </section>

</div>

@endsection

@section('scripts')
<script>
    function increaseHomeQty(inputId, maxStock) {
        const input = document.getElementById(inputId);
        let value = parseInt(input.value || 1);

        if (value < maxStock) {
            input.value = value + 1;
        }
    }

    function decreaseHomeQty(inputId) {
        const input = document.getElementById(inputId);
        let value = parseInt(input.value || 1);

        if (value > 1) {
            input.value = value - 1;
        }
    }
</script>
@endsection