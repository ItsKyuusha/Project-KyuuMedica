<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien) {
            return redirect()->route('pasien.dashboard')
                ->withErrors(['message' => 'Data pasien belum lengkap.']);
        }

        // Cek apakah ada antrian aktif hari ini (belum diperiksa)
        $pendaftaranHariIni = DaftarPoli::with('jadwal.dokter.poli')
            ->where('id_pasien', $pasien->id)
            ->whereDate('created_at', now())
            ->whereDoesntHave('periksa')
            ->first();

        // Ambil 5 riwayat terakhir
        $riwayat = DaftarPoli::with('jadwal.dokter.poli', 'periksa')
            ->where('id_pasien', $pasien->id)
            ->latest()
            ->take(5)
            ->get();

        return view('pasien.dashboard', compact('pendaftaranHariIni', 'riwayat'));
    }
}
