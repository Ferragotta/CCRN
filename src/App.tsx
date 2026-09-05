import React, { useState } from 'react';
import { useAuth } from './context/AuthContext';
import { LoginPage } from './components/auth/LoginPage';
import { Sidebar } from './components/layout/Sidebar';
import { Topbar } from './components/layout/Topbar';
import { Footer } from './components/layout/Footer';
import { ExecutiveDashboard } from './components/dashboard/ExecutiveDashboard';
import { HrDashboard } from './components/dashboard/HrDashboard';
import { LeaveAttendanceModule } from './components/modules/LeaveAttendanceModule';
import { ComplaintsModule } from './components/modules/ComplaintsModule';
import { CapModule } from './components/modules/CapModule';
import { PdpModule } from './components/modules/PdpModule';
import { TrainingModule } from './components/modules/TrainingModule';
import { StatesModule } from './components/modules/StatesModule';
import { RiskModule } from './components/modules/RiskModule';
import { PolicyModule } from './components/modules/PolicyModule';
import { LessonsModule } from './components/modules/LessonsModule';
import { ReportsModule } from './components/modules/ReportsModule';
import { AiAssistantModule } from './components/modules/AiAssistantModule';
import { AiReviewModule } from './components/modules/AiReviewModule';
import { InvestigationModule } from './components/modules/InvestigationModule';
import { TravelModule } from './components/modules/TravelModule';

export const App: React.FC = () => {
  const { currentUser, activeModule, isDocAdmin, setActiveModule, login } = useAuth();
  const [showExportModal, setShowExportModal] = useState(false);

  if (!currentUser) {
    return <LoginPage />;
  }

  const renderModuleContent = () => {
    switch (activeModule) {
      case 'dashboard':
        if (currentUser?.key === 'hr') {
          return <HrDashboard />;
        }
        if (isDocAdmin) {
          return <ExecutiveDashboard />;
        }
        return (
          <div className="card" style={{ textAlign: 'center', padding: 40, background: 'var(--danger-light, #fee2e2)', border: '1px solid #fca5a5' }}>
            <i className="fa-solid fa-lock" style={{ fontSize: 32, color: 'var(--danger, #dc2626)', marginBottom: 12 }}></i>
            <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--danger, #dc2626)', marginBottom: 6 }}>
              Restricted Access: Administrative Clearance Required
            </h3>
            <p style={{ fontSize: 13, color: 'var(--text-dim)' }}>
              The Dashboard is reserved for executive leadership and Human Resources.
            </p>
          </div>
        );

      case 'leave-attendance':
        return <LeaveAttendanceModule />;

      case 'complaints':
        return <ComplaintsModule />;

      case 'cap':
        return <CapModule />;

      case 'pdp':
        return <PdpModule />;

      case 'training':
        return <TrainingModule />;

      case 'states':
        return <StatesModule />;

      case 'risk':
        return <RiskModule />;

      case 'policy':
        return <PolicyModule />;

      case 'lessons':
        return <LessonsModule />;

      case 'reports':
        return <ReportsModule />;

      case 'ai':
        return <AiAssistantModule />;

      case 'ai-review':
        return <AiReviewModule />;

      case 'investigation':
        return <InvestigationModule />;

      case 'travel':
        return <TravelModule />;

      default:
        return (
          <div className="card" style={{ textAlign: 'center', padding: 40 }}>
            <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--accent)', marginBottom: 8 }}>
              {activeModule.toUpperCase()} Module
            </h3>
            <p style={{ fontSize: 13, color: 'var(--text-muted)' }}>
              Logged in as: <strong>{currentUser.name}</strong> ({currentUser.roleBadge})
            </p>
          </div>
        );
    }
  };

  return (
    <div className="app">
      <Sidebar />
      <main className="main">
        <Topbar
          onNewComplaint={() => setActiveModule('complaints')}
          onExport={() => setShowExportModal(true)}
        />
        <div className="content" style={{ flex: 1 }}>
          {renderModuleContent()}
        </div>
        <Footer />
      </main>

      
      {/* FLOATING DEV QUICK-SWITCH TOOLBAR */}
      <div style={{
        position: 'fixed',
        bottom: 16,
        right: 16,
        background: 'rgba(15, 43, 68, 0.95)',
        backdropFilter: 'blur(8px)',
        color: '#ffffff',
        padding: '8px 12px',
        borderRadius: 30,
        boxShadow: '0 8px 24px rgba(0, 0, 0, 0.25)',
        display: 'flex',
        alignItems: 'center',
        gap: 8,
        zIndex: 9999,
        border: '1px solid rgba(255, 255, 255, 0.15)',
        fontSize: 11
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 6, paddingRight: 6, borderRight: '1px solid rgba(255, 255, 255, 0.2)' }}>
          <i className="fa-solid fa-code" style={{ color: '#38bdf8' }}></i>
          <span style={{ fontWeight: 700, color: '#38bdf8' }}>DEV ACCESS:</span>
          <span style={{ opacity: 0.85 }}>{currentUser.name}</span>
        </div>
        <button
          onClick={() => login('doc')}
          style={{
            background: currentUser.key === 'doc' ? 'var(--accent)' : 'rgba(255, 255, 255, 0.12)',
            color: '#ffffff',
            border: 'none',
            borderRadius: 14,
            padding: '3px 9px',
            fontSize: 10,
            fontWeight: 700,
            cursor: 'pointer'
          }}
          title="Switch to Director of Compliance"
        >
          👑 DoC
        </button>
        <button
          onClick={() => login('compliance_officer')}
          style={{
            background: currentUser.key === 'compliance_officer' ? 'var(--accent)' : 'rgba(255, 255, 255, 0.12)',
            color: '#ffffff',
            border: 'none',
            borderRadius: 14,
            padding: '3px 9px',
            fontSize: 10,
            fontWeight: 700,
            cursor: 'pointer'
          }}
          title="Switch to Compliance Officer"
        >
          👮 Compliance
        </button>
        <button
          onClick={() => login('hr')}
          style={{
            background: currentUser.key === 'hr' ? 'var(--accent)' : 'rgba(255, 255, 255, 0.12)',
            color: '#ffffff',
            border: 'none',
            borderRadius: 14,
            padding: '3px 9px',
            fontSize: 10,
            fontWeight: 700,
            cursor: 'pointer'
          }}
          title="Switch to HR"
        >
          👥 HR
        </button>
        <button
          onClick={() => login('staff')}
          style={{
            background: currentUser.key === 'staff' ? 'var(--accent)' : 'rgba(255, 255, 255, 0.12)',
            color: '#ffffff',
            border: 'none',
            borderRadius: 14,
            padding: '3px 9px',
            fontSize: 10,
            fontWeight: 700,
            cursor: 'pointer'
          }}
          title="Switch to All Staff"
        >
          👤 Staff
        </button>
      </div>

      {/* EXPORT REPORT MODAL */}
      {showExportModal && (
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
                Export Compliance Report
              </div>
              <button
                onClick={() => setShowExportModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>
            <div style={{ padding: 20 }}>
              <p style={{ fontSize: 13, color: 'var(--text-dim)', marginBottom: 16 }}>
                Select reporting format for institutional governance and USAID/Global Fund submission:
              </p>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 10, marginBottom: 20 }}>
                <button
                  className="btn btn-outline"
                  onClick={() => {
                    alert('Generating Executive PDF Summary Report...');
                    setShowExportModal(false);
                  }}
                  style={{ justifyContent: 'flex-start', padding: 12 }}
                >
                  <i className="fa-solid fa-file-pdf" style={{ color: 'var(--danger)', fontSize: 16 }}></i>
                  <span><strong>Executive Summary Dossier</strong> (PDF Format)</span>
                </button>
                <button
                  className="btn btn-outline"
                  onClick={() => {
                    alert('Generating CSV Data Export...');
                    setShowExportModal(false);
                  }}
                  style={{ justifyContent: 'flex-start', padding: 12 }}
                >
                  <i className="fa-solid fa-file-csv" style={{ color: 'var(--success)', fontSize: 16 }}></i>
                  <span><strong>Full Compliance Ledger</strong> (CSV / Excel Format)</span>
                </button>
              </div>
              <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                <button className="btn btn-outline" onClick={() => setShowExportModal(false)}>
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
