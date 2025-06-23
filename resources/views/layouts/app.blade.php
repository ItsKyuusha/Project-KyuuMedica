<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"> 
  <meta name="description" content="Bengkel Koding UTS">
  <meta name="keywords" content="Laravel 12">
  <meta name="author" content="David Sugiarto">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KyuuMedica - Management Health System</title>
  <link rel="shortcut icon" href="{{ asset('AdminLTE/logo.png') }}" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">

  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 d-flex align-items-center" data-toggle="modal" data-target="#logoutModal">
        <i class="fas fa-user mr-2"></i>
        <span>
          {{ Auth::user()->name ?? 'Guest' }} 
          ({{ Auth::user()->role ?? 'No Role' }})
        </span>
        <i class="fas fa-sign-out-alt ml-2"></i>
      </button>
    </li>
  </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="text-center d-flex justify-content-center align-items-center">
      <img src="{{ asset('AdminLTE/logo.png') }}" 
           alt="Logo" 
           class="brand-image" 
           style="opacity: .9; width: 200px; height: auto; padding: 20px 0;">
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false" role="menu">
          {{-- Admin --}}
          @if(Auth::user() && Auth::user()->role === 'admin')
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard Admin</p>
            </a>
          </li>
          @endif
          {{-- Dokter --}}
          @if(Auth::user() && Auth::user()->role === 'dokter')
            <li class="nav-item">
              <a href="{{ route('dokter.dashboard') }}" class="nav-link {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard Dokter</p>
              </a>
            </li>
          @endif

          {{-- Pasien --}}
          @if(Auth::user() && Auth::user()->role === 'pasien')
            <li class="nav-item">
              <a href="{{ route('pasien.dashboard') }}" class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard Pasien</p>
              </a>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Page header -->
    <div class="content-header">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      @yield('content')
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer text-sm text-center">
    <strong>&copy; {{ date('Y') }} KyuuMedica</strong> - Sistem Manajemen Kesehatan
  </footer>
</div>

<!-- jQuery -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>

@stack('scripts')

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @elseif(session('error'))
        toastr.error("{{ session('error') }}");
    @elseif(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @elseif(session('info'))
        toastr.info("{{ session('info') }}");
    @endif
</script>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-danger">Ya, Logout</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
