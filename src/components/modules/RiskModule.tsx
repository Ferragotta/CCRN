import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export interface RiskItem {
  id: string;
  description: string;
  category: string;
  likelihood: number; // 1-5
  impact: number;     // 1-5
  rating: number;     // likelihood * impact
  owner: string;
  status: 'Open' | 'Mitigated' | 'Monitoring' | 'Closed';
  mitigation: string;
  stateScope: string;
  reviewDate: string;
}

export const RiskModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();

  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isHR = currentUser?.key === 'hr';
  const isStaff = currentUser?.key === 'staff';

  // Seed Risk Data matching wireframe
  const [risks, setRisks] = useState<RiskItem[]>([
    {
      id: 'RSK-019',
      description: 'Foreign exchange volatility and delayed vendor currency reconciliation impacting commodity procurement',
      category: 'Financial',
      likelihood: 4,
      impact: 5,
      rating: 20,
      owner: 'Director of Finance',
      status: 'Open',
      mitigation: 'Implement forward contract hedging and dual-currency escrow accounts with primary commercial banks.',
      stateScope: 'National HQ / All States',
      reviewDate: '15 Mar 2026'
    },
    {
      id: 'RSK-018',
      description: 'Single signatory risk during emergency field community outreach testing in remote clusters',
      category: 'Procurement',
      likelihood: 4,
      impact: 4,
      rating: 16,
      owner: 'Compliance Specialist',
      status: 'Monitoring',
      mitigation: 'Enforce mobile app digital secondary authorization token prior to cash disbursement.',
      stateScope: 'Kano & Kaduna',
      reviewDate: '30 Mar 2026'
    },
    {
      id: 'RSK-017',
      description: 'Non-compliance with mandatory safeguarding/PSEA recertification for ad-hoc facility volunteers',
      category: 'PSEA / Human Rights',
      likelihood: 3,
      impact: 4,
      rating: 12,
      owner: 'HR Lead & Safeguarding Focal Point',
      status: 'Open',
      mitigation: 'Auto-block facility access and timesheet approval for staff with expired certificates.',
      stateScope: 'All States',
      reviewDate: '25 Mar 2026'
    },
    {
      id: 'RSK-016',
      description: 'Potential data breach or loss of physical clinical patient registers during clinic facility renovation',
      category: 'Data Security',
      likelihood: 2,
      impact: 4,
      rating: 8,
      owner: 'IT & Strategic Information Lead',
      status: 'Mitigated',
      mitigation: 'Secure biometric locker storage and mandatory end-of-day encrypted cloud synchronization.',
      stateScope: 'Lagos & Rivers',
      reviewDate: '20 Feb 2026'
    },
    {
      id: 'RSK-015',
      description: 'Supply chain stock-out of viral load reagent cartridges due to port customs clearance delays',
      category: 'Logistics',
      likelihood: 2,
      impact: 3,
      rating: 6,
      owner: 'Supply Chain Associate',
      status: 'Closed',
      mitigation: 'Established 3-month regional buffer inventory repository in Abuja central warehouse.',
      stateScope: 'Borno & Abuja FCT',
      reviewDate: '10 Feb 2026'
    }
  ]);

  // Form & Filter States
  const [activeFilter, setActiveFilter] = useState<'all' | 'high' | 'medium' | 'low' | 'mitigated'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [showAddModal, setShowAddModal] = useState(false);
  const [editingRisk, setEditingRisk] = useState<RiskItem | null>(null);
  const [viewingRisk, setViewingRisk] = useState<RiskItem | null>(null);

  // Add / Edit Form State
  const [formData, setFormData] = useState({
    description: '',
    category: 'Financial',
    likelihood: 3,
    impact: 3,
    owner: '',
    status: 'Open' as 'Open' | 'Mitigated' | 'Monitoring' | 'Closed',
    mitigation: '',
    stateScope: 'All States',
    reviewDate: ''
  });

  // Guard: Strictly block Staff and HR per user matrix
  if (isStaff || isHR) {
    return (
      <div className="card" style={{ textAlign: 'center', padding: 40, background: 'var(--danger-light, #fee2e2)', border: '1px solid #fca5a5' }}>
        <i className="fa-solid fa-shield-halved" style={{ fontSize: 32, color: 'var(--danger, #dc2626)', marginBottom: 12 }}></i>
        <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--danger, #dc2626)', marginBottom: 6 }}>
          Restricted Access: Risk Register
        </h3>
        <p style={{ fontSize: 13, color: 'var(--text-dim)' }}>
          Access to the ISO 31000 Risk Register is restricted to the Compliance Officer and Director of Compliance.
        </p>
      </div>
    );
  }

  const handleOpenAdd = () => {
    setFormData({
      description: '',
      category: 'Financial',
      likelihood: 3,
      impact: 3,
      owner: '',
      status: 'Open',
      mitigation: '',
      stateScope: 'All States',
      reviewDate: ''
    });
    setEditingRisk(null);
    setShowAddModal(true);
  };

  const handleOpenEdit = (r: RiskItem) => {
    setFormData({
      description: r.description,
      category: r.category,
      likelihood: r.likelihood,
      impact: r.impact,
      owner: r.owner,
      status: r.status,
      mitigation: r.mitigation,
      stateScope: r.stateScope,
      reviewDate: r.reviewDate
    });
    setEditingRisk(r);
    setShowAddModal(true);
  };

  const handleSaveRisk = (e: React.FormEvent) => {
    e.preventDefault();
    const calculatedRating = formData.likelihood * formData.impact;

    if (editingRisk) {
      setRisks(risks.map(r => r.id === editingRisk.id ? { ...r, ...formData, rating: calculatedRating } : r));
      alert(`Risk record ${editingRisk.id} successfully updated.`);
    } else {
      const newId = 'RSK-0' + (20 + risks.length);
      const newRisk: RiskItem = {
        id: newId,
        ...formData,
        rating: calculatedRating
      };
      setRisks([newRisk, ...risks]);
      alert(`Risk ${newId} successfully registered in ISO 31000 ledger.`);
    }
    setShowAddModal(false);
  };

  const handleDeleteRisk = (id: string) => {
    if (!isDocAdmin) {
      alert('Action Restricted: Compliance Officers do not have permission to delete Risk records. Only the Director of Compliance has delete authority.');
      return;
    }
    if (!confirm(`Permanently delete risk ${id}? This deletion is logged in the compliance audit trail.`)) return;
    setRisks(risks.filter(r => r.id !== id));
  };

  const getRatingBadge = (rating: number) => {
    if (rating >= 15) return <span className="pill pill-open" style={{ fontWeight: 800 }}>Critical ({rating})</span>;
    if (rating >= 10) return <span className="pill pill-open" style={{ fontWeight: 700 }}>High ({rating})</span>;
    if (rating >= 5) return <span className="pill pill-progress" style={{ fontWeight: 700 }}>Medium ({rating})</span>;
    return <span className="pill pill-closed" style={{ fontWeight: 700 }}>Low ({rating})</span>;
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case 'Open': return <span className="pill pill-open">Open</span>;
      case 'Monitoring': return <span className="pill pill-progress">Monitoring</span>;
      case 'Mitigated': return <span className="pill pill-closed">Mitigated</span>;
      default: return <span className="pill pill-closed">Closed</span>;
    }
  };

  // Filtered risks
  const filteredRisks = risks.filter(r => {
    const matchesFilter = activeFilter === 'all'
      ? true
      : activeFilter === 'high'
      ? r.rating >= 10
      : activeFilter === 'medium'
      ? (r.rating >= 5 && r.rating < 10)
      : activeFilter === 'low'
      ? r.rating < 5
      : (r.status === 'Mitigated' || r.status === 'Closed');

    const matchesSearch = searchQuery === '' ||
      r.id.toLowerCase().includes(searchQuery.toLowerCase()) ||
      r.description.toLowerCase().includes(searchQuery.toLowerCase()) ||
      r.category.toLowerCase().includes(searchQuery.toLowerCase()) ||
      r.owner.toLowerCase().includes(searchQuery.toLowerCase());

    return matchesFilter && matchesSearch;
  });

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
              Risk Register (ISO 31000 Framework)
            </h2>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
              Identify, assess, and manage compliance and operational risks across all grant activities
            </p>
          </div>

          <button className="btn btn-primary" onClick={handleOpenAdd}>
            <i className="fa-solid fa-plus"></i> Add Risk
          </button>
        </div>

        {/* ROLE MATRIX SCOPE BADGES */}
        {isComplianceOfficer && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-shield"></i> <strong>Compliance Officer:</strong> Full Risk Assessment & Mitigation Management. (Delete disabled).
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> All Access · Add, Edit, Set Mitigation Strategies, and Admin Delete.
          </div>
        )}
      </div>

      {/* 5x5 RISK HEAT MAP OVERVIEW CARD */}
      <div className="card" style={{ marginBottom: 20 }}>
        <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div className="card-title" style={{ margin: 0 }}>
            <i className="fa-solid fa-fire" style={{ color: 'var(--danger)' }}></i> ISO 31000 $5 	imes 5$ Risk Heat Map Matrix
          </div>
          <span style={{ fontSize: 11, color: 'var(--text-muted)' }}>
            Formula: Likelihood (1–5) $	imes$ Impact (1–5) = Rating (1–25)
          </span>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(5, 1fr)', gap: 8, marginTop: 10 }}>
          <div style={{ background: '#fee2e2', border: '1px solid #fca5a5', borderRadius: 8, padding: 12, textAlign: 'center' }}>
            <div style={{ fontSize: 11, fontWeight: 700, color: '#991b1b' }}>Critical (15–25)</div>
            <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 24, fontWeight: 800, color: '#dc2626', marginTop: 4 }}>
              {risks.filter(r => r.rating >= 15).length}
            </div>
            <div style={{ fontSize: 10, color: '#7f1d1d' }}>Immediate Mitigation</div>
          </div>

          <div style={{ background: '#ffedd5', border: '1px solid #fed7aa', borderRadius: 8, padding: 12, textAlign: 'center' }}>
            <div style={{ fontSize: 11, fontWeight: 700, color: '#9a3412' }}>High (10–14)</div>
            <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 24, fontWeight: 800, color: '#ea580c', marginTop: 4 }}>
              {risks.filter(r => r.rating >= 10 && r.rating < 15).length}
            </div>
            <div style={{ fontSize: 10, color: '#7c2d12' }}>Action Plan Required</div>
          </div>

          <div style={{ background: '#fef3c7', border: '1px solid #fde68a', borderRadius: 8, padding: 12, textAlign: 'center' }}>
            <div style={{ fontSize: 11, fontWeight: 700, color: '#92400e' }}>Medium (5–9)</div>
            <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 24, fontWeight: 800, color: '#d97706', marginTop: 4 }}>
              {risks.filter(r => r.rating >= 5 && r.rating < 10).length}
            </div>
            <div style={{ fontSize: 10, color: '#78350f' }}>Active Monitoring</div>
          </div>

          <div style={{ background: '#dcfce7', border: '1px solid #86efac', borderRadius: 8, padding: 12, textAlign: 'center' }}>
            <div style={{ fontSize: 11, fontWeight: 700, color: '#166534' }}>Low (1–4)</div>
            <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 24, fontWeight: 800, color: '#16a34a', marginTop: 4 }}>
              {risks.filter(r => r.rating < 5).length}
            </div>
            <div style={{ fontSize: 10, color: '#14532d' }}>Acceptable Risk</div>
          </div>

          <div style={{ background: 'var(--surface2)', border: '1px solid var(--border)', borderRadius: 8, padding: 12, textAlign: 'center' }}>
            <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-dim)' }}>Mitigated / Closed</div>
            <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 24, fontWeight: 800, color: 'var(--accent)', marginTop: 4 }}>
              {risks.filter(r => r.status === 'Mitigated' || r.status === 'Closed').length}
            </div>
            <div style={{ fontSize: 10, color: 'var(--text-muted)' }}>Resolved Controls</div>
          </div>
        </div>
      </div>

      {/* FILTER TABS & SEARCH */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 14, flexWrap: 'wrap', gap: 10 }}>
        <div style={{ display: 'flex', gap: 6 }}>
          <button
            onClick={() => setActiveFilter('all')}
            style={{
              padding: '6px 12px', fontSize: 11, fontWeight: 600, borderRadius: 'var(--radius-sm)',
              background: activeFilter === 'all' ? 'var(--accent)' : 'var(--surface)',
              color: activeFilter === 'all' ? '#fff' : 'var(--text-dim)',
              border: '1px solid var(--border)', cursor: 'pointer'
            }}
          >
            All Risks ({risks.length})
          </button>
          <button
            onClick={() => setActiveFilter('high')}
            style={{
              padding: '6px 12px', fontSize: 11, fontWeight: 600, borderRadius: 'var(--radius-sm)',
              background: activeFilter === 'high' ? 'var(--danger)' : 'var(--surface)',
              color: activeFilter === 'high' ? '#fff' : 'var(--danger)',
              border: '1px solid var(--border)', cursor: 'pointer'
            }}
          >
            Critical & High
          </button>
          <button
            onClick={() => setActiveFilter('medium')}
            style={{
              padding: '6px 12px', fontSize: 11, fontWeight: 600, borderRadius: 'var(--radius-sm)',
              background: activeFilter === 'medium' ? 'var(--warning)' : 'var(--surface)',
              color: activeFilter === 'medium' ? '#fff' : '#b45309',
              border: '1px solid var(--border)', cursor: 'pointer'
            }}
          >
            Medium
          </button>
          <button
            onClick={() => setActiveFilter('mitigated')}
            style={{
              padding: '6px 12px', fontSize: 11, fontWeight: 600, borderRadius: 'var(--radius-sm)',
              background: activeFilter === 'mitigated' ? 'var(--success)' : 'var(--surface)',
              color: activeFilter === 'mitigated' ? '#fff' : 'var(--success)',
              border: '1px solid var(--border)', cursor: 'pointer'
            }}
          >
            Mitigated / Closed
          </button>
        </div>

        {/* Live Search */}
        <div style={{ position: 'relative' }}>
          <i className="fa-solid fa-magnifying-glass" style={{ position: 'absolute', left: 10, top: 8, color: 'var(--text-muted)', fontSize: 11 }}></i>
          <input
            type="text"
            placeholder="Search risk ID, description, owner..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            style={{
              padding: '5px 10px 5px 28px',
              fontSize: 11,
              border: '1px solid var(--border)',
              borderRadius: 6,
              background: 'var(--surface2)',
              outline: 'none',
              width: 220
            }}
          />
        </div>
      </div>

      {/* ACTIVE RISK REGISTER TABLE */}
      <div className="card">
        <table>
          <thead>
            <tr>
              <th>Risk ID</th>
              <th>Description</th>
              <th>Category</th>
              <th>Likelihood</th>
              <th>Impact</th>
              <th>Rating</th>
              <th>Owner</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {filteredRisks.length === 0 ? (
              <tr>
                <td colSpan={9} style={{ textAlign: 'center', padding: 24, color: 'var(--text-muted)' }}>
                  No risks found in this filter category.
                </td>
              </tr>
            ) : (
              filteredRisks.map((r) => (
                <tr key={r.id}>
                  <td style={{ color: 'var(--accent)', fontWeight: 700 }}>{r.id}</td>
                  <td style={{ fontSize: 11, maxWidth: 220, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }} title={r.description}>
                    {r.description}
                  </td>
                  <td>{r.category}</td>
                  <td style={{ textAlign: 'center', fontWeight: 600 }}>{r.likelihood}/5</td>
                  <td style={{ textAlign: 'center', fontWeight: 600 }}>{r.impact}/5</td>
                  <td>{getRatingBadge(r.rating)}</td>
                  <td style={{ fontSize: 11 }}>{r.owner}</td>
                  <td>{getStatusBadge(r.status)}</td>
                  <td>
                    <div style={{ display: 'flex', gap: 4, alignItems: 'center' }}>
                      <button
                        className="btn btn-outline btn-sm"
                        style={{ padding: '2px 6px', fontSize: 10 }}
                        onClick={() => setViewingRisk(r)}
                        title="View Full Risk Details"
                      >
                        👁️
                      </button>
                      <button
                        className="btn btn-outline btn-sm"
                        style={{ padding: '2px 6px', fontSize: 10 }}
                        onClick={() => handleOpenEdit(r)}
                        title="Edit Risk"
                      >
                        ✏️
                      </button>
                      {/* Delete: DoC ONLY (Compliance Officer cannot delete) */}
                      {isDocAdmin && (
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ padding: '2px 6px', fontSize: 10, color: 'var(--danger)', borderColor: 'var(--danger)' }}
                          onClick={() => handleDeleteRisk(r.id)}
                          title="Admin Delete Risk (DoC only)"
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

      {/* MODAL: ADD / EDIT RISK */}
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
                {editingRisk ? `Edit Risk — ${editingRisk.id}` : 'Add New ISO 31000 Compliance Risk'}
              </div>
              <button
                onClick={() => setShowAddModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleSaveRisk} style={{ padding: 20 }}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Risk Description & Event:</label>
                <textarea
                  placeholder="Describe the potential risk event and vulnerabilities..."
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  required
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Risk Category:</label>
                  <select
                    value={formData.category}
                    onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Financial</option>
                    <option>Procurement</option>
                    <option>PSEA / Human Rights</option>
                    <option>Data Security</option>
                    <option>Logistics & Supply</option>
                    <option>Grant Compliance</option>
                    <option>Legal / Regulatory</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>State / Geographic Scope:</label>
                  <select
                    value={formData.stateScope}
                    onChange={(e) => setFormData({ ...formData, stateScope: e.target.value })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>All States</option>
                    <option>Lagos</option>
                    <option>Kano</option>
                    <option>Rivers</option>
                    <option>Abuja FCT</option>
                    <option>Kaduna</option>
                    <option>Borno</option>
                  </select>
                </div>
              </div>

              {/* Likelihood & Impact 5x5 sliders */}
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 12, marginBottom: 12, background: 'var(--surface2)', padding: 12, borderRadius: 8 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Likelihood (1–5):</label>
                  <select
                    value={formData.likelihood}
                    onChange={(e) => setFormData({ ...formData, likelihood: parseInt(e.target.value) || 1 })}
                    style={{ width: '100%', padding: '6px 8px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option value={1}>1 — Rare</option>
                    <option value={2}>2 — Unlikely</option>
                    <option value={3}>3 — Moderate</option>
                    <option value={4}>4 — Likely</option>
                    <option value={5}>5 — Almost Certain</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Impact (1–5):</label>
                  <select
                    value={formData.impact}
                    onChange={(e) => setFormData({ ...formData, impact: parseInt(e.target.value) || 1 })}
                    style={{ width: '100%', padding: '6px 8px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option value={1}>1 — Negligible</option>
                    <option value={2}>2 — Minor</option>
                    <option value={3}>3 — Moderate</option>
                    <option value={4}>4 — Major</option>
                    <option value={5}>5 — Catastrophic</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Computed Rating:</label>
                  <div style={{ marginTop: 6 }}>
                    {getRatingBadge(formData.likelihood * formData.impact)}
                  </div>
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Risk Owner (Role / Lead):</label>
                  <input
                    type="text"
                    placeholder="e.g. Director of Finance"
                    value={formData.owner}
                    onChange={(e) => setFormData({ ...formData, owner: e.target.value })}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Risk Status:</label>
                  <select
                    value={formData.status}
                    onChange={(e) => setFormData({ ...formData, status: e.target.value as any })}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Open</option>
                    <option>Monitoring</option>
                    <option>Mitigated</option>
                    <option>Closed</option>
                  </select>
                </div>
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Mitigation Plan & Internal Controls:</label>
                <textarea
                  placeholder="Describe preventive and detective controls implemented..."
                  value={formData.mitigation}
                  onChange={(e) => setFormData({ ...formData, mitigation: e.target.value })}
                  required
                  style={{ width: '100%', minHeight: 60, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowAddModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  {editingRisk ? 'Update Risk Record' : 'Register Risk'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: VIEW RISK DETAILS */}
      {viewingRisk && (
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
                Risk Dossier — {viewingRisk.id}
              </div>
              <button
                onClick={() => setViewingRisk(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <div style={{ padding: 20, fontSize: 12 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 14 }}>
                <div><strong>Category:</strong> {viewingRisk.category}</div>
                <div><strong>Geographic Scope:</strong> {viewingRisk.stateScope}</div>
                <div><strong>Assigned Owner:</strong> {viewingRisk.owner}</div>
                <div><strong>Status:</strong> {getStatusBadge(viewingRisk.status)}</div>
                <div><strong>Likelihood $	imes$ Impact:</strong> {viewingRisk.likelihood} $	imes$ {viewingRisk.impact}</div>
                <div><strong>Risk Rating:</strong> {getRatingBadge(viewingRisk.rating)}</div>
              </div>

              <div style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, border: '1px solid var(--border)', marginBottom: 14 }}>
                <strong>Risk Event Description:</strong>
                <p style={{ marginTop: 4, color: 'var(--text-dim)', lineHeight: 1.4 }}>{viewingRisk.description}</p>
              </div>

              <div style={{ background: '#dbeafe', padding: 12, borderRadius: 8, border: '1px solid #bfdbfe', marginBottom: 14 }}>
                <strong style={{ color: 'var(--accent)' }}>🛡️ Control & Mitigation Strategy:</strong>
                <p style={{ marginTop: 4, color: '#1e3a8a', lineHeight: 1.4 }}>{viewingRisk.mitigation}</p>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                <button className="btn btn-outline" onClick={() => setViewingRisk(null)}>
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
