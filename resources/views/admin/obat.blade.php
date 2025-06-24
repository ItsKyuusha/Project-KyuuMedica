@extends('layouts.app')

@section('title', 'Manajemen Obat')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="my-4 mb-0"><strong>Data Obat</strong></h4>
        <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addObatModal">
            Tambah Obat
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Obat</th>
                <th>Kemasan</th>
                <th>Harga</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($obats as $obat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obat->nama }}</td>
                    <td>{{ $obat->kemasan }}</td>
                    <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editObatModal{{ $obat->id }}">
                            Edit
                        </button>

                        <form action="{{ route('admin.obat.destroy', $obat->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus obat ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editObatModal{{ $obat->id }}" tabindex="-1" role="dialog" aria-labelledby="editObatModalLabel{{ $obat->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Obat</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.obat.update', $obat->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="nama">Nama Obat</label>
                                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $obat->nama) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kemasan">Kemasan</label>
                                        <input type="text" name="kemasan" class="form-control" value="{{ old('kemasan', $obat->kemasan) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="number" name="harga" class="form-control" value="{{ old('harga', $obat->harga) }}" min="0" required>
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

<!-- Modal Tambah Obat -->
<div class="modal fade" id="addObatModal" tabindex="-1" role="dialog" aria-labelledby="addObatModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Obat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.obat.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Obat</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="kemasan">Kemasan</label>
                        <input type="text" name="kemasan" class="form-control" value="{{ old('kemasan') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Obat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#addObatModal').on('show.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
</script>
@endsection
