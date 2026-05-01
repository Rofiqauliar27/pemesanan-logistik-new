@extends('layouts.admin')

@section('title', 'Kategori Beranda')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Pengaturan</span>
            <h2>Kategori Beranda</h2>
            <p>Kelola kategori yang tampil di sidebar beranda marketplace.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.kategori-beranda.create') }}" class="btn-admin-header-light">
                Tambah Kategori
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Daftar Kategori</h4>
                <p>Total data: {{ $kategoris->count() }} kategori</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th width="110">Icon</th>
                        <th>Nama</th>
                        <th width="120">Urutan</th>
                        <th>Status</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($kategoris as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <span class="admin-icon-preview">
                                    {{ $kategori->icon ?: '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="admin-product-name">
                                    {{ $kategori->nama }}
                                </div>
                            </td>

                            <td>
                                <span class="admin-stock-badge">
                                    {{ $kategori->sort_order }}
                                </span>
                            </td>

                            <td>
                                @if($kategori->is_active)
                                    <span class="admin-status-badge status-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="admin-status-badge status-pending">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="admin-action-group">
                                    <a href="{{ route('admin.kategori-beranda.edit', $kategori->id) }}" class="btn-table-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.kategori-beranda.destroy', $kategori->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus kategori ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-table-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="admin-empty-state">
                                    Belum ada kategori beranda.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection