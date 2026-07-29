@extends('layouts.app', ['title' => 'Pantau'])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-between mb-3">
        <form class="d-flex gap-2">
            <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama anak">
            <button class="btn btn-outline-success"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Nama Anak</th>
                    <th>Gender</th>
                    <th>Tanggal Lahir</th>
                    <th>Usia Anak</th>
                    <th>Grafik</th>
                    <th>Riwayat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($children as $child)
                <tr>
                    <td>{{ $child->name }}</td>
                    <td>{{ $child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $child->birth_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $child->ageMonths() }} bulan</td>
                    <td>
                        <a class="btn btn-sm btn-primary text-white" href="{{ route('admin.pantau-anak.grafik', $child->id) }}" title="Lihat Grafik">
                            <i class="bi bi-graph-up me-1"></i> Grafik
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-secondary text-white" href="{{ route('admin.pantau-anak.riwayat', $child->id) }}" title="Lihat Riwayat">
                            <i class="bi bi-clock-history me-1"></i> Riwayat
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-info text-white fw-semibold" href="{{ route('admin.pantau-anak', $child->id) }}" title="Pantau">
                            <i class="bi bi-display me-1"></i> Pantau
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Tidak ada data anak.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $children->links() }}
</div></div>
@endsection
