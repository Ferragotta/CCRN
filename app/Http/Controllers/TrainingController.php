<?php

namespace App\Http\Controllers;

use App\Models\TrainingModule;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $modules = TrainingModule::with('attendances')->get();

        if ($request->wantsJson()) {
            return response()->json($modules);
        }

        return view('modules.training', [
            'headerTitle' => 'Compliance Academy & Certification',
            'trainingModules' => $modules,
        ]);
    }
}
