# CCCRN ComplianceIQ™ — Attendify Integration & Handover Package

> **Target Audience**: Attendify Engineering & Frontend Development Team  
> **Prepared by**: CCCRN ComplianceIQ Core Systems Engineering  
> **Module Name**: ComplianceIQ Staff Compliance & Workforce Feature (`v2.4-independent`)  
> **Status**: Ready for Production Deployment & Embedding  

---

## 1. Executive Summary & Architecture

The **Staff Compliance & Workforce Feature** has been built as a **100% independent, standalone, plug-and-play portal/feature module**. Because it does not require direct access to Attendify's internal codebase, the Attendify development team can integrate it into their platform effortlessly via **secure iframe embed**, **micro-frontend component**, or direct SSO deep-linking.

### What the Feature Provides:
1. **Biometric Attendance Status & Daily Clock**: Real-time arrival verification and biometric punch sync (`BIO-LOS-01`).
2. **2-Tier Leave Desk**: Staff leave entitlements calculation (Annual, Casual, Sick), reliever assignments, and 2-tier approval workflow (Supervisor Authentication $\rightarrow$ HR Final Approval).
3. **Confidential Complaints & Whistleblowing**: Zero-retaliation grievance logging with severity classification and real-time status tracking.
4. **Corrective Action Plans (CAP)**: Institutional audit tracking and verifiable State Evidence document upload.
5. **Professional Development Plan (PDP - 150 Point Matrix)**: 6-dimensional institutional performance evaluation (Objectives, Key Deliverables, Leadership, Innovation, Supervisor Verification, HOD Sign-off).
6. **Compliance Training Academy**: Mandatory institutional certifications, video course modules, and real-time attendance verification.
7. **Institutional Policy Repository**: Versioned policy distribution (PSEA, Whistleblower, Financial Controls) with digital sign-off and acknowledgement stamps.
8. **Lessons Learned & Field Retrospectives**: Institutional knowledge repository for root-cause preventative practices.
9. **AI Compliance Helpdesk**: Real-time intelligent compliance advisor trained on CCCRN standard operating procedures.

---

## 2. Integration Options for Attendify

### Option A: Clean Iframe Embed (Recommended — 5 Minutes Setup)

Attendify can embed the feature anywhere inside the Attendify application layout:

```html
<!-- Attendify Host Container -->
<div class="attendify-compliance-wrapper" style="width: 100%; min-height: 850px; border: none;">
  <iframe 
    id="complianceIframe"
    src="https://compliance.cccrn.org/attendify-feature"
    style="width: 100%; height: 100%; min-height: 850px; border: 0;"
    title="CCCRN ComplianceIQ Staff Portal"
    allow="geolocation; camera">
  </iframe>
</div>
```

---

## 3. Bidirectional PostMessage SSO Protocol

The embedded ComplianceIQ feature dynamically adapts its identity, permissions, and duty station based on the logged-in Attendify user.

Attendify sends a standard JavaScript `postMessage` upon rendering or user switch:

```javascript
// Example Attendify host script
const iframe = document.getElementById('complianceIframe');

iframe.onload = function() {
  iframe.contentWindow.postMessage({
    action: 'SET_USER_CONTEXT',
    payload: {
      name: 'Fatima Bello',
      id: 'CCCRN-STF-0482',
      email: 'fatima.bello@cccrn.org',
      role: 'staff',                  // 'staff' | 'supervisor' | 'hod' | 'stl'
      dept: 'Clinical Services',
      state: 'Lagos — Cluster A',
      supervisor: 'Dr. Ngozi Adeyemi',
      avatar: 'FB'
    }
  }, '*');
};
```

### Supported Role Perspectives:
- **'staff'**: Personal Leave desk, Biometric Clock, Log Complaints, Submit CAP Evidence, PDP self-scoring, Academy courses.
- **'supervisor'**: Unlocks Supervisor Authentication Queue for pending supervisee leaves (Tier-1) + PDP Supervisor evaluation desk.
- **'hod'**: Supervisee leave approval escalation + HOD PDP institutional sign-off.
- **'stl'**: Unlocks State Cluster Office monitoring desk and regional training consoles.

---

## 4. Deep-Linking & Sub-Tab Control via Host

Attendify navigation bars or notifications can programmatically switch tabs within the embedded module without reloading the iframe:

```javascript
function navigateComplianceModule(targetModule) {
  const iframe = document.getElementById('complianceIframe');
  iframe.contentWindow.postMessage({
    action: 'SWITCH_TAB',
    payload: targetModule // 'leave' | 'complaints' | 'cap' | 'pdp' | 'training' | 'policies' | 'lessons' | 'ai'
  }, '*');
}
```

---

## 5. API Endpoints for Headless Sync (Optional)

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/backend/data` | Fetches consolidated leaves, complaints, and CAPs |
| `POST` | `/api/leave/apply` | Submits a leave request into ComplianceIQ |
| `POST` | `/api/leave/action` | Tier-1 / Tier-2 approval action |
| `POST` | `/api/attendance/clock` | Syncs biometric attendance clock punch |

---

## 6. Verification & Test Endpoints

- **Independent Feature URL**: http://localhost:8000/attendify-feature
- **Direct Portal Route**: http://localhost:8000/staff-portal
- **Standard Staff Route**: http://localhost:8000/staff
- **Attendify Host Integration Simulator**: http://localhost:8000/identify
