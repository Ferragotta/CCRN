<?php

namespace App\Http\Controllers;

use App\Models\Cap;
use App\Models\CapEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CapController extends Controller
{
    public function index(Request $request)
    {
        $caps = Cap::with(['lead', 'complaint', 'evidences'])->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($caps);
        }

        return view('modules.cap', [
            'headerTitle' => 'Corrective Action Plan (CAP) Tracking',
            'caps' => $caps,
        ]);
    }

    public function uploadEvidence(Request $request, $id)
    {
        $role = Auth::user() ? Auth::user()->role : (request()->cookie('auth_role') ?? 'staff');
        if ($role === 'hr') {
            abort(403, 'Access Denied: HR role has view-only access to Corrective Action Plans.');
        }

        $cap = Cap::findOrFail($id);

        $validated = $request->validate([
            'file_name' => 'required|string',
            'file_url' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $evidence = CapEvidence::create([
            'cap_id' => $cap->id,
            'file_name' => $validated['file_name'],
            'file_url' => $validated['file_url'],
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => Auth::user() ? Auth::user()->name : 'Compliance Specialist',
        ]);

        if ($cap->progress_pct < 100) {
            $cap->update(['progress_pct' => min(100, $cap->progress_pct + 25)]);
        }

        return response()->json($evidence, 201);
    }
}
