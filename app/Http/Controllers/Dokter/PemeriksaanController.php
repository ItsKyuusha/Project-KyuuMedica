<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PemeriksaanController extends Controller
{
    // Menampilkan daftar pasien yang terdaftar hari ini dengan fitur skip antrian
public function hariIni()
{
    $dokter = Auth::user()->dokter;
    if (!$dokter) {
        return redirect()->route('dokter.dashboard')->withErrors('Data dokter tidak ditemukan.');
    }

    Carbon::setLocale('id');
    $hari_ini = Carbon::now()->translatedFormat('l');

    $jadwals = $dokter->jadwalPeriksas()
        ->where('hari', $hari_ini)
        ->where('status', 'aktif')
        ->with(['daftarPolis.pasien', 'daftarPolis.periksa'])
        ->get();

    $skipCounts = session()->get('skipped_counts', []);
    $nomorAntrian = session()->get('nomor_antrian', []);

    foreach ($jadwals as $jadwal) {
        $daftarHariIni = $jadwal->daftarPolis
            ->filter(fn($d) => $d->created_at->isToday())
            ->values();

        foreach ($daftarHariIni as $i => $item) {
            if (!isset($nomorAntrian[$item->id])) {
                $nomorAntrian[$item->id] = $i + 1;
            }
        }

        $daftarHariIni = $daftarHariIni->map(function ($item) use ($skipCounts) {
            $item->skip = $skipCounts[$item->id] ?? 0;
            return $item;
        });

        $normal = $daftarHariIni->filter(fn($d) => !$d->periksa && $d->skip == 0)->sortBy('created_at')->values();
        $skipped = $daftarHariIni->filter(fn($d) => !$d->periksa && $d->skip > 0)->sortBy('created_at')->values();
        $final = $normal->concat($skipped);

        $sudahDiperiksa = $daftarHariIni->filter(fn($d) => $d->periksa)->values();

        // Simpan ke properti untuk digunakan di view
        $jadwal->daftarPolisFiltered = $final;
        $jadwal->daftarPolisSudah = $sudahDiperiksa;
    }

    session()->put('nomor_antrian', $nomorAntrian);

    return view('dokter.jadwal_hari_ini', compact('jadwals', 'nomorAntrian'));
}

    // Menampilkan form pemeriksaan pasien
    public function show($id_daftar_poli)
    {
        $daftar = DaftarPoli::with(['pasien', 'jadwal.dokter'])->findOrFail($id_daftar_poli);
        $obats = Obat::all();

        $riwayatPemeriksaan = Periksa::with(['detailPeriksas.obat', 'daftarPoli.pasien', 'daftarPoli.jadwal.dokter'])
            ->whereHas('daftarPoli', function ($query) use ($daftar) {
                $query->where('id_pasien', $daftar->id_pasien);
            })
            ->latest('tgl_periksa')
            ->get();

        return view('dokter.pemeriksaan', compact('daftar', 'obats', 'riwayatPemeriksaan'));
    }

    // Simpan hasil pemeriksaan dan reset skip count pasien
    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_polis,id',
            'tgl_periksa' => 'required|date',
            'catatan' => 'nullable|string',
            'obat_ids' => 'array',
            'obat_ids.*' => 'exists:obats,id',
        ]);

        DB::beginTransaction();

        try {
            $biaya_jasa = 150000;
            $total_obat = 0;

            if ($request->has('obat_ids')) {
                $obatHarga = DB::table('obats')
                    ->whereIn('id', $request->obat_ids)
                    ->pluck('harga')
                    ->toArray();
                $total_obat = array_sum($obatHarga);
            }

            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => $request->tgl_periksa,
                'catatan' => $request->catatan,
                'biaya_periksa' => $biaya_jasa + $total_obat,
            ]);

            if ($request->has('obat_ids')) {
                foreach ($request->obat_ids as $id_obat) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $id_obat,
                    ]);
                }
            }

            DB::commit();

            // Reset skip count pasien setelah diperiksa
            $skipped = session()->get('skipped_counts', []);
            unset($skipped[$request->id_daftar_poli]);
            session()->put('skipped_counts', $skipped);

            return redirect()->route('dokter.jadwal.hari_ini')->with('success', 'Pemeriksaan berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Terjadi kesalahan saat menyimpan pemeriksaan', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Detail pemeriksaan pasien
    public function detail($id_daftar_poli)
    {
        $periksa = Periksa::with(['detailPeriksas.obat', 'daftarPoli.pasien'])
            ->where('id_daftar_poli', $id_daftar_poli)
            ->firstOrFail();

        $status = $periksa ? 'Sudah diperiksa' : 'Belum diperiksa';

        return view('dokter.pemeriksaan_detail', compact('periksa', 'status'));
    }

    // Fungsi untuk skip antrian pasien (increment skip count)
    public function skipAntrian(Request $request, $id_daftar_poli)
    {
        $skipped = session()->get('skipped_counts', []);
        $skipped[$id_daftar_poli] = ($skipped[$id_daftar_poli] ?? 0) + 1;
        session()->put('skipped_counts', $skipped);

        $count = $skipped[$id_daftar_poli];

        return redirect()->route('dokter.jadwal.hari_ini')->with('success', "Antrian pasien berhasil di-skip. Skip count: {$count}");
    }
}
