@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
@php
    $pesananItems = $pesananItems ?? collect([$pesanan]);
    $totalGrup = $totalGrup ?? $pesananItems->sum('total_harga');
    $totalJumlah = $totalJumlah ?? $pesananItems->sum('jumlah');

    $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id ?? '-';

    $customer = $pesanan->user;

    $alamatLengkap = collect([
        $customer->alamat_lengkap ?? null,
        $customer->kelurahan ?? null,
        $customer->kecamatan ?? null,
        $customer->kabupaten ?? null,
        $customer->provinsi ?? null,
        $customer->kode_pos ?? null,
    ])->filter()->implode(', ');

    $tanggalPesanan = $pesanan->created_at
        ? $pesanan->created_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesanan->paid_at
        ? $pesanan->paid_at->format('d-m-Y H:i')
        : '-';

    $batasBayar = $pesanan->expired_at
        ? $pesanan->expired_at->format('d-m-Y H:i')
        : '-';

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

    $statusPesanan = $pesanan->status ?? '-';
    $labelStatusPesanan = ucfirst(str_replace('_', ' ', $statusPesanan));
@endphp

<div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-1">Detail Pesanan</h2>
            <p class="text-muted mb-0">
                Informasi lengkap pesanan customer berdasarkan grup checkout.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <tr>
                <th width="30%">Order ID</th>
                <td>{{ $groupOrderId }}</td>
            </tr>

            <tr>
                <th>Nama Customer</th>
                <td>{{ $customer->name ?? '-' }}</td>
            </tr>

            <tr>
                <th>Email Customer</th>
                <td>{{ $customer->email ?? '-' }}</td>
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
                <th>Status Pesanan</th>
                <td>
                    @if($statusPesanan == 'pending')
                        <span class="badge bg-secondary">Pending</span>
                    @elseif($statusPesanan == 'diproses')
                        <span class="badge bg-warning text-dark">Diproses</span>
                    @elseif($statusPesanan == 'dikirim')
                        <span class="badge bg-primary">Dikirim</span>
                    @elseif($statusPesanan == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif($statusPesanan == 'dibatalkan')
                        <span class="badge bg-danger">Dibatalkan</span>
                    @else
                        <span class="badge bg-dark">{{ $labelStatusPesanan }}</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Status Pembayaran</th>
                <td>
                    @if(in_array($statusBayar, ['sudah_bayar', 'settlement', 'paid', 'capture']))
                        <span class="badge bg-success">{{ $labelStatusBayar }}</span>
                    @elseif(in_array($statusBayar, ['pending', 'challenge']))
                        <span class="badge bg-warning text-dark">{{ $labelStatusBayar }}</span>
                    @elseif(in_array($statusBayar, ['failed', 'gagal', 'expire']))
                        <span class="badge bg-danger">{{ $labelStatusBayar }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $labelStatusBayar }}</span>
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
                <th>Tanggal Pesanan</th>
                <td>{{ $tanggalPesanan }}</td>
            </tr>

            <tr>
                <th>Batas Pembayaran</th>
                <td>{{ $batasBayar }}</td>
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

    <h4 class="mb-3">Alamat Pengiriman</h4>

    <div class="border rounded p-3 mb-4 bg-light">
        <h6 class="mb-1 fw-bold">
            {{ $customer->name ?? '-' }}
        </h6>

        <p class="mb-2 text-muted">
            {{ $customer->email ?? '-' }}

            @if(!empty($customer->telepon))
                | {{ $customer->telepon }}
            @endif
        </p>

        <p class="mb-2">
            {{ $alamatLengkap ?: 'Alamat belum dilengkapi.' }}
        </p>

        @if(!empty($customer->google_maps_link))
            <a href="{{ $customer->google_maps_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                Buka Lokasi Google Maps
            </a>
        @endif
    </div>

    <h4 class="mb-3">Daftar Barang Pesanan</h4>

    <div class="table-responsive">
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

    <div class="mt-3">
        @if(in_array($statusBayar, ['sudah_bayar', 'settlement', 'paid', 'capture']))
            <a href="{{ route('admin.pesanan.editStatus', $pesanan->id) }}" class="btn btn-warning">
                Ubah Status
            </a>
        @endif

        <a href="{{ route('admin.pesanan.invoice', $pesanan->id) }}" class="btn btn-success">
            Cetak Invoice
        </a>

        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
            Kembali ke Data Pesanan
        </a>
    </div>
</div>
@endsection