<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Repositories\MeasurementRepository;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $child = $request->user()->child()->firstOrFail();
        
        $latest = Cache::get("live_measurement_child_{$child->id}");
        if (!$latest) {
            $latestModel = $child->measurement()->latest('measurement_date')->latest('measurement_time')->first();
            if ($latestModel) {
                $latest = $latestModel->toArray();
                $latest['overall_status'] = $latestModel->overall_status;
                $latest['is_live'] = false;
            }
        } else {
            $latest['overall_status'] = (new \App\Models\Measurement($latest))->overall_status;
            $latest['is_live'] = true;
        }

        $latest = $latest ? (array) $latest : null;
        if ($latest) {
            $age = $child->ageMonths();
            $gen = $child->gender;

            $ws = \App\Models\WeightStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
            if ($ws && isset($latest['weight']) && $latest['weight'] !== null) {
                $normalWeight = ($ws->min_weight + $ws->max_weight) / 2;
                $diff = (float)$latest['weight'] - $normalWeight;
                $latest['weight_diff_text'] = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' kg)' : '(Kurang ' . round(abs($diff), 2) . ' kg)');
            } else {
                $latest['weight_diff_text'] = '';
            }

            $hs = \App\Models\HeightStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
            if ($hs && isset($latest['height']) && $latest['height'] !== null) {
                $normalHeight = ($hs->min_height + $hs->max_height) / 2;
                $diff = (float)$latest['height'] - $normalHeight;
                $latest['height_diff_text'] = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' cm)' : '(Kurang ' . round(abs($diff), 2) . ' cm)');
            } else {
                $latest['height_diff_text'] = '';
            }

            $hcs = \App\Models\HeadCircumferenceStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
            if ($hcs && isset($latest['head_circumference']) && $latest['head_circumference'] !== null) {
                $normalHc = ($hcs->min_head_circumference + $hcs->max_head_circumference) / 2;
                $diff = (float)$latest['head_circumference'] - $normalHc;
                $latest['hc_diff_text'] = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' cm)' : '(Kurang ' . round(abs($diff), 2) . ' cm)');
            } else {
                $latest['hc_diff_text'] = '';
            }
        }

        $latest = $latest ? (object) $latest : null;

        $upcomingSchedule = \App\Models\Setting::where('key', 'jadwal_posyandu')->value('value') ?? 'Tanggal 25 setiap bulannya';
        $needsMeasurementThisMonth = false;
        
        if ($latest) {
            $lastMeasurementDate = \Carbon\Carbon::parse($latest->measurement_date);
            if (!$lastMeasurementDate->isCurrentMonth()) {
                $needsMeasurementThisMonth = true;
            }
        } else {
            $needsMeasurementThisMonth = true;
        }

        return view('user.dashboard', compact('child', 'latest', 'upcomingSchedule', 'needsMeasurementThisMonth'));
    }

    public function profil(Request $request)
    {
        $child = $request->user()->child()->firstOrFail();
        return view('user.profil', compact('child'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'father_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'mother_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $child = $request->user()->child()->firstOrFail();
        $data = [];

        if ($request->hasFile('photo')) {
            if ($child->photo) \Illuminate\Support\Facades\Storage::disk('public')->delete($child->photo);
            $data['photo'] = $request->file('photo')->store('children_photos', 'public');
        }
        if ($request->hasFile('father_photo')) {
            if ($child->father_photo) \Illuminate\Support\Facades\Storage::disk('public')->delete($child->father_photo);
            $data['father_photo'] = $request->file('father_photo')->store('parent_photos', 'public');
        }
        if ($request->hasFile('mother_photo')) {
            if ($child->mother_photo) \Illuminate\Support\Facades\Storage::disk('public')->delete($child->mother_photo);
            $data['mother_photo'] = $request->file('mother_photo')->store('parent_photos', 'public');
        }

        if (!empty($data)) {
            $child->update($data);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function grafik(Request $request)
    {
        $child = $request->user()->child()->firstOrFail();
        $measurement = $child->measurement()->orderBy('measurement_date')->get();

        $weightData = [];
        $heightData = [];
        $headCircumferenceData = [];
        $weightLengthData = [];
        $bmiData = [];

        foreach ($measurement as $m) {
            $ageMonths = $child->birth_date ? (int) $child->birth_date->diffInMonths($m->measurement_date) : 0;
            $weightData[] = ['x' => $ageMonths, 'y' => (float)$m->weight];
            $heightData[] = ['x' => $ageMonths, 'y' => (float)$m->height];
            $weightLengthData[] = ['x' => (float)$m->height, 'y' => (float)$m->weight];
            if ($m->height > 0) {
                $bmiData[] = ['x' => $ageMonths, 'y' => round($m->weight / (($m->height / 100) * ($m->height / 100)), 2)];
            }
            if ($m->head_circumference !== null) {
                $headCircumferenceData[] = ['x' => $ageMonths, 'y' => (float)$m->head_circumference];
            }
        }

        return view('user.grafik', [
            'child' => $child,
            'weightData' => collect($weightData)->values(),
            'heightData' => collect($heightData)->values(),
            'headCircumferenceData' => collect($headCircumferenceData)->values(),
            'weightLengthData' => collect($weightLengthData)->values(),
            'bmiData' => collect($bmiData)->values(),
        ]);
    }

    public function riwayat(Request $request)
    {
        $child = $request->user()->child()->firstOrFail();
        $measurement = $child->measurement()->latest('measurement_date')->latest('measurement_time')->paginate(10);
        return view('user.riwayat', compact('measurement', 'child'));
    }

    public function latest(Request $request)
    {
        $child = $request->user()->child()->firstOrFail();
        
        \Illuminate\Support\Facades\Cache::put('active_iot_child_id', $child->id, now()->addMinutes(5));

        $latest = Cache::get("live_measurement_child_{$child->id}");
        
        if (!$latest) {
            $latestModel = $child->measurement()->latest('measurement_date')->latest('measurement_time')->first();
            if ($latestModel) {
                $latest = $latestModel->toArray();
                $latest['overall_status'] = $latestModel->overall_status;
                $latest['is_live'] = false;
            }
        } else {
            $latest['overall_status'] = (new \App\Models\Measurement($latest))->overall_status;
            $latest['is_live'] = true;
        }

        return response()->json([
            'child' => [
                'name' => $child->name,
                'age_months' => $child->ageMonths(),
                'birth_date' => $child->birth_date?->format('d/m/Y'),
                'gender' => $child->gender === 'male' ? 'Laki-laki' : 'Perempuan',
            ],
            'latest' => call_user_func(function() use ($latest, $child) {
                if (!$latest) return null;
                $weight_diff_text = null;
                $height_diff_text = null;
                $hc_diff_text = null;
                $weight_normal_limit = null;
                $height_normal_limit = null;
                $hc_normal_limit = null;
                $age = $child->ageMonths();
                $gen = $child->gender;

                $ws = \App\Models\WeightStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
                if ($ws && $latest['weight'] !== null) {
                    $normalWeight = ($ws->min_weight + $ws->max_weight) / 2;
                    $diff = (float)$latest['weight'] - $normalWeight;
                    $weight_diff_text = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' kg)' : '(Kurang ' . round(abs($diff), 2) . ' kg)');
                    $weight_normal_limit = 'Normal: ' . $ws->min_weight . ' - ' . $ws->max_weight . ' kg';
                }

                $hs = \App\Models\HeightStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
                if ($hs && $latest['height'] !== null) {
                    $normalHeight = ($hs->min_height + $hs->max_height) / 2;
                    $diff = (float)$latest['height'] - $normalHeight;
                    $height_diff_text = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' cm)' : '(Kurang ' . round(abs($diff), 2) . ' cm)');
                    $height_normal_limit = 'Normal: ' . $hs->min_height . ' - ' . $hs->max_height . ' cm';
                }

                $hcs = \App\Models\HeadCircumferenceStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
                if ($hcs && $latest['head_circumference'] !== null) {
                    $normalHc = ($hcs->min_head_circumference + $hcs->max_head_circumference) / 2;
                    $diff = (float)$latest['head_circumference'] - $normalHc;
                    $hc_diff_text = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' cm)' : '(Kurang ' . round(abs($diff), 2) . ' cm)');
                    $hc_normal_limit = 'Normal: ' . $hcs->min_head_circumference . ' - ' . $hcs->max_head_circumference . ' cm';
                }

                return [
                    'weight' => (float) $latest['weight'],
                    'height' => (float) $latest['height'],
                    'head_circumference' => $latest['head_circumference'] !== null ? (float) $latest['head_circumference'] : null,
                    'weight_status' => $latest['weight_status'] ?? null,
                    'height_status' => $latest['height_status'] ?? null,
                    'head_circumference_status' => $latest['head_circumference_status'] ?? null,
                    'weight_diff_text' => $weight_diff_text,
                    'height_diff_text' => $height_diff_text,
                    'hc_diff_text' => $hc_diff_text,
                    'weight_normal_limit' => $weight_normal_limit,
                    'height_normal_limit' => $height_normal_limit,
                    'hc_normal_limit' => $hc_normal_limit,
                    'overall_status' => $latest['overall_status'] ?? null,
                    'recommendation' => $latest['recommendation'] ?? null,
                    'additional_recommendation' => $latest['additional_recommendation'] ?? null,
                    'measurement_date' => \Illuminate\Support\Carbon::parse($latest['measurement_date'])->format('d/m/Y'),
                    'measurement_time' => substr((string) $latest['measurement_time'], 0, 5),
                    'is_live' => $latest['is_live'],
                ];
            }),
        ]);
    }

    public function saveLive(Request $request, MeasurementRepository $repository)
    {
        $child = $request->user()->child()->firstOrFail();
        $liveData = Cache::get("live_measurement_child_{$child->id}");
        
        if (!$liveData) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data live terbaru dari alat untuk disimpan.'], 400);
        }

        $repository->createForChild($child, $liveData);
        Cache::forget("live_measurement_child_{$child->id}");

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan secara permanen ke riwayat.']);
    }
}
