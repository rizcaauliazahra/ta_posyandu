<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'child' => $this->child?->name,
            'weight' => (float) $this->weight,
            'height' => (float) $this->height,
            'measurement_date' => $this->measurement_date?->toDateString(),
            'measurement_time' => substr((string) $this->measurement_time, 0, 5),
            'weight_status' => $this->weight_status,
            'height_status' => $this->height_status,
            'recommendation' => $this->recommendation,
        ];
    }
}
