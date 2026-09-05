@extends('layouts.app')
@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="capModuleContainer">

  <!-- Header -->
  <div style="margin-bottom: 18px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
    <div>
      <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0 0 4px;">
        Corrective Action Plans (CAP)
      </h2>
      <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px;">
        Institutional remediation register, evidence verification, and state office compliance tracking
      </p>
      <div id="capRoleIndicator"></div>
    </div>
    <div style="display: flex; gap: 8px;">
      <button class="btn btn-outline btn-sm" onclick="filterCapTable('all')" style="font-size: 11px;">
        <i class="fa-solid fa-list-check"></i> All CAPs
      </button>
      <button class="btn btn-primary btn-sm" id="btnCreateCap" onclick="openModal('modalCreateCap')" style="font-size: 11px; font-weight: 700;">
        <i class="fa-solid fa-plus"></i> Create CAP
      </button>
    </div>
  </div>

  <!-- 4 Stat Tiles -->
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px;">
    <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #991b1b; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Open CAPs</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #dc2626; line-height: 1;" id="capStatOpen">0</div>
      <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Requiring action</div>
    </div>
    <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #92400e; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">In Progress</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #d97706; line-height: 1;" id="capStatProgress">0</div>
      <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Being remediated</div>
    </div>
    <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #0369a1; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Evidence Submitted</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #0284c7; line-height: 1;" id="capStatEvid">0</div>
      <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Awaiting verification</div>
    </div>
    <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 14px; text-align: center;">
      <div style="font-size: 10px; color: #065f46; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px;">Closed</div>
      <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: #059669; line-height: 1;" id="capStatClosed">0</div>
      <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Resolved & verified</div>
    </div>
  </div>

  <!-- FULL WIDTH CAP LEDGER (NO HORIZONTAL SCROLL) -->
  <div class="card" style="margin-bottom: 22px; padding: 18px 20px; overflow: hidden; width: 100%; box-sizing: border-box;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--surface2); flex-wrap: wrap; gap: 10px;">
      <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-check" style="color: var(--success);"></i> Corrective Action Plan Summary Ledger
      </div>
      <div style="display: flex; align-items: center; gap: 8px;">
        <input type="text" id="capSearchInput" onkeyup="searchCapTable()" placeholder="Search CAPs, states, issues..." style="padding: 5px 10px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; width: 220px; background: var(--surface2); color: var(--text);">
      </div>
    </div>

    <!-- Table fits 100% with zero horizontal scrollbar -->
    <div style="width: 100%; overflow: hidden;">
      <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 12px;">
        <thead>
          <tr style="background: var(--surface2); border-bottom: 1px solid var(--border);">
            <th style="width: 90px; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">CAP ID</th>
            <th style="padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Issue / Remediation Finding</th>
            <th style="width: 90px; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">State</th>
            <th style="width: 100px; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Linked Ref</th>
            <th style="width: 100px; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Deadline</th>
            <th style="width: 85px; padding: 10px 12px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Priority</th>
            <th style="width: 130px; padding: 10px 12px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Status</th>
            <th style="width: 80px; padding: 10px 12px; text-align: center; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Actions</th>
          </tr>
        </thead>
        <tbody id="capTableBody">
          <!-- Dynamic rendered rows -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- 2 CARDS BELOW: EVIDENCE REVIEW QUEUE & REMEDIATION PORTAL -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; width: 100%; box-sizing: border-box;">
    
    <!-- LEFT: Evidence Review Queue -->
    <div class="card" style="margin-bottom: 0; padding: 18px 20px;">
      <div class="card-header" style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--surface2); display: flex; justify-content: space-between; align-items: center;">
        <div class="card-title" style="margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-paperclip" style="color: var(--accent);"></i> Evidence Review Queue
        </div>
        <span class="badge" id="capEvidenceQueueBadge" style="background: var(--accent); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 10px;">0 Pending</span>
      </div>
      <p style="font-size: 11px; color: var(--text-muted); margin: 0 0 14px; line-height: 1.4;">
        State teams submit proof documents. Review, accept, or request further remediation before closing the action plan.
      </p>

      <div style="display: flex; flex-direction: column; gap: 10px;" id="evidenceReviewQueue">
        <div style="text-align: center; padding: 24px 12px; color: var(--text-muted); font-size: 12px; background: var(--surface2); border: 1px dashed var(--border); border-radius: 8px;">
          <i class="fa-solid fa-folder-open" style="font-size: 22px; color: #94a3b8; margin-bottom: 6px; display: block;"></i>
          No evidence files currently pending review.
        </div>
      </div>
    </div>

    <!-- RIGHT: State Remediation Submission (Or HR Remediation Summary) -->
    <div class="card" style="margin-bottom: 0; padding: 18px 20px;" id="capRightCard">
      <!-- Injected dynamically based on role -->
    </div>

  </div>

</div>

<!-- VIEW CAP DETAILS MODAL (HR View-Only Inspector) -->
<div class="modal-overlay" id="modalViewCap" style="display: none;" onclick="if(event.target===this)closeModal('modalViewCap')">
  <div class="modal-dialog" style="max-width: 540px; width: 95%;">
    <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <span class="modal-title" id="viewCapModalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 15px;">CAP Record</span>
        <span id="viewCapPrioBadge"></span>
      </div>
      <button class="modal-close" onclick="closeModal('modalViewCap')">&times;</button>
    </div>
    <div class="modal-body" style="padding: 16px 0; font-size: 12px; color: var(--text);" id="viewCapModalBody">
      <!-- Dynamic Content -->
    </div>
    <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-shield-halved"></i> HR View-Only Record</span>
      <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalViewCap')">Close</button>
    </div>
  </div>
</div>

<!-- CREATE CAP MODAL (Restricted from HR) -->
<div class="modal-overlay" id="modalCreateCap">
  <div class="modal-dialog" style="max-width: 540px;">
    <div class="modal-header">
      <div class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Create Corrective Action Plan (CAP)</div>
      <button class="modal-close" onclick="closeModal('modalCreateCap')">&#x2715;</button>
    </div>
    <form onsubmit="handleCreateCapSubmit(event)">
      <div class="modal-body">
        <div style="margin-bottom: 12px;">
          <label style="font-size: 11px; font-weight: 700; margin-bottom: 4px; display: block;">Issue / Finding Summary *</label>
          <input type="text" id="capIssueInput" required placeholder="e.g. Field procurement dual-authorization bypass" style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
          <div>
            <label style="font-size: 11px; font-weight: 700; margin-bottom: 4px; display: block;">State *</label>
            <select id="capStateInput" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
              <option>Lagos</option><option>Kano</option><option>Rivers</option><option>Abuja FCT</option><option>Kaduna</option><option>Borno</option>
            </select>
          </div>
          <div>
            <label style="font-size: 11px; font-weight: 700; margin-bottom: 4px; display: block;">Priority *</label>
            <select id="capPriorityInput" style="width: 100%; height: 36px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
              <option>Critical</option><option>High</option><option>Medium</option><option>Low</option>
            </select>
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
          <div>
            <label style="font-size: 11px; font-weight: 700; margin-bottom: 4px; display: block;">Responsible Lead *</label>
            <input type="text" id="capRespInput" required placeholder="e.g. State Coordinator" style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
          <div>
            <label style="font-size: 11px; font-weight: 700; margin-bottom: 4px; display: block;">Deadline *</label>
            <input type="date" id="capDeadInput" required style="width: 100%; height: 36px; padding: 0 8px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
          </div>
        </div>
        <div>
          <label style="font-size: 11px; font-weight: 700; margin-bottom: 4px; display: block;">Linked Ref (Complaint/Risk/Audit)</label>
          <input type="text" id="capLinkedInput" placeholder="e.g. CMP-048 or RSK-024" style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalCreateCap')">Cancel</button>
        <button type="submit" class="btn btn-primary">Create CAP</button>
      </div>
    </form>
  </div>
</div>

<script>
var CAPS_DATA = [];

function updateCapStats() {
  var open = CAPS_DATA.filter(function(c){ return c.status === 'Open'; }).length;
  var prog = CAPS_DATA.filter(function(c){ return c.status === 'In Progress'; }).length;
  var evid = CAPS_DATA.filter(function(c){ return c.status === 'Evidence Submitted'; }).length;
  var closed = CAPS_DATA.filter(function(c){ return c.status === 'Closed'; }).length;
  var elOpen = document.getElementById('capStatOpen');
  var elProg = document.getElementById('capStatProgress');
  var elEvid = document.getElementById('capStatEvid');
  var elClosed = document.getElementById('capStatClosed');
  if (elOpen) elOpen.textContent = open;
  if (elProg) elProg.textContent = prog;
  if (elEvid) elEvid.textContent = evid;
  if (elClosed) elClosed.textContent = closed;
}

function prioColor(p) {
  if (p === 'Critical') return 'var(--danger)';
  if (p === 'High') return 'var(--warning)';
  return 'var(--text-muted)';
}

function renderEvidenceQueue() {
  var queueEl = document.getElementById('evidenceReviewQueue');
  var badgeEl = document.getElementById('capEvidenceQueueBadge');
  if (!queueEl) return;

  var isHr = window.CURRENT_USER_ROLE === 'hr';
  var pendingItems = CAPS_DATA.filter(function(c) {
    return c.status === 'Evidence Submitted' || c.hasEvidence;
  });

  if (badgeEl) {
    badgeEl.innerText = pendingItems.length + ' Pending';
    badgeEl.style.background = pendingItems.length > 0 ? 'var(--danger)' : 'var(--accent)';
  }

  if (pendingItems.length === 0) {
    queueEl.innerHTML = '<div style="text-align: center; padding: 24px 12px; color: var(--text-muted); font-size: 12px; background: var(--surface2); border: 1px dashed var(--border); border-radius: 8px;">' +
      '<i class="fa-solid fa-folder-open" style="font-size: 22px; color: #94a3b8; margin-bottom: 6px; display: block;"></i>' +
      'No evidence files currently pending review.' +
    '</div>';
    return;
  }

  queueEl.innerHTML = pendingItems.map(function(c) {
    var fileName = c.evidenceFileName || (c.id + '_Remediation_Evidence.pdf');
    var isImg = fileName.toLowerCase().endsWith('.jpg') || fileName.toLowerCase().endsWith('.png');
    var iconClass = isImg ? 'fa-file-image' : 'fa-file-pdf';
    var iconColor = isImg ? 'var(--accent)' : 'var(--danger)';

    var actionsHtml = '';
    if (isHr) {
      actionsHtml = '<div style="font-size: 10px; color: var(--text-muted); margin-top: 6px;"><i class="fa-solid fa-lock"></i> Verification reserved for Compliance Directorate</div>';
    } else {
      actionsHtml = '<div class="evidence-action-zone" style="display: flex; gap: 6px; margin-top: 6px;">' +
        '<button class="btn btn-sm btn-evid-approve" style="background: #dcfce7; color: #166534; border: none; padding: 4px 10px; font-size: 10px; border-radius: 6px; font-weight: 700;" onclick="approveCapEvidence(this,\'' + c.id + '\')">' +
          '<i class="fa-solid fa-check"></i> Approve & Close' +
        '</button>' +
        '<button class="btn btn-sm btn-evid-reject" style="background: #fee2e2; color: #991b1b; border: none; padding: 4px 10px; font-size: 10px; border-radius: 6px; font-weight: 700;" onclick="rejectCapEvidence(this,\'' + c.id + '\')">' +
          '<i class="fa-solid fa-xmark"></i> Request More Proof' +
        '</button>' +
      '</div>';
    }

    return '<div style="background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; padding: 12px;">' +
      '<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">' +
        '<div>' +
          '<div style="font-size: 11px; font-weight: 700; color: var(--text);">' +
            '<i class="fa-solid ' + iconClass + '" style="color: ' + iconColor + '; margin-right: 6px;"></i>' + fileName +
          '</div>' +
          '<div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">' + c.id + ' &middot; ' + (c.state || 'National') + ' &middot; Status: Evidence Submitted</div>' +
        '</div>' +
        '<span class="pill pill-progress" style="font-size: 10px;">Pending Review</span>' +
      '</div>' +
      actionsHtml +
    '</div>';
  }).join('');
}

function renderCaps(items) {
  updateCapStats();
  renderEvidenceQueue();
  var tbody = document.getElementById('capTableBody');
  if (!tbody) return;

  var isHr = window.CURRENT_USER_ROLE === 'hr';
  var isSuperAdmin = window.CURRENT_USER_ROLE === 'superadmin';
  var list = items || CAPS_DATA;

  if (list.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12.5px;">' +
      '<div style="margin-bottom: 8px;"><i class="fa-solid fa-clipboard-check" style="font-size: 28px; color: #cbd5e1;"></i></div>' +
      '<strong>No Corrective Action Plans currently active.</strong>' +
      '<div style="font-size: 11px; margin-top: 4px; color: var(--text-dim);">' +
        (isHr ? 'CAP remediation dossiers are tracked once created by Compliance or converted from complaints.' : 'Click \'+ Create CAP\' to register a new corrective action or convert from an incident.') +
      '</div>' +
    '</td></tr>';
    return;
  }

  tbody.innerHTML = list.map(function(c) {
    var statPill = c.status === 'Open' ? '<span class="pill pill-open">Open</span>' :
      c.status === 'In Progress' ? '<span class="pill pill-progress">In Progress</span>' :
      c.status === 'Evidence Submitted' ? '<span class="pill" style="background: #dbeafe; color: var(--accent);">Evidence Submitted</span>' :
      '<span class="pill pill-closed">&#x2713; Closed</span>';
    var prioBadge = '<span style="font-size: 11px; font-weight: 700; color: ' + prioColor(c.priority) + ';">' + c.priority + '</span>';
    
    var actionsHtml = '';
    if (isHr) {
      // HR View-Only: Single clean View button
      actionsHtml = '<button class="btn btn-outline btn-sm" onclick="viewCapDetail(\'' + c.id + '\')" title="View CAP" style="font-size: 11px; padding: 2px 8px; color: var(--accent);"><i class="fa-solid fa-eye"></i> View</button>';
    } else if (isSuperAdmin) {
      // Super Admin: View + Update Status + ROOT PURGE (Zero Restrictions)
      actionsHtml = '<div style="display: flex; gap: 4px; justify-content: center;">' +
        '<button class="btn btn-outline btn-sm" onclick="viewCapDetail(\'' + c.id + '\')" title="View CAP" style="padding: 3px 6px; font-size: 11px;"><i class="fa-solid fa-eye"></i></button>' +
        '<button class="btn btn-outline btn-sm" onclick="updateCapStatus(\'' + c.id + '\')" title="Update Status" style="padding: 3px 6px; font-size: 11px; color: var(--accent); border-color: var(--accent);"><i class="fa-solid fa-pen-to-square"></i></button>' +
        '<button class="btn btn-outline btn-sm" onclick="superAdminDeleteCap(\'' + c.id + '\')" title="Root Purge CAP" style="padding: 3px 6px; font-size: 11px; color: #dc2626; border-color: #fca5a5;"><i class="fa-solid fa-trash-can"></i></button>' +
      '</div>';
    } else {
      // DoC / Admin: View + Update Status (No delete)
      actionsHtml = '<div style="display: flex; gap: 4px; justify-content: center;">' +
        '<button class="btn btn-outline btn-sm" onclick="viewCapDetail(\'' + c.id + '\')" title="View CAP" style="padding: 3px 7px;"><i class="fa-solid fa-eye"></i></button>' +
        '<button class="btn btn-outline btn-sm" onclick="updateCapStatus(\'' + c.id + '\')" title="Update Status" style="padding: 3px 7px; color: var(--accent); border-color: var(--accent);"><i class="fa-solid fa-pen-to-square"></i></button>' +
      '</div>';
    }

    return '<tr style="border-bottom: 1px solid #f1f5f9;">' +
      '<td style="color: var(--accent); font-weight: 700; padding: 10px 12px; white-space: nowrap;">' + c.id + '</td>' +
      '<td style="font-size: 12px; padding: 10px 12px; line-height: 1.35; word-break: break-word;">' + c.issue + '</td>' +
      '<td style="padding: 10px 12px; white-space: nowrap;">' + c.state + '</td>' +
      '<td style="font-size: 11px; color: var(--text-muted); padding: 10px 12px; white-space: nowrap;">' + c.linkedRef + '</td>' +
      '<td style="font-size: 11px; padding: 10px 12px; white-space: nowrap;">' + c.deadline + '</td>' +
      '<td style="text-align: center; padding: 10px 12px; white-space: nowrap;">' + prioBadge + '</td>' +
      '<td style="text-align: center; padding: 10px 12px; white-space: nowrap;">' + statPill + '</td>' +
      '<td style="text-align: center; padding: 10px 12px; white-space: nowrap;">' + actionsHtml + '</td>' +
    '</tr>';
  }).join('');
}

function searchCapTable() {
  var q = (document.getElementById('capSearchInput').value || '').toLowerCase();
  var filtered = CAPS_DATA.filter(function(c) {
    return c.id.toLowerCase().includes(q) ||
           c.issue.toLowerCase().includes(q) ||
           c.state.toLowerCase().includes(q) ||
           c.status.toLowerCase().includes(q) ||
           c.linkedRef.toLowerCase().includes(q);
  });
  renderCaps(filtered);
}

function filterCapTable(filter) {
  renderCaps();
}

function viewCapDetail(id) {
  var c = CAPS_DATA.find(function(x){ return x.id === id; });
  if (!c) return;

  var titleEl = document.getElementById('viewCapModalTitle');
  var badgeEl = document.getElementById('viewCapPrioBadge');
  var bodyEl = document.getElementById('viewCapModalBody');

  if (titleEl) titleEl.innerText = c.id + ' — Remediation Record';
  if (badgeEl) {
    badgeEl.innerHTML = '<span class="pill" style="color: ' + prioColor(c.priority) + '; background: var(--surface2); font-weight:700;">' + c.priority + ' Priority</span>';
  }

  if (bodyEl) {
    bodyEl.innerHTML = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; background: var(--surface2); padding: 12px; border-radius: 8px; border: 1px solid var(--border);">' +
      '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">State Office:</strong><div style="font-weight:700; margin-top:2px;">' + c.state + '</div></div>' +
      '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Current Status:</strong><div style="font-weight:700; margin-top:2px; color:var(--accent);">' + c.status + '</div></div>' +
      '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Responsible Lead:</strong><div style="font-weight:700; margin-top:2px;">' + c.responsible + '</div></div>' +
      '<div><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Resolution Deadline:</strong><div style="font-weight:700; margin-top:2px;">' + c.deadline + '</div></div>' +
      '<div style="grid-column: span 2;"><strong style="color:var(--text-muted); font-size:10px; text-transform:uppercase;">Source Link:</strong><div style="font-weight:700; margin-top:2px;">' + c.linkedRef + '</div></div>' +
    '</div>' +
    '<div style="margin-bottom: 14px;">' +
      '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Issue & Root Cause Finding:</strong>' +
      '<div style="padding: 10px 12px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; line-height: 1.4; font-size: 12px; font-weight:600;">' +
        c.issue +
      '</div>' +
    '</div>' +
    '<div style="margin-bottom: 14px;">' +
      '<strong style="display:block; margin-bottom:4px; font-size:11px; text-transform:uppercase; color:var(--text-muted);">Remediation & Corrective Actions:</strong>' +
      '<div style="padding: 10px 12px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; line-height: 1.4; font-size: 12px;">' +
        (c.notes || 'Institutional corrective action in progress per compliance guidelines.') +
      '</div>' +
    '</div>' +
    '<div style="background: rgba(2,54,123,0.06); border-left: 3px solid var(--accent); padding: 8px 12px; border-radius: 4px; font-size: 11px; color: var(--accent);">' +
      '<i class="fa-solid fa-circle-info me-1"></i> <strong>HR View-Only Mode:</strong> Corrective action sign-offs, evidence reviews, and status transitions are governed by the Director of Compliance.' +
    '</div>';
  }

  openModal('modalViewCap');
}

function superAdminDeleteCap(id) {
  if (confirm('SUPREME AUTHORITY OVERRIDE:\n\nAre you sure you want to permanently purge Corrective Action Plan ' + id + '? This action bypasses institutional escrow and deletes the finding.')) {
    CAPS_DATA = CAPS_DATA.filter(function(c) { return c.id !== id; });
    renderCaps();
    alert('CAP ' + id + ' permanently purged by Super Administrator.');
  }
}

function updateCapStatus(id) {
  if (window.CURRENT_USER_ROLE === 'hr') {
    alert('Access Denied: HR has view-only access to Corrective Action Plans and cannot modify status.');
    return;
  }
  var c = CAPS_DATA.find(function(x){ return x.id === id; });
  if (!c) return;
  var statuses = ['Open', 'In Progress', 'Evidence Submitted', 'Closed'];
  var newStat = prompt('Update status for ' + id + ':\n\nCurrent: ' + c.status + '\n\nEnter new status:\n1. Open\n2. In Progress\n3. Evidence Submitted\n4. Closed');
  if (newStat) {
    var n = parseInt(newStat);
    if (n >= 1 && n <= 4) {
      c.status = statuses[n-1];
      renderCaps();
      alert(id + ' status updated to: ' + c.status);
    }
  }
}

function approveCapEvidence(btn, capId) {
  if (window.CURRENT_USER_ROLE === 'hr') {
    alert('Access Denied: HR has view-only access. Remediation evidence verification is reserved for the Director of Compliance.');
    return;
  }
  var c = CAPS_DATA.find(function(x){ return x.id === capId; });
  if (c) {
    c.status = 'Closed';
    btn.closest('div[style]').innerHTML = '<div style="font-size: 11px; font-weight: 700; color: var(--success);"><i class="fa-solid fa-circle-check"></i> Approved & Closed</div>';
    renderCaps();
  }
}

function rejectCapEvidence(btn, capId) {
  if (window.CURRENT_USER_ROLE === 'hr') {
    alert('Access Denied: HR has view-only access.');
    return;
  }
  btn.parentElement.innerHTML = '<span style="font-size: 10px; font-weight: 700; color: var(--danger);"><i class="fa-solid fa-circle-xmark"></i> Rejected — Additional evidence requested</span>';
}

function handleCreateCapSubmit(e) {
  e.preventDefault();
  if (window.CURRENT_USER_ROLE === 'hr') {
    alert('Access Denied: HR has view-only access and cannot create new CAP entries.');
    return;
  }
  var payload = {
    issue: document.getElementById('capIssueInput').value,
    state: document.getElementById('capStateInput').value,
    linkedRef: document.getElementById('capLinkedInput').value || 'MANUAL',
    deadline: document.getElementById('capDeadInput').value,
    responsible: document.getElementById('capRespInput').value,
    priority: document.getElementById('capPriorityInput').value,
    notes: 'Directly logged corrective action plan.'
  };

  fetch('/api/cap/create', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(function(res) { return res.json(); })
  .then(function(result) {
    if (result && result.cap) {
      CAPS_DATA.unshift(result.cap);
      alert('CAP ' + result.cap.id + ' registered and synchronized.');
      closeModal('modalCreateCap');
      renderCaps();
      if (typeof syncTopbarNotificationsFromBackend === 'function') syncTopbarNotificationsFromBackend();
    } else {
      closeModal('modalCreateCap');
      window.initCapModule();
    }
  })
  .catch(function(err) {
    console.error('Error creating CAP:', err);
    closeModal('modalCreateCap');
    renderCaps();
  });
}

function handleCapEvidenceSubmit(e) {
  e.preventDefault();
  if (window.CURRENT_USER_ROLE === 'hr') {
    alert('Access Denied: HR has view-only access.');
    return;
  }
  var capId = document.getElementById('capEvidenceSelect').value;
  var fileInput = document.getElementById('capEvidenceFile');
  var fileName = (fileInput && fileInput.files && fileInput.files[0]) ? fileInput.files[0].name : ('State_Remediation_Evidence_' + capId + '.pdf');

  fetch('/api/cap/submit-evidence', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ capRef: capId, fileName: fileName })
  })
  .then(function(res) { return res.json(); })
  .then(function(res) {
    var item = CAPS_DATA.find(function(c){ return c.id === capId; });
    if (item) item.status = 'Evidence Submitted';
    alert('Evidence submitted for ' + capId + '. Sent to Compliance Directorate for verification.');
    renderCaps();
    if (typeof syncTopbarNotificationsFromBackend === 'function') syncTopbarNotificationsFromBackend();
  })
  .catch(function(err) {
    var item = CAPS_DATA.find(function(c){ return c.id === capId; });
    if (item) item.status = 'Evidence Submitted';
    renderCaps();
  });
}

window.initCapModule = function() {
  var isComplianceOfficer = window.CURRENT_USER_ROLE === 'compliance_officer' || window.CURRENT_USER_ROLE === 'compliance';
  var isDoc = window.CURRENT_USER_ROLE === 'doc' || window.CURRENT_USER_ROLE === 'superadmin';
  var isHr = window.CURRENT_USER_ROLE === 'hr';

  // Create Button: Hidden for HR
  var btnCreate = document.getElementById('btnCreateCap');
  if (btnCreate) {
    btnCreate.style.display = isHr ? 'none' : 'inline-flex';
  }

  // Scope Indicator Banner
  var ind = document.getElementById('capRoleIndicator');
  if (ind) {
    if (isComplianceOfficer) {
      ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: #ecfdf5; color: #065f46; border: 1px solid #bbf7d0; border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-user-shield"></i> <strong>Compliance Specialist:</strong> Full CAP Remediation Oversight, Status Update & Verification &middot; <span style="color:#dc2626; font-weight:700;"><i class="fa-solid fa-ban"></i> No Deletion</span></div>';
    } else if (isDoc) {
      ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: rgba(2,54,123,0.08); color: var(--accent); border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> <strong>Director of Compliance:</strong> Full Governance, Status Update & Evidence Verification</div>';
    } else if (isHr) {
      ind.innerHTML = '<div style="margin-top: 6px; padding: 7px 14px; background: rgba(217, 119, 6, 0.08); border-left: 4px solid var(--warning); border-radius: 6px; font-size: 11px; color: #b45309; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">' +
        '<div style="display: flex; align-items: center; gap: 8px;">' +
          '<i class="fa-solid fa-eye" style="font-size: 13px;"></i>' +
          '<div><strong>HR View-Only Access:</strong> Corrective action plans & remediation milestones are monitored for personnel alignment only.</div>' +
        '</div>' +
        '<span style="font-size: 10px; font-weight: 700; background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 4px; border: 1px solid #fde68a;"><i class="fa-solid fa-lock"></i> VIEW ONLY</span>' +
      '</div>';
    } else {
      ind.innerHTML = '<div style="margin-top: 6px; padding: 5px 12px; background: rgba(5,150,105,0.08); color: var(--success); border-radius: 6px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-eye"></i> <strong>State Team Portal:</strong> View Assigned Action Plans & Upload Evidence</div>';
    }
  }

  // Adjust Evidence Queue buttons for HR
  var evidZones = document.querySelectorAll('.evidence-action-zone');
  evidZones.forEach(function(zone) {
    if (isHr) {
      zone.innerHTML = '<span style="font-size: 10px; color: var(--text-muted);"><i class="fa-solid fa-lock"></i> Verification reserved for Compliance Directorate</span>';
    }
  });

  // Right card for HR: Show Institutional Remediation Overview instead of upload form
  var rightCard = document.getElementById('capRightCard');
  if (rightCard) {
    if (isHr) {
      rightCard.innerHTML = '<div class="card-header" style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--surface2);">' +
        '<div class="card-title" style="margin: 0; font-family: Plus Jakarta Sans, sans-serif; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px;">' +
          '<i class="fa-solid fa-chart-pie" style="color: var(--accent);"></i> Institutional Remediation Overview' +
        '</div>' +
      '</div>' +
      '<div style="display: flex; flex-direction: column; gap: 10px; font-size: 12px;">' +
        '<div style="background: var(--surface2); padding: 10px 12px; border-radius: 6px; display: flex; justify-content: space-between;">' +
          '<span>Target Remediation Rate:</span><strong>100% FY2026</strong>' +
        '</div>' +
        '<div style="background: var(--surface2); padding: 10px 12px; border-radius: 6px; display: flex; justify-content: space-between;">' +
          '<span>Lead Action Departments:</span><strong>Operations, Finance, Clinical</strong>' +
        '</div>' +
        '<div style="background: var(--surface2); padding: 10px 12px; border-radius: 6px; display: flex; justify-content: space-between;">' +
          '<span>Avg Resolution Timeline:</span><strong>14 Days from Audit Finding</strong>' +
        '</div>' +
        '<div style="padding: 10px; background: rgba(2,54,123,0.06); border-radius: 6px; font-size: 11px; color: var(--accent); line-height: 1.4; margin-top: 4px;">' +
          '<i class="fa-solid fa-circle-info me-1"></i> State teams submit evidence directly to the Compliance Directorate. HR maintains view-only audit visibility for staff appraisals and disciplinary alignment.' +
        '</div>' +
      '</div>';
    } else {
      rightCard.innerHTML = '<div class="card-header" style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--surface2);">' +
        '<div class="card-title" style="margin: 0; font-family: Plus Jakarta Sans, sans-serif; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px;">' +
          '<i class="fa-solid fa-cloud-arrow-up" style="color: var(--accent);"></i> Submit State Remediation Evidence' +
        '</div>' +
      '</div>' +
      '<form onsubmit="handleCapEvidenceSubmit(event)" style="display: flex; flex-direction: column; gap: 10px;">' +
        '<div>' +
          '<label style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Select Active CAP:</label>' +
          '<select id="capEvidenceSelect" style="width: 100%; padding: 7px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; margin-top: 3px; background: var(--surface); color: var(--text);">' +
            '<option value="CAP-032">CAP-032 — Field procurement dual-authorization bypass (Kano)</option>' +
            '<option value="CAP-031">CAP-031 — PSEA mandatory training compliance below 70% (Kaduna)</option>' +
            '<option value="CAP-030">CAP-030 — Unreconciled advance logs for January 2026 (Lagos)</option>' +
          '</select>' +
        '</div>' +
        '<div>' +
          '<label style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Upload Proof Document / Voucher:</label>' +
          '<input type="file" id="capEvidenceFile" style="width: 100%; padding: 6px; font-size: 11px; border: 1px solid var(--border); border-radius: 6px; margin-top: 3px; background: var(--surface2); color: var(--text);">' +
        '</div>' +
        '<div>' +
          '<label style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Remediation & Implementation Notes:</label>' +
          '<textarea id="capNotes" rows="2" placeholder="Detail corrective action implemented and verification steps..." required style="width: 100%; padding: 7px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; margin-top: 3px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>' +
        '</div>' +
        '<button type="submit" class="btn btn-primary" style="padding: 8px 14px; font-size: 12px; font-weight: 700;">' +
          '<i class="fa-solid fa-upload me-1"></i> Submit Evidence to DoC' +
        '</button>' +
      '</form>';
    }
  }

  fetch('/api/backend/data')
    .then(function(res) { return res.json(); })
    .then(function(data) {
      if (data && data.caps && Array.isArray(data.caps)) {
        CAPS_DATA = data.caps;
      } else {
        CAPS_DATA = [];
      }
      // Populate Evidence Select Options
      var sel = document.getElementById('capEvidenceSelect');
      if (sel) {
        if (CAPS_DATA.length === 0) {
          sel.innerHTML = '<option value="">No Active CAPs</option>';
        } else {
          sel.innerHTML = CAPS_DATA.map(function(c) {
            return '<option value="' + c.id + '">' + c.id + ' — ' + (c.issue || c.title || 'Finding') + ' (' + (c.state || 'National') + ')</option>';
          }).join('');
        }
      }
      renderCaps();
    })
    .catch(function(e) {
      renderCaps();
    });
};

document.addEventListener('DOMContentLoaded', function(){
  window.initCapModule();
});
</script>
@endsection
