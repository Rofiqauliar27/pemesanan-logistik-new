@extends('layouts.admin')

@section('title', 'Tambah Banner')

@section('content')
<div class="container-fluid">
    <h3>Tambah Banner Beranda</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.home-banners.store') }}" method="POST" enctype="multipart/form-data" class="card">
        @csrf

        <div class="card-body">
            <div class="mb-3">
                <label>Judul Banner</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label>Gambar</label>
                <input type="file" name="image" class="form-control" required>
                <small class="text-muted">
                    Banner utama disarankan ukuran lebar. Contoh: 800 x 420 px.
                    Banner samping disarankan: 300 x 420 px.
                </small>
            </div>

            <div class="mb-3">
                <label>Link Tujuan</label>
                <input type="text" name="link" class="form-control" value="{{ old('link') }}" placeholder="Contoh: /produk">
            </div>

            <div class="mb-3">
                <label>Posisi</label>
                <select name="position" class="form-control" required>
                    <option value="main">Banner Utama Tengah</option>
                    <option value="side">Banner Samping Kanan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                <label class="form-check-label">Aktif</label>
            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

            <a href="{{ route('admin.home-banners.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection