@extends('layouts.customer')

@section('title', 'Profil Customer')

@section('content')
@php
    $pesananGroupsUntukStatistik = $semuaPesanan->groupBy(function ($item) {
        return $item->group_order_id ?? $item->order_id ?? $item->id;
    });

    $pesananGroups = $pesanans->groupBy(function ($item) {
        return $item->group_order_id ?? $item->order_id ?? $item->id;
    });

    $pesananBelumBayarGroups = $pesananBelumBayar->groupBy(function ($item) {
        return $item->group_order_id ?? $item->order_id ?? $item->id;
    });

    $pesananSelesaiGroups = $semuaPesanan
        ->where('status', 'selesai')
        ->groupBy(function ($item) {
            return $item->group_order_id ?? $item->order_id ?? $item->id;
        });
@endphp

<div class="customer-account-hero">
    <div>
        <h1>Akun Saya</h1>
        <p>
            Kelola informasi akun, riwayat pesanan, dan status pembayaran Anda dengan mudah.
        </p>
    </div>
</div>

<div class="customer-stats-grid">
    <div class="customer-stat-card">
        <span>Total Pesanan</span>
        <strong>{{ $pesananGroupsUntukStatistik->count() }}</strong>
    </div>

    <div class="customer-stat-card">
        <span>Perlu Dibayar</span>
        <strong>{{ $pesananBelumBayarGroups->count() }}</strong>
    </div>

    <div class="customer-stat-card">
        <span>Pesanan Selesai</span>
        <strong>{{ $pesananSelesaiGroups->count() }}</strong>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="profile-sidebar">
            <div class="profile-user-box">
                <div class="customer-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h5>{{ $user->name }}</h5>
                <p>{{ $user->email }}</p>
            </div>

            <div class="nav flex-column profile-side-menu">
                <a href="{{ route('customer.profile', ['tab' => 'profil']) }}"
                   class="nav-link {{ $tab == 'profil' ? 'active' : '' }}">
                    Data Pribadi
                </a>

                <a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}"
                   class="nav-link {{ $tab == 'pesanan' ? 'active' : '' }}">
                    Pesanan Saya
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">

        {{-- DATA PRIBADI --}}
        @if($tab == 'profil')
            <div class="profile-content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1">Data Pribadi</h4>
                        <p class="text-muted mb-0">
                            Informasi akun dan alamat tujuan customer.
                        </p>
                    </div>

                    <a href="{{ route('customer.profile.edit') }}" class="customer-btn-primary">
                        Edit Data Customer
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Nama Lengkap</strong>
                            <p>{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Email</strong>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Nomor Telepon / WhatsApp</strong>
                            <p>{{ $user->telepon ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Role</strong>
                            <p>{{ $user->role }}</p>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="profile-mini-card">
                            <strong>Alamat Lengkap</strong>
                            <p>{{ $user->alamat_lengkap ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Provinsi</strong>
                            <p>{{ $user->provinsi ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Kabupaten / Kota</strong>
                            <p>{{ $user->kabupaten ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Kecamatan</strong>
                            <p>{{ $user->kecamatan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Kelurahan / Desa</strong>
                            <p>{{ $user->kelurahan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Kode Pos</strong>
                            <p>{{ $user->kode_pos ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="profile-mini-card">
                            <strong>Tanggal Daftar</strong>
                            <p>{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="profile-mini-card">
                            <strong>Google Maps</strong>

                            @if($user->google_maps_link)
                                <p class="mb-2">Lokasi tujuan sudah ditambahkan.</p>

                                <a href="{{ $user->google_maps_link }}" target="_blank" class="customer-map-btn">
                                    Buka Lokasi di Google Maps
                                </a>
                            @else
                                <p>Belum ada link Google Maps.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- PESANAN SAYA --}}
        @if($tab == 'pesanan')
            <div class="profile-content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1">Pesanan Saya</h4>
                        <p class="text-muted mb-0">
                            Daftar semua pesanan dan status pembayaran Anda.
                        </p>
                    </div>

                    <span class="profile-tab-badge blue">
                        {{ $pesananGroups->count() }} Pesanan
                    </span>
                </div>

                <form action="{{ route('customer.profile') }}" method="GET" class="mb-3">
                    <input type="hidden" name="tab" value="pesanan">

                    <div class="row align-items-end">
                        <div class="col-md-5 col-lg-4">
                            <label for="filter" class="form-label fw-semibold">Filter Pesanan</label>
                            <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                                <option value="semua" {{ ($filter ?? 'semua') == 'semua' ? 'selected' : '' }}>
                                    Semua
                                </option>

                                <option value="belum_bayar" {{ ($filter ?? 'semua') == 'belum_bayar' ? 'selected' : '' }}>
                                    Belum Dibayar
                                </option>

                                <option value="menunggu" {{ ($filter ?? 'semua') == 'menunggu' ? 'selected' : '' }}>
                                    Menunggu Konfirmasi
                                </option>

                                <option value="diproses" {{ ($filter ?? 'semua') == 'diproses' ? 'selected' : '' }}>
                                    Diproses
                                </option>

                                <option value="selesai" {{ ($filter ?? 'semua') == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>

                                <option value="gagal" {{ ($filter ?? 'semua') == 'gagal' ? 'selected' : '' }}>
                                    Gagal / Expire
                                </option>

                                <option value="dibatalkan" {{ ($filter ?? 'semua') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle profile-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Order ID</th>
                                <th>Tanggal Pesanan</th>
                                <th>Barang</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($pesananGroups as $groupOrderId => $items)
                                @php
                                    $itemUtama = $items->first();
                                    $totalJumlahBarang = $items->sum('jumlah');
                                    $totalBayar = $items->sum('total_harga');

                                    $statusPesanan = $itemUtama->status ?? '-';
                                    $statusBayar = $itemUtama->payment_status ?? '-';

                                    $statusBelumLunas = in_array($statusBayar, [
                                        'belum_bayar',
                                        'pending',
                                        'failed',
                                        'expire',
                                        'challenge',
                                    ]);

                                    $tanggalPesanan = $itemUtama->created_at
                                        ? $itemUtama->created_at->format('d-m-Y H:i')
                                        : '-';

                                    if (in_array($statusBayar, ['belum_bayar', 'pending', 'challenge', 'failed', 'expire'])) {
                                        $statusTampil = [
                                            'belum_bayar' => 'Belum Dibayar',
                                            'pending' => 'Menunggu Pembayaran',
                                            'challenge' => 'Menunggu Konfirmasi',
                                            'failed' => 'Gagal',
                                            'expire' => 'Expired',
                                        ][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));

                                        $statusClass = $statusBayar;
                                    } else {
                                        $statusTampil = ucfirst(str_replace('_', ' ', $statusPesanan));
                                        $statusClass = $statusPesanan;
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $itemUtama->group_order_id ?? $itemUtama->order_id ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $tanggalPesanan }}
                                    </td>

                                    <td>
                                        {{ $totalJumlahBarang }} Item
                                    </td>

                                    <td>
                                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <span class="status-badge status-{{ $statusClass }}">
                                            {{ $statusTampil }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($statusBelumLunas)
                                            <a href="{{ route('customer.pesanan.showBayar', $itemUtama->id) }}"
                                               class="btn btn-sm btn-primary">
                                                Lihat / Bayar
                                            </a>
                                        @else
                                            <a href="{{ route('customer.pesanan.showBayar', $itemUtama->id) }}"
                                               class="btn btn-sm btn-info">
                                                Lihat Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Tidak ada pesanan pada filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection