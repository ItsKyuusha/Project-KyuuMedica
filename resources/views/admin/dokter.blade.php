@extends('layouts.app')

@section('title', 'Manajemen Dokter')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="my-4 mb-0"><strong>Data Dokter</strong></h4>
        <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addDokterModal">
            Tambah Dokter
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel daftar dokter -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Poli</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dokters as $dokter)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dokter->nama }}</td>
                    <td>{{ $dokter->alamat }}</td>
                    <td>{{ $dokter->no_hp }}</td>
                    <td>{{ $dokter->poli->nama_poli }}</td>
                    <td>
                        <!-- Button untuk membuka modal edit dokter -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editDokterModal{{ $dokter->id }}">
                            Edit
                        </button>

                        <!-- Form hapus dokter -->
                        <form action="{{ route('admin.dokter.destroy', $dokter->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus dokter ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit Dokter -->
                <div class="modal fade" id="editDokterModal{{ $dokter->id }}" tabindex="-1" role="dialog" aria-labelledby="editDokterModalLabel{{ $dokter->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editDokterModalLabel{{ $dokter->id }}">Edit Dokter</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.dokter.update', $dokter->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <!-- Tampilkan Pesan Kesalahan -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="nama">Nama Dokter</label>
                                        <input type="text" name="nama" id="nama" class="form-control" value="{{ $dokter->nama }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" id="alamat" class="form-control" required>{{ $dokter->alamat }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_hp">No. HP</label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ $dokter->no_hp }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_poli">Poli</label>
                                        <select name="id_poli" id="id_poli" class="form-control" required>
                                            @foreach ($polis as $poli)
                                                <option value="{{ $poli->id }}" {{ $dokter->id_poli == $poli->id ? 'selected' : '' }}>{{ $poli->nama_poli }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Password (Opsional) -->
                                    <div class="form-group">
                                        <label for="password">Password (Opsional)</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Dokter -->
<div class="modal fade" id="addDokterModal" tabindex="-1" role="dialog" aria-labelledby="addDokterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDokterModalLabel">Tambah Dokter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Tambah Dokter -->
<form action="{{ route('admin.dokter.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <!-- Tampilkan Pesan Kesalahan -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="nama">Nama Dokter</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
        </div>
        <div class="form-group">
            <label for="no_hp">No. HP</label>
            <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" required>
        </div>
        <div class="form-group">
            <label for="id_poli">Poli</label>
            <select name="id_poli" id="id_poli" class="form-control" required>
                @foreach ($polis as $poli)
                    <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>{{ $poli->nama_poli }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Tambah Dokter</button>
    </div>
</form>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Reset form ketika modal tambah dokter dibuka
    $('#addDokterModal').on('show.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
</script>
@endsection
