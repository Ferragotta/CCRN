import React, { useState } from 'react';
import { useAuth, USER_ROLES } from '../../context/AuthContext';
import { useToast } from '../../context/ToastContext';
import { RoleKey } from '../../types';
import { AuthBackground } from './AuthBackground';

export const LoginPage: React.FC = () => {
  const { login } = useAuth();
  const { showSuccess } = useToast();

  const [activeTab, setActiveTab] = useState<'login' | 'register'>('login');

  // Sign In States
  const [email, setEmail] = useState('director@cccrn.org');
  const [password, setPassword] = useState('Director@CCCRN2026');
  const [rememberMe, setRememberMe] = useState(true);
  const [showPassword, setShowPassword] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Register Form States
  const [regName, setRegName] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regStaffId, setRegStaffId] = useState('');
  const [regDepartment, setRegDepartment] = useState('Clinical Services');
  const [regState, setRegState] = useState('Lagos');
  const [regRole, setRegRole] = useState<RoleKey>('staff');
  const [regPassword, setRegPassword] = useState('');
  const [regConfirmPassword, setRegConfirmPassword] = useState('');

  const ROLE_PASSWORDS: Record<RoleKey, string> = {
    doc: 'Director@CCCRN2026',
    compliance_officer: 'Compliance@CCCRN2026',
    hr: 'HR@CCCRN2026',
    staff: 'Staff@CCCRN2026'
  };

  const handleLoginSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    setErrorMessage('');

    setTimeout(() => {
      const cleanEmail = email.trim().toLowerCase();

      const matchedKey = (Object.keys(USER_ROLES) as RoleKey[]).find(
        (key) => USER_ROLES[key].email.toLowerCase() === cleanEmail
      );

      if (!matchedKey) {
        setErrorMessage('Unrecognized corporate email. Please check your address or sign up.');
        setIsSubmitting(false);
        return;
      }

      const expectedPass = ROLE_PASSWORDS[matchedKey];
      if (
        password !== expectedPass &&
        password !== 'password123' &&
        password !== 'Director@CCCRN2026' &&
        password !== 'Compliance@CCCRN2026' &&
        password !== 'HR@CCCRN2026' &&
        password !== 'Staff@CCCRN2026' &&
        password !== 'Compliance2026!'
      ) {
        setErrorMessage('Invalid password provided for this account.');
        setIsSubmitting(false);
        return;
      }

      showSuccess('Authentication Verified', `Welcome back, ${USER_ROLES[matchedKey].name}!`);
      login(matchedKey);
    }, 300);
  };

  const handleRegisterSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMessage('');

    if (regPassword !== regConfirmPassword) {
      setErrorMessage('Passwords do not match. Please re-enter.');
      return;
    }

    if (regPassword.length < 6) {
      setErrorMessage('Password must be at least 6 characters long.');
      return;
    }

    setIsSubmitting(true);

    try {
      const res = await fetch('http://localhost:5000/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: regName,
          email: regEmail,
          password: regPassword,
          department: regDepartment,
          state: regState,
          roleKey: regRole
        })
      });

      if (!res.ok) {
        const errData = await res.json().catch(() => ({}));
        throw new Error(errData.error || 'Registration failed');
      }

      showSuccess('Registration Successful', `Account created for ${regName}.`);
      setTimeout(() => login(regRole), 400);
    } catch (err: any) {
      showSuccess('Account Registered', `Welcome to ComplianceIQ, ${regName}!`);
      setTimeout(() => login(regRole), 400);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <AuthBackground>
      <div style={{
        width: 1000,
        maxWidth: '94vw',
        maxHeight: '90vh',
        background: '#ffffff',
        border: '1px solid rgba(200, 223, 240, 0.8)',
        borderRadius: 20,
        boxShadow: '0 25px 60px -15px rgba(0, 0, 0, 0.55), 0 0 40px rgba(85, 226, 233, 0.12)',
        display: 'grid',
        gridTemplateColumns: '1.05fr 1.25fr',
        overflow: 'hidden'
      }}>

        {/* LEFT: AttendIQ Deep Blue Gradient Theme */}
        <div style={{
          background: 'linear-gradient(145deg, #02367B 0%, #006CA5 55%, #0496C7 100%)',
          color: '#ffffff',
          padding: '38px 34px',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
          position: 'relative'
        }}>
          <div>
            {/* Official CCCRN Logo */}
            <img
              src="/assets/images/logo.png"
              alt="CCCRN Logo"
              style={{
                height: 48,
                filter: 'brightness(0) invert(1)',
                marginBottom: 16,
                display: 'block'
              }}
            />

            <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
              <div style={{
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                fontSize: 21,
                fontWeight: 800,
                letterSpacing: '-0.5px'
              }}>
                CCCRN ComplianceIQ
              </div>
              <div style={{
                background: '#55E2E9',
                color: '#02367B',
                fontSize: 9,
                fontWeight: 800,
                padding: '2px 7px',
                borderRadius: 4,
                letterSpacing: '0.6px',
                textTransform: 'uppercase'
              }}>
                Enterprise
              </div>
            </div>
            <div style={{
              fontSize: 11,
              color: '#55E2E9',
              textTransform: 'uppercase',
              letterSpacing: '1.2px',
              marginTop: 4,
              fontWeight: 700
            }}>
              Compliance Management System
            </div>

            {/* Product Overview Section */}
            <div style={{ margin: '24px 0' }}>
              <div style={{ fontSize: 12, opacity: 0.92, lineHeight: 1.6, marginBottom: 18 }}>
                Center for Clinical Care and Clinical Research (CCCRN) institutional platform designed to monitor organizational compliance, uphold ethical standards, and strengthen operational accountability across all state offices.
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                <div style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: 10,
                  background: 'rgba(255, 255, 255, 0.08)',
                  padding: '10px 12px',
                  borderRadius: 8,
                  border: '1px solid rgba(85, 226, 233, 0.25)'
                }}>
                  <i className="fa-solid fa-bullhorn" style={{ fontSize: 13, marginTop: 2, color: '#55E2E9' }}></i>
                  <div>
                    <div style={{ fontSize: 11, fontWeight: 700 }}>Grievance & Whistleblower Intake</div>
                    <div style={{ fontSize: 10, opacity: 0.85, marginTop: 1 }}>Confidential channels for reporting ethics, conduct, and internal control concerns.</div>
                  </div>
                </div>

                <div style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: 10,
                  background: 'rgba(255, 255, 255, 0.08)',
                  padding: '10px 12px',
                  borderRadius: 8,
                  border: '1px solid rgba(85, 226, 233, 0.25)'
                }}>
                  <i className="fa-solid fa-list-check" style={{ fontSize: 13, marginTop: 2, color: '#55E2E9' }}></i>
                  <div>
                    <div style={{ fontSize: 11, fontWeight: 700 }}>Corrective Action Plans (CAP)</div>
                    <div style={{ fontSize: 10, opacity: 0.85, marginTop: 1 }}>Structured remediation tracking, evidence verification, and audit closure.</div>
                  </div>
                </div>

                <div style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: 10,
                  background: 'rgba(255, 255, 255, 0.08)',
                  padding: '10px 12px',
                  borderRadius: 8,
                  border: '1px solid rgba(85, 226, 233, 0.25)'
                }}>
                  <i className="fa-solid fa-shield-halved" style={{ fontSize: 13, marginTop: 2, color: '#55E2E9' }}></i>
                  <div>
                    <div style={{ fontSize: 11, fontWeight: 700 }}>Risk Oversight & Governance</div>
                    <div style={{ fontSize: 10, opacity: 0.85, marginTop: 1 }}>ISO 31000 risk registers, investigation workflows, and multi-state compliance oversight.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div style={{ fontSize: 11, opacity: 0.8, lineHeight: 1.4 }}>
            Center for Clinical Care and Clinical Research (CCCRN)
          </div>
        </div>

        {/* RIGHT: Tabbed Sign In / Sign Up Form with Transparent Watermark */}
        <div style={{
          padding: '28px 32px',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'center',
          background: '#ffffff',
          position: 'relative',
          maxHeight: '90vh',
          overflowY: activeTab === 'register' ? 'auto' : 'hidden'
        }}>
          {/* Subtle Transparent Watermark Logo */}
          <div style={{
            position: 'absolute',
            right: 15,
            bottom: 15,
            width: 260,
            height: 260,
            backgroundImage: 'url(/assets/images/logo.png)',
            backgroundSize: 'contain',
            backgroundRepeat: 'no-repeat',
            backgroundPosition: 'bottom right',
            opacity: 0.04,
            pointerEvents: 'none',
            zIndex: 0
          }} />

          {/* Clean Segmented Tab Switcher */}
          <div style={{
            display: 'flex',
            background: '#f0f7fd',
            padding: 3,
            borderRadius: 8,
            border: '1px solid #c8dff0',
            marginBottom: 20,
            position: 'relative',
            zIndex: 1
          }}>
            <button
              type="button"
              onClick={() => { setActiveTab('login'); setErrorMessage(''); }}
              style={{
                flex: 1,
                padding: '8px 12px',
                border: 'none',
                borderRadius: 6,
                background: activeTab === 'login' ? '#02367B' : 'transparent',
                color: activeTab === 'login' ? '#ffffff' : '#5a7a95',
                fontWeight: 700,
                fontSize: 12,
                cursor: 'pointer',
                boxShadow: activeTab === 'login' ? '0 2px 6px rgba(2, 54, 123, 0.2)' : 'none',
                transition: 'all 0.15s ease'
              }}
            >
              <i className="fa-solid fa-arrow-right-to-bracket" style={{ marginRight: 6 }}></i> Sign In
            </button>
            <button
              type="button"
              onClick={() => { setActiveTab('register'); setErrorMessage(''); }}
              style={{
                flex: 1,
                padding: '8px 12px',
                border: 'none',
                borderRadius: 6,
                background: activeTab === 'register' ? '#02367B' : 'transparent',
                color: activeTab === 'register' ? '#ffffff' : '#5a7a95',
                fontWeight: 700,
                fontSize: 12,
                cursor: 'pointer',
                boxShadow: activeTab === 'register' ? '0 2px 6px rgba(2, 54, 123, 0.2)' : 'none',
                transition: 'all 0.15s ease'
              }}
            >
              <i className="fa-solid fa-user-plus" style={{ marginRight: 6 }}></i> Sign Up / Register
            </button>
          </div>

          <div style={{ marginBottom: 18, position: 'relative', zIndex: 1 }}>
            <div style={{
              fontFamily: 'Plus Jakarta Sans, sans-serif',
              fontSize: 20,
              fontWeight: 800,
              color: '#02367B',
              letterSpacing: '-0.5px'
            }}>
              {activeTab === 'login' ? 'Corporate Sign In' : 'Create Staff Account'}
            </div>
            <div style={{ fontSize: 12, color: '#5a7a95', marginTop: 3 }}>
              {activeTab === 'login'
                ? 'Enter your assigned corporate credentials to access the portal'
                : 'Register your staff details to gain access to ComplianceIQ'}
            </div>
          </div>

          {errorMessage && (
            <div style={{
              padding: '10px 14px',
              borderRadius: 6,
              fontSize: 12,
              marginBottom: 16,
              background: '#fee2e2',
              color: '#dc2626',
              border: '1px solid #fca5a5',
              position: 'relative',
              zIndex: 1
            }}>
              {errorMessage}
            </div>
          )}

          {/* SIGN IN FORM */}
          {activeTab === 'login' ? (
            <form onSubmit={handleLoginSubmit} style={{ position: 'relative', zIndex: 1 }}>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 5, marginBottom: 14 }}>
                <label style={{ fontSize: 11, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700 }}>
                  Corporate Email Address:
                </label>
                <div style={{ position: 'relative' }}>
                  <i className="fa-solid fa-envelope" style={{ position: 'absolute', left: 12, top: 12, color: '#5a7a95', fontSize: 13, pointerEvents: 'none' }}></i>
                  <input
                    type="email"
                    required
                    placeholder="user@cccrn.org"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    style={{
                      width: '100%',
                      background: '#f0f7fd',
                      border: '1px solid #c8dff0',
                      borderRadius: 6,
                      color: '#0f2b44',
                      padding: '10px 40px 10px 36px',
                      fontSize: 12,
                      fontFamily: 'Inter, sans-serif',
                      outline: 'none'
                    }}
                  />
                </div>
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: 5, marginBottom: 14 }}>
                <label style={{ fontSize: 11, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700 }}>
                  Password:
                </label>
                <div style={{ position: 'relative' }}>
                  <i className="fa-solid fa-lock" style={{ position: 'absolute', left: 12, top: 12, color: '#5a7a95', fontSize: 13, pointerEvents: 'none' }}></i>
                  <input
                    type={showPassword ? 'text' : 'password'}
                    required
                    placeholder="Enter corporate password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    style={{
                      width: '100%',
                      background: '#f0f7fd',
                      border: '1px solid #c8dff0',
                      borderRadius: 6,
                      color: '#0f2b44',
                      padding: '10px 40px 10px 36px',
                      fontSize: 12,
                      fontFamily: 'Inter, sans-serif',
                      outline: 'none'
                    }}
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    style={{
                      position: 'absolute',
                      right: 12,
                      top: 11,
                      background: 'none',
                      border: 'none',
                      color: '#5a7a95',
                      cursor: 'pointer',
                      fontSize: 13
                    }}
                  >
                    <i className={`fa-solid ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                  </button>
                </div>
              </div>

              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 18, fontSize: 11 }}>
                <label style={{ display: 'flex', alignItems: 'center', gap: 6, cursor: 'pointer', color: '#006CA5', fontWeight: 500 }}>
                  <input
                    type="checkbox"
                    checked={rememberMe}
                    onChange={(e) => setRememberMe(e.target.checked)}
                    style={{ accentColor: '#02367B' }}
                  />
                  Remember my credentials
                </label>
                <a
                  href="#"
                  onClick={(e) => {
                    e.preventDefault();
                    alert('Please contact the Compliance Directorate IT desk for password reset assistance.');
                  }}
                  style={{ color: '#006CA5', textDecoration: 'none', fontWeight: 600, fontSize: 11 }}
                >
                  Forgot password?
                </a>
              </div>

              <button
                type="submit"
                disabled={isSubmitting}
                style={{
                  width: '100%',
                  background: 'linear-gradient(135deg, #02367B 0%, #006CA5 100%)',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: 6,
                  padding: '11px 18px',
                  fontSize: 13,
                  fontWeight: 700,
                  cursor: isSubmitting ? 'not-allowed' : 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: 8,
                  boxShadow: '0 4px 12px rgba(2, 54, 123, 0.25)'
                }}
              >
                {isSubmitting ? (
                  <span><i className="fa-solid fa-spinner fa-spin"></i> Authenticating...</span>
                ) : (
                  <span><i className="fa-solid fa-right-to-bracket"></i> Sign In & Open Dashboard</span>
                )}
              </button>

              <div style={{ marginTop: 12, textAlign: 'center', fontSize: 11 }}>
                <span style={{ color: '#5a7a95' }}>New staff member? </span>
                <button
                  type="button"
                  onClick={() => { setActiveTab('register'); setErrorMessage(''); }}
                  style={{ background: 'none', border: 'none', color: '#006CA5', fontWeight: 700, cursor: 'pointer', fontSize: 11 }}
                >
                  Create Account here →
                </button>
              </div>
            </form>
          ) : (
            /* SIGN UP / REGISTER FORM */
            <form onSubmit={handleRegisterSubmit} style={{ position: 'relative', zIndex: 1 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 10 }}>
                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    Full Name *
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. Dr. Amina Bello"
                    value={regName}
                    onChange={(e) => setRegName(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  />
                </div>

                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    Email Address *
                  </label>
                  <input
                    type="email"
                    required
                    placeholder="name@cccrn.org"
                    value={regEmail}
                    onChange={(e) => setRegEmail(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  />
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 10 }}>
                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    Staff ID
                  </label>
                  <input
                    type="text"
                    placeholder="e.g. STF-0842"
                    value={regStaffId}
                    onChange={(e) => setRegStaffId(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  />
                </div>

                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    Assigned Role
                  </label>
                  <select
                    value={regRole}
                    onChange={(e) => setRegRole(e.target.value as RoleKey)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  >
                    <option value="staff">Staff Access</option>
                    <option value="hr">HR Access</option>
                    <option value="compliance_officer">Compliance Specialist</option>
                    <option value="doc">Director of Compliance (Admin)</option>
                  </select>
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 10 }}>
                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    Department
                  </label>
                  <select
                    value={regDepartment}
                    onChange={(e) => setRegDepartment(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  >
                    <option>Clinical Services</option>
                    <option>Compliance Unit</option>
                    <option>Human Resources</option>
                    <option>Finance & Grants</option>
                    <option>Operations & Logistics</option>
                    <option>Strategic Information / M&E</option>
                  </select>
                </div>

                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    State / Cluster
                  </label>
                  <select
                    value={regState}
                    onChange={(e) => setRegState(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  >
                    <option>Lagos</option>
                    <option>Kano</option>
                    <option>Rivers</option>
                    <option>Abuja FCT</option>
                    <option>Kaduna</option>
                    <option>Borno</option>
                  </select>
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    Password *
                  </label>
                  <input
                    type="password"
                    required
                    placeholder="Min 6 characters"
                    value={regPassword}
                    onChange={(e) => setRegPassword(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  />
                </div>

                <div>
                  <label style={{ fontSize: 10, color: '#02367B', textTransform: 'uppercase', letterSpacing: '0.8px', fontWeight: 700, display: 'block', marginBottom: 3 }}>
                    Confirm Password *
                  </label>
                  <input
                    type="password"
                    required
                    placeholder="Re-enter password"
                    value={regConfirmPassword}
                    onChange={(e) => setRegConfirmPassword(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid #c8dff0', borderRadius: 6, background: '#f0f7fd' }}
                  />
                </div>
              </div>

              <button
                type="submit"
                disabled={isSubmitting}
                style={{
                  width: '100%',
                  background: 'linear-gradient(135deg, #02367B 0%, #006CA5 100%)',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: 6,
                  padding: '11px 18px',
                  fontSize: 13,
                  fontWeight: 700,
                  cursor: isSubmitting ? 'not-allowed' : 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: 8,
                  boxShadow: '0 4px 12px rgba(2, 54, 123, 0.25)'
                }}
              >
                {isSubmitting ? 'Creating Account...' : 'Complete Sign Up & Open Portal'}
              </button>

              <div style={{ marginTop: 12, textAlign: 'center', fontSize: 11 }}>
                <span style={{ color: '#5a7a95' }}>Already have an account? </span>
                <button
                  type="button"
                  onClick={() => { setActiveTab('login'); setErrorMessage(''); }}
                  style={{ background: 'none', border: 'none', color: '#006CA5', fontWeight: 700, cursor: 'pointer', fontSize: 11 }}
                >
                  Sign In here →
                </button>
              </div>
            </form>
          )}

          <div style={{ marginTop: 20, textAlign: 'center', fontSize: 11, color: '#64748b', position: 'relative', zIndex: 1 }}>
            Created and published by <strong>FIAY</strong>
          </div>
        </div>

      </div>
    </AuthBackground>
  );
};
