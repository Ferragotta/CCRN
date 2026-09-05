import React from 'react';
import { useAuth } from '../../context/AuthContext';

export const Topbar: React.FC<{ onNewComplaint: () => void; onExport: () => void }> = ({ onNewComplaint, onExport }) => {
  const { currentUser, logout, activeModule } = useAuth();

  if (!currentUser) return null;

  const titles: Record<string, string> = {
    dashboard: 'Dashboard Overview',
    complaints: 'Complaints Management',
    cap: 'Corrective Action Plans (CAP)',
    pdp: 'Performance Development Plans (PDP)',
    training: 'Training Academy',
    states: 'States & Clusters',
    risk: 'Risk Register',
    policy: 'Policy Management',
    lessons: 'Lessons Learned',
    reports: 'Reports & Donor Governance',
    ai: 'AI Compliance Assistant',
    'ai-review': 'AI Compliance Review',
    investigation: 'Investigations Hub',
    travel: 'Travel & Ticket Compliance'
  };

  return (
    <header className="topbar">
      <div className="page-title">{titles[activeModule] || 'ComplianceIQ'}</div>
      
      <div className="topbar-actions">
        <div className="live-label"><div className="live-dot"></div> Live Monitor</div>
        <button className="btn btn-outline" onClick={onNewComplaint}><i className="fa-solid fa-plus"></i> New Complaint</button>
        <button className="btn btn-primary" onClick={onExport}><i className="fa-solid fa-file-export"></i> Export Report</button>
        <button className="btn btn-outline" onClick={logout} title="Sign Out" style={{ padding: '6px 12px', display: 'flex', alignItems: 'center', gap: 6 }}>
          <i className="fa-solid fa-power-off"></i>
          <span>Sign Out</span>
        </button>
      </div>
    </header>
  );
};
