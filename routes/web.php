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

// Route halaman utama
Route::get('/', function () {
    return view('welcome'); // Halaman welcome default
});

// Route Login, Register dan Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // Menampilkan halaman login
Route::post('/login', [AuthController::class, 'login']); // Proses login
Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // Menampilkan halaman register
Route::post('/register', [AuthController::class, 'register']); // Proses register
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Proses logout

// Route untuk Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboardAdmin'])->name('dashboard'); // Halaman dashboard admin

    // Dokter Management
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter'); // Daftar dokter
    Route::get('/dokter/{id}', [DokterController::class, 'show'])->name('dokter.show'); // Detail dokter berdasarkan ID
    Route::post('/dokter', [DokterController::class, 'store'])->name('dokter.store'); // Tambah dokter
    Route::put('/dokter/{id}', [DokterController::class, 'update'])->name('dokter.update'); // Update data dokter
    Route::delete('/dokter/{id}', [DokterController::class, 'destroy'])->name('dokter.destroy'); // Hapus dokter

    // Pasien Management
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien'); // Daftar pasien
    Route::get('/pasien/{id}', [PasienController::class, 'show'])->name('pasien.show'); // Detail pasien berdasarkan ID
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store'); // Tambah pasien
    Route::put('/pasien/{id}', [PasienController::class, 'update'])->name('pasien.update'); // Update data pasien
    Route::delete('/pasien/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy'); // Hapus pasien

    // Poli Management
    Route::get('/poli', [PoliController::class, 'index'])->name('poli'); // Daftar poli
    Route::get('/poli/{id}', [PoliController::class, 'show'])->name('poli.show'); // Detail poli berdasarkan ID
    Route::post('/poli', [PoliController::class, 'store'])->name('poli.store'); // Tambah poli
    Route::put('/poli/{id}', [PoliController::class, 'update'])->name('poli.update'); // Update data poli
    Route::delete('/poli/{id}', [PoliController::class, 'destroy'])->name('poli.destroy'); // Hapus poli

    // Obat Management
    Route::get('/obat', [ObatController::class, 'index'])->name('obat'); // Daftar obat
    Route::get('/obat/{id}', [ObatController::class, 'show'])->name('obat.show'); // Detail obat berdasarkan ID
    Route::post('/obat', [ObatController::class, 'store'])->name('obat.store'); // Tambah obat
    Route::put('/obat/{id}', [ObatController::class, 'update'])->name('obat.update'); // Update data obat
    Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('obat.destroy'); // Hapus obat
});

// Route untuk Dokter
Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'role:dokter'])->group(function () {
    // Dashboard Dokter
    Route::get('/dashboard', [DokterDashboardController::class, 'dashboard'])->name('dashboard'); // Halaman dashboard dokter

    // Jadwal Periksa
    Route::get('/jadwal', [JadwalPeriksaController::class, 'index'])->name('jadwal'); // Daftar jadwal periksa
    Route::post('/jadwal', [JadwalPeriksaController::class, 'store'])->name('jadwal.store'); // Tambah jadwal
    Route::put('/jadwal/{id}', [JadwalPeriksaController::class, 'update'])->name('jadwal.update'); // Update jadwal
    Route::delete('/jadwal/{id}', [JadwalPeriksaController::class, 'destroy'])->name('jadwal.destroy'); // Hapus jadwal

    // Pemeriksaan Pasien
    Route::get('/jadwal/hari-ini', [PemeriksaanController::class, 'hariIni'])->name('jadwal.hari_ini'); // Menampilkan pasien hari ini
    Route::post('/jadwal/hari_ini/skip/{id}', [PemeriksaanController::class, 'skipAntrian'])->name('jadwal.skip'); // Lewati pasien yang tidak hadir

    // Form pemeriksaan pasien
    Route::get('/pemeriksaan/{id_daftar_poli}', [PemeriksaanController::class, 'show'])->name('pemeriksaan.show'); // Menampilkan form pemeriksaan pasien
    Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store'); // Simpan hasil pemeriksaan
    Route::get('/pemeriksaan/detail/{id_daftar_poli}', [PemeriksaanController::class, 'detail'])->name('pemeriksaan.detail'); // Menampilkan detail pemeriksaan

    // Profil dokter
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil'); // Menampilkan profil dokter
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update'); // Update profil dokter
});

// Route untuk Pasien
Route::prefix('pasien')->name('pasien.')->middleware(['auth', 'role:pasien'])->group(function () {
    // Dashboard Pasien
    Route::get('/dashboard', [PasienDashboardController::class, 'dashboard'])->name('dashboard'); // Halaman dashboard pasien

    // Tampilkan semua jadwal periksa
    Route::get('/jadwal', [DaftarPoliController::class, 'getAllJadwal'])->name('jadwal.semua'); // Menampilkan semua jadwal periksa

    // Tampilkan jadwal berdasarkan poli tertentu
    Route::get('/jadwal/{id_poli}', [DaftarPoliController::class, 'getJadwalByPoli'])->name('jadwal'); // Jadwal per poli tertentu

    // Form daftar ke poli
    Route::get('/daftar', [DaftarPoliController::class, 'showForm'])->name('daftar'); // Menampilkan form pendaftaran poli
    Route::post('/daftar', [DaftarPoliController::class, 'daftar'])->name('daftar.store'); // Proses pendaftaran ke poli

    // Antrian hari ini (untuk jadwal tertentu)
    Route::get('/antrian/{id_jadwal}', [DaftarPoliController::class, 'getAntrianHariIni'])->name('antrian'); // Menampilkan antrian hari ini untuk jadwal tertentu
});
