<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'child'])
            ->when(request('q'), function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhereHas('child', function ($childQuery) use ($search) {
                            $childQuery->where('name', 'like', "%$search%");
                        });
                });
            })
            ->when(request('role', 'user'), function ($q, $role) {
                $q->whereHas('role', fn ($r) => $r->where('name', $role));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(), 'roles' => Role::all()]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'role_id' => $data['role_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => Hash::make($data['password']),
            'plain_password' => $data['password'],
        ]);

        if (($user->role?->name === 'user') || filled($data['child_name'] ?? null)) {
            $user->child()->create([
                'name' => ($data['child_name'] ?? null) ?: $data['name'],
                'birth_date' => $data['birth_date'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'gender' => $data['gender'] ?? null,
                'father_name' => $data['father_name'] ?? null,
                'mother_name' => $data['mother_name'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $user->load('child');
        return view('admin.users.form', ['user' => $user, 'roles' => Role::all()]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        $payload = ['role_id' => $data['role_id'], 'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'] ?? null, 'address' => $data['address'] ?? null];
        if (filled($data['password'] ?? null)) {
            $payload['password'] = Hash::make($data['password']);
            $payload['plain_password'] = $data['password'];
        }
        $user->update($payload);
        if ($user->child) {
            $user->child->update([
                'name' => ($data['child_name'] ?? null) ?: $data['name'],
                'birth_date' => $data['birth_date'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'gender' => $data['gender'] ?? null,
                'father_name' => $data['father_name'] ?? null,
                'mother_name' => $data['mother_name'] ?? null,
            ]);
        } else {
            $user->child()->create([
                'name' => ($data['child_name'] ?? null) ?: $data['name'],
                'birth_date' => $data['birth_date'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'gender' => $data['gender'] ?? null,
                'father_name' => $data['father_name'] ?? null,
                'mother_name' => $data['mother_name'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if(auth()->id() === $user->id, 403, 'Admin tidak dapat menghapus akun sendiri.');
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
