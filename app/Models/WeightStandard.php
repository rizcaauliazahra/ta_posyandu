<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightStandard extends Model
{
    protected $fillable = ['age_months', 'gender', 'age_label', 'min_weight', 'max_weight'];
}
