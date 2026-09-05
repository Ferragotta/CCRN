<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComplianceIQ™ | Admin Hub — Attendify Pro™</title>
    
    <!-- Attendify Pro / Bootstrap & Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #02367B;
            --primary-dark: #012454;
            --primary-light: rgba(2, 54, 123, 0.08);
            --accent: #0077b6;
            --accent-cyan: #55E2E9;
            --danger: #dc2626;
            --warning: #d97706;
            --success: #059669;
            --purple: #7c3aed;
            --surface: #ffffff;
            --bg: #f4f6f9;
            --border: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Top Hub Navbar */
        .hub-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 10px 24px;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .hub-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--primary);
        }
        .hub-brand img {
            height: 38px;
        }
        .hub-title {
            font-size: 16px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--primary);
        }
        .hub-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Sub-Header Banner */
        .hub-hero {
            background: linear-gradient(135deg, #022b61 0%, #02367B 70%, #006CA5 100%);
            color: #ffffff;
            padding: 22px 28px;
            margin-bottom: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(2, 54, 123, 0.12);
        }

        /* KPI Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.06);
        }
        .stat-card.border-accent { border-left: 4px solid var(--primary); }
        .stat-card.border-danger { border-left: 4px solid var(--danger); }
        .stat-card.border-warning { border-left: 4px solid var(--warning); }
        .stat-card.border-success { border-left: 4px solid var(--success); }
        .stat-card.border-purple { border-left: 4px solid var(--purple); }

        .stat-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
        }
        .stat-val {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
        }
        .stat-sub {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Navigation Tabs styled like Attendify PS Admin */
        .nav-hub-tabs {
            border-bottom: 2px solid var(--border);
            gap: 4px;
            margin-bottom: 20px;
        }
        .nav-hub-tabs .nav-link {
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 13px;
            padding: 10px 18px;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .nav-hub-tabs .nav-link:hover {
            color: var(--primary);
            background: rgba(2, 54, 123, 0.04);
        }
        .nav-hub-tabs .nav-link.active {
            color: var(--primary);
            background: #ffffff;
            border-bottom: 3px solid var(--primary);
            font-weight: 700;
        }

        /* Tables matching Attendify Pro Admin Hub */
        .hub-table-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }
        .hub-table-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .hub-table {
            width: 100%;
            margin-bottom: 0;
            font-size: 12px;
            vertical-align: middle;
        }
        .hub-table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            white-space: nowrap;
        }
        .hub-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .hub-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Status Pills */
        .pill-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-transform: uppercase;
        }
        .pill-open { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
        .pill-progress { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
        .pill-closed { background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; }
        .pill-purple { background: #ede9fe; color: #7c3aed; border: 1px solid #ddd6fe; }
        .pill-blue { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }

        /* Role Switcher in Topbar */
        .role-pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .role-doc { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
        .role-superadmin { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .role-compliance { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .role-hr { background: #fdf4ff; color: #86198f; border: 1px solid #f5d0fe; }

        .btn-hub-primary {
            background: var(--primary);
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-hub-primary:hover {
            background: var(--primary-dark);
            color: #ffffff;
        }

        .btn-hub-outline {
            background: #ffffff;
            color: var(--primary);
            border: 1px solid var(--border);
            font-weight: 600;
            font-size: 12px;
            padding: 8px 14px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-hub-outline:hover {
            background: #f8fafc;
            border-color: var(--primary);
        }

        /* 5x5 Heatmap Mini Matrix */
        .heatmap-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 4px;
            text-align: center;
            font-size: 10px;
            font-weight: 700;
        }
        .heatmap-cell {
            padding: 8px 4px;
            border-radius: 4px;
            line-height: 1.2;
        }

        .search-box {
            position: relative;
            width: 260px;
        }
        .search-box i {
            position: absolute;
            left: 10px;
            top: 10px;
            color: var(--text-muted);
            font-size: 12px;
        }
        .search-box input {
            padding: 6px 10px 6px 30px;
            font-size: 12px;
            border-radius: 6px;
            border: 1px solid var(--border);
            width: 100%;
            outline: none;
        }
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(2, 54, 123, 0.1);
        }
    </style>
</head>
<body>

    <!-- TOP HUB NAVBAR (Attendify Pro Style) -->
    <nav class="hub-navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            
            <!-- Brand -->
            <a href="/admin/hub" class="hub-brand">
                <img src="/assets/images/logo.png" alt="CCCRN Logo">
                <div>
                    <div class="hub-title">ComplianceIQ™ <span style="font-size: 11px; background: #55E2E9; color: #02367B; padding: 2px 6px; border-radius: 4px; font-weight: 800;">ADMIN HUB</span></div>
                    <div class="hub-subtitle">AttendIQ™ · Attendify Pro™ Ecosystem</div>
                </div>
            </a>

            <!-- Right Controls: Role Switcher & User Profile -->
            <div class="d-flex align-items-center gap-3">
                
                <!-- State / Cluster Filter -->
                <div class="d-none d-md-flex align-items-center gap-2">
                    <span class="text-muted small fw-bold"><i class="bi bi-geo-alt"></i> Cluster:</span>
                    <select id="hubStateFilter" onchange="filterHubData()" class="form-select form-select-sm" style="font-size: 12px; width: 150px;">
                        <option value="">All 6 States</option>
                        <option value="Lagos">Lagos (Cluster A)</option>
                        <option value="Kano">Kano (Cluster B)</option>
                        <option value="Rivers">Rivers (Cluster C)</option>
                        <option value="Abuja">Abuja FCT</option>
                        <option value="Kaduna">Kaduna</option>
                        <option value="Borno">Borno</option>
                    </select>
                </div>

                <!-- Role Simulator Switcher for instant multi-role testing -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" id="roleBadgeBtn">
                        <span class="role-pill role-doc" id="navRolePill">
                            <i class="bi bi-shield-lock-fill"></i> Director of Compliance
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 12px;">
                        <li class="dropdown-header text-uppercase fw-bold text-muted" style="font-size: 10px;">Switch Designated Portal</li>
                        <li><a class="dropdown-item" href="javascript:switchHubRole('doc')"><i class="bi bi-shield-shaded text-danger me-2"></i> Director of Compliance (DoC)</a></li>
                        <li><a class="dropdown-item" href="javascript:switchHubRole('superadmin')"><i class="bi bi-key-fill text-dark me-2"></i> Super Administrator</a></li>
                        <li><a class="dropdown-item" href="javascript:switchHubRole('compliance')"><i class="bi bi-shield-check text-warning me-2"></i> Compliance Specialist</a></li>
                        <li><a class="dropdown-item" href="javascript:switchHubRole('hr')"><i class="bi bi-people-fill text-purple me-2"></i> HR Manager</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i> Sign Out to Admin Login</a></li>
                    </ul>
                </div>

                <!-- Return to Attendify App link -->
                <a href="/attendify/test-harness" class="btn btn-sm btn-hub-outline d-none d-lg-inline-flex" title="View Attendify User App Simulation">
                    <i class="bi bi-arrow-up-right-square"></i> Host App View
                </a>
            </div>

        </div>
    </nav>

    <!-- MAIN HUB CONTAINER -->
    <div class="container-fluid px-4 py-3">

        <!-- HERO BANNER: Role-Tailored Announcement -->
        <div class="hub-hero d-flex justify-content-between align-items-center flex-wrap gap-3" id="hubHeroBanner">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge" style="background: #55E2E9; color: #02367B; font-weight: 800; font-size: 10px; text-transform: uppercase;" id="heroBadge">Executive Authority</span>
                    <span style="font-size: 12px; opacity: 0.85;">Quarter 1, COP FY2026 Live Audit Cycle</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" id="heroTitle">Director of Compliance — Executive Command Center</h3>
                <p class="mb-0 text-white-50 small" id="heroDesc">
                    Institutional oversight, fraud detection, board escalations, and cross-state compliance analytics.
                </p>
            </div>
            
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-sm btn-light fw-bold" onclick="alert('Exporting Institutional Master Compliance Dossier (Q1 FY2026)...')">
                    <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> Export Dossier
                </button>
                <button class="btn btn-sm btn-hub-primary" onclick="openAdminModal('modalNewEscalation')">
                    <i class="bi bi-plus-circle me-1"></i> Log Institutional Action
                </button>
            </div>
        </div>

        <!-- STAT TILES ROW (Dynamically adapted by Role) -->
        <div class="row g-3 mb-4" id="statTilesRow">
            <!-- Injected dynamically via JS based on active role -->
        </div>

        <!-- PORTAL CONTENT TABS -->
        <ul class="nav nav-hub-tabs" id="hubMainTabs" role="tablist">
            <!-- Dynamic tab buttons injected based on active role -->
        </ul>

        <!-- TAB PANES -->
        <div class="tab-content" id="hubTabContent">
            
            <!-- ============================================================== -->
            <!-- 1. EXECUTIVE COMMAND TAB (DoC & Super Admin) -->
            <!-- ============================================================== -->
            <div class="tab-pane fade show active" id="pane-executive" role="tabpanel">
                
                <!-- 2-Column Analytics Overview -->
                <div class="row g-3 mb-4">
                    <!-- 6-Month Trajectory -->
                    <div class="col-lg-8">
                        <div class="hub-table-card h-100 mb-0">
                            <div class="hub-table-header">
                                <div class="fw-bold text-dark"><i class="bi bi-graph-up-arrow text-primary me-2"></i> 6-Month Incident Trajectory & Category Distribution</div>
                                <span class="badge bg-light text-muted border">Oct 2025 – Mar 2026</span>
                            </div>
                            <div class="p-3">
                                <div style="height: 120px; width: 100%;">
                                    <svg viewBox="0 0 500 110" style="width: 100%; height: 100%;">
                                        <defs>
                                            <linearGradient id="hubCurveGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                                <stop offset="0%" stop-color="#02367B" stop-opacity="0.2" />
                                                <stop offset="100%" stop-color="#02367B" stop-opacity="0.0" />
                                            </linearGradient>
                                        </defs>
                                        <path d="M 20 85 Q 110 25 200 60 T 380 35 T 480 15 L 480 110 L 20 110 Z" fill="url(#hubCurveGrad)" />
                                        <path d="M 20 85 Q 110 25 200 60 T 380 35 T 480 15" fill="none" stroke="#02367B" stroke-width="3" />
                                        <circle cx="20" cy="85" r="4" fill="#02367B" />
                                        <circle cx="110" cy="25" r="4" fill="#02367B" />
                                        <circle cx="200" cy="60" r="4" fill="#02367B" />
                                        <circle cx="290" cy="45" r="4" fill="#02367B" />
                                        <circle cx="380" cy="35" r="4" fill="#02367B" />
                                        <circle cx="480" cy="15" r="5" fill="#dc2626" />
                                    </svg>
                                </div>
                                <div class="row text-center pt-2 border-top g-2">
                                    <div class="col-3"><div class="text-muted small fw-bold">FRAUD / FINANCE</div><div class="fw-bold text-danger">14 Cases</div></div>
                                    <div class="col-3"><div class="text-muted small fw-bold">MISCONDUCT</div><div class="fw-bold text-warning">11 Cases</div></div>
                                    <div class="col-3"><div class="text-muted small fw-bold">POLICY BREACH</div><div class="fw-bold text-primary">13 Cases</div></div>
                                    <div class="col-3"><div class="text-muted small fw-bold">PSEA / ETHICS</div><div class="fw-bold text-purple">9 Cases</div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compliance Index Radial -->
                    <div class="col-lg-4">
                        <div class="hub-table-card h-100 mb-0 text-center d-flex flex-column justify-content-between p-3">
                            <div class="fw-bold text-dark text-start mb-2"><i class="bi bi-speedometer2 text-primary me-2"></i> Compliance Health Index</div>
                            
                            <div style="position: relative; width: 130px; height: 130px; margin: 0 auto;">
                                <svg viewBox="0 0 36 36" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e2e8f0" stroke-width="3.5" />
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#02367B" stroke-dasharray="64, 100" stroke-width="3.5" stroke-linecap="round" />
                                </svg>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <div style="font-size: 24px; font-weight: 800; color: var(--primary);">64%</div>
                                    <div style="font-size: 9px; color: var(--text-muted); font-weight: 700;">ON TRACK</div>
                                </div>
                            </div>

                            <div class="bg-light p-2 rounded text-muted small mt-2">
                                Target: <strong>85%</strong> by Q3 · Statutory USAID 2 CFR 200 benchmark
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Priority Incident Escalations Table -->
                <div class="hub-table-card">
                    <div class="hub-table-header">
                        <div>
                            <div class="fw-bold text-dark"><i class="bi bi-exclamation-octagon-fill text-danger me-2"></i> High Priority Escalations Awaiting Executive Action</div>
                            <div class="text-muted small">Whistleblower fraud alerts and safeguarding referrals under executive oversight</div>
                        </div>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search escalations..." onkeyup="searchTable(this, 'tableExecEscalations')">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table hub-table" id="tableExecEscalations">
                            <thead>
                                <tr>
                                    <th>Incident Ref</th>
                                    <th>State Cluster</th>
                                    <th>Category</th>
                                    <th>Severity</th>
                                    <th>Allegation Summary</th>
                                    <th>Days Open</th>
                                    <th>Status</th>
                                    <th>Executive Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="text-primary">CMP-048</strong></td>
                                    <td><span class="badge bg-light text-dark border">Kano (Cluster B)</span></td>
                                    <td>Finance / Fraud</td>
                                    <td><span class="pill-badge pill-open">Critical</span></td>
                                    <td>Altered bank vouchers during bulk pharmaceutical procurement</td>
                                    <td><span class="text-danger fw-bold">45 Days</span></td>
                                    <td><span class="pill-badge pill-open">Under Investigation</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-hub-outline py-1 px-2" onclick="alert('Opening Full Confidential Dossier for CMP-048 (Forensic Vault).')">
                                            <i class="bi bi-folder2-open me-1"></i> Review Dossier
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary">CMP-047</strong></td>
                                    <td><span class="badge bg-light text-dark border">Lagos (Cluster A)</span></td>
                                    <td>Misconduct / Ethics</td>
                                    <td><span class="pill-badge pill-progress">High</span></td>
                                    <td>Procurement committee conflict of interest & favoritism</td>
                                    <td>12 Days</td>
                                    <td><span class="pill-badge pill-progress">Evidence Review</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-hub-outline py-1 px-2" onclick="alert('Routing CMP-047 to Disciplinary Committee.')">
                                            <i class="bi bi-arrow-right-circle me-1"></i> Triage
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary">CMP-043</strong></td>
                                    <td><span class="badge bg-light text-dark border">Borno Office</span></td>
                                    <td>Safeguarding / PSEA</td>
                                    <td><span class="pill-badge pill-open">Critical</span></td>
                                    <td>Unreported safeguarding incident with retaliation risk</td>
                                    <td>30 Days</td>
                                    <td><span class="pill-badge pill-purple">Referred to Board</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-hub-outline py-1 px-2" onclick="alert('Viewing Board In-Camera Safeguarding Minutes for CMP-043.')">
                                            <i class="bi bi-shield-shaded me-1"></i> Board Minutes
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 2. COMPLIANCE & INVESTIGATIONS TAB (Compliance Specialist, DoC, Super Admin) -->
            <!-- ============================================================== -->
            <div class="tab-pane fade" id="pane-compliance" role="tabpanel">
                
                <div class="row g-3 mb-4">
                    <!-- ISO 31000 Mini Matrix -->
                    <div class="col-lg-6">
                        <div class="hub-table-card h-100 mb-0 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-bold text-dark"><i class="bi bi-grid-3x3-gap-fill text-warning me-2"></i> ISO 31000 Risk Heatmap (5×5)</div>
                                <span class="badge bg-danger">3 Critical Risks</span>
                            </div>
                            <div class="heatmap-grid">
                                <div class="heatmap-cell" style="background: #fef08a;">L1·I1</div>
                                <div class="heatmap-cell" style="background: #fef08a;">L1·I2</div>
                                <div class="heatmap-cell" style="background: #fed7aa;">L1·I3</div>
                                <div class="heatmap-cell" style="background: #fca5a5;">L1·I4</div>
                                <div class="heatmap-cell" style="background: #ef4444; color: #fff;">RSK-024 (1)</div>

                                <div class="heatmap-cell" style="background: #bbf7d0;">L2·I1</div>
                                <div class="heatmap-cell" style="background: #fef08a;">L2·I2</div>
                                <div class="heatmap-cell" style="background: #fed7aa;">RSK-022 (1)</div>
                                <div class="heatmap-cell" style="background: #fca5a5;">L2·I4</div>
                                <div class="heatmap-cell" style="background: #ef4444; color: #fff;">L2·I5</div>

                                <div class="heatmap-cell" style="background: #bbf7d0;">L3·I1</div>
                                <div class="heatmap-cell" style="background: #bbf7d0;">L3·I2</div>
                                <div class="heatmap-cell" style="background: #fef08a;">L3·I3</div>
                                <div class="heatmap-cell" style="background: #fed7aa;">RSK-023 (1)</div>
                                <div class="heatmap-cell" style="background: #fca5a5;">L3·I5</div>

                                <div class="heatmap-cell" style="background: #86efac;">L4·I1</div>
                                <div class="heatmap-cell" style="background: #bbf7d0;">L4·I2</div>
                                <div class="heatmap-cell" style="background: #fef08a;">L4·I3</div>
                                <div class="heatmap-cell" style="background: #fca5a5;">L4·I4</div>
                                <div class="heatmap-cell" style="background: #ef4444; color: #fff;">L4·I5 (2)</div>

                                <div class="heatmap-cell" style="background: #86efac;">L5·I1</div>
                                <div class="heatmap-cell" style="background: #86efac;">L5·I2</div>
                                <div class="heatmap-cell" style="background: #bbf7d0;">L5·I3</div>
                                <div class="heatmap-cell" style="background: #fef08a;">L5·I4</div>
                                <div class="heatmap-cell" style="background: #fca5a5;">L5·I5</div>
                            </div>
                        </div>
                    </div>

                    <!-- CAP Evidence Queue Summary -->
                    <div class="col-lg-6">
                        <div class="hub-table-card h-100 mb-0 p-3 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold text-dark"><i class="bi bi-check2-all text-success me-2"></i> CAP Evidence Review Queue</div>
                                    <span class="badge bg-warning text-dark">5 Awaiting Proof</span>
                                </div>
                                <p class="text-muted small mb-3">
                                    Corrective Action Plans generated from audit findings and complaints awaiting Specialist review.
                                </p>
                                <div class="d-flex flex-column gap-2">
                                    <div class="p-2 border rounded d-flex justify-content-between align-items-center bg-light">
                                        <div>
                                            <strong class="text-primary small">CAP-012</strong> · Centralize bank reconciliation (Kano)
                                        </div>
                                        <button class="btn btn-sm btn-outline-success py-0 px-2" onclick="alert('CAP-012 Evidence Approved.')">Approve</button>
                                    </div>
                                    <div class="p-2 border rounded d-flex justify-content-between align-items-center bg-light">
                                        <div>
                                            <strong class="text-primary small">CAP-011</strong> · Mandatory PSEA refreshers (Lagos)
                                        </div>
                                        <button class="btn btn-sm btn-outline-success py-0 px-2" onclick="alert('CAP-011 Evidence Approved.')">Approve</button>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2 border-top text-end mt-2">
                                <a href="/cap" class="small fw-bold text-decoration-none text-primary">Open Full CAP Module →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forensic Investigations Register -->
                <div class="hub-table-card">
                    <div class="hub-table-header">
                        <div>
                            <div class="fw-bold text-dark"><i class="bi bi-shield-lock text-primary me-2"></i> Forensic Investigations & Evidence Vault</div>
                            <div class="text-muted small">Active case files, investigator appointments, and subpoenaed documentation</div>
                        </div>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search case ref..." onkeyup="searchTable(this, 'tableInvestHub')">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table hub-table" id="tableInvestHub">
                            <thead>
                                <tr>
                                    <th>Case Ref</th>
                                    <th>Source</th>
                                    <th>State</th>
                                    <th>Allegation Details</th>
                                    <th>Assigned Lead</th>
                                    <th>Days Open</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="text-primary">INV-012</strong></td>
                                    <td>CMP-048</td>
                                    <td>Kano</td>
                                    <td>Financial Diversion & Altered Invoices</td>
                                    <td>A. Bello (Lead Auditor)</td>
                                    <td><span class="text-danger fw-bold">45 Days</span></td>
                                    <td><span class="pill-badge pill-open">Under Investigation</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-hub-outline py-1 px-2" onclick="alert('Evidence Vault: 14 documents attached.')">
                                            <i class="bi bi-folder-check me-1"></i> Evidence (14)
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary">INV-011</strong></td>
                                    <td>CMP-047</td>
                                    <td>Lagos</td>
                                    <td>Vendor Collusion in Regional Supply</td>
                                    <td>Amaka Obi</td>
                                    <td>12 Days</td>
                                    <td><span class="pill-badge pill-progress">Evidence Collection</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-hub-outline py-1 px-2" onclick="alert('Evidence Vault: 4 documents attached.')">
                                            <i class="bi bi-folder-check me-1"></i> Evidence (4)
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary">INV-010</strong></td>
                                    <td>CMP-043</td>
                                    <td>Borno</td>
                                    <td>PSEA Violation & Retaliation</td>
                                    <td>Emeka Eze</td>
                                    <td>30 Days</td>
                                    <td><span class="pill-badge pill-purple">Referred to Disciplinary</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-hub-outline py-1 px-2" onclick="alert('Viewing Disciplinary Board Minutes.')">
                                            <i class="bi bi-file-text me-1"></i> Minutes
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 3. WORKFORCE & HR AUDIT TAB (HR Manager, DoC, Super Admin) -->
            <!-- ============================================================== -->
            <div class="tab-pane fade" id="pane-hr" role="tabpanel">
                
                <!-- Training League & PDP Stats -->
                <div class="row g-3 mb-4">
                    <!-- State Training League Table -->
                    <div class="col-lg-6">
                        <div class="hub-table-card h-100 mb-0">
                            <div class="hub-table-header">
                                <div class="fw-bold text-dark"><i class="bi bi-mortarboard-fill text-primary me-2"></i> Mandatory Training Compliance by State</div>
                                <span class="badge bg-light text-muted border">490 Total Staff</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table hub-table">
                                    <thead>
                                        <tr>
                                            <th>State Office</th>
                                            <th>Staff</th>
                                            <th>Trained</th>
                                            <th>Compliance Rate</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Lagos</strong> (Cluster A)</td>
                                            <td>95</td>
                                            <td>82</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: 86%;"></div>
                                                </div>
                                                <small class="fw-bold">86%</small>
                                            </td>
                                            <td><span class="pill-badge pill-closed">Exemplary</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Abuja FCT</strong></td>
                                            <td>72</td>
                                            <td>54</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: 75%;"></div>
                                                </div>
                                                <small class="fw-bold">75%</small>
                                            </td>
                                            <td><span class="pill-badge pill-closed">On Track</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Rivers</strong> (Cluster C)</td>
                                            <td>68</td>
                                            <td>51</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: 75%;"></div>
                                                </div>
                                                <small class="fw-bold">75%</small>
                                            </td>
                                            <td><span class="pill-badge pill-closed">On Track</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kano</strong> (Cluster B)</td>
                                            <td>80</td>
                                            <td>41</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-warning" style="width: 51%;"></div>
                                                </div>
                                                <small class="fw-bold">51%</small>
                                            </td>
                                            <td><span class="pill-badge pill-progress">Action Needed</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Borno Office</strong></td>
                                            <td>65</td>
                                            <td>23</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-danger" style="width: 35%;"></div>
                                                </div>
                                                <small class="fw-bold text-danger">35%</small>
                                            </td>
                                            <td><span class="pill-badge pill-open">Critical Gap</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Policy Sign-offs -->
                    <div class="col-lg-6">
                        <div class="hub-table-card h-100 mb-0">
                            <div class="hub-table-header">
                                <div class="fw-bold text-dark"><i class="bi bi-file-earmark-check text-success me-2"></i> Statutory Policy Acknowledgment Rates</div>
                                <span class="badge bg-light text-muted border">FY2026 Core</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table hub-table">
                                    <thead>
                                        <tr>
                                            <th>Policy Code</th>
                                            <th>Title</th>
                                            <th>Signed %</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong class="text-primary">POL-PSEA-001</strong></td>
                                            <td>PSEA & Safeguarding Ethics</td>
                                            <td><strong class="text-success">94%</strong> (461/490)</td>
                                            <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Dispatched reminder to 29 pending staff.')">Nudge (29)</button></td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-primary">POL-FIN-002</strong></td>
                                            <td>Financial Controls & Dual Signatory</td>
                                            <td><strong class="text-primary">78%</strong> (382/490)</td>
                                            <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Dispatched reminder to 108 pending staff.')">Nudge (108)</button></td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-primary">POL-TRV-03</strong></td>
                                            <td>Travel & Escrow Advance Policy</td>
                                            <td><strong class="text-primary">85%</strong> (416/490)</td>
                                            <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Dispatched reminder.')">Nudge</button></td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-primary">POL-DATA-004</strong></td>
                                            <td>Data Protection & Confidentiality</td>
                                            <td><strong class="text-warning">62%</strong> (304/490)</td>
                                            <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Dispatched reminder to 186 pending staff.')">Nudge (186)</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Master Institutional PDP Appraisal Ledger -->
                <div class="hub-table-card">
                    <div class="hub-table-header">
                        <div>
                            <div class="fw-bold text-dark"><i class="bi bi-clipboard2-data-fill text-purple me-2"></i> Staff Performance Development Plans (PDP 150) Institutional Audit</div>
                            <div class="text-muted small">490 staff appraisal records across 4 COP Objectives, Behavioural (40), and Innovation (50)</div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" placeholder="Search staff name / dept..." onkeyup="searchTable(this, 'tablePdpHub')">
                            </div>
                            <button class="btn btn-sm btn-hub-primary" onclick="alert('Exporting Institutional PDP Excel Master Audit...')">
                                <i class="bi bi-file-earmark-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table hub-table" id="tablePdpHub">
                            <thead>
                                <tr>
                                    <th>Staff Member</th>
                                    <th>Department</th>
                                    <th>State</th>
                                    <th>Objectives (/60)</th>
                                    <th>Behaviour (/40)</th>
                                    <th>Innovation (/50)</th>
                                    <th>Grand Total (/150)</th>
                                    <th>Appraisal Rating</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Fatima Bello</strong></td>
                                    <td>Clinical Services</td>
                                    <td>Lagos</td>
                                    <td>48.0</td>
                                    <td>34.0</td>
                                    <td>42.0</td>
                                    <td><strong class="text-success">124.0</strong></td>
                                    <td><span class="badge bg-success">Outstanding (82.7%)</span></td>
                                    <td><span class="pill-badge pill-closed">Approved</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Amaka Obi</strong></td>
                                    <td>Compliance & Ethics</td>
                                    <td>Kano</td>
                                    <td>55.0</td>
                                    <td>36.0</td>
                                    <td>44.0</td>
                                    <td><strong class="text-success">135.0</strong></td>
                                    <td><span class="badge bg-success">Exemplary (90.0%)</span></td>
                                    <td><span class="pill-badge pill-closed">Approved</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Ngozi Okoro</strong></td>
                                    <td>Finance & Admin</td>
                                    <td>Rivers</td>
                                    <td>52.0</td>
                                    <td><span class="text-muted">—</span></td>
                                    <td>40.0</td>
                                    <td><strong class="text-primary">92.0</strong></td>
                                    <td><span class="badge bg-primary">Good (61.3%)</span></td>
                                    <td><span class="pill-badge pill-progress">Supervisor Review</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Ibrahim Garba</strong></td>
                                    <td>Strategic Information</td>
                                    <td>Kano</td>
                                    <td><span class="text-muted">—</span></td>
                                    <td><span class="text-muted">—</span></td>
                                    <td><span class="text-muted">—</span></td>
                                    <td><span class="text-muted">—</span></td>
                                    <td><span class="badge bg-secondary">Not Graded</span></td>
                                    <td><span class="pill-badge pill-open">Draft / Pending</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Umar Farouk</strong></td>
                                    <td>Field Operations</td>
                                    <td>Kaduna</td>
                                    <td>38.0</td>
                                    <td>28.0</td>
                                    <td><span class="text-muted">—</span></td>
                                    <td><strong class="text-warning">66.0</strong></td>
                                    <td><span class="badge bg-warning text-dark">Needs Imp (44.0%)</span></td>
                                    <td><span class="pill-badge pill-progress">HOD Review</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 4. SYSTEM & SUPER ADMIN TAB (Super Admin Only) -->
            <!-- ============================================================== -->
            <div class="tab-pane fade" id="pane-system" role="tabpanel">
                <div class="hub-table-card">
                    <div class="hub-table-header">
                        <div>
                            <div class="fw-bold text-dark"><i class="bi bi-person-gear text-danger me-2"></i> User Access, Roles & Security Delegation</div>
                            <div class="text-muted small">Designated portal access control, security keys, and institutional permission matrix</div>
                        </div>
                        <button class="btn btn-sm btn-hub-primary" onclick="alert('Invite New Administrator modal opened.')">
                            <i class="bi bi-person-plus-fill me-1"></i> Add Administrator
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table hub-table">
                            <thead>
                                <tr>
                                    <th>Admin User</th>
                                    <th>Email / Login</th>
                                    <th>Designated Portal</th>
                                    <th>Security Key</th>
                                    <th>Assigned Scope</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Dr. Chika (Director)</strong></td>
                                    <td><code>director@cccrn.org</code></td>
                                    <td><span class="role-pill role-doc">Executive (DoC)</span></td>
                                    <td><code>DOC-9981</code></td>
                                    <td>Enterprise-wide · All 14 Modules</td>
                                    <td><span class="pill-badge pill-closed">Active</span></td>
                                    <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Editing DoC credentials.')">Edit</button></td>
                                </tr>
                                <tr>
                                    <td><strong>Super Administrator</strong></td>
                                    <td><code>superadmin@cccrn.org</code></td>
                                    <td><span class="role-pill role-superadmin">Super Admin</span></td>
                                    <td><code>SUPER-7700</code></td>
                                    <td>Master Root Governance</td>
                                    <td><span class="pill-badge pill-closed">Active</span></td>
                                    <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Editing Super Admin credentials.')">Edit</button></td>
                                </tr>
                                <tr>
                                    <td><strong>Biodun Alade (HR Lead)</strong></td>
                                    <td><code>hr@cccrn.org</code></td>
                                    <td><span class="role-pill role-hr">HR Portal</span></td>
                                    <td><code>HR-7742</code></td>
                                    <td>PDP Roster, Training, Policies</td>
                                    <td><span class="pill-badge pill-closed">Active</span></td>
                                    <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Editing HR credentials.')">Edit</button></td>
                                </tr>
                                <tr>
                                    <td><strong>Amaka Obi (Specialist)</strong></td>
                                    <td><code>compliance@cccrn.org</code></td>
                                    <td><span class="role-pill role-compliance">Compliance Specialist</span></td>
                                    <td><code>SPEC-8821</code></td>
                                    <td>Complaints, CAPs, Risks, Investigations</td>
                                    <td><span class="pill-badge pill-closed">Active</span></td>
                                    <td><button class="btn btn-sm btn-hub-outline py-0 px-2" onclick="alert('Editing Compliance Officer credentials.')">Edit</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL: Log Institutional Escalation -->
    <div class="modal fade" id="modalNewEscalation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header" style="background: var(--primary); color: #fff;">
                    <h5 class="modal-title fs-6 fw-bold"><i class="bi bi-shield-plus me-2"></i> Log Institutional Escalation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form onsubmit="event.preventDefault(); alert('Institutional escalation logged successfully.'); bootstrap.Modal.getInstance(document.getElementById('modalNewEscalation')).hide();">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Cluster / State Office</label>
                            <select class="form-select form-select-sm" required>
                                <option>Kano — Cluster B</option>
                                <option>Lagos — Cluster A</option>
                                <option>Rivers — Cluster C</option>
                                <option>Abuja FCT</option>
                                <option>Kaduna</option>
                                <option>Borno</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Category</label>
                            <select class="form-select form-select-sm" required>
                                <option>Finance / Fraud</option>
                                <option>Safeguarding / PSEA</option>
                                <option>Policy Breach</option>
                                <option>Procurement Irregularity</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Severity Level</label>
                            <select class="form-select form-select-sm" required>
                                <option value="Critical">Critical (Mandatory Board Escalation)</option>
                                <option value="High">High Severity</option>
                                <option value="Medium">Medium Severity</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Incident Description & Immediate Controls</label>
                            <textarea class="form-control form-control-sm" rows="3" required placeholder="Describe the findings and immediate corrective actions taken..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-hub-primary">Save Escalation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- LIVE DYNAMIC ROLE & PORTAL CONTROLLER SCRIPT -->
    <script>
        // Read active role from window or cookie (defaults to DoC)
        var currentRole = 'doc';
        
        // Check cookie
        var cookieMatch = document.cookie.match(/auth_role=([a-zA-Z0-9_]+)/);
        if (cookieMatch && cookieMatch[1]) {
            currentRole = cookieMatch[1];
        }

        // Set up Portal Configuration per Role
        var PORTAL_CONFIG = {
            'doc': {
                name: 'Director of Compliance (DoC)',
                pillClass: 'role-doc',
                icon: 'bi-shield-shaded',
                badgeText: 'Executive Authority',
                title: 'Director of Compliance — Executive Command Center',
                desc: 'Institutional governance, statutory USAID 2 CFR 200 audits, fraud detection, and multi-state compliance oversight.',
                tabs: [
                    { id: 'executive', name: 'Executive Overview', icon: 'bi-speedometer2', active: true },
                    { id: 'compliance', name: 'Compliance & Investigations Desk', icon: 'bi-shield-check' },
                    { id: 'hr', name: 'Workforce & PDP Audit', icon: 'bi-people' }
                ],
                stats: [
                    { title: 'Compliance Health Index', val: '64%', sub: 'Target 85% by Q3', border: 'border-accent', color: 'text-primary' },
                    { title: 'Open Grievances Logged', val: '18', sub: '4 Critical Severity', border: 'border-danger', color: 'text-danger' },
                    { title: 'Enterprise Risk (ISO 31000)', val: '18 Total', sub: '3 Board Escalations', border: 'border-warning', color: 'text-warning' },
                    { title: 'Staff Appraisals (PDP)', val: '186 / 490', sub: '38% Institutional Rate', border: 'border-success', color: 'text-success' }
                ]
            },
            'superadmin': {
                name: 'Super Administrator',
                pillClass: 'role-superadmin',
                icon: 'bi-key-fill',
                badgeText: 'Root Super Admin',
                title: 'Super Administrator — Master Governance Hub',
                desc: 'Root governance oversight, role and permission delegations, state cluster configuration, and system audit logs.',
                tabs: [
                    { id: 'executive', name: 'Executive Overview', icon: 'bi-speedometer2', active: true },
                    { id: 'compliance', name: 'Compliance & Investigations', icon: 'bi-shield-check' },
                    { id: 'hr', name: 'Workforce & PDP Audit', icon: 'bi-people' },
                    { id: 'system', name: 'System & Role Delegation', icon: 'bi-gear-wide-connected' }
                ],
                stats: [
                    { title: 'Enterprise Compliance Index', val: '64%', sub: 'Target 85% by Q3', border: 'border-accent', color: 'text-primary' },
                    { title: 'Active Incidents & CAPs', val: '30 Cases', sub: 'Across 6 State Clusters', border: 'border-danger', color: 'text-danger' },
                    { title: 'Total Institutional Staff', val: '490 Staff', sub: '100% Monitored in Attendify', border: 'border-success', color: 'text-success' },
                    { title: 'System Security State', val: '4 Active Portals', sub: 'Zero Authentication Breaches', border: 'border-purple', color: 'text-purple' }
                ]
            },
            'compliance': {
                name: 'Compliance Specialist',
                pillClass: 'role-compliance',
                icon: 'bi-shield-check',
                badgeText: 'Compliance Operations',
                title: 'Compliance Specialist — Grievance & Investigation Console',
                desc: 'Incident triage queue, CAP evidence reviews, forensic case dossiers, and ISO 31000 risk mitigation.',
                tabs: [
                    { id: 'compliance', name: 'Incidents, CAPs & Investigations', icon: 'bi-shield-check', active: true }
                ],
                stats: [
                    { title: 'Active Open Grievances', val: '18', sub: '4 Critical (CMP-048, 043)', border: 'border-danger', color: 'text-danger' },
                    { title: 'CAPs Under Treatment', val: '12 Active', sub: '5 Awaiting Evidence Review', border: 'border-warning', color: 'text-warning' },
                    { title: 'Active Forensic Cases', val: '4 Dossiers', sub: 'INV-012, INV-011, INV-010', border: 'border-accent', color: 'text-primary' },
                    { title: 'Resolved This FY', val: '17 Resolved', sub: '88.4% Resolution Rate', border: 'border-success', color: 'text-success' }
                ]
            },
            'hr': {
                name: 'HR Manager',
                pillClass: 'role-hr',
                icon: 'bi-people-fill',
                badgeText: 'Workforce & PDP Management',
                title: 'HR Manager — Institutional Performance & Training Portal',
                desc: 'Full visibility over 490 staff PDP appraisals, scoring stages, safeguarding training academy, and policy sign-offs.',
                tabs: [
                    { id: 'hr', name: 'Workforce PDP & Training Academy', icon: 'bi-people', active: true }
                ],
                stats: [
                    { title: 'Total Staff Monitored', val: '490', sub: 'Across 6 State Offices', border: 'border-accent', color: 'text-primary' },
                    { title: 'PDP Appraisals Approved', val: '186 / 490', sub: 'Avg Score: 104.2 / 150', border: 'border-success', color: 'text-success' },
                    { title: 'Mandatory Academy Compliant', val: '71%', sub: 'Lagos 86% vs Borno 35%', border: 'border-warning', color: 'text-warning' },
                    { title: 'PSEA Policy Sign-off', val: '94%', sub: '461 Staff Signed Off', border: 'border-purple', color: 'text-purple' }
                ]
            }
        };

        function renderHub() {
            var config = PORTAL_CONFIG[currentRole] || PORTAL_CONFIG['doc'];

            // 1. Update Navbar Pill
            var pill = document.getElementById('navRolePill');
            if (pill) {
                pill.className = 'role-pill ' + config.pillClass;
                pill.innerHTML = '<i class="bi ' + config.icon + '"></i> ' + config.name;
            }

            // 2. Update Hero Banner
            document.getElementById('heroBadge').innerText = config.badgeText;
            document.getElementById('heroTitle').innerText = config.title;
            document.getElementById('heroDesc').innerText = config.desc;

            // 3. Render Stat Cards
            var statsHtml = '';
            config.stats.forEach(function(s) {
                statsHtml += `
                    <div class="col-sm-6 col-lg-3">
                        <div class="stat-card ${s.border}">
                            <div class="stat-title">${s.title}</div>
                            <div class="stat-val ${s.color}">${s.val}</div>
                            <div class="stat-sub">${s.sub}</div>
                        </div>
                    </div>
                `;
            });
            document.getElementById('statTilesRow').innerHTML = statsHtml;

            // 4. Render Tabs
            var tabsHtml = '';
            var firstActiveId = config.tabs[0].id;

            config.tabs.forEach(function(t, idx) {
                var isActive = idx === 0 ? 'active' : '';
                tabsHtml += `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ${isActive}" id="tab-btn-${t.id}" data-bs-toggle="tab" data-bs-target="#pane-${t.id}" type="button" role="tab">
                            <i class="bi ${t.icon}"></i> ${t.name}
                        </button>
                    </li>
                `;
            });
            document.getElementById('hubMainTabs').innerHTML = tabsHtml;

            // 5. Activate Correct Tab Pane
            document.querySelectorAll('#hubTabContent .tab-pane').forEach(function(p) {
                p.classList.remove('show', 'active');
            });
            var targetPane = document.getElementById('pane-' + firstActiveId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        }

        // Live role switcher for the simulator
        function switchHubRole(newRole) {
            currentRole = newRole;
            document.cookie = "auth_role=" + newRole + "; path=/";
            renderHub();
        }

        // Filter by state
        function filterHubData() {
            var selectedState = document.getElementById('hubStateFilter').value.toLowerCase();
            var rows = document.querySelectorAll('.hub-table tbody tr');
            rows.forEach(function(r) {
                if (!selectedState) {
                    r.style.display = '';
                } else {
                    var text = r.innerText.toLowerCase();
                    if (text.includes(selectedState)) {
                        r.style.display = '';
                    } else {
                        r.style.display = 'none';
                    }
                }
            });
        }

        // Search in specific tables
        function searchTable(inputElem, tableId) {
            var query = inputElem.value.toLowerCase();
            var rows = document.querySelectorAll('#' + tableId + ' tbody tr');
            rows.forEach(function(r) {
                if (!query) {
                    r.style.display = '';
                } else {
                    var text = r.innerText.toLowerCase();
                    r.style.display = text.includes(query) ? '' : 'none';
                }
            });
        }

        function openAdminModal(id) {
            var el = document.getElementById(id);
            if (el) {
                var modal = new bootstrap.Modal(el);
                modal.show();
            }
        }

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            renderHub();
        });
    </script>

</body>
</html>
