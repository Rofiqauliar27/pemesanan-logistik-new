@extends('layouts.admin')

@section('title', 'Data Pesanan')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Transaksi</span>
            <h2>Data Pesanan Customer</h2>
            <p>Kelola pesanan customer, status pesanan, pembayaran, dan catatan transaksi.</p>
        </div>

        <div class="admin-page-actions">
        
        </div>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="admin-filter-form admin-filter-form-wide">
            <div class="admin-search-field">
                <label>Cari Pesanan</label>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari customer atau barang..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="admin-search-field">
                <label>Status Pesanan</label>
                <select name="status">
                    <option value="">Semua Status Pesanan</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="admin-search-field">
                <label>Status Bayar</label>
                <select name="payment_status">
                    <option value="">Semua Status Bayar</option>
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

                <a href="{{ route('admin.pesanan.index') }}" class="btn-admin-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Daftar Pesanan</h4>
                <p>Total data: {{ $pesanans->count() }} pesanan</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Customer</th>
                        <th>Barang</th>
                        <th width="90">Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th width="170">Aksi</th>
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
                                <div class="admin-desc-text">
                                    {{ $item->catatan ?: '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="admin-action-group admin-action-column">
                                    <a href="{{ route('admin.pesanan.show', $item->id) }}" class="btn-table-edit">
                                        Detail
                                    </a>

                                    @if($item->payment_status == 'sudah_bayar')
    <a href="{{ route('admin.pesanan.editStatus', $item->id) }}" class="btn-table-edit">
        Ubah Status
    </a>
                                    @elseif($item->payment_status == 'menunggu_pembayaran')
                                        <button class="btn-admin-disabled" disabled>
                                            Menunggu Bayar
                                        </button>
                                    @elseif($item->payment_status == 'gagal')
                                        <button class="btn-admin-disabled danger" disabled>
                                            Bayar Gagal
                                        </button>
                                    @else
                                        <button class="btn-admin-disabled" disabled>
                                            Belum Bayar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="admin-empty-state">
                                    Belum ada pesanan.
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