@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="page-box">
        <h2 class="mb-2">Dashboard Admin</h2>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Kelola seluruh data operasional logistik di sini.
        </p>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="page-box">
                <h6 class="text-muted">Total Barang</h6>
                <h3>{{ $totalBarang ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="page-box">
                <h6 class="text-muted">Total Pesanan</h6>
                <h3>{{ $totalPesanan ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="page-box">
                <h6 class="text-muted">Total Customer</h6>
                <h3>{{ $totalCustomer ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="page-box">
                <h6 class="text-muted">Pesanan Selesai</h6>
                <h3>{{ $totalSelesai ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="page-box">
        <h5 class="mb-3">Menu Cepat</h5>

        <div class="row">
            <div class="col-md-3 mb-3">
                <a href="{{ route('barang.index') }}" class="btn btn-primary w-100">Kelola Barang</a>
            </div>

            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-success w-100">Kelola Pesanan</a>
            </div>

            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.customer.index') }}" class="btn btn-warning w-100">Data Customer</a>
            </div>

            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.pesanan.laporan') }}" class="btn btn-dark w-100">Laporan</a>
            </div>
        </div>
    </div>
@endsection