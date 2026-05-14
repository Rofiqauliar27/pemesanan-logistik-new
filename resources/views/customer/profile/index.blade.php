@extends('layouts.customer')

@section('title', 'Profil Customer')

@section('content')
   <div class="customer-account-hero">
    <div>

    <h1>Akun Saya</h1>
    <h2 style="color:red;">INI FILE YANG SAYA EDIT</h2>
        <p>
            Kelola informasi akun, riwayat pesanan, dan status pembayaran Anda dengan mudah.
        </p>
    </div>
</div>

<div class="customer-stats-grid">
    <div class="customer-stat-card">
        <span>Total Pesanan</span>
        <strong>{{ $pesanans->count() }}</strong>
    </div>

    <div class="customer-stat-card">
        <span>Perlu Dibayar</span>
        <strong>{{ $pesananBelumBayar->count() }}</strong>
    </div>

    <div class="customer-stat-card">
        <span>Pesanan Selesai</span>
        <strong>{{ $pesanans->where('status', 'selesai')->count() }}</strong>
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
                        <button type="submit" class="btn btn-danger w-100">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
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
    </div>
@endif

            @if($tab == 'pembayaran')
                <div class="profile-content-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Pembayaran Saya</h4>
                        <span class="profile-tab-badge yellow">{{ $pesananBelumBayar->count() }} Data</span>
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
                                @forelse($pesananBelumBayar as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->group_order_id ?? $item->order_id ?? '-' }}</td>
                                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->payment_type ?? '-' }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $item->payment_status }}">
                                                {{ $item->payment_status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.pesanan.showBayar', $item->id) }}" class="btn btn-sm btn-primary">
                                                Lihat / Bayar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada pembayaran aktif</td>
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