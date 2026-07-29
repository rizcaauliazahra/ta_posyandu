<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StandardRequest;
use App\Models\HeightStandard;
use App\Models\WeightStandard;
use App\Models\HeadCircumferenceStandard;

class StandardController extends Controller
{
    public function index(string $type)
    {
        $this->guardType($type);
        $standards = $this->model($type)::orderBy('age_months')->orderBy('gender')->paginate(20);

        return view('admin.standards.index', compact('standards', 'type'));
    }

    public function create(string $type)
    {
        $this->guardType($type);
        return view('admin.standards.form', ['standard' => null, 'type' => $type]);
    }

    public function store(StandardRequest $request, string $type)
    {
        $this->guardType($type);
        $this->model($type)::create($this->payload($request->validated(), $type));

        return redirect()->route('admin.standards.index', $type)->with('success', 'Standar berhasil ditambahkan.');
    }

    public function edit(string $type, int $id)
    {
        $this->guardType($type);
        $standard = $this->model($type)::findOrFail($id);

        return view('admin.standards.form', compact('standard', 'type'));
    }

    public function update(StandardRequest $request, string $type, int $id)
    {
        $this->guardType($type);
        $standard = $this->model($type)::findOrFail($id);
        $standard->update($this->payload($request->validated(), $type));

        return redirect()->route('admin.standards.index', $type)->with('success', 'Standar berhasil diperbarui.');
    }

    public function destroy(string $type, int $id)
    {
        $this->guardType($type);
        $this->model($type)::findOrFail($id)->delete();

        return back()->with('success', 'Standar berhasil dihapus.');
    }

    private function guardType(string $type): void
    {
        abort_unless(in_array($type, ['weight', 'height', 'head_circumference'], true), 404);
    }

    private function model(string $type): string
    {
        return match ($type) {
            'weight' => WeightStandard::class,
            'height' => HeightStandard::class,
            'head_circumference' => HeadCircumferenceStandard::class,
        };
    }

    private function payload(array $data, string $type): array
    {
        return match ($type) {
            'weight' => ['age_months' => $data['age_months'], 'gender' => $data['gender'], 'age_label' => $data['age_label'], 'min_weight' => $data['min_value'], 'max_weight' => $data['max_value']],
            'height' => ['age_months' => $data['age_months'], 'gender' => $data['gender'], 'age_label' => $data['age_label'], 'min_height' => $data['min_value'], 'max_height' => $data['max_value']],
            'head_circumference' => ['age_months' => $data['age_months'], 'gender' => $data['gender'], 'age_label' => $data['age_label'], 'min_head_circumference' => $data['min_value'], 'max_head_circumference' => $data['max_value']],
        };
    }
}
