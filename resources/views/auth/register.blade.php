<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"> 
  <meta name="description" content="Bengkel Koding UTS">
  <meta name="keywords" content="Laravel 12">
  <meta name="author" content="David Sugiarto">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KyuuMedica - Management Health System</title>
  <link rel="shortcut icon" href="{{ asset('storage/logo.png') }}" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">
</head>
<style>
  body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #a8edea, #fed6e3);
      background-size: 300% 300%;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
</style>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="#">
        <img src="{{ asset('AdminLTE/logo.png') }}" 
            alt="Logo" 
            class="brand-image" 
            style="opacity: .9; width: 250px; height: auto;">
      </a>
    </a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg"> Register a new membership</p>
      <form action="{{ route('register') }}" method="POST">
      @csrf
        <!-- Nama -->
        <div class="input-group mb-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
        </div>

        <!-- Alamat -->
        <div class="input-group mb-3">
            <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div></div>
        </div>

        <!-- Nomor HP -->
        <div class="input-group mb-3">
            <input type="text" name="no_hp" class="form-control" placeholder="Nomor HP" required>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-phone"></span></div></div>
        </div>

        <!-- Email -->
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
        </div>

        <!-- Password -->
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
        </div>

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
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
