<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\WeightStandard;
use App\Models\HeightStandard;
use App\Models\HeadCircumferenceStandard;

class FullNutritionStandardSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        WeightStandard::truncate();
        HeightStandard::truncate();
        HeadCircumferenceStandard::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $genders = ['male', 'female'];

        foreach ($genders as $gender) {
            for ($i = 0; $i <= 60; $i++) {
                $ageLabel = $i . ' Bulan';
                if ($i == 0) $ageLabel = 'Baru Lahir';
                elseif ($i % 12 == 0) $ageLabel = ($i / 12) . ' Tahun';

                if ($gender === 'male') {
                    // Male WHO estimation
                    $minW = round(2.5 + ($i * (13.7 - 2.5) / 60), 2);
                    $maxW = round(4.4 + ($i * (24.2 - 4.4) / 60), 2);

                    $minH = round(46.1 + ($i * (99.1 - 46.1) / 60), 2);
                    $maxH = round(53.7 + ($i * (118.9 - 53.7) / 60), 2);

                    $minHC = round(33.1 + ($i * (48.2 - 33.1) / 60), 2);
                    $maxHC = round(38.3 + ($i * (54.0 - 38.3) / 60), 2);
                } else {
                    // Female WHO estimation (slightly lower)
                    $minW = round(2.4 + ($i * (13.3 - 2.4) / 60), 2);
                    $maxW = round(4.2 + ($i * (24.0 - 4.2) / 60), 2);

                    $minH = round(45.4 + ($i * (98.2 - 45.4) / 60), 2);
                    $maxH = round(52.9 + ($i * (118.0 - 52.9) / 60), 2);

                    $minHC = round(32.7 + ($i * (47.2 - 32.7) / 60), 2);
                    $maxHC = round(37.3 + ($i * (53.0 - 37.3) / 60), 2);
                }

                WeightStandard::create([
                    'age_months' => $i,
                    'gender' => $gender,
                    'age_label' => $ageLabel,
                    'min_weight' => $minW,
                    'max_weight' => $maxW
                ]);

                HeightStandard::create([
                    'age_months' => $i,
                    'gender' => $gender,
                    'age_label' => $ageLabel,
                    'min_height' => $minH,
                    'max_height' => $maxH
                ]);

                HeadCircumferenceStandard::create([
                    'age_months' => $i,
                    'gender' => $gender,
                    'age_label' => $ageLabel,
                    'min_head_circumference' => $minHC,
                    'max_head_circumference' => $maxHC
                ]);
            }
        }
    }
}
