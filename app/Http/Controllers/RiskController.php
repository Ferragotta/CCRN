<?php

namespace App\Http\Controllers;

use App\Models\RiskItem;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->check() ? auth()->user()->role : (request()->cookie('auth_role') ?? 'staff');
        if ($role === 'hr') {
            abort(403, 'Access Denied: The ISO 31000 Risk Register is restricted to Executive Leadership and Compliance Directorate. HR personnel do not have access.');
        }

        $risks = RiskItem::latest()->get();

        if ($request->wantsJson()) {
            return response()->json($risks);
        }

        return view('modules.risk', [
            'headerTitle' => 'ISO 31000 Risk Register & Controls',
            'risks' => $risks,
        ]);
    }
}
