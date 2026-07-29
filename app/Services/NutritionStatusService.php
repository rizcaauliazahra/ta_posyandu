<?php

namespace App\Services;

use App\Models\HeightStandard;
use App\Models\Recommendation;
use App\Models\WeightStandard;
use App\Models\HeadCircumferenceStandard;

class NutritionStatusService
{
    public function evaluate(float $weight, float $height, ?float $headCircumference, int $ageMonths, string $gender = 'male'): array
    {
        $weightStandard = WeightStandard::query()
            ->where('gender', $gender)
            ->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$ageMonths])
            ->first();
        $heightStandard = HeightStandard::query()
            ->where('gender', $gender)
            ->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$ageMonths])
            ->first();
        $headCircumferenceStandard = HeadCircumferenceStandard::query()
            ->where('gender', $gender)
            ->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$ageMonths])
            ->first();

        $weightStatus = 'Berat Normal';
        if ($weightStandard && $weight < $weightStandard->min_weight) {
            $weightStatus = 'Berat Kurang';
        } elseif ($weightStandard && $weight > $weightStandard->max_weight) {
            $weightStatus = 'Berat Berlebih';
        }

        $heightStatus = 'Tinggi Normal';
        if ($heightStandard && $height < $heightStandard->min_height) {
            $heightStatus = 'Tinggi Kurang';
        } elseif ($heightStandard && $height > $heightStandard->max_height) {
            $heightStatus = 'Tinggi Di Atas Rata-rata';
        }

        $headCircumferenceStatus = 'Normal';
        if ($headCircumference !== null) {
            if ($headCircumferenceStandard && $headCircumference < $headCircumferenceStandard->min_head_circumference) {
                $headCircumferenceStatus = 'Mikrosefali';
            } elseif ($headCircumferenceStandard && $headCircumference > $headCircumferenceStandard->max_head_circumference) {
                $headCircumferenceStatus = 'Makrosefali';
            }
        } else {
            $headCircumferenceStatus = null;
        }

        $statusesToFetch = array_filter([$weightStatus, $heightStatus, $headCircumferenceStatus]);

        $recommendations = Recommendation::query()
            ->whereIn('status', $statusesToFetch)
            ->pluck('content', 'status');

        $recText = trim(($recommendations[$weightStatus] ?? '')."\n\n".($recommendations[$heightStatus] ?? '')."\n\n".($recommendations[$headCircumferenceStatus] ?? ''));

        return [
            'weight_status' => $weightStatus,
            'height_status' => $heightStatus,
            'head_circumference_status' => $headCircumferenceStatus,
            'recommendation' => trim($recText),
        ];
    }
}
