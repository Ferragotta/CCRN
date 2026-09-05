import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export interface LessonItem {
  id: string;
  category: string;
  issueRef: string;
  whatHappened: string;
  rootCause: string;
  lesson: string;
  scope: string;
  priority: 'High' | 'Medium' | 'Low';
  date: string;
  submittedBy: string;
}

export const LessonsModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();

  const isHR = currentUser?.key === 'hr';
  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isStaff = currentUser?.key === 'staff';

  // Seed Lessons Learned matching wireframe
  const [lessons, setLessons] = useState<LessonItem[]>([
    {
      id: 'LL-012',
      category: 'Procurement',
      issueRef: 'CMP-048 / CAP-032',
      whatHappened: 'Facility procurement bypass occurred during emergency field testing when secondary signatories were unavailable on site.',
      rootCause: 'Lack of remote mobile authorization workflow in standard procurement operating procedures.',
      lesson: 'Mandate digital mobile authorization tokens with GPS validation before any local petty cash or emergency procurement is released.',
      scope: 'All States',
      priority: 'High',
      date: '28 Feb 2026',
      submittedBy: 'Dr. Biodun Ojo (HOD)'
    },
    {
      id: 'LL-011',
      category: 'Finance',
      issueRef: 'CMP-044',
      whatHappened: 'Delayed advance retirements for community outreach teams resulted in multi-week audit non-conformities.',
      rootCause: 'Manual paper receipt collection from rural facilities created a 3-week backlog before finance review.',
      lesson: 'All outreach teams must upload digital receipt photos to the portal within 24 hours of expenditure.',
      scope: 'Kaduna & Kano',
      priority: 'High',
      date: '20 Feb 2026',
      submittedBy: 'Chidinma Okoro (Supervisor)'
    },
    {
      id: 'LL-010',
      category: 'Safeguarding',
      issueRef: 'AUDIT-2026-02',
      whatHappened: 'Ad-hoc volunteers in community testing sites were interacting with clients without verified PSEA background clearance.',
      rootCause: 'Volunteer onboarding was managed at the facility level without central HR vetting check.',
      lesson: 'Mandate central HR electronic badge authorization before any ad-hoc worker is deployed to donor-funded clinic facilities.',
      scope: 'All States',
      priority: 'High',
      date: '15 Feb 2026',
      submittedBy: 'HR Lead'
    },
    {
      id: 'LL-009',
      category: 'Information Security',
      issueRef: 'RSK-016',
      whatHappened: 'Physical client registers were briefly left unattended during a clinic facility painting project.',
      rootCause: 'Absence of temporary secure lock-box protocols during routine facility renovations.',
      lesson: 'State Logistics officers must issue biometric temporary storage lock-boxes prior to commencing facility civil works.',
      scope: 'Lagos & Rivers',
      priority: 'Medium',
      date: '08 Feb 2026',
      submittedBy: 'Compliance Officer'
    }
  ]);

  // Form & Filter States
  const [activeCategory, setActiveCategory] = useState<string>('All');
  const [searchQuery, setSearchQuery] = useState('');
  const [viewingLesson, setViewingLesson] = useState<LessonItem | null>(null);
  const [editingLesson, setEditingLesson] = useState<LessonItem | null>(null);

  // Form State for Record New Lesson
  const [formData, setFormData] = useState({
    issueRef: '',
    category: 'Procurement',
    whatHappened: '',
    rootCause: '',
    lesson: '',
    scope: 'All States',
    priority: 'High' as 'High' | 'Medium' | 'Low'
  });

  // Check if active user can add new lessons:
  // - All Staff: View Access Only (unless designated Supervisor/HOD)
  // - HR: All Access
  // - Compliance Officer: All Access except Deletes
  // - DoC: All Access
  const canAddLesson = isHR || isComplianceOfficer || isDocAdmin || currentUser?.name.includes('Supervisor') || currentUser?.name.includes('HOD');

  const handleSaveLesson = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.whatHappened.trim() || !formData.lesson.trim()) {
      alert('Please fill in both the event description and the actionable lesson.');
      return;
    }

    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

    if (editingLesson) {
      setLessons(lessons.map(l => l.id === editingLesson.id ? { ...l, ...formData } : l));
      alert(`Lesson ${editingLesson.id} successfully updated.`);
      setEditingLesson(null);
    } else {
      const newId = 'LL-0' + (13 + lessons.length);
      const newEntry: LessonItem = {
        id: newId,
        ...formData,
        date: today,
        submittedBy: currentUser?.name || 'Institutional Contributor'
      };
      setLessons([newEntry, ...lessons]);
      alert(`Lesson Learned ${newId} registered into the institutional knowledge repository.`);
    }

    setFormData({
      issueRef: '',
      category: 'Procurement',
      whatHappened: '',
      rootCause: '',
      lesson: '',
      scope: 'All States',
      priority: 'High'
    });
  };

  const handleDeleteLesson = (id: string) => {
    if (!isDocAdmin) {
      alert('Action Restricted: Compliance Officers and Staff cannot delete lessons. Only the Director of Compliance has delete authority.');
      return;
    }
    if (!confirm(`Permanently delete lesson ${id}?`)) return;
    setLessons(lessons.filter(l => l.id !== id));
  };

  const getPriorityBadge = (p: string) => {
    switch (p) {
      case 'High': return <span className="pill pill-open">High</span>;
      case 'Medium': return <span className="pill pill-progress">Medium</span>;
      default: return <span className="pill pill-closed">Low</span>;
    }
  };

  // Filtered Lessons
  const filteredLessons = lessons.filter(l => {
    const matchesCat = activeCategory === 'All' || l.category === activeCategory;
    const matchesSearch = searchQuery === '' ||
      l.id.toLowerCase().includes(searchQuery.toLowerCase()) ||
      l.category.toLowerCase().includes(searchQuery.toLowerCase()) ||
      l.lesson.toLowerCase().includes(searchQuery.toLowerCase()) ||
      l.issueRef.toLowerCase().includes(searchQuery.toLowerCase()) ||
      l.scope.toLowerCase().includes(searchQuery.toLowerCase());

    return matchesCat && matchesSearch;
  });

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
          Lessons Learned & Knowledge Repository
        </h2>
        <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
          Capture organizational learning from compliance incidents, audits, and CAPs to prevent recurrence
        </p>

        {/* ROLE MATRIX SCOPE BADGES */}
        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-book-open-reader"></i> <strong>Staff Knowledge Portal:</strong> View-only access to institutional lessons, audit recommendations, and best practices.
          </div>
        )}
        {isHR && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-shield"></i> <strong>HR All Access:</strong> Record institutional lessons, update training curriculum insights, and manage organizational development.
          </div>
        )}
        {isComplianceOfficer && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-user-shield"></i> <strong>Compliance Officer:</strong> Add & edit lessons from closed CAPs and complaints. (Delete disabled).
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> All Access · Record, Edit, Categorize, and Admin Delete.
          </div>
        )}
      </div>

      {/* RECORD NEW LESSON CARD (Supervisor/HOD, HR, Compliance Officer, DoC) */}
      {canAddLesson && (
        <div className="card" style={{ marginBottom: 20 }}>
          <div className="card-header">
            <div className="card-title">
              <i className="fa-solid fa-pen-to-square" style={{ color: 'var(--accent)' }}></i> {editingLesson ? `Edit Lesson — ${editingLesson.id}` : '📝 Record New Lesson'}
            </div>
            {editingLesson && (
              <button className="btn btn-outline btn-sm" onClick={() => setEditingLesson(null)}>
                Cancel Editing
              </button>
            )}
          </div>

          <form onSubmit={handleSaveLesson}>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 12, marginBottom: 12 }}>
              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Related Issue / Incident Ref:</label>
                <input
                  type="text"
                  placeholder="e.g. CMP-048, CAP-032"
                  value={formData.issueRef}
                  onChange={(e) => setFormData({ ...formData, issueRef: e.target.value })}
                  style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                />
              </div>
              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Category:</label>
                <select
                  value={formData.category}
                  onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                  style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                >
                  <option>Procurement</option>
                  <option>Finance</option>
                  <option>Safeguarding</option>
                  <option>HR/Conduct</option>
                  <option>Information Security</option>
                  <option>Programme Delivery</option>
                  <option>Governance</option>
                </select>
              </div>
              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Applicable Geographic Scope:</label>
                <select
                  value={formData.scope}
                  onChange={(e) => setFormData({ ...formData, scope: e.target.value })}
                  style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                >
                  <option>All States</option>
                  <option>Lagos</option>
                  <option>Kano</option>
                  <option>Rivers</option>
                  <option>Abuja FCT</option>
                  <option>Kaduna</option>
                  <option>Borno</option>
                </select>
              </div>
            </div>

            <div style={{ marginBottom: 10 }}>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>What Happened (Incident Finding):</label>
              <textarea
                placeholder="Brief description of the compliance incident, non-conformity, or field challenge..."
                value={formData.whatHappened}
                onChange={(e) => setFormData({ ...formData, whatHappened: e.target.value })}
                required
                style={{ width: '100%', minHeight: 50, padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
              />
            </div>

            <div style={{ marginBottom: 10 }}>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Root Cause:</label>
              <textarea
                placeholder="What was the underlying root cause or system breakdown?"
                value={formData.rootCause}
                onChange={(e) => setFormData({ ...formData, rootCause: e.target.value })}
                required
                style={{ width: '100%', minHeight: 50, padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
              />
            </div>

            <div style={{ marginBottom: 12 }}>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Actionable Lesson / Institutional Recommendation:</label>
              <textarea
                placeholder="What should the organization do differently going forward to prevent recurrence?"
                value={formData.lesson}
                onChange={(e) => setFormData({ ...formData, lesson: e.target.value })}
                required
                style={{ width: '100%', minHeight: 60, padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
              />
            </div>

            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Priority Tier:</label>
                <select
                  value={formData.priority}
                  onChange={(e) => setFormData({ ...formData, priority: e.target.value as any })}
                  style={{ padding: '5px 8px', fontSize: 11, border: '1px solid var(--border)', borderRadius: 4 }}
                >
                  <option>High</option>
                  <option>Medium</option>
                  <option>Low</option>
                </select>
              </div>

              <button type="submit" className="btn btn-primary">
                <i className="fa-solid fa-floppy-disk"></i> {editingLesson ? 'Save Changes' : 'Save Lesson'}
              </button>
            </div>
          </form>
        </div>
      )}

      {/* LESSONS LIBRARY CARD & TABLE */}
      <div className="card">
        <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: 10 }}>
          <div className="card-title" style={{ margin: 0 }}>
            <i className="fa-solid fa-graduation-cap" style={{ color: 'var(--accent)' }}></i> 📚 Lessons Learned Library ({filteredLessons.length})
          </div>

          {/* Category Filter Pills & Search */}
          <div style={{ display: 'flex', gap: 6, alignItems: 'center', flexWrap: 'wrap' }}>
            {['All', 'Procurement', 'Finance', 'Safeguarding', 'Information Security'].map((cat) => (
              <button
                key={cat}
                onClick={() => setActiveCategory(cat)}
                style={{
                  padding: '3px 8px', fontSize: 10, fontWeight: 600, borderRadius: 12,
                  background: activeCategory === cat ? 'var(--accent)' : 'var(--surface2)',
                  color: activeCategory === cat ? '#fff' : 'var(--text-dim)',
                  border: '1px solid var(--border)', cursor: 'pointer'
                }}
              >
                {cat}
              </button>
            ))}

            {/* Live Search */}
            <div style={{ position: 'relative', marginLeft: 6 }}>
              <i className="fa-solid fa-magnifying-glass" style={{ position: 'absolute', left: 8, top: 7, color: 'var(--text-muted)', fontSize: 10 }}></i>
              <input
                type="text"
                placeholder="Search lessons..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                style={{
                  padding: '4px 8px 4px 24px', fontSize: 10,
                  border: '1px solid var(--border)', borderRadius: 6,
                  background: 'var(--surface2)', outline: 'none', width: 150
                }}
              />
            </div>
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Category</th>
              <th>Issue Ref</th>
              <th>Lesson Summary</th>
              <th>Scope</th>
              <th>Priority</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {filteredLessons.length === 0 ? (
              <tr>
                <td colSpan={8} style={{ textAlign: 'center', padding: 24, color: 'var(--text-muted)' }}>
                  No lessons found in this category.
                </td>
              </tr>
            ) : (
              filteredLessons.map((l) => (
                <tr key={l.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{l.id}</td>
                  <td>{l.category}</td>
                  <td style={{ fontSize: 11, color: 'var(--text-muted)' }}>{l.issueRef || '—'}</td>
                  <td style={{ fontSize: 11, maxWidth: 260, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }} title={l.lesson}>
                    {l.lesson}
                  </td>
                  <td>{l.scope}</td>
                  <td>{getPriorityBadge(l.priority)}</td>
                  <td style={{ fontSize: 11 }}>{l.date}</td>
                  <td>
                    <div style={{ display: 'flex', gap: 4, alignItems: 'center' }}>
                      <button
                        className="btn btn-outline btn-sm"
                        style={{ padding: '2px 6px', fontSize: 10 }}
                        onClick={() => setViewingLesson(l)}
                        title="View Full Lesson Dossier"
                      >
                        👁️
                      </button>

                      {/* Edit: HR, Compliance Officer & DoC */}
                      {(isHR || isComplianceOfficer || isDocAdmin) && (
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ padding: '2px 6px', fontSize: 10 }}
                          onClick={() => {
                            setFormData({
                              issueRef: l.issueRef,
                              category: l.category,
                              whatHappened: l.whatHappened,
                              rootCause: l.rootCause,
                              lesson: l.lesson,
                              scope: l.scope,
                              priority: l.priority
                            });
                            setEditingLesson(l);
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                          }}
                          title="Edit Lesson"
                        >
                          ✏️
                        </button>
                      )}

                      {/* Delete: DoC ONLY */}
                      {isDocAdmin && (
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ padding: '2px 6px', fontSize: 10, color: 'var(--danger)', borderColor: 'var(--danger)' }}
                          onClick={() => handleDeleteLesson(l.id)}
                          title="Admin Delete Lesson"
                        >
                          🗑️
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* MODAL: VIEW LESSON DOSSIER */}
      {viewingLesson && (
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
            width: 600,
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
                Lesson Learned Dossier — {viewingLesson.id}
              </div>
              <button
                onClick={() => setViewingLesson(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <div style={{ padding: 20, fontSize: 12 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 14 }}>
                <div><strong>Category:</strong> {viewingLesson.category}</div>
                <div><strong>Related Ref:</strong> {viewingLesson.issueRef || 'None'}</div>
                <div><strong>Geographic Scope:</strong> {viewingLesson.scope}</div>
                <div><strong>Priority:</strong> {getPriorityBadge(viewingLesson.priority)}</div>
                <div><strong>Contributed By:</strong> {viewingLesson.submittedBy}</div>
                <div><strong>Date Recorded:</strong> {viewingLesson.date}</div>
              </div>

              <div style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, border: '1px solid var(--border)', marginBottom: 12 }}>
                <strong>Incident Finding & Context:</strong>
                <p style={{ marginTop: 4, color: 'var(--text-dim)', lineHeight: 1.4 }}>{viewingLesson.whatHappened}</p>
              </div>

              <div style={{ background: '#fef3c7', padding: 12, borderRadius: 8, border: '1px solid #fde68a', marginBottom: 12 }}>
                <strong style={{ color: '#92400e' }}>🧩 Underlying Root Cause:</strong>
                <p style={{ marginTop: 4, color: '#78350f', lineHeight: 1.4 }}>{viewingLesson.rootCause}</p>
              </div>

              <div style={{ background: '#d1fae5', padding: 12, borderRadius: 8, border: '1px solid #6ee7b7', marginBottom: 14 }}>
                <strong style={{ color: '#065f46' }}>💡 Actionable Institutional Recommendation:</strong>
                <p style={{ marginTop: 4, color: '#064e3b', lineHeight: 1.4 }}>{viewingLesson.lesson}</p>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                <button className="btn btn-outline" onClick={() => setViewingLesson(null)}>
                  Close Dossier
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
