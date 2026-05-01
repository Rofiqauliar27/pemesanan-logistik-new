@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">Detail Pesanan</h2>
                <p class="text-muted mb-0">Informasi lengkap pesanan customer.</p>
            </div>
            <div>
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Order ID</th>
                    <td>{{ $pesanan->order_id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Customer</th>
                    <td>{{ $pesanan->user->name }}</td>
                </tr>
                <tr>
                    <th>Email Customer</th>
                    <td>{{ $pesanan->user->email }}</td>
                </tr>
                <tr>
                    <th>Nama Barang</th>
                    <td>{{ $pesanan->barang->nama_barang }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ $pesanan->jumlah }}</td>
                </tr>
                <tr>
                    <th>Total Harga</th>
                    <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Status Pesanan</th>
                    <td>
                        @if($pesanan->status == 'pending')
                            <span class="badge bg-secondary">Pending</span>
                        @elseif($pesanan->status == 'diproses')
                            <span class="badge bg-warning text-dark">Diproses</span>
                        @elseif($pesanan->status == 'dikirim')
                            <span class="badge bg-primary">Dikirim</span>
                        @elseif($pesanan->status == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-dark">{{ $pesanan->status }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td>
                        @if($pesanan->payment_status == 'belum_bayar')
                            <span class="badge bg-danger">Belum Bayar</span>
                        @elseif($pesanan->payment_status == 'menunggu_pembayaran')
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                        @elseif($pesanan->payment_status == 'sudah_bayar')
                            <span class="badge bg-success">Sudah Bayar</span>
                        @elseif($pesanan->payment_status == 'gagal')
                            <span class="badge bg-danger">Gagal</span>
                        @elseif($pesanan->payment_status == 'challenge')
                            <span class="badge bg-info text-dark">Challenge</span>
                        @else
                            <span class="badge bg-secondary">{{ $pesanan->payment_status }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ $pesanan->payment_type ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Transaction Status</th>
                    <td>{{ $pesanan->transaction_status ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td>{{ $pesanan->catatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pesanan</th>
                    <td>{{ $pesanan->created_at ? $pesanan->created_at->format('d-m-Y H:i') : '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-3">
    <a href="{{ route('admin.pesanan.editStatus', $pesanan->id) }}" class="btn btn-warning">
        Ubah Status
    </a>

    <a href="{{ route('admin.pesanan.invoice', $pesanan->id) }}" class="btn btn-success">
        Cetak Invoice
    </a>

    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
        Kembali ke Data Pesanan
    </a>
</div>
@endsection