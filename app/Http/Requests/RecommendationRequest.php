<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecommendationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string'],
        ];
    }
}
