@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')

@section('content')
<div class="container">
    <h3>Detail Pemeriksaan Pasien: {{ $periksa->daftarPoli->pasien->nama }}</h3>

    <table class="table table-bordered">
        <tr>
            <th>Tanggal Pemeriksaan</th>
            <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Catatan Pemeriksaan</th>
            <td>{{ $periksa->catatan }}</td>
        </tr>
        <tr>
            <th>Biaya Pemeriksaan</th>
            <td>Rp{{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Status Pemeriksaan</th>
            <td>{{ $status }}</td>
        </tr>
    </table>

    <h4>Resep Obat</h4>
    <ul>
        @foreach ($periksa->detailPeriksas as $detail)
            <li>{{ $detail->obat->nama }} - Rp{{ number_format($detail->obat->harga, 0, ',', '.') }}</li>
        @endforeach
    </ul>

    <a href="{{ route('dokter.jadwal.hari_ini') }}" class="btn btn-secondary">Kembali ke Jadwal Hari Ini</a>
</div>
@endsection
