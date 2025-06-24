@extends('layouts.app')

@section('title', 'Manajemen Poli')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="my-4 mb-0"><strong>Data Poli</strong></h4>
        <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addPoliModal">
            Tambah Poli
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
                <th>Nama Poli</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($polis as $poli)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $poli->nama_poli }}</td>
                    <td>{{ $poli->keterangan }}</td>
                    <td>
                        <!-- Button untuk membuka modal edit poli -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPoliModal{{ $poli->id }}">
                            Edit
                        </button>

                        <!-- Form hapus poli -->
                        <form action="{{ route('admin.poli.destroy', $poli->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus poli ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit Poli -->
                <div class="modal fade" id="editPoliModal{{ $poli->id }}" tabindex="-1" role="dialog" aria-labelledby="editPoliModalLabel{{ $poli->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPoliModalLabel{{ $poli->id }}">Edit Poli</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.poli.update', $poli->id) }}" method="POST">
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
                                        <label for="nama_poli">Nama Poli</label>
                                        <input type="text" name="nama_poli" id="nama_poli" class="form-control" value="{{ old('nama_poli', $poli->nama_poli) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan', $poli->keterangan) }}</textarea>
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

<!-- Modal Tambah Poli -->
<div class="modal fade" id="addPoliModal" tabindex="-1" role="dialog" aria-labelledby="addPoliModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPoliModalLabel">Tambah Poli</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.poli.store') }}" method="POST">
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
                        <label for="nama_poli">Nama Poli</label>
                        <input type="text" name="nama_poli" id="nama_poli" class="form-control" value="{{ old('nama_poli') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Poli</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Reset form ketika modal tambah poli dibuka
    $('#addPoliModal').on('show.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
</script>
@endsection
