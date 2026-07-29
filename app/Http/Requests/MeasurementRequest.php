<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeasurementRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'child_id' => ['required', 'exists:children,id'],
            'weight' => ['required', 'numeric', 'min:0', 'max:80'],
            'height' => ['required', 'numeric', 'min:0', 'max:150'],
            'measurement_date' => ['required', 'date'],
            'measurement_time' => ['required', 'date_format:H:i'],
            'age_months' => ['nullable', 'integer', 'min:0', 'max:72'],
        ];
    }
}
