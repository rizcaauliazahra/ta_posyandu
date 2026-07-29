<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        $role = $this->input('role', 'user');

        return [
            'role' => ['required', 'in:admin,user'],
            'username' => [Rule::requiredIf($role === 'admin'), 'nullable', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'child_name' => [Rule::requiredIf($role === 'user'), 'nullable', 'string', 'max:255'],
            'gender' => [Rule::requiredIf($role === 'user'), 'nullable', 'in:male,female'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ];
    }
}
