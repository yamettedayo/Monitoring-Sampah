@extends('layout.app')

@section('title', 'Tambah Tong Sampah')

@section('content')
<div class="container">
  <h2 class="mb-3">Tambah Tong Sampah</h2>
  <form action="{{ route('trash.store') }}" method="POST">
  @csrf
  <div class="mb-3">
    <label for="lokasi" class="form-label">Lokasi</label>
    <input type="text" name="lokasi" class="form-control" required>
  </div>
  <div class="mb-3">
    <label for="volume" class="form-label">Volume Awal (Liter)</label>
    <input type="number" name="volume" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Tambah Data</button>
</form>

</div>
@endsection
