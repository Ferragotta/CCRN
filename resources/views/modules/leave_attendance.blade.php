@extends('layouts.app')

@section('content')
<div id="leaveAttendanceModuleContainer" style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;">
    <!-- 1. MODULE HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; fontSize: 22px; font-weight: 800; color: var(--text); margin: 0;">
                    Leave & Attendance Management
                </h1>
                <span style="background: #059669; color: #ffffff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 12px; letter-spacing: 0.5px;">
                    ATTENDIFY PRO™ LIVE SYNC
                </span>
            </div>
            <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                Biometric clock-in records, staff leave entitlement tracking, and workforce attendance telemetry
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <button id="btnApplyLeaveHeader" class="btn btn-primary" onclick="alert('Leave Application Form Opened')">
                <i class="fa-solid fa-calendar-plus me-1"></i> Apply for Leave
            </button>
        </div>
    </div>

    <!-- 2. 4 STAT CARDS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0; border-left: 4px solid #0077b6; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Total Leave Applications</div>
            <div id="leaveStatTotal" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0077b6; line-height: 1;">0</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Quarter 1, FY2026</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #d97706; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Pending HR Verification</div>
            <div id="leaveStatPending" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;">0</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Action required by HR Manager</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #059669; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Approved Leaves</div>
            <div id="leaveStatApproved" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #059669; line-height: 1;">0</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Active roster updated</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #7c3aed; padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Today's Attendance Rate</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #7c3aed; line-height: 1;">94.2%</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">462 of 490 clocked in on time</div>
        </div>
    </div>

    <!-- 3. SUB TABS -->
    <div style="display: flex; gap: 8px; border-bottom: 1px solid var(--border); margin-bottom: 20px;">
        <button class="tab active" id="lveTabBtnApps" onclick="switchLveTab('apps')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid #0077b6; color: #0077b6; font-weight: 700; font-size: 13px; cursor: pointer;">
            <i class="fa-solid fa-file-signature me-1"></i> Leave Applications
        </button>
        <button class="tab" id="lveTabBtnBio" onclick="switchLveTab('bio')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer;">
            <i class="fa-solid fa-fingerprint me-1"></i> Attendify Biometrics Log
        </button>
        <button class="tab" id="lveTabBtnBal" onclick="switchLveTab('bal')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer;">
            <i class="fa-solid fa-chart-pie me-1"></i> Staff Entitlement Balances
        </button>
    </div>

    <!-- 4. CONTENT SECTIONS -->
    <div id="lveSectionApps" class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="card-title"><i class="fa-solid fa-table-list" style="color: #0077b6; margin-right: 8px;"></i> Workforce Leave Applications Register</div>
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
                <tbody id="hrLeaveRegisterTableBody">
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

    <div id="lveSectionBio" class="card" style="display: none;">
        <div class="card-header"><div class="card-title"><i class="fa-solid fa-fingerprint" style="color: #059669; margin-right: 8px;"></i> Attendify Pro™ Live Clock-in Telemetry</div></div>
        <div style="padding: 16px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted); font-size: 11px;">
                        <th style="padding: 10px;">Log ID</th>
                        <th style="padding: 10px;">Staff Member</th>
                        <th style="padding: 10px;">Duty Office</th>
                        <th style="padding: 10px;">Clock-In Time</th>
                        <th style="padding: 10px;">Terminal</th>
                        <th style="padding: 10px;">Status</th>
                    </tr>
                </thead>
                <tbody id="hrBiometricsTableBody">
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

    <div id="lveSectionBal" class="card" style="display: none;">
        <div class="card-header"><div class="card-title"><i class="fa-solid fa-chart-pie" style="color: #7c3aed; margin-right: 8px;"></i> Statutory Leave Balances (2026 Cycle)</div></div>
        <div style="padding: 16px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted); font-size: 11px;">
                        <th style="padding: 10px;">Staff Name</th>
                        <th style="padding: 10px;">Duty Station</th>
                        <th style="padding: 10px;">Annual Total</th>
                        <th style="padding: 10px;">Utilized</th>
                        <th style="padding: 10px;">Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="hrLeaveBalEmptyRow">
                        <td colspan="5" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">
                            <i class="fa-solid fa-clipboard-user" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>
                            Staff entitlement balances will populate dynamically upon employee record registration.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
function loadLeaveRegisterLiveBackend() {
    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data) return;

            // Dynamically update KPI tiles
            var requests = Array.isArray(data.leave_requests) ? data.leave_requests : [];
            var total = requests.length;
            var pending = requests.filter(function(r) { return r.status && r.status.includes('Pending'); }).length;
            var approved = requests.filter(function(r) { return r.status === 'Approved'; }).length;

            var elTot = document.getElementById('leaveStatTotal');
            var elPend = document.getElementById('leaveStatPending');
            var elApp = document.getElementById('leaveStatApproved');

            if (elTot) elTot.innerText = total;
            if (elPend) elPend.innerText = pending;
            if (elApp) elApp.innerText = approved;

            // Render Leave Applications Table
            var tbody = document.getElementById('hrLeaveRegisterTableBody');
            if (tbody) {
                if (requests.length === 0) {
                    tbody.innerHTML = '<tr>' +
                        '<td colspan="7" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">' +
                            '<i class="fa-solid fa-calendar-xmark" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>' +
                            'No leave applications recorded yet. Staff leave requests submitted via Attendify will appear here in real time.' +
                        '</td>' +
                    '</tr>';
                } else {
                    var html = '';
                    requests.forEach(function(r) {
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
                              '<button class="btn btn-sm btn-primary" title="HR Approve Leave" onclick="handleLeaveAttendanceAction(\'' + r.id + '\', \'Approved\')" style="font-size: 10px; padding: 3px 7px; line-height: 1; background: #059669; border-color: #059669;"><i class="fa-solid fa-check me-1"></i>Approve</button>' +
                              '<button class="btn btn-sm btn-outline" title="HR Reject Leave" style="color: #dc2626; border-color: #fca5a5; font-size: 10px; padding: 3px 7px; line-height: 1;" onclick="handleLeaveAttendanceAction(\'' + r.id + '\', \'Rejected\')"><i class="fa-solid fa-xmark"></i></button>' +
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

                        html += '<tr style="border-bottom: 1px solid var(--border);">' +
                            '<td style="padding: 9px 8px; font-weight: 700; color: #0077b6; font-family: monospace; font-size: 11px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + r.id + '</td>' +
                            '<td style="padding: 9px 8px;"><div style="font-weight: 700; color: var(--text); font-size: 12px; line-height: 1.2;">' + r.staff_name + '</div><div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">' + (r.department || 'Clinical') + ' · ' + (r.state || 'Lagos') + '</div></td>' +
                            '<td style="padding: 9px 8px;">' + shortBadge + '</td>' +
                            '<td style="padding: 9px 8px;">' + scheduleHtml + '</td>' +
                            '<td style="padding: 9px 8px;"><div style="font-size: 11px; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' + (r.reliever || 'None') + '">' + (r.reliever || 'None') + '</div></td>' +
                            '<td style="padding: 9px 6px; text-align: center;">' + statPillCompact + '</td>' +
                            '<td style="padding: 9px 8px; text-align: right;">' + actionBtnsCompact + '</td>' +
                        '</tr>';
                    });
                    tbody.innerHTML = html;
                }
            }

            // Render Attendify Biometrics Pulse Table
            var bioTbody = document.getElementById('hrBiometricsTableBody');
            if (bioTbody) {
                var logs = Array.isArray(data.attendance_logs) ? data.attendance_logs : [];
                if (logs.length === 0) {
                    bioTbody.innerHTML = '<tr>' +
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
                    bioTbody.innerHTML = bHtml;
                }
            }
        }).catch(function(e) { console.log('Leave register sync error', e); });
}

document.addEventListener('DOMContentLoaded', function() {
    applyLeaveRolePermissions();
    loadLeaveRegisterLiveBackend();
    setInterval(loadLeaveRegisterLiveBackend, 2000);
});


window.applyLeaveRolePermissions = applyLeaveRolePermissions;
function applyLeaveRolePermissions() {
    var role = window.CURRENT_USER_ROLE || 'doc';
    var btn = document.getElementById('btnApplyLeaveHeader');
    if (btn) {
        // DoC is executive authority and oversees institutional leave records rather than applying
        if (role === 'doc' || role === 'superadmin') {
            btn.style.display = 'none';
        } else {
            btn.style.display = 'inline-flex';
        }
    }
}

function switchLveTab(key) {

    document.getElementById('lveSectionApps').style.display = key === 'apps' ? 'block' : 'none';
    document.getElementById('lveSectionBio').style.display = key === 'bio' ? 'block' : 'none';
    document.getElementById('lveSectionBal').style.display = key === 'bal' ? 'block' : 'none';

    ['Apps', 'Bio', 'Bal'].forEach(t => {
        const btn = document.getElementById('lveTabBtn' + t);
        if (btn) {
            if (t.toLowerCase() === key) {
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
</script>
@endsection
