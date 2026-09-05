import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { useToast } from '../../context/ToastContext';

interface LeaveRequest {
  id: string;
  staffName: string;
  email: string;
  state: string;
  dept: string;
  leaveType: 'Annual Leave' | 'Sick Leave' | 'Casual Leave' | 'Maternity Leave' | 'Study / Exam';
  startDate: string;
  endDate: string;
  days: number;
  relievingStaff: string;
  status: 'Pending' | 'Approved' | 'Rejected';
  appliedDate: string;
}

interface AttendanceRecord {
  id: string;
  staffName: string;
  state: string;
  role: string;
  checkIn: string;
  checkOut: string;
  status: 'On-Time' | 'Late' | 'Field Duty' | 'Excused';
  source: string;
}

interface ComplaintViewOnly {
  ref: string;
  date: string;
  state: string;
  category: string;
  parties: string;
  investigator: string;
  severity: 'Critical' | 'High' | 'Medium' | 'Low';
  status: 'Under Investigation' | 'Triage' | 'Referred' | 'Closed';
}

export const HrDashboard: React.FC = () => {
  const { setActiveModule } = useAuth();
  const { showSuccess, showInfo } = useToast();

  const [activeTab, setActiveTab] = useState<'overview' | 'leave' | 'attendance' | 'complaints'>('overview');

  // Sample Leave Requests
  const [leaveRequests, setLeaveRequests] = useState<LeaveRequest[]>([
    {
      id: 'LVE-2026-042',
      staffName: 'Amina Kyari',
      email: 'amina.kyari@cccrn.org',
      state: 'Borno',
      dept: 'Clinical Services',
      leaveType: 'Annual Leave',
      startDate: '10 Mar 2026',
      endDate: '24 Mar 2026',
      days: 10,
      relievingStaff: 'Dr. Usman Bello',
      status: 'Pending',
      appliedDate: '02 Mar 2026'
    },
    {
      id: 'LVE-2026-041',
      staffName: 'Emeka Okafor',
      email: 'emeka.okafor@cccrn.org',
      state: 'Abuja FCT',
      dept: 'Procurement & Operations',
      leaveType: 'Casual Leave',
      startDate: '06 Mar 2026',
      endDate: '09 Mar 2026',
      days: 2,
      relievingStaff: 'Fatima Sanusi',
      status: 'Pending',
      appliedDate: '01 Mar 2026'
    },
    {
      id: 'LVE-2026-040',
      staffName: 'Biodun Alade',
      email: 'biodun.alade@cccrn.org',
      state: 'Lagos',
      dept: 'Human Resources',
      leaveType: 'Study / Exam',
      startDate: '15 Mar 2026',
      endDate: '20 Mar 2026',
      days: 5,
      relievingStaff: 'Ngozi Adeyemi',
      status: 'Approved',
      appliedDate: '26 Feb 2026'
    },
    {
      id: 'LVE-2026-039',
      staffName: 'Aliyu Usman',
      email: 'aliyu.usman@cccrn.org',
      state: 'Kano',
      dept: 'Field Security',
      leaveType: 'Sick Leave',
      startDate: '27 Feb 2026',
      endDate: '03 Mar 2026',
      days: 4,
      relievingStaff: 'Musa Ibrahim',
      status: 'Approved',
      appliedDate: '27 Feb 2026'
    }
  ]);

  // Today's Live Attendance Feed
  const todayAttendance: AttendanceRecord[] = [
    { id: 'ATT-901', staffName: 'Ngozi Adeyemi', state: 'Lagos', role: 'State Coordinator', checkIn: '07:48 AM', checkOut: '--', status: 'On-Time', source: 'Attendify Biometrics' },
    { id: 'ATT-902', staffName: 'Chidi Okafor', state: 'Rivers', role: 'Clinical Lead', checkIn: '07:55 AM', checkOut: '--', status: 'On-Time', source: 'Attendify Mobile' },
    { id: 'ATT-903', staffName: 'Musa Ibrahim', state: 'Kano', role: 'Cluster Lead', checkIn: '08:14 AM', checkOut: '--', status: 'Late', source: 'Attendify Biometrics' },
    { id: 'ATT-904', staffName: 'Fatima Bakura', state: 'Borno', role: 'Operations Field', checkIn: '08:02 AM', checkOut: '--', status: 'Field Duty', source: 'Attendify Geo-Fence' },
    { id: 'ATT-905', staffName: 'Amaka Okonkwo', state: 'Abuja FCT', role: 'Finance Officer', checkIn: '07:51 AM', checkOut: '--', status: 'On-Time', source: 'Attendify Biometrics' }
  ];

  // Complaints Feed (Strict View-Only for HR)
  const complaintsFeed: ComplaintViewOnly[] = [
    {
      ref: 'CMP-048',
      date: '28 Feb 2026',
      state: 'Kano (Cluster B)',
      category: 'Workplace Misconduct / Insubordination',
      parties: 'Confidential Staff vs Department Lead',
      investigator: 'In Progress (Assigned by DoC)',
      severity: 'High',
      status: 'Under Investigation'
    },
    {
      ref: 'CMP-047',
      date: '26 Feb 2026',
      state: 'Lagos (Cluster A)',
      category: 'PSEA / Harassment Prevention',
      parties: 'Internal Grievance Procedure',
      investigator: 'Amaka Obi (Senior Investigator)',
      severity: 'Critical',
      status: 'Under Investigation'
    },
    {
      ref: 'CMP-043',
      date: '20 Feb 2026',
      state: 'Borno',
      category: 'Staff Retaliation / Ethics Violation',
      parties: 'Safeguarding Anonymous Channel',
      investigator: 'Emeka Eze',
      severity: 'Critical',
      status: 'Referred'
    },
    {
      ref: 'CMP-040',
      date: '14 Feb 2026',
      state: 'Rivers (Cluster C)',
      category: 'Overtime & Field Subsistence Dispute',
      parties: 'Operations Team Lead & Field Staff',
      investigator: 'DoC Executive Panel',
      severity: 'Medium',
      status: 'Closed'
    }
  ];

  const handleApproveLeave = (id: string, staffName: string) => {
    setLeaveRequests(prev => prev.map(l => l.id === id ? { ...l, status: 'Approved' } : l));
    showSuccess(`Leave Approved: ${id}`, `Leave request for ${staffName} has been approved.`);
  };

  const handleRejectLeave = (id: string, staffName: string) => {
    setLeaveRequests(prev => prev.map(l => l.id === id ? { ...l, status: 'Rejected' } : l));
    showInfo(`Leave Rejected: ${id}`, `Leave request for ${staffName} was rejected.`);
  };

  return (
    <div className="hr-dashboard" style={{ paddingBottom: 40 }}>
      {/* ── 1. TOP HR COMMAND BANNER ── */}
      <div style={{
        background: 'linear-gradient(135deg, #022b61 0%, #02367B 55%, #0077b6 100%)',
        color: '#ffffff',
        padding: '24px 28px',
        borderRadius: 14,
        marginBottom: 24,
        boxShadow: '0 8px 24px rgba(2, 54, 123, 0.18)',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        flexWrap: 'wrap',
        gap: 16
      }}>
        <div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 4 }}>
            <span style={{
              background: '#55E2E9',
              color: '#02367B',
              fontSize: 10,
              fontWeight: 800,
              padding: '3px 8px',
              borderRadius: 4,
              textTransform: 'uppercase',
              letterSpacing: '0.6px'
            }}>
              HR Executive Operations
            </span>
            <span style={{ fontSize: 12, opacity: 0.85, fontWeight: 500 }}>
              Attendify Pro™ Synced · FY2026
            </span>
          </div>
          <h1 style={{
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            fontSize: 22,
            fontWeight: 800,
            margin: '4px 0 3px',
            color: '#ffffff'
          }}>
            Human Resources Command Center
          </h1>
          <div style={{ fontSize: 12, opacity: 0.9 }}>
            Workforce governance, leave & attendance rosters, PDP scoring, mandatory training compliance, and employee relations
          </div>
        </div>

        <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap' }}>
          <button
            className="btn btn-outline"
            style={{ background: 'rgba(255,255,255,0.14)', borderColor: 'rgba(255,255,255,0.35)', color: '#ffffff', fontSize: 11, fontWeight: 700 }}
            onClick={() => setActiveModule('leave-attendance')}
          >
            <i className="fa-solid fa-calendar-check" style={{ marginRight: 6 }}></i> Leave & Attendance
          </button>
          <button
            className="btn btn-outline"
            style={{ background: 'rgba(255,255,255,0.14)', borderColor: 'rgba(255,255,255,0.35)', color: '#ffffff', fontSize: 11, fontWeight: 700 }}
            onClick={() => setActiveModule('pdp')}
          >
            <i className="fa-solid fa-bullseye" style={{ marginRight: 6 }}></i> Institutional PDP Audit
          </button>
          <button
            className="btn btn-outline"
            style={{ background: 'rgba(255,255,255,0.14)', borderColor: 'rgba(255,255,255,0.35)', color: '#ffffff', fontSize: 11, fontWeight: 700 }}
            onClick={() => setActiveModule('training')}
          >
            <i className="fa-solid fa-graduation-cap" style={{ marginRight: 6 }}></i> Training Academy
          </button>
        </div>
      </div>

      {/* ── 2. 5 KEY HR STAT TILES ── */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
        gap: 16,
        marginBottom: 24
      }}>
        {/* Tile 1: Total Active Staff */}
        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #0077b6', background: '#ffffff', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Total Active Workforce
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#0077b6', lineHeight: 1 }}>
            490
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            Across 6 State Offices & Clusters
          </div>
        </div>

        {/* Tile 2: Today's Attendance Rate */}
        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #059669', background: '#ffffff', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Today's Attendance Rate
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#059669', lineHeight: 1 }}>
            94.2%
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            <span style={{ color: '#059669', fontWeight: 700 }}>462 Clocked In</span> · 28 on Field / Leave
          </div>
        </div>

        {/* Tile 3: Pending Leave Requests */}
        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #d97706', background: '#ffffff', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Pending Leave Requests
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#d97706', lineHeight: 1 }}>
            {leaveRequests.filter(l => l.status === 'Pending').length}
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            Awaiting HR Verification
          </div>
        </div>

        {/* Tile 4: Mandatory Training Completion */}
        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #7c3aed', background: '#ffffff', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Compliance Training
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#7c3aed', lineHeight: 1 }}>
            71.4%
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            <span style={{ color: '#dc2626', fontWeight: 700 }}>34 Overdue Staff</span> · PSEA at 100%
          </div>
        </div>

        {/* Tile 5: Complaints (HR View-Only) */}
        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #dc2626', background: '#ffffff', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Staff Grievances (View)
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#dc2626', lineHeight: 1 }}>
            4
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            <span style={{ color: '#0077b6', fontWeight: 700 }}>Strict View-Only</span> Access
          </div>
        </div>
      </div>

      {/* ── 3. INTERACTIVE HR DASHBOARD TABS ── */}
      <div style={{
        display: 'flex',
        gap: 8,
        borderBottom: '1px solid var(--border)',
        marginBottom: 20
      }}>
        <button
          className={`tab ${activeTab === 'overview' ? 'active' : ''}`}
          onClick={() => setActiveTab('overview')}
          style={{
            padding: '10px 18px',
            border: 'none',
            background: 'none',
            borderBottom: activeTab === 'overview' ? '3px solid #0077b6' : '3px solid transparent',
            color: activeTab === 'overview' ? '#0077b6' : 'var(--text-muted)',
            fontWeight: 700,
            fontSize: 13,
            cursor: 'pointer'
          }}
        >
          <i className="fa-solid fa-gauge-high" style={{ marginRight: 6 }}></i> HR Overview
        </button>

        <button
          className={`tab ${activeTab === 'leave' ? 'active' : ''}`}
          onClick={() => setActiveTab('leave')}
          style={{
            padding: '10px 18px',
            border: 'none',
            background: 'none',
            borderBottom: activeTab === 'leave' ? '3px solid #0077b6' : '3px solid transparent',
            color: activeTab === 'leave' ? '#0077b6' : 'var(--text-muted)',
            fontWeight: 700,
            fontSize: 13,
            cursor: 'pointer'
          }}
        >
          <i className="fa-solid fa-calendar-check" style={{ marginRight: 6 }}></i> Pending Leave Approvals ({leaveRequests.filter(l => l.status === 'Pending').length})
        </button>

        <button
          className={`tab ${activeTab === 'attendance' ? 'active' : ''}`}
          onClick={() => setActiveTab('attendance')}
          style={{
            padding: '10px 18px',
            border: 'none',
            background: 'none',
            borderBottom: activeTab === 'attendance' ? '3px solid #0077b6' : '3px solid transparent',
            color: activeTab === 'attendance' ? '#0077b6' : 'var(--text-muted)',
            fontWeight: 700,
            fontSize: 13,
            cursor: 'pointer'
          }}
        >
          <i className="fa-solid fa-clock" style={{ marginRight: 6 }}></i> Today's Attendance Pulse
        </button>

        <button
          className={`tab ${activeTab === 'complaints' ? 'active' : ''}`}
          onClick={() => setActiveTab('complaints')}
          style={{
            padding: '10px 18px',
            border: 'none',
            background: 'none',
            borderBottom: activeTab === 'complaints' ? '3px solid #0077b6' : '3px solid transparent',
            color: activeTab === 'complaints' ? '#0077b6' : 'var(--text-muted)',
            fontWeight: 700,
            fontSize: 13,
            cursor: 'pointer'
          }}
        >
          <i className="fa-solid fa-inbox" style={{ marginRight: 6 }}></i> Complaints Monitor (View Only)
        </button>
      </div>

      {/* ── 4. TAB PANELS ── */}

      {/* PANEL 1: OVERVIEW */}
      {activeTab === 'overview' && (
        <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 1fr', gap: 20 }}>
          {/* Left: State Distribution & Attendance Stats */}
          <div className="card">
            <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div className="card-title">
                <i className="fa-solid fa-map-location-dot" style={{ color: '#0077b6', marginRight: 8 }}></i>
                State Workforce & Attendance Rate
              </div>
              <button
                className="btn btn-sm btn-outline"
                onClick={() => setActiveModule('states')}
              >
                View Clusters
              </button>
            </div>
            <div style={{ padding: 16 }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
                <thead>
                  <tr style={{ borderBottom: '1px solid var(--border)', textAlign: 'left', color: 'var(--text-muted)', fontSize: 11 }}>
                    <th style={{ padding: '8px 10px' }}>State Office</th>
                    <th style={{ padding: '8px 10px' }}>Staff Count</th>
                    <th style={{ padding: '8px 10px' }}>Attendance Rate</th>
                    <th style={{ padding: '8px 10px' }}>Training Overdue</th>
                    <th style={{ padding: '8px 10px' }}>PDP Compliance</th>
                  </tr>
                </thead>
                <tbody>
                  {[
                    { state: 'Lagos (Cluster A)', count: 95, att: '96%', overdue: 1, pdp: '92%' },
                    { state: 'Abuja FCT', count: 110, att: '98%', overdue: 2, pdp: '95%' },
                    { state: 'Kano (Cluster B)', count: 82, att: '89%', overdue: 8, pdp: '78%' },
                    { state: 'Rivers (Cluster C)', count: 74, att: '94%', overdue: 3, pdp: '88%' },
                    { state: 'Kaduna', count: 68, att: '91%', overdue: 9, pdp: '74%' },
                    { state: 'Borno', count: 61, att: '87%', overdue: 11, pdp: '69%' }
                  ].map((row, idx) => (
                    <tr key={idx} style={{ borderBottom: '1px solid var(--border)' }}>
                      <td style={{ padding: '10px', fontWeight: 600 }}>{row.state}</td>
                      <td style={{ padding: '10px' }}>{row.count} staff</td>
                      <td style={{ padding: '10px', fontWeight: 700, color: '#059669' }}>{row.att}</td>
                      <td style={{ padding: '10px', color: row.overdue > 5 ? '#dc2626' : 'var(--text)' }}>
                        {row.overdue} staff
                      </td>
                      <td style={{ padding: '10px' }}>{row.pdp}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          {/* Right: Quick Action Leave Summary */}
          <div className="card">
            <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div className="card-title">
                <i className="fa-solid fa-calendar-days" style={{ color: '#d97706', marginRight: 8 }}></i>
                Pending Leave Approvals ({leaveRequests.filter(l => l.status === 'Pending').length})
              </div>
              <button
                className="btn btn-sm btn-outline"
                onClick={() => setActiveTab('leave')}
              >
                View All
              </button>
            </div>
            <div style={{ padding: 16 }}>
              {leaveRequests.filter(l => l.status === 'Pending').map((lve) => (
                <div key={lve.id} style={{
                  padding: '12px 14px',
                  borderRadius: 8,
                  border: '1px solid var(--border)',
                  marginBottom: 12,
                  background: 'var(--surface2)',
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'center'
                }}>
                  <div>
                    <div style={{ fontWeight: 700, fontSize: 13, color: '#02367B' }}>{lve.staffName}</div>
                    <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                      {lve.leaveType} · {lve.state} ({lve.days} days)
                    </div>
                    <div style={{ fontSize: 11, color: 'var(--text-dim)', marginTop: 2 }}>
                      {lve.startDate} — {lve.endDate} (Reliever: {lve.relievingStaff})
                    </div>
                  </div>
                  <div style={{ display: 'flex', gap: 6 }}>
                    <button
                      className="btn btn-sm btn-primary"
                      style={{ padding: '4px 10px', fontSize: 11 }}
                      onClick={() => handleApproveLeave(lve.id, lve.staffName)}
                    >
                      Approve
                    </button>
                    <button
                      className="btn btn-sm btn-outline"
                      style={{ padding: '4px 10px', fontSize: 11, color: '#dc2626', borderColor: '#fca5a5' }}
                      onClick={() => handleRejectLeave(lve.id, lve.staffName)}
                    >
                      Reject
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* PANEL 2: LEAVE APPROVALS FULL TABLE */}
      {activeTab === 'leave' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-calendar-check" style={{ color: '#0077b6', marginRight: 8 }}></i>
              Workforce Leave Applications & Verification
            </div>
            <button
              className="btn btn-primary btn-sm"
              onClick={() => setActiveModule('leave-attendance')}
            >
              Open Leave & Attendance Hub <i className="fa-solid fa-arrow-right ms-1"></i>
            </button>
          </div>
          <div style={{ padding: 16 }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
              <thead>
                <tr style={{ borderBottom: '1px solid var(--border)', textAlign: 'left', color: 'var(--text-muted)', fontSize: 11 }}>
                  <th style={{ padding: '10px' }}>Ref ID</th>
                  <th style={{ padding: '10px' }}>Staff Name</th>
                  <th style={{ padding: '10px' }}>State Office</th>
                  <th style={{ padding: '10px' }}>Leave Type</th>
                  <th style={{ padding: '10px' }}>Duration</th>
                  <th style={{ padding: '10px' }}>Relieving Staff</th>
                  <th style={{ padding: '10px' }}>Status</th>
                  <th style={{ padding: '10px', textAlign: 'right' }}>HR Actions</th>
                </tr>
              </thead>
              <tbody>
                {leaveRequests.map((row) => (
                  <tr key={row.id} style={{ borderBottom: '1px solid var(--border)' }}>
                    <td style={{ padding: '12px 10px', fontWeight: 700, color: '#0077b6' }}>{row.id}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <div style={{ fontWeight: 600 }}>{row.staffName}</div>
                      <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>{row.dept}</div>
                    </td>
                    <td style={{ padding: '12px 10px' }}>{row.state}</td>
                    <td style={{ padding: '12px 10px' }}>{row.leaveType}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <div>{row.startDate} - {row.endDate}</div>
                      <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>{row.days} working days</div>
                    </td>
                    <td style={{ padding: '12px 10px' }}>{row.relievingStaff}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <span className={`pill ${row.status === 'Approved' ? 'pill-closed' : row.status === 'Pending' ? 'pill-progress' : 'pill-open'}`}>
                        {row.status}
                      </span>
                    </td>
                    <td style={{ padding: '12px 10px', textAlign: 'right' }}>
                      {row.status === 'Pending' ? (
                        <div style={{ display: 'inline-flex', gap: 6 }}>
                          <button
                            className="btn btn-sm btn-primary"
                            style={{ padding: '3px 8px', fontSize: 11 }}
                            onClick={() => handleApproveLeave(row.id, row.staffName)}
                          >
                            Approve
                          </button>
                          <button
                            className="btn btn-sm btn-outline"
                            style={{ padding: '3px 8px', fontSize: 11, color: '#dc2626', borderColor: '#fca5a5' }}
                            onClick={() => handleRejectLeave(row.id, row.staffName)}
                          >
                            Reject
                          </button>
                        </div>
                      ) : (
                        <span style={{ fontSize: 11, color: 'var(--text-muted)', fontStyle: 'italic' }}>Verified</span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* PANEL 3: ATTENDANCE PULSE */}
      {activeTab === 'attendance' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div>
              <div className="card-title">
                <i className="fa-solid fa-clock" style={{ color: '#059669', marginRight: 8 }}></i>
                Today's Live Attendance Feed
              </div>
              <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2 }}>
                Real-time Clock-In sync with Attendify Pro™ across state cluster hubs
              </div>
            </div>
            <div style={{ display: 'flex', gap: 8 }}>
              <button
                className="btn btn-sm btn-outline"
                onClick={() => showInfo('Attendance Synced', 'Attendance records refreshed from Attendify Pro cloud.')}
              >
                <i className="fa-solid fa-rotate me-1"></i> Sync Attendify Pro
              </button>
              <button
                className="btn btn-sm btn-primary"
                onClick={() => setActiveModule('leave-attendance')}
              >
                Master Attendance Roster
              </button>
            </div>
          </div>
          <div style={{ padding: 16 }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
              <thead>
                <tr style={{ borderBottom: '1px solid var(--border)', textAlign: 'left', color: 'var(--text-muted)', fontSize: 11 }}>
                  <th style={{ padding: '10px' }}>Log Ref</th>
                  <th style={{ padding: '10px' }}>Staff Name</th>
                  <th style={{ padding: '10px' }}>State Office</th>
                  <th style={{ padding: '10px' }}>Designation</th>
                  <th style={{ padding: '10px' }}>Clock-In Time</th>
                  <th style={{ padding: '10px' }}>Verification Method</th>
                  <th style={{ padding: '10px' }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {todayAttendance.map((rec) => (
                  <tr key={rec.id} style={{ borderBottom: '1px solid var(--border)' }}>
                    <td style={{ padding: '12px 10px', fontWeight: 700, color: 'var(--accent)' }}>{rec.id}</td>
                    <td style={{ padding: '12px 10px', fontWeight: 600 }}>{rec.staffName}</td>
                    <td style={{ padding: '12px 10px' }}>{rec.state}</td>
                    <td style={{ padding: '12px 10px' }}>{rec.role}</td>
                    <td style={{ padding: '12px 10px', fontFamily: 'monospace', fontWeight: 700 }}>{rec.checkIn}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <span style={{ fontSize: 11, background: '#f0f7fd', padding: '2px 7px', borderRadius: 4, border: '1px solid #c8dff0', color: '#006CA5' }}>
                        <i className="fa-solid fa-fingerprint me-1"></i> {rec.source}
                      </span>
                    </td>
                    <td style={{ padding: '12px 10px' }}>
                      <span className={`pill ${rec.status === 'On-Time' ? 'pill-closed' : rec.status === 'Late' ? 'pill-open' : 'pill-progress'}`}>
                        {rec.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* PANEL 4: COMPLAINTS FEED (STRICT VIEW-ONLY FOR HR) */}
      {activeTab === 'complaints' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div>
              <div className="card-title" style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                <i className="fa-solid fa-shield-halved" style={{ color: '#dc2626' }}></i>
                <span>Employee Grievances & Misconduct Watchlist</span>
                <span style={{
                  background: '#e0f2fe',
                  color: '#02367B',
                  fontSize: 10,
                  fontWeight: 800,
                  padding: '2px 8px',
                  borderRadius: 12,
                  border: '1px solid #bae6fd'
                }}>
                  HR VIEW-ONLY PERMISSION
                </span>
              </div>
              <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2 }}>
                Safeguarding & whistleblower cases forwarded to HR for informational awareness. Triage and escalation authority belongs exclusively to the Director of Compliance.
              </div>
            </div>
            <button
              className="btn btn-sm btn-outline"
              onClick={() => setActiveModule('complaints')}
            >
              Open Full Complaints Register (View-Only)
            </button>
          </div>
          <div style={{ padding: 16 }}>
            <div style={{
              background: '#f8fafc',
              border: '1px solid #e2e8f0',
              padding: '10px 14px',
              borderRadius: 6,
              fontSize: 12,
              color: '#475569',
              marginBottom: 16,
              display: 'flex',
              alignItems: 'center',
              gap: 8
            }}>
              <i className="fa-solid fa-circle-info" style={{ color: '#0077b6', fontSize: 14 }}></i>
              <span>
                <strong>HR View-Only Policy:</strong> Per CCCRN Governance SOP, HR personnel can review case references, affected duty stations, and severity classifications. Case triage, sanction recommendations, and investigator appointments are managed by DoC.
              </span>
            </div>

            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
              <thead>
                <tr style={{ borderBottom: '1px solid var(--border)', textAlign: 'left', color: 'var(--text-muted)', fontSize: 11 }}>
                  <th style={{ padding: '10px' }}>Complaint Ref</th>
                  <th style={{ padding: '10px' }}>Date Logged</th>
                  <th style={{ padding: '10px' }}>State / Duty Station</th>
                  <th style={{ padding: '10px' }}>Grievance Category</th>
                  <th style={{ padding: '10px' }}>Parties / Scope</th>
                  <th style={{ padding: '10px' }}>Investigator Lead</th>
                  <th style={{ padding: '10px' }}>Severity</th>
                  <th style={{ padding: '10px' }}>Case Status</th>
                  <th style={{ padding: '10px', textAlign: 'center' }}>HR Permission</th>
                </tr>
              </thead>
              <tbody>
                {complaintsFeed.map((row) => (
                  <tr key={row.ref} style={{ borderBottom: '1px solid var(--border)' }}>
                    <td style={{ padding: '12px 10px', fontWeight: 700, color: 'var(--accent)' }}>{row.ref}</td>
                    <td style={{ padding: '12px 10px', color: 'var(--text-muted)' }}>{row.date}</td>
                    <td style={{ padding: '12px 10px', fontWeight: 600 }}>{row.state}</td>
                    <td style={{ padding: '12px 10px' }}>{row.category}</td>
                    <td style={{ padding: '12px 10px', color: 'var(--text-dim)', fontSize: 11 }}>{row.parties}</td>
                    <td style={{ padding: '12px 10px' }}>{row.investigator}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <span className={`pill ${row.severity === 'Critical' ? 'pill-open' : row.severity === 'High' ? 'pill-progress' : 'pill-closed'}`}>
                        {row.severity}
                      </span>
                    </td>
                    <td style={{ padding: '12px 10px' }}>
                      <span style={{
                        padding: '3px 8px',
                        borderRadius: 12,
                        fontSize: 11,
                        fontWeight: 700,
                        background: row.status === 'Closed' ? '#d1fae5' : '#fee2e2',
                        color: row.status === 'Closed' ? '#059669' : '#dc2626'
                      }}>
                        {row.status}
                      </span>
                    </td>
                    <td style={{ padding: '12px 10px', textAlign: 'center' }}>
                      <span style={{ fontSize: 11, color: 'var(--text-muted)', background: '#f1f5f9', padding: '3px 8px', borderRadius: 4 }}>
                        <i className="fa-solid fa-eye me-1"></i> View Only
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  );
};
