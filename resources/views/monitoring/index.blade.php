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
        @forelse ($data as $item)
            @include('monitoring._box', ['item' => $item])
        @empty
            {{-- Dummy Box Jika Tidak Ada Data --}}
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


{{-- Notifikasi Toast Peringatan Penuh --}}
{{-- <div class="position-fixed top-0 end-0 p-3" style="z-index: 1051">
    <div id="toast-alert" class="toast align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">⚠ Tong sampah penuh! Harap segera dikosongkan.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div> --}}



@endsection
