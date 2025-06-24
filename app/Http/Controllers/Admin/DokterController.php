<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    // Menampilkan semua dokter dengan relasi poli
    public function index()
    {
        $dokters = Dokter::with('poli')->get();
        $polis = Poli::all(); // Mengambil semua data poli untuk dropdown
        return view('admin.dokter', compact('dokters', 'polis'));
    }

    public function store(Request $request)
    {
        // Validasi input
       $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'id_poli' => 'required|exists:polis,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed', // Menggunakan confirmed untuk validasi password_confirmation
        ]);


        // Buat akun login dokter di tabel `users`
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        // Buat data dokter profesional di tabel `dokters`
        $dokter = Dokter::create([
            'user_id' => $user->id, // Menghubungkan dokter dengan user
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
        ]);

        return redirect()->route('admin.dokter')->with('success', 'Dokter berhasil ditambahkan');
    }

    // Menampilkan detail dokter
    public function show($id)
    {
        $dokter = Dokter::with('poli')->findOrFail($id);
        return view('admin.dokter.show', compact('dokter'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'id_poli' => 'required|exists:polis,id',
            'password' => 'nullable|min:5|confirmed', // Validasi password hanya jika diubah
        ]);

        // Mencari dokter berdasarkan ID
        $dokter = Dokter::findOrFail($id);

        // Update data dokter
        $dokter->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
        ]);

        // Mencari user yang terkait dengan dokter ini
        $user = User::find($dokter->user_id); // Menggunakan `user_id` yang sudah disimpan

        if ($user) {
            // Update data pengguna (email, no_hp, password jika ada perubahan)
            $user->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'password' => $request->password ? Hash::make($request->password) : $user->password, // Update password jika ada perubahan
            ]);
        }

        return redirect()->route('admin.dokter')->with('success', 'Dokter berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Mencari dokter berdasarkan ID
        $dokter = Dokter::findOrFail($id);

        // Hapus juga data user yang terkait dengan dokter
        if ($dokter->user_id) {
            $user = User::find($dokter->user_id);
            if ($user) {
                $user->delete(); // Hapus akun user dokter
            }
        }

        // Hapus dokter
        $dokter->delete(); 

        // Mengirimkan pesan flash ke session
        return redirect()->route('admin.dokter')->with('success', 'Dokter berhasil dihapus');
    }
}
