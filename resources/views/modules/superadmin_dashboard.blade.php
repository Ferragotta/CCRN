<div id="superadminDashboardModuleContainer" style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box;">
    <!-- 1. TOP SUPER ADMIN COMMAND BANNER -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #02367B 100%); color: #ffffff; padding: 24px 28px; border-radius: 14px; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(2, 54, 123, 0.22); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; border: 1px solid rgba(85, 226, 233, 0.25);">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span style="background: #ef4444; color: #ffffff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.8px;">
                    <i class="fa-solid fa-crown me-1"></i> Supreme Authority
                </span>
                <span style="background: rgba(85,226,233,0.15); color: #55E2E9; border: 1px solid rgba(85,226,233,0.3); font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">
                    Attendify &middot; ComplianceIQ Global Root
                </span>
                <span style="font-size: 12px; opacity: 0.85; font-weight: 500;">
                    Absolute Control Across All Subsystems & Regional Clusters
                </span>
            </div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; margin: 4px 0 3px; color: #ffffff; letter-spacing: -0.5px;">
                Super Administrator Master Command Console
            </h1>
            <div style="font-size: 12.5px; opacity: 0.9; color: #cbd5e1;">
                Institutional oversight, ComplianceIQ global management, audit lock overrides, policy approvals, and real-time cross-module telemetry.
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <!-- SUPER ADMIN LIVE NOTIFICATION BELL -->
            <div style="position: relative;" id="saBannerNotificationWrapper">
                <button id="saBannerNotificationBtn" onclick="toggleSaBannerNotifications(event)" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(85,226,233,0.5); color: #ffffff; width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; transition: all 0.2s ease;" title="Super Admin Real-Time Cross-Module Alerts">
                    <i class="fa-solid fa-bell" style="font-size: 16px; color: #55E2E9;"></i>
                    <span id="saBannerNotificationBadge" style="position: absolute; top: -4px; right: -4px; min-width: 18px; height: 18px; padding: 0 4px; background: #dc2626; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 800; display: none; align-items: center; justify-content: center; border: 2px solid #0f172a; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                        0
                    </span>
                </button>

                <!-- Super Admin Alert Dropdown Menu -->
                <div id="saBannerNotificationDropdown" style="display: none; position: absolute; right: 0; top: 48px; width: 380px; max-width: 90vw; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); z-index: 1050; overflow: hidden; text-align: left; color: var(--text);">
                    <div style="padding: 12px 16px; background: #0f172a; border-bottom: 1px solid rgba(85,226,233,0.3); display: flex; justify-content: space-between; align-items: center; color: #ffffff;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-crown" style="color: #55E2E9;"></i>
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 13px; color: #ffffff;">Root Central Alerts</span>
                        </div>
                        <span id="saBannerDropdownUnreadBadge" style="background: rgba(220,38,38,0.2); color: #fca5a5; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 12px; border: 1px solid rgba(220,38,38,0.4);">
                            0 Live Triggers
                        </span>
                    </div>

                    <div id="saBannerNotificationList" style="max-height: 380px; overflow-y: auto; padding: 4px 0;">
                        <!-- Injected live from /api/backend/data -->
                    </div>

                    <div style="padding: 10px 14px; background: #f8fafc; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
                        <a href="javascript:void(0)" onclick="markAllSaNotificationsRead()" style="color: #0077b6; text-decoration: none; font-weight: 600;">
                            <i class="fa-solid fa-check-double me-1"></i> Dismiss all
                        </a>
                        <a href="javascript:void(0)" onclick="switchSuperAdminTab('audit'); toggleSaBannerNotifications();" style="color: #02367B; text-decoration: none; font-weight: 700;">
                            Audit Trail <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <button class="btn btn-outline" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('investigations')">
                <i class="fa-solid fa-magnifying-glass-chart me-1"></i> Forensic Hub
            </button>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('leave-attendance')">
                <i class="fa-solid fa-calendar-check me-1"></i> Attendify Workforce
            </button>
            <button class="btn btn-outline" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: #ffffff; font-size: 11px; font-weight: 700;" onclick="switchPanel('risk')">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> Risk Register
            </button>
            <button class="btn btn-primary" style="background: #55E2E9; color: #022452; font-weight: 800; font-size: 11px; border: none; box-shadow: 0 0 15px rgba(85,226,233,0.4);" onclick="openModal('modalSuperAdminOverride')">
                <i class="fa-solid fa-shield-halved me-1"></i> Root Governance Override
            </button>
        </div>
    </div>

    <!-- 2. 5 GLOBAL KPI CARDS (CLEAN SLATE DYNAMIC TELEMETRY) -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0; border-left: 4px solid #02367B; background: #ffffff; padding: 14px 16px;">
            <div style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px;">Active Employees Registered</div>
            <div id="saKpiTotalWorkforce" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: #02367B; line-height: 1;">0</div>
            <div id="saKpiWorkforceSub" style="font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 4px;">Live Employee Register</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #dc2626; background: #ffffff; padding: 14px 16px;">
            <div style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px;">Active Grievances & Fraud</div>
            <div id="saKpiGrievances" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: #dc2626; line-height: 1;">0</div>
            <div id="saKpiGrievancesSub" style="font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 4px;">Active Whistleblower Cases</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #d97706; background: #ffffff; padding: 14px 16px;">
            <div style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px;">Corrective Actions (CAP)</div>
            <div id="saKpiCaps" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: #d97706; line-height: 1;">0</div>
            <div id="saKpiCapsSub" style="font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 4px;">USAID & Institutional Remediation</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #7c3aed; background: #ffffff; padding: 14px 16px;">
            <div style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px;">Forensic Investigations</div>
            <div id="saKpiInvestigations" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: #7c3aed; line-height: 1;">0</div>
            <div id="saKpiInvestigationsSub" style="font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 4px;">Active Inquiries in Custody</div>
        </div>

        <div class="card" style="margin-bottom: 0; border-left: 4px solid #059669; background: #ffffff; padding: 14px 16px;">
            <div style="font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px;">Clocked In Today</div>
            <div id="saKpiClockedToday" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: #059669; line-height: 1;">0</div>
            <div id="saKpiClockedSub" style="font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 4px;">Attendify Terminal Clock-ins</div>
        </div>
    </div>

    <!-- 3. INTERACTIVE ROOT COMMAND TABS -->
    <div style="display: flex; gap: 8px; border-bottom: 1px solid var(--border); margin-bottom: 20px; overflow-x: auto;">
        <button class="tab active" id="saTabBtnControlPanel" onclick="switchSuperAdminTab('controlpanel')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid #02367B; color: #02367B; font-weight: 700; font-size: 13px; cursor: pointer; white-space: nowrap;">
            <i class="fa-solid fa-sliders me-1" style="color: #02367B;"></i> ComplianceIQ Management Panel
        </button>
        <button class="tab" id="saTabBtnConsole" onclick="switchSuperAdminTab('console')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer; white-space: nowrap;">
            <i class="fa-solid fa-network-wired me-1"></i> Subsystem Telemetry
        </button>
        <button class="tab" id="saTabBtnRoles" onclick="switchSuperAdminTab('roles')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer; white-space: nowrap;">
            <i class="fa-solid fa-users-gear me-1"></i> Role Matrix & Access
        </button>
        <button class="tab" id="saTabBtnAudit" onclick="switchSuperAdminTab('audit')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer; white-space: nowrap;">
            <i class="fa-solid fa-clipboard-check me-1"></i> Live Activity & Audit Stream
        </button>
        <button class="tab" id="saTabBtnStates" onclick="switchSuperAdminTab('states')" style="padding: 10px 18px; border: none; background: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 700; font-size: 13px; cursor: pointer; white-space: nowrap;">
            <i class="fa-solid fa-map-location-dot me-1"></i> State Regional Hubs
        </button>
    </div>

    <!-- 4. TAB PANELS -->

    <!-- TAB 0: COMPLIANCEIQ MANAGEMENT PANEL -->
    <div id="saSectionControlpanel" style="display: block; margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 20px;">
            <!-- Left: Live Cross-Module Trigger Control Station -->
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-sliders" style="color: #02367B;"></i>
                        <span>ComplianceIQ Central Governance & Management</span>
                        <span style="background: #e0f2fe; color: #02367B; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 12px; border: 1px solid #bae6fd;">
                            ROOT CONTROL
                        </span>
                    </div>
                    <span style="font-size: 11px; color: #059669; font-weight: 700;">
                        <i class="fa-solid fa-circle-dot fa-beat-fade me-1 text-success"></i> Live Reactive Bus
                    </span>
                </div>
                <div style="padding: 16px;">
                    <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px; line-height: 1.4;">
                        Super Administrator interface to govern and manage ComplianceIQ modules, purge/reset test state, force synchronization across state clusters, and dispatch live alerts to DoC and HR.
                    </p>

                    <!-- Management Grid -->
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-bottom: 20px;">
                        <!-- Action Card 1: Data State & Reset Management -->
                        <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 14px; border-left: 4px solid #dc2626;">
                            <div style="font-weight: 700; font-size: 12.5px; color: #dc2626; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-trash-can"></i> State Management & Reset
                            </div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">
                                Clear dummy mock registers or reset all system stores to a clean slate across all 14 modules.
                            </div>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px; color: #dc2626; border-color: #fca5a5;" onclick="triggerCleanSlateReset()">
                                    <i class="fa-solid fa-broom me-1"></i> Reset Store to Clean
                                </button>
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px;" onclick="syncSuperAdminLiveState()">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i> Refresh
                                </button>
                            </div>
                        </div>

                        <!-- Action Card 2: Biometric & Attendify Control -->
                        <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 14px; border-left: 4px solid #0077b6;">
                            <div style="font-weight: 700; font-size: 12.5px; color: #0077b6; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-fingerprint"></i> Attendify Biometrics Pulse
                            </div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">
                                Force sync biometrics clock roster or test terminal event dispatch into the HR module.
                            </div>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px;" onclick="triggerSimulatedClockIn()">
                                    <i class="fa-solid fa-clock me-1"></i> Test Clock-in
                                </button>
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px;" onclick="switchPanel('leave-attendance')">
                                    HR Hub &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- Action Card 3: CAP & Audit Override -->
                        <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 14px; border-left: 4px solid #d97706;">
                            <div style="font-weight: 700; font-size: 12.5px; color: #d97706; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-stamp"></i> CAP & Audit Verification
                            </div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">
                                Execute unilateral CAP closures, sign off corrective action evidence, or lock audit trails.
                            </div>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px; color: #d97706; border-color: #fde68a;" onclick="switchPanel('cap')">
                                    <i class="fa-solid fa-circle-check me-1"></i> Review CAPs
                                </button>
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px;" onclick="openModal('modalSuperAdminOverride')">
                                    Root Override &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- Action Card 4: Executive Incident Broadcast -->
                        <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 14px; border-left: 4px solid #7c3aed;">
                            <div style="font-weight: 700; font-size: 12.5px; color: #7c3aed; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-bullhorn"></i> Executive Directive Broadcast
                            </div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">
                                Broadcast urgent compliance alert to DoC, Compliance Officers, and HR Managers.
                            </div>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px; color: #7c3aed; border-color: #ddd6fe;" onclick="triggerLiveAuditAlert()">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Broadcast Alert
                                </button>
                                <button class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px;" onclick="switchPanel('complaints')">
                                    Incidents &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- System Diagnostics & Health Status -->
                    <div style="padding: 12px 14px; background: #eff6ff; border-radius: 8px; border: 1px solid #bfdbfe; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #02367B; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fa-solid fa-server"></i>
                            </div>
                            <div>
                                <strong style="font-size: 12px; color: #02367B;">ComplianceIQ Engine Health: Clean Slate &amp; Active</strong>
                                <div style="font-size: 11px; color: var(--text-muted);">Reactive bus polling interval: 2000ms. All modules report 0 residual mock records.</div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="syncSuperAdminLiveState()" style="font-size: 11px; padding: 4px 10px; background: #02367B; border-color: #02367B;">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> Force Poll
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right: Real-Time Event Dispatch Feed -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div class="card" style="margin-bottom: 0; border-top: 3px solid #55E2E9;">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="card-title">
                            <i class="fa-solid fa-bolt" style="color: #0284c7;"></i>
                            Live Trigger Stream
                        </div>
                        <span id="saStreamCounter" class="badge" style="background: #e0f2fe; color: #02367B; font-size: 10px;">Live</span>
                    </div>
                    <div id="saLiveEventFeed" style="padding: 14px; max-height: 380px; overflow-y: auto; font-size: 11.5px;">
                        <div style="text-align: center; color: var(--text-muted); padding: 24px 10px;">
                            <i class="fa-solid fa-satellite-dish me-1"></i> Listening to cross-module event bus...<br>
                            <span style="font-size: 10.5px; opacity: 0.8;">Actions in Staff, HR, or Compliance trigger instant updates here.</span>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 0; background: #0f172a; color: #ffffff; border-left: 4px solid #55E2E9; padding: 14px 16px;">
                    <div style="font-size: 10.5px; font-weight: 700; color: #55E2E9; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px;">Super Admin Authority Check</div>
                    <div style="font-size: 12.5px; font-weight: 700; color: #ffffff; margin-bottom: 4px;">
                        <i class="fa-solid fa-shield-check text-success me-1"></i> Unrestricted System Root
                    </div>
                    <div style="font-size: 11px; color: #94a3b8; line-height: 1.4;">
                        Live management over staff registers, state offices, and all compliance, audit, and biometrics stores.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 1: ENTERPRISE TELEMETRY & SUBSYSTEMS -->
    <div id="saSectionConsole" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title">
                    <i class="fa-solid fa-cubes-stacked" style="color: var(--accent);"></i>
                    14-Module Real-Time Health &amp; Governance Authority
                </div>
                <span class="pill pill-closed" style="font-size: 10.5px;">All 14 Systems Nominal</span>
            </div>
            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="width: 25%; padding: 9px 10px;">Subsystem</th>
                            <th style="width: 25%; padding: 9px 10px;">Active Primary Lead</th>
                            <th style="width: 20%; padding: 9px 6px; text-align: center;">Root Policy</th>
                            <th style="width: 15%; padding: 9px 6px; text-align: center;">Authority</th>
                            <th style="width: 15%; padding: 9px 6px; text-align: center;">Super Admin Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px;"><i class="fa-solid fa-inbox text-primary me-1"></i> <strong>Complaints & Whistleblower</strong></td>
                            <td style="padding: 10px;">Director of Compliance / HR</td>
                            <td style="padding: 10px; text-align: center;"><span class="pill" style="background:#e0f2fe; color:#0369a1; font-size:9.5px;">POL-ETH-01</span></td>
                            <td style="padding: 10px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px;">Unrestricted</span></td>
                            <td style="padding: 10px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="switchPanel('complaints')">Audit</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px;"><i class="fa-solid fa-circle-check text-warning me-1"></i> <strong>Corrective Actions (CAP)</strong></td>
                            <td style="padding: 10px;">Compliance Specialists</td>
                            <td style="padding: 10px; text-align: center;"><span class="pill" style="background:#fef3c7; color:#92400e; font-size:9.5px;">USAID 2 CFR 200</span></td>
                            <td style="padding: 10px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px;">Full Sign-off</span></td>
                            <td style="padding: 10px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="switchPanel('cap')">Audit</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px;"><i class="fa-solid fa-magnifying-glass-chart text-danger me-1"></i> <strong>Forensic Investigations</strong></td>
                            <td style="padding: 10px;">Executive Legal & DoC</td>
                            <td style="padding: 10px; text-align: center;"><span class="pill" style="background:#fee2e2; color:#991b1b; font-size:9.5px;">POL-INV-04</span></td>
                            <td style="padding: 10px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px;">Absolute Custody</span></td>
                            <td style="padding: 10px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="switchPanel('investigations')">Inspect</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px;"><i class="fa-solid fa-brain me-1" style="color: #7c3aed;"></i> <strong>AI Compliance Reviewer</strong></td>
                            <td style="padding: 10px;">Directorate / Neural Engine</td>
                            <td style="padding: 10px; text-align: center;"><span class="pill" style="background:#ede9fe; color:#6d28d9; font-size:9.5px;">2 CFR 200 Multi-clause</span></td>
                            <td style="padding: 10px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px;">Supervisory</span></td>
                            <td style="padding: 10px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="switchPanel('ai-review')">Analyze</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px;"><i class="fa-solid fa-calendar-check text-success me-1"></i> <strong>Attendify Leave & Attendance</strong></td>
                            <td style="padding: 10px;">HR Management & Supervisors</td>
                            <td style="padding: 10px; text-align: center;"><span class="pill" style="background:#dcfce7; color:#166534; font-size:9.5px;">POL-HR-005</span></td>
                            <td style="padding: 10px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px;">Root Override</span></td>
                            <td style="padding: 10px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="switchPanel('leave-attendance')">Inspect</button></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px;"><i class="fa-solid fa-plane-departure text-primary me-1"></i> <strong>Travel & Ticket Escrow Gate</strong></td>
                            <td style="padding: 10px;">Operations & Finance Leads</td>
                            <td style="padding: 10px; text-align: center;"><span class="pill" style="background:#fef3c7; color:#92400e; font-size:9.5px;">POL-TRV-03</span></td>
                            <td style="padding: 10px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px;">Escrow Release</span></td>
                            <td style="padding: 10px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="switchPanel('travel')">Audit</button></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px;"><i class="fa-solid fa-bullseye text-primary me-1"></i> <strong>Staff PDP Scoring (150 Pts)</strong></td>
                            <td style="padding: 10px;">Supervisors & Institutional HR</td>
                            <td style="padding: 10px; text-align: center;"><span class="pill" style="background:#e0f2fe; color:#0369a1; font-size:9.5px;">POL-PDP-2026</span></td>
                            <td style="padding: 10px; text-align: center;"><span class="pill pill-closed" style="font-size: 9.5px;">Score Audit</span></td>
                            <td style="padding: 10px; text-align: center;"><button class="btn btn-outline btn-sm" onclick="switchPanel('pdp')">Audit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: ROLE MATRIX & PERMISSIONS -->
    <div id="saSectionRoles" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title">
                    <i class="fa-solid fa-users-gear" style="color: var(--accent);"></i>
                    Global Access Control & Role Permissions Matrix
                </div>
                <button class="btn btn-primary btn-sm" onclick="alert('All user role assignments are governed strictly under Super Admin root authority.')">
                    <i class="fa-solid fa-user-plus me-1"></i> Assign Elevated Role
                </button>
            </div>
            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="width: 18%; padding: 10px 12px;">Role Profile</th>
                            <th style="width: 22%; padding: 10px 12px;">Primary Users</th>
                            <th style="width: 35%; padding: 10px 12px;">Module Permissions Scope</th>
                            <th style="width: 15%; padding: 10px 12px; text-align: center;">Deletion Authority</th>
                            <th style="width: 10%; padding: 10px 12px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #f1f5f9; background: rgba(85,226,233,0.04);">
                            <td style="padding: 10px 12px;"><strong style="color: #02367B;"><i class="fa-solid fa-crown text-warning me-1"></i> Super Admin</strong></td>
                            <td style="padding: 10px 12px;">superadmin@cccrn.org</td>
                            <td style="padding: 10px 12px;"><span style="font-weight: 700; color: #02367B;">All 14 Modules (Total Unrestricted Control)</span></td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-closed" style="font-size: 10px; font-weight: 700;">Full Absolute</span></td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-closed" style="font-size: 10px;">Active Root</span></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 12px;"><strong>Director of Compliance (DoC)</strong></td>
                            <td style="padding: 10px 12px;">director@cccrn.org</td>
                            <td style="padding: 10px 12px;">Investigations, Triage, CAPs, Risk, AI Review, Reports, Travel, PDP</td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-open" style="font-size: 10px; font-weight: 700;">No Deletion</span></td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-closed" style="font-size: 10px;">Active</span></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 12px;"><strong>Compliance Specialist</strong></td>
                            <td style="padding: 10px 12px;">compliance@cccrn.org</td>
                            <td style="padding: 10px 12px;">Complaints Triage, CAP Management, Training, State Audits, Risk</td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-open" style="font-size: 10px; font-weight: 700;">No Deletion</span></td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-closed" style="font-size: 10px;">Active</span></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 12px;"><strong>HR Manager</strong></td>
                            <td style="padding: 10px 12px;">hr@cccrn.org</td>
                            <td style="padding: 10px 12px;">Leave, Attendance, PDP Institutional Audit, Training Roster, View-Only Incidents</td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-open" style="font-size: 10px; font-weight: 700;">No Deletion</span></td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-closed" style="font-size: 10px;">Active</span></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 12px;"><strong>State Team Lead (STL)</strong></td>
                            <td style="padding: 10px 12px;">lead@cccrn.org</td>
                            <td style="padding: 10px 12px;">State Regional Hub, State PDP Review, Cluster Training, CAP Evidence</td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-open" style="font-size: 10px; font-weight: 700;">No Deletion</span></td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-closed" style="font-size: 10px;">Active</span></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 12px;"><strong>Staff Member (Attendify)</strong></td>
                            <td style="padding: 10px 12px;">Field & Facility Personnel</td>
                            <td style="padding: 10px 12px;">Personal Leave, Log Incident, My CAPs, Self PDP, Mandatory Training Academy</td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-open" style="font-size: 10px; font-weight: 700;">No Deletion</span></td>
                            <td style="padding: 10px 12px; text-align: center;"><span class="pill pill-closed" style="font-size: 10px;">Active</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: ROOT AUDIT TRAIL & LOGS (CLEAN SLATE DYNAMIC) -->
    <div id="saSectionAudit" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title">
                    <i class="fa-solid fa-clipboard-check" style="color: var(--accent);"></i>
                    Immutable Super Admin Audit Trail (SHA-256 Verifiable)
                </div>
                <button class="btn btn-outline btn-sm" onclick="alert('Exporting immutable audit trail with SHA-256 hash...')">
                    <i class="fa-solid fa-download me-1"></i> Export Security Audit Log
                </button>
            </div>
            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="width: 16%; padding: 10px 12px;">Timestamp</th>
                            <th style="width: 18%; padding: 10px 12px;">Actor</th>
                            <th style="width: 16%; padding: 10px 12px;">Module</th>
                            <th style="width: 35%; padding: 10px 12px;">Action Executed</th>
                            <th style="width: 15%; padding: 10px 12px; text-align: center;">Cryptographic Hash</th>
                        </tr>
                    </thead>
                    <tbody id="saAuditTrailTableBody">
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">
                                <i class="fa-solid fa-clipboard-list" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>
                                No audit events recorded yet. Actions across Staff, HR, and DoC modules will generate immutable cryptographic log entries here.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: 6 STATE CLUSTERS MASTER GRID (CLEAN SLATE DYNAMIC) -->
    <div id="saSectionStates" style="display: none;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title">
                    <i class="fa-solid fa-map-location-dot" style="color: var(--accent);"></i>
                    State Regional Offices &amp; Clusters Master Command
                </div>
                <button class="btn btn-outline btn-sm" onclick="switchPanel('states')">
                    Open Full State Hub &rarr;
                </button>
            </div>
            <div style="width: 100%; overflow: hidden; border-radius: 8px; border: 1px solid var(--border);">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 11.5px;">
                    <thead>
                        <tr style="background: var(--surface2); border-bottom: 1px solid var(--border); font-size: 10.5px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="width: 18%; padding: 10px 12px;">State & Cluster</th>
                            <th style="width: 20%; padding: 10px 12px;">Assigned Lead</th>
                            <th style="width: 14%; padding: 10px 12px;">Staff Deployed</th>
                            <th style="width: 16%; padding: 10px 12px; text-align: center;">Attendance Today</th>
                            <th style="width: 16%; padding: 10px 12px; text-align: center;">Compliance Score</th>
                            <th style="width: 16%; padding: 10px 12px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="saStateMasterTableBody">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;">
                                <i class="fa-solid fa-map-location" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>
                                No active state regional clusters loaded. State office rosters and live telemetry will stream here.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL: SUPER ADMIN ROOT GOVERNANCE OVERRIDE -->
<div class="modal-overlay" id="modalSuperAdminOverride" style="display: none;" onclick="if(event.target===this)closeModal('modalSuperAdminOverride')">
    <div class="modal-dialog" style="max-width: 550px; width: 95%;">
        <div class="modal-header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="modal-title" style="font-weight: 800; font-size: 15px; color: #02367B;">
                    <i class="fa-solid fa-crown text-warning me-2"></i> Super Admin Root Governance Action
                </span>
            </div>
            <button class="modal-close" onclick="closeModal('modalSuperAdminOverride')">&times;</button>
        </div>
        <div class="modal-body" style="font-size: 12px; color: var(--text);">
            <div style="background: rgba(2,54,123,0.06); padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 11px; color: var(--accent);">
                <i class="fa-solid fa-shield-halved me-1"></i> You are exercising root executive authority over CCCRN ComplianceIQ and Attendify Host Integration.
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Target Action / Override *</label>
                <select id="superAdminActionSelect" style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text);">
                    <option value="FORCE_SYNC">Force Biometric Attendance Roster Sync across 6 States</option>
                    <option value="BYPASS_ESCROW">Unilateral Escrow Gate Clearance (POL-TRV-03 Override)</option>
                    <option value="APPROVE_ALL_CAP">Bulk Finalize & Sign-off Pending CAP Remediations</option>
                    <option value="LOCK_AUDIT">Freeze Audit State Ledgers for External Review</option>
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Executive Decision Rationale *</label>
                <textarea id="superAdminActionRationale" rows="3" placeholder="State the institutional justification for this root governance override..." style="width: 100%; padding: 8px 10px; font-size: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--text); box-sizing: border-box;"></textarea>
            </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalSuperAdminOverride')">Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="executeRootOverride()"><i class="fa-solid fa-check me-1"></i> Confirm & Execute</button>
        </div>
    </div>
</div>

<script>
// ══════════════════════════════════════════════════════════════════
// SUPER ADMIN MASTER CONTROL PANEL & REACTIVE NOTIFICATION ENGINE
// ══════════════════════════════════════════════════════════════════
var SUPERADMIN_NOTIFICATIONS = [];
var READ_SA_NOTIF_IDS = {};

function toggleSaBannerNotifications(e) {
    if (e) e.stopPropagation();
    var dropdown = document.getElementById('saBannerNotificationDropdown');
    if (!dropdown) return;
    var isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) {
        syncSuperAdminLiveState();
    }
}

function renderSaBannerNotifications() {
    var list = document.getElementById('saBannerNotificationList');
    var badge = document.getElementById('saBannerNotificationBadge');
    var unreadBadge = document.getElementById('saBannerDropdownUnreadBadge');
    if (!list) return;

    var unreadCount = SUPERADMIN_NOTIFICATIONS.filter(function(n) { return n.unread && !READ_SA_NOTIF_IDS[n.id]; }).length;
    if (badge) {
        badge.innerText = unreadCount;
        badge.style.display = unreadCount > 0 ? 'flex' : 'none';
    }
    if (unreadBadge) {
        unreadBadge.innerText = unreadCount + ' Live Trigger' + (unreadCount !== 1 ? 's' : '');
    }

    if (SUPERADMIN_NOTIFICATIONS.length === 0) {
        list.innerHTML = '<div style="padding: 28px 16px; text-align: center; color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-circle-check text-success me-1"></i> All subsystems in optimal standing. No pending root triggers.</div>';
        return;
    }

    var html = '';
    SUPERADMIN_NOTIFICATIONS.forEach(function(n) {
        var isUnread = n.unread && !READ_SA_NOTIF_IDS[n.id];
        var unreadBg = isUnread ? 'background: rgba(2, 54, 123, 0.04); font-weight: 600;' : 'background: #ffffff;';
        html += '<div onclick="handleSaNotificationClick(\'' + n.id + '\', \'' + (n.panelTarget || '') + '\')" style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; cursor: pointer; transition: background 0.15s ease; ' + unreadBg + '" onmouseover="this.style.background=\'#f8fafc\'" onmouseout="this.style.background=\'' + (isUnread ? 'rgba(2, 54, 123, 0.04)' : '#ffffff') + '\'">' +
            '<div style="width: 32px; height: 32px; border-radius: 50%; background: ' + n.iconBg + '; color: ' + n.iconColor + '; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 13px;">' +
                '<i class="fa-solid ' + n.icon + '"></i>' +
            '</div>' +
            '<div style="flex: 1; min-width: 0;">' +
                '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">' +
                    '<strong style="font-size: 12px; color: var(--text);">' + n.title + '</strong>' +
                    '<span style="font-size: 10px; color: var(--text-muted);">' + n.time + '</span>' +
                '</div>' +
                '<div style="font-size: 11px; color: var(--text-dim); line-height: 1.35; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + n.desc + '</div>' +
            '</div>' +
            (isUnread ? '<span style="width: 7px; height: 7px; border-radius: 50%; background: #dc2626; align-self: center; flex-shrink: 0;"></span>' : '') +
        '</div>';
    });
    list.innerHTML = html;
}

function handleSaNotificationClick(id, panel) {
    READ_SA_NOTIF_IDS[id] = true;
    var notif = SUPERADMIN_NOTIFICATIONS.find(function(n) { return n.id === id; });
    if (notif) notif.unread = false;
    renderSaBannerNotifications();
    var dropdown = document.getElementById('saBannerNotificationDropdown');
    if (dropdown) dropdown.style.display = 'none';

    if (panel && typeof switchPanel === 'function') {
        switchPanel(panel);
    }
}

function markAllSaNotificationsRead() {
    SUPERADMIN_NOTIFICATIONS.forEach(function(n) {
        n.unread = false;
        READ_SA_NOTIF_IDS[n.id] = true;
    });
    renderSaBannerNotifications();
}

function switchSuperAdminTab(tabKey) {
    var tabs = ['controlpanel', 'console', 'roles', 'audit', 'states'];
    tabs.forEach(function(t) {
        var secId = (t === 'controlpanel') ? 'saSectionControlpanel' : ('saSection' + t.charAt(0).toUpperCase() + t.slice(1));
        var btnId = (t === 'controlpanel') ? 'saTabBtnControlPanel' : ((t === 'states') ? 'saTabBtnStates' : ('saTabBtn' + t.charAt(0).toUpperCase() + t.slice(1)));

        var sec = document.getElementById(secId);
        var btn = document.getElementById(btnId);

        if (sec) sec.style.display = (t === tabKey) ? 'block' : 'none';
        if (btn) {
            if (t === tabKey) {
                btn.classList.add('active');
                btn.style.borderBottom = '3px solid #02367B';
                btn.style.color = '#02367B';
            } else {
                btn.classList.remove('active');
                btn.style.borderBottom = '3px solid transparent';
                btn.style.color = 'var(--text-muted)';
            }
        }
    });
}

function syncSuperAdminLiveState() {
    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data) return;

            // 1. Sync KPI Cards dynamically
            var compCount = (data.complaints || []).length;
            var capCount = (data.caps || []).length;
            var invCount = (data.investigations || []).length;
            var bioCount = (data.attendance_logs || []).length;
            var staffCount = (data.registered_officers ? Object.keys(data.registered_officers).length : 0);

            var elGriev = document.getElementById('saKpiGrievances');
            var elCap = document.getElementById('saKpiCaps');
            var elInv = document.getElementById('saKpiInvestigations');
            var elClock = document.getElementById('saKpiClockedToday');
            var elWorkforce = document.getElementById('saKpiTotalWorkforce');

            if (elGriev) elGriev.innerText = compCount;
            if (elCap) elCap.innerText = capCount;
            if (elInv) elInv.innerText = invCount;
            if (elClock) elClock.innerText = bioCount;
            if (elWorkforce) elWorkforce.innerText = staffCount;

            // 2. Generate Live Notifications for Super Admin
            var liveItems = [];

            // A. Complaints & Whistleblower
            if (data.complaints && data.complaints.length > 0) {
                var c = data.complaints[0];
                liveItems.push({
                    id: 'sa-alert-comp-' + c.id,
                    title: 'Grievance Alert (' + data.complaints.length + ' Active)',
                    desc: c.id + ': ' + (c.title || c.category) + ' reported from ' + (c.state || 'Field'),
                    time: c.date || 'Recent',
                    icon: 'fa-shield-halved',
                    iconBg: '#fee2e2',
                    iconColor: '#dc2626',
                    panelTarget: 'complaints',
                    unread: !READ_SA_NOTIF_IDS['sa-alert-comp-' + c.id]
                });
            }

            // B. Corrective Action Plans (CAP)
            if (data.caps && data.caps.length > 0) {
                var cp = data.caps[0];
                liveItems.push({
                    id: 'sa-alert-cap-' + cp.id,
                    title: 'CAP Action Pending (' + data.caps.length + ')',
                    desc: cp.id + ': ' + (cp.issue || 'Corrective action plan item') + ' (' + (cp.state || 'National') + ')',
                    time: 'Pending',
                    icon: 'fa-circle-check',
                    iconBg: '#fef3c7',
                    iconColor: '#d97706',
                    panelTarget: 'cap',
                    unread: !READ_SA_NOTIF_IDS['sa-alert-cap-' + cp.id]
                });
            }

            // C. Staff Leave Requests
            if (data.leave_requests && data.leave_requests.length > 0) {
                var lve = data.leave_requests[0];
                liveItems.push({
                    id: 'sa-alert-lve-' + lve.id,
                    title: 'Staff Leave Request (' + data.leave_requests.length + ')',
                    desc: lve.staff_name + ' applied for ' + lve.category + ' (' + (lve.days || 1) + 'd)',
                    time: 'HR Queue',
                    icon: 'fa-calendar-check',
                    iconBg: '#e0f2fe',
                    iconColor: '#02367B',
                    panelTarget: 'leave-attendance',
                    unread: !READ_SA_NOTIF_IDS['sa-alert-lve-' + lve.id]
                });
            }

            // D. Field Work Missions
            if (data.field_work && data.field_work.length > 0) {
                var fw = data.field_work[0];
                liveItems.push({
                    id: 'sa-alert-fw-' + fw.ref,
                    title: 'Field Mission Active (' + data.field_work.length + ')',
                    desc: fw.staff_name + ' deployed to ' + fw.destination + ' (' + fw.activity_type + ')',
                    time: 'Active',
                    icon: 'fa-route',
                    iconBg: '#ede9fe',
                    iconColor: '#7c3aed',
                    panelTarget: 'travel',
                    unread: !READ_SA_NOTIF_IDS['sa-alert-fw-' + fw.ref]
                });
            }

            // E. Attendify Biometrics Clock Scans
            if (data.attendance_logs && data.attendance_logs.length > 0) {
                var bio = data.attendance_logs[0];
                liveItems.push({
                    id: 'sa-alert-bio-latest',
                    title: 'Biometrics Event Registered',
                    desc: bio.staff_name + ' clocked in at ' + bio.time + ' (' + bio.state + ')',
                    time: bio.time,
                    icon: 'fa-fingerprint',
                    iconBg: '#dcfce7',
                    iconColor: '#059669',
                    panelTarget: 'leave-attendance',
                    unread: !READ_SA_NOTIF_IDS['sa-alert-bio-latest']
                });
            }

            SUPERADMIN_NOTIFICATIONS = liveItems;
            renderSaBannerNotifications();

            // 3. Render Live Event Feed in Control Station
            var feedContainer = document.getElementById('saLiveEventFeed');
            if (feedContainer) {
                if (liveItems.length === 0) {
                    feedContainer.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 24px 10px;"><i class="fa-solid fa-satellite-dish me-1"></i> Listening to cross-module event bus...<br><span style="font-size: 10.5px; opacity: 0.8;">Actions in Staff, HR, or Compliance trigger instant updates here.</span></div>';
                } else {
                    var fHtml = '';
                    liveItems.forEach(function(item) {
                        fHtml += '<div style="padding: 9px 10px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">' +
                            '<span style="width: 26px; height: 26px; border-radius: 50%; background: ' + item.iconBg + '; color: ' + item.iconColor + '; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0;"><i class="fa-solid ' + item.icon + '"></i></span>' +
                            '<div style="flex: 1; min-width: 0;">' +
                                '<div style="font-weight: 700; color: var(--text); font-size: 11.5px;">' + item.title + '</div>' +
                                '<div style="font-size: 10.5px; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + item.desc + '</div>' +
                            '</div>' +
                            '<span style="font-size: 9.5px; color: var(--text-muted); flex-shrink: 0;">' + item.time + '</span>' +
                        '</div>';
                    });
                    feedContainer.innerHTML = fHtml;
                }
            }

            // 4. Render Audit Logs Table
            var auditTbody = document.getElementById('saAuditTrailTableBody');
            if (auditTbody) {
                var logs = data.audit_logs || [];
                if (logs.length === 0) {
                    auditTbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-clipboard-list" style="font-size: 20px; color: var(--border); margin-bottom: 8px; display: block;"></i>No audit events recorded yet. Actions across Staff, HR, and DoC modules will generate immutable cryptographic log entries here.</td></tr>';
                } else {
                    var aHtml = '';
                    logs.forEach(function(l) {
                        aHtml += '<tr style="border-bottom: 1px solid #f1f5f9;">' +
                            '<td style="padding: 10px 12px; font-family: monospace;">' + l.timestamp + '</td>' +
                            '<td style="padding: 10px 12px;"><strong>' + l.actor + '</strong></td>' +
                            '<td style="padding: 10px 12px;"><span class="badge" style="background:#02367B; color:#fff;">' + l.module + '</span></td>' +
                            '<td style="padding: 10px 12px;">' + l.description + '</td>' +
                            '<td style="padding: 10px 12px; text-align: center; font-family: monospace; font-size: 10px; color: var(--text-muted);">' + l.hash + '</td>' +
                        '</tr>';
                    });
                    auditTbody.innerHTML = aHtml;
                }
            }
        }).catch(function(e) { console.log('Super Admin sync error', e); });
}

// Clean Slate Trigger from Control Panel
function triggerCleanSlateReset() {
    if (!confirm('Execute Clean Slate Reset across ComplianceIQ? All test mock records in backend will be cleared.')) return;
    fetch('/api/superadmin/override', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'RESET_CLEAN_SLATE',
            rationale: 'Super Administrator initiated complete system clean-slate purge.'
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        alert('ComplianceIQ Clean Slate Purge executed successfully.');
        syncSuperAdminLiveState();
    });
}

// Trigger actions from Master Control Panel
function triggerSimulatedClockIn() {
    fetch('/api/attendance/clock', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            staff_name: 'Verified Personnel',
            department: 'Clinical Operations',
            state: 'Lagos',
            terminal: 'BIO-HQ-01',
            clockedIn: true
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        alert('Super Admin Trigger: Biometric clock-in event broadcast across Attendify and HR module.');
        syncSuperAdminLiveState();
    });
}

function triggerLiveAuditAlert() {
    fetch('/api/complaints/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            category: 'Super Admin Audit Alert',
            title: 'Compliance Directive: Comprehensive Internal Review',
            source: 'Super Administrator Root Audit',
            state: 'National',
            severity: 'High',
            status: 'Open',
            details: 'Super Admin broadcast directive requiring immediate compliance review.'
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        alert('Super Admin Trigger: Compliance directive recorded and broadcast to Compliance Hub and DoC.');
        syncSuperAdminLiveState();
    });
}

function handleSuperAdminAction(actionType) {
    fetch('/api/superadmin/override', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: actionType,
            rationale: 'Executive console quick action trigger: ' + actionType
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        alert('Super Admin Action Executed: ' + resp.message);
        syncSuperAdminLiveState();
    });
}

function executeRootOverride() {
    var act = document.getElementById('superAdminActionSelect').value;
    var notes = document.getElementById('superAdminActionRationale').value;
    if (!notes.trim()) {
        alert('Please enter an executive justification before executing a root override.');
        return;
    }
    fetch('/api/superadmin/override', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: act,
            rationale: notes
        })
    }).then(function(res) { return res.json(); }).then(function(resp) {
        alert('Root Governance Action Executed: ' + act + '\n\n' + resp.message);
        closeModal('modalSuperAdminOverride');
        syncSuperAdminLiveState();
    });
}

// Close dropdown on outside click
document.addEventListener('click', function(e) {
    var wrapper = document.getElementById('saBannerNotificationWrapper');
    var dropdown = document.getElementById('saBannerNotificationDropdown');
    if (dropdown && wrapper && !wrapper.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    renderSaBannerNotifications();
    syncSuperAdminLiveState();
    setInterval(syncSuperAdminLiveState, 2000);
});

window.initSuperAdminModule = function() {
    renderSaBannerNotifications();
    syncSuperAdminLiveState();
};
</script>
