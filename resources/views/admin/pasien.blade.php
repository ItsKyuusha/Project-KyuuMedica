@extends('layouts.app')

@section('title', 'Manajemen Pasien')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="my-4 mb-0"><strong>Data Pasien</strong></h4>
        <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addPasienModal">
            Tambah Pasien
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. KTP</th>
                <th>No. HP</th>
                <th>No. RM</th>
                <th>Actions</th> <!-- Kolom aksi untuk edit dan delete -->
            </tr>
        </thead>
        <tbody>
            @foreach ($pasiens as $pasien)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pasien->nama }}</td>
                    <td>{{ $pasien->alamat }}</td>
                    <td>{{ $pasien->no_ktp }}</td>
                    <td>{{ $pasien->no_hp }}</td>
                    <td>{{ $pasien->no_rm }}</td>
                    <td>
                        <!-- Button untuk membuka modal edit pasien -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPasienModal{{ $pasien->id }}">
                            Edit
                        </button>

                        <!-- Form hapus pasien -->
                        <form action="{{ route('admin.pasien.destroy', $pasien->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pasien ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit Pasien -->
<!-- Modal Edit Pasien -->
<div class="modal fade" id="editPasienModal{{ $pasien->id }}" tabindex="-1" role="dialog" aria-labelledby="editPasienModalLabel{{ $pasien->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPasienModalLabel{{ $pasien->id }}">Edit Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.pasien.update', $pasien->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
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
                        <label for="nama">Nama Pasien</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pasien->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required>{{ old('alamat', $pasien->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="no_ktp">No. KTP</label>
                        <input type="text" name="no_ktp" id="no_ktp" class="form-control" value="{{ old('no_ktp', $pasien->no_ktp) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $pasien->no_hp) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $pasien->user ? $pasien->user->email : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password (Opsional)</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password jika ingin mengubahnya">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi password baru">
                    </div>
                    <input type="hidden" name="user_id" value="{{ $pasien->user->id }}">
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

<div class="modal fade" id="addPasienModal" tabindex="-1" role="dialog" aria-labelledby="addPasienModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPasienModalLabel">Tambah Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.pasien.store') }}" method="POST">
                @csrf
                <div class="modal-body">
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
                        <label for="nama">Nama Pasien</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="no_ktp">No. KTP</label>
                        <input type="text" name="no_ktp" id="no_ktp" class="form-control" value="{{ old('no_ktp') }}" required>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Pasien</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Reset form ketika modal tambah pasien dibuka
    $('#addPasienModal').on('show.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
</script>
@endsection
