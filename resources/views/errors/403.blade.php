@extends('layouts.app', ['title' => '403'])
@section('content')
<div class="text-center py-5"><h1 class="display-5 text-danger">403</h1><p class="text-muted">Anda tidak memiliki akses ke halaman ini.</p><a href="{{ url()->previous() }}" class="btn btn-success">Kembali</a></div>
@endsection
