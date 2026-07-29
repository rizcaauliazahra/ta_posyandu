<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Child;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function chooseRole()
    {
        return view('auth.choose-role');
    }

    public function showLogin(?string $role = null)
    {
        abort_unless(in_array($role, ['admin', 'user'], true), 404);

        return view('auth.login', compact('role'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
            'role' => ['required', 'in:admin,user'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $authAttempted = Auth::attempt([
            $field => $credentials['login'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if (! $authAttempted) {
            return back()->withErrors(['login' => 'Email/username atau password tidak sesuai.'])->onlyInput('login');
        }

        if (Auth::user()->role?->name !== $credentials['role']) {
            Auth::logout();

            return back()
                ->withErrors(['role' => 'Role yang dipilih tidak sesuai dengan akun.'])
                ->onlyInput('login', 'role');
        }

        $request->session()->regenerate();

        return redirect()->intended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard'))
            ->with('success', 'Berhasil login.');
    }

    public function showRegister(?string $role = null)
    {
        abort_unless(in_array($role, ['admin', 'user'], true), 404);

        return view('auth.register', compact('role'));
    }

    public function register(RegisterRequest $request)
    {
        $role = Role::firstWhere('name', $request->input('role', 'user'));
        $name = $role->name === 'admin'
            ? $request->username
            : $request->child_name;

        $user = User::create([
            'role_id' => $role->id,
            'username' => $role->name === 'admin' ? $request->username : null,
            'name' => $name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
        ]);

        if ($role->name === 'user') {
            Child::create([
                'user_id' => $user->id,
                'name' => $request->child_name,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
            ]);
        }

        Auth::login($user);

        return redirect()->route($role->name === 'admin' ? 'admin.dashboard' : 'user.dashboard')
            ->with('success', 'Registrasi berhasil.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
