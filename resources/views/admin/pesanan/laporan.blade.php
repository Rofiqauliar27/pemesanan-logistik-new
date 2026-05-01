@extends('layouts.admin')

@section('title', 'Laporan Pesanan')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <h2>Laporan Pesanan</h2>

        <a href="{{ url('/admin/dashboard') }}" class="btn btn-secondary mb-3">Kembali Dashboard</a>
        <a href="{{ route('admin.pesanan.print', ['status' => request('status')]) }}" class="btn btn-success mb-3">
    Cetak Laporan
</a>

        <form action="{{ route('admin.pesanan.laporan') }}" method="GET" class="row g-2 mb-4">
    <div class="col-md-2">
        <label class="form-label">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label">Status Pesanan</label>
        <select name="status" class="form-control">
            <option value="">Semua</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>pending</option>
            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>diproses</option>
            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>dikirim</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>selesai</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Status Pembayaran</label>
        <select name="payment_status" class="form-control">
            <option value="">Semua</option>
            <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>belum_bayar</option>
            <option value="menunggu_pembayaran" {{ request('payment_status') == 'menunggu_pembayaran' ? 'selected' : '' }}>menunggu_pembayaran</option>
            <option value="sudah_bayar" {{ request('payment_status') == 'sudah_bayar' ? 'selected' : '' }}>sudah_bayar</option>
            <option value="gagal" {{ request('payment_status') == 'gagal' ? 'selected' : '' }}>gagal</option>
            <option value="challenge" {{ request('payment_status') == 'challenge' ? 'selected' : '' }}>challenge</option>
        </select>
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-dark me-2">Filter</button>
        <a href="{{ route('admin.pesanan.laporan') }}" class="btn btn-outline-secondary me-2">Reset</a>

        <a href="{{ route('admin.pesanan.print', [
            'tanggal_awal' => request('tanggal_awal'),
            'tanggal_akhir' => request('tanggal_akhir'),
            'status' => request('status'),
            'payment_status' => request('payment_status')
        ]) }}" class="btn btn-success">
            Cetak
        </a>
    </div>
</form>
        <form action="{{ route('admin.pesanan.laporan') }}" method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="status" class="form-control">
            <option value="">-- Semua Status --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>pending</option>
            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>diproses</option>
            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>dikirim</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>selesai</option>
        </select>
    </div>

    <div class="col-md-auto">
        <button type="submit" class="btn btn-dark">Filter</button>
        <a href="{{ route('admin.pesanan.laporan') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
       <div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-body">
                <h5>Total Pesanan</h5>
                <h3>{{ $totalPesanan }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-body">
                <h5>Total Pendapatan (Sudah Bayar)</h5>
                <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

        <table class="table table-bordered table-striped">
            <thead>
    <tr>
        <th>No</th>
        <th>Customer</th>
        <th>Barang</th>
        <th>Jumlah</th>
        <th>Total Harga</th>
        <th>Status Pesanan</th>
        <th>Status Bayar</th>
        <th>Tanggal</th>
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
                    <span class="badge bg-secondary">Pending</span>
                @elseif($item->status == 'diproses')
                    <span class="badge bg-warning text-dark">Diproses</span>
                @elseif($item->status == 'dikirim')
                    <span class="badge bg-primary">Dikirim</span>
                @elseif($item->status == 'selesai')
                    <span class="badge bg-success">Selesai</span>
                @else
                    <span class="badge bg-dark">{{ $item->status }}</span>
                @endif
            </td>

            <td>
                @if($item->payment_status == 'belum_bayar')
                    <span class="badge bg-danger">Belum Bayar</span>
                @elseif($item->payment_status == 'menunggu_pembayaran')
                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                @elseif($item->payment_status == 'sudah_bayar')
                    <span class="badge bg-success">Sudah Bayar</span>
                @elseif($item->payment_status == 'gagal')
                    <span class="badge bg-danger">Gagal</span>
                @elseif($item->payment_status == 'challenge')
                    <span class="badge bg-info text-dark">Challenge</span>
                @else
                    <span class="badge bg-secondary">{{ $item->payment_status }}</span>
                @endif
            </td>

            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Belum ada data pesanan</td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>
@endsection