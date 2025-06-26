<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;  // Import Log
use Carbon\Carbon;

class JadwalPeriksaController extends Controller
{
    private static $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    private function isHariHPeriksaAktif($dokter)
    {
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        Log::debug('Memeriksa apakah hari ini adalah hari H periksa aktif untuk dokter: ' . $dokter->id);

        $jadwalAktifHariIni = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->where('status', 'aktif')
            ->whereRaw('LOWER(hari) = ?', [strtolower($hariIni)])
            ->first();

        if ($jadwalAktifHariIni) {
            Log::debug('Jadwal aktif hari ini ditemukan: ' . $jadwalAktifHariIni);
        } else {
            Log::debug('Tidak ada jadwal aktif untuk hari ini.');
        }

        return $jadwalAktifHariIni !== null;
    }

    public function index()
    {
        $dokter = Auth::user()->dokter;

        if (!$dokter) {
            return redirect()->back()->with('error', 'Akun ini belum dikaitkan dengan data dokter.');
        }

        $jadwals = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->orderByRaw("FIELD(hari, '" . implode("','", self::$daftarHari) . "')")
            ->get();

        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
        $isHariH = $this->isHariHPeriksaAktif($dokter);

        // Add $daftarHari to the view data
        $daftarHari = self::$daftarHari;

        return view('dokter.jadwal', compact('jadwals', 'isHariH', 'hariIni', 'daftarHari'));
    }

    public function update(Request $request, $id)
    {
        $dokter = Auth::user()->dokter;

        if (!$dokter) {
            Log::error('Akun ini belum dikaitkan dengan data dokter.');
            return redirect()->back()->with('error', 'Akun ini belum dikaitkan dengan data dokter.');
        }

        $jadwal = JadwalPeriksa::findOrFail($id);

        if ($jadwal->id_dokter != $dokter->id) {
            Log::warning('Dokter mencoba mengakses jadwal yang bukan miliknya.');
            abort(403, 'Akses tidak diizinkan.');
        }

        Log::debug('Memperbarui jadwal dengan ID: ' . $id);

        // Validasi data
        $request->validate([
            'hari' => 'required|string|in:' . implode(',', self::$daftarHari),
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Cek apakah hari ini jadwalnya aktif
        $isHariIniAktif = $this->isHariHPeriksaAktif($dokter);  
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        // Jika hari ini jadwalnya aktif, hanya bisa set status aktif untuk jadwal hari ini
        if ($isHariIniAktif && strtolower($jadwal->hari) !== strtolower($hariIni) && $request->status === 'aktif') {
            Log::warning('Tidak dapat mengubah status jadwal yang bukan hari ini menjadi aktif, karena hari ini sudah ada jadwal aktif.');
            return redirect()->route('dokter.jadwal')->with('error', 'Jadwal hari ini sudah aktif. Anda hanya dapat mengubah status aktif pada jadwal hari ini.');
        }

        // Jika status aktif, nonaktifkan jadwal lain
        if ($request->status === 'aktif') {
            Log::debug('Menonaktifkan jadwal lain sebelum memperbarui jadwal aktif.');
            JadwalPeriksa::where('id_dokter', $dokter->id)->update(['status' => 'nonaktif']);
        }

        $jadwal->update([
            'hari' => ucfirst(strtolower($request->hari)),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => $request->status,
        ]);

        Log::info('Jadwal dengan ID ' . $id . ' berhasil diperbarui.');

        return redirect()->route('dokter.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dokter = Auth::user()->dokter;

        if (!$dokter) {
            Log::error('Akun ini belum dikaitkan dengan data dokter.');
            return redirect()->back()->with('error', 'Akun ini belum dikaitkan dengan data dokter.');
        }

        $jadwal = JadwalPeriksa::findOrFail($id);

        if ($jadwal->id_dokter != $dokter->id) {
            Log::warning('Dokter mencoba mengakses jadwal yang bukan miliknya.');
            abort(403, 'Akses tidak diizinkan.');
        }

        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        if ($jadwal->status == 'aktif' && strtolower($jadwal->hari) === strtolower($hariIni)) {
            Log::warning('Jadwal aktif hari ini tidak dapat dihapus: ' . $jadwal->id);
            return redirect()->route('dokter.jadwal')->with('error', 'Jadwal aktif hari ini tidak dapat dihapus.');
        }

        $jadwal->delete();

        Log::info('Jadwal dengan ID ' . $id . ' berhasil dihapus.');

        return redirect()->route('dokter.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }
}
