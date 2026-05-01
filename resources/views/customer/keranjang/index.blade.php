@extends('layouts.customer')

@section('title', 'Keranjang Saya')

@section('content')
<div class="market-box">
    <h2 class="mb-1">Keranjang Saya</h2>
    <p class="text-muted mb-0">
        Kelola barang yang ingin Anda beli sebelum checkout.
    </p>
</div>

<div class="market-box">
    @if($keranjangs->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th width="60">No</th>
                        <th>Barang</th>
                        <th width="180">Harga</th>
                        <th width="190">Jumlah</th>
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
                                    class="item-checkbox"
                                    data-subtotal="{{ $item->barang->harga * $item->jumlah }}"
                                    form="checkoutForm"
                                >
                            </td>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->barang->nama_barang }}</td>

                            <td>
                                Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                            </td>

                            <td width="190">
                                <form action="{{ route('customer.keranjang.update', $item->id) }}" method="POST" class="cart-qty-form">
                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="number"
                                        name="jumlah"
                                        class="form-control form-control-sm cart-qty-input"
                                        min="1"
                                        value="{{ $item->jumlah }}"
                                    >

                                    <button type="submit" class="btn btn-sm btn-primary cart-update-btn">
                                        Update
                                    </button>
                                </form>
                            </td>

                            <td>
                                Rp {{ number_format($item->barang->harga * $item->jumlah, 0, ',', '.') }}
                            </td>

                            <td>
                                <form action="{{ route('customer.keranjang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('customer.keranjang.checkout') }}" method="POST" id="checkoutForm">
            @csrf
        </form>

        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-1">
                    Total Dipilih: Rp <span id="selectedTotal">0</span>
                </h4>

                <small class="text-muted">
                    Centang barang yang ingin Anda checkout.
                </small>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('public.produk') }}" class="btn btn-outline-secondary">
                    Lanjut Belanja
                </a>

                <button type="submit" class="btn btn-success" id="checkoutSelectedBtn" form="checkoutForm" disabled>
                    Checkout Terpilih
                </button>
            </div>
        </div>
    @else
        <div class="alert alert-info mb-0">
            Keranjang masih kosong.
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