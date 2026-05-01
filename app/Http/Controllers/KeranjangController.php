<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::with('barang')
            ->where('user_id', Auth::id())
            ->get();
            

        $total = $keranjangs->sum(function ($item) {
            return $item->barang->harga * $item->jumlah;
        });

        return view('customer.keranjang.index', compact('keranjangs', 'total'));
    }

    public function store(Request $request, $barangId)
{
    $request->validate([
        'jumlah' => 'required|integer|min:1',
    ]);

    $barang = Barang::findOrFail($barangId);

    $keranjang = Keranjang::where('user_id', Auth::id())
        ->where('barang_id', $barang->id)
        ->first();

    if ($keranjang) {
        $keranjang->jumlah += (int) $request->jumlah;
        $keranjang->save();
    } else {
        Keranjang::create([
            'user_id' => Auth::id(),
            'barang_id' => $barang->id,
            'jumlah' => (int) $request->jumlah,
        ]);
    }

    $cartCount = Keranjang::where('user_id', Auth::id())->sum('jumlah');

    if ($request->ajax() || $request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke keranjang.',
            'cart_count' => $cartCount,
        ]);
    }

    return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang.');
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $keranjang = Keranjang::where('user_id', Auth::id())->findOrFail($id);
        $keranjang->update([
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('customer.keranjang.index')->with('success', 'Jumlah keranjang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keranjang = Keranjang::where('user_id', Auth::id())->findOrFail($id);
        $keranjang->delete();

        return redirect()->route('customer.keranjang.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request)
{
    $request->validate([
        'keranjang_ids' => 'required|array',
        'keranjang_ids.*' => 'exists:keranjangs,id',
    ]);
    
    $keranjangs = Keranjang::with('barang')
        ->where('user_id', Auth::id())
        ->whereIn('id', $request->keranjang_ids)
        ->get();

    if ($keranjangs->isEmpty()) {
        return redirect()->route('customer.keranjang.index')
            ->with('error', 'Pilih minimal satu item untuk checkout.');
    }

    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    DB::beginTransaction();

    try {
        $groupOrderId = 'GROUP-' . time() . '-' . rand(100, 999);

        $grossAmount = 0;
        $itemDetails = [];
        $createdPesananIds = [];

        foreach ($keranjangs as $item) {
            $subtotal = $item->barang->harga * $item->jumlah;
            $grossAmount += $subtotal;

            $orderId = 'ORDER-' . time() . '-' . rand(1000, 9999);

            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'barang_id' => $item->barang_id,
                'jumlah' => $item->jumlah,
                'total_harga' => $subtotal,
                'status' => 'pending',
                'stok_dikurangi' => false,
                'catatan' => 'Checkout dari keranjang',
                'order_id' => $orderId,
                'group_order_id' => $groupOrderId,
                'payment_status' => 'belum_bayar',
            ]);

            $createdPesananIds[] = $pesanan->id;

            $itemDetails[] = [
                'id' => $item->barang->id,
                'price' => (int) $item->barang->harga,
                'quantity' => (int) $item->jumlah,
                'name' => $item->barang->nama_barang,
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $groupOrderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        Pesanan::whereIn('id', $createdPesananIds)->update([
            'snap_token' => $snapToken,
        ]);

        Keranjang::where('user_id', Auth::id())
        ->whereIn('id', $request->keranjang_ids)
        ->delete();

        DB::commit();

        return redirect()->route('customer.keranjang.bayar', $groupOrderId)
            ->with('success', 'Checkout berhasil. Silakan lanjut ke pembayaran.');
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->route('customer.keranjang.index')
            ->with('error', 'Checkout gagal: ' . $e->getMessage());
    }
}

public function bayarKeranjang($groupOrderId)
{
    $pesanans = Pesanan::with('barang')
        ->where('user_id', Auth::id())
        ->where('group_order_id', $groupOrderId)
        ->get();

    if ($pesanans->isEmpty()) {
        return redirect()->route('customer.keranjang.index')
            ->with('error', 'Data checkout tidak ditemukan.');
    }

    $total = $pesanans->sum('total_harga');
    $snapToken = $pesanans->first()->snap_token;

    return view('customer.keranjang.bayar', compact('pesanans', 'total', 'groupOrderId', 'snapToken'));
}
}