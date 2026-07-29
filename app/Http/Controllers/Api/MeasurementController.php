<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiMeasurementRequest;
use App\Http\Resources\MeasurementResource;
use App\Models\Child;
use App\Services\NutritionStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class MeasurementController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($this->validToken($request), 401);

        return MeasurementResource::collection(
            \App\Models\Measurement::with('child')->latest('measurement_date')->latest('measurement_time')->paginate(20)
        );
    }

    public function store(ApiMeasurementRequest $request, NutritionStatusService $nutritionStatusService)
    {
        abort_unless($this->validToken($request), 401);

        \Illuminate\Support\Facades\Log::info('Arduino Request Data: ', $request->all());

        // Prioritaskan ID anak yang sedang dibuka di dashboard (active_iot_child_id) 
        // daripada ID hardcode yang mungkin dikirim oleh Arduino.
        $childId = Cache::get('active_iot_child_id') ?? $request->child_id;
        
        if ($childId) {
            $child = Child::find($childId) ?? Child::query()->oldest()->firstOrFail();
        } else {
            $child = Child::query()->oldest()->firstOrFail();
        }

        $data = $request->validated();
        
        $ageMonths = (int) ($data['age_months'] ?? $child->ageMonths());
        $status = $nutritionStatusService->evaluate((float) $data['weight'], (float) $data['height'], $data['head_circumference'] ?? null, $ageMonths, $child->gender ?? 'male');

        $existingLiveData = Cache::get("live_measurement_child_{$child->id}");
        $additionalRec = $existingLiveData['additional_recommendation'] ?? null;
        if (!$additionalRec) {
            $latestModel = $child->measurement()->latest('measurement_date')->latest('measurement_time')->first();
            $additionalRec = $latestModel?->additional_recommendation;
        }

        $liveData = [
            'child_id' => $child->id,
            'weight' => $data['weight'],
            'height' => $data['height'],
            'head_circumference' => $data['head_circumference'] ?? null,
            'measurement_date' => Carbon::parse($data['measurement_date'] ?? now())->toDateString(),
            'measurement_time' => $data['measurement_time'] ?? now()->format('H:i:s'),
            'age_months' => $ageMonths,
            'additional_recommendation' => $additionalRec,
        ] + $status;

        Cache::put("live_measurement_child_{$child->id}", $liveData, now()->addMinutes(1));

        return response()->json(['message' => 'Data sensor berhasil diterima.', 'data' => $liveData]);
    }

    private function validToken(Request $request): bool
    {
        $token = config('services.iot.token', env('IOT_API_TOKEN', 'posyandu-iot-token'));

        return hash_equals($token, (string) $request->bearerToken());
    }
}
