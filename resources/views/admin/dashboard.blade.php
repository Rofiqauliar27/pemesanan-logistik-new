@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="admin-dashboard-page">

    

    <div class="dashboard-stat-grid">
        <div class="dashboard-stat-card stat-blue">
            <div class="stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <span>Total Barang</span>
                <strong>{{ $totalBarang ?? 0 }}</strong>
                <small>Produk tersedia di sistem</small>
            </div>
        </div>

        <div class="dashboard-stat-card stat-green">
            <div class="stat-icon">
                <i class="bi bi-receipt"></i>
            </div>

            <div>
                <span>Total Pesanan</span>
                <strong>{{ $totalPesanan ?? 0 }}</strong>
                <small>Seluruh grup pesanan</small>
            </div>
        </div>

        <div class="dashboard-stat-card stat-orange">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>

            <div>
                <span>Total Customer</span>
                <strong>{{ $totalCustomer ?? 0 }}</strong>
                <small>Customer yang terdaftar</small>
            </div>
        </div>

        <div class="dashboard-stat-card stat-red">
            <div class="stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>
                <span>Pesanan Selesai</span>
                <strong>{{ $totalSelesai ?? 0 }}</strong>
                <small>Pesanan yang sudah selesai</small>
            </div>
        </div>
    </div>

    <div class="dashboard-mini-stat-grid">
        <div class="dashboard-mini-stat-card mini-pending">
            <span>Pending</span>
            <strong>{{ $totalPending ?? 0 }}</strong>
        </div>

        <div class="dashboard-mini-stat-card mini-paid">
            <span>Sudah Bayar</span>
            <strong>{{ $totalSudahBayar ?? 0 }}</strong>
        </div>

        <div class="dashboard-mini-stat-card mini-process">
            <span>Diproses</span>
            <strong>{{ $totalDiproses ?? 0 }}</strong>
        </div>

        <div class="dashboard-mini-stat-card mini-success">
            <span>Selesai</span>
            <strong>{{ $totalSelesai ?? 0 }}</strong>
        </div>
    </div>

    <div class="dashboard-section-card">
        <div class="dashboard-section-header">
            <div>
                <h4>Pesanan Terbaru</h4>
                <p>Ringkasan 5 pesanan terbaru yang masuk ke sistem.</p>
            </div>

            <a href="{{ route('admin.pesanan.index') }}" class="btn-admin-secondary">
                Lihat Semua
            </a>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Customer</th>
                        <th>Barang</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse(($pesananTerbaru ?? []) as $item)
                        @php
                            $statusPesanan = $item->status ?? '-';
                            $statusBayar = $item->payment_status ?? '-';

                            $sudahLunas = in_array($statusBayar, [
                                'sudah_bayar',
                                'settlement',
                                'paid',
                                'capture',
                            ]);

                            if ($sudahLunas && $statusPesanan === 'pending') {
                                $statusLabel = 'Sudah Bayar';
                                $statusClass = 'payment-paid';
                            } elseif ($statusPesanan === 'diproses') {
                                $statusLabel = 'Diproses';
                                $statusClass = 'status-process';
                            } elseif ($statusPesanan === 'dikirim') {
                                $statusLabel = 'Dikirim';
                                $statusClass = 'status-shipping';
                            } elseif ($statusPesanan === 'selesai') {
                                $statusLabel = 'Selesai';
                                $statusClass = 'status-success';
                            } elseif ($statusPesanan === 'dibatalkan') {
                                $statusLabel = 'Dibatalkan';
                                $statusClass = 'payment-failed';
                            } elseif (in_array($statusBayar, ['pending', 'challenge'])) {
                                $statusLabel = 'Menunggu Bayar';
                                $statusClass = 'payment-waiting';
                            } elseif (in_array($statusBayar, ['failed', 'gagal', 'expire'])) {
                                $statusLabel = $statusBayar === 'expire' ? 'Expired' : 'Gagal';
                                $statusClass = 'payment-failed';
                            } else {
                                $statusLabel = 'Belum Bayar';
                                $statusClass = 'payment-unpaid';
                            }
                        @endphp

                        <tr>
                            <td>
                                <div class="admin-product-name">
                                    {{ $item->group_order_id ?? $item->order_id ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="admin-product-name">
                                    {{ $item->user->name ?? '-' }}
                                </div>
                                <small class="text-muted">
                                    {{ $item->user->email ?? '-' }}
                                </small>
                            </td>

                            <td>
                                <div class="admin-desc-text">
                                    {{ $item->total_barang ?? 1 }} Jenis / {{ $item->total_jumlah ?? $item->jumlah }} Item
                                </div>
                            </td>

                            <td>
                                <strong>
                                    Rp {{ number_format($item->total_grup ?? $item->total_harga ?? 0, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                <span class="admin-status-badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.pesanan.show', $item->id) }}" class="btn-table-edit">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="admin-empty-state">
                                    Belum ada pesanan terbaru.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
@endsection