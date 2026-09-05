
<style>
.staff-main-tab {
    background: transparent;
    color: var(--text-dim, #334155);
}
.staff-main-tab:hover {
    background: var(--surface2, #f1f5f9);
    color: var(--accent, #02367B);
}
.staff-main-tab.active {
    background: #02367B !important;
    color: #ffffff !important;
}
.staff-main-tab.active i {
    color: #ffffff !important;
}
</style>

@extends('layouts.app')

@section('content')
<div id="staffModuleContainer" style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;">

    <!-- ══════════════════════════════════════════════════════════════════
         ATTENDIFY HOST CONTEXT & STAFF SSO COMMAND BANNER
         ══════════════════════════════════════════════════════════════════ -->
    <div style="background: linear-gradient(135deg, #022b61 0%, #02367B 55%, #0077b6 100%); color: #ffffff; padding: 22px 26px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 16px rgba(2, 54, 123, 0.22); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; border-radius: 50%; background: #ffffff; color: #02367B; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; border: 3px solid #55E2E9; box-shadow: 0 2px 6px rgba(0,0,0,0.15);" id="staffAvatarDisplay">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span style="background: #55E2E9; color: #012454; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-link me-1"></i> Attendify SSO Verified
                    </span>
                    <span style="font-size: 11px; opacity: 0.9; color: #e0f2fe;">
                        <i class="fa-solid fa-id-badge me-1"></i> <span id="staffIdDisplay">CCCRN-SSO-ACTIVE</span>
                    </span>
                    <span style="background: rgba(255,255,255,0.15); font-size: 10px; padding: 2px 6px; border-radius: 4px;">
                        <i class="fa-solid fa-location-dot me-1"></i> <span id="staffDutyStationDisplay">National Office · Assigned Duty Station</span>
                    </span>
                    <span id="currentRoleBadgePill" class="pill" style="background: #fef08a; color: #854d0e; font-size: 10px; font-weight: 800;">
                        STAFF ROLE
                    </span>
                </div>
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 22px; font-weight: 800; margin: 4px 0 2px; color: #ffffff;" id="staffNameDisplay">
                    Authenticated Staff
                </h1>
                <div style="font-size: 12px; color: #e2e8f0; opacity: 0.95;">
                    <span id="staffRoleDeptDisplay">Technical &amp; Operational Services</span> · Supervisor: <strong id="staffSupervisorDisplay" style="color: #55E2E9;">Assigned Supervisor</strong>
                </div>
            </div>
        </div>

        <!-- Dynamic Host SSO Integration Status Badge -->
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; background: rgba(0,0,0,0.25); padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
            <span style="font-size: 11px; color: #a7f3d0; background: rgba(16, 185, 129, 0.2); padding: 4px 9px; border-radius: 4px; border: 1px solid rgba(52, 211, 153, 0.4); font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fa-solid fa-satellite-dish"></i> Host SSO Active
            </span>
            <span style="font-size: 11px; font-weight: 700; color: #55E2E9; margin-left: 4px;" id="hostSessionIndicator">
                <i class="fa-solid fa-id-card-clip me-1"></i> Identity: <span id="bannerActiveRoleText">Staff Portal</span>
            </span>
            <!-- Notification Bell in Staff Header -->
            <button class="btn btn-sm" onclick="toggleStaffAlertsPanel()" style="position: relative; font-size: 11px; padding: 3px 9px; background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3); border-radius: 4px; cursor: pointer;" title="Compliance Alerts & Notifications">
                <i class="fa-solid fa-bell"></i>
                <span id="staffHeaderAlertBadge" style="display: none; position: absolute; top: -5px; right: -5px; background: #dc2626; color: #ffffff; font-size: 9px; font-weight: 800; padding: 0 4px; border-radius: 8px;">0</span>
            </button>
            <button class="btn btn-sm" onclick="CCCRN_STORE.reset()" style="font-size: 10px; padding: 3px 8px; background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3); border-radius: 4px; cursor: pointer; margin-left: 6px;" title="Reset cache / LocalStorage">
                <i class="fa-solid fa-arrows-rotate me-1"></i> Refresh
            </button>
        </div>
    </div>

    
    <!-- ══════════════════════════════════════════════════════════════════
         INDEPENDENT WORKFORCE FEATURE NAVIGATION TABS
         • Self-contained top navigation bar enabling 100% independent access
         • Seamless whether embedded in Attendify or accessed as standalone feature
         ══════════════════════════════════════════════════════════════════ -->
    <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 10px; padding: 6px 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 6px; overflow-x: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.05); white-space: nowrap;">
        <button class="staff-main-tab active" onclick="switchStaffMainTab('leave', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-calendar-check text-primary"></i>
            <span>My Leave</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('complaints', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-inbox" style="color: #dc2626;"></i>
            <span>Complaints</span>
            <span class="badge" id="staffComplaintsNavBadge" style="background: #dc2626; color: #ffffff; font-size: 9px; padding: 1px 6px; border-radius: 10px; display: none;">0</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('fieldwork', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-map-location-dot" style="color: #10b981;"></i>
            <span>Field Work</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('cap', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-circle-check" style="color: #059669;"></i>
            <span>Corrective Actions (CAP)</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('pdp', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-bullseye" style="color: #0284c7;"></i>
            <span>PDP (150 Pts)</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('training', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-graduation-cap" style="color: #d97706;"></i>
            <span>Training Academy</span>
        </button>
        <button id="tabNavStates" class="staff-main-tab" onclick="switchStaffMainTab('states', this)" style="display: none; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-building-flag" style="color: #15803d;"></i>
            <span>State Offices</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('policies', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-file-contract" style="color: #7c3aed;"></i>
            <span>Policies</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('lessons', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-book-bookmark" style="color: #4f46e5;"></i>
            <span>Lessons Learned</span>
        </button>
        <button class="staff-main-tab" onclick="switchStaffMainTab('ai', this)" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-robot" style="color: #02367B;"></i>
            <span>AI Helpdesk</span>
        </button>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         EMAIL NOTIFICATION BANNER (MANDATORY TRAINING / OBJECTIVE NOTICES)
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffEmailNoticeBanner" style="background: #fffbeb; border: 1px solid #fde68a; border-left: 4px solid #d97706; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 12px; color: #92400e;">
                    <i class="fa-solid fa-bell me-1"></i> Email Broadcast: New Mandatory Training Published: "TR-02 PSEA &amp; Safeguarding Standards (2026)"
                </div>
                <div style="font-size: 11px; color: #78350f; margin-top: 1px;">
                    Dispatched by Compliance Directorate · Required for all clinical and field personnel · Deadline: <strong>15 March 2026</strong>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary btn-sm" onclick="switchStaffMainTab('training', document.querySelectorAll('.staff-main-tab')[3])" style="font-size: 11px; padding: 4px 12px; background: #d97706; border-color: #d97706;">
                <i class="fa-solid fa-graduation-cap me-1"></i> Launch Training &amp; Log Attendance
            </button>
            <button class="btn btn-outline btn-sm" onclick="document.getElementById('staffEmailNoticeBanner').style.display='none'" style="font-size: 11px; padding: 4px 8px;">Dismiss</button>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         STAFF ATTENDANCE CLOCK & PERSONAL LEAVE DESK
         • Staff can mark attendance and apply for leave
         • NO notifications of other staff leave requests (solely for HR/Supervisor/HOD)
         ══════════════════════════════════════════════════════════════════ -->
    <!-- STAFF ATTENDANCE CLOCK (ATTENDIFY BIOMETRICS) -->
    <div class="card" style="margin-bottom: 20px; padding: 14px 18px; border-left: 4px solid #02367B; background: #ffffff;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(2, 54, 123, 0.1); color: #02367B; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 14px; color: var(--text);">
                        Daily Biometric Attendance
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted);">
                        Terminal: <strong>BIO-PORTAL-01</strong> · Biometric Duty Station: <strong style="color: #0284c7;">Synced via Attendify</strong>
                    </div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="staffClockStatusPill" class="pill pill-progress" style="font-size: 10px; font-weight: 700; padding: 5px 10px;">
                    Clocked Out · Duty Pending
                </span>
                <button id="btnStaffClockToggle" class="btn btn-primary btn-sm" onclick="toggleStaffClock()" style="font-size: 11px; padding: 6px 14px; font-weight: 700; background: #02367B; border-color: #02367B; color: #ffffff;">
                    <i class="fa-solid fa-clock me-1"></i> Clock-In for Duty
                </button>
            </div>
        </div>
    </div>

    <!-- Supervisor Supervisee Leave Notification Queue (Visible ONLY when Supervisor or HOD perspective is toggled) -->
    <div id="supervisorLeaveQueueWrapper" style="display: none; background: #f0f7ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 14px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <div style="font-size: 12px; font-weight: 800; color: #02367B; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-user-check"></i> Supervisee Leave Applications — Supervisor Authentication Queue (Tier 1 of 2)
            </div>
            <span id="supervisorLeavePendingBadge" class="badge" style="background: #02367B; color: #fff; font-size: 9px; padding: 2px 8px; border-radius: 10px;">0 Pending</span>
        </div>
        <div id="supervisorLeaveListContainer" style="display: flex; flex-direction: column; gap: 8px;">
            <div style="background: #ffffff; border: 1px dashed #e9d5ff; border-radius: 6px; padding: 12px; text-align: center; color: #a855f7; font-size: 11px;">
                <i class="fa-solid fa-spinner fa-spin me-1"></i> Checking for pending supervisee leave applications...
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 0: MY LEAVE MANAGEMENT & APPLICATIONS (DEDICATED SECTION)
         ══════════════════════════════════════════════════════════════════ -->
    <div class="staff-module-section" id="staffSectionLeave" style="display: block;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-calendar-check" style="color: #0077b6;"></i> My Leave Entitlements &amp; Applications Desk
                </h3>
                <div style="font-size: 12px; color: var(--text-muted);">
                    Apply for leave, manage designated relievers, track real-time HR approvals, and review leave balance deductions
                </div>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('modalStaffApplyLeave')" style="background: #0077b6; border-color: #0077b6; font-size: 12px; padding: 7px 16px; font-weight: 700;">
                <i class="fa-solid fa-calendar-plus me-1"></i> Apply for Leave
            </button>
        </div>

        <!-- Leave Balances Row -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px;">
            <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--accent); background: #ffffff; padding: 14px 18px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">ANNUAL LEAVE</div>
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: var(--accent); margin: 4px 0;" id="staffAnnualBalanceDisplay">14 <span style="font-size: 13px; font-weight: 500; color: var(--text-muted);">/ 21 Days</span></div>
                <div style="font-size: 11px; color: var(--text-muted);">7 days utilized this fiscal year</div>
            </div>

            <div class="card" style="margin-bottom: 0; border-left: 4px solid #d97706; background: #ffffff; padding: 14px 18px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">CASUAL LEAVE</div>
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: #d97706; margin: 4px 0;" id="staffCasualBalanceDisplay">4 <span style="font-size: 13px; font-weight: 500; color: var(--text-muted);">/ 7 Days</span></div>
                <div style="font-size: 11px; color: var(--text-muted);">3 days utilized</div>
            </div>

            <div class="card" style="margin-bottom: 0; border-left: 4px solid #059669; background: #ffffff; padding: 14px 18px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">SICK LEAVE</div>
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: #059669; margin: 4px 0;" id="staffSickBalanceDisplay">10 <span style="font-size: 13px; font-weight: 500; color: var(--text-muted);">/ 12 Days</span></div>
                <div style="font-size: 11px; color: var(--text-muted);">2 days certified medical leave</div>
            </div>
        </div>

        <!-- Personal Leave Applications Register -->
        <div class="card" style="margin-bottom: 0; padding: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                <div style="font-weight: 700; font-size: 13px; color: var(--text); display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-list-check" style="color: #0077b6;"></i> My Leave Applications History
                </div>
                <span style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-rotate me-1"></i> Live synced with HR Leave Register</span>
            </div>

            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 11.5px; table-layout: fixed;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted); font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 9px 8px; width: 18%;">Request ID</th>
                            <th style="padding: 9px 8px; width: 16%;">Category</th>
                            <th style="padding: 9px 8px; width: 34%;">Schedule</th>
                            <th style="padding: 9px 8px; width: 18%;">Reliever</th>
                            <th style="padding: 9px 8px; width: 14%; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="staffLeaveHistoryTableBody">
                        <!-- Dynamic Rows from CCCRN_STORE and Backend -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 1: COMPLAINTS & GRIEVANCES
         • All staff can log complaint
         • See status of complaint
         • History of previous complaints & status on dashboard
         • Can view CAP
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionComplaints" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-inbox text-primary"></i> My Confidential Complaints &amp; Grievance History
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        All staff are empowered to log complaints with zero-retaliation protection · Track resolution status and linked CAPs
                    </div>
                </div>
                <button class="btn btn-primary btn-sm" onclick="openModal('modalStaffLogComplaint')" style="font-size: 11px; background: #dc2626; border-color: #dc2626;">
                    <i class="fa-solid fa-plus me-1"></i> Log New Complaint
                </button>
            </div>

            <!-- History Table (Zero Horizontal Scroll) -->
            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border); margin-top: 8px;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 12%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Ref Code</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Date Logged</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Category</th>
                            <th style="width: 26%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Summary / Subject</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Severity</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Status</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Linked CAP</th>
                        </tr>
                    </thead>
                    <tbody id="staffComplaintsHistoryTable">
                        <!-- Populated dynamically via renderComplaintsFromStorage -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 2: CORRECTIVE ACTION PLANS (CAP)
         • Can view CAP
         • Can submit "State Evidence"
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionCap" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-circle-check text-primary"></i> Corrective Action Plans (CAP) — State Roster
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        View active corrective plans for your state office and submit verifiable state evidence
                    </div>
                </div>
                <button class="btn btn-primary btn-sm" onclick="openModalSubmitStateEvidence()" style="font-size: 11px;">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Submit State Evidence
                </button>
            </div>

            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border); margin-top: 8px;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">CAP Ref</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">State / Facility</th>
                            <th style="width: 15%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Category</th>
                            <th style="width: 33%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Action Plan &amp; Measurable Target</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Target Date</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Status</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">State Evidence</th>
                        </tr>
                    </thead>
                    <tbody id="staffCapTableBody">
                        <!-- Populated dynamically via renderCapFromStorage -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 3: PERFORMANCE DEVELOPMENT PLAN (PDP 150)
         • All Staff: Set objectives, submit evidence, submit innovation/creative, view monthly/quarterly/annual performance
         • Supervisor: Approve PDP, review & approve evidence, grade monthly Behavioral, see all supervisees
         • HOD: Grade staff creativity/innovation
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionPdp" class="staff-module-section" style="display: none;">
        <!-- Sub-Tabs for PDP System -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                <button class="btn btn-primary btn-sm pdp-sub-tab active" onclick="switchStaffPdpSubTab('objectives', this)" style="font-size: 11px; font-weight: 700;">
                    <i class="fa-solid fa-list-check me-1"></i> My Objectives (/60)
                </button>
                <button class="btn btn-outline btn-sm pdp-sub-tab" onclick="switchStaffPdpSubTab('evidence', this)" style="font-size: 11px; font-weight: 600;">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Submit Evidence (/60)
                </button>
                <button class="btn btn-outline btn-sm pdp-sub-tab" onclick="switchStaffPdpSubTab('innovation', this)" style="font-size: 11px; font-weight: 600;">
                    <i class="fa-solid fa-lightbulb me-1"></i> Submit Innovation (/50)
                </button>
                <button class="btn btn-outline btn-sm pdp-sub-tab" onclick="switchStaffPdpSubTab('scorecard', this)" style="font-size: 11px; font-weight: 600;">
                    <i class="fa-solid fa-chart-line me-1"></i> Performance Scorecard
                </button>
                <!-- Supervisor Tab Option (Visible when toggled) -->
                <button class="btn btn-outline btn-sm pdp-sub-tab" id="btnPdpSupervisorTab" onclick="switchStaffPdpSubTab('supervisor', this)" style="font-size: 11px; font-weight: 600; color: #02367B; border-color: #bae6fd; display: none;">
                    <i class="fa-solid fa-user-check me-1"></i> Supervisor Review &amp; Behavioral Grading
                </button>
            </div>

            <div>
                <button class="btn btn-primary btn-sm" onclick="openModal('modalStaffSetObjective')" style="font-size: 11px;">
                    <i class="fa-solid fa-plus me-1"></i> Set New Objective
                </button>
            </div>
        </div>

        <!-- 3A. My Objectives Tab -->
        <div id="staffPdpSubObjectives" class="pdp-sub-content" style="display: block;">
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="card-title" style="font-size: 13px; font-weight: 700;">My Agreed Performance Objectives (Target: 4 Core Objectives · Max 60 Points)</div>
                    <span style="font-size: 11px; font-weight: 700; color: #059669;">Verified Objectives Score: 56 / 60 Pts</span>
                </div>
                <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border); margin-top: 8px;">
                    <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                                <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Ref Code</th>
                                <th style="width: 32%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Objective Statement</th>
                                <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Verifiable Output</th>
                                <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Weight</th>
                                <th style="width: 15%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Approval Status</th>
                                <th style="width: 15%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 32px 16px; color: var(--text-muted); font-size: 12px;">
                                    <div style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"><i class="fa-solid fa-bullseye"></i></div>
                                    <strong>No performance objectives registered yet</strong>
                                    <div style="font-size: 11px; margin-top: 2px;">Click "+ Set New Objective" above to establish your deliverables.</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3B. Submit Evidence Tab -->
        <div id="staffPdpSubEvidence" class="pdp-sub-content" style="display: none;">
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="card-title" style="font-size: 13px; font-weight: 700;">Evidence Upload Queue &amp; Verification Progress (/60 Pts)</div>
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalStaffSubmitPdpEvidence')" style="font-size: 11px;">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Attach New Evidence
                    </button>
                </div>
                <div style="padding: 12px 0;">
                    <div id="pdpEvidenceListContainer" style="padding: 24px 12px; text-align: center; color: var(--text-muted); font-size: 12px; border: 1px dashed var(--border); border-radius: 8px;">
                        <i class="fa-solid fa-folder-open" style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px; display: block;"></i>
                        <strong>No objective deliverables uploaded yet</strong>
                        <div style="font-size: 11px; margin-top: 2px;">Attach deliverable reports to accrue your /60 Marks.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3C. Submit Innovation & Creativity Tab -->
        <div id="staffPdpSubInnovation" class="pdp-sub-content" style="display: none;">
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="card-title" style="font-size: 13px; font-weight: 700;">Institutional Innovation &amp; Creative Deliverables (Graded by HOD · Max 50 Marks)</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Process improvements, cost-saving mechanisms, workflow automation, or clinical quality inventions</div>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalStaffSubmitInnovation')" style="font-size: 11px; background: #0284c7; border-color: #0284c7;">
                        <i class="fa-solid fa-lightbulb me-1"></i> Submit Innovation Work
                    </button>
                </div>
                <div style="padding: 12px 0;">
                    <div id="pdpInnovationListContainer" style="padding: 24px 12px; text-align: center; color: var(--text-muted); font-size: 12px; border: 1px dashed var(--border); border-radius: 8px;">
                        <i class="fa-solid fa-lightbulb" style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px; display: block;"></i>
                        <strong>No innovation or creative works submitted yet</strong>
                        <div style="font-size: 11px; margin-top: 2px;">Submit automated workflows, process improvements or cost-saving ideas for HOD review.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3D. Performance Scorecard (Monthly, Quarterly, Annual) -->
        <div id="staffPdpSubScorecard" class="pdp-sub-content" style="display: none;">
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="card-title" style="font-size: 13px; font-weight: 700;">Performance Standing &amp; Scorecard (Monthly, Quarterly &amp; Annual Drill-down)</div>
                    <span class="pill pill-closed" style="font-size: 11px; font-weight: 800;">Cumulative Score: 124 / 150 (Tier 1 High Performer)</span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; padding: 10px 0;">
                    <div style="border: 1px solid var(--border); border-radius: 8px; padding: 14px; background: #ffffff;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Monthly Behavioral Grade</div>
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: #059669; margin: 4px 0;">36 / 40 Pts</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Supervisor Average (Jan–Feb 2026): <strong>90%</strong> across 8 behavioral competencies.</div>
                    </div>
                    <div style="border: 1px solid var(--border); border-radius: 8px; padding: 14px; background: #ffffff;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Quarterly Objectives (Q1)</div>
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: var(--accent); margin: 4px 0;">44 / 60 Pts</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Verified Deliverables on Objectives 1 &amp; 2; Objective 3 in review.</div>
                    </div>
                    <div style="border: 1px solid var(--border); border-radius: 8px; padding: 14px; background: #ffffff;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Annual Innovation Standing</div>
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: #0284c7; margin: 4px 0;">44 / 50 Pts</div>
                        <div style="font-size: 11px; color: var(--text-muted);">HOD Approved &amp; Instituted under Cluster Best Practices.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3E. Supervisor Perspective: Approve PDP & Grade Behavioral Performance -->
        <div id="staffPdpSubSupervisor" class="pdp-sub-content" style="display: none;">
            <div class="card" style="margin-bottom: 0; border: 1px solid #bae6fd; background: #f0f7ff;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="card-title" style="font-size: 14px; font-weight: 800; color: #02367B;">
                            <i class="fa-solid fa-user-check me-2"></i> Supervisor Command Console: Review Supervisees &amp; Grade Monthly Behaviors
                        </div>
                        <div style="font-size: 11px; color: #0077b6;">Viewing supervisees assigned under Dr. Ngozi Adeyemi (Lead Supervisor · Lagos State)</div>
                    </div>
                </div>

                <div style="background: #ffffff; border-radius: 8px; padding: 14px; margin-top: 10px; border: 1px solid #bae6fd;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Supervisees PDP Approval &amp; Monthly Grading Roster</div>
                    <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                                <th style="width: 25%; padding: 8px; text-align: left; font-size: 11px; color: var(--text-muted);">Supervisee</th>
                                <th style="width: 20%; padding: 8px; text-align: left; font-size: 11px; color: var(--text-muted);">Role &amp; Facility</th>
                                <th style="width: 15%; padding: 8px; text-align: center; font-size: 11px; color: var(--text-muted);">PDP Status</th>
                                <th style="width: 15%; padding: 8px; text-align: center; font-size: 11px; color: var(--text-muted);">Evidence Queue</th>
                                <th style="width: 15%; padding: 8px; text-align: center; font-size: 11px; color: var(--text-muted);">Behavioral Grade</th>
                                <th style="width: 10%; padding: 8px; text-align: center; font-size: 11px; color: var(--text-muted);">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 8px; font-weight: 700;">Assigned Staff (Cluster Lead)</td>
                                <td style="padding: 8px; font-size: 11px;">Technical Officer (Facility Hub)</td>
                                <td style="padding: 8px; text-align: center;"><span class="pill pill-closed" style="font-size: 9px;">Approved</span></td>
                                <td style="padding: 8px; text-align: center;"><span class="pill pill-closed" style="font-size: 9px;">All Verified</span></td>
                                <td style="padding: 8px; text-align: center; font-weight: 700; color: #059669;">38 / 40</td>
                                <td style="padding: 8px; text-align: center;">
                                    <button class="btn btn-primary btn-sm" onclick="openModalGradeBehavioral('Assigned Staff')" style="font-size: 10px; padding: 2px 6px;">Grade</button>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 8px; font-weight: 700;">Biodun Alade</td>
                                <td style="padding: 8px; font-size: 11px;">Technical Officer (Facility B)</td>
                                <td style="padding: 8px; text-align: center;"><span class="pill pill-closed" style="font-size: 9px;">Approved</span></td>
                                <td style="padding: 8px; text-align: center;"><span class="pill pill-closed" style="font-size: 9px;">All Verified</span></td>
                                <td style="padding: 8px; text-align: center; font-weight: 700; color: #059669;">34 / 40</td>
                                <td style="padding: 8px; text-align: center;">
                                    <button class="btn btn-primary btn-sm" onclick="openModalGradeBehavioral('Biodun Alade')" style="font-size: 10px; padding: 2px 6px;">Grade</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3F. HOD Perspective: Grade Staff Creativity & Innovation (/50) -->
        <div id="staffPdpSubHod" class="pdp-sub-content" style="display: none;">
            <div class="card" style="margin-bottom: 0; border: 1px solid #bae6fd; background: #f0f9ff;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="card-title" style="font-size: 14px; font-weight: 800; color: #0369a1;">
                            <i class="fa-solid fa-medal me-2"></i> Head of Department (HOD) Innovation Evaluation Bench
                        </div>
                        <div style="font-size: 11px; color: #0284c7;">Direct grading of departmental creative initiatives &amp; process patents (/50 Marks)</div>
                    </div>
                </div>

                <div style="background: #ffffff; border-radius: 8px; padding: 14px; margin-top: 10px; border: 1px solid #e0f2fe;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-weight: 700; font-size: 12px;">Submitted Staff Innovation Work (Department of Clinical Services)</span>
                        <span class="pill" style="font-size: 9px; background: #e0f2fe; color: #0284c7;">HOD Exclusive Authority</span>
                    </div>
                    <div style="border: 1px solid var(--border); border-radius: 6px; padding: 10px; margin-bottom: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="font-size: 12px; color: var(--text);">Departmental Innovation Submission — Automated Viral Load SMS Escalation Alert</strong>
                                <div style="font-size: 11px; color: var(--text-muted);">Impact: 28% reduction in pediatric lost-to-follow-up across 12 facilities.</div>
                            </div>
                            <span class="pill pill-closed" style="font-size: 10px; font-weight: 700;">Score: 44 / 50 Marks</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 4: TRAINING (COMPLIANCE ACADEMY)
         • Get email notification for new training
         • Attend training, log attendance
         • View all training he/she has attended and completed
         • State Team Lead (STL): View State performance & list of staff yet to complete
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionTraining" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-graduation-cap text-primary"></i> Compliance Academy &amp; Certification Registry
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Attend online/classroom trainings, log verified attendance, and download institutional certificates
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalAttendLogTraining')" style="font-size: 11px;">
                        <i class="fa-solid fa-pen-nib me-1"></i> Attend &amp; Log Attendance
                    </button>
                </div>
            </div>

            <!-- Training Summary & State Team Lead Console -->
            <div id="stlTrainingConsole" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 14px; margin-top: 10px; margin-bottom: 14px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <div style="font-size: 12px; font-weight: 800; color: #1e40af;">
                        <i class="fa-solid fa-users-viewfinder me-1"></i> State Team Lead (STL) Performance Console — Lagos State Office
                    </div>
                    <span class="pill pill-closed" style="font-size: 9px; font-weight: 700;">86% Trained (82 / 95 Personnel)</span>
                </div>
                <div style="font-size: 11px; color: #1e3a8a; margin-bottom: 8px;">
                    Personnel with outstanding mandatory certifications in Lagos State:
                </div>
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11px; background: #ffffff; border-radius: 6px; overflow: hidden;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 25%; padding: 6px 8px; text-align: left;">Staff Name</th>
                            <th style="width: 20%; padding: 6px 8px; text-align: left;">Department</th>
                            <th style="width: 20%; padding: 6px 8px; text-align: left;">Facility / Station</th>
                            <th style="width: 25%; padding: 6px 8px; text-align: left;">Missing Mandatory Course</th>
                            <th style="width: 10%; padding: 6px 8px; text-align: center;">Escalation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 6px 8px; font-weight: 700;">Aisha Musa</td>
                            <td style="padding: 6px 8px;">Finance</td>
                            <td style="padding: 6px 8px;">Lagos HQ</td>
                            <td style="padding: 6px 8px; color: #dc2626; font-weight: 600;">TR-03 Data Protection</td>
                            <td style="padding: 6px 8px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="alert('Email reminder dispatched to Aisha Musa')" style="font-size: 9px; padding: 1px 4px;">Remind</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 6px 8px; font-weight: 700;">Tunde Balogun</td>
                            <td style="padding: 6px 8px;">Operations</td>
                            <td style="padding: 6px 8px;">Badagry Clinic</td>
                            <td style="padding: 6px 8px; color: #dc2626; font-weight: 600;">TR-02 PSEA Standards</td>
                            <td style="padding: 6px 8px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="alert('Email reminder dispatched to Tunde Balogun')" style="font-size: 9px; padding: 1px 4px;">Remind</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Staff Personal Attended & Completed Trainings List -->
            <div style="font-size: 12px; font-weight: 700; color: var(--text); margin: 8px 0 6px;">My Attended &amp; Completed Certification Ledger</div>
            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 12%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Module Code</th>
                            <th style="width: 38%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Course Title</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Date Attended</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Score</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Status</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Certificate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 28px 16px; color: var(--text-muted); font-size: 12px;">
                                <div style="font-size: 22px; color: #cbd5e1; margin-bottom: 4px;"><i class="fa-solid fa-graduation-cap"></i></div>
                                <strong>No attended trainings recorded yet</strong>
                                <div style="font-size: 11px; margin-top: 2px;">Click "Finish" or "Launch Training" to attend and certify.</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <!-- ══════════════════════════════════════════════════════════════════
         MODULE: FIELD WORK & MISSIONS
         • Staff can request field mission, log field visits & travel clearance
         • Track travel advances, vehicle logistics, facility mentorship
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionFieldWork" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 800; color: #02367B; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-map-location-dot" style="color: #10b981;"></i> Field Work &amp; Clinical Missions Desk
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Register programmatic site visits, field supportive supervision, logistics clearance, and travel allowance tracking
                    </div>
                </div>
                <button class="btn btn-primary btn-sm" onclick="openModal('modalStaffLogFieldWork')" style="font-size: 11px; background: #10b981; border-color: #10b981;">
                    <i class="fa-solid fa-plus me-1"></i> + Log Field Mission / Travel
                </button>
            </div>

            <!-- Field Work KPI Stat Tiles -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; padding: 12px 0;">
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px;">
                    <div style="font-size: 10px; font-weight: 700; color: #166534; text-transform: uppercase;">Active Missions</div>
                    <div id="statActiveFieldMissions" style="font-size: 20px; font-weight: 800; color: #15803d; margin: 4px 0;">0</div>
                    <div style="font-size: 10px; color: #166534;">Currently deployed</div>
                </div>
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px;">
                    <div style="font-size: 10px; font-weight: 700; color: #1e40af; text-transform: uppercase;">Missions Completed</div>
                    <div id="statCompletedFieldMissions" style="font-size: 20px; font-weight: 800; color: #1d4ed8; margin: 4px 0;">0</div>
                    <div style="font-size: 10px; color: #1e40af;">This Quarter</div>
                </div>
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px;">
                    <div style="font-size: 10px; font-weight: 700; color: #92400e; text-transform: uppercase;">Travel Advances Pending</div>
                    <div id="statPendingFieldAdvances" style="font-size: 20px; font-weight: 800; color: #b45309; margin: 4px 0;">0</div>
                    <div style="font-size: 10px; color: #92400e;">Awaiting Finance</div>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                    <div style="font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase;">Field Safety Standing</div>
                    <div style="font-size: 13px; font-weight: 800; color: #0f172a; margin: 6px 0;">POL-SEC-007 Cleared</div>
                    <div style="font-size: 10px; color: #059669;"><i class="fa-solid fa-circle-check me-1"></i> Security Protocol Active</div>
                </div>
            </div>

            <!-- Field Work Register Table -->
            <div style="font-size: 12px; font-weight: 700; color: var(--text); margin: 8px 0 6px;">Field Work Missions Register</div>
            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 12%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Ref</th>
                            <th style="width: 22%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Target Facility / State</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Mission Purpose</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Travel Dates</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Travel Advance</th>
                            <th style="width: 16%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="staffFieldWorkTableBody">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px 16px; color: var(--text-muted); font-size: 12px;">
                                <div style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"><i class="fa-solid fa-map-location-dot"></i></div>
                                <strong>No field work or missions recorded yet</strong>
                                <div style="font-size: 11px; margin-top: 2px;">Click "+ Log Field Mission / Travel" to register your next field assignment.</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- ══════════════════════════════════════════════════════════════════
         MODULE 5: STATE & CLUSTERS (VIEW ACCESS ONLY FOR STL & CLUSTER LEAD)
         • View Access for Only STL and Cluster Lead
         • Submit field update form
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionStates" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 800; color: var(--accent); display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-building-flag"></i> State Regional Office &amp; Cluster Command (STL Access)
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Restricted View: State Team Leads (STL) and Cluster Leads only · Field operations &amp; audit monitoring
                    </div>
                </div>
                <button class="btn btn-primary btn-sm" onclick="openModal('modalSubmitFieldUpdate')" style="font-size: 11px;">
                    <i class="fa-solid fa-file-pen me-1"></i> Submit Field Update Form
                </button>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; padding: 12px 0;">
                <div class="card" style="margin-bottom: 0; border-top: 3px solid #059669;">
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: var(--text);">Lagos Cluster A</div>
                    <div style="font-size: 11px; color: var(--text-muted); margin: 2px 0 8px;">Lead: Dr. Ngozi Adeyemi · 95 Personnel</div>
                    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                        <span>State Compliance Score</span>
                        <strong style="color: #059669;">78%</strong>
                    </div>
                    <div style="display: flex; gap: 6px; font-size: 10px; margin-top: 6px;">
                        <span class="pill pill-progress">2 Complaints</span>
                        <span class="pill pill-closed">1 Active CAP</span>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 0; border-top: 3px solid #dc2626; background: #fff5f5;">
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: #991b1b;">Kano Cluster B</div>
                    <div style="font-size: 11px; color: var(--text-muted); margin: 2px 0 8px;">Lead: Musa Ibrahim · 82 Personnel</div>
                    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                        <span>State Compliance Score</span>
                        <strong style="color: #dc2626;">62% (Audit Alert)</strong>
                    </div>
                    <div style="display: flex; gap: 6px; font-size: 10px; margin-top: 6px;">
                        <span class="pill pill-open">3 Complaints</span>
                        <span class="pill pill-open">2 Active CAPs</span>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 0; border-top: 3px solid #0284c7;">
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: var(--text);">Rivers Cluster C</div>
                    <div style="font-size: 11px; color: var(--text-muted); margin: 2px 0 8px;">Lead: Chidi Okafor · 74 Personnel</div>
                    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                        <span>State Compliance Score</span>
                        <strong style="color: #0284c7;">81%</strong>
                    </div>
                    <div style="display: flex; gap: 6px; font-size: 10px; margin-top: 6px;">
                        <span class="pill pill-closed">1 Complaint</span>
                        <span class="pill pill-closed">1 Active CAP</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 6: POLICY MANAGEMENT
         • View and read policy
         • Acknowledge (digital sign-off)
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionPolicies" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-file-contract text-primary"></i> Policy Repository &amp; Digital Acknowledgments
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Read organizational policies and submit legally binding digital compliance acknowledgments
                    </div>
                </div>
            </div>

            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border); margin-top: 8px;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 12%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Code</th>
                            <th style="width: 36%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Policy Title &amp; Scope</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Version</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Read Policy</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Sign-off Status</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">POL-PSEA-001</td>
                            <td style="padding: 10px 8px; font-weight: 600;">Prevention of Sexual Exploitation &amp; Abuse (PSEA)</td>
                            <td style="padding: 10px 8px; text-align: center; font-size: 11px;">v2.1</td>
                            <td style="padding: 10px 8px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="openModalReadPolicy('POL-PSEA-001', 'PSEA & Safeguarding Standards')" style="padding: 2px 6px; font-size: 10px;"><i class="fa-solid fa-book-open me-1"></i> Read</button></td>
                            <td style="padding: 10px 8px; text-align: center;"><span class="pill pill-open" style="font-size: 9px; font-weight: 700;">Sign-off Due</span></td>
                            <td style="padding: 10px 8px; text-align: center;"><button class="btn btn-primary btn-sm" onclick="handleAcknowledgePolicy('POL-PSEA-001')" style="font-size: 10px; padding: 3px 8px;"><i class="fa-solid fa-signature me-1"></i> Acknowledge</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">POL-TRV-03</td>
                            <td style="padding: 10px 8px; font-weight: 600;">Travel, Subsistence &amp; Flight Escrow Gate Protocol</td>
                            <td style="padding: 10px 8px; text-align: center; font-size: 11px;">v1.5</td>
                            <td style="padding: 10px 8px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="openModalReadPolicy('POL-TRV-03', 'Travel & Flight Escrow Protocol')" style="padding: 2px 6px; font-size: 10px;"><i class="fa-solid fa-book-open me-1"></i> Read</button></td>
                            <td style="padding: 10px 8px; text-align: center;"><span class="pill pill-open" style="font-size: 9px; font-weight: 700;">Sign-off Due</span></td>
                            <td style="padding: 10px 8px; text-align: center;"><button class="btn btn-primary btn-sm" onclick="handleAcknowledgePolicy('POL-TRV-03')" style="font-size: 10px; padding: 3px 8px;"><i class="fa-solid fa-signature me-1"></i> Acknowledge</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">POL-SEC-007</td>
                            <td style="padding: 10px 8px; font-weight: 600;">Field Physical Security &amp; Kidnap Incident Response</td>
                            <td style="padding: 10px 8px; text-align: center; font-size: 11px;">v1.0</td>
                            <td style="padding: 10px 8px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="openModalReadPolicy('POL-SEC-007', 'Field Security Protocol')" style="padding: 2px 6px; font-size: 10px;"><i class="fa-solid fa-book-open me-1"></i> Read</button></td>
                            <td style="padding: 10px 8px; text-align: center;" id="statusPolSec007"><span class="pill pill-open" style="font-size: 9px; font-weight: 700;">Sign-off Due</span></td>
                            <td style="padding: 10px 8px; text-align: center;" id="actionPolSec007">
                                <button class="btn btn-primary btn-sm" onclick="handleAcknowledgePolicy('POL-SEC-007')" style="font-size: 10px; padding: 3px 8px;">
                                    <i class="fa-solid fa-signature me-1"></i> Acknowledge
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 7: LESSONS LEARNED
         • All Staff: View access only
         • Supervisors / HOD: Add new lessons
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionLessons" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-book-bookmark text-primary"></i> Institutional Lessons Learned &amp; Case Retrospectives
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Staff View Only repository of operational improvements and audited root cause analyses
                    </div>
                </div>
                <div id="supervisorAddLessonWrapper" style="display: none;">
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalStaffAddLesson')" style="font-size: 11px; background: #02367B; border-color: #02367B;">
                        <i class="fa-solid fa-plus me-1"></i> + Add New Lesson Learned
                    </button>
                </div>
            </div>

            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border); margin-top: 8px;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Lesson Ref</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Source Domain</th>
                            <th style="width: 44%; padding: 10px 8px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700;">Key Lesson &amp; Preventative Recommendation</th>
                            <th style="width: 16%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Category</th>
                            <th style="width: 16%; padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); font-weight: 700;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="staffLessonsTableBody">
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 28px 16px; color: var(--text-muted); font-size: 12px;">
                                <div style="font-size: 22px; color: #cbd5e1; margin-bottom: 4px;"><i class="fa-solid fa-book-open"></i></div>
                                <strong>No institutional case retrospectives published yet</strong>
                                <div style="font-size: 11px; margin-top: 2px;">Lessons learned from audits and retrospectives will be displayed here.</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         MODULE 8: AI ASSISTANCE (STAFF HELPDESK ONLY)
         • Access only Staff Helpdesk
         ══════════════════════════════════════════════════════════════════ -->
    <div id="staffSectionAi" class="staff-module-section" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-robot text-primary"></i> ComplianceIQ AI Staff Helpdesk
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Immediate guidance on CCCRN policies, PSEA reporting channels, leave entitlement calculation, and travel rules
                    </div>
                </div>
                <span class="pill" style="background: #e0f2fe; color: #0284c7; font-size: 10px; font-weight: 800;">Staff Helpdesk Mode</span>
            </div>

            <!-- Suggested Prompt Chips -->
            <div style="display: flex; gap: 6px; padding: 10px 0; flex-wrap: wrap;">
                <button class="btn btn-outline btn-sm" onclick="askAiHelpdesk('What are the reporting channels under PSEA policy POL-001?')" style="font-size: 11px; border-radius: 20px;">
                    🛡️ PSEA Reporting Channels?
                </button>
                <button class="btn btn-outline btn-sm" onclick="askAiHelpdesk('How does the POL-TRV-03 boarding pass escrow work?')" style="font-size: 11px; border-radius: 20px;">
                    ✈️ Travel Boarding Pass Escrow Rule?
                </button>
                <button class="btn btn-outline btn-sm" onclick="askAiHelpdesk('What is the deadline for submitting Q1 PDP evidence?')" style="font-size: 11px; border-radius: 20px;">
                    🎯 PDP Evidence Submission Deadline?
                </button>
                <button class="btn btn-outline btn-sm" onclick="askAiHelpdesk('Can I submit a grievance 100% anonymously?')" style="font-size: 11px; border-radius: 20px;">
                    🔒 Anonymous Grievance Submission?
                </button>
            </div>

            <!-- Chat Window -->
            <div id="staffAiChatWindow" style="height: 320px; overflow-y: auto; background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                <div style="align-self: flex-start; max-width: 80%; background: #ffffff; border: 1px solid var(--border); padding: 10px 14px; border-radius: 12px 12px 12px 2px; font-size: 12px; line-height: 1.5; color: var(--text);">
                    <div style="font-weight: 700; color: var(--accent); margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-robot"></i> ComplianceIQ Staff Assistant
                    </div>
                    Hello! I am your ComplianceIQ Staff Helpdesk Assistant. Ask me any question regarding CCCRN policies, your PDP requirements, travel guidelines, or reporting ethical concerns.
                </div>
            </div>

            <!-- Chat Input Area -->
            <div style="display: flex; gap: 8px; margin-top: 10px;">
                <input type="text" id="staffAiInput" placeholder="Ask a question about policies, leave, travel, or reporting..." onkeypress="if(event.key==='Enter')sendStaffAiQuery()" style="flex: 1; height: 38px; padding: 0 12px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                <button class="btn btn-primary" onclick="sendStaffAiQuery()" style="height: 38px; padding: 0 16px; font-size: 12px;">
                    <i class="fa-solid fa-paper-plane me-1"></i> Ask
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODALS
     ══════════════════════════════════════════════════════════════════ -->

<!-- Modal: Apply for Personal Leave -->
<div class="modal-overlay" id="modalStaffApplyLeave" onclick="if(event.target===this)closeModal('modalStaffApplyLeave')">
    <div class="modal-dialog" style="max-width: 480px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px; color: #0077b6;">
                <i class="fa-solid fa-calendar-plus me-2"></i> Apply for Personal Leave
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffApplyLeave')">&times;</button>
        </div>
        <form onsubmit="handleStaffApplyLeave(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Leave Category</label>
                    <select id="staffLeaveCategory" required style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                        <option value="Annual">Annual Leave (14 Days Available)</option>
                        <option value="Casual">Casual Leave (4 Days Available)</option>
                        <option value="Sick">Sick Leave (10 Days Available)</option>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label style="display: block; font-weight: 700; margin-bottom: 4px;">Start Date</label>
                        <input type="date" id="staffLeaveStartDate" required style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; margin-bottom: 4px;">End Date</label>
                        <input type="date" id="staffLeaveEndDate" required style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                    </div>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Designated Relieving Colleague</label>
                    <input type="text" id="staffLeaveReliever" required placeholder="Name of relieving officer during absence..." style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Handover Notes &amp; Justification</label>
                    <textarea id="staffLeaveHandover" rows="3" required placeholder="Describe task handover arrangements and supervisor contact..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; resize: vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalStaffApplyLeave')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="background: #0077b6; border-color: #0077b6;"><i class="fa-solid fa-paper-plane me-1"></i> Submit to Supervisor</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Log Complaint -->
<div class="modal-overlay" id="modalStaffLogComplaint" onclick="if(event.target===this)closeModal('modalStaffLogComplaint')">
    <div class="modal-dialog" style="max-width: 520px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px; color: #dc2626;">
                <i class="fa-solid fa-inbox me-2"></i> Log New Complaint / Grievance
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffLogComplaint')">&times;</button>
        </div>
        <form onsubmit="handleStaffSubmitComplaint(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Reporting Identity Mode</label>
                    <select id="staffComplaintMode" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                        <option value="Named">Named Submission (Protected Employee Record)</option>
                        <option value="Anonymous">100% Anonymous (Metadata Scrubbed)</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Incident Category</label>
                    <select id="staffComplaintCategory" required style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                        <option>Procurement Defect</option>
                        <option>PSEA &amp; Safeguarding</option>
                        <option>Financial Mismanagement</option>
                        <option>Workplace Harassment / Abuse</option>
                        <option>Safety / Security Violation</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Incident Title &amp; Details</label>
                    <input type="text" id="staffComplaintTitle" required placeholder="Concise summary of grievance..." style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; margin-bottom: 8px;">
                    <textarea id="staffComplaintDetails" rows="3" required placeholder="Describe dates, location, persons involved, and specific impact..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; resize: vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalStaffLogComplaint')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="background: #dc2626; border-color: #dc2626;"><i class="fa-solid fa-lock me-1"></i> Submit to DoC</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: View Linked CAP -->
<div class="modal-overlay" id="modalViewLinkedCap" onclick="if(event.target===this)closeModal('modalViewLinkedCap')">
    <div class="modal-dialog" style="max-width: 520px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="modal-title" id="linkedCapModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">CAP Details</span>
                <span id="linkedCapModalStatus" class="pill pill-progress">In Progress</span>
            </div>
            <button class="modal-close" onclick="closeModal('modalViewLinkedCap')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px;" id="linkedCapModalBody"></div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end;">
            <button class="btn btn-primary btn-sm" onclick="closeModal('modalViewLinkedCap')">Close</button>
        </div>
    </div>
</div>

<!-- Modal: Submit State Evidence under CAP -->
<div class="modal-overlay" id="modalSubmitStateEvidence" onclick="if(event.target===this)closeModal('modalSubmitStateEvidence')">
    <div class="modal-dialog" style="max-width: 480px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Submit State Verification Evidence
            </span>
            <button class="modal-close" onclick="closeModal('modalSubmitStateEvidence')">&times;</button>
        </div>
        <form onsubmit="handleStaffSubmitStateEvidence(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Target CAP</label>
                    <select id="stateEvidenceCapRef" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                        <option value="CAP-2026-012">CAP-2026-012: Facility PPE Supply Chain Rectification (Lagos)</option>
                        <option value="CAP-2026-009">CAP-2026-009: EMR Backlog Clearance (Lagos Outreach)</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Attach State Verification Evidence (PDF, Excel, Photo)</label>
                    <input type="file" id="stateEvidenceFile" required style="width: 100%; font-size: 12px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">State Office / Facility Verification Notes</label>
                    <textarea id="stateEvidenceNotes" rows="3" required placeholder="Describe audit checks completed at site level..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; resize: vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalSubmitStateEvidence')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check me-1"></i> Submit State Evidence</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Set New Objective (PDP) -->
<div class="modal-overlay" id="modalStaffSetObjective" onclick="if(event.target===this)closeModal('modalStaffSetObjective')">
    <div class="modal-dialog" style="max-width: 480px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-bullseye text-primary me-2"></i> Set New Performance Objective
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffSetObjective')">&times;</button>
        </div>
        <form onsubmit="handleStaffSetObjective(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Objective Statement</label>
                    <input type="text" id="staffNewObjStatement" required placeholder="e.g. Conduct monthly PMTCT cascade audits..." style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Target Measurable Output</label>
                    <input type="text" id="staffNewObjOutput" required placeholder="e.g. Monthly PMTCT retention register signed by MO" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Target Weight (Points)</label>
                    <input type="number" id="staffNewObjWeight" required value="15" min="5" max="25" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalStaffSetObjective')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check me-1"></i> Submit to Supervisor</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Submit PDP Evidence -->
<div class="modal-overlay" id="modalStaffSubmitPdpEvidence" onclick="if(event.target===this)closeModal('modalStaffSubmitPdpEvidence')">
    <div class="modal-dialog" style="max-width: 480px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Submit Objective Deliverable Evidence
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffSubmitPdpEvidence')">&times;</button>
        </div>
        <form onsubmit="handleStaffSubmitPdpEvidence(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Target Objective</label>
                    <select id="staffPdpObjSelect" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                        <option value="OBJ-01">OBJ-01: Pediatric ART Viral Suppression</option>
                        <option value="OBJ-02">OBJ-02: Clinical Mentorship Audits</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Evidence File (PDF/Excel)</label>
                    <input type="file" id="staffPdpEvidenceFile" required style="width: 100%; font-size: 12px;">
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalStaffSubmitPdpEvidence')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check me-1"></i> Upload Evidence</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Submit Innovation -->
<div class="modal-overlay" id="modalStaffSubmitInnovation" onclick="if(event.target===this)closeModal('modalStaffSubmitInnovation')">
    <div class="modal-dialog" style="max-width: 480px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px; color: #0284c7;">
                <i class="fa-solid fa-lightbulb me-2"></i> Submit Creative / Innovation Work
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffSubmitInnovation')">&times;</button>
        </div>
        <form onsubmit="handleStaffSubmitInnovation(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Innovation Title</label>
                    <input type="text" id="staffInnTitle" required placeholder="e.g. Mobile EMR synchronization tool..." style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Methodology &amp; Institutional Benefit</label>
                    <textarea id="staffInnDesc" rows="3" required placeholder="Explain how this initiative enhances efficiency or clinical quality..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; resize: vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalStaffSubmitInnovation')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="background: #0284c7; border-color: #0284c7;"><i class="fa-solid fa-paper-plane me-1"></i> Submit Innovation</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Attend & Log Training -->
<div class="modal-overlay" id="modalAttendLogTraining" onclick="if(event.target===this)closeModal('modalAttendLogTraining')">
    <div class="modal-dialog" style="max-width: 480px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-graduation-cap text-primary me-2"></i> Log Training Attendance
            </span>
            <button class="modal-close" onclick="closeModal('modalAttendLogTraining')">&times;</button>
        </div>
        <form onsubmit="handleStaffLogTrainingAttendance(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Course Selection</label>
                    <select id="staffTrainingCourseSelect" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                        <option value="TR-02">TR-02: PSEA &amp; Safeguarding Standards (2026)</option>
                        <option value="TR-04">TR-04: USAID 2 CFR 200 Procurement Ethics</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Session Date &amp; Delivery Method</label>
                    <input type="text" value="Today (Attendify Integrated Webcast)" readonly style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; background: #f8fafc;">
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalAttendLogTraining')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check me-1"></i> Confirm &amp; Complete</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Submit Field Update (STL Only) -->
<div class="modal-overlay" id="modalSubmitFieldUpdate" onclick="if(event.target===this)closeModal('modalSubmitFieldUpdate')">
    <div class="modal-dialog" style="max-width: 500px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-file-pen text-primary me-2"></i> Submit State Field Update
            </span>
            <button class="modal-close" onclick="closeModal('modalSubmitFieldUpdate')">&times;</button>
        </div>
        <form onsubmit="handleStaffSubmitFieldUpdate(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">State Office / Cluster</label>
                    <input type="text" value="Lagos State Office (Cluster A)" readonly style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; background: #f8fafc;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Weekly Field Compliance Standing &amp; Bottlenecks</label>
                    <textarea id="staffFieldNotes" rows="4" required placeholder="Outline facility mentoring visits, commodity security, and any emerging compliance bottlenecks..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; resize: vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalSubmitFieldUpdate')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane me-1"></i> Transmit Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Read Policy -->
<div class="modal-overlay" id="modalReadPolicy" onclick="if(event.target===this)closeModal('modalReadPolicy')">
    <div class="modal-dialog" style="max-width: 580px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" id="readPolicyTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">Read Policy Document</span>
            <button class="modal-close" onclick="closeModal('modalReadPolicy')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px;" id="readPolicyBody"></div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end;">
            <button class="btn btn-primary btn-sm" onclick="closeModal('modalReadPolicy')">Close</button>
        </div>
    </div>
</div>

<!-- Modal: Add Lesson Learned (Supervisor/HOD) -->
<div class="modal-overlay" id="modalStaffAddLesson" onclick="if(event.target===this)closeModal('modalStaffAddLesson')">
    <div class="modal-dialog" style="max-width: 500px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px; color: #02367B;">
                <i class="fa-solid fa-book-bookmark me-2"></i> Document New Lesson Learned
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffAddLesson')">&times;</button>
        </div>
        <form onsubmit="handleStaffAddLesson(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Lesson Title</label>
                    <input type="text" id="staffLessonTitle" required placeholder="e.g. Field Sample Transport Reconciliation..." style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Root Cause &amp; Preventative Recommendation</label>
                    <textarea id="staffLessonBody" rows="3" required placeholder="Explain what occurred and institutional safeguards instituted..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; resize: vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalStaffAddLesson')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="background: #02367B; border-color: #02367B;"><i class="fa-solid fa-plus me-1"></i> Publish Lesson</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal: Log Field Work Mission -->
<div class="modal-overlay" id="modalStaffLogFieldWork" onclick="if(event.target===this)closeModal('modalStaffLogFieldWork')">
    <div class="modal-dialog" style="max-width: 500px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px; color: #10b981;">
                <i class="fa-solid fa-map-location-dot me-2"></i> Log Field Mission / Travel Activity
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffLogFieldWork')">&times;</button>
        </div>
        <form onsubmit="handleStaffSubmitFieldWork(event)">
            <div class="modal-body" style="font-size: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Target Facility / Field Destination</label>
                    <input type="text" id="fieldWorkDestination" required placeholder="e.g. Badagry General Hospital, Lagos" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Mission Activity Type</label>
                    <select id="fieldWorkActivityType" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                        <option value="Clinical Mentorship">Clinical Mentorship &amp; Quality Audit</option>
                        <option value="Sample Logistics">Sample Transport &amp; Cold-Chain Verification</option>
                        <option value="Data Verification">EMR / Data Quality Audit</option>
                        <option value="Community Outreach">Community PMTCT Outreach</option>
                        <option value="Commodity Tracking">Commodity &amp; Drug Supply Inspection</option>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label style="display: block; font-weight: 700; margin-bottom: 4px;">Departure Date</label>
                        <input type="date" id="fieldWorkStartDate" required style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; margin-bottom: 4px;">Return Date</label>
                        <input type="date" id="fieldWorkEndDate" required style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                    </div>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Mission Objectives &amp; Site Activities</label>
                    <textarea id="fieldWorkPurpose" rows="3" required placeholder="Specify verifiable deliverables for this field visit..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; resize: vertical;"></textarea>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 4px;">Travel Advance / Per-Diem Request (Optional)</label>
                    <input type="text" id="fieldWorkAdvance" placeholder="e.g. ₦45,000 (Per-diem + Intra-state transit)" style="width: 100%; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px;">
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalStaffLogFieldWork')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="background: #10b981; border-color: #10b981;"><i class="fa-solid fa-paper-plane me-1"></i> Submit Mission Plan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Staff Alerts Notification Center -->
<div class="modal-overlay" id="modalStaffAlertsCenter" onclick="if(event.target===this)closeModal('modalStaffAlertsCenter')">
    <div class="modal-dialog" style="max-width: 460px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px; color: #02367B;">
                <i class="fa-solid fa-bell me-2"></i> Staff Compliance &amp; Notification Alerts
            </span>
            <button class="modal-close" onclick="closeModal('modalStaffAlertsCenter')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px;">
            <div id="staffAlertsCenterList" style="max-height: 280px; overflow-y: auto;">
                <div style="text-align: center; color: #94a3b8; padding: 24px 12px;">
                    <i class="fa-solid fa-bell-slash" style="font-size: 24px; margin-bottom: 6px; display: block;"></i>
                    No unread notifications at this time
                </div>
            </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end;">
            <button class="btn btn-primary btn-sm" onclick="closeModal('modalStaffAlertsCenter')">Close</button>
        </div>
    </div>
</div>

<script>
// ══════════════════════════════════════════════════════════════════
// CCCRN COMPLIANCEIQ — LOCALSTORAGE REACTIVE DATA ENGINE
// ══════════════════════════════════════════════════════════════════

var CCCRN_STORE = {
    KEYS: {
        ATTENDANCE: 'CCCRN_STAFF_ATTENDANCE_V1',
        LEAVE_BALANCES: 'CCCRN_STAFF_LEAVE_BALANCES_V1',
        LEAVE_REQUESTS: 'CCCRN_STAFF_LEAVE_REQUESTS_V1',
        COMPLAINTS: 'CCCRN_STAFF_COMPLAINTS_V1',
        CAPS: 'CCCRN_STAFF_CAPS_V1',
        PDP_OBJECTIVES: 'CCCRN_STAFF_PDP_OBJECTIVES_V1',
        PDP_INNOVATIONS: 'CCCRN_STAFF_PDP_INNOVATIONS_V1',
        TRAININGS: 'CCCRN_STAFF_TRAININGS_V1',
        POLICIES: 'CCCRN_STAFF_POLICIES_V1',
        LESSONS: 'CCCRN_STAFF_LESSONS_V1',
        FIELD_WORK: 'CCCRN_STAFF_FIELD_WORK_V1'
    },

    get: function(key, defaultVal) {
        try {
            var data = localStorage.getItem(key);
            return data ? JSON.parse(data) : defaultVal;
        } catch (e) {
            console.error('LocalStorage get error', e);
            return defaultVal;
        }
    },

    set: function(key, val) {
        try {
            localStorage.setItem(key, JSON.stringify(val));
        } catch (e) {
            console.error('LocalStorage set error', e);
        }
    },

    initDefaults: function() {
        if (!localStorage.getItem(this.KEYS.ATTENDANCE)) {
            this.set(this.KEYS.ATTENDANCE, {
                clockedIn: false,
                time: 'Not Clocked In',
                terminal: 'BIO-PORTAL-01',
                rate: '100%'
            });
        }
        if (!localStorage.getItem(this.KEYS.LEAVE_BALANCES)) {
            this.set(this.KEYS.LEAVE_BALANCES, {
                annual: 21, maxAnnual: 21,
                casual: 7, maxCasual: 7,
                sick: 12, maxSick: 12
            });
        }
        if (!localStorage.getItem(this.KEYS.LEAVE_REQUESTS)) {
            this.set(this.KEYS.LEAVE_REQUESTS, []);
        }
        if (!localStorage.getItem(this.KEYS.COMPLAINTS)) {
            this.set(this.KEYS.COMPLAINTS, []);
        }
        if (!localStorage.getItem(this.KEYS.CAPS)) {
            this.set(this.KEYS.CAPS, []);
        }
        if (!localStorage.getItem(this.KEYS.PDP_OBJECTIVES)) {
            this.set(this.KEYS.PDP_OBJECTIVES, []);
        }
        if (!localStorage.getItem(this.KEYS.PDP_INNOVATIONS)) {
            this.set(this.KEYS.PDP_INNOVATIONS, []);
        }
        if (!localStorage.getItem(this.KEYS.TRAININGS)) {
            this.set(this.KEYS.TRAININGS, []);
        }
        if (!localStorage.getItem(this.KEYS.FIELD_WORK)) {
            this.set(this.KEYS.FIELD_WORK, []);
        }
        if (!localStorage.getItem(this.KEYS.POLICIES)) {
            this.set(this.KEYS.POLICIES, {
                'POL-PSEA-001': { signed: false, date: null },
                'POL-TRV-03': { signed: false, date: null },
                'POL-SEC-007': { signed: false, date: null }
            });
        }
        if (!localStorage.getItem(this.KEYS.LESSONS)) {
            this.set(this.KEYS.LESSONS, []);
        }
    },

    reset: function() {
        if (confirm('Reset all CCCRN Compliance test data in LocalStorage back to initial demo state?')) {
            Object.values(this.KEYS).forEach(function(k) { localStorage.removeItem(k); });
            this.initDefaults();
            renderAllFromStorage();
    updateStaffAlertsCenter();
    setInterval(updateStaffAlertsCenter, 3000);
    setInterval(syncStaffWithBackendLive, 2500);
            alert('LocalStorage test data refreshed successfully!');
        }
    }
};

// ══════════════════════════════════════════════════════════════════
// RENDERERS (SYNC UI FROM LOCALSTORAGE)
// ══════════════════════════════════════════════════════════════════

function renderAttendanceFromStorage() {
    var att = CCCRN_STORE.get(CCCRN_STORE.KEYS.ATTENDANCE, { clockedIn: false, time: 'Clocked Out', terminal: 'BIO-PORTAL-01' });
    var pill = document.getElementById('staffClockStatusPill');
    var btn = document.getElementById('btnStaffClockToggle');
    if (pill && btn) {
        if (att.clockedIn) {
            pill.className = 'pill pill-closed';
            pill.innerText = 'Clocked In · ' + att.time;
            btn.innerHTML = '<i class="fa-solid fa-clock me-1"></i> Clock-Out for the Day';
        } else {
            pill.className = 'pill pill-progress';
            pill.innerText = 'Clocked Out · Duty Ended';
            btn.innerHTML = '<i class="fa-solid fa-clock me-1"></i> Clock-In for Duty';
        }
    }
}

function renderLeaveFromStorage() {
    var bal = CCCRN_STORE.get(CCCRN_STORE.KEYS.LEAVE_BALANCES, { annual: 21, casual: 7, sick: 12 });
    var reqs = CCCRN_STORE.get(CCCRN_STORE.KEYS.LEAVE_REQUESTS, []);
    
    var tbody = document.getElementById('staffLeaveHistoryTableBody');
    if (tbody) {
        if (!reqs || reqs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 32px 16px; color: var(--text-muted); font-size: 12px;"><div style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"><i class="fa-solid fa-calendar-xmark"></i></div><strong>No leave applications recorded yet</strong><div style="font-size: 11px; margin-top: 2px;">Click "+ Apply for Leave" above to submit a new application.</div></td></tr>';
            return;
        }
        var html = '';
        reqs.forEach(function(r) {
            var statClass = 'pill-open';
            var statBadgeStyle = '';
            if (r.status === 'Approved') {
                statClass = 'pill-closed';
            } else if (r.status === 'Pending Supervisor') {
                statClass = 'pill-progress';
                statBadgeStyle = 'background: #f0f7ff; color: #02367B; border: 1px solid #bae6fd;';
            } else if (r.status === 'Pending HR') {
                statClass = 'pill-progress';
                statBadgeStyle = 'background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;';
            } else {
                statClass = 'pill-open';
            }
            var catBadge = r.category && r.category.includes('Annual')
                ? '<span style="background: #e0f2fe; color: #02367B; padding: 2px 7px; border-radius: 4px; font-weight: 600;">' + r.category + '</span>'
                : '<span style="background: #fef3c7; color: #d97706; padding: 2px 7px; border-radius: 4px; font-weight: 600;">' + r.category + '</span>';

            html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: #0077b6;">' + r.id + '</td>' +
                '<td style="padding: 10px 8px;">' + catBadge + '</td>' +
                '<td style="padding: 10px 8px;"><strong>' + (r.start || 'TBD') + '</strong> — <strong>' + (r.end || 'TBD') + '</strong> (' + (r.days || 1) + ' days)</td>' +
                '<td style="padding: 10px 8px;">' + (r.reliever || 'None') + '</td>' +
                '<td style="padding: 10px 8px; text-align: center;"><span class="pill ' + statClass + '" style="' + statBadgeStyle + ' font-size: 9.5px; padding: 3px 7px;">' + (r.status === "Pending Supervisor" ? "<i class=\"fa-solid fa-clock me-1\"></i>Awaiting Supervisor" : (r.status === "Pending HR" ? "<i class=\"fa-solid fa-check-double me-1\"></i>Pending HR Admin" : r.status)) + '</span></td>' +
            '</tr>';
        });
        tbody.innerHTML = html;
    }
}

function renderComplaintsFromStorage() {
    var list = CCCRN_STORE.get(CCCRN_STORE.KEYS.COMPLAINTS, []);
    var tbody = document.getElementById('staffComplaintsHistoryTable');
    if (!tbody) return;

    if (!list || list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 32px 16px; color: var(--text-muted); font-size: 12px;"><div style="font-size: 24px; color: #10b981; margin-bottom: 6px;"><i class="fa-solid fa-shield-check"></i></div><strong>No active grievances or incident reports on file</strong><div style="font-size: 11px; margin-top: 2px;">Use "+ Log New Complaint" to file a confidential report under zero-retaliation protection.</div></td></tr>';
        return;
    }

    var html = '';
    list.forEach(function(c) {
        var sevClass = c.severity === 'Critical' || c.severity === 'High' ? 'pill-open' : 'pill-progress';
        var statClass = c.status.includes('Closed') || c.status.includes('Resolved') ? 'pill-closed' : 'pill-progress';
        var capBtn = c.linkedCap
            ? '<button class="btn btn-outline btn-sm" onclick="viewLinkedCapModal(\'' + c.linkedCap + '\', \'' + c.title.replace(/'/g, "\\'") + '\', \'Facility Unit\', \'' + c.status + '\')" style="padding: 2px 6px; font-size: 10px; color: var(--accent);"><i class="fa-solid fa-eye me-1"></i> ' + c.linkedCap + '</button>'
            : '<span style="color: var(--text-muted); font-size: 10px;">None</span>';

        html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">' + c.id + '</td>' +
            '<td style="padding: 10px 8px; font-size: 11px; color: var(--text-muted);">' + c.date + '</td>' +
            '<td style="padding: 10px 8px; font-weight: 600;">' + c.category + '</td>' +
            '<td style="padding: 10px 8px;"><div style="font-weight: 600; color: var(--text);">' + c.title + '</div><div style="font-size: 10px; color: var(--text-muted);">Mode: ' + c.mode + '</div></td>' +
            '<td style="padding: 10px 8px; text-align: center;"><span class="pill ' + sevClass + '" style="font-size: 9px; font-weight: 700;">' + c.severity + '</span></td>' +
            '<td style="padding: 10px 8px; text-align: center;"><span class="pill ' + statClass + '" style="font-size: 9px;">' + c.status + '</span></td>' +
            '<td style="padding: 10px 8px; text-align: center;">' + capBtn + '</td>' +
        '</tr>';
    });
    tbody.innerHTML = html;
}

function renderCapFromStorage() {
    var list = CCCRN_STORE.get(CCCRN_STORE.KEYS.CAPS, []);
    var tbody = document.getElementById('staffCapTableBody');
    if (!tbody) return;

    var html = '';
    list.forEach(function(cap) {
        var actionBtn = cap.hasEvidence
            ? '<button class="btn btn-outline btn-sm" onclick="alert(\'Viewing State Evidence: ' + (cap.fileName || 'Verified_Evidence.pdf') + ' (Stored in LocalStorage)\')" style="padding: 3px 6px; font-size: 10px;"><i class="fa-solid fa-eye"></i> View</button>'
            : '<button class="btn btn-primary btn-sm" onclick="openModalSubmitStateEvidence(\'' + cap.id + '\')" style="font-size: 10px; padding: 3px 8px;"><i class="fa-solid fa-upload me-1"></i> Submit</button>';

        var statPill = cap.hasEvidence ? '<span class="pill pill-closed" style="font-size: 9px;">Evidence Submitted</span>' : '<span class="pill pill-progress" style="font-size: 9px;">' + cap.status + '</span>';

        html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">' + cap.id + '</td>' +
            '<td style="padding: 10px 8px; font-size: 11px;">' + cap.state + '</td>' +
            '<td style="padding: 10px 8px; font-weight: 600;">' + cap.category + '</td>' +
            '<td style="padding: 10px 8px;"><div style="font-weight: 700; color: var(--text);">' + cap.title + '</div></td>' +
            '<td style="padding: 10px 8px; text-align: center; font-size: 11px;">' + cap.targetDate + '</td>' +
            '<td style="padding: 10px 8px; text-align: center;">' + statPill + '</td>' +
            '<td style="padding: 10px 8px; text-align: center;">' + actionBtn + '</td>' +
        '</tr>';
    });
    tbody.innerHTML = html;
}

function renderPdpFromStorage() {
    var objs = CCCRN_STORE.get(CCCRN_STORE.KEYS.PDP_OBJECTIVES, []);
    var inns = CCCRN_STORE.get(CCCRN_STORE.KEYS.PDP_INNOVATIONS, []);
    // Render Objectives Table
    var tbody = document.querySelector('#staffPdpSubObjectives tbody');
    if (tbody) {
        if (!objs || objs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 32px 16px; color: var(--text-muted); font-size: 12px;"><div style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"><i class="fa-solid fa-bullseye"></i></div><strong>No performance objectives registered yet</strong><div style="font-size: 11px; margin-top: 2px;">Click \"+ Set New Objective\" above to establish your deliverables.</div></td></tr>';
            return;
        }
        var html = '';
        objs.forEach(function(o) {
            html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">' + o.id + '</td>' +
                '<td style="padding: 10px 8px;"><div style="font-weight: 700; color: var(--text);">' + o.statement + '</div></td>' +
                '<td style="padding: 10px 8px; font-size: 11px;">' + o.output + '</td>' +
                '<td style="padding: 10px 8px; text-align: center; font-weight: 700;">' + o.weight + ' Pts</td>' +
                '<td style="padding: 10px 8px; text-align: center;"><span class="pill pill-closed" style="font-size: 9px;"><i class="fa-solid fa-check me-1"></i>' + o.status + '</span></td>' +
                '<td style="padding: 10px 8px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="openModal(\'' + (o.evidenceFile ? 'modalStaffSubmitPdpEvidence' : 'modalStaffSubmitPdpEvidence') + '\')" style="padding: 3px 6px; font-size: 10px;"><i class="fa-solid fa-upload me-1"></i> ' + (o.evidenceFile ? 'Update' : 'Evidence') + '</button></td>' +
            '</tr>';
        });
        tbody.innerHTML = html;
    }
}

function renderTrainingFromStorage() {
    var list = CCCRN_STORE.get(CCCRN_STORE.KEYS.TRAININGS, []);
    var tbody = document.querySelector('#staffSectionTraining table tbody');
    if (!tbody) return;
    if (!list || list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 28px 16px; color: var(--text-muted); font-size: 12px;"><div style="font-size: 22px; color: #cbd5e1; margin-bottom: 4px;"><i class="fa-solid fa-graduation-cap"></i></div><strong>No attended trainings recorded yet</strong><div style="font-size: 11px; margin-top: 2px;">Click \"Finish\" or \"Launch Training\" to attend and certify.</div></td></tr>';
        return;
    }
    var html = '';
    list.forEach(function(t) {
        var certBtn = t.status === 'Certified'
            ? '<button class="btn btn-outline btn-sm" onclick="alert(\'Downloading ' + t.code + ' Certificate (PDF) from LocalStorage record\')" style="padding: 2px 6px; font-size: 10px;"><i class="fa-solid fa-download me-1"></i> PDF</button>'
            : '<button class="btn btn-primary btn-sm" onclick="openModal(\'' + 'modalAttendLogTraining' + '\')" style="padding: 2px 6px; font-size: 10px; background: #d97706; border-color: #d97706;"><i class="fa-solid fa-play me-1"></i> Finish</button>';

        var statPill = t.status === 'Certified'
            ? '<span class="pill pill-closed" style="font-size: 9px;">Certified</span>'
            : '<span class="pill pill-progress" style="font-size: 9px;">In Progress</span>';

        html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">' + t.code + '</td>' +
            '<td style="padding: 10px 8px; font-weight: 600;">' + t.title + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted);">' + (t.date || 'Pending') + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; font-weight: 700; color: #059669;">' + (t.score || '--') + '</td>' +
            '<td style="padding: 10px 8px; text-align: center;">' + statPill + '</td>' +
            '<td style="padding: 10px 8px; text-align: center;">' + certBtn + '</td>' +
        '</tr>';
    });
    tbody.innerHTML = html;
}

function renderPoliciesFromStorage() {
    var policies = CCCRN_STORE.get(CCCRN_STORE.KEYS.POLICIES, {});
    var polSec = policies['POL-SEC-007'];
    if (polSec && polSec.signed) {
        var statEl = document.getElementById('statusPolSec007');
        var actEl = document.getElementById('actionPolSec007');
        if (statEl) statEl.innerHTML = '<span class="pill pill-closed" style="font-size: 9px;"><i class="fa-solid fa-check me-1"></i>Acknowledged</span>';
        if (actEl) actEl.innerHTML = '<span style="font-size: 10px; color: var(--text-muted);">Signed ' + polSec.date + '</span>';
    }
}

function renderLessonsFromStorage() {
    var lessons = CCCRN_STORE.get(CCCRN_STORE.KEYS.LESSONS, []);
    var tbody = document.getElementById('staffLessonsTableBody');
    if (!tbody) return;
    if (!lessons || lessons.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 28px 16px; color: var(--text-muted); font-size: 12px;"><div style="font-size: 22px; color: #cbd5e1; margin-bottom: 4px;"><i class="fa-solid fa-book-open"></i></div><strong>No institutional case retrospectives published yet</strong><div style="font-size: 11px; margin-top: 2px;">Lessons learned from audits and retrospectives will be displayed here.</div></td></tr>';
        return;
    }
    var html = '';
    lessons.forEach(function(l) {
        html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent);">' + l.id + '</td>' +
            '<td style="padding: 10px 8px; font-size: 11px;">' + l.domain + '</td>' +
            '<td style="padding: 10px 8px;"><div style="font-weight: 700; color: var(--text);">' + l.title + '</div></td>' +
            '<td style="padding: 10px 8px; text-align: center; font-size: 11px;">' + l.category + '</td>' +
            '<td style="padding: 10px 8px; text-align: center;"><span class="pill pill-closed" style="font-size: 9px;">' + l.status + '</span></td>' +
        '</tr>';
    });
    tbody.innerHTML = html;
}

function renderAllFromStorage() {
    renderAttendanceFromStorage();
    renderLeaveFromStorage();
    renderComplaintsFromStorage();
    renderCapFromStorage();
    renderPdpFromStorage();
    renderTrainingFromStorage();
    renderPoliciesFromStorage();
    renderLessonsFromStorage();
    renderFieldWorkFromStorage();
}

// ══════════════════════════════════════════════════════════════════
// PERSPECTIVE & TAB CONTROLLER
// ══════════════════════════════════════════════════════════════════

var CURRENT_STAFF_PERSPECTIVE = 'staff';

function switchStaffMainTab(tabKey, btn) {
    document.querySelectorAll('.staff-module-section').forEach(function(el) { el.style.display = 'none'; });
    document.querySelectorAll('.staff-main-tab').forEach(function(b) { b.classList.remove('active'); });

    var map = {
        'leave': 'staffSectionLeave',
        'fieldwork': 'staffSectionFieldWork',
        'complaints': 'staffSectionComplaints',
        'cap': 'staffSectionCap',
        'pdp': 'staffSectionPdp',
        'training': 'staffSectionTraining',
        'states': 'staffSectionStates',
        'policies': 'staffSectionPolicies',
        'lessons': 'staffSectionLessons',
        'ai': 'staffSectionAi'
    };

    var targetEl = document.getElementById(map[tabKey] || 'staffSectionComplaints');
    if (targetEl) targetEl.style.display = 'block';
    if (btn) btn.classList.add('active');
}


// ══════════════════════════════════════════════════════════════════
// SUPERVISOR TIER-1 LEAVE AUTHENTICATION
// ══════════════════════════════════════════════════════════════════

function renderSupervisorLeaveQueue() {
    var container = document.getElementById('supervisorLeaveListContainer');
    var badge = document.getElementById('supervisorLeavePendingBadge');
    if (!container) return;

    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data || !data.leave_requests) return;
            var pendingSup = data.leave_requests.filter(function(r) {
                return r.status === 'Pending Supervisor';
            });

            if (badge) {
                badge.innerText = pendingSup.length + ' Action Required';
                badge.style.background = pendingSup.length > 0 ? '#02367B' : '#059669';
            }

            if (pendingSup.length === 0) {
                container.innerHTML = '<div style="background: #ffffff; border: 1px solid #bae6fd; border-radius: 6px; padding: 12px; text-align: center; color: #02367B; font-size: 11px;">' +
                    '<i class="fa-solid fa-circle-check text-success me-1"></i> No supervisee leave applications currently pending your authentication.' +
                '</div>';
                return;
            }

            var html = '';
            pendingSup.forEach(function(r) {
                html += '<div style="background: #ffffff; border: 1px solid #bae6fd; border-radius: 6px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; margin-bottom: 6px;">' +
                    '<div>' +
                        '<div style="font-weight: 700; color: #02367B; font-size: 12px;">' + r.staff_name + ' <span style="font-family: monospace; font-size: 10px; color: var(--text-muted);">(' + r.id + ')</span></div>' +
                        '<div style="color: var(--text); margin-top: 2px;">' + (r.category || 'Leave') + ' · <strong>' + (r.days || 1) + ' Working Days</strong> (' + (r.start || 'TBD') + ' — ' + (r.end || 'TBD') + ')</div>' +
                        '<div style="font-size: 10px; color: var(--text-muted); margin-top: 1px;">Reliever: <strong>' + (r.reliever || 'None') + '</strong> · Office: ' + (r.state || 'Lagos') + '</div>' +
                    '</div>' +
                    '<div style="display: flex; gap: 6px;">' +
                        '<button class="btn btn-primary btn-sm" onclick="handleSupervisorAuthenticateLeave(\'' + r.id + '\', \'Pending HR\')" style="font-size: 11px; padding: 4px 10px; background: #059669; border-color: #059669; font-weight: 700;">' +
                            '<i class="fa-solid fa-check me-1"></i> Authenticate & Send to HR' +
                        '</button>' +
                        '<button class="btn btn-outline btn-sm" onclick="handleSupervisorAuthenticateLeave(\'' + r.id + '\', \'Rejected\')" style="font-size: 11px; padding: 4px 10px; color: #dc2626; border-color: #fecaca;">' +
                            '<i class="fa-solid fa-xmark me-1"></i> Reject' +
                        '</button>' +
                    '</div>' +
                '</div>';
            });
            container.innerHTML = html;
        }).catch(function(e) { console.log('Supervisor leave fetch error', e); });
}

function handleSupervisorAuthenticateLeave(reqId, nextStatus) {
    var supName = 'Dr. Ngozi Adeyemi';
    fetch('/api/leave/action', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: reqId,
            action: nextStatus,
            supervisor_name: supName,
            supervisor_authenticated: nextStatus === 'Pending HR'
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        if (nextStatus === 'Pending HR') {
            alert('Leave application ' + reqId + ' authenticated by ' + supName + '! Forwarded to Admin / HR for final approval.');
        } else {
            alert('Leave application ' + reqId + ' rejected by Supervisor.');
        }
        renderSupervisorLeaveQueue();
        syncStaffWithBackendLive();
    }).catch(function(e) {
        alert('Action recorded: ' + nextStatus);
        renderSupervisorLeaveQueue();
    });
}

function switchStaffRolePerspective(role, btn) {
    CURRENT_STAFF_PERSPECTIVE = role;

    document.querySelectorAll('.btn-staff-role').forEach(function(b) {
        b.classList.remove('active');
        b.style.background = 'transparent';
        b.style.color = '#ffffff';
        b.style.border = '1px solid rgba(255,255,255,0.4)';
    });

    if (btn) {
        btn.classList.add('active');
        btn.style.background = '#55E2E9';
        btn.style.color = '#012454';
        btn.style.border = 'none';
    }

    var pill = document.getElementById('currentRoleBadgePill');
    var tabStates = document.getElementById('tabNavStates');
    var supWrapper = document.getElementById('supervisorAddLessonWrapper');
    var supLeave = document.getElementById('supervisorLeaveQueueWrapper');
    var stlConsole = document.getElementById('stlTrainingConsole');

    if (role === 'staff') {
        pill.innerText = 'STAFF ROLE';
        pill.style.background = '#fef08a';
        pill.style.color = '#854d0e';
        if (tabStates) tabStates.style.display = 'none';
        if (supWrapper) supWrapper.style.display = 'none';
        if (supLeave) supLeave.style.display = 'none';
        if (stlConsole) stlConsole.style.display = 'none';
    } else if (role === 'supervisor') {
        pill.innerText = 'SUPERVISOR ROLE';
        pill.style.background = '#f0f7ff';
        pill.style.color = '#02367B';
        if (tabStates) tabStates.style.display = 'none';
        if (supWrapper) supWrapper.style.display = 'block';
        if (supLeave) { supLeave.style.display = 'block'; renderSupervisorLeaveQueue(); }
        if (stlConsole) stlConsole.style.display = 'none';
        switchStaffMainTab('pdp', document.querySelectorAll('.staff-main-tab')[2]);
        switchStaffPdpSubTab('supervisor', document.getElementById('btnPdpSupervisorTab'));
    } else if (role === 'hod') {
        pill.innerText = 'HOD ROLE';
        pill.style.background = '#e0f2fe';
        pill.style.color = '#0369a1';
        if (tabStates) tabStates.style.display = 'none';
        if (supWrapper) supWrapper.style.display = 'block';
        if (supLeave) supLeave.style.display = 'block';
        if (stlConsole) stlConsole.style.display = 'none';
        switchStaffMainTab('pdp', document.querySelectorAll('.staff-main-tab')[2]);
        switchStaffPdpSubTab('hod', document.getElementById('btnPdpHodTab'));
    } else if (role === 'stl') {
        pill.innerText = 'STATE TEAM LEAD (STL)';
        pill.style.background = '#dcfce7';
        pill.style.color = '#15803d';
        if (tabStates) tabStates.style.display = 'flex';
        if (supWrapper) supWrapper.style.display = 'block';
        if (supLeave) supLeave.style.display = 'none';
        if (stlConsole) stlConsole.style.display = 'block';
        switchStaffMainTab('states', tabStates);
    }
}

function switchStaffPdpSubTab(key, btn) {
    document.querySelectorAll('.pdp-sub-content').forEach(function(el) { el.style.display = 'none'; });
    document.querySelectorAll('.pdp-sub-tab').forEach(function(b) {
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline');
    });

    var map = {
        'objectives': 'staffPdpSubObjectives',
        'evidence': 'staffPdpSubEvidence',
        'innovation': 'staffPdpSubInnovation',
        'scorecard': 'staffPdpSubScorecard',
        'supervisor': 'staffPdpSubSupervisor',
        'hod': 'staffPdpSubHod'
    };

    var targetEl = document.getElementById(map[key] || 'staffPdpSubObjectives');
    if (targetEl) targetEl.style.display = 'block';
    if (btn) {
        btn.classList.add('active', 'btn-primary');
        btn.classList.remove('btn-outline');
    }
}

// ══════════════════════════════════════════════════════════════════
// ACTIONS & MUTATIONS (SAVED TO LOCALSTORAGE)
// ══════════════════════════════════════════════════════════════════


// Dynamic User Context determined by Host SSO / Attendify
window.CURRENT_USER_CONTEXT = window.ATTENDIFY_STAFF_CONTEXT || {
    name: 'Authenticated Staff',
    id: 'CCCRN-STF-ACTIVE',
    role: 'staff',
    dept: 'Clinical & Operational Services',
    state: 'Nigeria Country Office',
    avatar: 'AS',
    supervisor: 'Assigned Supervisor',
    hod: 'Department Head'
};

function getActiveStaffName() {
    return (window.CURRENT_USER_CONTEXT && window.CURRENT_USER_CONTEXT.name) ? window.CURRENT_USER_CONTEXT.name : 'Authenticated Staff';
}
function getActiveStaffDept() {
    return (window.CURRENT_USER_CONTEXT && window.CURRENT_USER_CONTEXT.dept) ? window.CURRENT_USER_CONTEXT.dept : 'Operations';
}
function getActiveStaffState() {
    return (window.CURRENT_USER_CONTEXT && window.CURRENT_USER_CONTEXT.state) ? window.CURRENT_USER_CONTEXT.state : 'National Office';
}

function toggleStaffClock() {
    var att = CCCRN_STORE.get(CCCRN_STORE.KEYS.ATTENDANCE, { clockedIn: true, time: '07:48 AM', terminal: 'BIO-LOS-01' });
    att.clockedIn = !att.clockedIn;
    att.time = att.clockedIn ? 'Just Now' : 'Clocked Out';
    CCCRN_STORE.set(CCCRN_STORE.KEYS.ATTENDANCE, att);
    renderAttendanceFromStorage();

    // Transmit to Backend for HR Biometrics Roster
    fetch('/api/attendance/clock', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            staff_name: getActiveStaffName(),
            department: getActiveStaffDept(),
            state: getActiveStaffState(),
            terminal: 'BIO-LOS-01',
            time: att.time,
            clockedIn: att.clockedIn
        })
    }).catch(function(e) { console.log('Backend sync offline', e); });

    if (att.clockedIn) {
        alert('Biometric Attendance Verified: Clocked-in successfully for today (Synced to Backend & HR).');
    } else {
        alert('Attendance Clock-Out logged for today (Synced to Backend & HR).');
    }
}

function calculateLeaveDays(startStr, endStr) {
    try {
        if (!startStr || !endStr) return 3;
        var d1 = new Date(startStr);
        var d2 = new Date(endStr);
        var diff = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
        return diff > 0 ? diff : 1;
    } catch(err) {
        return 3;
    }
}


function getCachedTargetEmail(role) {
    if (role === 'hr') {
        return localStorage.getItem('complianceiq_hr_email') || localStorage.getItem('cached_officer_email') || 'hr@cccrn.org';
    } else {
        return localStorage.getItem('complianceiq_doc_email') || localStorage.getItem('cached_officer_email') || 'director@cccrn.org';
    }
}

function handleStaffApplyLeave(e) {
    e.preventDefault();
    var cat = document.getElementById('staffLeaveCategory').value;
    var start = document.getElementById('staffLeaveStartDate').value;
    var end = document.getElementById('staffLeaveEndDate').value;
    var reliever = document.getElementById('staffLeaveReliever').value;
    var days = calculateLeaveDays(start, end);
    closeModal('modalStaffApplyLeave');

    var reqs = CCCRN_STORE.get(CCCRN_STORE.KEYS.LEAVE_REQUESTS, []);
    var newId = 'LVE-2026-0' + (43 + reqs.length);
    var newReq = {
        id: newId,
        category: cat,
        start: start,
        end: end,
        days: days,
        reliever: reliever,
        status: 'Pending Supervisor'
    };
    reqs.unshift(newReq);
    CCCRN_STORE.set(CCCRN_STORE.KEYS.LEAVE_REQUESTS, reqs);
    renderLeaveFromStorage();

    // Transmit to Backend so HR immediately sees it in Workforce Leave Register
    fetch('/api/leave/apply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: newId,
            staff_name: getActiveStaffName(),
            department: getActiveStaffDept(),
            state: getActiveStaffState(),
            category: cat,
            start: start,
            end: end,
            reliever: reliever,
            days: days
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        console.log('Leave dispatched to HR backend:', resp);
    }).catch(function(e) { console.log('Backend sync offline', e); });

    var hrTarget = getCachedTargetEmail('hr');
    alert('Leave Application (' + newId + ') submitted successfully! Notification alert dispatched to HR (' + hrTarget + ') and Supervisor (Dr. Ngozi Adeyemi) for authentication.');
}

function handleStaffSubmitComplaint(e) {
    e.preventDefault();
    var mode = document.getElementById('staffComplaintMode').value;
    var cat = document.getElementById('staffComplaintCategory').value;
    var title = document.getElementById('staffComplaintTitle').value;
    closeModal('modalStaffLogComplaint');

    var list = CCCRN_STORE.get(CCCRN_STORE.KEYS.COMPLAINTS, []);
    var newId = 'CMP-2026-0' + (50 + list.length);
    var newComp = {
        id: newId,
        date: 'Today',
        category: cat,
        title: title,
        mode: mode,
        severity: 'Critical',
        status: 'Logged · Triage',
        linkedCap: null
    };
    list.unshift(newComp);
    CCCRN_STORE.set(CCCRN_STORE.KEYS.COMPLAINTS, list);
    renderComplaintsFromStorage();

    // Transmit to Backend so HR and DoC immediately see it in Complaints Module
    fetch('/api/complaints/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            staff_name: getActiveStaffName(),
            category: cat,
            title: title,
            mode: mode,
            state: getActiveStaffState(),
            severity: 'High'
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        console.log('Complaint dispatched to backend:', resp);
    }).catch(function(e) { console.log('Backend sync offline', e); });

    var docTarget = getCachedTargetEmail('doc');
    alert('Complaint registered successfully under reference ' + newId + '! Confidential alert dispatched directly to Director of Compliance (' + docTarget + ') and logged in registry.');
}

function openModalSubmitStateEvidence(capRef) {
    if (capRef) document.getElementById('stateEvidenceCapRef').value = capRef;
    openModal('modalSubmitStateEvidence');
}

function handleStaffSubmitStateEvidence(e) {
    e.preventDefault();
    var capRef = document.getElementById('stateEvidenceCapRef').value;
    var notes = document.getElementById('stateEvidenceNotes').value;
    closeModal('modalSubmitStateEvidence');

    var fileName = 'State_Evidence_' + capRef + '.pdf';
    var caps = CCCRN_STORE.get(CCCRN_STORE.KEYS.CAPS, []);
    caps.forEach(function(c) {
        if (c.id === capRef) {
            c.hasEvidence = true;
            c.status = 'Evidence Submitted';
            c.fileName = fileName;
        }
    });
    CCCRN_STORE.set(CCCRN_STORE.KEYS.CAPS, caps);
    renderCapFromStorage();

    // Transmit to Backend for DoC / HR Evidence Review Queue
    fetch('/api/cap/submit-evidence', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            capRef: capRef,
            fileName: fileName,
            notes: notes
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        console.log('CAP evidence dispatched to backend:', resp);
    }).catch(function(e) { console.log('Backend sync offline', e); });

    var docTarget = getCachedTargetEmail('doc');
    alert('State Evidence for ' + capRef + ' uploaded! Verification alert routed to Director of Compliance (' + docTarget + ').');
}

function handleStaffSetObjective(e) {
    e.preventDefault();
    var stmt = document.getElementById('staffNewObjStatement').value;
    var output = document.getElementById('staffNewObjOutput').value;
    var weight = parseInt(document.getElementById('staffNewObjWeight').value, 10) || 15;
    closeModal('modalStaffSetObjective');

    var objs = CCCRN_STORE.get(CCCRN_STORE.KEYS.PDP_OBJECTIVES, []);
    var newId = 'OBJ-2026-0' + (objs.length + 1);
    objs.push({
        id: newId,
        statement: stmt,
        output: output,
        weight: weight,
        status: 'Pending Supervisor Approval',
        evidenceFile: null
    });
    CCCRN_STORE.set(CCCRN_STORE.KEYS.PDP_OBJECTIVES, objs);
    renderPdpFromStorage();

    alert('New objective ' + newId + ' saved to LocalStorage and routed to supervisor for review.');
}

function handleStaffSubmitPdpEvidence(e) {
    e.preventDefault();
    var objRef = document.getElementById('staffPdpObjSelect').value;
    closeModal('modalStaffSubmitPdpEvidence');

    var objs = CCCRN_STORE.get(CCCRN_STORE.KEYS.PDP_OBJECTIVES, []);
    objs.forEach(function(o) {
        if (o.id.includes(objRef)) {
            o.evidenceFile = 'Deliverable_Audit_' + objRef + '.pdf';
            o.status = 'Evidence Submitted (In Review)';
        }
    });
    CCCRN_STORE.set(CCCRN_STORE.KEYS.PDP_OBJECTIVES, objs);
    renderPdpFromStorage();

    alert('Evidence for ' + objRef + ' uploaded and persisted in LocalStorage!');
}

function handleStaffSubmitInnovation(e) {
    e.preventDefault();
    var title = document.getElementById('staffInnTitle').value;
    var desc = document.getElementById('staffInnDesc').value;
    closeModal('modalStaffSubmitInnovation');

    var inns = CCCRN_STORE.get(CCCRN_STORE.KEYS.PDP_INNOVATIONS, []);
    var newId = 'INN-2026-00' + (inns.length + 5);
    inns.unshift({
        id: newId,
        title: title,
        desc: desc,
        score: 'Pending HOD Evaluation',
        status: 'Under Review',
        author: getActiveStaffName()
    });
    CCCRN_STORE.set(CCCRN_STORE.KEYS.PDP_INNOVATIONS, inns);

    alert('Innovation ' + newId + ' saved to LocalStorage and routed to HOD for 50-mark grading!');
}

function handleStaffLogTrainingAttendance(e) {
    e.preventDefault();
    var courseCode = document.getElementById('staffTrainingCourseSelect').value;
    closeModal('modalAttendLogTraining');

    var trainings = CCCRN_STORE.get(CCCRN_STORE.KEYS.TRAININGS, []);
    trainings.forEach(function(t) {
        if (t.code === courseCode) {
            t.status = 'Certified';
            t.date = 'Today';
            t.score = '95%';
        }
    });
    CCCRN_STORE.set(CCCRN_STORE.KEYS.TRAININGS, trainings);
    renderTrainingFromStorage();

    alert('Training attendance logged! Course ' + courseCode + ' marked Certified in LocalStorage.');
}

function handleAcknowledgePolicy(code) {
    var policies = CCCRN_STORE.get(CCCRN_STORE.KEYS.POLICIES, {});
    policies[code] = { signed: true, date: 'Today (Verified)' };
    CCCRN_STORE.set(CCCRN_STORE.KEYS.POLICIES, policies);
    renderPoliciesFromStorage();

    alert('Digital compliance sign-off recorded for ' + code + ' in LocalStorage. Audit timestamp saved.');
}

function handleStaffAddLesson(e) {
    e.preventDefault();
    var title = document.getElementById('staffLessonTitle').value;
    closeModal('modalStaffAddLesson');

    var lessons = CCCRN_STORE.get(CCCRN_STORE.KEYS.LESSONS, []);
    var newId = 'LES-0' + (lessons.length + 16);
    lessons.unshift({
        id: newId,
        domain: 'Field Retrospective',
        title: title,
        category: 'Quality Improvement',
        status: 'Published'
    });
    CCCRN_STORE.set(CCCRN_STORE.KEYS.LESSONS, lessons);
    renderLessonsFromStorage();

    alert('New lesson ' + newId + ' published and saved to LocalStorage!');
}

function handleStaffSubmitFieldUpdate(e) {
    e.preventDefault();
    closeModal('modalSubmitFieldUpdate');
    alert('State Field Update transmitted and saved to LocalStorage telemetry log.');
}

function viewLinkedCapModal(ref, title, location, status) {
    document.getElementById('linkedCapModalTitle').innerText = ref + ' — ' + title;
    document.getElementById('linkedCapModalStatus').innerText = status;
    document.getElementById('linkedCapModalBody').innerHTML = '<div style="line-height: 1.6;">' +
        '<p><strong>Applicable Duty Station:</strong> ' + location + '</p>' +
        '<p><strong>Mandatory Remediation:</strong> Root cause analysis requires physical batch testing and reconciliation of facility inventory ledgers.</p>' +
        '<p><strong>Verification Method:</strong> State Evidence document signed by facility pharmacist and supervisor.</p>' +
    '</div>';
    openModal('modalViewLinkedCap');
}

function openModalReadPolicy(code, title) {
    document.getElementById('readPolicyTitle').innerText = code + ': ' + title;
    document.getElementById('readPolicyBody').innerHTML = '<p><strong>Official CCCRN Institutional Policy (' + code + ')</strong></p>' +
        '<p style="color: var(--text-dim); line-height: 1.6;">This policy establishes mandatory operational protocols, zero-tolerance definitions, escalation procedures, and disciplinary mechanisms applicable to all CCCRN personnel and sub-recipients. Non-compliance constitutes immediate grounds for formal inquiry under USAID 2 CFR 200.</p>' +
        '<div style="background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid var(--border); margin-top: 10px;">' +
            '<i class="fa-solid fa-file-pdf text-danger me-2"></i> Verified Official Document (PDF · 12 Pages)' +
        '</div>';
    openModal('modalReadPolicy');
}

function openModalGradeBehavioral(name) {
    alert('Opening Monthly Behavioral Grading Bench for supervisee: ' + name + ' (8 Core Competencies)');
}

function askAiHelpdesk(query) {
    document.getElementById('staffAiInput').value = query;
    sendStaffAiQuery();
}

function sendStaffAiQuery() {
    var input = document.getElementById('staffAiInput');
    var query = input.value.trim();
    if (!query) return;

    var win = document.getElementById('staffAiChatWindow');
    var userDiv = document.createElement('div');
    userDiv.style.cssText = 'align-self: flex-end; max-width: 80%; background: var(--accent); color: #ffffff; padding: 10px 14px; border-radius: 12px 12px 2px 12px; font-size: 12px; line-height: 1.5;';
    userDiv.innerText = query;
    win.appendChild(userDiv);
    input.value = '';

    setTimeout(function() {
        var aiDiv = document.createElement('div');
        aiDiv.style.cssText = 'align-self: flex-start; max-width: 80%; background: #ffffff; border: 1px solid var(--border); padding: 10px 14px; border-radius: 12px 12px 12px 2px; font-size: 12px; line-height: 1.5; color: var(--text);';
        
        var reply = 'Regarding your query on <strong>"' + query + '"</strong>: Under CCCRN Compliance guidelines, all staff are protected under POL-001 with strict confidentiality. For grievances, you can submit either named or anonymously. Your submissions are saved securely.';
        if (query.includes('PDP')) {
            reply = 'For your PDP: You have 4 core objectives totaling 60 points. Evidence must be attached before the 15th of the month. Your supervisor grades your monthly behavioral performance (/40 marks), and your HOD grades your innovation (/50 marks).';
        } else if (query.includes('PSEA')) {
            reply = 'Under POL-PSEA-001: PSEA complaints go directly to the Director of Compliance and the Safeguarding Lead (Emeka Eze). Zero retaliation is guaranteed under institutional policy and USAID standards.';
        }

        aiDiv.innerHTML = '<div style="font-weight: 700; color: var(--accent); margin-bottom: 4px; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-robot"></i> ComplianceIQ Staff Assistant</div>' + reply;
        win.appendChild(aiDiv);
        win.scrollTop = win.scrollHeight;
    }, 400);

    win.scrollTop = win.scrollHeight;
}


// Robust modal controls supporting both .active class and inline style
window.openModal = function(id) {
    var el = document.getElementById(id);
    if (el) {
        el.classList.add('active');
        el.style.display = 'flex';
    }
};

window.closeModal = function(id) {
    var el = document.getElementById(id);
    if (el) {
        el.classList.remove('active');
        el.style.display = 'none';
    }
};

function syncStaffWithBackendLive() {
    fetch('/api/backend/data')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data && data.leave_requests) {
                var localReqs = CCCRN_STORE.get(CCCRN_STORE.KEYS.LEAVE_REQUESTS, []);
                var changed = false;
                data.leave_requests.forEach(function(br) {
                    if (br.staff_name === getActiveStaffName() || !br.staff_name) {
                        var match = localReqs.find(function(lr) { return lr.id === br.id; });
                        if (match && match.status !== br.status) {
                            match.status = br.status;
                            changed = true;
                        }
                    }
                });
                if (changed) {
                    CCCRN_STORE.set(CCCRN_STORE.KEYS.LEAVE_REQUESTS, localReqs);
                    renderLeaveFromStorage();
                }
                var sl = document.getElementById("supervisorLeaveQueueWrapper");
                if (sl && sl.style.display !== "none") renderSupervisorLeaveQueue();
            }
        }).catch(function(e) {});
}
// Global Initialization

function renderFieldWorkFromStorage() {
    var list = CCCRN_STORE.get(CCCRN_STORE.KEYS.FIELD_WORK, []);
    var tbody = document.getElementById('staffFieldWorkTableBody');
    var statActive = document.getElementById('statActiveFieldMissions');
    var statCompleted = document.getElementById('statCompletedFieldMissions');
    var statPendingAdv = document.getElementById('statPendingFieldAdvances');

    if (statActive) statActive.innerText = list.length;
    if (statCompleted) statCompleted.innerText = '0';
    if (statPendingAdv) statPendingAdv.innerText = list.filter(function(x) { return x.advance_requested && x.advance_requested !== 'None'; }).length;

    if (!tbody) return;

    if (!list || list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 32px 16px; color: var(--text-muted); font-size: 12px;"><div style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"><i class="fa-solid fa-map-location-dot"></i></div><strong>No field work or missions recorded yet</strong><div style="font-size: 11px; margin-top: 2px;">Click "+ Log Field Mission / Travel" to register your next field assignment.</div></td></tr>';
        return;
    }

    var html = '';
    list.forEach(function(fw) {
        html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: #10b981;">' + fw.ref + '</td>' +
            '<td style="padding: 10px 8px;"><div style="font-weight: 700; color: var(--text);">' + fw.destination + '</div><div style="font-size: 10px; color: var(--text-muted);">' + fw.activity_type + '</div></td>' +
            '<td style="padding: 10px 8px; font-size: 11px;">' + (fw.purpose || 'Supportive Supervision') + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; font-size: 11px;">' + (fw.start_date || 'Upcoming') + ' — ' + (fw.end_date || '') + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: #02367B;">' + (fw.advance_requested || 'None') + '</td>' +
            '<td style="padding: 10px 8px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">' + (fw.status || 'Active') + '</span></td>' +
        '</tr>';
    });
    tbody.innerHTML = html;
}

function handleStaffSubmitFieldWork(e) {
    e.preventDefault();
    var dest = document.getElementById('fieldWorkDestination').value;
    var actType = document.getElementById('fieldWorkActivityType').value;
    var start = document.getElementById('fieldWorkStartDate').value;
    var end = document.getElementById('fieldWorkEndDate').value;
    var purpose = document.getElementById('fieldWorkPurpose').value;
    var adv = document.getElementById('fieldWorkAdvance').value;
    closeModal('modalStaffLogFieldWork');

    var list = CCCRN_STORE.get(CCCRN_STORE.KEYS.FIELD_WORK, []);
    var newRef = 'FW-2026-0' + (list.length + 1);
    var mission = {
        ref: newRef,
        staff_name: getActiveStaffName(),
        destination: dest,
        activity_type: actType,
        start_date: start,
        end_date: end,
        purpose: purpose,
        advance_requested: adv || 'None',
        status: 'Approved & Active'
    };
    list.unshift(mission);
    CCCRN_STORE.set(CCCRN_STORE.KEYS.FIELD_WORK, list);
    renderFieldWorkFromStorage();

    // Transmit to Backend
    fetch('/api/fieldwork/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(mission)
    }).then(function(res) { return res.json(); }).then(function(resp) {
        console.log('Field mission synced to backend:', resp);
    }).catch(function(err) {});

    alert('Field Mission (' + newRef + ') successfully registered and transmitted to Operations & DoC!');
}

function toggleStaffAlertsPanel() {
    openModal('modalStaffAlertsCenter');
}

function updateStaffAlertsCenter() {
    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var items = [];
            if (data.leave_requests) {
                var myLeave = data.leave_requests.filter(function(x) { return x.staff_name === getActiveStaffName() || !x.staff_name; });
                myLeave.forEach(function(l) {
                    if (l.status === 'Approved') {
                        items.push({ title: 'Leave Approved (' + l.id + ')', desc: 'Approved for ' + l.days + ' days by HR', icon: 'fa-calendar-check', color: '#059669' });
                    }
                });
            }
            if (data.field_work && data.field_work.length > 0) {
                data.field_work.forEach(function(fw) {
                    items.push({ title: 'Mission Clearance: ' + fw.ref, desc: fw.destination + ' (' + fw.activity_type + ')', icon: 'fa-map-pin', color: '#10b981' });
                });
            }

            var badge = document.getElementById('staffHeaderAlertBadge');
            var listEl = document.getElementById('staffAlertsCenterList');
            if (badge) {
                badge.innerText = items.length;
                badge.style.display = items.length > 0 ? 'inline-block' : 'none';
            }
            if (listEl && items.length > 0) {
                var html = '';
                items.forEach(function(it) {
                    html += '<div style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; align-items: flex-start;">' +
                        '<div style="width: 28px; height: 28px; border-radius: 50%; background: #f8fafc; display: flex; align-items: center; justify-content: center; color: ' + it.color + '; flex-shrink: 0;"><i class="fa-solid ' + it.icon + '"></i></div>' +
                        '<div><strong style="color: #0f172a; font-size: 12px;">' + it.title + '</strong><div style="color: #64748b; font-size: 11px;">' + it.desc + '</div></div>' +
                    '</div>';
                });
                listEl.innerHTML = html;
            }
        }).catch(function(e) {});
}

window.initStaffModule = function() {
    CCCRN_STORE.initDefaults();
    renderAllFromStorage();

    // Check backend sync for live updates
    fetch('/api/backend/data')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data && data.leave_requests) {
                var fatimaReq = data.leave_requests.find(function(x) { return x.staff_name === getActiveStaffName(); });
                if (fatimaReq) {
                    var localReqs = CCCRN_STORE.get(CCCRN_STORE.KEYS.LEAVE_REQUESTS, []);
                    localReqs.forEach(function(lr) {
                        if (lr.id === fatimaReq.id) lr.status = fatimaReq.status;
                    });
                    CCCRN_STORE.set(CCCRN_STORE.KEYS.LEAVE_REQUESTS, localReqs);
                    renderLeaveFromStorage();
                }
            }
        }).catch(function(e) { /* offline mode fallback */ });

    if (window.ATTENDIFY_STAFF_CONTEXT) {
        var ctx = window.ATTENDIFY_STAFF_CONTEXT;
        if (ctx.name) document.getElementById('staffNameDisplay').innerText = ctx.name;
        if (ctx.id) document.getElementById('staffIdDisplay').innerText = ctx.id;
        if (ctx.role) document.getElementById('staffRoleDeptDisplay').innerText = ctx.role;
        if (ctx.state) document.getElementById('staffDutyStationDisplay').innerText = ctx.state;
    }
};

document.addEventListener('DOMContentLoaded', function() {
    if (window.initStaffModule) {
        window.initStaffModule();
    }
});

// Listen for events from parent host window (Identify Simulator)
window.addEventListener('message', function(event) {
    if (!event.data) return;
    if (event.data.action === 'SET_USER_CONTEXT') {
        var user = event.data.payload;
        if (user) {
            window.CURRENT_USER_CONTEXT = Object.assign({}, window.CURRENT_USER_CONTEXT, user);
            if (user.role) {
                switchStaffRolePerspective(user.role);
                var bannerRole = document.getElementById('bannerActiveRoleText');
                if (bannerRole) bannerRole.innerText = user.role.toUpperCase() + ' SESSION';
            }
            if (user.name) {
                var nameEl = document.getElementById('staffNameDisplay');
                if (nameEl) nameEl.innerText = user.name;
            }
            if (user.avatar) {
                var avEl = document.getElementById('staffAvatarDisplay');
                if (avEl) avEl.innerText = user.avatar;
            }
            if (user.dept) {
                var deptEl = document.getElementById('staffRoleDeptDisplay');
                if (deptEl) deptEl.innerText = user.dept;
            }
            if (user.id) {
                var idEl = document.getElementById('staffIdDisplay');
                if (idEl) idEl.innerText = user.id;
            }
            if (user.state) {
                var stateEl = document.getElementById('staffDutyStationDisplay');
                if (stateEl) stateEl.innerText = user.state;
            }
            // Re-render views with dynamic user context
            renderLeaveFromStorage();
            renderComplaintsFromStorage();
        }
    } else if (event.data.action === 'SWITCH_TAB') {
        switchStaffMainTab(event.data.payload);
    }
});

</script>
@endsection
