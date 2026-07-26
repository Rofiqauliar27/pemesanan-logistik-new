@extends('layouts.admin')

@section('title', 'Detail Customer')

@section('content')
@php
    $alamatLengkap = $customer->alamat_lengkap ?? '-';
@endphp

<style>
    .customer-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .customer-info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
    }

    .customer-info-box span {
        display: block;
        font-size: 13px;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .customer-info-box strong {
        display: block;
        color: #0f172a;
        font-size: 15px;
        font-weight: 900;
        word-break: break-word;
    }

    .customer-address-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        color: #334155;
        line-height: 1.7;
    }

    .customer-action-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .btn-customer-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 160px;
        height: 42px;
        padding: 0 18px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        text-decoration: none;
        font-weight: 800;
        font-size: 14px;
        border: none;
    }

    .btn-customer-primary:hover {
        color: #ffffff;
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        text-decoration: none;
    }

    .btn-customer-secondary {
        background: #eef2f7;
        color: #334155;
        border: 1px solid #dbe3ef;
    }

    .btn-customer-secondary:hover {
        background: #e5ebf4;
        color: #1e293b;
    }

    @media (max-width: 992px) {
        .customer-detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .customer-detail-grid {
            grid-template-columns: 1fr;
        }

        .btn-customer-primary {
            width: 100%;
        }
    }
</style>

<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <h2>Detail Customer</h2>
            <p>Informasi lengkap customer dan riwayat pesanannya.</p>
        </div>

        <div class="admin-page-actions">
            <a href="{{ route('admin.customer.index') }}" class="btn-admin-secondary">
                Kembali
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Profil Customer</h4>
                <p>Data identitas customer yang terdaftar pada sistem.</p>
            </div>
        </div>

        <div class="customer-detail-grid">
            <div class="customer-info-box">
                <span>Nama Customer</span>
                <strong>{{ $customer->name ?? '-' }}</strong>
            </div>

            <div class="customer-info-box">
                <span>Email</span>
                <strong>{{ $customer->email ?? '-' }}</strong>
            </div>

            <div class="customer-info-box">
                <span>Nomor Telepon</span>
                <strong>{{ $customer->telepon ?? '-' }}</strong>
            </div>
            <div class="customer-info-box">
    <span>Nama Kapal</span>
    <strong>{{ $customer->nama_kapal ?? '-' }}</strong>
</div>

            <div class="customer-info-box">
                <span>Role</span>
                <strong>{{ $customer->role ?? '-' }}</strong>
            </div>

            <div class="customer-info-box">
                <span>Tanggal Daftar</span>
                <strong>
                    {{ $customer->created_at ? $customer->created_at->format('d-m-Y H:i') : '-' }}
                </strong>
            </div>

            <div class="customer-info-box">
                <span>Status Email</span>
                <strong>
                    {{ $customer->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                </strong>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Lokasi Pengiriman</h4>
<p>Lokasi pelabuhan atau tujuan pengiriman barang.</p>
            </div>
        </div>

        <div class="customer-address-box">
            <strong>Lokasi Pengiriman:</strong><br>
{{ $alamatLengkap ?: 'Lokasi pengiriman belum dilengkapi.' }}

        </div>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Riwayat Pesanan</h4>
                <p>Daftar pesanan yang pernah dilakukan customer.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Order ID</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th>Total Harga</th>
                        <th>Tanggal Pesanan</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pesanans as $pesanan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $pesanan->group_order_id ?? $pesanan->order_id ?? '-' }}
                            </td>

                            <td>
                                <span class="admin-category-badge">
                                    {{ ucfirst(str_replace('_', ' ', $pesanan->status ?? '-')) }}
                                </span>
                            </td>

                            <td>
                                <span class="admin-category-badge">
                                    {{ ucfirst(str_replace('_', ' ', $pesanan->payment_status ?? '-')) }}
                                </span>
                            </td>

                            <td>
                                Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $pesanan->created_at ? $pesanan->created_at->format('d-m-Y H:i') : '-' }}
                            </td>

                            <td>
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn-table-edit">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="admin-empty-state">
                                    Customer belum memiliki riwayat pesanan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card">
        <div class="customer-action-bar">
            <a href="{{ route('admin.customer.index') }}" class="btn-customer-primary btn-customer-secondary">
                Kembali ke Data Customer
            </a>
        </div>
    </div>

</div>
@endsection