<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Riwayat Measurement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4">Riwayat Measurement</h1>
        <button class="btn btn-success d-print-none" onclick="window.print()">Cetak PDF</button>
    </div>
    <table class="table table-bordered table-sm">
        <thead><tr><th>Tanggal</th><th>Jam</th><th>Anak</th><th>Berat</th><th>Tinggi</th><th>Lingkar Kepala</th><th>Status Berat</th><th>Status Tinggi</th><th>Status LK</th><th>Status Gizi</th><th>Saran</th></tr></thead>
        <tbody>
        @foreach($measurement as $row)
            <tr><td>{{ $row->measurement_date->format('d/m/Y') }}</td><td>{{ substr($row->measurement_time,0,5) }}</td><td>{{ $row->child->name }}</td><td>{{ $row->weight }} kg</td><td>{{ $row->height }} cm</td><td>{{ $row->head_circumference !== null ? $row->head_circumference . ' cm' : '-' }}</td><td>{{ $row->weight_status }}</td><td>{{ $row->height_status }}</td><td>{{ $row->head_circumference_status ?? '-' }}</td><td>{{ $row->overall_status }}</td><td>{!! nl2br(e($row->recommendation)) !!}</td></tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
