<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index($id_pasien)
    {
        return Periksa::with(['daftarPoli.jadwal.dokter.poli', 'detailPeriksas.obat'])
            ->whereHas('daftarPoli', fn($q) => $q->where('id_pasien', $id_pasien))
            ->orderByDesc('tgl_periksa')
            ->get();
    }

    public function show($id)
    {
        return Periksa::with(['daftarPoli.jadwal.dokter.poli', 'detailPeriksas.obat'])
            ->findOrFail($id);
    }
}

