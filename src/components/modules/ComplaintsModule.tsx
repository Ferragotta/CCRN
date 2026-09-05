import React, { useState } from 'react';
import { useToast } from '../../context/ToastContext';
import { useAuth } from '../../context/AuthContext';
import { Complaint } from '../../types';

export const ComplaintsModule: React.FC = () => {
  const { currentUser, isDocAdmin, setActiveModule } = useAuth();
  const { showSuccess, showError } = useToast();
  const [activeTab, setActiveTab] = useState<'all' | 'Open' | 'In Progress' | 'Closed'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [showLogModal, setShowLogModal] = useState(false);
  const [viewingComplaint, setViewingComplaint] = useState<Complaint | null>(null);
  const [convertCapComplaint, setConvertCapComplaint] = useState<Complaint | null>(null);
  const [capIssueText, setCapIssueText] = useState('');
  const [capResponsible, setCapResponsible] = useState('');
  const [capDeadline, setCapDeadline] = useState('');

  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isHR = currentUser?.key === 'hr';
  const isStaff = currentUser?.key === 'staff';

  // Seed complaints data
  const [complaints, setComplaints] = useState<(Complaint & { loggedByEmail?: string })[]>([
    { id: 'CMP-048', date: '28 Feb 2026', state: 'Kano — Cluster B', category: 'Fraud', severity: 'Critical', source: 'Whistleblower', alleged: 'Finance Assistant', status: 'Open', loggedByEmail: 'staff@cccrn.org' },
    { id: 'CMP-047', date: '25 Feb 2026', state: 'Lagos — Cluster A', category: 'Misconduct', severity: 'High', source: 'Staff', alleged: 'Field Officer', status: 'In Progress', loggedByEmail: 'staff@cccrn.org' },
    { id: 'CMP-046', date: '22 Feb 2026', state: 'Rivers — Cluster C', category: 'Policy Breach', severity: 'Medium', source: 'Audit', alleged: 'Procurement Committee', status: 'Converted to CAP', loggedByEmail: 'other@cccrn.org' },
    { id: 'CMP-045', date: '19 Feb 2026', state: 'Abuja FCT', category: 'Safety Violation', severity: 'Low', source: 'Direct', alleged: 'Facility Supervisor', status: 'Closed', loggedByEmail: 'other@cccrn.org' },
    { id: 'CMP-044', date: '15 Feb 2026', state: 'Kaduna', category: 'Financial Irregularity', severity: 'High', source: 'Field Audit', alleged: 'State Coordinator', status: 'In Progress', loggedByEmail: 'other@cccrn.org' },
    { id: 'CMP-043', date: '10 Feb 2026', state: 'Borno', category: 'Harassment/PSEA', severity: 'Critical', source: 'Whistleblower', alleged: 'Anonymous Staff', status: 'Open', loggedByEmail: 'staff@cccrn.org' }
  ]);

  // Form State for Log Complaint Modal
  const [formData, setFormData] = useState({
    name: '',
    contact: '',
    state: 'Lagos — Cluster A',
    category: 'Fraud',
    severity: 'Medium' as 'Critical' | 'High' | 'Medium' | 'Low',
    source: 'Whistleblower',
    alleged: '',
    desc: ''
  });

  // Filter complaints based on User Matrix:
  // - Staff sees their own complaint history & status
  // - HR, Compliance Officer, and DoC see all complaints
  const visibleComplaints = isStaff
    ? complaints.filter(c => c.loggedByEmail === currentUser?.email || c.loggedByEmail === 'staff@cccrn.org')
    : complaints;

  // Tab counters
  const countAll = visibleComplaints.length;
  const countOpen = visibleComplaints.filter(c => c.status === 'Open').length;
  const countProgress = visibleComplaints.filter(c => c.status === 'In Progress').length;
  const countClosed = visibleComplaints.filter(c => c.status === 'Closed' || c.status === 'Converted to CAP').length;

  // Filter complaints by active tab & search
  const filtered = visibleComplaints.filter(c => {
    const matchesTab = activeTab === 'all'
      ? true
      : activeTab === 'Closed'
      ? (c.status === 'Closed' || c.status === 'Converted to CAP')
      : c.status === activeTab;

    const matchesSearch = searchQuery === '' ||
      c.id.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.state.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.category.toLowerCase().includes(searchQuery.toLowerCase()) ||
      (c.alleged && c.alleged.toLowerCase().includes(searchQuery.toLowerCase()));

    return matchesTab && matchesSearch;
  });

  const handleStatusChange = (id: string, newStatus: string) => {
    if (newStatus === 'investigate') {
      showSuccess('Investigation Initiated', `Escalated complaint ${id} to the Investigation Hub.`);
      setComplaints(complaints.map(c => c.id === id ? { ...c, status: 'In Progress' } : c));
      setActiveModule('investigations');
      return;
    }
    setComplaints(complaints.map(c => c.id === id ? { ...c, status: newStatus as any } : c));
    showSuccess('Status Updated', `Complaint ${id} status set to ${newStatus}.`);
  };

  const handleOpenCapModal = (c: Complaint) => {
    setConvertCapComplaint(c);
    setCapIssueText(`Remediation required for complaint ${c.id} (${c.category}) in ${c.state}: Allegations against ${c.alleged}.`);
    setCapResponsible(c.alleged || 'State Coordinator');
    const d = new Date();
    d.setDate(d.getDate() + 14);
    setCapDeadline(d.toISOString().split('T')[0]);
  };

  const handleConfirmConvertCap = (e: React.FormEvent) => {
    e.preventDefault();
    if (!convertCapComplaint) return;
    setComplaints(complaints.map(c => c.id === convertCapComplaint.id ? { ...c, status: 'Converted to CAP' } : c));
    showSuccess('CAP Generated', `Complaint ${convertCapComplaint.id} converted to CAP.`);
    setConvertCapComplaint(null);
    setActiveModule('cap');
  };

  const handleDelete = (id: string) => {
    if (!isDocAdmin) {
      showError('Access Restricted', 'Only the Director of Compliance has permission to delete complaints.');
      return;
    }
    if (!confirm(`Permanently delete complaint ${id}? This action is audit-logged.`)) return;
    setComplaints(complaints.filter(c => c.id !== id));
  };

  const handleLogSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newId = 'CMP-0' + (49 + complaints.length);
    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

    const newEntry = {
      id: newId,
      date: today,
      state: formData.state,
      category: formData.category,
      severity: formData.severity,
      source: formData.source,
      alleged: formData.alleged || '—',
      status: 'Open' as const,
      loggedByEmail: currentUser?.email || 'staff@cccrn.org'
    };

    setComplaints([newEntry, ...complaints]);
    setShowLogModal(false);
    setFormData({
      name: '',
      contact: '',
      state: 'Lagos — Cluster A',
      category: 'Fraud',
      severity: 'Medium',
      source: 'Whistleblower',
      alleged: '',
      desc: ''
    });
    showSuccess('Complaint Registered', `Complaint ${newId} registered successfully.`);
  };

  const getSevPillClass = (sev: string) => {
    switch (sev) {
      case 'Critical': return 'pill-open';
      case 'High': return 'pill-open';
      case 'Medium': return 'pill-progress';
      default: return 'pill-closed';
    }
  };

  const getStatusPillClass = (st: string) => {
    switch (st) {
      case 'Open': return 'pill-open';
      case 'In Progress': return 'pill-progress';
      case 'Converted to CAP': return 'pill-closed';
      default: return 'pill-closed';
    }
  };

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* Sub-heading & Sub-desc per wireframe */}
      <div style={{ marginBottom: 16 }}>
        <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
          Complaints Management
        </h2>
        <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
          Receive, track and resolve compliance complaints from all states and clusters
        </p>

        {/* Role Access Scope Badges */}
        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-check"></i> <strong>Staff Portal:</strong> Showing your personal grievance history and live resolution tracker.
          </div>
        )}
        {isHR && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-eye"></i> <strong>HR Access:</strong> View-only monitoring across all institutional complaints.
          </div>
        )}
        {isComplianceOfficer && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-shield"></i> <strong>Compliance Officer:</strong> Triage, investigation conversion, and CAP creation enabled. (Delete disabled).
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Administrative Authority · Triage, CAP Conversion, and Admin Deletion.
          </div>
        )}
      </div>

      {/* TABS matching wireframe */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 16 }}>
        <div
          className={`tab ${activeTab === 'all' ? 'active' : ''}`}
          onClick={() => setActiveTab('all')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'all' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'all' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          All ({countAll})
        </div>
        <div
          className={`tab ${activeTab === 'Open' ? 'active' : ''}`}
          onClick={() => setActiveTab('Open')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'Open' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'Open' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          Open ({countOpen})
        </div>
        <div
          className={`tab ${activeTab === 'In Progress' ? 'active' : ''}`}
          onClick={() => setActiveTab('In Progress')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'In Progress' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'In Progress' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          In Progress ({countProgress})
        </div>
        <div
          className={`tab ${activeTab === 'Closed' ? 'active' : ''}`}
          onClick={() => setActiveTab('Closed')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'Closed' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'Closed' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          Closed ({countClosed})
        </div>
      </div>

      {/* COMPLAINTS CARD & REGISTER TABLE */}
      <div className="card">
        <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <div className="card-title" style={{ margin: 0 }}>
              <i className="fa-solid fa-inbox" style={{ color: 'var(--accent)' }}></i> {isStaff ? 'My Registered Complaints' : 'Complaints Register'}
            </div>
            {/* Live Search Bar */}
            <div style={{ position: 'relative' }}>
              <i className="fa-solid fa-magnifying-glass" style={{ position: 'absolute', left: 10, top: 8, color: 'var(--text-muted)', fontSize: 11 }}></i>
              <input
                type="text"
                placeholder="Search ref, state, category..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                style={{
                  padding: '4px 10px 4px 28px',
                  fontSize: 11,
                  border: '1px solid var(--border)',
                  borderRadius: 6,
                  background: 'var(--surface2)',
                  outline: 'none',
                  width: 200
                }}
              />
            </div>
          </div>

          {/* All Staff, Compliance Officer, and DoC can log complaints */}
          {!isHR && (
            <button className="btn btn-primary" onClick={() => setShowLogModal(true)}>
              <i className="fa-solid fa-plus"></i> Log Complaint
            </button>
          )}
        </div>

        <table>
          <thead>
            <tr>
              <th>Ref#</th>
              <th>Date</th>
              <th>State/Cluster</th>
              <th>Category</th>
              <th>Severity</th>
              <th>Source</th>
              <th>Alleged Party</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {filtered.length === 0 ? (
              <tr>
                <td colSpan={9} style={{ textAlign: 'center', padding: 30, color: 'var(--text-muted)' }}>
                  No complaints found in this category.
                </td>
              </tr>
            ) : (
              filtered.map((c) => (
                <tr key={c.id}>
                  <td style={{ color: 'var(--accent)', fontWeight: 700 }}>{c.id}</td>
                  <td>{c.date}</td>
                  <td>{c.state}</td>
                  <td>{c.category}</td>
                  <td><span className={`pill ${getSevPillClass(c.severity)}`}>{c.severity}</span></td>
                  <td><span className="pill pill-progress" style={{ fontSize: 10 }}>{c.source || '—'}</span></td>
                  <td style={{ fontSize: 11, maxWidth: 120, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                    {c.alleged || '—'}
                  </td>
                  <td><span className={`pill ${getStatusPillClass(c.status)}`}>{c.status}</span></td>
                  <td>
                    <div style={{ display: 'flex', gap: 4, flexWrap: 'wrap', alignItems: 'center' }}>
                      {/* View button */}
                      <button
                        className="btn btn-outline"
                        style={{ padding: '3px 8px', fontSize: 10 }}
                        onClick={() => setViewingComplaint(c)}
                      >
                        👁️ View
                      </button>

                      {/* Triage action dropdown: Compliance Officer & DoC only */}
                      {(isDocAdmin || isComplianceOfficer) && (
                        <select
                          value=""
                          onChange={(e) => {
                            if (e.target.value) handleStatusChange(c.id, e.target.value);
                          }}
                          style={{
                            padding: '3px 6px',
                            fontSize: 10,
                            border: '1px solid var(--border)',
                            borderRadius: 6,
                            background: 'var(--surface)',
                            color: 'var(--text)',
                            cursor: 'pointer'
                          }}
                        >
                          <option value="">Action ▾</option>
                          <option value="Open">Set Open</option>
                          <option value="In Progress">Set In Progress</option>
                          <option value="Closed">Set Closed</option>
                          <option value="investigate">🔐 Convert to Investigation</option>
                        </select>
                      )}

                      {/* Convert to CAP button: Compliance Officer & DoC only */}
                      {(isDocAdmin || isComplianceOfficer) && c.status !== 'Converted to CAP' && (
                        <button
                          className="btn btn-primary"
                          style={{ padding: '3px 8px', fontSize: 10, whiteSpace: 'nowrap' }}
                          onClick={() => handleOpenCapModal(c)}
                        >
                          + CAP
                        </button>
                      )}

                      {/* Delete button: DoC ONLY per matrix */}
                      {isDocAdmin && (
                        <button
                          className="btn btn-outline"
                          style={{ padding: '3px 7px', fontSize: 10, color: 'var(--danger)', borderColor: 'var(--danger)' }}
                          title="Admin Delete Authority (DoC only)"
                          onClick={() => handleDelete(c.id)}
                        >
                          🗑️
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* MODAL: LOG NEW COMPLAINT */}
      {showLogModal && (
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
            width: 600,
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
                Log Compliance Complaint
              </div>
              <button
                onClick={() => setShowLogModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleLogSubmit} style={{ padding: 20 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Complainant Name:</label>
                  <input
                    type="text"
                    placeholder="Full name (or 'Anonymous')"
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Contact:</label>
                  <input
                    type="text"
                    placeholder="Email or phone"
                    value={formData.contact}
                    onChange={(e) => setFormData({ ...formData, contact: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>State / Cluster:</label>
                  <select
                    value={formData.state}
                    onChange={(e) => setFormData({ ...formData, state: e.target.value })}
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
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Category:</label>
                  <select
                    value={formData.category}
                    onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Fraud</option>
                    <option>Misconduct</option>
                    <option>Policy Breach</option>
                    <option>Safety Violation</option>
                    <option>Harassment/PSEA</option>
                    <option>Financial Irregularity</option>
                    <option>Corruption</option>
                  </select>
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Severity:</label>
                  <select
                    value={formData.severity}
                    onChange={(e) => setFormData({ ...formData, severity: e.target.value as any })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Low</option>
                    <option>Medium</option>
                    <option>High</option>
                    <option>Critical</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Source:</label>
                  <select
                    value={formData.source}
                    onChange={(e) => setFormData({ ...formData, source: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Whistleblower</option>
                    <option>Staff</option>
                    <option>Audit</option>
                    <option>Client</option>
                    <option>External</option>
                  </select>
                </div>
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>
                  Alleged Party <span style={{ fontStyle: 'italic', fontWeight: 400 }}>(Individual or Department)</span>:
                </label>
                <input
                  type="text"
                  placeholder="e.g. John Doe or Finance Department"
                  value={formData.alleged}
                  onChange={(e) => setFormData({ ...formData, alleged: e.target.value })}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Description:</label>
                <textarea
                  placeholder="Detailed description of the complaint..."
                  value={formData.desc}
                  onChange={(e) => setFormData({ ...formData, desc: e.target.value })}
                  style={{ width: '100%', minHeight: 70, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Attach Evidence (Optional):</label>
                <input type="file" style={{ width: '100%', padding: 6, fontSize: 11, marginTop: 4 }} />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowLogModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Submit Complaint
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: CONVERT TO CAP */}
      {convertCapComplaint && (
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
                Convert Complaint {convertCapComplaint.id} to CAP
              </div>
              <button
                onClick={() => setConvertCapComplaint(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleConfirmConvertCap} style={{ padding: 20 }}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Linked Issue Description:</label>
                <textarea
                  value={capIssueText}
                  onChange={(e) => setCapIssueText(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 70, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 16 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Responsible Lead / State:</label>
                  <input
                    type="text"
                    value={capResponsible}
                    onChange={(e) => setCapResponsible(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Remediation Deadline:</label>
                  <input
                    type="date"
                    value={capDeadline}
                    onChange={(e) => setCapDeadline(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setConvertCapComplaint(null)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Create Linked CAP
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: VIEW COMPLAINT DOSSIER */}
      {viewingComplaint && (
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
                Complaint Dossier — {viewingComplaint.id}
              </div>
              <button
                onClick={() => setViewingComplaint(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <div style={{ padding: 20, fontSize: 12 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 14 }}>
                <div><strong>Registration Date:</strong> {viewingComplaint.date}</div>
                <div><strong>State / Cluster:</strong> {viewingComplaint.state}</div>
                <div><strong>Category:</strong> {viewingComplaint.category}</div>
                <div><strong>Severity:</strong> <span className={`pill ${getSevPillClass(viewingComplaint.severity)}`}>{viewingComplaint.severity}</span></div>
                <div><strong>Source:</strong> {viewingComplaint.source}</div>
                <div><strong>Status:</strong> <span className={`pill ${getStatusPillClass(viewingComplaint.status)}`}>{viewingComplaint.status}</span></div>
              </div>

              <div style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, border: '1px solid var(--border)', marginBottom: 14 }}>
                <strong>Alleged Party:</strong> {viewingComplaint.alleged || 'Not Specified'}
              </div>

              <div style={{ marginBottom: 14 }}>
                <strong>Incident Scope & Description:</strong>
                <p style={{ color: 'var(--text-muted)', marginTop: 4, lineHeight: 1.5 }}>
                  Detailed compliance report lodged regarding potential irregularities in field reporting and internal control adherence for {viewingComplaint.state}.
                </p>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button className="btn btn-outline" onClick={() => setViewingComplaint(null)}>
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
