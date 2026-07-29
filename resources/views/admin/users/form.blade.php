@extends('layouts.app', ['title' => $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna'])

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm"><div class="card-body">
        <form method="POST" action="{{ $user->exists ? route('admin.users.update',$user) : route('admin.users.store') }}">
            @csrf @if($user->exists) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Nama Anak</label><input name="name" class="form-control" value="{{ old('name',$user->name) }}" required></div>
                <div class="col-12"><label class="form-label">Email</label><input name="email" type="email" class="form-control" value="{{ old('email',$user->email) }}" required></div>
                <div class="col-12"><label class="form-label">Nomor HP</label><input name="phone" class="form-control" value="{{ old('phone',$user->phone) }}"></div>
                <div class="col-12"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="1">{{ old('address',$user->address) }}</textarea></div>
                <div class="col-12"><label class="form-label">Role</label><select name="role_id" class="form-select">@foreach($roles as $role)<option value="{{ $role->id }}" @selected(old('role_id',$user->role_id)==$role->id)>{{ $role->label }}</option>@endforeach</select></div>
                <div class="col-12"><label class="form-label">Password</label><input name="password" type="text" class="form-control" value="{{ old('password', $user->plain_password) }}" {{ $user->exists ? '' : 'required' }}></div>
                <div class="col-12"><label class="form-label">Nama Ayah</label><input name="father_name" class="form-control" value="{{ old('father_name',$user->child?->father_name) }}"></div>
                <div class="col-12"><label class="form-label">Nama Ibu</label><input name="mother_name" class="form-control" value="{{ old('mother_name',$user->child?->mother_name) }}"></div>
                <div class="col-12"><label class="form-label">Tempat Lahir</label><input name="birth_place" class="form-control" value="{{ old('birth_place',$user->child?->birth_place) }}"></div>
                <div class="col-12"><label class="form-label">Tanggal Lahir</label><input name="birth_date" type="date" class="form-control" value="{{ old('birth_date',$user->child?->birth_date?->format('Y-m-d')) }}"></div>
                <div class="col-12"><label class="form-label">Jenis Kelamin</label><select name="gender" class="form-select"><option value="">-</option><option value="male" @selected(old('gender',$user->child?->gender)==='male')>Laki-laki</option><option value="female" @selected(old('gender',$user->child?->gender)==='female')>Perempuan</option></select></div>
            </div>
            <div class="mt-4 text-center">
                <button class="btn btn-success px-4 rounded-pill"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4 rounded-pill ms-2">Batal</a>
            </div>
        </form>
        </div></div>
    </div>
</div>
@endsection
