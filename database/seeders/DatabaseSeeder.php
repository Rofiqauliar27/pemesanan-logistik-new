<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'telepon' => '081234567890',
                'alamat_lengkap' => 'Banjarmasin',
                'provinsi' => 'Kalimantan Selatan',
                'kabupaten' => 'Banjarmasin',
                'kecamatan' => null,
                'kelurahan' => null,
                'kode_pos' => null,
                'google_maps_link' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'remember_token' => null,
            ]
        );
        User::updateOrCreate(
    ['email' => 'dummy@gmail.com'],
    [
        'name' => 'Dummy User',
        'telepon' => '081234567891',
        'alamat_lengkap' => 'Banjarmasin',
        'provinsi' => 'Kalimantan Selatan',
        'kabupaten' => 'Banjarmasin',
        'kecamatan' => null,
        'kelurahan' => null,
        'kode_pos' => null,
        'google_maps_link' => null,
        'email_verified_at' => now(),
        'password' => Hash::make('dummy123'),
        'role' => 'customer',
        'remember_token' => null,
    ]
);
    }
    
}