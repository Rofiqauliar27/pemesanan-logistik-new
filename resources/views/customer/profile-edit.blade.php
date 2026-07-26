@extends('layouts.customer')

@section('title', 'Edit Profil Customer')

@section('content')
<div class="customer-edit-wrapper">

    <div class="customer-edit-header">
        <div>
            <h1>Edit Profil Customer</h1>
            <p>
                Perbarui informasi pribadi dan alamat tujuan agar proses pemesanan,
                pengiriman, dan pembayaran berjalan lebih mudah.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success customer-edit-alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger customer-edit-alert">
            <strong>Periksa kembali data Anda.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        
        <div class="customer-edit-grid">

            <div class="customer-edit-left">

                <div class="customer-edit-card">
                    <div class="customer-card-title">
                        <div class="customer-title-icon">01</div>
                        <div>
                            <h3>Informasi Pribadi</h3>
                            <p>Data utama yang digunakan sebagai identitas akun customer.</p>
                        </div>
                    </div>

                    <div class="customer-form-grid">
                        <div class="customer-input-group">
                            <label>Nama Lengkap</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                        </div>

                        <div class="customer-input-group">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="Masukkan email aktif"
                                   required>
                        </div>

                        <div class="customer-input-group">
                            <label>Nomor Telepon / WhatsApp</label>
                            <input type="text"
                                   name="telepon"
                                   value="{{ old('telepon', $user->telepon) }}"
                                   placeholder="Contoh: 081234567890">
                        </div>

                        <div class="customer-input-group">
    <label>Nama Kapal</label>

    <input
        type="text"
        name="nama_kapal"
        value="{{ old('nama_kapal',$user->nama_kapal) }}"
        placeholder="Contoh : KM Meratus Surabaya"
        required>
</div>
                    </div>
                </div>

                <div class="customer-edit-card">
                    <div class="customer-card-title">
                        <div class="customer-title-icon">02</div>
                        <div>
                            <h3>Alamat Tujuan</h3>
                            <p>Lengkapi alamat agar pengiriman barang lebih tepat dan mudah diproses.</p>
                        </div>
                    </div>

                    <div class="customer-input-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat_lengkap"
                                  placeholder="Contoh : Pelabuhan Tanjung Perak, Dermaga Jamrud">{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                    </div>
                   
                    </div>

                   
                <div class="customer-edit-actions">
                    <a href="{{ route('customer.profile', ['tab' => 'profil']) }}" class="customer-cancel-btn">
                        Batal
                    </a>

                    <button type="submit" class="customer-save-btn">
                        Simpan Perubahan
                    </button>
                </div>

            </div>

            <aside class="customer-edit-right">
                <div class="customer-profile-preview">
                    <div class="customer-preview-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h4>{{ $user->name }}</h4>
                    <p>{{ $user->email }}</p>

                    <div class="customer-preview-divider"></div>

                    <div class="customer-preview-info">

    <div>
        <span>Telepon</span>
        <strong>{{ $user->telepon ?? '-' }}</strong>
    </div>

    <div>
        <span>Nama Kapal</span>
        <strong>{{ $user->nama_kapal ?? '-' }}</strong>
    </div>

    <div>
        <span>Lokasi Pengiriman</span>
        <strong>{{ $user->alamat_lengkap ?? '-' }}</strong>
    </div>

</div>

                    
                </div>
            </aside>

        </div>
    </form>

</div>
@endsection