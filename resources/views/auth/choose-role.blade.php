@extends('layouts.app', ['title' => 'Pilih Role'])

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-3" style="background:linear-gradient(135deg,#e0f2fe,#ffffff,#dcfce7)">
    <div class="card border-0 shadow-sm" style="max-width:720px;width:100%;border-radius:8px">
        <div class="card-body p-4">
            <div class="text-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Posyandu" style="width: 120px; height: auto; mix-blend-mode: multiply;">
            </div>
            <h1 class="h2 fw-bolder mb-2 text-center" style="color: #059669; letter-spacing: -1px;">PosCare</h1>
            <p class="text-center mb-4" style="color: #4b5563; font-size: 1.1rem; font-style: italic; line-height: 1.5; font-family: 'Georgia', serif;">
                Selamat Datang di PosCare<br>
                Pantau tumbuh kembang anak secara digital, praktis, dan akurat bersama kami
            </p>
            @include('partials.flash')
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded-3 p-4 h-100 text-center">
                        <div class="fs-3 text-success mb-2"><i class="bi bi-shield-lock"></i></div>
                        <h2 class="h5 mb-4">Admin</h2>
                        <div class="d-grid gap-2">
                            <a class="btn btn-success w-100" href="{{ route('login.role', 'admin') }}">Login</a>
                            <a class="btn btn-outline-success w-100" href="{{ route('register.role', 'admin') }}">Register</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-3 p-4 h-100 text-center">
                        <div class="fs-3 text-info mb-2"><i class="bi bi-people"></i></div>
                        <h2 class="h5 mb-4">Pengguna</h2>
                        <div class="d-grid gap-2">
                            <a class="btn btn-info text-white w-100" href="{{ route('login.role', 'user') }}">Login</a>
                            <a class="btn btn-outline-info w-100" href="{{ route('register.role', 'user') }}">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
