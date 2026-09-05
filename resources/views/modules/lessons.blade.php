@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="lessonsModuleContainer">

    <!-- SUB-HEADING -->
    <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px;">
                    Lessons Learned & Retrospective Intelligence
                </h2>
                <span class="pill pill-closed" style="font-size: 10px;">Institutional Memory</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px;">
                Root cause analysis, post-investigation retrospectives, and donor-facing preventive policies derived from field incidents.
            </p>
            <div id="lessonsRoleIndicator"></div>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <button class="btn btn-outline btn-sm" onclick="broadcastLearningDigest()" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-bullhorn me-1"></i> Broadcast Learning Digest
            </button>
            <button class="btn btn-outline btn-sm" onclick="exportRetrospectiveDossier('pdf')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-file-pdf me-1"></i> Retrospective Dossier
            </button>
            <button class="btn btn-primary btn-sm" onclick="openModal('modalAddLesson')" style="font-size: 11px; font-weight: 700;">
                <i class="fa-solid fa-plus me-1"></i> Log Lesson Learned
            </button>
        </div>
    </div>

    <!-- 4 STAT TILES -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px;">
        <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #0369a1; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Total Lessons</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0284c7; line-height: 1;" id="statTotalLessons">15</div>
            <div style="font-size: 11px; color: #0369a1; font-weight: 600; margin-top: 4px;">From CAPs & Investigations</div>
        </div>
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #065f46; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Published to Staff</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #059669; line-height: 1;" id="statPublishedLessons">10</div>
            <div style="font-size: 11px; color: #065f46; font-weight: 600; margin-top: 4px;">Active in SOP Refinements</div>
        </div>
        <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; text-align: center;">
            <div style="font-size: 10px; color: #92400e; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Pending Review</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;" id="statReviewLessons">3</div>
            <div style="font-size: 11px; color: #92400e; font-weight: 600; margin-top: 4px;">Draft Retrospectives</div>
        </div>
        <div style="background: #02367B; border: 1px solid #002b66; border-radius: 10px; padding: 14px; text-align: center; color: #fff;">
            <div style="font-size: 10px; color: #bae6fd; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Shared in Donor Dossier</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #55E2E9; line-height: 1;" id="statDonorSharedLessons">7</div>
            <div style="font-size: 11px; color: #bae6fd; font-weight: 600; margin-top: 4px;">USAID & PEPFAR Compliant</div>
        </div>
    </div>

    <!-- FILTER TABS & SEARCH CONTROLS -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; gap: 6px; flex-wrap: wrap;" id="lessonFilterTabs">
            <button class="btn btn-primary btn-sm lesson-tab active" onclick="filterLessonStatus('all', this)" style="font-size: 11px; font-weight: 700;">
                All Lessons (8)
            </button>
            <button class="btn btn-outline btn-sm lesson-tab" onclick="filterLessonStatus('Published', this)" style="font-size: 11px; font-weight: 600;">
                Published (5)
            </button>
            <button class="btn btn-outline btn-sm lesson-tab" onclick="filterLessonStatus('Pending Review', this)" style="font-size: 11px; font-weight: 600;">
                Pending Review (2)
            </button>
            <button class="btn btn-outline btn-sm lesson-tab" onclick="filterLessonStatus('Draft', this)" style="font-size: 11px; font-weight: 600;">
                Drafts (1)
            </button>
        </div>

        <div style="display: flex; gap: 8px; align-items: center;">
            <select id="lessonSourceFilter" onchange="searchLessons()" style="height: 32px; padding: 0 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                <option value="">All Incident Sources</option>
                <option value="CAP">Corrective Action Plans (CAP)</option>
                <option value="CMP">Whistleblower Complaints (CMP)</option>
                <option value="INV">Forensic Investigations (INV)</option>
                <option value="TRV">Travel & Field Logistics (TRV)</option>
                <option value="AUDIT">Periodic Compliance Audits</option>
            </select>
            <input type="text" id="lessonSearchInput" onkeyup="searchLessons()" placeholder="Search ref, title, root cause..." style="height: 32px; padding: 0 10px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; width: 220px; background: var(--surface); color: var(--text);">
        </div>
    </div>

    <!-- LESSONS LEDGER CARD (100% WIDTH, FIXED LAYOUT, ZERO HORIZONTAL SCROLL) -->
    <div class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box; margin-bottom: 0;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2);">
            <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-book-bookmark" style="color: var(--accent);"></i> Root Cause & Remediation Knowledge Ledger
            </div>
            <span style="font-size: 11px; color: var(--text-muted);">Continuous Institutional Improvement · FY2026</span>
        </div>

        <div style="width: 100%; overflow: hidden;">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                        <th style="width: 9%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Ref</th>
                        <th style="width: 9%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Source</th>
                        <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Incident / Trigger</th>
                        <th style="width: 22%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Root Cause Analysis</th>
                        <th style="width: 20%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Preventive Action Mandated</th>
                        <th style="width: 8%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Donor</th>
                        <th style="width: 8%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
                        <th style="width: 6%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Actions</th>
                    </tr>
                </thead>
                <tbody id="lessonsTableBody"></tbody>
            </table>
        </div>

        <div style="padding: 10px 4px 0; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--surface2); margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
            <span>Showing <strong id="lessonsCountDisplay">8</strong> retrospective intelligence cases.</span>
            <span style="color: var(--accent); font-weight: 700;"><i class="fa-solid fa-lock-open me-1"></i> Full Administrative Authoring & Publishing Rights</span>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 1: ADD LESSON LEARNED (HR ALL ACCESS)
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalAddLesson" style="display: none;" onclick="if(event.target===this)closeModal('modalAddLesson')">
    <div class="modal-dialog" style="max-width: 600px; width: 95%;">
        <div class="modal-header">
            <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
                <i class="fa-solid fa-plus-circle text-primary me-2"></i> Log New Lesson Learned & Retrospective
            </span>
            <button class="modal-close" onclick="closeModal('modalAddLesson')">&times;</button>
        </div>
        <form onsubmit="handleAddLessonSubmit(event)">
            <div class="modal-body" style="font-size: 12px; color: var(--text); display: flex; flex-direction: column; gap: 12px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Source Module *</label>
                        <select id="addLessonSourceModule" required style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                            <option value="CAP">Corrective Action Plan (CAP)</option>
                            <option value="CMP">Whistleblower Complaint (CMP)</option>
                            <option value="INV">Forensic Investigation (INV)</option>
                            <option value="TRV">Travel & Field Logistics (TRV)</option>
                            <option value="AUDIT">Periodic Audit Finding</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Source Reference Code *</label>
                        <input type="text" id="addLessonSourceRef" required placeholder="e.g. CAP-032 or CMP-048" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Incident / Trigger Summary *</label>
                    <input type="text" id="addLessonTitle" required placeholder="Brief summary of systemic breakdown or deficiency..." style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Root Cause Analysis (Why did this occur?) *</label>
                    <textarea id="addLessonRootCause" rows="3" required placeholder="Deep-dive analysis into procedural, human, or supervisory factors..." style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Preventive Action Mandated (Remediation Standard) *</label>
                    <textarea id="addLessonAction" rows="3" required placeholder="Mandated policy adjustment, automated control, or supervisory check instituted..." style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Publishing Status *</label>
                        <select id="addLessonStatus" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                            <option value="Published">Publish Immediately</option>
                            <option value="Pending Review">Queue for Review</option>
                            <option value="Draft">Save as Draft</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 18px;">
                        <input type="checkbox" id="addLessonDonorShared" checked style="accent-color: var(--accent);">
                        <label for="addLessonDonorShared" style="font-size: 11px; font-weight: 600; cursor: pointer; color: var(--text);">
                            Include in Donor Compliance Dossier
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalAddLesson')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 700;">Save Lesson Learned</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 2: VIEW / BRIEFING SHEET
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalViewLesson" style="display: none;" onclick="if(event.target===this)closeModal('modalViewLesson')">
    <div class="modal-dialog" style="max-width: 600px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="modal-title" id="viewLessonModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">Retrospective Case</span>
                <span id="viewLessonStatusBadge"></span>
            </div>
            <button class="modal-close" onclick="closeModal('modalViewLesson')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px; color: var(--text);" id="viewLessonModalBody"></div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-lightbulb"></i> CCCRN Institutional Memory</span>
            <button class="btn btn-primary btn-sm" onclick="closeModal('modalViewLesson')">Close</button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 3: EDIT LESSON LEARNED (HR ALL ACCESS)
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalEditLesson" style="display: none;" onclick="if(event.target===this)closeModal('modalEditLesson')">
    <div class="modal-dialog" style="max-width: 580px; width: 95%;">
        <div class="modal-header">
            <span class="modal-title" id="editLessonModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">Edit Lesson Learned</span>
            <button class="modal-close" onclick="closeModal('modalEditLesson')">&times;</button>
        </div>
        <form onsubmit="handleEditLessonSubmit(event)">
            <input type="hidden" id="editLessonTargetRef">
            <div class="modal-body" style="font-size: 12px; color: var(--text); display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Incident / Trigger Summary *</label>
                    <input type="text" id="editLessonTitle" required style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Root Cause Analysis *</label>
                    <textarea id="editLessonRootCause" rows="3" required style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Preventive Action Mandated *</label>
                    <textarea id="editLessonAction" rows="3" required style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Status *</label>
                        <select id="editLessonStatus" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
                            <option value="Published">Published</option>
                            <option value="Pending Review">Pending Review</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 18px;">
                        <input type="checkbox" id="editLessonDonorShared" style="accent-color: var(--accent);">
                        <label for="editLessonDonorShared" style="font-size: 11px; font-weight: 600; cursor: pointer; color: var(--text);">
                            Shared in Donor Dossier
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalEditLesson')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 700;">Update Lesson Record</button>
            </div>
        </form>
    </div>
</div>

<script>
var LESSONS_DATA = [
    { ref: 'LES-015', source: 'CAP-012', title: 'Procurement Document Alteration', rootCause: 'Manual signature verification bottleneck at state cluster level allowed unverified quote injection.', action: 'Enforced dual-signature QR verification for all Purchase Orders above ₦500k.', donorShared: true, status: 'Published', date: '10 Feb 2026', owner: 'Compliance & HR' },
    { ref: 'LES-014', source: 'INV-010', title: 'Delayed Reporting of PSEA Grievance', rootCause: 'Field community outreach volunteers lacked direct anonymous SMS/WhatsApp reporting access.', action: 'Deployed anonymous multilingual QR reporting cards and dedicated toll-free gateway in all 32 health facilities.', donorShared: true, status: 'Published', date: '18 Jan 2026', owner: 'Safeguarding Directorate' },
    { ref: 'LES-013', source: 'TRV-038', title: 'Late Flight Boarding Pass Reconciliations', rootCause: 'Personnel misplaced physical card stubs post field operations before monthly financial closing.', action: 'Mandated digital e-boarding pass screenshot upload within 48h prior to per-diem allowance finalization.', donorShared: false, status: 'Pending Review', date: '02 Mar 2026', owner: 'Operations & Travel' },
    { ref: 'LES-012', source: 'AUDIT-2025', title: 'Cold-Chain Reagent Expiry in Remote Clinics', rootCause: 'Local stock registers were maintained on paper without real-time inventory ledger syncing to HQ.', action: 'Introduced automated monthly FIFO (First-In, First-Out) digital stock balancing across regional clinics.', donorShared: true, status: 'Published', date: '14 Dec 2025', owner: 'Clinical Services' },
    { ref: 'LES-011', source: 'CMP-041', title: 'Overtime Allowance Discrepancies in Surge Drives', rootCause: 'Lack of pre-authorized overtime approval sheets before emergency community testing drives.', action: 'Implemented biometric field roster validation with supervisor sign-off prior to payroll submission.', donorShared: false, status: 'Published', date: '05 Nov 2025', owner: 'Human Resources' },
    { ref: 'LES-010', source: 'CAP-008', title: 'Asset Tracking Gaps on Field Laptops', rootCause: 'Asset movement between LGA sites lacked standardized transfer custody sign-off forms.', action: 'Instituted mandatory digital QR tagging and quarterly physical asset verification audits.', donorShared: true, status: 'Published', date: '22 Oct 2025', owner: 'IT & Logistics' },
    { ref: 'LES-009', source: 'INV-007', title: 'Conflict of Interest in Vendor Selection', rootCause: 'Declaration of interest was executed annually rather than per procurement tender cycle.', action: 'Mandated tender-specific declaration of non-conflict for every procurement evaluation panelist.', donorShared: true, status: 'Pending Review', date: '25 Feb 2026', owner: 'Compliance & Audit' },
    { ref: 'LES-008', source: 'TRV-024', title: 'Unreconciled State Fuel Advances', rootCause: 'State teams lacked centralized fleet management cards, relying on personal cash retirements.', action: 'Transitioned all 6 state offices to institutional fuel cards with automated monthly statement reconciliation.', donorShared: false, status: 'Draft', date: '28 Feb 2026', owner: 'Operations' }
];

var CURRENT_LESSON_FILTER = 'all';

function updateLessonStats() {
    var total = LESSONS_DATA.length;
    var published = LESSONS_DATA.filter(function(l){ return l.status === 'Published'; }).length;
    var review = LESSONS_DATA.filter(function(l){ return l.status === 'Pending Review'; }).length;
    var donor = LESSONS_DATA.filter(function(l){ return l.donorShared; }).length;

    var elTotal = document.getElementById('statTotalLessons');
    var elPub = document.getElementById('statPublishedLessons');
    var elRev = document.getElementById('statReviewLessons');
    var elDon = document.getElementById('statDonorSharedLessons');

    if (elTotal) elTotal.textContent = total;
    if (elPub) elPub.textContent = published;
    if (elRev) elRev.textContent = review;
    if (elDon) elDon.textContent = donor;
}

function renderLessonsTable(items) {
    updateLessonStats();
    var tbody = document.getElementById('lessonsTableBody');
    if (!tbody) return;

    var list = items || LESSONS_DATA;
    var countEl = document.getElementById('lessonsCountDisplay');
    if (countEl) countEl.textContent = list.length;

    if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 24px; color: var(--text-muted);">No lessons learned found matching filter criteria.</td></tr>';
        return;
    }

    tbody.innerHTML = list.map(function(l) {
        var statPill = l.status === 'Published' ? '<span class="pill pill-closed">Published</span>' :
                       l.status === 'Pending Review' ? '<span class="pill pill-progress">Pending Review</span>' :
                       '<span class="pill pill-open">Draft</span>';

        var donorBadge = l.donorShared ? '<span class="pill pill-closed" style="font-size: 10px;">Yes</span>' : '<span class="pill" style="font-size: 10px; background: #e2e8f0; color: #64748b;">No</span>';

        return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
            '<td style="padding: 10px 8px; white-space: nowrap;"><strong style="color: var(--accent); font-family: monospace; font-size: 12px;">' + l.ref + '</strong></td>' +
            '<td style="padding: 10px 8px; font-size: 11px; white-space: nowrap; color: var(--text-muted);">' + l.source + '</td>' +
            '<td style="padding: 10px 8px; font-weight: 700; font-size: 12px; line-height: 1.35;">' + l.title + '</td>' +
            '<td style="padding: 10px 8px; font-size: 11px; line-height: 1.35; color: var(--text-dim);">' + l.rootCause + '</td>' +
            '<td style="padding: 10px 8px; font-size: 11px; line-height: 1.35; color: var(--accent);">' + l.action + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + donorBadge + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + statPill + '</td>' +
            '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' +
                '<div style="display: flex; gap: 4px; justify-content: center;">' +
                    '<button class="btn btn-outline btn-sm" onclick="viewLessonDetails(\'' + l.ref + '\')" title="Briefing Dossier" style="padding: 3px 6px; font-size: 10px;"><i class="fa-solid fa-eye"></i></button>' +
                    '<button class="btn btn-outline btn-sm" onclick="openEditLessonModal(\'' + l.ref + '\')" title="Edit Retrospective" style="padding: 3px 6px; font-size: 10px; color: var(--accent);"><i class="fa-solid fa-pen-to-square"></i></button>' +
                    (l.status !== 'Published' ? '<button class="btn btn-outline btn-sm" onclick="publishLesson(\'' + l.ref + '\')" title="Approve & Publish" style="padding: 3px 6px; font-size: 10px; color: var(--success);"><i class="fa-solid fa-circle-check"></i></button>' : '') +
                    '<button class="btn btn-outline btn-sm" onclick="toggleDonorShare(\'' + l.ref + '\')" title="Toggle Donor Report Inclusion" style="padding: 3px 6px; font-size: 10px; color: #7c3aed;"><i class="fa-solid fa-share-nodes"></i></button>' +
                '</div>' +
            '</td>' +
        '</tr>';
    }).join('');
}

function filterLessonStatus(status, btn) {
    CURRENT_LESSON_FILTER = status;
    document.querySelectorAll('.lesson-tab').forEach(function(b){
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline');
    });
    if (btn) {
        btn.classList.add('active', 'btn-primary');
        btn.classList.remove('btn-outline');
    }
    searchLessons();
}

function searchLessons() {
    var q = ((document.getElementById('lessonSearchInput') || {}).value || '').toLowerCase();
    var src = ((document.getElementById('lessonSourceFilter') || {}).value || '').toLowerCase();

    var filtered = LESSONS_DATA.filter(function(l) {
        var matchStatus = CURRENT_LESSON_FILTER === 'all' || l.status === CURRENT_LESSON_FILTER;
        var matchSrc = !src || l.source.toLowerCase().includes(src);
        var matchQ = !q || l.ref.toLowerCase().includes(q) || l.title.toLowerCase().includes(q) || l.rootCause.toLowerCase().includes(q) || l.action.toLowerCase().includes(q);
        return matchStatus && matchSrc && matchQ;
    });

    renderLessonsTable(filtered);
}

function viewLessonDetails(ref) {
    var l = LESSONS_DATA.find(function(x){ return x.ref === ref; });
    if (!l) return;

    var titleEl = document.getElementById('viewLessonModalTitle');
    var badgeEl = document.getElementById('viewLessonStatusBadge');
    var bodyEl = document.getElementById('viewLessonModalBody');

    if (titleEl) titleEl.textContent = l.ref + ' — ' + l.title;
    if (badgeEl) {
        badgeEl.innerHTML = l.status === 'Published' ? '<span class="pill pill-closed">Published</span>' : '<span class="pill pill-progress">' + l.status + '</span>';
    }

    if (bodyEl) {
        bodyEl.innerHTML = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: var(--surface2); padding: 12px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 14px;">' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Source Incident:</strong><div style="font-weight:700; margin-top:2px; color:var(--accent);">' + l.source + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Documented Date:</strong><div style="font-weight:700; margin-top:2px;">' + l.date + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Governance Lead:</strong><div style="font-weight:700; margin-top:2px;">' + l.owner + '</div></div>' +
            '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Donor Disclosure:</strong><div style="font-weight:700; margin-top:2px;">' + (l.donorShared ? '<span style="color:var(--success);">Shared in Donor Dossier</span>' : 'Internal Use Only') + '</div></div>' +
        '</div>' +
        '<div style="margin-bottom: 14px;">' +
            '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Root Cause Analysis (Underlying Systemic Factor):</strong>' +
            '<div style="padding: 10px 12px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; line-height: 1.4; font-size: 12px;">' +
                l.rootCause +
            '</div>' +
        '</div>' +
        '<div style="margin-bottom: 14px;">' +
            '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Mandated Institutional Remediation Standard:</strong>' +
            '<div style="padding: 10px 12px; background: rgba(2,54,123,0.06); border-left: 3px solid var(--accent); border-radius: 4px; line-height: 1.4; font-size: 12px; color: var(--accent); font-weight:600;">' +
                l.action +
            '</div>' +
        '</div>' +
        '<div style="background: var(--surface2); padding: 8px 12px; border-radius: 4px; font-size: 11px; color: var(--text-muted);">' +
            '<i class="fa-solid fa-circle-check text-success me-1"></i> Recorded in permanent institutional knowledge base to prevent organizational recurrence.' +
        '</div>';
    }

    openModal('modalViewLesson');
}

function openEditLessonModal(ref) {
    var l = LESSONS_DATA.find(function(x){ return x.ref === ref; });
    if (!l) return;

    document.getElementById('editLessonTargetRef').value = l.ref;
    document.getElementById('editLessonModalTitle').textContent = 'Edit Lesson Learned — ' + l.ref;
    document.getElementById('editLessonTitle').value = l.title;
    document.getElementById('editLessonRootCause').value = l.rootCause;
    document.getElementById('editLessonAction').value = l.action;
    document.getElementById('editLessonStatus').value = l.status;
    document.getElementById('editLessonDonorShared').checked = l.donorShared;

    openModal('modalEditLesson');
}

function handleEditLessonSubmit(e) {
    e.preventDefault();
    var ref = document.getElementById('editLessonTargetRef').value;
    var l = LESSONS_DATA.find(function(x){ return x.ref === ref; });
    if (l) {
        l.title = document.getElementById('editLessonTitle').value;
        l.rootCause = document.getElementById('editLessonRootCause').value;
        l.action = document.getElementById('editLessonAction').value;
        l.status = document.getElementById('editLessonStatus').value;
        l.donorShared = document.getElementById('editLessonDonorShared').checked;

        alert('Lesson ' + ref + ' updated successfully (' + l.status + ').');
        closeModal('modalEditLesson');
        searchLessons();
    }
}

function handleAddLessonSubmit(e) {
    e.preventDefault();
    var mod = document.getElementById('addLessonSourceModule').value;
    var srcRef = document.getElementById('addLessonSourceRef').value;
    var title = document.getElementById('addLessonTitle').value;
    var rootCause = document.getElementById('addLessonRootCause').value;
    var action = document.getElementById('addLessonAction').value;
    var status = document.getElementById('addLessonStatus').value;
    var donorShared = document.getElementById('addLessonDonorShared').checked;

    var newRef = 'LES-0' + (16 + LESSONS_DATA.length);

    LESSONS_DATA.unshift({
        ref: newRef,
        source: srcRef || mod,
        title: title,
        rootCause: rootCause,
        action: action,
        donorShared: donorShared,
        status: status,
        date: '03 Mar 2026',
        owner: 'HR Administration'
    });

    alert('New Lesson ' + newRef + ' successfully logged & catalogued into Institutional Memory!');
    closeModal('modalAddLesson');
    searchLessons();
}

function publishLesson(ref) {
    var l = LESSONS_DATA.find(function(x){ return x.ref === ref; });
    if (!l) return;
    l.status = 'Published';
    alert('Lesson ' + ref + ' approved and published to Institutional Memory.');
    searchLessons();
}

function toggleDonorShare(ref) {
    var l = LESSONS_DATA.find(function(x){ return x.ref === ref; });
    if (!l) return;
    l.donorShared = !l.donorShared;
    alert('Lesson ' + ref + ' donor dossier sharing status set to: ' + (l.donorShared ? 'SHARED' : 'INTERNAL ONLY'));
    searchLessons();
}

function broadcastLearningDigest() {
    alert('Institutional Learning Digest Dispatched:\n\nLatest root cause retrospective summaries and preventative operating guidelines dispatched to all 490 personnel.');
}

function exportRetrospectiveDossier(format) {
    alert('Compiling Institutional Lessons Learned & Retrospective Dossier (' + format.toUpperCase() + ')...\n\nReport generated with full corrective action linkages.');
}

window.initLessonsModule = function() {
    var role = window.CURRENT_USER_ROLE || 'hr';
    var ind = document.getElementById('lessonsRoleIndicator');

    if (ind) {
        if (role === 'hr') {
            ind.innerHTML = '<div style="margin-top: 6px; padding: 7px 14px; background: rgba(124,58,237,0.08); border-left: 4px solid var(--accent2); border-radius: 6px; font-size: 11px; color: var(--accent2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">' +
                '<div style="display: flex; align-items: center; gap: 8px;">' +
                    '<i class="fa-solid fa-lightbulb" style="font-size: 13px;"></i>' +
                    '<div><strong>HR Retrospective Administration & Institutional Learning:</strong> Full access to document case retrospectives, formulate preventive controls, publish findings, and approve donor-facing compliance disclosures.</div>' +
                '</div>' +
                '<span style="font-size: 10px; font-weight: 700; background: #ede9fe; color: #6d28d9; padding: 2px 8px; border-radius: 4px; border: 1px solid #c4b5fd;"><i class="fa-solid fa-lock-open me-1"></i> FULL HR ACCESS</span>' +
            '</div>';
        } else if (role === 'doc') {
            ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: rgba(2,54,123,0.08); color: var(--accent); border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Retrospective intelligence sign-off, donor reporting & executive risk retrospectives.</div>';
        }
    }

    renderLessonsTable();
};

document.addEventListener('DOMContentLoaded', function(){
    window.initLessonsModule();
});
</script>
@endsection
