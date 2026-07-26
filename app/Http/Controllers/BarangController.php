<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBeranda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%')
                  ->orWhere('satuan', 'like', '%' . $search . '%')
                  ->orWhereHas('kategoriBeranda', function ($kategoriQuery) use ($search) {
                      $kategoriQuery->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $barangs = $query->latest()->get();

        return view('admin.barang.index', compact('barangs'));
    }

    public function create()
    {
        $kategoriList = KategoriBeranda::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.barang.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|max:255',
            'kategori_id' => 'nullable|exists:kategori_berandas,id',
            'satuan' => 'nullable|max:100',
            'harga' => 'required',
            'status' => 'required|in:aktif,tidak_aktif',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $pathGambar = null;

        if ($request->hasFile('gambar')) {
            $pathGambar = $request->file('gambar')->store('barang', 'public');
        }

        $harga = $this->cleanNumber($request->harga);
        $kategori = KategoriBeranda::find($request->kategori_id);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'kategori' => $kategori?->nama ?? null,
            'kategori_id' => $request->kategori_id,
            'satuan' => $request->satuan,
            'harga' => $harga,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'gambar' => $pathGambar,
        ]);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil ditambah');
    }

    public function show(Barang $barang)
    {
        //
    }

    public function edit(Barang $barang)
    {
        $kategoriList = KategoriBeranda::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.barang.edit', compact('barang', 'kategoriList'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|max:255',
            'kategori_id' => 'nullable|exists:kategori_berandas,id',
            'satuan' => 'nullable|max:100',
            'harga' => 'required',
            'status' => 'required|in:aktif,tidak_aktif',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $pathGambar = $barang->gambar;

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }

            $pathGambar = $request->file('gambar')->store('barang', 'public');
        }

        $harga = $this->cleanNumber($request->harga);
        $kategori = KategoriBeranda::find($request->kategori_id);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kategori' => $kategori?->nama ?? null,
            'kategori_id' => $request->kategori_id,
            'satuan' => $request->satuan,
            'harga' => $harga,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'gambar' => $pathGambar,
        ]);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil diupdate');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil dihapus');
    }

    private function cleanNumber($value): int
    {
        return (int) preg_replace('/[^0-9]/', '', (string) $value);
    }
}