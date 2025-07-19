@extends('layout.app')

@section('title', 'Data Volume Sampah')

@section('content')
<div class="container py-4">
    {{-- Data Volume Section --}}
    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-4 border-bottom pb-3">
                <div>
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-clipboard-data text-primary me-2"></i> Data Tong Sampah
                    </h4>
                    <p class="text-muted small mb-0">Lihat daftar tong sampah aktif.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <div class="input-group shadow-sm" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari lokasi...">
                </div>
                <a href="{{ route('trashlog.export') }}" class="btn btn-success btn-sm d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle text-nowrap" id="trashTable">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="text-center">No</th>
                            {{-- <th>Volume (Liter)</th> --}}
                            <th>Volume (Liter)</th>
                            <th>Lokasi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                            <tr class="border-top">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->volume }}</td>
                                <td>{{ $item->lokasi }}</td>
                                {{-- <td>{{ \Carbon\Carbon::parse($item->waktu)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($item->waktu)->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Volume Section --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-4 border-bottom pb-3">
                <div>
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-clock-history text-secondary me-2"></i> Riwayat  Sampah
                    </h4>
                    <p class="text-muted small mb-0">Lihat riwayat volume sampah dan ekspor sesuai kebutuhan.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Cari lokasi...">
                    </div>
                    <select name="show" class="form-select shadow-sm w-auto">
                        @foreach([10, 25, 50, 100] as $count)
                            <option value="{{ $count }}" {{ request('show') == $count ? 'selected' : '' }}>{{ $count }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-outline-secondary btn-sm shadow-sm">
                        <i class="bi bi-funnel me-1"></i> Tampilkan
                    </button>
                </form>
                <a href="{{ route('trashlog.export') }}" class="btn btn-success btn-sm d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle text-nowrap">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="text-center">No</th>
                            {{-- <th>Volume (Liter)</th> --}}
                            <th>Volume (Liter)</th>
                            <th>Lokasi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $index => $item)
                            <tr class="border-top">
                                <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                                <td>{{ $item->volume }}</td>
                                <td>{{ $item->lokasi }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->waktu)->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada riwayat data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}

            {{-- <p class="text-muted small mt-2">
                Menampilkan {{ $logs->count() }} dari total {{ $logs->total() }} entri
            </p> --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#trashTable tbody tr');

        rows.forEach(row => {
            const lokasi = row.children[2].textContent.toLowerCase();
            row.style.display = lokasi.includes(keyword) ? '' : 'none';
        });
    });
</script>
@endpush
