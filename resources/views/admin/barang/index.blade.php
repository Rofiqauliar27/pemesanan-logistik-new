@extends('layouts.admin')

@section('title', 'Data Barang')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Master Data</span>
            <h2>Data Barang</h2>
            <p>Kelola data barang, kategori, harga, stok, dan gambar produk.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('barang.create') }}" class="btn-admin-primary">
                Tambah Barang
            </a>
        </div>
    </div>

    <div class="admin-card">
        <form action="{{ route('barang.index') }}" method="GET" class="admin-filter-form">
            <div class="admin-search-field">
                <label>Cari Barang</label>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama barang, kategori, atau satuan..." 
                    value="{{ request('search') }}"
                >
            </div>

            <div class="admin-filter-actions">
                <button type="submit" class="btn-admin-primary">
    Cari
</button>

                <a href="{{ route('barang.index') }}" class="btn-admin-secondary">
    Reset
</a>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Daftar Barang</h4>
                <p>Total data: {{ $barangs->count() }} barang</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th width="95">Gambar</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Deskripsi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($barangs as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                @if($item->gambar)
                                    <img 
                                        src="{{ asset('storage/' . $item->gambar) }}" 
                                        alt="{{ $item->nama_barang }}" 
                                        class="admin-product-thumb"
                                    >
                                @else
                                    <div class="admin-no-image">
                                        No Image
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="admin-product-name">
                                    {{ $item->nama_barang }}
                                </div>
                            </td>

                            <td>
                                <span class="admin-category-badge">
                                    {{ $item->kategori ?? '-' }}
                                </span>
                            </td>

                            <td>{{ $item->satuan ?? '-' }}</td>

                            <td>
                                <strong>
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                <span class="admin-stock-badge {{ $item->stok <= 5 ? 'low' : '' }}">
                                    {{ $item->stok }}
                                </span>
                            </td>

                            <td>
                                <div class="admin-desc-text">
                                    {{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 60) }}
                                </div>
                            </td>

                            <td>
                                <div class="admin-action-group">
                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn-table-edit">
    Edit
</a>

                                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button 
    type="submit" 
    onclick="return confirm('Yakin hapus data ini?')" 
    class="btn-table-delete"
>
    Hapus
</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="admin-empty-state">
                                    Belum ada data barang.
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