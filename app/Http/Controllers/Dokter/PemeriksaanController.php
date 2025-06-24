<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
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
                $obatHarga = DB::table('obats')->whereIn('id', $request->obat_ids)->pluck('harga')->toArray();
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
            return response()->json(['message' => 'Pemeriksaan berhasil', 'data' => $periksa]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id_daftar_poli)
    {
        return Periksa::with('detailPeriksas.obat')->where('id_daftar_poli', $id_daftar_poli)->first();
    }
}

