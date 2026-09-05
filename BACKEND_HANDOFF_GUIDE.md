# CCCRN Compliance Management System — Backend Developer Handoff Guide

This guide provides the complete API specifications, data models, and database schema for the backend developer (PHP / MySQL) to connect with the frontend application.

---

## 📁 Project Structure

```text
ccrn/
├── index.html                   <-- Front-end entry point
├── css/
│   └── styles.css               <-- Modular design system & styling
├── js/
│   ├── api.js                   <-- API Client layer (configured with mock & fetch)
│   └── app.js                   <-- Application controller & RBAC view renderer
├── schema.sql                   <-- MySQL Database Schema & Seed Data
└── BACKEND_HANDOFF_GUIDE.md     <-- This API reference guide
```

---

## ⚡ How to Switch from Mock Data to Live PHP Backend

In [js/api.js](file:///c:/Users/ferragotta/Downloads/ccrn/js/api.js#L7-L10), change:
```javascript
const ApiConfig = {
  USE_MOCK: false,          // <-- Set to false to enable live PHP requests
  BASE_URL: '/api'          // <-- Path to your PHP endpoints folder
};
```

---

## 🔐 Authentication & Role Permissions

Each request should validate the authenticated user session or token (`$_SESSION['user_role']` or Bearer JWT).

| Role Key | Name | Allowed Modules |
|---|---|---|
| `doc` | Director of Compliance (Admin) | Full Access (All 14 Modules) |
| `compliance_officer` | Compliance Officer / Specialist | Complaints, CAP, Training, States, Risk, Lessons, AI Review, Investigations, Travel |
| `hr` | Human Resources (HR) | Complaints (View), CAP (View), PDP (Audit Master), Training, States (View), Policy (Admin), Lessons, Investigations (View) |
| `staff` | General Staff (Individual) | Complaints (Log & Own History), CAP (Submit Evidence), PDP (Objectives/Innovation), Training, Policy (Sign-off), Lessons, AI Assistant, Travel |
| `supervisor` | Supervisor | Staff Modules + Supervisee PDP Approval & Monthly Behavioral Grading |
| `hod` | Head of Department | Staff Modules + Departmental Staff Creative Innovation Grading |
| `stl` | State Team Lead | Staff Modules + Field Updates Submission & State Training Compliance Tracking |

---

## 📡 REST API Endpoint Specifications

### 1. Complaints API (`/api/complaints.php`)

#### `GET /api/complaints.php`
* **RBAC Rule**: `staff` sees only their own complaints + anonymous submissions. Other roles see all complaints.
* **Response `200 OK`**:
```json
[
  {
    "id": "CMP-048",
    "cat": "Procurement",
    "state": "Kano",
    "by": "Anonymous",
    "date": "28 Feb 2026",
    "desc": "Direct sourcing of generator diesel without 3 comparative vendor quotes.",
    "status": "Open"
  }
]
```

#### `POST /api/complaints.php`
* **Request Payload**:
```json
{
  "cat": "Procurement",
  "state": "Kano",
  "by": "Fatima Bello",
  "desc": "Vendor delivery without inspection certificate."
}
```
* **Response `201 Created`**:
```json
{
  "success": true,
  "id": "CMP-049"
}
```

#### `POST /api/complaints.php?action=convert_to_cap`
* **Request Payload**: `{"cmpId": "CMP-048"}`
* **Response `200 OK`**: Updates complaint status to `'Converted to CAP'` and inserts new record into `caps` table.

---

### 2. Corrective Action Plans API (`/api/cap.php`)

#### `GET /api/cap.php`
* **Response `200 OK`**:
```json
[
  {
    "id": "CAP-032",
    "issue": "Single-quotation fuel supply breach",
    "state": "Kano",
    "linked": "CMP-048",
    "resp": "Biodun Ojo",
    "deadline": "2026-03-15",
    "status": "Open",
    "ev": null
  }
]
```

#### `POST /api/cap.php?action=submit_evidence`
* **Request Payload**:
```json
{
  "capId": "CAP-032",
  "fileRef": "Kano_Fuel_Audit_Signoff_Signed.pdf",
  "notes": "Completed dual quotation validation on ground."
}
```
* **Response `200 OK`**: Updates CAP status to `'Evidence Submitted'`.

#### `POST /api/cap.php?action=close&id=CAP-032`
* **RBAC Rule**: Restricted to `doc` and `compliance_officer`.
* **Response `200 OK`**: Updates status to `'Verified'`.

---

### 3. PDP (Performance Development Plan) API (`/api/pdp.php`)

#### `GET /api/pdp.php?action=objectives&staff=Fatima%20Bello`
* **Response `200 OK`**:
```json
[
  {
    "id": "OBJ-101",
    "staff": "Fatima Bello",
    "title": "Achieve 100% adherence to clinical audit checklists",
    "weight": 30,
    "quarter": "Q1 2026",
    "approved": true,
    "ev": "Checklist_Signoff_Q1.pdf",
    "score": 28.00
  }
]
```

#### `POST /api/pdp.php?action=add_objective`
* **Request Payload**:
```json
{
  "staff": "Fatima Bello",
  "title": "Conduct 4 community safeguarding sessions",
  "weight": 25,
  "quarter": "Q2 2026"
}
```

#### `POST /api/pdp.php?action=grade_behavioral`
* **Request Payload**:
```json
{
  "staff": "Fatima Bello",
  "month": "February 2026",
  "compliance_mindset": 85,
  "teamwork": 90,
  "communication": 80,
  "punctuality": 88,
  "initiative": 82
}
```

#### `POST /api/pdp.php?action=grade_innovation`
* **Request Payload**:
```json
{
  "innId": "INN-01",
  "score": 8.5,
  "feedback": "Approved for deployment across Kano cluster."
}
```

---

### 4. Travel & Flight Tickets Compliance API (`/api/travel.php`)

#### `GET /api/travel.php`
* **Response `200 OK`**:
```json
[
  {
    "id": "TKT-1042",
    "name": "Dr. Ngozi Eze",
    "route": "ABV - PHC - ABV",
    "date": "2026-02-20",
    "code": "USAID-CCCRN-01",
    "status": "Utilized",
    "bp": "PHC_BoardingPass_Verified.pdf",
    "pay": "Cleared"
  }
]
```

#### `POST /api/travel.php` (Request Ticket)
* **Request Payload**:
```json
{
  "name": "Fatima Bello",
  "route": "ABV - KAN - ABV",
  "date": "2026-03-12",
  "code": "USAID-CCCRN-02",
  "purpose": "Quarterly clinical compliance review."
}
```

#### `POST /api/travel.php?action=upload_bp` (Boarding Pass Upload)
* **Request Payload**:
```json
{
  "tktId": "TKT-1043",
  "fileRef": "Fatima_Bello_BoardingPass_Scan.pdf"
}
```
* **Response `200 OK`**: Sets `ticket_status = 'Utilized'` and `payment_status = 'Ready for Vendor Clearance'`.

#### `POST /api/travel.php?action=clear_payment&id=TKT-1043`
* **RBAC Rule**: Restricted to `doc` and `compliance_officer`.
* **Response `200 OK`**: Sets `payment_status = 'Cleared'`.

---

### 5. Policy & Digital Sign-Off API (`/api/policies.php`)

#### `GET /api/policies.php`
* Returns policies with acknowledgment percentage and user-specific acknowledgment status (`myAck: true/false`).

#### `POST /api/policies.php?action=sign&id=POL-04`
* Records timestamped electronic signature in `policy_acknowledgments`.

---

## 🗄️ Database Schema Installation

Run the provided [schema.sql](file:///c:/Users/ferragotta/Downloads/ccrn/schema.sql) file directly in MySQL / MariaDB / phpMyAdmin:
```bash
mysql -u username -p < schema.sql
```
