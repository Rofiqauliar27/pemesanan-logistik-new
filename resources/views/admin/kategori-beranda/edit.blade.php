@extends('layouts.admin')

@section('title', 'Edit Kategori Beranda')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <h2>Edit Kategori Beranda</h2>
            <p>Ubah data kategori pilihan yang tampil pada halaman beranda marketplace.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.kategori-beranda.index') }}" class="btn-admin-header-light">
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
                <h4>Form Edit Kategori</h4>
                <p>Sesuaikan nama kategori, icon, dan status aktif kategori beranda.</p>
            </div>
        </div>

        <form action="{{ route('admin.kategori-beranda.update', $kategoriBeranda->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="sort_order" value="{{ $kategoriBeranda->sort_order ?? 0 }}">

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama', $kategoriBeranda->nama) }}"
                    placeholder="Contoh: Safety Equipment"
                    required
                >

                <small class="text-muted">
                    Nama ini harus sama dengan kategori barang. Contoh: Sembako, Sparepart, Safety.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Icon</label>
                <input
                    type="text"
                    name="icon"
                    class="form-control"
                    value="{{ old('icon', $kategoriBeranda->icon) }}"
                    placeholder="Contoh: ⛑️ / ⚙️ / 🧯"
                >

                <small class="text-muted">
                    Gunakan emoji sederhana agar tampilan kategori lebih menarik.
                </small>
            </div>

            <div class="form-check mb-4">
                <input
                    type="checkbox"
                    name="is_active"
                    class="form-check-input"
                    value="1"
                    id="is_active"
                    {{ $kategoriBeranda->is_active ? 'checked' : '' }}
                >
                <label class="form-check-label" for="is_active">
                    Aktif
                </label>
            </div>

            <div class="admin-action-group">
                <button type="submit" class="btn-admin-primary">
                    Update
                </button>

                <a href="{{ route('admin.kategori-beranda.index') }}" class="btn-admin-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>

</div>
@endsection