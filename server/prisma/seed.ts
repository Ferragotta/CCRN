import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function main() {
  console.log('🌱 Seeding CCCRN Compliance Database...');

  // 1. Seed Users
  const defaultPassword = await bcrypt.hash('Compliance2026!', 10);

  const users = [
    { email: 'director@cccrn.org', name: 'Director of Compliance', roleKey: 'doc', roleBadge: 'ADMIN (DoC)', avatar: 'DC', department: 'Compliance Directorate' },
    { email: 'compliance@cccrn.org', name: 'Compliance Officer', roleKey: 'compliance_officer', roleBadge: 'COMPLIANCE SPECIALIST', avatar: 'CO', department: 'Compliance Unit' },
    { email: 'hr@cccrn.org', name: 'HR Lead', roleKey: 'hr', roleBadge: 'HR ACCESS', avatar: 'HR', department: 'Human Resources' },
    { email: 'staff@cccrn.org', name: 'Dr. Biodun Ojo', roleKey: 'staff', roleBadge: 'STAFF ACCESS', avatar: 'ST', department: 'Programme Delivery', state: 'Lagos' }
  ];

  for (const u of users) {
    await prisma.user.upsert({
      where: { email: u.email },
      update: {},
      create: {
        ...u,
        passwordHash: defaultPassword
      }
    });
  }
  console.log('✓ Users seeded');

  // 2. Seed State Profiles
  const states = [
    { id: 'Lagos', name: 'Lagos State', clusterLead: 'Dr. Folake Davies', facilities: 48, activeCaps: 3, riskRating: 'Low', compliance: 92 },
    { id: 'Kano', name: 'Kano State', clusterLead: 'Ibrahim Aliyu', facilities: 62, activeCaps: 6, riskRating: 'Medium', compliance: 68 },
    { id: 'Rivers', name: 'Rivers State', clusterLead: 'Dr. Tariye Briggs', facilities: 35, activeCaps: 2, riskRating: 'Low', compliance: 94 },
    { id: 'Abuja FCT', name: 'Abuja FCT', clusterLead: 'Ngozi Okonkwo', facilities: 28, activeCaps: 4, riskRating: 'Medium', compliance: 78 },
    { id: 'Kaduna', name: 'Kaduna State', clusterLead: 'Balarabe Yusuf', facilities: 51, activeCaps: 7, riskRating: 'High', compliance: 62 },
    { id: 'Borno', name: 'Borno State', clusterLead: 'Dr. Bukar Goni', facilities: 40, activeCaps: 8, riskRating: 'Critical', compliance: 55 }
  ];

  for (const s of states) {
    await prisma.stateProfile.upsert({
      where: { id: s.id },
      update: {},
      create: s
    });
  }
  console.log('✓ State profiles seeded');

  // 3. Seed Complaints
  const complaints = [
    { id: 'CMP-048', date: '28 Feb 2026', state: 'Kano — Cluster B', category: 'Fraud', severity: 'Critical', source: 'Whistleblower', allegedParty: 'Finance Assistant', status: 'Open', summary: 'Emergency procurement bypass and cash voucher irregularities.' },
    { id: 'CMP-047', date: '25 Feb 2026', state: 'Lagos — Cluster A', category: 'Misconduct', severity: 'High', source: 'Staff', allegedParty: 'Field Officer', status: 'In Progress', summary: 'Safeguarding and PSEA compliance violation during outreach.' },
    { id: 'CMP-046', date: '22 Feb 2026', state: 'Rivers — Cluster C', category: 'Policy Breach', severity: 'Medium', source: 'Audit', allegedParty: 'Procurement Committee', status: 'Converted to CAP', summary: 'Three-quote bidding exception documentation missing.' },
    { id: 'CMP-045', date: '19 Feb 2026', state: 'Abuja FCT', category: 'Safety Violation', severity: 'Low', source: 'Direct', allegedParty: 'Facility Supervisor', status: 'Closed', summary: 'Clinic hazardous waste disposal compliance rectified.' },
    { id: 'CMP-044', date: '15 Feb 2026', state: 'Kaduna', category: 'Financial Irregularity', severity: 'High', source: 'Field Audit', allegedParty: 'State Coordinator', status: 'In Progress', summary: 'Unretired community outreach advances backlog.' },
    { id: 'CMP-043', date: '10 Feb 2026', state: 'Borno', category: 'Harassment/PSEA', severity: 'Critical', source: 'Whistleblower', allegedParty: 'Anonymous Staff', status: 'Closed', summary: 'Confidential grievance investigation concluded and remediated.' }
  ];

  for (const c of complaints) {
    await prisma.complaint.upsert({
      where: { id: c.id },
      update: {},
      create: {
        ...c,
        loggedByEmail: 'staff@cccrn.org'
      }
    });
  }
  console.log('✓ Complaints seeded');

  // 4. Seed CAPs
  const caps = [
    { id: 'CAP-032', issue: 'Procurement single-signatory threshold bypass during emergency outreach testing', state: 'Kano', linkedRef: 'CMP-048', deadline: '15 Mar 2026', responsible: 'State Team Lead & Finance Officer', status: 'Open', priority: 'Critical', rootCause: 'Field signatories unavailable during weekend community testing.' },
    { id: 'CAP-031', issue: 'PSEA mandatory training compliance rate below 75% for community volunteer nurses', state: 'Kaduna', linkedRef: 'CMP-047', deadline: '10 Mar 2026', responsible: 'HR Training Lead & Clinic Supervisor', status: 'Pending Review', priority: 'High', rootCause: 'High volunteer turnover in remote testing locations.' },
    { id: 'CAP-030', issue: 'Asset register discrepancies in clinical testing lab equipment serial records', state: 'Rivers', linkedRef: 'CMP-046', deadline: '28 Feb 2026', responsible: 'Logistics Officer', status: 'Closed', priority: 'Low', rootCause: 'Serial numbers misrecorded during physical clinic relocation.' }
  ];

  for (const cap of caps) {
    await prisma.cAP.upsert({
      where: { id: cap.id },
      update: {},
      create: cap
    });
  }
  console.log('✓ CAPs seeded');

  // 5. Seed Risk Items (ISO 31000)
  const risks = [
    { id: 'RSK-019', category: 'Financial & Grant', description: 'Forex commodity price volatility on imported medical reagents and viral load consumables', likelihood: 5, impact: 4, rating: 20, level: 'Critical', mitigation: 'Maintain dual-currency escrow buffer; negotiate forward-rate price lock contracts.', owner: 'Director of Finance', status: 'Active' },
    { id: 'RSK-018', category: 'Procurement', description: 'Single-signatory authorization vulnerability in remote mobile testing clusters during network outages', likelihood: 4, impact: 4, rating: 16, level: 'High', mitigation: 'Deploy offline digital mobile tokens with mandatory 24-hour sync verification.', owner: 'Lead Compliance Specialist', status: 'Active' },
    { id: 'RSK-017', category: 'Safeguarding & HR', description: 'Ad-hoc community volunteers deployed without central HR background verification and PSEA sign-off', likelihood: 4, impact: 3, rating: 12, level: 'High', mitigation: 'Enforce mandatory central electronic QR clearance badges prior to facility site access.', owner: 'Human Resources Lead', status: 'Active' },
    { id: 'RSK-016', category: 'Information Security', description: 'Paper patient clinic registers stored without lock-box physical custody during facility renovations', likelihood: 2, impact: 3, rating: 6, level: 'Medium', mitigation: 'Issue biometric temporary lock-boxes before facility contractor mobilization.', owner: 'State Logistics Officer', status: 'Mitigated' }
  ];

  for (const r of risks) {
    await prisma.riskItem.upsert({
      where: { id: r.id },
      update: {},
      create: r
    });
  }
  console.log('✓ Risk items seeded');

  // 6. Seed Policies
  const policies = [
    { id: 'POL-001', title: 'Institutional Code of Conduct, Ethics & Whistleblower Policy', category: 'Governance & Ethics', version: 'v3.0', lastReviewed: '15 Jan 2026', nextReview: '15 Jan 2027', status: 'Active / Published', summary: 'Governs professional integrity, conflict of interest declarations, gift policies, and confidential whistleblower protection channels.', fileName: 'CCCRN_Code_of_Conduct_2026.pdf' },
    { id: 'POL-002', title: 'Protection from Sexual Exploitation, Abuse & Harassment (PSEA)', category: 'Safeguarding', version: 'v2.2', lastReviewed: '10 Feb 2026', nextReview: '10 Feb 2027', status: 'Active / Published', summary: 'Zero tolerance policy for sexual misconduct, beneficiary exploitation, and child safeguarding standards in clinical facilities.', fileName: 'CCCRN_PSEA_Policy_Updated.pdf' },
    { id: 'POL-003', title: '2 CFR 200 & Federal Grant Procurement Standard Operating Procedures', category: 'Grant Compliance', version: 'v4.1', lastReviewed: '20 Jan 2026', nextReview: '20 Jan 2027', status: 'Active / Published', summary: 'Establishes mandatory thresholds for dual-signatory authorization, 3-quote competitive bidding, and sole-source justifications.', fileName: 'CCCRN_Procurement_SOP_v4.pdf' },
    { id: 'POL-004', title: 'Data Protection, Client Privacy & Electronic Records Security Policy', category: 'Information Security', version: 'v1.4', lastReviewed: '05 Feb 2026', nextReview: '05 Feb 2027', status: 'Active / Published', summary: 'Enforces NDPR and HIPAA compliance for physical clinic registers, EMR server encryption, and biometric access controls.', fileName: 'CCCRN_Data_Protection_Policy.pdf' }
  ];

  for (const p of policies) {
    await prisma.policy.upsert({
      where: { id: p.id },
      update: {},
      create: p
    });
  }
  console.log('✓ Policies seeded');

  // 7. Seed Travel Requests & Tickets
  const travelReq = await prisma.travelRequest.upsert({
    where: { id: 'TR-107' },
    update: {},
    create: {
      id: 'TR-107',
      travellerName: 'Chidinma Okoro (Supervisor)',
      department: 'Finance',
      route: 'Abuja (ABV) → Lagos (LOS) → Abuja',
      travelDate: '04 Mar 2026',
      returnDate: '07 Mar 2026',
      purpose: 'Quarterly financial reconciliation and advance ledger verification.',
      estimatedCost: 180000,
      donorCode: 'GF-MAL-2026-004',
      status: 'Ticket Issued',
      requestDate: '27 Feb 2026',
      authDocName: 'Signed_Travel_Form_Chidinma.pdf',
      userEmail: 'staff@cccrn.org'
    }
  });

  const ticket = await prisma.ticketPurchase.upsert({
    where: { reqRef: 'TR-107' },
    update: {},
    create: {
      id: 'TKT-554',
      reqRef: 'TR-107',
      travellerName: 'Chidinma Okoro',
      route: 'Abuja → Lagos → Abuja',
      airline: 'Air Peace',
      vendorName: 'Wakanow Corporate Travel',
      ticketNumber: '074-2983749281',
      amount: 185000,
      purchaseDate: '28 Feb 2026',
      travelDate: '04 Mar 2026',
      returnDate: '07 Mar 2026',
      status: 'Issued / Open',
      boardingPassUploaded: false,
      paymentStatus: 'Unpaid'
    }
  });

  await prisma.vendorPayment.upsert({
    where: { ticketId: 'TKT-554' },
    update: {},
    create: {
      id: 'PAY-091',
      vendorName: 'Wakanow Corporate Travel',
      invoiceRef: 'INV-WAK-2026-088',
      ticketId: 'TKT-554',
      tickets: 'TKT-554 (Chidinma Okoro)',
      amount: 185000,
      dueDate: '10 Mar 2026',
      daysOverdue: 0,
      boardingPassVerified: false,
      status: 'Pending Boarding Pass'
    }
  });
  console.log('✓ Travel, tickets & vendor payments seeded');

    // 7. Seed Training Modules
  const trainingModules = [
    {
      id: 'TRN-001',
      title: 'Code of Conduct & Ethics (POL-001)',
      category: 'Mandatory Governance',
      duration: '45 mins',
      instructor: 'Director of Compliance',
      deadline: '31 Mar 2026',
      mandatory: true,
      totalStaff: 490,
      completed: 312,
      status: 'Active'
    },
    {
      id: 'TRN-002',
      title: '2 CFR 200 Statutory Grant Compliance',
      category: 'Finance & Procurement',
      duration: '60 mins',
      instructor: 'Lead Compliance Specialist',
      deadline: '15 Apr 2026',
      mandatory: true,
      totalStaff: 180,
      completed: 145,
      status: 'Active'
    },
    {
      id: 'TRN-003',
      title: 'PSEA & Beneficiary Safeguarding (POL-002)',
      category: 'Safeguarding',
      duration: '40 mins',
      instructor: 'HR & Safeguarding Lead',
      deadline: '30 Apr 2026',
      mandatory: true,
      totalStaff: 490,
      completed: 405,
      status: 'Active'
    }
  ];

  for (const tm of trainingModules) {
    await prisma.trainingModule.upsert({
      where: { id: tm.id },
      update: {},
      create: tm
    });
  }
  console.log('✓ Training modules seeded');

  // 8. Seed PDP Appraisals
  const existingPdp = await prisma.pDP.findFirst({ where: { userEmail: 'staff@cccrn.org' } });
  if (!existingPdp) {
    await prisma.pDP.create({
      data: {
        userEmail: 'staff@cccrn.org',
        staffName: 'Dr. Biodun Ojo',
        position: 'Clinical Operations Lead',
        department: 'Programme Delivery',
        supervisor: 'Director of Compliance',
        hod: 'Executive Leadership',
        period: 'Oct 2025 - Sep 2026',
        status: 'Approved'
      }
    });
  }
  console.log('✓ PDP records seeded');

  console.log('✅ CCCRN Compliance Database seeding completed successfully!');

}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
