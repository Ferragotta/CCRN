import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export const ReportsModule: React.FC = () => {
  const { isDocAdmin } = useAuth();
  const [activeReportType, setActiveReportType] = useState<'management' | 'donor' | 'quarterly' | null>(null);

  // Strict Guard: Reserved exclusively for Director of Compliance (DoC) per user matrix
  if (!isDocAdmin) {
    return (
      <div className="card" style={{ textAlign: 'center', padding: 40, background: 'var(--danger-light, #fee2e2)', border: '1px solid #fca5a5' }}>
        <i className="fa-solid fa-lock" style={{ fontSize: 32, color: 'var(--danger, #dc2626)', marginBottom: 12 }}></i>
        <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--danger, #dc2626)', marginBottom: 6 }}>
          Restricted Access: Reports & Donor Updates
        </h3>
        <p style={{ fontSize: 13, color: 'var(--text-dim)' }}>
          Donor submissions, Board briefings, and Executive Management Reports are restricted exclusively to the Director of Compliance (DoC).
        </p>
      </div>
    );
  }

  const handleGenerate = (type: 'management' | 'donor' | 'quarterly') => {
    setActiveReportType(type);
  };

  const handlePrint = () => {
    window.print();
  };

  const handleDownload = () => {
    alert(`Downloading certified ${activeReportType?.toUpperCase()} Report (PDF Format) with cryptographic digital governance seal...`);
  };

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
              Reports & Donor Updates
            </h2>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
              Generate management summaries, donor compliance reports and board briefings instantly
            </p>
          </div>

          <div style={{ padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Exclusive Executive Reporting Authority
          </div>
        </div>
      </div>

      {/* 3 REPORT GENERATOR TILES matching wireframe */}
      <div className="grid-3" style={{ marginBottom: 20 }}>
        {/* Management Report */}
        <div
          className="card"
          style={{
            borderColor: activeReportType === 'management' ? 'var(--accent)' : 'rgba(0, 119, 182, 0.3)',
            textAlign: 'center',
            padding: 24,
            cursor: 'pointer',
            transition: 'all 0.2s ease',
            background: activeReportType === 'management' ? 'var(--surface2)' : 'var(--surface)'
          }}
          onClick={() => handleGenerate('management')}
        >
          <div style={{ fontSize: 36, marginBottom: 8 }}>📊</div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)', marginBottom: 4 }}>
            Management Report
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginBottom: 16, lineHeight: 1.4 }}>
            Dashboard summary for CCCRN Executive Board & Senior Management meetings
          </div>
          <button
            className="btn btn-primary"
            style={{ width: '100%' }}
            onClick={(e) => { e.stopPropagation(); handleGenerate('management'); }}
          >
            Generate & View Report
          </button>
        </div>

        {/* Donor Report */}
        <div
          className="card"
          style={{
            borderColor: activeReportType === 'donor' ? 'var(--accent2)' : 'rgba(124, 58, 237, 0.3)',
            textAlign: 'center',
            padding: 24,
            cursor: 'pointer',
            transition: 'all 0.2s ease',
            background: activeReportType === 'donor' ? 'var(--surface2)' : 'var(--surface)'
          }}
          onClick={() => handleGenerate('donor')}
        >
          <div style={{ fontSize: 36, marginBottom: 8 }}>🤝</div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)', marginBottom: 4 }}>
            Donor Report
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginBottom: 16, lineHeight: 1.4 }}>
            Compliance update formatted for USAID, CDC & Global Fund submissions
          </div>
          <button
            className="btn btn-primary"
            style={{ width: '100%', background: 'var(--accent2)', borderColor: 'var(--accent2)' }}
            onClick={(e) => { e.stopPropagation(); handleGenerate('donor'); }}
          >
            Generate & View Report
          </button>
        </div>

        {/* Quarterly Review */}
        <div
          className="card"
          style={{
            borderColor: activeReportType === 'quarterly' ? 'var(--success)' : 'rgba(5, 150, 105, 0.3)',
            textAlign: 'center',
            padding: 24,
            cursor: 'pointer',
            transition: 'all 0.2s ease',
            background: activeReportType === 'quarterly' ? 'var(--surface2)' : 'var(--surface)'
          }}
          onClick={() => handleGenerate('quarterly')}
        >
          <div style={{ fontSize: 36, marginBottom: 8 }}>📅</div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)', marginBottom: 4 }}>
            Quarterly Review
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginBottom: 16, lineHeight: 1.4 }}>
            Full quarterly compliance review with all metrics, CAP closures & risk matrices
          </div>
          <button
            className="btn btn-primary"
            style={{ width: '100%', background: 'var(--success)', borderColor: 'var(--success)', color: '#000' }}
            onClick={(e) => { e.stopPropagation(); handleGenerate('quarterly'); }}
          >
            Generate & View Report
          </button>
        </div>
      </div>

      {/* GENERATED REPORT OUTPUT CONTAINER (matching wireframe) */}
      {activeReportType && (
        <div className="card" style={{ marginBottom: 20, borderTop: '4px solid var(--accent)' }}>
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-file-lines" style={{ color: 'var(--accent)' }}></i>{' '}
              {activeReportType === 'management' && '📄 Executive Management Board Compliance Report — Q1 FY2026'}
              {activeReportType === 'donor' && '🤝 USAID / Global Fund Statutory Compliance Submission — FY2026'}
              {activeReportType === 'quarterly' && '📅 Comprehensive Quarterly Compliance Review — COP 2026'}
            </div>
            <div style={{ display: 'flex', gap: 8 }}>
              <button className="btn btn-outline btn-sm" onClick={handlePrint}>
                🖨️ Print
              </button>
              <button className="btn btn-primary btn-sm" onClick={handleDownload}>
                ⬇️ Download PDF
              </button>
              <button
                className="btn btn-outline btn-sm"
                style={{ color: 'var(--danger)', borderColor: 'var(--danger)' }}
                onClick={() => setActiveReportType(null)}
              >
                ✕ Close
              </button>
            </div>
          </div>

          <div style={{ background: '#ffffff', border: '1px solid var(--border)', borderRadius: 8, padding: 24, fontSize: 12, lineHeight: 1.8 }}>
            <div style={{ borderBottom: '2px solid var(--border)', paddingBottom: 12, marginBottom: 16 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                <div>
                  <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, fontWeight: 800, color: 'var(--text)', margin: 0 }}>
                    Center for Clinical Care and Clinical Research (CCCRN)
                  </h3>
                  <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2 }}>
                    Institutional Compliance, Grant Governance & Quality Assurance Directorate
                  </div>
                </div>
                <div style={{ textAlign: 'right', fontSize: 11 }}>
                  <div><strong>Date:</strong> 01 March 2026</div>
                  <div><strong>Prepared By:</strong> Director of Compliance</div>
                  <div style={{ color: 'var(--success)', fontWeight: 700 }}>✓ Verified & Signed</div>
                </div>
              </div>
            </div>

            {/* REPORT BODY */}
            <div style={{ color: 'var(--text)', display: 'flex', flexDirection: 'column', gap: 14 }}>
              <div>
                <strong>1. Executive Summary & Grant Governance:</strong>
                <p style={{ marginTop: 4, color: 'var(--text-dim)', lineHeight: 1.6 }}>
                  During the reporting period, CCCRN maintained strong adherence to federal grant guidelines (2 CFR 200, PEPFAR, and Global Fund standards). A total of <strong>47 compliance complaints</strong> were processed through the Whistleblower portal, resulting in <strong>29 full resolutions (62%)</strong> and <strong>18 Corrective Action Plans (58% completed)</strong>.
                </p>
              </div>

              <div>
                <strong>2. State Cluster Performance & Remediation:</strong>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 10, marginTop: 8 }}>
                  <div style={{ background: 'var(--surface2)', padding: 10, borderRadius: 6 }}>
                    <div style={{ fontWeight: 700, color: 'var(--success)' }}>Lagos & Rivers</div>
                    <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>Compliance Rate: 88% — Low Risk</div>
                  </div>
                  <div style={{ background: 'var(--surface2)', padding: 10, borderRadius: 6 }}>
                    <div style={{ fontWeight: 700, color: 'var(--warning)' }}>Kano & Abuja FCT</div>
                    <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>Compliance Rate: 68% — Medium Risk</div>
                  </div>
                  <div style={{ background: 'var(--surface2)', padding: 10, borderRadius: 6 }}>
                    <div style={{ fontWeight: 700, color: 'var(--danger)' }}>Kaduna & Borno</div>
                    <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>Compliance Rate: 52% — Priority Support</div>
                  </div>
                </div>
              </div>

              <div>
                <strong>3. Capacity Building & Mandatory Certifications:</strong>
                <p style={{ marginTop: 4, color: 'var(--text-dim)', lineHeight: 1.6 }}>
                  Institutional training completion currently stands at <strong>64% (312 of 490 active personnel)</strong>. Anti-Fraud recertification achieved 88% compliance, while PSEA and Data Protection modules are scheduled for completion prior to 31 March 2026.
                </p>
              </div>

              <div>
                <strong>4. Key Compliance Recommendations:</strong>
                <ul style={{ marginTop: 4, paddingLeft: 20, color: 'var(--text-dim)' }}>
                  <li>Accelerate secondary signatory mobile verification for Kano & Kaduna community outreach teams.</li>
                  <li>Complete reconciliation for all pending January 2026 advance logs prior to quarterly close.</li>
                  <li>Enforce mandatory boarding pass upload before clearing airline travel invoices.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Q1 2026 COMPLIANCE SNAPSHOT (matching wireframe) */}
      <div className="card">
        <div className="card-header">
          <div className="card-title">
            <i className="fa-solid fa-chart-pie" style={{ color: 'var(--accent)' }}></i> Q1 2026 Compliance Snapshot & Governance Indicators
          </div>
        </div>

        <div className="grid-2">
          <div>
            <div style={{ fontSize: 11, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: 1, marginBottom: 10, fontWeight: 700 }}>
              Key Operational Metrics
            </div>
            <table>
              <tbody>
                <tr><td>Total Complaints Received</td><td style={{ color: 'var(--text)', fontWeight: 700, textAlign: 'right' }}>47</td></tr>
                <tr><td>Complaints Resolved</td><td style={{ color: 'var(--success)', fontWeight: 700, textAlign: 'right' }}>29 (62%)</td></tr>
                <tr><td>Corrective Action Plans</td><td style={{ color: 'var(--text)', fontWeight: 700, textAlign: 'right' }}>31 issued</td></tr>
                <tr><td>CAPs Completed & Closed</td><td style={{ color: 'var(--success)', fontWeight: 700, textAlign: 'right' }}>18 (58%)</td></tr>
                <tr><td>Staff Trained & Certified</td><td style={{ color: 'var(--accent)', fontWeight: 700, textAlign: 'right' }}>312 / 490</td></tr>
                <tr><td>Critical & High Risks</td><td style={{ color: 'var(--danger)', fontWeight: 700, textAlign: 'right' }}>3 active</td></tr>
                <tr><td>Policies Reviewed & Enacted</td><td style={{ color: 'var(--text)', fontWeight: 700, textAlign: 'right' }}>7 of 15</td></tr>
              </tbody>
            </table>
          </div>

          <div>
            <div style={{ fontSize: 11, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: 1, marginBottom: 10, fontWeight: 700 }}>
              Overall Institutional Compliance Index
            </div>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: 160, background: 'var(--surface2)', borderRadius: 10, border: '1px solid var(--border)' }}>
              <div style={{ textAlign: 'center' }}>
                <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 56, fontWeight: 800, color: 'var(--warning)', lineHeight: 1 }}>
                  64%
                </div>
                <div style={{ fontSize: 12, color: 'var(--text-dim)', marginTop: 8, fontWeight: 600 }}>
                  Moderate — Targeted Remediation Required
                </div>
                <div style={{ fontSize: 10, color: 'var(--text-muted)', marginTop: 3 }}>
                  Based on ISO 31000 & 2 CFR 200 Weighted Evaluation
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
