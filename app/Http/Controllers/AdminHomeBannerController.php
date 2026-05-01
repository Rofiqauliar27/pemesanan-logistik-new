<?php

namespace App\Http\Controllers;

use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::orderBy('position')
            ->orderBy('sort_order')
            ->get();

        return view('admin.home-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.home-banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'required|in:main,side',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        $imagePath = $request->file('image')->store('home-banners', 'public');

        HomeBanner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(HomeBanner $homeBanner)
    {
        return view('admin.home-banners.edit', compact('homeBanner'));
    }

    public function update(Request $request, HomeBanner $homeBanner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'required|in:main,side',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'position' => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($homeBanner->image && Storage::disk('public')->exists($homeBanner->image)) {
                Storage::disk('public')->delete($homeBanner->image);
            }

            $data['image'] = $request->file('image')->store('home-banners', 'public');
        }

        $homeBanner->update($data);

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(HomeBanner $homeBanner)
    {
        if ($homeBanner->image && Storage::disk('public')->exists($homeBanner->image)) {
            Storage::disk('public')->delete($homeBanner->image);
        }

        $homeBanner->delete();

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }
}