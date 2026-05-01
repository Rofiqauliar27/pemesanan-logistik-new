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
        $query->where('nama_barang', 'like', '%' . $request->search . '%')
              ->orWhere('kategori', 'like', '%' . $request->search . '%')
              ->orWhere('satuan', 'like', '%' . $request->search . '%');
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
        'kategori' => 'nullable|max:255',
        'satuan' => 'nullable|max:100',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'deskripsi' => 'nullable',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $pathGambar = null;

    if ($request->hasFile('gambar')) {
        $pathGambar = $request->file('gambar')->store('barang', 'public');
    }

    Barang::create([
        'nama_barang' => $request->nama_barang,
        'kategori' => $request->kategori,
        'satuan' => $request->satuan,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'deskripsi' => $request->deskripsi,
        'gambar' => $pathGambar,
    ]);

    return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambah');
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
        'kategori' => 'nullable|max:255',
        'satuan' => 'nullable|max:100',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
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

    $barang->update([
        'nama_barang' => $request->nama_barang,
        'kategori' => $request->kategori,
        'satuan' => $request->satuan,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'deskripsi' => $request->deskripsi,
        'gambar' => $pathGambar,
    ]);

    return redirect()->route('barang.index')->with('success', 'Data barang berhasil diupdate');
}

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus');
    }
}