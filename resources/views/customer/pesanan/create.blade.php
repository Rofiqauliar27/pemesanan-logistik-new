@extends('layouts.public')

@section('title', 'Buat Pesanan')

@section('content')
<div class="order-create-page">

    <div class="order-hero">
        <div>
            <span>Form Pemesanan</span>
            <h1>Buat Pesanan</h1>
            <p>
                Lengkapi jumlah barang dan catatan pesanan Anda sebelum melanjutkan ke pembayaran.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger order-alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="order-create-layout">

        <div class="order-form-card">
            <div class="order-card-header">
                <div>
                    <span>Data Pesanan</span>
                    <h3>Informasi Pemesanan</h3>
                    <p>Pastikan jumlah dan catatan pesanan sudah sesuai.</p>
                </div>
            </div>

            <form action="{{ route('customer.pesanan.store') }}" method="POST">
                @csrf

                <input type="hidden" name="barang_id" value="{{ $barang->id }}">

                <div class="order-form-group">
                    <label>Nama Barang</label>
                    <input
                        type="text"
                        class="order-form-control"
                        value="{{ $barang->nama_barang }}"
                        readonly
                    >
                </div>

                <div class="order-form-row">
                    <div class="order-form-group">
                        <label>Kategori</label>
                        <input
                            type="text"
                            class="order-form-control"
                            value="{{ $barang->kategori ?? '-' }}"
                            readonly
                        >
                    </div>

                    <div class="order-form-group">
                        <label>Harga Satuan</label>
                        <input
                            type="text"
                            class="order-form-control"
                            value="Rp {{ number_format($barang->harga, 0, ',', '.') }}"
                            readonly
                        >
                    </div>
                </div>

                <div class="order-form-group">
                    <label>Jumlah Pesan</label>
                    <input
                        type="number"
                        name="jumlah"
                        class="order-form-control"
                        min="1"
                        max="{{ $barang->stok }}"
                        value="{{ old('jumlah', 1) }}"
                        required
                    >
                </div>

                <div class="order-form-group">
                    <label>Catatan Pesanan</label>
                    <textarea
                        name="catatan"
                        class="order-form-control order-textarea"
                        rows="5"
                        placeholder="Contoh: warna merah, ukuran besar, pengiriman cepat..."
                    >{{ old('catatan') }}</textarea>
                </div>

                <div class="order-info-note">
                    Setelah pesanan dibuat, Anda akan diarahkan ke halaman pembayaran.
                </div>

                <div class="order-form-actions">
                    <a href="{{ route('public.produk') }}" class="order-btn-secondary">
                        Kembali ke Produk
                    </a>

                    <button type="submit" class="order-btn-primary">
                        Buat Pesanan
                    </button>
                </div>
            </form>
        </div>

        <aside class="order-detail-card">
            <div class="order-detail-image">
                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}">
                @else
                    <div class="order-no-image">
                        Tidak ada gambar
                    </div>
                @endif
            </div>

            <div class="order-detail-body">
                <span class="order-detail-badge">
                    {{ $barang->kategori ?? 'Produk' }}
                </span>

                <h3>{{ $barang->nama_barang }}</h3>

                <div class="order-price">
                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                </div>

                <div class="order-summary-list">
                    <div>
                        <span>Kategori</span>
                        <strong>{{ $barang->kategori ?? '-' }}</strong>
                    </div>

                    <div>
                        <span>Stok Tersedia</span>
                        <strong>{{ $barang->stok }}</strong>
                    </div>

                    <div>
                        <span>Status</span>
                        <strong>Tersedia</strong>
                    </div>
                </div>
            </div>
        </aside>

    </div>

</div>
@endsection