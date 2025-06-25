@extends('layouts.app') {{-- Ganti jika layout-nya berbeda --}}

@section('content')
<div class="container mt-4">
    <h2>Profil Saya</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dokter.profil.update') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" name="nama" value="{{ old('nama', $user->nama) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" name="alamat" value="{{ old('alamat', $user->alamat) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="no_hp">No HP</label>
            <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>

        <div class="form-group mb-3">
            <label for="id_poli">Poli</label>
            <select name="id_poli" class="form-control" required>
                @foreach ($polis as $poli)
                    <option value="{{ $poli->id }}" {{ $dokter->id_poli == $poli->id ? 'selected' : '' }}>
                        {{ $poli->nama_poli }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr>

        <div class="form-group mb-3">
            <label for="password">Password Baru (opsional)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
