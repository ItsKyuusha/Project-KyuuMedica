@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<style>
  .btn-toggle-group {
    margin-bottom: 20px;
  }

  .small-box p {
    font-size: 1.1rem;
  }

  /* Ukuran maksimal Pie Chart */
  #chartPasien {
    max-width: 100%;
    max-height: 300px;
    margin: 0 auto;
  }
</style>

<div class="container-fluid">
  <h3 class="mb-4">Dashboard Admin</h3>

  {{-- Box stats --}}
  <div class="row g-4">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner">
          <p>Dokter</p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <p>Pasien</p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning text-dark">
        <div class="inner">
          <p>Poli</p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
        <div class="inner">
          <p>Obat</p>
        </div>
      </div>
    </div>
  </div>
@endsection
