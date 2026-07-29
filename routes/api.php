<?php

use App\Http\Controllers\Api\MeasurementController;
use Illuminate\Support\Facades\Route;

Route::get('/measurement', [MeasurementController::class, 'index']);
Route::post('/measurement', [MeasurementController::class, 'store']);
