@extends('layout.app')

@section('title', 'Edit Data Tong')

@section('content')
<div class="container">
  <h2 class="mb-3">Edit Data Tong Sampah</h2>
  <form action="{{ route('trash.update', $trash->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="lokasi" class="form-label">Lokasi</label>
      <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{ $trash->lokasi }}" required>
    </div>

    <div class="mb-3">
      <label for="volume" class="form-label">Volume (Liter)</label>
      <input type="number" name="volume" id="volume" class="form-control" value="{{ $trash->volume }}" required>
    </div>

    <div class="mb-3">
      <label for="waktu" class="form-label">Waktu</label>
      <input type="datetime-local" name="waktu" id="waktu" class="form-control" value="{{ \Carbon\Carbon::parse($trash->waktu)->format('Y-m-d\TH:i') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
  </form>
</div>
@endsection


