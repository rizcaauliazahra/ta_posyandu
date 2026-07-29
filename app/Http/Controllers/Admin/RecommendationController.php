<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecommendationRequest;
use App\Models\Recommendation;

class RecommendationController extends Controller
{
    public function index()
    {
        return view('admin.recommendations.index', [
            'recommendations' => Recommendation::orderBy('status')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.recommendations.form', ['recommendation' => new Recommendation()]);
    }

    public function store(RecommendationRequest $request)
    {
        Recommendation::create($request->validated());

        return redirect()->route('admin.recommendations.index')->with('success', 'Saran berhasil ditambahkan.');
    }

    public function edit(Recommendation $recommendation)
    {
        return view('admin.recommendations.form', compact('recommendation'));
    }

    public function update(RecommendationRequest $request, Recommendation $recommendation)
    {
        $recommendation->update($request->validated());

        return redirect()->route('admin.recommendations.index')->with('success', 'Saran berhasil diperbarui.');
    }

    public function destroy(Recommendation $recommendation)
    {
        $recommendation->delete();

        return back()->with('success', 'Saran berhasil dihapus.');
    }
}
