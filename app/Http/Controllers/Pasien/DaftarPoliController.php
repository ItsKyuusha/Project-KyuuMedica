<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;

class DaftarPoliController extends Controller
{
    // Semua jadwal (aktif + nonaktif) untuk halaman jadwal
    public function getAllJadwal()
    {
        $jadwals = JadwalPeriksa::with('dokter.poli')->get();  // tanpa filter status
        return view('pasien.jadwal', compact('jadwals'));
    }
    
    // Tampilkan halaman daftar jadwal & status pendaftaran pasien hari ini
    public function showForm(Request $request)
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien) {
            return redirect()->route('pasien.dashboard')
                ->withErrors(['message' => 'Data pasien belum lengkap, silakan hubungi admin.']);
        }

        $idPoli = $request->query('poli');
        
        $jadwals = JadwalPeriksa::with('dokter.poli')
            ->where('status', 'aktif')
            ->when($idPoli, function ($query) use ($idPoli) {
                $query->whereHas('dokter', function ($q) use ($idPoli) {
                    $q->where('id_poli', $idPoli);
                });
            })
            ->get();

        $polis = Poli::all();

        return view('pasien.daftar', compact('jadwals', 'pasien', 'polis', 'idPoli'));
    }

    // Proses pendaftaran pasien
    public function daftar(Request $request)
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien) {
            return redirect()->back()->withErrors(['message' => 'Data pasien tidak ditemukan.']);
        }

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksas,id',
            'keluhan' => 'nullable|string|max:255'
        ]);

        // Cek sudah daftar hari ini untuk jadwal itu
        $sudahDaftar = DaftarPoli::where('id_pasien', $pasien->id)
            ->where('id_jadwal', $request->id_jadwal)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($sudahDaftar) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => 'Anda sudah mendaftar hari ini pada jadwal tersebut.'])
                ->with('last_modal_id', $request->id_jadwal);
        }

        DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
        ]);

        return redirect()->route('pasien.daftar')->with('success', 'Pendaftaran berhasil.');
    }
}
