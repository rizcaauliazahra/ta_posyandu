<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadCircumferenceStandard extends Model
{
    protected $fillable = [
        'age_months',
        'gender',
        'age_label',
        'min_head_circumference',
        'max_head_circumference',
    ];

    protected function casts(): array
    {
        return [
            'age_months' => 'integer',
            'min_head_circumference' => 'decimal:2',
            'max_head_circumference' => 'decimal:2',
        ];
    }
}
