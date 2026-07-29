<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeadCircumferenceStandard;

class HeadCircumferenceStandardSeeder extends Seeder
{
    public function run(): void
    {
        // Menggabungkan nilai minimal dari Perempuan dan maksimal dari Laki-Laki
        // agar sistem yang tidak membedakan gender tetap bisa memvalidasi keduanya.
        $data = [
            ['age_months' => 12, 'age_label' => '1 Tahun', 'min_head_circumference' => 44, 'max_head_circumference' => 49],
            ['age_months' => 24, 'age_label' => '2 Tahun', 'min_head_circumference' => 46, 'max_head_circumference' => 51],
            ['age_months' => 36, 'age_label' => '3 Tahun', 'min_head_circumference' => 48, 'max_head_circumference' => 53],
            ['age_months' => 48, 'age_label' => '4 Tahun', 'min_head_circumference' => 49, 'max_head_circumference' => 53],
            ['age_months' => 60, 'age_label' => '5 Tahun', 'min_head_circumference' => 50, 'max_head_circumference' => 54],
        ];

        foreach($data as $row) {
            HeadCircumferenceStandard::updateOrCreate(['age_months' => $row['age_months']], $row);
        }
    }
}
