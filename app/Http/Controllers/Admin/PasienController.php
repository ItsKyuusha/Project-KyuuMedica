<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    // Show list of patients
    public function index()
    {
        $pasiens = Pasien::with('user')->get();  // Get data pasien dengan relasi user
        return view('admin.pasien', compact('pasiens'));
    }

    // Store new patient (by admin)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_ktp' => 'required|unique:pasiens,no_ktp',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Generate no_rm berdasarkan tahun dan bulan
            $prefix = now()->format('Ym');
            $count = Pasien::where('no_rm', 'like', $prefix . '%')->count();
            $no_rm = $prefix . '-' . ($count + 1);

            // Simpan data ke tabel users
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => 'pasien',
                'password' => Hash::make($request->password),
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);

            // Simpan data ke tabel pasiens dan pastikan user_id terhubung dengan user yang baru dibuat
            Pasien::create([
                'user_id' => $user->id, // Menyimpan user_id ke tabel pasiens
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_ktp' => $request->no_ktp,
                'no_hp' => $request->no_hp,
                'no_rm' => $no_rm,
            ]);

            // Commit transaksi jika berhasil
            DB::commit();
            return redirect()->route('admin.pasien')->with('success', 'Pasien berhasil didaftarkan');

        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollBack();

            // Log error dengan pesan kesalahan
            \Log::error('Gagal menambahkan pasien: ' . $e->getMessage());

            // Tampilkan pesan error ke user
            return redirect()->back()->with('error', 'Gagal menambahkan pasien.');
        }
    }

    // Show individual patient details
    public function show($id)
    {
        $pasien = Pasien::with('user')->findOrFail($id);
        return view('admin.pasien.show', compact('pasien'));
    }

    // Show form to edit a patient
    public function edit($id)
    {
        $pasien = Pasien::with('user')->findOrFail($id); // Pastikan kita mengambil user juga
        return view('admin.pasien.edit', compact('pasien'));
    }

    // Update patient details
   public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'nama' => 'required',
        'alamat' => 'required',
        'no_ktp' => 'required|unique:pasiens,no_ktp,' . $id,
        'no_hp' => 'required',
        // Validasi email dengan pengecualian berdasarkan user_id
        'email' => 'required|email|unique:users,email,' . $request->user_id, // Menggunakan $request->user_id untuk pengecualian user
    ]);

    // Mulai transaksi database
    DB::beginTransaction();

    try {
        // Update data pasien
        $pasien = Pasien::findOrFail($id);
        $pasien->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
        ]);

        // Update data user yang terkait dengan pasien
        if ($pasien->user) { // Pastikan user ada
            $pasien->user->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                // Jika password diubah, lakukan enkripsi
                'password' => $request->password ? Hash::make($request->password) : $pasien->user->password,
            ]);
        }

        // Commit transaksi jika berhasil
        DB::commit();
        return redirect()->route('admin.pasien')->with('success', 'Pasien berhasil diperbarui');

    } catch (\Exception $e) {
        // Rollback transaksi jika ada kesalahan
        DB::rollBack();

        // Log error dengan pesan kesalahan
        \Log::error('Gagal memperbarui pasien: ' . $e->getMessage());

        // Tampilkan pesan error ke user
        return redirect()->back()->with('error', 'Gagal memperbarui pasien.');
    }
}


    // Delete a patient
    public function destroy($id)
    {
        Pasien::destroy($id);
        return redirect()->route('admin.pasien')->with('success', 'Pasien berhasil dihapus');
    }
}


