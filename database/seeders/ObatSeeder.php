<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        Obat::insert([
            [
                'nama' => 'Paracetamol',
                'kemasan' => 'Tablet 500mg',
                'harga' => 3000,
            ],
            [
                'nama' => 'Amoxicillin',
                'kemasan' => 'Kapsul 500mg',
                'harga' => 5000,
            ],
            [
                'nama' => 'Ibuprofen',
                'kemasan' => 'Tablet 400mg',
                'harga' => 4000,
            ],
        ]);
    }
}
