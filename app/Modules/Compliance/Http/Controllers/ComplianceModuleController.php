<?php

namespace App\Modules\Compliance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ComplianceModuleController extends Controller
{
    public function index(Request $request)
    {
        $context = $request->input('compliance_context');
        
        return view('compliance::embed', [
            'context' => $context,
            'activeSubmodule' => 'dashboard' // Default view
        ]);
    }
    
    public function show(Request $request, $submodule)
    {
        $context = $request->input('compliance_context');
        
        // Basic authorization check could go here based on $context['capabilities']
        
        return view('compliance::embed', [
            'context' => $context,
            'activeSubmodule' => $submodule
        ]);
    }
}
