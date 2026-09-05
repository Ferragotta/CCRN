import React from 'react';
import { useAuth } from '../../context/AuthContext';

export const Sidebar: React.FC = () => {
  const { currentUser, activeModule, setActiveModule } = useAuth();

  if (!currentUser) return null;

  const navItems = [
    { id: 'dashboard', label: 'Dashboard', icon: 'fa-table-columns', section: 'Executive' },
    { id: 'leave-attendance', label: 'Leave & Attendance', icon: 'fa-calendar-check', section: 'Workforce', badge: 'PRO', badgeType: 'warn' },
    { id: 'complaints', label: 'Complaints', icon: 'fa-inbox', section: 'Core Modules', badge: '7' },
    { id: 'cap', label: 'Corrective Action', icon: 'fa-circle-check', section: 'Core Modules', badge: '3', badgeType: 'warn' },
    { id: 'pdp', label: 'PDP Tracker', icon: 'fa-bullseye', section: 'Core Modules' },
    { id: 'training', label: 'Training', icon: 'fa-graduation-cap', section: 'People & Training' },
    { id: 'states', label: 'States & Clusters', icon: 'fa-map-location-dot', section: 'People & Training' },
    { id: 'risk', label: 'Risk Register', icon: 'fa-triangle-exclamation', section: 'Governance' },
    { id: 'policy', label: 'Policy Management', icon: 'fa-file-shield', section: 'Governance' },
    { id: 'lessons', label: 'Lessons Learned', icon: 'fa-lightbulb', section: 'Governance' },
    { id: 'reports', label: 'Reports & Donor', icon: 'fa-chart-pie', section: 'Intelligence' },
    { id: 'ai', label: 'AI Assistant', icon: 'fa-robot', section: 'Intelligence' },
    { id: 'ai-review', label: 'AI Compliance Review', icon: 'fa-brain', section: 'New Modules', badge: 'NEW', badgeType: 'purple' },
    { id: 'investigation', label: 'Investigations', icon: 'fa-shield-halved', section: 'New Modules', badge: 'NEW', badgeType: 'purple' },
    { id: 'travel', label: 'Travel & Tickets', icon: 'fa-plane-departure', section: 'New Modules', badge: 'NEW', badgeType: 'purple' }
  ];

  // Filter based on user's permissions
  const permittedItems = navItems.filter(item => currentUser.allowedModules.includes(item.id));

  return (
    <nav className="sidebar">
      <div className="logo">
        <div className="logo-title">
          <span>CCCRN ComplianceIQ</span>
          <span className="logo-badge">PRO</span>
        </div>
        <div className="logo-sub">{currentUser.key === 'doc' ? 'Director Command Center' : 'Compliance Portal'}</div>
      </div>

      <div className="nav">
        {permittedItems.map((item, idx) => {
          const isFirstInSection = idx === 0 || permittedItems[idx - 1].section !== item.section;
          return (
            <React.Fragment key={item.id}>
              {isFirstInSection && <div className="nav-section">{item.section}</div>}
              <div
                className={`nav-item ${activeModule === item.id ? 'active' : ''}`}
                onClick={() => setActiveModule(item.id)}
              >
                <span className="icon"><i className={`fa-solid ${item.icon}`}></i></span>
                <span>{item.label}</span>
                {item.badge && <span className={`badge ${item.badgeType || ''}`}>{item.badge}</span>}
              </div>
            </React.Fragment>
          );
        })}
      </div>

      <div className="sidebar-footer">
        <div className="user-info">
          <div className="avatar">{currentUser.avatar}</div>
          <div>
            <div className="user-name">{currentUser.name}</div>
            <div className="user-role">{currentUser.roleBadge}</div>
          </div>
        </div>
      </div>
    </nav>
  );
};
