<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WeightStandard;
use App\Models\HeightStandard;
use App\Models\HeadCircumferenceStandard;

// Read the blade file
$content = file_get_contents(resource_path('views/user/grafik.blade.php'));

function extractArray($content, $regex) {
    if (preg_match($regex, $content, $matches)) {
        // Evaluate the JS array string as a PHP array
        // It looks like: [0, 2.0, ...], [1, 2.7, ...]
        $str = $matches[1];
        // clean up
        $str = preg_replace('/\]\s*,\s*\[/', '],[', $str);
        $str = str_replace(array("\r", "\n", " "), '', $str);
        $json = '[' . $str . ']';
        return json_decode($json, true);
    }
    return [];
}

$femaleWeightData = extractArray($content, '/const femaleWeightData = \[\s*(.*?)\s*\];/s');
$femaleHeightData = extractArray($content, '/const femaleHeightData = \[\s*(.*?)\s*\];/s');
$femaleHcData = extractArray($content, '/const femaleHcData = \[\s*(.*?)\s*\];/s');

$medianPointsW = extractArray($content, '/const medianPointsW = \[\s*(.*?)\s*\];/s');
$medianPointsH = extractArray($content, '/const medianPointsH = \[\s*(.*?)\s*\];/s');

// 24-60 median
$medianPointsW2Female = extractArray($content, '/const medianPointsW2Female = \[\s*(.*?)\s*\];/s');
$medianPointsH2Female = extractArray($content, '/const medianPointsH2Female = \[\s*(.*?)\s*\];/s');
$medianPointsW2 = extractArray($content, '/const medianPointsW2 = \[\s*(.*?)\s*\];/s');
$medianPointsH2 = extractArray($content, '/const medianPointsH2 = \[\s*(.*?)\s*\];/s');

function getInterpolatedFemale($data, $m, $sdIndex) {
    if (empty($data)) return 0;
    for ($i = 0; $i < count($data) - 1; $i++) {
        if ($m >= $data[$i][0] && $m <= $data[$i+1][0]) {
            $ratio = ($m - $data[$i][0]) / ($data[$i+1][0] - $data[$i][0]);
            return $data[$i][$sdIndex] + $ratio * ($data[$i+1][$sdIndex] - $data[$i][$sdIndex]);
        }
    }
    return $data[count($data) - 1][$sdIndex];
}

function getMedian($data, $m) {
    if (empty($data)) return 0;
    for ($i = 0; $i < count($data) - 1; $i++) {
        if ($m >= $data[$i][0] && $m <= $data[$i+1][0]) {
            $ratio = ($m - $data[$i][0]) / ($data[$i+1][0] - $data[$i][0]);
            return $data[$i][1] + $ratio * ($data[$i+1][1] - $data[$i][1]);
        }
    }
    return $data[count($data) - 1][1];
}

// Update DB
WeightStandard::truncate();
HeightStandard::truncate();
HeadCircumferenceStandard::truncate();

foreach (['male', 'female'] as $gender) {
    for ($m = 0; $m <= 60; $m++) {
        $ageLabel = $m == 0 ? 'Baru Lahir' : ($m % 12 == 0 ? ($m / 12) . ' Tahun' : $m . ' Bulan');
        
        $minW = $maxW = $minH = $maxH = $minHc = $maxHc = 0;
        
        if ($gender === 'female') {
            if ($m <= 24) {
                $minW = getInterpolatedFemale($femaleWeightData, $m, 2); // -2 SD
                $maxW = (getInterpolatedFemale($femaleWeightData, $m, 3) + getInterpolatedFemale($femaleWeightData, $m, 4)) / 2; // +1 SD approximation
                
                $minH = getInterpolatedFemale($femaleHeightData, $m, 2); // -2 SD
                $maxH = getInterpolatedFemale($femaleHeightData, $m, 5); // +3 SD
                
                $minHc = getInterpolatedFemale($femaleHcData, $m, 2); // -2 SD
                $maxHc = getInterpolatedFemale($femaleHcData, $m, 6); // +2 SD (in hc data, index 6 is +2 SD, wait index 1 is -3, 2 is -2, 3 is -1, 4 is 0, 5 is +1, 6 is +2. femaleHcData has 8 elements per row: [age, -3, -2, -1, 0, 1, 2, 3])
            } else {
                $medW = getMedian($medianPointsW2Female ?: [[24,12.2],[60,18.0]], $m);
                $minW = $medW - (2 * ($medW * 0.11));
                $maxW = $medW + (1 * ($medW * 0.11));
                
                $medH = getMedian($medianPointsH2Female ?: [[24,85.5],[60,108.4]], $m);
                $minH = $medH - (2 * ($medH * 0.04));
                $maxH = $medH + (3 * ($medH * 0.04));
                
                $hc_m = min($m, 24);
                $minHc = getInterpolatedFemale($femaleHcData, $hc_m, 2);
                $maxHc = getInterpolatedFemale($femaleHcData, $hc_m, 6);
            }
        } else {
            if ($m <= 24) {
                $medW = getMedian($medianPointsW, $m);
                $sdW = 0.4 + ($m * 0.035);
                $minW = $medW - (2 * $sdW);
                $maxW = $medW + (1 * $sdW);
                
                $medH = getMedian($medianPointsH, $m);
                $sdH = 1.9 + ($m * 0.054);
                $minH = $medH - (2 * $sdH);
                $maxH = $medH + (3 * $sdH);
                
                // hc: 1.2 + (m * 0.02)
                $medHc = 34.5 + ($m * 0.5); // very rough fallback for medianHc if missing
                $sdHc = 1.2 + ($m * 0.02);
                $minHc = $medHc - (2 * $sdHc);
                $maxHc = $medHc + (2 * $sdHc);
            } else {
                $medW = getMedian($medianPointsW2, $m);
                $sdW = 0.5 + (($m-24) * 0.035);
                $minW = $medW - (2 * $sdW);
                $maxW = $medW + (1 * $sdW);
                
                $medH = getMedian($medianPointsH2, $m);
                $sdH = 2.5 + (($m-24) * 0.05);
                $minH = $medH - (2 * $sdH);
                $maxH = $medH + (3 * $sdH);
                
                $hc_m = 24;
                $medHc = 34.5 + ($hc_m * 0.5);
                $sdHc = 1.2 + ($hc_m * 0.02);
                $minHc = $medHc - (2 * $sdHc);
                $maxHc = $medHc + (2 * $sdHc);
            }
        }
        
        WeightStandard::updateOrCreate(
            ['age_months' => $m, 'gender' => $gender],
            ['age_label' => $ageLabel, 'min_weight' => round($minW, 2), 'max_weight' => round($maxW, 2)]
        );
        HeightStandard::updateOrCreate(
            ['age_months' => $m, 'gender' => $gender],
            ['age_label' => $ageLabel, 'min_height' => round($minH, 2), 'max_height' => round($maxH, 2)]
        );
        HeadCircumferenceStandard::updateOrCreate(
            ['age_months' => $m, 'gender' => $gender],
            ['age_label' => $ageLabel, 'min_head_circumference' => round($minHc, 2), 'max_head_circumference' => round($maxHc, 2)]
        );
    }
}
echo "Standards successfully updated!\n";
