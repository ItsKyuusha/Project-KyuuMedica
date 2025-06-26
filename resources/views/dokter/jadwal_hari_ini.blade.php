@extends('layouts.app')

@section('title', 'Jadwal Hari Ini')

@section('content')
<div class="container">
    <h3 class="mb-4">Jadwal Hari Ini</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @foreach ($jadwals as $jadwal)
        @php
            $antrianBelum = $jadwal->daftarPolisFiltered;
            $antrianSudah = $jadwal->daftarPolisSudah ?? collect();
        @endphp
        @php
            $antrianBelumTidakSkip = $antrianBelum->filter(fn($d) => $d->skip == 0)->values();
        @endphp

        <div class="row mb-4">
            {{-- Informasi Jadwal --}}
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        Informasi Jadwal
                    </div>
                    <div class="card-body">
                        <p><strong>Poli:</strong> {{ $jadwal->dokter->poli->nama_poli }}</p>
                        <p><strong>Dokter:</strong> {{ $jadwal->dokter->nama }}</p>
                        <p><strong>Jam:</strong> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>
                        <p><strong>Tanggal:</strong> {{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Antrian Berikutnya --}}
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white text-center">
                        Antrian Berikutnya
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        @if ($antrianBelumTidakSkip->isNotEmpty())
                            @php $next = $antrianBelumTidakSkip[0]; @endphp
                            <h5 class="display-4 mb-1">
                                <strong>No. {{ $nomorAntrian[$next->id] ?? '-' }}</strong>
                            </h5>
                            <p class="mb-0">{{ $next->pasien->nama }}</p>
                            <small class="text-muted mb-3 d-block">No. RM: {{ $next->pasien->no_rm }}</small>

                            <div class="d-flex justify-content-center mt-8">
    <a href="{{ route('dokter.pemeriksaan.show', $next->id) }}" class="btn btn-primary btn-sm me-3 px-4">Periksa</a>

    <form action="{{ route('dokter.jadwal.skip', $next->id) }}" method="POST" class="d-inline ms-3">
        @csrf
        <button type="submit" class="btn btn-warning btn-sm px-3 ms-3">Skip</button>
    </form>
</div>


                        @else
                            <p class="text-muted">Tidak ada antrian.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Pasien Belum Diperiksa --}}
        <div class="row mb-4">
            <div class="col-12">
                <h5>Pasien Belum Diperiksa</h5>
                @if ($antrianBelum->isEmpty())
                    <p class="text-muted">Tidak ada pasien menunggu.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Pasien</th>
                                    <th>No. RM</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($antrianBelum as $daftar)
                                    <tr>
                                        <td>{{ $nomorAntrian[$daftar->id] ?? '-' }}</td>
                                        <td>{{ $daftar->pasien->nama }}</td>
                                        <td>{{ $daftar->pasien->no_rm }}</td>
                                        <td>
                                            <a href="{{ route('dokter.pemeriksaan.show', $daftar->id) }}" class="btn btn-primary btn-sm">Periksa</a>
                                            <form action="{{ route('dokter.jadwal.skip', $daftar->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">Skip</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pasien Sudah Diperiksa --}}
        <div class="row mb-5">
            <div class="col-12">
                <h5>Pasien Sudah Diperiksa</h5>
                @if ($antrianSudah->isEmpty())
                    <p class="text-muted">Belum ada yang diperiksa.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Pasien</th>
                                    <th>No. RM</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($antrianSudah as $daftar)
                                    <tr>
                                        <td>{{ $nomorAntrian[$daftar->id] ?? '-' }}</td>
                                        <td>{{ $daftar->pasien->nama }}</td>
                                        <td>{{ $daftar->pasien->no_rm }}</td>
                                        <td>
                                            <a href="{{ route('dokter.pemeriksaan.detail', $daftar->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
