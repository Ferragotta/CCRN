/**
 * CCCRN ComplianceIQ — Asynchronous API Integration Client
 * Built for seamless backend integration (PHP REST API / Node / Python).
 * 
 * To switch from Mock data to live PHP backend:
 * Set ApiConfig.USE_MOCK = false and adjust ApiConfig.BASE_URL if needed.
 */

const ApiConfig = {
  USE_MOCK: true,
  BASE_URL: '/api' // Base endpoint for PHP backend (e.g. /api/complaints.php)
};

// Initial Seed Data Store (Used when USE_MOCK is true or for offline development)
const MockDatabase = {
  complaints: [
    { id: 'CMP-048', cat: 'Procurement', state: 'Kano', by: 'Anonymous', date: '28 Feb 2026', desc: 'Direct sourcing of generator diesel without 3 comparative vendor quotes.', status: 'Open' },
    { id: 'CMP-047', cat: 'Safeguarding', state: 'Lagos', by: 'Named Staff', date: '25 Feb 2026', desc: 'Allegation of verbal harassment during quarterly cluster review meeting.', status: 'In Progress' },
    { id: 'CMP-046', cat: 'Finance', state: 'Rivers', by: 'Named Staff', date: '22 Feb 2026', desc: 'Delay in per diem advance retirement for clinical outreaches.', status: 'Converted to CAP' },
    { id: 'CMP-045', cat: 'HR', state: 'Abuja FCT', by: 'Fatima Bello', date: '19 Feb 2026', desc: 'Performance appraisal grading inconsistency.', status: 'Closed' },
    { id: 'CMP-044', cat: 'Procurement', state: 'Rivers', by: 'Anonymous', date: '15 Feb 2026', desc: 'Vendor delivery of medical consumables without inspection certificate.', status: 'Converted to CAP' },
    { id: 'CMP-043', cat: 'Finance', state: 'Kaduna', by: 'Named Staff', date: '10 Feb 2026', desc: 'Suspicion of duplicate transport reimbursement claims in Kaduna cluster.', status: 'Converted to Investigation' },
    { id: 'CMP-042', cat: 'Data', state: 'Borno', by: 'Fatima Bello', date: '04 Feb 2026', desc: 'Unencrypted patient intake records shared over open WhatsApp channel.', status: 'Open' }
  ],

  caps: [
    { id: 'CAP-032', issue: 'Single-quotation fuel supply breach', state: 'Kano', linked: 'CMP-048', resp: 'Biodun Ojo', deadline: '15 Mar 2026', status: 'Open', ev: null },
    { id: 'CAP-031', issue: 'Unsecured patient registry files', state: 'Borno', linked: 'CMP-042', resp: 'Fatima Bello', deadline: '10 Mar 2026', status: 'Evidence Submitted', ev: 'Borno_HIPAA_Proof.pdf' },
    { id: 'CAP-030', issue: 'Procurement dual-signoff bypass', state: 'Rivers', linked: 'CMP-044', resp: 'Emeka Nwosu', deadline: '20 Mar 2026', status: 'In Progress', ev: null },
    { id: 'CAP-029', issue: 'Advance liquidation reconciliation', state: 'Rivers', linked: 'CMP-046', resp: 'Ngozi Eze', deadline: '05 Mar 2026', status: 'Verified', ev: 'Rivers_Advance_Summary.pdf' }
  ],

  pdpObjs: [
    { id: 'OBJ-101', staff: 'Fatima Bello', title: 'Achieve 100% adherence to clinical audit checklists', weight: 30, quarter: 'Q1 2026', approved: true, ev: 'Checklist_Signoff_Q1.pdf', score: 28 },
    { id: 'OBJ-102', staff: 'Fatima Bello', title: 'Submit 100% of flight vouchers with boarding passes within 48 hrs', weight: 35, quarter: 'Q1 2026', approved: true, ev: 'Travel_Liquidation.pdf', score: 32 },
    { id: 'OBJ-103', staff: 'Fatima Bello', title: 'Conduct 4 community cluster safeguarding inductions', weight: 35, quarter: 'Q2 2026', approved: false, ev: null, score: 0 }
  ],

  innovations: [
    { id: 'INN-01', staff: 'Fatima Bello', title: 'SMS OTP Verification for Patient Outreach Attendance', desc: 'Prevents phantom patient attendance and improves donor auditability.', date: '18 Feb 2026', score: 7.0, feedback: 'Approved for cluster pilot.' },
    { id: 'INN-02', staff: 'Emeka Nwosu', title: 'QR Code Dual-Authorization for Field Petty Cash', desc: 'Stops single-signatory field cash disbursements.', date: '22 Feb 2026', score: null, feedback: null }
  ],

  trainings: [
    { id: 'TR-01', title: 'Anti-Fraud & FCPA Compliance Awareness', aud: 'All Staff', done: 410, total: 490, deadline: '31 Mar 2026', status: 'Active' },
    { id: 'TR-02', title: 'Safeguarding & PSEA Standards', aud: 'All Staff', done: 460, total: 490, deadline: '15 Mar 2026', status: 'Active' },
    { id: 'TR-03', title: 'USAID 2 CFR 200 Cost Principles & Procurement Rules', aud: 'Finance & Procurement', done: 85, total: 95, deadline: '30 Apr 2026', status: 'Active' },
    { id: 'TR-04', title: 'Data Privacy & HIPAA Security', aud: 'All Staff', done: 320, total: 490, deadline: '30 Apr 2026', status: 'Behind' }
  ],

  states: [
    { name: 'Lagos State', clusters: 'Clusters A, B, C', staff: 120, score: 92, status: 'Compliant' },
    { name: 'Rivers State', clusters: 'Clusters A, B', staff: 85, score: 88, status: 'Compliant' },
    { name: 'Abuja FCT', clusters: 'HQ & Cluster A', staff: 110, score: 85, status: 'Compliant' },
    { name: 'Kano State', clusters: 'Clusters A & B', staff: 75, score: 68, status: 'Minor Gaps' },
    { name: 'Kaduna State', clusters: 'Clusters A & B', staff: 55, score: 62, status: 'Minor Gaps' },
    { name: 'Borno State', clusters: 'Cluster A', staff: 45, score: 54, status: 'Critical Risk' }
  ],

  fieldUpdates: [
    { date: '27 Feb 2026', state: 'Rivers State · Cluster A, B', by: 'Ngozi Eze (STL)', status: 'Compliant', challenges: 'Fuel price volatility in coastal LGAs.', mitigations: 'Centralized bulk procurement established.' },
    { date: '24 Feb 2026', state: 'Kano State · Cluster A & B', by: 'Fatima Bello', status: 'Minor Gaps', challenges: 'Vendor delivery delay for lab consumables.', mitigations: 'Escalated under CAP-032.' }
  ],

  risks: [
    { id: 'RSK-101', cat: 'Financial', desc: 'Direct sourcing of field goods bypassing 3 quotes', like: 4, impact: 4, rating: 'Critical', owner: 'Biodun Ojo', status: 'Mitigating' },
    { id: 'RSK-102', cat: 'Safeguarding', desc: 'Under-reporting of field PSEA incidents', like: 3, impact: 5, rating: 'Critical', owner: 'Amina Yusuf', status: 'Mitigating' },
    { id: 'RSK-103', cat: 'Data', desc: 'Patient records stored on unencrypted laptops', like: 3, impact: 4, rating: 'High', owner: 'Chidinma Okoro', status: 'Active' },
    { id: 'RSK-104', cat: 'Governance', desc: 'Donor quarterly financial reporting delay', like: 2, impact: 4, rating: 'Medium', owner: 'Dr. Kabir Alabi', status: 'Mitigating' },
    { id: 'RSK-105', cat: 'Programme', desc: 'Stockout of rapid test kits due to logistics', like: 2, impact: 3, rating: 'Low', owner: 'Emeka Nwosu', status: 'Resolved' }
  ],

  policies: [
    { id: 'POL-01', title: 'Anti-Corruption & Whistleblower Protection', cat: 'Ethics', ver: 'v3.2', lastRev: 'Jan 2026', nextRev: 'Jan 2027', rate: 94, myAck: true },
    { id: 'POL-02', title: 'Procurement & Sub-Award Standard', cat: 'Finance', ver: 'v2.1', lastRev: 'Dec 2025', nextRev: 'Dec 2026', rate: 88, myAck: true },
    { id: 'POL-03', title: 'PSEA & Child Safeguarding Policy', cat: 'Safeguarding', ver: 'v4.0', lastRev: 'Nov 2025', nextRev: 'Nov 2026', rate: 98, myAck: true },
    { id: 'POL-04', title: 'Data Privacy & HIPAA Security Standard', cat: 'Data', ver: 'v1.5', lastRev: 'Feb 2026', nextRev: 'Feb 2027', rate: 72, myAck: false },
    { id: 'POL-05', title: 'Flight Ticket & Boarding Pass Utilization Directive', cat: 'Finance', ver: 'v1.0', lastRev: 'Jan 2026', nextRev: 'Jan 2027', rate: 64, myAck: false }
  ],

  lessons: [
    { id: 'LL-01', cat: 'Procurement', ref: 'CMP-048', text: 'Dual-authorisation from HOD & Compliance required for purchases > ₦100,000.', prio: 'High', date: 'Feb 2026' },
    { id: 'LL-02', cat: 'Safeguarding', ref: 'CMP-047', text: 'Anonymous reporting dropboxes must be translated into Hausa, Yoruba, and Pidgin.', prio: 'High', date: 'Jan 2026' },
    { id: 'LL-03', cat: 'Finance', ref: 'CAP-029', text: 'Boarding passes must be uploaded within 48 hrs to unlock vendor invoice clearance.', prio: 'Medium', date: 'Jan 2026' }
  ],

  investigations: [
    { id: 'INV-2026-01', title: 'Alleged duplicate travel allowance claims in Kaduna cluster', state: 'Kaduna State', lead: 'Amina Yusuf', scope: 'Dec 2025 - Jan 2026 transport advances', date: '12 Feb 2026', status: 'Active' },
    { id: 'INV-2026-02', title: 'Vendor bid collusion review for medical supplies', state: 'Kano State', lead: 'Dr. Kabir Alabi', scope: 'Q4 2025 Lab Tenders', date: '02 Feb 2026', status: 'Report Drafted' }
  ],

  travel: [
    { id: 'TKT-1042', name: 'Dr. Ngozi Eze', route: 'ABV - PHC - ABV', date: '20 Feb 2026', code: 'USAID-CCCRN-01', status: 'Utilized', bp: 'PHC_BoardingPass_Verified.pdf', pay: 'Cleared' },
    { id: 'TKT-1043', name: 'Fatima Bello, Emeka Nwosu', route: 'ABV - KAN - ABV', date: '26 Feb 2026', code: 'USAID-CCCRN-02', status: 'Booked', bp: null, pay: 'Pending Boarding Pass' },
    { id: 'TKT-1044', name: 'Dr. Biodun Ojo', route: 'LOS - ABV - LOS', date: '04 Mar 2026', code: 'GF-CCCRN-04', status: 'Booked', bp: null, pay: 'Pending Boarding Pass' }
  ]
};

// Unified API Service
const Api = {
  // Generic HTTP Request Handler
  async request(endpoint, options = {}) {
    if (ApiConfig.USE_MOCK) {
      // Simulate slight network latency
      await new Promise(r => setTimeout(r, 60));
      return null;
    }
    const res = await fetch(`${ApiConfig.BASE_URL}${endpoint}`, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      ...options
    });
    if (!res.ok) {
      const err = await res.json().catch(() => ({ message: 'Server error' }));
      throw new Error(err.message || `Request failed with status ${res.status}`);
    }
    return res.json();
  },

  // 1. COMPLAINTS API
  Complaints: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.complaints];
      return Api.request('/complaints.php');
    },
    async create(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'CMP-0' + (MockDatabase.complaints.length + 43);
        const item = { id: newId, ...data, date: '01 Mar 2026', status: 'Open' };
        MockDatabase.complaints.unshift(item);
        return { success: true, item };
      }
      return Api.request('/complaints.php', { method: 'POST', body: JSON.stringify(data) });
    },
    async convertToCap(cmpId) {
      if (ApiConfig.USE_MOCK) {
        const c = MockDatabase.complaints.find(x => x.id === cmpId);
        if (c) c.status = 'Converted to CAP';
        const newCapId = 'CAP-0' + (MockDatabase.caps.length + 30);
        const newCap = { id: newCapId, issue: c.desc, state: c.state, linked: c.id, resp: 'Responsible Lead', deadline: '31 Mar 2026', status: 'Open', ev: null };
        MockDatabase.caps.unshift(newCap);
        return { success: true, cap: newCap };
      }
      return Api.request('/complaints.php?action=convert_to_cap', { method: 'POST', body: JSON.stringify({ cmpId }) });
    },
    async convertToInvestigation(cmpId) {
      if (ApiConfig.USE_MOCK) {
        const c = MockDatabase.complaints.find(x => x.id === cmpId);
        if (c) c.status = 'Converted to Investigation';
        const newInvId = `INV-2026-0${MockDatabase.investigations.length + 1}`;
        const newInv = { id: newInvId, title: `Forensic Review: ${c.desc}`, state: c.state, lead: 'Amina Yusuf', scope: `Originating from ${c.id}`, date: '01 Mar 2026', status: 'Active' };
        MockDatabase.investigations.unshift(newInv);
        return { success: true, investigation: newInv };
      }
      return Api.request('/complaints.php?action=convert_to_inv', { method: 'POST', body: JSON.stringify({ cmpId }) });
    },
    async delete(cmpId) {
      if (ApiConfig.USE_MOCK) {
        MockDatabase.complaints = MockDatabase.complaints.filter(x => x.id !== cmpId);
        return { success: true };
      }
      return Api.request(`/complaints.php?id=${cmpId}`, { method: 'DELETE' });
    }
  },

  // 2. CAP API
  Caps: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.caps];
      return Api.request('/cap.php');
    },
    async create(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'CAP-0' + (MockDatabase.caps.length + 30);
        const item = { id: newId, ...data, status: 'Open', ev: null };
        MockDatabase.caps.unshift(item);
        return { success: true, item };
      }
      return Api.request('/cap.php', { method: 'POST', body: JSON.stringify(data) });
    },
    async submitEvidence(capId, fileRef, notes) {
      if (ApiConfig.USE_MOCK) {
        const c = MockDatabase.caps.find(x => x.id === capId);
        if (c) {
          c.ev = fileRef;
          c.status = 'Evidence Submitted';
        }
        return { success: true };
      }
      return Api.request('/cap.php?action=submit_evidence', { method: 'POST', body: JSON.stringify({ capId, fileRef, notes }) });
    },
    async close(capId) {
      if (ApiConfig.USE_MOCK) {
        const c = MockDatabase.caps.find(x => x.id === capId);
        if (c) c.status = 'Verified';
        return { success: true };
      }
      return Api.request(`/cap.php?action=close&id=${capId}`, { method: 'POST' });
    }
  },

  // 3. PDP API
  Pdp: {
    async getMyObjectives(staffName) {
      if (ApiConfig.USE_MOCK) return MockDatabase.pdpObjs.filter(o => o.staff === staffName);
      return Api.request(`/pdp.php?action=objectives&staff=${encodeURIComponent(staffName)}`);
    },
    async addObjective(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'OBJ-' + (MockDatabase.pdpObjs.length + 101);
        const item = { id: newId, ...data, approved: false, ev: null, score: 0 };
        MockDatabase.pdpObjs.push(item);
        return { success: true, item };
      }
      return Api.request('/pdp.php?action=add_objective', { method: 'POST', body: JSON.stringify(data) });
    },
    async submitInnovation(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'INN-0' + (MockDatabase.innovations.length + 1);
        const item = { id: newId, ...data, date: '01 Mar 2026', score: null, feedback: null };
        MockDatabase.innovations.push(item);
        return { success: true, item };
      }
      return Api.request('/pdp.php?action=submit_innovation', { method: 'POST', body: JSON.stringify(data) });
    },
    async getInnovations() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.innovations];
      return Api.request('/pdp.php?action=innovations');
    },
    async gradeInnovation(innId, score, feedback) {
      if (ApiConfig.USE_MOCK) {
        const i = MockDatabase.innovations.find(x => x.id === innId);
        if (i) { i.score = score; i.feedback = feedback; }
        return { success: true };
      }
      return Api.request('/pdp.php?action=grade_innovation', { method: 'POST', body: JSON.stringify({ innId, score, feedback }) });
    }
  },

  // 4. TRAVEL & TICKETS API
  Travel: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.travel];
      return Api.request('/travel.php');
    },
    async requestFlight(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'TKT-' + (MockDatabase.travel.length + 1043);
        const item = { id: newId, ...data, status: 'Booked', bp: null, pay: 'Pending Boarding Pass' };
        MockDatabase.travel.unshift(item);
        return { success: true, item };
      }
      return Api.request('/travel.php', { method: 'POST', body: JSON.stringify(data) });
    },
    async uploadBoardingPass(tktId, fileRef) {
      if (ApiConfig.USE_MOCK) {
        const t = MockDatabase.travel.find(x => x.id === tktId);
        if (t) {
          t.bp = fileRef;
          t.status = 'Utilized';
          t.pay = 'Ready for Vendor Clearance';
        }
        return { success: true };
      }
      return Api.request('/travel.php?action=upload_bp', { method: 'POST', body: JSON.stringify({ tktId, fileRef }) });
    },
    async clearPayment(tktId) {
      if (ApiConfig.USE_MOCK) {
        const t = MockDatabase.travel.find(x => x.id === tktId);
        if (t) t.pay = 'Cleared';
        return { success: true };
      }
      return Api.request(`/travel.php?action=clear_payment&id=${tktId}`, { method: 'POST' });
    }
  },

  // 5. TRAINING API
  Trainings: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.trainings];
      return Api.request('/training.php');
    },
    async create(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'TR-0' + (MockDatabase.trainings.length + 1);
        const item = { id: newId, ...data, done: 0, total: 490, status: 'Active' };
        MockDatabase.trainings.push(item);
        return { success: true, item };
      }
      return Api.request('/training.php', { method: 'POST', body: JSON.stringify(data) });
    }
  },

  // 6. STATES & CLUSTERS API
  States: {
    async getStates() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.states];
      return Api.request('/states.php');
    },
    async getFieldUpdates() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.fieldUpdates];
      return Api.request('/states.php?action=updates');
    },
    async submitFieldUpdate(data) {
      if (ApiConfig.USE_MOCK) {
        const item = { date: '01 Mar 2026', ...data };
        MockDatabase.fieldUpdates.unshift(item);
        return { success: true, item };
      }
      return Api.request('/states.php?action=submit_update', { method: 'POST', body: JSON.stringify(data) });
    }
  },

  // 7. RISKS API
  Risks: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.risks];
      return Api.request('/risks.php');
    },
    async create(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'RSK-' + (MockDatabase.risks.length + 101);
        const item = { id: newId, ...data, status: 'Active' };
        MockDatabase.risks.unshift(item);
        return { success: true, item };
      }
      return Api.request('/risks.php', { method: 'POST', body: JSON.stringify(data) });
    },
    async delete(id) {
      if (ApiConfig.USE_MOCK) {
        MockDatabase.risks = MockDatabase.risks.filter(x => x.id !== id);
        return { success: true };
      }
      return Api.request(`/risks.php?id=${id}`, { method: 'DELETE' });
    }
  },

  // 8. POLICIES API
  Policies: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.policies];
      return Api.request('/policies.php');
    },
    async create(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'POL-0' + (MockDatabase.policies.length + 1);
        const item = { id: newId, ...data, lastRev: 'Mar 2026', nextRev: 'Mar 2027', rate: 0, myAck: false };
        MockDatabase.policies.push(item);
        return { success: true, item };
      }
      return Api.request('/policies.php', { method: 'POST', body: JSON.stringify(data) });
    },
    async sign(id) {
      if (ApiConfig.USE_MOCK) {
        const p = MockDatabase.policies.find(x => x.id === id);
        if (p) { p.myAck = true; p.rate = Math.min(100, p.rate + 1); }
        return { success: true };
      }
      return Api.request(`/policies.php?action=sign&id=${id}`, { method: 'POST' });
    }
  },

  // 9. LESSONS LEARNED API
  Lessons: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.lessons];
      return Api.request('/lessons.php');
    },
    async create(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = 'LL-0' + (MockDatabase.lessons.length + 1);
        const item = { id: newId, ...data, prio: 'High', date: 'Mar 2026' };
        MockDatabase.lessons.unshift(item);
        return { success: true, item };
      }
      return Api.request('/lessons.php', { method: 'POST', body: JSON.stringify(data) });
    },
    async delete(id) {
      if (ApiConfig.USE_MOCK) {
        MockDatabase.lessons = MockDatabase.lessons.filter(x => x.id !== id);
        return { success: true };
      }
      return Api.request(`/lessons.php?id=${id}`, { method: 'DELETE' });
    }
  },

  // 10. INVESTIGATIONS API
  Investigations: {
    async getAll() {
      if (ApiConfig.USE_MOCK) return [...MockDatabase.investigations];
      return Api.request('/investigations.php');
    },
    async create(data) {
      if (ApiConfig.USE_MOCK) {
        const newId = `INV-2026-0${MockDatabase.investigations.length + 1}`;
        const item = { id: newId, ...data, date: '01 Mar 2026', status: 'Active' };
        MockDatabase.investigations.unshift(item);
        return { success: true, item };
      }
      return Api.request('/investigations.php', { method: 'POST', body: JSON.stringify(data) });
    }
  }
};
