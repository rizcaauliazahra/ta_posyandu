<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = ['user_id', 'name', 'birth_date', 'birth_place', 'gender', 'photo', 'father_photo', 'mother_photo', 'father_name', 'mother_name'];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function measurement()
    {
        return $this->hasMany(Measurement::class, 'child_id');
    }

    public function ageMonths(): int
    {
        return $this->birth_date ? max(0, (int) $this->birth_date->diffInMonths(now())) : 12;
    }
}
