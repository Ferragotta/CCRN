@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="investigationsModuleContainer">

    <!-- SUB-HEADING -->
    <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px;">
                    Forensic Investigation Hub
                </h2>
                <span class="pill pill-open" style="font-size: 10px;">Executive Clearance</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px;">
                Forensic dossier tracking, evidence custody, investigator assignments, and formal disciplinary proceedings.
            </p>
            <div id="investigationsRoleIndicator"></div>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <button class="btn btn-outline btn-sm" onclick="openEvidenceVaultManifest()" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-vault me-1"></i> Evidence Vault Manifest
            </button>
            <button class="btn btn-primary btn-sm" onclick="exportInvestigationsReport('pdf')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-file-pdf me-1"></i> Export Cases Ledger
            </button>
        </div>
    </div>

    <!-- 4 STAT TILES -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px;">
        <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #991b1b; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Active Investigations</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #dc2626; line-height: 1;" id="statActiveInv">4</div>
            <div style="font-size: 11px; color: #991b1b; font-weight: 600; margin-top: 4px;">2 Critical Escalations</div>
        </div>
        <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #92400e; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Evidence Collection</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;" id="statEvidInv">2</div>
            <div style="font-size: 11px; color: #92400e; font-weight: 600; margin-top: 4px;">Subpoenaed Records</div>
        </div>
        <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #0369a1; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Referred from Complaints</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0284c7; line-height: 1;" id="statReferredInv">6</div>
            <div style="font-size: 11px; color: #0369a1; font-weight: 600; margin-top: 4px;">Triage Conversions</div>
        </div>
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #065f46; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Closed This FY</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #059669; line-height: 1;" id="statClosedInv">8</div>
            <div style="font-size: 11px; color: #065f46; font-weight: 600; margin-top: 4px;">100% Retrospective Audit</div>
        </div>
    </div>

    <!-- FILTER TABS & SEARCH CONTROLS -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; gap: 6px; flex-wrap: wrap;" id="invFilterTabs">
            <button class="btn btn-primary btn-sm inv-tab active" onclick="filterInvStatus('all', this)" style="font-size: 11px; font-weight: 700;">
                All Cases (4)
            </button>
            <button class="btn btn-outline btn-sm inv-tab" onclick="filterInvStatus('Under Investigation', this)" style="font-size: 11px; font-weight: 600;">
                Under Investigation (1)
            </button>
            <button class="btn btn-outline btn-sm inv-tab" onclick="filterInvStatus('Evidence Collection', this)" style="font-size: 11px; font-weight: 600;">
                Evidence Collection (1)
            </button>
            <button class="btn btn-outline btn-sm inv-tab" onclick="filterInvStatus('Referred to Disciplinary', this)" style="font-size: 11px; font-weight: 600;">
                Disciplinary (1)
            </button>
            <button class="btn btn-outline btn-sm inv-tab" onclick="filterInvStatus('Closed', this)" style="font-size: 11px; font-weight: 600;">
                Closed (1)
            </button>
        </div>

        <div style="display: flex; gap: 8px; align-items: center;">
            <select id="invStateFilter" onchange="searchInvestigations()" style="height: 32px; padding: 0 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                <option value="">All States</option>
                <option>Kano</option>
                <option>Lagos</option>
                <option>Borno</option>
                <option>Rivers</option>
                <option>Abuja FCT</option>
                <option>Kaduna</option>
            </select>
            <input type="text" id="invSearchInput" onkeyup="searchInvestigations()" placeholder="Search case ref, allegation, lead..." style="height: 32px; padding: 0 10px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; width: 220px; background: var(--surface); color: var(--text);">
        </div>
    </div>

    <!-- FORMAL FORENSIC INVESTIGATIONS REGISTER (100% WIDTH, ZERO HORIZONTAL SCROLL) -->
    <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box; margin-bottom: 0;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2);">
            <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-magnifying-glass-chart" style="color: var(--accent);"></i> Formal Forensic Investigations Register
            </div>
            <span style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-user-shield me-1"></i> Confidential Legal Dossier</span>
        </div>

        <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border);">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                <thead>
                    <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);">
                        <th style="width: 10%; padding: 9px 8px; text-align: left;">Case Ref</th>
                        <th style="width: 9%; padding: 9px 8px; text-align: left;">Source</th>
                        <th style="width: 9%; padding: 9px 8px; text-align: left;">State</th>
                        <th style="width: 9%; padding: 9px 6px; text-align: center;">Severity</th>
                        <th style="width: 26%; padding: 9px 10px; text-align: left;">Allegation & Scope</th>
                        <th style="width: 17%; padding: 9px 8px; text-align: left;">Assigned Lead</th>
                        <th style="width: 7%; padding: 9px 6px; text-align: center;">Duration</th>
                        <th style="width: 9%; padding: 9px 6px; text-align: center;">Status</th>
                        <th style="width: 4%; padding: 9px 6px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody id="investigationsTableBody">
                    <tr><td colspan="9" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">
                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Initializing forensic register...
                    </td></tr>
                </tbody>
            </table>
        </div>

        <div style="padding: 10px 4px 0; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--surface2); margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
            <span>Showing <strong id="invCountDisplay">4</strong> active forensic cases.</span>
            <span style="color: var(--accent); font-weight: 700;"><i class="fa-solid fa-lock me-1"></i> Chain of Custody Verified</span>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════════════════════════════
     MODAL 1: VIEW INVESTIGATION DOSSIER
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalViewInvestigation" style="display: none;" onclick="if(event.target===this)closeModal('modalViewInvestigation')">
    <div class="modal-dialog" style="max-width: 620px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="modal-title" id="viewInvModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">Case File</span>
                <span id="viewInvStatusBadge"></span>
            </div>
            <button class="modal-close" onclick="closeModal('modalViewInvestigation')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px; color: var(--text);" id="viewInvModalBody"></div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-shield-halved"></i> Formal Dossier &middot; Disciplinary Alignment</span>
            <button class="btn btn-primary btn-sm" onclick="closeModal('modalViewInvestigation')">Close</button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 2: EVIDENCE VAULT MANIFEST (READ-ONLY)
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalEvidenceVault" style="display: none;" onclick="if(event.target===this)closeModal('modalEvidenceVault')">
    <div class="modal-dialog" style="max-width: 650px; width: 95%;">
        <div class="modal-header">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-vault text-primary me-2"></i> Evidence Vault Manifest (Chain of Custody)
            </span>
            <button class="modal-close" onclick="closeModal('modalEvidenceVault')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px; color: var(--text);">
            <div style="background: rgba(2,54,123,0.06); padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 11px; color: var(--accent);">
                <i class="fa-solid fa-circle-info me-1"></i> Read-only view of physical and digital forensic exhibits currently secured under chain of custody.
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <div style="background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; color: var(--text);"><i class="fa-solid fa-file-pdf text-danger me-2"></i>INV-012_Audit_Vouchers_Kano.pdf</div>
                        <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">Case: INV-012 &middot; Hash: SHA-256 Verified &middot; Custody: A. Bello</div>
                    </div>
                    <span class="pill pill-closed" style="font-size: 10px;">Secured</span>
                </div>
                <div style="background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; color: var(--text);"><i class="fa-solid fa-file-lines text-primary me-2"></i>INV-011_Vendor_Bidding_Transcripts_Lagos.xlsx</div>
                        <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">Case: INV-011 &middot; Hash: SHA-256 Verified &middot; Custody: Amaka Obi</div>
                    </div>
                    <span class="pill pill-closed" style="font-size: 10px;">Secured</span>
                </div>
                <div style="background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; color: var(--text);"><i class="fa-solid fa-file-audio text-warning me-2"></i>INV-010_Safeguarding_Interviews_Borno.m4a</div>
                        <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">Case: INV-010 &middot; Confidential Audio Transcript &middot; Custody: Emeka Eze</div>
                    </div>
                    <span class="pill pill-closed" style="font-size: 10px;">Secured</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline btn-sm" onclick="closeModal('modalEvidenceVault')">Close</button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 3: UPDATE CASE STATUS & FINDINGS
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalUpdateCaseStatus" style="display: none;" onclick="if(event.target===this)closeModal('modalUpdateCaseStatus')">
    <div class="modal-dialog" style="max-width: 520px; width: 95%;">
        <div class="modal-header">
            <span class="modal-title" id="updateCaseStatusModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Update Investigation Status
            </span>
            <button class="modal-close" onclick="closeModal('modalUpdateCaseStatus')">&times;</button>
        </div>
        <form onsubmit="handleSaveCaseStatus(event)">
            <input type="hidden" id="updateCaseRef">
            <div class="modal-body" style="font-size: 12px; color: var(--text);">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px; color: var(--text-muted); text-transform: uppercase;">Case Reference:</label>
                    <input type="text" id="updateCaseRefDisplay" readonly style="width: 100%; padding: 7px 10px; font-size: 12px; font-weight: 700; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--accent);">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px; color: var(--text-muted); text-transform: uppercase;">New Case Status *</label>
                    <select id="updateCaseStatusSelect" required style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                        <option value="Under Investigation">Under Investigation</option>
                        <option value="Evidence Collection">Evidence Collection</option>
                        <option value="Referred to Disciplinary">Referred to Disciplinary</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px; color: var(--text-muted); text-transform: uppercase;">Investigation Progress / Remediation Notes *</label>
                    <textarea id="updateCaseNotes" rows="3" required placeholder="Detail the latest forensic findings or decision rationale..." style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalUpdateCaseStatus')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check me-1"></i> Save Status</button>
            </div>
        </form>
    </div>
</div>

<script>
var INVESTIGATIONS_DATA = [];

var CURRENT_INV_FILTER = 'all';

function updateInvestigationKPIs() {
    var total = INVESTIGATIONS_DATA.length;
    var active = INVESTIGATIONS_DATA.filter(function(i){ return (i.status || '').toLowerCase().includes('investigation') || (i.status || '').toLowerCase().includes('open'); }).length;
    var evid = INVESTIGATIONS_DATA.filter(function(i){ return (i.status || '').toLowerCase().includes('evidence'); }).length;
    var referred = INVESTIGATIONS_DATA.filter(function(i){ return (i.status || '').toLowerCase().includes('referred') || (i.status || '').toLowerCase().includes('disciplinary'); }).length;
    var closed = INVESTIGATIONS_DATA.filter(function(i){ return (i.status || '').toLowerCase().includes('closed'); }).length;

    var elActive = document.getElementById('statActiveInv');
    var elEvid = document.getElementById('statEvidInv');
    var elReferred = document.getElementById('statReferredInv');
    var elClosed = document.getElementById('statClosedInv');

    if (elActive) elActive.innerText = active;
    if (elEvid) elEvid.innerText = evid;
    if (elReferred) elReferred.innerText = referred;
    if (elClosed) elClosed.innerText = closed;
}

function renderInvestigationsTable(items) {
    updateInvestigationKPIs();
    var tbody = document.getElementById('investigationsTableBody');
    if (!tbody) return;

    var isHr = window.CURRENT_USER_ROLE === 'hr';
    var isSuperAdmin = window.CURRENT_USER_ROLE === 'superadmin';
    var list = items || INVESTIGATIONS_DATA;
    var countEl = document.getElementById('invCountDisplay');
    if (countEl) countEl.textContent = list.length;

    if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12.5px;">' +
            '<div style="margin-bottom: 8px;"><i class="fa-solid fa-folder-open" style="font-size: 28px; color: #cbd5e1;"></i></div>' +
            '<strong>No forensic investigations currently active.</strong>' +
            '<div style="font-size: 11px; margin-top: 4px; color: var(--text-dim);">' +
                'Cases are opened when serious grievances or audit findings are flagged for investigation in Complaints or CAP.' +
            '</div>' +
        '</td></tr>';
        return;
    }

    tbody.innerHTML = list.map(function(inv) {
        var statPill = inv.status === 'Under Investigation' ? '<span class="pill pill-open" style="font-size: 10px; font-weight: 700; white-space: nowrap;">Under Investigation</span>' :
                       inv.status === 'Evidence Collection' ? '<span class="pill pill-progress" style="font-size: 10px; font-weight: 700; white-space: nowrap;">Evidence Collection</span>' :
                       inv.status === 'Referred to Disciplinary' ? '<span class="pill" style="background:#f0f7ff; color:#7e22ce; border:1px solid #bae6fd; font-size: 10px; font-weight: 700; white-space: nowrap;">Disciplinary</span>' :
                       '<span class="pill pill-closed" style="font-size: 10px; font-weight: 700; white-space: nowrap;">Closed</span>';

        var sevBadge = inv.severity === 'Critical'
            ? '<span class="pill pill-open" style="font-size: 10px; font-weight: 800; background:#fee2e2; color:#991b1b; border:1px solid #fca5a5;">Critical</span>'
            : inv.severity === 'High'
            ? '<span class="pill" style="background:#fef3c7; color:#92400e; font-size: 10px; font-weight: 700; border:1px solid #fde68a;">High</span>'
            : '<span class="pill pill-closed" style="font-size: 10px; font-weight: 700;">Medium</span>';

        var daysBadge = inv.daysOpen >= 30
            ? '<span class="pill pill-open" style="font-size: 10px; font-weight: 700;"><i class="fa-regular fa-clock me-1"></i>' + inv.daysOpen + 'd</span>'
            : '<span class="pill" style="background:var(--surface2); color:var(--text-dim); font-size: 10px; font-weight: 600;"><i class="fa-regular fa-clock me-1"></i>' + inv.daysOpen + 'd</span>';

        // Lead Investigator display
        var leadHtml = '';
        if (isHr) {
            leadHtml = '<div style="display: flex; align-items: center; gap: 6px;">' +
                '<div style="width: 22px; height: 22px; border-radius: 50%; background: rgba(2,54,123,0.08); display: flex; align-items: center; justify-content: center; font-size: 10px; color: var(--accent); font-weight: 700; flex-shrink: 0;"><i class="fa-solid fa-user-shield"></i></div>' +
                '<span style="font-size: 11px; font-weight: 600; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + inv.lead + '</span>' +
            '</div>';
        } else {
            leadHtml = '<select onchange="handleLeadChange('' + inv.ref + '', this.value)" style="height: 28px; font-size: 11px; font-weight: 600; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); width: 100%; padding: 0 6px;">' +
                '<option ' + (inv.lead.includes('A. Bello') ? 'selected' : '') + '>A. Bello (Lead Auditor)</option>' +
                '<option ' + (inv.lead.includes('Amaka Obi') ? 'selected' : '') + '>Amaka Obi</option>' +
                '<option ' + (inv.lead.includes('Emeka Eze') ? 'selected' : '') + '>Emeka Eze (Safeguarding)</option>' +
                '<option ' + (inv.lead.includes('M. Ibrahim') ? 'selected' : '') + '>M. Ibrahim</option>' +
            '</select>';
        }

        // Actions: View Dossier + Update Status
        var actionsHtml = '';
        if (isHr) {
            actionsHtml = '<button class="btn btn-outline btn-sm" onclick="viewInvestigationDossier('' + inv.ref + '')" style="font-size: 11px; padding: 3px 8px; color: var(--accent); font-weight: 700; border-radius: 4px;" title="View Case Dossier"><i class="fa-solid fa-eye me-1"></i>View</button>';
        } else if (isSuperAdmin) {
            // Super Admin: View + Update Status + Root Purge
            actionsHtml = '<div style="display: flex; gap: 4px; justify-content: center;">' +
                '<button class="btn btn-outline btn-sm" onclick="viewInvestigationDossier(\'' + inv.ref + '\')" title="View Case File" style="padding: 3px 6px; font-size: 11px; border-radius: 4px;"><i class="fa-solid fa-eye"></i></button>' +
                '<button class="btn btn-outline btn-sm" onclick="openUpdateStatusModal(\'' + inv.ref + '\')" title="Update Case Status" style="padding: 3px 6px; font-size: 11px; color: var(--accent); border-color: var(--accent); border-radius: 4px;"><i class="fa-solid fa-pen-to-square"></i></button>' +
                '<button class="btn btn-outline btn-sm" onclick="superAdminDeleteInvestigation(\'' + inv.ref + '\')" title="Root Purge Investigation" style="padding: 3px 6px; font-size: 11px; color: #dc2626; border-color: #fca5a5; border-radius: 4px;"><i class="fa-solid fa-trash-can"></i></button>' +
            '</div>';
        } else {
            actionsHtml = '<div style="display: flex; gap: 4px; justify-content: center;">' +
                '<button class="btn btn-outline btn-sm" onclick="viewInvestigationDossier(\'' + inv.ref + '\')" title="View Case File" style="padding: 3px 7px; font-size: 11px; border-radius: 4px;"><i class="fa-solid fa-eye"></i></button>' +
                '<button class="btn btn-outline btn-sm" onclick="openUpdateStatusModal(\'' + inv.ref + '\')" title="Update Case Status" style="padding: 3px 7px; font-size: 11px; color: var(--accent); border-color: var(--accent); border-radius: 4px;"><i class="fa-solid fa-pen-to-square"></i></button>' +
            '</div>';
        }

        return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; vertical-align: middle; white-space: nowrap;"><strong style="color: var(--accent); font-family: monospace; font-size: 12px;">' + inv.ref + '</strong></td>' +
            '<td style="padding: 10px 8px; vertical-align: middle; white-space: nowrap;"><span style="background: rgba(2,54,123,0.06); color: var(--accent); padding: 3px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; font-family: monospace;"><i class="fa-solid fa-arrow-turn-up me-1 text-muted"></i>' + inv.sourceComp + '</span></td>' +
            '<td style="padding: 10px 8px; vertical-align: middle; white-space: nowrap; font-size: 11px;"><span style="font-weight: 600; color: var(--text);"><i class="fa-solid fa-location-dot me-1 text-muted"></i>' + inv.state + '</span></td>' +
            '<td style="padding: 10px 6px; vertical-align: middle; text-align: center; white-space: nowrap;">' + sevBadge + '</td>' +
            '<td style="padding: 10px 10px; vertical-align: middle; line-height: 1.35;">' +
                '<div style="font-weight: 700; font-size: 11.5px; color: var(--text); word-break: break-word;">' + inv.allegation + '</div>' +
                '<div style="font-size: 10.5px; color: var(--text-muted); margin-top: 2px; word-break: break-word;">' + inv.notes + '</div>' +
            '</td>' +
            '<td style="padding: 10px 8px; vertical-align: middle;">' + leadHtml + '</td>' +
            '<td style="padding: 10px 6px; vertical-align: middle; text-align: center; white-space: nowrap;">' + daysBadge + '</td>' +
            '<td style="padding: 10px 6px; vertical-align: middle; text-align: center; white-space: nowrap;">' + statPill + '</td>' +
            '<td style="padding: 10px 6px; vertical-align: middle; text-align: center; white-space: nowrap;">' + actionsHtml + '</td>' +
        '</tr>';
    }).join('');
}

function filterInvStatus(status, btn) {
    CURRENT_INV_FILTER = status;
    document.querySelectorAll('.inv-tab').forEach(function(b){
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline');
    });
    if (btn) {
        btn.classList.add('active', 'btn-primary');
        btn.classList.remove('btn-outline');
    }
    searchInvestigations();
}

function searchInvestigations() {
    var q = ((document.getElementById('invSearchInput') || {}).value || '').toLowerCase();
    var state = ((document.getElementById('invStateFilter') || {}).value || '').toLowerCase();

    var filtered = INVESTIGATIONS_DATA.filter(function(inv) {
        var matchStatus = CURRENT_INV_FILTER === 'all' || inv.status === CURRENT_INV_FILTER;
        var matchState = !state || inv.state.toLowerCase().includes(state);
        var matchQ = !q || inv.ref.toLowerCase().includes(q) || inv.sourceComp.toLowerCase().includes(q) || inv.allegation.toLowerCase().includes(q) || inv.lead.toLowerCase().includes(q);
        return matchStatus && matchState && matchQ;
    });

    renderInvestigationsTable(filtered);
}

function viewInvestigationDossier(ref) {
    var inv = INVESTIGATIONS_DATA.find(function(x){ return x.ref === ref; });
    if (!inv) return;

    var titleEl = document.getElementById('viewInvModalTitle');
    var badgeEl = document.getElementById('viewInvStatusBadge');
    var bodyEl = document.getElementById('viewInvModalBody');

    if (titleEl) titleEl.textContent = inv.ref + ' — Formal Forensic Dossier';
    if (badgeEl) {
        badgeEl.innerHTML = '<span class="pill pill-open">' + inv.status + '</span>';
    }

    if (bodyEl) {
        bodyEl.innerHTML = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: var(--surface2); padding: 12px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 14px;">' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Source Whistleblower Ref:</strong><div style="font-weight:700; margin-top:2px; color:var(--accent);">' + inv.sourceComp + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">State Field Cluster:</strong><div style="font-weight:700; margin-top:2px;">' + inv.state + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Assigned Lead Investigator:</strong><div style="font-weight:700; margin-top:2px;">' + inv.lead + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Investigation Duration:</strong><div style="font-weight:700; margin-top:2px; color:var(--danger);">' + inv.daysOpen + ' Days Active</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Severity Classification:</strong><div style="font-weight:700; margin-top:2px;">' + inv.severity + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Evidence Vault Exhibits:</strong><div style="font-weight:700; margin-top:2px; color:var(--accent);">' + inv.evidenceCount + ' Secured Files</div></div>' +
        '</div>' +
        '<div style="margin-bottom: 14px;">' +
            '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Allegation Summary & Scope:</strong>' +
            '<div style="padding: 10px 12px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; line-height: 1.4; font-size: 12px; font-weight: 600;">' +
                inv.allegation +
            '</div>' +
        '</div>' +
        '<div style="margin-bottom: 14px;">' +
            '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Current Case Progress & Briefing Notes:</strong>' +
            '<div style="padding: 10px 12px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; line-height: 1.4; font-size: 12px;">' +
                inv.notes +
            '</div>' +
        '</div>' +
        '<div style="background: rgba(2,54,123,0.06); border-left: 3px solid var(--accent); padding: 8px 12px; border-radius: 4px; font-size: 11px; color: var(--accent);">' +
            '<i class="fa-solid fa-circle-info me-1"></i> <strong>Executive Audit Mode:</strong> Case management, investigator reassignment, and status transitions are governed by the Compliance Directorate.' +
        '</div>';
    }

    openModal('modalViewInvestigation');
}

function openEvidenceVaultManifest() {
    openModal('modalEvidenceVault');
}

function exportInvestigationsReport(format) {
    alert('Compiling Formal Forensic Investigations Register (' + format.toUpperCase() + ')...\n\nReport generated under executive chain of custody.');
}

function handleLeadChange(ref, newLead) {
    if (window.CURRENT_USER_ROLE === 'hr') {
        alert('Access Denied: HR role has view-only access. Investigator reassignment is restricted to the Compliance Directorate.');
        renderInvestigationsTable();
        return;
    }
    var inv = INVESTIGATIONS_DATA.find(function(x){ return x.ref === ref; });
    if (inv) {
        inv.lead = newLead;
        alert('Investigator for ' + ref + ' reassigned to: ' + newLead);
    }
}

function superAdminDeleteInvestigation(ref) {
    if (confirm('SUPREME AUTHORITY OVERRIDE:\n\nAre you sure you want to permanently purge forensic case ' + ref + '? This will erase the chain of custody and case dossier.')) {
        INVESTIGATIONS_DATA = INVESTIGATIONS_DATA.filter(function(x){ return x.ref !== ref; });
        searchInvestigations();
        alert('Forensic Case ' + ref + ' permanently purged by Super Administrator.');
    }
}

function openUpdateStatusModal(ref) {
    if (window.CURRENT_USER_ROLE === 'hr') {
        alert('Access Denied: HR role has view-only access to Investigations.');
        return;
    }
    var inv = INVESTIGATIONS_DATA.find(function(x){ return x.ref === ref; });
    if (!inv) return;

    document.getElementById('updateCaseRef').value = inv.ref;
    document.getElementById('updateCaseRefDisplay').value = inv.ref + ' — ' + inv.allegation;
    document.getElementById('updateCaseStatusSelect').value = inv.status;
    document.getElementById('updateCaseNotes').value = inv.notes || '';

    openModal('modalUpdateCaseStatus');
}

function handleSaveCaseStatus(e) {
    e.preventDefault();
    var ref = document.getElementById('updateCaseRef').value;
    var newStat = document.getElementById('updateCaseStatusSelect').value;
    var notes = document.getElementById('updateCaseNotes').value;

    var inv = INVESTIGATIONS_DATA.find(function(x){ return x.ref === ref; });
    if (inv) {
        inv.status = newStat;
        inv.notes = notes;
        renderInvestigationsTable();
        closeModal('modalUpdateCaseStatus');
        alert(ref + ' status successfully updated to: ' + newStat);
    }
}

window.initInvestigationsModule = function() {
    var role = window.CURRENT_USER_ROLE || 'hr';
    var ind = document.getElementById('investigationsRoleIndicator');

    if (ind) {
        if (role === 'hr') {
            ind.innerHTML = '<div style="margin-top: 6px; padding: 7px 14px; background: rgba(217, 119, 6, 0.08); border-left: 4px solid var(--warning); border-radius: 6px; font-size: 11px; color: #b45309; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">' +
                '<div style="display: flex; align-items: center; gap: 8px;">' +
                    '<i class="fa-solid fa-eye" style="font-size: 13px;"></i>' +
                    '<div><strong>HR View-Only Access:</strong> Formal investigation proceedings and forensic findings are monitored for disciplinary and staffing alignment only. Case reassignments, status modifications, and evidence vault administration are restricted to the Compliance Directorate.</div>' +
                '</div>' +
                '<span style="font-size: 10px; font-weight: 700; background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 4px; border: 1px solid #fde68a;"><i class="fa-solid fa-lock"></i> VIEW ONLY</span>' +
            '</div>';
        } else if (role === 'compliance_officer' || role === 'compliance') {
            ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: #ecfdf5; color: #065f46; border: 1px solid #bbf7d0; border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-user-shield"></i> <strong>Compliance Specialist:</strong> Full Forensic Oversight, Lead Assignment & Status Updates &middot; <span style="color:#dc2626; font-weight:700;"><i class="fa-solid fa-ban"></i> No Deletion</span></div>';
        } else if (role === 'doc' || role === 'superadmin') {
            ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: rgba(2,54,123,0.08); color: var(--accent); border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Forensic Oversight, Investigator Assignment & Evidence Vault Administration</div>';
        }
    }

    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data && data.investigations && Array.isArray(data.investigations)) {
                INVESTIGATIONS_DATA = data.investigations;
            } else {
                INVESTIGATIONS_DATA = [];
            }
            renderInvestigationsTable();
        })
        .catch(function(e) {
            renderInvestigationsTable();
        });
};

document.addEventListener('DOMContentLoaded', function(){
    window.initInvestigationsModule();
});
</script>
@endsection
