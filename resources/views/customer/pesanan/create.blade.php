@extends('layouts.customer')

@section('title', 'Buat Pesanan')

@section('content')
    <div class="market-box">
        <h2 class="mb-1">Buat Pesanan</h2>
        <p class="text-muted mb-0">
            Silakan isi jumlah barang dan catatan pesanan Anda.
        </p>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="market-box">
                <h4 class="mb-3">Form Pemesanan</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.pesanan.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" value="{{ $barang->nama_barang }}" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" value="{{ $barang->kategori ?? '-' }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($barang->harga, 0, ',', '.') }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Pesan</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="{{ old('jumlah', 1) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Pesanan</label>
                        <textarea name="catatan" class="form-control" rows="4" placeholder="Contoh: warna merah, ukuran besar, pengiriman cepat...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-success">
                            Buat Pesanan
                        </button>

                        <a href="{{ route('public.produk') }}" class="btn btn-outline-secondary">
                            Kembali ke Produk
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="market-box">
                <h4 class="mb-3">Detail Barang</h4>

                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}"
                         alt="{{ $barang->nama_barang }}"
                         class="img-fluid rounded mb-3"
                         style="height: 240px; width: 100%; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                         style="height: 240px;">
                        <span class="text-muted">Tidak ada gambar</span>
                    </div>
                @endif

                <h5 class="fw-bold">{{ $barang->nama_barang }}</h5>
                <p class="text-muted mb-2">Kategori: {{ $barang->kategori ?? '-' }}</p>
                <p class="text-muted mb-2">Stok tersedia: {{ $barang->stok }}</p>
                <div class="price mb-3">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>

                <div class="alert alert-info mb-0">
                    Setelah pesanan dibuat, Anda akan diarahkan ke halaman pembayaran.
                </div>
            </div>
        </div>
    </div>
@endsection