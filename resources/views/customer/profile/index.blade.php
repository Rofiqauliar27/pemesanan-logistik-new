@extends('layouts.public')

@section('title', 'Akun Saya')

@section('content')
@php
    $pesananGroupsUntukStatistik = ($semuaPesanan ?? $pesanans)->groupBy(function ($item) {
        return $item->group_order_id ?? $item->order_id ?? $item->id;
    });

    $pesananGroups = $pesanans->groupBy(function ($item) {
        return $item->group_order_id ?? $item->order_id ?? $item->id;
    });

    $pesananBelumBayarGroups = $pesananBelumBayar->groupBy(function ($item) {
        return $item->group_order_id ?? $item->order_id ?? $item->id;
    });

    $pesananSelesaiGroups = ($semuaPesanan ?? $pesanans)
        ->where('status', 'selesai')
        ->groupBy(function ($item) {
            return $item->group_order_id ?? $item->order_id ?? $item->id;
        });
@endphp

<div class="customer-account-page">

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

    <div class="customer-account-layout">

        <aside class="customer-account-sidebar">
            <div class="profile-user-box">
                <div class="customer-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h5>{{ $user->name }}</h5>
                <p>{{ $user->email }}</p>
            </div>

            <div class="profile-side-menu">
                <a href="{{ route('customer.profile', ['tab' => 'profil']) }}"
                   class="profile-menu-link {{ $tab == 'profil' ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    <span>Data Pribadi</span>
                </a>

                <a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}"
                   class="profile-menu-link {{ $tab == 'pesanan' ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Pesanan Saya</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="profile-logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="customer-account-main">

            @if($tab == 'profil')
                <div class="profile-content-box">
                    <div class="customer-section-header">
                        <div>
                            <h4>Data Pribadi</h4>
                            <p>
                                Informasi akun dan alamat tujuan customer.
                            </p>
                        </div>

                        <a href="{{ route('customer.profile.edit') }}" class="customer-btn-primary">
                            Edit Data Customer
                        </a>
                    </div>

                    <div class="customer-profile-grid">
                        <div class="profile-mini-card">
                            <strong>Nama Lengkap</strong>
                            <p>{{ $user->name }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Email</strong>
                            <p>{{ $user->email }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Nomor Telepon / WhatsApp</strong>
                            <p>{{ $user->telepon ?? '-' }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Role</strong>
                            <p>{{ $user->role }}</p>
                        </div>

                        <div class="profile-mini-card profile-mini-card-full">
                            <strong>Alamat Lengkap</strong>
                            <p>{{ $user->alamat_lengkap ?? '-' }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Provinsi</strong>
                            <p>{{ $user->provinsi ?? '-' }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Kabupaten / Kota</strong>
                            <p>{{ $user->kabupaten ?? '-' }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Kecamatan</strong>
                            <p>{{ $user->kecamatan ?? '-' }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Kelurahan / Desa</strong>
                            <p>{{ $user->kelurahan ?? '-' }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Kode Pos</strong>
                            <p>{{ $user->kode_pos ?? '-' }}</p>
                        </div>

                        <div class="profile-mini-card">
                            <strong>Tanggal Daftar</strong>
                            <p>{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-' }}</p>
                        </div>

                        <div class="profile-mini-card profile-mini-card-full">
                            <strong>Google Maps</strong>

                            @if($user->google_maps_link)
                                <p class="mb-2">
                                    Lokasi tujuan sudah ditambahkan.
                                </p>

                                <a href="{{ $user->google_maps_link }}" target="_blank" class="customer-map-btn">
                                    Buka Lokasi di Google Maps
                                </a>
                            @else
                                <p>Belum ada link Google Maps.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($tab == 'pesanan')
                <div class="profile-content-box">
                    <div class="customer-section-header">
                        <div>
                            <h4>Pesanan Saya</h4>
                            <p>
                                Daftar semua pesanan dan status pembayaran Anda.
                            </p>
                        </div>

                        <span class="profile-tab-badge">
                            {{ $pesananGroups->count() }} Pesanan
                        </span>
                    </div>

                    <form action="{{ route('customer.profile') }}" method="GET" class="customer-filter-box">
                        <input type="hidden" name="tab" value="pesanan">

                        <label>Filter Pesanan</label>

                        <select name="filter" onchange="this.form.submit()">
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

                            <option value="dikirim" {{ ($filter ?? 'semua') == 'dikirim' ? 'selected' : '' }}>
                                Dikirim
                            </option>

                            <option value="selesai" {{ ($filter ?? 'semua') == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>

                            <option value="gagal" {{ ($filter ?? 'semua') == 'gagal' ? 'selected' : '' }}>
                                Gagal / Expired
                            </option>

                            <option value="dibatalkan" {{ ($filter ?? 'semua') == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>
                        </select>
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

                                        $statusPesanan = $itemUtama->status ?? 'pending';
                                        $statusBayar = $itemUtama->payment_status ?? 'belum_bayar';

                                        $statusBelumLunas = in_array($statusBayar, [
                                            'belum_bayar',
                                            'pending',
                                            'failed',
                                            'gagal',
                                            'expire',
                                            'challenge',
                                        ]);

                                        $tanggalPesanan = $itemUtama->created_at
                                            ? $itemUtama->created_at->format('d-m-Y H:i')
                                            : '-';

                                        if (in_array($statusBayar, ['belum_bayar', 'pending', 'challenge', 'failed', 'gagal', 'expire'])) {
                                            $statusTampil = [
                                                'belum_bayar' => 'Belum Dibayar',
                                                'pending' => 'Menunggu Pembayaran',
                                                'challenge' => 'Menunggu Konfirmasi',
                                                'failed' => 'Gagal',
                                                'gagal' => 'Gagal',
                                                'expire' => 'Expired',
                                            ][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));

                                            $statusClass = $statusBayar;
                                        } else {
                                            $statusTampil = [
                                                'pending' => 'Pending',
                                                'diproses' => 'Diproses',
                                                'dikirim' => 'Dikirim',
                                                'selesai' => 'Selesai',
                                                'dibatalkan' => 'Dibatalkan',
                                            ][$statusPesanan] ?? ucfirst(str_replace('_', ' ', $statusPesanan));

                                            $statusClass = $statusPesanan;
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>
                                                {{ $itemUtama->group_order_id ?? $itemUtama->order_id ?? '-' }}
                                            </strong>
                                        </td>

                                        <td>{{ $tanggalPesanan }}</td>

                                        <td>
                                            {{ $items->count() }} Jenis / {{ $totalJumlahBarang }} Item
                                        </td>

                                        <td>
                                            <strong>
                                                Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                            </strong>
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

            @if($tab == 'pembayaran')
                <div class="profile-content-box">
                    <div class="customer-section-header">
                        <div>
                            <h4>Pembayaran Saya</h4>
                            <p>
                                Pesanan yang belum dibayar, menunggu pembayaran, atau gagal.
                            </p>
                        </div>

                        <span class="profile-tab-badge yellow">
                            {{ $pesananBelumBayarGroups->count() }} Data
                        </span>
                    </div>

                    <div class="alert alert-info">
                        Bagian ini menampilkan pesanan yang belum dibayar, sedang menunggu pembayaran, gagal, atau perlu ditinjau kembali.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle profile-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pesanan</th>
                                    <th>Barang</th>
                                    <th>Total</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($pesananBelumBayarGroups as $groupOrderId => $items)
                                    @php
                                        $itemUtama = $items->first();
                                        $totalJumlahBarang = $items->sum('jumlah');
                                        $totalBayar = $items->sum('total_harga');
                                        $statusBayar = $itemUtama->payment_status ?? 'belum_bayar';

                                        $statusTampil = [
                                            'belum_bayar' => 'Belum Dibayar',
                                            'pending' => 'Menunggu Pembayaran',
                                            'challenge' => 'Menunggu Konfirmasi',
                                            'failed' => 'Gagal',
                                            'gagal' => 'Gagal',
                                            'expire' => 'Expired',
                                        ][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>
                                                {{ $itemUtama->group_order_id ?? $itemUtama->order_id ?? '-' }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $items->count() }} Jenis / {{ $totalJumlahBarang }} Item
                                        </td>

                                        <td>
                                            <strong>
                                                Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                            </strong>
                                        </td>

                                        <td>{{ $itemUtama->payment_type ?? '-' }}</td>

                                        <td>
                                            <span class="status-badge status-{{ $statusBayar }}">
                                                {{ $statusTampil }}
                                            </span>
                                        </td>

                                        <td>
                                            <a href="{{ route('customer.pesanan.showBayar', $itemUtama->id) }}"
                                               class="btn btn-sm btn-primary">
                                                Lihat / Bayar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            Tidak ada pembayaran aktif.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </main>
    </div>
</div>
@endsection