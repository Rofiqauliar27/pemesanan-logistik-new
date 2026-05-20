@extends('layouts.admin')

@section('title', 'Tambah Banner')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <h2>Tambah Banner Beranda</h2>
            <p>Tambahkan banner utama yang tampil pada halaman beranda marketplace.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.home-banners.index') }}" class="btn-admin-header-light">
                Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger admin-alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card">

        <form action="{{ route('admin.home-banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="position" value="main">
            <input type="hidden" name="sort_order" value="0">

            <div class="mb-3">
                <label class="form-label">Judul Banner</label>
                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    placeholder="Masukkan judul banner"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Banner</label>
                <input type="file" name="image" class="form-control" required>

                <small class="text-muted">
                    Ukuran banner disarankan 2048 x 520 px atau 1920 x 480 px agar tampil rapi.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Tujuan</label>
                <input
                    type="text"
                    name="link"
                    class="form-control"
                    value="{{ old('link') }}"
                    placeholder="Contoh: /produk"
                >
            </div>

            <div class="form-check mb-4">
                <input
                    type="checkbox"
                    name="is_active"
                    class="form-check-input"
                    value="1"
                    id="is_active"
                    checked
                >
                <label class="form-check-label" for="is_active">
                    Aktif
                </label>
            </div>

            <div class="admin-action-group">
                <button type="submit" class="btn-admin-primary">
                    Simpan
                </button>

                <a href="{{ route('admin.home-banners.index') }}" class="btn-admin-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>

</div>
@endsection