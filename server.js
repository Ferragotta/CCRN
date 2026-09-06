// ══════════════════════════════════════════════════════════════════
// CCCRN COMPLIANCEIQ — PRODUCTION SERVER FOR RENDER DEPLOYMENT
// ══════════════════════════════════════════════════════════════════

const http = require('http');
const fs = require('fs');
const path = require('path');
const querystring = require('querystring');

const root = __dirname;
const PORT = process.env.PORT || 8000;

const dataDir = path.join(root, 'data');
if (!fs.existsSync(dataDir)) {
  try {
    fs.mkdirSync(dataDir, { recursive: true });
  } catch (e) {}
}

const storePath = path.join(dataDir, 'backend_store.json');

function getBackendStore() {
  try {
    if (fs.existsSync(storePath)) return JSON.parse(fs.readFileSync(storePath, 'utf8'));
  } catch (e) {
    console.error('Error reading backend store:', e);
  }
  return {
    leave_requests: [],
    attendance_logs: [],
    complaints: [],
    caps: [],
    investigations: [],
    pdp_records: [],
    field_work: [],
    audit_logs: [],
    registered_officers: {}
  };
}

function saveBackendStore(data) {
  try {
    fs.writeFileSync(storePath, JSON.stringify(data, null, 2), 'utf8');
  } catch (e) {
    console.error('Error saving backend store:', e);
  }
}

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
  if (store.audit_logs.length > 50) store.audit_logs = store.audit_logs.slice(0, 50);
  saveBackendStore(store);
  return newLog;
}

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
    allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'states', 'policies', 'lessons', 'reports', 'ai', 'ai-review', 'investigations', 'travel'],
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
    allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'states', 'policies', 'lessons', 'investigations', 'travel'],
    defaultModule: 'dashboard'
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

let loggedInUser = USER_ROLES['staff@cccrn.org'];

function renderDynamicSidebarHtml(user) {
  const allowed = user.allowedModules || [];
  const isDoc = user.key === 'doc';
  const isSuperAdmin = user.key === 'superadmin';
  const isHr = user.key === 'hr';

  let navHtml = '';

  if (allowed.includes('dashboard')) {
    navHtml += `
      <a href="/dashboard" class="nav-item active" data-panel="dashboard" onclick="switchPanel('dashboard'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 10px 18px; font-size: 13px; font-weight: 600; color: var(--accent); background: var(--accent-light); border-left: 3px solid var(--accent); text-decoration: none;">
        <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-chart-line"></i></span>
        <span>${isSuperAdmin ? 'Super Admin Console' : (isDoc ? 'Executive Dashboard' : (isHr ? 'HR Command Dashboard' : 'Dashboard'))}</span>
        ${isSuperAdmin ? '<span class="badge" style="margin-left: auto; background: #ef4444; color: #fff; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 800;">ROOT</span>' : ''}
      </a>
    `;
  }

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
    if (allowed.includes('leave-attendance')) {
      const leaveBadge = isHr ? '<span class="badge" style="margin-left: auto; background: var(--accent); color: #fff; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">HR LEAD</span>' : '';
      navHtml += `<a href="/leave-attendance" class="nav-item" data-panel="leave-attendance" onclick="switchPanel('leave-attendance'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-calendar-check"></i></span><span>Leave & Attendance</span>${leaveBadge}</a>`;
    }
    if (allowed.includes('pdp')) {
      const pdpBadge = isHr ? '<span class="badge" style="margin-left: auto; background: #e0f2fe; color: #0369a1; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">AUDIT</span>' : '';
      navHtml += `<a href="/pdp" class="nav-item" data-panel="pdp" onclick="switchPanel('pdp'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-bullseye"></i></span><span>PDP & Objectives</span>${pdpBadge}</a>`;
    }
  }

  if (allowed.includes('training')) {
    navHtml += `<a href="/training" class="nav-item" data-panel="training" onclick="switchPanel('training'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-graduation-cap"></i></span><span>Training Academy</span></a>`;
  }
  if (allowed.includes('states')) {
    navHtml += `<a href="/states" class="nav-item" data-panel="states" onclick="switchPanel('states'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-map-location-dot"></i></span><span>State Offices</span></a>`;
  }

  let oversightItems = '';
  if (allowed.includes('complaints')) {
    oversightItems += `<a href="/complaints" class="nav-item" data-panel="complaints" onclick="switchPanel('complaints'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-inbox"></i></span><span>Complaints & Whistleblower</span></a>`;
  }
  if (allowed.includes('cap')) {
    oversightItems += `<a href="/cap" class="nav-item" data-panel="cap" onclick="switchPanel('cap'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-circle-check"></i></span><span>Corrective Action (CAP)</span></a>`;
  }
  if (oversightItems) {
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Accountability</div>${oversightItems}`;
  }

  let govItems = '';
  if (allowed.includes('risk') && !isHr) {
    govItems += `<a href="/risk" class="nav-item" data-panel="risk" onclick="switchPanel('risk'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-triangle-exclamation"></i></span><span>Risk Register</span><span class="badge" style="margin-left: auto; background: var(--accent); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">ISO 31000</span></a>`;
  }
  if (allowed.includes('policies')) {
    govItems += `<a href="/policies" class="nav-item" data-panel="policies" onclick="switchPanel('policies'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-file-shield"></i></span><span>Policy Management</span></a>`;
  }
  if (allowed.includes('lessons')) {
    govItems += `<a href="/lessons" class="nav-item" data-panel="lessons" onclick="switchPanel('lessons'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-lightbulb"></i></span><span>Lesson Learned</span></a>`;
  }
  if (govItems) {
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Governance</div>${govItems}`;
  }

  let advancedItems = '';
  if (allowed.includes('ai-review')) {
    advancedItems += `<a href="/ai-review" class="nav-item" data-panel="ai-review" onclick="switchPanel('ai-review'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-brain"></i></span><span>AI Compliance Review</span></a>`;
  }
  if (allowed.includes('investigations')) {
    advancedItems += `<a href="/investigations" class="nav-item" data-panel="investigations" onclick="switchPanel('investigations'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-shield-halved"></i></span><span>Investigation</span></a>`;
  }
  if (allowed.includes('travel')) {
    advancedItems += `<a href="/travel" class="nav-item" data-panel="travel" onclick="switchPanel('travel'); return false;" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid transparent;"><span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-plane-departure"></i></span><span>Travel & Tickets</span></a>`;
  }
  if (advancedItems) {
    navHtml += `<div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Special Operations</div>${advancedItems}`;
  }

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

  layoutContent = layoutContent.replace(/@include\('([^']+)'\)/g, (match, includeName) => {
    const includePath = path.join(root, 'resources/views', includeName.replace(/\./g, '/') + '.blade.php');
    if (!fs.existsSync(includePath)) return '<!-- missing: ' + includeName + ' -->';
    let includeContent = fs.readFileSync(includePath, 'utf8');
    const sectionMatch = includeContent.match(/@section\('content'\)([\s\S]*?)@endsection/);
    if (sectionMatch) includeContent = sectionMatch[1];
    includeContent = includeContent.replace(/@extends\([^)]+\)/g, '');
    return includeContent;
  });

  layoutContent = layoutContent.replace(/\{\{\s*asset\('([^']+)'\)\s*\}\}/g, '/$1');
  layoutContent = layoutContent.replace(/\{\{\s*\$title\s*\?\?\s*'([^']+)'\s*\}\}/g, '$1');
  layoutContent = layoutContent.replace(/@csrf/g, '');
  layoutContent = layoutContent.replace(/\{\{--[\s\S]*?--\}\}/g, '');

  const activeModule = data.currentRoute || user.defaultModule;
  layoutContent = layoutContent.replace('</body>', `
    <script>
      window.CURRENT_USER_ROLE = '${user.key}';
      window.CURRENT_USER_EMAIL = '${user.email}';
      window.CURRENT_USER_NAME = '${user.name}';
      
      document.addEventListener('DOMContentLoaded', () => {
        if (typeof switchPanel === 'function') {
          switchPanel('${activeModule}');
        }
      });
    </script>
    </body>
  `);

  return layoutContent;
}

const server = http.createServer((req, res) => {
  const parsedUrl = new URL(req.url, 'http://' + (req.headers.host || 'localhost'));
  const url = parsedUrl.pathname;

  if (url === '/api/health' || url === '/healthz') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    return res.end(JSON.stringify({ status: 'ok', time: new Date().toISOString() }));
  }

  if (url.startsWith('/assets/') || url.endsWith('.css') || url.endsWith('.js') || url.endsWith('.png') || url.endsWith('.jpg')) {
    let filePath = path.join(root, 'public', url);
    if (!fs.existsSync(filePath)) filePath = path.join(root, url);
    if (fs.existsSync(filePath) && fs.statSync(filePath).isFile()) {
      let contentType = 'text/plain';
      if (filePath.endsWith('.css')) contentType = 'text/css';
      if (filePath.endsWith('.js')) contentType = 'application/javascript';
      if (filePath.endsWith('.png')) contentType = 'image/png';
      if (filePath.endsWith('.jpg')) contentType = 'image/jpeg';
      if (filePath.endsWith('.svg')) contentType = 'image/svg+xml';
      res.writeHead(200, { 'Content-Type': contentType });
      return fs.createReadStream(filePath).pipe(res);
    }
  }

  if (url.startsWith('/portals/')) {
    const portalFile = path.join(root, url.endsWith('.html') ? url : url + '/index.html');
    if (fs.existsSync(portalFile) && fs.statSync(portalFile).isFile()) {
      res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
      return fs.createReadStream(portalFile).pipe(res);
    }
  }

  if (url === '/staff_compliance_local.html') {
    const localHtml = path.join(root, 'staff_compliance_local.html');
    if (fs.existsSync(localHtml)) {
      res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
      return fs.createReadStream(localHtml).pipe(res);
    }
  }

  if (url === '/api/backend/data' || url === '/api/sync') {
    const store = getBackendStore();
    res.writeHead(200, { 'Content-Type': 'application/json' });
    return res.end(JSON.stringify(store));
  }

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
        msg = 'Clean Slate executed: all test records purged.';
        logAuditEvent('Super Administrator', 'ComplianceIQ Central', 'Purged all test records.');
      } else if (action === 'APPROVE_ALL_CAP') {
        const caps = store.caps || [];
        caps.forEach(c => { c.status = 'Closed'; c.resolvedAt = new Date().toISOString(); });
        saveBackendStore(store);
        msg = 'Bulk approved ' + caps.length + ' pending CAP remediations.';
        logAuditEvent('Super Administrator', 'CAP Directorate', 'Bulk closed pending CAP remediations. ' + rationale);
      } else {
        logAuditEvent('Super Administrator', 'Root Override', 'Directive: ' + action + '. ' + rationale);
      }

      res.writeHead(200, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, message: msg }));
    });
  }

  if (url === '/api/leave/apply' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      if (err || !data) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        return res.end(JSON.stringify({ success: false, message: 'Invalid payload' }));
      }
      const store = getBackendStore();
      if (!store.leave_requests) store.leave_requests = [];
      const newLeave = {
        id: 'LVE-' + Date.now().toString().slice(-6),
        staff_name: data.staff_name || 'Staff Member',
        staff_email: data.staff_email || 'staff@cccrn.org',
        leave_type: data.leave_type || 'Annual Leave',
        start_date: data.start_date || new Date().toISOString().split('T')[0],
        end_date: data.end_date || new Date().toISOString().split('T')[0],
        days: data.days || 1,
        reason: data.reason || 'Personal request',
        status: 'Pending HR Verification',
        created_at: new Date().toISOString()
      };
      store.leave_requests.unshift(newLeave);
      saveBackendStore(store);
      logAuditEvent(newLeave.staff_name, 'HR Leave Desk', 'Applied for ' + newLeave.leave_type + ' (' + newLeave.days + ' days).');
      res.writeHead(201, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, leave: newLeave }));
    });
  }

  if (url === '/api/attendance/clock' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.attendance_logs) store.attendance_logs = [];
      const newLog = {
        id: 'LOG-' + Date.now().toString().slice(-6),
        staff_name: data.staff_name || 'Staff Member',
        staff_email: data.staff_email || 'staff@cccrn.org',
        type: data.type || 'In',
        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        date: new Date().toISOString().split('T')[0],
        location: data.location || 'Headquarters'
      };
      store.attendance_logs.unshift(newLog);
      saveBackendStore(store);
      res.writeHead(201, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, log: newLog }));
    });
  }

  if (url === '/api/complaints/submit' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.complaints) store.complaints = [];
      const newComplaint = {
        id: 'CMP-' + (store.complaints.length + 49),
        ref: 'CMP-' + (store.complaints.length + 49),
        date: new Date().toLocaleDateString('en-GB'),
        state: data.state || 'Lagos',
        category: data.category || 'General',
        severity: data.severity || 'Medium',
        source: data.source || 'Whistleblower',
        allegedParty: data.allegedParty || '—',
        summary: data.summary || '',
        status: 'Open',
        loggedByEmail: data.loggedByEmail || 'staff@cccrn.org'
      };
      store.complaints.unshift(newComplaint);
      saveBackendStore(store);
      logAuditEvent('Whistleblower Desk', 'Complaints Intake', 'Logged complaint ' + newComplaint.ref + ' [' + newComplaint.severity + '].');
      res.writeHead(201, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, complaint: newComplaint }));
    });
  }

  if (url === '/api/fieldwork/submit' && req.method === 'POST') {
    return parseJsonBody(req, (err, data) => {
      const store = getBackendStore();
      if (!store.field_work) store.field_work = [];
      const newMission = {
        id: 'MSN-' + Date.now().toString().slice(-6),
        mission_name: data.mission_name || 'Field Mission',
        staff_name: data.staff_name || 'Field Lead',
        state: data.state || 'Kano',
        date: new Date().toISOString().split('T')[0],
        status: 'Active Fieldwork'
      };
      store.field_work.unshift(newMission);
      saveBackendStore(store);
      res.writeHead(201, { 'Content-Type': 'application/json' });
      return res.end(JSON.stringify({ success: true, mission: newMission }));
    });
  }

  if (url === '/login' || url === '/') {
    const html = renderBladeView('auth.login');
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(html);
  }

  if (url === '/admin/login') {
    const html = renderBladeView('auth.admin_login');
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(html);
  }

  if (url === '/login/submit' && req.method === 'POST') {
    let body = '';
    req.on('data', chunk => body += chunk);
    req.on('end', () => {
      const post = querystring.parse(body);
      const email = (post.email || '').trim().toLowerCase();
      if (USER_ROLES[email]) {
        loggedInUser = USER_ROLES[email];
      } else {
        loggedInUser = {
          key: 'staff',
          name: post.name || email.split('@')[0] || 'Staff User',
          roleBadge: 'STAFF ACCESS',
          avatar: 'ST',
          email: email,
          allowedModules: ['staff', 'complaints', 'cap', 'pdp', 'training', 'policies', 'lessons', 'ai'],
          defaultModule: 'staff'
        };
      }
      res.writeHead(302, { 'Location': '/' + loggedInUser.defaultModule });
      return res.end();
    });
    return;
  }

  if (url === '/logout') {
    loggedInUser = USER_ROLES['staff@cccrn.org'];
    res.writeHead(302, { 'Location': '/login' });
    return res.end();
  }

  let requestedModule = url.replace('/', '') || loggedInUser.defaultModule;
  if (requestedModule === 'dashboard' && loggedInUser.key === 'staff') {
    requestedModule = 'staff';
  }

  const html = renderBladeView('dashboard.index', { currentRoute: requestedModule, user: loggedInUser });
  res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(html);
});

server.listen(PORT, () => {
  console.log('🚀 CCCRN ComplianceIQ live on port ' + PORT);
});
