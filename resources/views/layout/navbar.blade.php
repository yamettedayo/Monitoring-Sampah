<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <a class="navbar-brand d-flex align-items-center" href="#">
    <img src="{{ asset('img/akucantik.jpg') }}" alt="Logo" width="40" height="40" class="rounded-circle me-2" />
    {{-- <span>Dashboard Monitoring Volume Sampah</span> --}}
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('monitoring.index') }}">Monitoring</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/data-sampah') }}">Data Sampah</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/laporan') }}">Laporan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/pengaturan') }}">Pengaturan</a>
      </li>


    </ul>
    <div class="text-white fw-bold d-flex align-items-center" id="datetime"></div>
  </div>
</nav>

<script>
  function updateDateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute:'2-digit', second:'2-digit' };
    document.getElementById('datetime').textContent = now.toLocaleDateString('id-ID', options);
  }
  updateDateTime();
  setInterval(updateDateTime, 1000);
</script>
