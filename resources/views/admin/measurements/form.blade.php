@extends('layouts.app', ['title' => $measurement->exists ? 'Edit Measurement' : 'Tambah Measurement'])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="{{ $measurement->exists ? route('admin.measurement.update',$measurement) : route('admin.measurement.store') }}">
    @csrf @if($measurement->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Anak</label><select name="child_id" class="form-select" required>@foreach($children as $child)<option value="{{ $child->id }}" @selected(old('child_id',$measurement->child_id)==$child->id)>{{ $child->name }} - {{ $child->user->email }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Berat Badan</label><input name="weight" type="number" step="0.01" class="form-control" value="{{ old('weight',$measurement->weight) }}" required></div>
        <div class="col-md-3"><label class="form-label">Tinggi Badan</label><input name="height" type="number" step="0.01" class="form-control" value="{{ old('height',$measurement->height) }}" required></div>
        <div class="col-md-4"><label class="form-label">Tanggal</label><input name="measurement_date" type="date" class="form-control" value="{{ old('measurement_date',$measurement->measurement_date?->format('Y-m-d') ?? now()->toDateString()) }}" required></div>
        <div class="col-md-4"><label class="form-label">Jam</label><input name="measurement_time" type="time" class="form-control" value="{{ old('measurement_time',substr((string)($measurement->measurement_time ?? now()->format('H:i')),0,5)) }}" required></div>
        <div class="col-md-4"><label class="form-label">Usia Bulan</label><input name="age_months" type="number" class="form-control" value="{{ old('age_months',$measurement->age_months) }}"></div>
    </div>
    <div class="mt-3"><button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button> <a href="{{ route('admin.measurement.index') }}" class="btn btn-light">Batal</a></div>
</form>
</div></div>
@endsection
