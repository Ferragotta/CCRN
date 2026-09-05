<?php

namespace Database\Seeders;

use App\Models\Cap;
use App\Models\Complaint;
use App\Models\Pdp;
use App\Models\Policy;
use App\Models\RiskItem;
use App\Models\StateProfile;
use App\Models\TicketPurchase;
use App\Models\TrainingModule;
use App\Models\TravelRequest;
use App\Models\User;
use App\Models\VendorPayment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        $doc = User::firstOrCreate(['email' => 'director@cccrn.org'], [
            'name' => 'Director of Compliance (DoC)',
            'password' => Hash::make('Director@CCCRN2026'),
            'role' => 'doc',
            'role_name' => 'ADMIN (DoC)',
            'department' => 'Compliance Directorate',
            'state' => 'Abuja FCT',
            'avatar' => 'DC',
        ]);

        $co = User::firstOrCreate(['email' => 'compliance@cccrn.org'], [
            'name' => 'Compliance Officer or Specialist',
            'password' => Hash::make('Compliance@CCCRN2026'),
            'role' => 'compliance_officer',
            'role_name' => 'COMPLIANCE SPECIALIST',
            'department' => 'Compliance Unit',
            'state' => 'Lagos',
            'avatar' => 'CO',
        ]);

        $hr = User::firstOrCreate(['email' => 'hr@cccrn.org'], [
            'name' => 'HR (Human Resources)',
            'password' => Hash::make('HR@CCCRN2026'),
            'role' => 'hr',
            'role_name' => 'HR ACCESS',
            'department' => 'Human Resources',
            'state' => 'Abuja FCT',
            'avatar' => 'HR',
        ]);

        $staff = User::firstOrCreate(['email' => 'staff@cccrn.org'], [
            'name' => 'Staff Member',
            'password' => Hash::make('Staff@CCCRN2026'),
            'role' => 'staff',
            'role_name' => 'STAFF ACCESS',
            'department' => 'Clinical Services',
            'state' => 'Kano',
            'avatar' => 'ST',
        ]);

        // 2. Seed State Profiles
        $states = [
            ['name' => 'Lagos', 'code' => 'LAG', 'cluster' => 'Cluster A', 'lead_name' => 'Dr. Babatunde Alabi', 'staff_count' => 54, 'compliance_score' => 92],
            ['name' => 'Kano', 'code' => 'KAN', 'cluster' => 'Cluster B', 'lead_name' => 'Hajiya Fatima Garba', 'staff_count' => 48, 'compliance_score' => 84],
            ['name' => 'Rivers', 'code' => 'RIV', 'cluster' => 'Cluster C', 'lead_name' => 'Engr. Tonye Briggs', 'staff_count' => 42, 'compliance_score' => 78],
            ['name' => 'Abuja FCT', 'code' => 'FCT', 'cluster' => 'HQ Cluster', 'lead_name' => 'Dr. Michael Okafor', 'staff_count' => 65, 'compliance_score' => 96],
            ['name' => 'Kaduna', 'code' => 'KAD', 'cluster' => 'Cluster A', 'lead_name' => 'Dr. Usman Danjuma', 'staff_count' => 38, 'compliance_score' => 88],
            ['name' => 'Borno', 'code' => 'BOR', 'cluster' => 'Cluster B', 'lead_name' => 'Dr. Maryam Kyari', 'staff_count' => 35, 'compliance_score' => 81],
        ];
        foreach ($states as $s) {
            StateProfile::firstOrCreate(['code' => $s['code']], $s);
        }

        // 3. Seed Complaints
        $complaints = [
            ['complaint_ref' => 'CMP-048', 'category' => 'Fraud / Advance', 'severity' => 'Critical', 'state' => 'Lagos', 'status' => 'Open', 'summary' => 'Discrepancy observed in local facility logistics fuel disbursements.'],
            ['complaint_ref' => 'CMP-047', 'category' => 'Conduct / Conflict', 'severity' => 'High', 'state' => 'Kano', 'status' => 'In Progress', 'summary' => 'Failure to disclose vendor relationship during clinic consumable sourcing.'],
            ['complaint_ref' => 'CMP-046', 'category' => 'Policy / Procurement', 'severity' => 'Medium', 'state' => 'Abuja FCT', 'status' => 'Closed', 'summary' => 'Single source justification missing for laboratory diagnostic kit order.'],
            ['complaint_ref' => 'CMP-045', 'category' => 'Safety / Field', 'severity' => 'Low', 'state' => 'Rivers', 'status' => 'Open', 'summary' => 'PPE supplies delayed during mobile clinical outreach session.'],
        ];
        foreach ($complaints as $c) {
            Complaint::firstOrCreate(['complaint_ref' => $c['complaint_ref']], array_merge($c, ['assigned_to_id' => $co->id]));
        }

        // 4. Seed CAPs
        $caps = [
            ['cap_ref' => 'CAP-101', 'finding' => 'Fuel reconciliation records in Lagos cluster had 14-day submission lag.', 'action_plan' => 'Implement automated digital fuel voucher reconciliation and fleet log sign-off.', 'state' => 'Lagos', 'priority' => 'High', 'status' => 'In Progress', 'progress_pct' => 65, 'lead_id' => $co->id],
            ['cap_102', 'cap_ref' => 'CAP-102', 'finding' => 'Procurement bid evaluation committees lacked rotated external compliance observer.', 'action_plan' => 'Mandate Compliance Specialist attendance for all tender openings above threshold.', 'state' => 'Abuja FCT', 'priority' => 'Critical', 'status' => 'Closed', 'progress_pct' => 100, 'lead_id' => $doc->id],
        ];
        foreach ($caps as $cp) {
            Cap::firstOrCreate(['cap_ref' => $cp['cap_ref']], $cp);
        }

        // 5. Seed Travel & Boarding Pass Gate
        $travel = TravelRequest::firstOrCreate(['travel_ref' => 'TR-107'], [
            'traveler_name' => 'Dr. Amina Bello',
            'destination' => 'Abuja (HQ) to Kano State Clinic',
            'purpose' => 'Quarterly Clinical Site Compliance Audit',
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(1),
            'airline' => 'Air Peace',
            'flight_number' => 'P4-7124',
            'ticket_cost' => 185000.00,
            'status' => 'Approved',
            'user_id' => $staff->id,
        ]);

        TicketPurchase::firstOrCreate(['travel_request_id' => $travel->id], [
            'ticket_number' => 'TKT-904821',
            'vendor_name' => 'Touchdown Travels Ltd',
            'pnr_code' => 'APK942',
            'amount' => 185000.00,
            'status' => 'Pending Boarding Pass',
        ]);

        VendorPayment::firstOrCreate(['travel_request_id' => $travel->id], [
            'vendor_name' => 'Touchdown Travels Ltd',
            'amount' => 185000.00,
            'payment_status' => 'Locked (Awaiting Boarding Pass)',
        ]);

        // 6. Seed Risk Items
        $risks = [
            ['risk_ref' => 'RSK-01', 'category' => 'Financial', 'title' => 'Vendor Advance Retirement Delays', 'description' => 'Unretired operational advances crossing fiscal cutoffs.', 'likelihood' => 4, 'impact' => 4, 'risk_score' => 16, 'status' => 'Active', 'mitigation_strategy' => 'Strict 7-day automatic salary deduction notice.'],
            ['risk_ref' => 'RSK-02', 'category' => 'Regulatory', 'title' => 'Clinical Data Privacy Breach', 'description' => 'Unencrypted patient intake records on portable field devices.', 'likelihood' => 2, 'impact' => 5, 'risk_score' => 10, 'status' => 'Active', 'mitigation_strategy' => 'Enforce BitLocker AES-256 full disk encryption on all field tablets.'],
        ];
        foreach ($risks as $r) {
            RiskItem::firstOrCreate(['risk_ref' => $r['risk_ref']], $r);
        }

        // 7. Seed Policies
        $policies = [
            ['policy_code' => 'POL-ETH-01', 'title' => 'Code of Conduct & Professional Ethics Policy', 'category' => 'Governance', 'version' => '2.4', 'effective_date' => '2026-01-01', 'status' => 'Active', 'summary' => 'Establishes zero-tolerance standards for fraud, conflicts of interest, and mandatory whistleblower protections.'],
            ['policy_code' => 'POL-TRV-03', 'title' => 'Official Travel & Boarding Pass Verification Standard', 'category' => 'Operations', 'version' => '3.1', 'effective_date' => '2026-01-15', 'status' => 'Active', 'summary' => 'Enforces mandatory electronic submission of physical/digital boarding passes within 72 hours of trip completion prior to vendor payment release.'],
        ];
        foreach ($policies as $p) {
            Policy::firstOrCreate(['policy_code' => $p['policy_code']], $p);
        }

        // 8. Seed Training
        $trainings = [
            ['module_code' => 'TRN-PSEA-2026', 'title' => 'Prevention of Sexual Exploitation and Abuse (PSEA)', 'category' => 'Ethics & Safeguarding', 'duration_hours' => 3, 'target_audience' => 'All Staff', 'mandatory' => true, 'status' => 'Active'],
            ['module_code' => 'TRN-FIN-2026', 'title' => 'Advance Retirals & Asset Custody Compliance', 'category' => 'Finance & Operations', 'duration_hours' => 2, 'target_audience' => 'Finance & Program Leads', 'mandatory' => true, 'status' => 'Active'],
        ];
        foreach ($trainings as $t) {
            TrainingModule::firstOrCreate(['module_code' => $t['module_code']], $t);
        }

        // 9. Seed PDP
        $pdps = [
            ['staff_name' => 'Dr. Amina Bello', 'department' => 'Clinical Services', 'state' => 'Lagos', 'objective_score' => 55, 'behaviour_score' => 36, 'innovation_score' => 45, 'total_score' => 136, 'status' => 'Supervisor Approved'],
            ['staff_name' => 'Ibrahim Yakubu', 'department' => 'Strategic Information', 'state' => 'Kano', 'objective_score' => 51, 'behaviour_score' => 34, 'innovation_score' => 40, 'total_score' => 125, 'status' => 'Supervisor Approved'],
        ];
        foreach ($pdps as $pd) {
            Pdp::firstOrCreate(['staff_name' => $pd['staff_name']], array_merge($pd, ['staff_id' => $staff->id]));
        }
    }
}
