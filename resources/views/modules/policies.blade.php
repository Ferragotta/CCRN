@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="policiesModuleContainer">

    <!-- SUB-HEADING -->
    <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px;">
                    Institutional Policy Repository & Staff Sign-Off
                </h2>
                <span class="pill pill-closed" style="font-size: 10px;">Governance Baseline</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px;">
                Statutory policies, mandatory digital acknowledgements, version control, and compliance gates across 490 personnel.
            </p>
            <div id="policiesRoleIndicator"></div>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <button class="btn btn-outline btn-sm" onclick="broadcastPolicySignOff()" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-bullhorn me-1"></i> Send Sign-off Broadcast
            </button>
            <button class="btn btn-outline btn-sm" onclick="exportPolicyComplianceReport('pdf')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-file-pdf me-1"></i> Compliance Dossier
            </button>
            <button class="btn btn-primary btn-sm" onclick="openModal('modalUploadPolicy')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload New Policy
            </button>
        </div>
    </div>

    <!-- 4 STAT TILES -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px;">
        <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #0369a1; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Institutional Policies</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0284c7; line-height: 1;" id="statTotalPolicies">8</div>
            <div style="font-size: 11px; color: #0369a1; font-weight: 600; margin-top: 4px;">All Version Controlled</div>
        </div>
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #065f46; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Active Enforced</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #059669; line-height: 1;" id="statActivePolicies">6</div>
            <div style="font-size: 11px; color: #065f46; font-weight: 600; margin-top: 4px;">Currently In Force</div>
        </div>
        <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #92400e; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Under Annual Review</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;" id="statReviewPolicies">2</div>
            <div style="font-size: 11px; color: #92400e; font-weight: 600; margin-top: 4px;">POL-DATA & POL-PROC</div>
        </div>
        <div style="background: #02367B; border: 1px solid #002b66; border-radius: 10px; padding: 14px; text-align: center; color: #fff;">
            <div style="font-size: 10px; color: #bae6fd; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Staff Sign-off Rate</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #55E2E9; line-height: 1;" id="statAvgSignOff">89.4%</div>
            <div style="font-size: 11px; color: #bae6fd; font-weight: 600; margin-top: 4px;">438 / 490 Staff Certified</div>
        </div>
    </div>

    <!-- FILTER TABS & SEARCH (FULL ACCESS CONTROLS) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; gap: 6px; flex-wrap: wrap;" id="policyFilterTabs">
            <button class="btn btn-primary btn-sm policy-tab active" onclick="filterPolicyStatus('all', this)" style="font-size: 11px; font-weight: 700;">
                All Policies (8)
            </button>
            <button class="btn btn-outline btn-sm policy-tab" onclick="filterPolicyStatus('Active', this)" style="font-size: 11px; font-weight: 600;">
                Active (6)
            </button>
            <button class="btn btn-outline btn-sm policy-tab" onclick="filterPolicyStatus('Under Review', this)" style="font-size: 11px; font-weight: 600;">
                Under Review (2)
            </button>
            <button class="btn btn-outline btn-sm policy-tab" onclick="filterPolicyStatus('Archived', this)" style="font-size: 11px; font-weight: 600;">
                Archived (0)
            </button>
        </div>

        <div style="display: flex; gap: 8px; align-items: center;">
            <select id="policyCategoryFilter" onchange="searchPolicies()" style="height: 32px; padding: 0 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                <option value="">All Categories</option>
                <option>Human Resources</option>
                <option>Ethics & Safeguarding</option>
                <option>Finance & Audit</option>
                <option>Operations & Travel</option>
                <option>IT & Data Privacy</option>
                <option>Procurement</option>
            </select>
            <input type="text" id="policySearchInput" onkeyup="searchPolicies()" placeholder="Search code, title, owner..." style="height: 32px; padding: 0 10px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; width: 200px; background: var(--surface); color: var(--text);">
        </div>
    </div>

    <!-- POLICY LEDGER CARD (100% WIDTH, FIXED LAYOUT, ZERO HORIZONTAL SCROLL) -->
    <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box; margin-bottom: 0;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2);">
            <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-file-shield" style="color: var(--accent);"></i> CCCRN Statutory Policy Index & Sign-Off Ledger
            </div>
            <span style="font-size: 11px; color: var(--text-muted);">Institutional Version Control · FY2026</span>
        </div>

        <div style="width: 100%; overflow: hidden;">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                        <th style="width: 13%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Policy Code</th>
                        <th style="width: 25%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Policy Title & Scope</th>
                        <th style="width: 13%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Category</th>
                        <th style="width: 7%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Version</th>
                        <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Effective</th>
                        <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Review Due</th>
                        <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Staff Sign-Off</th>
                        <th style="width: 9%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
                        <th style="width: 11%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">HR Actions</th>
                    </tr>
                </thead>
                <tbody id="policiesTableBody"></tbody>
            </table>
        </div>

        <div style="padding: 10px 4px 0; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--surface2); margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
            <span>Showing <strong id="policiesCountDisplay">8</strong> institutional policy documents.</span>
            <span style="color: var(--accent); font-weight: 700;"><i class="fa-solid fa-lock-open me-1"></i> Full Administrative Permissions Active</span>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 1: UPLOAD NEW POLICY (HR ALL ACCESS)
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalUploadPolicy" style="display: none;" onclick="if(event.target===this)closeModal('modalUploadPolicy')">
    <div class="modal-dialog" style="max-width: 580px; width: 95%;">
        <div class="modal-header">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Upload New Statutory Policy
            </span>
            <button class="modal-close" onclick="closeModal('modalUploadPolicy')">&times;</button>
        </div>
        <form onsubmit="handleUploadPolicySubmit(event)">
            <div class="modal-body" style="font-size: 12px; color: var(--text); display: flex; flex-direction: column; gap: 12px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Policy Code *</label>
                        <input type="text" id="uploadPolCode" required placeholder="e.g. POL-HR-008" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Category *</label>
                        <select id="uploadPolCategory" required style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                            <option>Human Resources</option>
                            <option>Ethics & Safeguarding</option>
                            <option>Finance & Audit</option>
                            <option>Operations & Travel</option>
                            <option>IT & Data Privacy</option>
                            <option>Procurement</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Policy Title *</label>
                    <input type="text" id="uploadPolTitle" required placeholder="e.g. Staff Grievance Redressal & Anti-Retaliation Policy" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Initial Version *</label>
                        <input type="text" id="uploadPolVersion" required value="v1.0" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Effective Date *</label>
                        <input type="date" id="uploadPolEffective" required value="2026-04-01" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Review Due Date *</label>
                        <input type="date" id="uploadPolReview" required value="2027-04-01" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Attach Official PDF Document *</label>
                    <input type="file" id="uploadPolFile" required style="width: 100%; padding: 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text); box-sizing: border-box;">
                </div>

                <div style="background: rgba(2,54,123,0.06); padding: 10px 12px; border-radius: 6px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="uploadPolMandatorySignOff" checked style="accent-color: var(--accent);">
                    <label for="uploadPolMandatorySignOff" style="font-size: 11px; font-weight: 600; cursor: pointer; color: var(--accent);">
                        Enforce Mandatory Digital Sign-off & Broadcast Alert to all 490 Staff
                    </label>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalUploadPolicy')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 700;">Publish & Distribute Policy</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 2: VIEW / READ POLICY & SIGN-OFF BREAKDOWN
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalViewPolicy" style="display: none;" onclick="if(event.target===this)closeModal('modalViewPolicy')">
    <div class="modal-dialog" style="max-width: 600px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="modal-title" id="viewPolicyModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">Policy Dossier</span>
                <span id="viewPolicyStatusBadge"></span>
            </div>
            <button class="modal-close" onclick="closeModal('modalViewPolicy')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px; color: var(--text);" id="viewPolicyModalBody"></div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
            <button class="btn btn-outline btn-sm" id="btnDownloadPolicyPdf" onclick="alert('Downloading signed policy PDF document...')"><i class="fa-solid fa-download me-1"></i> Download PDF</button>
            <button class="btn btn-primary btn-sm" onclick="closeModal('modalViewPolicy')">Done</button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 3: EDIT POLICY & VERSION BUMP (HR ALL ACCESS)
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalEditPolicy" style="display: none;" onclick="if(event.target===this)closeModal('modalEditPolicy')">
    <div class="modal-dialog" style="max-width: 540px; width: 95%;">
        <div class="modal-header">
            <span class="modal-title" id="editPolicyModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">Edit Policy & Version Bump</span>
            <button class="modal-close" onclick="closeModal('modalEditPolicy')">&times;</button>
        </div>
        <form onsubmit="handleEditPolicySubmit(event)">
            <input type="hidden" id="editPolTargetCode">
            <div class="modal-body" style="font-size: 12px; color: var(--text); display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Policy Title *</label>
                    <input type="text" id="editPolTitle" required style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Version (Bump on revision) *</label>
                        <input type="text" id="editPolVersion" required style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Enforcement Status *</label>
                        <select id="editPolStatus" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                            <option value="Active">Active (In Force)</option>
                            <option value="Under Review">Under Review</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Effective Date *</label>
                        <input type="date" id="editPolEffective" required style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Next Annual Review *</label>
                        <input type="date" id="editPolReview" required style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Replace Document File (Optional):</label>
                    <input type="file" id="editPolFile" style="width: 100%; padding: 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text); box-sizing: border-box;">
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalEditPolicy')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 700;">Save Policy Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
var POLICIES_DATA = [
    { code: 'POL-HR-001', title: 'HR Recruitment, Compensation & Disciplinary Governance', category: 'Human Resources', version: 'v2.2', effective: '01 Jan 2026', review: '01 Jan 2027', signed: 472, total: 490, status: 'Active', owner: 'HR Directorate', escrow: false, scope: 'All regular, temporary, and project-based personnel across CCCRN operations.' },
    { code: 'POL-PSEA-002', title: 'Prevention of Sexual Exploitation, Abuse & Harassment (PSEA)', category: 'Ethics & Safeguarding', version: 'v2.1', effective: '01 Jan 2026', review: '01 Jan 2027', signed: 461, total: 490, status: 'Active', owner: 'Compliance & Safeguarding', escrow: false, scope: 'Mandatory zero-tolerance code for all staff, vendors, and community volunteers.' },
    { code: 'POL-FIN-003', title: 'Financial Management, Advance Retirals & Asset Custody', category: 'Finance & Audit', version: 'v3.0', effective: '15 Mar 2026', review: '15 Mar 2027', signed: 382, total: 490, status: 'Active', owner: 'Finance Lead', escrow: false, scope: 'Controls governing project fund advances, disbursement thresholds, and audit trails.' },
    { code: 'POL-TRV-04', title: 'Travel & Per-Diem Policy with Mandatory Boarding Pass Reconciliation', category: 'Operations & Travel', version: 'v1.5', effective: '01 Jan 2026', review: '01 Jan 2027', signed: 416, total: 490, status: 'Active', owner: 'Operations & Admin', escrow: true, scope: 'Enforces POL-TRV-03 Escrow Gate: strict travel advance lockdown until previous travel proof is reconciled.' },
    { code: 'POL-DATA-005', title: 'Data Protection, Patient Privacy & NDPR Compliance', category: 'IT & Data Privacy', version: 'v1.0', effective: '10 Feb 2025', review: '10 Feb 2026', signed: 303, total: 490, status: 'Under Review', owner: 'IT & SI Directorate', escrow: false, scope: 'Clinical and biometric confidentiality protocols aligned with NDPR and international donor standards.' },
    { code: 'POL-WHISTLE-006', title: 'Whistleblower Protection, Anonymity & Anti-Retaliation Policy', category: 'Ethics & Safeguarding', version: 'v2.0', effective: '15 Jan 2026', review: '15 Jan 2027', signed: 440, total: 490, status: 'Active', owner: 'Director of Compliance', escrow: false, scope: 'Independent grievance reporting channels with zero retaliation safeguards.' },
    { code: 'POL-PROC-007', title: 'USAID & Institutional Procurement Dual-Authorization Code', category: 'Procurement', version: 'v2.0', effective: '01 Nov 2025', review: '01 Nov 2026', signed: 335, total: 490, status: 'Under Review', owner: 'Procurement Committee', escrow: false, scope: 'Vendor competitive bidding, conflict-of-interest disclosures, and multi-tier approval gates.' },
    { code: 'POL-LEAVE-008', title: 'Workforce Attendance, Remote Operations & Annual Leave Standard', category: 'Human Resources', version: 'v3.1', effective: '01 Feb 2026', review: '01 Feb 2027', signed: 479, total: 490, status: 'Active', owner: 'HR Directorate', escrow: false, scope: 'Biometric sign-in, compassionate leave, and institutional public holiday calendar.' }
];

var CURRENT_FILTER_STATUS = 'all';

function updatePolicyStats() {
    var total = POLICIES_DATA.length;
    var active = POLICIES_DATA.filter(function(p){ return p.status === 'Active'; }).length;
    var review = POLICIES_DATA.filter(function(p){ return p.status === 'Under Review'; }).length;
    
    var totalSigned = 0;
    var totalStaff = 0;
    POLICIES_DATA.forEach(function(p){
        totalSigned += p.signed;
        totalStaff += p.total;
    });
    var avgPct = totalStaff > 0 ? (totalSigned / totalStaff * 100).toFixed(1) + '%' : '0%';

    var elTotal = document.getElementById('statTotalPolicies');
    var elActive = document.getElementById('statActivePolicies');
    var elReview = document.getElementById('statReviewPolicies');
    var elAvg = document.getElementById('statAvgSignOff');

    if (elTotal) elTotal.textContent = total;
    if (elActive) elActive.textContent = active;
    if (elReview) elReview.textContent = review;
    if (elAvg) elAvg.textContent = avgPct;
}

function renderPoliciesTable(items) {
    updatePolicyStats();
    var tbody = document.getElementById('policiesTableBody');
    if (!tbody) return;

    var list = items || POLICIES_DATA;
    var countEl = document.getElementById('policiesCountDisplay');
    if (countEl) countEl.textContent = list.length;

    if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 24px; color: var(--text-muted);">No policies found matching filter criteria.</td></tr>';
        return;
    }

    tbody.innerHTML = list.map(function(p) {
        var pct = Math.round(p.signed / p.total * 100);
        var pctColor = pct >= 90 ? 'var(--success)' : pct >= 75 ? 'var(--warning)' : 'var(--danger)';
        var statPill = p.status === 'Active' ? '<span class="pill pill-closed">Active</span>' :
                       p.status === 'Under Review' ? '<span class="pill pill-progress">Under Review</span>' :
                       '<span class="pill pill-open">Archived</span>';

        var escrowBadge = p.escrow ? '<div style="font-size: 9px; background: #fee2e2; color: #dc2626; padding: 1px 5px; border-radius: 4px; display: inline-block; font-weight: 700; margin-top: 2px;"><i class="fa-solid fa-lock me-1"></i>ESCROW GATE</div>' : '';

        return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; white-space: nowrap;">' +
                '<strong style="color: var(--accent); font-family: monospace; font-size: 12px;">' + p.code + '</strong>' +
                escrowBadge +
            '</td>' +
            '<td style="padding: 10px 8px; font-weight: 600; line-height: 1.35; font-size: 12px;">' +
                p.title +
                '<div style="font-size: 10px; color: var(--text-muted); font-weight: 400; margin-top: 2px;">Owner: ' + p.owner + '</div>' +
            '</td>' +
            '<td style="padding: 10px 8px; font-size: 11px; white-space: nowrap;">' + p.category + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; font-weight: 700; font-size: 11px;">' + p.version + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); white-space: nowrap;">' + p.effective + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted); white-space: nowrap;">' + p.review + '</td>' +
            '<td style="padding: 10px 8px; text-align: center;">' +
                '<div style="font-weight: 700; color: ' + pctColor + '; font-size: 11px;">' + pct + '% (' + p.signed + '/' + p.total + ')</div>' +
                '<div style="height: 4px; width: 60px; background: #e2e8f0; border-radius: 2px; margin: 3px auto 0; overflow: hidden;">' +
                    '<div style="height: 100%; width: ' + pct + '%; background: ' + pctColor + ';"></div>' +
                '</div>' +
            '</td>' +
            '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + statPill + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' +
                '<div style="display: flex; gap: 4px; justify-content: center;">' +
                    '<button class="btn btn-outline btn-sm" onclick="viewPolicyDetails(\'' + p.code + '\')" title="Read & View Dossier" style="padding: 3px 6px; font-size: 10px;"><i class="fa-solid fa-eye"></i></button>' +
                    '<button class="btn btn-outline btn-sm" onclick="openEditPolicyModal(\'' + p.code + '\')" title="Edit Metadata & Version Bump" style="padding: 3px 6px; font-size: 10px; color: var(--accent);"><i class="fa-solid fa-pen-to-square"></i></button>' +
                    '<button class="btn btn-outline btn-sm" onclick="reSendPolicyAck(\'' + p.code + '\')" title="Push Re-acknowledgement" style="padding: 3px 6px; font-size: 10px; color: #d97706;"><i class="fa-solid fa-paper-plane"></i></button>' +
                    '<button class="btn btn-outline btn-sm" onclick="togglePolicyStatus(\'' + p.code + '\')" title="Toggle Status / Archive" style="padding: 3px 6px; font-size: 10px;"><i class="fa-solid fa-sliders"></i></button>' +
                '</div>' +
            '</td>' +
        '</tr>';
    }).join('');
}

function filterPolicyStatus(status, btn) {
    CURRENT_FILTER_STATUS = status;
    document.querySelectorAll('.policy-tab').forEach(function(b){
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline');
    });
    if (btn) {
        btn.classList.add('active', 'btn-primary');
        btn.classList.remove('btn-outline');
    }
    searchPolicies();
}

function searchPolicies() {
    var q = ((document.getElementById('policySearchInput') || {}).value || '').toLowerCase();
    var cat = ((document.getElementById('policyCategoryFilter') || {}).value || '').toLowerCase();

    var filtered = POLICIES_DATA.filter(function(p) {
        var matchStatus = CURRENT_FILTER_STATUS === 'all' || p.status === CURRENT_FILTER_STATUS;
        var matchCat = !cat || p.category.toLowerCase().includes(cat);
        var matchQ = !q || p.code.toLowerCase().includes(q) || p.title.toLowerCase().includes(q) || p.owner.toLowerCase().includes(q);
        return matchStatus && matchCat && matchQ;
    });

    renderPoliciesTable(filtered);
}

function viewPolicyDetails(code) {
    var p = POLICIES_DATA.find(function(x){ return x.code === code; });
    if (!p) return;

    var titleEl = document.getElementById('viewPolicyModalTitle');
    var badgeEl = document.getElementById('viewPolicyStatusBadge');
    var bodyEl = document.getElementById('viewPolicyModalBody');

    if (titleEl) titleEl.textContent = p.code + ' — ' + p.title;
    if (badgeEl) {
        badgeEl.innerHTML = p.status === 'Active' ? '<span class="pill pill-closed">Active</span>' : '<span class="pill pill-progress">Under Review</span>';
    }

    if (bodyEl) {
        var pct = Math.round(p.signed / p.total * 100);
        bodyEl.innerHTML = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: var(--surface2); padding: 12px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 14px;">' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Category:</strong><div style="font-weight:700; margin-top:2px;">' + p.category + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Current Version:</strong><div style="font-weight:700; margin-top:2px; color:var(--accent);">' + p.version + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Effective Date:</strong><div style="font-weight:700; margin-top:2px;">' + p.effective + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Next Annual Review:</strong><div style="font-weight:700; margin-top:2px;">' + p.review + '</div></div>' +
            '<div style="grid-column: span 2;"><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Lead Policy Owner:</strong><div style="font-weight:700; margin-top:2px;">' + p.owner + '</div></div>' +
        '</div>' +
        '<div style="margin-bottom: 14px;">' +
            '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Scope & Application:</strong>' +
            '<div style="padding: 10px 12px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; line-height: 1.4; font-size: 12px;">' +
                p.scope +
            '</div>' +
        '</div>' +
        '<div style="margin-bottom: 14px;">' +
            '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">' +
                '<strong style="font-size:11px; text-transform:uppercase; color:var(--text-muted);">Staff Digital Acknowledgement Breakdown:</strong>' +
                '<span style="font-size: 11px; font-weight: 700; color: var(--accent);">' + pct + '% (' + p.signed + ' of ' + p.total + ' signed)</span>' +
            '</div>' +
            '<div style="height: 6px; width: 100%; background: #e2e8f0; border-radius: 3px; overflow: hidden;">' +
                '<div style="height: 100%; width: ' + pct + '%; background: var(--accent);"></div>' +
            '</div>' +
            '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; margin-top: 10px; font-size: 11px;">' +
                '<div style="padding: 6px; background: var(--surface2); border-radius: 4px; text-align: center;">Lagos: <strong>92/95</strong></div>' +
                '<div style="padding: 6px; background: var(--surface2); border-radius: 4px; text-align: center;">Kano: <strong>76/82</strong></div>' +
                '<div style="padding: 6px; background: var(--surface2); border-radius: 4px; text-align: center;">Rivers: <strong>71/74</strong></div>' +
                '<div style="padding: 6px; background: var(--surface2); border-radius: 4px; text-align: center;">Abuja: <strong>108/110</strong></div>' +
                '<div style="padding: 6px; background: var(--surface2); border-radius: 4px; text-align: center;">Kaduna: <strong>58/68</strong></div>' +
                '<div style="padding: 6px; background: var(--surface2); border-radius: 4px; text-align: center;">Borno: <strong>33/61</strong></div>' +
            '</div>' +
        '</div>';
    }

    openModal('modalViewPolicy');
}

function openEditPolicyModal(code) {
    var p = POLICIES_DATA.find(function(x){ return x.code === code; });
    if (!p) return;

    document.getElementById('editPolTargetCode').value = p.code;
    document.getElementById('editPolicyModalTitle').textContent = 'Edit Policy — ' + p.code;
    document.getElementById('editPolTitle').value = p.title;
    document.getElementById('editPolVersion').value = p.version;
    document.getElementById('editPolStatus').value = p.status;
    document.getElementById('editPolEffective').value = '2026-01-01';
    document.getElementById('editPolReview').value = '2027-01-01';

    openModal('modalEditPolicy');
}

function handleEditPolicySubmit(e) {
    e.preventDefault();
    var code = document.getElementById('editPolTargetCode').value;
    var p = POLICIES_DATA.find(function(x){ return x.code === code; });
    if (p) {
        p.title = document.getElementById('editPolTitle').value;
        p.version = document.getElementById('editPolVersion').value;
        p.status = document.getElementById('editPolStatus').value;
        alert('Policy ' + code + ' updated successfully to version ' + p.version + ' (' + p.status + ').');
        closeModal('modalEditPolicy');
        searchPolicies();
    }
}

function handleUploadPolicySubmit(e) {
    e.preventDefault();
    var newCode = document.getElementById('uploadPolCode').value;
    var newTitle = document.getElementById('uploadPolTitle').value;
    var newCategory = document.getElementById('uploadPolCategory').value;
    var newVer = document.getElementById('uploadPolVersion').value || 'v1.0';
    var isMandatory = document.getElementById('uploadPolMandatorySignOff').checked;

    POLICIES_DATA.unshift({
        code: newCode,
        title: newTitle,
        category: newCategory,
        version: newVer,
        effective: '01 Apr 2026',
        review: '01 Apr 2027',
        signed: 0,
        total: 490,
        status: 'Active',
        owner: 'HR Directorate',
        escrow: false,
        scope: 'Uploaded by HR Administration. Mandatory sign-off instituted.'
    });

    alert('New policy ' + newCode + ' successfully uploaded & published!' + (isMandatory ? '\n\nDigital sign-off notification broadcast sent to all 490 personnel.' : ''));
    closeModal('modalUploadPolicy');
    searchPolicies();
}

function reSendPolicyAck(code) {
    alert('Re-acknowledgement push notification dispatched to outstanding staff for policy: ' + code + '.');
}

function togglePolicyStatus(code) {
    var p = POLICIES_DATA.find(function(x){ return x.code === code; });
    if (!p) return;

    var newStat = p.status === 'Active' ? 'Under Review' : p.status === 'Under Review' ? 'Archived' : 'Active';
    p.status = newStat;
    alert('Policy ' + code + ' status changed to: ' + newStat);
    searchPolicies();
}

function broadcastPolicySignOff() {
    alert('Institutional Broadcast Dispatched:\n\nDigital acknowledgement alert sent to 490 staff members across all 6 state offices for outstanding statutory policy sign-offs.');
}

function exportPolicyComplianceReport(format) {
    alert('Compiling CCCRN Institutional Policy Compliance Dossier (' + format.toUpperCase() + ')...\n\nReport generated with full staff digital signature audit trail.');
}

window.initPoliciesModule = function() {
    var role = window.CURRENT_USER_ROLE || 'hr';
    var ind = document.getElementById('policiesRoleIndicator');

    if (ind) {
        if (role === 'hr') {
            ind.innerHTML = '<div style="margin-top: 6px; padding: 7px 14px; background: rgba(124,58,237,0.08); border-left: 4px solid var(--accent2); border-radius: 6px; font-size: 11px; color: var(--accent2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">' +
                '<div style="display: flex; align-items: center; gap: 8px;">' +
                    '<i class="fa-solid fa-file-signature" style="font-size: 13px;"></i>' +
                    '<div><strong>HR Policy Administration & Full Governance:</strong> Complete authorization to upload statutory policies, manage revision cycles, trigger digital sign-offs, and monitor institutional acknowledgement compliance across all 490 personnel.</div>' +
                '</div>' +
                '<span style="font-size: 10px; font-weight: 700; background: #ede9fe; color: #6d28d9; padding: 2px 8px; border-radius: 4px; border: 1px solid #c4b5fd;"><i class="fa-solid fa-lock-open me-1"></i> FULL HR ACCESS</span>' +
            '</div>';
        } else if (role === 'doc') {
            ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: rgba(2,54,123,0.08); color: var(--accent); border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Institutional policy governance, legal alignment & regulatory sign-off.</div>';
        }
    }

    renderPoliciesTable();
};

document.addEventListener('DOMContentLoaded', function(){
    window.initPoliciesModule();
});
</script>
@endsection
