import React, { useState } from 'react';
import { useToast } from '../../context/ToastContext';
import { useAuth } from '../../context/AuthContext';

export interface CAPItem {
  id: string;
  issue: string;
  state: string;
  linkedRef: string;
  deadline: string;
  responsible: string;
  status: 'Open' | 'In Progress' | 'Evidence Submitted' | 'Closed';
  priority: 'Critical' | 'High' | 'Medium' | 'Low';
  rootCause?: string;
  evidenceNotes?: string;
  evidenceFileName?: string;
  submittedBy?: string;
  submittedAt?: string;
}

export const CapModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();
  const { showSuccess } = useToast();

  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isHR = currentUser?.key === 'hr';
  const isStaff = currentUser?.key === 'staff';

  // Seed CAP data matching wireframe
  const [caps, setCaps] = useState<CAPItem[]>([
    {
      id: 'CAP-032',
      issue: 'Field procurement dual-authorization bypass',
      state: 'Kano',
      linkedRef: 'CMP-048',
      deadline: '15 Mar 2026',
      responsible: 'State Coordinator',
      status: 'Open',
      priority: 'Critical',
      rootCause: 'Lack of local secondary signatory during remote cluster visits.'
    },
    {
      id: 'CAP-031',
      issue: 'PSEA mandatory training compliance below 70% threshold',
      state: 'Kaduna',
      linkedRef: 'AUDIT-2026-02',
      deadline: '20 Mar 2026',
      responsible: 'Field HR Officer',
      status: 'In Progress',
      priority: 'High',
      rootCause: 'High turnover of ad-hoc community testing staff.'
    },
    {
      id: 'CAP-030',
      issue: 'Unreconciled advance logs for January 2026 outreach',
      state: 'Lagos',
      linkedRef: 'CMP-047',
      deadline: '10 Mar 2026',
      responsible: 'Finance Associate',
      status: 'Evidence Submitted',
      priority: 'Medium',
      rootCause: 'Delayed physical receipt collection from clinical facilities.',
      evidenceNotes: 'All 14 receipt vouchers collected, scanned and verified with bank statements.',
      evidenceFileName: 'Lagos_Jan2026_Reconciliation_Signed.pdf',
      submittedBy: 'Fatima Aliyu (Finance)',
      submittedAt: 'Today, 11:30 AM'
    },
    {
      id: 'CAP-029',
      issue: 'Asset register discrepancies in clinical testing lab',
      state: 'Rivers',
      linkedRef: 'CMP-046',
      deadline: '28 Feb 2026',
      responsible: 'Logistics Officer',
      status: 'Closed',
      priority: 'Low',
      rootCause: 'Serial numbers misrecorded during physical relocation.'
    }
  ]);

  // Form states
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingCap, setEditingCap] = useState<CAPItem | null>(null);
  const [viewingCap, setViewingCap] = useState<CAPItem | null>(null);

  // Evidence Submission Form State
  const [selectedCapForEvidence, setSelectedCapForEvidence] = useState(caps[0]?.id || '');
  const [evidenceSubmitter, setEvidenceSubmitter] = useState(currentUser?.name || '');
  const [evidenceNotes, setEvidenceNotes] = useState('');
  const [evidenceFileName, setEvidenceFileName] = useState('');

  // Create / Edit Form State
  const [formData, setFormData] = useState({
    issue: '',
    state: 'Lagos',
    linkedRef: '',
    deadline: '',
    responsible: '',
    priority: 'High' as 'Critical' | 'High' | 'Medium' | 'Low',
    rootCause: ''
  });

  const handleOpenCreate = () => {
    setFormData({
      issue: '',
      state: 'Lagos',
      linkedRef: '',
      deadline: '',
      responsible: '',
      priority: 'High',
      rootCause: ''
    });
    setEditingCap(null);
    setShowCreateModal(true);
  };

  const handleOpenEdit = (cap: CAPItem) => {
    setFormData({
      issue: cap.issue,
      state: cap.state,
      linkedRef: cap.linkedRef,
      deadline: cap.deadline,
      responsible: cap.responsible,
      priority: cap.priority,
      rootCause: cap.rootCause || ''
    });
    setEditingCap(cap);
    setShowCreateModal(true);
  };

  const handleSaveCap = (e: React.FormEvent) => {
    e.preventDefault();
    if (editingCap) {
      setCaps(caps.map(c => c.id === editingCap.id ? { ...c, ...formData } : c));
      showSuccess('CAP Updated', `CAP ${editingCap.id} updated successfully.`);
    } else {
      const newId = 'CAP-0' + (33 + caps.length);
      const newCap: CAPItem = {
        id: newId,
        ...formData,
        status: 'Open'
      };
      setCaps([newCap, ...caps]);
      alert(`Corrective Action Plan ${newId} successfully registered.`);
    }
    setShowCreateModal(false);
  };

  const handleDelete = (id: string) => {
    if (!isDocAdmin) {
      alert('Action Restricted: Only the Director of Compliance has permission to delete Corrective Action Plans.');
      return;
    }
    if (!confirm(`Permanently delete ${id}? This action is audit-logged.`)) return;
    setCaps(caps.filter(c => c.id !== id));
  };

  const handleSubmitEvidence = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedCapForEvidence) {
      alert('Please select a CAP to submit evidence for.');
      return;
    }
    if (!evidenceNotes.trim()) {
      alert('Please enter progress notes describing the corrective action taken.');
      return;
    }

    const today = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    setCaps(caps.map(c => {
      if (c.id === selectedCapForEvidence) {
        return {
          ...c,
          status: 'Evidence Submitted',
          evidenceNotes: evidenceNotes,
          evidenceFileName: evidenceFileName || 'State_Evidence_Document.pdf',
          submittedBy: evidenceSubmitter || currentUser?.name || 'State Field Lead',
          submittedAt: `Today, ${today}`
        };
      }
      return c;
    }));

    setEvidenceNotes('');
    setEvidenceFileName('');
    alert(`Evidence for ${selectedCapForEvidence} successfully submitted. Transferred to Compliance Review Queue.`);
  };

  const handleCloseCap = (capId: string) => {
    setCaps(caps.map(c => c.id === capId ? { ...c, status: 'Closed' } : c));
    alert(`CAP ${capId} verified and officially Closed.`);
  };

  const handleRejectEvidence = (capId: string) => {
    setCaps(caps.map(c => c.id === capId ? { ...c, status: 'In Progress' } : c));
    alert(`Evidence for ${capId} returned to state team for further documentation.`);
  };

  const getStatusPill = (st: string) => {
    switch (st) {
      case 'Open': return <span className="pill pill-open">Open</span>;
      case 'In Progress': return <span className="pill pill-progress">In Progress</span>;
      case 'Evidence Submitted': return <span className="pill" style={{ background: '#dbeafe', color: 'var(--accent)' }}>Evidence Submitted</span>;
      case 'Closed': return <span className="pill pill-closed">✓ Closed & Verified</span>;
      default: return <span className="pill pill-progress">{st}</span>;
    }
  };

  const pendingReviews = caps.filter(c => c.status === 'Evidence Submitted');

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* Sub-heading & Sub-desc per wireframe */}
      <div style={{ marginBottom: 16 }}>
        <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
          Corrective Action Plans (CAP)
        </h2>
        <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
          Track, update and verify corrective actions with evidence submission by state teams
        </p>

        {/* Role Matrix Indicator */}
        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-cloud-arrow-up"></i> <strong>Staff Permissions:</strong> View assigned CAPs and submit state remediation evidence.
          </div>
        )}
        {isHR && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-eye"></i> <strong>HR Access:</strong> View-only monitoring across all institutional CAPs.
          </div>
        )}
        {isComplianceOfficer && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-shield"></i> <strong>Compliance Officer:</strong> Create CAP, Edit, Review state evidence, and Close CAP. (Delete disabled).
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Administrative Authority · Create, Edit, Review Evidence, Close & Delete.
          </div>
        )}
      </div>

      {/* GRID 2: CAP Summary Table + State Evidence Submission */}
      <div className="grid-2">
        
        {/* LEFT: CAP Summary Table */}
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title" style={{ margin: 0 }}>
              <i className="fa-solid fa-circle-check" style={{ color: 'var(--success)' }}></i> CAP Summary Ledger
            </div>
            {/* Create CAP: Compliance Officer & DoC only */}
            {(isDocAdmin || isComplianceOfficer) && (
              <button className="btn btn-primary btn-sm" onClick={handleOpenCreate}>
                <i className="fa-solid fa-plus"></i> Create CAP
              </button>
            )}
          </div>

          <table>
            <thead>
              <tr>
                <th>CAP ID</th>
                <th>Issue</th>
                <th>State</th>
                <th>Linked</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {caps.map((c) => (
                <tr key={c.id}>
                  <td style={{ color: 'var(--accent)', fontWeight: 700 }}>{c.id}</td>
                  <td style={{ fontSize: 11, maxWidth: 140, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }} title={c.issue}>
                    {c.issue}
                  </td>
                  <td>{c.state}</td>
                  <td style={{ fontSize: 10, color: 'var(--text-muted)' }}>{c.linkedRef || '—'}</td>
                  <td style={{ fontSize: 11 }}>{c.deadline}</td>
                  <td>{getStatusPill(c.status)}</td>
                  <td>
                    <div style={{ display: 'flex', gap: 4, alignItems: 'center' }}>
                      <button
                        className="btn btn-outline"
                        style={{ padding: '2px 6px', fontSize: 10 }}
                        onClick={() => setViewingCap(c)}
                        title="View Details"
                      >
                        👁️
                      </button>

                      {/* Edit: Compliance Officer & DoC only */}
                      {(isDocAdmin || isComplianceOfficer) && (
                        <button
                          className="btn btn-outline"
                          style={{ padding: '2px 6px', fontSize: 10 }}
                          onClick={() => handleOpenEdit(c)}
                          title="Edit CAP"
                        >
                          ✏️
                        </button>
                      )}

                      {/* Delete: DoC ONLY */}
                      {isDocAdmin && (
                        <button
                          className="btn btn-outline"
                          style={{ padding: '2px 6px', fontSize: 10, color: 'var(--danger)', borderColor: 'var(--danger)' }}
                          onClick={() => handleDelete(c.id)}
                          title="Admin Delete (DoC Only)"
                        >
                          🗑️
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* RIGHT: State Evidence Submission & Review Queue */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-paperclip" style={{ color: 'var(--accent)' }}></i> State Evidence Submission & Review
            </div>
          </div>
          <p style={{ fontSize: 11, color: 'var(--text-muted)', marginBottom: 14 }}>
            State teams submit proof documents. Compliance reviews, accepts, or requests further remediation before closing CAP.
          </p>

          {/* STEP 1: STAFF EVIDENCE SUBMISSION (All Staff, Compliance Officer, DoC) */}
          {!isHR && (
            <div style={{ background: 'var(--surface2, #f0f7fd)', border: '1px solid var(--border)', borderRadius: 10, padding: 14, marginBottom: 14 }}>
              <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--accent)', textTransform: 'uppercase', letterSpacing: 0.8, marginBottom: 10, display: 'flex', alignItems: 'center', gap: 6 }}>
                <i className="fa-solid fa-cloud-arrow-up"></i> Step 1 — Staff Evidence Submission
              </div>
              <form onSubmit={handleSubmitEvidence} style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Select Active CAP:</label>
                  <select
                    value={selectedCapForEvidence}
                    onChange={(e) => setSelectedCapForEvidence(e.target.value)}
                    style={{ width: '100%', padding: '6px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3, background: '#ffffff' }}
                  >
                    {caps.filter(c => c.status !== 'Closed').map(c => (
                      <option key={c.id} value={c.id}>{c.id} — {c.state}: {c.issue.substring(0, 45)}...</option>
                    ))}
                  </select>
                </div>

                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8 }}>
                  <div>
                    <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Submitted By:</label>
                    <input
                      type="text"
                      placeholder="Staff name / State Team"
                      value={evidenceSubmitter}
                      onChange={(e) => setEvidenceSubmitter(e.target.value)}
                      required
                      style={{ width: '100%', padding: '6px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3, background: '#ffffff' }}
                    />
                  </div>
                  <div>
                    <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Attach Evidence File:</label>
                    <input
                      type="file"
                      onChange={(e) => {
                        if (e.target.files && e.target.files[0]) {
                          setEvidenceFileName(e.target.files[0].name);
                        }
                      }}
                      style={{ width: '100%', padding: '4px', fontSize: 11, marginTop: 3 }}
                    />
                  </div>
                </div>

                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Progress / Remediation Notes:</label>
                  <textarea
                    placeholder="Describe specific corrective measures implemented..."
                    value={evidenceNotes}
                    onChange={(e) => setEvidenceNotes(e.target.value)}
                    required
                    style={{ width: '100%', minHeight: 60, padding: '6px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3, background: '#ffffff' }}
                  />
                </div>

                <button type="submit" className="btn btn-primary btn-sm" style={{ alignSelf: 'flex-start', marginTop: 4 }}>
                  <i className="fa-solid fa-paper-plane"></i> Submit Evidence for Review
                </button>
              </form>
            </div>
          )}

          {/* STEP 2: COMPLIANCE OFFICER & DOC REVIEW QUEUE */}
          <div style={{ background: '#fef3c7', border: '1px solid #fde68a', borderRadius: 10, padding: 14 }}>
            <div style={{ fontSize: 11, fontWeight: 700, color: '#92400e', textTransform: 'uppercase', letterSpacing: 0.8, marginBottom: 8, display: 'flex', alignItems: 'center', gap: 6 }}>
              <i className="fa-solid fa-clipboard-check"></i> Step 2 — Compliance Review & Closure Queue
            </div>

            {pendingReviews.length === 0 ? (
              <div style={{ fontSize: 11, color: 'var(--text-muted)', textAlign: 'center', padding: '12px 0' }}>
                ✓ No evidence submissions currently awaiting review.
              </div>
            ) : (
              <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
                {pendingReviews.map((c) => (
                  <div key={c.id} style={{ background: '#ffffff', border: '1px solid #fde68a', borderRadius: 8, padding: 10, fontSize: 11 }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 4 }}>
                      <span style={{ fontWeight: 700, color: 'var(--accent)' }}>{c.id} ({c.state})</span>
                      <span style={{ fontSize: 10, color: 'var(--text-muted)' }}>{c.submittedAt}</span>
                    </div>
                    <div style={{ color: 'var(--text-dim)', marginBottom: 4 }}>
                      <strong>Submitted by:</strong> {c.submittedBy}
                    </div>
                    <div style={{ color: 'var(--text-muted)', marginBottom: 6, fontStyle: 'italic' }}>
                      "{c.evidenceNotes}"
                    </div>
                    {c.evidenceFileName && (
                      <div style={{ display: 'inline-flex', alignItems: 'center', gap: 4, padding: '2px 8px', background: 'var(--surface2)', borderRadius: 4, fontSize: 10, marginBottom: 8, color: 'var(--accent)' }}>
                        <i className="fa-solid fa-file-pdf"></i> {c.evidenceFileName}
                      </div>
                    )}

                    {/* Review Actions: Compliance Officer & DoC only */}
                    {(isDocAdmin || isComplianceOfficer) ? (
                      <div style={{ display: 'flex', gap: 6, marginTop: 4 }}>
                        <button
                          className="btn btn-primary btn-sm"
                          style={{ background: 'var(--success)', borderColor: 'var(--success)', padding: '3px 8px', fontSize: 10 }}
                          onClick={() => handleCloseCap(c.id)}
                        >
                          ✓ Accept & Close CAP
                        </button>
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ padding: '3px 8px', fontSize: 10, color: 'var(--danger)', borderColor: 'var(--danger)' }}
                          onClick={() => handleRejectEvidence(c.id)}
                        >
                          ✕ Reject / More Proof
                        </button>
                      </div>
                    ) : (
                      <div style={{ fontSize: 10, color: 'var(--text-muted)', fontStyle: 'italic' }}>
                        Awaiting review by Compliance Officer or Director.
                      </div>
                    )}
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>

      </div>

      {/* MODAL: CREATE / EDIT CAP */}
      {showCreateModal && (
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
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                {editingCap ? `Edit Corrective Action Plan — ${editingCap.id}` : 'Create Corrective Action Plan (CAP)'}
              </div>
              <button
                onClick={() => setShowCreateModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleSaveCap} style={{ padding: 20 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Linked Complaint / Audit Ref:</label>
                  <input
                    type="text"
                    placeholder="e.g. CMP-047 or AUDIT-2026-01"
                    value={formData.linkedRef}
                    onChange={(e) => setFormData({ ...formData, linkedRef: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Operational State:</label>
                  <select
                    value={formData.state}
                    onChange={(e) => setFormData({ ...formData, state: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Lagos</option>
                    <option>Kano</option>
                    <option>Rivers</option>
                    <option>Abuja FCT</option>
                    <option>Kaduna</option>
                    <option>Borno</option>
                  </select>
                </div>
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Issue Description:</label>
                <textarea
                  placeholder="What compliance issue requires correction?"
                  value={formData.issue}
                  onChange={(e) => setFormData({ ...formData, issue: e.target.value })}
                  required
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 10, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Responsible Lead:</label>
                  <input
                    type="text"
                    placeholder="Name / position"
                    value={formData.responsible}
                    onChange={(e) => setFormData({ ...formData, responsible: e.target.value })}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Remediation Deadline:</label>
                  <input
                    type="date"
                    value={formData.deadline}
                    onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Priority Tier:</label>
                  <select
                    value={formData.priority}
                    onChange={(e) => setFormData({ ...formData, priority: e.target.value as any })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>High</option>
                    <option>Medium</option>
                    <option>Low</option>
                    <option>Critical</option>
                  </select>
                </div>
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>
                  Root Cause Analysis <span style={{ color: 'var(--danger)' }}>*</span>:
                </label>
                <textarea
                  placeholder="Describe root cause (e.g. system control gap, staff training deficit, lack of field oversight)..."
                  value={formData.rootCause}
                  onChange={(e) => setFormData({ ...formData, rootCause: e.target.value })}
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowCreateModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  {editingCap ? 'Save Changes' : 'Create CAP'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: VIEW CAP DETAILS */}
      {viewingCap && (
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
                CAP Dossier — {viewingCap.id}
              </div>
              <button
                onClick={() => setViewingCap(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <div style={{ padding: 20, fontSize: 12 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 14 }}>
                <div><strong>Operational State:</strong> {viewingCap.state}</div>
                <div><strong>Linked Ref:</strong> {viewingCap.linkedRef || 'None'}</div>
                <div><strong>Responsible Lead:</strong> {viewingCap.responsible}</div>
                <div><strong>Deadline:</strong> {viewingCap.deadline}</div>
                <div><strong>Priority:</strong> {viewingCap.priority}</div>
                <div><strong>Current Status:</strong> {getStatusPill(viewingCap.status)}</div>
              </div>

              <div style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, border: '1px solid var(--border)', marginBottom: 14 }}>
                <strong>Issue Description:</strong>
                <p style={{ marginTop: 4, color: 'var(--text-dim)' }}>{viewingCap.issue}</p>
              </div>

              {viewingCap.rootCause && (
                <div style={{ background: '#fef3c7', padding: 12, borderRadius: 8, border: '1px solid #fde68a', marginBottom: 14 }}>
                  <strong style={{ color: '#92400e' }}>🧩 Root Cause Analysis:</strong>
                  <p style={{ marginTop: 4, color: '#78350f' }}>{viewingCap.rootCause}</p>
                </div>
              )}

              {viewingCap.evidenceNotes && (
                <div style={{ background: '#d1fae5', padding: 12, borderRadius: 8, border: '1px solid #6ee7b7', marginBottom: 14 }}>
                  <strong style={{ color: '#065f46' }}>📎 Submitted Remediation Evidence:</strong>
                  <p style={{ marginTop: 4, color: '#064e3b' }}>{viewingCap.evidenceNotes}</p>
                  {viewingCap.evidenceFileName && (
                    <div style={{ marginTop: 6, fontSize: 11, fontWeight: 700, color: 'var(--accent)' }}>
                      <i className="fa-solid fa-file-pdf"></i> {viewingCap.evidenceFileName}
                    </div>
                  )}
                </div>
              )}

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button className="btn btn-outline" onClick={() => setViewingCap(null)}>
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
