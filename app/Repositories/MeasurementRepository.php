<?php

namespace App\Repositories;

use App\Models\Child;
use App\Models\Measurement;
use App\Services\NutritionStatusService;
use Illuminate\Support\Carbon;

class MeasurementRepository
{
    public function __construct(private NutritionStatusService $nutritionStatusService)
    {
    }

    public function createForChild(Child $child, array $data): Measurement
    {
        $date = Carbon::parse($data['measurement_date'] ?? now());
        $time = $data['measurement_time'] ?? now()->format('H:i:s');
        $ageMonths = $child->birth_date ? max(0, $child->birth_date->diffInMonths($date)) : 12;
        $status = $this->nutritionStatusService->evaluate((float) $data['weight'], (float) $data['height'], $data['head_circumference'] ?? null, $ageMonths, $child->gender ?? 'male');

        return Measurement::create([
            'child_id' => $child->id,
            'weight' => $data['weight'],
            'height' => $data['height'],
            'head_circumference' => $data['head_circumference'] ?? null,
            'measurement_date' => $date->toDateString(),
            'measurement_time' => $time,
            'age_months' => $ageMonths,
            'additional_recommendation' => $data['additional_recommendation'] ?? null,
            ...$status,
        ]);
    }

    public function update(Measurement $measurement, array $data): Measurement
    {
        $child = $measurement->child;
        $date = Carbon::parse($data['measurement_date'] ?? $measurement->measurement_date);
        $ageMonths = $child->birth_date ? max(0, $child->birth_date->diffInMonths($date)) : 0;
        $status = $this->nutritionStatusService->evaluate((float) $data['weight'], (float) $data['height'], $data['head_circumference'] ?? null, $ageMonths, $child->gender ?? 'male');

        $measurement->update([
            'weight' => $data['weight'],
            'height' => $data['height'],
            'head_circumference' => $data['head_circumference'] ?? null,
            'measurement_date' => $date->toDateString(),
            'measurement_time' => $data['measurement_time'] ?? $measurement->measurement_time,
            'age_months' => $ageMonths,
            'additional_recommendation' => array_key_exists('additional_recommendation', $data) ? $data['additional_recommendation'] : $measurement->additional_recommendation,
            ...$status,
        ]);

        return $measurement;
    }
}
