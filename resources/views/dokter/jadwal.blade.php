@extends('layouts.app')

@section('title', 'Manajemen Jadwal Dokter')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="my-4 mb-0"><strong>Jadwal Praktik Dokter</strong></h4>
        <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addJadwalModal">
            Tambah Jadwal
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwals as $jadwal)
                @php
                    $hariIni = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
                    $isHariIni = strtolower($jadwal->hari) === strtolower($hariIni);
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucfirst($jadwal->hari) }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_selesai)->format('H:i') }}</td>
                    <td>
                        <span class="badge badge-{{ $jadwal->status == 'aktif' ? 'success' : 'danger' }}">
                            {{ ucfirst($jadwal->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editJadwalModal{{ $jadwal->id }}">
                            Edit
                        </button>
                        <form action="{{ route('dokter.jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                {{ ($isHariIni && $jadwal->status == 'aktif') ? 'disabled' : '' }}>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editJadwalModal{{ $jadwal->id }}" tabindex="-1" role="dialog" aria-labelledby="editJadwalModalLabel{{ $jadwal->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('dokter.jadwal.update', $jadwal->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="edit_jadwal_id" value="{{ $jadwal->id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Jadwal</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @php
                                        $hariIni = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
                                        $isHariIniAktif = strtolower($jadwal->hari) === strtolower($hariIni) && $jadwal->status === 'aktif';
                                    @endphp

                                    @if ($errors->any() && session('edit_jadwal_id') == $jadwal->id)
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="hari{{ $jadwal->id }}">Hari</label>
                                        <select name="hari" id="hari{{ $jadwal->id }}" class="form-control"
                                            {{ $isHariIniAktif ? 'disabled' : '' }}>
                                            @foreach ($daftarHari as $hari)
                                                <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>
                                                    {{ $hari }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($isHariIniAktif)
                                            <input type="hidden" name="hari" value="{{ $jadwal->hari }}">
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="jam_mulai{{ $jadwal->id }}">Jam Mulai</label>
                                        <input type="time" name="jam_mulai" class="form-control"
                                            value="{{ old('jam_mulai', \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai)->format('H:i')) }}"
                                            required {{ $isHariIniAktif ? 'readonly' : '' }}>
                                    </div>

                                    <div class="form-group">
                                        <label for="jam_selesai{{ $jadwal->id }}">Jam Selesai</label>
                                        <input type="time" name="jam_selesai" class="form-control"
                                            value="{{ old('jam_selesai', \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_selesai)->format('H:i')) }}"
                                            required {{ $isHariIniAktif ? 'readonly' : '' }}>
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status', $jadwal->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>

                                    @if($isHariIniAktif)
                                        <div class="alert alert-info">
                                            Jadwal aktif hari ini hanya dapat mengubah status, tidak bisa ubah hari atau jam.
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addJadwalModal" tabindex="-1" role="dialog" aria-labelledby="addJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('dokter.jadwal.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($errors->any() && !session('edit_jadwal_id'))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select name="hari" class="form-control" required>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Jadwal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

@if ($errors->any())
<script>
    $(document).ready(function(){
        @if(session('edit_jadwal_id'))
            $('#editJadwalModal{{ session('edit_jadwal_id') }}').modal('show');
        @else
            $('#addJadwalModal').modal('show');
        @endif
    });
</script>
@endif
@endsection
