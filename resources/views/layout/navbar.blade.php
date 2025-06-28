{{-- <nav class="navbar navbar-expand-lg shadow-sm bg-white px-3"> --}}
{{-- <nav class="navbar navbar-expand-lg shadow-sm bg-white px-3 sticky-top"> --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top px-3 shadow-sm">

  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
      <img src="{{ asset('img/sampah2.png') }}" alt="Logo" width="40" height="40" class="rounded-circle me-2" />
      {{-- <span class="fw-semibold text-dark">Monitoring Sampah</span> --}}
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active text-primary' : '' }}" href="{{ url('/') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('monitoring') ? 'active text-primary' : '' }}" href="{{ route('monitoring.index') }}">Monitoring</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('data-sampah') ? 'active text-primary' : '' }}" href="{{ url('/data-sampah') }}">Laporan</a>
        </li>
        {{-- <li class="nav-item">
          <a class="nav-link {{ request()->is('laporan') ? 'active text-primary' : '' }}" href="{{ url('/laporan') }}">Laporan</a>
        </li> --}}
        <li class="nav-item">
          <a class="nav-link {{ request()->is('pengaturan') ? 'active text-primary' : '' }}" href="{{ url('/pengaturan') }}">Pengaturan</a>
        </li>
      </ul>

      <div class="text-dark fw-semibold d-flex align-items-center" id="datetime"></div>
    </div>
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
