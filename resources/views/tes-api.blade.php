@extends('layout.app')

@section('title', 'Tes API')

@section('content')
<div class="container mt-4">
    <h3>Form Tes API Kirim Data</h3>
    <form method="POST" action="/tes-api">
        @csrf
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="volume" class="form-label">Volume</label>
            <input type="number" name="volume" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>
@endsection
