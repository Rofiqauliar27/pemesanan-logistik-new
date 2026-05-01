@php
    $profilPerusahaan = \App\Models\ProfilPerusahaan::first();
@endphp

<div class="admin-sidebar">
    <div class="admin-brand">
    @if($profilPerusahaan && $profilPerusahaan->logo)
        <img src="{{ asset('storage/' . $profilPerusahaan->logo) }}" alt="Logo Perusahaan">
    @else
        <img src="{{ asset('images/logo-bst.jpeg') }}" alt="Logo Perusahaan">
    @endif

    <div>
        <h5>{{ $profilPerusahaan->nama_perusahaan ?? 'CV Bintang Saida Teknik' }}</h5>
        <small>{{ $profilPerusahaan->bidang_usaha ?? 'Sistem Pemesanan Logistik' }}</small>
    </div>
</div>

    <div class="admin-menu nav flex-column">
        <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <span>🏠</span> Dashboard
        </a>

        <a href="{{ route('barang.index') }}" class="nav-link {{ request()->is('barang*') ? 'active' : '' }}">
            <span>📦</span> Kelola Barang
        </a>

        <a href="{{ route('admin.pesanan.index') }}" class="nav-link {{ request()->is('admin/pesanan*') ? 'active' : '' }}">
            <span>🧾</span> Kelola Pesanan
        </a>

        <a href="{{ route('admin.customer.index') }}" class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }}">
            <span>👥</span> Data Customer
        </a>

        <a href="{{ route('admin.pesanan.laporan') }}" class="nav-link {{ request()->is('admin/pesanan/laporan*') ? 'active' : '' }}">
            <span>📊</span> Laporan
        </a>

        <a href="{{ route('admin.profil.edit') }}" class="nav-link {{ request()->is('admin/profil-perusahaan') ? 'active' : '' }}">
    <span>🏢</span> Profil Perusahaan
</a>
        <a href="{{ route('admin.home-banners.index') }}" 
          class="nav-link {{ request()->is('admin/home-banners*') ? 'active' : '' }}">
               Banner Beranda
        </a>

        <a href="{{ route('admin.kategori-beranda.index') }}" 
   class="nav-link {{ request()->is('admin/kategori-beranda*') ? 'active' : '' }}">
    Kategori Beranda
</a>
    </div>

    <div class="help-box">
        <h6 class="fw-bold">Informasi</h6>
        <p class="text-muted small mb-3">
            Panel admin digunakan untuk mengelola barang, pesanan, customer, dan laporan sistem.
        </p>
    </div>
</div>