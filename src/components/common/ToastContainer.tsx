import React from 'react';
import { useToast, ToastType } from '../../context/ToastContext';

export const ToastContainer: React.FC = () => {
  const { toasts, removeToast } = useToast();

  if (toasts.length === 0) return null;

  const getToastIcon = (type: ToastType) => {
    switch (type) {
      case 'success':
        return <i className="fa-solid fa-circle-check" style={{ color: 'var(--success, #059669)', fontSize: 18 }}></i>;
      case 'error':
        return <i className="fa-solid fa-circle-xmark" style={{ color: 'var(--danger, #dc2626)', fontSize: 18 }}></i>;
      case 'warning':
        return <i className="fa-solid fa-triangle-exclamation" style={{ color: 'var(--warning, #d97706)', fontSize: 18 }}></i>;
      case 'info':
      default:
        return <i className="fa-solid fa-circle-info" style={{ color: 'var(--accent, #0077b6)', fontSize: 18 }}></i>;
    }
  };

  const getToastBorder = (type: ToastType) => {
    switch (type) {
      case 'success': return '1px solid #86efac';
      case 'error': return '1px solid #fca5a5';
      case 'warning': return '1px solid #fde68a';
      case 'info':
      default: return '1px solid #93c5fd';
    }
  };

  return (
    <div style={{
      position: 'fixed',
      top: 20,
      right: 20,
      zIndex: 9999,
      display: 'flex',
      flexDirection: 'column',
      gap: 10,
      maxWidth: 380,
      width: '100%',
      pointerEvents: 'none'
    }}>
      {toasts.map((toast) => (
        <div
          key={toast.id}
          style={{
            pointerEvents: 'auto',
            background: '#ffffff',
            borderRadius: 10,
            border: getToastBorder(toast.type),
            boxShadow: '0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1)',
            padding: '12px 16px',
            display: 'flex',
            alignItems: 'flex-start',
            gap: 12,
            animation: 'toastSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
            transition: 'all 0.2s ease'
          }}
        >
          <div style={{ marginTop: 2, flexShrink: 0 }}>
            {getToastIcon(toast.type)}
          </div>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 13, fontWeight: 700, color: 'var(--text)' }}>
              {toast.title}
            </div>
            {toast.message && (
              <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2, lineHeight: 1.4 }}>
                {toast.message}
              </div>
            )}
          </div>
          <button
            onClick={() => removeToast(toast.id)}
            style={{
              background: 'none',
              border: 'none',
              color: 'var(--text-muted)',
              fontSize: 14,
              cursor: 'pointer',
              padding: 0,
              lineHeight: 1
            }}
          >
            ×
          </button>
        </div>
      ))}
    </div>
  );
};
