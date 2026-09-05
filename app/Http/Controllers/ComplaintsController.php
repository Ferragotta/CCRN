<?php

namespace App\Http\Controllers;

use App\Models\Cap;
use App\Models\Complaint;
use App\Models\InvestigationCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with('assignedTo');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        $complaints = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($complaints);
        }

        return view('modules.complaints', [
            'headerTitle' => 'Ethics & Whistleblower Grievance Management',
            'complaints' => $complaints,
        ]);
    }

    public function store(Request $request)
    {
        $role = Auth::user() ? Auth::user()->role : (request()->cookie('auth_role') ?? 'staff');
        if ($role === 'hr') {
            abort(403, 'Access Denied: HR role has view-only access to complaints. Logging new grievances is restricted.');
        }

        $validated = $request->validate([
            'category' => 'required|string',
            'severity' => 'required|string',
            'state' => 'required|string',
            'summary' => 'required|string',
            'details' => 'nullable|string',
            'alleged_party' => 'nullable|string',
            'incident_date' => 'nullable|date',
        ]);

        $count = Complaint::count() + 1;
        $ref = 'CMP-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $complaint = Complaint::create([
            'complaint_ref' => $ref,
            'category' => $validated['category'],
            'severity' => $validated['severity'],
            'state' => $validated['state'],
            'summary' => $validated['summary'],
            'details' => $validated['details'] ?? null,
            'alleged_party' => $validated['alleged_party'] ?? null,
            'incident_date' => $validated['incident_date'] ?? now(),
            'submitted_by' => Auth::user() ? Auth::user()->name : 'Anonymous Whistleblower',
            'status' => 'Open',
        ]);

        if ($request->wantsJson()) {
            return response()->json($complaint, 201);
        }

        return redirect()->route('complaints')->with('success', 'Complaint ' . $ref . ' logged successfully.');
    }

    public function convertToCap(Request $request, $id)
    {
        $role = Auth::user() ? Auth::user()->role : (request()->cookie('auth_role') ?? 'staff');
        if ($role === 'hr') {
            abort(403, 'Access Denied: HR role has view-only access. Converting complaints to Corrective Action Plans is restricted to Compliance Specialists and the Director of Compliance.');
        }

        $complaint = Complaint::findOrFail($id);

        $capCount = Cap::count() + 1;
        $capRef = 'CAP-' . str_pad($capCount, 3, '0', STR_PAD_LEFT);

        $cap = Cap::create([
            'cap_ref' => $capRef,
            'finding' => 'Derived from ' . $complaint->complaint_ref . ': ' . $complaint->summary,
            'action_plan' => 'Implement internal control monitoring and supervisory review.',
            'state' => $complaint->state,
            'priority' => $complaint->severity,
            'status' => 'Open',
            'progress_pct' => 10,
            'due_date' => now()->addDays(30),
            'lead_id' => Auth::id(),
            'complaint_id' => $complaint->id,
        ]);

        $complaint->update(['status' => 'In Progress']);

        return redirect()->route('cap')->with('success', 'Complaint ' . $complaint->complaint_ref . ' converted to CAP ' . $capRef . '.');
    }

    public function convertToInvestigation(Request $request, $id)
    {
        $role = Auth::user() ? Auth::user()->role : (request()->cookie('auth_role') ?? 'staff');
        if ($role === 'hr') {
            abort(403, 'Access Denied: HR role has view-only access. Escalation to forensic investigation is restricted to the Director of Compliance.');
        }

        $complaint = Complaint::findOrFail($id);

        $invCount = InvestigationCase::count() + 1;
        $invRef = 'INV-' . str_pad($invCount, 3, '0', STR_PAD_LEFT);

        $investigation = InvestigationCase::create([
            'case_ref' => $invRef,
            'complaint_id' => $complaint->id,
            'title' => 'Formal Investigation: ' . $complaint->category . ' (' . $complaint->complaint_ref . ')',
            'lead_investigator' => Auth::user() ? Auth::user()->name : 'Director of Compliance',
            'status' => 'Active Investigation',
            'severity' => $complaint->severity,
            'findings_summary' => $complaint->summary,
        ]);

        $complaint->update(['status' => 'In Progress']);

        return redirect()->route('investigations')->with('success', 'Complaint ' . $complaint->complaint_ref . ' escalated to Investigation Case ' . $invRef . '.');
    }
}
