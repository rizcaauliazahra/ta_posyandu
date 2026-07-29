@extends('layouts.app', ['title' => 'Data Pengguna'])

@section('content')
<div class="card border-0 shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-between mb-3"><form class="d-flex gap-2"><select name="role" class="form-select" onchange="this.form.submit()"><option value="user" {{ request('role', 'user') == 'user' ? 'selected' : '' }}>Pengguna</option><option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option></select><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama/email"><button class="btn btn-outline-success"><i class="bi bi-search"></i></button></form><a href="{{ route('admin.users.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah</a></div>
    <div class="table-responsive"><table class="table table-hover align-middle"><thead>
        @if(request('role', 'user') == 'admin')
            <tr><th>Nama</th><th>Email</th><th>Password</th><th>Aksi</th></tr>
        @else
            <tr><th>Nama Anak</th><th>Gender</th><th>Tanggal Lahir</th><th>Nama Ayah</th><th>Nama Ibu</th><th>Email</th><th>Nomor HP</th><th>Alamat</th><th>Password</th><th>Aksi</th></tr>
        @endif
        </thead><tbody>
    @foreach($users as $user)
        @if(request('role', 'user') == 'admin')
            <tr><td>{{ $user->name ?? '-' }}</td><td>{{ $user->email }}</td><td>{{ $user->plain_password ?: '-' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit',$user) }}"><i class="bi bi-pencil"></i></a> <form class="d-inline" method="POST" action="{{ route('admin.users.destroy',$user) }}" data-confirm>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
        @else
            <tr><td>{{ $user->child?->name ?? '-' }}</td><td>{{ $user->child?->gender ? ($user->child->gender === 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}</td><td>{{ $user->child?->birth_date?->format('d/m/Y') ?? '-' }}</td><td>{{ $user->child?->father_name ?? '-' }}</td><td>{{ $user->child?->mother_name ?? '-' }}</td><td>{{ $user->email }}</td><td>{{ $user->phone ?? '-' }}</td><td>{{ $user->address ?? '-' }}</td><td>{{ $user->plain_password ?: '-' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit',$user) }}"><i class="bi bi-pencil"></i></a> <form class="d-inline" method="POST" action="{{ route('admin.users.destroy',$user) }}" data-confirm>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
        @endif
    @endforeach
    </tbody></table></div>{{ $users->links() }}
</div></div>
@endsection
