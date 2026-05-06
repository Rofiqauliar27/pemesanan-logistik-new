@extends('layouts.admin')

@section('title', 'Edit Status Pesanan')

@section('content')
@php
    $pesananItems = $pesananItems ?? collect([$pesanan]);

    $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id ?? '-';
    $totalGrup = $pesananItems->sum('total_harga');
    $totalJumlah = $pesananItems->sum('jumlah');

    $statusBayar = $pesanan->payment_status ?? '-';

    $labelStatusBayar = [
        'belum_bayar' => 'Belum Bayar',
        'pending' => 'Menunggu Pembayaran',
        'challenge' => 'Menunggu Konfirmasi',
        'sudah_bayar' => 'Sudah Bayar',
        'settlement' => 'Sudah Bayar',
        'paid' => 'Sudah Bayar',
        'capture' => 'Sudah Bayar',
        'failed' => 'Gagal',
        'gagal' => 'Gagal',
        'expire' => 'Expired',
    ][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));

    $tanggalPesanan = $pesanan->created_at
        ? $pesanan->created_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesanan->paid_at
        ? $pesanan->paid_at->format('d-m-Y H:i')
        : '-';
@endphp

<div class="bg-white p-4 rounded shadow-sm">
    <h2 class="mb-3">Edit Status Pesanan</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
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
                <td>{{ $pesanan->user->name ?? '-' }}</td>
            </tr>

            <tr>
                <th>Email Customer</th>
                <td>{{ $pesanan->user->email ?? '-' }}</td>
            </tr>

            <tr>
                <th>Order ID</th>
                <td>{{ $groupOrderId }}</td>
            </tr>

            <tr>
                <th>Tanggal Pesanan</th>
                <td>{{ $tanggalPesanan }}</td>
            </tr>

            <tr>
                <th>Total Jenis Barang</th>
                <td>{{ $pesananItems->count() }} Barang</td>
            </tr>

            <tr>
                <th>Total Jumlah Item</th>
                <td>{{ $totalJumlah }} Item</td>
            </tr>

            <tr>
                <th>Total Pembayaran</th>
                <td>
                    <strong>
                        Rp {{ number_format($totalGrup, 0, ',', '.') }}
                    </strong>
                </td>
            </tr>

            <tr>
                <th>Status Pesanan Saat Ini</th>
                <td>{{ ucfirst(str_replace('_', ' ', $pesanan->status ?? '-')) }}</td>
            </tr>

            <tr>
                <th>Status Pembayaran</th>
                <td>{{ $labelStatusBayar }}</td>
            </tr>

            <tr>
                <th>Metode Pembayaran</th>
                <td>{{ $pesanan->payment_type ?? '-' }}</td>
            </tr>

            <tr>
                <th>Tanggal Pembayaran</th>
                <td>{{ $tanggalBayar }}</td>
            </tr>

            <tr>
                <th>Catatan</th>
                <td>{{ $pesanan->catatan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <h4 class="mb-3">Daftar Barang Pesanan</h4>

    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Nama Barang</th>
                    <th width="120">Jumlah</th>
                    <th width="180">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach($pesananItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->jumlah }} Item</td>
                        <td>
                            Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="2" class="text-end">Total</th>
                    <th>{{ $totalJumlah }} Item</th>
                    <th>
                        Rp {{ number_format($totalGrup, 0, ',', '.') }}
                    </th>
                </tr>
            </tbody>
        </table>
    </div>

    <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pilih Status Baru</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>
                    Diproses
                </option>

                <option value="dikirim" {{ $pesanan->status == 'dikirim' ? 'selected' : '' }}>
                    Dikirim
                </option>

                <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>

                <option value="dibatalkan" {{ $pesanan->status == 'dibatalkan' ? 'selected' : '' }}>
                    Dibatalkan
                </option>
            </select>
        </div>

        <div class="alert alert-info">
            Perubahan status akan diterapkan ke semua barang dalam satu grup pesanan ini.
        </div>

        <button type="submit" class="btn btn-primary">
            Update Status
        </button>

        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-secondary">
            Kembali ke Detail
        </a>

        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary">
            Kembali ke Data Pesanan
        </a>
    </form>
</div>
@endsection