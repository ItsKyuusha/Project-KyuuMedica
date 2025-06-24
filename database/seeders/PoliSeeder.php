<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poli;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        Poli::insert([
            [
                'nama_poli' => 'Poli Umum',
                'keterangan' => 'Pemeriksaan umum dan keluhan ringan',
            ],
            [
                'nama_poli' => 'Poli Gigi',
                'keterangan' => 'Pemeriksaan dan perawatan gigi',
            ],
            [
                'nama_poli' => 'Poli Anak',
                'keterangan' => 'Pemeriksaan khusus untuk anak-anak',
            ],
        ]);
    }
}

