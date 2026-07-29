<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeightStandard extends Model
{
    protected $fillable = ['age_months', 'gender', 'age_label', 'min_height', 'max_height'];
}
