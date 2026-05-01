@extends('layouts.admin')

@section('title', 'Banner Beranda')

@section('content')
<div class="container-fluid">
    <h3>Banner Beranda</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.home-banners.create') }}" class="btn btn-primary mb-3">
        Tambah Banner
    </a>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Posisi</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $banner->image) }}" width="120">
                            </td>
                            <td>{{ $banner->title }}</td>
                            <td>
                                @if($banner->position === 'main')
                                    Banner Utama Tengah
                                @else
                                    Banner Samping Kanan
                                @endif
                            </td>
                            <td>{{ $banner->sort_order }}</td>
                            <td>
                                {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.home-banners.edit', $banner->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('admin.home-banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus banner ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada banner.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection