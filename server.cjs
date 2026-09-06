
// ══════════════════════════════════════════════════════════════════
// SHARED BACKEND API ROUTER (BRIDGING STAFF PORTAL & HR DASHBOARD)
// ══════════════════════════════════════════════════════════════════

const dataDir = path.join(__dirname, 'data');
if (!fs.existsSync(dataDir)) {
  try { fs.mkdirSync(dataDir, { recursive: true }); } catch (e) {}
}
const backendStoreFile = path.join(dataDir, 'backend_store.json');

function getBackendStore() {
  try {
    if (fs.existsSync(backendStoreFile)) return JSON.parse(fs.readFileSync(backendStoreFile, 'utf8'));
  } catch (e) {
    console.error('Error reading backend store:', e);
  }
  return { leave_requests: [], attendance_logs: [], complaints: [], caps: [], investigations: [], pdp_records: [], field_work: [], audit_logs: [], registered_officers: {} };
}


function getRegisteredOfficers() {
  const store = getBackendStore();
  return store.registered_officers || {};
}

function saveRegisteredOfficer(role, data) {
  const store = getBackendStore();
  if (!store.registered_officers) store.registered_officers = {};
  store.registered_officers[role] = data;
  saveBackendStore(store);
}

function saveBackendStore(data) {
  const p = backendStoreFile;
  try {
    fs.writeFileSync(p, JSON.stringify(data, null, 2), 'utf8');
  } catch (e) {
    console.error('Error saving backend store:', e);
  }
}

// Audit Log Helper
function logAuditEvent(actor, moduleName, description, txHash = null) {
  const store = getBackendStore();
  if (!store.audit_logs) store.audit_logs = [];
  const hash = txHash || ('0x' + Math.random().toString(16).substr(2, 4) + '..' + Math.random().toString(16).substr(2, 4));
  const newLog = {
    id: 'AUD-' + Date.now(),
    timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
    date: 'Today',
    actor: actor || 'Super Administrator',
    module: moduleName || 'Root Governance',
    description: description || 'System operation executed.',
    hash: hash
  };
  store.audit_logs.unshift(newLog);
  // Keep max 50 logs
  if (store.audit_logs.length > 50) store.audit_logs = store.audit_logs.slice(0, 50);
  saveBackendStore(store);
  return newLog;
}

// Request Helper to parse JSON body
function parseJsonBody(req, callback) {
  let body = '';
  req.on('data', chunk => body += chunk);
  req.on('end', () => {
    try {
      callback(null, body ? JSON.parse(body) : {});
    } catch (e) {
      callback(e, {});
    }
  });
}

const http = require('http');
const fs = require('fs');
const path = require('path');
const querystring = require('querystring');

const root = __dirname;

// Secret Security Tokens for Elevated Roles
const SECURITY_KEYS = {
  doc: { email: 'director@cccrn.org', key: 'DOC-9981', default: 'dashboard' },
  compliance: { email: 'compliance@cccrn.org', key: 'SPEC-8821', default: 'complaints' },
  hr: { email: 'hr@cccrn.org', key: 'HR-7742', default: 'dashboard' },
  state_lead: { email: 'lead@cccrn.org', key: 'LEAD-5531', default: 'training' }
};

const USER_ROLES = {
  'superadmin@cccrn.org': {
    key: 'superadmin',
    name: 'Super Administrator',
    roleBadge: 'SUPER ADMIN',
    avatar: 'SA',
    email: 'superadmin@cccrn.org',
    allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'states', 'policies', 'risk', 'lessons', 'reports', 'ai', 'ai-review', 'investigations', 'travel', 'staff'],
    defaultModule: 'dashboard'
  },
  'director@cccrn.org': {
    key: 'doc',
    name: 'Director of Compliance (DoC)',
    roleBadge: 'ADMIN (DoC)',
    avatar: 'DC',
    email: 'director@cccrn.org',
    allowedModules: ['dashboard', 'leave-attendance',
      'complaints',
      'cap',
      'pdp',
      'training',
      'states', 'policies', 'lessons', 'reports', 'ai', 'ai-review', 'investigations', 'travel'],
    defaultModule: 'dashboard'
  },
  'compliance@cccrn.org': {
    key: 'compliance_officer',
    name: 'Compliance Specialist',
    roleBadge: 'COMPLIANCE SPECIALIST',
    avatar: 'CO',
    email: 'compliance@cccrn.org',
    allowedModules: ['complaints', 'cap', 'training', 'states', 'risk', 'lessons', 'investigations', 'travel'],
    defaultModule: 'complaints'
  },
  'hr@cccrn.org': {
    key: 'hr',
    name: 'HR Manager',
    roleBadge: 'HR ACCESS',
    avatar: 'HR',
    email: 'hr@cccrn.org',
    allowedModules: [
      'dashboard',
      'leave-attendance',
      'complaints',
      'cap',
      'pdp',
      'training',
      'states',
      'policies',
      'lessons',
      'investigations',
      'travel'
    ],
    defaultModule: 'dashboard'
  },
  'lead@cccrn.org': {
    key: 'state_lead',
    name: 'State Team Lead',
    roleBadge: 'STATE LEAD ACCESS',
    avatar: 'SL',
    email: 'lead@cccrn.org',
    allowedModules: ['complaints', 'cap', 'training', 'states', 'lessons', 'travel'],
    defaultModule: 'training'
  },
  'staff@cccrn.org': {
    key: 'staff',
    name: 'Regular Staff Member',
    roleBadge: 'STAFF ACCESS',
    avatar: 'ST',
    email: 'staff@cccrn.org',
    allowedModules: ['staff', 'complaints', 'cap', 'pdp', 'training', 'policies', 'lessons', 'ai'],
    defaultModule: 'staff'
  }
};

let loggedInUser = USER_ROLES['staff@cccrn.org']; // Default

function renderDynamicSidebarHtml(user) {
  const allowed = user.allowedModules;
  const isSuperAdmin = user.key === 'superadmin';
  const isDoc = user.key === 'doc';
  const isHr = user.key === 'hr';

  let navHtml = '';

  // 1. Executive Dashboard (DoC & HR)
  if (allowed.includes('dashboard')) {
    navHtml += `
      <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Executive</div>
      <a href="/dashboard" class="nav-item active" data-panel="dashboard" onclick="switchPanel('dashboard'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--accent); background: rgba(2, 54, 123, 0.08); text-decoration: none; border-left: 3px solid var(--accent); font-weight: 700;">
        <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-table-columns"></i></span>
        <span>Dashboard</span>
      </a>
    `;
  }

  // 2. Workforce & Operations
  navHtml += `
    <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Workforce & Operations</div>
  `;

  if (user.key === 'staff') {
    navHtml += `
      <div class="nav-item-accordion" style="margin-bottom: 2px;">
        <div class="nav-item active" onclick="toggleStaffAccordionMenu()" style="display: flex; align-items: center; justify-content: space-between; padding: 9px 18px; font-size: 13px; color: var(--text-dim); cursor: pointer; border-left: 3px solid var(--accent); background: var(--accent-light); font-weight: 700;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-shield-halved" style="color: #02367B;"></i></span>
            <span>Staff Compliance</span>
            <span class="badge" style="background: #02367B; color: #55E2E9; font-size: 9px; padding: 1px 6px; border-radius: 10px; font-weight: 800;">IQ</span>
          </div>
          <i class="fa-solid fa-chevron-down" id="staffPortalAccordionChevron" style="font-size: 10px; color: var(--text-muted); transition: transform 0.2s ease;"></i>
        </div>
        <div id="staffPortalSubmenu" style="display: block; background: rgba(2, 54, 123, 0.03); padding: 4px 0 6px;">
<a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('leave');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-calendar-check" style="width: 14px; color: var(--text-muted);"></i>
            <span>My Leave</span>
          </a>
          <a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('complaints');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-inbox" style="width: 14px; color: var(--text-muted);"></i>
            <span>Complaints</span>
          </a>
          <a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('cap');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-circle-check" style="width: 14px; color: var(--text-muted);"></i>
            <span>Corrective Action (CAP)</span>
          </a>
          <a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('pdp');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-bullseye" style="width: 14px; color: var(--text-muted);"></i>
            <span>PDP System</span>
          </a>
          <a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('training');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-graduation-cap" style="width: 14px; color: var(--text-muted);"></i>
            <span>Training Academy</span>
          </a>
          <a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('policies');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-file-contract" style="width: 14px; color: var(--text-muted);"></i>
            <span>Policy Management</span>
          </a>
          <a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('lessons');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-book-bookmark" style="width: 14px; color: var(--text-muted);"></i>
            <span>Lessons Learned</span>
          </a>
          <a href="javascript:void(0)" onclick="if(window.switchStaffMainTab){switchStaffMainTab('ai');}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
            <i class="fa-solid fa-robot" style="width: 14px; color: var(--text-muted);"></i>
            <span>AI Staff Helpdesk</span>
          </a>
        </div>
      </div>
      <script>
        function toggleStaffAccordionMenu() {
          var m = document.getElementById('staffPortalSubmenu');
          var c = document.getElementById('staffPortalAccordionChevron');
          if (!m) return;
          if (m.style.display === 'none') {
            m.style.display = 'block';
            if (c) c.style.transform = 'rotate(0deg)';
          } else {
            m.style.display = 'none';
            if (c) c.style.transform = 'rotate(-90deg)';
          }
        }
      </script>
    `;
  }

  if (['hr', 'supervisor', 'hod', 'doc', 'superadmin'].includes(user.key)) {
    navHtml += `
      <a href="/leave-attendance" class="nav-item" data-panel="leave-attendance" onclick="switchPanel('leave-attendance'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;">
        <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-calendar-check"></i></span>
        <span>Leave & Attendance</span>
        <span class="badge" style="margin-left: auto; background: var(--warning); color: #000; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">PRO</span>
      </a>
    `;
  }

  // 3. Core Modules (Complaints, CAP, PDP)
  let coreItems = '';
  if (allowed.includes('complaints')) {
    const compBadge = isHr 
      ? '<span class="badge" style="margin-left: auto; background: #e2e8f0; color: #475569; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">VIEW ONLY</span>'
      : '<span class="badge" style="margin-left: auto; background: var(--danger); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">7</span>';
    coreItems += `<a href="/complaints" class="nav-item" data-panel="complaints" onclick="switchPanel('complaints'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-inbox"></i></span><span>Complaints</span>${compBadge}</a>`;
  }
  if (allowed.includes('cap')) {
    const capBadge = isHr
      ? '<span class="badge" style="margin-left: auto; background: #e2e8f0; color: #475569; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">VIEW ONLY</span>'
      : '<span class="badge" style="margin-left: auto; background: var(--warning); color: #000; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">3</span>';
    coreItems += `<a href="/cap" class="nav-item" data-panel="cap" onclick="switchPanel('cap'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-circle-check"></i></span><span>Corrective Action Plans</span>${capBadge}</a>`;
  }
  if (allowed.includes('pdp')) {
    coreItems += `<a href="/pdp" class="nav-item" data-panel="pdp" onclick="switchPanel('pdp'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-bullseye"></i></span><span>PDP</span></a>`;
  }
  if (coreItems) {
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Core Modules</div>${coreItems}`;
  }

  // 4. Training & Regional
  let trainingItems = '';
  if (allowed.includes('training')) {
    trainingItems += `<a href="/training" class="nav-item" data-panel="training" onclick="switchPanel('training'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-graduation-cap"></i></span><span>Training</span></a>`;
  }
  if (allowed.includes('states')) {
    trainingItems += `<a href="/states" class="nav-item" data-panel="states" onclick="switchPanel('states'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-map-location-dot"></i></span><span>State and Cluster</span></a>`;
  }
  if (trainingItems) {
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">People & States</div>${trainingItems}`;
  }

  // 5. Governance (Risk, Policy, Lessons)
  let govItems = '';
  if (allowed.includes('risk')) {
    govItems += `<a href="/risk" class="nav-item" data-panel="risk" onclick="switchPanel('risk'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-triangle-exclamation"></i></span><span>Risk Register</span></a>`;
  }
  if (allowed.includes('policies')) {
    const polBadge = isHr ? '<span class="badge" style="margin-left: auto; background: #ede9fe; color: #6d28d9; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">ALL ACCESS</span>' : '';
    govItems += `<a href="/policies" class="nav-item" data-panel="policies" onclick="switchPanel('policies'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-file-shield"></i></span><span>Policy Management</span>${polBadge}</a>`;
  }
  if (allowed.includes('lessons')) {
    const lesBadge = isHr ? '<span class="badge" style="margin-left: auto; background: #ede9fe; color: #6d28d9; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">ALL ACCESS</span>' : '';
    govItems += `<a href="/lessons" class="nav-item" data-panel="lessons" onclick="switchPanel('lessons'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-lightbulb"></i></span><span>Lesson Learned</span>${lesBadge}</a>`;
  }
  if (govItems) {
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Governance</div>${govItems}`;
  }

  // 6. Advanced & Operations (AI Review, Investigation, Travel)
  let advancedItems = '';
  if (allowed.includes('ai-review')) {
    advancedItems += `<a href="/ai-review" class="nav-item" data-panel="ai-review" onclick="switchPanel('ai-review'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-brain"></i></span><span>AI Compliance Review</span><span class="badge" style="margin-left: auto; background: var(--accent2); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">NEW</span></a>`;
  }
  if (allowed.includes('investigations')) {
    const invBadge = isHr ? '<span class="badge" style="margin-left: auto; background: #e2e8f0; color: #475569; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">VIEW ONLY</span>' : '<span class="badge" style="margin-left: auto; background: var(--accent2); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">NEW</span>';
    advancedItems += `<a href="/investigations" class="nav-item" data-panel="investigations" onclick="switchPanel('investigations'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-shield-halved"></i></span><span>Investigation</span>${invBadge}</a>`;
  }
  if (allowed.includes('travel')) {
    advancedItems += `<a href="/travel" class="nav-item" data-panel="travel" onclick="switchPanel('travel'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-plane-departure"></i></span><span>Travel & Tickets</span><span class="badge" style="margin-left: auto; background: var(--accent2); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">NEW</span></a>`;
  }
  if (advancedItems) {
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Special Operations</div>${advancedItems}`;
  }

  // 7. DoC & Super Admin Exclusive Intelligence
  if ((isDoc || isSuperAdmin) && (allowed.includes('reports') || allowed.includes('ai'))) {
    let intel = '';
    if (allowed.includes('reports')) intel += `<a href="/reports" class="nav-item" data-panel="reports" onclick="switchPanel('reports'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-chart-pie"></i></span><span>Reports & Donor</span></a>`;
    if (allowed.includes('ai')) intel += `<a href="/ai" class="nav-item" data-panel="ai" onclick="switchPanel('ai'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-robot"></i></span><span>AI Assistant</span></a>`;
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Intelligence</div>${intel}`;
  }

  return `
    <div class="sidebar" style="width: 270px; background: #ffffff; border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; box-shadow: 2px 0 10px rgba(0, 0, 0, 0.03);">
      <div class="logo" style="padding: 18px 20px 14px; border-bottom: 1px solid var(--border); flex-shrink: 0;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <img src="/assets/images/logo.png" alt="CCCRN Logo" style="height: 36px; display: block;">
          <div>
            <div class="logo-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: var(--accent); display: flex; align-items: center; gap: 6px;">
              <span>CCCRN ComplianceIQ</span>
              <span style="background: var(--accent); color: #fff; font-size: 9px; padding: 2px 5px; border-radius: 4px; font-weight: 700;">PRO</span>
            </div>
            <div class="logo-sub" style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px;">${isSuperAdmin ? 'Super Admin Console' : (isDoc ? 'Director Command Center' : (isHr ? 'HR Command Portal' : 'Staff Compliance Portal'))}</div>
          </div>
        </div>
      </div>

      <div class="nav" style="flex: 1 1 auto; min-height: 0; padding: 10px 0; overflow-y: auto;">
        ${navHtml}
      </div>

      <div class="sidebar-footer" style="padding: 14px 18px; border-top: 1px solid var(--border); background: var(--surface2); flex-shrink: 0;">
        <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
          <div class="avatar" style="width: 36px; height: 36px; border-radius: 50%; background: ${isSuperAdmin ? 'linear-gradient(135deg, #ef4444, #7f1d1d)' : 'linear-gradient(135deg, var(--accent2), var(--accent))'}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; flex-shrink: 0; box-shadow: ${isSuperAdmin ? '0 0 10px rgba(239, 68, 68, 0.45)' : 'none'}; border: ${isSuperAdmin ? '2px solid #fecaca' : 'none'};">${user.avatar}</div>
          <div>
            <div class="user-name" style="font-size: 12px; font-weight: 700; color: var(--text);">${user.name}</div>
            <div class="user-role" style="font-size: 10px; color: ${isSuperAdmin ? '#dc2626' : 'var(--accent)'}; font-weight: 800; text-transform: uppercase; display: flex; align-items: center; gap: 4px;">${isSuperAdmin ? '<i class="fa-solid fa-crown" style="font-size: 9px; color: #f59e0b;"></i> ' : ''}${user.roleBadge}</div>
          </div>
          <form action="/logout" method="POST" style="margin-left: auto; margin-bottom: 0;">
            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 4px;" title="Sign Out">
              <i class="fa-solid fa-right-from-bracket"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  `;
}

function renderBladeView(viewName, data = {}) {
  const viewPath = path.join(root, 'resources/views', viewName.replace(/\./g, '/') + '.blade.php');
  const layoutPath = path.join(root, 'resources/views/layouts/app.blade.php');
  const topbarPath = path.join(root, 'resources/views/partials/topbar.blade.php');
  const footerPath = path.join(root, 'resources/views/partials/footer.blade.php');

  if (viewName.startsWith('auth.')) {
    let authContent = fs.readFileSync(viewPath, 'utf8');
    authContent = authContent.replace(/\{\{\s*asset\('([^']+)'\)\s*\}\}/g, '/$1');
    authContent = authContent.replace(/@csrf/g, '');
    authContent = authContent.replace(/\{\{--[\s\S]*?--\}\}/g, '');
    if (data && data.error) {
      authContent = authContent.replace('class="alert alert-danger d-none"', 'class="alert alert-danger"');
      authContent = authContent.replace('Invalid Administrator Credentials.', data.error);
    }
    authContent = authContent.replace(/@if\([^\)]+\)[\s\S]*?@endif/g, '');
    authContent = authContent.replace(/@(extends|section|endsection|yield|include|push|stack)\b[^\n]*/g, '');
    return authContent;
  }

  const user = data.user || loggedInUser;
  const isHr = user.key === 'hr';
  const isSuperAdmin = user.key === 'superadmin';

  let layoutContent = fs.readFileSync(layoutPath, 'utf8');
  let viewContent = fs.readFileSync(viewPath, 'utf8');
  let sidebarContent = renderDynamicSidebarHtml(user);
  let topbarContent = fs.readFileSync(topbarPath, 'utf8');

  // Customise Topbar for Super Admin vs HR vs DoC
  if (isSuperAdmin) {
    topbarContent = topbarContent.replace('Executive Command Center', 'Super Administrator Master Command Console');
    topbarContent = topbarContent.replace('Live Operations', '<span style="color: #dc2626; font-weight: 800;"><i class="fa-solid fa-crown me-1" style="color: #f59e0b;"></i> ROOT ACCESS · ZERO RESTRICTIONS</span>');
  } else if (isHr) {
    topbarContent = topbarContent.replace('Executive Command Center', 'Human Resources Command Center');
    topbarContent = topbarContent.replace(/<button class="btn btn-primary"[^>]*New Complaint[\s\S]*?<\/button>/, '<button class="btn btn-primary" onclick="switchPanel(\'leave-attendance\')" style="height: 36px; padding: 0 14px; white-space: nowrap;"><i class="fa-solid fa-calendar-plus me-1"></i> Apply for Leave</button>');
  }

  layoutContent = layoutContent.replace(/@include\('partials\.sidebar'\)/g, sidebarContent);
  layoutContent = layoutContent.replace(/@include\('partials\.topbar'\)/g, topbarContent);
  layoutContent = layoutContent.replace(/@include\('partials\.footer'\)/g, '');

  const contentMatch = viewContent.match(/@section\('content'\)([\s\S]*?)@endsection/);
  let bodyHtml = contentMatch ? contentMatch[1] : viewContent;

  layoutContent = layoutContent.replace(/@yield\('content'\)/g, bodyHtml);
  layoutContent = layoutContent.replace(/@yield\('scripts'\)/g, '');
  layoutContent = layoutContent.replace(/@if\(session\('success'\)\)[\s\S]*?@endif/g, '');
  layoutContent = layoutContent.replace(/@if\(session\('error'\)\)[\s\S]*?@endif/g, '');

  // Resolve @include('modules.xxx') and partials
  layoutContent = layoutContent.replace(/@include\('([^']+)'\)/g, (match, includeName) => {
    const includePath = path.join(root, 'resources/views', includeName.replace(/\./g, '/') + '.blade.php');
    if (!fs.existsSync(includePath)) return `<!-- missing: ${includeName} -->`;
    let includeContent = fs.readFileSync(includePath, 'utf8');
    const sectionMatch = includeContent.match(/@section\('content'\)([\s\S]*?)@endsection/);
    if (sectionMatch) includeContent = sectionMatch[1];
    includeContent = includeContent.replace(/@extends\([^)]+\)/g, '');
    return includeContent;
  });

  layoutContent = layoutContent.replace(/\{\{--[\s\S]*?--\}\}/g, '');
  layoutContent = layoutContent.replace(/\{\{\s*\$title\s*\?\?[^}]+\}\}/g, isHr ? 'CCCRN ComplianceIQ — HR Command Portal' : 'CCCRN ComplianceIQ — Ethics & Governance');
  layoutContent = layoutContent.replace(/\{\{\s*\$headerTitle\s*\?\?[^}]+\}\}/g, data.headerTitle || (isHr ? 'Human Resources Command Center' : 'Executive Command Center'));

  layoutContent = layoutContent.replace(/\{\{\s*asset\('([^']+)'\)\s*\}\}/g, '/$1');
  layoutContent = layoutContent.replace(/\{\{\s*route\('([^']+)'\)\s*\}\}/g, '/$1');
  layoutContent = layoutContent.replace(/@csrf/g, '');

  const initialPanel = user.allowedModules.includes(data.currentRoute) ? data.currentRoute : user.defaultModule;

  // Toggle HR vs DoC vs SuperAdmin view inside panel-dashboard server-side
  if (user.key === 'superadmin') {
    layoutContent = layoutContent.replace(/id="superadminDashboardView" style="display: none;"/, 'id="superadminDashboardView" style="display: block;"');
    layoutContent = layoutContent.replace(/id="docDashboardView" style="display: [^"]*"/, 'id="docDashboardView" style="display: none;"');
    layoutContent = layoutContent.replace(/id="hrDashboardView" style="display: [^"]*"/, 'id="hrDashboardView" style="display: none;"');
  } else if (isHr) {
    layoutContent = layoutContent.replace(/id="hrDashboardView" style="display: none;"/, 'id="hrDashboardView" style="display: block;"');
    layoutContent = layoutContent.replace(/id="docDashboardView"/, 'id="docDashboardView" style="display: none;"');
    layoutContent = layoutContent.replace(/id="superadminDashboardView"/, 'id="superadminDashboardView" style="display: none;"');
    layoutContent = layoutContent.replace(/<button[^>]*onclick="openModal\('modalLogComplaint'\)"[^>]*>[\s\S]*?<\/button>/gi, '<!-- New Complaint button removed for HR profile -->');
    layoutContent = layoutContent.replace(/<button[^>]*id="topbarBtnNewComplaint"[^>]*>[\s\S]*?<\/button>/gi, '<!-- Topbar New Complaint button removed for HR profile -->');
    layoutContent = layoutContent.replace(/<button[^>]*id="btnLogComplaint"[^>]*>[\s\S]*?<\/button>/gi, '<!-- Log Complaint button removed for HR profile -->');
  } else {
    layoutContent = layoutContent.replace(/id="hrDashboardView" style="display: [^"]*"/, 'id="hrDashboardView" style="display: none;"');
    layoutContent = layoutContent.replace(/id="superadminDashboardView" style="display: [^"]*"/, 'id="superadminDashboardView" style="display: none;"');
    layoutContent = layoutContent.replace(/id="docDashboardView" style="display: none;"/, 'id="docDashboardView" style="display: block;"');
    if (user.key === 'doc') {
      layoutContent = layoutContent.replace(/<button[^>]*id="btnApplyLeaveHeader"[^>]*>[\s\S]*?<\/button>/gi, '<!-- Apply for Leave button removed for DoC profile -->');
    }
  }

  const autoSwitchScript = `<script>
    window.CURRENT_USER_ROLE = '${user.key}';
    window.ALLOWED_MODULES = ${JSON.stringify(user.allowedModules)};
    window.DEFAULT_MODULE = '${user.defaultModule}';

    document.addEventListener('DOMContentLoaded', function() {
      if (typeof switchPanel === 'function') {
        switchPanel('${initialPanel}');
      }
    });
  </script>`;
  layoutContent = layoutContent.replace('</body>', autoSwitchScript + '</body>');

  // Server-side guarantee of panel visibility
  if (initialPanel) {
    if (initialPanel === 'dashboard') {
      layoutContent = layoutContent.replace(/id="panel-dashboard"[^>]*>/, 'id="panel-dashboard" style="display: block;">');
    } else {
      layoutContent = layoutContent.replace(/id="panel-dashboard"[^>]*>/, 'id="panel-dashboard" style="display: none;">');
      layoutContent = layoutContent.replace(new RegExp('id="panel-' + initialPanel + '"[^>]*>'), 'id="panel-' + initialPanel + '" style="display: block;">');
    }
  }

  return layoutContent;
}

const server = http.createServer((req, res) => {
  const url = req.url.split('?')[0];

  // Parse session cookie
  const cookieHeader = req.headers.cookie || '';
  if (cookieHeader.includes('auth_role=superadmin')) {
    loggedInUser = USER_ROLES['superadmin@cccrn.org'];
  } else if (cookieHeader.includes('auth_role=staff')) {
    loggedInUser = USER_ROLES['staff@cccrn.org'];
  } else if (cookieHeader.includes('auth_role=state_lead')) {
    loggedInUser = USER_ROLES['lead@cccrn.org'];
  } else if (cookieHeader.includes('auth_role=compliance')) {
    loggedInUser = USER_ROLES['compliance@cccrn.org'];
  } else if (cookieHeader.includes('auth_role=hr')) {
    loggedInUser = USER_ROLES['hr@cccrn.org'];
  } else if (cookieHeader.includes('auth_role=doc')) {
    loggedInUser = USER_ROLES['director@cccrn.org'];
  } else {
    loggedInUser = USER_ROLES['staff@cccrn.org'];
  }

  // STRICT SERVER-SIDE ENFORCEMENT: HR VIEW-ONLY ACCESS CANNOT BE BYPASSED
  const isHrRole = loggedInUser && loggedInUser.key === 'hr';
  const isMutative = req.method === 'POST' || req.method === 'PUT' || req.method === 'PATCH' || req.method === 'DELETE';
  if (isHrRole && isMutative) {
    if (url.startsWith('/complaints') || url.startsWith('/api/complaints') || url.startsWith('/cap') || url.startsWith('/api/cap') || url.startsWith('/investigations') || url.startsWith('/api/investigations')) {
      res.writeHead(403, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({
        error: 'Forbidden',
        status: 403,
        message: 'Security Policy Violation: HR role has view-only access to Complaints and Corrective Action Plans. All create, update, and delete actions are permanently blocked by server policy.'
      }));
    }
  }

  
  // ─── API: SUPER ADMIN ROOT GOVERNANCE OVERRIDE & MANAGEMENT ───
  if (url === '/api/superadmin/override' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      const action = data.action;
      const rationale = data.rationale || 'Authorized Super Admin Override';
      let msg = 'Governance action completed successfully.';

      if (action === 'RESET_CLEAN_SLATE') {
        store.leave_requests = [];
        store.attendance_logs = [];
        store.complaints = [];
        store.caps = [];
        store.investigations = [];
        store.field_work = [];
        saveBackendStore(store);
        msg = 'Complete Clean Slate executed: all dummy and test records purged across all modules.';
        logAuditEvent('Super Administrator', 'ComplianceIQ Central', 'Purged all test records and restored system to pure clean slate.');
      } else if (action === 'FORCE_SYNC') {
        msg = 'Biometric attendance roster force-synchronized across all 6 state offices.';
        logAuditEvent('Super Administrator', 'Attendify Biometrics', 'Executed forced synchronization of national staff attendance logs. ' + rationale);
      } else if (action === 'BYPASS_ESCROW') {
        msg = 'Unilateral Escrow Gate clearance granted for all active travel advances.';
        logAuditEvent('Super Administrator', 'Travel POL-TRV-03', 'Unilateral escrow bypass executed under root authority. ' + rationale);
      } else if (action === 'APPROVE_ALL_CAP') {
        const caps = store.caps || [];
        caps.forEach(c => { c.status = 'Closed'; c.resolvedAt = new Date().toISOString(); });
        saveBackendStore(store);
        msg = 'Bulk approved and finalized ' + caps.length + ' pending CAP remediations.';
        logAuditEvent('Super Administrator', 'CAP Directorate', 'Bulk finalized and closed pending CAP remediations. ' + rationale);
      } else if (action === 'LOCK_AUDIT') {
        msg = 'State audit ledgers locked and frozen for external review.';
        logAuditEvent('Super Administrator', 'Audit Ledgers', 'State audit ledgers frozen for external compliance verification. ' + rationale);
      } else {
        logAuditEvent('Super Administrator', 'Root Override', 'Executed root governance directive: ' + action + '. ' + rationale);
      }

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, message: msg }));
    });
  }

  // ─── API: SUPER ADMIN SYSTEM SETTINGS & MODULE CONTROL ───
  if (url === '/api/superadmin/module-toggle' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const moduleName = data.module;
      const status = data.status; // 'Active', 'Maintenance', 'Locked'
      logAuditEvent('Super Administrator', moduleName, 'Subsystem operational status set to: ' + status);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, module: moduleName, status: status }));
    });
  }

  // ─── API: GET COMPLETE BACKEND STORE ───
  if (url === '/api/backend/data' || url === '/api/sync') {
    const store = getBackendStore();
    res.writeHead(200, { 'Content-Type': 'application/json' });
    return res.end(JSON.stringify(store));
  }

  // ─── API: STAFF LEAVE APPLICATION (Staff -> HR) ───
  
  // ─── API: REGISTER OFFICER (DIRECTOR OF COMPLIANCE / HR MANAGER) ───
  if (url === '/api/auth/register-officer' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      if (err || !data.email || !data.role) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        return res.end(JSON.stringify({ success: false, message: 'Role and valid email required' }));
      }
      const roleKey = data.role === 'hr' ? 'hr' : 'doc';
      const email = data.email.trim().toLowerCase();
      const name = data.name || (roleKey === 'hr' ? 'HR Manager' : 'Director of Compliance');
      
      saveRegisteredOfficer(roleKey, {
        email: email,
        name: name,
        phone: data.phone || '',
        registered_at: new Date().toISOString()
      });

      if (roleKey === 'hr') {
        USER_ROLES[email] = {
          key: 'hr',
          name: name,
          roleBadge: 'HR ACCESS',
          avatar: 'HR',
          email: email,
          allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'policies', 'lessons', 'states'],
          defaultModule: 'dashboard'
        };
      } else {
        USER_ROLES[email] = {
          key: 'doc',
          name: name,
          roleBadge: 'ADMIN (DoC)',
          avatar: 'DC',
          email: email,
          allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'states', 'policies', 'lessons', 'reports', 'ai', 'ai-review', 'investigations', 'travel'],
          defaultModule: 'dashboard'
        };
      }

      res.writeHead(200, { 'Content-Type': 'application/json', 'Set-Cookie': 'auth_role=' + roleKey + '; Path=/' });
      return res.end(JSON.stringify({
        success: true,
        message: 'Officer registered successfully. Email cached for all alerts & notifications.',
        role: roleKey,
        email: email,
        name: name
      }));
    });
  }

  // ─── API: GET CACHED OFFICER EMAILS ───
  if (url === '/api/auth/cached-officers' && req.method === 'GET') {
    const officers = getRegisteredOfficers();
    res.writeHead(200, { 'Content-Type': 'application/json' });
    return res.end(JSON.stringify({
      success: true,
      doc_email: (officers.doc && officers.doc.email) || 'director@cccrn.org',
      doc_name: (officers.doc && officers.doc.name) || 'Director of Compliance',
      hr_email: (officers.hr && officers.hr.email) || 'hr@cccrn.org',
      hr_name: (officers.hr && officers.hr.name) || 'HR Manager'
    }));
  }

    // ─── API: SUBMIT FIELD WORK MISSION ───
  if (url === '/api/fieldwork/submit' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.field_work) store.field_work = [];
      const newRef = 'FW-2026-0' + (store.field_work.length + 1);
      const mission = {
        ref: newRef,
        staff_name: data.staff_name || 'Authenticated Staff',
        destination: data.destination || 'Field Facility',
        activity_type: data.activity_type || 'Clinical Mentorship',
        start_date: data.start_date || 'Upcoming',
        end_date: data.end_date || 'Upcoming',
        purpose: data.purpose || '',
        advance_requested: data.advance_requested || 'None',
        advance_status: data.advance_requested ? 'Pending Finance' : 'N/A',
        status: 'Approved & Active',
        created_at: new Date().toISOString()
      };
      store.field_work.unshift(mission);
      saveBackendStore(store);

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, message: 'Field mission ' + newRef + ' registered and synced to Operations & DoC.', mission: mission }));
    });
  }

  if (url === '/api/leave/apply' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      const newId = data.id || ('LVE-2026-0' + (43 + (store.leave_requests ? store.leave_requests.length : 0)));
      const newReq = {
        id: newId,
        staff_name: data.staff_name || 'Fatima Bello',
        department: data.department || 'Clinical Services',
        state: data.state || 'Lagos',
        category: data.category || 'Annual Leave',
        start: data.start || 'Tomorrow',
        end: data.end || 'Next Week',
        days: data.days || 3,
        reliever: data.reliever || 'Biodun Alade',
        status: 'Pending Supervisor'
      };
      store.leave_requests.unshift(newReq);
      saveBackendStore(store);

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, message: 'Leave application submitted. Routed to Supervisor for authentication.', request: newReq }));
    });
  }

  // ─── API: HR OR SUPERVISOR APPROVE / REJECT LEAVE (HR -> Staff) ───
  if (url === '/api/leave/action' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      const reqId = data.id;
      const action = data.action || 'Approved'; // 'Approved' or 'Rejected'

      let found = false;
      let targetReq = null;
      store.leave_requests.forEach(r => {
        if (r.id === reqId) {
          r.status = action;
          if (data.supervisor_authenticated !== undefined) {
            r.supervisor_authenticated = data.supervisor_authenticated;
          }
          if (action === 'Pending HR') {
            r.supervisor_authenticated = true;
            r.authenticated_by = data.supervisor_name || 'Dr. Ngozi Adeyemi';
            r.authenticated_at = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
          }
          targetReq = r;
          found = true;
        }
      });
      saveBackendStore(store);

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: found, message: 'Leave request ' + reqId + ' updated to ' + action, request: targetReq }));
    });
  }

  // ─── API: BIOMETRIC ATTENDANCE CLOCK (Staff -> HR) ───
  if (url === '/api/attendance/clock' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      const newLog = {
        staff_name: data.staff_name || 'Fatima Bello',
        department: data.department || 'Clinical Services',
        state: data.state || 'Lagos',
        terminal: data.terminal || 'BIO-LOS-01',
        time: data.time || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        date: 'Today',
        clockedIn: data.clockedIn !== false,
        status: data.clockedIn !== false ? 'Clocked In' : 'Clocked Out'
      };
      store.attendance_logs.unshift(newLog);
      saveBackendStore(store);

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, log: newLog }));
    });
  }

  // ─── API: LOG / REGISTER COMPLAINT (Staff / DoC) ───
  if (url === '/api/complaints/submit' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.complaints) store.complaints = [];
      const newId = 'CMP-2026-0' + (50 + store.complaints.length);
      const newComp = {
        id: newId,
        date: 'Today',
        category: data.category || 'Grievance',
        title: data.title || (data.category ? data.category + ' Incident' : 'Staff Incident Report'),
        source: data.source || ((data.staff_name || 'Staff') + ' (Portal)'),
        state: data.state || 'National',
        alleged: data.alleged || 'Management Oversight',
        mode: data.mode || 'Named',
        severity: data.severity || 'Medium',
        status: data.status || 'Open',
        owner: data.owner || 'officer@cccrn.org',
        details: data.details || data.description || 'Registered complaint incident.'
      };
      store.complaints.unshift(newComp);
      saveBackendStore(store);

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, complaint: newComp }));
    });
  }

  // ─── API: UPDATE COMPLAINT STATUS / CONVERT TO CAP ───
  if (url === '/api/complaints/action' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.complaints) store.complaints = [];
      const id = data.id;
      const action = data.action; // 'Open', 'In Progress', 'Closed', 'Converted to CAP', 'investigate'
      let updated = null;

      store.complaints.forEach(c => {
        if (c.id === id) {
          if (action === 'investigate') {
            c.status = 'In Progress';
            // Also create investigation record if not existing
            if (!store.investigations) store.investigations = [];
            const invRef = 'INV-0' + (13 + store.investigations.length);
            store.investigations.unshift({
              ref: invRef,
              sourceComp: c.id,
              state: c.state || 'National',
              allegation: c.category + ': ' + (c.details || 'Investigation required'),
              lead: 'Assigned Lead Auditor',
              daysOpen: 1,
              severity: c.severity || 'High',
              status: 'Under Investigation',
              evidenceCount: 1,
              notes: 'Spawned from complaint ' + c.id
            });
          } else {
            c.status = action;
          }
          updated = c;
        }
      });
      saveBackendStore(store);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: !!updated, complaint: updated }));
    });
  }

  // ─── API: DELETE COMPLAINT (Super Admin Only) ───
  if (url === '/api/complaints/delete' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.complaints) store.complaints = [];
      store.complaints = store.complaints.filter(c => c.id !== data.id);
      saveBackendStore(store);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true }));
    });
  }

  // ─── API: CREATE CAP (DoC / Compliance) ───
  if (url === '/api/cap/create' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.caps) store.caps = [];
      const newId = 'CAP-2026-0' + (33 + store.caps.length);
      const newCap = {
        id: newId,
        issue: data.issue || 'Corrective action plan finding',
        state: data.state || 'National',
        linkedRef: data.linkedRef || 'DIRECT',
        deadline: data.deadline || '30 Days',
        responsible: data.responsible || 'Unit Lead',
        status: 'Open',
        priority: data.priority || 'High',
        notes: data.notes || 'Institutional corrective action item.',
        hasEvidence: false,
        evidenceList: []
      };
      store.caps.unshift(newCap);
      saveBackendStore(store);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, cap: newCap }));
    });
  }

  // ─── API: UPDATE CAP STATUS (DoC / Admin) ───
  if (url === '/api/cap/action' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.caps) store.caps = [];
      let updated = null;
      store.caps.forEach(c => {
        if (c.id === data.id) {
          c.status = data.status;
          if (data.status === 'Closed') {
            c.resolvedAt = new Date().toISOString();
          }
          updated = c;
        }
      });
      saveBackendStore(store);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: !!updated, cap: updated }));
    });
  }

  // ─── API: DELETE CAP (Super Admin Only) ───
  if (url === '/api/cap/delete' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.caps) store.caps = [];
      store.caps = store.caps.filter(c => c.id !== data.id);
      saveBackendStore(store);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true }));
    });
  }

  // ─── API: INVESTIGATION ACTIONS ───
  if (url === '/api/investigations/action' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.investigations) store.investigations = [];
      let updated = null;
      store.investigations.forEach(inv => {
        if (inv.ref === data.ref) {
          if (data.lead) inv.lead = data.lead;
          if (data.status) inv.status = data.status;
          if (data.findings) inv.notes = data.findings;
          updated = inv;
        }
      });
      saveBackendStore(store);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: !!updated, investigation: updated }));
    });
  }

  if (url === '/api/investigations/delete' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.investigations) store.investigations = [];
      store.investigations = store.investigations.filter(i => i.ref !== data.ref);
      saveBackendStore(store);
      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true }));
    });
  }

  // ─── API: SUBMIT CAP STATE EVIDENCE (Staff -> HR/DoC) ───
  if (url === '/api/cap/submit-evidence' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      const capRef = data.capRef;
      const fileName = data.fileName || ('State_Evidence_' + capRef + '.pdf');

      let updated = false;
      store.caps.forEach(c => {
        if (c.id === capRef) {
          c.hasEvidence = true;
          c.status = 'Evidence Submitted';
          if (!c.evidenceList) c.evidenceList = [];
          c.evidenceList.push(fileName);
          updated = true;
        }
      });
      saveBackendStore(store);

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: updated, message: 'Evidence for ' + capRef + ' transmitted to Compliance Directorate.' }));
    });
  }

  
  // DIRECT TESTING ROUTE: SUPER ADMIN (ROOT ABSOLUTE AUTHORITY)
  if (url === '/superadmin' || url === '/superadmin-dashboard' || url === '/super-admin') {
    loggedInUser = USER_ROLES['superadmin@cccrn.org'];
    const html = renderBladeView('dashboard.index', { currentRoute: 'dashboard', user: loggedInUser });
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8', 'Set-Cookie': 'auth_role=superadmin; Path=/' });
    return res.end(html);
  }

    // DIRECT TESTING ROUTE: COMPLIANCE OFFICER / COMPLAINTS HUB
  if (url === '/compliance' || url === '/compliance-officer' || url === '/complaints-hub') {
    loggedInUser = USER_ROLES['compliance@cccrn.org'];
    const html = renderBladeView('dashboard.index', { currentRoute: 'complaints', user: loggedInUser });
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8', 'Set-Cookie': 'auth_role=compliance; Path=/' });
    return res.end(html);
  }

  // DIRECT TESTING ROUTE: HR DASHBOARD
  if (url === '/hr' || url === '/hr-dashboard') {
    loggedInUser = USER_ROLES['hr@cccrn.org'];
    const html = renderBladeView('dashboard.index', { currentRoute: 'dashboard', user: loggedInUser });
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8', 'Set-Cookie': 'auth_role=hr; Path=/' });
    return res.end(html);
  }

  // DIRECT TESTING ROUTE: DoC (DIRECTOR OF COMPLIANCE) DASHBOARD
  if (url === '/doc' || url === '/doc-dashboard' || url === '/director' || url === '/compliance-dashboard') {
    loggedInUser = USER_ROLES['director@cccrn.org'];
    const html = renderBladeView('dashboard.index', { currentRoute: 'dashboard', user: loggedInUser });
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8', 'Set-Cookie': 'auth_role=doc; Path=/' });
    return res.end(html);
  }

  // Static Assets
  if (url.startsWith('/assets/')) {
    const filePath = path.join(root, 'assets', url.replace('/assets/', ''));
    if (fs.existsSync(filePath) && fs.statSync(filePath).isFile()) {
      const ext = path.extname(filePath);
      const mime = ext === '.css' ? 'text/css' : ext === '.png' ? 'image/png' : ext === '.js' ? 'application/javascript' : 'text/plain';
      res.writeHead(200, { 'Content-Type': mime });
      return res.end(fs.readFileSync(filePath));
    }
    // Fallback search in public/assets/
    const publicPath = path.join(root, 'public', url);
    if (fs.existsSync(publicPath) && fs.statSync(publicPath).isFile()) {
      const ext = path.extname(publicPath);
      const mime = ext === '.css' ? 'text/css' : ext === '.png' ? 'image/png' : ext === '.js' ? 'application/javascript' : 'text/plain';
      res.writeHead(200, { 'Content-Type': mime });
      return res.end(fs.readFileSync(publicPath));
    }
  }

  if (url === '/assets/images/logo.png' || url === '/logo.png') {
    const logoPath = path.join(root, 'public/assets/images/logo.png');
    if (fs.existsSync(logoPath)) {
      res.writeHead(200, { 'Content-Type': 'image/png' });
      return res.end(fs.readFileSync(logoPath));
    }
  }

    // STANDALONE IDENTIFY HOST INTEGRATION & EMBED ROUTES
  if (url === '/identify' || url === '/identify/staff-compliance' || url === '/test-harness/identify') {
    const simPath = path.join(root, 'resources/views/test_harness/identify_simulator.blade.php');
    if (fs.existsSync(simPath)) {
      res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
      return res.end(fs.readFileSync(simPath, 'utf8'));
    }
  }

  // INDEPENDENT STANDALONE WORKFORCE & COMPLIANCE FEATURE ROUTES
  // (Can be embedded by Attendify team via iframe, or opened directly as an independent feature)
  if (url === '/staff-portal' || url === '/attendify-feature' || url === '/compliance-feature' || url === '/my-compliance' || (url === '/staff' && req.method === 'GET')) {
    let embedHtml = fs.readFileSync(path.join(root, 'resources/views/modules/identify_embed.blade.php'), 'utf8');
    const staffHtml = fs.readFileSync(path.join(root, 'resources/views/modules/staff.blade.php'), 'utf8');
    const contentOnly = staffHtml.match(/@section\('content'\)([\s\S]*?)@endsection/);
    embedHtml = embedHtml.replace(/@include\('modules\.staff'\)/, contentOnly ? contentOnly[1] : staffHtml);
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(embedHtml);
  }

  if (url === '/staff-embed' || url === '/identify/embed') {
    let embedHtml = fs.readFileSync(path.join(root, 'resources/views/modules/identify_embed.blade.php'), 'utf8');
    const staffHtml = fs.readFileSync(path.join(root, 'resources/views/modules/staff.blade.php'), 'utf8');
    const contentOnly = staffHtml.match(/@section\('content'\)([\s\S]*?)@endsection/);
    embedHtml = embedHtml.replace(/@include\('modules\.staff'\)/, contentOnly ? contentOnly[1] : staffHtml);
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(embedHtml);
  }

  // 1. DEDICATED ADMIN & COMPLIANCE SPECIALIST LOGIN vs GENERAL LOGIN
  if (url === '/admin' || url === '/admin/login' || url === '/compliance-hub/login') {
    if (req.method === 'GET') {
      const html = renderBladeView('auth.compliance_hub_login');
      res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
      return res.end(html);
    }
  }

  if (url === '/login' || url === '/auth/login') {
    if (req.method === 'GET') {
      const html = renderBladeView('auth.login');
      res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
      return res.end(html);
    }
  }

  if (url === '/admin' || url === '/admin/login' || url === '/login' || url === '/auth/login' || url === '/compliance-hub/login') {
    if (req.method === 'POST') {
      let body = '';
      req.on('data', chunk => body += chunk);
      req.on('end', () => {
        const params = new URLSearchParams(body);
        const userIdentifier = (params.get('username') || params.get('email') || '').trim().toLowerCase();

        const regOfficers = getRegisteredOfficers();
        const docRegisteredEmail = (regOfficers.doc && regOfficers.doc.email || '').toLowerCase();
        const hrRegisteredEmail = (regOfficers.hr && regOfficers.hr.email || '').toLowerCase();

        if (userIdentifier === docRegisteredEmail || userIdentifier === 'director@cccrn.org' || userIdentifier === 'director' || userIdentifier === 'chika@cccrn.org') {
          loggedInUser = USER_ROLES[userIdentifier] || {
            key: 'doc',
            name: (regOfficers.doc && regOfficers.doc.name) || 'Director of Compliance (DoC)',
            roleBadge: 'ADMIN (DoC)',
            avatar: 'DC',
            email: userIdentifier,
            allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'states', 'policies', 'lessons', 'reports', 'ai', 'ai-review', 'investigations', 'travel'],
            defaultModule: 'dashboard'
          };
          res.writeHead(302, { 'Location': '/dashboard', 'Set-Cookie': 'auth_role=doc; Path=/' });
          return res.end();
        } else if (userIdentifier === hrRegisteredEmail || userIdentifier === 'hr@cccrn.org' || userIdentifier === 'hr' || userIdentifier === 'people@cccrn.org') {
          loggedInUser = USER_ROLES[userIdentifier] || {
            key: 'hr',
            name: (regOfficers.hr && regOfficers.hr.name) || 'HR Manager',
            roleBadge: 'HR ACCESS',
            avatar: 'HR',
            email: userIdentifier,
            allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'policies', 'lessons', 'states'],
            defaultModule: 'dashboard'
          };
          res.writeHead(302, { 'Location': '/dashboard', 'Set-Cookie': 'auth_role=hr; Path=/' });
          return res.end();
        } else if (userIdentifier === 'superadmin@cccrn.org' || userIdentifier === 'superadmin' || userIdentifier === 'admin@cccrn.org') {
          loggedInUser = USER_ROLES['superadmin@cccrn.org'];
          res.writeHead(302, { 'Location': '/dashboard', 'Set-Cookie': 'auth_role=superadmin; Path=/' });
          return res.end();
        } else if (userIdentifier === 'compliance@cccrn.org' || userIdentifier === 'compliance' || userIdentifier === 'specialist@cccrn.org') {
          loggedInUser = USER_ROLES['compliance@cccrn.org'];
          res.writeHead(302, { 'Location': '/complaints', 'Set-Cookie': 'auth_role=compliance; Path=/' });
          return res.end();
        } else if (userIdentifier === 'supervisor@cccrn.org' || userIdentifier === 'supervisor' || userIdentifier === 'ngozi@cccrn.org') {
          loggedInUser = {
            key: 'supervisor',
            name: 'Dr. Ngozi Adeyemi',
            roleBadge: 'SUPERVISOR (LAGOS)',
            avatar: 'NA',
            email: 'supervisor@cccrn.org',
            allowedModules: ['staff', 'complaints', 'cap', 'pdp', 'training', 'policies', 'lessons', 'ai'],
            defaultModule: 'staff'
          };
          res.writeHead(302, { 'Location': '/identify', 'Set-Cookie': 'auth_role=supervisor; Path=/' });
          return res.end();
        } else if (userIdentifier === 'lead@cccrn.org' || userIdentifier === 'lead' || userIdentifier === 'stl@cccrn.org' || userIdentifier === 'musa@cccrn.org') {
          loggedInUser = USER_ROLES['lead@cccrn.org'];
          res.writeHead(302, { 'Location': '/identify', 'Set-Cookie': 'auth_role=state_lead; Path=/' });
          return res.end();
        } else {
          loggedInUser = {
            key: 'staff',
            name: userIdentifier ? userIdentifier.split('@')[0] : 'Staff Member',
            roleBadge: 'STAFF ACCESS',
            avatar: 'ST',
            email: userIdentifier || 'staff@cccrn.org',
            allowedModules: ['complaints', 'cap', 'pdp', 'training', 'states', 'policies', 'lessons', 'ai', 'travel'],
            defaultModule: 'complaints'
          };
          res.writeHead(302, { 'Location': '/staff', 'Set-Cookie': 'auth_role=staff; Path=/' });
          return res.end();
        }
      });
      return;
    }
  }

  if (url === '/logout') {
    loggedInUser = USER_ROLES['staff@cccrn.org'];
    res.writeHead(302, { 'Location': '/login', 'Set-Cookie': 'auth_role=; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT' });
    return res.end();
  }

  if (url === '/') {
    const html = renderBladeView('auth.login');
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(html);
  }

  // Module Routing on Unified Dashboard
  let requestedModule = url.replace('/', '') || loggedInUser.defaultModule;

  if (requestedModule === 'dashboard') {
    if (loggedInUser.key !== 'doc' && loggedInUser.key !== 'superadmin' && loggedInUser.key !== 'hr') {
      res.writeHead(302, { 'Location': '/' + loggedInUser.defaultModule });
      return res.end();
    }
  }

    // LEAVE & ATTENDANCE REGISTER: STRICTLY RESTRICTED TO HR/SUPERVISOR/HOD/DOC
  if (requestedModule === 'leave-attendance' && loggedInUser.key === 'staff') {
    res.writeHead(302, { 'Location': '/staff' });
    return res.end();
  }

    // STAFF COMPLIANCE: EXCLUSIVELY FOR STAFF / IDENTIFY — HR CANNOT ACCESS AS STAFF
  if (requestedModule === 'staff' && loggedInUser.key === 'hr') {
    res.writeHead(302, { 'Location': '/leave-attendance' });
    return res.end();
  }

  // RISK REGISTER: STRICTLY NO ACCESS FOR HR
  if (requestedModule === 'risk' && loggedInUser.key === 'hr') {
    res.writeHead(302, { 'Location': '/' + loggedInUser.defaultModule });
    return res.end();
  }

  // AI COMPLIANCE REVIEW: STRICTLY NO ACCESS FOR HR
  if (requestedModule === 'ai-review' && loggedInUser.key === 'hr') {
    res.writeHead(302, { 'Location': '/' + loggedInUser.defaultModule });
    return res.end();
  }

  const docOnlyPages = ['reports', 'ai'];
  if (docOnlyPages.includes(requestedModule) && loggedInUser.key !== 'doc' && loggedInUser.key !== 'superadmin') {
    res.writeHead(302, { 'Location': '/' + loggedInUser.defaultModule });
    return res.end();
  }

  const html = renderBladeView('dashboard.index', { currentRoute: requestedModule, user: loggedInUser });
  res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(html);
});

const PORT = process.env.PORT || 8000;
server.listen(PORT, () => {
  console.log('🚀 CCCRN ComplianceIQ Single Port running live on http://localhost:8000');
});