@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <h2>Tambah Barang</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}">
            </div>

            <div class="mb-3">
    <label class="form-label">Kategori</label>

    <select name="kategori" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>

        @foreach($kategoriList as $kategori)
            <option value="{{ $kategori->nama }}" {{ old('kategori') == $kategori->nama ? 'selected' : '' }}>
                {{ $kategori->icon ? $kategori->icon . ' ' : '' }}{{ $kategori->nama }}
            </option>
        @endforeach
    </select>

    <small class="text-muted">
        Kategori diambil dari menu Kategori Beranda.
    </small>
</div>

            <div class="mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ old('stok') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-3">
    <label class="form-label">Gambar Barang</label>
    <input type="file" name="gambar" class="form-control">
</div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection