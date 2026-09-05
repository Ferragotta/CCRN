import React from 'react';

interface AuthBackgroundProps {
  children: React.ReactNode;
}

export const AuthBackground: React.FC<AuthBackgroundProps> = ({ children }) => {
  return (
    <div
      style={{
        height: '100vh',
        maxHeight: '100vh',
        width: '100vw',
        maxWidth: '100vw',
        position: 'relative',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        background: 'radial-gradient(ellipse at 50% -20%, #0b2f64 0%, #031633 45%, #010a1a 100%)',
        overflow: 'hidden',
        margin: 0,
        padding: 0,
        boxSizing: 'border-box',
        fontFamily: "'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif",
      }}
    >
      {/* ── 1. AMBIENT GLOWING ORBS ── */}
      {/* Top-Left Cyan Glow */}
      <div
        style={{
          position: 'absolute',
          top: '-15%',
          left: '-10%',
          width: '650px',
          height: '650px',
          borderRadius: '50%',
          background: 'radial-gradient(circle, rgba(85, 226, 233, 0.18) 0%, rgba(2, 54, 123, 0.08) 50%, transparent 70%)',
          filter: 'blur(90px)',
          pointerEvents: 'none',
          zIndex: 0,
        }}
      />

      {/* Bottom-Right Deep Navy & Royal Glow */}
      <div
        style={{
          position: 'absolute',
          bottom: '-20%',
          right: '-10%',
          width: '750px',
          height: '750px',
          borderRadius: '50%',
          background: 'radial-gradient(circle, rgba(0, 119, 182, 0.22) 0%, rgba(2, 43, 97, 0.15) 50%, transparent 70%)',
          filter: 'blur(110px)',
          pointerEvents: 'none',
          zIndex: 0,
        }}
      />

      {/* Center Subtle Violet Security Halo */}
      <div
        style={{
          position: 'absolute',
          top: '50%',
          left: '50%',
          transform: 'translate(-50%, -50%)',
          width: '850px',
          height: '650px',
          borderRadius: '50%',
          background: 'radial-gradient(ellipse, rgba(124, 58, 237, 0.07) 0%, rgba(2, 54, 123, 0.12) 40%, transparent 75%)',
          filter: 'blur(80px)',
          pointerEvents: 'none',
          zIndex: 0,
        }}
      />

      {/* ── 2. ARCHITECTURAL SVG GRID & TELEMETRY MESH ── */}
      <svg
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          pointerEvents: 'none',
          opacity: 0.28,
          zIndex: 0,
        }}
      >
        <defs>
          {/* Fine Grid Pattern */}
          <pattern id="authGridPattern" width="40" height="40" patternUnits="userSpaceOnUse">
            <path
              d="M 40 0 L 0 0 0 40"
              fill="none"
              stroke="rgba(186, 230, 253, 0.16)"
              strokeWidth="0.8"
            />
            <circle cx="40" cy="0" r="1" fill="rgba(85, 226, 233, 0.4)" />
            <circle cx="0" cy="40" r="1" fill="rgba(85, 226, 233, 0.4)" />
          </pattern>

          {/* Glowing Wave Gradients */}
          <linearGradient id="waveGrad1" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stopColor="#55E2E9" stopOpacity="0.0" />
            <stop offset="50%" stopColor="#55E2E9" stopOpacity="0.35" />
            <stop offset="100%" stopColor="#0077b6" stopOpacity="0.0" />
          </linearGradient>

          <linearGradient id="waveGrad2" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stopColor="#02367B" stopOpacity="0.0" />
            <stop offset="50%" stopColor="#7c3aed" stopOpacity="0.25" />
            <stop offset="100%" stopColor="#55E2E9" stopOpacity="0.0" />
          </linearGradient>
        </defs>

        {/* Grid Canvas */}
        <rect width="100%" height="100%" fill="url(#authGridPattern)" />

        {/* Subtle Decorative Flowing Curvilinear Waves */}
        <path
          d="M -100 250 C 300 120, 700 380, 1200 180 S 1800 320, 2200 160"
          fill="none"
          stroke="url(#waveGrad1)"
          strokeWidth="1.5"
          strokeDasharray="4 6"
        />
        <path
          d="M -50 480 C 400 320, 800 620, 1300 420 S 1900 580, 2300 400"
          fill="none"
          stroke="url(#waveGrad2)"
          strokeWidth="1.2"
        />
      </svg>

      {/* ── 3. TOP BRANDING & ENVIRONMENT FLOATING HEADER ── */}
      <div
        style={{
          position: 'absolute',
          top: 24,
          left: 32,
          right: 32,
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          pointerEvents: 'none',
          zIndex: 1,
        }}
      >
        {/* Left: System Integrity Status */}
        <div
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: 10,
            background: 'rgba(2, 34, 77, 0.65)',
            border: '1px solid rgba(85, 226, 233, 0.28)',
            padding: '6px 14px',
            borderRadius: 24,
            backdropFilter: 'blur(10px)',
          }}
        >
          <span
            style={{
              width: 8,
              height: 8,
              borderRadius: '50%',
              background: '#10b981',
              boxShadow: '0 0 10px #10b981',
              display: 'inline-block',
            }}
          />
          <span style={{ fontSize: 11, fontWeight: 700, color: '#e0f2fe', letterSpacing: '0.4px' }}>
            CCCRN SECURE GATEWAY · FY2026 AUDIT CYCLE
          </span>
        </div>

        {/* Right: ComplianceIQ Pill */}
        <div
          style={{
            display: 'none',
            alignItems: 'center',
            gap: 8,
            background: 'rgba(255, 255, 255, 0.06)',
            border: '1px solid rgba(85, 226, 233, 0.28)',
            padding: '6px 16px',
            borderRadius: 24,
            backdropFilter: 'blur(10px)',
            color: '#55E2E9',
            fontSize: 12,
            fontWeight: 800,
            letterSpacing: '0.5px',
          }}
          className="d-md-flex"
        >
          <i className="fa-solid fa-shield-halved" style={{ color: '#55E2E9', fontSize: 13 }} />
          <span>ComplianceIQ</span>
        </div>
      </div>

      {/* ── 4. FLOATING LOGIN CARD WRAPPER WITH LIGHT ACCENT ── */}
      <div
        style={{
          position: 'relative',
          zIndex: 2,
          boxShadow: '0 30px 70px -15px rgba(0, 0, 0, 0.65), 0 0 50px rgba(0, 119, 182, 0.22)',
          borderRadius: 20,
        }}
      >
        {children}
      </div>

    </div>
  );
};
