@extends('layouts.app', ['title' => 'Data Pengukuran'])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-between mb-3"><form class="d-flex gap-2"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari anak"><input type="date" name="date" value="{{ request('date') }}" class="form-control" title="Pilih Tanggal Measurement"><button class="btn btn-outline-success"><i class="bi bi-search"></i></button></form></div>
    <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Nama Anak</th><th>Gender</th><th>Tanggal</th><th>Usia <br>(Bulan)</th><th>Berat <br>(kg)</th><th>Status Berat</th><th>Tinggi <br>(cm)</th><th>Status Tinggi</th><th>Lingkar Kepala <br>(cm)</th><th>Status Kepala</th><th>Aksi</th></tr></thead><tbody>
    @forelse($measurement as $row)
        <tr><td>{{ $row->child->name }}</td><td>{{ $row->child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td><td>{{ $row->measurement_date->format('d/m/Y') }} {{ substr($row->measurement_time,0,5) }}</td><td>{{ $row->child->ageMonths() }}</td><td>{{ $row->weight }}</td><td class="small">{{ $row->weight_status ?? '-' }}</td><td>{{ $row->height }}</td><td class="small">{{ $row->height_status ?? '-' }}</td><td>
            @if($row->head_circumference !== null)
                {{ $row->head_circumference }}
            @else
                -
            @endif
        </td><td class="small">{{ $row->head_circumference_status ?? '-' }}</td><td class="text-end"><form class="d-inline" method="POST" action="{{ route('admin.measurement.destroy',$row) }}" data-confirm>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
    @empty
        <tr><td colspan="11" class="text-center text-muted">Belum ada data.</td></tr>
    @endforelse
    </tbody></table></div>{{ $measurement->links() }}
</div></div>
@endsection

@push('scripts')
<script>
setTimeout(() => window.location.reload(), 10000);
</script>
@endpush
