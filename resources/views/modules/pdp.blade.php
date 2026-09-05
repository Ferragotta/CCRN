@extends('layouts.app')

@section('content')

<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="pdpModuleContainer">
    <!-- SUB-HEADING -->
    <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px;">
                Performance Development Plans (PDP)
            </h2>
            <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px;">
                COP Year: October 2025 – September 2026 &nbsp;|&nbsp; 4 Main Objectives (60) · Monthly Behavioural (40) · Creativity/Innovation (50) &nbsp;|&nbsp; Total 150 Marks
            </p>
            <div id="pdpRoleScopeIndicator"></div>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;" id="pdpHeaderActions">
            <button class="btn btn-outline btn-sm" onclick="openPdpReportModal('monthly')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-calendar me-1"></i> Monthly Report
            </button>
            <button class="btn btn-outline btn-sm" onclick="openPdpReportModal('quarterly')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-chart-line me-1"></i> Quarterly Review
            </button>
            <button class="btn btn-primary btn-sm" onclick="openPdpReportModal('annually')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-file-pdf me-1"></i> Annual Dossier
            </button>
        </div>
    </div>

    <!-- 4 INSTITUTIONAL SCORE / HEALTH TILES -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px;">
        <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #0369a1; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; font-weight: 700;">
                Submitted PDPs
            </div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0077b6; line-height: 1;" id="pdpTileSubmitted">
                398 / 490
            </div>
            <div style="font-size: 11px; color: #0369a1; font-weight: 600; margin-top: 4px;">81.2% Submission Rate</div>
            <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Approved + In Review</div>
        </div>

        <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #991b1b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; font-weight: 700;">
                Pending Submissions
            </div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #dc2626; line-height: 1;" id="pdpTilePendingSub">
                92
            </div>
            <div style="font-size: 11px; color: #991b1b; font-weight: 600; margin-top: 4px;">Staff Yet to Submit</div>
            <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Action: Automated Nudge</div>
        </div>

        <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #92400e; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; font-weight: 700;">
                Pending Approvals (Sup & HOD)
            </div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;" id="pdpTilePendingApprovals">
                64
            </div>
            <div style="font-size: 11px; color: #92400e; font-weight: 600; margin-top: 4px;">Obj, Evidence & Innovation</div>
            <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Bottleneck Tracking</div>
        </div>

        <div style="background: #02367B; border: 1px solid #002b66; border-radius: 10px; padding: 14px; text-align: center; color: #ffffff;">
            <div style="font-size: 10px; color: #bae6fd; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; font-weight: 700;">
                Ungraded Behavioural
            </div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #55E2E9; line-height: 1;" id="pdpTileUngradedBeh">
                48
            </div>
            <div style="font-size: 11px; color: #bae6fd; font-weight: 600; margin-top: 4px;">Missing Monthly Grades</div>
            <div style="font-size: 10px; color: #55E2E9; margin-top: 2px;">February & March 2026</div>
        </div>
    </div>

    <!-- TABS NAVIGATION -->
    <div style="display: flex; gap: 8px; margin-bottom: 18px; flex-wrap: wrap;" id="pdpSubTabs">
        <!-- HR Primary Tabs -->
        <button class="tab active" id="pdpTabHrMaster" onclick="switchPdpTab('hr-master')" style="padding: 7px 14px; font-size: 12px; font-weight: 700; cursor: pointer; border-radius: var(--radius-sm); background: var(--accent); color: #fff; border: 1px solid var(--accent);">
            📋 All Submitted PDPs & Master Roster
        </button>
        <button class="tab" id="pdpTabHrPending" onclick="switchPdpTab('hr-pending')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
            ⏳ Pending Submissions (92)
        </button>
        <button class="tab" id="pdpTabHrApprovals" onclick="switchPdpTab('hr-approvals')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
            🔍 Supervisor & HOD Approval Backlog
        </button>
        <button class="tab" id="pdpTabHrBehavioral" onclick="switchPdpTab('hr-behavioral')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
            🧠 Monthly Behavioural Grading Audit
        </button>
        <button class="tab" id="pdpTabHrReporting" onclick="switchPdpTab('hr-reporting')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
            📊 Performance Reporting Engine
        </button>

        <!-- Staff Personal Tabs (Hidden when HR is active) -->
        <button class="tab" id="pdpTabObj" onclick="switchPdpTab('objectives')" style="display: none; padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
            📝 Set Objectives
        </button>
        <button class="tab" id="pdpTabEvid" onclick="switchPdpTab('evidence')" style="display: none; padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
            📎 Submit Evidence
        </button>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         PANEL 1 – ALL SUBMITTED PDPS & MASTER INSTITUTIONAL ROSTER
         HR: See and generate report on all PDP submitted
         ══════════════════════════════════════════════════════════════════ -->
    <div id="pdpPanelHrMaster" class="pdp-panel" style="display: block;">
        <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
                <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700;">
                    <i class="fa-solid fa-clipboard-list" style="color: var(--accent);"></i> All Submitted Performance Development Plans (398 Records)
                </div>
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <select id="pdpStateFilter" onchange="filterPdpRoster()" style="height: 32px; padding: 0 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                        <option value="">All States</option>
                        <option>Lagos</option><option>Kano</option><option>Rivers</option><option>Abuja FCT</option><option>Kaduna</option><option>Borno</option>
                    </select>
                    <select id="pdpStatusFilter" onchange="filterPdpRoster()" style="height: 32px; padding: 0 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                        <option value="">All Statuses</option>
                        <option>Approved</option><option>Review</option><option>Draft</option>
                    </select>
                    <button class="btn btn-primary btn-sm" onclick="exportPdpReport('all', 'pdf')" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-file-pdf me-1"></i> Generate Submitted PDF Report
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="exportPdpReport('all', 'excel')" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-file-excel me-1"></i> Excel Roster
                    </button>
                </div>
            </div>

            <!-- Table 100% full width, fixed layout, zero horizontal scroll -->
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 16%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Staff Member</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Department</th>
                            <th style="width: 8%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">State</th>
                            <th style="width: 9%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Obj (/60)</th>
                            <th style="width: 9%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Beh (/40)</th>
                            <th style="width: 9%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Inn (/50)</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Total (/150)</th>
                            <th style="width: 6%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">%</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
                            <th style="width: 7%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Action</th>
                        </tr>
                    </thead>
                    <tbody id="pdpRosterBody"></tbody>
                </table>
            </div>
            <div style="padding: 10px 4px 0; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--surface2); margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span>Showing <strong id="pdpRosterCount">10</strong> representative institutional records out of 398 submitted plans.</span>
                <span style="color: var(--accent); font-weight: 700;">Synced with FY2026 Master Records</span>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         PANEL 2 – PENDING PDP SUBMISSIONS (92 STAFF)
         HR: See pending PDP submission and generate report on it
         ══════════════════════════════════════════════════════════════════ -->
    <div id="pdpPanelHrPending" class="pdp-panel" style="display: none;">
        <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
                <div>
                    <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: var(--danger);">
                        <i class="fa-solid fa-triangle-exclamation"></i> Delinquent / Pending PDP Submissions (92 Staff)
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Staff who have not finalized or submitted their 4 COP Objectives for COP Year 2025/2026.
                    </div>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button class="btn btn-primary btn-sm" onclick="exportPdpReport('pending', 'pdf')" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-file-pdf me-1"></i> Generate Pending Submissions Report
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="broadcastPendingNudge()" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-bell me-1"></i> Nudge All 92 Staff
                    </button>
                </div>
            </div>

            <!-- Table 100% full width -->
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 20%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Staff Member</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Department</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">State Office</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Direct Supervisor</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Days Overdue</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Action</th>
                        </tr>
                    </thead>
                    <tbody id="pdpPendingTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         PANEL 3 – SUPERVISOR & HOD APPROVAL BACKLOG
         HR: See from back-end supervisor and HOD that are yet to approve PDP, Evidence and creativity
         ══════════════════════════════════════════════════════════════════ -->
    <div id="pdpPanelHrApprovals" class="pdp-panel" style="display: none;">
        <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
                <div>
                    <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: #b45309;">
                        <i class="fa-solid fa-clock-rotate-left"></i> Supervisor & HOD Approval Bottleneck Tracker
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Back-end oversight of leadership personnel yet to grade Objectives, verify proof Evidence, or score Innovation.
                    </div>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button class="btn btn-primary btn-sm" onclick="exportPdpReport('approvals', 'pdf')" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-file-pdf me-1"></i> Export Approvals Backlog Dossier
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="alert('Escalation notification dispatched to all delinquent Supervisors & HODs.');" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-paper-plane me-1"></i> Remind Reviewers
                    </button>
                </div>
            </div>

            <!-- Table 100% full width -->
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Reviewer Name</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Leadership Role</th>
                            <th style="width: 14%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Department</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Staff Pending Review</th>
                            <th style="width: 16%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Pending Item</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Overdue</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Action</th>
                        </tr>
                    </thead>
                    <tbody id="pdpApprovalsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         PANEL 4 – MONTHLY BEHAVIOURAL PERFORMANCE GRADING AUDIT
         HR: See staff whose monthly behavioral performances are yet to be graded and drill report on it
         ══════════════════════════════════════════════════════════════════ -->
    <div id="pdpPanelHrBehavioral" class="pdp-panel" style="display: none;">
        <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
                <div>
                    <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: #7c3aed;">
                        <i class="fa-solid fa-brain"></i> Staff Monthly Behavioural Grading Audit (/40 Marks)
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                        Staff whose direct supervisors have not submitted the mandatory monthly 8-attribute competency appraisal.
                    </div>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button class="btn btn-primary btn-sm" onclick="exportPdpReport('behavioral', 'pdf')" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-file-pdf me-1"></i> Drill Report: Behavioural Audit
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="alert('Alert notifications sent to supervisors of all 48 ungraded staff.');" style="font-size: 11px; padding: 4px 10px; font-weight: 700;">
                        <i class="fa-solid fa-bell me-1"></i> Alert Supervisors
                    </button>
                </div>
            </div>

            <!-- Table 100% full width -->
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Staff Member</th>
                            <th style="width: 16%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Department</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">State</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Supervisor Responsible</th>
                            <th style="width: 16%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Ungraded Month</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Days Pending</th>
                            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Action</th>
                        </tr>
                    </thead>
                    <tbody id="pdpBehavioralTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         PANEL 5 – PERFORMANCE REPORTING ENGINE
         HR: Generate report for staff performance (Monthly, quarterly and annually)
         ══════════════════════════════════════════════════════════════════ -->
    <div id="pdpPanelHrReporting" class="pdp-panel" style="display: none;">
        <div class="card" style="padding: 24px; max-width: 820px; margin: 0 auto; box-sizing: border-box;">
            <div class="card-header" style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--surface2);">
                <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--accent);">
                    <i class="fa-solid fa-chart-pie me-1"></i> Institutional Performance Reporting Engine
                </div>
                <p style="margin: 4px 0 0; font-size: 12px; color: var(--text-muted);">
                    Generate comprehensive staff performance dossiers across monthly appraisals, quarterly benchmarks, or annual COP appraisals.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">1. Reporting Frequency *</label>
                    <select id="reportEnginePeriod" style="width: 100%; height: 38px; padding: 0 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); font-weight: 700;">
                        <option value="monthly">📅 Monthly Appraisal Report (March 2026)</option>
                        <option value="quarterly">📈 Quarterly Performance Review (Q2 Jan–Mar 2026)</option>
                        <option value="annually" selected>🏆 Annual COP Performance Dossier (FY2025/2026)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">2. State Cluster Filter</label>
                    <select id="reportEngineState" style="width: 100%; height: 38px; padding: 0 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                        <option value="">All 6 State Offices (490 Personnel)</option>
                        <option value="Lagos">Lagos Office (Cluster A)</option>
                        <option value="Kano">Kano Office (Cluster B)</option>
                        <option value="Rivers">Rivers Office (Cluster C)</option>
                        <option value="Abuja">Abuja FCT Headquarters</option>
                        <option value="Kaduna">Kaduna Regional Office</option>
                        <option value="Borno">Borno Field Office</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">3. Departmental Scope</label>
                    <select id="reportEngineDept" style="width: 100%; height: 38px; padding: 0 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                        <option value="">All Departments & Cadres</option>
                        <option value="Clinical">Clinical Services</option>
                        <option value="Finance">Finance & Administration</option>
                        <option value="Operations">Field Operations & Logistics</option>
                        <option value="SI">Strategic Information & M&E</option>
                        <option value="HR">Human Resources</option>
                        <option value="Programme">Programme Delivery</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">4. Score Breakdown Inclusion</label>
                    <div style="display: flex; gap: 10px; margin-top: 8px; font-size: 11px; color: var(--text);">
                        <label><input type="checkbox" checked disabled> Objectives (/60)</label>
                        <label><input type="checkbox" checked disabled> Behaviour (/40)</label>
                        <label><input type="checkbox" checked disabled> Innovation (/50)</label>
                    </div>
                </div>
            </div>

            <div style="background: var(--surface2); padding: 14px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; font-size: 12px; line-height: 1.5;">
                <div style="font-weight: 700; color: var(--accent); margin-bottom: 2px;">Summary of Generated Institutional Dataset:</div>
                <div style="color: var(--text-muted);">
                    The exported report includes staff demographic roster, supervisor grading metrics, behavioural performance averages, creativity bonus approvals, and overall ranking out of 150 points.
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                <button class="btn btn-outline" onclick="triggerEngineReport('csv')" style="font-size: 12px; font-weight: 700;">
                    <i class="fa-solid fa-file-csv me-1"></i> Export Raw CSV
                </button>
                <button class="btn btn-outline" onclick="triggerEngineReport('excel')" style="font-size: 12px; font-weight: 700;">
                    <i class="fa-solid fa-file-excel me-1"></i> Export Excel Workbook
                </button>
                <button class="btn btn-primary" onclick="triggerEngineReport('pdf')" style="font-size: 12px; font-weight: 700; padding: 10px 20px;">
                    <i class="fa-solid fa-file-pdf me-1"></i> Generate Official PDF Dossier
                </button>
            </div>
        </div>
    </div>

</div>

<!-- REPORT MODAL DIALOG -->
<div class="modal-overlay" id="pdpReportModal" style="display: none;" onclick="if(event.target===this)closeModal('pdpReportModal')">
    <div class="modal-dialog" style="max-width: 500px; width: 95%;">
        <div class="modal-header">
            <span class="modal-title" id="pdpReportModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800;">Generate Performance Report</span>
            <button class="modal-close" onclick="closeModal('pdpReportModal')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px; color: var(--text);">
            <div style="margin-bottom: 12px;" id="pdpReportModalDesc">
                Select your export format for the staff performance appraisal report.
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <button class="btn btn-primary" onclick="downloadSelectedReport('pdf')" style="justify-content: center; padding: 12px; font-weight: 700;">
                    <i class="fa-solid fa-file-pdf me-2"></i> Download Official PDF Report
                </button>
                <button class="btn btn-outline" onclick="downloadSelectedReport('excel')" style="justify-content: center; padding: 12px; font-weight: 700;">
                    <i class="fa-solid fa-file-excel me-2"></i> Download Master Excel Ledger (490 Staff)
                </button>
                <button class="btn btn-outline" onclick="downloadSelectedReport('csv')" style="justify-content: center; padding: 12px; font-weight: 700;">
                    <i class="fa-solid fa-file-csv me-2"></i> Export Clean CSV File
                </button>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline btn-sm" onclick="closeModal('pdpReportModal')">Cancel</button>
        </div>
    </div>
</div>

<script>
    var ACTIVE_REPORT_TYPE = 'annually';

    function switchPdpTab(tabKey) {
        document.querySelectorAll('.pdp-panel').forEach(function(p) { p.style.display = 'none'; });
        document.querySelectorAll('#pdpSubTabs .tab').forEach(function(b) {
            b.classList.remove('active');
            b.style.background = 'var(--surface)';
            b.style.color = 'var(--text-dim)';
        });

        var map = {
            'hr-master':     { panel: 'pdpPanelHrMaster', btn: 'pdpTabHrMaster' },
            'hr-pending':    { panel: 'pdpPanelHrPending', btn: 'pdpTabHrPending' },
            'hr-approvals':  { panel: 'pdpPanelHrApprovals', btn: 'pdpTabHrApprovals' },
            'hr-behavioral': { panel: 'pdpPanelHrBehavioral', btn: 'pdpTabHrBehavioral' },
            'hr-reporting':  { panel: 'pdpPanelHrReporting', btn: 'pdpTabHrReporting' }
        };

        if (map[tabKey]) {
            var pnl = document.getElementById(map[tabKey].panel);
            var btn = document.getElementById(map[tabKey].btn);
            if (pnl) pnl.style.display = 'block';
            if (btn) {
                btn.classList.add('active');
                btn.style.background = 'var(--accent)';
                btn.style.color = '#fff';
            }
        }
    }

    // 1. All Submitted PDPs Data
    var PDP_ROSTER = [
        { name: 'Fatima Bello', dept: 'Clinical Services', state: 'Lagos', obj: 48.0, beh: 34.0, inn: 42.0, total: 124.0, status: 'Approved' },
        { name: 'Ibrahim Garba', dept: 'Strategic Information', state: 'Kano', obj: null, beh: null, inn: null, total: null, status: 'Draft' },
        { name: 'Ngozi Okoro', dept: 'Finance & Admin', state: 'Rivers', obj: 52.0, beh: null, inn: 40.0, total: 92.0, status: 'Review' },
        { name: 'Umar Farouk', dept: 'Operations', state: 'Kaduna', obj: 38.0, beh: 28.0, inn: null, total: 66.0, status: 'Review' },
        { name: 'Amaka Obi', dept: 'Compliance', state: 'Kano', obj: 55.0, beh: 36.0, inn: 44.0, total: 135.0, status: 'Approved' },
        { name: 'Emeka Eze', dept: 'Programme', state: 'Borno', obj: 42.0, beh: 30.0, inn: 38.0, total: 110.0, status: 'Approved' },
        { name: 'Halima Suleiman', dept: 'HR', state: 'Borno', obj: null, beh: null, inn: null, total: null, status: 'Draft' },
        { name: 'Chidi Okafor', dept: 'Operations', state: 'Rivers', obj: 50.0, beh: 35.0, inn: 40.0, total: 125.0, status: 'Approved' },
        { name: 'Hassan Suleiman', dept: 'Field Outreach', state: 'Kaduna', obj: 40.0, beh: 28.0, inn: 32.0, total: 100.0, status: 'Review' },
        { name: 'Amina Bello', dept: 'Programme', state: 'Lagos', obj: 58.0, beh: 38.0, inn: 46.0, total: 142.0, status: 'Approved' }
    ];

    // 2. Pending Submissions (Staff yet to submit objectives)
    var PDP_PENDING_LIST = [
        { name: 'Ibrahim Garba', dept: 'Strategic Information', state: 'Kano', supervisor: 'Dr. Musa Aliyu', days: 24, status: 'Draft' },
        { name: 'Halima Suleiman', dept: 'Human Resources', state: 'Borno', supervisor: 'Fatima Bakura', days: 30, status: 'Unsubmitted' },
        { name: 'Usman Danladi', dept: 'Clinical Outreach', state: 'Kaduna', supervisor: 'Hassan Suleiman', days: 18, status: 'Draft' },
        { name: 'Khadija Sani', dept: 'Finance & Accounts', state: 'Lagos', supervisor: 'Biodun Alade', days: 12, status: 'Draft' },
        { name: 'Peter Obi', dept: 'Logistics & Fleet', state: 'Rivers', supervisor: 'Chidi Okafor', days: 21, status: 'Unsubmitted' },
        { name: 'Zainab Ahmed', dept: 'Nursing & Care', state: 'Abuja FCT', supervisor: 'Amaka Okonkwo', days: 15, status: 'Draft' }
    ];

    // 3. Supervisor & HOD Approval Backlog (Leadership yet to approve)
    var PDP_APPROVALS_BACKLOG = [
        { reviewer: 'Dr. Musa Aliyu', role: 'State Supervisor', dept: 'Clinical Services', state: 'Kano', staff: 'Amina Musa (Nurse Lead)', item: 'Supervisor Objectives (/60)', days: 14 },
        { reviewer: 'Dr. Biodun Ojo', role: 'Head of Department (HOD)', dept: 'Medical & Clinical', state: 'Abuja HQ', staff: 'Fatima Bello (Senior Doctor)', item: 'Creativity & Innovation (/50)', days: 19 },
        { reviewer: 'Emeka Nwosu', role: 'State Supervisor', dept: 'Operations', state: 'Rivers', staff: 'Chukwuma Eze (Field Driver)', item: 'Monthly Proof Evidence Verification', days: 11 },
        { reviewer: 'Hassan Suleiman', role: 'State Supervisor', dept: 'Strategic Information', state: 'Kaduna', staff: 'Aliyu Usman (Data Clerk)', item: 'Supervisor Objectives (/60)', days: 16 },
        { reviewer: 'Ngozi Adeyemi', role: 'Head of Department (HOD)', dept: 'Finance & Admin', state: 'Lagos', staff: 'Kelechi Madu (Finance Lead)', item: 'Creativity & Innovation (/50)', days: 22 }
    ];

    // 4. Staff Monthly Behavioural Performances Yet to be Graded
    var PDP_UNGRADED_BEHAVIORAL_LIST = [
        { name: 'Ngozi Okoro', dept: 'Finance & Admin', state: 'Rivers', supervisor: 'Chidi Okafor', month: 'March 2026', days: 12 },
        { name: 'Umar Farouk', dept: 'Operations', state: 'Kaduna', supervisor: 'Hassan Suleiman', month: 'March 2026', days: 14 },
        { name: 'Kelechi Madu', dept: 'Finance', state: 'Lagos', supervisor: 'Ngozi Adeyemi', month: 'February 2026', days: 32 },
        { name: 'Bala Mohammed', dept: 'Field Outreach', state: 'Kaduna', supervisor: 'Hassan Suleiman', month: 'March 2026', days: 9 },
        { name: 'Amina Kyari', dept: 'Clinical Care', state: 'Borno', supervisor: 'Fatima Bakura', month: 'February 2026', days: 28 },
        { name: 'Yusuf Bukar', dept: 'Facility Safety', state: 'Borno', supervisor: 'Fatima Bakura', month: 'March 2026', days: 10 }
    ];

    function filterPdpRoster() {
        var stateF = (document.getElementById('pdpStateFilter') || {}).value || '';
        var statF = (document.getElementById('pdpStatusFilter') || {}).value || '';
        var data = PDP_ROSTER.filter(function(r){
            return (!stateF || r.state === stateF) && (!statF || r.status === statF);
        });
        var tbody = document.getElementById('pdpRosterBody');
        if (!tbody) return;
        var count = document.getElementById('pdpRosterCount');
        if (count) count.textContent = data.length;
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var r = data[i];
            var sc = r.total !== null ? r.total.toFixed(1) : 'Pending';
            var pct = r.total !== null ? (r.total / 150 * 100).toFixed(0) + '%' : '—';
            var scColor = r.total >= 120 ? 'var(--success)' : r.total >= 80 ? 'var(--accent)' : r.total ? 'var(--warning)' : 'var(--text-muted)';
            var statPill = r.status === 'Approved' ? '<span class="pill pill-closed">Approved</span>' : r.status === 'Review' ? '<span class="pill pill-progress">Review</span>' : '<span class="pill pill-open">Draft</span>';
            html += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                '<td style="font-weight: 700; padding: 10px 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' + r.name + '</td>' +
                '<td style="font-size: 11px; padding: 10px 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' + r.dept + '</td>' +
                '<td style="padding: 10px 8px; white-space: nowrap;">' + r.state + '</td>' +
                '<td style="text-align: center; padding: 10px 8px; font-size: 11px;">' + (r.obj !== null ? r.obj.toFixed(1) : '<span style="color: var(--text-muted);">—</span>') + '</td>' +
                '<td style="text-align: center; padding: 10px 8px; font-size: 11px;">' + (r.beh !== null ? r.beh.toFixed(1) : '<span style="color: var(--text-muted);">—</span>') + '</td>' +
                '<td style="text-align: center; padding: 10px 8px; font-size: 11px;">' + (r.inn !== null ? r.inn.toFixed(1) : '<span style="color: var(--text-muted);">—</span>') + '</td>' +
                '<td style="text-align: center; font-weight: 700; color: ' + scColor + '; padding: 10px 8px;">' + sc + '</td>' +
                '<td style="text-align: center; font-weight: 700; padding: 10px 8px;">' + pct + '</td>' +
                '<td style="text-align: center; padding: 10px 8px; white-space: nowrap;">' + statPill + '</td>' +
                '<td style="text-align: center; padding: 10px 8px;"><button class="btn btn-outline btn-sm" onclick="alert(\'Viewing PDP Appraisal Record for ' + r.name + '\')" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-eye"></i></button></td>' +
            '</tr>';
        }
        tbody.innerHTML = html || '<tr><td colspan="10" style="text-align: center; padding: 20px; color: var(--text-muted);">No records match filter.</td></tr>';
    }

    function renderPendingPdpTable() {
        var tbody = document.getElementById('pdpPendingTableBody');
        if (!tbody) return;
        tbody.innerHTML = PDP_PENDING_LIST.map(function(item) {
            return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                '<td style="font-weight: 700; padding: 10px 8px;">' + item.name + '</td>' +
                '<td style="font-size: 11px; padding: 10px 8px;">' + item.dept + '</td>' +
                '<td style="padding: 10px 8px;">' + item.state + '</td>' +
                '<td style="padding: 10px 8px; color: var(--text-muted);">' + item.supervisor + '</td>' +
                '<td style="text-align: center; font-weight: 700; color: var(--danger); padding: 10px 8px;">' + item.days + ' days</td>' +
                '<td style="text-align: center; padding: 10px 8px;"><span class="pill pill-open">' + item.status + '</span></td>' +
                '<td style="text-align: center; padding: 10px 8px;"><button class="btn btn-outline btn-sm" onclick="sendStaffNudge(\'' + item.name + '\')" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-bell"></i> Nudge</button></td>' +
            '</tr>';
        }).join('');
    }

    function renderApprovalsBacklogTable() {
        var tbody = document.getElementById('pdpApprovalsTableBody');
        if (!tbody) return;
        tbody.innerHTML = PDP_APPROVALS_BACKLOG.map(function(item) {
            return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                '<td style="font-weight: 700; padding: 10px 8px; color: var(--accent);">' + item.reviewer + '</td>' +
                '<td style="font-size: 11px; padding: 10px 8px; font-weight: 600;">' + item.role + '</td>' +
                '<td style="font-size: 11px; padding: 10px 8px;">' + item.dept + ' (' + item.state + ')</td>' +
                '<td style="padding: 10px 8px;">' + item.staff + '</td>' +
                '<td style="text-align: center; padding: 10px 8px;"><span class="pill pill-progress" style="font-size: 10px;">' + item.item + '</span></td>' +
                '<td style="text-align: center; font-weight: 700; color: var(--danger); padding: 10px 8px;">' + item.days + ' days</td>' +
                '<td style="text-align: center; padding: 10px 8px;"><button class="btn btn-outline btn-sm" onclick="nudgeReviewer(\'' + item.reviewer + '\')" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-paper-plane"></i> Escalate</button></td>' +
            '</tr>';
        }).join('');
    }

    function renderBehavioralAuditTable() {
        var tbody = document.getElementById('pdpBehavioralTableBody');
        if (!tbody) return;
        tbody.innerHTML = PDP_UNGRADED_BEHAVIORAL_LIST.map(function(item) {
            return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                '<td style="font-weight: 700; padding: 10px 8px;">' + item.name + '</td>' +
                '<td style="font-size: 11px; padding: 10px 8px;">' + item.dept + '</td>' +
                '<td style="padding: 10px 8px;">' + item.state + '</td>' +
                '<td style="padding: 10px 8px; color: var(--accent); font-weight: 600;">' + item.supervisor + '</td>' +
                '<td style="text-align: center; padding: 10px 8px;"><span class="badge" style="background:#ede9fe; color:#7c3aed; font-weight:700;">' + item.month + '</span></td>' +
                '<td style="text-align: center; font-weight: 700; color: var(--danger); padding: 10px 8px;">' + item.days + ' days overdue</td>' +
                '<td style="text-align: center; padding: 10px 8px;"><button class="btn btn-outline btn-sm" onclick="alert(\'Alert sent to supervisor ' + item.supervisor + ' for grading ' + item.name + ' (Behavioural ' + item.month + ')\')" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-bell"></i> Remind</button></td>' +
            '</tr>';
        }).join('');
    }

    function sendStaffNudge(name) {
        alert('Submission reminder email notification dispatched to ' + name + '.');
    }

    function broadcastPendingNudge() {
        alert('Dispatched automated submission reminder broadcast to all 92 staff with pending PDP submissions.');
    }

    function nudgeReviewer(name) {
        alert('Escalation reminder dispatched to ' + name + ' to expedite pending appraisal review.');
    }

    function openPdpReportModal(type) {
        ACTIVE_REPORT_TYPE = type;
        var titleMap = {
            'monthly': 'Generate Monthly Performance Appraisal Report (March 2026)',
            'quarterly': 'Generate Quarterly Performance Review Dossier (Q2 FY2026)',
            'annually': 'Generate Institutional Annual COP Appraisal Dossier (FY2025/2026)'
        };
        var titleEl = document.getElementById('pdpReportModalTitle');
        if (titleEl) titleEl.textContent = titleMap[type] || 'Generate Performance Report';
        openModal('pdpReportModal');
    }

    function downloadSelectedReport(format) {
        alert('Generating ' + ACTIVE_REPORT_TYPE.toUpperCase() + ' Staff Performance Report in ' + format.toUpperCase() + ' format. Download starting in background...');
        closeModal('pdpReportModal');
    }

    function exportPdpReport(scope, format) {
        if (scope === 'all') {
            alert('Generating Master Institutional Report on ALL 398 Submitted PDPs (' + format.toUpperCase() + ')...');
        } else if (scope === 'pending') {
            alert('Generating Delinquent & Pending PDP Submissions Report (92 Staff) in ' + format.toUpperCase() + '...');
        } else if (scope === 'approvals') {
            alert('Generating Supervisor & HOD Approval Bottleneck Dossier in ' + format.toUpperCase() + '...');
        } else if (scope === 'behavioral') {
            alert('Drilling Monthly Behavioural Competency Audit Dossier in ' + format.toUpperCase() + '...');
        }
    }

    function triggerEngineReport(format) {
        var period = (document.getElementById('reportEnginePeriod') || {}).value || 'annually';
        var state = (document.getElementById('reportEngineState') || {}).value || 'All States';
        var dept = (document.getElementById('reportEngineDept') || {}).value || 'All Departments';
        alert('Engine Report Generated:\n\nPeriod: ' + period.toUpperCase() + '\nScope: ' + state + ' · ' + dept + '\nFormat: ' + format.toUpperCase() + '\n\nFile successfully compiled and ready for download.');
    }

    window.initPdpModule = function() {
        var role = window.CURRENT_USER_ROLE || 'hr';
        var ind = document.getElementById('pdpRoleScopeIndicator');
        var headerActions = document.getElementById('pdpHeaderActions');

        if (ind) {
            if (role === 'hr') {
                ind.innerHTML = '<div style="margin-top: 6px; padding: 6px 12px; background: rgba(124,58,237,0.08); border-left: 4px solid var(--accent2); border-radius: 6px; font-size: 11px; color: var(--accent2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">' +
                    '<div style="display: flex; align-items: center; gap: 8px;">' +
                        '<i class="fa-solid fa-clipboard-list" style="font-size: 13px;"></i>' +
                        '<div><strong>HR Performance Command:</strong> Full oversight across submitted PDPs, pending submissions, supervisor approval bottlenecks, and behavioral competency audits.</div>' +
                    '</div>' +
                    '<span style="font-size: 10px; font-weight: 700; background: #ede9fe; color: #6d28d9; padding: 2px 8px; border-radius: 4px; border: 1px solid #c4b5fd;">HR GOVERNANCE</span>' +
                '</div>';
            } else if (role === 'doc') {
                ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: rgba(2,54,123,0.08); color: var(--accent); border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Institutional appraisal oversight and compliance grading governance.</div>';
            }
        }

        if (headerActions) {
            headerActions.style.display = (role === 'hr' || role === 'doc') ? 'flex' : 'none';
        }

        // Render Tables
        filterPdpRoster();
        renderPendingPdpTable();
        renderApprovalsBacklogTable();
        renderBehavioralAuditTable();

        if (role === 'hr' || role === 'doc' || role === 'superadmin') {
            switchPdpTab('hr-master');
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.initPdpModule();
    });
</script>

@endsection
