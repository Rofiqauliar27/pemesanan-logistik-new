<?php

use App\Models\Barang;
use App\Models\KategoriBeranda;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

it('menghubungkan barang ke kategori beranda melalui relasi', function () {
    Schema::dropIfExists('barangs');
    Schema::dropIfExists('kategori_berandas');

    Schema::create('kategori_berandas', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    Schema::create('barangs', function (Blueprint $table) {
        $table->id();
        $table->string('nama_barang');
        $table->string('kategori')->nullable();
        $table->integer('kategori_id')->nullable();
        $table->timestamps();
    });

    $kategori = KategoriBeranda::create([
        'nama' => 'Alat Tulis',
        'is_active' => true,
    ]);

    $barang = Barang::create([
        'nama_barang' => 'Pensil',
        'kategori' => 'Alat Tulis',
        'kategori_id' => $kategori->id,
    ]);

    $this->assertNotNull($barang->kategoriBeranda);
    $this->assertEquals($kategori->id, $barang->kategoriBeranda->id);
    $this->assertTrue($kategori->barangs()->whereKey($barang->id)->exists());
});
