<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class PublicBarangController extends Controller
{
    public function index(Request $request)
{
    $query = Barang::query();

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_barang', 'like', '%' . $search . '%')
              ->orWhere('kategori', 'like', '%' . $search . '%')
              ->orWhere('deskripsi', 'like', '%' . $search . '%');
        });
    }

    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    $barangs = $query->latest()
        ->paginate(12)
        ->withQueryString();

    $kategoriList = Barang::select('kategori')
        ->whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->distinct()
        ->orderBy('kategori', 'asc')
        ->pluck('kategori');

    return view('public.produk', compact('barangs', 'kategoriList'));
}

    public function show($id)
{
    $barang = Barang::findOrFail($id);

    $produkTerkait = Barang::where('id', '!=', $barang->id)
        ->when($barang->kategori, function ($query) use ($barang) {
            $query->where('kategori', $barang->kategori);
        })
        ->latest()
        ->take(4)
        ->get();

    return view('public.produk-detail', compact('barang', 'produkTerkait'));
}
}