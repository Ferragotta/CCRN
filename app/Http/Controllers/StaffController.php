<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display the embedded Attendify Staff Portal.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? [
            'id' => 'CCCRN-STF-0142',
            'name' => 'Fatima Bello',
            'email' => 'staff@cccrn.org',
            'role' => 'staff',
            'department' => 'Clinical Services & Public Health',
            'state' => 'Lagos State Office (Cluster A)',
            'supervisor' => 'Dr. Ngozi Adeyemi'
        ];

        return view('modules.staff', compact('user'));
    }
}
