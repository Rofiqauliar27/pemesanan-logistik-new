@extends('layouts.customer')

@section('title', 'Daftar Barang')

@section('content')

    <div class="panel-box mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <div class="panel-title">Data Barang</div>
            <div class="panel-subtitle">
                @if(request('kategori'))
                    Menampilkan kategori: <strong>{{ request('kategori') }}</strong>
                @else
                    Menampilkan semua kategori barang
                @endif
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('customer.barang.index') }}"
               class="btn {{ request('kategori') ? 'btn-outline-secondary' : 'btn-primary' }}">
                Semua
            </a>

            @foreach($kategoris as $kategori)
                <a href="{{ route('customer.barang.index', ['kategori' => $kategori]) }}"
                   class="btn {{ request('kategori') == $kategori ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $kategori }}
                </a>
            @endforeach
        </div>
    </div>
</div>
    <div class="bg-white p-4 rounded shadow-sm">
        <h2>Daftar Barang</h2>

        <a href="{{ url('/customer/dashboard') }}" class="btn btn-secondary mb-3">Kembali Dashboard</a>
        <a href="{{ route('customer.pesanan.index') }}" class="btn btn-primary mb-3">Riwayat Pesanan</a>

        <form action="{{ route('customer.barang.index') }}" method="GET" class="row g-2 mb-3">
    <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Cari barang..." value="{{ request('search') }}">
    </div>
    <div class="col-md-auto">
        <button type="submit" class="btn btn-dark">Cari</button>
        <a href="{{ route('customer.barang.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
        <div class="row">
            @forelse($barangs as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($item->gambar)
    <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="Gambar Barang" style="height: 200px; object-fit: cover;">
@endif
                        <div class="card-body">
                            <h5>{{ $item->nama_barang }}</h5>
                            <p>Kategori: {{ $item->kategori }}</p>
                            <p>Satuan: {{ $item->satuan }}</p>
                            <p>Harga: Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <p>Stok: {{ $item->stok }}</p>
                            <p>{{ $item->deskripsi }}</p>

                            <a href="{{ route('customer.pesanan.create', $item->id) }}" class="btn btn-success">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p>Belum ada data barang</p>
            @endforelse
        </div>
    </div>
@endsection