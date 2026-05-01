<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['barang', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.pesanan.index', compact('pesanans'));
    }

    public function create($barang_id)
    {
        $barang = Barang::findOrFail($barang_id);
        return view('customer.pesanan.create', compact('barang'));
    }

    public function store(Request $request)
{
    $request->validate([
        'barang_id' => 'required|exists:barangs,id',
        'jumlah' => 'required|integer|min:1',
        'catatan' => 'nullable',
    ]);

    $barang = Barang::findOrFail($request->barang_id);
    $total_harga = $barang->harga * $request->jumlah;

    $orderId = 'ORDER-' . time() . '-' . rand(100, 999);

    $pesanan = Pesanan::create([
        'user_id' => Auth::id(),
        'barang_id' => $barang->id,
        'jumlah' => $request->jumlah,
        'total_harga' => $total_harga,
        'status' => 'pending',
        'stok_dikurangi' => false,
        'catatan' => $request->catatan,
        'order_id' => $orderId,
        'payment_status' => 'belum_bayar',
    ]);

    $this->midtransConfig();

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => (int) $total_harga,
        ],
        'customer_details' => [
            'first_name' => Auth::user()->name,
'email' => Auth::user()->email,
        ],
        'item_details' => [[
            'id' => $barang->id,
            'price' => (int) $barang->harga,
            'quantity' => (int) $request->jumlah,
            'name' => $barang->nama_barang,
        ]],
    ];

    $snapToken = Snap::getSnapToken($params);

    $pesanan->update([
        'snap_token' => $snapToken,
    ]);

    return redirect()->route('customer.pesanan.showBayar', $pesanan->id)
        ->with('success', 'Pesanan berhasil dibuat. Silakan lanjut bayar.');
}

    public function adminIndex(Request $request)
{
    $query = Pesanan::with(['barang', 'user']);

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($q2) use ($search) {
                $q2->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('barang', function ($q3) use ($search) {
                $q3->where('nama_barang', 'like', '%' . $search . '%');
            });
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('payment_status')) {
    $query->where('payment_status', $request->payment_status);
}

    $pesanans = $query->latest()->get();

    return view('admin.pesanan.index', compact('pesanans'));
}

    public function editStatus($id)
    {
        $pesanan = Pesanan::with(['barang', 'user'])->findOrFail($id);
        return view('admin.pesanan.edit-status', compact('pesanan'));
    }

    public function show($id)
{
    $pesanan = Pesanan::with(['barang', 'user'])->findOrFail($id);

    return view('admin.pesanan.show', compact('pesanan'));
}

public function invoice($id)
{
    $pesanan = Pesanan::with(['barang', 'user'])->findOrFail($id);

    return view('admin.pesanan.invoice', compact('pesanan'));
}
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,diproses,dikirim,selesai',
    ]);

    $pesanan = Pesanan::with('barang')->findOrFail($id);

    // Kalau belum bayar, admin tidak boleh ubah ke diproses/dikirim/selesai
    if (
        $pesanan->payment_status !== 'sudah_bayar' &&
        in_array($request->status, ['diproses', 'dikirim', 'selesai'])
    ) {
        return redirect()->back()->with(
            'error',
            'Pesanan belum dibayar. Admin hanya bisa memproses pesanan yang sudah lunas.'
        );
    }

    // Saat status diubah ke diproses, stok dikurangi sekali saja
    if ($request->status == 'diproses' && $pesanan->stok_dikurangi == false) {
        if ($pesanan->barang->stok < $pesanan->jumlah) {
            return redirect()->back()->with(
                'error',
                'Stok barang tidak cukup untuk memproses pesanan ini'
            );
        }

        $pesanan->barang->update([
            'stok' => $pesanan->barang->stok - $pesanan->jumlah
        ]);

        $pesanan->stok_dikurangi = true;
    }

    $pesanan->status = $request->status;
    $pesanan->save();

    return redirect()->route('admin.pesanan.index')->with(
        'success',
        'Status pesanan berhasil diupdate'
    );
}
public function laporan(Request $request)
{
    $query = Pesanan::with(['barang', 'user']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }

    if ($request->filled('tanggal_awal')) {
        $query->whereDate('created_at', '>=', $request->tanggal_awal);
    }

    if ($request->filled('tanggal_akhir')) {
        $query->whereDate('created_at', '<=', $request->tanggal_akhir);
    }

    $pesanans = $query->latest()->get();

    $totalPesanan = $pesanans->count();
    $totalPendapatan = $pesanans->where('payment_status', 'sudah_bayar')->sum('total_harga');

    return view('admin.pesanan.laporan', compact(
        'pesanans',
        'totalPesanan',
        'totalPendapatan'
    ));
}
public function printLaporan(Request $request)
{
    $query = Pesanan::with(['barang', 'user']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }

    if ($request->filled('tanggal_awal')) {
        $query->whereDate('created_at', '>=', $request->tanggal_awal);
    }

    if ($request->filled('tanggal_akhir')) {
        $query->whereDate('created_at', '<=', $request->tanggal_akhir);
    }

    $pesanans = $query->latest()->get();

    $totalPesanan = $pesanans->count();
    $totalPendapatan = $pesanans->where('payment_status', 'sudah_bayar')->sum('total_harga');

    return view('admin.pesanan.print', compact(
        'pesanans',
        'totalPesanan',
        'totalPendapatan'
    ));
}
 public function showBayar($id)
{
    $pesanan = Pesanan::with('barang')
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    return view('customer.pesanan.bayar', compact('pesanan'));
}

private function midtransConfig()
{
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = config('midtrans.is_sanitized');
    Config::$is3ds = config('midtrans.is_3ds');
}

public function notificationHandler(Request $request)
{
    Log::info('Midtrans notification masuk', $request->all());

    $serverKey = config('midtrans.server_key');

    $signatureKey = hash(
        'sha512',
        $request->order_id .
        $request->status_code .
        $request->gross_amount .
        $serverKey
    );

    if ($signatureKey !== $request->signature_key) {
        Log::warning('Signature Midtrans tidak valid', $request->all());

        return response()->json([
            'message' => 'Signature tidak valid'
        ], 403);
    }

    $orderId = $request->order_id;

    $pesanans = Pesanan::where('order_id', $orderId)
        ->orWhere('group_order_id', $orderId)
        ->get();

    if ($pesanans->isEmpty()) {
        Log::warning('Midtrans notification masuk, tapi pesanan tidak ditemukan', [
            'order_id' => $orderId,
            'payload' => $request->all(),
        ]);

        return response()->json([
            'message' => 'Notification diterima, tapi pesanan tidak ditemukan',
            'order_id' => $orderId,
        ], 200);
    }

    $transactionStatus = $request->transaction_status;
    $paymentType = $request->payment_type ?? null;
    $fraudStatus = $request->fraud_status ?? null;

    $paymentStatus = 'menunggu_pembayaran';

    if ($transactionStatus == 'capture') {
        if ($fraudStatus == 'accept') {
            $paymentStatus = 'sudah_bayar';
        } else {
            $paymentStatus = 'challenge';
        }
    } elseif ($transactionStatus == 'settlement') {
        $paymentStatus = 'sudah_bayar';
    } elseif ($transactionStatus == 'pending') {
        $paymentStatus = 'menunggu_pembayaran';
    } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
        $paymentStatus = 'gagal';
    }

    foreach ($pesanans as $pesanan) {
        $pesanan->payment_status = $paymentStatus;
        $pesanan->transaction_status = $transactionStatus;
        $pesanan->payment_type = $paymentType;
        $pesanan->save();
    }

    Log::info('Pesanan berhasil diupdate dari webhook Midtrans', [
        'order_id_midtrans' => $orderId,
        'jumlah_pesanan_diupdate' => $pesanans->count(),
        'payment_status' => $paymentStatus,
        'transaction_status' => $transactionStatus,
        'payment_type' => $paymentType,
    ]);

    return response()->json([
        'message' => 'Notification berhasil diproses'
    ], 200);
}
}