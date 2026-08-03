@extends('layouts.app', ['title' => 'Dashboard Pengguna'])

@section('content')


<div class="alert alert-light border shadow-sm mb-4 py-2 px-3 d-inline-flex align-items-center rounded-3">
    <i class="bi bi-calendar-event text-primary me-2"></i>
    <span class="text-muted me-2 small">Jadwal Posyandu:</span>
    <span class="fw-semibold text-dark small">{{ $upcomingSchedule ?? 'Menunggu Info Admin' }}</span>
</div>

<!-- Profil Anak di atas -->
<div class="row g-4 mb-4">
    <!-- Box Nama -->
    <div class="col-md-4">
        <div class="card border-0 h-100" style="border-radius: 12px; background-color: #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <div class="text-muted small fw-bold text-uppercase mb-1">Nama</div>
                <div class="fs-4 fw-bold text-dark" id="childName">{{ $child->name }}</div>
            </div>
        </div>
    </div>
    <!-- Box Usia -->
    <div class="col-md-4">
        <div class="card border-0 h-100" style="border-radius: 12px; background-color: #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <div class="text-muted small fw-bold text-uppercase mb-1">Usia</div>
                <div class="fs-4 fw-bold text-dark"><span id="childAge">{{ $child->ageMonths() }}</span> bulan</div>
            </div>
        </div>
    </div>
    <!-- Box Jenis Kelamin -->
    <div class="col-md-4">
        <div class="card border-0 h-100 text-white" id="genderBox" style="border-radius: 12px; background-color: {{ $child->gender === 'male' ? '#3b82f6' : '#ec4899' }}; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <div class="text-white-50 small fw-bold text-uppercase mb-1">Jenis Kelamin</div>
                <div class="fs-4 fw-bold" id="childGender">{{ $child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Kotak Live Metric -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="alert {{ $latest && isset($latest->is_live) && $latest->is_live ? 'alert-danger' : 'alert-secondary' }} text-center fw-bold shadow-sm mb-0" id="liveStatusBanner">
            <i class="bi bi-broadcast me-2"></i><span id="liveStatusText">{{ $latest && isset($latest->is_live) && $latest->is_live ? 'Live' : 'Data Pengukuran Terbaru (' . ($latest ? \Carbon\Carbon::parse($latest->measurement_date)->format('d/m/Y') . ' ' . substr($latest->measurement_time, 0, 5) : '') . ')' }}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #f1f8f5; border-left: 5px solid #198754 !important;">
            <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                <div class="text-muted fs-5 mb-3 fw-semibold">Berat Badan</div>
                <div class="display-4 fw-bold text-success mb-3" id="weightStatus" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">{{ $latest ? $latest->weight . ' kg' : '-' }}</div>
                <div class="fw-bold text-dark mb-1 fs-5" id="weightConclusion">{{ $latest && isset($latest->weight_status) ? $latest->weight_status : '-' }}</div>
                <div class="text-secondary small fw-bold" id="weightDiffBox" style="min-height: 20px;">@if($latest && isset($latest->weight_diff_text) && $latest->weight_diff_text)<i class="bi bi-info-circle me-1"></i>{{ $latest->weight_diff_text }}@endif</div>
                <div class="text-muted small mt-1" id="weightNormalBox">@if($latest && isset($latest->weight_normal_limit)){{ $latest->weight_normal_limit }}@endif</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #f0f9ff; border-left: 5px solid #0dcaf0 !important;">
            <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                <div class="text-muted fs-5 mb-3 fw-semibold">Tinggi Badan</div>
                <div class="display-4 fw-bold text-info mb-3" id="heightStatus" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">{{ $latest ? $latest->height . ' cm' : '-' }}</div>
                <div class="fw-bold text-dark mb-1 fs-5" id="heightConclusion">{{ $latest && isset($latest->height_status) ? $latest->height_status : '-' }}</div>
                <div class="text-secondary small fw-bold" id="heightDiffBox" style="min-height: 20px;">@if($latest && isset($latest->height_diff_text) && $latest->height_diff_text)<i class="bi bi-info-circle me-1"></i>{{ $latest->height_diff_text }}@endif</div>
                <div class="text-muted small mt-1" id="heightNormalBox">@if($latest && isset($latest->height_normal_limit)){{ $latest->height_normal_limit }}@endif</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #fffdf5; border-left: 5px solid #ffc107 !important;">
            <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                <div class="text-muted fs-5 mb-3 fw-semibold">Lingkar Kepala</div>
                <div class="display-4 fw-bold text-warning mb-3" id="headCircumferenceStatus" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">{{ $latest && $latest->head_circumference !== null ? $latest->head_circumference . ' cm' : '-' }}</div>
                <div class="fw-bold text-dark mb-1 fs-5" id="headCircumferenceConclusion">{{ $latest && isset($latest->head_circumference_status) ? $latest->head_circumference_status : '-' }}</div>
                <div class="text-secondary small fw-bold" id="headCircumferenceDiffBox" style="min-height: 20px;">@if($latest && isset($latest->hc_diff_text) && $latest->hc_diff_text)<i class="bi bi-info-circle me-1"></i>{{ $latest->hc_diff_text }}@endif</div>
                <div class="text-muted small mt-1" id="headCircumferenceNormalBox">@if($latest && isset($latest->hc_normal_limit)){{ $latest->hc_normal_limit }}@endif</div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script>


let lastMeasurementTime = null;

async function refreshLatestMeasurement() {
    try {
        const response = await fetch(@json(route('user.dashboard.latest')), {
            headers: {'Accept': 'application/json'}
        });

        if (!response.ok) return;
        const data = await response.json();

        if (data.child) {
            document.getElementById('childName').textContent = data.child.name ?? '-';
            document.getElementById('childAge').textContent = data.child.age_months ?? '-';
            
            const genderStr = data.child.gender ?? '-';
            document.getElementById('childGender').textContent = genderStr;
            const genderBox = document.getElementById('genderBox');
            if (genderBox && genderStr !== '-') {
                genderBox.style.backgroundColor = genderStr === 'Laki-laki' ? '#3b82f6' : '#ec4899';
            }
        }

        if (data.latest) {
            lastMeasurementTime = data.latest.measurement_time;

            document.getElementById('weightStatus').textContent = (data.latest.weight !== null && data.latest.weight !== undefined) ? data.latest.weight + ' kg' : '-';
            document.getElementById('heightStatus').textContent = (data.latest.height !== null && data.latest.height !== undefined) ? data.latest.height + ' cm' : '-';
            document.getElementById('headCircumferenceStatus').textContent = (data.latest.head_circumference !== null && data.latest.head_circumference !== undefined) ? data.latest.head_circumference + ' cm' : '-';
            
            document.getElementById('weightConclusion').textContent = data.latest.weight_status ? data.latest.weight_status : '-';
            document.getElementById('heightConclusion').textContent = data.latest.height_status ? data.latest.height_status : '-';
            document.getElementById('headCircumferenceConclusion').textContent = data.latest.head_circumference_status ? data.latest.head_circumference_status : '-';
            
            if (data.latest.weight_diff_text) {
                document.getElementById('weightDiffBox').innerHTML = '<i class="bi bi-info-circle me-1"></i>' + data.latest.weight_diff_text;
            } else {
                document.getElementById('weightDiffBox').innerHTML = '';
            }
            document.getElementById('weightNormalBox').textContent = data.latest.weight_normal_limit ? data.latest.weight_normal_limit : '';

            if (data.latest.height_diff_text) {
                document.getElementById('heightDiffBox').innerHTML = '<i class="bi bi-info-circle me-1"></i>' + data.latest.height_diff_text;
            } else {
                document.getElementById('heightDiffBox').innerHTML = '';
            }
            document.getElementById('heightNormalBox').textContent = data.latest.height_normal_limit ? data.latest.height_normal_limit : '';

            if (data.latest.hc_diff_text) {
                document.getElementById('headCircumferenceDiffBox').innerHTML = '<i class="bi bi-info-circle me-1"></i>' + data.latest.hc_diff_text;
            } else {
                document.getElementById('headCircumferenceDiffBox').innerHTML = '';
            }
            document.getElementById('headCircumferenceNormalBox').textContent = data.latest.hc_normal_limit ? data.latest.hc_normal_limit : '';
            

            const liveBanner = document.getElementById('liveStatusBanner');
            const liveText = document.getElementById('liveStatusText');
            if (data.latest.is_live) {
                liveBanner.className = 'alert alert-danger text-center fw-bold shadow-sm mb-0';
                liveText.textContent = 'Live';
            } else {
                liveBanner.className = 'alert alert-secondary text-center fw-bold shadow-sm mb-0';
                liveText.textContent = 'Data Pengukuran Terbaru (' + data.latest.measurement_date + ' ' + data.latest.measurement_time + ')';
            }
        }
    } catch (error) {
        console.error('Gagal refresh data realtime', error);
    }
}

refreshLatestMeasurement();
setInterval(refreshLatestMeasurement, 3000);
</script>
@endpush
