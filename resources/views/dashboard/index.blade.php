@extends('layouts.app')

@section('content')
<div id="unifiedDashboardContainer" style="width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;">

    <!-- ==================== 1. EXECUTIVE DASHBOARD PANEL (DoC Only) ==================== -->
    <div class="panel" id="panel-dashboard" style="display: none;">
        <div id="hrDashboardView" style="display: none;">
            @include('modules.hr_dashboard')
        </div>
        <div id="docDashboardView" style="display: none;">
            @include('modules.doc_dashboard')
        </div>
        <div id="superadminDashboardView" style="display: none;">
            @include('modules.superadmin_dashboard')
        </div>
    </div>

    <!-- ==================== LEAVE & ATTENDANCE PANEL ==================== -->
    <div class="panel" id="panel-leave-attendance" style="display: none;">
        @include('modules.leave_attendance')
    </div>

    <!-- ==================== 2. COMPLAINTS PANEL ==================== -->
    <div class="panel" id="panel-complaints" style="display: none;">
        @include('modules.complaints')
    </div>

    <!-- ==================== 3. CAP PANEL ==================== -->
    <div class="panel" id="panel-cap" style="display: none;">
        @include('modules.cap')
    </div>

    <!-- ==================== 4. TRAVEL PANEL ==================== -->
    <div class="panel" id="panel-travel" style="display: none;">
        @include('modules.travel')
    </div>

    <!-- ==================== 5. RISK PANEL ==================== -->
    <div class="panel" id="panel-risk" style="display: none;">
        @include('modules.risk')
    </div>

    <!-- ==================== 6. POLICIES PANEL ==================== -->
    <div class="panel" id="panel-policies" style="display: none;">
        @include('modules.policies')
    </div>

    <!-- ==================== 7. INVESTIGATIONS PANEL ==================== -->
    <div class="panel" id="panel-investigations" style="display: none;">
        @include('modules.investigations')
    </div>

    <!-- ==================== 8. PDP PANEL ==================== -->
    <div class="panel" id="panel-pdp" style="display: none;">
        @include('modules.pdp')
    </div>

    <!-- ==================== 9. TRAINING PANEL ==================== -->
    <div class="panel" id="panel-training" style="display: none;">
        @include('modules.training')
    </div>

    <!-- ==================== 10. STATES PANEL ==================== -->
    <div class="panel" id="panel-states" style="display: none;">
        @include('modules.states')
    </div>

    <!-- ==================== 11. LESSONS PANEL ==================== -->
    <div class="panel" id="panel-lessons" style="display: none;">
        @include('modules.lessons')
    </div>

    <!-- ==================== 12. REPORTS PANEL ==================== -->
    <div class="panel" id="panel-reports" style="display: none;">
        @include('modules.reports')
    </div>

    <!-- ==================== 13. AI PANEL ==================== -->
    <div class="panel" id="panel-ai" style="display: none;">
        @include('modules.ai')
    </div>

    <!-- ==================== 14. AI REVIEW PANEL ==================== -->
    <div class="panel" id="panel-ai-review" style="display: none;">
        @include('modules.ai_review')
    </div>

</div>

<script>
    const PANEL_TITLES = {
        'dashboard': (window.CURRENT_USER_ROLE === 'superadmin' ? 'Super Administrator Master Command Console' : (window.CURRENT_USER_ROLE === 'hr' ? 'HR Command Dashboard' : 'Director Executive Command Center')),
        'leave-attendance': 'Leave & Attendance Management',
        'complaints': 'Complaints & Whistleblower Hub',
        'cap': 'Corrective Action Plans (CAP)',
        'travel': 'Travel & Ticket Boarding Pass Gate',
        'risk': 'ISO 31000 Risk Register',
        'policies': 'Policy Repository & Sign-off',
        'investigations': 'Forensic Investigation Hub',
        'pdp': 'Staff Performance Development Plans (PDP 150)',
        'training': 'Compliance Academy & Training',
        'states': 'State Regional Offices & Clusters',
        'lessons': 'Lessons Learned & Case Retrospectives',
        'reports': 'Reports & Donor Intelligence',
        'ai': 'ComplianceIQ AI Assistant',
        'ai-review': 'AI Compliance Review & Clause Diagnostics',
        'staff': 'Attendify Staff Compliance Portal'
    };

    function switchPanel(panelId) {
        // =========================================================================
        // ABSOLUTE UNRESTRICTED ACCESS FOR SUPER ADMINISTRATOR (ROOT MASTER ACCESS)
        // =========================================================================
        if (window.CURRENT_USER_ROLE === 'superadmin') {
            document.querySelectorAll('.panel').forEach(p => p.style.display = 'none');
            const target = document.getElementById('panel-' + panelId);
            if (target) {
                target.style.display = 'block';
                if (panelId === 'dashboard') {
                    const saView = document.getElementById('superadminDashboardView');
                    const docView = document.getElementById('docDashboardView');
                    const hrView = document.getElementById('hrDashboardView');
                    if (saView) saView.style.display = 'block';
                    if (docView) docView.style.display = 'none';
                    if (hrView) hrView.style.display = 'none';
                }
            }

            if (panelId === 'ai-review' && typeof renderHistory === 'function' && typeof renderActiveReport === 'function') {
                renderHistory();
                renderActiveReport();
            }
            if (panelId === 'pdp' && typeof window.initPdpModule === 'function') {
                window.initPdpModule();
            }
            if (panelId === 'training' && typeof window.initTrainingModule === 'function') {
                window.initTrainingModule();
            }
            if (panelId === 'cap' && typeof window.initCapModule === 'function') {
                window.initCapModule();
            }
            if (panelId === 'complaints' && typeof window.initComplaintsModule === 'function') {
                window.initComplaintsModule();
            }
            if (panelId === 'policies' && typeof window.initPoliciesModule === 'function') {
                window.initPoliciesModule();
            }
            if (panelId === 'lessons' && typeof window.initLessonsModule === 'function') {
                window.initLessonsModule();
            }
            if (panelId === 'investigations' && typeof window.initInvestigationsModule === 'function') {
                window.initInvestigationsModule();
            }
            if (panelId === 'travel' && typeof window.initTravelModule === 'function') {
                window.initTravelModule();
            }
            if (panelId === 'staff' && typeof window.initStaffModule === 'function') {
                window.initStaffModule();
            }

            // Update Topbar Title dynamically
            const topbarTitle = document.querySelector('.topbar-title');
            if (topbarTitle && PANEL_TITLES[panelId]) {
                topbarTitle.innerText = PANEL_TITLES[panelId];
            }

            // Update Sidebar active state
            document.querySelectorAll('.nav-item').forEach(item => {
                item.style.background = 'transparent';
                item.style.color = 'var(--text-dim)';
                item.style.borderLeftColor = 'transparent';
            });

            const activeNav = document.querySelector('.nav-item[href="/' + panelId + '"]') || document.querySelector('.nav-item[data-panel="' + panelId + '"]');
            if (activeNav) {
                activeNav.style.background = 'rgba(2, 54, 123, 0.08)';
                activeNav.style.color = 'var(--accent)';
                activeNav.style.borderLeftColor = 'var(--accent)';
                activeNav.style.fontWeight = '700';
            }

            history.pushState(null, '', '/' + panelId);
            return;
        }

        // Enforce role access to Dashboard (Director of Compliance & Super Admin Only)
        if (panelId === 'dashboard' && window.CURRENT_USER_ROLE && !['doc', 'superadmin'].includes(window.CURRENT_USER_ROLE)) {
            alert('Access Restricted: The Executive Command Dashboard is restricted to the Director of Compliance (DoC) and Super Administrator.');
            panelId = window.DEFAULT_MODULE || 'leave-attendance';
        }

        // Enforce NO ACCESS to Risk Register for HR
        if (panelId === 'risk' && window.CURRENT_USER_ROLE === 'hr') {
            alert('Access Restricted: The ISO 31000 Enterprise Risk Register is restricted to the Compliance Directorate and Executive Leadership. HR personnel do not have access.');
            panelId = 'leave-attendance';
        }

        // Enforce NO ACCESS to AI Compliance Review for HR
        if (panelId === 'ai-review' && window.CURRENT_USER_ROLE === 'hr') {
            alert('Access Restricted: AI Compliance Review is restricted to Compliance Directorate and Legal. HR personnel do not have access.');
            panelId = 'leave-attendance';
        }

        // Check if module is allowed for current role (Super Admin has zero restrictions)
        if (!['doc', 'superadmin'].includes(window.CURRENT_USER_ROLE) && window.ALLOWED_MODULES && !window.ALLOWED_MODULES.includes(panelId)) {
            alert('Access Restricted: Your role profile does not have permission to view this module.');
            panelId = window.DEFAULT_MODULE || 'complaints';
        }

        // If HR, remove New Complaint buttons from topbar
        if (window.CURRENT_USER_ROLE === 'hr') {
            document.querySelectorAll('button[onclick*="modalLogComplaint"]').forEach(b => b.style.display = 'none');
            const topbarBtn = document.getElementById('topbarBtnNewComplaint');
            if (topbarBtn) topbarBtn.style.display = 'none';
        }

        // Hide all panels
        document.querySelectorAll('.panel').forEach(p => p.style.display = 'none');
        
        // Show target panel
        const target = document.getElementById('panel-' + panelId);
        if (target) {
            target.style.display = 'block';
            if (panelId === 'dashboard') {
                const hrView = document.getElementById('hrDashboardView');
                const docView = document.getElementById('docDashboardView');
                const saView = document.getElementById('superadminDashboardView');
                if (window.CURRENT_USER_ROLE === 'superadmin') {
                    if (saView) saView.style.display = 'block';
                    if (docView) docView.style.display = 'none';
                    if (hrView) hrView.style.display = 'none';
                } else if (window.CURRENT_USER_ROLE === 'hr') {
                    if (hrView) hrView.style.display = 'block';
                    if (docView) docView.style.display = 'none';
                    if (saView) saView.style.display = 'none';
                } else {
                    if (docView) {
                        docView.style.display = 'block';
                        if (typeof window.syncDocDashboardFromBackend === 'function') {
                            window.syncDocDashboardFromBackend();
                        }
                    }
                    if (hrView) hrView.style.display = 'none';
                    if (saView) saView.style.display = 'none';
                }
            }
            if (panelId === 'dashboard' && typeof window.syncDocDashboardFromBackend === 'function') {
                window.syncDocDashboardFromBackend();
            }
            if (panelId === 'ai-review' && typeof renderHistory === 'function' && typeof renderActiveReport === 'function') {
                renderHistory();
                renderActiveReport();
            }
            if (panelId === 'pdp' && typeof window.initPdpModule === 'function') {
                window.initPdpModule();
            } else if (panelId === 'pdp' && (window.CURRENT_USER_ROLE === 'doc' || window.CURRENT_USER_ROLE === 'hr') && typeof switchPdpSubTab === 'function') {
                switchPdpSubTab('hr-audit');
            }
            if (panelId === 'training' && typeof window.initTrainingModule === 'function') {
                window.initTrainingModule();
            }
            if (panelId === 'cap' && typeof window.initCapModule === 'function') {
                window.initCapModule();
            }
            if (panelId === 'complaints' && typeof window.initComplaintsModule === 'function') {
                window.initComplaintsModule();
            }
            if (panelId === 'policies' && typeof window.initPoliciesModule === 'function') {
                window.initPoliciesModule();
            }
            if (panelId === 'lessons' && typeof window.initLessonsModule === 'function') {
                window.initLessonsModule();
            }
            if (panelId === 'investigations' && typeof window.initInvestigationsModule === 'function') {
                window.initInvestigationsModule();
            }
            if (panelId === 'travel' && typeof window.initTravelModule === 'function') {
                window.initTravelModule();
            }
            if (panelId === 'staff' && typeof window.initStaffModule === 'function') {
                window.initStaffModule();
            }
        }

        // Update Topbar Title dynamically
        const topbarTitle = document.querySelector('.topbar-title');
        if (topbarTitle && PANEL_TITLES[panelId]) {
            topbarTitle.innerText = PANEL_TITLES[panelId];
        }

        // Update Sidebar active state
        document.querySelectorAll('.nav-item').forEach(item => {
            item.style.background = 'transparent';
            item.style.color = 'var(--text-dim)';
            item.style.borderLeftColor = 'transparent';
        });

        const activeNav = document.querySelector('.nav-item[href="/' + panelId + '"]') || document.querySelector('.nav-item[data-panel="' + panelId + '"]');
        if (activeNav) {
            activeNav.style.background = 'rgba(2, 54, 123, 0.08)';
            activeNav.style.color = 'var(--accent)';
            activeNav.style.borderLeftColor = 'var(--accent)';
            activeNav.style.fontWeight = '700';
        }

        // Update browser URL hash without full reload
        history.pushState(null, '', '/' + panelId);
    }
</script>
@endsection
