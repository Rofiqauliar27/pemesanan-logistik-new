@php
    $profil = \App\Models\ProfilPerusahaan::first();
@endphp

@extends('layouts.public')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="company-profile-page">

    <div class="company-profile-hero">
        <div>
            <h2>{{ $profil->nama_perusahaan ?? 'CV Bintang Saida Teknik' }}</h2>
            <p>
                {{ $profil->deskripsi ?? 'Profil perusahaan belum diisi.' }}
            </p>
        </div>
    </div>

    <div class="company-profile-layout">
        <aside class="company-profile-sidebar">
            <div class="company-logo-box-new">
                <div class="company-logo-frame">
                    @if($profil && $profil->logo)
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo Perusahaan">
                    @else
                        <img src="{{ asset('images/logo-bst.jpeg') }}" alt="Logo Perusahaan">
                    @endif
                </div>

                <h4>{{ $profil->nama_perusahaan ?? 'CV Bintang Saida Teknik' }}</h4>

                <p>
                    {{ $profil->bidang_usaha ?? 'Logistik dan kebutuhan perkapalan' }}
                </p>
            </div>
        </aside>

        <main class="company-profile-content">
            <div class="company-section-box-new">
                <h4>Informasi Perusahaan</h4>

                <div class="company-info-grid">
                    <div class="company-info-card">
                        <strong>Nama Perusahaan</strong>
                        <span>{{ $profil->nama_perusahaan ?? '-' }}</span>
                    </div>

                    <div class="company-info-card">
                        <strong>Bidang Usaha</strong>
                        <span>{{ $profil->bidang_usaha ?? '-' }}</span>
                    </div>

                    <div class="company-info-card">
                        <strong>Alamat</strong>
                        <span>{{ $profil->alamat ?? '-' }}</span>
                    </div>

                    <div class="company-info-card">
                        <strong>Telepon</strong>
                        <span>{{ $profil->telepon ?? '-' }}</span>
                    </div>

                    <div class="company-info-card">
                        <strong>Email</strong>
                        <span>{{ $profil->email ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="company-vm-grid">
                <div class="company-section-box-new">
                    <h4>Visi</h4>
                    <p class="mb-0">
                        {{ $profil->visi ?? '-' }}
                    </p>
                </div>

                <div class="company-section-box-new">
                    <h4>Misi</h4>

                    @if(!empty($profil->misi))
                        <div class="company-text-preline">
                            {{ $profil->misi }}
                        </div>
                    @else
                        <p class="mb-0">-</p>
                    @endif
                </div>
            </div>
        </main>
    </div>

</div>
@endsection