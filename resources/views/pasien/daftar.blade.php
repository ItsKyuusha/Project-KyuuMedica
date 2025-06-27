@extends('layouts.app')

@section('title', 'Daftar Pemeriksaan')

@section('content')
<div class="container">
    <h4 class="my-4"><strong>Daftar ke Poli</strong></h4>

    {{-- Alert error --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Alert sukses --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form method="GET" action="{{ route('pasien.daftar') }}" class="mb-3">
    <div class="form-row align-items-end">
        <div class="col-md-4">
            <label for="poli">Pilih Poli</label>
            <select name="poli" id="poli" class="form-control">
                <option value="">-- Pilih Poli --</option>
                @foreach ($polis as $poli)
                    <option value="{{ $poli->id }}" {{ (int) old('poli', $idPoli) === $poli->id ? 'selected' : '' }}>
                        {{ $poli->nama_poli }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Pilih</button>
        </div>
    </div>
</form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Dokter</th>
                <th>Poli</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jadwals as $item)
            @php
                $pendaftaran = \App\Models\DaftarPoli::where('id_pasien', $pasien->id)
                    ->where('id_jadwal', $item->id)
                    ->whereDate('created_at', now()->toDateString())
                    ->with('periksa')
                    ->first();
            @endphp
            <tr>
                <td>{{ $item->dokter->nama }}</td>
                <td>{{ $item->dokter->poli->nama_poli }}</td>
                <td>{{ $item->hari }}</td>
                <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                <td>
                    @if (!$pendaftaran)
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#daftarModal{{ $item->id }}">
                            Daftar
                        </button>
                    @elseif ($pendaftaran && !$pendaftaran->periksa)
                        @php
                            // Nomor antrian berdasarkan urutan daftar hari ini
                            $noAntrian = \App\Models\DaftarPoli::where('id_jadwal', $item->id)
                                ->whereDate('created_at', now()->toDateString())
                                ->orderBy('created_at')
                                ->pluck('id')
                                ->search($pendaftaran->id) + 1;
                        @endphp
                        <button class="btn btn-warning btn-sm" disabled>
                            Menunggu (No. {{ $noAntrian }})
                        </button>
                    @elseif ($pendaftaran && $pendaftaran->periksa)
                        <button class="btn btn-success btn-sm" disabled>
                            Selesai Diperiksa
                        </button>
                    @endif
                </td>
            </tr>

            {{-- Modal daftar --}}
            <div class="modal fade" id="daftarModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="daftarModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('pasien.daftar.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="daftarModalLabel{{ $item->id }}">
                                    Daftar ke {{ $item->dokter->poli->nama_poli }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_pasien" value="{{ $pasien->id }}">
                                <input type="hidden" name="id_jadwal" value="{{ $item->id }}">

                                <div class="form-group">
                                    <label for="keluhan{{ $item->id }}">Keluhan</label>
                                    <textarea name="keluhan" id="keluhan{{ $item->id }}" class="form-control" rows="3">{{ old('keluhan') }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Jika ada error dan last_modal_id, buka modal yang terkait --}}
@if ($errors->any() && session('last_modal_id'))
<script>
    $(document).ready(function(){
        $('#daftarModal{{ session("last_modal_id") }}').modal('show');
    });
</script>
@endif
@endsection
