<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;

class DaftarPoliController extends Controller
{
    public function getJadwalByPoli($id_poli)
    {
        return JadwalPeriksa::with('dokter')
            ->whereHas('dokter', fn($q) => $q->where('id_poli', $id_poli))
            ->get();
    }

    public function daftar(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required|exists:pasiens,id',
            'id_jadwal' => 'required|exists:jadwal_periksas,id',
            'keluhan' => 'nullable|string'
        ]);

        $daftar = DaftarPoli::create($request->all());
        return response()->json(['message' => 'Pendaftaran berhasil', 'data' => $daftar]);
    }

    public function getAntrianHariIni($id_jadwal)
    {
        $jumlah = DaftarPoli::where('id_jadwal', $id_jadwal)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return response()->json(['antrian' => $jumlah + 1]);
    }
}

