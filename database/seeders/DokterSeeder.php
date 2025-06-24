<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dokter;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        Dokter::insert([
            [
                'nama' => 'dr. Kenji Kusuma',
                'alamat' => 'Jl. Dokter Sehat No.1',
                'no_hp' => '081298765432',
                'id_poli' => 1, // Poli Umum
            ],
            [
                'nama' => 'drg. Nana Sakura',
                'alamat' => 'Jl. Gigi Bersih No.3',
                'no_hp' => '081234000123',
                'id_poli' => 2, // Poli Gigi
            ]
        ]);
    }
}

