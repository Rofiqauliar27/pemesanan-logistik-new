@extends('layouts.customer')

@section('title', 'Keranjang Saya')

@section('content')
<div class="cart-page">

    <div class="cart-hero">
        <h2>Keranjang Saya</h2>
        <p>
            Kelola produk yang ingin Anda beli sebelum melanjutkan ke proses checkout.
        </p>
    </div>

    @if($keranjangs->count() > 0)
        <div class="cart-card">
            <div class="table-responsive">
                <table class="table cart-table align-middle">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th width="60">No</th>
                            <th>Barang</th>
                            <th width="180">Harga</th>
                            <th width="210">Jumlah</th>
                            <th width="180">Subtotal</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($keranjangs as $item)
                            @php
                                $harga = (int) ($item->barang->harga ?? 0);
                                $jumlah = (int) $item->jumlah;
                                $subtotal = $harga * $jumlah;
                            @endphp

                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        name="keranjang_ids[]"
                                        value="{{ $item->id }}"
                                        class="item-checkbox cart-check"
                                        data-subtotal="{{ $subtotal }}"
                                        form="checkoutForm"
                                    >
                                </td>

                                <td>
                                    <span class="cart-number">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <div class="cart-product-info">
                                        <strong>{{ $item->barang->nama_barang ?? '-' }}</strong>
                                        <small>{{ $item->barang->kategori ?? 'Produk' }}</small>
                                    </div>
                                </td>

                                <td>
                                    <span class="cart-price">
                                        Rp {{ number_format($harga, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ route('customer.keranjang.update', $item->id) }}" method="POST" class="cart-qty-form">
                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="number"
                                            name="jumlah"
                                            class="cart-qty-input"
                                            min="1"
                                            value="{{ $jumlah }}"
                                        >

                                        <button type="submit" class="cart-update-btn">
                                            Update
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <span class="cart-subtotal">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ route('customer.keranjang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')" class="m-0">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="cart-delete-btn">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FORM CHECKOUT UTAMA --}}
        <form action="{{ route('customer.keranjang.checkout') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="cart-note-input-card mt-3" id="catatanPesananBox" style="display: none;">
    <div class="cart-note-input-header">
        <div>
            <span class="cart-note-label">Opsional</span>
            <h4>Catatan Pesanan</h4>
            <p>
                Tambahkan instruksi khusus untuk admin sebelum checkout.
            </p>
        </div>
    </div>

    <div class="cart-note-textarea-wrap">
        <textarea
            name="catatan"
            id="catatan"
            class="cart-note-textarea"
            rows="4"
            maxlength="1000"
            placeholder="Contoh: Mohon dikirim sore hari, hubungi sebelum pengiriman, atau packing dibuat aman.">{{ old('catatan') }}</textarea>

        <small class="cart-note-helper">
            Catatan ini akan tampil di detail pesanan customer, admin, dan invoice.
        </small>
    </div>

    @error('catatan')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
    @enderror
</div>
        </form>

        <div class="cart-bottom">
            <div class="cart-note-card">
                <h4>Informasi Checkout</h4>
                <p>
                    Centang produk yang ingin Anda checkout. Total belanja akan otomatis dihitung berdasarkan produk yang dipilih.
                </p>
            </div>

            <div class="cart-summary-card">
                <h4>Ringkasan Belanja</h4>

                <div class="cart-summary-total">
    <span>Total Dipilih</span>

    <div class="cart-total-price">
        <span class="cart-total-currency">Rp</span>
        <span id="totalDipilih" class="cart-total-amount">0</span>
    </div>
</div>

                <div class="cart-action-row">
                    <a href="{{ route('public.produk') }}" class="cart-continue-btn">
                        Lanjut Belanja
                    </a>

                    <button type="submit" class="cart-checkout-btn" id="checkoutSelectedBtn" form="checkoutForm" disabled>
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="cart-empty">
            <h4>Keranjang masih kosong</h4>
            <p>
                Anda belum menambahkan produk ke keranjang. Silakan lihat produk terlebih dahulu.
            </p>

            <a href="{{ route('public.produk') }}" class="cart-empty-btn">
                Lihat Produk
            </a>
        </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const totalDipilih = document.getElementById('totalDipilih');
        const checkoutBtn = document.getElementById('checkoutSelectedBtn');
        const catatanPesananBox = document.getElementById('catatanPesananBox');
        const catatanTextarea = document.getElementById('catatan');

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function updateSelectedTotal() {
            let total = 0;
            let selectedCount = 0;

            itemCheckboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    total += parseInt(checkbox.dataset.subtotal || 0);
                    selectedCount++;
                }
            });

            if (totalDipilih) {
                totalDipilih.textContent = formatRupiah(total);
            }

            if (checkoutBtn) {
                checkoutBtn.disabled = selectedCount === 0;
            }

            if (catatanPesananBox) {
                catatanPesananBox.style.display = selectedCount > 0 ? 'block' : 'none';
            }

            if (selectedCount === 0 && catatanTextarea) {
                catatanTextarea.value = '';
            }

            if (checkAll) {
                checkAll.checked = itemCheckboxes.length > 0 && Array.from(itemCheckboxes).every(function (checkbox) {
                    return checkbox.checked;
                });
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                itemCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = checkAll.checked;
                });

                updateSelectedTotal();
            });
        }

        itemCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateSelectedTotal);
        });

        updateSelectedTotal();
    });
</script>
@endsection