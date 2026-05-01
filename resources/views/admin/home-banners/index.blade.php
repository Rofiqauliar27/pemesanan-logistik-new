@extends('layouts.admin')

@section('title', 'Banner Beranda')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Pengaturan</span>
            <h2>Banner Beranda</h2>
            <p>Kelola banner yang tampil pada halaman beranda marketplace.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.home-banners.create') }}" class="btn-admin-header-light">
                Tambah Banner
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Daftar Banner</h4>
                <p>Total data: {{ $banners->count() }} banner</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th width="160">Gambar</th>
                        <th>Judul</th>
                        <th>Posisi</th>
                        <th width="100">Urutan</th>
                        <th>Status</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <img
                                    src="{{ asset('storage/' . $banner->image) }}"
                                    alt="{{ $banner->title }}"
                                    class="admin-banner-thumb"
                                >
                            </td>

                            <td>
                                <div class="admin-product-name">
                                    {{ $banner->title }}
                                </div>
                            </td>

                            <td>
                                <span class="admin-category-badge">
                                    @if($banner->position === 'main')
                                        Banner Utama Tengah
                                    @else
                                        Banner Samping Kanan
                                    @endif
                                </span>
                            </td>

                            <td>
                                <span class="admin-stock-badge">
                                    {{ $banner->sort_order }}
                                </span>
                            </td>

                            <td>
                                @if($banner->is_active)
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
                                    <a href="{{ route('admin.home-banners.edit', $banner->id) }}" class="btn-table-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.home-banners.destroy', $banner->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus banner ini?')"
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
                            <td colspan="7">
                                <div class="admin-empty-state">
                                    Belum ada banner beranda.
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