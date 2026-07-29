@extends('layouts.app', ['title' => match($type) { 'weight' => 'Standar Berat Badan', 'height' => 'Standar Tinggi Badan', default => 'Standar Lingkar Kepala' }])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-between mb-3"><div><a class="btn btn-outline-success btn-sm" href="{{ route('admin.standards.index','weight') }}">Berat</a> <a class="btn btn-outline-info btn-sm" href="{{ route('admin.standards.index','height') }}">Tinggi</a> <a class="btn btn-outline-warning btn-sm" href="{{ route('admin.standards.index','head_circumference') }}">Lingkar Kepala</a></div><a href="{{ route('admin.standards.create',$type) }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah</a></div>
    <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Usia</th><th>Gender</th><th>Minimum</th><th>Maksimum</th><th></th></tr></thead><tbody>
    @foreach($standards as $row)
        <tr><td>{{ $row->age_label }}</td><td>{{ $row->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td><td>{{ match($type) { 'weight' => $row->min_weight.' kg', 'height' => $row->min_height.' cm', default => $row->min_head_circumference.' cm' } }}</td><td>{{ match($type) { 'weight' => $row->max_weight.' kg', 'height' => $row->max_height.' cm', default => $row->max_head_circumference.' cm' } }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.standards.edit',[$type,$row->id]) }}"><i class="bi bi-pencil"></i></a> <form class="d-inline" method="POST" action="{{ route('admin.standards.destroy',[$type,$row->id]) }}" data-confirm>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
    @endforeach
    </tbody></table></div>{{ $standards->links() }}
</div></div>
@endsection
