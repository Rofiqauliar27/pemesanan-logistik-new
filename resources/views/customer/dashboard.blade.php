@extends('layouts.customer')

@section('title', 'Dashboard Customer')

@section('content')
    <div class="customer-hero">
        <h2>Dashboard Customer</h2>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Pantau pesanan dan pembayaran Anda di sini.
        </p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="customer-stat">
                <h6>Total Pesanan Saya</h6>
                <h3>{{ $totalPesananSaya ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="customer-stat">
                <h6>Belum / Menunggu Bayar</h6>
                <h3>{{ $belumBayar ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="customer-stat">
                <h6>Pesanan Selesai</h6>
                <h3>{{ $selesai ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="customer-box">
        <h5 class="mb-3">Akses Cepat</h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('public.produk') }}" class="btn btn-primary w-100">
                    Lihat Produk
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('customer.pesanan.index') }}" class="btn btn-success w-100">
                    Riwayat Pesanan
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('customer.pesanan.index') }}" class="btn btn-warning w-100">
                    Pembayaran
                </a>
            </div>
        </div>
    </div>

    <div class="customer-box">
        <h5 class="mb-3">Informasi Customer</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100">
                    <strong>Nama</strong>
                    <p class="mb-0 text-muted">{{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100">
                    <strong>Email</strong>
                    <p class="mb-0 text-muted">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection