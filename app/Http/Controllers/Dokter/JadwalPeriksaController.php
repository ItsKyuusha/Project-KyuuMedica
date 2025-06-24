<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;

class JadwalPeriksaController extends Controller
{
    public function index($dokter_id)
    {
        return JadwalPeriksa::where('id_dokter', $dokter_id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dokter' => 'required|exists:dokters,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Validasi bentrok waktu bisa ditambahkan di sini (opsional)

        return JadwalPeriksa::create($request->all());
    }
}
