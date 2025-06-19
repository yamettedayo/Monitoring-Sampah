@php
    use Carbon\Carbon;
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


<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="text-center">{{ $item->lokasi }}</h5>
        <p><strong>Volume:</strong> <span class="volume-text">{{ $item->volume }}</span> Liter</p>
        <p class="text-muted">Update: 
            <span class="waktu-text">
                {{ Carbon::parse($item->waktu)->setTimezone('Asia/Jakarta')->format('d/m/Y, H:i:s') }}
            </span>
        </p>
        <div class="progress mb-2">
            <div class="progress-bar bg-{{ $warna }} progress-bar-text" style="width: {{ $persentase }}%">
                {{ round($persentase) }}%
            </div>
        </div>
        <span class="badge bg-{{ $warna }} d-block mb-3 status-text">Status: {{ $status }}</span>

        <canvas id="chart-{{ $item->id }}" class="chart-realtime" data-lokasi="{{ $item->lokasi }}" height="200"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const charts = {};

    document.querySelectorAll('.chart-realtime').forEach(canvas => {
        const ctx = canvas.getContext('2d');
        const lokasi = canvas.dataset.lokasi;

        charts[lokasi] = new Chart(ctx, {
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

                    data.forEach(item => {
                        // const date = new Date(item.waktu); // Sudah diperbaiki, HAPUS ' UTC'?
                        const date = new Date(item.waktu.replace(' ', 'T') + '+07:00');
                        const time = date.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false,
                            timeZone: 'Asia/Jakarta'
                        });
                        labels.push(time);
                        values.push(item.volume);
                        latest = item;
                    });

                    chart.data.labels = labels;
                    chart.data.datasets[0].data = values;
                    chart.update();

                    if (latest) {
                        const card = document.querySelector(`[data-lokasi="${lokasi}"]`).closest('.card-body');
                        card.querySelector('.volume-text').textContent = latest.volume;
                        card.querySelector('.waktu-text').textContent = new Date(latest.waktu)
                        // card.querySelector('.waktu-text').textContent = date.toLocaleString('id-ID');
                            .toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' });

                        let warna = 'success';
                        let status = 'Aman';
                        if (latest.volume >= 80) {
                            warna = 'danger'; status = 'Penuh';
                        } else if (latest.volume >= 50) {
                            warna = 'warning'; status = 'Hampir Penuh';
                        }

                        const bar = card.querySelector('.progress-bar-text');
                        bar.style.width = latest.volume + '%';
                        bar.textContent = Math.round(latest.volume) + '%';
                        bar.className = 'progress-bar bg-' + warna + ' progress-bar-text';

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
