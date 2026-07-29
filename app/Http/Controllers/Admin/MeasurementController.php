<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeasurementRequest;
use App\Models\Child;
use App\Models\Measurement;
use App\Repositories\MeasurementRepository;

class MeasurementController extends Controller
{
    public function index()
    {
        $measurement = Measurement::with('child.user')
            ->when(request('q'), fn ($q, $search) => $q->whereHas('child', fn ($c) => $c->where('name', 'like', "%$search%")))
            ->when(request('date'), fn ($q, $date) => $q->whereDate('measurement_date', $date))
            ->latest('measurement_date')
            ->latest('measurement_time')
            ->paginate(10)
            ->withQueryString();

        return view('admin.measurements.index', compact('measurement'));
    }

    public function create()
    {
        return view('admin.measurements.form', ['measurement' => new Measurement(), 'children' => Child::orderBy('name')->get()]);
    }

    public function store(MeasurementRequest $request, MeasurementRepository $repository)
    {
        $child = Child::findOrFail($request->child_id);
        $repository->createForChild($child, $request->validated());

        return redirect()->route('admin.measurement.index')->with('success', 'Measurement berhasil ditambahkan.');
    }

    public function edit(Measurement $measurement)
    {
        return view('admin.measurements.form', ['measurement' => $measurement, 'children' => Child::orderBy('name')->get()]);
    }

    public function update(MeasurementRequest $request, Measurement $measurement, MeasurementRepository $repository)
    {
        $measurement->child_id = $request->child_id;
        $measurement->save();
        $repository->update($measurement->fresh('child'), $request->validated());

        return redirect()->route('admin.measurement.index')->with('success', 'Measurement berhasil diperbarui.');
    }

    public function destroy(Measurement $measurement)
    {
        $measurement->delete();

        return back()->with('success', 'Measurement berhasil dihapus.');
    }

    public function pantauIndex()
    {
        $children = Child::with('user')
            ->when(request('q'), fn ($q, $search) => $q->where('name', 'like', "%$search%"))
            ->paginate(10)
            ->withQueryString();

        return view('admin.measurements.pantau_index', compact('children'));
    }

    public function pantau(Child $child)
    {
        $latest = \Illuminate\Support\Facades\Cache::get("live_measurement_child_{$child->id}");
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
                $latest['weight_normal_limit'] = 'Normal: ' . $ws->min_weight . ' - ' . $ws->max_weight . ' kg';
            } else {
                $latest['weight_diff_text'] = '';
                $latest['weight_normal_limit'] = '';
            }

            $hs = \App\Models\HeightStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
            if ($hs && isset($latest['height']) && $latest['height'] !== null) {
                $normalHeight = ($hs->min_height + $hs->max_height) / 2;
                $diff = (float)$latest['height'] - $normalHeight;
                $latest['height_diff_text'] = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' cm)' : '(Kurang ' . round(abs($diff), 2) . ' cm)');
                $latest['height_normal_limit'] = 'Normal: ' . $hs->min_height . ' - ' . $hs->max_height . ' cm';
            } else {
                $latest['height_diff_text'] = '';
                $latest['height_normal_limit'] = '';
            }

            $hcs = \App\Models\HeadCircumferenceStandard::where('gender', $gen)->orderByRaw('ABS(CAST(age_months AS SIGNED) - ?)', [$age])->first();
            if ($hcs && isset($latest['head_circumference']) && $latest['head_circumference'] !== null) {
                $normalHc = ($hcs->min_head_circumference + $hcs->max_head_circumference) / 2;
                $diff = (float)$latest['head_circumference'] - $normalHc;
                $latest['hc_diff_text'] = abs($diff) < 0.1 ? '' : ($diff > 0 ? '(Lebih ' . round($diff, 2) . ' cm)' : '(Kurang ' . round(abs($diff), 2) . ' cm)');
                $latest['hc_normal_limit'] = 'Normal: ' . $hcs->min_head_circumference . ' - ' . $hcs->max_head_circumference . ' cm';
            } else {
                $latest['hc_diff_text'] = '';
                $latest['hc_normal_limit'] = '';
            }
        }

        $latest = $latest ? (object) $latest : null;
        return view('admin.measurements.pantau', compact('child', 'latest'));
    }

    public function grafik(Child $child)
    {
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

    public function riwayat(Child $child)
    {
        $measurement = $child->measurement()->latest('measurement_date')->latest('measurement_time')->paginate(10);
        return view('admin.measurements.riwayat', compact('measurement', 'child'));
    }

    public function latest(Child $child)
    {
        \Illuminate\Support\Facades\Cache::put('active_iot_child_id', $child->id, now()->addMinutes(5));

        $latest = \Illuminate\Support\Facades\Cache::get("live_measurement_child_{$child->id}");
        
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
                    'measurement_date' => \Illuminate\Support\Carbon::parse($latest['measurement_date'])->format('d/m/Y'),
                    'measurement_date_formatted' => \Carbon\Carbon::parse($latest['measurement_date'])->translatedFormat('d F Y'),
                    'measurement_time' => substr((string) $latest['measurement_time'], 0, 5),
                    'is_live' => $latest['is_live'],
                ];
            }),
        ]);
    }

    public function saveLive(\Illuminate\Http\Request $request, Child $child, MeasurementRepository $repository)
    {
        $liveData = \Illuminate\Support\Facades\Cache::get("live_measurement_child_{$child->id}");
        
        if (!$liveData) {
            return back()->with('error', 'Tidak ada data live terbaru dari alat untuk disimpan.');
        }

        $saran = $request->additional_recommendation ? $request->additional_recommendation : '';
        $petugas = $request->officer_name ? 'Petugas: ' . $request->officer_name . '\n' : '';
        $liveData['additional_recommendation'] = $petugas . $saran;
        
        if ($request->filled('weight_override')) {
            $liveData['weight'] = $request->weight_override;
        }
        if ($request->filled('height_override')) {
            $liveData['height'] = $request->height_override;
        }
        if ($request->filled('hc_override')) {
            $liveData['head_circumference'] = $request->hc_override;
        }

        $repository->createForChild($child, $liveData);
        \Illuminate\Support\Facades\Cache::forget("live_measurement_child_{$child->id}");

        return redirect()->route('admin.measurement.index')->with('success', 'Data pemantauan berhasil disimpan ke riwayat.');
    }

    public function sendRecommendation(\Illuminate\Http\Request $request, Child $child)
    {
        $request->validate([
            'additional_recommendation' => 'nullable|string',
            'officer_name' => 'nullable|string',
        ]);

        $latestMeasurement = $child->measurement()->latest('measurement_date')->latest('measurement_time')->first();
        
        if (!$latestMeasurement) {
            return back()->with('error', 'Belum ada data measurement untuk anak ini.');
        }

        $saran = $request->additional_recommendation ? $request->additional_recommendation : '';
        $petugas = $request->officer_name ? 'Petugas: ' . $request->officer_name . '\n' : '';
        $latestMeasurement->additional_recommendation = $petugas . $saran;
        $latestMeasurement->save();

        $liveData = \Illuminate\Support\Facades\Cache::get("live_measurement_child_{$child->id}");
        if ($liveData) {
            $saran = $request->additional_recommendation ? $request->additional_recommendation : '';
        $petugas = $request->officer_name ? 'Petugas: ' . $request->officer_name . '\n' : '';
        $liveData['additional_recommendation'] = $petugas . $saran;
            \Illuminate\Support\Facades\Cache::put("live_measurement_child_{$child->id}", $liveData, now()->addHours(2));
        }

        return back()->with('success', 'Saran tambahan berhasil dikirim ke pengguna.');
    }
    public function updateRecommendation(\Illuminate\Http\Request $request, Measurement $measurement)
    {
        $request->validate([
            'additional_recommendation' => 'nullable|string',
            'officer_name' => 'nullable|string',
        ]);

        $measurement->additional_recommendation = $request->additional_recommendation;
        $measurement->save();

        return back()->with('success', 'Saran tambahan berhasil diperbarui.');
    }
}
