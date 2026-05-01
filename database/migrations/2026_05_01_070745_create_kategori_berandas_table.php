<?php

namespace App\Http\Controllers;

use App\Models\KategoriBeranda;
use Illuminate\Http\Request;

class AdminKategoriBerandaController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBeranda::orderBy('sort_order')->get();

        return view('admin.kategori-beranda.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori-beranda.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        KategoriBeranda::create([
            'nama' => $request->nama,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.kategori-beranda.index')
            ->with('success', 'Kategori beranda berhasil ditambahkan.');
    }

    public function edit(KategoriBeranda $kategoriBeranda)
    {
        return view('admin.kategori-beranda.edit', compact('kategoriBeranda'));
    }

    public function update(Request $request, KategoriBeranda $kategoriBeranda)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        $kategoriBeranda->update([
            'nama' => $request->nama,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.kategori-beranda.index')
            ->with('success', 'Kategori beranda berhasil diperbarui.');
    }

    public function destroy(KategoriBeranda $kategoriBeranda)
    {
        $kategoriBeranda->delete();

        return redirect()
            ->route('admin.kategori-beranda.index')
            ->with('success', 'Kategori beranda berhasil dihapus.');
    }
}