<?php

namespace App\Http\Controllers;

use App\Models\InvestigationCase;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    public function index(Request $request)
    {
        $cases = InvestigationCase::with(['complaint', 'rcaFindings', 'controlDeviations', 'evidenceCustody'])->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($cases);
        }

        return view('modules.investigations', [
            'headerTitle' => 'Forensic Investigation Cases & Root Cause Analysis',
            'cases' => $cases,
        ]);
    }
}
