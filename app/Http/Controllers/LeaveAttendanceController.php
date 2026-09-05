<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $role = session('user_role') ?? $request->cookie('auth_role') ?? (auth()->check() ? auth()->user()->role : 'hr');

        $data = [
            'title' => 'Leave & Attendance Management — CCCRN ComplianceIQ',
            'userRole' => $role,
            'stats' => [
                'totalApplications' => 24,
                'pendingHr' => 4,
                'approved' => 18,
                'attendanceRate' => '94.2%',
                'clockedIn' => 462,
                'totalStaff' => 490,
            ],
            'applications' => [
                [
                    'id' => 'LVE-2026-042',
                    'staffName' => 'Amina Kyari',
                    'dept' => 'Clinical Services',
                    'state' => 'Borno',
                    'leaveType' => 'Annual Leave',
                    'dates' => '10 Mar 2026 — 24 Mar 2026',
                    'workingDays' => 10,
                    'reliever' => 'Dr. Usman Bello',
                    'status' => 'Pending HR',
                ],
                [
                    'id' => 'LVE-2026-041',
                    'staffName' => 'Emeka Okafor',
                    'dept' => 'Procurement & Logistics',
                    'state' => 'Abuja FCT',
                    'leaveType' => 'Casual Leave',
                    'dates' => '06 Mar 2026 — 09 Mar 2026',
                    'workingDays' => 2,
                    'reliever' => 'Fatima Sanusi',
                    'status' => 'Pending HR',
                ],
                [
                    'id' => 'LVE-2026-040',
                    'staffName' => 'Biodun Alade',
                    'dept' => 'Human Resources',
                    'state' => 'Lagos',
                    'leaveType' => 'Study / Exam',
                    'dates' => '15 Mar 2026 — 20 Mar 2026',
                    'workingDays' => 5,
                    'reliever' => 'Ngozi Adeyemi',
                    'status' => 'Approved',
                ],
                [
                    'id' => 'LVE-2026-039',
                    'staffName' => 'Aliyu Usman',
                    'dept' => 'Security & Operations',
                    'state' => 'Kano',
                    'leaveType' => 'Sick Leave',
                    'dates' => '27 Feb 2026 — 03 Mar 2026',
                    'workingDays' => 4,
                    'reliever' => 'Musa Ibrahim',
                    'status' => 'Approved',
                ],
            ],
            'attendanceLogs' => [
                ['id' => 'LOG-881', 'staffName' => 'Ngozi Adeyemi', 'state' => 'Lagos', 'dept' => 'Executive', 'clockIn' => '07:46 AM', 'clockOut' => '--', 'terminal' => 'Attendify Biometrics', 'status' => 'On-Time'],
                ['id' => 'LOG-882', 'staffName' => 'Amaka Okonkwo', 'state' => 'Abuja FCT', 'dept' => 'Finance', 'clockIn' => '07:51 AM', 'clockOut' => '--', 'terminal' => 'Attendify Biometrics', 'status' => 'On-Time'],
                ['id' => 'LOG-883', 'staffName' => 'Chidi Okafor', 'state' => 'Rivers', 'dept' => 'Clinical Support', 'clockIn' => '07:58 AM', 'clockOut' => '--', 'terminal' => 'Attendify Mobile', 'status' => 'On-Time'],
                ['id' => 'LOG-884', 'staffName' => 'Musa Ibrahim', 'state' => 'Kano', 'dept' => 'Cluster Head', 'clockIn' => '08:16 AM', 'clockOut' => '--', 'terminal' => 'Attendify Biometrics', 'status' => 'Late'],
                ['id' => 'LOG-885', 'staffName' => 'Fatima Bakura', 'state' => 'Borno', 'dept' => 'Field Operations', 'clockIn' => '08:04 AM', 'clockOut' => '--', 'terminal' => 'Attendify Geo-Fence', 'status' => 'Field Duty'],
            ],
            'balances' => [
                ['staffName' => 'Ngozi Adeyemi', 'state' => 'Lagos', 'annualTotal' => 21, 'annualUsed' => 5, 'annualRemaining' => 16],
                ['staffName' => 'Amaka Okonkwo', 'state' => 'Abuja FCT', 'annualTotal' => 21, 'annualUsed' => 7, 'annualRemaining' => 14],
                ['staffName' => 'Musa Ibrahim', 'state' => 'Kano', 'annualTotal' => 21, 'annualUsed' => 4, 'annualRemaining' => 17],
                ['staffName' => 'Chidi Okafor', 'state' => 'Rivers', 'annualTotal' => 21, 'annualUsed' => 6, 'annualRemaining' => 15],
            ]
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('modules.leave_attendance', $data);
    }
}
