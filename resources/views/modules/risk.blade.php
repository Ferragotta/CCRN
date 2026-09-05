@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="riskModuleContainer">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0;">
                    ISO 31000 Enterprise Risk Register
                </h2>
                <span class="pill pill-open" style="font-size: 10px;">Executive Governance</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">
                Likelihood vs. Impact 5×5 matrix, statutory controls, fraud vulnerability, and treatment plans
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openModal('modalAddRisk')">
                <i class="fa-solid fa-plus"></i> Log Institutional Risk
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--accent);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Identified Risks</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--accent); line-height: 1.2;">18</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Enterprise & Clusters</div>
        </div>
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--danger);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Critical Severity (Score ≥ 15)</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--danger); line-height: 1.2;">3</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Mandatory Board Escalation</div>
        </div>
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--warning);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">High Severity (Score 10–14)</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--warning); line-height: 1.2;">5</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">In Active Treatment</div>
        </div>
        <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--success);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Mitigated / Residual Low</div>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--success); line-height: 1.2;">10</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Controls Verified</div>
        </div>
    </div>

    <!-- 5x5 Matrix Card -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-table-cells" style="color: var(--accent);"></i> ISO 31000 5×5 Likelihood vs Impact Matrix</div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; text-align: center; font-size: 11px; font-weight: 700;">
            <div style="background: #fef08a; padding: 12px; border-radius: 6px;">L1 · I1<br><span style="font-size:9px; font-weight:normal;">Low (1)</span></div>
            <div style="background: #fef08a; padding: 12px; border-radius: 6px;">L1 · I2<br><span style="font-size:9px; font-weight:normal;">Low (2)</span></div>
            <div style="background: #fed7aa; padding: 12px; border-radius: 6px;">L1 · I3<br><span style="font-size:9px; font-weight:normal;">Mod (3)</span></div>
            <div style="background: #fca5a5; padding: 12px; border-radius: 6px;">L1 · I4<br><span style="font-size:9px; font-weight:normal;">High (4)</span></div>
            <div style="background: #ef4444; color: #fff; padding: 12px; border-radius: 6px;">L1 · I5<br><span style="font-size:9px; font-weight:normal;">RSK-024 (1)</span></div>

            <div style="background: #bbf7d0; padding: 12px; border-radius: 6px;">L2 · I1<br><span style="font-size:9px; font-weight:normal;">Low (2)</span></div>
            <div style="background: #fef08a; padding: 12px; border-radius: 6px;">L2 · I2<br><span style="font-size:9px; font-weight:normal;">Low (4)</span></div>
            <div style="background: #fed7aa; padding: 12px; border-radius: 6px;">L2 · I3<br><span style="font-size:9px; font-weight:normal;">RSK-022 (1)</span></div>
            <div style="background: #fca5a5; padding: 12px; border-radius: 6px;">L2 · I4<br><span style="font-size:9px; font-weight:normal;">High (8)</span></div>
            <div style="background: #ef4444; color: #fff; padding: 12px; border-radius: 6px;">L2 · I5<br><span style="font-size:9px; font-weight:normal;">Crit (10)</span></div>

            <div style="background: #bbf7d0; padding: 12px; border-radius: 6px;">L3 · I1<br><span style="font-size:9px; font-weight:normal;">Low (3)</span></div>
            <div style="background: #bbf7d0; padding: 12px; border-radius: 6px;">L3 · I2<br><span style="font-size:9px; font-weight:normal;">Low (6)</span></div>
            <div style="background: #fef08a; padding: 12px; border-radius: 6px;">L3 · I3<br><span style="font-size:9px; font-weight:normal;">Mod (9)</span></div>
            <div style="background: #fed7aa; padding: 12px; border-radius: 6px;">L3 · I4<br><span style="font-size:9px; font-weight:normal;">RSK-023 (1)</span></div>
            <div style="background: #fca5a5; padding: 12px; border-radius: 6px;">L3 · I5<br><span style="font-size:9px; font-weight:normal;">High (15)</span></div>

            <div style="background: #86efac; padding: 12px; border-radius: 6px;">L4 · I1<br><span style="font-size:9px; font-weight:normal;">Low (4)</span></div>
            <div style="background: #bbf7d0; padding: 12px; border-radius: 6px;">L4 · I2<br><span style="font-size:9px; font-weight:normal;">Low (8)</span></div>
            <div style="background: #fef08a; padding: 12px; border-radius: 6px;">L4 · I3<br><span style="font-size:9px; font-weight:normal;">Mod (12)</span></div>
            <div style="background: #fca5a5; padding: 12px; border-radius: 6px;">L4 · I4<br><span style="font-size:9px; font-weight:normal;">High (16)</span></div>
            <div style="background: #ef4444; color: #fff; padding: 12px; border-radius: 6px;">L4 · I5<br><span style="font-size:9px; font-weight:normal;">Crit (20)</span></div>

            <div style="background: #86efac; padding: 12px; border-radius: 6px;">L5 · I1<br><span style="font-size:9px; font-weight:normal;">Low (5)</span></div>
            <div style="background: #86efac; padding: 12px; border-radius: 6px;">L5 · I2<br><span style="font-size:9px; font-weight:normal;">Low (10)</span></div>
            <div style="background: #bbf7d0; padding: 12px; border-radius: 6px;">L5 · I3<br><span style="font-size:9px; font-weight:normal;">Mod (15)</span></div>
            <div style="background: #fef08a; padding: 12px; border-radius: 6px;">L5 · I4<br><span style="font-size:9px; font-weight:normal;">Mod (20)</span></div>
            <div style="background: #fca5a5; padding: 12px; border-radius: 6px;">L5 · I5<br><span style="font-size:9px; font-weight:normal;">High (25)</span></div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-list-check" style="color: var(--accent);"></i> Enterprise Risk Ledger</div>
        </div>
        <div style="width: 100%; overflow: hidden;">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
                        <th style="width: 9%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Risk Ref</th>
                        <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">State / Office</th>
                        <th style="width: 11%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Category</th>
                        <th style="width: 26%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Risk Description</th>
                        <th style="width: 5%; padding: 10px 4px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">L</th>
                        <th style="width: 5%; padding: 10px 4px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">I</th>
                        <th style="width: 6%; padding: 10px 4px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Score</th>
                        <th style="width: 15%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Treatment</th>
                        <th style="width: 8%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
                        <th style="width: 5%; padding: 10px 4px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong style="color: var(--accent);">RSK-024</strong></td>
                        <td>Kano Cluster</td>
                        <td>Finance / Fraud</td>
                        <td>Altered Vendor Invoices during bulk commodity procurement</td>
                        <td>4</td>
                        <td>5</td>
                        <td><strong style="color: var(--danger);">20 (Crit)</strong></td>
                        <td>Centralize bank reconciliations; enforce automated vendor KYC checks</td>
                        <td><span class="pill pill-open">Open</span></td>
                        <td>
                            <select onchange="alert('Risk status updated.')" style="height: 26px; font-size: 11px; border: 1px solid var(--border); border-radius: 4px;">
                                <option>Set Status...</option>
                                <option>In Treatment</option>
                                <option>Accepted</option>
                                <option>Closed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong style="color: var(--accent);">RSK-023</strong></td>
                        <td>Lagos Cluster</td>
                        <td>PSEA & Ethics</td>
                        <td>Under-reporting of safeguarding concerns by contract staff</td>
                        <td>3</td>
                        <td>4</td>
                        <td><strong style="color: var(--warning);">12 (High)</strong></td>
                        <td>Deploy confidential QR flyers; mandatory quarterly refreshers</td>
                        <td><span class="pill pill-progress">In Treatment</span></td>
                        <td>
                            <select onchange="alert('Risk status updated.')" style="height: 26px; font-size: 11px; border: 1px solid var(--border); border-radius: 4px;">
                                <option>Set Status...</option>
                                <option>In Treatment</option>
                                <option>Accepted</option>
                                <option>Closed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong style="color: var(--accent);">RSK-022</strong></td>
                        <td>Rivers Cluster</td>
                        <td>Procurement</td>
                        <td>Sole-sourcing risks during emergency medical supply runs</td>
                        <td>2</td>
                        <td>3</td>
                        <td><strong style="color: #006CA5;">6 (Mod)</strong></td>
                        <td>Pre-qualify minimum 3 regional suppliers per state</td>
                        <td><span class="pill pill-closed">Mitigated</span></td>
                        <td>
                            <select onchange="alert('Risk status updated.')" style="height: 26px; font-size: 11px; border: 1px solid var(--border); border-radius: 4px;">
                                <option>Set Status...</option>
                                <option>In Treatment</option>
                                <option>Accepted</option>
                                <option>Closed</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modalAddRisk">
    <div class="modal-dialog">
        <div class="modal-header">
            <div class="modal-title">Log New Institutional Risk</div>
            <button class="modal-close" onclick="closeModal('modalAddRisk')">✕</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Risk Category *</label>
                <select style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px;">
                    <option>Financial & Fraud</option>
                    <option>Safeguarding & PSEA</option>
                    <option>Operations & Supply Chain</option>
                    <option>IT & Data Security</option>
                </select>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Likelihood (1–5) *</label>
                    <input type="number" min="1" max="5" value="3" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Impact (1–5) *</label>
                    <input type="number" min="1" max="5" value="4" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px;">
                </div>
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 4px;">Risk Description *</label>
                <textarea rows="3" placeholder="Describe the vulnerability..." style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px;"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modalAddRisk')">Cancel</button>
            <button class="btn btn-primary" onclick="alert('Risk logged to Enterprise Matrix.'); closeModal('modalAddRisk');">Save Risk</button>
        </div>
    </div>
</div>
@endsection
