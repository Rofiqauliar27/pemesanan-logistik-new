@extends('layouts.admin')

@section('title', 'Edit Banner')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <h2>Edit Banner Beranda</h2>
            <p>Ubah data banner yang tampil pada halaman beranda marketplace.</p>
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
        <div class="admin-table-header">
            <div>
                <h4>Form Edit Banner</h4>
                <p>Sesuaikan judul, gambar, link tujuan, dan status banner.</p>
            </div>
        </div>

        <form action="{{ route('admin.home-banners.update', $homeBanner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="position" value="main">
            <input type="hidden" name="sort_order" value="{{ $homeBanner->sort_order ?? 0 }}">

            <div class="mb-3">
                <label class="form-label">Judul Banner</label>
                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $homeBanner->title) }}"
                    placeholder="Masukkan judul banner"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Sekarang</label><br>

                <img
                    src="{{ asset('storage/' . $homeBanner->image) }}"
                    alt="{{ $homeBanner->title }}"
                    class="admin-banner-thumb mb-2"
                    style="width: 220px; height: auto;"
                >

                <input type="file" name="image" class="form-control">

                <small class="text-muted">
                    Kosongkan kalau tidak ingin mengganti gambar. Ukuran banner disarankan 2048 x 520 px atau 1920 x 480 px.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Tujuan</label>
                <input
                    type="text"
                    name="link"
                    class="form-control"
                    value="{{ old('link', $homeBanner->link) }}"
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
                    {{ $homeBanner->is_active ? 'checked' : '' }}
                >
                <label class="form-check-label" for="is_active">
                    Aktif
                </label>
            </div>

            <div class="admin-action-group">
                <button type="submit" class="btn-admin-primary">
                    Update
                </button>

                <a href="{{ route('admin.home-banners.index') }}" class="btn-admin-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>

</div>
@endsection