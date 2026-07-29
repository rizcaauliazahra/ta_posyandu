@extends('layouts.app', ['title' => '404'])
@section('content')
<div class="text-center py-5"><h1 class="display-5 text-success">404</h1><p class="text-muted">Halaman tidak ditemukan.</p><a href="{{ url('/') }}" class="btn btn-success">Beranda</a></div>
@endsection
