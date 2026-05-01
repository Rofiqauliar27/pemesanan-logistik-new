<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProfilPerusahaanController extends Controller
{
    public function edit()
    {
        $profil = ProfilPerusahaan::first();

        if (!$profil) {
            $profil = ProfilPerusahaan::create([
                'nama_perusahaan' => 'CV Bintang Saida Teknik',
                'bidang_usaha' => 'Logistik dan kebutuhan perkapalan',
                'deskripsi' => 'Perusahaan yang bergerak dalam bidang logistik perkapalan dan pengelolaan pemesanan barang.',
                'alamat' => '-',
                'telepon' => '-',
                'email' => '-',
                'visi' => 'Menjadi perusahaan logistik perkapalan yang terpercaya dan profesional.',
                'misi' => "Meningkatkan kualitas layanan.\nMempermudah pemesanan online.\nMenyediakan informasi transaksi yang jelas.",
                'logo' => null,
            ]);
        }

        return view('admin.profil-perusahaan.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|max:255',
            'bidang_usaha' => 'nullable|max:255',
            'deskripsi' => 'nullable',
            'alamat' => 'nullable',
            'telepon' => 'nullable|max:50',
            'email' => 'nullable|email|max:255',
            'visi' => 'nullable',
            'misi' => 'nullable',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profil = ProfilPerusahaan::first();

        if (!$profil) {
            $profil = new ProfilPerusahaan();
        }

        $pathLogo = $profil->logo;

        if ($request->hasFile('logo')) {
            if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                Storage::disk('public')->delete($profil->logo);
            }

            $pathLogo = $request->file('logo')->store('profil-perusahaan', 'public');
        }

        $profil->updateOrCreate(
            ['id' => $profil->id ?? null],
            [
                'nama_perusahaan' => $request->nama_perusahaan,
                'bidang_usaha' => $request->bidang_usaha,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'visi' => $request->visi,
                'misi' => $request->misi,
                'logo' => $pathLogo,
            ]
        );

        return redirect()->route('admin.profil.edit')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}