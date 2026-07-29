@extends('layouts.app', ['title' => 'Dashboard Admin'])
@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm text-white h-100 position-relative overflow-hidden" style="background-color: #ffd166;">
            <div class="position-absolute" style="right: -10%; top: -20%; opacity: 0.15; transform: rotate(-15deg);">
                <i class="bi bi-people-fill" style="font-size: 10rem;"></i>
            </div>
            <div class="card-body d-flex align-items-center p-4 position-relative z-1">
                <div class="fs-1 me-4 bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px;">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h3 class="display-5 fw-bolder mb-0">{{ $childrenCount ?? 0 }}</h3>
                    <div class="fs-6 fw-medium text-white-50 mt-1">Jumlah Anak</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm text-white h-100 position-relative overflow-hidden" style="background-color: #58d68d;">
            <div class="position-absolute" style="right: -10%; top: -20%; opacity: 0.15; transform: rotate(-15deg);">
                <i class="bi bi-rulers" style="font-size: 10rem;"></i>
            </div>
            <div class="card-body d-flex align-items-center p-4 position-relative z-1">
                <div class="fs-1 me-4 bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div>
                    <h3 class="display-5 fw-bolder mb-0">{{ $measurementCount ?? 0 }}</h3>
                    <div class="fs-6 fw-medium text-white-50 mt-1">Total Catatan Pengukuran</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm text-white h-100 position-relative overflow-hidden" style="background-color: #63b3ed;">
            <div class="position-absolute" style="right: -10%; top: -20%; opacity: 0.15; transform: rotate(-15deg);">
                <i class="bi bi-gender-male" style="font-size: 10rem;"></i>
            </div>
            <div class="card-body d-flex align-items-center p-4 position-relative z-1">
                <div class="fs-1 me-4 bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px;">
                    <i class="bi bi-gender-male"></i>
                </div>
                <div>
                    <h3 class="display-5 fw-bolder mb-0">{{ $maleChildrenCount ?? 0 }}</h3>
                    <div class="fs-6 fw-medium text-white-50 mt-1">Anak Laki-laki</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm text-white h-100 position-relative overflow-hidden" style="background-color: #f687b3;">
            <div class="position-absolute" style="right: -10%; top: -20%; opacity: 0.15; transform: rotate(-15deg);">
                <i class="bi bi-gender-female" style="font-size: 10rem;"></i>
            </div>
            <div class="card-body d-flex align-items-center p-4 position-relative z-1">
                <div class="fs-1 me-4 bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px;">
                    <i class="bi bi-gender-female"></i>
                </div>
                <div>
                    <h3 class="display-5 fw-bolder mb-0">{{ $femaleChildrenCount ?? 0 }}</h3>
                    <div class="fs-6 fw-medium text-white-50 mt-1">Anak Perempuan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <!-- Jadwal Posyandu -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-calendar-check me-2"></i>Jadwal Posyandu
                </h5>
            </div>
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <div class="fs-1 text-primary mb-3"><i class="bi bi-calendar-date"></i></div>
                <h4 class="fw-bold">{{ $upcomingSchedule }}</h4>
                <p class="text-muted small mb-4">Ubah Jadwal</p>
                <form action="{{ route('admin.dashboard.update-schedule') }}" method="POST" class="mt-auto mx-auto" style="max-width: 300px;">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="date" name="jadwal_posyandu" class="form-control" required>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Anak Belum Diukur -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Belum Diukur Bulan Ini
                </h5>
            </div>
            <div class="card-body p-0 mt-3" style="max-height: 280px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @forelse($unmeasuredChildren as $uc)
                        <li class="list-group-item px-4 py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="fw-bold text-dark">{{ $uc->name }}</div>
                            </div>
                            <div class="small text-muted">{{ $uc->ageMonths() }} Bulan</div>
                        </li>
                    @empty
                        <li class="list-group-item px-4 py-5 text-center text-muted border-0">
                            <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                            Semua anak sudah diukur bulan ini!
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-bar-chart-fill me-2"></i>Statistik Pengukuran 6 Bulan Terakhir
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div style="height: 300px;">
                    <canvas id="monthlyMeasurementChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-primary">
                <i class="bi bi-activity me-2"></i>Aktivitas Pengukuran Terbaru
            </h5>
        </div>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4 fw-semibold border-0">Nama Anak</th>
                        <th class="fw-semibold border-0">Gender</th>
                        <th class="fw-semibold border-0">Tanggal</th>
                        <th class="fw-semibold border-0 text-center">Usia</th>
                        <th class="fw-semibold border-0 text-center">BB (kg)</th>
                        <th class="fw-semibold border-0 text-center">Status BB</th>
                        <th class="fw-semibold border-0 text-center">TB (cm)</th>
                        <th class="fw-semibold border-0 text-center">Status TB</th>
                        <th class="fw-semibold border-0 text-center">LK (cm)</th>
                        <th class="pe-4 fw-semibold border-0 text-center">Status LK</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($latestMeasurement as $measurement)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-dark">{{ $measurement->child->name }}</div>
                                </div>
                            </td>
                            <td class="py-3 text-muted">
                                {{ $measurement->child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td class="py-3 text-muted">
                                <div><i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($measurement->measurement_date)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="py-3 text-center text-muted">
                                {{ $measurement->child->ageMonths() }} Bln
                            </td>
                            <td class="py-3 text-center fw-medium">
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $measurement->weight }}</span>
                            </td>
                            <td class="py-3 text-center text-muted small">
                                {{ $measurement->weight_status ?? '-' }}
                            </td>
                            <td class="py-3 text-center fw-medium">
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $measurement->height }}</span>
                            </td>
                            <td class="py-3 text-center text-muted small">
                                {{ $measurement->height_status ?? '-' }}
                            </td>
                            <td class="py-3 text-center fw-medium">
                                @if($measurement->head_circumference !== null)
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $measurement->head_circumference }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-center text-muted small">
                                {{ $measurement->head_circumference_status ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <div class="fs-1 mb-2"><i class="bi bi-inbox text-light"></i></div>
                                Belum ada data pengukuran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthlyMeasurementChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(13, 110, 253, 0.8)');
        gradient.addColorStop(1, 'rgba(13, 110, 253, 0.2)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Pengukuran',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: gradient,
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#000',
                        bodyColor: '#000',
                        borderColor: '#dee2e6',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' Pengukuran';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f8f9fa',
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

    });
</script>
@endsection
