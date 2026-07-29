@extends('layouts.app', ['title' => ($standard ? 'Edit ' : 'Tambah ').(match($type) { 'weight' => 'Standar Berat', 'height' => 'Standar Tinggi', default => 'Standar Lingkar Kepala' })])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="{{ $standard ? route('admin.standards.update',[$type,$standard->id]) : route('admin.standards.store',$type) }}">
    @csrf @if($standard) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-2"><label class="form-label">Usia</label><input name="age_months" type="number" class="form-control" value="{{ old('age_months',$standard->age_months ?? '') }}" required></div>
        <div class="col-md-3"><label class="form-label">Gender</label><select name="gender" class="form-select" required><option value="male" {{ old('gender', $standard->gender ?? '') === 'male' ? 'selected' : '' }}>Laki-laki</option><option value="female" {{ old('gender', $standard->gender ?? '') === 'female' ? 'selected' : '' }}>Perempuan</option></select></div>
        <div class="col-md-3"><label class="form-label">Label Usia</label><input name="age_label" class="form-control" value="{{ old('age_label',$standard->age_label ?? '') }}" required></div>
        <div class="col-md-2"><label class="form-label">Min</label><input name="min_value" type="number" step="0.01" class="form-control" value="{{ old('min_value', match($type) { 'weight' => ($standard->min_weight ?? ''), 'height' => ($standard->min_height ?? ''), default => ($standard->min_head_circumference ?? '') }) }}" required></div>
        <div class="col-md-2"><label class="form-label">Max</label><input name="max_value" type="number" step="0.01" class="form-control" value="{{ old('max_value', match($type) { 'weight' => ($standard->max_weight ?? ''), 'height' => ($standard->max_height ?? ''), default => ($standard->max_head_circumference ?? '') }) }}" required></div>
    </div>
    <div class="mt-3"><button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button> <a href="{{ route('admin.standards.index',$type) }}" class="btn btn-light">Batal</a></div>
</form>
</div></div>
@endsection
