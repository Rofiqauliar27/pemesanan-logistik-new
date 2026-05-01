@extends('layouts.customer')

@section('title', 'Profil Customer')

@section('content')
    <div class="market-box">
        <h2 class="mb-1">Akun Customer</h2>
        <p class="text-muted mb-0">
            Kelola data akun, pesanan, dan pembayaran Anda dalam satu halaman.
        </p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="profile-summary-card">
                <h6>Total Pesanan</h6>
                <h3>{{ $pesanans->count() }}</h3>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="profile-summary-card">
                <h6>Perlu Dibayar</h6>
                <h3>{{ $pesananBelumBayar->count() }}</h3>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="profile-summary-card">
                <h6>Pesanan Selesai</h6>
                <h3>{{ $pesanans->where('status', 'selesai')->count() }}</h3>
            </div>
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

                    <a href="{{ route('customer.profile', ['tab' => 'pembayaran']) }}"
                       class="nav-link {{ $tab == 'pembayaran' ? 'active' : '' }}">
                        Pembayaran Saya
                    </a>

                    <a href="{{ route('customer.profile', ['tab' => 'keamanan']) }}"
                       class="nav-link {{ $tab == 'keamanan' ? 'active' : '' }}">
                        Keamanan
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
                    <h4>Data Pribadi</h4>

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
                                <strong>Role</strong>
                                <p>{{ $user->role }}</p>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="profile-mini-card">
                                <strong>Tanggal Daftar</strong>
                                <p>{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($tab == 'pesanan')
                <div class="profile-content-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Pesanan Saya</h4>
                        <span class="profile-tab-badge blue">{{ $pesanans->count() }} Pesanan</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle profile-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Barang</th>
                                    <th>Total</th>
                                    <th>Status Pesanan</th>
                                    <th>Status Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesanans as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->order_id ?? '-' }}</td>
                                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $item->status }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $item->payment_status }}">
                                                {{ $item->payment_status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada pesanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                                    <th>Order ID</th>
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
                                        <td>{{ $item->order_id ?? '-' }}</td>
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

            @if($tab == 'keamanan')
                <div class="profile-content-box">
                    <h4>Keamanan Akun</h4>

                    <div class="alert alert-info">
                        Saat ini fitur keamanan masih dasar. Nanti bisa ditambah ubah password, foto profil, dan pengamanan tambahan.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="profile-mini-card">
                                <strong>Nama Akun</strong>
                                <p>{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="profile-mini-card">
                                <strong>Email Akun</strong>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection