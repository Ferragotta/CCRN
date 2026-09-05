<?php

namespace App\Http\Controllers;

use App\Models\StateProfile;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $states = StateProfile::with('updates')->get();

        if ($request->wantsJson()) {
            return response()->json($states);
        }

        return view('modules.states', [
            'headerTitle' => 'State Regional Offices & Field Monitoring',
            'states' => $states,
        ]);
    }
}
