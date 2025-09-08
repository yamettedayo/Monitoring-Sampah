@extends('layout.app')

@section('title', 'Monitoring Volume Sampah')

@php
    $adaTongPenuh = false;
    foreach ($data as $item) {
        if ($item->volume > 8) {
            $adaTongPenuh = true;
            break;
        }
    }
@endphp

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Dashboard Monitoring Volume Sampah</h2>
        <small class="text-muted">Update terakhir: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
    </div>

    <a href="{{ route('trash.create') }}" class="btn btn-success mb-3">+ Tambah Tong</a>

    <div class="row g-3">
        @forelse ($data as $item)
            @include('monitoring._box', ['item' => $item])
        @empty
            <div class="col-12">
                <div class="card shadow-sm text-center p-5">
                    <h4 class="mb-3">Belum ada data tong sampah</h4>
                    <p class="text-muted">Silakan tambahkan data terlebih dahulu melalui tombol "Tambah Tong".</p>
                    <img src="{{ asset('img/sampah7.png') }}" alt="Tong Sampah" width="120" class="mb-3">
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Container Toast --}}
<div class="position-fixed top-0 end-0 p-3" id="toast-container" style="z-index: 1055"></div>

{{-- Kirim data volume ke JS --}}
<script>
    const tongData = @json($data);
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kapasitasMaks = 10;
    const shownToasts = new Set();
    const toastContainer = document.getElementById('toast-container');

    function tampilkanToast(lokasi) {
        if (shownToasts.has(lokasi)) return;

        const toastId = `toast-${btoa(lokasi).replace(/=/g, '')}`;
        const toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-white bg-danger border-0 mb-2';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ⚠ Tong <strong>${lokasi}</strong> sudah penuh! Harap segera dikosongkan
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl);
        toast.show();

        shownToasts.add(lokasi);
    }

    function cekVolume() {
        tongData.forEach(item => {
            const persen = (item.volume / kapasitasMaks) * 100;
            if (persen >= 80) {
                tampilkanToast(item.lokasi);
            }
        });
    }

    cekVolume(); // pertama kali
    setInterval(cekVolume, 5000); // real-time
});
</script>
@endpush
