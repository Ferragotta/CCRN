import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export interface TrainingModuleItem {
  id: string;
  title: string;
  category: string;
  duration: string;
  mandatory: boolean;
  completionRate: number;
  totalEnrolled: number;
  deadline: string;
}

export interface AttendanceRecord {
  id: string;
  staffName: string;
  email: string;
  state: string;
  moduleId: string;
  moduleTitle: string;
  date: string;
  status: 'Completed' | 'In Progress';
}

export const TrainingModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();

  const isHR = currentUser?.key === 'hr';
  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isStaff = currentUser?.key === 'staff';

  // Seed Training Modules matching wireframe
  const [modules, setModules] = useState<TrainingModuleItem[]>([
    {
      id: 'TR-01',
      title: 'Anti-Fraud, Bribery & Whistleblower Ethics',
      category: 'Institutional Governance',
      duration: '45 mins',
      mandatory: true,
      completionRate: 88,
      totalEnrolled: 490,
      deadline: '31 Mar 2026'
    },
    {
      id: 'TR-02',
      title: 'Safeguarding, PSEA & Beneficiary Protection',
      category: 'PSEA / Human Rights',
      duration: '60 mins',
      mandatory: true,
      completionRate: 74,
      totalEnrolled: 490,
      deadline: '31 Mar 2026'
    },
    {
      id: 'TR-03',
      title: 'Data Protection, HIPAA & Client Privacy',
      category: 'Information Security',
      duration: '30 mins',
      mandatory: true,
      completionRate: 61,
      totalEnrolled: 490,
      deadline: '15 Apr 2026'
    },
    {
      id: 'TR-04',
      title: 'USAID & 2 CFR 200 Dual-Signatory Procurement',
      category: 'Grant Compliance',
      duration: '75 mins',
      mandatory: false,
      completionRate: 52,
      totalEnrolled: 210,
      deadline: '30 Apr 2026'
    }
  ]);

  // Attendance Records
  const [attendances, setAttendances] = useState<AttendanceRecord[]>([
    { id: 'ATT-101', staffName: 'Fatima Bello', email: 'staff@cccrn.org', state: 'Lagos', moduleId: 'TR-01', moduleTitle: 'Anti-Fraud, Bribery & Whistleblower Ethics', date: '26 Feb 2026', status: 'Completed' },
    { id: 'ATT-102', staffName: 'Fatima Bello', email: 'staff@cccrn.org', state: 'Lagos', moduleId: 'TR-02', moduleTitle: 'Safeguarding, PSEA & Beneficiary Protection', date: '27 Feb 2026', status: 'Completed' },
    { id: 'ATT-103', staffName: 'Ibrahim Garba', email: 'i.garba@cccrn.org', state: 'Kano', moduleId: 'TR-01', moduleTitle: 'Anti-Fraud, Bribery & Whistleblower Ethics', date: '25 Feb 2026', status: 'Completed' },
    { id: 'ATT-104', staffName: 'Ngozi Okoro', email: 'n.okoro@cccrn.org', state: 'Rivers', moduleId: 'TR-03', moduleTitle: 'Data Protection, HIPAA & Client Privacy', date: '24 Feb 2026', status: 'Completed' }
  ]);

  // State Completion Breakdown
  const stateStats = [
    { state: 'Lagos', staff: 120, trained: 98, rate: 82, incompleteStaff: ['Musa Danladi (Finance)', 'Grace Adeleke (Admin)'] },
    { state: 'Kano', staff: 80, trained: 41, rate: 51, incompleteStaff: ['Aliyu Usman (Field M&E)', 'Hassan Bello (Logistics)', 'Zainab Ahmed (Clinical)'] },
    { state: 'Rivers', staff: 70, trained: 60, rate: 86, incompleteStaff: ['Tamuno Briggs (Lab Tech)'] },
    { state: 'Abuja FCT', staff: 90, trained: 62, rate: 69, incompleteStaff: ['Emeka Okafor (IT)', 'Sarah John (Communications)'] },
    { state: 'Kaduna', staff: 65, trained: 28, rate: 43, incompleteStaff: ['Bala Mohammed (Field Officer)', 'Khadija Sani (Counselor)'] },
    { state: 'Borno', staff: 65, trained: 23, rate: 35, incompleteStaff: ['Yusuf Bukar (Community Lead)', 'Amina Kyari (Nurse)'] }
  ];

  // Modals & UI States
  const [showAddModal, setShowAddModal] = useState(false);
  const [selectedStateForDrilldown, setSelectedStateForDrilldown] = useState<string | null>(null);
  const [notificationEmailSent, setNotificationEmailSent] = useState(false);

  // Staff Attendance Form State
  const [attName, setAttName] = useState(currentUser?.name || '');
  const [attState, setAttState] = useState('Lagos');
  const [attModuleId, setAttModuleId] = useState('TR-01');

  // Add Module Form State
  const [newModTitle, setNewModTitle] = useState('');
  const [newModCategory, setNewModCategory] = useState('Grant Compliance');
  const [newModDuration, setNewModDuration] = useState('45 mins');
  const [newModMandatory, setNewModMandatory] = useState(true);
  const [newModDeadline, setNewModDeadline] = useState('');

  const handleLogAttendance = (e: React.FormEvent) => {
    e.preventDefault();
    const mod = modules.find(m => m.id === attModuleId);
    if (!mod) return;

    const newRec: AttendanceRecord = {
      id: 'ATT-' + (105 + attendances.length),
      staffName: attName || currentUser?.name || 'Staff Member',
      email: currentUser?.email || 'staff@cccrn.org',
      state: attState,
      moduleId: mod.id,
      moduleTitle: mod.title,
      date: new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }),
      status: 'Completed'
    };

    setAttendances([newRec, ...attendances]);
    alert(`✓ Attendance verified for "${mod.title}". Course completion certificate generated.`);
  };

  const handleAddModule = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newModTitle.trim()) return;

    const newMod: TrainingModuleItem = {
      id: 'TR-0' + (modules.length + 1),
      title: newModTitle,
      category: newModCategory,
      duration: newModDuration,
      mandatory: newModMandatory,
      completionRate: 0,
      totalEnrolled: 490,
      deadline: newModDeadline || '30 May 2026'
    };

    setModules([...modules, newMod]);
    setShowAddModal(false);
    setNewModTitle('');
    setNotificationEmailSent(true);
    alert(`Training Module ${newMod.id} added. Automated email notifications broadcast to all 490 institutional staff accounts.`);
  };

  const handleDeleteModule = (id: string) => {
    if (!isDocAdmin) {
      alert('Action Restricted: Only the Director of Compliance has authority to delete training curriculum modules.');
      return;
    }
    if (!confirm(`Permanently delete module ${id}?`)) return;
    setModules(modules.filter(m => m.id !== id));
  };

  const handleExportTrainingReport = (format: string) => {
    alert(`Exporting institutional training compliance report in ${format} format...`);
  };

  // Staff's personal completed trainings
  const myAttendances = attendances.filter(a => a.email === currentUser?.email || a.email === 'staff@cccrn.org');

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
          Training Management & Academy
        </h2>
        <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
          Deliver compliance training, track completion rates and certifications across all states
        </p>

        {/* ROLE MATRIX INDICATOR */}
        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-graduation-cap"></i> <strong>Staff Training Portal:</strong> Complete mandatory training modules, log your attendance, and download certifications.
          </div>
        )}
        {(isHR || isComplianceOfficer) && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-chalkboard-user"></i> <strong>{isHR ? 'HR Governance' : 'Compliance Officer'}:</strong> Add training modules, monitor state completion thresholds, and generate staff completion reports. (Delete disabled).
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Training Academy Oversight · Add, Edit, Delete Curriculum, and Audit Certifications.
          </div>
        )}

        {notificationEmailSent && (
          <div style={{ marginTop: 8, padding: '6px 14px', background: '#dcfce7', color: '#15803d', border: '1px solid #86efac', borderRadius: 6, fontSize: 11, display: 'flex', alignItems: 'center', gap: 8 }}>
            <i className="fa-solid fa-envelope-circle-check"></i> <strong>Email Notification Dispatch:</strong> Broadcast notification sent to staff regarding newly added course.
          </div>
        )}
      </div>

      {/* 4 STAT CARDS matching wireframe */}
      <div className="stats-grid" style={{ marginBottom: 20 }}>
        <div className="stat-card green">
          <div className="stat-label">Staff Trained</div>
          <div className="stat-value">312</div>
        </div>
        <div className="stat-card blue">
          <div className="stat-label">Completion Rate</div>
          <div className="stat-value">64%</div>
        </div>
        <div className="stat-card purple">
          <div className="stat-label">Active Courses</div>
          <div className="stat-value">{modules.length}</div>
        </div>
        <div className="stat-card red">
          <div className="stat-label">Overdue</div>
          <div className="stat-value">45</div>
        </div>
      </div>

      {/* GRID 2: Active Modules + Completion By State */}
      <div className="grid-2">
        {/* LEFT: Active Training Modules */}
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title" style={{ margin: 0 }}>
              <i className="fa-solid fa-book-bookmark" style={{ color: 'var(--accent)' }}></i> Active Training Modules
            </div>
            {/* Add Module: HR, Compliance Officer, and DoC */}
            {(isHR || isComplianceOfficer || isDocAdmin) && (
              <button className="btn btn-primary btn-sm" onClick={() => setShowAddModal(true)}>
                <i className="fa-solid fa-plus"></i> Add Module
              </button>
            )}
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            {modules.map((m) => (
              <div key={m.id} style={{ background: 'var(--surface2)', border: '1px solid var(--border)', borderRadius: 8, padding: 12 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                      <span style={{ fontWeight: 700, fontSize: 13, color: 'var(--text)' }}>{m.title}</span>
                      {m.mandatory && (
                        <span style={{ fontSize: 9, fontWeight: 700, background: 'var(--danger-light)', color: 'var(--danger)', padding: '1px 5px', borderRadius: 4 }}>
                          MANDATORY
                        </span>
                      )}
                    </div>
                    <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2 }}>
                      {m.category} &nbsp;·&nbsp; ⏱️ {m.duration} &nbsp;·&nbsp; Deadline: {m.deadline}
                    </div>
                  </div>
                  {/* Delete button: DoC ONLY */}
                  {isDocAdmin && (
                    <button
                      onClick={() => handleDeleteModule(m.id)}
                      style={{ background: 'none', border: 'none', color: 'var(--danger)', cursor: 'pointer', fontSize: 12 }}
                      title="Admin Delete Module"
                    >
                      🗑️
                    </button>
                  )}
                </div>

                {/* Progress bar */}
                <div style={{ marginTop: 10 }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 10, color: 'var(--text-dim)', marginBottom: 3 }}>
                    <span>Institutional Completion</span>
                    <strong>{m.completionRate}% ({Math.round(m.totalEnrolled * m.completionRate / 100)} / {m.totalEnrolled})</strong>
                  </div>
                  <div style={{ height: 6, background: 'var(--border)', borderRadius: 3, overflow: 'hidden' }}>
                    <div style={{ height: '100%', width: `${m.completionRate}%`, background: m.completionRate >= 70 ? 'var(--success)' : 'var(--warning)', borderRadius: 3 }}></div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* RIGHT: Completion by State */}
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title" style={{ margin: 0 }}>
              <i className="fa-solid fa-map-location-dot" style={{ color: 'var(--accent2)' }}></i> Completion by State / Cluster
            </div>
            {(isHR || isComplianceOfficer || isDocAdmin) && (
              <div style={{ display: 'flex', gap: 4 }}>
                <button className="btn btn-outline btn-sm" onClick={() => handleExportTrainingReport('PDF')}>
                  <i className="fa-solid fa-file-pdf"></i> PDF
                </button>
                <button className="btn btn-outline btn-sm" onClick={() => handleExportTrainingReport('CSV')}>
                  <i className="fa-solid fa-file-csv"></i> CSV
                </button>
              </div>
            )}
          </div>

          <table>
            <thead>
              <tr>
                <th>State</th>
                <th>Staff</th>
                <th>Trained</th>
                <th>% Rate</th>
                <th>Drill-Down</th>
              </tr>
            </thead>
            <tbody>
              {stateStats.map((st) => (
                <tr key={st.state}>
                  <td style={{ fontWeight: 600 }}>{st.state}</td>
                  <td>{st.staff}</td>
                  <td>{st.trained}</td>
                  <td>
                    <span style={{
                      fontWeight: 700,
                      color: st.rate >= 75 ? 'var(--success)' : st.rate >= 50 ? 'var(--warning)' : 'var(--danger)'
                    }}>
                      {st.rate}%
                    </span>
                  </td>
                  <td>
                    <button
                      className="btn btn-outline btn-sm"
                      style={{ padding: '2px 6px', fontSize: 10 }}
                      onClick={() => setSelectedStateForDrilldown(st.state)}
                    >
                      👁️ Incomplete ({st.incompleteStaff.length})
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* STAFF TRAINING ATTENDANCE PORTAL (matching wireframe) */}
      <div className="card" style={{ marginTop: 20, borderLeft: '4px solid var(--success)' }}>
        <div style={{ background: 'linear-gradient(135deg, #f0fdf4, #dcfce7)', margin: '-16px -16px 14px -16px', padding: '12px 16px', borderRadius: '8px 8px 0 0' }}>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 15, fontWeight: 700, color: '#166534' }}>
            🎓 Staff Training Portal — Attend & Log Attendance
          </div>
          <div style={{ fontSize: 11, color: '#15803d', marginTop: 2 }}>
            Staff log in here to mark attendance — counts towards module completion & issues certification
          </div>
        </div>

        <form onSubmit={handleLogAttendance} style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1.5fr auto', gap: 10, alignItems: 'flex-end' }}>
          <div>
            <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Full Name:</label>
            <input
              type="text"
              value={attName}
              onChange={(e) => setAttName(e.target.value)}
              required
              placeholder="Your full name"
              style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
            />
          </div>
          <div>
            <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>State / Cluster:</label>
            <select
              value={attState}
              onChange={(e) => setAttState(e.target.value)}
              style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
            >
              <option>Lagos</option>
              <option>Kano</option>
              <option>Rivers</option>
              <option>Abuja FCT</option>
              <option>Kaduna</option>
              <option>Borno</option>
            </select>
          </div>
          <div>
            <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Select Training Module:</label>
            <select
              value={attModuleId}
              onChange={(e) => setAttModuleId(e.target.value)}
              style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
            >
              {modules.map((m) => (
                <option key={m.id} value={m.id}>{m.id} — {m.title}</option>
              ))}
            </select>
          </div>
          <button type="submit" className="btn btn-primary" style={{ background: 'var(--success)', borderColor: 'var(--success)', whiteSpace: 'nowrap' }}>
            ✓ Log My Attendance
          </button>
        </form>
      </div>

      {/* STAFF PERSONAL CERTIFICATE LEDGER */}
      {isStaff && (
        <div className="card" style={{ marginTop: 16 }}>
          <div className="card-header">
            <div className="card-title"><i className="fa-solid fa-award" style={{ color: 'var(--accent)' }}></i> My Completed Trainings & Certifications</div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Certificate ID</th>
                <th>Training Module</th>
                <th>Date Completed</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {myAttendances.map((a) => (
                <tr key={a.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{a.id}</td>
                  <td>{a.moduleTitle}</td>
                  <td>{a.date}</td>
                  <td><span className="pill pill-closed">✓ Certified</span></td>
                  <td>
                    <button
                      className="btn btn-outline btn-sm"
                      onClick={() => alert(`Downloading PDF Compliance Certificate for ${a.moduleTitle}...`)}
                    >
                      <i className="fa-solid fa-download"></i> Download Certificate
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* ATTENDANCE REGISTER */}
      <div className="card" style={{ marginTop: 16 }}>
        <div className="card-header">
          <div className="card-title"><i className="fa-solid fa-clipboard-user"></i> Master Attendance Register</div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>{attendances.length} attendances logged</div>
        </div>
        <table>
          <thead>
            <tr>
              <th>Staff Name</th>
              <th>State</th>
              <th>Module Title</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            {attendances.map((a) => (
              <tr key={a.id}>
                <td style={{ fontWeight: 600 }}>{a.staffName}</td>
                <td>{a.state}</td>
                <td>{a.moduleTitle}</td>
                <td>{a.date}</td>
                <td><span className="pill pill-closed">Completed</span></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* MODAL: ADD TRAINING MODULE */}
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
                Add New Training Curriculum Module
              </div>
              <button
                onClick={() => setShowAddModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleAddModule} style={{ padding: 20 }}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Module Title:</label>
                <input
                  type="text"
                  placeholder="e.g. Anti-Harassment & Whistleblowing Procedures"
                  value={newModTitle}
                  onChange={(e) => setNewModTitle(e.target.value)}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Category:</label>
                  <select
                    value={newModCategory}
                    onChange={(e) => setNewModCategory(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Institutional Governance</option>
                    <option>PSEA / Safeguarding</option>
                    <option>Information Security</option>
                    <option>Grant Compliance</option>
                    <option>Financial Controls</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Estimated Duration:</label>
                  <input
                    type="text"
                    placeholder="e.g. 45 mins"
                    value={newModDuration}
                    onChange={(e) => setNewModDuration(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 16 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Completion Deadline:</label>
                  <input
                    type="date"
                    value={newModDeadline}
                    onChange={(e) => setNewModDeadline(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div style={{ display: 'flex', alignItems: 'center', marginTop: 18 }}>
                  <label style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, cursor: 'pointer' }}>
                    <input
                      type="checkbox"
                      checked={newModMandatory}
                      onChange={(e) => setNewModMandatory(e.target.checked)}
                      style={{ accentColor: 'var(--accent)' }}
                    />
                    <strong>Mandatory for all staff</strong>
                  </label>
                </div>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowAddModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Publish Module & Broadcast Email
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: STATE DRILLDOWN FOR INCOMPLETE STAFF */}
      {selectedStateForDrilldown && (
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
            width: 480,
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
                {selectedStateForDrilldown} — Incomplete Training Staff
              </div>
              <button
                onClick={() => setSelectedStateForDrilldown(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>
            <div style={{ padding: 20 }}>
              <p style={{ fontSize: 12, color: 'var(--text-muted)', marginBottom: 12 }}>
                List of personnel in <strong>{selectedStateForDrilldown}</strong> with pending mandatory training:
              </p>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 8, marginBottom: 16 }}>
                {stateStats.find(s => s.state === selectedStateForDrilldown)?.incompleteStaff.map((staff, idx) => (
                  <div key={idx} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: 'var(--surface2)', padding: '8px 12px', borderRadius: 6, fontSize: 12 }}>
                    <span><i className="fa-solid fa-user-xmark" style={{ color: 'var(--danger)', marginRight: 6 }}></i> {staff}</span>
                    <button
                      className="btn btn-outline btn-sm"
                      style={{ fontSize: 10, padding: '2px 6px' }}
                      onClick={() => alert(`Reminder notification sent to ${staff}.`)}
                    >
                      Send Reminder
                    </button>
                  </div>
                ))}
              </div>
              <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                <button className="btn btn-outline" onClick={() => setSelectedStateForDrilldown(null)}>
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
