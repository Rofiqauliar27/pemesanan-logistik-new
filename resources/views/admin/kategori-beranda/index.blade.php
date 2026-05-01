@extends('layouts.admin')

@section('title', 'Kategori Beranda')

@section('content')
<div class="container-fluid">
    <h3>Kategori Beranda</h3>

    <a href="{{ route('admin.kategori-beranda.create') }}" class="btn btn-primary mb-3">
        Tambah Kategori
    </a>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Icon</th>
                        <th>Nama</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-size: 22px;">{{ $kategori->icon }}</td>
                            <td>{{ $kategori->nama }}</td>
                            <td>{{ $kategori->sort_order }}</td>
                            <td>{{ $kategori->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>
                                <a href="{{ route('admin.kategori-beranda.edit', $kategori->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('admin.kategori-beranda.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
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
                            <td colspan="6" class="text-center">
                                Belum ada kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection