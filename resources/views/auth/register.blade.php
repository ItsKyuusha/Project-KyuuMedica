<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="description" content="KyuuMedica - Register">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KyuuMedica - Daftar Pasien</title>
  <link rel="shortcut icon" href="{{ asset('storage/logo.png') }}" type="image/x-icon">

  <!-- Fonts & Styles -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #a8edea, #fed6e3);
      background-size: 300% 300%;
      animation: gradientShift 15s ease infinite;
    }
    @keyframes gradientShift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
  </style>
</head>
<body class="hold-transition register-page">

<div class="register-box">
  <div class="register-logo">
    <a href="#">
      <img src="{{ asset('AdminLTE/logo.png') }}" alt="Logo" style="opacity:.9; width: 250px;">
    </a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register Akun Pasien Baru</p>

      {{-- Tampilkan error jika ada --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Tampilkan pesan sukses jika ada --}}
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST">
        @csrf

        {{-- Nama --}}
        <div class="input-group mb-3">
          <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required value="{{ old('nama') }}">
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
        </div>

        {{-- Alamat --}}
        <div class="input-group mb-3">
          <input type="text" name="alamat" class="form-control" placeholder="Alamat" required value="{{ old('alamat') }}">
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div></div>
        </div>

        {{-- No HP --}}
        <div class="input-group mb-3">
          <input type="text" name="no_hp" class="form-control" placeholder="Nomor HP" required value="{{ old('no_hp') }}">
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-phone"></span></div></div>
        </div>

        {{-- No KTP --}}
        <div class="input-group mb-3">
          <input type="text" name="no_ktp" class="form-control" placeholder="Nomor KTP" required value="{{ old('no_ktp') }}">
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-id-card"></span></div></div>
        </div>

        {{-- Email --}}
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
        </div>

        {{-- Password --}}
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
        </div>

        {{-- Konfirmasi Password --}}
        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
        </div>

        {{-- Hidden role --}}
        <input type="hidden" name="role" value="pasien">

        <div class="row">
          <div class="col-8">
            <a href="{{ route('login') }}">Sudah punya akun?</a>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-success btn-block">Daftar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
