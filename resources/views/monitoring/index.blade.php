@extends('layout.app')

@section('title', 'Monitoring Volume Sampah')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Dashboard Monitoring Volume Sampah</h2>
        <small class="text-muted">Update terakhir: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
    </div>

    <a href="{{ route('trash.create') }}" class="btn btn-success mb-3">+ Tambah Tong</a>

    <div class="row g-3">
        @foreach ($data as $item)
            @php
                $kapasitas_maks = 100;
                $persentase = min(100, ($item->volume / $kapasitas_maks) * 100);
                if ($persentase < 50) {
                    $warna = 'success'; $status = 'Aman';
                } elseif ($persentase < 80) {
                    $warna = 'warning'; $status = 'Hampir Penuh';
                } else {
                    $warna = 'danger'; $status = 'Penuh';
                }
            @endphp

            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body" data-lokasi="{{ $item->lokasi }}">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-12 text-center">
                                <h5 class="mb-2 fw-semibold">Sampah {{ $loop->iteration }} - {{ $item->lokasi }}</h5>
                                <img src="{{ asset('img/sampah7.png') }}" alt="Icon Sampah" width="100" class="mb-3">
                                <p class="mb-1"><strong>Volume:</strong> <span class="volume-text">{{ $item->volume }}</span> Liter</p>
                                <p class="text-muted mb-3">Update: <span class="waktu-text">{{ \Carbon\Carbon::parse($item->waktu)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</span></p>

                                <div class="progress position-relative mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $warna }} progress-bar-text d-flex align-items-center justify-content-center text-white fw-bold" style="width: {{ $persentase }}%;">
                                        {{ round($persentase) }}%
                                    </div>
                                </div>
                                <span class="badge bg-{{ $warna }} status-text d-block mb-3">Status: {{ $status }}</span>
                            </div>

                            <div class="col-lg-6 col-12 d-flex flex-column justify-content-between">
                                <div class="overflow-auto" style="max-width: 100%; overflow-x: auto;">
                                    <canvas id="chart-{{ $loop->iteration }}" class="chart-realtime" data-lokasi="{{ $item->lokasi }}" height="200"></canvas>
                                </div>

                                <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
                                    <a href="{{ route('trash.edit', $item->id) }}" class="btn btn-outline-primary btn-sm rounded-circle" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('trash.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 1051">
    <div id="toast-alert" class="toast align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                ⚠️ Tong sampah penuh! Harap segera dikosongkan.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('toast-alert');
    const toastInstance = toastEl ? new bootstrap.Toast(toastEl) : null;
    const charts = {};

    document.querySelectorAll('.chart-realtime').forEach(canvas => {
        const ctx = canvas.getContext('2d');
        const lokasi = canvas.dataset.lokasi;

        charts[lokasi] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Volume Sampah (%)',
                    data: [],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.2)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                animation: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false },
                    title: {
                        display: true,
                        text: (ctx) => `Grafik Volume - ${lokasi}`
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Waktu' },
                        grid: { display: true }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: { display: true, text: 'Volume (%)' },
                        grid: { display: true }
                    }
                }
            }
        });
    });

    function updateCharts() {
        Object.keys(charts).forEach(lokasi => {
            fetch(`/api/grafik/${encodeURIComponent(lokasi)}`)
                .then(res => res.json())
                .then(data => {
                    const chart = charts[lokasi];
                    const labels = [];
                    const values = [];
                    let latest = null;

                    data.slice(-10).forEach(item => {
                        const date = new Date(item.waktu);
                        const time = date.toLocaleTimeString('id-ID', {
                            hour: '2-digit', minute: '2-digit',
                            hour12: false
                        });
                        labels.push(time);
                        values.push(item.volume);
                        latest = item;
                    });

                    chart.data.labels = labels;
                    chart.data.datasets[0].data = values;
                    chart.update();

                    if (latest) {
                        const card = document.querySelector(`[data-lokasi="${lokasi}"]`);
                        card.querySelector('.volume-text').textContent = latest.volume;

                        const date = new Date(latest.waktu);
                        card.querySelector('.waktu-text').textContent = date.toLocaleString('id-ID');

                        let warna = 'success', status = 'Aman';
                        if (latest.volume >= 80) {
                            warna = 'danger'; status = 'Penuh';
                            if (toastInstance) {
                                toastEl.querySelector('.toast-body').textContent = `⚠️ Tong ${lokasi} sudah penuh! (Volume: ${latest.volume}%)`;
                                toastInstance.show();
                            }
                        } else if (latest.volume >= 50) {
                            warna = 'warning'; status = 'Hampir Penuh';
                        }

                        const bar = card.querySelector('.progress-bar-text');
                        bar.style.width = latest.volume + '%';
                        bar.textContent = Math.round(latest.volume) + '%';
                        bar.className = 'progress-bar bg-' + warna + ' progress-bar-text d-flex align-items-center justify-content-center text-white fw-bold';

                        const badge = card.querySelector('.status-text');
                        badge.textContent = 'Status: ' + status;
                        badge.className = 'badge bg-' + warna + ' d-block mb-3 status-text';
                    }
                });
        });
    }

    updateCharts();
    setInterval(updateCharts, 5000);
});
</script>
@endpush
