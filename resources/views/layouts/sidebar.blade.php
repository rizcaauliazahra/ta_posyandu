    <aside class="sidebar p-3">
        <div class="brand fs-4 mb-4 d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 45px; width: auto; mix-blend-mode: multiply;" class="me-2">
            <span>PosCare</span>
        </div>
        <nav class="nav flex-column">
            @if(auth()->user()->isAdmin())
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Pengguna</a>
                <a class="nav-link {{ request()->routeIs('admin.measurement.*') && !request()->routeIs('admin.pantau-anak.index') ? 'active' : '' }}" href="{{ route('admin.measurement.index') }}"><i class="bi bi-rulers me-2"></i>Pengukuran</a>
                <a class="nav-link {{ request()->routeIs('admin.pantau-anak*') ? 'active' : '' }}" href="{{ route('admin.pantau-anak.index') }}"><i class="bi bi-display me-2"></i>Pantau</a>
                <a class="nav-link {{ request()->routeIs('admin.tabel-gizi.*') ? 'active' : '' }}" href="{{ route('admin.tabel-gizi.index') }}"><i class="bi bi-table me-2"></i>Standar Gizi</a>
            @else
                <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="bi bi-house-heart me-2"></i>Dashboard</a>
                <a class="nav-link {{ request()->routeIs('user.grafik') ? 'active' : '' }}" href="{{ route('user.grafik') }}"><i class="bi bi-graph-up me-2"></i>Grafik</a>
                <a class="nav-link {{ request()->routeIs('user.riwayat') ? 'active' : '' }}" href="{{ route('user.riwayat') }}"><i class="bi bi-clock-history me-2"></i>Riwayat</a>
                <a class="nav-link {{ request()->routeIs('user.tabel-gizi') ? 'active' : '' }}" href="{{ route('user.tabel-gizi') }}"><i class="bi bi-table me-2"></i>Standar Gizi</a>
                <a class="nav-link {{ request()->routeIs('user.profil') ? 'active' : '' }}" href="{{ route('user.profil') }}"><i class="bi bi-person-badge me-2"></i>Profil</a>
            @endif

        </nav>
    </aside>
