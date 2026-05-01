@extends('layouts.admin')

@section('title', 'Data Customer')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Customer</span>
            <h2>Data Customer</h2>
            <p>Daftar seluruh customer yang terdaftar di sistem.</p>
        </div>

        <div class="admin-page-actions">
        </div>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.customer.index') }}" method="GET" class="admin-filter-form">
            <div class="admin-search-field">
                <label>Cari Customer</label>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama atau email customer..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="admin-filter-actions">
                <button type="submit" class="btn-admin-primary">
                    Cari
                </button>

                <a href="{{ route('admin.customer.index') }}" class="btn-admin-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-table-header">
            <div>
                <h4>Daftar Customer</h4>
                <p>Total data: {{ $customers->count() }} customer</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Customer</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <div class="admin-product-name">
                                    {{ $item->name }}
                                </div>
                            </td>

                            <td>
                                <div class="admin-desc-text">
                                    {{ $item->email }}
                                </div>
                            </td>

                            <td>
                                <span class="admin-category-badge">
                                    {{ $item->role }}
                                </span>
                            </td>

                            <td>
                                {{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="admin-empty-state">
                                    Belum ada data customer.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection