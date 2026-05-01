<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\CustomerBarangController;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\User;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\PublicBarangController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\AdminProfilPerusahaanController;
use App\Http\Controllers\KeranjangController;
use App\Models\HomeBanner;
use App\Http\Controllers\AdminKategoriBerandaController;
use App\Models\KategoriBeranda;
use App\Http\Controllers\AdminHomeBannerController;

Route::get('/', function () {
    $barangs = Barang::latest()->take(12)->get();

    $mainBanners = HomeBanner::where('is_active', true)
        ->where('position', 'main')
        ->orderBy('sort_order')
        ->get();

    $sideBanners = HomeBanner::where('is_active', true)
        ->where('position', 'side')
        ->orderBy('sort_order')
        ->get();

    $kategoriMenu = KategoriBeranda::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $produkKategori = [];

    foreach ($kategoriMenu as $kategori) {
        $produkKategori[$kategori->nama] = Barang::where('kategori', $kategori->nama)
            ->latest()
            ->take(6)
            ->get();
    }

    return view('welcome', compact(
        'barangs',
        'mainBanners',
        'sideBanners',
        'kategoriMenu',
        'produkKategori'
    ));
})->name('home');

Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect('/admin/dashboard');
    }

    return redirect()->route('customer.profile');
})->middleware(['auth'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    $totalBarang = Barang::count();
    $totalPesanan = Pesanan::count();
    $totalCustomer = User::where('role', 'customer')->count();
    $totalSelesai = Pesanan::where('status', 'selesai')->count();

    return view('admin.dashboard', compact(
        'totalBarang',
        'totalPesanan',
        'totalCustomer',
        'totalSelesai'
    ));
})->middleware(['auth', 'role:admin']);

Route::get('/customer/dashboard', function () {
    return redirect()->route('customer.profile');
})->middleware(['auth', 'role:customer'])->name('customer.dashboard');

Route::resource('/admin/barang', BarangController::class)
    ->middleware(['auth', 'role:admin'])
    ->names('barang');

Route::get('/customer/barang', [CustomerBarangController::class, 'index'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.barang.index');

Route::get('/customer/pesanan', [PesananController::class, 'index'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.pesanan.index');

Route::get('/customer/pesanan/create/{barang_id}', [PesananController::class, 'create'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.pesanan.create');

Route::post('/customer/pesanan/store', [PesananController::class, 'store'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.pesanan.store');
Route::get('/admin/pesanan', [PesananController::class, 'adminIndex'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.index');

Route::get('/admin/pesanan/{id}', [PesananController::class, 'show'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.show');

Route::get('/admin/pesanan/{id}/edit-status', [PesananController::class, 'editStatus'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.editStatus');

Route::put('/admin/pesanan/{id}/update-status', [PesananController::class, 'updateStatus'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.updateStatus');

Route::get('/admin/laporan-pesanan', [PesananController::class, 'laporan'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.laporan');

Route::get('/admin/laporan-pesanan/print', [PesananController::class, 'printLaporan'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.print');

Route::get('/customer/pesanan/{id}/bayar', [PesananController::class, 'showBayar'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.pesanan.showBayar');
    
Route::post('/midtrans/notification', [PesananController::class, 'notificationHandler'])
    ->name('midtrans.notification');
    
Route::get('/admin/customer', [AdminCustomerController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.customer.index');
    
Route::get('/admin/pesanan/{id}/invoice', [PesananController::class, 'invoice'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.invoice');
    
Route::get('/tentang-sistem', function () {
    return view('tentang');
})->name('tentang.sistem');

Route::get('/produk', [PublicBarangController::class, 'index'])->name('public.produk');

Route::get('/produk/{id}', [PublicBarangController::class, 'show'])->name('public.produk.show');

Route::get('/customer/profile', [CustomerProfileController::class, 'index'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.profile');

Route::get('/admin/profil-perusahaan', [AdminProfilPerusahaanController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.profil.edit');

Route::post('/admin/profil-perusahaan', [AdminProfilPerusahaanController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.profil.update');

Route::resource('/admin/home-banners', AdminHomeBannerController::class)
    ->middleware(['auth', 'role:admin'])
    ->names('admin.home-banners');

Route::resource('/admin/kategori-beranda', AdminKategoriBerandaController::class)
    ->middleware(['auth', 'role:admin'])
    ->names('admin.kategori-beranda');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/keranjang', [KeranjangController::class, 'index'])
        ->name('customer.keranjang.index');

    Route::post('/customer/keranjang/checkout', [KeranjangController::class, 'checkout'])
        ->name('customer.keranjang.checkout');

    Route::get('/customer/keranjang/bayar/{groupOrderId}', [KeranjangController::class, 'bayarKeranjang'])
        ->name('customer.keranjang.bayar');

    Route::post('/customer/keranjang/{barangId}', [KeranjangController::class, 'store'])
        ->name('customer.keranjang.store');

    Route::put('/customer/keranjang/{id}', [KeranjangController::class, 'update'])
        ->name('customer.keranjang.update');

    Route::delete('/customer/keranjang/{id}', [KeranjangController::class, 'destroy'])
        ->name('customer.keranjang.destroy');
});
require __DIR__.'/auth.php';