@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Master Data</span>
            <h2>Tambah Barang</h2>
            <p>Tambahkan produk baru yang akan tampil di katalog dan beranda marketplace.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('barang.index') }}" class="btn-admin-light">
                Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="admin-card">
            <div class="alert alert-danger mb-0">
                <strong>Data belum lengkap.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="admin-form-layout">
        @csrf

        <div class="admin-card">
            <div class="admin-form-section-title">
                <h4>Informasi Barang</h4>
                <p>Isi nama, kategori, satuan, harga, stok, dan deskripsi barang.</p>
            </div>

            <div class="admin-form-grid">
                <div class="admin-form-group full">
                    <label>Nama Barang</label>
                    <input 
                        type="text" 
                        name="nama_barang" 
                        value="{{ old('nama_barang') }}" 
                        placeholder="Contoh: Beras 50kg"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>

                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori->nama }}" {{ old('kategori') == $kategori->nama ? 'selected' : '' }}>
                                {{ $kategori->icon ? $kategori->icon . ' ' : '' }}{{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>

                    <small>Kategori diambil dari menu Kategori Beranda.</small>
                </div>

                <div class="admin-form-group">
                    <label>Satuan</label>
                    <input 
                        type="text" 
                        name="satuan" 
                        value="{{ old('satuan') }}" 
                        placeholder="Contoh: pcs, dus, kg, unit"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Harga</label>
                    <input 
                        type="number" 
                        name="harga" 
                        value="{{ old('harga') }}" 
                        placeholder="Contoh: 150000"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Stok</label>
                    <input 
                        type="number" 
                        name="stok" 
                        value="{{ old('stok') }}" 
                        placeholder="Contoh: 20"
                    >
                </div>

                <div class="admin-form-group full">
                    <label>Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
                        rows="5" 
                        placeholder="Tuliskan deskripsi singkat barang..."
                    >{{ old('deskripsi') }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-form-section-title">
                <h4>Gambar Barang</h4>
                <p>Upload gambar produk dengan format JPG, PNG, atau WEBP.</p>
            </div>

            <div class="admin-form-group full">
                <label>Upload Gambar</label>
                <input type="file" name="gambar">
                <small>Maksimal 2MB. Gunakan gambar yang jelas agar produk terlihat profesional.</small>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('barang.index') }}" class="btn-admin-light">
                Batal
            </a>

            <button type="submit" class="btn-admin-primary">
                Simpan Barang
            </button>
        </div>
    </form>

</div>
@endsection