<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiMeasurementRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $weight = $this->weight ?? $this->weight;
        
        // Clamp negative weights to 0 to handle HX711 sensor drift
        if (is_numeric($weight) && $weight < 0) {
            $weight = 0;
        }

        $this->merge([
            'weight' => $weight,
            'height' => $this->height ?? $this->height,
            'head_circumference' => $this->head_circumference ?? $this->head_circumference,
        ]);
    }

    public function rules(): array
    {
        return [
            'child_id' => ['nullable', 'exists:children,id'],
            'weight' => ['required', 'numeric', 'min:0', 'max:80'],
            'height' => ['required', 'numeric', 'min:0', 'max:150'],
            'head_circumference' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'measurement_date' => ['nullable', 'date'],
            'measurement_time' => ['nullable', 'date_format:H:i:s'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Log::error('API Validation Failed: ', $validator->errors()->toArray());
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
