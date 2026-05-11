@php
    $profilPerusahaan = \App\Models\ProfilPerusahaan::first();

    $cartCount = 0;
    if (auth()->check() && auth()->user()->role === 'customer') {
        $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->sum('jumlah');
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CV Bintang Saida Teknik')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/marketplace.css') }}">

    <style>
        .bst-cart-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .bst-cart-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>

<header class="bst-indo-header">
    <div class="bst-mainbar">
        <div class="bst-header-container bst-mainbar-inner">

            <a href="{{ url('/') }}" class="bst-brand">
                <div class="bst-brand-logo">
                    @if($profilPerusahaan && $profilPerusahaan->logo)
                        <img src="{{ asset('storage/' . $profilPerusahaan->logo) }}" alt="Logo Perusahaan">
                    @else
                        <img src="{{ asset('images/logo-bst.jpeg') }}" alt="Logo Perusahaan">
                    @endif
                </div>

                <div class="bst-brand-text">
                    <strong>{{ $profilPerusahaan->nama_perusahaan ?? 'CV Bintang Saida Teknik' }}</strong>
                    <small>{{ $profilPerusahaan->bidang_usaha ?? 'Sistem Pemesanan Logistik Perkapalan' }}</small>
                </div>
            </a>

            <form action="{{ route('public.produk') }}" method="GET" class="bst-search">
                <input
                    type="text"
                    name="search"
                    placeholder="Ketik Yang Anda Cari"
                    value="{{ request('search') }}"
                >

                <select name="tipe">
                    <option value="produk" {{ request('tipe') == 'produk' ? 'selected' : '' }}>Produk</option>
                    <option value="kategori" {{ request('tipe') == 'kategori' ? 'selected' : '' }}>Kategori</option>
                </select>

                <button type="submit" aria-label="Cari">
                    🔍
                </button>
            </form>

            <div class="bst-auth-area">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.keranjang.index') }}" class="bst-cart-btn">
                            <svg class="bst-cart-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M6.2 6.5H20L18.6 13.2C18.45 13.95 17.78 14.5 17 14.5H9.1C8.32 14.5 7.65 13.95 7.5 13.2L5.8 4.8C5.7 4.35 5.3 4 4.83 4H3.5"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                                <path d="M9.5 19.2C10.05 19.2 10.5 18.75 10.5 18.2C10.5 17.65 10.05 17.2 9.5 17.2C8.95 17.2 8.5 17.65 8.5 18.2C8.5 18.75 8.95 19.2 9.5 19.2Z"
                                      fill="currentColor"/>
                                <path d="M17 19.2C17.55 19.2 18 18.75 18 18.2C18 17.65 17.55 17.2 17 17.2C16.45 17.2 16 17.65 16 18.2C16 18.75 16.45 19.2 17 19.2Z"
                                      fill="currentColor"/>
                            </svg>

                            <span>Keranjang</span>

                            <span id="cart-badge" class="bst-cart-badge">
                                {{ $cartCount }}
                            </span>
                        </a>

                        <div class="dropdown">
                            <button class="bst-login-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item {{ request()->is('customer/profile') && request('tab', 'profil') == 'profil' ? 'active' : '' }}"
                                       href="{{ route('customer.profile') }}">
                                        Profil Saya
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item {{ request()->is('customer/profile') && request('tab') == 'pesanan' ? 'active' : '' }}"
                                       href="{{ route('customer.profile', ['tab' => 'pesanan']) }}">
                                        Pesanan Saya
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item {{ request()->is('customer/profile') && request('tab') == 'pembayaran' ? 'active' : '' }}"
                                       href="{{ route('customer.profile', ['tab' => 'pembayaran']) }}">
                                        Pembayaran
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="px-3">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="bst-login-btn">
                            Panel Admin
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bst-login-btn">
                        LOGIN
                    </a>

                    <a href="{{ route('register') }}" class="bst-register-btn">
                        DAFTAR
                    </a>
                @endauth
            </div>

        </div>
    </div>

    <nav class="bst-menu-row">
        <div class="bst-header-container bst-menu-inner">
            <div class="bst-menu-left">
                <a class="{{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                    Beranda
                </a>

                <div class="dropdown">
                    <a href="#" class="dropdown-toggle {{ request('kategori') ? 'active' : '' }}" data-bs-toggle="dropdown">
                        ☰ Kategori
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('public.produk') }}">
                                Semua Kategori
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        @php
                            $kategoriNavbar = \App\Models\Barang::select('kategori')
                                ->whereNotNull('kategori')
                                ->where('kategori', '!=', '')
                                ->distinct()
                                ->orderBy('kategori', 'asc')
                                ->pluck('kategori');
                        @endphp

                        @foreach($kategoriNavbar as $kategori)
                            <li>
                                <a class="dropdown-item {{ request('kategori') == $kategori ? 'active' : '' }}"
                                   href="{{ route('public.produk', ['kategori' => $kategori]) }}">
                                    {{ $kategori }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <a class="{{ request()->is('produk') || request()->is('produk/*') ? 'active' : '' }}" href="{{ route('public.produk') }}">
                    Produk
                </a>

                <a class="{{ request()->is('tentang-sistem') ? 'active' : '' }}" href="{{ route('tentang.sistem') }}">
                    Profil Perusahaan
                </a>
            </div>

            <div class="bst-menu-right">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}">
                            Admin
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}">
                        Jadi Customer
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</header>

<div class="market-page">
    <div class="container">
        @yield('content')
    </div>
</div>

<div class="market-footer">
    <div class="container text-center">
        © {{ date('Y') }}
        {{ $profilPerusahaan->nama_perusahaan ?? 'CV Bintang Saida Teknik' }}
        —
        {{ $profilPerusahaan->bidang_usaha ?? 'Sistem Pemesanan Logistik Perkapalan' }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>