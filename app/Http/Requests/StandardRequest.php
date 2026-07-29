<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StandardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'age_months' => ['required', 'integer', 'min:0', 'max:72'],
            'gender' => ['required', 'in:male,female'],
            'age_label' => ['required', 'string', 'max:50'],
            'min_value' => ['required', 'numeric', 'min:0'],
            'max_value' => ['required', 'numeric', 'gte:min_value'],
        ];
    }
}
