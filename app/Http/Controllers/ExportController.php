<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function csv(Request $request): StreamedResponse
    {
        return $this->download($request, 'riwayat-measurement.csv', 'text/csv');
    }

    public function excel(Request $request): StreamedResponse
    {
        return $this->download($request, 'riwayat-measurement.xls', 'application/vnd.ms-excel');
    }

    private function download(Request $request, string $filename, string $contentType): StreamedResponse
    {
        $measurement = $this->query($request)->get();

        return response()->streamDownload(function () use ($measurement) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Jam', 'Nama Anak', 'Berat', 'Tinggi', 'Lingkar Kepala', 'Status Berat', 'Status Tinggi', 'Status LK', 'Status Gizi', 'Saran']);
            foreach ($measurement as $measurement) {
                fputcsv($handle, [
                    $measurement->measurement_date?->format('Y-m-d'),
                    substr((string) $measurement->measurement_time, 0, 5),
                    $measurement->child?->name,
                    $measurement->weight,
                    $measurement->height,
                    $measurement->head_circumference,
                    $measurement->weight_status,
                    $measurement->height_status,
                    $measurement->head_circumference_status,
                    $measurement->overall_status,
                    preg_replace('/\s+/', ' ', (string) $measurement->recommendation),
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => $contentType]);
    }

    public function pdf(Request $request)
    {
        return view('exports.measurement', ['measurement' => $this->query($request)->get()]);
    }

    private function query(Request $request)
    {
        $query = Measurement::with('child.user')->latest('measurement_date')->latest('measurement_time');

        if (! $request->user()->isAdmin()) {
            $query->whereHas('child', fn ($child) => $child->where('user_id', $request->user()->id));
        }

        return $query;
    }
}
