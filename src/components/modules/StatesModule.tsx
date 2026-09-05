import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export interface StateClusterItem {
  id: string;
  name: string;
  cluster: string;
  coordinator: string;
  facilitiesCount: number;
  activeCaps: number;
  openComplaints: number;
  riskLevel: 'Low' | 'Medium' | 'High' | 'Critical';
  complianceScore: number;
}

export interface FieldUpdateRecord {
  id: string;
  state: string;
  updateType: string;
  details: string;
  submittedBy: string;
  date: string;
  fileName?: string;
}

export const StatesModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();

  const isHR = currentUser?.key === 'hr';
  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isStaff = currentUser?.key === 'staff';

  // State Profiles Data matching wireframe
  const [statesList, setStatesList] = useState<StateClusterItem[]>([
    { id: 'ST-01', name: 'Lagos', cluster: 'Cluster A', coordinator: 'Dr. Grace Aliyu', facilitiesCount: 24, activeCaps: 1, openComplaints: 1, riskLevel: 'Low', complianceScore: 92 },
    { id: 'ST-02', name: 'Kano', cluster: 'Cluster B', coordinator: 'Ibrahim Haruna', facilitiesCount: 18, activeCaps: 2, openComplaints: 2, riskLevel: 'High', complianceScore: 68 },
    { id: 'ST-03', name: 'Rivers', cluster: 'Cluster C', coordinator: 'Tari Briggs', facilitiesCount: 15, activeCaps: 1, openComplaints: 0, riskLevel: 'Low', complianceScore: 94 },
    { id: 'ST-04', name: 'Abuja FCT', cluster: 'North-Central Hub', coordinator: 'Emeka Okafor', facilitiesCount: 20, activeCaps: 1, openComplaints: 1, riskLevel: 'Medium', complianceScore: 78 },
    { id: 'ST-05', name: 'Kaduna', cluster: 'Cluster D', coordinator: 'Bala Mohammed', facilitiesCount: 14, activeCaps: 2, openComplaints: 1, riskLevel: 'High', complianceScore: 62 },
    { id: 'ST-06', name: 'Borno', cluster: 'North-East Response', coordinator: 'Yusuf Bukar', facilitiesCount: 12, activeCaps: 3, openComplaints: 2, riskLevel: 'Critical', complianceScore: 55 }
  ]);

  // Field Updates Feed Data
  const [fieldUpdates, setFieldUpdates] = useState<FieldUpdateRecord[]>([
    { id: 'FU-101', state: 'Lagos — Cluster A', updateType: 'Compliance Activity', details: 'Completed monthly dual-signatory verification across 8 high-volume clinic sites with zero discrepancies.', submittedBy: 'Fatima Bello (STL)', date: '28 Feb 2026', fileName: 'Lagos_DualSig_Audit.pdf' },
    { id: 'FU-102', state: 'Kano — Cluster B', updateType: 'CAP Progress', details: 'Remediation underway for local procurement secondary signatories. Orientation session held with facility focal persons.', submittedBy: 'Ibrahim Haruna (STL)', date: '27 Feb 2026' },
    { id: 'FU-103', state: 'Rivers — Cluster C', updateType: 'Training Completed', details: '100% of facility focal persons completed Anti-Fraud and PSEA certification for Q1 FY2026.', submittedBy: 'Tari Briggs (STL)', date: '25 Feb 2026' },
    { id: 'FU-104', state: 'Borno', updateType: 'Risk Identified', details: 'Supply chain transit delays due to flood-damaged access routes near Monguno. Buffer stock requested.', submittedBy: 'Yusuf Bukar (STL)', date: '22 Feb 2026' }
  ]);

  // Modals & UI States
  const [showAddStateModal, setShowAddStateModal] = useState(false);
  const [selectedStateProfile, setSelectedStateProfile] = useState<StateClusterItem | null>(null);

  // Field Update Form State
  const [fuState, setFuState] = useState('Lagos — Cluster A');
  const [fuType, setFuType] = useState('Compliance Activity');
  const [fuDetails, setFuDetails] = useState('');
  const [fuBy, setFuBy] = useState(currentUser?.name || '');
  const [fuFileName, setFuFileName] = useState('');

  // Add State Form State
  const [newStateName, setNewStateName] = useState('');
  const [newClusterName, setNewClusterName] = useState('Cluster E');
  const [newCoordinator, setNewCoordinator] = useState('');
  const [newFacilitiesCount, setNewFacilitiesCount] = useState(10);

  const handleSubmitFieldUpdate = (e: React.FormEvent) => {
    e.preventDefault();
    if (!fuDetails.trim()) {
      alert('Please enter details for the field update.');
      return;
    }

    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    const newUpdate: FieldUpdateRecord = {
      id: 'FU-' + (105 + fieldUpdates.length),
      state: fuState,
      updateType: fuType,
      details: fuDetails,
      submittedBy: fuBy || currentUser?.name || 'State Team Lead',
      date: today,
      fileName: fuFileName || undefined
    };

    setFieldUpdates([newUpdate, ...fieldUpdates]);
    setFuDetails('');
    setFuFileName('');
    alert(`Field update for ${fuState} successfully logged and synchronized to Live Dashboard Stream.`);
  };

  const handleAddState = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newStateName.trim()) return;

    const newState: StateClusterItem = {
      id: 'ST-0' + (statesList.length + 1),
      name: newStateName,
      cluster: newClusterName,
      coordinator: newCoordinator || 'Assigned Lead',
      facilitiesCount: newFacilitiesCount,
      activeCaps: 0,
      openComplaints: 0,
      riskLevel: 'Low',
      complianceScore: 100
    };

    setStatesList([...statesList, newState]);
    setShowAddStateModal(false);
    setNewStateName('');
    alert(`State / Cluster "${newStateName}" successfully registered into institutional compliance matrix.`);
  };

  const handleDeleteState = (id: string) => {
    if (!isDocAdmin) {
      alert('Action Restricted: Only the Director of Compliance has authority to delete state cluster profiles.');
      return;
    }
    if (!confirm(`Permanently delete state profile ${id}?`)) return;
    setStatesList(statesList.filter(s => s.id !== id));
  };

  const getRiskBadge = (level: string) => {
    switch (level) {
      case 'Critical': return <span className="pill pill-open">Critical Risk</span>;
      case 'High': return <span className="pill pill-open">High Risk</span>;
      case 'Medium': return <span className="pill pill-progress">Medium Risk</span>;
      default: return <span className="pill pill-closed">Low Risk</span>;
    }
  };

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
              States & Clusters
            </h2>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
              Monitor compliance status and receive live field updates from all state teams
            </p>
          </div>

          {/* Add State Button: DoC ALL ACCESS ONLY */}
          {isDocAdmin && (
            <button className="btn btn-primary" onClick={() => setShowAddStateModal(true)}>
              <i className="fa-solid fa-plus"></i> Add State / Cluster
            </button>
          )}
        </div>

        {/* ROLE MATRIX SCOPE BADGE */}
        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-satellite-dish"></i> <strong>STL & Cluster Lead Portal:</strong> View state profiles and submit live field updates to Executive Command.
          </div>
        )}
        {isHR && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-eye"></i> <strong>HR Access:</strong> View-only access across all operational state clusters.
          </div>
        )}
        {isComplianceOfficer && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-eye"></i> <strong>Compliance Officer:</strong> View-only access to state risk levels, facilities, and field updates.
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> All Access · Add/Edit State Clusters, Delete Profiles, and Audit Live Field Feed.
          </div>
        )}
      </div>

      {/* 6 OPERATIONAL STATES GRID (matching wireframe) */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))', gap: 14, marginBottom: 20 }}>
        {statesList.map((st) => (
          <div key={st.id} className="card" style={{ padding: 16, borderTop: `3px solid ${st.complianceScore >= 80 ? 'var(--success)' : st.complianceScore >= 65 ? 'var(--warning)' : 'var(--danger)'}` }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 8 }}>
              <div>
                <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                  {st.name}
                </div>
                <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                  {st.cluster} &nbsp;·&nbsp; {st.facilitiesCount} Facilities
                </div>
              </div>
              {getRiskBadge(st.riskLevel)}
            </div>

            <div style={{ fontSize: 11, color: 'var(--text-dim)', marginBottom: 12 }}>
              <i className="fa-solid fa-user-tie" style={{ marginRight: 5, color: 'var(--text-muted)' }}></i>
              <strong>Lead:</strong> {st.coordinator}
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, background: 'var(--surface2)', padding: 10, borderRadius: 6, fontSize: 11, marginBottom: 12 }}>
              <div>
                <div style={{ color: 'var(--text-muted)', fontSize: 10 }}>Active CAPs</div>
                <strong style={{ color: st.activeCaps > 0 ? 'var(--danger)' : 'var(--success)' }}>{st.activeCaps} CAPs</strong>
              </div>
              <div>
                <div style={{ color: 'var(--text-muted)', fontSize: 10 }}>Open Complaints</div>
                <strong style={{ color: st.openComplaints > 0 ? 'var(--warning)' : 'var(--success)' }}>{st.openComplaints} Open</strong>
              </div>
            </div>

            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div style={{ fontSize: 11 }}>
                <span style={{ color: 'var(--text-muted)' }}>Compliance: </span>
                <strong style={{ color: st.complianceScore >= 80 ? 'var(--success)' : 'var(--warning)' }}>{st.complianceScore}%</strong>
              </div>

              <div style={{ display: 'flex', gap: 4 }}>
                <button
                  className="btn btn-outline btn-sm"
                  style={{ padding: '2px 8px', fontSize: 10 }}
                  onClick={() => setSelectedStateProfile(st)}
                >
                  👁️ Profile
                </button>
                {isDocAdmin && (
                  <button
                    className="btn btn-outline btn-sm"
                    style={{ padding: '2px 6px', fontSize: 10, color: 'var(--danger)', borderColor: 'var(--danger)' }}
                    onClick={() => handleDeleteState(st.id)}
                    title="Admin Delete State"
                  >
                    🗑️
                  </button>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* FIELD UPDATE FORM & RECENT UPDATES FEED */}
      <div className="grid-2">
        {/* LEFT: Field Update Form (STL Staff & DoC) */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-tower-broadcast" style={{ color: 'var(--accent)' }}></i> 📡 Field Update Submission Form
            </div>
          </div>
          <p style={{ fontSize: 11, color: 'var(--text-muted)', marginBottom: 14 }}>
            State Team Leads submit real-time operational alerts, compliance events, and field visit logs directly into the Executive Monitor.
          </p>

          {/* Form enabled for All Staff (STL) and DoC */}
          {(isStaff || isDocAdmin) ? (
            <form onSubmit={handleSubmitFieldUpdate} style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>State / Cluster:</label>
                  <select
                    value={fuState}
                    onChange={(e) => setFuState(e.target.value)}
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
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
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Update Type:</label>
                  <select
                    value={fuType}
                    onChange={(e) => setFuType(e.target.value)}
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                  >
                    <option>Compliance Activity</option>
                    <option>Complaint Received</option>
                    <option>Training Completed</option>
                    <option>Risk Identified</option>
                    <option>CAP Progress</option>
                    <option>Field Visit Report</option>
                  </select>
                </div>
              </div>

              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Submitted By (Lead / Role):</label>
                <input
                  type="text"
                  value={fuBy}
                  onChange={(e) => setFuBy(e.target.value)}
                  required
                  placeholder="e.g. Fatima Bello (State Team Lead)"
                  style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                />
              </div>

              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Update Details & Summary:</label>
                <textarea
                  placeholder="Describe field activity, compliance check findings, or facility challenge..."
                  value={fuDetails}
                  onChange={(e) => setFuDetails(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 70, padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                />
              </div>

              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Attach Field Report (Optional):</label>
                <input
                  type="file"
                  onChange={(e) => {
                    if (e.target.files && e.target.files[0]) {
                      setFuFileName(e.target.files[0].name);
                    }
                  }}
                  style={{ width: '100%', padding: 4, fontSize: 11, marginTop: 3 }}
                />
              </div>

              <button type="submit" className="btn btn-primary" style={{ alignSelf: 'flex-start', marginTop: 4 }}>
                <i className="fa-solid fa-paper-plane"></i> Submit Field Update
              </button>
            </form>
          ) : (
            <div style={{ background: 'var(--surface2)', padding: 16, borderRadius: 8, fontSize: 12, color: 'var(--text-muted)', textAlign: 'center' }}>
              <i className="fa-solid fa-lock" style={{ marginRight: 6 }}></i> View-Only Mode: Field update submissions are restricted to State Team Leads and DoC Admin.
            </div>
          )}
        </div>

        {/* RIGHT: Live Field Updates Stream */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-list-check" style={{ color: 'var(--accent2)' }}></i> Recent Field Updates Feed
            </div>
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            {fieldUpdates.map((fu) => (
              <div key={fu.id} style={{ background: 'var(--surface2)', border: '1px solid var(--border)', borderRadius: 8, padding: 12, fontSize: 11 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 4 }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                    <strong style={{ color: 'var(--accent)' }}>{fu.state}</strong>
                    <span className="pill pill-progress" style={{ fontSize: 9 }}>{fu.updateType}</span>
                  </div>
                  <span style={{ fontSize: 10, color: 'var(--text-muted)' }}>{fu.date}</span>
                </div>
                <div style={{ color: 'var(--text)', lineHeight: 1.4, marginBottom: 6 }}>
                  {fu.details}
                </div>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: 10, color: 'var(--text-muted)' }}>
                  <span><i className="fa-solid fa-user-check"></i> {fu.submittedBy}</span>
                  {fu.fileName && (
                    <span style={{ color: 'var(--accent)', fontWeight: 600 }}>
                      <i className="fa-solid fa-paperclip"></i> {fu.fileName}
                    </span>
                  )}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* MODAL: ADD STATE / CLUSTER (DoC Only) */}
      {showAddStateModal && (
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
            width: 500,
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
                Add Operational State / Cluster
              </div>
              <button
                onClick={() => setShowAddStateModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleAddState} style={{ padding: 20 }}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>State Name:</label>
                <input
                  type="text"
                  placeholder="e.g. Sokoto"
                  value={newStateName}
                  onChange={(e) => setNewStateName(e.target.value)}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Cluster Designation:</label>
                  <input
                    type="text"
                    placeholder="e.g. Cluster E"
                    value={newClusterName}
                    onChange={(e) => setNewClusterName(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Assigned Facilities:</label>
                  <input
                    type="number"
                    value={newFacilitiesCount}
                    onChange={(e) => setNewFacilitiesCount(parseInt(e.target.value) || 0)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>State Coordinator / Lead:</label>
                <input
                  type="text"
                  placeholder="e.g. Dr. Aliyu Usman"
                  value={newCoordinator}
                  onChange={(e) => setNewCoordinator(e.target.value)}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowAddStateModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Register State Cluster
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: STATE PROFILE DETAILS */}
      {selectedStateProfile && (
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
            width: 520,
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
                State Profile — {selectedStateProfile.name} ({selectedStateProfile.cluster})
              </div>
              <button
                onClick={() => setSelectedStateProfile(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <div style={{ padding: 20, fontSize: 12 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 14 }}>
                <div><strong>State Coordinator:</strong> {selectedStateProfile.coordinator}</div>
                <div><strong>Clinical Facilities:</strong> {selectedStateProfile.facilitiesCount}</div>
                <div><strong>Active CAPs:</strong> {selectedStateProfile.activeCaps}</div>
                <div><strong>Open Complaints:</strong> {selectedStateProfile.openComplaints}</div>
                <div><strong>Risk Tier:</strong> {getRiskBadge(selectedStateProfile.riskLevel)}</div>
                <div><strong>Compliance Rating:</strong> <strong style={{ color: 'var(--accent)' }}>{selectedStateProfile.complianceScore}%</strong></div>
              </div>

              <div style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, border: '1px solid var(--border)', marginBottom: 14 }}>
                <strong>Grant Scope & Mandate:</strong>
                <p style={{ marginTop: 4, color: 'var(--text-muted)', lineHeight: 1.4 }}>
                  Comprehensive HIV care, treatment, viral load testing, and PMTCT scale-up operations across supported PEPFAR/Global Fund health facilities.
                </p>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                <button className="btn btn-outline" onClick={() => setSelectedStateProfile(null)}>
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
