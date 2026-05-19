<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tab = $request->get('tab', 'profil');

        if ($tab === 'pembayaran') {
            return redirect()->route('customer.profile', [
                'tab' => 'pesanan',
            ]);
        }

        $filter = $request->get('filter', 'semua');

        Pesanan::where('user_id', $user->id)
            ->whereNotIn('payment_status', [
                'sudah_bayar',
                'settlement',
                'paid',
                'capture',
            ])
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->where('status', '!=', 'dibatalkan')
            ->update([
                'status' => 'dibatalkan',
                'payment_status' => 'expire',
                'transaction_status' => 'expire',
            ]);

        $pesananQuery = Pesanan::with('barang')
            ->where('user_id', $user->id);

        if ($filter === 'belum_bayar') {
            $pesananQuery->where('payment_status', 'belum_bayar');
        } elseif ($filter === 'menunggu') {
            $pesananQuery->whereIn('payment_status', [
                'pending',
                'challenge',
            ]);
        } elseif ($filter === 'gagal') {
            $pesananQuery->whereIn('payment_status', [
                'failed',
                'gagal',
                'expire',
            ]);
        } elseif ($filter === 'diproses') {
            $pesananQuery->where('status', 'diproses');
        } elseif ($filter === 'selesai') {
            $pesananQuery->where('status', 'selesai');
        } elseif ($filter === 'dibatalkan') {
            $pesananQuery->where('status', 'dibatalkan');
        }

        $pesanans = $pesananQuery
            ->latest()
            ->get();

        $semuaPesanan = Pesanan::with('barang')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $pesananBelumBayar = Pesanan::with('barang')
            ->where('user_id', $user->id)
            ->whereIn('payment_status', [
                'belum_bayar',
                'pending',
                'failed',
                'gagal',
                'expire',
                'challenge',
            ])
            ->latest()
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'tab',
            'filter',
            'pesanans',
            'semuaPesanan',
            'pesananBelumBayar'
        ));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('customer.profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat_lengkap' => ['nullable', 'string', 'max:1000'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kelurahan' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:20'],
            'google_maps_link' => ['nullable', 'url', 'max:1000'],
        ]);

        $user->update($validated);

if ($request->filled('redirect')) {
    return redirect($request->redirect)
        ->with('success', 'Data customer berhasil diperbarui. Silakan lanjutkan pembayaran.');
}

return redirect()
    ->route('customer.profile', ['tab' => 'profil'])
    ->with('success', 'Data customer berhasil diperbarui.');
    }
}