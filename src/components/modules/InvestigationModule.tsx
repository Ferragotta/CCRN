import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export interface InvestigationCase {
  id: string;
  linkedComplaint: string;
  category: string;
  stage: 'Preliminary Assessment' | 'Evidence Collection' | 'Interviews' | 'Report Drafting' | 'Concluded';
  openedDate: string;
  investigator: string;
  status: 'Active' | 'Pending Evidence' | 'Concluded';
  summary: string;
  allegedParty: string;
  state: string;
}

export interface RcaRecord {
  id: string;
  invId: string;
  rootCause: string;
  contributingFactors: string;
  systemic: boolean;
  preventionAction: string;
}

export interface ControlDeviationRecord {
  id: string;
  invId: string;
  controlFailed: string;
  type: string;
  frequency: string;
  severity: 'Critical' | 'High' | 'Medium' | 'Low';
  remediation: string;
}

export interface EvidenceCustodyRecord {
  id: string;
  invId: string;
  description: string;
  collectedBy: string;
  timestamp: string;
  custodyChain: string;
  status: 'Secured' | 'In Lab Analysis' | 'Verified';
  fileName?: string;
}

export const InvestigationModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();

  const isHR = currentUser?.key === 'hr';
  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isStaff = currentUser?.key === 'staff';

  // Active Sub-Tab
  const [activeTab, setActiveTab] = useState<'tracker' | 'rca' | 'control' | 'evidence'>('tracker');

  // Seed Cases Data
  const [cases, setCases] = useState<InvestigationCase[]>([
    {
      id: 'INV-004',
      linkedComplaint: 'CMP-048',
      category: 'Procurement Fraud',
      stage: 'Evidence Collection',
      openedDate: '28 Feb 2026',
      investigator: 'Emeka Nwosu (Lead Compliance Specialist)',
      status: 'Active',
      summary: 'Alleged unauthorized procurement bypass and single signatory payment clearance in Kano cluster.',
      allegedParty: 'Finance Assistant',
      state: 'Kano — Cluster B'
    },
    {
      id: 'INV-003',
      linkedComplaint: 'CMP-047',
      category: 'Safeguarding Breach',
      stage: 'Interviews',
      openedDate: '26 Feb 2026',
      investigator: 'Dr. Biodun Ojo (HOD & External Co-Investigator)',
      status: 'Active',
      summary: 'Investigation into reported breach of beneficiary safeguarding and PSEA standards in facility outreach.',
      allegedParty: 'Field Officer',
      state: 'Lagos — Cluster A'
    },
    {
      id: 'INV-002',
      linkedComplaint: 'CMP-044',
      category: 'Advance Non-Reconciliation',
      stage: 'Report Drafting',
      openedDate: '16 Feb 2026',
      investigator: 'Emeka Nwosu (Lead Compliance Specialist)',
      status: 'Pending Evidence',
      summary: 'Forensic audit into unretired community outreach funds and delayed voucher submission in Kaduna.',
      allegedParty: 'State Coordinator',
      state: 'Kaduna'
    },
    {
      id: 'INV-001',
      linkedComplaint: 'CMP-043',
      category: 'Harassment / PSEA',
      stage: 'Concluded',
      openedDate: '10 Feb 2026',
      investigator: 'Director of Compliance',
      status: 'Concluded',
      summary: 'Full inquiry concluded into anonymous harassment grievance. Disciplinary and administrative actions executed.',
      allegedParty: 'Anonymous Staff',
      state: 'Borno'
    }
  ]);

  // RCA Records State
  const [rcaList, setRcaList] = useState<RcaRecord[]>([
    { id: 'RCA-1', invId: 'INV-004', rootCause: 'Absence of automated token checks during emergency weekend disbursements.', contributingFactors: 'Urgent facility testing demand, delayed cellular connectivity.', systemic: true, preventionAction: 'Enforce mobile app biometric token for all offline transactions.' },
    { id: 'RCA-2', invId: 'INV-003', rootCause: 'Facility volunteer onboarding bypassed central HR verification screening.', contributingFactors: 'High staff turnover in ad-hoc community testing sites.', systemic: true, preventionAction: 'Implement mandatory electronic QR badge issuance before site deployment.' }
  ]);

  // Control Deviations State
  const [controlDeviations, setControlDeviations] = useState<ControlDeviationRecord[]>([
    { id: 'CD-1', invId: 'INV-004', controlFailed: 'Dual-Signatory Petty Cash Limit (POL-003 §4.2)', type: 'Preventive Control Failure', frequency: 'Occasional (2 instances)', severity: 'Critical', remediation: 'Auto-block voucher processing in ERP if secondary signature is missing.' },
    { id: 'CD-2', invId: 'INV-003', controlFailed: 'Mandatory PSEA Certification (POL-002 §2.1)', type: 'Administrative Control Gap', frequency: 'Persistent', severity: 'High', remediation: 'Facility leads prohibited from assigning non-certified personnel.' }
  ]);

  // Evidence Custody State
  const [evidenceLog, setEvidenceLog] = useState<EvidenceCustodyRecord[]>([
    { id: 'EV-301', invId: 'INV-004', description: 'Original physical invoice vouchers and cash disbursement receipts from Kano facility', collectedBy: 'Emeka Nwosu', timestamp: '28 Feb 2026, 14:20', custodyChain: 'Collected on-site -> Placed in Tamper-Proof Bag #882 -> Transferred to HQ Vault', status: 'Secured', fileName: 'Kano_Vouchers_Scan.pdf' },
    { id: 'EV-302', invId: 'INV-003', description: 'Signed witness interview transcript and confidential audio recording with focal person', collectedBy: 'Dr. Biodun Ojo', timestamp: '27 Feb 2026, 11:00', custodyChain: 'Recorded in private hearing room -> Encrypted with AES-256 -> Uploaded to Secure Vault', status: 'Verified', fileName: 'Hearing_Transcript_Witness1.pdf' }
  ]);

  // Modals & UI States
  const [showOpenModal, setShowOpenModal] = useState(false);
  const [showAddEvidenceModal, setShowAddEvidenceModal] = useState(false);
  const [showAddRcaModal, setShowAddRcaModal] = useState(false);
  const [showAddControlModal, setShowAddControlModal] = useState(false);
  const [viewingCaseDossier, setViewingCaseDossier] = useState<InvestigationCase | null>(null);

  // Forms State
  const [newInvComplaint, setNewInvComplaint] = useState('CMP-049');
  const [newInvCategory, setNewInvCategory] = useState('Procurement Fraud');
  const [newInvInvestigator, setNewInvInvestigator] = useState('Emeka Nwosu (Lead Compliance Specialist)');
  const [newInvSummary, setNewInvSummary] = useState('');
  const [newInvState, setNewInvState] = useState('Lagos — Cluster A');

  // New Evidence Form
  const [evInvId, setEvInvId] = useState('INV-004');
  const [evDesc, setEvDesc] = useState('');
  const [evCustody, setEvCustody] = useState('');
  const [evFile, setEvFile] = useState('');

  // New RCA Form
  const [rcaInvId, setRcaInvId] = useState('INV-004');
  const [rcaRootCause, setRcaRootCause] = useState('');
  const [rcaFactors, setRcaFactors] = useState('');
  const [rcaAction, setRcaAction] = useState('');

  // New Control Form
  const [ctrlInvId, setCtrlInvId] = useState('INV-004');
  const [ctrlFailed, setCtrlFailed] = useState('');
  const [ctrlType] = useState('Preventive Control Failure');
  const [ctrlSeverity, setCtrlSeverity] = useState<'Critical' | 'High' | 'Medium' | 'Low'>('High');
  const [ctrlRemediation, setCtrlRemediation] = useState('');

  // Guard: Staff has No Access
  if (isStaff) {
    return (
      <div className="card" style={{ textAlign: 'center', padding: 40, background: 'var(--danger-light, #fee2e2)', border: '1px solid #fca5a5' }}>
        <i className="fa-solid fa-lock" style={{ fontSize: 32, color: 'var(--danger, #dc2626)', marginBottom: 12 }}></i>
        <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--danger, #dc2626)', marginBottom: 6 }}>
          Restricted Access: Investigation Hub
        </h3>
        <p style={{ fontSize: 13, color: 'var(--text-dim)' }}>
          Confidential investigation dossiers are restricted to Authorized Compliance Investigators, HR, and Director of Compliance.
        </p>
      </div>
    );
  }

  const handleOpenInvestigation = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newInvSummary.trim()) return;

    const newId = 'INV-00' + (cases.length + 1);
    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

    const newCase: InvestigationCase = {
      id: newId,
      linkedComplaint: newInvComplaint,
      category: newInvCategory,
      stage: 'Preliminary Assessment',
      openedDate: today,
      investigator: newInvInvestigator,
      status: 'Active',
      summary: newInvSummary,
      allegedParty: 'Subject Under Inquiry',
      state: newInvState
    };

    setCases([newCase, ...cases]);
    setShowOpenModal(false);
    setNewInvSummary('');
    alert(`Confidential Investigation Case ${newId} officially opened. Investigator assigned: ${newInvInvestigator}.`);
  };

  const handleCloseInvestigation = (caseId: string) => {
    if (!isDocAdmin) {
      alert('Action Restricted: Only the Director of Compliance has authority to officially close investigation cases and issue final determination.');
      return;
    }
    setCases(cases.map(c => c.id === caseId ? { ...c, status: 'Concluded', stage: 'Concluded' } : c));
    alert(`Case ${caseId} concluded. Final compliance determination and corrective sanctions archived.`);
  };

  const handleSaveEvidence = (e: React.FormEvent) => {
    e.preventDefault();
    if (!evDesc.trim()) return;

    const time = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const newEv: EvidenceCustodyRecord = {
      id: 'EV-' + (303 + evidenceLog.length),
      invId: evInvId,
      description: evDesc,
      collectedBy: currentUser?.name || 'Investigator',
      timestamp: time,
      custodyChain: evCustody || 'Collected and logged into secure vault',
      status: 'Secured',
      fileName: evFile || 'Evidence_Exhibit.pdf'
    };

    setEvidenceLog([newEv, ...evidenceLog]);
    setShowAddEvidenceModal(false);
    setEvDesc('');
    setEvCustody('');
    setEvFile('');
    alert(`Evidence exhibit ${newEv.id} logged with cryptographic timestamp.`);
  };

  const handleSaveRca = (e: React.FormEvent) => {
    e.preventDefault();
    if (!rcaRootCause.trim()) return;

    const newRca: RcaRecord = {
      id: 'RCA-' + (rcaList.length + 1),
      invId: rcaInvId,
      rootCause: rcaRootCause,
      contributingFactors: rcaFactors,
      systemic: true,
      preventionAction: rcaAction
    };

    setRcaList([...rcaList, newRca]);
    setShowAddRcaModal(false);
    setRcaRootCause('');
    setRcaFactors('');
    setRcaAction('');
    alert(`Root cause analysis for ${rcaInvId} updated.`);
  };

  const handleSaveControl = (e: React.FormEvent) => {
    e.preventDefault();
    if (!ctrlFailed.trim()) return;

    const newCtrl: ControlDeviationRecord = {
      id: 'CD-' + (controlDeviations.length + 1),
      invId: ctrlInvId,
      controlFailed: ctrlFailed,
      type: ctrlType,
      frequency: 'Documented Case',
      severity: ctrlSeverity,
      remediation: ctrlRemediation
    };

    setControlDeviations([...controlDeviations, newCtrl]);
    setShowAddControlModal(false);
    setCtrlFailed('');
    setCtrlRemediation('');
    alert(`Control deviation logged for ${ctrlInvId}.`);
  };

  const getStagePill = (stage: string) => {
    switch (stage) {
      case 'Concluded': return <span className="pill pill-closed">Concluded</span>;
      case 'Report Drafting': return <span className="pill pill-progress">Report Drafting</span>;
      case 'Interviews': return <span className="pill" style={{ background: '#ede9fe', color: '#7c3aed' }}>Interviews</span>;
      case 'Evidence Collection': return <span className="pill pill-open">Evidence Collection</span>;
      default: return <span className="pill pill-progress">{stage}</span>;
    }
  };

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
              🔐 Compliance Investigation Dashboard & Case Hub
            </h2>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
              Confidential investigations linked to grievances · Evidence chain of custody, RCA, and control deviation logs
            </p>
          </div>

          {/* Open Investigation: DoC Only */}
          {isDocAdmin && (
            <button className="btn btn-primary" onClick={() => setShowOpenModal(true)}>
              <i className="fa-solid fa-plus"></i> Open Investigation
            </button>
          )}
        </div>

        {/* ROLE MATRIX SCOPE BADGES */}
        {isHR && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-eye"></i> <strong>HR Access:</strong> View-only access to active and concluded confidential investigation dossiers.
          </div>
        )}
        {isComplianceOfficer && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-shield"></i> <strong>Compliance Specialist:</strong> Case Investigator Access · Input findings across all sections and attach evidence exhibits.
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Case Governance · Open Investigations, Assign Investigators, Close Cases, and Export Reports.
          </div>
        )}
      </div>

      {/* 4 STAT CARDS matching wireframe */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 12, marginBottom: 16 }}>
        <div className="stat-card blue">
          <div className="stat-label">Total Investigations</div>
          <div className="stat-value">{cases.length}</div>
        </div>
        <div className="stat-card red">
          <div className="stat-label">Active Cases</div>
          <div className="stat-value">{cases.filter(c => c.status === 'Active').length}</div>
        </div>
        <div className="stat-card purple">
          <div className="stat-label">Pending Evidence</div>
          <div className="stat-value">{cases.filter(c => c.status === 'Pending Evidence').length}</div>
        </div>
        <div className="stat-card green">
          <div className="stat-label">Concluded</div>
          <div className="stat-value">{cases.filter(c => c.status === 'Concluded').length}</div>
        </div>
      </div>

      {/* 4 SUB-TABS matching wireframe */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 16 }}>
        <button
          onClick={() => setActiveTab('tracker')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'tracker' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'tracker' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          🔍 Investigation Tracker
        </button>
        <button
          onClick={() => setActiveTab('rca')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'rca' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'rca' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          🧩 Root Cause Analysis ({rcaList.length})
        </button>
        <button
          onClick={() => setActiveTab('control')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'control' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'control' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          ⚙️ Control Deviation Log ({controlDeviations.length})
        </button>
        <button
          onClick={() => setActiveTab('evidence')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'evidence' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'evidence' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          📎 Evidence Log ({evidenceLog.length})
        </button>
      </div>

      {/* 1. INVESTIGATION TRACKER VIEW */}
      {activeTab === 'tracker' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-folder-open" style={{ color: 'var(--accent)' }}></i> Active & Concluded Case Register
            </div>
            {isDocAdmin && (
              <button
                className="btn btn-outline btn-sm"
                onClick={() => alert('Generating Comprehensive Master Investigation Audit Report across all cases...')}
              >
                <i className="fa-solid fa-file-pdf"></i> Generate Master Report
              </button>
            )}
          </div>

          <div style={{ background: '#fef3c7', border: '1px solid #fde68a', borderRadius: 8, padding: '8px 12px', marginBottom: 12, fontSize: 11, color: '#92400e', display: 'flex', alignItems: 'center', gap: 8 }}>
            <i className="fa-solid fa-user-lock"></i>
            <span><strong>Confidentiality Notice:</strong> Investigator assignments and case details are strictly restricted to Compliance and HR oversight. All actions are audit-logged.</span>
          </div>

          <table>
            <thead>
              <tr>
                <th>Inv. ID</th>
                <th>Linked Complaint</th>
                <th>Category</th>
                <th>Stage</th>
                <th>Opened</th>
                <th>Investigator</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {cases.map((c) => (
                <tr key={c.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{c.id}</td>
                  <td><span className="pill pill-progress" style={{ fontSize: 10 }}>{c.linkedComplaint}</span></td>
                  <td style={{ fontWeight: 600 }}>{c.category}</td>
                  <td>{getStagePill(c.stage)}</td>
                  <td style={{ fontSize: 11 }}>{c.openedDate}</td>
                  <td style={{ fontSize: 11 }}>{c.investigator}</td>
                  <td>
                    <span className={`pill ${c.status === 'Concluded' ? 'pill-closed' : 'pill-open'}`}>
                      {c.status}
                    </span>
                  </td>
                  <td>
                    <div style={{ display: 'flex', gap: 4, alignItems: 'center' }}>
                      <button
                        className="btn btn-outline btn-sm"
                        style={{ padding: '2px 6px', fontSize: 10 }}
                        onClick={() => setViewingCaseDossier(c)}
                        title="View Case Dossier"
                      >
                        👁️ Dossier
                      </button>

                      {/* Close Investigation: DoC Only */}
                      {isDocAdmin && c.status !== 'Concluded' && (
                        <button
                          className="btn btn-primary btn-sm"
                          style={{ background: 'var(--success)', borderColor: 'var(--success)', padding: '2px 6px', fontSize: 10 }}
                          onClick={() => handleCloseInvestigation(c.id)}
                          title="Close Investigation & Issue Sanctions"
                        >
                          ✓ Close Case
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* 2. ROOT CAUSE ANALYSIS VIEW */}
      {activeTab === 'rca' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-puzzle-piece" style={{ color: 'var(--accent2)' }}></i> Root Cause Analysis Register
            </div>
            {(isComplianceOfficer || isDocAdmin) && (
              <button className="btn btn-primary btn-sm" onClick={() => setShowAddRcaModal(true)}>
                <i className="fa-solid fa-plus"></i> Add RCA Entry
              </button>
            )}
          </div>

          <table>
            <thead>
              <tr>
                <th>Inv. ID</th>
                <th>Root Cause Finding</th>
                <th>Contributing Factors</th>
                <th>Systemic?</th>
                <th>Prevention Action</th>
              </tr>
            </thead>
            <tbody>
              {rcaList.map((rca) => (
                <tr key={rca.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{rca.invId}</td>
                  <td style={{ fontSize: 11, maxWidth: 220 }}>{rca.rootCause}</td>
                  <td style={{ fontSize: 11, maxWidth: 200, color: 'var(--text-muted)' }}>{rca.contributingFactors}</td>
                  <td>
                    <span className="pill pill-open" style={{ fontSize: 9 }}>
                      {rca.systemic ? 'Systemic' : 'Isolated'}
                    </span>
                  </td>
                  <td style={{ fontSize: 11, color: '#15803d', fontWeight: 600 }}>{rca.preventionAction}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* 3. CONTROL DEVIATION VIEW */}
      {activeTab === 'control' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-gears" style={{ color: 'var(--danger)' }}></i> Internal Control Deviation Log
            </div>
            {(isComplianceOfficer || isDocAdmin) && (
              <button className="btn btn-primary btn-sm" onClick={() => setShowAddControlModal(true)}>
                <i className="fa-solid fa-plus"></i> Log Control Deviation
              </button>
            )}
          </div>

          <table>
            <thead>
              <tr>
                <th>Inv. ID</th>
                <th>Control Failed</th>
                <th>Failure Type</th>
                <th>Frequency</th>
                <th>Severity</th>
                <th>Remediation Requirement</th>
              </tr>
            </thead>
            <tbody>
              {controlDeviations.map((cd) => (
                <tr key={cd.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{cd.invId}</td>
                  <td style={{ fontWeight: 600, fontSize: 11 }}>{cd.controlFailed}</td>
                  <td><span className="pill pill-progress" style={{ fontSize: 9 }}>{cd.type}</span></td>
                  <td style={{ fontSize: 11 }}>{cd.frequency}</td>
                  <td>
                    <span className="pill pill-open" style={{ fontSize: 9 }}>
                      {cd.severity}
                    </span>
                  </td>
                  <td style={{ fontSize: 11, color: '#15803d' }}>{cd.remediation}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* 4. EVIDENCE LOG VIEW */}
      {activeTab === 'evidence' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-paperclip" style={{ color: 'var(--accent)' }}></i> Chain of Custody — Evidence Exhibits
            </div>
            {(isComplianceOfficer || isDocAdmin) && (
              <button className="btn btn-primary btn-sm" onClick={() => setShowAddEvidenceModal(true)}>
                <i className="fa-solid fa-plus"></i> Attach Evidence Exhibit
              </button>
            )}
          </div>

          <table>
            <thead>
              <tr>
                <th>Exhibit ID</th>
                <th>Inv. ID</th>
                <th>Evidence Description</th>
                <th>Collected By</th>
                <th>Timestamp</th>
                <th>Custody Chain</th>
                <th>Attachment</th>
              </tr>
            </thead>
            <tbody>
              {evidenceLog.map((ev) => (
                <tr key={ev.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{ev.id}</td>
                  <td style={{ fontWeight: 700 }}>{ev.invId}</td>
                  <td style={{ fontSize: 11, maxWidth: 220 }}>{ev.description}</td>
                  <td style={{ fontSize: 11 }}>{ev.collectedBy}</td>
                  <td style={{ fontSize: 10, color: 'var(--text-muted)' }}>{ev.timestamp}</td>
                  <td style={{ fontSize: 11, color: 'var(--text-dim)', fontStyle: 'italic' }}>{ev.custodyChain}</td>
                  <td>
                    {ev.fileName ? (
                      <span style={{ color: 'var(--accent)', fontSize: 10, fontWeight: 700 }}>
                        <i className="fa-solid fa-file-pdf"></i> {ev.fileName}
                      </span>
                    ) : '—'}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* MODAL: OPEN INVESTIGATION (DoC Only) */}
      {showOpenModal && (
        <div style={{
          position: 'fixed',
          top: 0, left: 0, right: 0, bottom: 0,
          background: 'rgba(0, 20, 50, 0.45)',
          backdropFilter: 'blur(3px)',
          zIndex: 1000,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          padding: 20
        }}>
          <div style={{
            background: '#ffffff',
            borderRadius: 'var(--radius-md)',
            border: '1px solid var(--border)',
            boxShadow: 'var(--shadow-lg)',
            width: 580,
            maxWidth: '100%',
            overflow: 'hidden'
          }}>
            <div style={{
              padding: '16px 20px',
              borderBottom: '1px solid var(--border)',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              background: 'var(--surface2)'
            }}>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                Open Confidential Compliance Investigation
              </div>
              <button
                onClick={() => setShowOpenModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleOpenInvestigation} style={{ padding: 20 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Linked Complaint Ref:</label>
                  <input
                    type="text"
                    value={newInvComplaint}
                    onChange={(e) => setNewInvComplaint(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Investigation Category:</label>
                  <select
                    value={newInvCategory}
                    onChange={(e) => setNewInvCategory(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Procurement Fraud</option>
                    <option>Safeguarding / PSEA Breach</option>
                    <option>Advance Non-Reconciliation</option>
                    <option>Bribery & Corruption</option>
                    <option>Data Security Non-Conformity</option>
                    <option>Timesheet Falsification</option>
                  </select>
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Assigned Lead Investigator:</label>
                  <input
                    type="text"
                    value={newInvInvestigator}
                    onChange={(e) => setNewInvInvestigator(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>State / Cluster Scope:</label>
                  <select
                    value={newInvState}
                    onChange={(e) => setNewInvState(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Lagos — Cluster A</option>
                    <option>Kano — Cluster B</option>
                    <option>Rivers — Cluster C</option>
                    <option>Abuja FCT</option>
                    <option>Kaduna</option>
                    <option>Borno</option>
                  </select>
                </div>
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Case Scope & Allegations Summary:</label>
                <textarea
                  placeholder="Outline case background, alleged parties, and preliminary evidence..."
                  value={newInvSummary}
                  onChange={(e) => setNewInvSummary(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 70, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowOpenModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Open Case & Assign Investigator
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: ATTACH EVIDENCE (Compliance Officer & DoC) */}
      {showAddEvidenceModal && (
        <div style={{
          position: 'fixed',
          top: 0, left: 0, right: 0, bottom: 0,
          background: 'rgba(0, 20, 50, 0.45)',
          backdropFilter: 'blur(3px)',
          zIndex: 1000,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          padding: 20
        }}>
          <div style={{
            background: '#ffffff',
            borderRadius: 'var(--radius-md)',
            border: '1px solid var(--border)',
            boxShadow: 'var(--shadow-lg)',
            width: 540,
            maxWidth: '100%',
            overflow: 'hidden'
          }}>
            <div style={{
              padding: '16px 20px',
              borderBottom: '1px solid var(--border)',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              background: 'var(--surface2)'
            }}>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                Attach Evidence Exhibit to Custody Chain
              </div>
              <button
                onClick={() => setShowAddEvidenceModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleSaveEvidence} style={{ padding: 20 }}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Select Investigation Case:</label>
                <select
                  value={evInvId}
                  onChange={(e) => setEvInvId(e.target.value)}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                >
                  {cases.map(c => (
                    <option key={c.id} value={c.id}>{c.id} — {c.category} ({c.state})</option>
                  ))}
                </select>
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Evidence Description:</label>
                <textarea
                  placeholder="Describe evidence (e.g. signed receipts, interview audio, bank transfer logs)..."
                  value={evDesc}
                  onChange={(e) => setEvDesc(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Chain of Custody Handover Log:</label>
                <input
                  type="text"
                  placeholder="e.g. Collected on-site by Investigator -> Placed in Vault"
                  value={evCustody}
                  onChange={(e) => setEvCustody(e.target.value)}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Attach Exhibit File:</label>
                <input
                  type="file"
                  onChange={(e) => {
                    if (e.target.files && e.target.files[0]) {
                      setEvFile(e.target.files[0].name);
                    }
                  }}
                  style={{ width: '100%', padding: 4, fontSize: 11, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowAddEvidenceModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Secure & Log Exhibit
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: ADD RCA ENTRY */}
      {showAddRcaModal && (
        <div style={{
          position: 'fixed',
          top: 0, left: 0, right: 0, bottom: 0,
          background: 'rgba(0, 20, 50, 0.45)',
          backdropFilter: 'blur(3px)',
          zIndex: 1000,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          padding: 20
        }}>
          <div style={{
            background: '#ffffff',
            borderRadius: 'var(--radius-md)',
            border: '1px solid var(--border)',
            boxShadow: 'var(--shadow-lg)',
            width: 540,
            maxWidth: '100%',
            overflow: 'hidden'
          }}>
            <div style={{
              padding: '16px 20px',
              borderBottom: '1px solid var(--border)',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              background: 'var(--surface2)'
            }}>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                Add Root Cause Analysis (RCA)
              </div>
              <button
                onClick={() => setShowAddRcaModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleSaveRca} style={{ padding: 20 }}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Select Investigation Case:</label>
                <select
                  value={rcaInvId}
                  onChange={(e) => setRcaInvId(e.target.value)}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                >
                  {cases.map(c => (
                    <option key={c.id} value={c.id}>{c.id} — {c.category}</option>
                  ))}
                </select>
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Root Cause Finding:</label>
                <textarea
                  placeholder="Primary root cause for the compliance failure..."
                  value={rcaRootCause}
                  onChange={(e) => setRcaRootCause(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Contributing Environmental Factors:</label>
                <input
                  type="text"
                  placeholder="e.g. Remote location, staffing shortage"
                  value={rcaFactors}
                  onChange={(e) => setRcaFactors(e.target.value)}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Systemic Prevention Action:</label>
                <input
                  type="text"
                  placeholder="e.g. Enforce dual-token digital authorization"
                  value={rcaAction}
                  onChange={(e) => setRcaAction(e.target.value)}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowAddRcaModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Save RCA Entry
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: ADD CONTROL DEVIATION */}
      {showAddControlModal && (
        <div style={{
          position: 'fixed',
          top: 0, left: 0, right: 0, bottom: 0,
          background: 'rgba(0, 20, 50, 0.45)',
          backdropFilter: 'blur(3px)',
          zIndex: 1000,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          padding: 20
        }}>
          <div style={{
            background: '#ffffff',
            borderRadius: 'var(--radius-md)',
            border: '1px solid var(--border)',
            boxShadow: 'var(--shadow-lg)',
            width: 540,
            maxWidth: '100%',
            overflow: 'hidden'
          }}>
            <div style={{
              padding: '16px 20px',
              borderBottom: '1px solid var(--border)',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              background: 'var(--surface2)'
            }}>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                Log Internal Control Deviation
              </div>
              <button
                onClick={() => setShowAddControlModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleSaveControl} style={{ padding: 20 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Select Investigation Case:</label>
                  <select
                    value={ctrlInvId}
                    onChange={(e) => setCtrlInvId(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    {cases.map(c => (
                      <option key={c.id} value={c.id}>{c.id} — {c.category}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Deviation Severity:</label>
                  <select
                    value={ctrlSeverity}
                    onChange={(e) => setCtrlSeverity(e.target.value as any)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Critical</option>
                    <option>High</option>
                    <option>Medium</option>
                    <option>Low</option>
                  </select>
                </div>
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Internal Control Failed:</label>
                <input
                  type="text"
                  placeholder="e.g. Dual Signatory Petty Cash (POL-003 §4.2)"
                  value={ctrlFailed}
                  onChange={(e) => setCtrlFailed(e.target.value)}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Required Systemic Remediation:</label>
                <textarea
                  placeholder="Describe control fixes required in standard procedures..."
                  value={ctrlRemediation}
                  onChange={(e) => setCtrlRemediation(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowAddControlModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Save Control Deviation
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: VIEW CASE DOSSIER */}
      {viewingCaseDossier && (
        <div style={{
          position: 'fixed',
          top: 0, left: 0, right: 0, bottom: 0,
          background: 'rgba(0, 20, 50, 0.45)',
          backdropFilter: 'blur(3px)',
          zIndex: 1000,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          padding: 20
        }}>
          <div style={{
            background: '#ffffff',
            borderRadius: 'var(--radius-md)',
            border: '1px solid var(--border)',
            boxShadow: 'var(--shadow-lg)',
            width: 620,
            maxWidth: '100%',
            overflow: 'hidden'
          }}>
            <div style={{
              padding: '16px 20px',
              borderBottom: '1px solid var(--border)',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              background: 'var(--surface2)'
            }}>
              <div>
                <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                  Case Dossier — {viewingCaseDossier.id}
                </div>
                <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                  Linked Complaint: {viewingCaseDossier.linkedComplaint} &nbsp;·&nbsp; {viewingCaseDossier.state}
                </div>
              </div>
              <button
                onClick={() => setViewingCaseDossier(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <div style={{ padding: 20, fontSize: 12 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 14 }}>
                <div><strong>Category:</strong> {viewingCaseDossier.category}</div>
                <div><strong>Stage:</strong> {getStagePill(viewingCaseDossier.stage)}</div>
                <div><strong>Opened Date:</strong> {viewingCaseDossier.openedDate}</div>
                <div><strong>Status:</strong> {viewingCaseDossier.status}</div>
                <div><strong>Alleged Party:</strong> {viewingCaseDossier.allegedParty}</div>
                <div><strong>Lead Investigator:</strong> {viewingCaseDossier.investigator}</div>
              </div>

              <div style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, border: '1px solid var(--border)', marginBottom: 14 }}>
                <strong>Case Background & Scope:</strong>
                <p style={{ marginTop: 4, color: 'var(--text-dim)', lineHeight: 1.5 }}>
                  {viewingCaseDossier.summary}
                </p>
              </div>

              {/* Linked Evidence Count */}
              <div style={{ background: '#dbeafe', padding: 12, borderRadius: 8, border: '1px solid #bfdbfe', marginBottom: 16 }}>
                <strong style={{ color: 'var(--accent)' }}>📎 Secured Evidence Exhibits:</strong>
                <div style={{ fontSize: 11, color: '#1e3a8a', marginTop: 4 }}>
                  {evidenceLog.filter(e => e.invId === viewingCaseDossier.id).length} exhibits secured in the chain of custody.
                </div>
              </div>

              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                {isDocAdmin && (
                  <button
                    className="btn btn-outline btn-sm"
                    onClick={() => alert(`Exporting Certified Investigation Report Dossier for ${viewingCaseDossier.id} (PDF)...`)}
                  >
                    <i className="fa-solid fa-file-pdf"></i> Generate Case PDF
                  </button>
                )}
                <button className="btn btn-outline" onClick={() => setViewingCaseDossier(null)}>
                  Close Dossier
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
