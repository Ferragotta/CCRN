import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { InteractiveBarChart, InteractiveTrendChart, RadialProgressGauge } from '../common/Charts';
import { DataTable, Column } from '../common/DataTable';

interface ComplaintRow {
  id: string;
  state: string;
  cat: string;
  sev: string;
  status: string;
  date: string;
}

export const ExecutiveDashboard: React.FC = () => {
  const { setActiveModule } = useAuth();
  const [hoveredRiskCell, setHoveredRiskCell] = useState<{ like: number; imp: number; rating: string } | null>(null);

  // Category Bar Chart Data
  const barData = [
    { label: 'Fraud / Advance', value: 14, color: '#0077b6' },
    { label: 'Conduct / Ethics', value: 9, color: '#dc2626' },
    { label: 'Procurement', value: 7, color: '#d97706' },
    { label: 'Safety / Field', value: 5, color: '#059669' },
    { label: 'PSEA / Harass.', value: 4, color: '#7c3aed' },
    { label: 'Asset Custody', value: 6, color: '#0891b2' }
  ];

  // Trend Chart Data (6-month historical trajectory)
  const trendData = [
    { label: 'Oct 25', value: 18 },
    { label: 'Nov 25', value: 14 },
    { label: 'Dec 25', value: 19 },
    { label: 'Jan 26', value: 12 },
    { label: 'Feb 26', value: 9 },
    { label: 'Mar 26', value: 5 }
  ];

  // Recent Complaints Data
  const recentComplaints: ComplaintRow[] = [
    { id: 'CMP-047', state: 'Lagos (Cluster A)', cat: 'Fraud / Advance', sev: 'Critical', status: 'Open', date: '28 Feb 2026' },
    { id: 'CMP-046', state: 'Kano (Cluster B)', cat: 'Conduct / Conflict', sev: 'High', status: 'In Progress', date: '26 Feb 2026' },
    { id: 'CMP-045', state: 'Abuja FCT', cat: 'Policy / Proc.', sev: 'Medium', status: 'Closed', date: '24 Feb 2026' },
    { id: 'CMP-044', state: 'Rivers (Cluster C)', cat: 'Safety / Field', sev: 'Low', status: 'Open', date: '22 Feb 2026' },
    { id: 'CMP-043', state: 'Kaduna (Cluster A)', cat: 'Finance / Asset', sev: 'High', status: 'In Progress', date: '20 Feb 2026' }
  ];

  // Columns for DataTable
  const complaintColumns: Column<ComplaintRow>[] = [
    {
      key: 'id',
      header: 'Complaint Ref',
      sortable: true,
      render: (item) => <span style={{ fontWeight: 700, color: 'var(--accent)' }}>{item.id}</span>
    },
    { key: 'state', header: 'State / Cluster', sortable: true },
    { key: 'cat', header: 'Category' },
    {
      key: 'sev',
      header: 'Severity',
      sortable: true,
      render: (item) => {
        const bg = item.sev === 'Critical' ? '#fee2e2' : item.sev === 'High' ? '#fef3c7' : '#dcfce7';
        const color = item.sev === 'Critical' ? '#991b1b' : item.sev === 'High' ? '#92400e' : '#166534';
        return <span style={{ padding: '2px 8px', borderRadius: 12, fontSize: 10, fontWeight: 700, background: bg, color }}>{item.sev}</span>;
      }
    },
    {
      key: 'status',
      header: 'Status',
      sortable: true,
      render: (item) => (
        <span className={`pill ${item.status === 'Closed' ? 'pill-closed' : item.status === 'In Progress' ? 'pill-progress' : 'pill-open'}`}>
          {item.status}
        </span>
      )
    },
    { key: 'date', header: 'Logged Date', sortable: true },
    {
      key: 'actions',
      header: 'Action',
      render: () => (
        <button className="btn btn-outline btn-sm" style={{ padding: '2px 8px', fontSize: 10 }} onClick={() => setActiveModule('complaints')}>
          View Dossier
        </button>
      )
    }
  ];

  // 5x5 Matrix definition
  const riskGrid = [
    [ { r: 'r4', dot: true }, { r: 'r4', dot: true }, { r: 'r3', dot: true }, { r: 'r2', dot: true }, { r: 'r1', dot: false } ],
    [ { r: 'r3', dot: true }, { r: 'r3', dot: true }, { r: 'r2', dot: true }, { r: 'r1', dot: false }, { r: 'r1', dot: false } ],
    [ { r: 'r2', dot: true }, { r: 'r2', dot: true }, { r: 'r1', dot: false }, { r: 'r1', dot: false }, { r: 'r1', dot: false } ],
    [ { r: 'r1', dot: false }, { r: 'r1', dot: false }, { r: 'r1', dot: false }, { r: 'r1', dot: false }, { r: 'r1', dot: false } ],
    [ { r: 'r1', dot: false }, { r: 'r1', dot: false }, { r: 'r1', dot: false }, { r: 'r1', dot: false }, { r: 'r1', dot: false } ]
  ];

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* EXECUTIVE COMMAND SHORTCUTS */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 18, background: 'var(--surface)', border: '1px solid var(--border)', padding: '12px 18px', borderRadius: 'var(--radius-md)' }}>
        <div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 15, fontWeight: 700, color: 'var(--text)' }}>
            Director of Compliance — Executive Command Center
          </div>
          <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2 }}>
            Real-time multi-state institutional compliance metrics across 6 operational states
          </div>
        </div>
        <div style={{ display: 'flex', gap: 8 }}>
          <button className="btn btn-outline btn-sm" onClick={() => setActiveModule('risk')}>
            <i className="fa-solid fa-triangle-exclamation"></i> Audit Risks
          </button>
          <button className="btn btn-outline btn-sm" onClick={() => setActiveModule('cap')}>
            <i className="fa-solid fa-circle-check"></i> Review CAPs
          </button>
          <button className="btn btn-primary btn-sm" onClick={() => setActiveModule('reports')}>
            <i className="fa-solid fa-file-invoice"></i> Donor Reports
          </button>
        </div>
      </div>

      {/* 4 STAT KPI CARDS */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 14, marginBottom: 20 }}>
        <div className="stat-card blue" onClick={() => setActiveModule('complaints')} style={{ cursor: 'pointer' }}>
          <div className="stat-label">Open Complaints</div>
          <div className="stat-value">18</div>
          <div className="stat-sub warn">↑ 3 new this week</div>
        </div>
        <div className="stat-card red" onClick={() => setActiveModule('risk')} style={{ cursor: 'pointer' }}>
          <div className="stat-label">Critical Risks</div>
          <div className="stat-value">3</div>
          <div className="stat-sub danger">Action required</div>
        </div>
        <div className="stat-card green" onClick={() => setActiveModule('cap')} style={{ cursor: 'pointer' }}>
          <div className="stat-label">CAP Completion</div>
          <div className="stat-value">76%</div>
          <div className="stat-sub success">↑ 8% vs last month</div>
        </div>
        <div className="stat-card purple" onClick={() => setActiveModule('training')} style={{ cursor: 'pointer' }}>
          <div className="stat-label">Staff Trained</div>
          <div className="stat-value">312</div>
          <div className="stat-sub">of 490 total staff (64%)</div>
        </div>
      </div>

      {/* MIDDLE SECTION: Complaints Breakdown + 6-Month Trajectory + Radial Index */}
      <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 1.2fr 0.8fr', gap: 16, marginBottom: 20 }}>
        {/* Category Breakdown Bar Chart */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-chart-column" style={{ color: 'var(--accent)' }}></i> Complaints by Category
            </div>
            <div style={{ fontSize: 10, color: 'var(--text-muted)' }}>Interactive Hover</div>
          </div>
          <InteractiveBarChart data={barData} maxValue={16} unit=" cases" height={160} />
        </div>

        {/* 6-Month Trajectory Trend */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-arrow-trend-down" style={{ color: 'var(--success)' }}></i> Incident Trajectory (6 Months)
            </div>
            <span className="pill pill-closed" style={{ fontSize: 9 }}>-72% Resolution Drop</span>
          </div>
          <InteractiveTrendChart data={trendData} height={160} strokeColor="#059669" fillColor="rgba(5, 150, 105, 0.1)" />
        </div>

        {/* Radial Compliance Gauge */}
        <div className="card" style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: 20 }}>
          <div style={{ fontSize: 11, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', marginBottom: 12 }}>
            Overall Compliance Score
          </div>
          <RadialProgressGauge
            percentage={64}
            label="Moderate Index"
            sublabel="Target: 85% by Q2 COP Review"
            size={130}
          />
        </div>
      </div>

      {/* LOWER SECTION: Recent Complaints DataTable & 5x5 Heat Map */}
      <div style={{ display: 'grid', gridTemplateColumns: '1.5fr 1fr', gap: 16 }}>
        {/* Recent Complaints Table */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-list-check" style={{ color: 'var(--accent)' }}></i> Active Grievances & Complaints
            </div>
          </div>
          <DataTable
            columns={complaintColumns}
            data={recentComplaints}
            searchPlaceholder="Search complaints..."
            categories={['Open', 'In Progress', 'Closed']}
            categoryField="status"
            pageSize={5}
          />
        </div>

        {/* 5x5 Heatmap Matrix */}
        <div className="card">
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-table-cells-large" style={{ color: 'var(--danger)' }}></i> 5×5 Risk Heat Map (ISO 31000)
            </div>
          </div>

          <div style={{ display: 'flex', gap: 10, alignItems: 'center', justifyContent: 'center' }}>
            {/* Y Axis label */}
            <div style={{ writingMode: 'vertical-rl', transform: 'rotate(180deg)', fontSize: 10, color: 'var(--text-muted)', fontWeight: 700, letterSpacing: 1 }}>
              LIKELIHOOD →
            </div>

            <div>
              <div className="risk-grid">
                {riskGrid.map((row, rIdx) =>
                  row.map((cell, cIdx) => (
                    <div
                      key={`${rIdx}-${cIdx}`}
                      className={`risk-cell ${cell.r}`}
                      onMouseEnter={() => setHoveredRiskCell({ like: 5 - rIdx, imp: cIdx + 1, rating: cell.r === 'r4' ? 'Critical (15-25)' : cell.r === 'r3' ? 'High (10-14)' : cell.r === 'r2' ? 'Medium (5-9)' : 'Low (1-4)' })}
                      onMouseLeave={() => setHoveredRiskCell(null)}
                    >
                      {cell.dot && <div className="risk-dot" />}
                    </div>
                  ))
                )}
              </div>
              <div style={{ textAlign: 'center', fontSize: 10, color: 'var(--text-muted)', fontWeight: 700, marginTop: 6, letterSpacing: 1 }}>
                IMPACT →
              </div>
            </div>
          </div>

          {/* Hover cell detail */}
          <div style={{ minHeight: 28, marginTop: 10, textAlign: 'center', fontSize: 11 }}>
            {hoveredRiskCell ? (
              <span style={{ fontWeight: 600, color: 'var(--accent)' }}>
                Likelihood: {hoveredRiskCell.like} · Impact: {hoveredRiskCell.imp} → {hoveredRiskCell.rating}
              </span>
            ) : (
              <span style={{ color: 'var(--text-muted)', fontSize: 10 }}>Hover over any matrix cell to inspect risk coordinates</span>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};
