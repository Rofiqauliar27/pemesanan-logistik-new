@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Master Data</span>
            <h2>Edit Barang</h2>
            <p>Perbarui data barang, kategori, harga, stok, deskripsi, dan gambar produk.</p>
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

    <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="admin-form-layout">
        @csrf
        @method('PUT')

        <div class="admin-card">
            <div class="admin-form-section-title">
                <h4>Informasi Barang</h4>
                <p>Ubah nama, kategori, satuan, harga, stok, dan deskripsi barang.</p>
            </div>

            <div class="admin-form-grid">
                <div class="admin-form-group full">
                    <label>Nama Barang</label>
                    <input
                        type="text"
                        name="nama_barang"
                        value="{{ old('nama_barang', $barang->nama_barang) }}"
                        placeholder="Contoh: Beras 50kg"
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Kategori</label>

                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>

                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori->nama }}" {{ old('kategori', $barang->kategori) == $kategori->nama ? 'selected' : '' }}>
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
                        value="{{ old('satuan', $barang->satuan) }}"
                        placeholder="Contoh: pcs, dus, kg, unit"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Harga</label>
                    <input
                        type="text"
                        name="harga"
                        class="harga-input"
                        value="{{ old('harga', (int) $barang->harga) }}"
                        placeholder="Contoh: 120000"
                        inputmode="numeric"
                        autocomplete="off"
                        required
                    >
                    <small>Masukkan angka biasa tanpa titik. Contoh: 120000</small>
                </div>

                <div class="admin-form-group">
                    <label>Stok</label>
                    <input
                        type="number"
                        name="stok"
                        value="{{ old('stok', (int) $barang->stok) }}"
                        placeholder="Contoh: 20"
                        min="0"
                        step="1"
                        required
                    >
                </div>

                <div class="admin-form-group full">
                    <label>Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        rows="5"
                        placeholder="Tuliskan deskripsi singkat barang..."
                    >{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-form-section-title">
                <h4>Gambar Barang</h4>
                <p>Upload gambar baru jika ingin mengganti gambar produk.</p>
            </div>

            <div class="admin-form-group full">
                <label>Upload Gambar Baru</label>
                <input type="file" name="gambar">
                <small>Maksimal 2MB. Format JPG, JPEG, PNG, atau WEBP.</small>
            </div>

            @if($barang->gambar)
                <div class="admin-form-group full">
                    <label>Gambar Saat Ini</label>
                    <img
                        src="{{ asset('storage/' . $barang->gambar) }}"
                        alt="{{ $barang->nama_barang }}"
                        class="img-thumbnail"
                        style="width: 140px; height: 140px; object-fit: contain;"
                    >
                </div>
            @endif
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('barang.index') }}" class="btn-admin-light">
                Batal
            </a>

            <button type="submit" class="btn-admin-primary">
                Update Barang
            </button>
        </div>
    </form>

</div>

<script>
    document.querySelectorAll('.harga-input').forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endsection