@extends('layouts.app', ['title' => 'Data Saran'])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.recommendations.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah</a></div>
    <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Status</th><th>Saran</th><th></th></tr></thead><tbody>
    @foreach($recommendations as $row)
        <tr><td><span class="badge text-bg-success">{{ $row->status }}</span></td><td>{!! nl2br(e($row->content)) !!}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.recommendations.edit',$row) }}"><i class="bi bi-pencil"></i></a> <form class="d-inline" method="POST" action="{{ route('admin.recommendations.destroy',$row) }}" data-confirm>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
    @endforeach
    </tbody></table></div>{{ $recommendations->links() }}
</div></div>
@endsection
