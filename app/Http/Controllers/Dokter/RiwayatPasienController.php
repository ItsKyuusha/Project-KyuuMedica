<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Http\Request;

class RiwayatPasienController extends Controller
{
    public function index($id_pasien)
    {
        return Periksa::with(['daftarPoli.pasien', 'detailPeriksas.obat'])
            ->whereHas('daftarPoli', function ($query) use ($id_pasien) {
                $query->where('id_pasien', $id_pasien);
            })
            ->orderBy('tgl_periksa', 'desc')
            ->get();
    }
}

