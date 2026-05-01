<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'profil');

        $user = Auth::user();

        $pesanans = Pesanan::with('barang')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $pesananBelumBayar = Pesanan::with('barang')
            ->where('user_id', $user->id)
            ->whereIn('payment_status', ['belum_bayar', 'menunggu_pembayaran', 'gagal', 'challenge'])
            ->latest()
            ->get();

        return view('customer.profile.index', compact(
            'user',
            'tab',
            'pesanans',
            'pesananBelumBayar'
        ));
    }
}