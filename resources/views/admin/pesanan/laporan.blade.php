@extends('layouts.admin')

@section('title', 'Laporan Pesanan')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <h2>Laporan Pesanan</h2>
            <p>Filter, pantau, dan cetak laporan pesanan berdasarkan grup checkout.</p>
        </div>

        <div class="admin-page-actions"></div>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.pesanan.laporan') }}" method="GET" class="admin-report-filter-form">
            <div class="admin-search-field">
                <label>Tanggal Awal</label>
                <input 
                    type="date" 
                    name="tanggal_awal" 
                    value="{{ request('tanggal_awal') }}"
                >
            </div>

            <div class="admin-search-field">
                <label>Tanggal Akhir</label>
                <input 
                    type="date" 
                    name="tanggal_akhir" 
                    value="{{ request('tanggal_akhir') }}"
                >
            </div>

            <div class="admin-search-field">
                <label>Status Pesanan</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="admin-search-field">
                <label>Status Pembayaran</label>
                <select name="payment_status">
                    <option value="">Semua Pembayaran</option>
                    <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="challenge" {{ request('payment_status') == 'challenge' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="sudah_bayar" {{ request('payment_status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="expire" {{ request('payment_status') == 'expire' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div class="admin-filter-actions">
                <button type="submit" class="btn-admin-primary">
                    Filter
                </button>

                <a href="{{ route('admin.pesanan.laporan') }}" class="btn-admin-secondary">
                    Reset
                </a>

                <a href="{{ route('admin.pesanan.print', [
                    'tanggal_awal' => request('tanggal_awal'),
                    'tanggal_akhir' => request('tanggal_akhir'),
                    'status' => request('status'),
                    'payment_status' => request('payment_status')
                ]) }}" class="btn-admin-success">
                    Cetak
                </a>
            </div>
        </form>
    </div>

    <div class="admin-report-summary-grid">
        <div class="admin-report-summary-card">
            <span>Total Pesanan</span>
            <strong>{{ $totalPesanan }}</strong>
        </div>

        <div class="admin-report-summary-card green">
            <span>Total Pendapatan Sudah Bayar</span>
            <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Daftar Laporan Pesanan</h4>
                <p>Total data: {{ $pesanans->count() }} grup pesanan</p>
            </div>
        </div>

        <div class="table-responsive">
    <table class="table admin-table align-middle">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Order ID</th>
                <th>Tanggal Pesanan</th>
                <th>Customer</th>
                <th>Barang</th>
                <th>Total Bayar</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pesanans as $item)
                @php
                    $items = $item->items ?? collect([$item]);

                    $jumlahJenisBarang = $item->total_barang ?? $items->count();
                    $totalJumlah = $item->total_jumlah ?? $items->sum('jumlah');
                    $totalGrup = $item->total_grup ?? $items->sum('total_harga');

                    $statusPesanan = $item->status ?? '-';
                    $statusBayar = $item->payment_status ?? '-';

                    $tanggalPesanan = $item->created_at
                        ? $item->created_at->format('d-m-Y H:i')
                        : '-';

                    $sudahLunas = in_array($statusBayar, [
                        'sudah_bayar',
                        'settlement',
                        'paid',
                        'capture',
                    ]);

                    $belumLunas = in_array($statusBayar, [
                        'belum_bayar',
                        'pending',
                        'challenge',
                        'failed',
                        'gagal',
                        'expire',
                    ]);

                    if ($belumLunas) {
                        $statusTampil = [
                            'belum_bayar' => 'Belum Bayar',
                            'pending' => 'Menunggu Pembayaran',
                            'challenge' => 'Menunggu Konfirmasi',
                            'failed' => 'Gagal',
                            'gagal' => 'Gagal',
                            'expire' => 'Expired',
                        ][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));
                    } else {
                        $statusTampil = ucfirst(str_replace('_', ' ', $statusPesanan));
                    }
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <div class="admin-product-name">
                            {{ $item->group_order_id ?? $item->order_id ?? '-' }}
                        </div>
                    </td>

                    <td>
                        {{ $tanggalPesanan }}
                    </td>

                    <td>
                        <div class="admin-product-name">
                            {{ $item->user->name ?? '-' }}
                        </div>
                    </td>

                    <td>
                        <div class="admin-desc-text">
                            {{ $jumlahJenisBarang }} Jenis Barang / {{ $totalJumlah }} Item
                        </div>
                    </td>

                    <td>
                        <strong>
                            Rp {{ number_format($totalGrup, 0, ',', '.') }}
                        </strong>
                    </td>

                    <td>
                        @if($sudahLunas && $statusPesanan == 'pending')
                            <span class="admin-status-badge payment-paid">
                                Sudah Bayar
                            </span>
                        @elseif($sudahLunas && $statusPesanan == 'diproses')
                            <span class="admin-status-badge status-process">
                                Diproses
                            </span>
                        @elseif($sudahLunas && $statusPesanan == 'dikirim')
                            <span class="admin-status-badge status-shipping">
                                Dikirim
                            </span>
                        @elseif($sudahLunas && $statusPesanan == 'selesai')
                            <span class="admin-status-badge status-success">
                                Selesai
                            </span>
                        @elseif($statusPesanan == 'dibatalkan')
                            <span class="admin-status-badge payment-failed">
                                Dibatalkan
                            </span>
                        @elseif(in_array($statusBayar, ['pending', 'challenge']))
                            <span class="admin-status-badge payment-waiting">
                                {{ $statusTampil }}
                            </span>
                        @elseif(in_array($statusBayar, ['failed', 'gagal', 'expire']))
                            <span class="admin-status-badge payment-failed">
                                {{ $statusTampil }}
                            </span>
                        @else
                            <span class="admin-status-badge payment-unpaid">
                                {{ $statusTampil }}
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="admin-empty-state">
                            Belum ada data pesanan.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>
@endsection