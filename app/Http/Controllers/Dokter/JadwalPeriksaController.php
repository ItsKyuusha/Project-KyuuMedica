<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JadwalPeriksaController extends Controller
{
    private static $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    private function isHariHPeriksaAktif($dokter)
    {
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        return JadwalPeriksa::where('id_dokter', $dokter->id)
            ->where('status', 'aktif')
            ->whereRaw('LOWER(hari) = ?', [strtolower($hariIni)])
            ->exists();
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
        $daftarHari = self::$daftarHari;

        return view('dokter.jadwal', compact('jadwals', 'isHariH', 'hariIni', 'daftarHari'));
    }

    public function store(Request $request)
{
    $dokter = Auth::user()->dokter;

    if (!$dokter) {
        Log::error('Akun ini belum dikaitkan dengan data dokter.');
        return redirect()->back()->with('error', 'Akun ini belum dikaitkan dengan data dokter.');
    }

    // Validasi data
    $request->validate([
        'hari' => 'required|string|in:' . implode(',', self::$daftarHari),
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        'status' => 'required|in:aktif,nonaktif',
    ]);

    $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
    $isHariIniAktif = $this->isHariHPeriksaAktif($dokter);

    // Jika hari ini jadwalnya sudah aktif, tidak boleh menambahkan jadwal aktif lain di hari selain hari ini
    if ($isHariIniAktif && strtolower($request->hari) !== strtolower($hariIni) && $request->status === 'aktif') {
        Log::warning('Tidak dapat menambahkan jadwal aktif untuk hari lain, karena hari ini sudah ada jadwal aktif.');
        return redirect()->route('dokter.jadwal')->with('error', 'Hari ini sudah ada jadwal aktif. Anda hanya dapat menambahkan jadwal aktif untuk hari ini.');
    }

    // Jika status aktif, nonaktifkan jadwal lain terlebih dahulu
    if ($request->status === 'aktif') {
        JadwalPeriksa::where('id_dokter', $dokter->id)->update(['status' => 'nonaktif']);
    }

    // Simpan data jadwal baru
    JadwalPeriksa::create([
        'id_dokter' => $dokter->id,
        'hari' => ucfirst(strtolower($request->hari)),
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
        'status' => $request->status,
    ]);

    Log::info('Jadwal baru berhasil ditambahkan untuk dokter ID: ' . $dokter->id);

    return redirect()->route('dokter.jadwal')->with('success', 'Jadwal baru berhasil ditambahkan.');
}


    public function update(Request $request, $id)
    {
        $dokter = Auth::user()->dokter;

        if (!$dokter) {
            return redirect()->back()->with('error', 'Akun ini belum dikaitkan dengan data dokter.');
        }

        $jadwal = JadwalPeriksa::findOrFail($id);

        if ($jadwal->id_dokter != $dokter->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $request->validate([
            'hari' => 'required|string|in:' . implode(',', self::$daftarHari),
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
        $isHariIniAktif = strtolower($jadwal->hari) === strtolower($hariIni) && $jadwal->status === 'aktif';

        // Tidak boleh aktifkan hari lain jika sudah ada yang aktif hari ini
        if ($request->status === 'aktif' && !$isHariIniAktif && $this->isHariHPeriksaAktif($dokter)) {
            return redirect()->route('dokter.jadwal')->with('error', 'Jadwal hari ini sudah aktif. Anda hanya dapat mengubah status aktif pada jadwal hari ini.');
        }

        // Jika akan mengaktifkan jadwal lain, nonaktifkan semua
        if ($request->status === 'aktif') {
            JadwalPeriksa::where('id_dokter', $dokter->id)->update(['status' => 'nonaktif']);
        }

        // Jika hari ini aktif, jangan ubah hari/jam
        if ($isHariIniAktif) {
            $jadwal->update([
                'status' => $request->status
            ]);
        } else {
            $jadwal->update([
                'hari' => ucfirst(strtolower($request->hari)),
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('dokter.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dokter = Auth::user()->dokter;

        if (!$dokter) {
            return redirect()->back()->with('error', 'Akun ini belum dikaitkan dengan data dokter.');
        }

        $jadwal = JadwalPeriksa::findOrFail($id);

        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        if ($jadwal->status == 'aktif' && strtolower($jadwal->hari) === strtolower($hariIni)) {
            return redirect()->route('dokter.jadwal')->with('error', 'Jadwal aktif hari ini tidak dapat dihapus.');
        }

        $jadwal->delete();

        return redirect()->route('dokter.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }
}
