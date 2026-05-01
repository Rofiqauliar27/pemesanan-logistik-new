@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="admin-dashboard-page">

    <div class="dashboard-welcome-card">
        <div>
            <span>Dashboard</span>
            <h2>Selamat datang, {{ auth()->user()->name }}</h2>
            <p>
                Kelola data barang, pesanan, customer, banner, kategori, dan laporan sistem pemesanan logistik.
            </p>
        </div>
    </div>

    <div class="dashboard-stat-grid">
        <div class="dashboard-stat-card">
            <span>Total Barang</span>
            <strong>{{ $totalBarang ?? 0 }}</strong>
        </div>

        <div class="dashboard-stat-card">
            <span>Total Pesanan</span>
            <strong>{{ $totalPesanan ?? 0 }}</strong>
        </div>

        <div class="dashboard-stat-card">
            <span>Total Customer</span>
            <strong>{{ $totalCustomer ?? 0 }}</strong>
        </div>

        <div class="dashboard-stat-card">
            <span>Pesanan Selesai</span>
            <strong>{{ $totalSelesai ?? 0 }}</strong>
        </div>
    </div>

    <div class="dashboard-section-card">
        <div class="dashboard-section-header">
            <div>
                <h4>Menu Cepat</h4>
                <p>Akses cepat ke fitur utama panel admin.</p>
            </div>
        </div>

        <div class="dashboard-quick-grid">
            <a href="{{ route('barang.index') }}" class="dashboard-quick-card">
                <span>Barang</span>
                <strong>Kelola Barang</strong>
                <small>Tambah, edit, dan hapus data barang.</small>
            </a>

            <a href="{{ route('admin.pesanan.index') }}" class="dashboard-quick-card">
                <span>Pesanan</span>
                <strong>Kelola Pesanan</strong>
                <small>Pantau pesanan dan status pembayaran.</small>
            </a>

            <a href="{{ route('admin.customer.index') }}" class="dashboard-quick-card">
                <span>Customer</span>
                <strong>Data Customer</strong>
                <small>Lihat data customer yang terdaftar.</small>
            </a>

            <a href="{{ route('admin.pesanan.laporan') }}" class="dashboard-quick-card">
                <span>Laporan</span>
                <strong>Laporan Pesanan</strong>
                <small>Cetak dan filter laporan operasional.</small>
            </a>
        </div>
    </div>

</div>
@endsection