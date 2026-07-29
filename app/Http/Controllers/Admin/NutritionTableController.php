<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeightStandard;
use App\Models\HeightStandard;
use App\Models\HeadCircumferenceStandard;

class NutritionTableController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $gender = $request->query('gender', 'male');
        if (!in_array($gender, ['male', 'female'])) {
            $gender = 'male';
        }

        $weightStandards = WeightStandard::where('gender', $gender)->orderBy('age_months')->get()->keyBy('age_months');
        $heightStandards = HeightStandard::where('gender', $gender)->orderBy('age_months')->get()->keyBy('age_months');
        $headStandards = HeadCircumferenceStandard::where('gender', $gender)->orderBy('age_months')->get()->keyBy('age_months');
        
        // We want to display from 0 to 60 months
        $ages = range(0, 60);

        return view('admin.nutrition_table', compact('weightStandards', 'heightStandards', 'headStandards', 'ages', 'gender'));
    }
}
