<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'dokter') {
                return redirect()->route('dokter.dashboard');
            } elseif ($user->role === 'pasien') {
                return redirect()->route('pasien.dashboard');
            }
        }

        // If login failed
        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // Show register page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
            'alamat' => 'required',
            'no_ktp' => 'required|unique:pasiens,no_ktp',
            'no_hp' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // Generate nomor rekam medis (no_rm)
            $prefix = now()->format('Ym');
            $count = Pasien::where('no_rm', 'like', $prefix . '%')->count();
            $no_rm = $prefix . '-' . ($count + 1);

            // Simpan ke tabel user
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien',
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);

            // Simpan ke tabel pasien
            Pasien::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'no_ktp' => $request->no_ktp,
                'no_rm' => $no_rm,
            ]);

            DB::commit();
            return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Register Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar.');
        }
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
