/**
 * CCCRN ComplianceIQ — Real-Time Central Data Store
 * Reactive shared persistence ensuring all role portals stay synchronized.
 */

const SEED_DATA = {
  complaints: [
    { id: 'CMP-048', cat: 'Procurement', state: 'Kano', by: 'Anonymous', date: '28 Feb 2026', desc: 'Direct sourcing of generator diesel without 3 comparative vendor quotes.', status: 'Open', priority: 'High' },
    { id: 'CMP-047', cat: 'Safeguarding', state: 'Lagos', by: 'Staff Member', date: '25 Feb 2026', desc: 'Allegation of verbal harassment during quarterly cluster review meeting.', status: 'In Progress', priority: 'Critical' },
    { id: 'CMP-046', cat: 'Finance', state: 'Rivers', by: 'Staff Supervisor', date: '22 Feb 2026', desc: 'Delay in per diem advance retirement for clinical outreaches.', status: 'Converted to CAP', priority: 'Medium' },
    { id: 'CMP-045', cat: 'HR', state: 'Abuja FCT', by: 'Staff Member', date: '19 Feb 2026', desc: 'Performance appraisal grading inconsistency and missing supervisor signoff.', status: 'Closed', priority: 'Low' },
    { id: 'CMP-042', cat: 'Data', state: 'Borno', by: 'Staff Member', date: '04 Feb 2026', desc: 'Unencrypted patient intake records shared over open WhatsApp channel.', status: 'Converted to CAP', priority: 'High' }
  ],
  caps: [
    { id: 'CAP-032', issue: 'Single-quotation fuel supply breach in Kano cluster', state: 'Kano', linked: 'CMP-048', resp: 'Head of Department', deadline: '2026-03-15', status: 'Open', ev: null, evNotes: null },
    { id: 'CAP-031', issue: 'Unsecured patient registry files protocol implementation', state: 'Borno', linked: 'CMP-042', resp: 'Staff Member', deadline: '2026-03-10', status: 'Evidence Submitted', ev: 'Borno_HIPAA_Encrypted_Proof.pdf', evNotes: 'All clinical laptops configured with BitLocker encryption.' },
    { id: 'CAP-030', issue: 'Procurement dual-signoff bypass for medical consumables', state: 'Rivers', linked: 'CMP-044', resp: 'Staff Supervisor', deadline: '2026-03-20', status: 'In Progress', ev: null, evNotes: null },
    { id: 'CAP-029', issue: 'Advance liquidation reconciliation and donor receipt audit', state: 'Rivers', linked: 'CMP-046', resp: 'State Team Lead', deadline: '2026-03-05', status: 'Verified', ev: 'Rivers_Advance_Liquidation_Signoff.pdf', evNotes: 'Full audit complete and cleared by compliance specialist.' }
  ],
  pdpObjectives: [
    { id: 'OBJ-101', staff: 'Staff Member', title: 'Achieve 100% adherence to clinical audit checklists and patient documentation', weight: 30, quarter: 'Q1 2026', approved: true, ev: 'Checklist_Audit_Q1_Proof.pdf', score: 28 },
    { id: 'OBJ-102', staff: 'Staff Member', title: 'Submit 100% of flight vouchers with boarding passes within 48 hours of travel', weight: 35, quarter: 'Q1 2026', approved: true, ev: 'Flight_Liquidation_Q1.pdf', score: 32 },
    { id: 'OBJ-103', staff: 'Staff Member', title: 'Conduct 4 community cluster safeguarding and PSEA inductions', weight: 35, quarter: 'Q2 2026', approved: false, ev: null, score: 0 }
  ],
  innovations: [
    { id: 'INN-01', staff: 'Staff Member', title: 'SMS OTP Verification for Patient Outreach Attendance', desc: 'Prevents phantom patient attendance records and guarantees donor audit transparency.', date: '18 Feb 2026', score: 42.5, maxScore: 50, feedback: 'Commendable initiative. Approved for deployment across Northern clusters.' },
    { id: 'INN-02', staff: 'Staff Supervisor', title: 'QR Code Dual-Authorization for Field Petty Cash Disbursements', desc: 'Eliminates single-signatory field cash disbursements and flags discrepancies in real-time.', date: '22 Feb 2026', score: 45.0, maxScore: 50, feedback: 'Validated by Finance. Ready for organization rollout.' }
  ],
  travel: [
    { id: 'TKT-1042', traveler: 'State Team Lead', route: 'ABV - PHC - ABV', travelDate: '20 Feb 2026', budgetCode: 'USAID-CCCRN-CLIN-01', status: 'Utilized', boardingPass: 'PHC_BoardingPass_Verified.pdf', paymentStatus: 'Cleared' },
    { id: 'TKT-1043', traveler: 'Staff Member', route: 'ABV - KAN - ABV', travelDate: '26 Feb 2026', budgetCode: 'USAID-CCCRN-CLIN-02', status: 'Booked', boardingPass: null, paymentStatus: 'Pending Boarding Pass' },
    { id: 'TKT-1044', traveler: 'Head of Department', route: 'LOS - ABV - LOS', travelDate: '04 Mar 2026', budgetCode: 'GF-CCCRN-GOV-04', status: 'Booked', boardingPass: null, paymentStatus: 'Pending Boarding Pass' }
  ],
  fieldUpdates: [
    { date: '27 Feb 2026', state: 'Rivers State · Clusters A & B', reportedBy: 'State Team Lead (STL)', status: 'Compliant', challenges: 'Fuel price volatility affecting coastal LGA outreaches.', mitigations: 'Centralized bulk procurement established with vetted vendor.' },
    { date: '24 Feb 2026', state: 'Kano State · Clusters A & B', reportedBy: 'Staff Member', status: 'Minor Gaps', challenges: 'Laboratory consumable delivery delayed by logistics vendor.', mitigations: 'Escalated under CAP-032 with alternative buffer stock.' }
  ],
  risks: [
    { id: 'RSK-101', cat: 'Financial', desc: 'Direct sourcing of field commodities bypassing 3 quotes', likelihood: 4, impact: 4, rating: 'Critical', owner: 'Head of Department', status: 'Mitigating' },
    { id: 'RSK-102', cat: 'Safeguarding', desc: 'Under-reporting of field PSEA community incidents', likelihood: 3, impact: 5, rating: 'Critical', owner: 'Compliance Officer', status: 'Mitigating' },
    { id: 'RSK-103', cat: 'Data', desc: 'Patient clinical records stored on unencrypted field laptops', likelihood: 3, impact: 4, rating: 'High', owner: 'HR Lead', status: 'Active' },
    { id: 'RSK-104', cat: 'Governance', desc: 'Donor quarterly financial liquidation reporting timeline pressure', likelihood: 2, impact: 4, rating: 'Medium', owner: 'Compliance Director', status: 'Mitigating' }
  ]
};

const RealTimeStore = {
  get(key) {
    const raw = localStorage.getItem('CCCRN_STORE_' + key);
    if (!raw) {
      if (SEED_DATA[key]) {
        localStorage.setItem('CCCRN_STORE_' + key, JSON.stringify(SEED_DATA[key]));
        return SEED_DATA[key];
      }
      return [];
    }
    return JSON.parse(raw);
  },
  set(key, data) {
    localStorage.setItem('CCCRN_STORE_' + key, JSON.stringify(data));
  },
  addComplaint(item) {
    const list = this.get('complaints');
    list.unshift(item);
    this.set('complaints', list);
    return list;
  },
  addCap(item) {
    const list = this.get('caps');
    list.unshift(item);
    this.set('caps', list);
    return list;
  },
  uploadCapEvidence(capId, filename, notes) {
    const list = this.get('caps');
    const cap = list.find(c => c.id === capId);
    if (cap) {
      cap.ev = filename;
      cap.evNotes = notes;
      cap.status = 'Evidence Submitted';
      this.set('caps', list);
    }
    return cap;
  },
  closeCap(capId) {
    const list = this.get('caps');
    const cap = list.find(c => c.id === capId);
    if (cap) {
      cap.status = 'Verified';
      this.set('caps', list);
    }
    return cap;
  },
  uploadBoardingPass(ticketId, filename) {
    const list = this.get('travel');
    const ticket = list.find(t => t.id === ticketId);
    if (ticket) {
      ticket.boardingPass = filename;
      ticket.status = 'Utilized';
      ticket.paymentStatus = 'Ready for Vendor Clearance';
      this.set('travel', list);
    }
    return ticket;
  },
  clearTravelPayment(ticketId) {
    const list = this.get('travel');
    const ticket = list.find(t => t.id === ticketId);
    if (ticket) {
      ticket.paymentStatus = 'Cleared';
      this.set('travel', list);
    }
    return ticket;
  }
};
