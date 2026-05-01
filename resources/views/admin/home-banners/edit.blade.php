@extends('layouts.admin')

@section('title', 'Edit Banner')

@section('content')
<div class="container-fluid">
    <h3>Edit Banner Beranda</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.home-banners.update', $homeBanner->id) }}" method="POST" enctype="multipart/form-data" class="card">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="mb-3">
                <label>Judul Banner</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $homeBanner->title) }}">
            </div>

            <div class="mb-3">
                <label>Gambar Sekarang</label><br>

                <img src="{{ asset('storage/' . $homeBanner->image) }}" width="220" class="mb-2">

                <input type="file" name="image" class="form-control">

                <small class="text-muted">
                    Kosongkan kalau tidak ingin mengganti gambar.
                </small>
            </div>

            <div class="mb-3">
                <label>Link Tujuan</label>
                <input type="text" name="link" class="form-control" value="{{ old('link', $homeBanner->link) }}">
            </div>

            <div class="mb-3">
                <label>Posisi</label>
                <select name="position" class="form-control" required>
                    <option value="main" {{ $homeBanner->position === 'main' ? 'selected' : '' }}>
                        Banner Utama Tengah
                    </option>

                    <option value="side" {{ $homeBanner->position === 'side' ? 'selected' : '' }}>
                        Banner Samping Kanan
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label>Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $homeBanner->sort_order) }}">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $homeBanner->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Aktif</label>
            </div>

            <button class="btn btn-primary">
                Update
            </button>

            <a href="{{ route('admin.home-banners.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection