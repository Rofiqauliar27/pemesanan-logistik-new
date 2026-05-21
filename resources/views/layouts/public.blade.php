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
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/marketplace.css') }}">

    <style>
        .public-cart-button {
            width: 174px;
            height: 44px;
            padding: 0 18px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 999px;
            background: transparent;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 900;
            line-height: 1;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .public-cart-button:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            text-decoration: none;
        }

        .public-cart-icon {
            font-size: 20px;
            line-height: 1;
            flex-shrink: 0;
        }

        .public-cart-count {
            min-width: 22px;
            height: 22px;
            padding: 0 7px;
            border-radius: 999px;
            background: #ef4444;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
            line-height: 1;
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
                    <strong>
                        {{ $profilPerusahaan->nama_perusahaan ?? 'CV Bintang Saida Teknik' }}
                    </strong>

                    <small>
                        {{ $profilPerusahaan->bidang_usaha ?? 'Sistem Pemesanan Logistik Perkapalan' }}
                    </small>
                </div>
            </a>

            <form action="{{ route('public.produk') }}" method="GET" class="bst-search">
                <input
                    type="text"
                    name="search"
                    placeholder="Ketik Yang Anda Cari"
                    value="{{ request('search') }}"
                    onkeydown="if(event.key === 'Enter') this.form.submit();"
                >
            </form>

            <div class="bst-auth-area">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.keranjang.index') }}" class="public-cart-button">
                            <i class="bi bi-cart public-cart-icon"></i>
                            <span>Keranjang</span>
                            <span class="public-cart-count">{{ $cartCount ?? 0 }}</span>
                        </a>

                        <div class="dropdown">
                            <button class="bst-login-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a
                                        class="dropdown-item {{ request()->is('customer/profile') && request('tab', 'profil') == 'profil' ? 'active' : '' }}"
                                        href="{{ route('customer.profile') }}"
                                    >
                                        Profil Saya
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item {{ request()->is('customer/profile') && request('tab') == 'pesanan' ? 'active' : '' }}"
                                        href="{{ route('customer.profile', ['tab' => 'pesanan']) }}"
                                    >
                                        Pesanan Saya
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

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
                    <a
                        href="#"
                        class="dropdown-toggle {{ request('kategori') ? 'active' : '' }}"
                        data-bs-toggle="dropdown"
                    >
                        <i class="bi bi-list"></i>
                        Kategori
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('public.produk') }}">
                                Semua Kategori
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

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
                                <a
                                    class="dropdown-item {{ request('kategori') == $kategori ? 'active' : '' }}"
                                    href="{{ route('public.produk', ['kategori' => $kategori]) }}"
                                >
                                    {{ $kategori }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <a
                    class="{{ request()->is('produk') || request()->is('produk/*') ? 'active' : '' }}"
                    href="{{ route('public.produk') }}"
                >
                    Produk
                </a>

                <a
                    class="{{ request()->is('tentang-sistem') ? 'active' : '' }}"
                    href="{{ route('tentang.sistem') }}"
                >
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