@extends('layouts.admin')

@section('title', 'Profil Perusahaan')

@section('content')
    <div class="page-box">
        <h2 class="mb-2">Edit Profil Perusahaan</h2>
        <p class="text-muted mb-4">
            Ubah informasi perusahaan yang akan tampil di halaman publik.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" value="{{ old('nama_perusahaan', $profil->nama_perusahaan) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Bidang Usaha</label>
                    <input type="text" name="bidang_usaha" class="form-control" value="{{ old('bidang_usaha', $profil->bidang_usaha) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $profil->telepon) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" value="{{ old('email', $profil->email) }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $profil->alamat) }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $profil->deskripsi) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Visi</label>
                    <textarea name="visi" class="form-control" rows="5">{{ old('visi', $profil->visi) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Misi</label>
                    <textarea name="misi" class="form-control" rows="5">{{ old('misi', $profil->misi) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo Perusahaan</label>
                    <input type="file" name="logo" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    @if($profil->logo)
                        <label class="form-label">Logo Saat Ini</label><br>
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo Perusahaan" width="120" class="img-thumbnail">
                    @else
                        <label class="form-label">Logo Saat Ini</label>
                        <p class="text-muted mb-0">Belum ada logo tersimpan.</p>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
@endsection