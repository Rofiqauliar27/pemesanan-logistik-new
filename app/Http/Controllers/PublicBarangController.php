<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class PublicBarangController extends Controller
{
    public function index(Request $request)
{
    $query = Barang::where('status', 'aktif');

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

    $barangs = $query
    ->withSum([
        'pesanans as total_terjual' => function ($q) {
            $q->where('status', 'selesai');
        }
    ], 'jumlah')
    ->latest()
    ->paginate(20)
    ->withQueryString();

   $topProdukIds = Barang::where('status', 'aktif')
    ->withSum([
        'pesanans as total_terjual' => function ($q) {
            $q->where('status', 'selesai');
        }
    ], 'jumlah')
    ->orderByDesc('total_terjual')
    ->take(5)
    ->pluck('id')
    ->toArray();

    $barangs->getCollection()->transform(function ($barang) use ($topProdukIds) {

    $barang->is_top = in_array($barang->id, $topProdukIds);

    return $barang;

});

$sorted = $barangs->getCollection()->sortByDesc(function ($barang) {

    return $barang->is_top;

});

$barangs->setCollection($sorted->values());

    $kategoriList = Barang::select('kategori')
        ->whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->distinct()
        ->orderBy('kategori', 'asc')
        ->pluck('kategori');

    return view('public.produk', compact('barangs', 'kategoriList', 'topProdukIds'));
}

    public function show($id)
{
    $barang = Barang::where('status', 'aktif')
                ->findOrFail($id);

    $produkTerkait = Barang::where('status','aktif')
    ->where('id','!=',$barang->id)
        ->when($barang->kategori, function ($query) use ($barang) {
            $query->where('kategori', $barang->kategori);
        })
        ->latest()
        ->take(4)
        ->get();

    return view('public.produk-detail', compact('barang', 'produkTerkait'));
}
}