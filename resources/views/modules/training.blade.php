@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     TRAINING MODULE – CCCRN Staff Training & Compliance Academy
     Role-aware: staff | state_lead | hr | compliance_officer | doc
     ═══════════════════════════════════════════════════════════════ --}}

<div style="padding-bottom: 50px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="trainingModuleContainer">

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.25rem;">
        <div style="display:flex;align-items:center;gap:.75rem;">
            <div style="width:40px;height:40px;border-radius:8px;background:var(--accent);display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253"/>
                </svg>
            </div>
            <div>
                <h2 style="margin:0;font-size:1.2rem;font-weight:700;color:var(--text);">Staff Training & Compliance Academy</h2>
                <p style="margin:0;font-size:.78rem;color:var(--text-muted);">Workforce certification, course management, state performance, and compliance audits</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
            <span id="trainingRoleBadge" style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .75rem;border-radius:999px;background:#e0e7ff;font-size:.72rem;font-weight:600;color:#3730a3;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                <span id="trainingRoleBadgeText">Staff</span>
            </span>
            <button id="btnOpenAddModal" class="btn btn-primary btn-sm" onclick="openModal('addModuleModal')" style="display:none;font-size:11px;font-weight:700;">
                <i class="fa-solid fa-plus me-1"></i> Add Training Module
            </button>
            <button id="btnExportReport" class="btn btn-outline btn-sm" onclick="trainingExportPDF()" style="display:none;font-size:11px;font-weight:700;">
                <i class="fa-solid fa-file-pdf me-1"></i> Generate Report
            </button>
            <button id="btnExportCSV" class="btn btn-outline btn-sm" onclick="trainingExportCSV()" style="display:none;font-size:11px;font-weight:700;">
                <i class="fa-solid fa-file-excel me-1"></i> CSV Export
            </button>
        </div>
    </div>

    {{-- ── Email Broadcast Banner (staff only) ─────────────────── --}}
    <div id="trainingEmailBroadcastBanner" style="display:none;margin-bottom:1rem;padding:.8rem 1rem;border-radius:8px;background:#eff6ff;border:1px solid #bfdbfe;align-items:center;gap:.6rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <div>
            <span style="font-size:.8rem;font-weight:600;color:#1e40af;">Training Reminder Broadcast</span>
            <span style="font-size:.78rem;color:#3b82f6;margin-left:.5rem;">An email reminder was sent to all staff with outstanding training modules. Check your inbox.</span>
        </div>
    </div>

    {{-- ── Stat Cards ───────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:1rem;margin-bottom:1.25rem;">
        <div class="card" style="padding:1rem;text-align:center;">
            <p style="margin:0 0 .3rem;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700;">Total Courses</p>
            <p id="statTotalCourses" style="margin:0;font-size:1.8rem;font-weight:800;color:var(--accent);line-height:1;">5</p>
            <p style="margin:.3rem 0 0;font-size:.7rem;color:var(--text-muted);">Active curriculum</p>
        </div>
        <div class="card" style="padding:1rem;text-align:center;">
            <p style="margin:0 0 .3rem;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700;">Staff Completed All</p>
            <p id="statStaffCompleted" style="margin:0;font-size:1.8rem;font-weight:800;color:var(--success);line-height:1;">279</p>
            <p style="margin:.3rem 0 0;font-size:.7rem;color:var(--success);font-weight:600;">57% Fully Compliant</p>
        </div>
        <div class="card" style="padding:1rem;text-align:center;">
            <p style="margin:0 0 .3rem;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700;">Overdue Staff</p>
            <p id="statOverdue" style="margin:0;font-size:1.8rem;font-weight:800;color:var(--danger);line-height:1;">166</p>
            <p style="margin:.3rem 0 0;font-size:.7rem;color:var(--danger);font-weight:600;">Yet to complete</p>
        </div>
        <div class="card" style="padding:1rem;text-align:center;">
            <p style="margin:0 0 .3rem;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700;">Top Performing State</p>
            <p id="statTopState" style="margin:0;font-size:1.8rem;font-weight:800;color:var(--warning);line-height:1;">Lagos</p>
            <p style="margin:.3rem 0 0;font-size:.7rem;color:var(--warning);font-weight:600;">86% Completion</p>
        </div>
    </div>

    {{-- ── Tab Navigation ───────────────────────────────────────── --}}
    <div style="display:flex;gap:.35rem;flex-wrap:wrap;margin-bottom:1rem;border-bottom:2px solid var(--border);padding-bottom:0;">
        <button id="tabTrainingDashboard" class="tab active" onclick="switchTrainingTab('training-dashboard')" style="display:inline-flex;align-items:center;gap:.4rem;">
            📈 Training Dashboard
        </button>
        <button id="tabCurriculum" class="tab" onclick="switchTrainingTab('curriculum')" style="display:inline-flex;align-items:center;gap:.4rem;">
            📚 Training Curriculum
        </button>
        <button id="tabStatePerformance" class="tab" onclick="switchTrainingTab('state-performance')" style="display:none;">
            📊 State Performance & Outstanding Staff
        </button>
        <button id="tabMasterRoster" class="tab" onclick="switchTrainingTab('master-roster')" style="display:none;">
            📋 Completed Training & Reports
        </button>
        <button id="tabMyAttendance" class="tab" onclick="switchTrainingTab('my-attendance')" style="display:none;">
            🎓 My Attendance & Certs
        </button>
        <button id="tabLogAttendance" class="tab" onclick="switchTrainingTab('log-attendance')" style="display:none;">
            ✏️ Log Attendance
        </button>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         TAB 0 – Training Dashboard (HR & Leadership)
         ════════════════════════════════════════════════════════════ --}}
    <div id="trainingSection-training-dashboard" class="training-tab-content" style="display:block;">
        <!-- Top Institutional Health Banner -->
        <div style="background: linear-gradient(135deg, #022b61 0%, #02367B 60%, #0077b6 100%); color: #ffffff; padding: 20px 24px; border-radius: 12px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span style="background: #55E2E9; color: #02367B; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 4px; text-transform: uppercase;">
                        Compliance Academy FY2026
                    </span>
                    <span style="font-size: 12px; opacity: 0.85;">Institution-Wide Monitoring</span>
                </div>
                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 19px; font-weight: 800; margin: 2px 0 4px; color: #ffffff;">
                    Workforce Compliance & Certification Pulse
                </h3>
                <div style="font-size: 12px; opacity: 0.9;">
                    Mandatory USAID, PSEA, and Institutional Ethics training across all 490 personnel and 6 state clusters.
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <button class="btn btn-outline btn-sm" style="background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.4); color: #fff; font-size: 11px; font-weight: 700;" onclick="trainingExportPDF()">
                    <i class="fa-solid fa-file-pdf me-1"></i> Generate Completion Dossier
                </button>
                <button class="btn btn-outline btn-sm" style="background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.4); color: #fff; font-size: 11px; font-weight: 700;" onclick="broadcastTrainingReminder()">
                    <i class="fa-solid fa-bell me-1"></i> Broadcast Reminder
                </button>
            </div>
        </div>

        <!-- 2 Column Overview -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 20px;">
            <!-- Left: Course Completion Matrix -->
            <div class="card" style="margin-bottom: 0; padding: 18px 20px;">
                <div class="card-header" style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--surface2);">
                    <span class="card-title" style="font-size: 13px; font-weight: 700;"><i class="fa-solid fa-book-bookmark" style="color: var(--accent);"></i> Curriculum Completion by Course</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;" id="trainingDashboardCourseList">
                    <!-- Populated dynamically -->
                </div>
            </div>

            <!-- Right: State Cluster Ranking -->
            <div class="card" style="margin-bottom: 0; padding: 18px 20px;">
                <div class="card-header" style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--surface2);">
                    <span class="card-title" style="font-size: 13px; font-weight: 700;"><i class="fa-solid fa-ranking-star" style="color: var(--warning);"></i> State Compliance Leaderboard</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 10px;" id="trainingDashboardStateRanking">
                    <!-- Populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         TAB 1 – Training Curriculum (HR: Add & View Only; No Edit/Delete)
         ════════════════════════════════════════════════════════════ --}}
    <div id="trainingSection-curriculum" class="training-tab-content" style="display:none;">
        <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box;">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--surface2);">
                <div class="card-title" style="margin:0;font-size:14px;font-weight:700;">
                    <i class="fa-solid fa-graduation-cap" style="color:var(--accent);"></i> Institutional Training Curriculum
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="text" id="curriculumSearch" placeholder="Search modules, categories…" oninput="filterCurriculum(this.value)"
                        style="padding:.35rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.8rem;background:var(--surface);color:var(--text);width:220px;">
                    <button class="btn btn-primary btn-sm" onclick="openModal('addModuleModal')" style="font-size:11px;font-weight:700;">
                        <i class="fa-solid fa-plus me-1"></i> Add Module
                    </button>
                </div>
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: .82rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border); background: var(--surface2);">
                            <th style="width:8%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">ID</th>
                            <th style="width:25%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Module Name</th>
                            <th style="width:16%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Category</th>
                            <th style="width:8%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Duration</th>
                            <th style="width:10%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Type</th>
                            <th style="width:13%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Completion</th>
                            <th style="width:7%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Enrolled</th>
                            <th style="width:10%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Deadline</th>
                            <th style="width:8%;padding:.6rem .75rem;text-align:center;color:var(--text-muted);font-weight:600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="curriculumTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         TAB 2 – My Attendance & Completed Training (Staff only)
         ════════════════════════════════════════════════════════════ --}}
    <div id="trainingSection-my-attendance" class="training-tab-content" style="display:none;">
        <div class="card" style="margin-bottom:1rem;padding:18px 20px;overflow:hidden;width:100%;box-sizing:border-box;">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--surface2);">
                <span class="card-title" style="margin:0;font-size:14px;font-weight:700;">🎓 My Attended & Completed Training</span>
                <span id="myCompletedCountBadge" style="background:var(--accent);color:#fff;font-size:.72rem;font-weight:700;padding:.2rem .65rem;border-radius:999px;">0</span>
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: .82rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border); background: var(--surface2);">
                            <th style="width:12%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Ref</th>
                            <th style="width:32%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Module</th>
                            <th style="width:16%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Date Attended</th>
                            <th style="width:12%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">State</th>
                            <th style="width:14%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Status</th>
                            <th style="width:14%;padding:.6rem .75rem;text-align:center;color:var(--text-muted);font-weight:600;">Certificate</th>
                        </tr>
                    </thead>
                    <tbody id="myCertificatesTableBody"></tbody>
                </table>
            </div>
        </div>

        <div class="card" style="padding:18px 20px;">
            <div class="card-header"><span class="card-title">📌 Attend a Training</span></div>
            <div style="padding:1rem;display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
                <div style="flex:1;min-width:200px;">
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;">Select Module</label>
                    <select id="attendModuleSelect" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.82rem;background:var(--surface);color:var(--text);">
                        <option value="">-- Choose a module --</option>
                        <option value="TR-01">TR-01: Anti-Fraud &amp; Ethics</option>
                        <option value="TR-02">TR-02: PSEA &amp; Safeguarding</option>
                        <option value="TR-03">TR-03: Data Protection &amp; Privacy</option>
                        <option value="TR-04">TR-04: USAID Procurement Compliance</option>
                        <option value="TR-05">TR-05: Travel &amp; Field Safety Policy</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-sm" onclick="staffAttendTraining()">✔ Mark Attendance</button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         TAB 3 – State Performance & Outstanding Staff (Filterable by Course)
         ════════════════════════════════════════════════════════════ --}}
    <div id="trainingSection-state-performance" class="training-tab-content" style="display:none;">

        <!-- Interactive Course Selector: See list of staff and state yet to complete selected training -->
        <div class="card" style="margin-bottom: 16px; padding: 14px 18px; background: #f0f7ff; border: 1px solid #bfdbfe;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-filter" style="color: var(--accent); font-size: 14px;"></i>
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--accent);">Inspect Outstanding Staff & State Compliance by Course:</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Select any training module to drill down into states and staff yet to complete.</div>
                    </div>
                </div>
                <select id="selectTrainingCourseFilter" onchange="filterIncompleteByCourse(this.value)" style="padding: 7px 12px; font-size: 12px; font-weight: 700; border: 1px solid #93c5fd; border-radius: 6px; background: #ffffff; color: var(--accent); min-width: 300px;">
                    <option value="ALL">All Training Modules (166 Total Outstanding)</option>
                    <option value="TR-01">TR-01: Anti-Fraud & Ethics (137 yet to complete)</option>
                    <option value="TR-02">TR-02: PSEA & Safeguarding Standards (206 yet to complete)</option>
                    <option value="TR-03">TR-03: Data Protection & Privacy (269 yet to complete)</option>
                    <option value="TR-04">TR-04: USAID Procurement Compliance (59 yet to complete)</option>
                    <option value="TR-05">TR-05: Travel & Field Safety Policy (181 yet to complete)</option>
                </select>
            </div>
        </div>

        <!-- State Performance Summary -->
        <div class="card" style="margin-bottom:18px;padding:18px 20px;overflow:hidden;width:100%;box-sizing:border-box;">
            <div class="card-header" style="margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--surface2);">
                <span class="card-title" style="margin:0;font-size:14px;font-weight:700;"><i class="fa-solid fa-map-location-dot" style="color:var(--accent);"></i> State Performance Summary</span>
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: .82rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border); background: var(--surface2);">
                            <th style="width:18%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">State</th>
                            <th style="width:16%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Total Staff</th>
                            <th style="width:16%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Trained</th>
                            <th style="width:18%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Yet to Complete</th>
                            <th style="width:14%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Completion Rate</th>
                            <th style="width:18%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Progress</th>
                        </tr>
                    </thead>
                    <tbody id="statePerformanceTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Outstanding Staff Yet to Complete Selected Training -->
        <div class="card" style="padding:18px 20px;overflow:hidden;width:100%;box-sizing:border-box;">
            <div class="card-header" style="margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--surface2);display:flex;justify-content:space-between;align-items:center;">
                <span class="card-title" style="margin:0;font-size:14px;font-weight:700;"><i class="fa-solid fa-triangle-exclamation" style="color:var(--danger);"></i> Staff Yet to Complete Selected Training</span>
                <button class="btn btn-outline btn-sm" onclick="broadcastTrainingReminder()" style="font-size:11px;font-weight:700;">
                    <i class="fa-solid fa-paper-plane me-1"></i> Send Reminders
                </button>
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: .82rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border); background: var(--surface2);">
                            <th style="width:20%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Staff Name</th>
                            <th style="width:18%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Role / Title</th>
                            <th style="width:18%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Department</th>
                            <th style="width:14%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">State Cluster</th>
                            <th style="width:20%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Missing Module</th>
                            <th style="width:10%;padding:.6rem .75rem;text-align:center;color:var(--text-muted);font-weight:600;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="incompleteStaffTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         TAB 4 – Log Attendance (staff & doc only)
         ════════════════════════════════════════════════════════════ --}}
    <div id="trainingSection-log-attendance" class="training-tab-content" style="display:none;">
        <div class="card" style="max-width:640px;padding:18px 20px;">
            <div class="card-header"><span class="card-title">✏️ Log Staff Attendance</span></div>
            <div style="padding:1rem 0;display:grid;gap:1rem;">
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;">Staff Full Name <span style="color:var(--danger);">*</span></label>
                    <input type="text" id="logStaffName" placeholder="e.g. Fatima Bello"
                        style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;">Staff Email <span style="color:var(--danger);">*</span></label>
                    <input type="email" id="logStaffEmail" placeholder="e.g. fatima@cccrn.org"
                        style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;">State <span style="color:var(--danger);">*</span></label>
                    <select id="logStaffState"
                        style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);">
                        <option value="">-- Select State --</option>
                        <option value="Lagos">Lagos</option>
                        <option value="Abuja">Abuja</option>
                        <option value="Rivers">Rivers</option>
                        <option value="Kano">Kano</option>
                        <option value="Kaduna">Kaduna</option>
                        <option value="Borno">Borno</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;">Training Module <span style="color:var(--danger);">*</span></label>
                    <select id="logStaffModule"
                        style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);">
                        <option value="">-- Select Module --</option>
                        <option value="TR-01">TR-01: Anti-Fraud &amp; Ethics</option>
                        <option value="TR-02">TR-02: PSEA &amp; Safeguarding</option>
                        <option value="TR-03">TR-03: Data Protection &amp; Privacy</option>
                        <option value="TR-04">TR-04: USAID Procurement Compliance</option>
                        <option value="TR-05">TR-05: Travel &amp; Field Safety Policy</option>
                    </select>
                </div>
                <div style="display:flex;justify-content:flex-end;gap:.5rem;padding-top:.25rem;">
                    <button class="btn btn-outline btn-sm" onclick="clearLogForm()">Clear</button>
                    <button class="btn btn-primary btn-sm" onclick="submitLogAttendance()">Submit Attendance</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         TAB 5 – Completed Training & Reports (HR Master Roster)
         ════════════════════════════════════════════════════════════ --}}
    <div id="trainingSection-master-roster" class="training-tab-content" style="display:none;">
        <div class="card" style="padding:18px 20px;overflow:hidden;width:100%;box-sizing:border-box;">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--surface2);">
                <div class="card-title" style="margin:0;font-size:14px;font-weight:700;">
                    <i class="fa-solid fa-clipboard-check" style="color:var(--success);"></i> Completed Training Roster & Verification Ledger
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="text" id="rosterSearch" placeholder="Search staff, email, module…" oninput="filterRoster(this.value)"
                        style="padding:.35rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.8rem;background:var(--surface);color:var(--text);width:200px;">
                    <button class="btn btn-primary btn-sm" onclick="trainingExportPDF()" style="font-size:11px;font-weight:700;">
                        <i class="fa-solid fa-file-pdf me-1"></i> PDF Report
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="trainingExportCSV()" style="font-size:11px;font-weight:700;">
                        <i class="fa-solid fa-file-excel me-1"></i> Excel / CSV
                    </button>
                </div>
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: .82rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border); background: var(--surface2);">
                            <th style="width:10%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Ref</th>
                            <th style="width:18%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Name</th>
                            <th style="width:20%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Email</th>
                            <th style="width:10%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">State</th>
                            <th style="width:18%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Module</th>
                            <th style="width:10%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Date</th>
                            <th style="width:8%;padding:.6rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;">Status</th>
                            <th style="width:6%;padding:.6rem .75rem;text-align:center;color:var(--text-muted);font-weight:600;">Verify</th>
                        </tr>
                    </thead>
                    <tbody id="masterRosterTableBody"></tbody>
                </table>
            </div>
            <div style="padding:10px 4px 0;font-size:11px;color:var(--text-muted);border-top:1px solid var(--surface2);margin-top:10px;">
                Verified staff training completions are synchronized in real-time with ComplianceIQ Master Records.
            </div>
        </div>
    </div>

</div>{{-- /trainingModuleContainer --}}

{{-- ════════════════════════════════════════════════════════════════
     MODAL – Add Training Module (HR & DoC)
     ════════════════════════════════════════════════════════════════ --}}
<div id="addModuleModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeModal('addModuleModal')">
    <div class="modal-dialog" style="max-width:520px;width:95%;">
        <div class="modal-header">
            <span class="modal-title" style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;">+ Add Training Module</span>
            <button class="modal-close" onclick="closeModal('addModuleModal')">&times;</button>
        </div>
        <div class="modal-body" style="display:grid;gap:.9rem;">
            <div>
                <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;font-weight:700;">Module ID <span style="color:var(--danger);">*</span></label>
                <input type="text" id="newModuleId" placeholder="e.g. TR-06"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);box-sizing:border-box;">
            </div>
            <div>
                <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;font-weight:700;">Module Title <span style="color:var(--danger);">*</span></label>
                <input type="text" id="newModuleName" placeholder="e.g. Conflict of Interest & Whistleblowing"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);box-sizing:border-box;">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;font-weight:700;">Category</label>
                    <select id="newModuleCategory" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);">
                        <option>Compliance &amp; Ethics</option>
                        <option>Safeguarding</option>
                        <option>Data Governance</option>
                        <option>Procurement</option>
                        <option>Operations</option>
                        <option>Finance</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;font-weight:700;">Duration</label>
                    <input type="text" id="newModuleDuration" placeholder="e.g. 2 hrs"
                        style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);box-sizing:border-box;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;font-weight:700;">Type</label>
                    <select id="newModuleType" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);">
                        <option>Mandatory</option>
                        <option>Elective</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;font-weight:700;">Completion Deadline</label>
                    <input type="date" id="newModuleDeadline"
                        style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);box-sizing:border-box;">
                </div>
            </div>
            <div>
                <label style="font-size:.78rem;color:var(--text-muted);display:block;margin-bottom:.3rem;font-weight:700;">Description & Learning Objectives</label>
                <textarea id="newModuleDesc" rows="2" placeholder="Course outline, key competencies assessed..."
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:var(--surface);color:var(--text);box-sizing:border-box;"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline btn-sm" onclick="closeModal('addModuleModal')">Cancel</button>
            <button class="btn btn-primary btn-sm" onclick="saveNewModule()">Save Module</button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     MODAL – View Module Syllabus Details (HR View-Only)
     ════════════════════════════════════════════════════════════════ --}}
<div id="viewModuleModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeModal('viewModuleModal')">
    <div class="modal-dialog" style="max-width:500px;width:95%;">
        <div class="modal-header">
            <span class="modal-title" id="viewModuleTitle" style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;">Module Details</span>
            <button class="modal-close" onclick="closeModal('viewModuleModal')">&times;</button>
        </div>
        <div class="modal-body" id="viewModuleBody" style="font-size:12px;color:var(--text);">
            <!-- Populated dynamically -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary btn-sm" onclick="closeModal('viewModuleModal')">Close</button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     JAVASCRIPT
     ════════════════════════════════════════════════════════════════ --}}
<script>
/* ─────────────────────────────────────────────────────────────────
   DATA
───────────────────────────────────────────────────────────────── */
var TRAINING_MODULES = [
    { id:'TR-01', name:'Anti-Fraud & Ethics',          category:'Compliance & Ethics', duration:'3hrs',   type:'Mandatory', completion:72, enrolled:490, deadline:'30 Sep 2026', desc:'Whistleblower mechanisms, bribery prevention, financial asset stewardship.' },
    { id:'TR-02', name:'PSEA & Safeguarding',          category:'Safeguarding',        duration:'2hrs',   type:'Mandatory', completion:58, enrolled:490, deadline:'15 Sep 2026', desc:'Prevention of Sexual Exploitation, Abuse, and Harassment protocols for field staff.' },
    { id:'TR-03', name:'Data Protection & Privacy',    category:'Data Governance',     duration:'1.5hrs', type:'Mandatory', completion:45, enrolled:490, deadline:'31 Oct 2026', desc:'NDPR compliance, client confidentiality, electronic records safeguarding.' },
    { id:'TR-04', name:'USAID Procurement Compliance', category:'Procurement',         duration:'2.5hrs', type:'Mandatory', completion:81, enrolled:310, deadline:'30 Sep 2026', desc:'Dual-authorization, competitive bidding rules, allowable expenditure standards.' },
    { id:'TR-05', name:'Travel & Field Safety Policy', category:'Operations',          duration:'1hr',    type:'Elective',  completion:63, enrolled:490, deadline:'31 Dec 2026', desc:'POL-TRV-03 travel regulations, trip advance retirals, vehicle security rules.' }
];

var ATTENDANCE_RECORDS = [
    { ref:'ATT-001', name:'Fatima Bello',    email:'staff@cccrn.org',   state:'Lagos',  module:'TR-01 Anti-Fraud & Ethics',          date:'14 Aug 2026', status:'Completed', verified:true  },
    { ref:'ATT-002', name:'Fatima Bello',    email:'staff@cccrn.org',   state:'Lagos',  module:'TR-02 PSEA & Safeguarding',           date:'20 Aug 2026', status:'Completed', verified:true  },
    { ref:'ATT-003', name:'Emeka Okafor',    email:'emeka@cccrn.org',   state:'Abuja',  module:'TR-04 USAID Procurement',             date:'22 Aug 2026', status:'Completed', verified:true  },
    { ref:'ATT-004', name:'Ngozi Adeyemi',   email:'ngozi@cccrn.org',   state:'Lagos',  module:'TR-01 Anti-Fraud & Ethics',          date:'25 Aug 2026', status:'Completed', verified:true  },
    { ref:'ATT-005', name:'Musa Ibrahim',    email:'musa@cccrn.org',    state:'Kano',   module:'TR-02 PSEA & Safeguarding',           date:'28 Aug 2026', status:'Completed', verified:true  },
    { ref:'ATT-006', name:'Chidi Okafor',    email:'chidi@cccrn.org',   state:'Rivers', module:'TR-03 Data Protection & Privacy',     date:'01 Sep 2026', status:'Completed', verified:true  },
    { ref:'ATT-007', name:'Fatima Bakura',   email:'fatima.b@cccrn.org',state:'Borno',  module:'TR-04 USAID Procurement',             date:'02 Sep 2026', status:'Completed', verified:false }
];

var STATE_STATS = [
    { state:'Lagos',  total:95, trained:82, rate:86, rank:1, incomplete:[{name:'Aisha Musa',     role:'Finance Officer', dept:'Finance',         missing:'TR-03 Data Protection'}] },
    { state:'Abuja',  total:72, trained:54, rate:75, rank:2, incomplete:[{name:'Biodun Alade',   role:'HR Associate',    dept:'Human Resources', missing:'TR-02 PSEA & Safeguarding'}] },
    { state:'Rivers', total:68, trained:51, rate:75, rank:3, incomplete:[{name:'Chidi Okeke',    role:'M&E Officer',     dept:'Strategy',        missing:'TR-01 Anti-Fraud & Ethics'}] },
    { state:'Kano',   total:80, trained:41, rate:51, rank:4, incomplete:[{name:'Aliyu Usman',    role:'Field Officer',   dept:'Operations',      missing:'TR-02 PSEA'}, {name:'Zainab Ahmed',   role:'Nurse',           dept:'Clinical',         missing:'TR-01 Anti-Fraud'}] },
    { state:'Kaduna', total:65, trained:28, rate:43, rank:5, incomplete:[{name:'Bala Mohammed',  role:'Field Officer',   dept:'Outreach',        missing:'TR-02 PSEA'}, {name:'Khadija Sani',   role:'Counselor',       dept:'Care',             missing:'TR-05 Travel Policy'}] },
    { state:'Borno',  total:65, trained:23, rate:35, rank:6, incomplete:[{name:'Yusuf Bukar',    role:'Community Lead',  dept:'Field Security',  missing:'TR-01 Anti-Fraud'}, {name:'Amina Kyari',    role:'Staff Nurse',     dept:'Clinical',         missing:'TR-02 PSEA'}] }
];

/* ─────────────────────────────────────────────────────────────────
   HELPERS
───────────────────────────────────────────────────────────────── */
function completionColor(pct) {
    if (pct >= 75) return 'var(--success)';
    if (pct >= 50) return 'var(--warning)';
    return 'var(--danger)';
}

function progressBar(pct) {
    var color = completionColor(pct);
    return '<div style="background:var(--surface2);border-radius:999px;height:8px;width:55px;overflow:hidden;display:inline-block;vertical-align:middle;">' +
           '<div style="width:' + pct + '%;background:' + color + ';height:100%;border-radius:999px;transition:width .4s;"></div></div>' +
           '<span style="margin-left:.35rem;font-size:.75rem;color:' + color + ';font-weight:700;">' + pct + '%</span>';
}

/* ─────────────────────────────────────────────────────────────────
   TAB SWITCHING
───────────────────────────────────────────────────────────────── */
function switchTrainingTab(key) {
    document.querySelectorAll('.training-tab-content').forEach(function(el) { el.style.display = 'none'; });
    document.querySelectorAll('.tab').forEach(function(el) { el.classList.remove('active'); });
    var section = document.getElementById('trainingSection-' + key);
    if (section) section.style.display = 'block';
    var tabMap = {
        'training-dashboard':'tabTrainingDashboard',
        'curriculum':        'tabCurriculum',
        'my-attendance':     'tabMyAttendance',
        'state-performance': 'tabStatePerformance',
        'log-attendance':    'tabLogAttendance',
        'master-roster':     'tabMasterRoster'
    };
    var btn = document.getElementById(tabMap[key]);
    if (btn) btn.classList.add('active');
}

/* ─────────────────────────────────────────────────────────────────
   ROLE PERMISSIONS
   HR CAN:
   - Add training module
   - View and generate report for all training completed by staff
   - See list of staff and state yet to complete selected training
   - See state performance
   - View training dashboard
   - CANNOT edit nor delete
───────────────────────────────────────────────────────────────── */
function applyRolePermissions(role) {
    document.querySelectorAll('.training-tab-content').forEach(function(el) { el.style.display = 'none'; });

    ['tabTrainingDashboard','tabCurriculum','tabMyAttendance','tabStatePerformance','tabLogAttendance','tabMasterRoster'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    ['btnOpenAddModal','btnExportReport','btnExportCSV'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    var banner = document.getElementById('trainingEmailBroadcastBanner');
    if (banner) banner.style.display = 'none';

    var badgeText = document.getElementById('trainingRoleBadgeText');
    var roleLabels = { staff:'Staff', state_lead:'State Team Lead', hr:'HR Manager', compliance_officer:'Compliance Specialist', doc:'Director (DoC)' };
    if (badgeText) badgeText.textContent = roleLabels[role] || role;

    if (role === 'staff') {
        _showTab('tabCurriculum');
        _showTab('tabMyAttendance');
        _showTab('tabLogAttendance');
        if (banner) banner.style.display = 'flex';
        switchTrainingTab('my-attendance');

    } else if (role === 'state_lead') {
        _showTab('tabCurriculum');
        _showTab('tabStatePerformance');
        switchTrainingTab('state-performance');

    } else if (role === 'hr') {
        // HR Permissions: Training Dashboard, Curriculum (Add & View only), State Performance & Outstanding, Completed Training & Reports
        _showTab('tabTrainingDashboard');
        _showTab('tabCurriculum');
        _showTab('tabStatePerformance');
        _showTab('tabMasterRoster');
        _showBtn('btnOpenAddModal');
        _showBtn('btnExportReport');
        _showBtn('btnExportCSV');
        switchTrainingTab('training-dashboard');

    } else if (role === 'compliance_officer') {
        _showTab('tabTrainingDashboard');
        _showTab('tabCurriculum');
        _showTab('tabStatePerformance');
        _showTab('tabMasterRoster');
        _showBtn('btnExportReport');
        _showBtn('btnExportCSV');
        switchTrainingTab('training-dashboard');

    } else if (role === 'doc' || role === 'superadmin') {
        _showTab('tabTrainingDashboard');
        _showTab('tabCurriculum');
        _showTab('tabStatePerformance');
        _showTab('tabMasterRoster');
        _showTab('tabMyAttendance');
        _showTab('tabLogAttendance');
        _showBtn('btnOpenAddModal');
        _showBtn('btnExportReport');
        _showBtn('btnExportCSV');
        switchTrainingTab('training-dashboard');
    }

    renderCurriculum(role);
    renderTrainingDashboard();
}

function _showTab(id) {
    var el = document.getElementById(id);
    if (el) el.style.display = 'inline-flex';
}

function _showBtn(id) {
    var el = document.getElementById(id);
    if (el) el.style.display = 'inline-flex';
}

/* ─────────────────────────────────────────────────────────────────
   RENDER – TRAINING DASHBOARD
───────────────────────────────────────────────────────────────── */
function renderTrainingDashboard() {
    // Course list with progress bars
    var courseListEl = document.getElementById('trainingDashboardCourseList');
    if (courseListEl) {
        courseListEl.innerHTML = TRAINING_MODULES.map(function(m) {
            var color = completionColor(m.completion);
            return '<div style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:10px 14px;">' +
                '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">' +
                    '<div style="font-weight:700;font-size:12px;color:var(--text);">' + m.id + ': ' + m.name + '</div>' +
                    '<span style="font-size:11px;font-weight:800;color:' + color + ';">' + m.completion + '% Complete</span>' +
                '</div>' +
                '<div style="background:#e2e8f0;border-radius:999px;height:7px;overflow:hidden;margin-bottom:4px;">' +
                    '<div style="width:' + m.completion + '%;background:' + color + ';height:100%;border-radius:999px;"></div>' +
                '</div>' +
                '<div style="display:flex;justify-content:space-between;font-size:10px;color:var(--text-muted);">' +
                    '<span>' + m.category + ' · ' + m.duration + '</span>' +
                    '<span>Deadline: ' + m.deadline + '</span>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    // State ranking leaderboard
    var stateRankEl = document.getElementById('trainingDashboardStateRanking');
    if (stateRankEl) {
        stateRankEl.innerHTML = STATE_STATS.map(function(s) {
            var color = completionColor(s.rate);
            var rankBadge = s.rank === 1 ? '🥇' : s.rank === 2 ? '🥈' : s.rank === 3 ? '🥉' : '#' + s.rank;
            return '<div style="display:flex;align-items:center;justify-content:space-between;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:9px 12px;">' +
                '<div style="display:flex;align-items:center;gap:8px;">' +
                    '<span style="font-size:12px;font-weight:700;width:22px;">' + rankBadge + '</span>' +
                    '<div>' +
                        '<div style="font-size:12px;font-weight:700;color:var(--text);">' + s.state + ' Office</div>' +
                        '<div style="font-size:10px;color:var(--text-muted);">' + s.trained + ' of ' + s.total + ' staff completed</div>' +
                    '</div>' +
                '</div>' +
                '<div style="display:flex;align-items:center;gap:8px;">' +
                    '<div style="background:#e2e8f0;border-radius:999px;height:6px;width:70px;overflow:hidden;">' +
                        '<div style="width:' + s.rate + '%;background:' + color + ';height:100%;"></div>' +
                    '</div>' +
                    '<span style="font-size:11px;font-weight:800;color:' + color + ';width:36px;text-align:right;">' + s.rate + '%</span>' +
                '</div>' +
            '</div>';
        }).join('');
    }
}

/* ─────────────────────────────────────────────────────────────────
   RENDER – CURRICULUM TABLE (HR: View Only, No Edit, No Delete)
───────────────────────────────────────────────────────────────── */
function renderCurriculum(role) {
    var tbody = document.getElementById('curriculumTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    TRAINING_MODULES.forEach(function(m) {
        var typePill = m.type === 'Mandatory'
            ? '<span class="pill pill-open" style="font-size:.7rem;">Mandatory</span>'
            : '<span class="pill" style="font-size:.7rem;background:#f3f4f6;color:var(--text-muted);">Elective</span>';

        var actionCell = '';
        if (role === 'staff') {
            actionCell = '<button class="btn btn-outline btn-sm" onclick="staffAttendModule(\'' + m.id + '\')">Attend</button>';
        } else if (role === 'state_lead') {
            actionCell = '<span style="font-size:.75rem;color:var(--text-dim);">View only</span>';
        } else if (role === 'hr' || role === 'compliance_officer') {
            // HR CANNOT EDIT NOR DELETE — ONLY VIEW
            actionCell = '<button class="btn btn-outline btn-sm" onclick="viewModuleDetails(\'' + m.id + '\')" title="View Syllabus" style="font-size:10px;padding:3px 8px;"><i class="fa-solid fa-eye"></i> View</button>';
        } else if (role === 'doc' || role === 'superadmin') {
            // DoC has Edit and Delete
            actionCell = '<div style="display:flex;gap:.35rem;justify-content:center;">' +
                         '<button class="btn btn-outline btn-sm" onclick="docEditModule(\'' + m.id + '\')" style="font-size:10px;padding:3px 6px;">Edit</button>' +
                         '<button class="btn btn-sm" style="background:var(--danger);color:#fff;border:none;font-size:10px;padding:3px 6px;" onclick="docDeleteModule(\'' + m.id + '\',\'' + m.name + '\')">Delete</button>' +
                         '</div>';
        }

        var tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid var(--border)';
        tr.innerHTML = '<td style="padding:.6rem .75rem;font-weight:700;color:var(--accent);">' + m.id + '</td>' +
            '<td style="padding:.6rem .75rem;font-weight:600;color:var(--text);">' + m.name + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + m.category + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + m.duration + '</td>' +
            '<td style="padding:.6rem .75rem;">' + typePill + '</td>' +
            '<td style="padding:.6rem .75rem;">' + progressBar(m.completion) + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + m.enrolled + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);white-space:nowrap;">' + m.deadline + '</td>' +
            '<td style="padding:.6rem .75rem;text-align:center;">' + actionCell + '</td>';
        tbody.appendChild(tr);
    });
}

function viewModuleDetails(id) {
    var m = TRAINING_MODULES.find(function(x){ return x.id === id; });
    if (!m) return;
    var titleEl = document.getElementById('viewModuleTitle');
    var bodyEl = document.getElementById('viewModuleBody');
    if (titleEl) titleEl.textContent = m.id + ': ' + m.name;
    if (bodyEl) {
        bodyEl.innerHTML = '<div style="margin-bottom:12px;background:var(--surface2);padding:12px;border-radius:8px;border:1px solid var(--border);">' +
            '<div style="font-size:11px;font-weight:700;color:var(--accent);text-transform:uppercase;">Overview & Syllabus</div>' +
            '<p style="margin:4px 0 0;font-size:12px;line-height:1.4;">' + (m.desc || 'Standard CCCRN Compliance Training module.') + '</p>' +
        '</div>' +
        '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">' +
            '<div style="background:var(--surface2);padding:8px 10px;border-radius:6px;"><strong>Category:</strong> ' + m.category + '</div>' +
            '<div style="background:var(--surface2);padding:8px 10px;border-radius:6px;"><strong>Duration:</strong> ' + m.duration + '</div>' +
            '<div style="background:var(--surface2);padding:8px 10px;border-radius:6px;"><strong>Type:</strong> ' + m.type + '</div>' +
            '<div style="background:var(--surface2);padding:8px 10px;border-radius:6px;"><strong>Deadline:</strong> ' + m.deadline + '</div>' +
        '</div>' +
        '<div style="padding:10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;font-size:11px;color:#1e40af;">' +
            '<i class="fa-solid fa-circle-info me-1"></i> <strong>Institutional Progress:</strong> ' + m.completion + '% completed out of ' + m.enrolled + ' enrolled staff. (HR View-Only Access)' +
        '</div>';
    }
    openModal('viewModuleModal');
}

/* ─────────────────────────────────────────────────────────────────
   RENDER – MY CERTIFICATES / ATTENDANCE TABLE
───────────────────────────────────────────────────────────────── */
function renderMyCertificates() {
    var tbody = document.getElementById('myCertificatesTableBody');
    var badge = document.getElementById('myCompletedCountBadge');
    if (!tbody) return;
    var myRecords = ATTENDANCE_RECORDS.filter(function(r) { return r.email === 'staff@cccrn.org'; });
    tbody.innerHTML = '';
    myRecords.forEach(function(r) {
        var statusPill = r.status === 'Completed'
            ? '<span class="pill pill-closed" style="font-size:.7rem;">Completed</span>'
            : '<span class="pill pill-progress" style="font-size:.7rem;">In Progress</span>';
        var certBtn = r.status === 'Completed'
            ? '<button class="btn btn-outline btn-sm" onclick="downloadCert(\'' + r.ref + '\')">📄 Download</button>'
            : '<span style="font-size:.75rem;color:var(--text-dim);">—</span>';
        var tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid var(--border)';
        tr.innerHTML = '<td style="padding:.6rem .75rem;font-weight:700;color:var(--accent);">' + r.ref + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text);">' + r.module + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + r.date + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + r.state + '</td>' +
            '<td style="padding:.6rem .75rem;">' + statusPill + '</td>' +
            '<td style="padding:.6rem .75rem;text-align:center;">' + certBtn + '</td>';
        tbody.appendChild(tr);
    });
    if (badge) badge.textContent = myRecords.length;
}

/* ─────────────────────────────────────────────────────────────────
   RENDER – STATE PERFORMANCE & OUTSTANDING STAFF
   Interactive: see list of staff and state yet to complete selected training
───────────────────────────────────────────────────────────────── */
function renderStatePerformance(filterCourseId) {
    var tbody = document.getElementById('statePerformanceTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    STATE_STATS.forEach(function(s) {
        var trained = s.trained;
        var total = s.total;
        var pending = total - trained;
        var rate = s.rate;

        // If specific course is chosen, adapt metrics slightly
        if (filterCourseId && filterCourseId !== 'ALL') {
            var m = TRAINING_MODULES.find(function(x){ return x.id === filterCourseId; });
            if (m) {
                rate = m.completion;
                trained = Math.round(total * (rate / 100));
                pending = total - trained;
            }
        }

        var tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid var(--border)';
        tr.innerHTML = '<td style="padding:.6rem .75rem;font-weight:700;color:var(--text);">' + s.state + ' Office</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + total + ' Staff</td>' +
            '<td style="padding:.6rem .75rem;color:var(--success);font-weight:700;">' + trained + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--danger);font-weight:700;">' + pending + ' Staff</td>' +
            '<td style="padding:.6rem .75rem;font-weight:700;color:' + completionColor(rate) + ';">' + rate + '%</td>' +
            '<td style="padding:.6rem .75rem;">' + progressBar(rate) + '</td>';
        tbody.appendChild(tr);
    });

    renderIncompleteStaff(filterCourseId);
}

function renderIncompleteStaff(filterCourseId) {
    var incTbody = document.getElementById('incompleteStaffTableBody');
    if (!incTbody) return;
    incTbody.innerHTML = '';

    var allIncomplete = [];
    STATE_STATS.forEach(function(s) {
        s.incomplete.forEach(function(p) {
            allIncomplete.push({
                name: p.name,
                role: p.role,
                dept: p.dept,
                state: s.state,
                missing: p.missing
            });
        });
    });

    // Filter by selected course
    var filtered = allIncomplete;
    if (filterCourseId && filterCourseId !== 'ALL') {
        filtered = allIncomplete.filter(function(p) {
            return p.missing.includes(filterCourseId) || filterCourseId === 'ALL';
        });
        if (filtered.length === 0) {
            // Include state representatives if specific filter
            filtered = allIncomplete.slice(0, 3);
        }
    }

    filtered.forEach(function(p) {
        var tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid var(--border)';
        tr.innerHTML = '<td style="padding:.6rem .75rem;font-weight:600;color:var(--text);">' + p.name + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + p.role + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + p.dept + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text);font-weight:600;">' + p.state + '</td>' +
            '<td style="padding:.6rem .75rem;"><span class="pill pill-open" style="font-size:.7rem;">' + p.missing + '</span></td>' +
            '<td style="padding:.6rem .75rem;text-align:center;"><button class="btn btn-outline btn-sm" onclick="sendStaffReminder(\'' + p.name + '\')" style="font-size:10px;padding:2px 6px;">Remind</button></td>';
        incTbody.appendChild(tr);
    });
}

function filterIncompleteByCourse(courseId) {
    renderStatePerformance(courseId);
}

function sendStaffReminder(name) {
    alert('Compliance Reminder notification sent to ' + name + '.');
}

function broadcastTrainingReminder() {
    alert('Compliance Reminder Broadcast dispatched to all 166 outstanding staff across 6 state clusters.');
}

/* ─────────────────────────────────────────────────────────────────
   RENDER – MASTER VERIFICATION ROSTER & REPORTS
───────────────────────────────────────────────────────────────── */
function renderMasterRoster(records) {
    var tbody = document.getElementById('masterRosterTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    var list = records || ATTENDANCE_RECORDS;

    list.forEach(function(r) {
        var verifyBtn = r.verified
            ? '<span style="color:var(--success);font-size:.78rem;font-weight:700;">✔ Verified</span>'
            : '<button class="btn btn-outline btn-sm" style="font-size:10px;padding:2px 6px;" onclick="verifyRecord(\'' + r.ref + '\')">Verify</button>';
        var tr = document.createElement('tr');
        tr.setAttribute('data-ref', r.ref);
        tr.style.borderBottom = '1px solid var(--border)';
        tr.innerHTML = '<td style="padding:.6rem .75rem;font-weight:700;color:var(--accent);">' + r.ref + '</td>' +
            '<td style="padding:.6rem .75rem;font-weight:600;color:var(--text);">' + r.name + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + r.email + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + r.state + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text);">' + r.module + '</td>' +
            '<td style="padding:.6rem .75rem;color:var(--text-muted);">' + r.date + '</td>' +
            '<td style="padding:.6rem .75rem;"><span class="pill pill-closed" style="font-size:.7rem;">' + r.status + '</span></td>' +
            '<td style="padding:.6rem .75rem;text-align:center;">' + verifyBtn + '</td>';
        tbody.appendChild(tr);
    });
}

function filterRoster(q) {
    var term = (q || '').toLowerCase();
    var filtered = ATTENDANCE_RECORDS.filter(function(r) {
        return r.name.toLowerCase().includes(term) ||
               r.email.toLowerCase().includes(term) ||
               r.module.toLowerCase().includes(term) ||
               r.state.toLowerCase().includes(term);
    });
    renderMasterRoster(filtered);
}

function filterCurriculum(q) {
    var term = (q || '').toLowerCase();
    var rows = document.querySelectorAll('#curriculumTableBody tr');
    rows.forEach(function(tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
}

/* ─────────────────────────────────────────────────────────────────
   ACTIONS (ADD MODULE, REPORTS, ETC.)
───────────────────────────────────────────────────────────────── */
function saveNewModule() {
    var mid  = (document.getElementById('newModuleId')  || {}).value || '';
    var name = (document.getElementById('newModuleName')|| {}).value || '';
    var cat  = (document.getElementById('newModuleCategory') || {}).value || 'Compliance & Ethics';
    var dur  = (document.getElementById('newModuleDuration') || {}).value || '2 hrs';
    var type = (document.getElementById('newModuleType') || {}).value || 'Mandatory';
    var dead = (document.getElementById('newModuleDeadline') || {}).value || '31 Dec 2026';
    var desc = (document.getElementById('newModuleDesc') || {}).value || '';

    if (!mid || !name) { alert('Module ID and Module Name are required.'); return; }

    TRAINING_MODULES.push({
        id: mid,
        name: name,
        category: cat,
        duration: dur,
        type: type,
        completion: 0,
        enrolled: 490,
        deadline: dead,
        desc: desc
    });

    alert('Module ' + mid + ' – ' + name + ' has been added to the training curriculum.');
    closeModal('addModuleModal');
    
    var role = (typeof window.CURRENT_USER_ROLE !== 'undefined') ? window.CURRENT_USER_ROLE : 'hr';
    renderCurriculum(role);
    renderTrainingDashboard();
    
    var statTotal = document.getElementById('statTotalCourses');
    if (statTotal) statTotal.textContent = TRAINING_MODULES.length;
}

function docEditModule(moduleId) {
    alert('Edit mode for module ' + moduleId + ' – reserved for Director of Compliance.');
}

function docDeleteModule(moduleId, moduleName) {
    if (!confirm('Are you sure you want to delete ' + moduleId + ': ' + moduleName + '? This action cannot be undone.')) return;
    TRAINING_MODULES = TRAINING_MODULES.filter(function(m) { return m.id !== moduleId; });
    alert('Module ' + moduleId + ' has been deleted.');
    var role = (typeof window.CURRENT_USER_ROLE !== 'undefined') ? window.CURRENT_USER_ROLE : 'doc';
    renderCurriculum(role);
    renderTrainingDashboard();
}

function verifyRecord(ref) {
    alert('Record ' + ref + ' has been marked as verified.');
    var row = document.querySelector('[data-ref="' + ref + '"]');
    if (row) {
        var lastCell = row.querySelector('td:last-child');
        if (lastCell) lastCell.innerHTML = '<span style="color:var(--success);font-size:.78rem;font-weight:700;">✔ Verified</span>';
    }
}

function downloadCert(ref) {
    alert('Downloading verified training completion certificate for record ' + ref + '.');
}

function trainingExportPDF() {
    alert('Generating Institutional Staff Training Completion Dossier (PDF).');
}

function trainingExportCSV() {
    alert('Exporting Master Training Attendance Ledger as CSV.');
}

function staffAttendTraining() {
    var sel = document.getElementById('attendModuleSelect');
    if (!sel || !sel.value) { alert('Please select a module.'); return; }
    alert('Attendance logged for ' + sel.value + '. Certificate generated.');
    renderMyCertificates();
}

function staffAttendModule(id) {
    alert('You have registered and attended ' + id + '. Status marked as Completed.');
    renderMyCertificates();
}

function submitLogAttendance() {
    var name = (document.getElementById('logStaffName') || {}).value;
    var email = (document.getElementById('logStaffEmail') || {}).value;
    var state = (document.getElementById('logStaffState') || {}).value;
    var mod = (document.getElementById('logStaffModule') || {}).value;

    if (!name || !email || !state || !mod) {
        alert('All fields are required.');
        return;
    }

    ATTENDANCE_RECORDS.unshift({
        ref: 'ATT-00' + (ATTENDANCE_RECORDS.length + 1),
        name: name,
        email: email,
        state: state,
        module: mod,
        date: 'Today',
        status: 'Completed',
        verified: false
    });

    alert('Attendance successfully logged for ' + name + '.');
    clearLogForm();
    renderMasterRoster();
}

function clearLogForm() {
    var name = document.getElementById('logStaffName');
    var email = document.getElementById('logStaffEmail');
    var state = document.getElementById('logStaffState');
    var mod = document.getElementById('logStaffModule');
    if (name) name.value = '';
    if (email) email.value = '';
    if (state) state.value = '';
    if (mod) mod.value = '';
}

/* ─────────────────────────────────────────────────────────────────
   INIT TRAINING MODULE – exposed globally
───────────────────────────────────────────────────────────────── */
window.initTrainingModule = function() {
    var role = (typeof window.CURRENT_USER_ROLE !== 'undefined') ? window.CURRENT_USER_ROLE : 'hr';
    renderMyCertificates();
    renderStatePerformance('ALL');
    renderMasterRoster();
    applyRolePermissions(role);
};

/* ─────────────────────────────────────────────────────────────────
   DOMCONTENTLOADED – call init only when panel is visible
───────────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
    var panel = document.getElementById('panel-training');
    if (panel && panel.style.display !== 'none') {
        window.initTrainingModule();
    }
});
</script>

@endsection
