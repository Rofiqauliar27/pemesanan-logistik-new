<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['barang', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->groupBy(function ($item) {
                return $item->group_order_id ?? $item->order_id ?? $item->id;
            });

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

        $user = Auth::user();

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

        if (!$alamatLengkapDiisi) {
            return redirect()
                ->route('customer.profile.edit', [
                    'redirect' => url()->previous(),
                ])
                ->with('error', 'Lengkapi nama, email, nomor telepon, dan alamat pengiriman terlebih dahulu sebelum membuat pesanan.');
        }

        $barang = Barang::findOrFail($request->barang_id);
        $totalHarga = $barang->harga * $request->jumlah;

        $orderId = $this->generateOrderCode();
        $groupOrderId = $orderId;
        $expiredAt = now()->addHours(24);

        $this->midtransConfig();

        DB::beginTransaction();

        try {
            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah,
                'total_harga' => $totalHarga,
                'status' => 'pending',
                'stok_dikurangi' => false,
                'catatan' => $request->catatan,
                'order_id' => $orderId,
                'group_order_id' => $groupOrderId,
                'payment_status' => 'belum_bayar',
                'payment_type' => null,
                'transaction_status' => null,
                'snap_token' => null,
                'paid_at' => null,
                'expired_at' => $expiredAt,
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $groupOrderId,
                    'gross_amount' => (int) $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->telepon ?? '',
                    'shipping_address' => [
                        'first_name' => $user->name,
                        'phone' => $user->telepon ?? '',
                        'address' => $user->alamat_lengkap ?? '',
                        'city' => $user->kabupaten ?? '',
                        'postal_code' => $user->kode_pos ?? '',
                        'country_code' => 'IDN',
                    ],
                ],
                'item_details' => [[
                    'id' => (string) $barang->id,
                    'price' => (int) $barang->harga,
                    'quantity' => (int) $request->jumlah,
                    'name' => $barang->nama_barang,
                ]],
            ];

            $snapToken = Snap::getSnapToken($params);

            $pesanan->update([
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            return redirect()->route('customer.pesanan.showBayar', $pesanan->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Pesanan gagal dibuat: ' . $e->getMessage());
        }
    }

    public function adminIndex(Request $request)
    {
        $this->batalkanPesananExpired();

        $query = Pesanan::with(['barang', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', '%' . $search . '%')
                    ->orWhere('group_order_id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('barang', function ($q3) use ($search) {
                        $q3->where('nama_barang', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
    if ($request->payment_status === 'lunas') {
        $query->whereIn('payment_status', [
            'sudah_bayar',
            'settlement',
            'paid',
            'capture',
        ]);
    } elseif ($request->payment_status === 'gagal') {
        $query->whereIn('payment_status', [
            'failed',
            'gagal',
            'expire',
        ]);
    } else {
        $query->where('payment_status', $request->payment_status);
    }
}
        $pesananItems = $query->latest()->get();

        $pesananGroups = $pesananItems->groupBy(function ($item) {
            return $item->group_order_id ?? $item->order_id ?? $item->id;
        })->map(function ($items) {
            $utama = $items->first();

            $utama->items = $items;
            $utama->total_barang = $items->count();
            $utama->total_jumlah = $items->sum('jumlah');
            $utama->total_grup = $items->sum('total_harga');

            return $utama;
        })->values();

        $pesanans = $pesananGroups;

        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function editStatus($id)
    {
        $pesanan = Pesanan::with(['barang', 'user'])->findOrFail($id);

        $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id;

        $pesananItems = Pesanan::with(['barang', 'user'])
            ->where(function ($query) use ($groupOrderId, $pesanan) {
                $query->where('group_order_id', $groupOrderId)
                    ->orWhere('order_id', $pesanan->order_id);
            })
            ->get();

        return view('admin.pesanan.edit-status', compact('pesanan', 'pesananItems'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['barang', 'user'])->findOrFail($id);

        $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id;

        $pesananItems = Pesanan::with(['barang', 'user'])
            ->where(function ($query) use ($groupOrderId, $pesanan) {
                $query->where('group_order_id', $groupOrderId)
                    ->orWhere('order_id', $pesanan->order_id);
            })
            ->get();

        $totalGrup = $pesananItems->sum('total_harga');
        $totalJumlah = $pesananItems->sum('jumlah');

        return view('admin.pesanan.show', compact(
            'pesanan',
            'pesananItems',
            'totalGrup',
            'totalJumlah'
        ));
    }

    public function invoice($id)
    {
        $pesanan = Pesanan::with(['barang', 'user'])->findOrFail($id);

        $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id;

        $pesananItems = Pesanan::with(['barang', 'user'])
            ->where(function ($query) use ($groupOrderId, $pesanan) {
                $query->where('group_order_id', $groupOrderId)
                    ->orWhere('order_id', $pesanan->order_id);
            })
            ->get();

        $totalGrup = $pesananItems->sum('total_harga');
        $totalJumlah = $pesananItems->sum('jumlah');

        return view('admin.pesanan.invoice', compact(
            'pesanan',
            'pesananItems',
            'totalGrup',
            'totalJumlah'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
           'status' => 'required|in:pending,diproses,dikirim,selesai',
        ]);

        $pesanan = Pesanan::with('barang')->findOrFail($id);

        $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id;

        $pesananItems = Pesanan::with('barang')
            ->where(function ($query) use ($groupOrderId, $pesanan) {
                $query->where('group_order_id', $groupOrderId)
                    ->orWhere('order_id', $pesanan->order_id);
            })
            ->get();

        $paymentStatusLunas = [
            'sudah_bayar',
            'settlement',
            'paid',
            'capture',
        ];

        if (
            !in_array($pesanan->payment_status, $paymentStatusLunas) &&
            in_array($request->status, ['diproses', 'dikirim', 'selesai'])
        ) {
            return redirect()->back()->with(
                'error',
                'Pesanan belum dibayar. Admin hanya bisa memproses pesanan yang sudah lunas.'
            );
        }

        DB::beginTransaction();

        try {
            Pesanan::whereIn('id', $pesananItems->pluck('id'))->update([
                'status' => $request->status,
            ]);

            DB::commit();

           return redirect()
    ->route('admin.pesanan.show', $id)
    ->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Status pesanan gagal diupdate: ' . $e->getMessage()
            );
        }
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

        $pesananItems = $query->latest()->get();

        $pesanans = $pesananItems->groupBy(function ($item) {
            return $item->group_order_id ?? $item->order_id ?? $item->id;
        })->map(function ($items) {
            $utama = $items->first();

            $utama->items = $items;
            $utama->total_barang = $items->count();
            $utama->total_jumlah = $items->sum('jumlah');
            $utama->total_grup = $items->sum('total_harga');

            return $utama;
        })->values();

        $totalPesanan = $pesanans->count();
       $totalPendapatan = $pesanans
    ->filter(function ($item) {
        return in_array($item->payment_status, [
            'sudah_bayar',
            'settlement',
            'paid',
            'capture'
        ])
        && $item->status !== 'refund_success';
    })
    ->sum('total_grup');

    $totalRefund = $pesanans
    ->filter(function ($item) {
        return $item->status === 'refund_success';
    })
    ->sum('total_grup');

        return view('admin.pesanan.laporan', compact(
    'pesanans',
    'totalPesanan',
    'totalPendapatan',
    'totalRefund'
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

        $pesananItems = $query->latest()->get();

        $pesanans = $pesananItems->groupBy(function ($item) {
            return $item->group_order_id ?? $item->order_id ?? $item->id;
        })->map(function ($items) {
            $utama = $items->first();

            $utama->items = $items;
            $utama->total_barang = $items->count();
            $utama->total_jumlah = $items->sum('jumlah');
            $utama->total_grup = $items->sum('total_harga');

            return $utama;
        })->values();

        $totalPesanan = $pesanans->count();
       $totalPendapatan = $pesanans
    ->filter(function ($item) {
        return in_array($item->payment_status, [
            'sudah_bayar',
            'settlement',
            'paid',
            'capture'
        ])
        && $item->status !== 'refund_success';
    })
    ->sum('total_grup');

    $totalRefund = $pesanans
    ->filter(function ($item) {
        return $item->status === 'refund_success';
    })
    ->sum('total_grup');
       return view('admin.pesanan.print', compact(
    'pesanans',
    'totalPesanan',
    'totalPendapatan',
    'totalRefund'
));
    }

    public function showBayar($id)
    {
        $pesanan = Pesanan::with(['barang', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id;

        $pesanans = Pesanan::with('barang')
            ->where('user_id', Auth::id())
            ->where(function ($query) use ($groupOrderId, $pesanan) {
                $query->where('group_order_id', $groupOrderId)
                    ->orWhere('order_id', $pesanan->order_id);
            })
            ->get();

        $pesananUtama = $pesanans->first();

        if (
            $pesananUtama->expired_at &&
            now()->greaterThan($pesananUtama->expired_at) &&
            !in_array($pesananUtama->payment_status, ['sudah_bayar', 'settlement', 'paid', 'capture'])
        ) {
            Pesanan::whereIn('id', $pesanans->pluck('id'))->update([
                'status' => 'dibatalkan',
                'payment_status' => 'expire',
                'transaction_status' => 'expire',
            ]);
        }

        $total = $pesanans->sum('total_harga');
        $snapToken = $pesananUtama->snap_token;

        return view('customer.pesanan.bayar', compact(
            'pesanan',
            'pesanans',
            'pesananUtama',
            'groupOrderId',
            'total',
            'snapToken'
        ));
    }

    private function midtransConfig()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
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
            'message' => 'Signature tidak valid',
        ], 403);
    }

    $orderId = $request->order_id;

    $pesanans = Pesanan::where('group_order_id', $orderId)
        ->orWhere('order_id', $orderId)
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

    $paymentStatus = 'belum_bayar';
    $paidAt = null;

    if ($transactionStatus === 'capture') {
        if ($fraudStatus === 'accept') {
            $paymentStatus = 'sudah_bayar';
            $paidAt = now();
        } else {
            $paymentStatus = 'challenge';
        }
    } elseif ($transactionStatus === 'settlement') {
        $paymentStatus = 'sudah_bayar';
        $paidAt = now();
    } elseif ($transactionStatus === 'pending') {
        $paymentStatus = 'pending';
    } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
        $paymentStatus = 'failed';
    } elseif ($transactionStatus === 'expire') {
        $paymentStatus = 'expire';
    }

    $statusLunas = [
        'sudah_bayar',
        'settlement',
        'paid',
        'capture',
    ];

    DB::beginTransaction();

    try {
        $pesanansFresh = Pesanan::with('barang')
            ->whereIn('id', $pesanans->pluck('id'))
            ->lockForUpdate()
            ->get();

        foreach ($pesanansFresh as $item) {
            $statusSebelumnyaLunas = in_array($item->payment_status, $statusLunas);
            $statusBaruLunas = $paymentStatus === 'sudah_bayar';

            $updateData = [
                'payment_status' => $paymentStatus,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
            ];

            if ($paidAt && empty($item->paid_at)) {
                $updateData['paid_at'] = $paidAt;
            }

            if (in_array($paymentStatus, ['failed', 'expire'])) {
                $updateData['status'] = 'dibatalkan';
            }

            /*
             * Kalau pesanan lama sudah lunas, tapi stok_dikurangi masih 0,
             * jangan potong stok lagi. Cukup tandai agar webhook lama tidak
             * mengurangi stok saat masuk ulang.
             */
            if ($statusSebelumnyaLunas && !$item->stok_dikurangi) {
                $updateData['stok_dikurangi'] = true;
            }

            /*
             * Stok hanya dikurangi kalau:
             * 1. status baru lunas
             * 2. status sebelumnya belum lunas
             * 3. stok belum pernah dikurangi
             */
            if (
                $statusBaruLunas &&
                !$statusSebelumnyaLunas &&
                !$item->stok_dikurangi
            ) {
                if (!$item->barang) {
                    Log::warning('Barang tidak ditemukan saat pembayaran berhasil', [
                        'pesanan_id' => $item->id,
                        'barang_id' => $item->barang_id,
                    ]);

                    $item->update($updateData);
                    continue;
                }

                if ($item->barang->stok < $item->jumlah) {
                    Log::warning('Stok tidak cukup saat pembayaran berhasil', [
                        'pesanan_id' => $item->id,
                        'barang_id' => $item->barang_id,
                        'stok' => $item->barang->stok,
                        'jumlah' => $item->jumlah,
                    ]);

                    $item->update($updateData);
                    continue;
                }

                DB::table('barangs')
                    ->where('id', $item->barang_id)
                    ->decrement('stok', $item->jumlah);

                $updateData['stok_dikurangi'] = true;
            }

            $item->update($updateData);
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Gagal memproses notifikasi Midtrans', [
            'error' => $e->getMessage(),
            'order_id' => $orderId,
        ]);

        return response()->json([
            'message' => 'Gagal memproses notification',
        ], 500);
    }

    Log::info('Pesanan grup berhasil diupdate dari webhook Midtrans', [
        'order_id_midtrans' => $orderId,
        'jumlah_pesanan_diupdate' => $pesanans->count(),
        'payment_status' => $paymentStatus,
        'transaction_status' => $transactionStatus,
        'payment_type' => $paymentType,
        'paid_at' => $paidAt,
    ]);

    return response()->json([
        'message' => 'Notification berhasil diproses',
    ], 200);
}

    private function batalkanPesananExpired()
    {
        Pesanan::whereNotIn('payment_status', ['sudah_bayar', 'settlement', 'paid', 'capture'])
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->where('status', '!=', 'dibatalkan')
            ->update([
                'status' => 'dibatalkan',
                'payment_status' => 'expire',
                'transaction_status' => 'expire',
            ]);
    }

    private function generateOrderCode()
    {
        do {
            $code = 'ORDER-' . mt_rand(10000, 99999);
        } while (
            Pesanan::where('order_id', $code)
                ->orWhere('group_order_id', $code)
                ->exists()
        );

        return $code;
    }

    public function cancel(Request $request, $id)
{
    $request->validate([
        'cancel_reason' => 'required|string|max:500',

        'refund_bank' => 'required|string',

        'refund_account_number' => 'required|string|max:50',

        'refund_account_name' => 'required|string|max:100',
    ]);

    $pesanan = Pesanan::findOrFail($id);

    if ($pesanan->user_id != auth()->id()) {
        abort(403);
    }

    $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id;

   Pesanan::where(function ($query) use ($groupOrderId, $pesanan) {
    $query->where('group_order_id', $groupOrderId)
          ->orWhere('order_id', $pesanan->order_id);
})->update([
    'status' => 'cancel_request',

    'cancel_reason' => $request->cancel_reason,

    'refund_bank' => $request->refund_bank,

    'refund_account_number' => $request->refund_account_number,

    'refund_account_name' => $request->refund_account_name,
]);

    return redirect()
        ->route('customer.profile', ['tab' => 'pesanan'])
        ->with('success', 'Permintaan pembatalan berhasil dikirim.');
}

public function refund($id)
{
    $pesanan = Pesanan::findOrFail($id);

    $groupOrderId = $pesanan->group_order_id ?? $pesanan->order_id;

    Pesanan::where(function ($query) use ($groupOrderId, $pesanan) {
        $query->where('group_order_id', $groupOrderId)
              ->orWhere('order_id', $pesanan->order_id);
    })->update([
        'status' => 'refund_success'
    ]);

    return redirect()
        ->back()
        ->with('success', 'Refund berhasil diproses.');
}
}