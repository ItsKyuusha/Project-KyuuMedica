<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Dokter\ProfilController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PemeriksaanController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\DaftarPoliController;
use App\Http\Controllers\Pasien\RiwayatController;

Route::get('/', function () {
    return view('welcome');
});

// Route Login, Register dan Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk Admin
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboardAdmin'])->name('dashboard');

    // Dokter
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter');
    Route::get('/dokter/{id}', [DokterController::class, 'show'])->name('dokter.show');
    Route::post('/dokter', [DokterController::class, 'store'])->name('dokter.store');
    Route::put('/dokter/{id}', [DokterController::class, 'update'])->name('dokter.update');
    Route::delete('/dokter/{id}', [DokterController::class, 'destroy'])->name('dokter.destroy');

    // Pasien
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien');
    Route::get('/pasien/{id}', [PasienController::class, 'show'])->name('pasien.show');
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
    Route::put('/pasien/{id}', [PasienController::class, 'update'])->name('pasien.update');
    Route::delete('/pasien/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

    // Poli
    Route::get('/poli', [PoliController::class, 'index'])->name('poli');
    Route::get('/poli/{id}', [PoliController::class, 'show'])->name('poli.show');
    Route::post('/poli', [PoliController::class, 'store'])->name('poli.store');
    Route::put('/poli/{id}', [PoliController::class, 'update'])->name('poli.update');
    Route::delete('/poli/{id}', [PoliController::class, 'destroy'])->name('poli.destroy');

    // Obat
    Route::get('/obat', [ObatController::class, 'index'])->name('obat');
    Route::get('/obat/{id}', [ObatController::class, 'show'])->name('obat.show');
    Route::post('/obat', [ObatController::class, 'store'])->name('obat.store');
    Route::put('/obat/{id}', [ObatController::class, 'update'])->name('obat.update');
    Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('obat.destroy');
});

// Route untuk Dokter
Route::prefix('dokter')->name('dokter.')->middleware('auth')->group(function () {

    // Profil dokter
    Route::get('/profil/{id}', [ProfilController::class, 'show'])->name('profil.show');
    Route::put('/profil/{id}', [ProfilController::class, 'update'])->name('profil.update');

    // Jadwal periksa
    Route::get('/jadwal', [JadwalPeriksaController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalPeriksaController::class, 'store'])->name('jadwal.store');

    // Pemeriksaan pasien
    Route::post('/periksa', [PemeriksaanController::class, 'store'])->name('periksa.store');
    Route::get('/periksa/{id_daftar_poli}', [PemeriksaanController::class, 'show'])->name('periksa.show');

    // Riwayat pasien
    Route::get('/riwayat/{id_pasien}', [RiwayatPasienController::class, 'index'])->name('riwayat.index');
});

// Route untuk Pasien
Route::prefix('pasien')->name('pasien.')->middleware('auth')->group(function () {

    // Melihat daftar jadwal poli (dokter & jadwal)
    Route::get('/jadwal', [DaftarPoliController::class, 'index'])->name('jadwal.index');

    // Mendaftar ke poli
    Route::post('/daftar', [DaftarPoliController::class, 'store'])->name('daftar.store');

    // Mengetahui jumlah antrian di jadwal tertentu
    Route::get('/antrian/{id_jadwal}', [DaftarPoliController::class, 'antrian'])->name('antrian');

    // Riwayat pemeriksaan pasien
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // Detail satu pemeriksaan
    Route::get('/riwayat/{id_periksa}', [RiwayatController::class, 'show'])->name('riwayat.show');
});
