import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export interface ReviewRecord {
  id: string;
  docTitle: string;
  docType: string;
  date: string;
  score: number;
  criticalFlags: number;
  warnings: number;
  status: 'Compliant' | 'Action Required' | 'High Risk';
  narrative: string;
  flags: {
    clause: string;
    issueType: string;
    riskLevel: 'Critical' | 'High' | 'Medium' | 'Low';
    finding: string;
    recommendation: string;
  }[];
}

export const AiReviewModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();

  const isComplianceOfficer = currentUser?.key === 'compliance_officer';

  // Seed Recent Reviews Data
  const [reviewsHistory, setReviewsHistory] = useState<ReviewRecord[]>([
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
  ]);

  // Form State
  const [docType, setDocType] = useState('Sub-Grant Agreement');
  const [docText, setDocText] = useState('');
  const [fileName, setFileName] = useState('');
  const [refDonor, setRefDonor] = useState(true);
  const [refCccrn, setRefCccrn] = useState(true);
  const [refAward, setRefAward] = useState(true);
  const [isAnalyzing, setIsAnalyzing] = useState(false);

  // Active Result State
  const [activeReviewResult, setActiveReviewResult] = useState<ReviewRecord | null>(reviewsHistory[0]);

  // Guard: Strictly Director of Compliance (DoC) Full Access ONLY
  if (!isDocAdmin) {
    return (
      <div className="card" style={{ textAlign: 'center', padding: 40, background: 'var(--danger-light, #fee2e2)', border: '1px solid #fca5a5' }}>
        <i className="fa-solid fa-brain" style={{ fontSize: 32, color: 'var(--danger, #dc2626)', marginBottom: 12 }}></i>
        <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--danger, #dc2626)', marginBottom: 6 }}>
          Restricted Access: AI Compliance Review
        </h3>
        <p style={{ fontSize: 13, color: 'var(--text-dim)' }}>
          Automated document clause audit and donor regulation gap analysis is strictly reserved for the Director of Compliance (DoC).
        </p>
      </div>
    );
  }

  const handleRunReview = (e: React.FormEvent) => {
    e.preventDefault();
    if (!docText.trim() && !fileName) {
      alert('Please upload a document or paste agreement text to analyze.');
      return;
    }

    setIsAnalyzing(true);

    setTimeout(() => {
      const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
      const newId = 'AIR-00' + (reviewsHistory.length + 3);
      const title = fileName || (docText.substring(0, 45) + '...');

      const newRecord: ReviewRecord = {
        id: newId,
        docTitle: title,
        docType: docType,
        date: today,
        score: 76,
        criticalFlags: 1,
        warnings: 1,
        status: 'Action Required',
        narrative: `Automated AI compliance analysis completed for ${docType}. Found 1 critical deviation regarding USAID 2 CFR 200 dual-authorization protocols and 1 medium notice requirement gap.`,
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

      setReviewsHistory([newRecord, ...reviewsHistory]);
      setActiveReviewResult(newRecord);
      setIsAnalyzing(false);
      setDocText('');
      setFileName('');
      alert(`✓ AI Compliance Review completed for ${title}. Score: 76/100 (Action Required).`);
    }, 800);
  };

  const getRiskBadge = (lvl: string) => {
    switch (lvl) {
      case 'Critical': return <span className="pill pill-open" style={{ fontWeight: 800 }}>Critical</span>;
      case 'High': return <span className="pill pill-open">High</span>;
      case 'Medium': return <span className="pill pill-progress">Medium</span>;
      default: return <span className="pill pill-closed">Low</span>;
    }
  };

  const getScoreColor = (score: number) => {
    if (score >= 85) return 'var(--success)';
    if (score >= 65) return 'var(--warning)';
    return 'var(--danger)';
  };

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
              🧠 AI Compliance Review & Clause Diagnostics
            </h2>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
              Upload any document — agreements, grants, contracts — for automated clause-by-clause compliance analysis against donor regulations
            </p>
          </div>

          <div style={{ display: 'flex', gap: 6 }}>
            {isComplianceOfficer && (
              <span style={{ padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
                <i className="fa-solid fa-user-shield"></i> <strong>Compliance Specialist:</strong> Full Automated Audit Authority
              </span>
            )}
            {isDocAdmin && (
              <span style={{ padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
                <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Diagnostics & Governance Control
              </span>
            )}
          </div>
        </div>
      </div>

      {/* GRID 2: Upload & Configuration + Recent Reviews */}
      <div className="grid-2" style={{ marginBottom: 20 }}>
        {/* LEFT: Upload & Configuration */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-file-arrow-up" style={{ color: 'var(--accent)' }}></i> Document Upload & Audit Configuration
            </div>
          </div>

          <form onSubmit={handleRunReview} style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
            <div>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Document Classification:</label>
              <select
                value={docType}
                onChange={(e) => setDocType(e.target.value)}
                style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
              >
                <option>Sub-Grant Agreement</option>
                <option>Procurement Contract</option>
                <option>Partnership MOU</option>
                <option>Donor Award Document</option>
                <option>Field Activity Report</option>
                <option>Consultancy Agreement</option>
                <option>Other Operational Document</option>
              </select>
            </div>

            <div>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Reference Regulations for Comparison:</label>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 6, padding: 10, background: 'var(--surface2)', borderRadius: 8, border: '1px solid var(--border)', marginTop: 4 }}>
                <label style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 11, cursor: 'pointer' }}>
                  <input
                    type="checkbox"
                    checked={refDonor}
                    onChange={(e) => setRefDonor(e.target.checked)}
                    style={{ accentColor: 'var(--accent)' }}
                  />
                  <span><strong>Donor Regulations:</strong> 2 CFR 200 / USAID / Global Fund / CDC</span>
                </label>
                <label style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 11, cursor: 'pointer' }}>
                  <input
                    type="checkbox"
                    checked={refCccrn}
                    onChange={(e) => setRefCccrn(e.target.checked)}
                    style={{ accentColor: 'var(--accent)' }}
                  />
                  <span><strong>CCCRN Institutional Policies:</strong> POL-001 through POL-004</span>
                </label>
                <label style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 11, cursor: 'pointer' }}>
                  <input
                    type="checkbox"
                    checked={refAward}
                    onChange={(e) => setRefAward(e.target.checked)}
                    style={{ accentColor: 'var(--accent)' }}
                  />
                  <span><strong>Award Specific Terms:</strong> Milestone Schedules & Budgets</span>
                </label>
              </div>
            </div>

            <div>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Upload Document (PDF, DOCX, DOC):</label>
              <div style={{ border: '2px dashed var(--border)', borderRadius: 8, padding: 16, textAlign: 'center', marginTop: 4, background: 'var(--surface2)' }}>
                <i className="fa-solid fa-cloud-arrow-up" style={{ fontSize: 24, color: 'var(--accent)', marginBottom: 4 }}></i>
                <div style={{ fontSize: 12, fontWeight: 600 }}>Drop file here or click to browse</div>
                <div style={{ fontSize: 10, color: 'var(--text-muted)', marginBottom: 8 }}>PDF, DOCX up to 25MB</div>
                <input
                  type="file"
                  onChange={(e) => {
                    if (e.target.files && e.target.files[0]) {
                      setFileName(e.target.files[0].name);
                    }
                  }}
                  style={{ fontSize: 11 }}
                />
              </div>
            </div>

            <div>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Or Paste Contract / Agreement Text Directly:</label>
              <textarea
                placeholder="Paste clauses or full contract text here for immediate AI compliance analysis..."
                value={docText}
                onChange={(e) => setDocText(e.target.value)}
                style={{ width: '100%', minHeight: 70, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
              />
            </div>

            <button
              type="submit"
              disabled={isAnalyzing}
              className="btn btn-primary"
              style={{ background: 'var(--accent2)', borderColor: 'var(--accent2)', padding: '10px 16px', fontWeight: 700 }}
            >
              {isAnalyzing ? (
                <span><i className="fa-solid fa-spinner fa-spin"></i> Running Neural Diagnostics...</span>
              ) : (
                <span><i className="fa-solid fa-brain"></i> Run AI Compliance Analysis</span>
              )}
            </button>
          </form>
        </div>

        {/* RIGHT: Recent Audit Reviews History */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-clock-rotate-left" style={{ color: 'var(--accent2)' }}></i> Recent AI Document Audits
            </div>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            {reviewsHistory.map((rev) => (
              <div
                key={rev.id}
                onClick={() => setActiveReviewResult(rev)}
                style={{
                  background: activeReviewResult?.id === rev.id ? '#f0f9ff' : 'var(--surface2)',
                  border: activeReviewResult?.id === rev.id ? '2px solid var(--accent)' : '1px solid var(--border)',
                  borderRadius: 8,
                  padding: 12,
                  cursor: 'pointer',
                  transition: 'all 0.15s ease'
                }}
              >
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 4 }}>
                  <div>
                    <span style={{ fontWeight: 700, fontSize: 12, color: 'var(--text)' }}>{rev.id}: {rev.docTitle}</span>
                    <div style={{ fontSize: 10, color: 'var(--text-muted)', marginTop: 2 }}>{rev.docType} &nbsp;·&nbsp; {rev.date}</div>
                  </div>
                  <span style={{
                    fontFamily: 'Plus Jakarta Sans',
                    fontSize: 16,
                    fontWeight: 800,
                    color: getScoreColor(rev.score)
                  }}>
                    {rev.score}%
                  </span>
                </div>

                <div style={{ display: 'flex', gap: 6, marginTop: 6, fontSize: 10 }}>
                  <span style={{ color: rev.criticalFlags > 0 ? 'var(--danger)' : 'var(--success)', fontWeight: 600 }}>
                    <i className="fa-solid fa-triangle-exclamation"></i> {rev.criticalFlags} Critical Flags
                  </span>
                  <span style={{ color: 'var(--warning)', fontWeight: 600 }}>
                    <i className="fa-solid fa-circle-exclamation"></i> {rev.warnings} Warnings
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* ANALYSIS REPORT CONTAINER (matching wireframe) */}
      {activeReviewResult && (
        <div className="card" style={{ borderTop: `4px solid ${getScoreColor(activeReviewResult.score)}` }}>
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-chart-simple" style={{ color: 'var(--accent)' }}></i> Compliance Analysis Report — {activeReviewResult.docTitle}
            </div>
            <div style={{ display: 'flex', gap: 8 }}>
              <button className="btn btn-outline btn-sm" onClick={() => alert(`Exporting AI Compliance Audit Dossier for ${activeReviewResult.id} (PDF)...`)}>
                ⬇️ Export PDF Report
              </button>
            </div>
          </div>

          {/* Score Banner */}
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 10, marginBottom: 16 }}>
            <div style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, textAlign: 'center', border: '1px solid var(--border)' }}>
              <div style={{ fontSize: 10, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Compliance Index</div>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: getScoreColor(activeReviewResult.score) }}>
                {activeReviewResult.score}%
              </div>
              <div style={{ fontSize: 10, color: 'var(--text-dim)' }}>{activeReviewResult.status}</div>
            </div>

            <div style={{ background: '#fee2e2', padding: 12, borderRadius: 8, textAlign: 'center', border: '1px solid #fca5a5' }}>
              <div style={{ fontSize: 10, color: '#991b1b', textTransform: 'uppercase' }}>Critical Gaps</div>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#dc2626' }}>
                {activeReviewResult.criticalFlags}
              </div>
              <div style={{ fontSize: 10, color: '#7f1d1d' }}>High Audit Exposure</div>
            </div>

            <div style={{ background: '#fef3c7', padding: 12, borderRadius: 8, textAlign: 'center', border: '1px solid #fde68a' }}>
              <div style={{ fontSize: 10, color: '#92400e', textTransform: 'uppercase' }}>Warnings</div>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#d97706' }}>
                {activeReviewResult.warnings}
              </div>
              <div style={{ fontSize: 10, color: '#78350f' }}>Revisions Needed</div>
            </div>

            <div style={{ background: '#dcfce7', padding: 12, borderRadius: 8, textAlign: 'center', border: '1px solid #86efac' }}>
              <div style={{ fontSize: 10, color: '#166534', textTransform: 'uppercase' }}>Verified Compliant</div>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#16a34a' }}>
                14 Clauses
              </div>
              <div style={{ fontSize: 10, color: '#14532d' }}>Standard Adherence</div>
            </div>
          </div>

          {/* Flags Table */}
          <div style={{ marginBottom: 16 }}>
            <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 13, fontWeight: 700, marginBottom: 8, color: 'var(--text)' }}>
              🚩 Clause-by-Clause Audit Findings & Risk Breakdown:
            </div>
            <table>
              <thead>
                <tr>
                  <th>Clause / Section</th>
                  <th>Issue Type</th>
                  <th>Risk Tier</th>
                  <th>Specific Finding</th>
                  <th>Recommended Amendment</th>
                </tr>
              </thead>
              <tbody>
                {activeReviewResult.flags.map((flag, idx) => (
                  <tr key={idx}>
                    <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{flag.clause}</td>
                    <td>{flag.issueType}</td>
                    <td>{getRiskBadge(flag.riskLevel)}</td>
                    <td style={{ fontSize: 11, maxWidth: 220 }}>{flag.finding}</td>
                    <td style={{ fontSize: 11, maxWidth: 220, color: '#15803d', fontWeight: 600 }}>{flag.recommendation}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* AI Narrative */}
          <div style={{ background: '#f0f7fd', border: '1px solid var(--border)', borderRadius: 8, padding: 16 }}>
            <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--accent)', textTransform: 'uppercase', marginBottom: 6 }}>
              📝 AI Executive Narrative Summary:
            </div>
            <p style={{ fontSize: 12, lineHeight: 1.6, color: 'var(--text-dim)', margin: 0 }}>
              {activeReviewResult.narrative}
            </p>
          </div>
        </div>
      )}
    </div>
  );
};
