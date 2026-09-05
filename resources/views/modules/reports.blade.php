@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="reportsModuleContainer">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0;">
                    Reports & Donor Intelligence Hub
                </h2>
                <span class="pill pill-closed" style="font-size: 10px;">Audit-Grade Export</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">
                One-click donor compliance dossiers, statutory CDC/USAID reporting bundles, and executive analytics
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openModal('modalGenReport')">
                <i class="fa-solid fa-file-circle-plus"></i> Generate Custom Dossier
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--accent);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Dossiers Generated</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--accent); line-height: 1.2;">24</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">FY2025–2026 Cycle</div>
        </div>
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--warning);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Pending Submissions</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--warning); line-height: 1.2;">3</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Q1 Donor Audit Due</div>
        </div>
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--success);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Donor Approval Score</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--success); line-height: 1.2;">98.2%</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">CDC Compliance Rating</div>
        </div>
        <div class="card" style="margin-bottom: 0; border-left: 4px solid #006CA5;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Average Resolution Time</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #006CA5; line-height: 1.2;">14.2 Days</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Within 21-Day SOP Limit</div>
        </div>
    </div>

    <!-- 2 Column: Report Templates & Recent Submissions -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-file-pdf" style="color: var(--danger);"></i> Standard Compliance Report Templates</div>
            </div>
            <div style="width: 100%; overflow: hidden;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                            <th style="width: 40%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Report Template</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Cycle</th>
                            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Last Compiled</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
                            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Export</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>RPT-Q1-2026: Executive Donor Compliance Dossier</strong>
                                <div style="font-size: 11px; color: var(--text-muted);">Includes Complaints, CAP closures, Travel & Escrow, Risk Matrix</div>
                            </td>
                            <td>Quarterly</td>
                            <td>01 Mar 2026</td>
                            <td><span class="pill pill-closed">Ready</span></td>
                            <td>
                                <div style="display: flex; gap: 4px;">
                                    <button class="btn btn-outline btn-sm" onclick="alert('Downloading PDF...')"><i class="fa-solid fa-file-pdf" style="color: var(--danger);"></i></button>
                                    <button class="btn btn-outline btn-sm" onclick="alert('Downloading Excel...')"><i class="fa-solid fa-file-excel" style="color: var(--success);"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>RPT-PSEA-2026: Safeguarding & PSEA Annual Review</strong>
                                <div style="font-size: 11px; color: var(--text-muted);">Zero-tolerance monitoring, incident log, corrective measures</div>
                            </td>
                            <td>Annual</td>
                            <td>15 Jan 2026</td>
                            <td><span class="pill pill-closed">Ready</span></td>
                            <td>
                                <div style="display: flex; gap: 4px;">
                                    <button class="btn btn-outline btn-sm" onclick="alert('Downloading PDF...')"><i class="fa-solid fa-file-pdf" style="color: var(--danger);"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>RPT-CAP-2026: State Corrective Action Summary</strong>
                                <div style="font-size: 11px; color: var(--text-muted);">Open, overdue, and verified CAPs across all 6 cluster states</div>
                            </td>
                            <td>Monthly</td>
                            <td>Today</td>
                            <td><span class="pill pill-progress">Drafting</span></td>
                            <td>
                                <button class="btn btn-outline btn-sm" onclick="alert('Recompiling monthly CAP ledger...')">Compile</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color: var(--accent);"></i> Transmission Audit Log</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 12px;">
                <div style="padding: 10px; background: var(--surface2); border-radius: 6px;">
                    <div style="font-weight: 700; color: var(--text);">Q4 FY2025 Comprehensive Audit</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Submitted to CDC Mission Compliance Lead</div>
                    <div style="font-size: 10px; color: var(--success); font-weight: 700; margin-top: 4px;">✓ Verified & Acknowledged (14 Jan 2026)</div>
                </div>
                <div style="padding: 10px; background: var(--surface2); border-radius: 6px;">
                    <div style="font-weight: 700; color: var(--text);">Kano Cluster Forensic Brief</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Internal DoC Transmission</div>
                    <div style="font-size: 10px; color: var(--accent); font-weight: 700; margin-top: 4px;">✓ Archived in Vault (28 Feb 2026)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modalGenReport">
    <div class="modal-dialog">
        <div class="modal-header">
            <div class="modal-title">Compile Custom Executive Dossier</div>
            <button class="modal-close" onclick="closeModal('modalGenReport')">✕</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Reporting Period *</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <input type="date" value="2026-01-01" style="width: 100%; height: 34px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px;">
                    <input type="date" value="2026-03-31" style="width: 100%; height: 34px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px;">
                </div>
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 6px;">Include Modules in Dossier:</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 12px;">
                    <label><input type="checkbox" checked> Complaints Register</label>
                    <label><input type="checkbox" checked> CAP Resolutions</label>
                    <label><input type="checkbox" checked> 5x5 Risk Heatmap</label>
                    <label><input type="checkbox" checked> Travel & Escrow Audit</label>
                    <label><input type="checkbox" checked> PDP Performance 150</label>
                    <label><input type="checkbox" checked> Disciplinary Minutes</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modalGenReport')">Cancel</button>
            <button class="btn btn-primary" onclick="alert('Compiling PDF Dossier...'); closeModal('modalGenReport');">Generate PDF</button>
        </div>
    </div>
</div>
@endsection
