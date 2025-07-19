@php
    use Carbon\Carbon;
    $kapasitas_maks = 100;
    $persentase = min(100, ($item->volume / $kapasitas_maks) * 100);

    if ($persentase < 50) {
        $warna = 'success';
        $status = 'Aman';
    } elseif ($persentase < 80) {
        $warna = 'warning';
        $status = 'Hampir Penuh';
    } else {
        $warna = 'danger';
        $status = 'Penuh';
    }
@endphp

<div class="col-12 mb-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body" data-lokasi="{{ $item->lokasi }}">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12 text-center">
                    <h5 class="text-center">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $item->lokasi }}
                    </h5>
                    <img src="{{ asset('img/sampah7.png') }}" alt="Icon Sampah" width="150" class="mb-3">
                    <p class="mb-1"><strong>Volume:</strong> <span class="volume-text">{{ $item->volume }}</span> Liter</p>
                    <p class="text-muted mb-3">
                        Update: <span class="waktu-text">{{ Carbon::parse($item->waktu)->format('Y-m-d H:i:s') }}</span>
                    </p>

                    <div class="progress position-relative mb-2" style="height: 20px;">
                        <div class="progress-bar bg-{{ $warna }} progress-bar-text d-flex align-items-center justify-content-center text-white fw-bold"
                            style="width: {{ $persentase }}%;">
                            {{ round($persentase) }}%
                        </div>
                    </div>

                    <span class="badge bg-{{ $warna }} status-text d-block mb-3">Status: {{ $status }}</span>
                </div>

                <div class="col-lg-6 col-12">
                    <canvas id="chart-{{ $item->id }}" class="chart-realtime" data-lokasi="{{ $item->lokasi }}" height="200"></canvas>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
                    <a href="{{ route('trash.edit', $item->id) }}" class="btn btn-outline-primary btn-sm rounded-circle" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('trash.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById("chart-{{ $item->id }}").getContext("2d");
    const lokasi = "{{ $item->lokasi }}";

    function checkUpdate() {
        fetch(`/api/latest-tong`)
            .then(res => res.json())
            .then(data => {
                if (!data) return;

                const volume = parseFloat(data.volume);
                const kapasitasMaks = 10;
                const persen = Math.min(100, (volume / kapasitasMaks) * 100);

                document.querySelector(`.volume-text`).textContent = volume;

                const waktu = new Date(data.waktu).toLocaleString('id-ID', {
                    year: 'numeric', month: '2-digit', day: '2-digit',
                    hour: '2-digit', minute: '2-digit', second: '2-digit',
                    hour12: false
                });
                document.querySelector(`.waktu-text`).textContent = waktu;

                const progressBar = document.querySelector(`.progress-bar`);
                const statusText = document.querySelector(`.status-text`);

                let warna = '', status = '';
                if (persen < 50) {
                    warna = 'success'; status = 'Aman';
                } else if (persen < 80) {
                    warna = 'warning'; status = 'Hampir Penuh';
                } else {
                    warna = 'danger'; status = 'Penuh';
                }

                progressBar.style.width = `${persen}%`;
                progressBar.className = `progress-bar bg-${warna} progress-bar-text d-flex align-items-center justify-content-center text-white fw-bold`;
                progressBar.textContent = `${Math.round(persen)}%`;

                statusText.className = `badge bg-${warna} status-text d-block mb-3`;
                statusText.textContent = `Status: ${status}`;
            });
    }

    function updateChart() {
        fetch(`/api/grafik/${encodeURIComponent(lokasi)}`)
            .then(res => res.json())
            .then(data => {
                const labels = [];
                const values = [];

                data.slice(-10).forEach(item => {
                    const waktu = new Date(item.waktu).toLocaleTimeString('id-ID', {
                        hour: '2-digit', minute: '2-digit', hour12: false
                    });

                    labels.push(waktu);
                    values.push(item.volume);
                });

                chart.data.labels = labels;
                chart.data.datasets[0].data = values;
                chart.update();
            });
    }

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Volume Sampah (Liter)',
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
            scales: {
                x: {
                    title: { display: true, text: 'Waktu' },
                    ticks: { autoSkip: true, maxTicksLimit: 10 }
                },
                y: {
                    title: { display: true, text: 'Volume (Liter)' },
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    updateChart();
    checkUpdate();
    setInterval(() => {
        updateChart();
        checkUpdate();
    }, 5000);
});
</script>
@endpush
