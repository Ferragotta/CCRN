import React from 'react';

export const Footer: React.FC = () => {
  return (
    <footer style={{
      marginTop: 'auto',
      padding: '14px 24px',
      borderTop: '1px solid var(--border)',
      background: 'var(--surface)',
      display: 'flex',
      justifyContent: 'space-between',
      alignItems: 'center',
      fontSize: 11,
      color: 'var(--text-muted)',
      flexWrap: 'wrap',
      gap: 10
    }}>
      <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
        <span style={{ fontWeight: 700, color: 'var(--text)' }}>
          Center for Clinical Care and Clinical Research (CCCRN)
        </span>
        <span>·</span>
        <span>© 2026 Institutional Compliance & Governance System</span>
      </div>

      <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4 }}>
          <i className="fa-solid fa-scale-balanced" style={{ color: 'var(--accent)' }}></i>
          <span>Institutional Compliance & Operating Standards</span>
        </span>
        <span>·</span>
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4 }}>
          <i className="fa-solid fa-shield-halved" style={{ color: 'var(--success)' }}></i>
          <span>Audit Logged & Confidential</span>
        </span>
      </div>
    </footer>
  );
};
