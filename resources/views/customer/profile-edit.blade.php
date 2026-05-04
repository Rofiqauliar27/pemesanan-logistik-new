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

        <a href="{{ route('customer.profile', ['tab' => 'profil']) }}" class="customer-back-btn">
            Kembali ke Profil
        </a>
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
                                  placeholder="Contoh: Jl. Ahmad Yani No. 10, RT 02/RW 04, dekat kantor kelurahan">{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                    </div>

                    <div class="customer-form-grid">
                        <div class="customer-input-group">
                            <label>Provinsi</label>
                            <input type="text"
                                   name="provinsi"
                                   value="{{ old('provinsi', $user->provinsi) }}"
                                   placeholder="Contoh: Kalimantan Selatan">
                        </div>

                        <div class="customer-input-group">
                            <label>Kabupaten / Kota</label>
                            <input type="text"
                                   name="kabupaten"
                                   value="{{ old('kabupaten', $user->kabupaten) }}"
                                   placeholder="Contoh: Hulu Sungai Selatan">
                        </div>

                        <div class="customer-input-group">
                            <label>Kecamatan</label>
                            <input type="text"
                                   name="kecamatan"
                                   value="{{ old('kecamatan', $user->kecamatan) }}"
                                   placeholder="Contoh: Kandangan">
                        </div>

                        <div class="customer-input-group">
                            <label>Kelurahan / Desa</label>
                            <input type="text"
                                   name="kelurahan"
                                   value="{{ old('kelurahan', $user->kelurahan) }}"
                                   placeholder="Contoh: Kandangan Kota">
                        </div>

                        <div class="customer-input-group">
                            <label>Kode Pos</label>
                            <input type="text"
                                   name="kode_pos"
                                   value="{{ old('kode_pos', $user->kode_pos) }}"
                                   placeholder="Contoh: 71211">
                        </div>

                        <div class="customer-input-group">
                            <label>Link Google Maps</label>
                            <input type="url"
                                   name="google_maps_link"
                                   value="{{ old('google_maps_link', $user->google_maps_link) }}"
                                   placeholder="Tempel link lokasi dari Google Maps">
                        </div>
                    </div>

                    <div class="customer-map-help">
                        <strong>Tips:</strong>
                        Buka Google Maps, cari lokasi tujuan, klik <b>Bagikan</b>,
                        lalu salin link dan tempelkan pada kolom Google Maps.
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
                            <span>Alamat</span>
                            <strong>{{ $user->alamat_lengkap ?? '-' }}</strong>
                        </div>

                        <div>
                            <span>Kecamatan</span>
                            <strong>{{ $user->kecamatan ?? '-' }}</strong>
                        </div>

                        <div>
                            <span>Kelurahan / Desa</span>
                            <strong>{{ $user->kelurahan ?? '-' }}</strong>
                        </div>

                        <div>
                            <span>Kode Pos</span>
                            <strong>{{ $user->kode_pos ?? '-' }}</strong>
                        </div>
                    </div>

                    @if($user->google_maps_link)
                        <a href="{{ $user->google_maps_link }}" target="_blank" class="customer-maps-button">
                            Buka Lokasi Google Maps
                        </a>
                    @else
                        <div class="customer-maps-empty">
                            Link Google Maps belum ditambahkan.
                        </div>
                    @endif
                </div>
            </aside>

        </div>
    </form>

</div>
@endsection