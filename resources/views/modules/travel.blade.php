@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="travelModuleContainer">

  <!-- HEADER -->
  <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
    <div>
      <div style="display: flex; align-items: center; gap: 8px;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px;">
          Flight Requests, Ticket Utilization &amp; Vendor Payments
        </h2>
        <span class="pill pill-progress" style="font-size: 10px; font-weight: 700;">Under Review by Logistics &amp; DOF</span>
      </div>
      <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px;">
        Compliance oversight system across flight requests, ticket bookings, online boarding pass reconciliation, and vendor payments (POL-TRV-03).
      </p>
      <div id="travelRoleIndicator"></div>
    </div>

    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
      <button class="btn btn-outline btn-sm" onclick="openModal('modalNewFlightRequest')" style="font-size: 11px; font-weight: 700;">
        <i class="fa-solid fa-plane-departure me-1"></i> Request Flight Ticket
      </button>
      <button class="btn btn-primary btn-sm" onclick="exportTravelComplianceReport('pdf')" style="font-size: 11px; font-weight: 700;">
        <i class="fa-solid fa-file-pdf me-1"></i> Travel &amp; Payment Audit Report
      </button>
    </div>
  </div>

  <!-- 4 STAT TILES (EXACT USER SPECIFICATION) -->
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 18px;">
    <!-- Tile 1: Total Tickets Booked -->
    <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #0369a1; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Total Tickets Booked</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0284c7; line-height: 1;" id="statTotalTickets">142</div>
      <div style="font-size: 11px; color: #0369a1; font-weight: 600; margin-top: 4px;">&#8358;24,850,000.00 Total Flight Spend</div>
      <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Individual &amp; Group Missions</div>
    </div>

    <!-- Tile 2: Utilized Tickets -->
    <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #065f46; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Utilized Tickets</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #059669; line-height: 1;" id="statUtilizedTickets">118</div>
      <div style="font-size: 11px; color: #065f46; font-weight: 600; margin-top: 4px;">83.1% Reconciliation Rate</div>
      <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Boarding Passes Verified Online</div>
    </div>

    <!-- Tile 3: Unused / Open Tickets -->
    <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #92400e; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Unused / Open Tickets</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;" id="statOpenTickets">24</div>
      <div style="font-size: 11px; color: #92400e; font-weight: 600; margin-top: 4px;">Awaiting Boarding Pass Submission</div>
      <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Flagged for Escrow Lock / Clawback</div>
    </div>

    <!-- Tile 4: Ticket Payment / Outstanding Vendor Payment -->
    <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #991b1b; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Outstanding Vendor Payment</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: #dc2626; line-height: 1.1;" id="statOutstandingPayment">&#8358;4,680,000</div>
      <div style="font-size: 11px; color: #991b1b; font-weight: 600; margin-top: 4px;">Locked at Escrow Gate</div>
      <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Requires Boarding Pass Before DOF Release</div>
    </div>
  </div>

  <!-- COMPLIANCE ADOPTION BANNER -->
  <div style="background: rgba(2,54,123,0.06); border-left: 4px solid var(--accent); padding: 12px 16px; border-radius: 6px; margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
    <div style="display: flex; gap: 10px; align-items: center;">
      <i class="fa-solid fa-shield-halved" style="font-size: 20px; color: var(--accent);"></i>
      <div>
        <strong style="color: var(--accent); font-size: 12px;">Institutional Travel &amp; Payment Compliance Gate (POL-TRV-03):</strong>
        <div style="font-size: 11px; color: var(--text-dim); margin-top: 2px;">
          Developed for review &amp; adoption by the <strong>Logistics Department</strong> and <strong>Directorate of Finance (DOF)</strong>. Travel agency invoices cannot be disbursed until electronic boarding passes are submitted directly under each booked ticket.
        </div>
      </div>
    </div>
    <span class="pill" style="background: var(--accent); color: #fff; padding: 3px 10px; font-size: 11px; font-weight: 700;">DOF Escrow Standard</span>
  </div>

  <!-- 4 WORKFLOW SUB-TABS -->
  <div style="display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap;" id="travelTabs">
    <button class="tab active" id="tTabReq" onclick="switchTravelTab('request')" style="padding: 7px 14px; font-size: 12px; font-weight: 700; cursor: pointer; border-radius: var(--radius-sm); background: var(--accent); color: #fff; border: 1px solid var(--accent);">
      ✈️ 1. Flight Ticket Requests (Staff Portal)
    </button>
    <button class="tab" id="tTabIssue" onclick="switchTravelTab('purchase')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
      🎫 2. Logistics Booking Desk
    </button>
    <button class="tab" id="tTabTrack" onclick="switchTravelTab('tracking')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
      📋 3. Ticket Ledger &amp; Online Boarding Pass Submission
    </button>
    <button class="tab" id="tTabPay" onclick="switchTravelTab('payments')" style="padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm); background: var(--surface); color: var(--text-dim); border: 1px solid var(--border);">
      💳 4. Vendor Payments &amp; DOF Clearance Gate
    </button>
  </div>

  <!-- ══════════════════════════════════════════════════════════════════
       SUB-PANEL 1: FLIGHT REQUESTS & DOCUMENTATION (STAFF PORTAL)
       ══════════════════════════════════════════════════════════════════ -->
  <div id="tPanelReq" class="card" style="padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box; margin-bottom: 0;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
      <div>
        <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-plane-arrival" style="color: var(--accent);"></i> Staff Flight Ticket Requests (Individual &amp; Group Missions)
        </div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
          Staff request flight ticket bookings with mandatory Travel Authorization and Budget Memos attached.
        </div>
      </div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modalNewFlightRequest')" style="font-size: 11px; font-weight: 700;">
        <i class="fa-solid fa-plus me-1"></i> New Flight Request
      </button>
    </div>

    <!-- Table 100% width, fixed layout, zero horizontal scroll -->
    <div style="width: 100%; overflow: hidden;">
      <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
        <thead>
          <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
            <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Request Ref</th>
            <th style="width: 20%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Traveler(s) / Mission</th>
            <th style="width: 12%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Trip Type</th>
            <th style="width: 18%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Routing &amp; Flight Dates</th>
            <th style="width: 14%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Attached Documentation</th>
            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Logistics Action</th>
          </tr>
        </thead>
        <tbody id="travelRequestsTableBody"></tbody>
      </table>
    </div>
  </div>

  <!-- ══════════════════════════════════════════════════════════════════
       SUB-PANEL 2: LOGISTICS BOOKING DESK
       ══════════════════════════════════════════════════════════════════ -->
  <div id="tPanelIssue" class="card" style="display: none; padding: 18px 20px; width: 100%; box-sizing: border-box; margin-bottom: 0;">
    <div class="card-header" style="margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2);">
      <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-ticket" style="color: var(--accent);"></i> Logistics Booking Desk — Ticket Issuance Portal
      </div>
      <p style="font-size: 11px; color: var(--text-muted); margin: 4px 0 0;">
        Logistics personnel book ticket(s) directly using the traveler details and memos already submitted by staff.
      </p>
    </div>

    <form onsubmit="handleLogisticsTicketIssue(event)" style="max-width: 820px; margin: 0 auto;">
      <div style="background: rgba(2,54,123,0.04); border: 1px solid var(--border); border-radius: 8px; padding: 14px; margin-bottom: 16px;">
        <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px; color: var(--accent);">
          1. Select Approved Staff Request to Book:
        </label>
        <select id="ticketTravelRef" onchange="autoPopulateLogisticsBooking(this.value)" style="width: 100%; height: 38px; padding: 0 10px; font-size: 12px; font-weight: 600; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
          <option value="TR-108">TR-108 — Amina Bello (State Lead) · Abuja (ABV) &rarr; Kano (KAN) · CAP-032 Field Audit</option>
          <option value="TR-109">TR-109 — Cluster Surge Team (Group of 4) · Abuja &rarr; Borno Field Clinics · Surge Testing Drive</option>
          <option value="TR-107">TR-107 — Chidinma Okoro · Abuja &rarr; Lagos · Q1 Finance Review</option>
        </select>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">Passenger / Group Manifest (From Request) *</label>
          <input type="text" id="ticketTraveller" required placeholder="Passenger Name(s)" style="width: 100%; height: 36px; padding: 0 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text); box-sizing: border-box;">
        </div>
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">Flight Routing *</label>
          <input type="text" id="ticketRoute" required placeholder="e.g. Abuja (ABV) → Kano (KAN) → Abuja" style="width: 100%; height: 36px; padding: 0 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text); box-sizing: border-box;">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 14px;">
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">Airline Selected *</label>
          <select id="ticketAirline" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
            <option>Air Peace</option>
            <option>Ibom Air</option>
            <option>United Nigeria</option>
            <option>Max Air</option>
            <option>Overland Airways</option>
            <option>ValueJet</option>
          </select>
        </div>
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">PNR / Booking Reference *</label>
          <input type="text" id="ticketPnr" required placeholder="e.g. 6Q9ZKR" style="width: 100%; height: 36px; padding: 0 10px; font-size: 12px; font-family: monospace; font-weight: 700; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
        </div>
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">E-Ticket Number *</label>
          <input type="text" id="ticketNumberInput" required placeholder="e.g. 072-2491827461" style="width: 100%; height: 36px; padding: 0 10px; font-size: 12px; font-family: monospace; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 14px;">
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">Contracted Travel Agency *</label>
          <select id="ticketVendor" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
            <option>Wakanow Corporate Travel</option>
            <option>Travelbeta Logistics Ltd</option>
            <option>Finchglow Travels</option>
            <option>Travelstart Nigeria</option>
          </select>
        </div>
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">Total Ticket Fare (&#8358;) *</label>
          <input type="number" id="ticketCost" required placeholder="e.g. 195000" style="width: 100%; height: 36px; padding: 0 10px; font-size: 12px; font-weight: 700; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
        </div>
        <div>
          <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">Flight Departure Date *</label>
          <input type="date" id="ticketDate" required value="2026-03-10" style="width: 100%; height: 36px; padding: 0 8px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
        </div>
      </div>

      <div style="margin-bottom: 16px;">
        <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px; color: var(--text-muted);">Upload Issued E-Ticket Itinerary (PDF) *</label>
        <input type="file" id="ticketPdfFile" required style="width: 100%; padding: 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text); box-sizing: border-box;">
      </div>

      <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 12px; margin-bottom: 18px; font-size: 12px; line-height: 1.4;">
        <i class="fa-solid fa-lock" style="color: var(--warning); margin-right: 6px;"></i>
        <strong>Automatic POL-TRV-03 Escrow Placement:</strong> Logging this ticket will set its initial state as <em>Unused / Open Ticket</em>. Vendor payment to the travel agency is locked until the traveler uploads the electronic boarding pass.
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button type="button" class="btn btn-outline" onclick="switchTravelTab('request')">Cancel</button>
        <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 10px 20px;">
          <i class="fa-solid fa-ticket me-1"></i> Issue &amp; Log Ticket into System
        </button>
      </div>
    </form>
  </div>

  <!-- ══════════════════════════════════════════════════════════════════
       SUB-PANEL 3: TICKET LEDGER & ONLINE BOARDING PASS SUBMISSION
       ══════════════════════════════════════════════════════════════════ -->
  <div id="tPanelTrack" class="card" style="display: none; padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box; margin-bottom: 0;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
      <div>
        <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-list-check" style="color: var(--accent);"></i> Flight Ticket Ledger &amp; Direct Boarding Pass Submission
        </div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
          All issued tickets are logged below. Staff submit their electronic boarding passes directly under each booked ticket to clear vendor payments.
        </div>
      </div>
      <div style="display: flex; gap: 8px; align-items: center;">
        <select id="filterTicketUtilization" onchange="filterTicketsTable()" style="height: 32px; padding: 0 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
          <option value="">All Tickets (Booked &amp; Open)</option>
          <option value="Utilized">Utilized (Boarding Pass Verified)</option>
          <option value="Open">Unused / Open Tickets (Awaiting Pass)</option>
        </select>
        <input type="text" id="searchTicketInput" onkeyup="filterTicketsTable()" placeholder="Search PNR, ticket ID, traveler..." style="height: 32px; padding: 0 10px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; width: 220px; background: var(--surface); color: var(--text);">
      </div>
    </div>

    <!-- Table 100% width, fixed layout, zero horizontal scroll -->
    <div style="width: 100%; overflow: hidden;">
      <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
        <thead>
          <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
            <th style="width: 9%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Ticket Ref</th>
            <th style="width: 8%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">PNR Code</th>
            <th style="width: 20%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Passenger / Group</th>
            <th style="width: 16%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Routing &amp; Airline</th>
            <th style="width: 9%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Travel Date</th>
            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Fare (&#8358;)</th>
            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Utilization Status</th>
            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Boarding Pass Action</th>
          </tr>
        </thead>
        <tbody id="ticketLedgerBody"></tbody>
      </table>
    </div>
  </div>

  <!-- ══════════════════════════════════════════════════════════════════
       SUB-PANEL 4: VENDOR PAYMENTS & DOF CLEARANCE GATE
       ══════════════════════════════════════════════════════════════════ -->
  <div id="tPanelPay" class="card" style="display: none; padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box; margin-bottom: 0;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2);">
      <div>
        <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-money-bill-wave" style="color: var(--success);"></i> Travel Vendor Invoices &amp; DOF Disbursement Gate
        </div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
          Prepared for Directorate of Finance (DOF) validation. Invoices are unlocked for disbursement strictly when boarding passes are reconciled under each ticket.
        </div>
      </div>
      <button class="btn btn-outline btn-sm" onclick="alert('Exporting DOF Vendor Reconciliation Schedule (Excel)...')" style="font-size: 11px; font-weight: 700;">
        <i class="fa-solid fa-file-excel me-1"></i> DOF Payment Schedule
      </button>
    </div>

    <!-- Table 100% width, fixed layout, zero horizontal scroll -->
    <div style="width: 100%; overflow: hidden;">
      <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
        <thead>
          <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
            <th style="width: 10%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Invoice Ref</th>
            <th style="width: 22%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Contracted Travel Agency</th>
            <th style="width: 14%; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Tickets in Batch</th>
            <th style="width: 14%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Invoice Amount</th>
            <th style="width: 18%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">POL-TRV-03 Escrow Gate</th>
            <th style="width: 12%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Payment Status</th>
            <th style="width: 10%; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">DOF Action</th>
          </tr>
        </thead>
        <tbody id="vendorPaymentsTableBody"></tbody>
      </table>
    </div>

    <div style="margin-top: 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; font-size: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
      <div>
        <i class="fa-solid fa-circle-check" style="color: var(--success); margin-right: 6px;"></i>
        <strong>DOF Escrow Summary:</strong> &#8358;20,170,000 cleared and paid to vendors. &#8358;4,680,000 currently held in escrow pending outstanding staff boarding passes.
      </div>
      <span class="pill pill-closed" style="font-size: 11px; font-weight: 700;">Clawback Protected</span>
    </div>
  </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL: NEW FLIGHT TICKET REQUEST (INDIVIDUAL OR GROUP TRIPS)
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalNewFlightRequest" style="display: none;" onclick="if(event.target===this)closeModal('modalNewFlightRequest')">
  <div class="modal-dialog" style="max-width: 600px; width: 95%;">
    <div class="modal-header">
      <span class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
        <i class="fa-solid fa-plane-departure text-primary me-2"></i> Request Flight Ticket Booking
      </span>
      <button class="modal-close" onclick="closeModal('modalNewFlightRequest')">&times;</button>
    </div>
    <form onsubmit="handleFlightRequestSubmit(event)">
      <div class="modal-body" style="font-size: 12px; color: var(--text); display: flex; flex-direction: column; gap: 12px;">
        
        <!-- Individual vs Group Trip Toggle -->
        <div>
          <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; color: var(--text-muted);">Trip Booking Type *</label>
          <div style="display: flex; gap: 16px;">
            <label style="font-weight: 700; display: flex; align-items: center; gap: 6px; cursor: pointer;">
              <input type="radio" name="tripBookingType" value="individual" checked onchange="toggleGroupTripFields(false)" style="accent-color: var(--accent);">
              Individual Staff Mission
            </label>
            <label style="font-weight: 700; display: flex; align-items: center; gap: 6px; cursor: pointer;">
              <input type="radio" name="tripBookingType" value="group" onchange="toggleGroupTripFields(true)" style="accent-color: var(--accent);">
              Group Mission / Multi-Passenger Trip
            </label>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);" id="lblTravelerName">Lead Traveler Name *</label>
            <input type="text" id="reqTravelerInput" required placeholder="e.g. Amina Bello" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Department *</label>
            <select id="reqDeptInput" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
              <option>Programme Delivery</option>
              <option>Clinical Services</option>
              <option>Strategic Information / M&E</option>
              <option>Finance &amp; Admin</option>
              <option>Compliance &amp; Legal</option>
              <option>Human Resources</option>
            </select>
          </div>
        </div>

        <!-- Group Passengers Input (Shown only when Group Trip selected) -->
        <div id="groupTripExtraContainer" style="display: none; background: var(--surface2); padding: 10px 12px; border-radius: 6px; border: 1px solid var(--border);">
          <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--accent);">
            Additional Group Passenger Names (One per line or comma-separated) *
          </label>
          <textarea id="reqGroupManifestInput" rows="2" placeholder="e.g. 1. Kelechi Madu (Finance), 2. Fatima Bakura (SI), 3. Chidi Okeke (Clinical)" style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Origin Airport *</label>
            <input type="text" id="reqOriginInput" required value="Abuja (ABV)" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Destination Airport *</label>
            <input type="text" id="reqDestInput" required placeholder="e.g. Kano (KAN) or Lagos (LOS)" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Departure Date *</label>
            <input type="date" id="reqDepDateInput" required value="2026-03-12" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Return Date (Optional for one-way)</label>
            <input type="date" id="reqRetDateInput" value="2026-03-16" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
        </div>

        <div>
          <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Travel Purpose &amp; Activity Justification *</label>
          <textarea id="reqPurposeInput" rows="2" required placeholder="Detail specific project activities, clinical surge, audit, or donor meeting..." style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
        </div>

        <div>
          <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">
            Attach Required Documentation (Travel Authorization Memo &amp; Approved Budget) *
          </label>
          <input type="file" id="reqMemoFileInput" required style="width: 100%; padding: 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface2); color: var(--text); box-sizing: border-box;">
          <div style="font-size: 10px; color: var(--text-muted); margin-top: 3px;">Logistics personnel will book tickets using the information and memos uploaded here.</div>
        </div>

      </div>
      <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalNewFlightRequest')">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 700;">Submit Flight Request to Logistics</button>
      </div>
    </form>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL: UPLOAD BOARDING PASS (ONLINE UNDER EACH TICKET BOOKED)
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalUploadBoardingPass" style="display: none;" onclick="if(event.target===this)closeModal('modalUploadBoardingPass')">
  <div class="modal-dialog" style="max-width: 520px; width: 95%;">
    <div class="modal-header">
      <span class="modal-title" id="bpModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">
        <i class="fa-solid fa-qrcode text-primary me-2"></i> Submit Electronic Boarding Pass
      </span>
      <button class="modal-close" onclick="closeModal('modalUploadBoardingPass')">&times;</button>
    </div>
    <form onsubmit="handleBoardingPassUploadSubmit(event)">
      <input type="hidden" id="bpTargetTicketId">
      <div class="modal-body" style="font-size: 12px; color: var(--text); display: flex; flex-direction: column; gap: 12px;">
        
        <div id="bpTicketInfoBox" style="background: var(--surface2); padding: 12px; border-radius: 6px; border: 1px solid var(--border); line-height: 1.5;">
          <!-- Dynamically filled -->
        </div>

        <div>
          <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">
            Upload Boarding Pass (PDF stub / Airport Mobile Screenshot) *
          </label>
          <input type="file" id="bpFileScan" required style="width: 100%; padding: 8px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Actual Flight Date Taken *</label>
            <input type="date" id="bpFlightDateActual" required value="2026-03-08" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
          <div>
            <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted);">Seat Number (Optional)</label>
            <input type="text" id="bpSeatNumber" placeholder="e.g. 14C" style="width: 100%; height: 36px; padding: 0 10px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px 12px; font-size: 11px; color: var(--success); line-height: 1.4;">
          <i class="fa-solid fa-circle-check me-1"></i> Submitting this boarding pass automatically reconciles the ticket as <strong>Utilized</strong> and opens the vendor payment escrow gate for DOF clearance.
        </div>

      </div>
      <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalUploadBoardingPass')">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 700;">Verify &amp; Clear Escrow Gate</button>
      </div>
    </form>
  </div>
</div>

<script>
// 1. Staff Flight Requests Data
var TRAVEL_REQUESTS_DATA = [
  { ref: 'TR-108', traveler: 'Amina Bello (State Lead)', dept: 'Programme Delivery', type: 'Individual', origin: 'Abuja (ABV)', dest: 'Kano (KAN)', dates: '06 Mar 2026 - 09 Mar 2026', memo: 'CAP-032_Travel_Memo.pdf', purpose: 'CAP-032 Field Remediation Audit', status: 'Pending Logistics Booking' },
  { ref: 'TR-109', traveler: 'Cluster Surge Team (4 Staff)', dept: 'Clinical Services', type: 'Group Mission', origin: 'Abuja (ABV)', dest: 'Borno (MIU)', dates: '12 Mar 2026 - 18 Mar 2026', memo: 'Borno_Surge_Authorization.pdf', purpose: 'Community Anti-Retroviral Field Surge', status: 'Pending Logistics Booking' },
  { ref: 'TR-107', traveler: 'Chidinma Okoro (Supervisor)', dept: 'Finance & Admin', type: 'Individual', origin: 'Abuja (ABV)', dest: 'Lagos (LOS)', dates: '04 Mar 2026 - 07 Mar 2026', memo: 'Q1_Financial_Audit_Memo.pdf', purpose: 'Q1 Financial Review & Reconciliations', status: 'Ticket Issued (TKT-554)' },
  { ref: 'TR-106', traveler: 'Dr. Biodun Ojo (HOD)', dept: 'Clinical Services', type: 'Individual', origin: 'Abuja (ABV)', dest: 'Rivers (PHC)', dates: '28 Feb 2026 - 03 Mar 2026', memo: 'Rivers_Lab_Certification.pdf', purpose: 'Regional Laboratory Quality Audit', status: 'Ticket Issued (TKT-553)' }
];

// 2. Tickets Ledger Data (Booked Tickets & Utilization)
var TICKETS_DATA = [
  { id: 'TKT-554', pnr: '6Q9ZKR', traveler: 'Chidinma Okoro', route: 'Abuja &rarr; Lagos', airline: 'Air Peace', vendor: 'Wakanow Corporate Travel', cost: 185000, date: '04 Mar 2026', status: 'Open', bpStatus: 'Awaiting Boarding Pass', bpFile: null },
  { id: 'TKT-553', pnr: 'IB8491', traveler: 'Dr. Biodun Ojo', route: 'Abuja &rarr; Rivers', airline: 'Ibom Air', vendor: 'Travelbeta Logistics Ltd', cost: 220000, date: '28 Feb 2026', status: 'Utilized', bpStatus: 'Boarding Pass Verified', bpFile: 'BP_Biodun_Ojo_Rivers.pdf' },
  { id: 'TKT-552', pnr: 'UN3014', traveler: 'Halima Suleiman', route: 'Abuja &rarr; Kaduna', airline: 'United Nigeria', vendor: 'Travelstart Nigeria', cost: 98000, date: '22 Feb 2026', status: 'Utilized', bpStatus: 'Boarding Pass Verified', bpFile: 'BP_Halima_Kaduna.pdf' },
  { id: 'TKT-551', pnr: 'AP9022', traveler: 'Musa Ibrahim (State Coordinator)', route: 'Kano &rarr; Abuja', airline: 'Air Peace', vendor: 'Wakanow Corporate Travel', cost: 175000, date: '18 Feb 2026', status: 'Utilized', bpStatus: 'Boarding Pass Verified', bpFile: 'BP_Musa_Abuja.pdf' },
  { id: 'TKT-550', pnr: 'OV5512', traveler: 'Kelechi Madu & 2 Field Nurses (Group)', route: 'Abuja &rarr; Borno', airline: 'Overland Airways', vendor: 'Finchglow Travels', cost: 420000, date: '14 Feb 2026', status: 'Utilized', bpStatus: 'Boarding Pass Verified', bpFile: 'BP_Group_Borno_Mission.pdf' },
  { id: 'TKT-549', pnr: 'MX7120', traveler: 'Fatima Bakura', route: 'Abuja &rarr; Kano', airline: 'Max Air', vendor: 'Wakanow Corporate Travel', cost: 160000, date: '01 Mar 2026', status: 'Open', bpStatus: 'Awaiting Boarding Pass', bpFile: null }
];

// 3. Vendor Payments Batches
var VENDOR_PAYMENTS_DATA = [
  { invoiceRef: 'INV-WK-091', vendor: 'Wakanow Corporate Travel', tickets: 'TKT-554, TKT-549', amount: 345000, bpReconciled: '0/2 Passes (0%)', gate: 'Locked', status: 'Withheld at Escrow Gate' },
  { invoiceRef: 'INV-TB-090', vendor: 'Travelbeta Logistics Ltd', tickets: 'TKT-553', amount: 220000, bpReconciled: '1/1 Passes (100%)', gate: 'Open', status: 'Cleared for DOF Payment' },
  { invoiceRef: 'INV-TS-089', vendor: 'Travelstart Nigeria', tickets: 'TKT-552', amount: 98000, bpReconciled: '1/1 Passes (100%)', gate: 'Open', status: 'Paid & Disbursed' },
  { invoiceRef: 'INV-FG-088', vendor: 'Finchglow Travels', tickets: 'TKT-550', amount: 420000, bpReconciled: '3/3 Passes (100%)', gate: 'Open', status: 'Paid & Disbursed' }
];

function updateTravelModuleStats() {
  var totalBooked = TICKETS_DATA.length;
  var utilized = TICKETS_DATA.filter(function(t){ return t.status === 'Utilized'; }).length;
  var openTickets = TICKETS_DATA.filter(function(t){ return t.status === 'Open'; }).length;

  var outstanding = 0;
  VENDOR_PAYMENTS_DATA.forEach(function(v){
    if (v.status !== 'Paid & Disbursed') {
      outstanding += v.amount;
    }
  });

  var elBooked = document.getElementById('statTotalTickets');
  var elUtilized = document.getElementById('statUtilizedTickets');
  var elOpen = document.getElementById('statOpenTickets');
  var elOut = document.getElementById('statOutstandingPayment');

  if (elBooked) elBooked.textContent = totalBooked;
  if (elUtilized) elUtilized.textContent = utilized;
  if (elOpen) elOpen.textContent = openTickets;
  if (elOut) elOut.textContent = '&#8358;' + outstanding.toLocaleString();
}

function renderTravelRequestsTable() {
  var tbody = document.getElementById('travelRequestsTableBody');
  if (!tbody) return;

  tbody.innerHTML = TRAVEL_REQUESTS_DATA.map(function(r) {
    var typeBadge = r.type === 'Group Mission' ? '<span class="badge" style="background:#ede9fe; color:#7c3aed; font-weight:700;"><i class="fa-solid fa-users me-1"></i>Group</span>' : '<span class="badge" style="background:#e0f2fe; color:#0369a1; font-weight:600;"><i class="fa-solid fa-user me-1"></i>Individual</span>';
    var statBadge = r.status.includes('Pending') ? '<span class="pill pill-progress">' + r.status + '</span>' : '<span class="pill pill-closed">' + r.status + '</span>';

    var actionBtn = r.status.includes('Pending')
      ? '<button class="btn btn-primary btn-sm" onclick="sendRequestToLogisticsBooking(\'' + r.ref + '\', \'' + r.traveler.replace(/'/g, "\\'") + '\', \'' + r.origin + ' &rarr; ' + r.dest + '\')" style="font-size: 11px; padding: 3px 8px; font-weight: 700;"><i class="fa-solid fa-ticket me-1"></i> Book Ticket</button>'
      : '<span style="font-size: 11px; color: var(--success); font-weight: 700;"><i class="fa-solid fa-check-double me-1"></i>Booked</span>';

    return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
      '<td style="padding: 10px 8px; white-space: nowrap;"><strong style="color: var(--accent); font-family: monospace; font-size: 12px;">' + r.ref + '</strong></td>' +
      '<td style="padding: 10px 8px; font-weight: 700; font-size: 12px;">' + r.traveler + '<div style="font-size: 10px; color: var(--text-muted); font-weight: 400; margin-top: 2px;">' + r.dept + '</div></td>' +
      '<td style="padding: 10px 8px;">' + typeBadge + '</td>' +
      '<td style="padding: 10px 8px; font-size: 11px;"><strong>' + r.origin + ' &rarr; ' + r.dest + '</strong><div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">' + r.dates + '</div></td>' +
      '<td style="padding: 10px 8px;"><button class="btn btn-outline btn-sm" onclick="alert(\'Inspecting attached document:\\n' + r.memo + '\\n\\nAuthorization memo signed & approved.\')" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-paperclip me-1 text-primary"></i>' + r.memo + '</button></td>' +
      '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + statBadge + '</td>' +
      '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + actionBtn + '</td>' +
    '</tr>';
  }).join('');
}

function renderTicketsLedger(items) {
  var tbody = document.getElementById('ticketLedgerBody');
  if (!tbody) return;

  var list = items || TICKETS_DATA;
  tbody.innerHTML = list.map(function(t) {
    var isUtilized = t.status === 'Utilized';
    var utilPill = isUtilized ? '<span class="pill pill-closed">&#x2713; Utilized</span>' : '<span class="pill pill-open">Unused / Open</span>';
    
    // Direct Online Boarding Pass upload under each ticket
    var actionHtml = '';
    if (isUtilized) {
      actionHtml = '<div style="display:flex; align-items:center; justify-content:center; gap:4px;">' +
        '<span style="font-size: 11px; color: var(--success); font-weight: 700;"><i class="fa-solid fa-circle-check me-1"></i>Pass Verified</span>' +
        '<button class="btn btn-outline btn-sm" onclick="alert(\'Viewing uploaded boarding pass proof:\\n' + t.bpFile + '\')" style="font-size: 10px; padding: 2px 5px;"><i class="fa-solid fa-eye"></i></button>' +
      '</div>';
    } else {
      actionHtml = '<button class="btn btn-outline btn-sm" onclick="openBoardingPassModal(\'' + t.id + '\', \'' + t.traveler.replace(/'/g, "\\'") + '\', \'' + t.pnr + '\', \'' + t.airline + '\')" style="font-size: 11px; padding: 3px 8px; color: var(--accent); font-weight: 700;">' +
        '<i class="fa-solid fa-upload me-1"></i> Upload Boarding Pass' +
      '</button>';
    }

    return '<tr id="row-' + t.id + '" style="border-bottom: 1px solid #f1f5f9;">' +
      '<td style="padding: 10px 8px; white-space: nowrap;"><strong style="color: var(--accent); font-family: monospace; font-size: 12px;">' + t.id + '</strong></td>' +
      '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: #7c3aed; font-size: 11px;">' + t.pnr + '</td>' +
      '<td style="padding: 10px 8px; font-weight: 700; font-size: 12px;">' + t.traveler + '</td>' +
      '<td style="padding: 10px 8px; font-size: 11px;">' + t.route + '<div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">' + t.airline + ' (' + t.vendor + ')</div></td>' +
      '<td style="padding: 10px 8px; text-align: center; font-size: 11px; color: var(--text-muted);">' + t.date + '</td>' +
      '<td style="padding: 10px 8px; text-align: center; font-weight: 700;">&#8358;' + t.cost.toLocaleString() + '</td>' +
      '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + utilPill + '</td>' +
      '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + actionHtml + '</td>' +
    '</tr>';
  }).join('');
}

function renderVendorPaymentsTable() {
  var tbody = document.getElementById('vendorPaymentsTableBody');
  if (!tbody) return;

  tbody.innerHTML = VENDOR_PAYMENTS_DATA.map(function(v) {
    var isGateOpen = v.gate === 'Open';
    var isPaid = v.status === 'Paid & Disbursed';

    var gateBadge = isGateOpen
      ? '<span class="pill pill-closed" style="font-size: 10px;"><i class="fa-solid fa-lock-open me-1"></i>Gate Cleared (100% Passes)</span>'
      : '<span class="pill pill-open" style="font-size: 10px;"><i class="fa-solid fa-lock me-1"></i>Withheld at Escrow Gate</span>';

    var statBadge = isPaid
      ? '<span class="pill pill-closed">Paid &amp; Disbursed</span>'
      : isGateOpen
      ? '<span class="pill pill-progress" style="background:#e0f2fe; color:#0369a1;">Cleared for DOF Release</span>'
      : '<span class="pill pill-open">Escrow Locked</span>';

    var actionHtml = isPaid
      ? '<span style="font-size: 11px; color: var(--success); font-weight: 700;"><i class="fa-solid fa-circle-check me-1"></i>Completed</span>'
      : isGateOpen
      ? '<button class="btn btn-primary btn-sm" onclick="releaseDofPayment(\'' + v.invoiceRef + '\', \'' + v.vendor + '\', ' + v.amount + ')" style="font-size: 10px; padding: 3px 7px; font-weight: 700;"><i class="fa-solid fa-paper-plane me-1"></i> Release &#8358;</button>'
      : (window.CURRENT_USER_ROLE === 'superadmin' ? '<button class="btn btn-sm" onclick="superAdminForceClearEscrow(\'' + v.invoiceRef + '\')" style="background: #ef4444; color: #fff; font-size: 10px; padding: 3px 7px; border: none; font-weight: 700;" title="Super Admin Unilateral Escrow Release"><i class="fa-solid fa-bolt me-1"></i> Root Clear</button>' : '<button class="btn btn-outline btn-sm" disabled style="opacity: 0.5; font-size: 10px; padding: 3px 7px;" title="Passes must be uploaded under tickets first"><i class="fa-solid fa-lock me-1"></i> Withheld</button>');

    return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
      '<td style="padding: 10px 8px; font-family: monospace; font-weight: 700; color: var(--accent); font-size: 12px;">' + v.invoiceRef + '</td>' +
      '<td style="padding: 10px 8px; font-weight: 700; font-size: 12px;">' + v.vendor + '</td>' +
      '<td style="padding: 10px 8px; font-size: 11px; color: var(--text-dim);">' + v.tickets + '<div style="font-size: 10px; color: var(--text-muted);">' + v.bpReconciled + '</div></td>' +
      '<td style="padding: 10px 8px; text-align: center; font-weight: 800;">&#8358;' + v.amount.toLocaleString() + '</td>' +
      '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + gateBadge + '</td>' +
      '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + statBadge + '</td>' +
      '<td style="padding: 10px 8px; text-align: center; white-space: nowrap;">' + actionHtml + '</td>' +
    '</tr>';
  }).join('');
}

function switchTravelTab(tab) {
  ['Req', 'Issue', 'Track', 'Pay'].forEach(function(t) {
    var btn = document.getElementById('tTab' + t);
    if (btn) {
      btn.style.background = 'var(--surface)';
      btn.style.color = 'var(--text-dim)';
      btn.classList.remove('active');
    }
  });

  document.getElementById('tPanelReq').style.display = 'none';
  document.getElementById('tPanelIssue').style.display = 'none';
  document.getElementById('tPanelTrack').style.display = 'none';
  document.getElementById('tPanelPay').style.display = 'none';

  if (tab === 'request') {
    document.getElementById('tTabReq').style.background = 'var(--accent)';
    document.getElementById('tTabReq').style.color = '#fff';
    document.getElementById('tTabReq').classList.add('active');
    document.getElementById('tPanelReq').style.display = 'block';
  } else if (tab === 'purchase') {
    document.getElementById('tTabIssue').style.background = 'var(--accent)';
    document.getElementById('tTabIssue').style.color = '#fff';
    document.getElementById('tTabIssue').classList.add('active');
    document.getElementById('tPanelIssue').style.display = 'block';
  } else if (tab === 'tracking') {
    document.getElementById('tTabTrack').style.background = 'var(--accent)';
    document.getElementById('tTabTrack').style.color = '#fff';
    document.getElementById('tTabTrack').classList.add('active');
    document.getElementById('tPanelTrack').style.display = 'block';
  } else if (tab === 'payments') {
    document.getElementById('tTabPay').style.background = 'var(--accent)';
    document.getElementById('tTabPay').style.color = '#fff';
    document.getElementById('tTabPay').classList.add('active');
    document.getElementById('tPanelPay').style.display = 'block';
  }
}

function toggleGroupTripFields(isGroup) {
  var box = document.getElementById('groupTripExtraContainer');
  var lbl = document.getElementById('lblTravelerName');
  if (box) box.style.display = isGroup ? 'block' : 'none';
  if (lbl) lbl.textContent = isGroup ? 'Group Mission Lead / Team Coordinator *' : 'Lead Traveler Name *';
}

function handleFlightRequestSubmit(e) {
  e.preventDefault();
  var traveler = document.getElementById('reqTravelerInput').value;
  var dept = document.getElementById('reqDeptInput').value;
  var orig = document.getElementById('reqOriginInput').value;
  var dest = document.getElementById('reqDestInput').value;
  var depDate = document.getElementById('reqDepDateInput').value;
  var retDate = document.getElementById('reqRetDateInput').value;
  var purpose = document.getElementById('reqPurposeInput').value;
  var isGroup = document.querySelector('input[name="tripBookingType"]:checked').value === 'group';
  var groupManifest = isGroup ? document.getElementById('reqGroupManifestInput').value : '';

  var newRef = 'TR-' + (110 + TRAVEL_REQUESTS_DATA.length);
  var travelerLabel = isGroup ? traveler + ' (Group Mission)' : traveler;

  TRAVEL_REQUESTS_DATA.unshift({
    ref: newRef,
    traveler: travelerLabel,
    dept: dept,
    type: isGroup ? 'Group Mission' : 'Individual',
    origin: orig,
    dest: dest,
    dates: depDate + (retDate ? ' - ' + retDate : ' (One-Way)'),
    memo: isGroup ? 'Group_Mission_Authorization.pdf' : 'Travel_Authorization_Memo.pdf',
    purpose: purpose,
    status: 'Pending Logistics Booking'
  });

  alert('Flight Ticket Request ' + newRef + ' submitted successfully!\n\nLogged into Logistics Booking Desk with attached Travel Authorization Documentation.');
  closeModal('modalNewFlightRequest');
  renderTravelRequestsTable();
}

function sendRequestToLogisticsBooking(ref, traveler, route) {
  switchTravelTab('purchase');
  var sel = document.getElementById('ticketTravelRef');
  if (sel) {
    // Add option if not present
    var optExists = false;
    for (var i = 0; i < sel.options.length; i++) {
      if (sel.options[i].value === ref) { optExists = true; break; }
    }
    if (!optExists) {
      var opt = document.createElement('option');
      opt.value = ref;
      opt.textContent = ref + ' — ' + traveler + ' (' + route + ')';
      sel.appendChild(opt);
    }
    sel.value = ref;
  }
  document.getElementById('ticketTraveller').value = traveler;
  document.getElementById('ticketRoute').value = route;
  document.getElementById('ticketCost').value = '195000';
  document.getElementById('ticketPnr').value = '6Q' + Math.floor(1000 + Math.random() * 9000);
  document.getElementById('ticketNumberInput').value = '072-' + Math.floor(1000000000 + Math.random() * 9000000000);
}

function autoPopulateLogisticsBooking(ref) {
  var item = TRAVEL_REQUESTS_DATA.find(function(r){ return r.ref === ref; });
  if (item) {
    document.getElementById('ticketTraveller').value = item.traveler;
    document.getElementById('ticketRoute').value = item.origin + ' → ' + item.dest;
    document.getElementById('ticketCost').value = item.type === 'Group Mission' ? '780000' : '195000';
    document.getElementById('ticketPnr').value = '6Q' + Math.floor(1000 + Math.random() * 9000);
    document.getElementById('ticketNumberInput').value = '072-' + Math.floor(1000000000 + Math.random() * 9000000000);
  }
}

function handleLogisticsTicketIssue(e) {
  e.preventDefault();
  var ref = document.getElementById('ticketTravelRef').value;
  var traveler = document.getElementById('ticketTraveller').value;
  var route = document.getElementById('ticketRoute').value;
  var airline = document.getElementById('ticketAirline').value;
  var pnr = document.getElementById('ticketPnr').value;
  var tktNum = document.getElementById('ticketNumberInput').value;
  var vendor = document.getElementById('ticketVendor').value;
  var cost = parseInt(document.getElementById('ticketCost').value) || 195000;
  var date = document.getElementById('ticketDate').value;

  var newTktId = 'TKT-' + (555 + TICKETS_DATA.length);

  // Add into system
  TICKETS_DATA.unshift({
    id: newTktId,
    pnr: pnr,
    traveler: traveler,
    route: route,
    airline: airline,
    vendor: vendor,
    cost: cost,
    date: date,
    status: 'Open',
    bpStatus: 'Awaiting Boarding Pass',
    bpFile: null
  });

  // Update originating request status
  var reqItem = TRAVEL_REQUESTS_DATA.find(function(r){ return r.ref === ref; });
  if (reqItem) {
    reqItem.status = 'Ticket Issued (' + newTktId + ')';
  }

  alert('Ticket ' + newTktId + ' (PNR: ' + pnr + ') logged into system successfully!\n\nMarked as Unused/Open. Boarding pass submission is now enabled under this ticket.');
  updateTravelModuleStats();
  renderTravelRequestsTable();
  renderTicketsLedger();
  switchTravelTab('tracking');
}

function openBoardingPassModal(tktId, traveler, pnr, airline) {
  document.getElementById('bpTargetTicketId').value = tktId;
  document.getElementById('bpModalTitle').innerHTML = '<i class="fa-solid fa-qrcode text-primary me-2"></i> Submit Boarding Pass for ' + tktId;
  
  var infoBox = document.getElementById('bpTicketInfoBox');
  if (infoBox) {
    infoBox.innerHTML = '<div><strong>Ticket ID:</strong> ' + tktId + ' &bull; <strong>PNR:</strong> ' + pnr + '</div>' +
      '<div><strong>Passenger:</strong> ' + traveler + '</div>' +
      '<div><strong>Airline:</strong> ' + airline + '</div>' +
      '<div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Upload physical boarding pass scan or digital QR screenshot.</div>';
  }

  openModal('modalUploadBoardingPass');
}

function handleBoardingPassUploadSubmit(e) {
  e.preventDefault();
  var tktId = document.getElementById('bpTargetTicketId').value;
  var tkt = TICKETS_DATA.find(function(x){ return x.id === tktId; });
  if (tkt) {
    tkt.status = 'Utilized';
    tkt.bpStatus = 'Boarding Pass Verified';
    tkt.bpFile = 'BP_' + tktId + '_Verified.pdf';

    // Check if vendor batch can be unlocked
    var vBatch = VENDOR_PAYMENTS_DATA.find(function(v){ return v.vendor === tkt.vendor; });
    if (vBatch) {
      vBatch.gate = 'Open';
      vBatch.status = 'Cleared for DOF Payment';
      vBatch.bpReconciled = 'Reconciled (100% Passes)';
    }

    alert('Boarding Pass for ' + tktId + ' verified online!\n\nTicket status updated to UTILIZED.\nPOL-TRV-03 Escrow Gate cleared for vendor payment clearance by DOF.');
    closeModal('modalUploadBoardingPass');
    updateTravelModuleStats();
    renderTicketsLedger();
    renderVendorPaymentsTable();
  }
}

function filterTicketsTable() {
  var filterStatus = (document.getElementById('filterTicketUtilization') || {}).value || '';
  var q = ((document.getElementById('searchTicketInput') || {}).value || '').toLowerCase();

  var filtered = TICKETS_DATA.filter(function(t) {
    var matchStatus = !filterStatus || t.status === filterStatus;
    var matchQ = !q || t.id.toLowerCase().includes(q) || t.pnr.toLowerCase().includes(q) || t.traveler.toLowerCase().includes(q) || t.route.toLowerCase().includes(q);
    return matchStatus && matchQ;
  });

  renderTicketsLedger(filtered);
}

function superAdminForceClearEscrow(invRef) {
  if (confirm('SUPREME AUTHORITY OVERRIDE:\n\nUnilaterally clear POL-TRV-03 Escrow Gate for invoice ' + invRef + ' without boarding pass verification?')) {
    var v = VENDOR_PAYMENTS_DATA.find(function(x){ return x.invoiceRef === invRef; });
    if (v) {
      v.gate = 'Open';
      v.status = 'Cleared by Super Admin Override';
      v.bpReconciled = 'Bypassed (Root Authority)';
      renderVendorPaymentsTable();
      updateTravelModuleStats();
      alert('Invoice ' + invRef + ' unilaterally cleared for payment disbursement by Super Administrator.');
    }
  }
}

function releaseDofPayment(invRef, vendor, amount) {
  alert('DOF Payment Authorization Released:\n\nInvoice: ' + invRef + '\nVendor: ' + vendor + '\nAmount: &#8358;' + amount.toLocaleString() + '\n\nBoarding pass reconciliation audited and cleared for bank transfer.');
  var v = VENDOR_PAYMENTS_DATA.find(function(x){ return x.invoiceRef === invRef; });
  if (v) {
    v.status = 'Paid & Disbursed';
    renderVendorPaymentsTable();
    updateTravelModuleStats();
  }
}

function exportTravelComplianceReport(format) {
  alert('Compiling Travel, Ticket Utilization & Vendor Payment Audit Dossier (' + format.toUpperCase() + ')...\n\nReport generated for Logistics Department & DOF Validation.');
}

window.initTravelModule = function() {
  var role = window.CURRENT_USER_ROLE || 'staff';
  var ind = document.getElementById('travelRoleIndicator');

  if (ind) {
    ind.innerHTML = '<div style="margin-top: 6px; padding: 6px 12px; background: rgba(2,54,123,0.06); border-left: 4px solid var(--accent); border-radius: 6px; font-size: 11px; color: var(--accent); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">' +
      '<div style="display: flex; align-items: center; gap: 8px;">' +
        '<i class="fa-solid fa-plane-circle-check" style="font-size: 13px;"></i>' +
        '<div><strong>Compliance Oversight:</strong> Direct online boarding pass reconciliation under each ticket booked ensures vendor payments are approved strictly after physical travel validation.</div>' +
      '</div>' +
      '<span style="font-size: 10px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; border: 1px solid #bae6fd;">POL-TRV-03 GATE</span>' +
    '</div>';
  }

  updateTravelModuleStats();
  renderTravelRequestsTable();
  renderTicketsLedger();
  renderVendorPaymentsTable();
};

document.addEventListener('DOMContentLoaded', function() {
  window.initTravelModule();
});
</script>
@endsection
