@extends('layouts.customer')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">Riwayat Pesanan Saya</h2>
                <p class="text-muted mb-0">Lihat status pesanan dan pembayaran Anda di sini.</p>
            </div>
            <div>
                <a href="{{ route('customer.barang.index') }}" class="btn btn-primary">Lihat Barang</a>
                <a href="{{ url('/customer/dashboard') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th>Metode</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold">{{ $item->order_id ?? '-' }}</span>
                            </td>
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
                                    <span class="badge bg-warning text-dark">Menunggu</span>
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

                            <td>
                                {{ $item->payment_type ?? '-' }}
                            </td>

                            <td>{{ $item->catatan ?? '-' }}</td>

                            <td>
                                @if($item->payment_status == 'sudah_bayar')
                                    <button class="btn btn-sm btn-secondary" disabled>Lunas</button>
                                @else
                                    <a href="{{ route('customer.pesanan.showBayar', $item->id) }}" class="btn btn-sm btn-success">
                                        Bayar
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada pesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection