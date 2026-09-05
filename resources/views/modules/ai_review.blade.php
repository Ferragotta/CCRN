@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="aiReviewModuleContainer">
    <!-- HEADER -->
    <div style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0;">
                    🧠 AI Compliance Review &amp; Clause Diagnostics
                </h2>
                <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">
                    Upload any document — agreements, grants, contracts — for automated clause-by-clause compliance analysis against donor regulations
                </p>
            </div>

            <div style="display: flex; gap: 6px;">
                <span style="padding: 5px 12px; background: rgba(124, 58, 237, 0.08); color: #7c3aed; border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; font-weight: 700; border: 1px solid rgba(124, 58, 237, 0.2);">
                    <i class="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Diagnostics &amp; Governance Control
                </span>
            </div>
        </div>
    </div>

    <!-- GRID 2: Upload & Configuration + Recent Reviews -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <!-- LEFT: Upload & Configuration -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-file-arrow-up" style="color: var(--accent);"></i> Document Upload &amp; Audit Configuration
                </div>
            </div>

            <form id="aiReviewForm" onsubmit="handleRunReview(event)" style="display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <label style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Document Classification:</label>
                    <select id="docClassification" style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text);">
                        <option value="Sub-Grant Agreement">Sub-Grant Agreement</option>
                        <option value="Procurement Contract">Procurement Contract</option>
                        <option value="Partnership MOU">Partnership MOU</option>
                        <option value="Donor Award Document">Donor Award Document</option>
                        <option value="Field Activity Report">Field Activity Report</option>
                        <option value="Consultancy Agreement">Consultancy Agreement</option>
                        <option value="Other Operational Document">Other Operational Document</option>
                    </select>
                </div>

                <div>
                    <label style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Reference Regulations for Comparison:</label>
                    <div style="display: flex; flex-direction: column; gap: 8px; padding: 10px; background: var(--surface2); border-radius: 8px; border: 1px solid var(--border);">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 11px; cursor: pointer;">
                            <input type="checkbox" id="refDonor" checked style="accent-color: var(--accent);">
                            <span><strong>Donor Regulations:</strong> 2 CFR 200 / USAID / Global Fund / CDC</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 11px; cursor: pointer;">
                            <input type="checkbox" id="refCccrn" checked style="accent-color: var(--accent);">
                            <span><strong>CCCRN Institutional Policies:</strong> POL-001 through POL-004</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 11px; cursor: pointer;">
                            <input type="checkbox" id="refAward" checked style="accent-color: var(--accent);">
                            <span><strong>Award Specific Terms:</strong> Milestone Schedules &amp; Budgets</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Upload Document (PDF, DOCX, DOC):</label>
                    <div style="border: 2px dashed var(--border); border-radius: 8px; padding: 16px; text-align: center; background: var(--surface2);">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 24px; color: var(--accent); margin-bottom: 4px; display: block;"></i>
                        <div style="font-size: 12px; font-weight: 600;" id="fileNameDisplay">Drop file here or click to browse</div>
                        <div style="font-size: 10px; color: var(--text-muted); margin-bottom: 8px;">PDF, DOCX up to 25MB</div>
                        <input type="file" id="fileUploadInput" onchange="handleFileSelected(event)" style="font-size: 11px;">
                    </div>
                </div>

                <div>
                    <label style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Or Paste Contract / Agreement Text Directly:</label>
                    <textarea id="contractTextInput" placeholder="Paste clauses or full contract text here for immediate AI compliance analysis..." style="width: 100%; min-height: 70px; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text); box-sizing: border-box;"></textarea>
                </div>

                <button type="submit" id="btnRunAudit" class="btn btn-primary" style="background: #7c3aed; border-color: #7c3aed; padding: 10px 16px; font-weight: 700; width: 100%;">
                    <i class="fa-solid fa-brain"></i> Run AI Compliance Analysis
                </button>
            </form>
        </div>

        <!-- RIGHT: Recent Audit Reviews History -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #7c3aed;"></i> Recent AI Document Audits
                </div>
            </div>

            <div id="reviewsHistoryList" style="display: flex; flex-direction: column; gap: 10px;">
                <!-- Populated dynamically by reviewsHistory -->
            </div>
        </div>
    </div>

    <!-- ANALYSIS REPORT CONTAINER -->
    <div id="analysisReportContainer" class="card" style="border-top: 4px solid var(--warning);">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div class="card-title" id="reportHeaderTitle">
                <i class="fa-solid fa-chart-simple" style="color: var(--accent);"></i> Compliance Analysis Report — <span id="reportDocTitle">Sub-Grant Agreement — Kano Community Health Initiative</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn btn-outline btn-sm" onclick="exportPdfReport()">
                    ⬇️ Export PDF Report
                </button>
            </div>
        </div>

        <!-- Score Banner -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 16px;">
            <div style="background: var(--surface2); padding: 12px; border-radius: 8px; text-align: center; border: 1px solid var(--border);">
                <div style="font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Compliance Index</div>
                <div id="metricScore" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--warning);">
                    68%
                </div>
                <div id="metricStatus" style="font-size: 10px; color: var(--text-dim);">Action Required</div>
            </div>

            <div style="background: #fee2e2; padding: 12px; border-radius: 8px; text-align: center; border: 1px solid #fca5a5;">
                <div style="font-size: 10px; color: #991b1b; text-transform: uppercase;">Critical Gaps</div>
                <div id="metricCritical" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #dc2626;">
                    1
                </div>
                <div style="font-size: 10px; color: #7f1d1d;">High Audit Exposure</div>
            </div>

            <div style="background: #fef3c7; padding: 12px; border-radius: 8px; text-align: center; border: 1px solid #fde68a;">
                <div style="font-size: 10px; color: #92400e; text-transform: uppercase;">Warnings</div>
                <div id="metricWarnings" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706;">
                    2
                </div>
                <div style="font-size: 10px; color: #78350f;">Revisions Needed</div>
            </div>

            <div style="background: #dcfce7; padding: 12px; border-radius: 8px; text-align: center; border: 1px solid #86efac;">
                <div style="font-size: 10px; color: #166534; text-transform: uppercase;">Verified Compliant</div>
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #16a34a;">
                    14 Clauses
                </div>
                <div style="font-size: 10px; color: #14532d;">Standard Adherence</div>
            </div>
        </div>

        <!-- Flags Table -->
        <div style="margin-bottom: 16px;">
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--text);">
                🚩 Clause-by-Clause Audit Findings &amp; Risk Breakdown:
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 22%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Clause / Section</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Issue Type</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Risk Tier</th>
                            <th style="width: 26%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Specific Finding</th>
                            <th style="width: 22%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Recommended Amendment</th>
                        </tr>
                    </thead>
                    <tbody id="reportFlagsTableBody">
                        <!-- Populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AI Narrative -->
        <div style="background: #f0f7fd; border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--accent); text-transform: uppercase; margin-bottom: 6px;">
                📝 AI Executive Narrative Summary:
            </div>
            <p id="reportNarrative" style="font-size: 12px; line-height: 1.6; color: var(--text-dim); margin: 0;">
                The agreement largely complies with grant milestones, but lacks mandatory 2 CFR 200 secondary signatory clause for emergency field disbursements exceeding NGN 500,000.
            </p>
        </div>
    </div>
</div>

<script>
    // Exact seed dataset matching React AiReviewModule
    let reviewsHistory = [
        {
            id: 'AIR-004',
            docTitle: 'Sub-Grant Agreement — Kano Community Health Initiative',
            docType: 'Sub-Grant Agreement',
            date: '28 Feb 2026',
            score: 68,
            criticalFlags: 1,
            warnings: 2,
            status: 'Action Required',
            narrative: 'The agreement largely complies with grant milestones, but lacks mandatory 2 CFR 200 secondary signatory clause for emergency field disbursements exceeding NGN 500,000.',
            flags: [
                {
                    clause: 'Section 4.2 (Financial Controls)',
                    issueType: 'Dual-Signatory Deficit',
                    riskLevel: 'Critical',
                    finding: 'Single signatory approval permitted for facility emergency purchases.',
                    recommendation: 'Amend clause to require mandatory Compliance Specialist counter-authorization.'
                },
                {
                    clause: 'Section 8.1 (Audit Rights)',
                    issueType: 'Donor Access Restriction',
                    riskLevel: 'High',
                    finding: '14-day advance notice required before donor audit inspections.',
                    recommendation: 'Reduce advance notice to 48 hours per USAID standard provisions.'
                },
                {
                    clause: 'Section 12.0 (Asset Custody)',
                    issueType: 'Asset Disposal Ambiguity',
                    riskLevel: 'Medium',
                    finding: 'Disposition of capital clinical equipment after COP expiration is unaddressed.',
                    recommendation: 'Include standard CCCRN asset handover clause.'
                }
            ]
        },
        {
            id: 'AIR-003',
            docTitle: 'Supply Chain Agreement — Medical Reagents & Consumables',
            docType: 'Procurement Contract',
            date: '24 Feb 2026',
            score: 92,
            criticalFlags: 0,
            warnings: 1,
            status: 'Compliant',
            narrative: 'Excellent alignment with federal procurement standards. 3-quote competitive bidding documented and delivery SLA terms are compliant.',
            flags: [
                {
                    clause: 'Section 6.3 (Late Delivery Penalty)',
                    issueType: 'Liquidated Damages',
                    riskLevel: 'Low',
                    finding: 'Late delivery penalty cap at 5% is slightly below the 10% standard.',
                    recommendation: 'Adjust liquidated damages cap to 10% in final contract.'
                }
            ]
        }
    ];

    let activeReviewResult = reviewsHistory[0];
    let selectedUploadedFileName = '';

    function getScoreColor(score) {
        if (score >= 85) return '#16a34a'; // var(--success)
        if (score >= 65) return '#d97706'; // var(--warning)
        return '#dc2626'; // var(--danger)
    }

    function getRiskBadge(lvl) {
        switch (lvl) {
            case 'Critical': return '<span class="pill pill-open" style="font-weight: 800; background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">Critical</span>';
            case 'High': return '<span class="pill pill-open" style="background: #fee2e2; color: #991b1b;">High</span>';
            case 'Medium': return '<span class="pill pill-progress" style="background: #fef3c7; color: #92400e;">Medium</span>';
            default: return '<span class="pill pill-closed" style="background: #dcfce7; color: #166534;">Low</span>';
        }
    }

    function handleFileSelected(e) {
        if (e.target.files && e.target.files[0]) {
            selectedUploadedFileName = e.target.files[0].name;
            document.getElementById('fileNameDisplay').innerText = 'Selected: ' + selectedUploadedFileName;
        }
    }

    function renderHistory() {
        const container = document.getElementById('reviewsHistoryList');
        if (!container) return;

        container.innerHTML = reviewsHistory.map(rev => {
            const isSelected = activeReviewResult && activeReviewResult.id === rev.id;
            const scoreColor = getScoreColor(rev.score);
            return `
                <div onclick="selectReview('${rev.id}')" style="
                    background: ${isSelected ? '#f0f9ff' : 'var(--surface2)'};
                    border: ${isSelected ? '2px solid var(--accent)' : '1px solid var(--border)'};
                    border-radius: 8px;
                    padding: 12px;
                    cursor: pointer;
                    transition: all 0.15s ease;
                ">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                        <div>
                            <span style="font-weight: 700; font-size: 12px; color: var(--text);">${rev.id}: ${rev.docTitle}</span>
                            <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">${rev.docType} &nbsp;&middot;&nbsp; ${rev.date}</div>
                        </div>
                        <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: ${scoreColor};">
                            ${rev.score}%
                        </span>
                    </div>
                    <div style="display: flex; gap: 8px; margin-top: 6px; font-size: 10px;">
                        <span style="color: ${rev.criticalFlags > 0 ? 'var(--danger)' : 'var(--success)'}; font-weight: 600;">
                            <i class="fa-solid fa-triangle-exclamation"></i> ${rev.criticalFlags} Critical Flags
                        </span>
                        <span style="color: var(--warning); font-weight: 600;">
                            <i class="fa-solid fa-circle-exclamation"></i> ${rev.warnings} Warnings
                        </span>
                    </div>
                </div>
            `;
        }).join('');
    }

    function selectReview(id) {
        const found = reviewsHistory.find(r => r.id === id);
        if (found) {
            activeReviewResult = found;
            renderActiveReport();
            renderHistory();
        }
    }

    function renderActiveReport() {
        if (!activeReviewResult) return;

        const reportContainer = document.getElementById('analysisReportContainer');
        const scoreColor = getScoreColor(activeReviewResult.score);
        reportContainer.style.borderTop = `4px solid ${scoreColor}`;

        document.getElementById('reportDocTitle').innerText = activeReviewResult.docTitle;
        const metricScore = document.getElementById('metricScore');
        metricScore.innerText = activeReviewResult.score + '%';
        metricScore.style.color = scoreColor;
        document.getElementById('metricStatus').innerText = activeReviewResult.status;
        document.getElementById('metricCritical').innerText = activeReviewResult.criticalFlags;
        document.getElementById('metricWarnings').innerText = activeReviewResult.warnings;
        document.getElementById('reportNarrative').innerText = activeReviewResult.narrative;

        const tbody = document.getElementById('reportFlagsTableBody');
        tbody.innerHTML = activeReviewResult.flags.map(flag => `
            <tr>
                <td style="font-weight: 700; color: var(--accent);">${flag.clause}</td>
                <td>${flag.issueType}</td>
                <td>${getRiskBadge(flag.riskLevel)}</td>
                <td style="font-size: 11px; max-width: 220px;">${flag.finding}</td>
                <td style="font-size: 11px; max-width: 220px; color: #15803d; font-weight: 600;">${flag.recommendation}</td>
            </tr>
        `).join('');
    }

    function handleRunReview(e) {
        e.preventDefault();
        const docText = document.getElementById('contractTextInput').value.trim();
        const docClassification = document.getElementById('docClassification').value;

        if (!docText && !selectedUploadedFileName) {
            alert('Please upload a document or paste agreement text to analyze.');
            return;
        }

        const btn = document.getElementById('btnRunAudit');
        btn.disabled = true;
        btn.innerHTML = '<span><i class="fa-solid fa-spinner fa-spin"></i> Running Neural Diagnostics...</span>';

        setTimeout(() => {
            const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            const newId = 'AIR-00' + (reviewsHistory.length + 3);
            const title = selectedUploadedFileName || (docText.substring(0, 45) + '...');

            const newRecord = {
                id: newId,
                docTitle: title,
                docType: docClassification,
                date: today,
                score: 76,
                criticalFlags: 1,
                warnings: 1,
                status: 'Action Required',
                narrative: `Automated AI compliance analysis completed for ${docClassification}. Found 1 critical deviation regarding USAID 2 CFR 200 dual-authorization protocols and 1 medium notice requirement gap.`,
                flags: [
                    {
                        clause: 'Procurement & Financial Authorization',
                        issueType: '2 CFR 200 Non-Conformity',
                        riskLevel: 'Critical',
                        finding: 'Clause permits single signatory clearance for expenditures up to NGN 750,000.',
                        recommendation: 'Enforce mandatory Compliance Officer review for all purchases above NGN 200,000.'
                    },
                    {
                        clause: 'Safeguarding & PSEA Undertaking',
                        issueType: 'Missing Mandatory Warranty',
                        riskLevel: 'Medium',
                        finding: 'Document lacks formal PSEA zero-tolerance indemnification clause.',
                        recommendation: 'Insert Standard CCCRN POL-002 Safeguarding Clause Schedule.'
                    }
                ]
            };

            reviewsHistory.unshift(newRecord);
            activeReviewResult = newRecord;

            renderActiveReport();
            renderHistory();

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-brain"></i> Run AI Compliance Analysis';
            document.getElementById('contractTextInput').value = '';
            document.getElementById('fileUploadInput').value = '';
            document.getElementById('fileNameDisplay').innerText = 'Drop file here or click to browse';
            selectedUploadedFileName = '';

            alert(`✓ AI Compliance Review completed for ${title}. Score: 76/100 (Action Required).`);
        }, 800);
    }

    function exportPdfReport() {
        if (activeReviewResult) {
            alert(`Exporting AI Compliance Audit Dossier for ${activeReviewResult.id} (PDF)...`);
        }
    }

    // Initialize display on load
    document.addEventListener('DOMContentLoaded', function() {
        renderHistory();
        renderActiveReport();
    });

    // Also run immediately if in SPA mode
    renderHistory();
    renderActiveReport();
</script>
@endsection
