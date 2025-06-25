@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="container">
    {{-- 1. Selamat Datang --}}
    <h4 class="my-4">Selamat datang, {{ auth()->user()->nama }}!</h4>

    {{-- 2. Status Antrian Aktif Hari Ini --}}
    @if ($pendaftaranHariIni)
        <div class="alert alert-info">
            <strong>Menunggu Pemeriksaan:</strong><br>
            Poli: {{ $pendaftaranHariIni->jadwal->dokter->poli->nama_poli }}<br>
            Dokter: {{ $pendaftaranHariIni->jadwal->dokter->nama }}<br>
            Jam: {{ $pendaftaranHariIni->jadwal->jam_mulai }} - {{ $pendaftaranHariIni->jadwal->jam_selesai }}<br>
            Tanggal: {{ $pendaftaranHariIni->created_at->format('d M Y') }}
        </div>
    @endif

    {{-- 3. Riwayat Pendaftaran Terbaru --}}
    <h5 class="mb-3 mt-5">Riwayat Pendaftaran Terakhir</h5>
    @if ($riwayat->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Dokter</th>
                    <th>Poli</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayat as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>{{ $item->jadwal->dokter->nama }}</td>
                        <td>{{ $item->jadwal->dokter->poli->nama_poli }}</td>
                        <td>
                            @if (!$item->periksa)
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Belum ada riwayat pendaftaran.</p>
    @endif
</div>
@endsection
