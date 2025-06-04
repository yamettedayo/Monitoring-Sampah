@extends('layout.app')

@section('title', 'Dashboard Monitoring Sampah')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Dashboard Monitoring Sampah</h2>
    <p>Update terakhir: {{ now()->format('d M Y H:i') }}</p>

    <div class="row mb-4">
        <div class="col-md-12">
            <h5>Grafik Volume Sampah per Lokasi</h5>
            <canvas id="sampahChart"></canvas>
        </div>
    </div>

    <div class="row">
        @foreach ($data as $item)
            @php
                $persentase = ($item->volume / 20) * 100; // Misal kapasitas max 20L
                if ($persentase < 50) {
                    $warna = 'success'; // hijau
                    $status = 'Aman';
                } elseif ($persentase < 80) {
                    $warna = 'warning'; // kuning
                    $status = 'Perlu Perhatian';
                } else {
                    $warna = 'danger'; // merah
                    $status = 'Penuh';
                }
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card shadow rounded">
                    <div class="card-body text-center">
                        <h5 class="mb-2">Sampah {{ $loop->iteration }}</h5>
                        <img src="{{ asset('img/trash-icon.png') }}" alt="Tong Sampah" width="200" onerror="this.src='https://cdn-icons-png.flaticon.com/512/679/679720.png'">
                        <h6 class="mt-3">{{ $item->lokasi }}</h6>
                        <p class="mb-1">Volume: {{ $item->volume }} Liter</p>
                        <p class="text-muted" style="font-size: 0.85rem;">Waktu: {{ $item->waktu }}</p>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-{{ $warna }}" role="progressbar" style="width: {{ $persentase }}%">
                                {{ round($persentase) }}%
                            </div>
                        </div>
                        <p class="fw-bold text-{{ $warna }}">Status: {{ $status }}</p>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('sampahChart').getContext('2d');
    const sampahChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data->pluck('lokasi')) !!},
            datasets: [{
                label: 'Volume Sampah (Liter)',
                data: {!! json_encode($data->pluck('volume')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderRadius: 5
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20
                }
            }
        }
    });

    // Auto-refresh setiap 30 detik
    setInterval(() => {
        location.reload();
    }, 30000);
</script>
@endsection
