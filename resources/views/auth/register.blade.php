@extends('layouts.app', ['title' => 'Register'])

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-3" style="background:linear-gradient(135deg,#dcfce7,#ffffff,#e0f2fe)">
    <div class="card border-0 shadow-sm" style="max-width:480px;width:100%;border-radius:8px">
        <div class="card-body p-4 position-relative">
            <a href="{{ route('role.choose') }}" class="text-decoration-none text-muted small position-absolute top-0 start-0 m-3"><i class="bi bi-arrow-left"></i> Kembali</a>
            <div class="text-center mt-3 mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Posyandu" style="width: 120px; height: auto; mix-blend-mode: multiply;">
            </div>
            <h1 class="h4 brand mb-4 text-center">Register {{ $role === 'admin' ? 'Admin' : 'Pengguna' }}</h1>
            @include('partials.flash')
            <form method="POST" action="{{ route('register.store') }}" autocomplete="off">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">
                @if($role === 'admin')
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input name="username" class="form-control" value="{{ old('username') }}" required>
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label">Nama Anak</label>
                        <input name="child_name" class="form-control" value="{{ old('child_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin Anak</label>
                        <select name="gender" class="form-select" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Tempat Lahir Anak</label>
                            <input name="birth_place" class="form-control" value="{{ old('birth_place') }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Tanggal Lahir Anak</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Nama Ayah</label>
                            <input name="father_name" class="form-control" value="{{ old('father_name') }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input name="mother_name" class="form-control" value="{{ old('mother_name') }}">
                        </div>
                    </div>
                @endif
                <div class="mb-3"><label class="form-label">Gmail</label><input name="email" type="email" class="form-control" value="{{ old('email') }}" required autocomplete="off"></div>
                @if($role !== 'admin')
                    <div class="mb-3"><label class="form-label">Nomor HP</label><input name="phone" class="form-control" value="{{ old('phone') }}"></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="1">{{ old('address') }}</textarea></div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input name="password" id="password_input" type="password" class="form-control" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_input"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input name="password_confirmation" id="password_confirmation_input" type="password" class="form-control" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation_input"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <button class="btn btn-success w-100"><i class="bi bi-person-plus me-1"></i>Register</button>
                <a href="{{ route('login.role', $role) }}" class="btn btn-link w-100 mt-2 text-success">Sudah punya akun</a>
            </form>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = document.getElementById(this.dataset.target);
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
    });
</script>
@endsection
