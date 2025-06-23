@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-12 mb-4">
      <h3>Selamat Datang, {{ Auth::user()->nama }}</h3>
      <p>Selamat datang di sistem informasi poliklinik KyuuMedica. Anda bisa melihat jadwal poli, melakukan pendaftaran, dan memeriksa riwayat periksa Anda.</p>
    </div>
  </div>

  <div class="row">
    <!-- Card daftar ke poli -->
    <div class="col-md-6">
      <div class="card border-left-primary shadow">
        <div class="card-body">
          <h5 class="card-title">Pendaftaran ke Poli</h5>
          <p class="card-text">Daftar ke poli sesuai dengan keluhan Anda.</p>

        </div>
      </div>
    </div>

    <!-- Card lihat riwayat -->
    <div class="col-md-6">
      <div class="card border-left-success shadow">
        <div class="card-body">
          <h5 class="card-title">Riwayat Pemeriksaan</h5>
          <p class="card-text">Lihat hasil pemeriksaan dan catatan dokter.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
