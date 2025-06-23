<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"> 
  <meta name="description" content="Bengkel Koding UTS">
  <meta name="keywords" content="Laravel 12">
  <meta name="author" content="David Sugiarto">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KyuuMedica - Management Health System</title>
  <link rel="shortcut icon" href="{{ asset('storage/logo.png') }}" type="image/x-icon">

  <!-- Fonts & AdminLTE -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;600&display=swap">
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">

  <style>

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: url('{{ asset("AdminLTE/background.jpg") }}') no-repeat center center fixed;
      background-size: cover;
      position: relative;
      z-index: 0;
    }

    .hero-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      padding: 2rem;
      position: relative;
      z-index: 1;
    }

    .hero-text {
      flex: 1 1 450px;
      padding: 2rem;
      text-align: center;
    }

    .hero-text img.logo {
      max-width: 200px;
      height: auto;
      margin-bottom: 1.5rem;
      filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2));
      animation: fadeInDown 1s ease forwards;
      opacity: 0;
    }

    .hero-text p {
      color: #2c3e50;
      font-size: 1.15rem;
      line-height: 1.7;
      max-width: 650px;
      margin-left: auto;
      margin-right: auto;
      text-align: justify;
      letter-spacing: 0.03em;
      text-shadow: 0 1px 1px rgba(255,255,255,0.7);
      animation: fadeInUp 1.2s ease forwards;
      opacity: 0;
    }

    .hero-buttons {
      margin-top: 1.5rem;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
      animation: fadeInUp 1.5s ease forwards;
      opacity: 0;
    }

    .hero-buttons a {
        min-width: 120px;       /* dikurangi dari 140px */
        font-weight: 500;
        padding: 0.4rem 1.2rem; /* padding dikurangi */
        border-radius: 30px;
        font-size: 0.9rem;      /* ukuran font diperkecil */
        transition: all 0.3s ease;
        text-align: center;
    }


    .hero-buttons a.btn-primary:hover {
      background-color: #1d4ed8;
    }

    .hero-buttons a.btn-success:hover {
      background-color: #059669;
    }

    /* Tetap ada flex kanan tapi kosong */
    .hero-img {
      flex: 1 1 500px;
      text-align: center;
      padding: 1rem;
    }

    @media (max-width: 768px) {
      .hero-text {
        padding: 1.5rem;
      }
    }

    /* Animasi fade in from top and bottom */
    @keyframes fadeInDown {
      0% {
        opacity: 0;
        transform: translateY(-20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

  </style>
</head>
<body>

<div class="hero-container">
  
  <!-- Kiri: Logo + teks bawahnya + tombol -->
  <div class="hero-text">
    <img src="{{ asset('AdminLTE/logo.png') }}" alt="KyuuMedica Logo" class="logo">
        <p>
        Sistem manajemen kesehatan digital yang modern dan ramah untuk pasien dan dokter. KyuuMedica menyediakan solusi lengkap untuk memudahkan pengelolaan rekam medis, jadwal konsultasi, dan komunikasi antara pasien dan tenaga medis secara efisien, aman, dan terpercaya.
        </p>


    <div class="hero-buttons">
      <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-sign-in-alt me-1"></i> Login
      </a>
      <a href="{{ route('register') }}" class="btn btn-success btn-lg">
        <i class="fas fa-user-plus me-1"></i> Daftar
      </a>
    </div>
  </div>

  <!-- Kanan: Kosong tetap ada untuk layout -->
  <div class="hero-img"></div>

</div>

<!-- Scripts -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
