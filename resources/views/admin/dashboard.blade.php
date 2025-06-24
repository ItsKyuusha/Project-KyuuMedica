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

  <div class="btn-toggle-group">
    <a href="{{ route('admin.dashboard', ['type' => 'daily']) }}" 
       class="btn btn-outline-primary {{ $type === 'daily' ? 'active' : '' }}">Harian</a>
    <a href="{{ route('admin.dashboard', ['type' => 'monthly']) }}" 
       class="btn btn-outline-primary {{ $type === 'monthly' ? 'active' : '' }}">Bulanan</a>
  </div>

  {{-- Box stats --}}
  <div class="row g-4">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $jumlahDokter }}</h3>
          <p>Dokter</p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $jumlahPasien }}</h3>
          <p>Pasien</p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning text-dark">
        <div class="inner">
          <h3>{{ $jumlahPoli }}</h3>
          <p>Poli</p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{ $jumlahObat }}</h3>
          <p>Obat</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Charts --}}
  <div class="row mt-5 g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0">Statistik Dokter {{ $type === 'daily' ? '(Per Hari)' : '(Per Bulan)' }}</h5>
        </div>
        <div class="card-body">
          <canvas id="chartDokter"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Statistik Pasien {{ $type === 'daily' ? '(Per Hari)' : '(Per Bulan)' }}</h5>
        </div>
        <div class="card-body">
          <canvas id="chartPasien"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4 g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
          <h5 class="mb-0">Statistik Poli {{ $type === 'daily' ? '(Per Hari)' : '(Per Bulan)' }}</h5>
        </div>
        <div class="card-body">
          <canvas id="chartPoli"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
          <h5 class="mb-0">Statistik Obat {{ $type === 'daily' ? '(Per Hari)' : '(Per Bulan)' }}</h5>
        </div>
        <div class="card-body">
          <canvas id="chartObat"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = @json($labels);

  const dataDokter = @json($statDokter);
  const dataPasien = @json($statPasien);
  const dataPoli = @json($statPoli);
  const dataObatRaw = @json($statObat);
  const dataObat = labels.map((label, i) => ({ x: label, y: dataObatRaw[i] }));

  // Bar chart Dokter
  new Chart(document.getElementById('chartDokter').getContext('2d'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Dokter',
        data: dataDokter,
        backgroundColor: 'rgba(23, 162, 184, 0.7)',
        borderColor: 'rgba(23, 162, 184, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true, stepSize: 1 } }
    }
  });

  // Ganti Pasien menjadi Bar chart
  new Chart(document.getElementById('chartPasien').getContext('2d'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Pasien',
        data: dataPasien,
        backgroundColor: 'rgba(40, 167, 69, 0.7)',
        borderColor: 'rgba(40, 167, 69, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true, stepSize: 1 } }
    }
  });

  // Line chart Poli
  new Chart(document.getElementById('chartPoli').getContext('2d'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Poli',
        data: dataPoli,
        fill: true,
        backgroundColor: 'rgba(255, 193, 7, 0.3)',
        borderColor: 'rgba(255, 193, 7, 1)',
        borderWidth: 2,
        tension: 0.25,
        pointRadius: 4,
        pointHoverRadius: 6,
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true, stepSize: 1 } }
    }
  });

  // Scatter chart Obat
  new Chart(document.getElementById('chartObat').getContext('2d'), {
    type: 'scatter',
    data: {
      datasets: [{
        label: 'Obat',
        data: dataObat,
        backgroundColor: 'rgba(220, 53, 69, 0.8)',
        borderColor: 'rgba(220, 53, 69, 1)',
        pointRadius: 6,
        pointHoverRadius: 8,
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          type: 'category',
          labels: labels,
          title: { display: true, text: '{{ $type === "daily" ? "Tanggal" : "Bulan" }}' }
        },
        y: {
          beginAtZero: true,
          stepSize: 1,
          title: { display: true, text: 'Jumlah' }
        }
      }
    }
  });
</script>
@endsection
