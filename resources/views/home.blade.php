@extends('layout.app')

@section('title', 'Beranda')

@section('content')
<div class="container">
    <h1 class="mb-3">Selamat Datang di Aplikasi Monitoring Sampah</h1>
    <p>Aplikasi ini bertujuan untuk memantau dan mengelola data sampah dengan efisien.</p>

    <hr class="my-4">

    <h2 class="mb-4">Grafik Volume Sampah</h2>
    <canvas id="sampahChart" width="400" height="200"></canvas>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('sampahChart').getContext('2d');
  const sampahChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
      datasets: [{
        label: 'Volume Sampah (kg)',
        data: [50, 65, 40, 80, 70],
        backgroundColor: '#33a35e'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
@endsection
