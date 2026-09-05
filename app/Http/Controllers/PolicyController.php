<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $policies = Policy::with('acknowledgements')->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($policies);
        }

        return view('modules.policies', [
            'headerTitle' => 'Institutional Policies & Standard Operating Procedures',
            'policies' => $policies,
        ]);
    }
}
