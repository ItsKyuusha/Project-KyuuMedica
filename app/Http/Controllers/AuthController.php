<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else if ($user->role === 'dokter') {
            return redirect()->route('dokter.dashboard');
        }  else if ($user->role === 'pasien') {
            return redirect()->route('pasien.dashboard');
        }
    }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'role' => 'required|in:pasien', // hanya pasien yang bisa daftar mandiri
            'alamat' => 'required',
            'no_ktp' => 'required|unique:users,no_ktp',
            'no_hp' => 'required'
        ]);

        // Generate no_rm: contoh "202506-001"
        $prefix = now()->format('Ym');
        $count = Pasien::where('no_rm', 'like', $prefix . '%')->count();
        $no_rm = $prefix . '-' . ($count + 1);

        $user = User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'role' => 'pasien',
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'no_rm' => $no_rm,
        ]);

        Pasien::create([
            'nama' => $request->name,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'no_rm' => $no_rm,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

