<div class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-box">
            BST
        </div>
        <div>
            <h5>Logistik Perkapalan</h5>
            <small>Menu Customer</small>
        </div>
    </div>

    <div class="sidebar-menu nav flex-column">
        <a href="{{ url('/customer/dashboard') }}" class="nav-link {{ request()->is('customer/dashboard') ? 'active' : '' }}">
            <span>🏠</span> Dashboard
        </a>

        <a href="{{ route('customer.barang.index') }}" class="nav-link {{ request()->is('customer/barang*') ? 'active' : '' }}">
            <span>📦</span> Data Barang
        </a>

        <a href="{{ route('customer.pesanan.index') }}" class="nav-link {{ request()->is('customer/pesanan') ? 'active' : '' }}">
            <span>🧾</span> Riwayat Pesanan
        </a>

        {{-- MENU KATEGORI --}}
       <a class="nav-link" data-bs-toggle="collapse" href="#kategoriMenu" role="button" aria-expanded="false" aria-controls="kategoriMenu">
    <span>📂</span> Kategori
</a>

<div class="collapse {{ request('kategori') ? 'show' : '' }}" id="kategoriMenu">
    <div class="sidebar-submenu nav flex-column">
        <a href="{{ route('customer.barang.index') }}"
           class="nav-link {{ request('kategori') ? '' : 'active' }}">
            Semua Kategori
        </a>

        @if(isset($kategoris) && count($kategoris) > 0)
            @foreach($kategoris as $kategori)
                <a href="{{ route('customer.barang.index', ['kategori' => $kategori]) }}"
                   class="nav-link {{ request('kategori') == $kategori ? 'active' : '' }}">
                    {{ $kategori }}
                </a>
            @endforeach
        @endif
    </div>
</div>

        <a href="{{ route('customer.pesanan.index') }}" class="nav-link">
            <span>💳</span> Pembayaran
        </a>

        <a href="{{ route('tentang.sistem') }}" class="nav-link">
            <span>ℹ️</span> Tentang Sistem
        </a>
    </div>

    <div class="help-box">
        <h6 class="fw-bold">Butuh Bantuan?</h6>
        <p class="text-muted small mb-3">
            Jika ada kendala pemesanan, silakan hubungi admin.
        </p>
        <a href="#" class="btn btn-outline-primary w-100">Hubungi Kami</a>
    </div>
</div>