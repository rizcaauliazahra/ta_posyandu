<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Child::all() as $child) {
    $m = $child->measurements()->whereNotNull('additional_recommendation')->latest('measurement_date')->latest('measurement_time')->first();
    if ($m && !empty($m->additional_recommendation)) {
        $child->additional_recommendation = $m->additional_recommendation;
        $child->save();
        echo "Updated child {$child->id}\n";
    }
}
echo "Done.\n";
