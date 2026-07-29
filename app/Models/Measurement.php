<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable = [
        'child_id',
        'weight',
        'height',
        'head_circumference',
        'measurement_date',
        'measurement_time',
        'age_months',
        'weight_status',
        'height_status',
        'head_circumference_status',
        'recommendation',
        'additional_recommendation',
    ];

    protected $appends = ['overall_status'];

    protected function casts(): array
    {
        return [
            'measurement_date' => 'date',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'head_circumference' => 'decimal:2',
        ];
    }

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function getOverallStatusAttribute(): string
    {
        $statuses = [$this->weight_status, $this->height_status, $this->head_circumference_status];
        if (in_array('Berat Kurang', $statuses) || in_array('Tinggi Kurang', $statuses) || in_array('Lingkar Kepala Kurang', $statuses)) {
            return 'Kurang';
        }
        if (in_array('Berat Berlebih', $statuses) || in_array('Tinggi Di Atas Rata-rata', $statuses) || in_array('Lingkar Kepala Berlebih', $statuses)) {
            return 'Lebih';
        }
        return 'Normal';
    }
}
