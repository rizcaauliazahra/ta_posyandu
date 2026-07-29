<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Measurement;
use Illuminate\Support\Carbon;

$measurements = Measurement::where('measurement_date', '2026-07-20')->get();

foreach ($measurements as $m) {
    // If the time is before 16:00 (which means it was saved in UTC during the afternoon here)
    $time = Carbon::parse($m->measurement_time);
    if ($time->hour < 16) {
        $m->measurement_time = $time->addHours(7)->toTimeString();
        $m->save();
    }
}

echo "Times adjusted!\n";
