<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class CustomerBarangController extends Controller
{
    public function index(Request $request)
{
    $query = Barang::query();

    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    $barangs = $query->latest()->get();

    $kategoris = Barang::select('kategori')
        ->whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->distinct()
        ->orderBy('kategori', 'asc')
        ->pluck('kategori');

    return view('customer.barang.index', compact('barangs', 'kategoris'));
}
}