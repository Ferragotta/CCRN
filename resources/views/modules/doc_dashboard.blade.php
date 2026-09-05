<div id="docDashboardModuleContainer" style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box;">
    <!-- 1. TOP COMMAND BANNER -->
    <div style="background: linear-gradient(135deg, #022b61 0%, #02367B 60%, #006CA5 100%); color: #ffffff; padding: 22px 26px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(2, 54, 123, 0.15); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="background: #55E2E9; color: #02367B; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Executive Authority</span>
                <span style="font-size: 12px; opacity: 0.85;">Quarter 1, FY2026</span>
                <span class="pill" style="background: rgba(85,226,233,0.15); color: #55E2E9; border: 1px solid rgba(85,226,233,0.3); font-size: 10px; font-weight: 700;">
                    <i class="fa-solid fa-shield-halved me-1"></i> DoC Command Center
                </span>
            </div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 22px; font-weight: 800; margin: 4px 0 2px; color: #ffffff;">
                Director of Compliance — Executive Command Center
            </h1>
            <div style="font-size: 12px; opacity: 0.9;">
                Enterprise governance, forensic investigations, USAID & CDC compliance, CAP escalations, and multi-state audit monitoring
            </div>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <!-- Dedicated DoC Dashboard Notification Bell -->
            <button id="docBannerNotifBtn" onclick="toggleDocDashboardBell(event)" class="btn btn-outline" style="background: rgba(255,255,255,0.18); border-color: rgba(255,255,255,0.45); color: #ffffff; font-size: 11px; font-weight: 700; position: relative; padding: 6px 14px; display: inline-flex; align-items: center; gap: 6px;" title="DoC Operational Alerts">
                <i class="fa-solid fa-bell"></i> Alerts &amp; Triage
                <span id="docBannerNotifBadge" style="min-width: 18px; height: 18px; padding: 0 5px; background: #dc2626; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 800; display: none; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">0</span>
            </button>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.4); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('ai')">
                <i class="fa-solid fa-robot"></i> AI Assistant
            </button>
            <button class="btn btn-outline" style="background: rgba(124, 58, 237, 0.35); border-color: rgba(196, 181, 253, 0.6); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('ai-review')">
                <i class="fa-solid fa-brain"></i> AI Clause Review
            </button>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('investigations')">
                <i class="fa-solid fa-magnifying-glass-chart"></i> Investigations (<span id="docBannerInvCount">0</span>)
            </button>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('reports')">
                <i class="fa-solid fa-file-pdf"></i> Donor Dossier
            </button>
            <button class="btn btn-primary" style="background: #55E2E9; color: #02367B; font-weight: 700; font-size: 11px; border: none;" onclick="switchPanel('complaints')">
                <i class="fa-solid fa-bolt"></i> Triage Hub
            </button>
        </div>
    </div>

    <!-- 2. 4 EXECUTIVE KPI TILES -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--accent); background: #ffffff;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Total Grievances Logged</div>
            <div id="docStatGrievances" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 30px; font-weight: 800; color: var(--accent); line-height: 1;">0</div>
            <div id="docStatGrievancesSub" style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Across 6 State Offices</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--danger); background: #ffffff;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Active Open Cases</div>
            <div id="docStatOpenCases" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 30px; font-weight: 800; color: var(--danger); line-height: 1;">0</div>
            <div id="docStatOpenCasesSub" style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Awaiting Review &amp; Triage</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--warning); background: #ffffff;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Corrective Actions (CAP)</div>
            <div id="docStatCaps" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 30px; font-weight: 800; color: var(--warning); line-height: 1;">0</div>
            <div id="docStatCapsSub" style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Remediation Tracking</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--success); background: #ffffff;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;">Resolved & Verified</div>
            <div id="docStatResolved" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 30px; font-weight: 800; color: var(--success); line-height: 1;">0</div>
            <div id="docStatResolvedSub" style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Resolution Rate: <span id="docStatRate">0%</span></div>
        </div>
    </div>

    <!-- 3. 2-COLUMN ANALYTICS ROW -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
        <!-- Trajectory Chart + Categories -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-line" style="color: var(--accent);"></i> 6-Month Incident Trajectory & Category Distribution</div>
                <span style="font-size: 11px; color: var(--text-muted);">Oct 2025 – Mar 2026</span>
            </div>
            <!-- SVG Smooth Curve -->
            <div style="height: 130px; width: 100%; position: relative; margin-bottom: 16px;">
                <svg viewBox="0 0 500 120" style="width: 100%; height: 100%; overflow: visible;">
                    <defs>
                        <linearGradient id="gradCurveDoc" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#02367B" stop-opacity="0.25" />
                            <stop offset="100%" stop-color="#02367B" stop-opacity="0.0" />
                        </linearGradient>
                    </defs>
                    <path d="M 20 90 Q 110 30 200 65 T 380 40 T 480 20 L 480 120 L 20 120 Z" fill="url(#gradCurveDoc)" />
                    <path d="M 20 90 Q 110 30 200 65 T 380 40 T 480 20" fill="none" stroke="#02367B" stroke-width="3.5" />
                    <circle cx="20" cy="90" r="4" fill="#02367B" />
                    <circle cx="110" cy="30" r="4" fill="#02367B" />
                    <circle cx="200" cy="65" r="4" fill="#02367B" />
                    <circle cx="290" cy="50" r="4" fill="#02367B" />
                    <circle cx="380" cy="40" r="4" fill="#02367B" />
                    <circle cx="480" cy="20" r="5" fill="#dc2626" />
                </svg>
            </div>
            <!-- Category Breakdown -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; text-align: center; border-top: 1px solid var(--surface2); padding-top: 12px;">
                <div><div style="font-size: 10px; color: var(--text-muted); font-weight: 700;">FRAUD / FINANCE</div><div id="docCatFraud" style="font-size: 15px; font-weight: 800; color: #991b1b;">0 Cases</div></div>
                <div><div style="font-size: 10px; color: var(--text-muted); font-weight: 700;">MISCONDUCT</div><div id="docCatMisconduct" style="font-size: 15px; font-weight: 800; color: #b45309;">0 Cases</div></div>
                <div><div style="font-size: 10px; color: var(--text-muted); font-weight: 700;">POLICY BREACH</div><div id="docCatPolicy" style="font-size: 15px; font-weight: 800; color: var(--accent);">0 Cases</div></div>
                <div><div style="font-size: 10px; color: var(--text-muted); font-weight: 700;">PSEA / SAFETY</div><div id="docCatPsea" style="font-size: 15px; font-weight: 800; color: #7c3aed;">0 Cases</div></div>
            </div>
        </div>

        <!-- Radial Gauge -->
        <div class="card" style="margin-bottom: 0; text-align: center; display: flex; flex-direction: column; justify-content: space-between;">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-gauge-high" style="color: var(--accent);"></i> Institutional Compliance Index</div></div>
            <div style="position: relative; width: 140px; height: 140px; margin: 0 auto;">
                <svg viewBox="0 0 36 36" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e2e8f0" stroke-width="3.5" />
                    <path id="docGaugeCircle" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#02367B" stroke-dasharray="0, 100" stroke-width="3.5" stroke-linecap="round" />
                </svg>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <div id="docGaugeVal" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: var(--accent);">0%</div>
                    <div id="docGaugeLabel" style="font-size: 9px; color: var(--text-muted); font-weight: 700;">BASELINE</div>
                </div>
            </div>
            <div style="font-size: 11px; color: var(--text-muted); padding: 8px; background: var(--surface2); border-radius: 6px;">
                Statutory Target: <strong>85%</strong> by Q3 FY2026
            </div>
        </div>
    </div>

    <!-- 4. 5x5 ISO 31000 RISK MATRIX & RECENT COMPLAINTS TABLE -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
        <!-- ISO 31000 Risk Heatmap -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-triangle-exclamation" style="color: var(--danger);"></i> ISO 31000 Risk Heatmap (5×5)</div>
                <div style="display: flex; gap: 6px;">
                    <span class="badge" id="docRiskCritBadge" style="background: var(--danger); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 10px;">0 Critical</span>
                    <button class="btn btn-outline btn-sm" onclick="switchPanel('risk')" style="font-size: 11px;">Risk Register →</button>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px; text-align: center; font-size: 10px; font-weight: 700;">
                <div style="background: #fef08a; padding: 10px 4px; border-radius: 4px;">L1·I1</div>
                <div style="background: #fef08a; padding: 10px 4px; border-radius: 4px;">L1·I2</div>
                <div style="background: #fed7aa; padding: 10px 4px; border-radius: 4px;">L1·I3</div>
                <div style="background: #fca5a5; padding: 10px 4px; border-radius: 4px;">L1·I4</div>
                <div style="background: #ef4444; color: #fff; padding: 10px 4px; border-radius: 4px;">L1·I5</div>

                <div style="background: #bbf7d0; padding: 10px 4px; border-radius: 4px;">L2·I1</div>
                <div style="background: #fef08a; padding: 10px 4px; border-radius: 4px;">L2·I2</div>
                <div style="background: #fed7aa; padding: 10px 4px; border-radius: 4px;">L2·I3</div>
                <div style="background: #fca5a5; padding: 10px 4px; border-radius: 4px;">L2·I4</div>
                <div style="background: #ef4444; color: #fff; padding: 10px 4px; border-radius: 4px;">L2·I5</div>

                <div style="background: #bbf7d0; padding: 10px 4px; border-radius: 4px;">L3·I1</div>
                <div style="background: #bbf7d0; padding: 10px 4px; border-radius: 4px;">L3·I2</div>
                <div style="background: #fef08a; padding: 10px 4px; border-radius: 4px;">L3·I3</div>
                <div style="background: #fed7aa; padding: 10px 4px; border-radius: 4px;">L3·I4</div>
                <div style="background: #fca5a5; padding: 10px 4px; border-radius: 4px;">L3·I5</div>

                <div style="background: #86efac; padding: 10px 4px; border-radius: 4px;">L4·I1</div>
                <div style="background: #bbf7d0; padding: 10px 4px; border-radius: 4px;">L4·I2</div>
                <div style="background: #fef08a; padding: 10px 4px; border-radius: 4px;">L4·I3</div>
                <div style="background: #fca5a5; padding: 10px 4px; border-radius: 4px;">L4·I4</div>
                <div style="background: #ef4444; color: #fff; padding: 10px 4px; border-radius: 4px;">L4·I5</div>

                <div style="background: #86efac; padding: 10px 4px; border-radius: 4px;">L5·I1</div>
                <div style="background: #86efac; padding: 10px 4px; border-radius: 4px;">L5·I2</div>
                <div style="background: #bbf7d0; padding: 10px 4px; border-radius: 4px;">L5·I3</div>
                <div style="background: #fef08a; padding: 10px 4px; border-radius: 4px;">L5·I4</div>
                <div style="background: #fca5a5; padding: 10px 4px; border-radius: 4px;">L5·I5</div>
            </div>
        </div>

        <!-- Recent Complaints Ledger -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-inbox" style="color: var(--accent);"></i> Recent Priority Complaints</div>
                <a href="/complaints" onclick="switchPanel('complaints'); return false;" style="font-size: 11px; color: var(--accent); text-decoration: none; font-weight: 700;">View All →</a>
            </div>
            <div style="width: 100%; overflow: hidden; border-radius: 6px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="width: 22%; padding: 8px 10px;">Ref</th>
                            <th style="width: 20%; padding: 8px 10px;">State</th>
                            <th style="width: 25%; padding: 8px 10px;">Category</th>
                            <th style="width: 18%; padding: 8px 6px; text-align: center;">Severity</th>
                            <th style="width: 15%; padding: 8px 6px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="docRecentComplaintsTbody">
                        <tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 12px;">Connecting live complaints register...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. FORENSIC INVESTIGATIONS SUMMARY & AI DIAGNOSTICS ROW -->
    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px;">
        <!-- Forensic Investigations Hub Summary -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title">
                    <i class="fa-solid fa-magnifying-glass-chart" style="color: var(--accent);"></i>
                    Formal Forensic Investigations Register
                </div>
                <button class="btn btn-sm btn-outline" onclick="switchPanel('investigations')">Open Hub →</button>
            </div>
            <div style="width: 100%; overflow: hidden; border-radius: 6px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="width: 22%; padding: 8px 10px;">Case Ref</th>
                            <th style="width: 18%; padding: 8px 10px;">State</th>
                            <th style="width: 32%; padding: 8px 10px;">Assigned Lead</th>
                            <th style="width: 28%; padding: 8px 6px; text-align: center;">Case Status</th>
                        </tr>
                    </thead>
                    <tbody id="docInvestigationsTbody">
                        <tr><td colspan="4" style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 12px;">Connecting live forensic register...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AI Compliance Diagnostics Quick Card -->
        <div class="card" style="margin-bottom: 0; border-top: 3px solid #7c3aed; background: #ffffff;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title" style="font-size: 13px; font-weight: 800; color: #7c3aed;">
                    <i class="fa-solid fa-brain"></i> AI Compliance Review & Clause Diagnostics
                </div>
                <button class="btn btn-primary btn-sm" style="background: #7c3aed; border-color: #7c3aed; font-size: 11px; padding: 4px 10px; border-radius: 6px; cursor: pointer;" onclick="switchPanel('ai-review')">
                    Open Reviewer →
                </button>
            </div>
            <div style="padding: 12px 14px; font-size: 12px; color: var(--text-muted); line-height: 1.5;">
                Automated clause-by-clause neural audit against <strong>USAID 2 CFR 200</strong> &amp; <strong>POL-001–004</strong>.
                <div style="margin-top: 10px; display: flex; gap: 10px;">
                    <div id="docAiReviewSummaryCard" style="flex: 1; padding: 8px 10px; background: rgba(124, 58, 237, 0.06); border-radius: 6px; border: 1px solid rgba(124, 58, 237, 0.15);">
                        <div style="font-size: 10px; font-weight: 700; color: #7c3aed; text-transform: uppercase;">Recent Clause Audit</div>
                        <div id="docRecentAuditTitle" style="font-size: 12px; font-weight: 800; color: var(--text);">No Reviews Performed</div>
                        <div id="docRecentAuditScore" style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Clean Audit Slate &middot; 0 Flags</div>
                    </div>
                    <div style="flex: 1; padding: 8px 10px; background: rgba(2, 54, 123, 0.06); border-radius: 6px; border: 1px solid rgba(2, 54, 123, 0.15);">
                        <div style="font-size: 10px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Compliance Assistant</div>
                        <div style="font-size: 12px; font-weight: 800; color: var(--text);">AI Assistant Ready</div>
                        <a href="#" onclick="switchPanel('ai'); return false;" style="font-size: 11px; color: var(--accent); font-weight: 700;">Ask ComplianceIQ →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.toggleDocDashboardBell = function(e) {
    if (e) e.stopPropagation();
    if (typeof toggleTopbarNotifications === 'function') {
        toggleTopbarNotifications(e);
    } else if (typeof switchPanel === 'function') {
        switchPanel('complaints');
    }
};

window.syncDocDashboardFromBackend = function() {
    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data) return;

            var complaints = Array.isArray(data.complaints) ? data.complaints : [];
            var caps = Array.isArray(data.caps) ? data.caps : [];
            var investigations = Array.isArray(data.investigations) ? data.investigations : (typeof INVESTIGATIONS_DATA !== 'undefined' ? INVESTIGATIONS_DATA : []);

            // 1. Calculate KPI Metrics
            var totalGrievances = complaints.length;
            var openCases = complaints.filter(function(c) {
                var s = (c.status || '').toLowerCase();
                return s.includes('open') || s.includes('triage') || s.includes('progress');
            }).length;
            var criticalCases = complaints.filter(function(c) {
                var sev = (c.severity || '').toLowerCase();
                return sev === 'critical';
            }).length;

            var totalCaps = caps.length;
            var pendingEvidenceCaps = caps.filter(function(cp) {
                return (cp.status || '').toLowerCase().includes('evidence') || cp.hasEvidence;
            }).length;

            var resolvedComplaints = complaints.filter(function(c) {
                var s = (c.status || '').toLowerCase();
                return s.includes('closed') || s.includes('cap generated') || s.includes('resolved');
            }).length;
            var resRate = totalGrievances > 0 ? Math.round((resolvedComplaints / totalGrievances) * 100) : 100;

            // 2. Update KPI Tiles
            var elTot = document.getElementById('docStatGrievances');
            var elTotSub = document.getElementById('docStatGrievancesSub');
            if (elTot) elTot.innerText = totalGrievances;
            if (elTotSub) elTotSub.innerHTML = '<span style="color: var(--danger); font-weight: 700;">' + totalGrievances + ' Total Logged</span> · 6 State Offices';

            var elOpen = document.getElementById('docStatOpenCases');
            var elOpenSub = document.getElementById('docStatOpenCasesSub');
            if (elOpen) elOpen.innerText = openCases;
            if (elOpenSub) elOpenSub.innerHTML = '<span style="color: var(--danger); font-weight: 700;">' + criticalCases + ' Critical</span> · Immediate Action';

            var elCaps = document.getElementById('docStatCaps');
            var elCapsSub = document.getElementById('docStatCapsSub');
            if (elCaps) elCaps.innerText = totalCaps;
            if (elCapsSub) elCapsSub.innerHTML = '<span style="color: var(--success); font-weight: 700;">' + pendingEvidenceCaps + ' Evidence Pending</span> Review';

            var elRes = document.getElementById('docStatResolved');
            var elRate = document.getElementById('docStatRate');
            if (elRes) elRes.innerText = resolvedComplaints;
            if (elRate) elRate.innerText = resRate + '%';

            // 3. Category Breakdown
            var fraudCount = complaints.filter(function(c) { return (c.category || '').toLowerCase().includes('fraud') || (c.category || '').toLowerCase().includes('finance'); }).length;
            var misconductCount = complaints.filter(function(c) { return (c.category || '').toLowerCase().includes('misconduct') || (c.category || '').toLowerCase().includes('ethics'); }).length;
            var policyCount = complaints.filter(function(c) { return (c.category || '').toLowerCase().includes('policy') || (c.category || '').toLowerCase().includes('procurement'); }).length;
            var pseaCount = complaints.filter(function(c) { return (c.category || '').toLowerCase().includes('psea') || (c.category || '').toLowerCase().includes('safety'); }).length;

            var elFraud = document.getElementById('docCatFraud');
            var elMis = document.getElementById('docCatMisconduct');
            var elPol = document.getElementById('docCatPolicy');
            var elPsea = document.getElementById('docCatPsea');
            if (elFraud) elFraud.innerText = fraudCount + ' Cases';
            if (elMis) elMis.innerText = misconductCount + ' Cases';
            if (elPol) elPol.innerText = policyCount + ' Cases';
            if (elPsea) elPsea.innerText = pseaCount + ' Cases';

            // 4. Compliance Index Gauge
            var gaugeVal = document.getElementById('docGaugeVal');
            var gaugeCircle = document.getElementById('docGaugeCircle');
            var gaugeLabel = document.getElementById('docGaugeLabel');
            if (gaugeVal && gaugeCircle) {
                gaugeVal.innerText = resRate + '%';
                gaugeCircle.setAttribute('stroke-dasharray', resRate + ', 100');
                if (gaugeLabel) {
                    gaugeLabel.innerText = resRate >= 80 ? 'EXCELLENT' : (resRate >= 50 ? 'ON TRACK' : 'NEEDS ACTION');
                }
            }

            // 5. Investigations Header Count
            var elInvBadge = document.getElementById('docBannerInvCount');
            if (elInvBadge) elInvBadge.innerText = investigations.length;

            // Update DoC Dashboard Banner Notification Bell & Badge
            var bannerNotifBadge = document.getElementById('docBannerNotifBadge');
            var bannerNotifBtn = document.getElementById('docBannerNotifBtn');
            var totalActionableAlerts = openCases + pendingEvidenceCaps + investigations.filter(function(i){ return i.status !== 'Closed'; }).length;
            if (bannerNotifBadge) {
                bannerNotifBadge.innerText = totalActionableAlerts;
                bannerNotifBadge.style.display = totalActionableAlerts > 0 ? 'inline-flex' : 'none';
            }
            if (bannerNotifBtn) {
                if (totalActionableAlerts > 0) {
                    bannerNotifBtn.style.background = 'rgba(220, 38, 38, 0.35)';
                    bannerNotifBtn.style.borderColor = 'rgba(252, 165, 165, 0.7)';
                } else {
                    bannerNotifBtn.style.background = 'rgba(255, 255, 255, 0.18)';
                    bannerNotifBtn.style.borderColor = 'rgba(255, 255, 255, 0.45)';
                }
            }

            // Update Risk Heatmap Critical Badge
            var riskCritBadge = document.getElementById('docRiskCritBadge');
            if (riskCritBadge) {
                var critRiskCount = criticalCases; // Tied to critical grievances and forensic investigations
                riskCritBadge.innerText = critRiskCount + ' Critical';
                riskCritBadge.style.background = critRiskCount > 0 ? 'var(--danger)' : 'var(--success)';
            }

            // Sync AI Review Summary Card
            var auditTitleEl = document.getElementById('docRecentAuditTitle');
            var auditScoreEl = document.getElementById('docRecentAuditScore');
            if (auditTitleEl && auditScoreEl) {
                if (typeof reviewsHistory !== 'undefined' && reviewsHistory.length > 0) {
                    var latestAudit = reviewsHistory[0];
                    auditTitleEl.innerText = latestAudit.id + ' ' + (latestAudit.docTitle ? latestAudit.docTitle.split('—')[0].trim() : 'Agreement');
                    auditScoreEl.innerHTML = '<span style="color: ' + (latestAudit.criticalFlags > 0 ? 'var(--danger)' : 'var(--success)') + '; font-weight: 700;">Score: ' + latestAudit.score + '/100 (' + latestAudit.criticalFlags + ' Critical Flags)</span>';
                } else {
                    auditTitleEl.innerText = 'No Reviews Performed';
                    auditScoreEl.innerHTML = '<span style="color: var(--text-muted); font-weight: 600;">Clean Audit Slate &middot; 0 Flags</span>';
                }
            }

            // 6. Populate Recent Complaints Table
            var compTbody = document.getElementById('docRecentComplaintsTbody');
            if (compTbody) {
                if (complaints.length === 0) {
                    compTbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-check-circle me-1" style="color: var(--success);"></i> No complaints currently active in register.</td></tr>';
                } else {
                    var recent = complaints.slice(0, 5);
                    var compHtml = '';
                    recent.forEach(function(c) {
                        var sevClass = (c.severity === 'Critical') ? 'pill-critical' : ((c.severity === 'High') ? 'pill-open' : 'pill-progress');
                        var statClass = (c.status && c.status.toLowerCase().includes('closed')) ? 'pill-closed' : ((c.status && c.status.toLowerCase().includes('progress')) ? 'pill-progress' : 'pill-open');
                        
                        compHtml += '<tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.1s ease;">' +
                            '<td style="padding: 9px 10px;"><strong style="color: var(--accent); font-family: monospace;">' + c.id + '</strong></td>' +
                            '<td style="padding: 9px 10px; font-size: 11px;">' + (c.state || 'National') + '</td>' +
                            '<td style="padding: 9px 10px; font-size: 11px;">' + (c.category || 'Compliance') + '</td>' +
                            '<td style="padding: 9px 6px; text-align: center;"><span class="pill ' + sevClass + '" style="font-size: 9.5px; padding: 2px 6px;">' + (c.severity || 'Medium') + '</span></td>' +
                            '<td style="padding: 9px 6px; text-align: center;"><span class="pill ' + statClass + '" style="font-size: 9.5px; padding: 2px 6px;">' + (c.status || 'Logged') + '</span></td>' +
                        '</tr>';
                    });
                    compTbody.innerHTML = compHtml;
                }
            }

            // 7. Populate Investigations Table
            var invTbody = document.getElementById('docInvestigationsTbody');
            if (invTbody) {
                if (investigations.length === 0) {
                    invTbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-shield-halved me-1" style="color: var(--accent);"></i> No forensic investigations opened yet.</td></tr>';
                } else {
                    var invRecent = investigations.slice(0, 4);
                    var invHtml = '';
                    invRecent.forEach(function(inv) {
                        var invStatPill = (inv.status === 'Closed')
                            ? '<span class="pill pill-closed" style="font-size: 10px; font-weight: 700;">Closed</span>'
                            : ((inv.status === 'Evidence Collection')
                                ? '<span class="pill pill-progress" style="font-size: 10px; font-weight: 700;">Evidence Collection</span>'
                                : '<span class="pill pill-open" style="font-size: 10px; font-weight: 700;">' + (inv.status || 'Under Investigation') + '</span>');

                        invHtml += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                            '<td style="padding: 8px 10px;"><strong style="color: var(--accent); font-family: monospace;">' + (inv.ref || inv.id) + '</strong></td>' +
                            '<td style="padding: 8px 10px; font-size: 11px;">' + (inv.state || 'Kano') + '</td>' +
                            '<td style="padding: 8px 10px; font-size: 11px;">' + (inv.lead || inv.responsible || 'Unassigned') + '</td>' +
                            '<td style="padding: 8px 6px; text-align: center;">' + invStatPill + '</td>' +
                        '</tr>';
                    });
                    invTbody.innerHTML = invHtml;
                }
            }
        })
        .catch(function(err) {
            console.log('Error syncing DoC dashboard:', err);
        });
};

document.addEventListener('DOMContentLoaded', function() {
    window.syncDocDashboardFromBackend();
    setInterval(window.syncDocDashboardFromBackend, 3000);
});
</script>
