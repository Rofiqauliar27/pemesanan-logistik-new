@extends('layouts.public')

@section('title', 'Detail Produk')

@section('content')
    <div class="market-box">
        <div class="row">
            <div class="col-md-5 mb-4">
                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}"
                         alt="{{ $barang->nama_barang }}"
                         class="img-fluid rounded"
                         style="width: 100%; height: 360px; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                         style="height: 360px;">
                        <span class="text-muted">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            <div class="col-md-7 mb-4">
                <div class="mb-2">
                    <span class="badge bg-primary">{{ $barang->kategori ?? 'Produk' }}</span>
                </div>

                <h2 class="fw-bold mb-2">{{ $barang->nama_barang }}</h2>

                <div class="text-muted mb-2">Stok tersedia: {{ $barang->stok }}</div>

                <div class="price mb-3">
                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                </div>

                <div class="mb-4">
                    <h5>Deskripsi Produk</h5>
                    <p class="text-muted mb-0">
                        {{ $barang->deskripsi ?: 'Belum ada deskripsi produk.' }}
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
    @auth
        @if(auth()->user()->role === 'customer')
            <form action="{{ route('customer.keranjang.store', $barang->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">
                    Tambah ke Keranjang
                </button>
            </form>

            <a href="{{ route('customer.pesanan.create', $barang->id) }}" class="btn btn-success">
                Pesan Sekarang
            </a>
        @else
            <button class="btn btn-secondary" disabled>
                Login sebagai Customer
            </button>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-primary">
            Login untuk Pesan
        </a>
    @endauth

    <a href="{{ route('public.produk') }}" class="btn btn-outline-secondary">
        Kembali ke Produk
    </a>
</div>

                @guest
                    <div class="login-note mt-2">
                        Anda harus login terlebih dahulu untuk melakukan pemesanan.
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <div class="market-box">
        <div class="market-section-title">Produk Rekomendasi</div>
        <div class="market-section-subtitle">
            Produk lain yang mungkin Anda butuhkan.
        </div>

        <div class="row">
            @forelse($rekomendasi as $item)
                <div class="col-md-3 mb-4">
                    <div class="market-card h-100 d-flex flex-column">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}"
                                 alt="{{ $item->nama_barang }}">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                                 style="height: 190px;">
                                <span class="text-muted">Tidak ada gambar</span>
                            </div>
                        @endif

                        <h5>{{ $item->nama_barang }}</h5>
                        <div class="product-meta">Kategori: {{ $item->kategori ?? '-' }}</div>
                        <div class="price mb-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>

                        <div class="mt-auto">
                            <a href="{{ route('public.produk.show', $item->id) }}" class="btn btn-outline-primary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted mb-0">Belum ada produk rekomendasi.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection