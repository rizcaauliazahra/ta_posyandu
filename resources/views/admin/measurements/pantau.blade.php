@extends('layouts.app', ['title' => 'Pantau Measurement Anak'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pantau: {{ $child->name }}</h1>
    <a href="{{ route('admin.pantau-anak.index') }}" class="btn btn-secondary">Kembali</a>
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
    <div class="col-md-4">
        <div class="card metric border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #f1f8f5; border-left: 5px solid #198754 !important;">
            <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                <div class="text-muted fs-5 mb-3 fw-semibold">Berat Badan (Live)</div>
                <div class="display-4 fw-bold text-success mb-3" id="weightStatus" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">{{ $latest && isset($latest->is_live) && $latest->is_live ? $latest->weight . ' kg' : '-' }}</div>
                <div class="fw-bold text-dark mb-1 fs-5" id="weightConclusion">{{ $latest && isset($latest->is_live) && $latest->is_live && isset($latest->weight_status) ? $latest->weight_status : '-' }}</div>
                <div class="text-secondary small fw-bold" id="weightDiffBox" style="min-height: 20px;">@if($latest && isset($latest->is_live) && $latest->is_live && isset($latest->weight_diff_text) && $latest->weight_diff_text)<i class="bi bi-info-circle me-1"></i>{{ $latest->weight_diff_text }}@endif</div>
                <div class="text-muted small mt-1" id="weightNormalBox">@if($latest && isset($latest->is_live) && $latest->is_live && isset($latest->weight_normal_limit)){{ $latest->weight_normal_limit }}@endif</div>
                <button type="button" class="btn btn-dark btn-sm mt-3 w-100 fw-bold text-white" id="btnFreezeWeight" onclick="toggleFreeze('weight')">Simpan Data</button>            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #f0f9ff; border-left: 5px solid #0dcaf0 !important;">
            <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                <div class="text-muted fs-5 mb-3 fw-semibold">Tinggi Badan (Live)</div>
                <div class="display-4 fw-bold text-info mb-3" id="heightStatus" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">{{ $latest && isset($latest->is_live) && $latest->is_live ? $latest->height . ' cm' : '-' }}</div>
                <div class="fw-bold text-dark mb-1 fs-5" id="heightConclusion">{{ $latest && isset($latest->is_live) && $latest->is_live && isset($latest->height_status) ? $latest->height_status : '-' }}</div>
                <div class="text-secondary small fw-bold" id="heightDiffBox" style="min-height: 20px;">@if($latest && isset($latest->is_live) && $latest->is_live && isset($latest->height_diff_text) && $latest->height_diff_text)<i class="bi bi-info-circle me-1"></i>{{ $latest->height_diff_text }}@endif</div>
                <div class="text-muted small mt-1" id="heightNormalBox">@if($latest && isset($latest->is_live) && $latest->is_live && isset($latest->height_normal_limit)){{ $latest->height_normal_limit }}@endif</div>
                <button type="button" class="btn btn-dark btn-sm mt-3 w-100 fw-bold text-white" id="btnFreezeHeight" onclick="toggleFreeze('height')">Simpan Data</button>            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #fffdf5; border-left: 5px solid #ffc107 !important;">
            <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                <div class="text-muted fs-5 mb-3 fw-semibold">Lingkar Kepala (Live)</div>
                <div class="display-4 fw-bold text-warning mb-3" id="headCircumferenceStatus" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">{{ $latest && isset($latest->is_live) && $latest->is_live && $latest->head_circumference !== null ? $latest->head_circumference . ' cm' : '-' }}</div>
                <div class="fw-bold text-dark mb-1 fs-5" id="headCircumferenceConclusion">{{ $latest && isset($latest->is_live) && $latest->is_live && isset($latest->head_circumference_status) ? $latest->head_circumference_status : '-' }}</div>
                <div class="text-secondary small fw-bold" id="headCircumferenceDiffBox" style="min-height: 20px;">@if($latest && isset($latest->is_live) && $latest->is_live && isset($latest->hc_diff_text) && $latest->hc_diff_text)<i class="bi bi-info-circle me-1"></i>{{ $latest->hc_diff_text }}@endif</div>
                <div class="text-muted small mt-1" id="headCircumferenceNormalBox">@if($latest && isset($latest->is_live) && $latest->is_live && isset($latest->hc_normal_limit)){{ $latest->hc_normal_limit }}@endif</div>
                <button type="button" class="btn btn-dark btn-sm mt-3 w-100 fw-bold text-white" id="btnFreezeHc" onclick="toggleFreeze('hc')">Simpan Data</button>            </div>
        </div>
    </div>
</div>

<!-- Button Simpan Measurement -->
<form action="{{ route('admin.pantau-anak.save-live', $child->id) }}" method="POST" class="mb-4" id="formSaveLive">
    @csrf
    <input type="hidden" name="weight_override" id="weight_override" value="">
    <input type="hidden" name="height_override" id="height_override" value="">
    <input type="hidden" name="hc_override" id="hc_override" value="">

@php
    $officerName = '';
    $saran = '';
@endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="text-primary fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Nama Petugas</h6>
        <div class="mb-3">
            <input type="text" class="form-control bg-light" name="officer_name" id="officer_name" placeholder="Ketik nama petugas di sini..." value="{{ $officerName }}">
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="text-primary fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Saran dari Petugas</h6>
        <div class="mb-3">
            <textarea class="form-control bg-light" name="additional_recommendation" id="additional_recommendation" rows="4" placeholder="Ketik saran untuk orang tua di sini...">{{ $saran }}</textarea>
        </div>
    </div>
</div>

    <button type="submit" id="btnSaveLive" class="btn {{ $latest && isset($latest->is_live) && $latest->is_live ? 'btn-danger' : 'btn-secondary' }} btn-lg w-100 shadow-sm fw-bold py-3" {{ $latest && isset($latest->is_live) && $latest->is_live ? '' : 'disabled' }}>
        <i class="bi bi-save me-2"></i>Simpan
    </button>
</form>
@endsection

@push('scripts')
<script>
let lastMeasurementTime = null;
let latestRawData = { weight: null, height: null, hc: null };
let frozen = { weight: false, height: false, hc: false };

function toggleFreeze(type) {
    const btnId = type === 'hc' ? 'btnFreezeHc' : 'btnFreeze' + type.charAt(0).toUpperCase() + type.slice(1);
    const btn = document.getElementById(btnId);
    const input = document.getElementById(type + '_override');
    
    if (frozen[type]) {
        // Unfreeze
        frozen[type] = false;
        input.value = '';
        btn.textContent = 'Simpan Data';
        
        if (latestRawData[type] !== null && latestRawData[type] !== undefined) {
            btn.classList.replace('btn-secondary', 'btn-dark');
            btn.classList.add('text-white');
            btn.disabled = false;
        } else {
            btn.classList.replace('btn-dark', 'btn-secondary');
            btn.classList.remove('text-white');
            btn.disabled = true;
        }
    } else {
        // Freeze
        if (latestRawData[type] !== null && latestRawData[type] !== undefined) {
            frozen[type] = true;
            input.value = latestRawData[type];
            btn.textContent = 'Batal Simpan';
            btn.classList.replace('btn-dark', 'btn-secondary');
            btn.classList.remove('text-white');
            btn.disabled = false; // keep it enabled so user can unfreeze
        }
    }
}

async function refreshLatestMeasurement() {
    try {
        const response = await fetch('{{ route("admin.pantau-anak.latest", $child->id) }}', {
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
            
            const isLive = data.latest.is_live;
            
            if (isLive) {
                latestRawData.weight = data.latest.weight;
                latestRawData.height = data.latest.height;
                latestRawData.hc = data.latest.head_circumference;
            } else {
                latestRawData.weight = null;
                latestRawData.height = null;
                latestRawData.hc = null;
            }

            // Update "Simpan Data" buttons style based on live status
            ['weight', 'height', 'hc'].forEach(type => {
                const btnId = type === 'hc' ? 'btnFreezeHc' : 'btnFreeze' + type.charAt(0).toUpperCase() + type.slice(1);
                const btn = document.getElementById(btnId);
                if (btn && !frozen[type]) {
                    if (isLive) {
                        btn.disabled = false;
                        btn.classList.remove('btn-secondary');
                        btn.classList.add('btn-dark', 'text-white');
                    } else {
                        btn.disabled = true;
                        btn.classList.remove('btn-dark', 'text-white');
                        btn.classList.add('btn-secondary');
                    }
                }
            });

            if (!frozen.weight) {
                document.getElementById('weightStatus').textContent = (isLive && data.latest.weight !== null && data.latest.weight !== undefined) ? data.latest.weight + ' kg' : '-';
                document.getElementById('weightConclusion').textContent = (isLive && data.latest.weight_status) ? data.latest.weight_status : '-';
                if (isLive && data.latest.weight_diff_text) {
                    document.getElementById('weightDiffBox').innerHTML = '<i class="bi bi-info-circle me-1"></i>' + data.latest.weight_diff_text;
                } else {
                    document.getElementById('weightDiffBox').innerHTML = '';
                }
                document.getElementById('weightNormalBox').textContent = (isLive && data.latest.weight_normal_limit) ? data.latest.weight_normal_limit : '';
            }

            if (!frozen.height) {
                document.getElementById('heightStatus').textContent = (isLive && data.latest.height !== null && data.latest.height !== undefined) ? data.latest.height + ' cm' : '-';
                document.getElementById('heightConclusion').textContent = (isLive && data.latest.height_status) ? data.latest.height_status : '-';
                if (isLive && data.latest.height_diff_text) {
                    document.getElementById('heightDiffBox').innerHTML = '<i class="bi bi-info-circle me-1"></i>' + data.latest.height_diff_text;
                } else {
                    document.getElementById('heightDiffBox').innerHTML = '';
                }
                document.getElementById('heightNormalBox').textContent = (isLive && data.latest.height_normal_limit) ? data.latest.height_normal_limit : '';
            }

            if (!frozen.hc) {
                document.getElementById('headCircumferenceStatus').textContent = (isLive && data.latest.head_circumference !== null && data.latest.head_circumference !== undefined) ? data.latest.head_circumference + ' cm' : '-';
                document.getElementById('headCircumferenceConclusion').textContent = (isLive && data.latest.head_circumference_status) ? data.latest.head_circumference_status : '-';
                if (isLive && data.latest.hc_diff_text) {
                    document.getElementById('headCircumferenceDiffBox').innerHTML = '<i class="bi bi-info-circle me-1"></i>' + data.latest.hc_diff_text;
                } else {
                    document.getElementById('headCircumferenceDiffBox').innerHTML = '';
                }
                document.getElementById('headCircumferenceNormalBox').textContent = (isLive && data.latest.hc_normal_limit) ? data.latest.hc_normal_limit : '';
            }
            
            // Kosongkan form Saran jika tidak ada data dari alat
            if (!isLive) {
                const saranBox = document.getElementById('additional_recommendation');
                if (saranBox) saranBox.value = '';
            }
            
            const btnSave = document.getElementById('btnSaveLive');
            if (btnSave) {
                if (data.latest.is_live) {
                    btnSave.disabled = false;
                    btnSave.classList.remove('btn-secondary');
                    btnSave.classList.add('btn-danger');
                } else {
                    btnSave.disabled = true;
                    btnSave.classList.remove('btn-danger');
                    btnSave.classList.add('btn-secondary');
                }
            }
        }
    } catch (error) {
        console.error('Gagal refresh data realtime', error);
    }
}

refreshLatestMeasurement();
setInterval(refreshLatestMeasurement, 3000);

document.addEventListener('DOMContentLoaded', function() {
});
</script>
@endpush
