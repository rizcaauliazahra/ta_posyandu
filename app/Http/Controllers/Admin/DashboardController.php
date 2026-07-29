<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $gender = $request->query('gender', 'male');
        if (!in_array($gender, ['male', 'female'])) {
            $gender = 'male';
        }

        $measurement = Measurement::with('child')->latest('measurement_date')->latest('measurement_time')->take(8)->get();
        
        // Data untuk grafik batang (Jumlah Measurement per Bulan)
        $sixMonthsAgo = \Carbon\Carbon::now()->subMonths(5)->startOfMonth();
        $monthlyMeasurement = Measurement::where('measurement_date', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(measurement_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartLabels = [];
        $chartData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $chartLabels[] = $month->translatedFormat('M Y');
            
            $found = $monthlyMeasurement->firstWhere('month', $monthKey);
            $chartData[] = $found ? $found->count : 0;
        }

        // 1. Jadwal Posyandu
        $upcomingSchedule = \App\Models\Setting::where('key', 'jadwal_posyandu')->value('value') ?? 'Tanggal 25 setiap bulannya';

        // 2. Anak Belum Diukur Berdasarkan Jadwal
        preg_match('/\d+/', $upcomingSchedule, $matches);
        $scheduleDate = $matches ? (int) $matches[0] : 25;
        $now = \Carbon\Carbon::now();
        if ($now->day >= $scheduleDate) {
            $startDate = $now->copy()->startOfMonth()->day($scheduleDate);
        } else {
            $startDate = $now->copy()->subMonth()->startOfMonth()->day($scheduleDate);
        }

        $unmeasuredChildren = \App\Models\Child::whereDoesntHave('measurement', function ($query) use ($startDate) {
            $query->where('measurement_date', '>=', $startDate);
        })->get();

        // 3. Statistik Status Gizi (dari measurement terakhir tiap anak)
        $latestMeasurementAll = Measurement::whereIn('id', function($query) {
            $query->selectRaw('MAX(id)')->from('measurements')->groupBy('child_id');
        })->get();

        $nutritionStats = [
            'overall' => [],
            'weight' => [],
            'height' => [],
            'head' => []
        ];

        foreach ($latestMeasurementAll as $m) {
            $ov = $m->overall_status ?? 'Tidak Diketahui';
            $wt = $m->weight_status ?? 'Tidak Diketahui';
            $ht = $m->height_status ?? 'Tidak Diketahui';
            $hc = $m->head_circumference_status ?? 'Tidak Diketahui';

            $nutritionStats['overall'][$ov] = ($nutritionStats['overall'][$ov] ?? 0) + 1;
            $nutritionStats['weight'][$wt] = ($nutritionStats['weight'][$wt] ?? 0) + 1;
            $nutritionStats['height'][$ht] = ($nutritionStats['height'][$ht] ?? 0) + 1;
            $nutritionStats['head'][$hc] = ($nutritionStats['head'][$hc] ?? 0) + 1;
        }

        return view('admin.dashboard', [
            'childrenCount' => \App\Models\Child::count(),
            'maleChildrenCount' => \App\Models\Child::where('gender', 'male')->count(),
            'femaleChildrenCount' => \App\Models\Child::where('gender', 'female')->count(),
            'measurementCount' => Measurement::count(),
            'latestMeasurement' => $measurement,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'upcomingSchedule' => $upcomingSchedule,
            'unmeasuredChildren' => $unmeasuredChildren,
            'nutritionStats' => $nutritionStats,
        ]);
    }

    public function updateSchedule(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'jadwal_posyandu' => 'required|string|max:255',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'jadwal_posyandu'],
            ['value' => $request->jadwal_posyandu]
        );

        return redirect()->back()->with('success', 'Jadwal Posyandu berhasil diperbarui!');
    }
}
