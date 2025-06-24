<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@kyuumedica.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'alamat' => 'Jl. Admin',
        ]);

        // Dokter (akun login, datanya akan dikaitkan ke tabel dokter)
        User::create([
            'nama' => 'dr. Kenji Kusuma',
            'email' => 'dokter@kyuumedica.com',
            'password' => Hash::make('dokter123'),
            'role' => 'dokter',
            'alamat' => 'Jl. Dokter',
        ]);

        // Pasien (akun login, dan bisa dikaitkan ke tabel `pasiens`)
        User::create([
            'nama' => 'Akira Aoyama',
            'email' => 'pasien@kyuumedica.com',
            'password' => Hash::make('pasien123'),
            'role' => 'pasien',
            'alamat' => 'Jl. Pasien',
        ]);
    }
}
