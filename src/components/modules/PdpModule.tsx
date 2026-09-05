import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export interface ObjectiveItem {
  id: number;
  title: string;
  subObjectives: { id: string; text: string; marks: number; evidenceSubmitted?: boolean; supervisorScore?: number }[];
}

export interface StaffPDP {
  id: string;
  staffName: string;
  email: string;
  position: string;
  department: string;
  state: string;
  supervisorName: string;
  hodName: string;
  pdpApproved: boolean;
  objectivesScore: number; // /60
  behaviouralScore: number; // /40
  innovationScore: number; // /50
  totalScore: number; // /150
  behaviouralGraded: boolean;
  innovationGraded: boolean;
  pendingEvidenceCount: number;
}

export const PdpModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();

  const isHR = currentUser?.key === 'hr';
  const isStaff = currentUser?.key === 'staff';

  // Active PDP Sub-Tab (default to Institutional Governance roster for DoC / HR)
  const [activeTab, setActiveTab] = useState<'objectives' | 'evidence' | 'verify' | 'behavioural' | 'innovation' | 'performance' | 'hr-audit'>(
    isDocAdmin || isHR ? 'hr-audit' : 'objectives'
  );
  const [showAddObjModal, setShowAddObjModal] = useState(false);
  const [newObjTitle, setNewObjTitle] = useState('');
  const [newSubObj1, setNewSubObj1] = useState('');
  const [newSubObj2, setNewSubObj2] = useState('');

  // Staff PDP Roster Data
  const [pdpRoster] = useState<StaffPDP[]>([
    {
      id: 'PDP-001',
      staffName: 'Fatima Bello',
      email: 'staff@cccrn.org',
      position: 'Clinical Officer',
      department: 'Clinical Services',
      state: 'Lagos',
      supervisorName: 'Emeka Nwosu (Supervisor)',
      hodName: 'Dr. Biodun Ojo (HOD)',
      pdpApproved: true,
      objectivesScore: 48,
      behaviouralScore: 34,
      innovationScore: 42,
      totalScore: 124,
      behaviouralGraded: true,
      innovationGraded: true,
      pendingEvidenceCount: 1
    },
    {
      id: 'PDP-002',
      staffName: 'Ibrahim Garba',
      email: 'i.garba@cccrn.org',
      position: 'M&E Specialist',
      department: 'Strategic Information',
      state: 'Kano',
      supervisorName: 'Emeka Nwosu (Supervisor)',
      hodName: 'Dr. Biodun Ojo (HOD)',
      pdpApproved: false, // Pending supervisor approval
      objectivesScore: 0,
      behaviouralScore: 0,
      innovationScore: 0,
      totalScore: 0,
      behaviouralGraded: false,
      innovationGraded: false,
      pendingEvidenceCount: 2
    },
    {
      id: 'PDP-003',
      staffName: 'Ngozi Okoro',
      email: 'n.okoro@cccrn.org',
      position: 'Finance Associate',
      department: 'Finance & Admin',
      state: 'Rivers',
      supervisorName: 'Chidinma Okoro (Supervisor)',
      hodName: 'Dr. Biodun Ojo (HOD)',
      pdpApproved: true,
      objectivesScore: 52,
      behaviouralScore: 0, // Pending monthly grading
      innovationScore: 40,
      totalScore: 92,
      behaviouralGraded: false,
      innovationGraded: true,
      pendingEvidenceCount: 0
    },
    {
      id: 'PDP-004',
      staffName: 'Umar Farouk',
      email: 'u.farouk@cccrn.org',
      position: 'Field Logistics Officer',
      department: 'Operations',
      state: 'Kaduna',
      supervisorName: 'Emeka Nwosu (Supervisor)',
      hodName: 'Dr. Biodun Ojo (HOD)',
      pdpApproved: true,
      objectivesScore: 38,
      behaviouralScore: 28,
      innovationScore: 0, // Pending HOD grading
      totalScore: 66,
      behaviouralGraded: true,
      innovationGraded: false,
      pendingEvidenceCount: 3
    }
  ]);

  // Current Staff 4 Objectives State
  const [objectives, setObjectives] = useState<ObjectiveItem[]>([
    {
      id: 1,
      title: 'HIV Testing Services (HTS) Target & Quality Assurance',
      subObjectives: [
        { id: '1.1', text: 'Achieve 95% linkage rate for identified HIV-positive clients across assigned facilities', marks: 7.5, evidenceSubmitted: true, supervisorScore: 6.5 },
        { id: '1.2', text: 'Conduct weekly recency testing compliance checks with zero specimen rejection', marks: 7.5, evidenceSubmitted: true, supervisorScore: 7.0 }
      ]
    },
    {
      id: 2,
      title: 'Viral Load Suppression & Client Retention Tracking',
      subObjectives: [
        { id: '2.1', text: 'Maintain 98% 12-month cohort retention in ART care in Cluster A facilities', marks: 7.5, evidenceSubmitted: true, supervisorScore: 6.0 },
        { id: '2.2', text: 'Ensure 100% of eligible clients receive viral load cascade tests on schedule', marks: 7.5, evidenceSubmitted: false }
      ]
    },
    {
      id: 3,
      title: 'Compliance With Grant Standard Operating Procedures (SOPs)',
      subObjectives: [
        { id: '3.1', text: 'Ensure 100% adherence to USAID & Global Fund dual-signatory protocols', marks: 7.5, evidenceSubmitted: true, supervisorScore: 7.5 },
        { id: '3.2', text: 'Zero audit non-conformities identified during quarterly internal compliance reviews', marks: 7.5, evidenceSubmitted: false }
      ]
    },
    {
      id: 4,
      title: 'Capacity Building, Mentorship & Facility Staff Mentorship',
      subObjectives: [
        { id: '4.1', text: 'Conduct 4 monthly on-site mentorship sessions for facility focal persons', marks: 7.5, evidenceSubmitted: true, supervisorScore: 7.0 },
        { id: '4.2', text: 'Facilitate 100% staff completion of mandatory PSEA and Anti-Fraud certifications', marks: 7.5, evidenceSubmitted: true, supervisorScore: 7.5 }
      ]
    }
  ]);

  const handleCreateObjective = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newObjTitle.trim()) return;

    const newId = objectives.length + 1;
    const newEntry: ObjectiveItem = {
      id: newId,
      title: newObjTitle.trim(),
      subObjectives: [
        { id: `${newId}.1`, text: newSubObj1 || 'Execute key milestone deliverable per project workplan', marks: 7.5, evidenceSubmitted: false },
        { id: `${newId}.2`, text: newSubObj2 || 'Ensure 100% compliance with institutional quality standards', marks: 7.5, evidenceSubmitted: false }
      ]
    };

    setObjectives([...objectives, newEntry]);
    setShowAddObjModal(false);
    setNewObjTitle('');
    setNewSubObj1('');
    setNewSubObj2('');
    alert('✓ New Institutional Objective created successfully.');
  };

  // Innovation Submission State
  const [innovationTitle, setInnovationTitle] = useState('');
  const [innovationDesc, setInnovationDesc] = useState('');
  const [innovationImpact, setInnovationImpact] = useState('High');

  // Evidence Form State
  const [selectedSubObj, setSelectedSubObj] = useState('1.2');
  const [evidenceNotes, setEvidenceNotes] = useState('');

  // Active user's scores
  const myPdp = pdpRoster[0]; // Active staff view
  const totalObjScore = objectives.reduce((acc, o) => acc + o.subObjectives.reduce((sAcc, sub) => sAcc + (sub.supervisorScore || 0), 0), 0);
  const myTotalScore = totalObjScore + myPdp.behaviouralScore + myPdp.innovationScore;

  // HR Filter & Delinquency State
  const [hrFilter, setHrFilter] = useState<'all' | 'pending-approval' | 'pending-behavioural' | 'pending-innovation'>('all');

  const filteredPdpList = pdpRoster.filter(p => {
    if (hrFilter === 'pending-approval') return !p.pdpApproved;
    if (hrFilter === 'pending-behavioural') return !p.behaviouralGraded;
    if (hrFilter === 'pending-innovation') return !p.innovationGraded;
    return true;
  });

  const handleAddSubObjective = (mainId: number) => {
    const text = prompt('Enter description for new sub-objective:');
    if (!text) return;
    setObjectives(objectives.map(o => {
      if (o.id === mainId) {
        const newCount = o.subObjectives.length + 1;
        const newMarks = parseFloat((15 / newCount).toFixed(2));
        const updatedSubs = [...o.subObjectives, { id: `${mainId}.${newCount}`, text, marks: newMarks }];
        return {
          ...o,
          subObjectives: updatedSubs.map(s => ({ ...s, marks: newMarks }))
        };
      }
      return o;
    }));
  };

  const handleSubmitEvidence = (e: React.FormEvent) => {
    e.preventDefault();
    if (!evidenceNotes.trim()) {
      alert('Please enter evidence documentation notes.');
      return;
    }
    alert(`Evidence for Sub-Objective ${selectedSubObj} successfully uploaded and queued for Supervisor Verification.`);
    setEvidenceNotes('');
  };

  const handleSupervisorScore = (mainId: number, subId: string, score: number) => {
    setObjectives(objectives.map(o => {
      if (o.id === mainId) {
        return {
          ...o,
          subObjectives: o.subObjectives.map(s => s.id === subId ? { ...s, supervisorScore: score } : s)
        };
      }
      return o;
    }));
    alert(`Score of ${score} saved for Sub-Objective ${subId}.`);
  };

  const handleSubmitInnovation = (e: React.FormEvent) => {
    e.preventDefault();
    if (!innovationTitle.trim()) {
      alert('Please enter an innovation title.');
      return;
    }
    alert(`Innovation project "${innovationTitle}" submitted to Head of Department (HOD) for grading out of 50 marks.`);
    setInnovationTitle('');
    setInnovationDesc('');
  };

  const handleExportHRReport = (format: string) => {
    alert(`Generating ${format} Master Performance Report across all 490 institutional personnel...`);
  };

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
          Performance Development Plans (PDP)
        </h2>
        <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
          COP Year: October 2025 – September 2026 &nbsp;|&nbsp; 4 Main Objectives · Behavioural Competencies · Creativity/Innovation &nbsp;|&nbsp; Auto-scored out of 150
        </p>

        {/* ROLE CONTEXT BADGES */}
        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-bullseye"></i> <strong>Staff Portal:</strong> Set your 4 COP objectives, submit monthly proof, and track your performance score out of 150.
          </div>
        )}
        {isHR && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--warning-light)', color: '#b45309', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-clipboard-list"></i> <strong>HR Master Audit:</strong> Full oversight across all institutional PDPs, delinquent reviewer drill-downs, and performance exports.
          </div>
        )}
        {isDocAdmin && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'rgba(124, 58, 237, 0.08)', color: 'var(--accent2)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> View-Only institutional performance governance and appraisal oversight.
          </div>
        )}
      </div>

      {/* 4 SCORE TILES (/150) matching wireframe */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 12, marginBottom: 20 }}>
        <div style={{ background: '#e0f2fe', border: '1px solid #bae6fd', borderRadius: 12, padding: 16, textAlign: 'center' }}>
          <div style={{ fontSize: 10, color: '#0369a1', textTransform: 'uppercase', letterSpacing: 1, marginBottom: 4, fontWeight: 700 }}>
            Set Objectives
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 32, fontWeight: 800, color: '#0077b6' }}>
            {totalObjScore.toFixed(1)}
          </div>
          <div style={{ fontSize: 11, color: '#64748b', fontWeight: 600 }}>/ 60 marks</div>
          <div style={{ fontSize: 9, color: '#0369a1', marginTop: 3 }}>Supervisor-scored</div>
        </div>

        <div style={{ background: '#ede9fe', border: '1px solid #c4b5fd', borderRadius: 12, padding: 16, textAlign: 'center' }}>
          <div style={{ fontSize: 10, color: '#6d28d9', textTransform: 'uppercase', letterSpacing: 1, marginBottom: 4, fontWeight: 700 }}>
            Behavioural Competence
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 32, fontWeight: 800, color: '#7c3aed' }}>
            {myPdp.behaviouralScore}
          </div>
          <div style={{ fontSize: 11, color: '#64748b', fontWeight: 600 }}>/ 40 marks</div>
          <div style={{ fontSize: 9, color: '#6d28d9', marginTop: 3 }}>Monthly graded</div>
        </div>

        <div style={{ background: '#d1fae5', border: '1px solid #6ee7b7', borderRadius: 12, padding: 16, textAlign: 'center' }}>
          <div style={{ fontSize: 10, color: '#065f46', textTransform: 'uppercase', letterSpacing: 1, marginBottom: 4, fontWeight: 700 }}>
            Creativity / Innovation
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 32, fontWeight: 800, color: '#059669' }}>
            {myPdp.innovationScore}
          </div>
          <div style={{ fontSize: 11, color: '#64748b', fontWeight: 600 }}>/ 50 marks</div>
          <div style={{ fontSize: 9, color: '#065f46', marginTop: 3 }}>HOD-graded</div>
        </div>

        <div style={{ background: '#0077b6', border: '1px solid #005f99', borderRadius: 12, padding: 16, textAlign: 'center', color: '#ffffff' }}>
          <div style={{ fontSize: 10, color: '#bae6fd', textTransform: 'uppercase', letterSpacing: 1, marginBottom: 4, fontWeight: 700 }}>
            Overall Appraisal
          </div>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 32, fontWeight: 800, color: '#ffffff' }}>
            {myTotalScore.toFixed(1)}
          </div>
          <div style={{ fontSize: 11, color: '#bae6fd', fontWeight: 600 }}>/ 150 total</div>
          <div style={{ fontSize: 9, color: '#bae6fd', marginTop: 3 }}>Auto-calculated</div>
        </div>
      </div>

      {/* SUB-TABS NAVIGATION */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 16, flexWrap: 'wrap' }}>
        <button
          onClick={() => setActiveTab('objectives')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'objectives' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'objectives' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          📝 Set Objectives
        </button>
        <button
          onClick={() => setActiveTab('evidence')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'evidence' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'evidence' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          📎 Submit Evidence
        </button>
        <button
          onClick={() => setActiveTab('verify')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'verify' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'verify' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          🔍 Supervisor Verify (/60)
        </button>
        <button
          onClick={() => setActiveTab('behavioural')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'behavioural' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'behavioural' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          🧠 Behavioural (/40)
        </button>
        <button
          onClick={() => setActiveTab('innovation')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'innovation' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'innovation' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          💡 Creativity / Innovation (/50)
        </button>
        <button
          onClick={() => setActiveTab('performance')}
          style={{
            padding: '7px 14px',
            fontSize: 12,
            fontWeight: 600,
            cursor: 'pointer',
            borderRadius: 'var(--radius-sm)',
            background: activeTab === 'performance' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'performance' ? '#ffffff' : 'var(--text-dim)',
            border: '1px solid var(--border)'
          }}
        >
          🏆 My Performance Review
        </button>

        {/* HR MASTER AUDIT TAB (HR & DoC) */}
        {(isHR || isDocAdmin) && (
          <button
            onClick={() => setActiveTab('hr-audit')}
            style={{
              padding: '7px 14px',
              fontSize: 12,
              fontWeight: 700,
              cursor: 'pointer',
              borderRadius: 'var(--radius-sm)',
              background: activeTab === 'hr-audit' ? 'var(--accent2)' : 'var(--surface)',
              color: activeTab === 'hr-audit' ? '#ffffff' : 'var(--accent2)',
              border: '1px solid var(--accent2)'
            }}
          >
            📋 HR Master Audit & Reports
          </button>
        )}
      </div>

      {/* 1. SET OBJECTIVES VIEW */}
      {activeTab === 'objectives' && (
        <div>
          <div style={{ background: '#e0f2fe', borderLeft: '4px solid #0077b6', borderRadius: '0 8px 8px 0', padding: '12px 16px', marginBottom: 16, fontSize: 12, color: '#0369a1' }}>
            <strong>ℹ️ Instructions:</strong> Set exactly <strong>4 main objectives</strong> for COP Year <strong>October 2025 – September 2026</strong>. Each objective carries <strong>15 marks</strong> (Total 60 marks). Sub-objectives automatically split the 15 marks equally.
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            {objectives.map((obj) => (
              <div key={obj.id} className="card" style={{ padding: 18 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 10 }}>
                  <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 14, fontWeight: 700, color: 'var(--text)' }}>
                    Objective {obj.id}: {obj.title}
                  </div>
                  <span style={{ fontSize: 11, fontWeight: 700, color: 'var(--accent)', background: 'var(--surface2)', padding: '2px 8px', borderRadius: 6 }}>
                    15.0 Marks Allocation
                  </span>
                </div>

                <div style={{ display: 'flex', flexDirection: 'column', gap: 8, marginBottom: 12 }}>
                  {obj.subObjectives.map((sub) => (
                    <div key={sub.id} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', background: 'var(--surface2)', padding: '8px 12px', borderRadius: 6, fontSize: 12 }}>
                      <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
                        <span style={{ fontWeight: 700, color: 'var(--accent)' }}>{sub.id}</span>
                        <span>{sub.text}</span>
                      </div>
                      <span style={{ fontWeight: 700, color: 'var(--text-dim)', fontSize: 11 }}>
                        {sub.marks} marks
                      </span>
                    </div>
                  ))}
                </div>

                <button
                  className="btn btn-outline btn-sm"
                  onClick={() => handleAddSubObjective(obj.id)}
                >
                  <i className="fa-solid fa-plus"></i> Add Sub-Objective
                </button>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* 2. SUBMIT EVIDENCE VIEW */}
      {activeTab === 'evidence' && (
        <div className="card" style={{ maxWidth: 650 }}>
          <div className="card-header">
            <div className="card-title"><i className="fa-solid fa-cloud-arrow-up"></i> Submit Objective Evidence</div>
          </div>
          <form onSubmit={handleSubmitEvidence} style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
            <div>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Select Sub-Objective:</label>
              <select
                value={selectedSubObj}
                onChange={(e) => setSelectedSubObj(e.target.value)}
                style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
              >
                {objectives.flatMap(o => o.subObjectives).map(sub => (
                  <option key={sub.id} value={sub.id}>Sub-Obj {sub.id}: {sub.text.substring(0, 60)}...</option>
                ))}
              </select>
            </div>
            <div>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Evidence Documentation / Output:</label>
              <textarea
                placeholder="Describe key outputs achieved, verification links, or facility data..."
                value={evidenceNotes}
                onChange={(e) => setEvidenceNotes(e.target.value)}
                required
                style={{ width: '100%', minHeight: 80, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
              />
            </div>
            <div>
              <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Attach Proof File (PDF/Excel/Report):</label>
              <input type="file" style={{ width: '100%', padding: 6, fontSize: 11, marginTop: 4 }} />
            </div>
            <button type="submit" className="btn btn-primary" style={{ alignSelf: 'flex-start' }}>
              <i className="fa-solid fa-paper-plane"></i> Submit Evidence for Verification
            </button>
          </form>
        </div>
      )}

      {/* 3. SUPERVISOR VERIFY VIEW */}
      {activeTab === 'verify' && (
        <div className="card">
          <div className="card-header">
            <div className="card-title"><i className="fa-solid fa-magnifying-glass"></i> Supervisor Objective Verification & Grading Queue</div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Sub-Obj</th>
                <th>Objective Details</th>
                <th>Max Marks</th>
                <th>Evidence Status</th>
                <th>Supervisor Score</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {objectives.flatMap(o => o.subObjectives.map(sub => (
                <tr key={sub.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{sub.id}</td>
                  <td style={{ fontSize: 12, maxWidth: 300 }}>{sub.text}</td>
                  <td style={{ fontWeight: 700 }}>{sub.marks} pts</td>
                  <td>
                    {sub.evidenceSubmitted ? (
                      <span className="pill pill-closed">✓ Evidence Attached</span>
                    ) : (
                      <span className="pill pill-open">Pending Evidence</span>
                    )}
                  </td>
                  <td>
                    <input
                      type="number"
                      step="0.5"
                      max={sub.marks}
                      defaultValue={sub.supervisorScore || ''}
                      placeholder="0.0"
                      id={`score-${sub.id}`}
                      style={{ width: 60, padding: '4px 6px', fontSize: 11, border: '1px solid var(--border)', borderRadius: 4, textAlign: 'center' }}
                    />
                  </td>
                  <td>
                    <button
                      className="btn btn-primary btn-sm"
                      onClick={() => {
                        const val = parseFloat((document.getElementById(`score-${sub.id}`) as HTMLInputElement)?.value || '0');
                        handleSupervisorScore(parseInt(sub.id.split('.')[0]), sub.id, val);
                      }}
                    >
                      Save Grade
                    </button>
                  </td>
                </tr>
              )))}
            </tbody>
          </table>
        </div>
      )}

      {/* 4. BEHAVIOURAL COMPETENCE VIEW */}
      {activeTab === 'behavioural' && (
        <div className="card" style={{ maxWidth: 700 }}>
          <div className="card-header">
            <div className="card-title"><i className="fa-solid fa-brain"></i> Monthly Behavioural Competence Evaluation (/40 Marks)</div>
          </div>
          <p style={{ fontSize: 12, color: 'var(--text-muted)', marginBottom: 16 }}>
            Graded monthly by Supervisor across 5 core institutional values (8 marks each):
          </p>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
            {[
              { title: '1. Professional Integrity & Compliance Adherence', desc: 'Maintains high ethical standards, zero compromise on donor regulations.', score: 7.0 },
              { title: '2. Communication & Interpersonal Collaboration', desc: 'Clear reporting, timely responsiveness, and cross-cluster teamwork.', score: 6.5 },
              { title: '3. Reliability & Punctuality', desc: 'Consistent delivery of tasks before deadlines and active meeting participation.', score: 7.0 },
              { title: '4. Problem Solving & Critical Initiative', desc: 'Proactively identifies facility challenges and proposes viable solutions.', score: 6.5 },
              { title: '5. Client & Stakeholder Respect', desc: 'Exemplifies patient dignity, safeguarding, and anti-harassment standards.', score: 7.0 }
            ].map((b, idx) => (
              <div key={idx} style={{ background: 'var(--surface2)', padding: 12, borderRadius: 8, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <div>
                  <div style={{ fontWeight: 700, fontSize: 12, color: 'var(--text)' }}>{b.title}</div>
                  <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2 }}>{b.desc}</div>
                </div>
                <div style={{ textAlign: 'right' }}>
                  <span style={{ fontWeight: 800, fontSize: 14, color: 'var(--accent2)' }}>{b.score}</span>
                  <span style={{ fontSize: 10, color: 'var(--text-muted)' }}> / 8.0</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* 5. CREATIVITY / INNOVATION VIEW */}
      {activeTab === 'innovation' && (
        <div className="grid-2">
          {/* Submit Innovation (Staff) */}
          <div className="card">
            <div className="card-header">
              <div className="card-title"><i className="fa-solid fa-lightbulb"></i> Submit Creativity / Innovation Project</div>
            </div>
            <form onSubmit={handleSubmitInnovation} style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Innovation Title:</label>
                <input
                  type="text"
                  placeholder="e.g. Automated EMR Recency Flagging Tool"
                  value={innovationTitle}
                  onChange={(e) => setInnovationTitle(e.target.value)}
                  required
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>
              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Innovation Impact Tier:</label>
                <select
                  value={innovationImpact}
                  onChange={(e) => setInnovationImpact(e.target.value)}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4, marginBottom: 8 }}
                >
                  <option>High (Institutional Efficiency)</option>
                  <option>Medium (Cluster / Facility Level)</option>
                  <option>Transformational (Grant-Wide)</option>
                </select>
              </div>
              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Expected Impact & Efficiency Gains:</label>
                <textarea
                  placeholder="Describe how this innovation improves efficiency, reduces costs, or enhances data quality..."
                  value={innovationDesc}
                  onChange={(e) => setInnovationDesc(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 70, padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                />
              </div>
              <button type="submit" className="btn btn-primary btn-sm" style={{ alignSelf: 'flex-start' }}>
                <i className="fa-solid fa-paper-plane"></i> Submit to HOD
              </button>
            </form>
          </div>

          {/* HOD Evaluation Card */}
          <div className="card" style={{ background: 'linear-gradient(135deg, #f0fdf4, #e6f9ed)' }}>
            <div className="card-header">
              <div className="card-title"><i className="fa-solid fa-award"></i> HOD Innovation Grading (/50 Marks)</div>
            </div>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginBottom: 12 }}>
              Evaluated directly by the Head of Department across institutional impact, scalability, and cost optimization.
            </p>
            <div style={{ background: '#ffffff', border: '1px solid #86efac', borderRadius: 8, padding: 14 }}>
              <div style={{ fontWeight: 700, fontSize: 13, color: '#065f46' }}>Current Evaluated Innovation:</div>
              <div style={{ fontSize: 12, color: 'var(--text)', marginTop: 4 }}>"WhatsApp Bot for Fast-Tracking Community Viral Load Results"</div>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: 12 }}>
                <span style={{ fontSize: 11, color: 'var(--text-muted)' }}>Graded by Dr. Biodun Ojo (HOD)</span>
                <span style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 22, fontWeight: 800, color: '#059669' }}>42.0 / 50</span>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* 6. PERFORMANCE REVIEW SUMMARY VIEW */}
      {activeTab === 'performance' && (
        <div className="card">
          <div className="card-header">
            <div className="card-title"><i className="fa-solid fa-chart-line"></i> Annual Performance Review Rollup (/150 Total)</div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Appraisal Domain</th>
                <th>Maximum Marks</th>
                <th>Achieved Score</th>
                <th>Grading Authority</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style={{ fontWeight: 600 }}>1. Set Objectives (4 Main Objectives)</td>
                <td>60.0</td>
                <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{totalObjScore.toFixed(1)}</td>
                <td>Supervisor Verified</td>
                <td><span className="pill pill-closed">Graded</span></td>
              </tr>
              <tr>
                <td style={{ fontWeight: 600 }}>2. Behavioural Competencies (Monthly)</td>
                <td>40.0</td>
                <td style={{ fontWeight: 700, color: 'var(--accent2)' }}>{myPdp.behaviouralScore.toFixed(1)}</td>
                <td>Supervisor Monthly</td>
                <td><span className="pill pill-closed">Graded</span></td>
              </tr>
              <tr>
                <td style={{ fontWeight: 600 }}>3. Creativity / Innovation</td>
                <td>50.0</td>
                <td style={{ fontWeight: 700, color: 'var(--success)' }}>{myPdp.innovationScore.toFixed(1)}</td>
                <td>HOD Graded</td>
                <td><span className="pill pill-closed">Graded</span></td>
              </tr>
              <tr style={{ background: 'var(--surface2)' }}>
                <td style={{ fontWeight: 800 }}>OVERALL APPRAISAL TOTAL</td>
                <td style={{ fontWeight: 800 }}>150.0</td>
                <td style={{ fontWeight: 800, fontSize: 16, color: 'var(--accent)' }}>{myTotalScore.toFixed(1)}</td>
                <td style={{ fontWeight: 700 }}>Auto-Calculated</td>
                <td><span className="pill pill-closed" style={{ fontSize: 11 }}>Excellent (82.7%)</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      )}

      {/* 7. HR MASTER AUDIT & DELINQUENCY REPORTS (HR & DoC Only) */}
      {activeTab === 'hr-audit' && (isHR || isDocAdmin) && (
        <div>
          {/* HR ACTIONS STRIP */}
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16, background: 'var(--surface)', padding: '12px 18px', borderRadius: 'var(--radius-md)', border: '1px solid var(--border)' }}>
            <div>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 15, fontWeight: 700, color: 'var(--accent2)' }}>
                <i className="fa-solid fa-user-shield"></i> HR Master PDP Audit & Delinquent Tracker
              </div>
              <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 2 }}>
                Real-time compliance monitoring on pending PDP submissions, unapproved evidence, and overdue supervisor evaluations
              </div>
            </div>
            <div style={{ display: 'flex', gap: 6 }}>
              <button className="btn btn-outline btn-sm" onClick={() => handleExportHRReport('Monthly PDF')}>
                <i className="fa-solid fa-file-pdf"></i> Monthly Report
              </button>
              <button className="btn btn-outline btn-sm" onClick={() => handleExportHRReport('Quarterly CSV')}>
                <i className="fa-solid fa-file-csv"></i> Quarterly Export
              </button>
              <button className="btn btn-primary btn-sm" onClick={() => handleExportHRReport('Annual Master')}>
                <i className="fa-solid fa-award"></i> Annual Master Report
              </button>
            </div>
          </div>

          {/* DELINQUENCY FILTER BUTTONS */}
          <div style={{ display: 'flex', gap: 6, marginBottom: 14 }}>
            <button
              onClick={() => setHrFilter('all')}
              style={{
                padding: '4px 10px', fontSize: 11, borderRadius: 14,
                border: '1px solid var(--border)',
                background: hrFilter === 'all' ? 'var(--accent2)' : 'var(--surface)',
                color: hrFilter === 'all' ? '#fff' : 'var(--text-dim)',
                cursor: 'pointer', fontWeight: 600
              }}
            >
              All Staff ({pdpRoster.length})
            </button>
            <button
              onClick={() => setHrFilter('pending-approval')}
              style={{
                padding: '4px 10px', fontSize: 11, borderRadius: 14,
                border: '1px solid var(--border)',
                background: hrFilter === 'pending-approval' ? 'var(--danger)' : 'var(--surface)',
                color: hrFilter === 'pending-approval' ? '#fff' : 'var(--danger)',
                cursor: 'pointer', fontWeight: 600
              }}
            >
              Pending PDP Approval (1)
            </button>
            <button
              onClick={() => setHrFilter('pending-behavioural')}
              style={{
                padding: '4px 10px', fontSize: 11, borderRadius: 14,
                border: '1px solid var(--border)',
                background: hrFilter === 'pending-behavioural' ? 'var(--warning)' : 'var(--surface)',
                color: hrFilter === 'pending-behavioural' ? '#fff' : '#b45309',
                cursor: 'pointer', fontWeight: 600
              }}
            >
              Delinquent Behavioral Review (2)
            </button>
            <button
              onClick={() => setHrFilter('pending-innovation')}
              style={{
                padding: '4px 10px', fontSize: 11, borderRadius: 14,
                border: '1px solid var(--border)',
                background: hrFilter === 'pending-innovation' ? 'var(--accent)' : 'var(--surface)',
                color: hrFilter === 'pending-innovation' ? '#fff' : 'var(--accent)',
                cursor: 'pointer', fontWeight: 600
              }}
            >
              Pending HOD Innovation (1)
            </button>
          </div>

          {/* HR MASTER TABLE */}
          <div className="card">
            <table>
              <thead>
                <tr>
                  <th>Staff Name</th>
                  <th>Department / State</th>
                  <th>Supervisor</th>
                  <th>HOD</th>
                  <th>PDP Approval</th>
                  <th>Behavioral (/40)</th>
                  <th>Innovation (/50)</th>
                  <th>Total (/150)</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {filteredPdpList.map((p) => (
                  <tr key={p.id}>
                    <td style={{ fontWeight: 700 }}>{p.staffName}</td>
                    <td>{p.department} · {p.state}</td>
                    <td style={{ fontSize: 11 }}>{p.supervisorName}</td>
                    <td style={{ fontSize: 11 }}>{p.hodName}</td>
                    <td>
                      {p.pdpApproved ? (
                        <span className="pill pill-closed">Approved</span>
                      ) : (
                        <span className="pill pill-open">Pending Approval</span>
                      )}
                    </td>
                    <td>
                      {p.behaviouralGraded ? (
                        <span style={{ fontWeight: 700, color: 'var(--accent2)' }}>{p.behaviouralScore} pts</span>
                      ) : (
                        <span className="pill pill-open" style={{ fontSize: 9 }}>Delinquent</span>
                      )}
                    </td>
                    <td>
                      {p.innovationGraded ? (
                        <span style={{ fontWeight: 700, color: 'var(--success)' }}>{p.innovationScore} pts</span>
                      ) : (
                        <span className="pill pill-progress" style={{ fontSize: 9 }}>Ungraded</span>
                      )}
                    </td>
                    <td style={{ fontWeight: 800, color: 'var(--accent)' }}>
                      {p.totalScore} / 150
                    </td>
                    <td>
                      <button
                        className="btn btn-outline btn-sm"
                        onClick={() => alert(`Drilling down into ${p.staffName}'s comprehensive PDP dossier & audit trail...`)}
                      >
                        👁️ Audit
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    {/* ADMIN / SUPERVISOR ADD OBJECTIVE MODAL */}
      {showAddObjModal && (
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
            padding: 24,
            width: 540,
            maxWidth: '100%',
            boxShadow: 'var(--shadow-lg)'
          }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 }}>
              <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                <i className="fa-solid fa-bullseye" style={{ color: 'var(--accent)', marginRight: 8 }}></i>
                Create Institutional Objective
              </div>
              <button
                onClick={() => setShowAddObjModal(false)}
                style={{ background: 'none', border: 'none', fontSize: 18, color: 'var(--text-muted)', cursor: 'pointer' }}
              >
                &times;
              </button>
            </div>

            <form onSubmit={handleCreateObjective}>
              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 11, color: 'var(--text-muted)', fontWeight: 700, textTransform: 'uppercase', display: 'block', marginBottom: 4 }}>
                  Main Objective Title *
                </label>
                <input
                  type="text"
                  required
                  placeholder="e.g. Expand Clinical Viral Load Cascade & Client Tracking"
                  value={newObjTitle}
                  onChange={(e) => setNewObjTitle(e.target.value)}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, background: 'var(--surface2)' }}
                />
              </div>

              <div style={{ marginBottom: 12 }}>
                <label style={{ fontSize: 11, color: 'var(--text-muted)', fontWeight: 700, textTransform: 'uppercase', display: 'block', marginBottom: 4 }}>
                  Sub-Objective 1 (7.5 Marks) *
                </label>
                <input
                  type="text"
                  required
                  placeholder="e.g. Ensure 95% linkage rate for identified clients"
                  value={newSubObj1}
                  onChange={(e) => setNewSubObj1(e.target.value)}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, background: 'var(--surface2)' }}
                />
              </div>

              <div style={{ marginBottom: 16 }}>
                <label style={{ fontSize: 11, color: 'var(--text-muted)', fontWeight: 700, textTransform: 'uppercase', display: 'block', marginBottom: 4 }}>
                  Sub-Objective 2 (7.5 Marks) *
                </label>
                <input
                  type="text"
                  required
                  placeholder="e.g. Complete weekly quality assurance verification audits"
                  value={newSubObj2}
                  onChange={(e) => setNewSubObj2(e.target.value)}
                  style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, background: 'var(--surface2)' }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline btn-sm" onClick={() => setShowAddObjModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary btn-sm">
                  Save Objective
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
