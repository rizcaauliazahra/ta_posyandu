@extends('layouts.app', ['title' => 'Tabel Standar Pertumbuhan ( 0 - 60 Bulan )'])

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-start align-items-center mb-3">
            <div class="btn-group">
                @php $route = auth()->user()->isAdmin() ? 'admin.tabel-gizi.index' : 'user.tabel-gizi'; @endphp
                <a href="{{ route($route, ['gender' => 'male']) }}" class="btn btn-sm {{ $gender === 'male' ? 'btn-primary' : 'btn-outline-primary' }}"><i class="bi bi-gender-male"></i> Laki-laki</a>
                <a href="{{ route($route, ['gender' => 'female']) }}" class="btn btn-sm {{ $gender === 'female' ? 'btn-danger' : 'btn-outline-danger' }}"><i class="bi bi-gender-female"></i> Perempuan</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2" class="align-middle">Usia (Bulan)</th>
                        <th colspan="2">Berat Badan (kg)</th>
                        <th colspan="2">Tinggi Badan (cm)</th>
                        <th colspan="2">Lingkar Kepala (cm)</th>
                    </tr>
                    <tr>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Min</th>
                        <th>Max</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ages as $age)
                    <tr>
                        <td class="fw-bold">{{ $age }}</td>
                        <td>{{ isset($weightStandards[$age]) ? $weightStandards[$age]->min_weight : '-' }}</td>
                        <td>{{ isset($weightStandards[$age]) ? $weightStandards[$age]->max_weight : '-' }}</td>
                        <td>{{ isset($heightStandards[$age]) ? $heightStandards[$age]->min_height : '-' }}</td>
                        <td>{{ isset($heightStandards[$age]) ? $heightStandards[$age]->max_height : '-' }}</td>
                        <td>{{ isset($headStandards[$age]) ? $headStandards[$age]->min_head_circumference : '-' }}</td>
                        <td>{{ isset($headStandards[$age]) ? $headStandards[$age]->max_head_circumference : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
