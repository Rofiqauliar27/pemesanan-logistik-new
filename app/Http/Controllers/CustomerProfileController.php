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

    $pesanans = Pesanan::where('user_id', $user->id)
        ->latest()
        ->get();

    $pesananBelumBayar = Pesanan::where('user_id', $user->id)
        ->whereIn('payment_status', [
            'belum_bayar',
            'pending',
            'failed',
            'expire',
            'challenge',
        ])
        ->latest()
        ->get();

    return view('customer.dashboard', compact(
        'user',
        'tab',
        'pesanans',
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

        return redirect()
            ->route('customer.profile', ['tab' => 'profil'])
            ->with('success', 'Data customer berhasil diperbarui.');
    }
}