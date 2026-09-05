import React, { useState } from 'react';
import { useToast } from '../../context/ToastContext';
import { useAuth } from '../../context/AuthContext';

export interface PolicyItem {
  id: string;
  title: string;
  category: string;
  version: string;
  lastReviewed: string;
  nextReview: string;
  status: 'Active / Published' | 'Under Review' | 'Draft';
  ackCount: number;
  totalRequired: number;
  summary: string;
  fileName: string;
  userAcknowledged?: boolean;
}

export const PolicyModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();
  const { showSuccess, showError } = useToast();

  const isHR = currentUser?.key === 'hr';
  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isStaff = currentUser?.key === 'staff';

  // Seed Institutional Policies matching wireframe
  const [policies, setPolicies] = useState<PolicyItem[]>([
    {
      id: 'POL-001',
      title: 'Institutional Code of Conduct, Ethics & Whistleblower Policy',
      category: 'Governance & Ethics',
      version: 'v3.0',
      lastReviewed: '15 Jan 2026',
      nextReview: '15 Jan 2027',
      status: 'Active / Published',
      ackCount: 462,
      totalRequired: 490,
      summary: 'Governs professional integrity, conflict of interest declarations, gift policies, and confidential whistleblower protection channels.',
      fileName: 'CCCRN_Code_of_Conduct_2026.pdf',
      userAcknowledged: true
    },
    {
      id: 'POL-002',
      title: 'Protection from Sexual Exploitation, Abuse & Harassment (PSEA)',
      category: 'Safeguarding',
      version: 'v2.2',
      lastReviewed: '10 Feb 2026',
      nextReview: '10 Feb 2027',
      status: 'Active / Published',
      ackCount: 420,
      totalRequired: 490,
      summary: 'Zero tolerance policy for sexual misconduct, beneficiary exploitation, and child safeguarding standards in clinical facilities.',
      fileName: 'CCCRN_PSEA_Policy_Updated.pdf',
      userAcknowledged: true
    },
    {
      id: 'POL-003',
      title: '2 CFR 200 & Federal Grant Procurement Standard Operating Procedures',
      category: 'Grant Compliance',
      version: 'v4.1',
      lastReviewed: '20 Jan 2026',
      nextReview: '20 Jan 2027',
      status: 'Active / Published',
      ackCount: 388,
      totalRequired: 490,
      summary: 'Establishes mandatory thresholds for dual-signatory authorization, 3-quote competitive bidding, and sole-source justifications.',
      fileName: 'CCCRN_Procurement_SOP_v4.pdf',
      userAcknowledged: false
    },
    {
      id: 'POL-004',
      title: 'Data Protection, Client Privacy & Electronic Records Security Policy',
      category: 'Information Security',
      version: 'v1.4',
      lastReviewed: '05 Feb 2026',
      nextReview: '05 Feb 2027',
      status: 'Active / Published',
      ackCount: 345,
      totalRequired: 490,
      summary: 'Enforces NDPR and HIPAA compliance for physical clinic registers, EMR server encryption, and biometric access controls.',
      fileName: 'CCCRN_Data_Protection_Policy.pdf',
      userAcknowledged: false
    }
  ]);

  // Modals & UI States
  const [showAddModal, setShowAddModal] = useState(false);
  const [editingPolicy, setEditingPolicy] = useState<PolicyItem | null>(null);
  const [readingPolicy, setReadingPolicy] = useState<PolicyItem | null>(null);
  const [ackConfirmed, setAckConfirmed] = useState(false);

  // Form State for Add / Edit Policy
  const [formData, setFormData] = useState({
    title: '',
    category: 'Governance & Ethics',
    version: 'v1.0',
    summary: '',
    status: 'Active / Published' as 'Active / Published' | 'Under Review' | 'Draft',
    nextReview: '2027-01-15'
  });

  // Guard: Compliance Officer has No Access per matrix
  if (isComplianceOfficer) {
    return (
      <div className="card" style={{ textAlign: 'center', padding: 40, background: 'var(--danger-light, #fee2e2)', border: '1px solid #fca5a5' }}>
        <i className="fa-solid fa-lock" style={{ fontSize: 32, color: 'var(--danger, #dc2626)', marginBottom: 12 }}></i>
        <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--danger, #dc2626)', marginBottom: 6 }}>
          Restricted Access: Policy Management
        </h3>
        <p style={{ fontSize: 13, color: 'var(--text-dim)' }}>
          Policy administration is governed exclusively by Human Resources (HR) and Director of Compliance (DoC).
        </p>
      </div>
    );
  }

  const handleOpenAdd = () => {
    setFormData({
      title: '',
      category: 'Governance & Ethics',
      version: 'v1.0',
      summary: '',
      status: 'Active / Published',
      nextReview: '2027-01-15'
    });
    setEditingPolicy(null);
    setShowAddModal(true);
  };

  const handleOpenEdit = (p: PolicyItem) => {
    setFormData({
      title: p.title,
      category: p.category,
      version: p.version,
      summary: p.summary,
      status: p.status,
      nextReview: p.nextReview
    });
    setEditingPolicy(p);
    setShowAddModal(true);
  };

  const handleSavePolicy = (e: React.FormEvent) => {
    e.preventDefault();
    if (editingPolicy) {
      setPolicies(policies.map(p => p.id === editingPolicy.id ? { ...p, ...formData } : p));
      alert(`Policy ${editingPolicy.id} updated successfully.`);
    } else {
      const newId = 'POL-00' + (policies.length + 1);
      const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
      const newPolicy: PolicyItem = {
        id: newId,
        ...formData,
        lastReviewed: today,
        ackCount: 0,
        totalRequired: 490,
        fileName: formData.title.replace(/\s+/g, '_') + '.pdf',
        userAcknowledged: false
      };
      setPolicies([...policies, newPolicy]);
      alert(`New Institutional Policy "${formData.title}" published. Digital acknowledgement broadcast to all staff.`);
    }
    setShowAddModal(false);
  };

  const handleDeletePolicy = (id: string) => {
    if (!isDocAdmin) {
      showError('Access Restricted', 'Only the Director of Compliance has authority to delete institutional policies.');
      return;
    }
    if (!confirm(`Permanently delete policy ${id}? This action is logged.`)) return;
    setPolicies(policies.filter(p => p.id !== id));
  };

  const handleAcknowledge = (policyId: string) => {
    if (!ackConfirmed) {
      alert('Please check the digital attestation box confirming you have read and understood the policy.');
      return;
    }

    setPolicies(policies.map(p => {
      if (p.id === policyId) {
        return {
          ...p,
          userAcknowledged: true,
          ackCount: p.ackCount + 1
        };
      }
      return p;
    }));

    setReadingPolicy(null);
    setAckConfirmed(false);
    showSuccess('Acknowledgement Registered', 'Your digital compliance attestation has been cryptographically recorded.');
  };

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
              Policy Management & Institutional Library
            </h2>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
              Create, review, publish and track staff compliance acknowledgement of institutional policies
            </p>
          </div>

          {/* New Policy: HR and DoC (All Access) */}
          {(isHR || isDocAdmin) && (
            <button className="btn btn-primary" onClick={handleOpenAdd}>
              <i className="fa-solid fa-plus"></i> New Policy
            </button>
          )}
        </div>

        {/* ROLE MATRIX SCOPE BADGES */}
        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-file-signature"></i> <strong>Staff Policy Portal:</strong> Read mandatory institutional policies and submit your digital compliance acknowledgement.
          </div>
        )}
        {isHR && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-shield"></i> <strong>HR All Access:</strong> Upload policies, manage versions, and monitor staff acknowledgement rates across all states.
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> All Access · Publish, Edit, Delete Policies, and Enforce Grant Regulatory Standards.
          </div>
        )}
      </div>

      {/* POLICY LIBRARY CARD & TABLE */}
      <div className="card">
        <div className="card-header">
          <div className="card-title">
            <i className="fa-solid fa-book-bookmark" style={{ color: 'var(--accent)' }}></i> Institutional Policy Library
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
            Total {policies.length} Policies Published
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>Policy ID</th>
              <th>Title</th>
              <th>Category</th>
              <th>Version</th>
              <th>Last Reviewed</th>
              <th>Status</th>
              <th>Acknowledgement Rate</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {policies.map((p) => {
              const ackPercent = Math.round((p.ackCount / p.totalRequired) * 100);
              return (
                <tr key={p.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{p.id}</td>
                  <td style={{ fontWeight: 600, maxWidth: 240, fontSize: 12 }}>
                    {p.title}
                  </td>
                  <td>{p.category}</td>
                  <td><span className="pill pill-progress" style={{ fontSize: 10 }}>{p.version}</span></td>
                  <td style={{ fontSize: 11 }}>{p.lastReviewed}</td>
                  <td><span className="pill pill-closed">{p.status}</span></td>
                  <td>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 2 }}>
                      <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 10 }}>
                        <span>{p.ackCount}/{p.totalRequired}</span>
                        <strong>{ackPercent}%</strong>
                      </div>
                      <div style={{ height: 5, width: 90, background: 'var(--border)', borderRadius: 3, overflow: 'hidden' }}>
                        <div style={{ height: '100%', width: `${ackPercent}%`, background: ackPercent >= 80 ? 'var(--success)' : 'var(--warning)', borderRadius: 3 }}></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div style={{ display: 'flex', gap: 4, alignItems: 'center' }}>
                      {/* Read & Acknowledge */}
                      <button
                        className="btn btn-outline btn-sm"
                        style={{
                          padding: '3px 8px', fontSize: 10,
                          background: p.userAcknowledged ? '#f0fdf4' : 'var(--accent-light)',
                          color: p.userAcknowledged ? 'var(--success)' : 'var(--accent)',
                          borderColor: p.userAcknowledged ? 'var(--success)' : 'var(--accent)'
                        }}
                        onClick={() => {
                          setReadingPolicy(p);
                          setAckConfirmed(p.userAcknowledged || false);
                        }}
                      >
                        {p.userAcknowledged ? '✓ Acknowledged' : '📖 Read & Acknowledge'}
                      </button>

                      {/* Edit: HR & DoC */}
                      {(isHR || isDocAdmin) && (
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ padding: '3px 6px', fontSize: 10 }}
                          onClick={() => handleOpenEdit(p)}
                          title="Edit Policy"
                        >
                          ✏️
                        </button>
                      )}

                      {/* Delete: DoC Only */}
                      {isDocAdmin && (
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ padding: '3px 6px', fontSize: 10, color: 'var(--danger)', borderColor: 'var(--danger)' }}
                          onClick={() => handleDeletePolicy(p.id)}
                          title="Admin Delete Policy"
                        >
                          🗑️
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {/* MODAL: READ & ACKNOWLEDGE POLICY */}
      {readingPolicy && (
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
            width: 640,
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
                  {readingPolicy.title}
                </div>
                <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                  {readingPolicy.id} &nbsp;·&nbsp; Version: {readingPolicy.version} &nbsp;·&nbsp; Category: {readingPolicy.category}
                </div>
              </div>
              <button
                onClick={() => setReadingPolicy(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <div style={{ padding: 20, fontSize: 12 }}>
              <div style={{ background: 'var(--surface2)', padding: 14, borderRadius: 8, border: '1px solid var(--border)', marginBottom: 14 }}>
                <strong>Executive Policy Summary:</strong>
                <p style={{ marginTop: 4, color: 'var(--text)', lineHeight: 1.5 }}>
                  {readingPolicy.summary}
                </p>
                <div style={{ marginTop: 10 }}>
                  <button
                    className="btn btn-outline btn-sm"
                    onClick={() => alert(`Opening ${readingPolicy.fileName} in secure institutional document viewer...`)}
                  >
                    <i className="fa-solid fa-file-pdf" style={{ color: 'var(--danger)', marginRight: 5 }}></i>
                    Download Full Policy Document (PDF)
                  </button>
                </div>
              </div>

              {/* Digital Attestation Box */}
              <div style={{ background: '#f0f9ff', border: '1px solid #bae6fd', borderRadius: 8, padding: 14, marginBottom: 16 }}>
                <label style={{ display: 'flex', alignItems: 'flex-start', gap: 10, cursor: 'pointer' }}>
                  <input
                    type="checkbox"
                    checked={ackConfirmed}
                    onChange={(e) => setAckConfirmed(e.target.checked)}
                    disabled={readingPolicy.userAcknowledged}
                    style={{ width: 18, height: 18, marginTop: 2, accentColor: 'var(--accent)', flexShrink: 0 }}
                  />
                  <div>
                    <strong style={{ color: '#0369a1' }}>Digital Compliance Attestation:</strong>
                    <div style={{ color: '#0c4a6e', marginTop: 2, lineHeight: 1.4, fontSize: 11 }}>
                      I hereby certify that I have thoroughly read, understood, and agreed to adhere to the requirements, principles, and obligations outlined in <strong>{readingPolicy.title}</strong>. I acknowledge that non-compliance is subject to disciplinary action per CCCRN governance rules.
                    </div>
                  </div>
                </label>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button className="btn btn-outline" onClick={() => setReadingPolicy(null)}>
                  Close
                </button>
                {!readingPolicy.userAcknowledged && (
                  <button
                    className="btn btn-primary"
                    onClick={() => handleAcknowledge(readingPolicy.id)}
                  >
                    ✓ I Acknowledge & Comply
                  </button>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* MODAL: ADD / EDIT POLICY */}
      {showAddModal && (
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
                {editingPolicy ? `Edit Policy — ${editingPolicy.id}` : 'Publish New Institutional Policy'}
              </div>
              <button
                onClick={() => setShowAddModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleSavePolicy} style={{ padding: 20 }}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Policy Title:</label>
                <input
                  type="text"
                  placeholder="e.g. Conflict of Interest & Financial Transparency Policy"
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Category:</label>
                  <select
                    value={formData.category}
                    onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Governance & Ethics</option>
                    <option>Safeguarding</option>
                    <option>Grant Compliance</option>
                    <option>Information Security</option>
                    <option>Human Resources</option>
                    <option>Financial Management</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Version Tag:</label>
                  <input
                    type="text"
                    placeholder="e.g. v2.0"
                    value={formData.version}
                    onChange={(e) => setFormData({ ...formData, version: e.target.value })}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Policy Scope & Summary:</label>
                <textarea
                  placeholder="Summarize key compliance mandates and enforceable rules..."
                  value={formData.summary}
                  onChange={(e) => setFormData({ ...formData, summary: e.target.value })}
                  required
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Attach PDF Document:</label>
                <input type="file" style={{ width: '100%', padding: 4, fontSize: 11, marginTop: 4 }} />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowAddModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  {editingPolicy ? 'Update Policy' : 'Publish Policy'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
