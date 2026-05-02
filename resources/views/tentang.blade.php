@php
    $profil = \App\Models\ProfilPerusahaan::first();
@endphp

@extends('layouts.public')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="company-about-page">

    {{-- HERO --}}
    <section class="about-hero scroll-reveal">
        <div class="about-hero-bg"></div>

        <div class="about-hero-content">
            

            <h1>
                Mengenal
                <span>{{ $profil->nama_perusahaan ?? 'Hanesa Company' }}</span>
            </h1>

            <p>
                {{ $profil->deskripsi ?? 'Kami hadir untuk membantu kebutuhan logistik, produk operasional, dan perlengkapan usaha dalam satu sistem yang lebih mudah, rapi, dan modern.' }}
            </p>

            <div class="about-hero-actions">
                <a href="{{ route('public.produk') }}" class="about-primary-btn">
                    Lihat Produk
                </a>

                <a href="#company-info" class="about-secondary-btn">
                    Tentang Kami
                </a>
            </div>
        </div>

        <div class="about-hero-visual">
            <div class="about-logo-card">
                <div class="about-logo-circle">
                    @if($profil && $profil->logo)
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo Perusahaan">
                    @else
                        <img src="{{ asset('images/logo-bst.jpeg') }}" alt="Logo Perusahaan">
                    @endif
                </div>

                <h3>{{ $profil->nama_perusahaan ?? 'Hanesa Company' }}</h3>
                <p>{{ $profil->bidang_usaha ?? 'Logistik dan kebutuhan operasional' }}</p>
            </div>

            <div class="about-floating-card card-one">
                <strong>Produk</strong>
                <span>Kebutuhan operasional lebih mudah ditemukan</span>
            </div>

            <div class="about-floating-card card-two">
                <strong>Logistik</strong>
                <span>Pengelolaan pesanan lebih praktis</span>
            </div>
        </div>
    </section>

    {{-- INTRO --}}
    <section id="company-info" class="about-section about-intro-section">
        <div class="about-section-header scroll-reveal">
            <span>Tentang Perusahaan</span>
            <h2>Solusi produk dan kebutuhan logistik dalam satu tempat</h2>
            <p>
                Kami berfokus membantu pelanggan menemukan produk, mengelola pesanan,
                dan mendapatkan informasi perusahaan secara lebih mudah.
            </p>
        </div>

        <div class="about-intro-grid">
            <div class="about-story-card scroll-reveal-left">
                <h3>Siapa Kami?</h3>
                <p>
                    {{ $profil->deskripsi ?? 'Perusahaan kami bergerak di bidang penyediaan produk dan layanan pendukung logistik untuk kebutuhan pelanggan, operasional, pergudangan, dan perkapalan.' }}
                </p>

                <div class="about-mini-list">
                    <div>
                        <strong>{{ $profil->nama_perusahaan ?? 'Hanesa Company' }}</strong>
                        <span>Nama Perusahaan</span>
                    </div>

                    <div>
                        <strong>{{ $profil->bidang_usaha ?? 'Logistik dan Produk Operasional' }}</strong>
                        <span>Bidang Usaha</span>
                    </div>
                </div>
            </div>

            <div class="about-info-panel scroll-reveal-right">
                <div class="about-info-item">
                    <span>Alamat</span>
                    <strong>{{ $profil->alamat ?? '-' }}</strong>
                </div>

                <div class="about-info-item">
                    <span>Telepon</span>
                    <strong>{{ $profil->telepon ?? '-' }}</strong>
                </div>

                <div class="about-info-item">
                    <span>Email</span>
                    <strong>{{ $profil->email ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </section>

    {{-- HIGHLIGHT --}}
    <section class="about-highlight-section">
        <div class="about-highlight-grid">
            <div class="about-highlight-card scroll-reveal">
                <span>01</span>
                <h3>Produk Terkelola</h3>
                <p>
                    Data produk dapat dikelola dengan lebih rapi, mulai dari kategori,
                    harga, stok, hingga informasi barang.
                </p>
            </div>

            <div class="about-highlight-card scroll-reveal delay-1">
                <span>02</span>
                <h3>Pesanan Praktis</h3>
                <p>
                    Pelanggan dapat melihat produk, menambahkan ke keranjang,
                    melakukan checkout, dan memantau pesanan.
                </p>
            </div>

            <div class="about-highlight-card scroll-reveal delay-2">
                <span>03</span>
                <h3>Informasi Transparan</h3>
                <p>
                    Profil perusahaan, kontak, produk, serta layanan dapat ditampilkan
                    dengan jelas kepada pelanggan.
                </p>
            </div>
        </div>
    </section>

    {{-- VISI MISI --}}
    <section class="about-section about-vision-section">
        <div class="about-vision-grid">
            <div class="about-vision-card scroll-reveal-left">
                <span>Visi</span>
                <h2>Tujuan Kami</h2>
                <p>
                    {{ $profil->visi ?? 'Menjadi perusahaan yang terpercaya dalam menyediakan produk dan solusi logistik yang mudah diakses, efisien, dan bermanfaat bagi pelanggan.' }}
                </p>
            </div>

            <div class="about-vision-card scroll-reveal-right">
                <span>Misi</span>
                <h2>Langkah Kami</h2>

                @if(!empty($profil->misi))
                    <div class="about-mission-text">
                        {{ $profil->misi }}
                    </div>
                @else
                    <div class="about-mission-text">
                        Memberikan layanan yang mudah digunakan, menyediakan produk berkualitas,
                        menjaga kepercayaan pelanggan, dan terus mengembangkan sistem yang lebih modern.
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- VALUES --}}
    <section class="about-values-section">
        <div class="about-section-header scroll-reveal">
            <span>Nilai Perusahaan</span>
            <h2>Memberikan pengalaman yang lebih baik untuk pelanggan</h2>
            <p>
                Sistem ini dibangun agar pelanggan dapat memperoleh informasi dan produk
                dengan cepat, jelas, dan nyaman digunakan.
            </p>
        </div>

        <div class="about-values-grid">
            <div class="about-value-card scroll-reveal">
                <div class="about-value-icon">01</div>
                <h3>Mudah Digunakan</h3>
                <p>
                    Tampilan dibuat sederhana agar pelanggan mudah mencari produk dan informasi.
                </p>
            </div>

            <div class="about-value-card scroll-reveal delay-1">
                <div class="about-value-icon">02</div>
                <h3>Rapi dan Terstruktur</h3>
                <p>
                    Produk, kategori, pesanan, dan informasi perusahaan tersusun dalam satu sistem.
                </p>
            </div>

            <div class="about-value-card scroll-reveal delay-2">
                <div class="about-value-icon">03</div>
                <h3>Responsif</h3>
                <p>
                    Sistem dapat diakses dengan nyaman dari berbagai perangkat.
                </p>
            </div>

            <div class="about-value-card scroll-reveal delay-3">
                <div class="about-value-icon">04</div>
                <h3>Terpercaya</h3>
                <p>
                    Informasi produk dan perusahaan ditampilkan secara jelas kepada pelanggan.
                </p>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="about-cta scroll-reveal">
        <div>
            <span>Mulai Jelajahi</span>
            <h2>Temukan kebutuhan produk dan logistik Anda sekarang</h2>
            <p>
                Lihat daftar produk yang tersedia dan temukan kebutuhan operasional
                yang sesuai dengan kebutuhan Anda.
            </p>
        </div>

        <a href="{{ route('public.produk') }}">
            Lihat Produk
        </a>
    </section>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const revealElements = document.querySelectorAll(
            '.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right'
        );

        const activateReveal = (element) => {
            element.classList.add('active');
        };

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        activateReveal(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.14,
                rootMargin: '0px 0px -60px 0px'
            });

            revealElements.forEach((element) => {
                observer.observe(element);
            });
        } else {
            const revealOnScroll = () => {
                revealElements.forEach((element) => {
                    const windowHeight = window.innerHeight;
                    const elementTop = element.getBoundingClientRect().top;

                    if (elementTop < windowHeight - 80) {
                        activateReveal(element);
                    }
                });
            };

            window.addEventListener('scroll', revealOnScroll);
            window.addEventListener('touchmove', revealOnScroll);
            window.addEventListener('load', revealOnScroll);
            revealOnScroll();
        }
    });
</script>
@endsection