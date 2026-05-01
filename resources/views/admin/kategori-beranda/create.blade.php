@extends('layouts.admin')

@section('title', 'Tambah Kategori Beranda')

@section('content')
<div class="container-fluid">
    <h3>Tambah Kategori Beranda</h3>

    <form action="{{ route('admin.kategori-beranda.store') }}" method="POST" class="card">
        @csrf

        <div class="card-body">
            <div class="mb-3">
                <label>Nama Kategori</label>
                <input type="text" name="nama" class="form-control" required>
                <small class="text-muted">
                    Nama ini harus sama dengan kategori barang. Contoh: Sembako, Sparepart, Safety.
                </small>
            </div>

            <div class="mb-3">
                <label>Icon</label>
                <input type="text" name="icon" class="form-control" placeholder="Contoh: 🍚 / ⚙️ / 🧯">
            </div>

            <div class="mb-3">
                <label>Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                <label class="form-check-label">Aktif</label>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.kategori-beranda.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection