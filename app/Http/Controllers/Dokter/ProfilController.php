<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function show($id)
    {
        return Dokter::with('poli')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);

        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'id_poli' => 'required|exists:polis,id'
        ]);

        $dokter->update($request->all());
        return response()->json(['message' => 'Profil diperbarui', 'data' => $dokter]);
    }
}

