@extends('layouts.admin')

@section('title', 'Data Barang')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <h2>Data Barang</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">+ Tambah Barang</a>
        <a href="{{ url('/admin/dashboard') }}" class="btn btn-secondary mb-3">Kembali Dashboard</a>
        
        <form action="{{ route('barang.index') }}" method="GET" class="row g-2 mb-3">
    <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Cari nama barang / kategori / satuan..." value="{{ request('search') }}">
    </div>
    <div class="col-md-auto">
        <button type="submit" class="btn btn-dark">Cari</button>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
    @if($item->gambar)
        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Barang" width="70" class="img-thumbnail">
    @else
        <span class="text-muted">Tidak ada</span>
    @endif
</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>
                            <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data barang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection