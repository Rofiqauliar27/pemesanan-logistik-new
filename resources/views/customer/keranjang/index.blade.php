@extends('layouts.public')

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
        <form action="{{ route('customer.keranjang.checkout') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="cart-card">
                <div class="table-responsive">
                    <table class="table cart-table align-middle mb-0">
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
                                        <div class="cart-qty-action">
                                            <input
                                                type="number"
                                                name="jumlah_display_{{ $item->id }}"
                                                class="cart-qty-input"
                                                min="1"
                                                value="{{ $jumlah }}"
                                                data-update-url="{{ route('customer.keranjang.update', $item->id) }}"
                                            >

                                            <button
                                                type="button"
                                                class="cart-update-btn js-update-cart"
                                                data-item-id="{{ $item->id }}"
                                                data-update-url="{{ route('customer.keranjang.update', $item->id) }}"
                                            >
                                                Update
                                            </button>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="cart-subtotal">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <button
                                            type="submit"
                                            formaction="{{ route('customer.keranjang.destroy', $item->id) }}"
                                            formmethod="POST"
                                            name="_method"
                                            value="DELETE"
                                            onclick="return confirm('Hapus item ini dari keranjang?')"
                                            class="cart-delete-btn"
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cart-bottom">
                <div class="cart-left-column">

                    <div class="cart-note-input-card" id="catatanPesananBox" style="display: none;">
                        <div class="cart-note-input-header">
                            <h4>Catatan Pesanan</h4>
                            <p>
                                Tambahkan instruksi khusus untuk admin sebelum checkout.
                            </p>
                        </div>

                        <textarea
                            name="catatan"
                            id="catatan"
                            class="cart-note-textarea"
                            rows="4"
                            maxlength="1000"
                            placeholder="Contoh: Mohon dikirim sore hari, hubungi sebelum pengiriman, atau packing dibuat aman."
                        >{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <div class="text-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
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

                        <button type="submit" class="cart-checkout-btn" id="checkoutSelectedBtn" disabled>
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @foreach($keranjangs as $item)
            <form
                action="{{ route('customer.keranjang.update', $item->id) }}"
                method="POST"
                id="updateCartForm{{ $item->id }}"
                class="d-none"
            >
                @csrf
                @method('PUT')
                <input type="hidden" name="jumlah" id="updateJumlah{{ $item->id }}" value="{{ $item->jumlah }}">
            </form>
        @endforeach
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
        const updateButtons = document.querySelectorAll('.js-update-cart');

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

        updateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const itemId = button.dataset.itemId;
                const row = button.closest('tr');
                const qtyInput = row ? row.querySelector('.cart-qty-input') : null;
                const hiddenQty = document.getElementById('updateJumlah' + itemId);
                const updateForm = document.getElementById('updateCartForm' + itemId);

                if (qtyInput && hiddenQty && updateForm) {
                    hiddenQty.value = qtyInput.value;
                    updateForm.submit();
                }
            });
        });

        updateSelectedTotal();
    });
</script>
@endsection