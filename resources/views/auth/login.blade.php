@extends('layouts.app', ['title' => 'Login'])

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-3" style="background:linear-gradient(135deg,#e0f2fe,#ffffff,#dcfce7)">
    <div class="card border-0 shadow-sm" style="max-width:430px;width:100%;border-radius:8px">
        <div class="card-body p-4 position-relative">
            <a href="{{ route('role.choose') }}" class="text-decoration-none text-muted small position-absolute top-0 start-0 m-3"><i class="bi bi-arrow-left"></i> Kembali</a>
            <div class="text-center mt-3 mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Posyandu" style="width: 120px; height: auto; mix-blend-mode: multiply;">
            </div>
            <h1 class="h4 brand mb-4 text-center">Login {{ $role === 'admin' ? 'Admin' : 'Pengguna' }}</h1>
            @include('partials.flash')
            <form method="POST" action="{{ route('login.store') }}" autocomplete="off">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">
                <div class="mb-3">
                    <label class="form-label">{{ $role === 'admin' ? 'Username / Gmail' : 'Gmail' }}</label>
                    <input name="login" type="{{ $role === 'admin' ? 'text' : 'email' }}" class="form-control" value="{{ old('login') }}" required autofocus autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input name="password" id="password_input" type="password" class="form-control" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="toggle_password"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-check"><input class="form-check-input" type="checkbox" name="remember"> <span class="form-check-label">Ingat saya</span></label>
                    <a href="{{ route('register.role', $role) }}" class="text-success">Register</a>
                </div>
                <button class="btn btn-success w-100"><i class="bi bi-box-arrow-in-right me-1"></i>Login</button>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('toggle_password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_input');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const role = '{{ $role }}';
        const emailInput = document.querySelector('input[name="login"]');
        const passwordInput = document.querySelector('input[name="password"]');
        const rememberCheckbox = document.querySelector('input[name="remember"]');
        const form = document.querySelector('form');

        if (localStorage.getItem('remember_login_' + role)) {
            emailInput.value = localStorage.getItem('saved_email_' + role) || '';
            passwordInput.value = localStorage.getItem('saved_password_' + role) || '';
            rememberCheckbox.checked = true;
        }

        form.addEventListener('submit', function() {
            if (rememberCheckbox.checked) {
                localStorage.setItem('remember_login_' + role, '1');
                localStorage.setItem('saved_email_' + role, emailInput.value);
                localStorage.setItem('saved_password_' + role, passwordInput.value);
            } else {
                localStorage.removeItem('remember_login_' + role);
                localStorage.removeItem('saved_email_' + role);
                localStorage.removeItem('saved_password_' + role);
            }
        });
    });
</script>
@endsection
