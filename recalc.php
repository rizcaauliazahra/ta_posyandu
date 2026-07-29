<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Measurement;
use App\Models\Child;
use App\Services\NutritionStatusService;
use Illuminate\Support\Facades\Cache;

$nutritionService = app(NutritionStatusService::class);
$measurements = Measurement::all();

foreach ($measurements as $m) {
    $child = Child::find($m->child_id);
    if ($child) {
        $status = $nutritionService->evaluate(
            (float) $m->weight,
            (float) $m->height,
            $m->head_circumference !== null ? (float) $m->head_circumference : null,
            $m->age_months,
            $child->gender
        );
        
        $m->weight_status = $status['weight_status'];
        $m->height_status = $status['height_status'];
        $m->head_circumference_status = $status['head_circumference_status'];

        $m->recommendation = $status['recommendation'];
        $m->save();
    }
}

// Clear the cache for all children so live dashboard resets
$children = Child::all();
foreach ($children as $c) {
    Cache::forget("live_measurement_child_{$c->id}");
}

echo "All historical measurements recalculated and cache cleared!\n";
