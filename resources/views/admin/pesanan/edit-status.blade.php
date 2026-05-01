@extends('layouts.admin')

@section('title', 'Edit Status Pesanan')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <h2 class="mb-3">Edit Status Pesanan</h2>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Customer</th>
                    <td>{{ $pesanan->user->name }}</td>
                </tr>
                <tr>
                    <th>Order ID</th>
                    <td>{{ $pesanan->order_id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Barang</th>
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
                    <td>{{ $pesanan->status }}</td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td>{{ $pesanan->payment_status }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ $pesanan->payment_type ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td>{{ $pesanan->catatan ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Pilih Status Baru</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>pending</option>
                    <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>diproses</option>
                    <option value="dikirim" {{ $pesanan->status == 'dikirim' ? 'selected' : '' }}>dikirim</option>
                    <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>selesai</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Status</button>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection