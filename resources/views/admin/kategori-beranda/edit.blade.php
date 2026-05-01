@extends('layouts.admin')

@section('title', 'Edit Kategori Beranda')

@section('content')
<div class="container-fluid">
    <h3>Edit Kategori Beranda</h3>

    <form action="{{ route('admin.kategori-beranda.update', $kategoriBeranda->id) }}" method="POST" class="card">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="mb-3">
                <label>Nama Kategori</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategoriBeranda->nama) }}" required>
            </div>

            <div class="mb-3">
                <label>Icon</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon', $kategoriBeranda->icon) }}">
            </div>

            <div class="mb-3">
                <label>Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $kategoriBeranda->sort_order) }}">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $kategoriBeranda->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Aktif</label>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.kategori-beranda.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection