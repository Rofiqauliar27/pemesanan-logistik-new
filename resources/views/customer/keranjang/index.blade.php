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
                                <input type="checkbox" id="checkAll" class="cart-check">
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
                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        name="keranjang_ids[]"
                                        value="{{ $item->id }}"
                                        class="item-checkbox cart-check"
                                        data-subtotal="{{ $item->barang->harga * $item->jumlah }}"
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
                                        <strong>{{ $item->barang->nama_barang }}</strong>
                                        <small>{{ $item->barang->kategori ?? 'Produk' }}</small>
                                    </div>
                                </td>

                                <td>
                                    <span class="cart-price">
                                        Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
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
                                            value="{{ $item->jumlah }}"
                                        >

                                        <button type="submit" class="cart-update-btn">
                                            Update
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <span class="cart-subtotal">
                                        Rp {{ number_format($item->barang->harga * $item->jumlah, 0, ',', '.') }}
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

        <form action="{{ route('customer.keranjang.checkout') }}" method="POST" id="checkoutForm">
            @csrf
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

                <div class="cart-total-box">
                    <span>Total Dipilih</span>
                    <strong>Rp <span id="selectedTotal">0</span></strong>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedTotal = document.getElementById('selectedTotal');
        const checkoutBtn = document.getElementById('checkoutSelectedBtn');

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function updateSelectedTotal() {
            let total = 0;
            let selectedCount = 0;

            itemCheckboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    total += parseInt(checkbox.dataset.subtotal);
                    selectedCount++;
                }
            });

            if (selectedTotal) {
                selectedTotal.textContent = formatRupiah(total);
            }

            if (checkoutBtn) {
                checkoutBtn.disabled = selectedCount === 0;
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
            checkbox.addEventListener('change', function () {
                if (!checkbox.checked && checkAll) {
                    checkAll.checked = false;
                }

                const allChecked = Array.from(itemCheckboxes).every(function (item) {
                    return item.checked;
                });

                if (checkAll) {
                    checkAll.checked = allChecked;
                }

                updateSelectedTotal();
            });
        });

        updateSelectedTotal();
    });
</script>
@endsection