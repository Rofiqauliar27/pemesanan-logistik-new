@php
    $profilPerusahaan = \App\Models\ProfilPerusahaan::first();
@endphp

<div class="admin-sidebar-overlay"></div>

<aside class="admin-sidebar">
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

    <nav class="admin-menu nav flex-column">
        <div class="admin-menu-label">Menu Utama</div>

        <a href="{{ url('/admin/dashboard') }}"
           class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-speedometer2"></i>
            </span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('barang.index') }}"
           class="nav-link {{ request()->is('admin/barang*') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-box-seam"></i>
            </span>
            <span>Kelola Barang</span>
        </a>

        <a href="{{ route('admin.pesanan.index') }}"
           class="nav-link {{ request()->routeIs('admin.pesanan.index') || request()->routeIs('admin.pesanan.show') || request()->routeIs('admin.pesanan.editStatus') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-receipt"></i>
            </span>
            <span>Kelola Pesanan</span>
        </a>

        <a href="{{ route('admin.customer.index') }}"
           class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-people"></i>
            </span>
            <span>Data Customer</span>
        </a>

        <a href="{{ route('admin.kategori-beranda.index') }}"
           class="nav-link {{ request()->is('admin/kategori-beranda*') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-grid"></i>
            </span>
            <span>Kategori Beranda</span>
        </a>

        <a href="{{ route('admin.pesanan.laporan') }}"
           class="nav-link {{ request()->routeIs('admin.pesanan.laporan') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-bar-chart-line"></i>
            </span>
            <span>Laporan</span>
        </a>

        <div class="admin-menu-label mt-3">Pengaturan</div>

        <a href="{{ route('admin.profil.edit') }}"
           class="nav-link {{ request()->is('admin/profil-perusahaan*') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-building"></i>
            </span>
            <span>Profil Perusahaan</span>
        </a>

        <a href="{{ route('admin.home-banners.index') }}"
           class="nav-link {{ request()->is('admin/home-banners*') ? 'active' : '' }}">
            <span class="menu-icon">
                <i class="bi bi-image"></i>
            </span>
            <span>Banner Beranda</span>
        </a>
        
    </nav>
</aside>