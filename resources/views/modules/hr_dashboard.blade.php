@extends('layouts.app')

@section('content')
<div id="hrDashboardContainer" style="padding-bottom: 40px;">
    <!-- 1. TOP HR COMMAND BANNER -->
    <div style="background: linear-gradient(135deg, #022b61 0%, #02367B 55%, #0077b6 100%); color: #ffffff; padding: 24px 28px; border-radius: 14px; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(2, 54, 123, 0.18); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; marginBottom: 4px;">
                <span style="background: #55E2E9; color: #02367B; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.6px;">
                    HR Executive Operations
                </span>
                <span style="font-size: 12px; opacity: 0.85; font-weight: 500;">
                    Attendify Pro™ Synced · FY2026
                </span>
            </div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 22px; font-weight: 800; margin: 4px 0 3px; color: #ffffff;">
                Human Resources Command Center
            </h1>
            <div style="font-size: 12px; opacity: 0.9;">
                Workforce governance, leave & attendance rosters, PDP scoring, mandatory training compliance, and employee relations
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <!-- Active Notification Bell Button on HR Dashboard -->
            <div style="position: relative;" id="hrBannerNotificationWrapper">
                <button id="hrBannerNotificationBtn" onclick="toggleHrBannerNotifications(event)" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); color: #ffffff; width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; transition: all 0.2s ease;" title="HR Notifications & Pending Actions">
                    <i class="fa-solid fa-bell" style="font-size: 16px; color: #55E2E9;"></i>
                    <span id="hrBannerNotificationBadge" style="position: absolute; top: -4px; right: -4px; min-width: 18px; height: 18px; padding: 0 4px; background: #dc2626; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 800; display: none; align-items: center; justify-content: center; border: 2px solid #022b61; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">0</span>
                </button>

                <!-- HR Notification Floating Panel -->
                <div id="hrBannerNotificationDropdown" style="display: none; position: absolute; right: 0; top: 48px; width: 370px; max-width: 90vw; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; box-shadow: 0 15px 30px rgba(0,0,0,0.25); z-index: 1050; overflow: hidden; text-align: left; color: var(--text);">
                    <div style="padding: 12px 16px; background: #f8fafc; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-bell" style="color: #02367B;"></i>
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 13px; color: #02367B;">HR Actions & Notifications</span>
                        </div>
                        <span id="hrBannerDropdownUnreadBadge" style="background: #fee2e2; color: #991b1b; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 12px;">0 Action Items</span>
                    </div>

                    <div id="hrBannerNotificationList" style="max-height: 380px; overflow-y: auto; padding: 4px 0;">
                        <!-- Dynamically Injected by live sync -->
                    </div>

                    <div style="padding: 10px 14px; background: #f8fafc; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
                        <a href="javascript:void(0)" onclick="markAllHrNotificationsRead()" style="color: #0077b6; text-decoration: none; font-weight: 600;">
                            <i class="fa-solid fa-check-double me-1"></i> Dismiss all
                        </a>
                        <a href="javascript:void(0)" onclick="switchHrTab('leave'); toggleHrBannerNotifications();" style="color: #02367B; text-decoration: none; font-weight: 700;">
                            Open Leave Queue <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.14); border-color: rgba(255,255,255,0.35); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('leave-attendance')">
                <i class="fa-solid fa-calendar-check me-1"></i> Leave & Attendance
            </button>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.14); border-color: rgba(255,255,255,0.35); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('pdp')">
                <i class="fa-solid fa-bullseye me-1"></i> Institutional PDP Audit
            </button>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.14); border-color: rgba(255,255,255,0.35); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('training')">
                <i class="fa-solid fa-graduation-cap me-1"></i> Training Academy
            </button>
        </div>
    </div>

    <!-- 2. 5 KEY HR STAT TILES -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0; border-left: 4px solid #0077b6; background: #ffffff; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Total Active Workforce</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0077b6; line-height: 1;">490</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Across 6 State Offices & Clusters</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #059669; background: #ffffff; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Today's Attendance Rate</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #059669; line-height: 1;">94.2%</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;"><span style="color: #059669; font-weight: 700;">462 Clocked In</span> · 28 on Field / Leave</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #d97706; background: #ffffff; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Pending Leave Requests</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;" id="hrPendingLeaveCount">0</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Awaiting HR Verification</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #7c3aed; background: #ffffff; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Compliance Training</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #7c3aed; line-height: 1;">71.4%</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;"><span style="color: #dc2626; font-weight: 700;">34 Overdue Staff</span> · PSEA at 100%</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #dc2626; background: #ffffff; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Staff Grievances (View)</div>
            <div id="hrGrievancesCount" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #dc2626; line-height: 1;">0</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;"><span style="color: #0077b6; font-weight: 700;">Strict View-Only</span> Access</div>
        </div>
    </div>

    <!-- 3. INTERACTIVE TABS -->
    <div style="display: flex; gap: 8px; border-bottom: 1px solid var(--border); margin-bottom: 20px;">
        <button class="tab active" id="hrTabBtnOverview" onclick="switchHrTab('overview')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid #0077b6; color: #0077b6; font-weight: 700; font-size: 13px; cursor: pointer;">
            <i class="fa-solid fa-gauge-high me-1"></i> HR Overview
        </button>
        <button class="tab" id="hrTabBtnLeave" onclick="switchHrTab('leave')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer;">
            <i class="fa-solid fa-calendar-check me-1"></i> Pending Leave Approvals
        </button>
        <button class="tab" id="hrTabBtnAttendance" onclick="switchHrTab('attendance')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer;">
            <i class="fa-solid fa-clock me-1"></i> Today's Attendance Pulse
        </button>
        <button class="tab" id="hrTabBtnComplaints" onclick="switchHrTab('complaints')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer;">
            <i class="fa-solid fa-inbox me-1"></i> Complaints Monitor (View Only)
        </button>
    </div>

    <!-- 4. TAB PANELS -->
    <div id="hrSectionOverview">
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px;">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="card-title">
                        <i class="fa-solid fa-map-location-dot" style="color: #0077b6; margin-right: 8px;"></i>
                        State Workforce & Attendance Rate
                    </div>
                    <button class="btn btn-sm btn-outline" onclick="switchPanel('states')">View Clusters</button>
                </div>
                <div style="padding: 16px;">
                    <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted); font-size: 11px;">
                                <th style="padding: 8px 10px;">State Office</th>
                                <th style="padding: 8px 10px;">Staff Count</th>
                                <th style="padding: 8px 10px;">Attendance Rate</th>
                                <th style="padding: 8px 10px;">Training Overdue</th>
                                <th style="padding: 8px 10px;">PDP Compliance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid var(--border);"><td style="padding: 10px; font-weight: 600;">Lagos (Cluster A)</td><td style="padding: 10px;">95 staff</td><td style="padding: 10px; font-weight: 700; color: #059669;">96%</td><td style="padding: 10px;">1 staff</td><td style="padding: 10px;">92%</td></tr>
                            <tr style="border-bottom: 1px solid var(--border);"><td style="padding: 10px; font-weight: 600;">Abuja FCT</td><td style="padding: 10px;">110 staff</td><td style="padding: 10px; font-weight: 700; color: #059669;">98%</td><td style="padding: 10px;">2 staff</td><td style="padding: 10px;">95%</td></tr>
                            <tr style="border-bottom: 1px solid var(--border);"><td style="padding: 10px; font-weight: 600;">Kano (Cluster B)</td><td style="padding: 10px;">82 staff</td><td style="padding: 10px; font-weight: 700; color: #059669;">89%</td><td style="padding: 10px; color: #dc2626;">8 staff</td><td style="padding: 10px;">78%</td></tr>
                            <tr style="border-bottom: 1px solid var(--border);"><td style="padding: 10px; font-weight: 600;">Rivers (Cluster C)</td><td style="padding: 10px;">74 staff</td><td style="padding: 10px; font-weight: 700; color: #059669;">94%</td><td style="padding: 10px;">3 staff</td><td style="padding: 10px;">88%</td></tr>
                            <tr style="border-bottom: 1px solid var(--border);"><td style="padding: 10px; font-weight: 600;">Kaduna</td><td style="padding: 10px;">68 staff</td><td style="padding: 10px; font-weight: 700; color: #059669;">91%</td><td style="padding: 10px; color: #dc2626;">9 staff</td><td style="padding: 10px;">74%</td></tr>
                            <tr style="border-bottom: 1px solid var(--border);"><td style="padding: 10px; font-weight: 600;">Borno</td><td style="padding: 10px;">61 staff</td><td style="padding: 10px; font-weight: 700; color: #059669;">87%</td><td style="padding: 10px; color: #dc2626;">11 staff</td><td style="padding: 10px;">69%</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="card-title">
                        <i class="fa-solid fa-calendar-days" style="color: #d97706; margin-right: 8px;"></i>
                        Pending Leave Queue
                    </div>
                    <button class="btn btn-sm btn-outline" onclick="switchHrTab('leave')">View All</button>
                </div>
                <div style="padding: 16px;" id="hrOverviewPendingLeaveList">
                    <div style="text-align: center; color: var(--text-muted); padding: 16px; font-size: 12px;">
                        <i class="fa-solid fa-spinner fa-spin me-1"></i> Syncing with Staff Attendify Live Queue...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="hrSectionLeave" style="display: none;">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title">
                    <i class="fa-solid fa-calendar-check" style="color: #0077b6; margin-right: 8px;"></i>
                    Workforce Leave Applications & Verification
                </div>
                <button class="btn btn-primary btn-sm" onclick="switchPanel('leave-attendance')">
                    Open Full Leave & Attendance Hub <i class="fa-solid fa-arrow-right ms-1"></i>
                </button>
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 11.5px; table-layout: fixed;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted); font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 9px 8px; width: 13%;">Request ID</th>
                            <th style="padding: 9px 8px; width: 21%;">Staff & Office</th>
                            <th style="padding: 9px 8px; width: 13%;">Category</th>
                            <th style="padding: 9px 8px; width: 22%;">Schedule</th>
                            <th style="padding: 9px 8px; width: 14%;">Reliever</th>
                            <th style="padding: 9px 6px; width: 9%; text-align: center;">Status</th>
                            <th style="padding: 9px 8px; width: 8%; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="hrLeaveTabTableBody">
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">
                                <i class="fa-solid fa-calendar-xmark" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>
                                No leave applications recorded yet. Staff leave requests submitted via Attendify will appear here in real time.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="hrSectionAttendance" style="display: none;">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title">
                    <i class="fa-solid fa-clock" style="color: #059669; margin-right: 8px;"></i>
                    Attendify Pro™ Live Clock-in Telemetry
                </div>
                <button class="btn btn-sm btn-primary" onclick="switchPanel('leave-attendance')">Full Attendance Matrix</button>
            </div>
            <div style="padding: 16px;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted); font-size: 11px;">
                            <th style="padding: 10px;">Log Ref</th>
                            <th style="padding: 10px;">Staff Member</th>
                            <th style="padding: 10px;">State Office</th>
                            <th style="padding: 10px;">Clock-In Time</th>
                            <th style="padding: 10px;">Verification Terminal</th>
                            <th style="padding: 10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tbody id="hrAttendancePulseTableBody">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">
                                <i class="fa-solid fa-fingerprint" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>
                                No biometrics clocked in today yet. Live terminal scans from state offices will sync here.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="hrSectionComplaints" style="display: none;">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-shield-halved" style="color: #dc2626;"></i>
                        <span>Employee Grievances & Misconduct Watchlist</span>
                        <span style="background: #e0f2fe; color: #02367B; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 12px; border: 1px solid #bae6fd;">
                            HR VIEW-ONLY PERMISSION
                        </span>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline" onclick="switchPanel('complaints')">Open Full Complaints Register</button>
            </div>
            <div style="padding: 16px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 6px; font-size: 12px; color: #475569; margin-bottom: 16px;">
                    <i class="fa-solid fa-circle-info" style="color: #0077b6; margin-right: 6px;"></i>
                    <strong>HR View-Only Policy:</strong> Per CCCRN Governance SOP, HR personnel can view grievance status and duty stations for record-keeping. Triage and investigator assignment belong to the Director of Compliance.
                </div>
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted); font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 9px 8px; width: 13%;">Ref ID</th>
                            <th style="padding: 9px 8px; width: 22%;">Duty Station & Date</th>
                            <th style="padding: 9px 8px; width: 20%;">Category</th>
                            <th style="padding: 9px 8px; width: 17%;">Investigator</th>
                            <th style="padding: 9px 6px; width: 11%; text-align: center;">Severity</th>
                            <th style="padding: 9px 6px; width: 17%; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="hrComplaintsWatchlistTableBody">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">
                                <i class="fa-solid fa-shield-check" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>
                                No employee grievances logged on the register.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function loadHrDashboardLiveBackend() {
    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data) return;

            // 1. Update KPI Card Counts
            var pendingRequests = (data.leave_requests || []).filter(function(r) { return r.status === 'Pending HR'; });
            var countEl = document.getElementById('hrPendingLeaveCount');
            if (countEl) countEl.innerText = pendingRequests.length;

            var grievancesCountEl = document.getElementById('hrGrievancesCount');
            if (grievancesCountEl) grievancesCountEl.innerText = (data.complaints || []).length;

            // 2. Render Overview Pending Leave Queue Widget
            var queueContainer = document.getElementById('hrOverviewPendingLeaveList');
            if (queueContainer) {
                var awaitingSupervisor = (data.leave_requests || []).filter(function(r) { return r.status === 'Pending Supervisor'; });

                if (pendingRequests.length === 0) {
                    var noticeHtml = '<div style="text-align: center; color: var(--text-muted); padding: 18px; font-size: 12px;">' +
                        '<i class="fa-solid fa-circle-check text-success me-1"></i> No leave applications awaiting HR Admin approval.';
                    if (awaitingSupervisor.length > 0) {
                        noticeHtml += '<div style="margin-top: 8px; font-size: 11px; color: #02367B; background: #f0f7ff; padding: 6px 10px; border-radius: 6px; border: 1px solid #bae6fd;">' +
                            '<i class="fa-solid fa-clock me-1"></i> ' + awaitingSupervisor.length + ' application(s) currently awaiting Tier-1 Supervisor authentication before reaching HR.' +
                        '</div>';
                    }
                    noticeHtml += '</div>';
                    queueContainer.innerHTML = noticeHtml;
                } else {
                    var qHtml = '';
                    if (awaitingSupervisor.length > 0) {
                        qHtml += '<div style="margin-bottom: 10px; font-size: 10.5px; color: #02367B; background: #f0f7ff; padding: 6px 10px; border-radius: 6px; border: 1px solid #bae6fd; display: flex; justify-content: space-between; align-items: center;">' +
                            '<span><i class="fa-solid fa-info-circle me-1"></i> ' + awaitingSupervisor.length + ' request(s) awaiting Tier-1 Supervisor authentication</span>' +
                            '<span style="font-weight: 700;">Tier 1 in Progress</span>' +
                        '</div>';
                    }
                    pendingRequests.forEach(function(r) {
                        qHtml += '<div style="padding: 12px 14px; border-radius: 8px; border: 1px solid #bfdbfe; margin-bottom: 10px; background: #eff6ff; display: flex; justify-content: space-between; align-items: center;">' +
                            '<div>' +
                                '<div style="font-weight: 700; font-size: 13px; color: #02367B;">' + (r.staff_name || 'Staff Member') + ' <span style="font-size: 10px; font-family: monospace; color: var(--text-muted);">(' + r.id + ')</span> <span class="badge" style="background: #dcfce7; color: #166534; font-size: 9px; padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-check me-1"></i>Supervisor Authenticated</span></div>' +
                                '<div style="font-size: 11px; color: var(--text-muted);">' + r.category + ' · ' + (r.state || 'Lagos') + ' (' + (r.days || 1) + ' days)</div>' +
                                '<div style="font-size: 11px; color: var(--text-dim); margin-top: 2px;">' + (r.start || 'TBD') + ' — ' + (r.end || 'TBD') + ' (Reliever: ' + (r.reliever || 'None') + ')</div>' +
                            '</div>' +
                            '<div style="display: flex; gap: 6px;">' +
                                '<button class="btn btn-sm btn-primary" style="background: #059669; border-color: #059669;" onclick="handleHrDashboardLeaveAction(\'' + r.id + '\', \'Approved\')"><i class="fa-solid fa-check me-1"></i>Approve</button>' +
                                '<button class="btn btn-sm btn-outline" style="color: #dc2626; border-color: #fca5a5;" onclick="handleHrDashboardLeaveAction(\'' + r.id + '\', \'Rejected\')">Reject</button>' +
                            '</div>' +
                        '</div>';
                    });
                    queueContainer.innerHTML = qHtml;
                }
            }

            // 3. Render Tab 2 Leave Table
            var tabTableBody = document.getElementById('hrLeaveTabTableBody');
            if (tabTableBody) {
                var leaves = data.leave_requests || [];
                if (leaves.length === 0) {
                    tabTableBody.innerHTML = '<tr>' +
                        '<td colspan="7" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">' +
                            '<i class="fa-solid fa-calendar-xmark" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>' +
                            'No leave applications recorded yet. Staff leave requests submitted via Attendify will appear here in real time.' +
                        '</td>' +
                    '</tr>';
                } else {
                    var tHtml = '';
                    leaves.forEach(function(r) {
                        var isPendingSupervisor = (r.status === 'Pending Supervisor');
                        var isPendingHr = (r.status === 'Pending HR');
                        var isApproved = (r.status === 'Approved');
                        var isRejected = (r.status === 'Rejected');

                        var shortCat = r.category && r.category.includes('Annual') ? 'Annual' : (r.category && r.category.includes('Casual') ? 'Casual' : (r.category && r.category.includes('Sick') ? 'Sick' : (r.category || 'Leave')));
                        var shortBadge = r.category && r.category.includes('Annual')
                            ? '<span style="background: #e0f2fe; color: #02367B; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 10px;">' + shortCat + '</span>'
                            : '<span style="background: #fef3c7; color: #d97706; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 10px;">' + shortCat + '</span>';

                        var statPillCompact = '';
                        var actionBtnsCompact = '';

                        if (isPendingSupervisor) {
                            statPillCompact = '<span class="pill pill-progress" style="font-size: 9px; padding: 2px 6px; background: #f0f7ff; color: #02367B; border: 1px solid #bae6fd;"><i class="fa-solid fa-user-clock me-1"></i>Tier 1: Supv Auth</span>';
                            actionBtnsCompact = '<span style="font-size: 9.5px; color: #02367B; background: #f0f7ff; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd; display: inline-block; white-space: nowrap;"><i class="fa-solid fa-lock me-1"></i>Awaiting Supv</span>';
                        } else if (isPendingHr) {
                            statPillCompact = '<span class="pill pill-progress" style="font-size: 9px; padding: 2px 6px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;"><i class="fa-solid fa-check-double me-1"></i>Tier 2: Pending HR</span>';
                            actionBtnsCompact = '<div style="display: flex; gap: 4px; justify-content: flex-end;">' +
                              '<button class="btn btn-sm btn-primary" title="HR Approve Leave" onclick="handleHrDashboardLeaveAction(\'' + r.id + '\', \'Approved\')" style="font-size: 10px; padding: 3px 7px; line-height: 1; background: #059669; border-color: #059669;"><i class="fa-solid fa-check me-1"></i>Approve</button>' +
                              '<button class="btn btn-sm btn-outline" title="HR Reject Leave" style="color: #dc2626; border-color: #fca5a5; font-size: 10px; padding: 3px 7px; line-height: 1;" onclick="handleHrDashboardLeaveAction(\'' + r.id + '\', \'Rejected\')"><i class="fa-solid fa-xmark"></i></button>' +
                              '</div>';
                        } else if (isApproved) {
                            statPillCompact = '<span class="pill pill-closed" style="font-size: 9px; padding: 2px 6px;"><i class="fa-solid fa-circle-check me-1"></i>Approved</span>';
                            actionBtnsCompact = '<span style="font-size: 10px; color: #059669; font-weight: 600;"><i class="fa-solid fa-check"></i> Approved</span>';
                        } else {
                            statPillCompact = '<span class="pill pill-open" style="font-size: 9px; padding: 2px 6px;">Rejected</span>';
                            actionBtnsCompact = '<span style="font-size: 10px; color: #dc2626;"><i class="fa-solid fa-xmark"></i> Rejected</span>';
                        }

                        var scheduleHtml = '<div style="font-weight: 600; font-size: 11px; line-height: 1.2; color: var(--text);">' + (r.start || 'TBD') + ' — ' + (r.end || 'TBD') + '</div>' +
                                           '<div style="font-size: 10px; color: var(--text-muted); margin-top: 1px;">' + (r.days || 1) + ' working day' + ((r.days || 1) > 1 ? 's' : '') + '</div>';

                        tHtml += '<tr style="border-bottom: 1px solid var(--border);">' +
                            '<td style="padding: 9px 8px; font-weight: 700; color: #0077b6; font-family: monospace; font-size: 11px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + r.id + '</td>' +
                            '<td style="padding: 9px 8px;"><div style="font-weight: 700; color: var(--text); font-size: 12px; line-height: 1.2;">' + r.staff_name + '</div><div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">' + (r.department || 'Clinical') + ' · ' + (r.state || 'Lagos') + '</div></td>' +
                            '<td style="padding: 9px 8px;">' + shortBadge + '</td>' +
                            '<td style="padding: 9px 8px;">' + scheduleHtml + '</td>' +
                            '<td style="padding: 9px 8px;"><div style="font-size: 11px; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' + (r.reliever || 'None') + '">' + (r.reliever || 'None') + '</div></td>' +
                            '<td style="padding: 9px 6px; text-align: center;">' + statPillCompact + '</td>' +
                            '<td style="padding: 9px 8px; text-align: right;">' + actionBtnsCompact + '</td>' +
                        '</tr>';
                    });
                    tabTableBody.innerHTML = tHtml;
                }
            }

            // 4. Render Biometrics Telemetry
            var bioTableBody = document.getElementById('hrAttendancePulseTableBody');
            if (bioTableBody) {
                var logs = data.attendance_logs || [];
                if (logs.length === 0) {
                    bioTableBody.innerHTML = '<tr>' +
                        '<td colspan="6" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">' +
                            '<i class="fa-solid fa-fingerprint" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>' +
                            'No biometrics clocked in today yet. Live terminal scans from state offices will sync here.' +
                        '</td>' +
                    '</tr>';
                } else {
                    var bHtml = '';
                    logs.forEach(function(l, i) {
                        var statClass = l.clockedIn ? 'pill-closed' : 'pill-progress';
                        bHtml += '<tr style="border-bottom: 1px solid var(--border);">' +
                            '<td style="padding: 12px 10px; font-weight: 700; color: #0077b6;">LOG-0' + (900 + i) + '</td>' +
                            '<td style="padding: 12px 10px; font-weight: 600;">' + l.staff_name + '</td>' +
                            '<td style="padding: 12px 10px;">' + l.state + '</td>' +
                            '<td style="padding: 12px 10px; font-family: monospace; font-weight: 700;">' + l.time + '</td>' +
                            '<td style="padding: 12px 10px;">' + (l.terminal || 'Attendify Terminal') + '</td>' +
                            '<td style="padding: 12px 10px;"><span class="pill ' + statClass + '">' + (l.status || 'Clocked-In') + '</span></td>' +
                        '</tr>';
                    });
                    bioTableBody.innerHTML = bHtml;
                }
            }

            // 5. Render Complaints Watchlist (View Only)
            var compTableBody = document.getElementById('hrComplaintsWatchlistTableBody');
            if (compTableBody) {
                var compList = data.complaints || [];
                if (compList.length === 0) {
                    compTableBody.innerHTML = '<tr>' +
                        '<td colspan="6" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">' +
                            '<i class="fa-solid fa-shield-check" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>' +
                            'No employee grievances logged on the register.' +
                        '</td>' +
                    '</tr>';
                } else {
                    var cHtml = '';
                    compList.forEach(function(c) {
                        var sevClass = (c.severity === 'Critical') ? 'pill-open' : 'pill-progress';
                        cHtml += '<tr style="border-bottom: 1px solid var(--border);">' +
                            '<td style="padding: 9px 8px; font-weight: 700; color: #0077b6; font-family: monospace; font-size: 11px;">' + c.id + '</td>' +
                            '<td style="padding: 9px 8px;"><div style="font-weight: 700; color: var(--text); font-size: 12px;">' + (c.state || 'National') + ' Office</div><div style="font-size: 10px; color: var(--text-muted); margin-top: 1px;">' + (c.date || 'Recent') + '</div></td>' +
                            '<td style="padding: 9px 8px;"><span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 600; font-size: 10.5px;">' + (c.category || 'General') + '</span></td>' +
                            '<td style="padding: 9px 8px;"><div style="font-size: 11px; font-weight: 500;">' + (c.investigator || 'Assigned by DoC') + '</div></td>' +
                            '<td style="padding: 9px 6px; text-align: center;"><span class="pill ' + sevClass + '" style="font-size: 9px; padding: 2px 6px;">' + (c.severity || 'Medium') + '</span></td>' +
                            '<td style="padding: 9px 6px; text-align: center;"><span style="color: #dc2626; font-weight: 700; font-size: 11px;"><i class="fa-solid fa-magnifying-glass me-1"></i> ' + (c.status || 'Active') + '</span></td>' +
                        '</tr>';
                    });
                    compTableBody.innerHTML = cHtml;
                }
            }
        }).catch(function(e) { console.log('HR dashboard sync offline', e); });
}

function handleHrDashboardLeaveAction(id, action) {
    fetch('/api/leave/action', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, action: action })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        alert('HR Decision Recorded: Leave Request ' + id + ' set to ' + action + '. Employee profile updated.');
        loadHrDashboardLiveBackend();
    }).catch(function(e) {
        alert('Action applied locally: ' + action);
        loadHrDashboardLiveBackend();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    loadHrDashboardLiveBackend();
    // Auto-poll every 2 seconds for live sync
    setInterval(loadHrDashboardLiveBackend, 2000);
});

function switchHrTab(tabKey) {
    document.getElementById('hrSectionOverview').style.display = tabKey === 'overview' ? 'block' : 'none';
    document.getElementById('hrSectionLeave').style.display = tabKey === 'leave' ? 'block' : 'none';
    document.getElementById('hrSectionAttendance').style.display = tabKey === 'attendance' ? 'block' : 'none';
    document.getElementById('hrSectionComplaints').style.display = tabKey === 'complaints' ? 'block' : 'none';

    ['Overview', 'Leave', 'Attendance', 'Complaints'].forEach(t => {
        const btn = document.getElementById('hrTabBtn' + t);
        if (btn) {
            if (t.toLowerCase() === tabKey) {
                btn.style.borderBottom = '3px solid #0077b6';
                btn.style.color = '#0077b6';
                btn.classList.add('active');
            } else {
                btn.style.borderBottom = '3px solid transparent';
                btn.style.color = 'var(--text-muted)';
                btn.classList.remove('active');
            }
        }
    });
}

// ══════════════════════════════════════════════════════════════════
// HR DASHBOARD NOTIFICATION BELL ENGINE
// ══════════════════════════════════════════════════════════════════
var HR_DASHBOARD_NOTIFICATIONS = [];
var READ_HR_NOTIF_IDS = {};

function toggleHrBannerNotifications(e) {
    if (e) e.stopPropagation();
    var dropdown = document.getElementById('hrBannerNotificationDropdown');
    if (!dropdown) return;
    var isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) {
        syncHrBannerNotifications();
    }
}

function renderHrBannerNotifications() {
    var list = document.getElementById('hrBannerNotificationList');
    var badge = document.getElementById('hrBannerNotificationBadge');
    var unreadBadge = document.getElementById('hrBannerDropdownUnreadBadge');
    if (!list) return;

    var unreadCount = HR_DASHBOARD_NOTIFICATIONS.filter(function(n) { return n.unread; }).length;
    if (badge) {
        badge.innerText = unreadCount;
        badge.style.display = unreadCount > 0 ? 'flex' : 'none';
    }
    if (unreadBadge) {
        unreadBadge.innerText = unreadCount + ' Action Item' + (unreadCount !== 1 ? 's' : '');
    }

    if (HR_DASHBOARD_NOTIFICATIONS.length === 0) {
        list.innerHTML = '<div style="padding: 24px; text-align: center; color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-check-circle me-1 text-success"></i> No pending notifications.</div>';
        return;
    }

    var html = '';
    HR_DASHBOARD_NOTIFICATIONS.forEach(function(n) {
        var unreadBg = n.unread ? 'background: rgba(2, 54, 123, 0.03);' : '';
        html += '<div onclick="handleHrNotificationItemClick(\'' + n.id + '\', \'' + (n.tabTarget || '') + '\', \'' + (n.panelTarget || '') + '\')" style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; cursor: pointer; transition: background 0.15s ease; ' + unreadBg + '" onmouseover="this.style.background=\'#f8fafc\'" onmouseout="this.style.background=\'' + (n.unread ? 'rgba(2, 54, 123, 0.03)' : '#ffffff') + '\'">' +
            '<div style="width: 32px; height: 32px; border-radius: 50%; background: ' + n.iconBg + '; color: ' + n.iconColor + '; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 13px;">' +
                '<i class="fa-solid ' + n.icon + '"></i>' +
            '</div>' +
            '<div style="flex: 1; min-width: 0;">' +
                '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">' +
                    '<strong style="font-size: 12px; color: var(--text);">' + n.title + '</strong>' +
                    '<span style="font-size: 10px; color: var(--text-muted);">' + n.time + '</span>' +
                '</div>' +
                '<div style="font-size: 11px; color: var(--text-dim); line-height: 1.35; overflow: hidden; text-overflow: ellipsis;">' + n.desc + '</div>' +
            '</div>' +
            (n.unread ? '<span style="width: 6px; height: 6px; border-radius: 50%; background: #0077b6; align-self: center; flex-shrink: 0;"></span>' : '') +
        '</div>';
    });
    list.innerHTML = html;
}

function handleHrNotificationItemClick(id, tabTarget, panelTarget) {
    READ_HR_NOTIF_IDS[id] = true;
    var notif = HR_DASHBOARD_NOTIFICATIONS.find(function(n) { return n.id === id; });
    if (notif) notif.unread = false;
    renderHrBannerNotifications();
    var dropdown = document.getElementById('hrBannerNotificationDropdown');
    if (dropdown) dropdown.style.display = 'none';

    if (panelTarget && typeof switchPanel === 'function') {
        switchPanel(panelTarget);
    } else if (tabTarget && typeof switchHrTab === 'function') {
        switchHrTab(tabTarget);
    }
}

function markAllHrNotificationsRead() {
    HR_DASHBOARD_NOTIFICATIONS.forEach(function(n) {
        n.unread = false;
        READ_HR_NOTIF_IDS[n.id] = true;
    });
    renderHrBannerNotifications();
}

function syncHrBannerNotifications() {
    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data) return;
            var liveItems = [];

            // 1. Pending Leave Requests for HR Action
            var pendingLeaves = (data.leave_requests || []).filter(function(r) { return r.status === 'Pending HR'; });
            if (pendingLeaves.length > 0) {
                var topReq = pendingLeaves[0];
                liveItems.push({
                    id: 'live-hr-leave-' + topReq.id,
                    title: 'Pending Leave Approvals (' + pendingLeaves.length + ')',
                    desc: topReq.staff_name + ' applied for ' + topReq.category + ' (' + (topReq.days || 1) + ' days)',
                    time: 'Action Required',
                    type: 'leave',
                    icon: 'fa-calendar-check',
                    iconBg: '#e0f2fe',
                    iconColor: '#02367B',
                    tabTarget: 'leave',
                    unread: !READ_HR_NOTIF_IDS['live-hr-leave-' + topReq.id]
                });
            }

            // 2. Supervisor-tier In Progress Leaves
            var supvLeaves = (data.leave_requests || []).filter(function(r) { return r.status === 'Pending Supervisor'; });
            if (supvLeaves.length > 0) {
                var topS = supvLeaves[0];
                liveItems.push({
                    id: 'live-hr-supv-' + topS.id,
                    title: 'Tier 1 Supervisor Review (' + supvLeaves.length + ')',
                    desc: topS.staff_name + ' applied for ' + topS.category + ' — awaiting Supervisor authorization',
                    time: 'In Progress',
                    type: 'leave',
                    icon: 'fa-user-clock',
                    iconBg: '#fef3c7',
                    iconColor: '#d97706',
                    tabTarget: 'leave',
                    unread: !READ_HR_NOTIF_IDS['live-hr-supv-' + topS.id]
                });
            }

            // 3. Complaints Watchlist (View Only)
            var complaintsList = data.complaints || [];
            if (complaintsList.length > 0) {
                var topC = complaintsList[0];
                liveItems.push({
                    id: 'live-hr-complaint-' + topC.id,
                    title: 'Grievance Watchlist (' + complaintsList.length + ' Active)',
                    desc: topC.id + ' (' + (topC.category || 'Incident') + ') — ' + (topC.state || 'National') + ' Office',
                    time: 'Watchlist',
                    type: 'complaint',
                    icon: 'fa-shield-halved',
                    iconBg: '#fee2e2',
                    iconColor: '#dc2626',
                    tabTarget: 'complaints',
                    unread: !READ_HR_NOTIF_IDS['live-hr-complaint-' + topC.id]
                });
            }

            HR_DASHBOARD_NOTIFICATIONS = liveItems;
            renderHrBannerNotifications();
        }).catch(function(e) {});
}

// Close dropdown on outside click
document.addEventListener('click', function(e) {
    var wrapper = document.getElementById('hrBannerNotificationWrapper');
    var dropdown = document.getElementById('hrBannerNotificationDropdown');
    if (dropdown && wrapper && !wrapper.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

// Hook into existing DOMContentLoaded
var oldDomLoaded = document.addEventListener;
renderHrBannerNotifications();
syncHrBannerNotifications();
setInterval(syncHrBannerNotifications, 2500);

</script>
@endsection
