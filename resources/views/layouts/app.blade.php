<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Posyandu IoT' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { 
            /* Skema Warna Pastel Soft Hijau/Biru */
            --sidebar-bg: #e0f2fe; /* Biru pastel sangat soft */
            --sidebar-active: #bae6fd; /* Biru pastel sedikit lebih gelap untuk aktif */
            --sidebar-text: #0369a1; /* Teks biru gelap agar kontras */
            --pastel-bg: #f8fafc; /* Background sangat soft off-white/blue */
            --table-header: #dcfce7; /* Hijau pastel untuk header tabel */
            --table-text: #166534; /* Teks hijau gelap untuk header */
        }
        body { background: var(--pastel-bg); color: #334155; }
        .sidebar { width:260px; min-height:100vh; background: var(--sidebar-bg); border-right: 1px solid #e2e8f0; position:fixed; inset:0 auto 0 0; color: var(--sidebar-text); }
        .brand { color: var(--sidebar-text); font-weight:800; letter-spacing:0; }
        .nav-link { color: #0284c7; border-radius:12px; margin:.25rem .75rem; padding: 12px 20px; transition: 0.3s ease; }
        .nav-link i { color: #0ea5e9; }
        .nav-link:hover, .nav-link.active { background: var(--sidebar-active); color: var(--sidebar-text); font-weight: 600; box-shadow: none; }
        .nav-link:hover i, .nav-link.active i { color: var(--sidebar-text); }
        .main { margin-left:260px; min-height:100vh; }
        .topbar { background: #6cd8c5; border-bottom: 1px solid rgba(0,0,0,0.05); color: #064e3b; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .topbar .text-muted { color: #064e3b !important; opacity: 0.8; }
        .metric { border:0; border-radius:12px; background: #fff; box-shadow:0 4px 20px rgba(0,0,0,.02); }
        .btn-success { background: #6ee7b7; border-color: #6ee7b7; color: #064e3b; font-weight: 500; }
        .btn-success:hover { background: #34d399; border-color: #34d399; color: #fff; }
        
        /* Table Styling */
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: hidden; background: #fff; }
        .table { margin-bottom: 0; }
        .table thead th { background: var(--table-header); color: var(--table-text); font-weight: 600; border: 1px solid #86efac; padding: 15px; font-size: 14px; text-align: center; }
        .table thead th:first-child { border-top-left-radius: 12px; }
        .table thead th:last-child { border-top-right-radius: 12px; }
        .table tbody td { padding: 15px; vertical-align: middle; font-size: 14px; border: 1px solid #cbd5e1; color: #475569; text-align: center; }
        .badge-status { padding: 6px 12px; border-radius: 6px; font-weight: 500; font-size: 12px; }
        
        @media (max-width: 991.98px) {
            .sidebar { position:relative; width:100%; min-height:auto; }
            .main { margin-left:0; }
        }
    </style>
</head>
<body>
@auth
    @include('layouts.sidebar')
    <main class="main">
        @include('layouts.topbar')
        <div class="p-4">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>
@else
    @yield('content')
@endauth
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-confirm]').forEach(form => {
            form.addEventListener('submit', event => {
                event.preventDefault();
                Swal.fire({title:'Hapus data?', text:'Data yang dihapus tidak dapat dikembalikan.', icon:'warning', showCancelButton:true, confirmButtonText:'Hapus', cancelButtonText:'Batal'}).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tables = document.querySelectorAll('.table');
        tables.forEach(table => {
            // Auto align-left untuk kolom Nama
            const headers = table.querySelectorAll('thead th');
            headers.forEach((th, index) => {
                if (th.textContent.toLowerCase().includes('nama')) {
                    th.style.textAlign = 'left';
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        if(cells[index]) {
                            cells[index].style.textAlign = 'left';
                        }
                    });
                }
            });

            // Pewarnaan background Gender
            const allCells = table.querySelectorAll('tbody td');
            allCells.forEach(cell => {
                const text = cell.textContent.trim();
                if (text === 'Laki-laki') {
                    cell.innerHTML = `<span style="background-color: #dbeafe; color: #1e3a8a; padding: 4px 12px; border-radius: 12px; font-weight: 600; display: inline-block;">Laki-laki</span>`;
                } else if (text === 'Perempuan') {
                    cell.innerHTML = `<span style="background-color: #fbcfe8; color: #831843; padding: 4px 12px; border-radius: 12px; font-weight: 600; display: inline-block;">Perempuan</span>`;
                }
            });
        });
    });
</script>
</body>
</html>
