@extends('layout.app')

@section('title', 'Beranda')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1 class="fw-bold">Selamat Datang di Aplikasi Monitoring Sampah</h1>
        <p class="text-muted">Sistem ini digunakan untuk memantau kapasitas volume sampah secara real-time.</p>
    </div>

    <div class="row text-center mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="fas fa-dumpster fa-2x text-primary mb-2"></i>
                    <h5 class="card-title">Tong Aktif</h5>
                    <p class="card-text fs-4">{{ $jumlahTong ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                    <h5 class="card-title">Rata-rata Volume</h5>
                    <p class="card-text fs-4">{{ $rataVolume ?? 0 }} Liter</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                    <h5 class="card-title">Tong Penuh (&gt;80%)</h5>
                    <p class="card-text fs-4">{{ $tongPenuh ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h5 class="mb-3">Grafik Volume Sampah (7 Hari Terakhir)</h5>
        <canvas id="grafikHome" height="120"></canvas>
    </div>

    <div class="text-end text-muted small">
        Data diperbarui terakhir: {{ now()->format('d M Y, H:i') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikHome').getContext('2d');
    const grafik = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels ?? ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']) !!},
            datasets: [{
                label: 'Volume Sampah (Liter)',
                data: {!! json_encode($values ?? [10, 12, 9, 14, 15, 11, 13]) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.5,
                categoryPercentage: 0.5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#333',
                    titleFont: { size: 14 },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#444', font: { size: 12 } },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { color: '#444', font: { size: 12 } },
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    }
                }
            }
        }
    });
</script>
@endsection
