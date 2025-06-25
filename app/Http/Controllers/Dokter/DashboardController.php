<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DaftarPoli;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $dokter = Auth::user()->dokter;

        // Set locale Indonesia untuk Carbon
        Carbon::setLocale('id');

        // Nama hari dalam bahasa Indonesia
        $hariIni = Carbon::now()->translatedFormat('l');

        // Ambil jadwal aktif hari ini dengan eager load poli
        $jadwals = $dokter->jadwalPeriksas()
            ->where('hari', $hariIni)
            ->where('status', 'aktif')
            ->with('poli')
            ->get();

        // Ambil daftar poli pasien hari ini sesuai jadwal dokter
        $daftarPolis = DaftarPoli::with(['pasien', 'periksa', 'jadwal'])
            ->whereIn('id_jadwal', $jadwals->pluck('id'))
            ->whereDate('created_at', now()->toDateString())
            ->get();

        $totalPasien = $daftarPolis->count();
        $sudahPeriksa = $daftarPolis->whereNotNull('periksa')->count();
        $belumPeriksa = $daftarPolis->whereNull('periksa')->count();

        return view('dokter.dashboard', compact(
            'dokter','jadwals', 'daftarPolis', 'totalPasien', 'sudahPeriksa', 'belumPeriksa'
        ));
    }
}
