@extends('layouts.app')
@section('title', 'Jadwal Pemeriksaan')
@section('content')
<div class="container">
    <h4 class="my-4"><strong>Jadwal Pemeriksaan</strong></h4>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Dokter</th>
                <th>Poli</th>
                <th>Hari</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jadwals as $item)
                <tr>
                    <td>{{ $item->dokter->nama }}</td>
                    <td>{{ $item->dokter->poli->nama_poli }}</td>
                    <td>{{ $item->hari }}</td>
                    <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection