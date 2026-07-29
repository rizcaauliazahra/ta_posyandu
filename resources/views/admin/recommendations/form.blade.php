@extends('layouts.app', ['title' => $recommendation->exists ? 'Edit Saran' : 'Tambah Saran'])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="{{ $recommendation->exists ? route('admin.recommendations.update',$recommendation) : route('admin.recommendations.store') }}">
    @csrf @if($recommendation->exists) @method('PUT') @endif
    <div class="mb-3"><label class="form-label">Status</label><input name="status" class="form-control" value="{{ old('status',$recommendation->status) }}" required></div>
    <div class="mb-3"><label class="form-label">Saran</label><textarea name="content" class="form-control" rows="8" required>{{ old('content',$recommendation->content) }}</textarea></div>
    <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button> <a href="{{ route('admin.recommendations.index') }}" class="btn btn-light">Batal</a>
</form>
</div></div>
@endsection
