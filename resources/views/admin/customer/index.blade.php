@extends('layouts.admin')

@section('title', 'Data Customer')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">Data Customer</h2>
                <p class="text-muted mb-0">Daftar seluruh customer yang terdaftar di sistem.</p>
            </div>
            <div>
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-secondary">Kembali Dashboard</a>
            </div>
        </div>

        <form action="{{ route('admin.customer.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau email customer..." value="{{ request('search') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-dark">Cari</button>
                <a href="{{ route('admin.customer.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
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
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $item->role }}</span>
                            </td>
                            <td>{{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data customer</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection