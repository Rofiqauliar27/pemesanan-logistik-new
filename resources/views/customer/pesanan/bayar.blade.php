@extends('layouts.public')

@section('title', 'Pembayaran Pesanan')

@section('content')
@php
    $pesananUtama = $pesananUtama ?? $pesanan;
    $user = auth()->user();

    $groupOrderId = $groupOrderId ?? ($pesananUtama->group_order_id ?? $pesananUtama->order_id ?? '-');

    $alamatLengkapDiisi =
        !empty($user->name) &&
        !empty($user->email) &&
        !empty($user->telepon) &&
        !empty($user->alamat_lengkap) &&
        !empty($user->kelurahan) &&
        !empty($user->kecamatan) &&
        !empty($user->kabupaten) &&
        !empty($user->provinsi) &&
        !empty($user->kode_pos);

    $statusBayar = $pesananUtama->payment_status ?? 'belum_bayar';
    $statusPesanan = $pesananUtama->status ?? 'pending';

    $sudahLunas = in_array($statusBayar, [
        'sudah_bayar',
        'settlement',
        'paid',
        'capture',
    ]);

    $sudahExpired = in_array($statusBayar, [
        'expire',
        'failed',
        'gagal',
    ]);

    $totalJenisBarang = $pesanans->count();
    $totalJumlahItem = $pesanans->sum('jumlah');

    $tanggalPesanan = $pesananUtama && $pesananUtama->created_at
        ? $pesananUtama->created_at->format('d-m-Y H:i')
        : '-';

    $batasBayar = $pesananUtama && $pesananUtama->expired_at
        ? $pesananUtama->expired_at->format('d-m-Y H:i')
        : '-';

    $tanggalBayar = $pesananUtama && $pesananUtama->paid_at
        ? $pesananUtama->paid_at->format('d-m-Y H:i')
        : '-';

    $labelStatusBayar = [
    'belum_bayar' => 'Belum Dibayar',
    'pending' => 'Menunggu Pembayaran',
    'challenge' => 'Menunggu Konfirmasi',
    'sudah_bayar' => 'Sudah Bayar',
    'settlement' => 'Sudah Bayar',
    'paid' => 'Sudah Bayar',
    'capture' => 'Sudah Bayar',
    'failed' => 'Gagal',
    'gagal' => 'Gagal',
    'expire' => 'Kadaluarsa',
][$statusBayar] ?? ucfirst(str_replace('_', ' ', $statusBayar));

    $labelStatusPesanan = [
        'pending' => 'Pending',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        'cancel_request' => 'Menunggu Refund',
        'refund_success' => 'Refund Berhasil',
    ][$statusPesanan] ?? ucfirst(str_replace('_', ' ', $statusPesanan));

    $alamatLengkap = collect([
        $user->alamat_lengkap ?? null,
        $user->kelurahan ?? null,
        $user->kecamatan ?? null,
        $user->kabupaten ?? null,
        $user->provinsi ?? null,
        $user->kode_pos ?? null,
    ])->filter()->implode(', ');

    $stepPesananDibuat = true;
    $stepPembayaran = $sudahLunas || in_array($statusPesanan, ['diproses', 'dikirim', 'selesai']);
    $stepDiproses = in_array($statusPesanan, ['diproses', 'dikirim', 'selesai']);
    $stepDikirim = in_array($statusPesanan, ['dikirim', 'selesai']);
    $stepSelesai = $statusPesanan === 'selesai';
@endphp

@if(!$sudahLunas)
    <div class="checkout-payment-page">

        <div class="checkout-payment-header">
            <div>

                @if($sudahExpired)
                    <h2>Detail Pesanan Kadaluarsa</h2>
                    <p>
                        Pesanan telah melewati batas waktu pembayaran dan tidak dapat dibayar lagi.
                    </p>
                @else
                    <h2>Pembayaran Pesanan</h2>
                    <p>
                        Periksa kembali item yang Anda checkout sebelum melanjutkan pembayaran.
                    </p>
                @endif

            </div>
        </div>

        @if($sudahExpired)
    <div class="alert alert-warning mb-4">
        <strong>Pesanan Kadaluarsa</strong><br>
        Pesanan ini telah melewati batas waktu pembayaran 24 jam dan otomatis dibatalkan oleh sistem.
    </div>
@endif

        <div class="checkout-payment-layout">

            <div class="checkout-items-card">
                <div class="checkout-card-header">
                    <h4>Detail Item Checkout</h4>
                    <small>{{ $totalJenisBarang }} jenis barang, {{ $totalJumlahItem }} item</small>
                </div>

                <div class="mb-3">
                    <table class="table table-bordered align-middle">
                        <tr>
                            <th width="35%">Group Order ID</th>
                            <td>{{ $groupOrderId }}</td>
                        </tr>

                        <tr>
                            <th>Tanggal Pesanan</th>
                            <td>{{ $tanggalPesanan }}</td>
                        </tr>

                        <tr>
                            <th>Batas Pembayaran</th>
                            <td>{{ $batasBayar }}</td>
                        </tr>

                        <tr>
                            <th>Status Pembayaran</th>
                            <td>{{ $labelStatusBayar }}</td>
                        </tr>

                        <tr>
                            <th>Tanggal Pembayaran</th>
                            <td>{{ $tanggalBayar }}</td>
                        </tr>
                    </table>
                </div>

                <div class="checkout-item-list">
                    @foreach($pesanans as $item)
                        <div class="checkout-item-row">
                            <div class="checkout-item-number">
                                {{ $loop->iteration }}
                            </div>

                            <div class="checkout-item-info">
                                <h5>{{ $item->barang->nama_barang ?? '-' }}</h5>
                                <span>
                                    Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="checkout-item-qty">
                                x{{ $item->jumlah }}
                            </div>

                            <div class="checkout-item-subtotal">
                                Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="checkout-summary-card">
                <h4>Ringkasan Pembayaran</h4>

                <div class="summary-group-id">
                    <span>Group Order ID</span>
                    <strong>{{ $groupOrderId }}</strong>
                </div>

                <div class="summary-line">
                    <span>Total Jenis Barang</span>
                    <strong>{{ $totalJenisBarang }} Barang</strong>
                </div>

                <div class="summary-line">
                    <span>Total Item</span>
                    <strong>{{ $totalJumlahItem }} Item</strong>
                </div>

                <div class="summary-line">
                    <span>Status</span>
                    <strong class="summary-status">
                        {{ $labelStatusBayar }}
                    </strong>
                </div>

                <div class="summary-line">
                    <span>Batas Bayar</span>
                    <strong>{{ $batasBayar }}</strong>
                </div>

                <div class="summary-total">
                    <span>Total Bayar</span>
                    <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                @if($sudahExpired)
                    <button class="btn-pay-now" disabled>
                        Pembayaran Tidak Tersedia
                    </button>
                @elseif(!$alamatLengkapDiisi)
                    <div class="alert alert-warning mb-3">
                        <strong>Alamat belum lengkap.</strong><br>
                        Silakan lengkapi alamat dan nomor telepon terlebih dahulu sebelum melakukan pembayaran.
                    </div>

                    <a href="{{ route('customer.profile.edit', ['redirect' => url()->current()]) }}" class="btn-pay-now text-center text-decoration-none">
                        Lengkapi Profil
                    </a>

                    <button type="button" class="btn-pay-now mt-2" disabled>
                        Bayar Sekarang
                    </button>
                @else
                    <button id="pay-button" class="btn-pay-now">
                        Bayar Sekarang
                    </button>
                @endif

                <a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}" class="btn-payment-history">
                    Kembali ke Pesanan Saya
                </a>

                <div class="summary-note">
                    Pembayaran ini berlaku untuk seluruh item dalam satu pesanan.
                    Batas pembayaran maksimal 24 jam setelah pesanan dibuat.
                </div>
            </aside>

        </div>

    </div>
@else
    <div class="order-tracking-page">

        <div class="order-status-card">
            <div class="order-status-header">
                <span>Kode Pesanan</span>
                <strong>{{ $groupOrderId }}</strong>
            </div>

           <div class="order-status-line">

@if($statusPesanan == 'cancel_request')

    <div class="order-status-step active">
        <div class="step-circle">1</div>
        <strong>Pesanan Dibuat</strong>
        <span>{{ $tanggalPesanan }}</span>
    </div>

    <div class="step-connector active"></div>

    <div class="order-status-step active">
        <div class="step-circle">2</div>
        <strong>Pembayaran Dikonfirmasi</strong>
        <span>{{ $tanggalBayar }}</span>
    </div>

    <div class="step-connector active"></div>

    <div class="order-status-step active">
        <div class="step-circle">3</div>
        <strong>Menunggu Refund</strong>
        <span>Permintaan refund sedang diproses</span>
    </div>

@elseif($statusPesanan == 'refund_success')

    <div class="order-status-step active">
        <div class="step-circle">1</div>
        <strong>Pesanan Dibuat</strong>
        <span>{{ $tanggalPesanan }}</span>
    </div>

    <div class="step-connector active"></div>

    <div class="order-status-step active">
        <div class="step-circle">2</div>
        <strong>Pembayaran Dikonfirmasi</strong>
        <span>{{ $tanggalBayar }}</span>
    </div>

    <div class="step-connector active"></div>

    <div class="order-status-step active">
        <div class="step-circle">3</div>
        <strong>Refund Berhasil</strong>
        <span>Dana telah dikembalikan</span>
    </div>

@else

    {{-- Timeline lama tetap dipakai --}}

    <div class="order-status-step {{ $stepPesananDibuat ? 'active' : '' }}">
        <div class="step-circle">1</div>
        <strong>Pesanan Dibuat</strong>
        <span>{{ $tanggalPesanan }}</span>
    </div>

    <div class="step-connector {{ $stepPembayaran ? 'active' : '' }}"></div>

    <div class="order-status-step {{ $stepPembayaran ? 'active' : '' }}">
        <div class="step-circle">2</div>
        <strong>Pembayaran Dikonfirmasi</strong>
        <span>{{ $tanggalBayar }}</span>
    </div>

    <div class="step-connector {{ $stepDiproses ? 'active' : '' }}"></div>

    <div class="order-status-step {{ $stepDiproses ? 'active' : '' }}">
        <div class="step-circle">3</div>
        <strong>Diproses</strong>
        <span>Pesanan sedang disiapkan</span>
    </div>

    <div class="step-connector {{ $stepDikirim ? 'active' : '' }}"></div>

    <div class="order-status-step {{ $stepDikirim ? 'active' : '' }}">
        <div class="step-circle">4</div>
        <strong>Dikirim</strong>
        <span>Pesanan dalam pengiriman</span>
    </div>

    <div class="step-connector {{ $stepSelesai ? 'active' : '' }}"></div>

    <div class="order-status-step {{ $stepSelesai ? 'active' : '' }}">
        <div class="step-circle">5</div>
        <strong>Selesai</strong>
        <span>Pesanan selesai</span>
    </div>

@endif

</div>

        <div class="order-detail-layout">
            <div class="order-detail-left">

                <div class="order-section-card">
                    <h4>Alamat Pengiriman</h4>

                    <div class="order-address-box">
                        <strong>{{ $user->name ?? '-' }}</strong>

                        @if(!empty($user->telepon))
                            <span>{{ $user->telepon }}</span>
                        @endif

                        <p>
                            {{ $alamatLengkap ?: 'Alamat belum dilengkapi.' }}
                        </p>
                    </div>
                </div>

                <div class="order-section-card">
                    <div class="order-section-header">
                        <div>
                            <h4>Daftar Barang</h4>
                            <p>{{ $totalJenisBarang }} jenis barang, {{ $totalJumlahItem }} item</p>
                        </div>
                    </div>

                    <div class="order-product-list">
                        @foreach($pesanans as $item)
                            <div class="order-product-row">
                                <div class="order-product-image">
                                    @if($item->barang && $item->barang->gambar)
                                        <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->barang->nama_barang }}">
                                    @else
                                        <span>Produk</span>
                                    @endif
                                </div>

                                <div class="order-product-info">
                                    <h5>{{ $item->barang->nama_barang ?? '-' }}</h5>
                                    <p>Jumlah: {{ $item->jumlah }} item</p>
                                </div>

                                <div class="order-product-price">
                                    <span>Harga</span>
                                    <strong>Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}</strong>
                                </div>

                                <div class="order-product-subtotal">
                                    <span>Subtotal</span>
                                    <strong>Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <aside class="order-payment-card">
                <h4>Ringkasan Pembayaran</h4>

                <div class="payment-summary-total">
                    <span>Total Bayar</span>
                    <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                <div class="payment-summary-line">
                    <span>Status Pembayaran</span>
                    <strong>{{ $labelStatusBayar }}</strong>
                </div>

                <div class="payment-summary-line">
                    <span>Status Pesanan</span>
                    <strong>{{ $labelStatusPesanan }}</strong>
                </div>

                <div class="payment-summary-line">
                    <span>Tanggal Pesanan</span>
                    <strong>{{ $tanggalPesanan }}</strong>
                </div>

                <div class="payment-summary-line">
                    <span>Tanggal Bayar</span>
                    <strong>{{ $tanggalBayar }}</strong>
                </div>

                @if(!empty($pesananUtama->payment_type))
                    <div class="payment-summary-line">
                        <span>Metode Pembayaran</span>
                        <strong>{{ strtoupper($pesananUtama->payment_type) }}</strong>
                    </div>
                @endif

                <button class="btn-payment-disabled" disabled>
    Pesanan Sudah Dibayar
</button>

@if(in_array($statusPesanan, ['pending','diproses']))
    <button
        type="button"
        class="btn btn-danger w-100 mt-2"
        data-bs-toggle="modal"
        data-bs-target="#cancelModal">

        Ajukan Pembatalan
    </button>
@endif

<a href="{{ route('customer.profile', ['tab' => 'pesanan']) }}"
   class="btn-order-history">
    Lihat Pesanan Saya
</a>
            </aside>
        </div>

    </div>
@endif

<div class="modal fade"
     id="cancelModal"
     tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('customer.pesanan.cancel', $pesananUtama->id) }}"
                  method="POST">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        Pembatalan Pesanan
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Alasan Pembatalan
                    </label>

                    <textarea
                        name="cancel_reason"
                        class="form-control"
                        rows="4"
                        required></textarea>

                        <div class="mt-3">

    <label class="form-label">
        Bank Tujuan Refund
    </label>

    <select name="refund_bank"
            class="form-control"
            required>

        <option value="">-- Pilih Bank --</option>

        <option value="BCA">BCA</option>
        <option value="BRI">BRI</option>
        <option value="BNI">BNI</option>
        <option value="Mandiri">Mandiri</option>
        <option value="BSI">BSI</option>
        <option value="CIMB Niaga">CIMB Niaga</option>

    </select>

</div>

<div class="mt-3">

    <label class="form-label">
        Nomor Rekening
    </label>

    <input type="text"
           name="refund_account_number"
           class="form-control"
           required>

</div>

<div class="mt-3">

    <label class="form-label">
        Nama Pemilik Rekening
    </label>

    <input type="text"
           name="refund_account_name"
           class="form-control"
           required>

</div>
                    <small class="text-muted">
                        Refund akan diproses oleh admin.
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Tutup
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Kirim Permintaan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@section('scripts')
@if(!$sudahLunas && !$sudahExpired && $alamatLengkapDiisi && $snapToken)
    <script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.getElementById('pay-button').onclick = function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil");
                    window.location.href = "{{ route('customer.pesanan.showBayar', $pesananUtama->id) }}";
                },
                onPending: function(result) {
                    alert("Pembayaran sedang menunggu penyelesaian");
                    window.location.href = "{{ route('customer.pesanan.showBayar', $pesananUtama->id) }}";
                },
                onError: function(result) {
                    alert("Pembayaran gagal");
                    window.location.href = "{{ route('customer.profile', ['tab' => 'pesanan', 'filter' => 'gagal']) }}";
                },
                onClose: function() {
                    alert("Kamu menutup popup pembayaran");
                }
            });
        };
    </script>
@endif
@endsection