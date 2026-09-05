<?php

namespace App\Http\Controllers;

use App\Models\Pdp;
use Illuminate\Http\Request;

class PdpController extends Controller
{
    public function index(Request $request)
    {
        $pdps = Pdp::latest()->get();

        if ($request->wantsJson()) {
            return response()->json($pdps);
        }

        return view('modules.pdp', [
            'headerTitle' => 'Staff Performance Development Plans (PDP 150)',
            'pdps' => $pdps,
        ]);
    }
}
