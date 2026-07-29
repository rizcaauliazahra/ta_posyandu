<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\HeightStandard;
use App\Models\Measurement;
use App\Models\Recommendation;
use App\Models\Role;
use App\Models\User;
use App\Models\WeightStandard;
use App\Services\NutritionStatusService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(['name' => 'admin'], ['label' => 'Admin']);
        $userRole = Role::updateOrCreate(['name' => 'user'], ['label' => 'Pengguna']);

        $standards = [
            [0, 'Lahir', 2.5, 4.0, 48, 52],
            [1, '1 bulan', 3.4, 5.8, 51, 56],
            [2, '2 bulan', 4.3, 6.8, 54, 59],
            [3, '3 bulan', 5.0, 7.5, 57, 62],
            [4, '4 bulan', 5.6, 8.1, 59, 65],
            [5, '5 bulan', 6.0, 8.7, 61, 67],
            [6, '6 bulan', 6.4, 9.2, 63, 69],
            [7, '7 bulan', 6.7, 9.6, 64, 71],
            [8, '8 bulan', 7.0, 10.0, 66, 72],
            [9, '9 bulan', 7.2, 10.4, 67, 74],
            [10, '10 bulan', 7.5, 10.8, 69, 75],
            [11, '11 bulan', 7.7, 11.0, 70, 76],
            [12, '12 bulan', 7.9, 11.3, 71, 78],
            [18, '18 bulan', 8.8, 13.2, 77, 84],
            [24, '2 tahun', 9.7, 15.3, 82, 89],
            [36, '3 tahun', 11.3, 18.3, 89, 97],
            [48, '4 tahun', 12.7, 21.2, 96, 104],
            [60, '5 tahun', 14.1, 24.2, 103, 111],
        ];

        foreach ($standards as [$ageMonths, $label, $minWeight, $maxWeight, $minHeight, $maxHeight]) {
            WeightStandard::updateOrCreate(['age_months' => $ageMonths], [
                'age_label' => $label,
                'min_weight' => $minWeight,
                'max_weight' => $maxWeight,
            ]);
            HeightStandard::updateOrCreate(['age_months' => $ageMonths], [
                'age_label' => $label,
                'min_height' => $minHeight,
                'max_height' => $maxHeight,
            ]);
        }

        $recommendations = [
            'Berat Kurang' => "Berikan ASI eksklusif (0-6 bulan).\nBerikan MPASI bergizi seimbang mulai usia 6 bulan.\nTingkatkan asupan protein.\nBerikan makanan 3 kali sehari dan camilan sehat.\nPantau pertumbuhan setiap bulan.\nKonsultasikan ke dokter atau ahli gizi.",
            'Berat Normal' => "Pertahankan pola makan bergizi seimbang.\nBerikan buah dan sayur.\nTetap aktif.\nPemeriksaan rutin ke Posyandu.",
            'Berat Berlebih' => "Kurangi makanan tinggi gula.\nKurangi makanan tinggi lemak.\nPerbanyak aktivitas fisik.\nBatasi screen time.\nKonsultasikan ke dokter.",
            'Tinggi Kurang' => "Tingkatkan protein.\nTingkatkan kalsium.\nTingkatkan zinc.\nTidur cukup.\nPantau pertumbuhan.",
            'Tinggi Normal' => "Pertahankan pola makan sehat.\nMinum susu.\nAktivitas fisik.",
            'Tinggi Di Atas Rata-rata' => "Tidak memerlukan tindakan khusus bila proporsional.\nTetap pantau perkembangan.",
        ];

        foreach ($recommendations as $status => $content) {
            Recommendation::updateOrCreate(['status' => $status], ['content' => $content]);
        }

        User::updateOrCreate(['email' => 'admin@posyandu.test'], [
            'role_id' => $adminRole->id,
            'username' => 'admin_posyandu',
            'name' => 'Admin Posyandu',
            'password' => Hash::make('password'),
        ]);

        $parent = User::updateOrCreate(['email' => 'orangtua@posyandu.test'], [
            'role_id' => $userRole->id,
            'name' => 'Budi',
            'password' => Hash::make('password'),
        ]);
        $child = Child::updateOrCreate(['user_id' => $parent->id], [
            'name' => 'Budi',
            'birth_date' => now()->subMonths(12)->toDateString(),
            'gender' => 'male',
        ]);

        if ($child->measurement()->count() === 0) {
            $service = app(NutritionStatusService::class);
            foreach ([9.0, 9.3, 9.7, 10.0, 10.2] as $index => $weight) {
                $height = 72 + $index;
                $status = $service->evaluate($weight, $height, null, 12);
                Measurement::create([
                    'child_id' => $child->id,
                    'weight' => $weight,
                    'height' => $height,
                    'measurement_date' => now()->subMonths(4 - $index)->toDateString(),
                    'measurement_time' => '08:00:00',
                    'age_months' => 12,
                    ...$status,
                ]);
            }
        }
    }
}
