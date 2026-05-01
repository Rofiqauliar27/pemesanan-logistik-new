@extends('layouts.admin')

@section('title', 'Data Pesanan')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <h2>Data Pesanan Customer</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ url('/admin/dashboard') }}" class="btn btn-secondary mb-3">Kembali Dashboard</a>
        
        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Cari customer / barang..." value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">Semua Status Pesanan</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>pending</option>
            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>diproses</option>
            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>dikirim</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>selesai</option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="payment_status" class="form-control">
            <option value="">Semua Status Bayar</option>
            <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>belum_bayar</option>
            <option value="menunggu_pembayaran" {{ request('payment_status') == 'menunggu_pembayaran' ? 'selected' : '' }}>menunggu_pembayaran</option>
            <option value="sudah_bayar" {{ request('payment_status') == 'sudah_bayar' ? 'selected' : '' }}>sudah_bayar</option>
            <option value="gagal" {{ request('payment_status') == 'gagal' ? 'selected' : '' }}>gagal</option>
            <option value="challenge" {{ request('payment_status') == 'challenge' ? 'selected' : '' }}>challenge</option>
        </select>
    </div>

    <div class="col-md-auto">
        <button type="submit" class="btn btn-dark">Filter</button>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge bg-secondary">{{ $item->status }}</span>
                            @elseif($item->status == 'diproses')
                                <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                            @elseif($item->status == 'dikirim')
                                <span class="badge bg-primary">{{ $item->status }}</span>
                            @elseif($item->status == 'selesai')
                                <span class="badge bg-success">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>{{ $item->catatan }}</td>
                    <td>
    <a href="{{ route('admin.pesanan.show', $item->id) }}" class="btn btn-sm btn-info text-white mb-1">
        Detail
    </a>

    <br>

    @if($item->payment_status == 'sudah_bayar')
        <a href="{{ route('admin.pesanan.editStatus', $item->id) }}" class="btn btn-sm btn-warning">
            Ubah Status
        </a>
    @elseif($item->payment_status == 'menunggu_pembayaran')
        <button class="btn btn-sm btn-secondary" disabled>
            Menunggu Pembayaran
        </button>
    @elseif($item->payment_status == 'gagal')
        <button class="btn btn-sm btn-danger" disabled>
            Pembayaran Gagal
        </button>
    @else
        <button class="btn btn-sm btn-secondary" disabled>
            Belum Bayar
        </button>
    @endif
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection