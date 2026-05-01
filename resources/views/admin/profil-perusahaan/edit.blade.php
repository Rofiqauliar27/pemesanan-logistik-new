@extends('layouts.admin')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Pengaturan</span>
            <h2>Edit Profil Perusahaan</h2>
            <p>Ubah informasi perusahaan yang akan tampil di halaman publik.</p>
        </div>

        <div class="admin-page-actions">
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger admin-alert">
            <strong>Terjadi kesalahan.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data" class="admin-form-layout">
        @csrf

        <div class="admin-card">
            <div class="admin-form-section-title">
                <h4>Informasi Utama</h4>
                <p>Data dasar perusahaan seperti nama, bidang usaha, kontak, dan alamat.</p>
            </div>

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label>Nama Perusahaan</label>
                    <input
                        type="text"
                        name="nama_perusahaan"
                        value="{{ old('nama_perusahaan', $profil->nama_perusahaan) }}"
                        placeholder="Masukkan nama perusahaan"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Bidang Usaha</label>
                    <input
                        type="text"
                        name="bidang_usaha"
                        value="{{ old('bidang_usaha', $profil->bidang_usaha) }}"
                        placeholder="Contoh: Logistik, Perkapalan, Supplier"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Telepon</label>
                    <input
                        type="text"
                        name="telepon"
                        value="{{ old('telepon', $profil->telepon) }}"
                        placeholder="Masukkan nomor telepon"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $profil->email) }}"
                        placeholder="Masukkan email perusahaan"
                    >
                </div>

                <div class="admin-form-group full">
                    <label>Alamat</label>
                    <textarea
                        name="alamat"
                        placeholder="Masukkan alamat perusahaan"
                    >{{ old('alamat', $profil->alamat) }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-form-section-title">
                <h4>Profil dan Identitas Perusahaan</h4>
                <p>Deskripsi, visi, dan misi yang akan tampil pada halaman profil perusahaan publik.</p>
            </div>

            <div class="admin-form-grid">
                <div class="admin-form-group full">
                    <label>Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        placeholder="Tulis deskripsi singkat perusahaan"
                    >{{ old('deskripsi', $profil->deskripsi) }}</textarea>
                </div>

                <div class="admin-form-group">
                    <label>Visi</label>
                    <textarea
                        name="visi"
                        placeholder="Tulis visi perusahaan"
                    >{{ old('visi', $profil->visi) }}</textarea>
                </div>

                <div class="admin-form-group">
                    <label>Misi</label>
                    <textarea
                        name="misi"
                        placeholder="Tulis misi perusahaan"
                    >{{ old('misi', $profil->misi) }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-form-section-title">
                <h4>Logo Perusahaan</h4>
                <p>Upload logo yang akan digunakan pada sidebar admin dan halaman publik.</p>
            </div>

            <div class="admin-logo-upload-grid">
                <div class="admin-form-group">
                    <label>Upload Logo Baru</label>
                    <input type="file" name="logo">
                    <small>Format disarankan: JPG, PNG, atau WEBP. Gunakan logo ukuran persegi agar terlihat rapi.</small>
                </div>

                <div class="admin-current-logo-box">
                    <label>Logo Saat Ini</label>

                    @if($profil->logo)
                        <div class="admin-current-logo-preview">
                            <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo Perusahaan">
                        </div>
                    @else
                        <div class="admin-no-logo-preview">
                            Belum ada logo tersimpan.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ url('/admin/dashboard') }}" class="btn-admin-secondary">
                Batal
            </a>

            <button type="submit" class="btn-admin-primary">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection