<?php

namespace App\Modules\Compliance\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendifyComplianceBridge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // In a real Attendify environment, we would inspect $request->user()
        // or a JWT token to determine the user's role and details.
        
        // For the purpose of the test harness, we can look for a simulated
        // role passed via session or header.
        $simulatedRole = $request->session()->get('simulated_role', 'staff');
        
        $complianceContext = [
            'id' => 999, // Mock ID
            'name' => 'Mock User',
            'email' => 'mock@attendify.local',
            'department' => 'Operations',
            'role' => $simulatedRole,
        ];
        
        // Map Attendify roles to Compliance capabilities
        $complianceContext['capabilities'] = $this->mapRoleToCapabilities($simulatedRole);

        // Inject the context into the request so the controller can use it
        $request->merge(['compliance_context' => $complianceContext]);

        return $next($request);
    }
    
    private function mapRoleToCapabilities(string $role): array
    {
        $capabilities = [
            'staff' => ['complaints.create', 'pdp.manage', 'training.view', 'travel.request', 'policies.view'],
            'hr' => ['pdp.grade', 'training.manage', 'policies.manage'],
            'compliance' => ['complaints.triage', 'cap.manage', 'risk.manage', 'investigations.manage'],
            'doc' => ['dashboard.view', 'all.read_only'],
        ];
        
        // Super Admin (doc) gets everything implicitly or explicitly
        if ($role === 'doc') {
            return array_merge(...array_values($capabilities));
        }
        
        // Staff capabilities are baseline for everyone
        if (isset($capabilities[$role])) {
           return array_merge($capabilities['staff'], $capabilities[$role]);
        }
        
        return $capabilities['staff'];
    }
}
