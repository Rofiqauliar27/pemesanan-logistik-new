@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h2 class="mb-2">Dashboard Admin</h2>
                <p class="mb-1">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>
                <p class="text-muted mb-0">Kelola data barang, pesanan customer, pembayaran, dan laporan sistem.</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Barang</h6>
                    <h3>{{ $totalBarang }}</h3>
                    <p class="mb-0 text-success">Data stok barang</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Pesanan</h6>
                    <h3>{{ $totalPesanan }}</h3>
                    <p class="mb-0 text-warning">Semua pesanan customer</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-primary shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Customer</h6>
                    <h3>{{ $totalCustomer }}</h3>
                    <p class="mb-0 text-primary">Pengguna customer</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-info shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Pesanan Selesai</h6>
                    <h3>{{ $totalSelesai }}</h3>
                    <p class="mb-0 text-info">Pesanan yang selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h5 class="mb-3">Menu Cepat Admin</h5>

        <div class="row">
            <div class="col-md-3 mb-3">
                <a href="{{ route('barang.index') }}" class="btn btn-success w-100 py-3">
                    Kelola Data Barang
                </a>
            </div>

            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-warning w-100 py-3">
                    Kelola Pesanan
                </a>
            </div>

            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.pesanan.laporan') }}" class="btn btn-info w-100 py-3 text-white">
                    Laporan Pesanan
                </a>
            </div>

            <div class="col-md-3 mb-3">
                <a href="{{ route('dashboard') }}" class="btn btn-dark w-100 py-3">
                    Refresh Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection