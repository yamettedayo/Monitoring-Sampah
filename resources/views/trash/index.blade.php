@extends('layout.app')

@section('title', 'Monitoring Sampah')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Data Volume Sampah</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Volume (Liter)</th>
                <th>Lokasi</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->volume }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td>{{ $item->waktu }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
