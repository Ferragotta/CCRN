import React, { useState } from 'react';
import { useToast } from '../../context/ToastContext';
import { useAuth } from '../../context/AuthContext';

export interface TravelRequestItem {
  id: string;
  travellerName: string;
  department: string;
  route: string;
  travelDate: string;
  returnDate: string;
  purpose: string;
  estimatedCost: number;
  donorCode: string;
  status: 'Pending Logistics' | 'Ticket Issued' | 'Rejected';
  requestDate: string;
  authDocName?: string;
}

export interface TicketPurchaseItem {
  id: string;
  reqRef: string;
  travellerName: string;
  route: string;
  airline: string;
  vendorName: string;
  ticketNumber: string;
  amount: number;
  purchaseDate: string;
  travelDate: string;
  returnDate: string;
  status: 'Issued / Open' | 'Utilised' | 'Cancelled';
  boardingPassUploaded: boolean;
  boardingPassFile?: string;
  paymentStatus: 'Unpaid' | 'Approved for Payment' | 'Paid';
}

export interface VendorPaymentItem {
  id: string;
  vendorName: string;
  invoiceRef: string;
  tickets: string;
  amount: number;
  dueDate: string;
  daysOverdue: number;
  boardingPassVerified: boolean;
  status: 'Pending Boarding Pass' | 'Ready for Payment' | 'Paid';
}

export const TravelModule: React.FC = () => {
  const { currentUser, isDocAdmin } = useAuth();
  const { showSuccess, showError } = useToast();

  const isComplianceOfficer = currentUser?.key === 'compliance_officer';
  const isHR = currentUser?.key === 'hr';

  // Sub-Tabs State
  const [activeTab, setActiveTab] = useState<'request' | 'purchase' | 'tracking' | 'payments'>('request');

  // Seed Travel Requests
  const [requests, setRequests] = useState<TravelRequestItem[]>([
    {
      id: 'TR-108',
      travellerName: 'Amina Bello (State Lead)',
      department: 'Programme',
      route: 'Abuja (ABV) → Kano (KAN) → Abuja',
      travelDate: '06 Mar 2026',
      returnDate: '10 Mar 2026',
      purpose: 'Routine facility audit and PEPFAR cluster supervision in Kano.',
      estimatedCost: 195000,
      donorCode: 'DOS-2026-HIV-001',
      status: 'Pending Logistics',
      requestDate: '01 Mar 2026',
      authDocName: 'Travel_Auth_Amina_Signed.pdf'
    },
    {
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
      authDocName: 'Signed_Travel_Form_Chidinma.pdf'
    }
  ]);

  // Seed Ticket Purchases
  const [tickets, setTickets] = useState<TicketPurchaseItem[]>([
    {
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
    },
    {
      id: 'TKT-553',
      reqRef: 'TR-105',
      travellerName: 'Dr. Biodun Ojo',
      route: 'Abuja → Rivers (PHC) → Abuja',
      airline: 'Ibom Air',
      vendorName: 'Travelbeta Logistics Ltd',
      ticketNumber: '921-8374910283',
      amount: 220000,
      purchaseDate: '20 Feb 2026',
      travelDate: '22 Feb 2026',
      returnDate: '26 Feb 2026',
      status: 'Utilised',
      boardingPassUploaded: true,
      boardingPassFile: 'Boarding_Pass_Biodun_PHC.jpg',
      paymentStatus: 'Approved for Payment'
    },
    {
      id: 'TKT-552',
      reqRef: 'TR-104',
      travellerName: 'Emeka Nwosu',
      route: 'Abuja → Kaduna → Abuja',
      airline: 'Overland Airways',
      vendorName: 'Wakanow Corporate Travel',
      ticketNumber: '074-5546372819',
      amount: 160000,
      purchaseDate: '15 Feb 2026',
      travelDate: '18 Feb 2026',
      returnDate: '20 Feb 2026',
      status: 'Utilised',
      boardingPassUploaded: true,
      boardingPassFile: 'BoardingPass_Emeka_Kaduna.pdf',
      paymentStatus: 'Paid'
    }
  ]);

  // Seed Vendor Payments
  const [payments, setPayments] = useState<VendorPaymentItem[]>([
    {
      id: 'PAY-091',
      vendorName: 'Wakanow Corporate Travel',
      invoiceRef: 'INV-WAK-2026-088',
      tickets: 'TKT-554 (Chidinma Okoro)',
      amount: 185000,
      dueDate: '10 Mar 2026',
      daysOverdue: 0,
      boardingPassVerified: false,
      status: 'Pending Boarding Pass'
    },
    {
      id: 'PAY-090',
      vendorName: 'Travelbeta Logistics Ltd',
      invoiceRef: 'INV-TB-98231',
      tickets: 'TKT-553 (Dr. Biodun Ojo)',
      amount: 220000,
      dueDate: '02 Mar 2026',
      daysOverdue: 2,
      boardingPassVerified: true,
      status: 'Ready for Payment'
    }
  ]);

  // Modals & Form State
  const [showUploadBpModal, setShowUploadBpModal] = useState<TicketPurchaseItem | null>(null);
  const [showBookTicketModal, setShowBookTicketModal] = useState<TravelRequestItem | null>(null);
  const [bpFile, setBpFile] = useState('');

  // New Travel Request Form
  const [reqName, setReqName] = useState(currentUser?.name || '');
  const [reqDept, setReqDept] = useState('Programme');
  const [reqRoute, setReqRoute] = useState('');
  const [reqDate, setReqDate] = useState('');
  const [reqReturn, setReqReturn] = useState('');
  const [reqPurpose, setReqPurpose] = useState('');
  const [reqCost, setReqCost] = useState('');
  const [reqDonor, setReqDonor] = useState('DOS-2026-HIV-001');

  // Book Ticket Form
  const [bookAirline, setBookAirline] = useState('Air Peace');
  const [bookVendor, setBookVendor] = useState('Wakanow Corporate Travel');
  const [bookTktNo, setBookTktNo] = useState('');
  const [bookAmount, setBookAmount] = useState('');

  const handleCreateRequest = (e: React.FormEvent) => {
    e.preventDefault();
    if (!reqRoute.trim() || !reqDate) {
      alert('Please specify travel route and departure date.');
      return;
    }

    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    const newReq: TravelRequestItem = {
      id: 'TR-' + (109 + requests.length),
      travellerName: reqName || 'Staff Member',
      department: reqDept,
      route: reqRoute,
      travelDate: reqDate,
      returnDate: reqReturn || reqDate,
      purpose: reqPurpose,
      estimatedCost: parseFloat(reqCost) || 150000,
      donorCode: reqDonor,
      status: 'Pending Logistics',
      requestDate: today,
      authDocName: 'Signed_Travel_Authorization.pdf'
    };

    setRequests([newReq, ...requests]);
    setReqRoute('');
    setReqPurpose('');
    setReqCost('');
    showSuccess('Travel Request Submitted', `Flight request ${newReq.id} logged for logistics processing.`);
  };

  const handleIssueTicket = (e: React.FormEvent) => {
    e.preventDefault();
    if (!showBookTicketModal) return;

    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    const newTkt: TicketPurchaseItem = {
      id: 'TKT-' + (555 + tickets.length),
      reqRef: showBookTicketModal.id,
      travellerName: showBookTicketModal.travellerName,
      route: showBookTicketModal.route,
      airline: bookAirline,
      vendorName: bookVendor,
      ticketNumber: bookTktNo || '074-' + Math.floor(1000000000 + Math.random() * 9000000000),
      amount: parseFloat(bookAmount) || showBookTicketModal.estimatedCost,
      purchaseDate: today,
      travelDate: showBookTicketModal.travelDate,
      returnDate: showBookTicketModal.returnDate,
      status: 'Issued / Open',
      boardingPassUploaded: false,
      paymentStatus: 'Unpaid'
    };

    setTickets([newTkt, ...tickets]);
    setRequests(requests.map(r => r.id === showBookTicketModal.id ? { ...r, status: 'Ticket Issued' } : r));

    // Also add to vendor payments
    const newPay: VendorPaymentItem = {
      id: 'PAY-0' + (92 + payments.length),
      vendorName: bookVendor,
      invoiceRef: 'INV-' + bookVendor.substring(0, 3).toUpperCase() + '-2026-' + Math.floor(100 + Math.random() * 900),
      tickets: `${newTkt.id} (${newTkt.travellerName})`,
      amount: newTkt.amount,
      dueDate: showBookTicketModal.returnDate,
      daysOverdue: 0,
      boardingPassVerified: false,
      status: 'Pending Boarding Pass'
    };
    setPayments([...payments, newPay]);

    setShowBookTicketModal(null);
    setBookTktNo('');
    setBookAmount('');
    showSuccess('Ticket Issued', `Ticket ${newTkt.id} successfully issued.`);
  };

  const handleUploadBoardingPass = (e: React.FormEvent) => {
    e.preventDefault();
    if (!showUploadBpModal) return;

    const fileName = bpFile || 'BoardingPass_' + showUploadBpModal.travellerName.replace(/\s+/g, '_') + '.pdf';

    setTickets(tickets.map(t => {
      if (t.id === showUploadBpModal.id) {
        return {
          ...t,
          status: 'Utilised',
          boardingPassUploaded: true,
          boardingPassFile: fileName,
          paymentStatus: 'Approved for Payment'
        };
      }
      return t;
    }));

    // Update vendor payment status to Ready for Payment
    setPayments(payments.map(p => {
      if (p.tickets.includes(showUploadBpModal.id)) {
        return {
          ...p,
          boardingPassVerified: true,
          status: 'Ready for Payment'
        };
      }
      return p;
    }));

    setShowUploadBpModal(null);
    setBpFile('');
    showSuccess('Boarding Pass Verified', `Boarding pass uploaded for ${showUploadBpModal.id}. Unlocked for vendor payment.`);
  };

  const handleApprovePayment = (payId: string) => {
    if (!isDocAdmin && !isComplianceOfficer) {
      alert('Action Restricted: Only Compliance / Finance has authority to clear vendor payments.');
      return;
    }

    const pay = payments.find(p => p.id === payId);
    if (pay && !pay.boardingPassVerified) {
      showError('Compliance Block', 'Cannot clear payment. Physical boarding pass image has not been attached.');
      return;
    }

    setPayments(payments.map(p => p.id === payId ? { ...p, status: 'Paid' } : p));
    showSuccess('Payment Cleared', `Payment ${payId} approved and cleared for disbursement.`);
  };

  // Stat calculations
  const totalTickets = tickets.length;
  const usedTickets = tickets.filter(t => t.status === 'Utilised').length;
  const unusedTickets = tickets.filter(t => t.status === 'Issued / Open').length;
  const totalDebt = payments.filter(p => p.status !== 'Paid').reduce((acc, curr) => acc + curr.amount, 0);

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
              ✈️ Travel & Ticket Compliance Portal
            </h2>
            <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
              Flight ticket booking, travel authorization, utilization tracking, and mandatory boarding pass submission for vendor payment
            </p>
          </div>

          <div style={{ padding: '5px 12px', background: 'rgba(0, 119, 182, 0.08)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-plane-departure"></i> <strong>Mandatory Compliance:</strong> Boarding pass proof required before vendor clearance
          </div>
        </div>
      </div>

      {/* 4 STAT KPI CARDS matching wireframe */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 12, marginBottom: 16 }}>
        <div className="stat-card blue">
          <div className="stat-label">Total Tickets Booked</div>
          <div className="stat-value">{totalTickets}</div>
        </div>
        <div className="stat-card green">
          <div className="stat-label">Utilised (Boarding Pass Verified)</div>
          <div className="stat-value">{usedTickets}</div>
        </div>
        <div className="stat-card red">
          <div className="stat-label">Unused / Open Tickets</div>
          <div className="stat-value">{unusedTickets}</div>
        </div>
        <div className="stat-card purple">
          <div className="stat-label">Outstanding Vendor Payments</div>
          <div className="stat-value" style={{ fontSize: 18, marginTop: 4 }}>
            ₦{totalDebt.toLocaleString()}
          </div>
        </div>
      </div>

      {/* 4 SUB-TABS matching wireframe */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 16 }}>
        <button
          onClick={() => setActiveTab('request')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'request' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'request' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          📋 Ticket Request ({requests.length})
        </button>
        <button
          onClick={() => setActiveTab('purchase')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'purchase' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'purchase' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          🛒 Purchase Log ({tickets.length})
        </button>
        <button
          onClick={() => setActiveTab('tracking')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'tracking' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'tracking' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          📍 Ticket Tracking & Boarding Passes
        </button>
        <button
          onClick={() => setActiveTab('payments')}
          style={{
            padding: '7px 14px', fontSize: 12, fontWeight: 600, borderRadius: 'var(--radius-sm)',
            background: activeTab === 'payments' ? 'var(--accent)' : 'var(--surface)',
            color: activeTab === 'payments' ? '#fff' : 'var(--text-dim)',
            border: '1px solid var(--border)', cursor: 'pointer'
          }}
        >
          💳 Outstanding Vendor Payments ({payments.length})
        </button>
      </div>

      {/* 1. TICKET REQUEST VIEW (Staff can request; Logistics/Compliance can book) */}
      {activeTab === 'request' && (
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
          {/* LEFT: Request Form */}
          <div className="card">
            <div className="card-header">
              <div className="card-title">
                <i className="fa-solid fa-paper-plane" style={{ color: 'var(--accent)' }}></i> 📋 New Flight Ticket Request
              </div>
            </div>

            <form onSubmit={handleCreateRequest} style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Traveller Name:</label>
                  <input
                    type="text"
                    value={reqName}
                    onChange={(e) => setReqName(e.target.value)}
                    required
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Department / State:</label>
                  <select
                    value={reqDept}
                    onChange={(e) => setReqDept(e.target.value)}
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                  >
                    <option>Programme</option>
                    <option>Finance</option>
                    <option>Compliance</option>
                    <option>HR</option>
                    <option>M&E</option>
                    <option>Admin / Logistics</option>
                  </select>
                </div>
              </div>

              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Travel Route:</label>
                <input
                  type="text"
                  placeholder="e.g. Abuja (ABV) → Kano (KAN) → Abuja"
                  value={reqRoute}
                  onChange={(e) => setReqRoute(e.target.value)}
                  required
                  style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Travel Date:</label>
                  <input
                    type="date"
                    value={reqDate}
                    onChange={(e) => setReqDate(e.target.value)}
                    required
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Return Date:</label>
                  <input
                    type="date"
                    value={reqReturn}
                    onChange={(e) => setReqReturn(e.target.value)}
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                  />
                </div>
              </div>

              <div>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Purpose of Travel:</label>
                <textarea
                  placeholder="Programme supervision, facility audit, training workshop..."
                  value={reqPurpose}
                  onChange={(e) => setReqPurpose(e.target.value)}
                  required
                  style={{ width: '100%', minHeight: 45, padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Estimated Cost (₦):</label>
                  <input
                    type="number"
                    placeholder="180000"
                    value={reqCost}
                    onChange={(e) => setReqCost(e.target.value)}
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Donor / Project Code:</label>
                  <input
                    type="text"
                    value={reqDonor}
                    onChange={(e) => setReqDonor(e.target.value)}
                    style={{ width: '100%', padding: '7px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 3 }}
                  />
                </div>
              </div>

              <div style={{ marginTop: 6 }}>
                <button type="submit" className="btn btn-primary" style={{ width: '100%', padding: 9 }}>
                  📤 Submit Flight Ticket Request
                </button>
              </div>
            </form>
          </div>

          {/* RIGHT: Pending Requests */}
          <div className="card">
            <div className="card-header">
              <div className="card-title">
                <i className="fa-solid fa-clock" style={{ color: 'var(--accent2)' }}></i> Pending Travel Requests ({requests.length})
              </div>
            </div>

            <table>
              <thead>
                <tr>
                  <th>Ref#</th>
                  <th>Traveller</th>
                  <th>Route</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {requests.map((r) => (
                  <tr key={r.id}>
                    <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{r.id}</td>
                    <td style={{ fontWeight: 600, fontSize: 11 }}>{r.travellerName}</td>
                    <td style={{ fontSize: 10 }}>{r.route}</td>
                    <td style={{ fontSize: 10 }}>{r.travelDate}</td>
                    <td>
                      <span className={`pill ${r.status === 'Ticket Issued' ? 'pill-closed' : 'pill-progress'}`} style={{ fontSize: 9 }}>
                        {r.status}
                      </span>
                    </td>
                    <td>
                      {r.status === 'Pending Logistics' && (isComplianceOfficer || isDocAdmin || isHR) ? (
                        <button
                          className="btn btn-primary btn-sm"
                          style={{ padding: '2px 6px', fontSize: 10 }}
                          onClick={() => {
                            setShowBookTicketModal(r);
                            setBookAmount(r.estimatedCost.toString());
                          }}
                        >
                          🛒 Book Ticket
                        </button>
                      ) : (
                        <span style={{ fontSize: 10, color: 'var(--text-muted)' }}>✓ Processed</span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* 2. PURCHASE LOG VIEW */}
      {activeTab === 'purchase' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-cart-shopping" style={{ color: 'var(--accent)' }}></i> 🛒 Ticket Purchase Register
            </div>
            <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
              Total Value: ₦{tickets.reduce((a, b) => a + b.amount, 0).toLocaleString()}
            </div>
          </div>

          <table>
            <thead>
              <tr>
                <th>Ticket ID</th>
                <th>Traveller</th>
                <th>Route</th>
                <th>Airline</th>
                <th>Vendor</th>
                <th>Ticket Number</th>
                <th>Amount (₦)</th>
                <th>Travel Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              {tickets.map((t) => (
                <tr key={t.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{t.id}</td>
                  <td style={{ fontWeight: 600, fontSize: 11 }}>{t.travellerName}</td>
                  <td style={{ fontSize: 10 }}>{t.route}</td>
                  <td>{t.airline}</td>
                  <td style={{ fontSize: 11 }}>{t.vendorName}</td>
                  <td style={{ fontSize: 11, fontFamily: 'monospace' }}>{t.ticketNumber}</td>
                  <td style={{ fontWeight: 700 }}>₦{t.amount.toLocaleString()}</td>
                  <td style={{ fontSize: 11 }}>{t.travelDate}</td>
                  <td>
                    <span className={`pill ${t.status === 'Utilised' ? 'pill-closed' : 'pill-open'}`} style={{ fontSize: 10 }}>
                      {t.status}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* 3. TICKET TRACKING & MANDATORY BOARDING PASS SUBMISSION */}
      {activeTab === 'tracking' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-ticket" style={{ color: 'var(--accent)' }}></i> 📍 Ticket Tracking & Mandatory Boarding Pass Upload
            </div>
          </div>

          <div style={{ background: '#f0f9ff', border: '1px solid #bae6fd', borderRadius: 8, padding: '10px 14px', marginBottom: 14, fontSize: 11, color: '#0369a1', display: 'flex', alignItems: 'center', gap: 10 }}>
            <i className="fa-solid fa-circle-info" style={{ fontSize: 16 }}></i>
            <div>
              <strong>Mandatory Compliance Rule:</strong> All staff must upload physical boarding pass images upon return. Vendor payments remain blocked until the boarding pass is confirmed attached.
            </div>
          </div>

          <table>
            <thead>
              <tr>
                <th>Ticket ID</th>
                <th>Traveller</th>
                <th>Route</th>
                <th>Travel Dates</th>
                <th>Boarding Pass Status</th>
                <th>Proof File</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {tickets.map((t) => (
                <tr key={t.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{t.id}</td>
                  <td style={{ fontWeight: 600, fontSize: 11 }}>{t.travellerName}</td>
                  <td style={{ fontSize: 10 }}>{t.route}</td>
                  <td style={{ fontSize: 10 }}>{t.travelDate} → {t.returnDate}</td>
                  <td>
                    {t.boardingPassUploaded ? (
                      <span className="pill pill-closed" style={{ fontSize: 9 }}>
                        <i className="fa-solid fa-check"></i> Boarding Pass Verified
                      </span>
                    ) : (
                      <span className="pill pill-open" style={{ fontSize: 9 }}>
                        <i className="fa-solid fa-triangle-exclamation"></i> Pending Upload
                      </span>
                    )}
                  </td>
                  <td>
                    {t.boardingPassFile ? (
                      <span style={{ color: 'var(--accent)', fontSize: 10, fontWeight: 700 }}>
                        <i className="fa-solid fa-file-image"></i> {t.boardingPassFile}
                      </span>
                    ) : (
                      <span style={{ color: 'var(--text-muted)', fontSize: 10 }}>None attached</span>
                    )}
                  </td>
                  <td>
                    {!t.boardingPassUploaded ? (
                      <button
                        className="btn btn-primary btn-sm"
                        style={{ padding: '3px 8px', fontSize: 10, background: 'var(--accent2)', borderColor: 'var(--accent2)' }}
                        onClick={() => setShowUploadBpModal(t)}
                      >
                        📎 Upload Boarding Pass
                      </button>
                    ) : (
                      <button
                        className="btn btn-outline btn-sm"
                        style={{ padding: '3px 8px', fontSize: 10 }}
                        onClick={() => alert(`Displaying ${t.boardingPassFile} in secure compliance proof viewer...`)}
                      >
                        👁️ View Pass
                      </button>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* 4. OUTSTANDING VENDOR PAYMENTS VIEW */}
      {activeTab === 'payments' && (
        <div className="card">
          <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div className="card-title">
              <i className="fa-solid fa-credit-card" style={{ color: 'var(--warning)' }}></i> 💳 Outstanding Travel Vendor Payments & Payment Clearance
            </div>
            <div style={{ fontWeight: 700, fontSize: 12, color: 'var(--danger)' }}>
              Total Outstanding: ₦{totalDebt.toLocaleString()}
            </div>
          </div>

          <table>
            <thead>
              <tr>
                <th>Invoice Ref</th>
                <th>Vendor</th>
                <th>Ticket / Traveller</th>
                <th>Amount (₦)</th>
                <th>Due Date</th>
                <th>Compliance Condition</th>
                <th>Payment Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {payments.map((p) => (
                <tr key={p.id}>
                  <td style={{ fontWeight: 700, color: 'var(--accent)' }}>{p.invoiceRef}</td>
                  <td style={{ fontWeight: 600 }}>{p.vendorName}</td>
                  <td style={{ fontSize: 11 }}>{p.tickets}</td>
                  <td style={{ fontWeight: 700 }}>₦{p.amount.toLocaleString()}</td>
                  <td style={{ fontSize: 11 }}>{p.dueDate}</td>
                  <td>
                    {p.boardingPassVerified ? (
                      <span style={{ color: 'var(--success)', fontWeight: 700, fontSize: 10 }}>
                        ✓ Boarding Pass Verified
                      </span>
                    ) : (
                      <span style={{ color: 'var(--danger)', fontWeight: 700, fontSize: 10 }}>
                        ⛔ BLOCKED: No Boarding Pass
                      </span>
                    )}
                  </td>
                  <td>
                    <span className={`pill ${p.status === 'Paid' ? 'pill-closed' : p.status === 'Ready for Payment' ? 'pill-progress' : 'pill-open'}`} style={{ fontSize: 9 }}>
                      {p.status}
                    </span>
                  </td>
                  <td>
                    {p.status !== 'Paid' ? (
                      <button
                        className="btn btn-primary btn-sm"
                        style={{
                          padding: '3px 8px', fontSize: 10,
                          background: p.boardingPassVerified ? 'var(--success)' : '#9ca3af',
                          borderColor: p.boardingPassVerified ? 'var(--success)' : '#9ca3af',
                          cursor: p.boardingPassVerified ? 'pointer' : 'not-allowed'
                        }}
                        onClick={() => handleApprovePayment(p.id)}
                      >
                        ✓ Clear Payment
                      </button>
                    ) : (
                      <span style={{ color: 'var(--success)', fontSize: 10, fontWeight: 700 }}>
                        ✓ Cleared
                      </span>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* MODAL: UPLOAD BOARDING PASS */}
      {showUploadBpModal && (
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
            width: 520,
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
              <div>
                <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                  Upload Flight Boarding Pass Proof
                </div>
                <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                  {showUploadBpModal.id} &nbsp;·&nbsp; {showUploadBpModal.travellerName} &nbsp;·&nbsp; {showUploadBpModal.airline}
                </div>
              </div>
              <button
                onClick={() => setShowUploadBpModal(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleUploadBoardingPass} style={{ padding: 20 }}>
              <div style={{ background: '#fef3c7', border: '1px solid #fde68a', borderRadius: 8, padding: 10, fontSize: 11, color: '#92400e', marginBottom: 14 }}>
                <strong>Attestation:</strong> I certify that I utilized this flight ticket for approved official CCCRN activity and that the attached boarding pass is authentic.
              </div>

              <div style={{ marginBottom: 14 }}>
                <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Select Boarding Pass Image / PDF:</label>
                <input
                  type="file"
                  required
                  onChange={(e) => {
                    if (e.target.files && e.target.files[0]) {
                      setBpFile(e.target.files[0].name);
                    }
                  }}
                  style={{ width: '100%', padding: 6, fontSize: 11, marginTop: 4, border: '1px solid var(--border)', borderRadius: 6 }}
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowUploadBpModal(null)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary" style={{ background: 'var(--accent2)', borderColor: 'var(--accent2)' }}>
                  ✓ Submit Boarding Pass
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: BOOK TICKET (Logistics / Compliance) */}
      {showBookTicketModal && (
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
            width: 540,
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
              <div>
                <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 16, fontWeight: 700, color: 'var(--text)' }}>
                  Book Ticket for Request {showBookTicketModal.id}
                </div>
                <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                  Traveller: {showBookTicketModal.travellerName} &nbsp;·&nbsp; Route: {showBookTicketModal.route}
                </div>
              </div>
              <button
                onClick={() => setShowBookTicketModal(null)}
                style={{ background: 'none', border: 'none', fontSize: 18, cursor: 'pointer', color: 'var(--text-muted)' }}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleIssueTicket} style={{ padding: 20 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Airline:</label>
                  <select
                    value={bookAirline}
                    onChange={(e) => setBookAirline(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Air Peace</option>
                    <option>Ibom Air</option>
                    <option>United Nigeria Airlines</option>
                    <option>Overland Airways</option>
                    <option>ValueJet</option>
                    <option>Max Air</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Travel Vendor Agency:</label>
                  <select
                    value={bookVendor}
                    onChange={(e) => setBookVendor(e.target.value)}
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  >
                    <option>Wakanow Corporate Travel</option>
                    <option>Travelbeta Logistics Ltd</option>
                    <option>Finchglow Travels</option>
                  </select>
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 14 }}>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Ticket / E-Ticket Number:</label>
                  <input
                    type="text"
                    placeholder="e.g. 074-2983749281"
                    value={bookTktNo}
                    onChange={(e) => setBookTktNo(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
                <div>
                  <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>Actual Invoice Cost (₦):</label>
                  <input
                    type="number"
                    value={bookAmount}
                    onChange={(e) => setBookAmount(e.target.value)}
                    required
                    style={{ width: '100%', padding: '8px 10px', fontSize: 12, border: '1px solid var(--border)', borderRadius: 6, marginTop: 4 }}
                  />
                </div>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowBookTicketModal(null)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  Confirm Ticket & Log Purchase
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
