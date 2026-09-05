@extends('layouts.app')

@section('content')

<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="complaintsModuleContainer">
    <!-- Sub-heading & Sub-desc -->
    <div style="margin-bottom: 16px;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px;" id="reactCompTitle">
            Complaints Management
        </h2>
        <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px;" id="reactCompSub">
            Receive, track and resolve compliance complaints from all states and clusters
        </p>

        <!-- Dynamic Role Access Scope Badge -->
        <div id="roleAccessScopeBadge">
            <!-- Injected via JavaScript based on active role -->
        </div>
    </div>

    <!-- TABS -->
    <div style="display: flex; gap: 8px; margin-bottom: 16px;" id="reactTabsContainer">
        <div class="tab active" id="tabAll" onclick="setReactTab('all')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--accent); color: #ffffff; border: 1px solid var(--border); user-select: none;">
            All (<span id="countAll">6</span>)
        </div>
        <div class="tab" id="tabOpen" onclick="setReactTab('Open')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border); user-select: none;">
            Open (<span id="countOpen">2</span>)
        </div>
        <div class="tab" id="tabProgress" onclick="setReactTab('In Progress')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border); user-select: none;">
            In Progress (<span id="countProgress">2</span>)
        </div>
        <div class="tab" id="tabClosed" onclick="setReactTab('Closed')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border); user-select: none;">
            Closed (<span id="countClosed">2</span>)
        </div>
    </div>

    <!-- COMPLAINTS CARD & REGISTER TABLE (100% WIDTH, NO HORIZONTAL SCROLL) -->
    <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div class="card-title" id="reactCardTitle" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-inbox" style="color: var(--accent);"></i> Complaints Register
                </div>
                <!-- Live Search Bar -->
                <div style="position: relative;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 9px; color: var(--text-muted); font-size: 11px;"></i>
                    <input type="text" id="reactSearchInput" placeholder="Search ref, state, category..." onkeyup="searchReactComplaints()" style="padding: 5px 10px 5px 28px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); outline: none; width: 220px; box-sizing: border-box; color: var(--text);">
                </div>
            </div>

            <!-- Log Complaint Button -->
            <button class="btn btn-primary" id="btnLogComplaint" onclick="openModal('modalLogComplaint')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-plus"></i> Log Complaint
            </button>
        </div>

        <div style="width: 100%; overflow: hidden;">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                <thead>
                    <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);">
                        <th style="width: 12%; padding: 9px 8px; text-align: left;">Incident ID</th>
                        <th style="width: 18%; padding: 9px 8px; text-align: left;">Duty Station & Date</th>
                        <th style="width: 16%; padding: 9px 8px; text-align: left;">Category</th>
                        <th style="width: 18%; padding: 9px 8px; text-align: left;">Alleged & Source</th>
                        <th style="width: 10%; padding: 9px 6px; text-align: center;">Severity</th>
                        <th style="width: 13%; padding: 9px 6px; text-align: center;">Status</th>
                        <th style="width: 13%; padding: 9px 8px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody id="reactComplaintsTableBody">
                    <!-- Injected dynamically by role & active tab -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- VIEW COMPLAINT DETAILS MODAL (HR & All Roles Read-Only Inspector) -->
<div class="modal-overlay" id="modalViewComplaint" style="display: none;" onclick="if(event.target===this)closeModal('modalViewComplaint')">
    <div class="modal-dialog" style="max-width: 540px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="modal-title" id="viewComplaintModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">Complaint Details</span>
                <span id="viewComplaintSevBadge"></span>
            </div>
            <button class="modal-close" onclick="closeModal('modalViewComplaint')">&times;</button>
        </div>
        <div class="modal-body" style="padding: 16px 0; font-size: 12px; color: var(--text);" id="viewComplaintModalBody">
            <!-- Dynamic Content -->
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-shield-halved"></i> HR View-Only Record</span>
            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalViewComplaint')">Close</button>
        </div>
    </div>
</div>

<!-- LOG COMPLAINT MODAL (Restricted from HR) -->
<div class="modal-overlay" id="modalLogComplaint">
    <div class="modal-dialog">
        <div class="modal-header">
            <div class="modal-title">Log New Ethics or Grievance Report</div>
            <button class="modal-close" onclick="closeModal('modalLogComplaint')">✕</button>
        </div>
        <form onsubmit="handleReactLogSubmit(event)">
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">State Office / Cluster *</label>
                        <select id="mState" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                            <option value="Lagos — Cluster A">Lagos — Cluster A</option>
                            <option value="Kano — Cluster B">Kano — Cluster B</option>
                            <option value="Rivers — Cluster C">Rivers — Cluster C</option>
                            <option value="Abuja FCT">Abuja FCT</option>
                            <option value="Kaduna">Kaduna</option>
                            <option value="Borno">Borno</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Category *</label>
                        <select id="mCat" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                            <option value="Fraud">Fraud / Financial</option>
                            <option value="Misconduct">Misconduct / Ethics</option>
                            <option value="Policy Breach">Policy Breach</option>
                            <option value="Safety Violation">Safety Violation</option>
                            <option value="Harassment/PSEA">Harassment / PSEA</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Severity *</label>
                        <select id="mSev" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                            <option value="Critical">Critical</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Alleged Party / Role</label>
                        <input type="text" id="mAlleged" placeholder="e.g. Field Officer, Vendor..." style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Incident Details & Timeline *</label>
                    <textarea id="mDesc" rows="4" placeholder="Provide detailed timeline, location, witnesses, and facts..." required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalLogComplaint')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit Report</button>
            </div>
        </form>
    </div>
</div>

<script>
    let ACTIVE_REACT_TAB = 'all';

    let REACT_COMPLAINTS = [];

    function renderReactComplaints() {
        const role = window.CURRENT_USER_ROLE || 'doc';
        const isStaff = role === 'staff';
        const isHR = role === 'hr';
        const isComplianceOfficer = role === 'compliance_officer';
        const isDocAdmin = role === 'doc';

        // Hide Log Complaint Button & Topbar New Complaint Button for HR
        const btnLog = document.getElementById('btnLogComplaint');
        if (btnLog) {
            btnLog.style.display = isHR ? 'none' : 'inline-flex';
        }
        if (isHR) {
            document.querySelectorAll('button[onclick*="modalLogComplaint"]').forEach(b => b.style.display = 'none');
            const topbarBtn = document.getElementById('topbarBtnNewComplaint');
            if (topbarBtn) topbarBtn.style.display = 'none';
        }

        // Render Scope Badge
        const badgeContainer = document.getElementById('roleAccessScopeBadge');
        if (badgeContainer) {
            if (isStaff) {
                badgeContainer.innerHTML = '<div style="margin-top: 8px; padding: 5px 12px; background: var(--accent-light); color: var(--accent); border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-user-check"></i> <strong>Staff Portal:</strong> Showing your personal grievance history and live resolution tracker.</div>';
                document.getElementById('reactCardTitle').innerHTML = '<i class="fa-solid fa-inbox" style="color: var(--accent);"></i> My Registered Complaints';
            } else if (isHR) {
                badgeContainer.innerHTML = '<div style="margin-top: 8px; padding: 7px 14px; background: rgba(217, 119, 6, 0.08); border-left: 4px solid var(--warning); border-radius: 6px; font-size: 11px; color: #b45309; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">' +
                    '<div style="display: flex; align-items: center; gap: 8px;">' +
                        '<i class="fa-solid fa-eye" style="font-size: 13px;"></i>' +
                        '<div><strong>HR View-Only Access:</strong> Grievance and whistleblower records are restricted for institutional & disciplinary monitoring only.</div>' +
                    '</div>' +
                    '<span style="font-size: 10px; font-weight: 700; background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 4px; border: 1px solid #fde68a;"><i class="fa-solid fa-lock"></i> VIEW ONLY</span>' +
                '</div>';
            } else if (isComplianceOfficer) {
                badgeContainer.innerHTML = '<div style="margin-top: 8px; padding: 6px 12px; background: #ecfdf5; color: #065f46; border: 1px solid #bbf7d0; border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 8px;"><i class="fa-solid fa-user-shield"></i> <strong>Compliance Specialist:</strong> View all complaints &middot; Convert to CAP &middot; Set Actions (Open / In Progress / Closed / Investigate) &middot; <span style="color:#dc2626; font-weight:700;"><i class="fa-solid fa-ban"></i> No Deletion</span></div>';
            } else if (isSuperAdmin) {
                badgeContainer.innerHTML = '<div style="margin-top: 8px; padding: 6px 14px; background: linear-gradient(135deg, #fef2f2, #fff1f2); color: #991b1b; border: 1px solid #fecaca; border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 1px 3px rgba(239,68,68,0.1);"><i class="fa-solid fa-crown" style="color: #ef4444; font-size: 12px;"></i> <strong>Super Administrator Master Authority:</strong> Zero Restrictions &middot; Full Deletion Authority &middot; Unilateral Status Override &middot; Root Jurisdiction</div>';
            } else if (isDocAdmin) {
                badgeContainer.innerHTML = '<div style="margin-top: 8px; padding: 6px 12px; background: #eff6ff; color: #1e3a8a; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 8px;"><i class="fa-solid fa-shield-halved"></i> <strong>Director of Compliance (DoC):</strong> View all complaints &middot; Convert to CAP &middot; Set Actions (Open / In Progress / Closed / Investigate) &middot; <span style="color:#dc2626; font-weight:700;"><i class="fa-solid fa-ban"></i> No Deletion</span></div>';
            }
        }

        const visibleList = isStaff
            ? REACT_COMPLAINTS.filter(c => c.owner === 'staff@cccrn.org')
            : REACT_COMPLAINTS;

        // Update Tab Counts
        document.getElementById('countAll').innerText = visibleList.length;
        document.getElementById('countOpen').innerText = visibleList.filter(c => c.status === 'Open').length;
        document.getElementById('countProgress').innerText = visibleList.filter(c => c.status === 'In Progress').length;
        document.getElementById('countClosed').innerText = visibleList.filter(c => c.status === 'Closed' || c.status === 'Converted to CAP').length;

        const searchQuery = (document.getElementById('reactSearchInput') ? document.getElementById('reactSearchInput').value : '').toLowerCase();

        const filtered = visibleList.filter(c => {
            const matchesTab = ACTIVE_REACT_TAB === 'all'
                ? true
                : ACTIVE_REACT_TAB === 'Closed'
                ? (c.status === 'Closed' || c.status === 'Converted to CAP')
                : c.status === ACTIVE_REACT_TAB;

            const matchesSearch = searchQuery === '' ||
                c.id.toLowerCase().includes(searchQuery) ||
                c.state.toLowerCase().includes(searchQuery) ||
                c.category.toLowerCase().includes(searchQuery) ||
                (c.alleged && c.alleged.toLowerCase().includes(searchQuery));

            return matchesTab && matchesSearch;
        });

        const tbody = document.getElementById('reactComplaintsTableBody');
        if (!tbody) return;

        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12.5px;">' +
                '<div style="margin-bottom: 8px;"><i class="fa-solid fa-inbox" style="font-size: 28px; color: #cbd5e1;"></i></div>' +
                '<strong>No complaints currently recorded.</strong>' +
                '<div style="font-size: 11px; margin-top: 4px; color: var(--text-dim);">' +
                    (window.CURRENT_USER_ROLE === 'hr' ? 'Grievance logs will appear here once registered by staff or whistleblowers.' : 'Click \'Log Complaint\' to record a new compliance issue or incident.') +
                '</div>' +
            '</td></tr>';
            return;
        }

        tbody.innerHTML = filtered.map(c => {
            let sevPill = '';
            if (c.severity === 'Critical') sevPill = '<span class="pill pill-open">Critical</span>';
            else if (c.severity === 'High') sevPill = '<span class="pill pill-open">High</span>';
            else if (c.severity === 'Medium') sevPill = '<span class="pill pill-progress">Medium</span>';
            else sevPill = '<span class="pill pill-closed">Low</span>';

            let statPill = '';
            if (c.status === 'Open') statPill = '<span class="pill pill-open">Open</span>';
            else if (c.status === 'In Progress') statPill = '<span class="pill pill-progress">In Progress</span>';
            else if (c.status === 'Converted to CAP') statPill = '<span class="pill pill-closed">Converted to CAP</span>';
            else statPill = '<span class="pill pill-closed">Closed</span>';

            let actionHtml = '';
            if (isStaff) {
                actionHtml = '<button class="btn btn-outline btn-sm" onclick="viewComplaintDetails(\'' + c.id + '\')" style="font-size: 11px; padding: 2px 8px; color: var(--accent);"><i class="fa-solid fa-eye"></i> View</button>';
            } else if (isHR) {
                // HR: VIEW ONLY — CANNOT MODIFY STATUS, CANNOT LOG, CANNOT CONVERT, CANNOT DELETE
                actionHtml = '<button class="btn btn-outline btn-sm" onclick="viewComplaintDetails(\'' + c.id + '\')" style="font-size: 11px; padding: 2px 8px; color: var(--accent);" title="View Incident Dossier"><i class="fa-solid fa-eye"></i> View</button>';
            } else if (isSuperAdmin) {
                actionHtml = '<div style="display: flex; gap: 4px; align-items: center; justify-content: center; flex-wrap: nowrap;">' +
                    '<button class="btn btn-outline btn-sm" onclick="convertComplaintToCap(\'' + c.id + '\', \'' + c.category + '\', \'' + c.state + '\', \'' + c.alleged + '\')" title="Convert to CAP" style="white-space: nowrap; font-size: 10px; padding: 2px 6px;">' +
                        '<i class="fa-solid fa-circle-check"></i> CAP' +
                    '</button>' +
                    '<select onchange="handleComplaintAction(\'' + c.id + '\', this.value)" style="height: 26px; font-size: 10px; border: 1px solid var(--border); border-radius: 4px; background: var(--surface2); color: var(--text); padding: 0 4px; outline: none; cursor: pointer; max-width: 82px;">' +
                        '<option value="">Set...</option>' +
                        '<option value="Open">Open</option>' +
                        '<option value="In Progress">In Progress</option>' +
                        '<option value="Closed">Closed</option>' +
                        '<option value="investigate">Investigate</option>' +
                    '</select>' +
                    '<button class="btn btn-outline btn-sm" onclick="superAdminDeleteComplaint(\'' + c.id + '\')" title="Root Purge / Delete" style="white-space: nowrap; font-size: 10px; padding: 2px 6px; color: #dc2626; border-color: #fca5a5;">' +
                        '<i class="fa-solid fa-trash-can"></i>' +
                    '</button>' +
                '</div>';
            } else if (isComplianceOfficer || isDocAdmin) {
                actionHtml = '<div style="display: flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: nowrap;">' +
                    '<button class="btn btn-outline btn-sm" onclick="convertComplaintToCap(\'' + c.id + '\', \'' + c.category + '\', \'' + c.state + '\', \'' + c.alleged + '\')" title="Convert to CAP" style="white-space: nowrap; font-size: 10px; padding: 2px 6px;">' +
                        '<i class="fa-solid fa-circle-check"></i> CAP' +
                    '</button>' +
                    '<select onchange="handleComplaintAction(\'' + c.id + '\', this.value)" style="height: 26px; font-size: 10px; border: 1px solid var(--border); border-radius: 4px; background: var(--surface2); color: var(--text); padding: 0 4px; outline: none; cursor: pointer; max-width: 90px;">' +
                        '<option value="">Set...</option>' +
                        '<option value="Open">Open</option>' +
                        '<option value="In Progress">In Progress</option>' +
                        '<option value="Closed">Closed</option>' +
                        '<option value="investigate">Investigate</option>' +
                    '</select>' +
                '</div>';
            }

            return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                '<td style="color: var(--accent); font-weight: 700; padding: 9px 8px; white-space: nowrap; font-family: monospace; font-size: 11px;">' + c.id + '</td>' +
                '<td style="padding: 9px 8px;"><div style="font-weight: 600; color: var(--text); font-size: 11.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' + c.state + '">' + c.state + '</div><div style="font-size: 10px; color: var(--text-muted); margin-top: 1px;">' + c.date + '</div></td>' +
                '<td style="padding: 9px 8px;"><span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 600; font-size: 10.5px; display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' + c.category + '">' + c.category + '</span></td>' +
                '<td style="padding: 9px 8px;"><div style="font-weight: 600; font-size: 11px; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' + c.alleged + '">' + c.alleged + '</div><div style="font-size: 10px; color: var(--text-muted); margin-top: 1px;">via ' + c.source + '</div></td>' +
                '<td style="padding: 9px 6px; text-align: center; white-space: nowrap;">' + sevPill + '</td>' +
                '<td style="padding: 9px 6px; text-align: center; white-space: nowrap;">' + statPill + '</td>' +
                '<td style="padding: 9px 8px; text-align: right; white-space: nowrap;">' + actionHtml + '</td>' +
            '</tr>';
        }).join('');
    }

    function viewComplaintDetails(id) {
        const c = REACT_COMPLAINTS.find(x => x.id === id);
        if (!c) return;

        const titleEl = document.getElementById('viewComplaintModalTitle');
        const badgeEl = document.getElementById('viewComplaintSevBadge');
        const bodyEl = document.getElementById('viewComplaintModalBody');

        if (titleEl) titleEl.innerText = c.id + ' — Incident Dossier';
        if (badgeEl) {
            badgeEl.innerHTML = c.severity === 'Critical'
                ? '<span class="pill pill-open">Critical Severity</span>'
                : (c.severity === 'High' ? '<span class="pill pill-open">High</span>' : '<span class="pill pill-progress">' + c.severity + '</span>');
        }

        if (bodyEl) {
            bodyEl.innerHTML = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; background: var(--surface2); padding: 12px; border-radius: 8px; border: 1px solid var(--border);">' +
                '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Date Logged:</strong><div style="font-weight:700; margin-top:2px;">' + c.date + '</div></div>' +
                '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">State Cluster:</strong><div style="font-weight:700; margin-top:2px;">' + c.state + '</div></div>' +
                '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Category:</strong><div style="font-weight:700; margin-top:2px;">' + c.category + '</div></div>' +
                '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Source:</strong><div style="font-weight:700; margin-top:2px;">' + c.source + '</div></div>' +
                '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Alleged Party / Role:</strong><div style="font-weight:700; margin-top:2px;">' + c.alleged + '</div></div>' +
                '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Current Status:</strong><div style="font-weight:700; margin-top:2px; color:var(--accent);">' + c.status + '</div></div>' +
            '</div>' +
            '<div style="margin-bottom: 14px;">' +
                '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Incident Timeline & Narrative:</strong>' +
                '<div style="padding: 10px 12px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; line-height: 1.5; font-size: 12px;">' +
                    (c.details || 'Standard complaint record registered for compliance review.') +
                '</div>' +
            '</div>' +
            '<div style="background: rgba(2,54,123,0.06); border-left: 3px solid var(--accent); padding: 8px 12px; border-radius: 4px; font-size: 11px; color: var(--accent);">' +
                '<i class="fa-solid fa-circle-info me-1"></i> <strong>HR Governance Note:</strong> HR access is strictly view-only. Grievance resolution, CAP generation, and disciplinary investigations are under the jurisdiction of the Compliance Directorate. All logs are tamper-evident.' +
            '</div>';
        }

        openModal('modalViewComplaint');
    }

    function setReactTab(tab) {
        ACTIVE_REACT_TAB = tab;
        ['All', 'Open', 'Progress', 'Closed'].forEach(t => {
            const el = document.getElementById('tab' + t);
            if (el) {
                if ((tab === 'all' && t === 'All') || (tab === 'Open' && t === 'Open') || (tab === 'In Progress' && t === 'Progress') || (tab === 'Closed' && t === 'Closed')) {
                    el.style.background = 'var(--accent)';
                    el.style.color = '#ffffff';
                } else {
                    el.style.background = 'var(--surface)';
                    el.style.color = 'var(--text-dim)';
                }
            }
        });
        renderReactComplaints();
    }

    function searchReactComplaints() {
        renderReactComplaints();
    }

    function handleReactLogSubmit(e) {
        e.preventDefault();
        if (window.CURRENT_USER_ROLE === 'hr') {
            alert('Access Denied: HR has view-only access to complaints and cannot log new grievance entries.');
            return;
        }

        const state = document.getElementById('mState').value;
        const cat = document.getElementById('mCat').value;
        const sev = document.getElementById('mSev').value;
        const alleged = document.getElementById('mAlleged').value || '—';
        const desc = document.getElementById('mDesc').value || '';

        const payload = {
            category: cat,
            title: cat + ' Issue (' + state + ')',
            state: state,
            alleged: alleged,
            severity: sev,
            details: desc,
            source: (window.CURRENT_USER_ROLE === 'doc' ? 'Director of Compliance' : 'Staff / Whistleblower')
        };

        fetch('/api/complaints/submit', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(function(res) { return res.json(); })
        .then(function(result) {
            if (result && result.complaint) {
                REACT_COMPLAINTS.unshift(result.complaint);
                alert('Complaint ' + result.complaint.id + ' registered and synchronized across Compliance Directorate.');
                closeModal('modalLogComplaint');
                renderReactComplaints();
                // Trigger global topbar notification refresh
                if (typeof syncTopbarNotificationsFromBackend === 'function') {
                    syncTopbarNotificationsFromBackend();
                }
            } else {
                alert('Complaint registered.');
                closeModal('modalLogComplaint');
                window.initComplaintsModule();
            }
        })
        .catch(function(err) {
            console.error('Error saving complaint to backend:', err);
            closeModal('modalLogComplaint');
            renderReactComplaints();
        });
    }

    function handleComplaintAction(id, action) {
        if (!action) return;
        if (window.CURRENT_USER_ROLE === 'hr') {
            alert('Access Denied: HR has view-only access and cannot alter complaint statuses.');
            return;
        }

        const complaint = REACT_COMPLAINTS.find(c => c.id === id);
        if (!complaint) return;

        fetch('/api/complaints/action', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, action: action })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (action === 'investigate') {
                complaint.status = 'In Progress';
                showToast('🔍 ' + id + ' flagged for Forensic Investigation. Routed to Investigations module.', 'info');
            } else {
                complaint.status = action;
                const labelMap = { 'Open': '🔓 Reopened', 'In Progress': '⏳ Marked In Progress', 'Closed': '✅ Closed' };
                showToast((labelMap[action] || action) + ' — ' + id + ' status updated.', 'success');
            }
            renderReactComplaints();
            if (typeof syncTopbarNotificationsFromBackend === 'function') syncTopbarNotificationsFromBackend();
        })
        .catch(function(e) {
            complaint.status = (action === 'investigate' ? 'In Progress' : action);
            renderReactComplaints();
        });
    }

    function convertComplaintToCap(id, category, state, alleged) {
        if (window.CURRENT_USER_ROLE === 'hr') {
            alert('Access Denied: HR has view-only access. Corrective Action Plan (CAP) conversions are reserved for Compliance and DoC.');
            return;
        }

        const complaint = REACT_COMPLAINTS.find(c => c.id === id);
        if (!complaint) return;

        if (complaint.status === 'Converted to CAP') {
            showToast('⚠️ ' + id + ' is already tagged as Converted to CAP.', 'warning');
            return;
        }

        // Also create CAP on backend
        fetch('/api/cap/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                issue: category + ' remediation for ' + alleged,
                state: state,
                linkedRef: id,
                responsible: alleged || 'State Coordinator',
                priority: complaint.severity === 'Critical' ? 'Critical' : 'High'
            })
        }).then(function() {
            return fetch('/api/complaints/action', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, action: 'Converted to CAP' })
            });
        }).then(function() {
            complaint.status = 'Converted to CAP';
            showToast('✅ ' + id + ' converted to live CAP in Corrective Action module.', 'success');
            renderReactComplaints();
            if (typeof syncTopbarNotificationsFromBackend === 'function') syncTopbarNotificationsFromBackend();
        }).catch(function() {
            complaint.status = 'Converted to CAP';
            renderReactComplaints();
        });
    }

    function deleteComplaint(id) {
        if (window.CURRENT_USER_ROLE === 'superadmin') {
            return superAdminDeleteComplaint(id);
        }
        showToast('🚫 Deletion Restricted: CCCRN policy does not permit deletion of complaint records for regular roles. Super Administrator required.', 'danger');
    }

    function superAdminDeleteComplaint(id) {
        if (confirm('SUPREME AUTHORITY OVERRIDE:\n\nAre you sure you want to permanently purge complaint ' + id + ' from the ComplianceIQ database? This root action is irrevocable.')) {
            fetch('/api/complaints/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            }).then(function() {
                REACT_COMPLAINTS = REACT_COMPLAINTS.filter(function(c) { return c.id !== id; });
                showToast('🗑️ Incident ' + id + ' permanently purged by Super Administrator.', 'danger');
                renderReactComplaints();
                if (typeof syncTopbarNotificationsFromBackend === 'function') syncTopbarNotificationsFromBackend();
            }).catch(function() {
                REACT_COMPLAINTS = REACT_COMPLAINTS.filter(function(c) { return c.id !== id; });
                renderReactComplaints();
            });
        }
    }

    function showToast(message, type) {
        const colorMap = {
            success: '#059669',
            info: '#0284c7',
            warning: '#d97706',
            danger: '#dc2626'
        };
        const toast = document.createElement('div');
        toast.style.cssText = 'position: fixed; bottom: 24px; right: 24px; z-index: 9999;' +
            'background: ' + (colorMap[type] || '#02367B') + '; color: #fff;' +
            'padding: 12px 18px; border-radius: 8px; font-size: 12px; font-weight: 600;' +
            'max-width: 420px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); line-height: 1.5;';
        toast.innerText = message;
        document.body.appendChild(toast);
        setTimeout(function() { toast.remove(); }, 4500);
    }

    window.initComplaintsModule = function() {
        fetch('/api/backend/data')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data && data.complaints && Array.isArray(data.complaints)) {
                    REACT_COMPLAINTS = data.complaints;
                } else {
                    REACT_COMPLAINTS = [];
                }
                renderReactComplaints();
            })
            .catch(function(e) {
                renderReactComplaints();
            });
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.initComplaintsModule();
    });
</script>

@endsection
