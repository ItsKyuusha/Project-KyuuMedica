<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Dokter;
use App\Models\User;
use App\Models\Poli;

class ProfilController extends Controller
{
    // Tampilkan halaman profil dokter
    public function show()
    {
        $user = Auth::user();

        // Ambil data dokter beserta relasi polinya
        $dokter = Dokter::where('user_id', $user->id)->with('poli')->firstOrFail();
        $polis = Poli::all(); // Untuk dropdown

        return view('dokter.profil', compact('user', 'dokter', 'polis'));
    }

    // Update data profil dokter
    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'id_poli' => 'required|exists:polis,id',
            'password' => 'nullable|min:5|confirmed',
        ]);

        $user = Auth::user();

        // Update data user
        $user->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Update data dokter
        $dokter = Dokter::where('user_id', $user->id)->firstOrFail();
        $dokter->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
