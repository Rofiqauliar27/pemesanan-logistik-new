@php
    $profilPerusahaan = \App\Models\ProfilPerusahaan::first();
@endphp

<div class="admin-sidebar">
    <div class="admin-brand">
        <div class="admin-brand-logo">
            @if($profilPerusahaan && $profilPerusahaan->logo)
                <img src="{{ asset('storage/' . $profilPerusahaan->logo) }}" alt="Logo Perusahaan">
            @else
                <img src="{{ asset('images/logo-bst.jpeg') }}" alt="Logo Perusahaan">
            @endif
        </div>

        <div class="admin-brand-info">
            <h5>{{ $profilPerusahaan->nama_perusahaan ?? 'CV Bintang Saida Teknik' }}</h5>
            <small>{{ $profilPerusahaan->bidang_usaha ?? 'Sistem Pemesanan Logistik' }}</small>
        </div>
    </div>

<div class="admin-menu nav flex-column">
    <div class="admin-menu-label">Menu Utama</div>

    <a href="{{ url('/admin/dashboard') }}" 
       class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <span class="menu-icon">D</span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('barang.index') }}" 
       class="nav-link {{ request()->is('admin/barang*') ? 'active' : '' }}">
        <span class="menu-icon">B</span>
        <span>Kelola Barang</span>
    </a>

    <a href="{{ route('admin.pesanan.index') }}" 
       class="nav-link {{ request()->routeIs('admin.pesanan.index') || request()->routeIs('admin.pesanan.show') || request()->routeIs('admin.pesanan.editStatus') ? 'active' : '' }}">
        <span class="menu-icon">P</span>
        <span>Kelola Pesanan</span>
    </a>

    <a href="{{ route('admin.customer.index') }}" 
       class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }}">
        <span class="menu-icon">C</span>
        <span>Data Customer</span>
    </a>

    <a href="{{ route('admin.pesanan.laporan') }}" 
       class="nav-link {{ request()->routeIs('admin.pesanan.laporan') ? 'active' : '' }}">
        <span class="menu-icon">L</span>
        <span>Laporan</span>
    </a>

    <div class="admin-menu-label mt-3">Pengaturan</div>

    <a href="{{ route('admin.profil.edit') }}" 
       class="nav-link {{ request()->is('admin/profil-perusahaan*') ? 'active' : '' }}">
        <span class="menu-icon">PR</span>
        <span>Profil Perusahaan</span>
    </a>

    <a href="{{ route('admin.home-banners.index') }}" 
       class="nav-link {{ request()->is('admin/home-banners*') ? 'active' : '' }}">
        <span class="menu-icon">BN</span>
        <span>Banner Beranda</span>
    </a>

    <a href="{{ route('admin.kategori-beranda.index') }}" 
       class="nav-link {{ request()->is('admin/kategori-beranda*') ? 'active' : '' }}">
        <span class="menu-icon">KT</span>
        <span>Kategori Beranda</span>
    </a>
</div>

    <div class="help-box">
        <span class="help-label">Informasi</span>
        <h6>Panel Admin</h6>
        <p>
            Kelola barang, pesanan, customer, banner, kategori, dan laporan sistem dalam satu tempat.
        </p>
    </div>
</div>