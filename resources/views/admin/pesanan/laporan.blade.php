@extends('layouts.admin')

@section('title', 'Laporan Pesanan')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Laporan</span>
            <h2>Laporan Pesanan</h2>
            <p>Filter, pantau, dan cetak laporan pesanan berdasarkan tanggal, status pesanan, dan pembayaran.</p>
        </div>

        <div class="admin-page-actions">
        </div>
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
                </select>
            </div>

            <div class="admin-search-field">
                <label>Status Pembayaran</label>
                <select name="payment_status">
                    <option value="">Semua Pembayaran</option>
                    <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="menunggu_pembayaran" {{ request('payment_status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="sudah_bayar" {{ request('payment_status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                    <option value="gagal" {{ request('payment_status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    <option value="challenge" {{ request('payment_status') == 'challenge' ? 'selected' : '' }}>Challenge</option>
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
                <p>Total data: {{ $pesanans->count() }} pesanan</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Customer</th>
                        <th>Barang</th>
                        <th width="90">Jumlah</th>
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

                            <td>
                                <div class="admin-product-name">
                                    {{ $item->user->name ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="admin-desc-text">
                                    {{ $item->barang->nama_barang ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="admin-stock-badge">
                                    {{ $item->jumlah }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                @if($item->status == 'pending')
                                    <span class="admin-status-badge status-pending">Pending</span>
                                @elseif($item->status == 'diproses')
                                    <span class="admin-status-badge status-process">Diproses</span>
                                @elseif($item->status == 'dikirim')
                                    <span class="admin-status-badge status-shipping">Dikirim</span>
                                @elseif($item->status == 'selesai')
                                    <span class="admin-status-badge status-success">Selesai</span>
                                @else
                                    <span class="admin-status-badge status-pending">{{ $item->status }}</span>
                                @endif
                            </td>

                            <td>
                                @if($item->payment_status == 'belum_bayar')
                                    <span class="admin-status-badge payment-unpaid">Belum Bayar</span>
                                @elseif($item->payment_status == 'menunggu_pembayaran')
                                    <span class="admin-status-badge payment-waiting">Menunggu Pembayaran</span>
                                @elseif($item->payment_status == 'sudah_bayar')
                                    <span class="admin-status-badge payment-paid">Sudah Bayar</span>
                                @elseif($item->payment_status == 'gagal')
                                    <span class="admin-status-badge payment-failed">Gagal</span>
                                @elseif($item->payment_status == 'challenge')
                                    <span class="admin-status-badge payment-challenge">Challenge</span>
                                @else
                                    <span class="admin-status-badge status-pending">{{ $item->payment_status }}</span>
                                @endif
                            </td>

                            <td>
                                {{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
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

</div>
@endsection