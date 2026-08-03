@extends('layouts.app', ['title' => 'Riwayat Pengukuran'])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h6 mb-0">Riwayat Pengukuran: {{ $child->name }}</h2>
            <a href="{{ route('admin.pantau-anak.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead><tr><th>Usia <br>(Bulan)</th><th>Tanggal</th><th>Berat <br>(kg)</th><th>Status Berat</th><th>Tinggi <br>(cm)</th><th>Status Tinggi</th><th>Lingkar Kepala <br>(cm)</th><th>Status Kepala</th><th>Nama Petugas</th><th>Saran</th></tr></thead>
                <tbody>
                @php $startIndex = $measurement->total() - ($measurement->currentPage() - 1) * $measurement->perPage(); @endphp
                @forelse($measurement as $index => $row)
                    @php 
                        $usiaBulan = $row->age_months;
                        $saranRaw = $row->additional_recommendation ?? '';
                        $officerName = '-';
                        $fullRecommendation = $saranRaw;
                        $parts = explode("\n", (string)$saranRaw, 2);
                        // Fallback untuk data lama yang tersimpan dengan literal \n
                        if (count($parts) === 1 && str_contains((string)$saranRaw, '\n')) {
                            $parts = explode('\n', (string)$saranRaw, 2);
                        }
                        
                        if (count($parts) > 1 && str_starts_with($parts[0], 'Petugas: ')) {
                            $officerName = str_replace('Petugas: ', '', $parts[0]);
                            $fullRecommendation = $parts[1];
                        } elseif (str_starts_with((string)$saranRaw, 'Petugas: ')) {
                            $officerName = str_replace('Petugas: ', '', $saranRaw);
                            $fullRecommendation = '';
                        }
                    @endphp
                    <tr>
                        <td>{{ $usiaBulan }}</td>
                        <td>{{ $row->measurement_date->format('d/m/Y') }} {{ substr($row->measurement_time,0,5) }}</td>
                        <td>{{ $row->weight }}</td>
                        <td class="small">{{ $row->weight_status ?? '-' }}</td>
                        <td>{{ $row->height }}</td>
                        <td class="small">{{ $row->height_status ?? '-' }}</td>
                        <td>{{ $row->head_circumference !== null ? $row->head_circumference : '-' }}</td>
                        <td class="small">{{ $row->head_circumference_status ?? '-' }}</td>
                        <td>{{ $officerName }}</td>
                        <td style="min-width: 300px;">
                                                        @if(strlen($fullRecommendation) > 60)
                                <div id="shortText{{ $row->id }}" class="d-flex justify-content-between align-items-start">
                                    <div>
                                        {!! nl2br(e(\Illuminate\Support\Str::limit($fullRecommendation, 60))) !!}
                                        <a href="#" class="text-decoration-none small ms-1 text-primary" onclick="event.preventDefault(); document.getElementById('shortText{{ $row->id }}').classList.add('d-none'); document.getElementById('fullText{{ $row->id }}').classList.remove('d-none');">Selengkapnya</a>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link p-0 ms-2 text-warning" onclick="document.getElementById('shortText{{ $row->id }}').classList.add('d-none'); document.getElementById('editRec{{ $row->id }}').classList.remove('d-none');"><i class="bi bi-pencil-square"></i></button>
                                </div>
                                <div id="fullText{{ $row->id }}" class="d-none d-flex justify-content-between align-items-start">
                                    <div>
                                        {!! nl2br(e($fullRecommendation)) !!}
                                        <a href="#" class="text-decoration-none small ms-1 text-danger d-block mt-1" onclick="event.preventDefault(); document.getElementById('fullText{{ $row->id }}').classList.add('d-none'); document.getElementById('shortText{{ $row->id }}').classList.remove('d-none');">Tutup</a>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link p-0 mt-2 text-warning" onclick="document.getElementById('fullText{{ $row->id }}').classList.add('d-none'); document.getElementById('editRec{{ $row->id }}').classList.remove('d-none');"><i class="bi bi-pencil-square"></i> Edit</button>
                                </div>
                            @else
                                <div id="shortText{{ $row->id }}" class="d-flex justify-content-between align-items-start">
                                    <div class="text-break">{!! nl2br(e($fullRecommendation)) !!}</div>
                                    <button type="button" class="btn btn-sm btn-link p-0 ms-2 text-warning flex-shrink-0" onclick="document.getElementById('shortText{{ $row->id }}').classList.add('d-none'); document.getElementById('editRec{{ $row->id }}').classList.remove('d-none');"><i class="bi bi-pencil-square"></i></button>
                                </div>
                            @endif
                            <div id="editRec{{ $row->id }}" class="d-none mt-2">
                                <form action="{{ route('admin.measurement.update-recommendation', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="additional_recommendation" class="form-control form-control-sm mb-2" rows="2">{{ $fullRecommendation }}</textarea>
                                    <div class="d-flex gap-1">
                                        <button type="submit" class="btn btn-primary btn-sm py-0"><i class="bi bi-check2"></i> Simpan</button>
                                        <button type="button" class="btn btn-secondary btn-sm py-0" onclick="document.getElementById('editRec{{ $row->id }}').classList.add('d-none'); document.getElementById('shortText{{ $row->id }}').classList.remove('d-none');">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada riwayat.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $measurement->links() }}
    </div>
</div>
@endsection
