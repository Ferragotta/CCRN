/**
 * CCCRN ComplianceIQ — Main Front-End Application Controller
 * Handles RBAC routing, DOM rendering, form actions, and interactions.
 */

// Application State
const AppState = {
  currentRole: 'doc',
  activePanel: 'dashboard',
  users: {
    doc: { name: 'Dr. Kabir Alabi', role: 'Director of Compliance (Admin)', avatar: 'KA' },
    compliance_officer: { name: 'Amina Yusuf', role: 'Compliance Officer / Specialist', avatar: 'AY' },
    hr: { name: 'Chidinma Okoro', role: 'Human Resources Lead', avatar: 'CO' },
    staff: { name: 'Fatima Bello', role: 'Clinical Officer (General Staff)', avatar: 'FB' },
    supervisor: { name: 'Emeka Nwosu', role: 'Senior Field Supervisor', avatar: 'EN' },
    hod: { name: 'Dr. Biodun Ojo', role: 'Head of Department (HOD)', avatar: 'BO' },
    stl: { name: 'Ngozi Eze', role: 'State Team Lead (Rivers)', avatar: 'NE' }
  }
};

// Access Matrix Definition
const RBAC_MATRIX = {
  doc: {
    panels: ['dashboard','complaints','cap','pdp','training','states','risk','policy','lessons','reports','ai','ai-review','investigation','travel'],
    defaultPanel: 'dashboard'
  },
  compliance_officer: {
    panels: ['complaints','cap','training','states','risk','lessons','ai-review','investigation','travel'],
    defaultPanel: 'complaints'
  },
  hr: {
    panels: ['complaints','cap','pdp','training','states','policy','lessons','investigation'],
    defaultPanel: 'pdp'
  },
  staff: {
    panels: ['complaints','cap','pdp','training','policy','lessons','ai','travel'],
    defaultPanel: 'complaints'
  },
  supervisor: {
    panels: ['complaints','cap','pdp','training','policy','lessons','ai','travel'],
    defaultPanel: 'pdp'
  },
  hod: {
    panels: ['complaints','cap','pdp','training','policy','lessons','ai','travel'],
    defaultPanel: 'pdp'
  },
  stl: {
    panels: ['complaints','cap','pdp','training','states','policy','lessons','ai','travel'],
    defaultPanel: 'states'
  }
};

const PANEL_METADATA = {
  dashboard: { title: 'Director Command Center', icon: '📊', sec: 'Executive' },
  complaints: { title: 'Complaints Management', icon: '📥', sec: 'Core' },
  cap: { title: 'Corrective Action (CAP)', icon: '✅', sec: 'Core' },
  pdp: { title: 'PDP Performance Tracker', icon: '🎯', sec: 'Core' },
  training: { title: 'Training Academy', icon: '🎓', sec: 'People' },
  states: { title: 'States & Clusters', icon: '🗺️', sec: 'Operations' },
  risk: { title: 'Risk Register & 5x5', icon: '⚠️', sec: 'Governance' },
  policy: { title: 'Policy Management', icon: '📋', sec: 'Governance' },
  lessons: { title: 'Lessons Learned', icon: '💡', sec: 'Governance' },
  reports: { title: 'Reports & Donor Center', icon: '📊', sec: 'Intelligence' },
  ai: { title: 'AI Assistant', icon: '🤖', sec: 'Intelligence' },
  'ai-review': { title: 'AI Compliance Review', icon: '🧠', sec: 'New Modules' },
  investigation: { title: 'Investigations', icon: '🔐', sec: 'New Modules' },
  travel: { title: 'Travel & Tickets', icon: '✈️', sec: 'New Modules' }
};

// 1. LIFECYCLE INITIALIZER
async function initApp() {
  renderSidebar();
  updateUserBadge();
  showPanel(RBAC_MATRIX[AppState.currentRole].defaultPanel);
  await refreshAllData();
}

function switchRole(roleKey) {
  AppState.currentRole = roleKey;
  const select = document.getElementById('role-select');
  if (select) select.value = roleKey;
  
  updateUserBadge();
  renderSidebar();

  const allowed = RBAC_MATRIX[roleKey].panels;
  if (!allowed.includes(AppState.activePanel)) {
    showPanel(RBAC_MATRIX[roleKey].defaultPanel);
  } else {
    showPanel(AppState.activePanel);
  }
  showNotification('Role Switched', `Active perspective: ${AppState.users[roleKey].role}`, 'success');
}

function updateUserBadge() {
  const u = AppState.users[AppState.currentRole];
  document.getElementById('sidebar-user-name').textContent = u.name;
  document.getElementById('sidebar-user-role').textContent = u.role;
  document.getElementById('sidebar-avatar').textContent = u.avatar;
}

function renderSidebar() {
  const allowed = RBAC_MATRIX[AppState.currentRole].panels;
  const container = document.getElementById('sidebar-nav-container');

  const sections = {};
  allowed.forEach(p => {
    const meta = PANEL_METADATA[p];
    if (!meta) return;
    if (!sections[meta.sec]) sections[meta.sec] = [];
    sections[meta.sec].push({ id: p, ...meta });
  });

  let html = '';
  for (const [secName, items] of Object.entries(sections)) {
    html += `<div class="nav-section">${secName}</div>`;
    items.forEach(it => {
      let badge = '';
      if (it.id === 'complaints') badge = `<span class="badge" id="badge-cmp-count">7</span>`;
      if (it.id === 'cap') badge = `<span class="badge warn" id="badge-cap-count">3</span>`;
      if (it.id === 'ai-review' || it.id === 'investigation' || it.id === 'travel') badge = `<span class="badge purple">NEW</span>`;

      html += `
        <div class="nav-item ${it.id === AppState.activePanel ? 'active' : ''}" onclick="showPanel('${it.id}')">
          <span class="icon">${it.icon}</span>
          <span>${it.title}</span>
          ${badge}
        </div>
      `;
    });
  }
  container.innerHTML = html;
}

async function showPanel(id) {
  const allowed = RBAC_MATRIX[AppState.currentRole].panels;
  if (!allowed.includes(id)) {
    showNotification('Access Restricted', 'This module is restricted under your assigned role.', 'warning');
    return;
  }

  AppState.activePanel = id;
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));

  const target = document.getElementById('panel-' + id);
  if (target) target.classList.add('active');

  const meta = PANEL_METADATA[id];
  document.getElementById('page-title').textContent = meta ? meta.title : 'CCCRN ComplianceIQ';
  renderSidebar();
  await refreshActivePanelView(id);
}

// 2. DATA REFRESH & VIEW DISPATCHER
async function refreshAllData() {
  await Promise.all([
    renderDashboardView(),
    renderComplaintsView(),
    renderCapView(),
    renderPdpView(),
    renderTrainingView(),
    renderStatesView(),
    renderRiskView(),
    renderPolicyView(),
    renderLessonsView(),
    renderInvestigationsView(),
    renderTravelView()
  ]);
}

async function refreshActivePanelView(panelId) {
  switch (panelId) {
    case 'dashboard': await renderDashboardView(); break;
    case 'complaints': await renderComplaintsView(); break;
    case 'cap': await renderCapView(); break;
    case 'pdp': await renderPdpView(); break;
    case 'training': await renderTrainingView(); break;
    case 'states': await renderStatesView(); break;
    case 'risk': await renderRiskView(); break;
    case 'policy': await renderPolicyView(); break;
    case 'lessons': await renderLessonsView(); break;
    case 'investigation': await renderInvestigationsView(); break;
    case 'travel': await renderTravelView(); break;
  }
}

// 3. INDIVIDUAL MODULE RENDERERS

// Helper to jump between roles and panels in a workflow tunnel
function jumpWorkflowTunnel(roleKey, panelId) {
  switchRole(roleKey);
  showPanel(panelId);
  showNotification('Workflow Tunneled', `Switched to ${AppState.users[roleKey].role} perspective`, 'success');
}

// 3.1 DASHBOARD
async function renderDashboardView() {
  const [complaints, caps, risks, states] = await Promise.all([
    Api.Complaints.getAll(),
    Api.Caps.getAll(),
    Api.Risks.getAll(),
    Api.States.getStates()
  ]);

  document.getElementById('dash-complaints-count').textContent = complaints.length;
  document.getElementById('dash-caps-count').textContent = caps.filter(c => c.status !== 'Verified').length;
  document.getElementById('dash-risks-count').textContent = risks.filter(r => r.rating === 'Critical' || r.rating === 'High').length;

  // Render Complaints by Category Graph on Dashboard
  renderCategoryBars('dash-category-bars', complaints);

  const capTbody = document.getElementById('dash-recent-caps-tbody');
  if (capTbody) {
    capTbody.innerHTML = caps.slice(0, 4).map(c => `
      <tr>
        <td><strong>${c.id}</strong></td>
        <td>${c.issue}</td>
        <td>${c.deadline}</td>
        <td><span class="pill ${c.status==='Open'?'pill-open':c.status==='In Progress'?'pill-progress':'pill-verified'}">${c.status}</span></td>
      </tr>
    `).join('');
  }

  const stGrid = document.getElementById('dash-state-mini-grid');
  if (stGrid) {
    stGrid.innerHTML = states.slice(0, 4).map(s => `
      <div class="state-card">
        <div class="state-name">${s.name}</div>
        <div class="state-meta">${s.staff} staff · ${s.score}% score</div>
        <div class="progress-wrap"><div class="progress-bar ${s.score>=80?'bar-green':s.score>=60?'bar-amber':'bar-red'}" style="width:${s.score}%;"></div></div>
      </div>
    `).join('');
  }

  const feed = document.getElementById('dash-activity-feed');
  if (feed) {
    feed.innerHTML = `
      <div class="activity-item">
        <div class="activity-dot" style="background:var(--danger);"></div>
        <div class="activity-text"><strong>CMP-048 Logged:</strong> Direct sourcing in Kano diesel procurement.<div class="activity-time">10 mins ago</div></div>
      </div>
      <div class="activity-item">
        <div class="activity-dot" style="background:var(--success);"></div>
        <div class="activity-text"><strong>CAP-029 Closed:</strong> Rivers per diem advance reconciliation cleared.<div class="activity-time">2 hours ago</div></div>
      </div>
      <div class="activity-item">
        <div class="activity-dot" style="background:var(--accent2);"></div>
        <div class="activity-text"><strong>Flight TKT-1042 Verified:</strong> Boarding pass confirmed for Dr. Ngozi Eze.<div class="activity-time">4 hours ago</div></div>
      </div>
    `;
  }
}

// Render visual category bars with percentage calculation & filter triggers
function renderCategoryBars(containerId, complaintsList) {
  const container = document.getElementById(containerId);
  if (!container) return;

  const categories = [
    { name: 'Procurement', color: '#0077b6' },
    { name: 'Safeguarding', color: '#7c3aed' },
    { name: 'Finance', color: '#d97706' },
    { name: 'HR', color: '#059669' },
    { name: 'Data', color: '#dc2626' }
  ];

  const total = complaintsList.length || 1;
  const counts = {};
  categories.forEach(c => counts[c.name] = 0);
  complaintsList.forEach(item => {
    if (counts[item.cat] !== undefined) counts[item.cat]++;
    else counts[item.cat] = (counts[item.cat] || 0) + 1;
  });

  let html = '';
  categories.forEach(cat => {
    const count = counts[cat.name] || 0;
    const pct = Math.round((count / total) * 100);
    html += `
      <div class="cat-bar-item" onclick="filterByCategoryDirect('${cat.name}')" title="Click to filter by ${cat.name}">
        <div class="cat-bar-header">
          <span>${cat.name}</span>
          <span><strong>${count}</strong> (${pct}%)</span>
        </div>
        <div class="cat-bar-track">
          <div class="cat-bar-fill" style="width:${pct}%; background:${cat.color};"></div>
        </div>
      </div>
    `;
  });
  container.innerHTML = html;
}

function filterByCategoryDirect(catName) {
  showPanel('complaints');
  const sel = document.getElementById('filter-cmp-cat');
  if (sel) {
    sel.value = catName;
    renderComplaintsView();
    showNotification('Filtered', `Showing complaints in category: ${catName}`, 'info');
  }
}

// 3.2 COMPLAINTS
async function renderComplaintsView() {
  const role = AppState.currentRole;
  const cat = document.getElementById('filter-cmp-cat').value;
  const stat = document.getElementById('filter-cmp-stat').value;
  const q = (document.getElementById('search-cmp').value || '').toLowerCase();

  let allList = await Api.Complaints.getAll();

  // Render category distribution graph in complaints panel
  renderCategoryBars('cmp-category-bars', allList);
  const totalBadge = document.getElementById('cmp-total-badge');
  if (totalBadge) totalBadge.textContent = `${allList.length} Total Complaints`;

  let list = allList;
  if (role === 'staff') {
    list = list.filter(c => c.by === 'Fatima Bello' || c.by === 'Anonymous');
    document.getElementById('cmp-role-notice').textContent = 'Viewing your personal and anonymous submissions';
  } else {
    document.getElementById('cmp-role-notice').textContent = role === 'hr' ? 'HR View-Only Access' : 'Full Compliance Triage';
  }

  if (cat !== 'all') list = list.filter(c => c.cat === cat);
  if (stat !== 'all') list = list.filter(c => c.status === stat);
  if (q) list = list.filter(c => c.desc.toLowerCase().includes(q) || c.id.toLowerCase().includes(q) || c.state.toLowerCase().includes(q));

  const tbody = document.getElementById('complaints-tbody');
  tbody.innerHTML = list.map(c => {
    let actions = '';
    if (role === 'doc' || role === 'compliance_officer') {
      actions = `
        <div style="display:flex; gap:4px;">
          <button class="btn btn-sm btn-outline" onclick="convertCmpToCap('${c.id}')">Convert to CAP</button>
          <button class="btn btn-sm btn-secondary" onclick="convertCmpToInv('${c.id}')">Investigate</button>
          ${role === 'doc' ? `<button class="btn btn-sm btn-danger" onclick="deleteCmp('${c.id}')">Delete</button>` : ''}
        </div>
      `;
    } else {
      actions = `<span style="font-size:11px; color:var(--text-muted);">${c.status}</span>`;
    }

    return `
      <tr>
        <td><strong>${c.id}</strong></td>
        <td><span class="pill pill-info">${c.cat}</span></td>
        <td>${c.state}</td>
        <td>${c.by}</td>
        <td>${c.date}</td>
        <td><span class="pill ${c.status==='Open'?'pill-open':c.status==='In Progress'?'pill-progress':c.status==='Closed'?'pill-closed':'pill-high'}">${c.status}</span></td>
        <td>${actions}</td>
      </tr>
    `;
  }).join('');
}

async function convertCmpToCap(id) {
  await Api.Complaints.convertToCap(id);
  showNotification('Converted to CAP', `Complaint ${id} converted to CAP successfully`, 'success');
  await renderComplaintsView();
  await renderCapView();
}

async function convertCmpToInv(id) {
  await Api.Complaints.convertToInvestigation(id);
  showNotification('Investigation Opened', `Formal case opened from ${id}`, 'success');
  await renderComplaintsView();
  await renderInvestigationsView();
}

async function deleteCmp(id) {
  if (confirm(`Are you sure you want to permanently delete ${id}?`)) {
    await Api.Complaints.delete(id);
    showNotification('Deleted', `Complaint ${id} removed`, 'danger');
    await renderComplaintsView();
  }
}

// 3.3 CAP
async function renderCapView() {
  const role = AppState.currentRole;
  const stat = document.getElementById('filter-cap-stat').value;
  const q = (document.getElementById('search-cap').value || '').toLowerCase();

  let list = await Api.Caps.getAll();
  if (stat !== 'all') list = list.filter(c => c.status === stat);
  if (q) list = list.filter(c => c.issue.toLowerCase().includes(q) || c.id.toLowerCase().includes(q) || c.state.toLowerCase().includes(q));

  const createBtn = document.getElementById('cap-create-btn');
  if (createBtn) createBtn.style.display = (role === 'doc' || role === 'compliance_officer') ? 'inline-flex' : 'none';

  const tbody = document.getElementById('cap-tbody');
  tbody.innerHTML = list.map(c => {
    let evDisplay = '';
    if (c.ev) {
      evDisplay = `<span class="pill pill-closed">📄 ${c.ev}</span>`;
    } else {
      evDisplay = `<button class="btn btn-sm btn-outline" onclick="openCapEvidenceModal('${c.id}')">+ Submit State Proof</button>`;
    }

    let actions = '';
    if (role === 'doc' || role === 'compliance_officer') {
      if (c.status === 'Evidence Submitted') {
        actions = `<button class="btn btn-sm btn-success" onclick="closeCapItem('${c.id}')">Verify & Close</button>`;
      } else if (c.status !== 'Verified') {
        actions = `<button class="btn btn-sm btn-outline" onclick="closeCapItem('${c.id}')">Close CAP</button>`;
      } else {
        actions = `<span class="pill pill-verified">Verified & Closed</span>`;
      }
    } else {
      actions = `<span style="font-size:11px; color:var(--text-muted);">${c.status}</span>`;
    }

    return `
      <tr>
        <td><strong>${c.id}</strong></td>
        <td>${c.issue}</td>
        <td>${c.state}</td>
        <td>${c.linked || 'N/A'}</td>
        <td>${c.resp}</td>
        <td>${c.deadline}</td>
        <td><span class="pill ${c.status==='Open'?'pill-open':c.status==='In Progress'?'pill-progress':c.status==='Evidence Submitted'?'pill-medium':'pill-verified'}">${c.status}</span></td>
        <td>${evDisplay}</td>
        <td>${actions}</td>
      </tr>
    `;
  }).join('');
}

function openCapEvidenceModal(id) {
  document.getElementById('ev-cap-id').value = id;
  document.getElementById('ev-cap-id-display').value = id;
  showModal('modal-cap-evidence');
}

async function handleCapEvidenceSubmit(e) {
  e.preventDefault();
  const id = document.getElementById('ev-cap-id').value;
  const file = document.getElementById('ev-file-ref').value;
  const notes = document.getElementById('ev-notes').value;
  await Api.Caps.submitEvidence(id, file, notes);
  showNotification('Evidence Uploaded', `CAP ${id} is ready for verification`, 'success');
  closeModal('modal-cap-evidence');
  await renderCapView();
}

async function closeCapItem(id) {
  await Api.Caps.close(id);
  showNotification('CAP Verified', `CAP ${id} closed successfully`, 'success');
  await renderCapView();
}

// 3.4 PDP
async function renderPdpView() {
  const role = AppState.currentRole;
  document.getElementById('pdp-tab-sup').style.display = (role === 'supervisor' || role === 'doc') ? 'block' : 'none';
  document.getElementById('pdp-tab-hod').style.display = (role === 'hod' || role === 'doc') ? 'block' : 'none';
  document.getElementById('pdp-tab-hr').style.display = (role === 'hr' || role === 'doc') ? 'block' : 'none';

  const [myObjs, myInns] = await Promise.all([
    Api.Pdp.getMyObjectives('Fatima Bello'),
    Api.Pdp.getInnovations()
  ]);

  const objTbody = document.getElementById('my-pdp-objs-tbody');
  objTbody.innerHTML = myObjs.map((o, idx) => `
    <tr>
      <td>${idx+1}</td>
      <td><strong>${o.title}</strong><br><small style="color:var(--text-muted);">${o.quarter}</small></td>
      <td>${o.weight}%</td>
      <td><span class="pill ${o.approved?'pill-closed':'pill-progress'}">${o.approved?'Approved by Supervisor':'Pending Approval'}</span></td>
      <td><span class="pill ${o.ev?'pill-closed':'pill-open'}">${o.ev || 'No File'}</span></td>
      <td>
        ${!o.ev ? `<button class="btn btn-sm btn-outline" onclick="showNotification('Attached','Proof uploaded','success')">+ Attach Proof</button>` : `<span style="color:var(--success); font-size:11px;">✓ Scored (${o.score} pts)</span>`}
      </td>
    </tr>
  `).join('');

  const innTbody = document.getElementById('my-pdp-inns-tbody');
  innTbody.innerHTML = myInns.filter(i => i.staff === 'Fatima Bello').map(i => `
    <tr>
      <td><strong>${i.title}</strong><br><small style="color:var(--text-dim);">${i.desc}</small></td>
      <td>${i.date}</td>
      <td>${i.score ? `<span class="pill pill-closed">${i.score} / 10 pts</span>` : `<span class="pill pill-progress">Pending HOD</span>`}</td>
      <td>${i.feedback || 'Awaiting evaluation'}</td>
    </tr>
  `).join('');

  const supTbody = document.getElementById('pdp-sup-approval-tbody');
  supTbody.innerHTML = `
    <tr>
      <td><strong>Fatima Bello</strong></td>
      <td>Clinical Services (Kano)</td>
      <td>3 Objectives (100% Total)</td>
      <td><span class="pill pill-progress">2 Approved · 1 Pending</span></td>
      <td>2 Proofs Uploaded</td>
      <td><button class="btn btn-sm btn-primary" onclick="approveSupervisorObjectives('Fatima Bello')">Approve All</button></td>
    </tr>
  `;

  const hodTbody = document.getElementById('pdp-hod-tbody');
  hodTbody.innerHTML = myInns.map(i => `
    <tr>
      <td><strong>${i.staff}</strong></td>
      <td>Clinical Services</td>
      <td><strong>${i.title}</strong><br><small style="color:var(--text-dim);">${i.desc}</small></td>
      <td>${i.date}</td>
      <td>${i.score ? `<strong style="color:var(--accent);">${i.score} / 10</strong>` : `<span class="pill pill-progress">Ungraded</span>`}</td>
      <td><button class="btn btn-sm btn-secondary" onclick="gradeInnovationPrompt('${i.id}')">Grade Innovation</button></td>
    </tr>
  `).join('');

  const hrTbody = document.getElementById('pdp-hr-master-tbody');
  hrTbody.innerHTML = `
    <tr><td><strong>Fatima Bello</strong></td><td>Clinical Services</td><td>Dr. Biodun Ojo</td><td><span class="pill pill-closed">Submitted</span></td><td><span class="pill pill-closed">Approved</span></td><td>85 / 100</td><td><strong style="color:var(--accent);">84.5 (A)</strong></td></tr>
    <tr><td><strong>Emeka Nwosu</strong></td><td>Clinical Services</td><td>Dr. Biodun Ojo</td><td><span class="pill pill-closed">Submitted</span></td><td><span class="pill pill-closed">Approved</span></td><td>88 / 100</td><td><strong style="color:var(--accent);">87.0 (A)</strong></td></tr>
    <tr><td><strong>Ngozi Eze</strong></td><td>Strategic Information</td><td>Dr. Kabir Alabi</td><td><span class="pill pill-closed">Submitted</span></td><td><span class="pill pill-closed">Approved</span></td><td>90 / 100</td><td><strong style="color:var(--accent);">91.2 (A+)</strong></td></tr>
  `;
}

function switchPdpView(id) {
  ['pdp-my-view','pdp-sup-view','pdp-hod-view','pdp-hr-view'].forEach(v => {
    const el = document.getElementById(v);
    if (el) el.style.display = 'none';
  });
  document.querySelectorAll('#pdp-tabs .tab').forEach(t => t.classList.remove('active'));

  if (id === 'my-pdp') document.getElementById('pdp-my-view').style.display = 'block';
  if (id === 'sup-view') document.getElementById('pdp-sup-view').style.display = 'block';
  if (id === 'hod-view') document.getElementById('pdp-hod-view').style.display = 'block';
  if (id === 'hr-view') document.getElementById('pdp-hr-view').style.display = 'block';

  const curTab = document.querySelector(`[onclick="switchPdpView('${id}')"]`);
  if (curTab) curTab.classList.add('active');
}

async function approveSupervisorObjectives(name) {
  showNotification('Objectives Approved', `All PDP objectives approved for ${name}`, 'success');
  await renderPdpView();
}

function saveBehavioralScores() {
  const s1 = parseInt(document.getElementById('sc-1').value)||0;
  const s2 = parseInt(document.getElementById('sc-2').value)||0;
  const s3 = parseInt(document.getElementById('sc-3').value)||0;
  const s4 = parseInt(document.getElementById('sc-4').value)||0;
  const s5 = parseInt(document.getElementById('sc-5').value)||0;
  const avg = ((s1+s2+s3+s4+s5)/5).toFixed(1);
  const staff = document.getElementById('beh-staff-select').value;
  const month = document.getElementById('beh-month-select').value;
  showNotification('Behavioral Grade Saved', `${staff} evaluated for ${month}: ${avg} / 100 pts`, 'success');
}

async function gradeInnovationPrompt(id) {
  const s = prompt('Enter score out of 10 for this creative initiative (e.g. 8.5):', '8.5');
  if (s !== null) {
    const fb = prompt('Enter HOD evaluation remarks:', 'Commendable initiative. Approved for deployment.');
    await Api.Pdp.gradeInnovation(id, parseFloat(s), fb);
    showNotification('Innovation Graded', `Initiative evaluated with ${s} / 10 pts`, 'success');
    await renderPdpView();
  }
}

function exportPdpCSV() {
  let csv = 'Staff Name,Department,Supervisor,Submission Status,Supervisor Approval,Behavioral Score,Final Grade\n';
  csv += 'Fatima Bello,Clinical Services,Dr. Biodun Ojo,Submitted,Approved,85/100,84.5\n';
  csv += 'Emeka Nwosu,Clinical Services,Dr. Biodun Ojo,Submitted,Approved,88/100,87.0\n';
  csv += 'Ngozi Eze,Strategic Information,Dr. Kabir Alabi,Submitted,Approved,90/100,91.2\n';
  const blob = new Blob([csv], { type: 'text/csv' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a'); a.href = url; a.download = 'CCCRN_PDP_Audit.csv'; a.click();
  showNotification('Export Completed', 'Downloaded CCCRN_PDP_Audit.csv', 'success');
}

// 3.5 TRAINING
async function renderTrainingView() {
  const role = AppState.currentRole;
  const addBtn = document.getElementById('training-add-btn');
  if (addBtn) addBtn.style.display = (role === 'doc' || role === 'hr' || role === 'compliance_officer') ? 'inline-flex' : 'none';

  const list = await Api.Trainings.getAll();
  const tbody = document.getElementById('training-tbody');
  tbody.innerHTML = list.map(t => {
    const pct = Math.round((t.done / t.total) * 100);
    return `
      <tr>
        <td><strong>${t.title}</strong></td>
        <td>${t.aud}</td>
        <td>${t.done} / ${t.total}</td>
        <td>
          <div style="display:flex; align-items:center; gap:6px;">
            <strong>${pct}%</strong>
            <div class="progress-wrap" style="width:60px;"><div class="progress-bar ${pct>=85?'bar-green':pct>=70?'bar-blue':'bar-amber'}" style="width:${pct}%;"></div></div>
          </div>
        </td>
        <td>${t.deadline}</td>
        <td><span class="pill ${t.status==='Active'?'pill-closed':'pill-progress'}">${t.status}</span></td>
        <td><button class="btn btn-sm btn-outline" onclick="showNotification('Attendance Logged','Certificate recorded','success')">Log Attendance</button></td>
      </tr>
    `;
  }).join('');
}

// 3.6 STATES & CLUSTERS
async function renderStatesView() {
  const role = AppState.currentRole;
  const updBtn = document.getElementById('state-update-btn');
  if (updBtn) updBtn.style.display = (role === 'stl' || role === 'doc') ? 'inline-flex' : 'none';

  const [states, fieldUpdates] = await Promise.all([
    Api.States.getStates(),
    Api.States.getFieldUpdates()
  ]);

  const grid = document.getElementById('states-full-grid');
  grid.innerHTML = states.map(s => `
    <div class="state-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
        <div class="state-name">${s.name}</div>
        <span class="pill ${s.status==='Compliant'?'pill-closed':s.status==='Minor Gaps'?'pill-progress':'pill-critical'}">${s.status}</span>
      </div>
      <div class="state-meta">${s.clusters} · ${s.staff} staff</div>
      <div style="display:flex; justify-content:space-between; font-size:11px; margin-bottom:4px;">
        <span>Compliance Health:</span><strong>${s.score}%</strong>
      </div>
      <div class="progress-wrap"><div class="progress-bar ${s.score>=80?'bar-green':s.score>=60?'bar-amber':'bar-red'}" style="width:${s.score}%;"></div></div>
    </div>
  `).join('');

  const fTbody = document.getElementById('field-updates-tbody');
  fTbody.innerHTML = fieldUpdates.map(f => `
    <tr>
      <td>${f.date}</td>
      <td><strong>${f.state}</strong></td>
      <td>${f.by}</td>
      <td><span class="pill ${f.status==='Compliant'?'pill-closed':'pill-progress'}">${f.status}</span></td>
      <td>${f.challenges}</td>
      <td>${f.mitigations}</td>
    </tr>
  `).join('');
}

// 3.7 RISK REGISTER
async function renderRiskView() {
  const role = AppState.currentRole;
  const grid = document.getElementById('risk-matrix-container');
  if (!grid) return;

  const list = await Api.Risks.getAll();

  let cellsHtml = '';
  for (let L = 5; L >= 1; L--) {
    for (let I = 1; I <= 5; I++) {
      const score = L * I;
      let rClass = 'r1';
      if (score >= 15) rClass = 'r4';
      else if (score >= 10) rClass = 'r3';
      else if (score >= 6) rClass = 'r2';

      const count = list.filter(r => r.like === L && r.impact === I).length;
      cellsHtml += `
        <div class="risk-cell ${rClass}" onclick="showNotification('Risk Matrix','Showing ${count} risks under L${L} × I${I}','info')">
          <div>L${L} × I${I}</div>
          ${count > 0 ? `<div style="font-size:11px; font-weight:800;">[${count}]</div>` : '<div style="opacity:0.4;">-</div>'}
        </div>
      `;
    }
  }
  grid.innerHTML = cellsHtml;

  const tbody = document.getElementById('risk-tbody');
  tbody.innerHTML = list.map(r => `
    <tr>
      <td><strong>${r.id}</strong></td>
      <td><span class="pill pill-info">${r.cat}</span></td>
      <td>${r.desc}</td>
      <td>${r.like} / 5</td>
      <td>${r.impact} / 5</td>
      <td><span class="pill ${r.rating==='Critical'?'pill-critical':r.rating==='High'?'pill-high':r.rating==='Medium'?'pill-medium':'pill-low'}">${r.rating}</span></td>
      <td>${r.owner}</td>
      <td><span class="pill ${r.status==='Resolved'?'pill-closed':'pill-progress'}">${r.status}</span></td>
      <td>${role === 'doc' ? `<button class="btn btn-sm btn-danger" onclick="deleteRiskItem('${r.id}')">Delete</button>` : `<span style="font-size:11px; color:var(--text-muted);">Active</span>`}</td>
    </tr>
  `).join('');
}

async function deleteRiskItem(id) {
  await Api.Risks.delete(id);
  showNotification('Risk Deleted', `Risk ${id} removed`, 'danger');
  await renderRiskView();
}

// 3.8 POLICY MANAGEMENT
async function renderPolicyView() {
  const role = AppState.currentRole;
  const addBtn = document.getElementById('policy-add-btn');
  if (addBtn) addBtn.style.display = (role === 'doc' || role === 'hr') ? 'inline-flex' : 'none';

  const list = await Api.Policies.getAll();
  const tbody = document.getElementById('policy-tbody');
  tbody.innerHTML = list.map(p => `
    <tr>
      <td><strong>${p.title}</strong><br><small style="color:var(--text-muted);">${p.id}</small></td>
      <td><span class="pill pill-info">${p.cat}</span></td>
      <td>${p.ver}</td>
      <td>${p.lastRev}</td>
      <td>${p.nextRev}</td>
      <td>
        <div style="display:flex; align-items:center; gap:6px;">
          <strong>${p.rate}%</strong>
          <div class="progress-wrap" style="width:50px;"><div class="progress-bar bar-green" style="width:${p.rate}%;"></div></div>
        </div>
      </td>
      <td>${p.myAck ? `<span class="pill pill-closed">✓ Acknowledged</span>` : `<button class="btn btn-sm btn-primary" onclick="signPolicyAction('${p.id}')">Sign Acknowledgment</button>`}</td>
      <td><button class="btn btn-sm btn-outline" onclick="showNotification('Policy Document', 'Viewing ${p.title}','info')">View</button></td>
    </tr>
  `).join('');
}

async function signPolicyAction(id) {
  await Api.Policies.sign(id);
  showNotification('Policy Signed', `Digital sign-off completed for policy`, 'success');
  await renderPolicyView();
}

// 3.9 LESSONS LEARNED
async function renderLessonsView() {
  const role = AppState.currentRole;
  const addBtn = document.getElementById('lesson-add-btn');
  if (addBtn) addBtn.style.display = (role === 'doc' || role === 'hr' || role === 'compliance_officer' || role === 'supervisor' || role === 'hod') ? 'inline-flex' : 'none';

  const list = await Api.Lessons.getAll();
  const tbody = document.getElementById('lessons-tbody');
  tbody.innerHTML = list.map(l => `
    <tr>
      <td><strong>${l.id}</strong></td>
      <td><span class="pill pill-info">${l.cat}</span></td>
      <td>${l.ref}</td>
      <td>${l.text}</td>
      <td><span class="pill ${l.prio==='High'?'pill-high':'pill-medium'}">${l.prio}</span></td>
      <td>${l.date}</td>
      <td>${(role === 'doc' || role === 'hr') ? `<button class="btn btn-sm btn-danger" onclick="deleteLessonItem('${l.id}')">Delete</button>` : `<span style="font-size:11px; color:var(--text-muted);">Standard</span>`}</td>
    </tr>
  `).join('');
}

async function deleteLessonItem(id) {
  await Api.Lessons.delete(id);
  showNotification('Lesson Removed', `Lesson ${id} deleted`, 'danger');
  await renderLessonsView();
}

// 3.10 INVESTIGATIONS
async function renderInvestigationsView() {
  const role = AppState.currentRole;
  const openBtn = document.getElementById('inv-open-btn');
  if (openBtn) openBtn.style.display = (role === 'doc') ? 'inline-flex' : 'none';

  const list = await Api.Investigations.getAll();
  const tbody = document.getElementById('investigations-tbody');
  tbody.innerHTML = list.map(inv => `
    <tr>
      <td><strong>${inv.id}</strong></td>
      <td>${inv.title}</td>
      <td>${inv.state}</td>
      <td>${inv.lead}</td>
      <td><small>${inv.scope}</small></td>
      <td>${inv.date}</td>
      <td><span class="pill ${inv.status==='Active'?'pill-critical':'pill-progress'}">${inv.status}</span></td>
      <td><button class="btn btn-sm btn-outline" onclick="showNotification('Case Dossier','Opening file ${inv.id}','info')">Dossier</button></td>
    </tr>
  `).join('');
}

// 3.11 TRAVEL & TICKETS
async function renderTravelView() {
  const role = AppState.currentRole;
  const list = await Api.Travel.getAll();
  const tbody = document.getElementById('travel-tbody');
  tbody.innerHTML = list.map(t => {
    let bpAction = '';
    if (t.bp) {
      bpAction = `<span class="pill pill-closed">🎫 ${t.bp}</span>`;
    } else {
      bpAction = `<button class="btn btn-sm btn-primary" onclick="openBoardingPassModal('${t.id}')">+ Upload Boarding Pass</button>`;
    }

    let actions = '';
    if (role === 'doc' || role === 'compliance_officer') {
      if (t.status === 'Utilized' && t.pay === 'Cleared') {
        actions = `<span class="pill pill-closed">Payment Cleared</span>`;
      } else if (t.status === 'Utilized') {
        actions = `<button class="btn btn-sm btn-success" onclick="clearTravelVendorPayment('${t.id}')">Clear Payment</button>`;
      } else {
        actions = `<span style="font-size:11px; color:var(--text-muted);">Awaiting Boarding Pass</span>`;
      }
    } else {
      actions = `<span style="font-size:11px; color:var(--text-muted);">${t.pay}</span>`;
    }

    return `
      <tr>
        <td><strong>${t.id}</strong></td>
        <td>${t.name}</td>
        <td>${t.route}</td>
        <td>${t.date}</td>
        <td><span class="pill pill-info">${t.code}</span></td>
        <td><span class="pill ${t.status==='Utilized'?'pill-closed':'pill-progress'}">${t.status}</span></td>
        <td>${bpAction}</td>
        <td><span class="pill ${t.pay==='Cleared'?'pill-closed':'pill-open'}">${t.pay}</span></td>
        <td>${actions}</td>
      </tr>
    `;
  }).join('');
}

function openBoardingPassModal(id) {
  document.getElementById('bp-tkt-id').value = id;
  document.getElementById('bp-tkt-display').value = id;
  showModal('modal-boarding-pass');
}

async function handleBoardingPassSubmit(e) {
  e.preventDefault();
  const id = document.getElementById('bp-tkt-id').value;
  const file = document.getElementById('bp-file-name').value;
  await Api.Travel.uploadBoardingPass(id, file);
  showNotification('Boarding Pass Verified', `Flight ${id} marked utilized and ready for vendor payment`, 'success');
  closeModal('modal-boarding-pass');
  await renderTravelView();
}

async function clearTravelVendorPayment(id) {
  await Api.Travel.clearPayment(id);
  showNotification('Payment Authorized', `Vendor invoice cleared for ${id}`, 'success');
  await renderTravelView();
}

function switchTvlTab(id) {
  document.getElementById('tvl-tracking-view').style.display = id === 'tvl-tracking-view' ? 'block' : 'none';
  document.getElementById('tvl-request-view').style.display = id === 'tvl-request-view' ? 'block' : 'none';
  document.querySelectorAll('#tvl-tabs .tab').forEach(t => t.classList.remove('active'));
  const cur = document.querySelector(`[onclick="switchTvlTab('${id}')"]`);
  if (cur) cur.classList.add('active');
}

async function handleTvlRequest(e) {
  e.preventDefault();
  const data = {
    name: document.getElementById('tvl-input-name').value,
    route: document.getElementById('tvl-input-route').value,
    date: document.getElementById('tvl-input-date').value,
    code: document.getElementById('tvl-input-code').value
  };
  await Api.Travel.requestFlight(data);
  showNotification('Ticket Requested', `Flight booking logged successfully`, 'success');
  e.target.reset();
  switchTvlTab('tvl-tracking-view');
  await renderTravelView();
}

// 4. FORM HANDLERS
async function handleComplaintSubmit(e) {
  e.preventDefault();
  const data = {
    cat: document.getElementById('cmp-cat').value,
    state: document.getElementById('cmp-state').value,
    by: document.getElementById('cmp-anon').value === 'Anonymous' ? 'Anonymous' : AppState.users[AppState.currentRole].name,
    desc: document.getElementById('cmp-desc').value
  };
  await Api.Complaints.create(data);
  showNotification('Complaint Logged', `Grievance registered in compliance repository`, 'success');
  closeModal('modal-complaint');
  e.target.reset();
  await renderComplaintsView();
  await renderDashboardView();
}

async function handleCapSubmit(e) {
  e.preventDefault();
  const data = {
    issue: document.getElementById('cap-issue').value,
    state: document.getElementById('cap-state').value,
    linked: document.getElementById('cap-linked').value || 'Direct Audit',
    resp: document.getElementById('cap-resp').value,
    deadline: document.getElementById('cap-deadline').value
  };
  await Api.Caps.create(data);
  showNotification('CAP Issued', `Corrective Action Plan generated`, 'success');
  closeModal('modal-cap');
  e.target.reset();
  await renderCapView();
  await renderDashboardView();
}

async function handlePdpObjSubmit(e) {
  e.preventDefault();
  const data = {
    staff: 'Fatima Bello',
    title: document.getElementById('pdp-obj-title').value,
    weight: parseInt(document.getElementById('pdp-obj-weight').value)||25,
    quarter: document.getElementById('pdp-obj-quarter').value
  };
  await Api.Pdp.addObjective(data);
  showNotification('Objective Saved', 'New objective added to your PDP plan', 'success');
  closeModal('modal-pdp-obj');
  e.target.reset();
  await renderPdpView();
}

async function handlePdpInnSubmit(e) {
  e.preventDefault();
  const data = {
    staff: 'Fatima Bello',
    title: document.getElementById('pdp-inn-title').value,
    desc: document.getElementById('pdp-inn-desc').value
  };
  await Api.Pdp.submitInnovation(data);
  showNotification('Innovation Submitted', 'Initiative routed to HOD for evaluation', 'success');
  closeModal('modal-pdp-inn');
  e.target.reset();
  await renderPdpView();
}

async function handleFieldUpdateSubmit(e) {
  e.preventDefault();
  const data = {
    state: document.getElementById('fld-state').value,
    status: document.getElementById('fld-stat').value,
    challenges: document.getElementById('fld-challenges').value,
    mitigations: document.getElementById('fld-mitigations').value,
    by: AppState.users[AppState.currentRole].name
  };
  await Api.States.submitFieldUpdate(data);
  showNotification('Field Update Logged', 'State cluster compliance update recorded', 'success');
  closeModal('modal-field-update');
  e.target.reset();
  await renderStatesView();
}

async function handleRiskSubmit(e) {
  e.preventDefault();
  const like = parseInt(document.getElementById('rsk-like').value);
  const impact = parseInt(document.getElementById('rsk-impact').value);
  const score = like * impact;
  let rating = 'Medium';
  if (score >= 15) rating = 'Critical';
  else if (score >= 10) rating = 'High';
  else if (score < 4) rating = 'Low';

  const data = {
    desc: document.getElementById('rsk-desc').value,
    cat: document.getElementById('rsk-cat').value,
    owner: document.getElementById('rsk-owner').value,
    like: like,
    impact: impact,
    rating: rating
  };
  await Api.Risks.create(data);
  showNotification('Risk Registered', `Logged into risk matrix and register`, 'success');
  closeModal('modal-risk');
  e.target.reset();
  await renderRiskView();
}

async function handlePolicySubmit(e) {
  e.preventDefault();
  const data = {
    title: document.getElementById('pol-title').value,
    cat: document.getElementById('pol-cat').value,
    ver: document.getElementById('pol-ver').value
  };
  await Api.Policies.create(data);
  showNotification('Policy Published', `${data.title} published for staff acknowledgment`, 'success');
  closeModal('modal-policy');
  e.target.reset();
  await renderPolicyView();
}

async function handleLessonSubmit(e) {
  e.preventDefault();
  const data = {
    cat: document.getElementById('ll-cat').value,
    ref: document.getElementById('ll-ref').value || 'Audit Review',
    text: document.getElementById('ll-text').value
  };
  await Api.Lessons.create(data);
  showNotification('Lesson Logged', `Saved to knowledge base repository`, 'success');
  closeModal('modal-lesson');
  e.target.reset();
  await renderLessonsView();
}

async function handleTrainingSubmit(e) {
  e.preventDefault();
  const data = {
    title: document.getElementById('tr-title').value,
    aud: document.getElementById('tr-aud').value,
    deadline: document.getElementById('tr-date').value || '30 Jun 2026'
  };
  await Api.Trainings.create(data);
  showNotification('Training Created', `${data.title} published to Academy`, 'success');
  closeModal('modal-training');
  e.target.reset();
  await renderTrainingView();
}

async function handleInvestigationSubmit(e) {
  e.preventDefault();
  const data = {
    title: document.getElementById('inv-title').value,
    state: document.getElementById('inv-state').value,
    lead: document.getElementById('inv-lead').value,
    scope: document.getElementById('inv-scope').value
  };
  await Api.Investigations.create(data);
  showNotification('Investigation Authorized', `Case opened with assigned lead`, 'success');
  closeModal('modal-investigation');
  e.target.reset();
  await renderInvestigationsView();
}

// 5. AI ENGINE
function setAiMode(mode) {
  document.getElementById('ai-mode-dir').classList.toggle('active', mode === 'director');
  document.getElementById('ai-mode-stf').classList.toggle('active', mode === 'staff');
  document.getElementById('ai-badge-dir').style.display = mode === 'director' ? 'block' : 'none';
  document.getElementById('ai-badge-stf').style.display = mode === 'staff' ? 'block' : 'none';
}

function askQuickPrompt(text) {
  document.getElementById('ai-user-input').value = text;
  sendAiMessage();
}

function sendAiMessage() {
  const input = document.getElementById('ai-user-input');
  const text = input.value.trim();
  if (!text) return;

  const log = document.getElementById('ai-chat-messages');
  const uMsg = document.createElement('div');
  uMsg.className = 'ai-msg from-user';
  uMsg.textContent = text;
  log.appendChild(uMsg);
  input.value = '';

  setTimeout(() => {
    const bMsg = document.createElement('div');
    bMsg.className = 'ai-msg from-ai';
    const low = text.toLowerCase();
    if (low.includes('procurement') || low.includes('quote')) {
      bMsg.innerHTML = `<strong>🛒 Procurement Rule (2 CFR 200):</strong> All field purchases above ₦50,000 mandate 3 independent competitive quotes. Dual authorization from HOD and Compliance is required for advances above ₦100,000.`;
    } else if (low.includes('flight') || low.includes('boarding') || low.includes('travel')) {
      bMsg.innerHTML = `<strong>✈️ Travel & Ticket Directive:</strong> Staff must upload physical or digital boarding passes directly online under the ticket record within <strong>48 hours of flight completion</strong> to unlock vendor payment clearance.`;
    } else if (low.includes('pdp') || low.includes('behavioral') || low.includes('weight')) {
      bMsg.innerHTML = `<strong>🎯 PDP Structure:</strong> Annual score is composed of: Key Work Objectives (60%), Supervisor Monthly Behavioral Evaluation (30%), and HOD Creative Innovation (10%).`;
    } else {
      bMsg.innerHTML = `<strong>🤖 Compliance Assistant:</strong> According to CCCRN Standard Operating Procedures, all staff are protected under the Whistleblower Policy with zero retaliation tolerance. Please consult the Compliance Directorate for complex cases.`;
    }
    log.appendChild(bMsg);
    log.scrollTop = log.scrollHeight;
  }, 600);
}

function runAiReview() {
  const container = document.getElementById('air-findings-container');
  container.innerHTML = `
    <div style="font-family:'Syne',sans-serif; font-weight:700; color:var(--danger); margin-bottom:8px; font-size:13px;">⚠️ 2 High-Risk Non-Compliances Detected:</div>
    <div style="margin-bottom:8px; line-height:1.45;">
      🔴 <strong>Clause 1 (40% Advance Payment without Bank Guarantee):</strong> Violates USAID Mandatory Standard Provisions on recipient financial risk management.
    </div>
    <div style="margin-bottom:8px; line-height:1.45;">
      🟡 <strong>Clause 2 (Single-Signatory Delivery Sign-off):</strong> Violates CCCRN Dual-Authorization Procurement Standard v2.0.
    </div>
    <div style="color:var(--text-muted); font-size:11px;">Recommended Action: Mandate advance bank guarantee and dual delivery inspection sign-offs prior to contract execution.</div>
  `;
  showNotification('Analysis Complete', '2 regulatory risks identified', 'warning');
}

function generateExecutiveReport() {
  const s = document.getElementById('report-type-select');
  showNotification('Report Generated', `Downloaded ${s.value}`, 'success');
}

// 6. MODAL & TOAST HELPERS
function showModal(id) {
  const m = document.getElementById(id);
  if (m) m.classList.add('open');
}

function closeModal(id) {
  const m = document.getElementById(id);
  if (m) m.classList.remove('open');
}

function showNotification(title, msg, type = 'success') {
  const n = document.getElementById('notif');
  const tEl = document.getElementById('notif-title');
  const mEl = document.getElementById('notif-msg');
  if (!n) return;

  n.className = `notif show ${type}`;
  tEl.textContent = title;
  mEl.textContent = msg;

  setTimeout(() => {
    n.classList.remove('show');
  }, 3500);
}

document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open');
  }
});

// Start on DOM ready
window.addEventListener('DOMContentLoaded', initApp);
