<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\ProfilController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PemeriksaanController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Pasien\DaftarPoliController;

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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
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
Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'role:dokter'])->group(function () {

    // Dashboard Dokter (opsional, misal hanya untuk tampilan utama dokter)
    Route::get('/dashboard', [DokterDashboardController::class, 'dashboard'])->name('dashboard');

    // Jadwal Periksa
    Route::get('/jadwal', [JadwalPeriksaController::class, 'index'])->name('jadwal');
    Route::post('/jadwal', [JadwalPeriksaController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [JadwalPeriksaController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalPeriksaController::class, 'destroy'])->name('jadwal.destroy');

    // Pemeriksaan Pasien
    // Menampilkan daftar pasien yang mendaftar hari ini
    Route::get('/jadwal/hari-ini', [PemeriksaanController::class, 'hariIni'])->name('jadwal.hari_ini');
    Route::post('/jadwal/hari_ini/skip/{id}', [PemeriksaanController::class, 'skipAntrian'])->name('jadwal.skip');


    // Menampilkan form pemeriksaan pasien
    Route::get('/pemeriksaan/{id_daftar_poli}', [PemeriksaanController::class, 'show'])->name('pemeriksaan.show');

    // Menyimpan hasil pemeriksaan
    Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');

    // Menampilkan detail pemeriksaan
    Route::get('/pemeriksaan/detail/{id_daftar_poli}', [PemeriksaanController::class, 'detail'])->name('pemeriksaan.detail');

    //profil
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil');
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
});

// Route untuk Pasien
Route::prefix('pasien')->name('pasien.')->middleware(['auth', 'role:pasien'])->group(function () {

    // Dashboard Pasien
    Route::get('/dashboard', [PasienDashboardController::class, 'dashboard'])->name('dashboard');

    // Tampilkan semua jadwal periksa
    Route::get('/jadwal', [DaftarPoliController::class, 'getAllJadwal'])->name('jadwal.semua');

    // Tampilkan jadwal berdasarkan poli tertentu
    Route::get('/jadwal/{id_poli}', [DaftarPoliController::class, 'getJadwalByPoli'])->name('jadwal');

    // Form daftar ke poli
    Route::get('/daftar', [DaftarPoliController::class, 'showForm'])->name('daftar');

    // Proses daftar ke poli
    Route::post('/daftar', [DaftarPoliController::class, 'daftar'])->name('daftar.store');

    // Antrian hari ini (untuk jadwal tertentu)
    Route::get('/antrian/{id_jadwal}', [DaftarPoliController::class, 'getAntrianHariIni'])->name('antrian');
});