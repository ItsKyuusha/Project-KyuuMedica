@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="container">
    <h4 class="my-4">Selamat Datang, {{ auth()->user()->nama }}</h4>

    <div class="row mb-4">
    <!-- Pasien Hari Ini -->
    <div class="col-md-4">
        <div class="card text-white shadow-sm border-0 h-100" style="background-color: #0d6efd;">
            <div class="card-body d-flex flex-column justify-content-center">
                <h3 class="fw-bold mb-1">{{ $totalPasien }}</h3>
                <p class="mb-0">Pasien Hari Ini</p>
            </div>
        </div>
    </div>

    <!-- Sudah Diperiksa -->
    <div class="col-md-4">
        <div class="card text-white shadow-sm border-0 h-100" style="background-color: #198754;">
            <div class="card-body d-flex flex-column justify-content-center">
                <h3 class="fw-bold mb-1">{{ $sudahPeriksa }}</h3>
                <p class="mb-0">Sudah Diperiksa</p>
            </div>
        </div>
    </div>

    <!-- Belum Diperiksa -->
    <div class="col-md-4">
        <div class="card text-dark shadow-sm border-0 h-100" style="background-color: #ffc107;">
            <div class="card-body d-flex flex-column justify-content-center">
                <h3 class="fw-bold mb-1">{{ $belumPeriksa }}</h3>
                <p class="mb-0">Belum Diperiksa</p>
            </div>
        </div>
    </div>
</div>


    <!-- Jadwal Hari Ini -->
    <div class="mb-4">
        <h5>Jadwal Praktik Hari Ini ({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l') }})</h5>
        @forelse ($jadwals as $jadwal)
    <div>
        {{ $dokter->poli?->nama_poli ?? 'Poli tidak tersedia' }} - 
        {{ $jadwal->jam_mulai }} s/d {{ $jadwal->jam_selesai }}
    </div>
@empty
    <p class="text-muted">Tidak ada jadwal praktik hari ini.</p>
@endforelse

    </div>

    <!-- Antrian Pasien Hari Ini -->
    <div>
        <h5>Antrian Pasien Hari Ini</h5>
        @if ($daftarPolis->isEmpty())
            <p class="text-muted">Belum ada pasien yang mendaftar hari ini.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Pasien</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarPolis as $daftar)
                        <tr>
                            <td>{{ $daftar->pasien->nama }}</td>
                            <td>{{ $daftar->keluhan }}</td>
                            <td>
                                {{ $daftar->periksa ? 'Sudah Diperiksa' : 'Belum Diperiksa' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
