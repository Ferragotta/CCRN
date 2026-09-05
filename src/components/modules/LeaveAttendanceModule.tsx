import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { useToast } from '../../context/ToastContext';

interface LeaveApplication {
  id: string;
  staffName: string;
  staffEmail: string;
  state: string;
  department: string;
  leaveType: 'Annual Leave' | 'Sick Leave' | 'Casual Leave' | 'Maternity Leave' | 'Study / Exam';
  startDate: string;
  endDate: string;
  workingDays: number;
  relievingStaff: string;
  reason: string;
  status: 'Pending HR' | 'Approved' | 'Rejected';
  appliedOn: string;
}

interface AttendanceLogItem {
  id: string;
  staffName: string;
  state: string;
  department: string;
  date: string;
  clockIn: string;
  clockOut: string;
  locationStatus: 'Office Biometrics' | 'Field Geo-Fence' | 'Approved Remote';
  attendanceStatus: 'On-Time' | 'Late' | 'Field Duty' | 'Absent';
}

interface LeaveBalance {
  staffName: string;
  state: string;
  annualTotal: number;
  annualUsed: number;
  annualRemaining: number;
  casualUsed: number;
  sickUsed: number;
}

export const LeaveAttendanceModule: React.FC = () => {
  const { currentUser } = useAuth();
  const { showSuccess, showInfo } = useToast();

  const [activeTab, setActiveTab] = useState<'applications' | 'attendance' | 'balances'>('applications');
  const [filterState, setFilterState] = useState<string>('All');
  const [showApplyModal, setShowApplyModal] = useState<boolean>(false);

  // New application form state
  const [newLeaveType, setNewLeaveType] = useState<LeaveApplication['leaveType']>('Annual Leave');
  const [newStartDate, setNewStartDate] = useState<string>('2026-03-16');
  const [newEndDate, setNewEndDate] = useState<string>('2026-03-27');
  const [newDays, setNewDays] = useState<number>(10);
  const [newReliever, setNewReliever] = useState<string>('Dr. Usman Bello');
  const [newReason, setNewReason] = useState<string>('Annual statutory leave entitlement');

  // Leave Applications Register
  const [applications, setApplications] = useState<LeaveApplication[]>([
    {
      id: 'LVE-2026-042',
      staffName: 'Amina Kyari',
      staffEmail: 'amina.kyari@cccrn.org',
      state: 'Borno',
      department: 'Clinical Services',
      leaveType: 'Annual Leave',
      startDate: '10 Mar 2026',
      endDate: '24 Mar 2026',
      workingDays: 10,
      relievingStaff: 'Dr. Usman Bello',
      reason: 'Scheduled rest and annual family break.',
      status: 'Pending HR',
      appliedOn: '02 Mar 2026'
    },
    {
      id: 'LVE-2026-041',
      staffName: 'Emeka Okafor',
      staffEmail: 'emeka.okafor@cccrn.org',
      state: 'Abuja FCT',
      department: 'Procurement & Logistics',
      leaveType: 'Casual Leave',
      startDate: '06 Mar 2026',
      endDate: '09 Mar 2026',
      workingDays: 2,
      relievingStaff: 'Fatima Sanusi',
      reason: 'Personal urgent family commitment.',
      status: 'Pending HR',
      appliedOn: '01 Mar 2026'
    },
    {
      id: 'LVE-2026-040',
      staffName: 'Biodun Alade',
      staffEmail: 'biodun.alade@cccrn.org',
      state: 'Lagos',
      department: 'Human Resources',
      leaveType: 'Study / Exam',
      startDate: '15 Mar 2026',
      endDate: '20 Mar 2026',
      workingDays: 5,
      relievingStaff: 'Ngozi Adeyemi',
      reason: 'CIPM Professional Human Resource Certification Exams.',
      status: 'Approved',
      appliedOn: '26 Feb 2026'
    },
    {
      id: 'LVE-2026-039',
      staffName: 'Aliyu Usman',
      staffEmail: 'aliyu.usman@cccrn.org',
      state: 'Kano',
      department: 'Security & Field Ops',
      leaveType: 'Sick Leave',
      startDate: '27 Feb 2026',
      endDate: '03 Mar 2026',
      workingDays: 4,
      relievingStaff: 'Musa Ibrahim',
      reason: 'Medical recovery under clinician care.',
      status: 'Approved',
      appliedOn: '27 Feb 2026'
    },
    {
      id: 'LVE-2026-038',
      staffName: 'Chidi Okeke',
      staffEmail: 'chidi.okeke@cccrn.org',
      state: 'Rivers',
      department: 'Strategy & M&E',
      leaveType: 'Casual Leave',
      startDate: '20 Feb 2026',
      endDate: '22 Feb 2026',
      workingDays: 2,
      relievingStaff: 'Chidi Okafor',
      reason: 'Family emergency.',
      status: 'Approved',
      appliedOn: '18 Feb 2026'
    }
  ]);

  // Today's Live Attendance Feed
  const [attendanceLogs] = useState<AttendanceLogItem[]>([
    { id: 'LOG-881', staffName: 'Ngozi Adeyemi', state: 'Lagos', department: 'Executive Lead', date: '03 Mar 2026', clockIn: '07:46 AM', clockOut: '--', locationStatus: 'Office Biometrics', attendanceStatus: 'On-Time' },
    { id: 'LOG-882', staffName: 'Amaka Okonkwo', state: 'Abuja FCT', department: 'Finance & Compliance', date: '03 Mar 2026', clockIn: '07:51 AM', clockOut: '--', locationStatus: 'Office Biometrics', attendanceStatus: 'On-Time' },
    { id: 'LOG-883', staffName: 'Chidi Okafor', state: 'Rivers', department: 'Clinical Support', date: '03 Mar 2026', clockIn: '07:58 AM', clockOut: '--', locationStatus: 'Field Geo-Fence', attendanceStatus: 'On-Time' },
    { id: 'LOG-884', staffName: 'Musa Ibrahim', state: 'Kano', department: 'State Cluster Head', date: '03 Mar 2026', clockIn: '08:16 AM', clockOut: '--', locationStatus: 'Office Biometrics', attendanceStatus: 'Late' },
    { id: 'LOG-885', staffName: 'Fatima Bakura', state: 'Borno', department: 'Field Operations', date: '03 Mar 2026', clockIn: '08:04 AM', clockOut: '--', locationStatus: 'Field Geo-Fence', attendanceStatus: 'Field Duty' },
    { id: 'LOG-886', staffName: 'Hassan Suleiman', state: 'Kaduna', department: 'Logistics Lead', date: '03 Mar 2026', clockIn: '07:54 AM', clockOut: '--', locationStatus: 'Office Biometrics', attendanceStatus: 'On-Time' }
  ]);

  // Staff Leave Balances
  const [balances] = useState<LeaveBalance[]>([
    { staffName: 'Ngozi Adeyemi (Lagos)', state: 'Lagos', annualTotal: 21, annualUsed: 5, annualRemaining: 16, casualUsed: 1, sickUsed: 0 },
    { staffName: 'Amaka Okonkwo (Abuja)', state: 'Abuja FCT', annualTotal: 21, annualUsed: 7, annualRemaining: 14, casualUsed: 2, sickUsed: 1 },
    { staffName: 'Musa Ibrahim (Kano)', state: 'Kano', annualTotal: 21, annualUsed: 4, annualRemaining: 17, casualUsed: 1, sickUsed: 2 },
    { staffName: 'Chidi Okafor (Rivers)', state: 'Rivers', annualTotal: 21, annualUsed: 6, annualRemaining: 15, casualUsed: 0, sickUsed: 0 },
    { staffName: 'Hassan Suleiman (Kaduna)', state: 'Kaduna', annualTotal: 21, annualUsed: 8, annualRemaining: 13, casualUsed: 3, sickUsed: 1 },
    { staffName: 'Fatima Bakura (Borno)', state: 'Borno', annualTotal: 21, annualUsed: 2, annualRemaining: 19, casualUsed: 1, sickUsed: 0 }
  ]);

  const handleApprove = (id: string, staffName: string) => {
    setApplications(prev => prev.map(a => a.id === id ? { ...a, status: 'Approved' } : a));
    showSuccess(`Leave Approved: ${id}`, `Leave request for ${staffName} approved. Notification dispatched.`);
  };

  const handleReject = (id: string, staffName: string) => {
    setApplications(prev => prev.map(a => a.id === id ? { ...a, status: 'Rejected' } : a));
    showInfo(`Leave Rejected: ${id}`, `Leave request for ${staffName} has been rejected.`);
  };

  const handleSubmitLeave = (e: React.FormEvent) => {
    e.preventDefault();
    const newId = `LVE-2026-0${applications.length + 43}`;
    const newApp: LeaveApplication = {
      id: newId,
      staffName: currentUser?.name || 'Staff Member',
      staffEmail: currentUser?.email || 'staff@cccrn.org',
      state: 'Abuja FCT',
      department: 'Corporate Headquarters',
      leaveType: newLeaveType,
      startDate: newStartDate,
      endDate: newEndDate,
      workingDays: Number(newDays),
      relievingStaff: newReliever,
      reason: newReason,
      status: 'Pending HR',
      appliedOn: '03 Mar 2026'
    };
    setApplications([newApp, ...applications]);
    setShowApplyModal(false);
    showSuccess(`Leave Submitted: ${newId}`, `Your leave application has been submitted to HR.`);
  };

  const filteredApps = filterState === 'All' ? applications : applications.filter(a => a.state.includes(filterState));

  return (
    <div className="leave-attendance-module" style={{ paddingBottom: 40 }}>
      {/* ── 1. MODULE HEADER ── */}
      <div style={{
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        flexWrap: 'wrap',
        gap: 16,
        marginBottom: 20
      }}>
        <div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
            <h1 style={{
              fontFamily: "'Plus Jakarta Sans', sans-serif",
              fontSize: 22,
              fontWeight: 800,
              color: 'var(--text)',
              margin: 0
            }}>
              Leave & Attendance Management
            </h1>
            <span style={{
              background: '#059669',
              color: '#ffffff',
              fontSize: 10,
              fontWeight: 800,
              padding: '2px 8px',
              borderRadius: 12,
              letterSpacing: '0.5px'
            }}>
              ATTENDIFY PRO™ LIVE SYNC
            </span>
          </div>
          <div style={{ fontSize: 13, color: 'var(--text-muted)', marginTop: 4 }}>
            Biometric clock-in records, staff leave entitlement tracking, and workforce attendance telemetry
          </div>
        </div>

        <div style={{ display: 'flex', gap: 10 }}>
          <button
            className="btn btn-primary"
            onClick={() => setShowApplyModal(true)}
            style={{ fontSize: 12, fontWeight: 700 }}
          >
            <i className="fa-solid fa-calendar-plus me-1"></i> Apply for Leave
          </button>
        </div>
      </div>

      {/* ── 2. 4 STAT CARDS ── */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
        gap: 16,
        marginBottom: 24
      }}>
        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #0077b6', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Total Leave Applications
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#0077b6', lineHeight: 1 }}>
            {applications.length}
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            Quarter 1, FY2026
          </div>
        </div>

        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #d97706', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Pending HR Verification
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#d97706', lineHeight: 1 }}>
            {applications.filter(a => a.status === 'Pending HR').length}
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            Action required by HR Manager
          </div>
        </div>

        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #059669', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Approved Leaves
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#059669', lineHeight: 1 }}>
            {applications.filter(a => a.status === 'Approved').length}
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            Active roster updated
          </div>
        </div>

        <div className="card" style={{ marginBottom: 0, borderLeft: '4px solid #7c3aed', padding: '16px 20px' }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: 6 }}>
            Today's Attendance Rate
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: '#7c3aed', lineHeight: 1 }}>
            94.2%
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 6 }}>
            462 of 490 clocked in on time
          </div>
        </div>
      </div>

      {/* ── 3. MODULE SUB-TABS ── */}
      <div style={{
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        borderBottom: '1px solid var(--border)',
        marginBottom: 20
      }}>
        <div style={{ display: 'flex', gap: 8 }}>
          <button
            className={`tab ${activeTab === 'applications' ? 'active' : ''}`}
            onClick={() => setActiveTab('applications')}
            style={{
              padding: '10px 18px',
              border: 'none',
              background: 'none',
              borderBottom: activeTab === 'applications' ? '3px solid #0077b6' : '3px solid transparent',
              color: activeTab === 'applications' ? '#0077b6' : 'var(--text-muted)',
              fontWeight: 700,
              fontSize: 13,
              cursor: 'pointer'
            }}
          >
            <i className="fa-solid fa-file-signature me-1"></i> Leave Applications ({applications.length})
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
            <i className="fa-solid fa-fingerprint me-1"></i> Attendify Biometrics Log ({attendanceLogs.length})
          </button>

          <button
            className={`tab ${activeTab === 'balances' ? 'active' : ''}`}
            onClick={() => setActiveTab('balances')}
            style={{
              padding: '10px 18px',
              border: 'none',
              background: 'none',
              borderBottom: activeTab === 'balances' ? '3px solid #0077b6' : '3px solid transparent',
              color: activeTab === 'balances' ? '#0077b6' : 'var(--text-muted)',
              fontWeight: 700,
              fontSize: 13,
              cursor: 'pointer'
            }}
          >
            <i className="fa-solid fa-chart-pie me-1"></i> Staff Entitlement Balances
          </button>
        </div>

        {/* State filter */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <span style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)' }}>STATE:</span>
          <select
            value={filterState}
            onChange={(e) => setFilterState(e.target.value)}
            style={{
              padding: '6px 12px',
              borderRadius: 6,
              border: '1px solid var(--border)',
              fontSize: 12,
              background: '#ffffff',
              color: 'var(--text)'
            }}
          >
            <option value="All">All 6 States</option>
            <option value="Lagos">Lagos</option>
            <option value="Abuja">Abuja FCT</option>
            <option value="Kano">Kano</option>
            <option value="Rivers">Rivers</option>
            <option value="Kaduna">Kaduna</option>
            <option value="Borno">Borno</option>
          </select>
        </div>
      </div>

      {/* ── 4. TAB PANELS ── */}

      {/* TAB 1: APPLICATIONS TABLE */}
      {activeTab === 'applications' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-table-list" style={{ color: '#0077b6', marginRight: 8 }}></i>
              Workforce Leave Applications Register
            </div>
            <span style={{ fontSize: 12, color: 'var(--text-muted)' }}>
              Showing {filteredApps.length} records
            </span>
          </div>
          <div style={{ padding: 16 }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
              <thead>
                <tr style={{ borderBottom: '1px solid var(--border)', textAlign: 'left', color: 'var(--text-muted)', fontSize: 11 }}>
                  <th style={{ padding: '10px' }}>Request ID</th>
                  <th style={{ padding: '10px' }}>Staff & Department</th>
                  <th style={{ padding: '10px' }}>Duty Station</th>
                  <th style={{ padding: '10px' }}>Leave Category</th>
                  <th style={{ padding: '10px' }}>Schedule & Working Days</th>
                  <th style={{ padding: '10px' }}>Relieving Colleague</th>
                  <th style={{ padding: '10px' }}>Status</th>
                  <th style={{ padding: '10px', textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {filteredApps.map((app) => (
                  <tr key={app.id} style={{ borderBottom: '1px solid var(--border)' }}>
                    <td style={{ padding: '12px 10px', fontWeight: 700, color: '#0077b6' }}>{app.id}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <div style={{ fontWeight: 600 }}>{app.staffName}</div>
                      <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>{app.department}</div>
                    </td>
                    <td style={{ padding: '12px 10px' }}>{app.state}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <span style={{
                        padding: '3px 8px',
                        borderRadius: 4,
                        fontSize: 11,
                        fontWeight: 600,
                        background: app.leaveType === 'Annual Leave' ? '#e0f2fe' : app.leaveType === 'Sick Leave' ? '#fee2e2' : '#fef3c7',
                        color: app.leaveType === 'Annual Leave' ? '#02367B' : app.leaveType === 'Sick Leave' ? '#dc2626' : '#d97706'
                      }}>
                        {app.leaveType}
                      </span>
                    </td>
                    <td style={{ padding: '12px 10px' }}>
                      <div style={{ fontWeight: 600 }}>{app.startDate} — {app.endDate}</div>
                      <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>{app.workingDays} working days</div>
                    </td>
                    <td style={{ padding: '12px 10px' }}>{app.relievingStaff}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <span className={`pill ${app.status === 'Approved' ? 'pill-closed' : app.status === 'Pending HR' ? 'pill-progress' : 'pill-open'}`}>
                        {app.status}
                      </span>
                    </td>
                    <td style={{ padding: '12px 10px', textAlign: 'right' }}>
                      {app.status === 'Pending HR' ? (
                        <div style={{ display: 'inline-flex', gap: 6 }}>
                          <button
                            className="btn btn-sm btn-primary"
                            style={{ padding: '4px 10px', fontSize: 11 }}
                            onClick={() => handleApprove(app.id, app.staffName)}
                          >
                            Approve
                          </button>
                          <button
                            className="btn btn-sm btn-outline"
                            style={{ padding: '4px 10px', fontSize: 11, color: '#dc2626', borderColor: '#fca5a5' }}
                            onClick={() => handleReject(app.id, app.staffName)}
                          >
                            Reject
                          </button>
                        </div>
                      ) : (
                        <span style={{ fontSize: 11, color: 'var(--text-muted)', fontStyle: 'italic' }}>
                          Completed
                        </span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* TAB 2: BIOMETRIC LOGS */}
      {activeTab === 'attendance' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-fingerprint" style={{ color: '#059669', marginRight: 8 }}></i>
              Attendify Pro™ Live Clock-in Telemetry
            </div>
            <button
              className="btn btn-sm btn-outline"
              onClick={() => showSuccess('Terminals Synced', 'Biometric terminal sync verified.')}
            >
              <i className="fa-solid fa-rotate me-1"></i> Refresh Terminals
            </button>
          </div>
          <div style={{ padding: 16 }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
              <thead>
                <tr style={{ borderBottom: '1px solid var(--border)', textAlign: 'left', color: 'var(--text-muted)', fontSize: 11 }}>
                  <th style={{ padding: '10px' }}>Log ID</th>
                  <th style={{ padding: '10px' }}>Staff Member</th>
                  <th style={{ padding: '10px' }}>Duty Office</th>
                  <th style={{ padding: '10px' }}>Department</th>
                  <th style={{ padding: '10px' }}>Clock-In Time</th>
                  <th style={{ padding: '10px' }}>Clock-Out Time</th>
                  <th style={{ padding: '10px' }}>Terminal Method</th>
                  <th style={{ padding: '10px' }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {attendanceLogs.map((log) => (
                  <tr key={log.id} style={{ borderBottom: '1px solid var(--border)' }}>
                    <td style={{ padding: '12px 10px', fontWeight: 700, color: '#0077b6' }}>{log.id}</td>
                    <td style={{ padding: '12px 10px', fontWeight: 600 }}>{log.staffName}</td>
                    <td style={{ padding: '12px 10px' }}>{log.state}</td>
                    <td style={{ padding: '12px 10px' }}>{log.department}</td>
                    <td style={{ padding: '12px 10px', fontFamily: 'monospace', fontWeight: 700, color: '#02367B' }}>{log.clockIn}</td>
                    <td style={{ padding: '12px 10px', color: 'var(--text-muted)' }}>{log.clockOut}</td>
                    <td style={{ padding: '12px 10px' }}>
                      <span style={{ fontSize: 11, background: '#f0f7fd', padding: '3px 8px', borderRadius: 4, border: '1px solid #c8dff0', color: '#0077b6' }}>
                        {log.locationStatus}
                      </span>
                    </td>
                    <td style={{ padding: '12px 10px' }}>
                      <span className={`pill ${log.attendanceStatus === 'On-Time' ? 'pill-closed' : log.attendanceStatus === 'Late' ? 'pill-open' : 'pill-progress'}`}>
                        {log.attendanceStatus}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* TAB 3: LEAVE BALANCES */}
      {activeTab === 'balances' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-chart-pie" style={{ color: '#7c3aed', marginRight: 8 }}></i>
              Statutory Leave Balances (2026 Cycle)
            </div>
            <span style={{ fontSize: 12, color: 'var(--text-muted)' }}>
              Standard: 21 Days Annual, 5 Days Casual, 12 Days Sick Leave
            </span>
          </div>
          <div style={{ padding: 16 }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 12 }}>
              <thead>
                <tr style={{ borderBottom: '1px solid var(--border)', textAlign: 'left', color: 'var(--text-muted)', fontSize: 11 }}>
                  <th style={{ padding: '10px' }}>Staff Name</th>
                  <th style={{ padding: '10px' }}>Duty Station</th>
                  <th style={{ padding: '10px' }}>Annual Leave Total</th>
                  <th style={{ padding: '10px' }}>Days Utilized</th>
                  <th style={{ padding: '10px' }}>Remaining Days</th>
                  <th style={{ padding: '10px' }}>Casual Used</th>
                  <th style={{ padding: '10px' }}>Sick Days Taken</th>
                  <th style={{ padding: '10px' }}>Entitlement Progress</th>
                </tr>
              </thead>
              <tbody>
                {balances.map((b, idx) => {
                  const pct = Math.round((b.annualRemaining / b.annualTotal) * 100);
                  return (
                    <tr key={idx} style={{ borderBottom: '1px solid var(--border)' }}>
                      <td style={{ padding: '12px 10px', fontWeight: 600 }}>{b.staffName}</td>
                      <td style={{ padding: '12px 10px' }}>{b.state}</td>
                      <td style={{ padding: '12px 10px' }}>{b.annualTotal} days</td>
                      <td style={{ padding: '12px 10px', color: '#d97706', fontWeight: 700 }}>{b.annualUsed} days</td>
                      <td style={{ padding: '12px 10px', color: '#059669', fontWeight: 800 }}>{b.annualRemaining} days</td>
                      <td style={{ padding: '12px 10px' }}>{b.casualUsed} / 5</td>
                      <td style={{ padding: '12px 10px' }}>{b.sickUsed} / 12</td>
                      <td style={{ padding: '12px 10px' }}>
                        <div style={{ width: 120, height: 8, background: '#e2e8f0', borderRadius: 4, overflow: 'hidden' }}>
                          <div style={{ width: `${pct}%`, height: '100%', background: pct < 30 ? '#dc2626' : '#059669' }}></div>
                        </div>
                        <span style={{ fontSize: 10, color: 'var(--text-muted)' }}>{pct}% available</span>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* ── 5. APPLY FOR LEAVE MODAL ── */}
      {showApplyModal && (
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
            borderRadius: 14,
            border: '1px solid var(--border)',
            boxShadow: '0 25px 50px rgba(0,0,0,0.25)',
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
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 800, color: '#02367B' }}>
                <i className="fa-solid fa-calendar-plus me-2"></i> Submit Leave Request
              </div>
              <button
                onClick={() => setShowApplyModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleSubmitLeave} style={{ padding: 20 }}>
              <div style={{ marginBottom: 14 }}>
                <label style={{ display: 'block', fontSize: 11, fontWeight: 700, color: '#02367B', marginBottom: 4, textTransform: 'uppercase' }}>
                  Leave Type:
                </label>
                <select
                  value={newLeaveType}
                  onChange={(e) => setNewLeaveType(e.target.value as any)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: 6, border: '1px solid var(--border)', fontSize: 12 }}
                >
                  <option value="Annual Leave">Annual Leave (21 Days Statutory)</option>
                  <option value="Casual Leave">Casual Leave (Short Notice)</option>
                  <option value="Sick Leave">Sick Leave (Medical Certificate Required)</option>
                  <option value="Study / Exam">Study / Examination Leave</option>
                  <option value="Maternity Leave">Maternity / Paternity Leave</option>
                </select>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 14 }}>
                <div>
                  <label style={{ display: 'block', fontSize: 11, fontWeight: 700, color: '#02367B', marginBottom: 4, textTransform: 'uppercase' }}>
                    Start Date:
                  </label>
                  <input
                    type="date"
                    value={newStartDate}
                    onChange={(e) => setNewStartDate(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 12px', borderRadius: 6, border: '1px solid var(--border)', fontSize: 12 }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: 11, fontWeight: 700, color: '#02367B', marginBottom: 4, textTransform: 'uppercase' }}>
                    End Date:
                  </label>
                  <input
                    type="date"
                    value={newEndDate}
                    onChange={(e) => setNewEndDate(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 12px', borderRadius: 6, border: '1px solid var(--border)', fontSize: 12 }}
                  />
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1.5fr', gap: 12, marginBottom: 14 }}>
                <div>
                  <label style={{ display: 'block', fontSize: 11, fontWeight: 700, color: '#02367B', marginBottom: 4, textTransform: 'uppercase' }}>
                    Working Days:
                  </label>
                  <input
                    type="number"
                    min="1"
                    max="30"
                    value={newDays}
                    onChange={(e) => setNewDays(Number(e.target.value))}
                    required
                    style={{ width: '100%', padding: '8px 12px', borderRadius: 6, border: '1px solid var(--border)', fontSize: 12 }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: 11, fontWeight: 700, color: '#02367B', marginBottom: 4, textTransform: 'uppercase' }}>
                    Relieving Colleague:
                  </label>
                  <input
                    type="text"
                    value={newReliever}
                    onChange={(e) => setNewReliever(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 12px', borderRadius: 6, border: '1px solid var(--border)', fontSize: 12 }}
                  />
                </div>
              </div>

              <div style={{ marginBottom: 18 }}>
                <label style={{ display: 'block', fontSize: 11, fontWeight: 700, color: '#02367B', marginBottom: 4, textTransform: 'uppercase' }}>
                  Handover Notes / Justification:
                </label>
                <textarea
                  value={newReason}
                  onChange={(e) => setNewReason(e.target.value)}
                  rows={3}
                  required
                  style={{ width: '100%', padding: '8px 12px', borderRadius: 6, border: '1px solid var(--border)', fontSize: 12, resize: 'vertical' }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button
                  type="button"
                  className="btn btn-outline"
                  onClick={() => setShowApplyModal(false)}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="btn btn-primary"
                >
                  <i className="fa-solid fa-paper-plane me-1"></i> Submit to HR
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
