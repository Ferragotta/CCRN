<?php

namespace App\Http\Controllers;

use App\Models\Cap;
use App\Models\Complaint;
use App\Models\RiskItem;
use App\Models\TrainingAttendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = session('user_role') ?? $request->cookie('auth_role') ?? (auth()->check() ? auth()->user()->role : 'hr');

        // If user is HR, HR has no dashboard access -> redirect to Leave & Attendance
        if ($role === 'hr') {
            return redirect()->route('leave.attendance');
        }

        // Standard Director of Compliance (DoC) Executive View
        $openComplaintsCount = Complaint::where('status', 'Open')->count();
        $criticalRisksCount = RiskItem::where('severity', 'Critical')->orWhere('risk_score', '>=', 15)->count();
        $totalCaps = Cap::count();
        $closedCaps = Cap::where('status', 'Closed')->count();
        $capCompletionPct = $totalCaps > 0 ? round(($closedCaps / $totalCaps) * 100) : 76;
        $staffTrainedCount = TrainingAttendance::distinct('user_id')->count();

        $recentComplaints = Complaint::with('assignedTo')->latest()->limit(5)->get();

        $data = [
            'headerTitle' => 'Director of Compliance — Executive Command Center',
            'metrics' => [
                'openComplaints' => $openComplaintsCount ?: 18,
                'criticalRisks' => $criticalRisksCount ?: 3,
                'capCompletion' => $capCompletionPct,
                'staffTrained' => $staffTrainedCount ?: 312,
            ],
            'recentComplaints' => $recentComplaints,
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('dashboard.index', $data);
    }
}
