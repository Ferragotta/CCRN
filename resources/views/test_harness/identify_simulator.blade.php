<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCCRN ComplianceIQ™ — Integrated Host Environment</title>
    <!-- FontAwesome 6 & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --identify-teal: #0077b6;
            --identify-dark: #022452;
            --identify-bg: #f8fafc;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
            --ccrn-navy: #02367B;
            --ccrn-blue: #0077b6;
            --ccrn-cyan: #55E2E9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--identify-bg);
            color: var(--text);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* 1. Host Simulator Control Banner */
        .simulator-header {
            background: #011b3d;
            color: #ffffff;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            font-size: 12px;
            border-bottom: 2px solid #55E2E9;
            z-index: 1000;
        }

        .role-btn {
            background: #1e293b;
            color: #e2e8f0;
            border: 1px solid #334155;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .role-btn.active {
            background: #0077b6;
            color: #ffffff;
            border-color: #55E2E9;
        }

        /* 2. Host Layout */
        .app-layout {
            display: flex;
            flex: 1;
            height: calc(100vh - 48px);
            overflow: hidden;
        }

        /* Simulated Identify Sidebar */
        .identify-sidebar {
            width: 240px;
            background: linear-gradient(180deg, #022452 0%, #011838 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .identify-brand {
            padding: 18px 20px;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-list {
            list-style: none;
            padding: 15px 0;
            flex: 1;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-item a:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.08);
        }

        .nav-item.active a {
            color: #ffffff;
            background: rgba(0, 119, 182, 0.4);
            border-left: 4px solid #55E2E9;
        }

        /* Expandable Accordion Menu */
        .compliance-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 20px;
            color: #ffffff;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            background: rgba(0, 119, 182, 0.35);
            border-left: 4px solid #55E2E9;
            transition: background 0.2s ease;
            user-select: none;
        }

        .compliance-menu-header:hover {
            background: rgba(0, 119, 182, 0.55);
        }

        .submenu-chevron {
            font-size: 11px;
            transition: transform 0.25s ease;
            color: #55E2E9;
        }

        .submenu-chevron.expanded {
            transform: rotate(90deg);
        }

        .compliance-submenu {
            list-style: none;
            padding: 4px 0 6px;
            margin: 0;
            background: rgba(0, 0, 0, 0.22);
        }

        .submenu-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px 8px 36px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .submenu-item a:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .submenu-item.active a {
            color: #55E2E9;
            font-weight: 700;
            background: rgba(85, 226, 233, 0.15);
        }

        /* Simulated Identify Topbar */
        .identify-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #ffffff;
        }

        .identify-topbar {
            height: 60px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            background: #ffffff;
            flex-shrink: 0;
        }

        /* Embedded Module Container */
        .module-frame-container {
            flex: 1;
            overflow: hidden;
            position: relative;
            background: #f8fafc;
        }

        iframe#complianceFrame {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>

    <!-- ComplianceIQ Integrated Host Environment Top Bar -->
    <div class="simulator-header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="background: #ffffff; padding: 4px 10px; border-radius: 6px; display: flex; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                <img src="/assets/images/logo.png" alt="CCCRN Logo" style="height: 24px; display: block;" onerror="this.style.display='none'">
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <strong style="font-size: 13px; letter-spacing: 0.3px; color: #ffffff;">CCCRN ComplianceIQ™</strong>
                <span style="background: rgba(85, 226, 233, 0.15); color: #55E2E9; border: 1px solid rgba(85, 226, 233, 0.4); font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px;">
                    <i class="fa-solid fa-link me-1"></i> Host SSO Integration Environment
                </span>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">

            <!-- NOTIFICATION ALERT BELL (HOST LEVEL) -->
            <div style="position: relative;">
                <button id="identifyHostNotifBell" onclick="toggleHostNotifDropdown()" style="position: relative; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color: #ffffff; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                    <i class="fa-solid fa-bell"></i>
                    <span id="identifyHostNotifBadge" style="display: none; position: absolute; top: -4px; right: -4px; background: #dc2626; color: #ffffff; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 10px; border: 2px solid #012454;">0</span>
                </button>
                <div id="identifyHostNotifDropdown" style="display: none; position: absolute; right: 0; top: 38px; width: 290px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; padding: 12px; color: #0f172a;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 8px;">
                        <strong style="font-size: 12px; color: #02367B;"><i class="fa-solid fa-bell me-1"></i> System Alert Notifications</strong>
                        <span id="identifyHostNotifCount" style="font-size: 10px; color: #64748b;">0 Active</span>
                    </div>
                    <div id="identifyHostNotifList" style="max-height: 200px; overflow-y: auto; font-size: 11px;">
                        <div style="text-align: center; color: #94a3b8; padding: 16px 8px;">
                            <i class="fa-solid fa-bell-slash" style="font-size: 18px; margin-bottom: 4px; display: block;"></i>
                            No unread notifications at this time
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.08); padding: 5px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.15); font-size: 11.5px; color: #e2e8f0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-user-shield" style="color: #55E2E9;"></i>
                <span>Active: <strong id="activeHostUserName" style="color: #ffffff;">Authenticated User</strong> (<span id="activeHostUserRole" style="color: #55E2E9; font-weight: 700;">SSO Session</span>)</span>
            </div>
        </div>
    </div>

    <!-- Identify Host Application Layout -->
    <div class="app-layout">
        <!-- Sidebar -->
        <div class="identify-sidebar">
            <div class="identify-brand" style="gap: 12px; padding: 14px 18px; display: flex; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="background: #ffffff; padding: 4px 8px; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                    <img src="/assets/images/logo.png" alt="CCCRN Logo" style="height: 26px; display: block;" onerror="this.style.display='none'">
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 800; color: #ffffff; line-height: 1.2;">ComplianceIQ</div>
                    <div style="font-size: 10px; color: #55E2E9; font-weight: 700; letter-spacing: 0.5px;">HOST PORTAL</div>
                </div>
            </div>

            <ul class="nav-list">
                

                <!-- EMBEDDED MODULE LINK IN IDENTIFY (EXPANDABLE SUBMENU) -->
                <li style="padding: 16px 20px 6px; font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;">
                    Embedded Modules
                </li>
                <li class="nav-item-accordion">
                    <div class="compliance-menu-header" onclick="toggleComplianceSidebarMenu()" id="sidebarComplianceHeader">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-shield-halved" style="width: 16px; color: #55E2E9;"></i>
                            <span>Staff Compliance</span>
                            <span style="background: #55E2E9; color: #012454; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 4px;">IQ</span>
                        </div>
                        <i class="fa-solid fa-chevron-right submenu-chevron expanded" id="complianceSidebarChevron"></i>
                    </div>

                    <!-- Submodules List -->
                    <ul class="compliance-submenu" id="complianceSubmenuList" style="display: block;">
                        <li class="submenu-item" id="subitem-leave">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('leave', this)">
                                <i class="fa-solid fa-calendar-check" style="width: 14px;"></i>
                                <span>My Leave &amp; Applications</span>
                            </a>
                        </li>
                        <li class="submenu-item active" id="subitem-complaints">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('complaints', this)">
                                <i class="fa-solid fa-inbox" style="width: 14px;"></i>
                                <span>Complaints</span>
                                <span class="badge" id="identifyComplaintsBadge" style="margin-left: auto; background: var(--danger, #dc2626); color: #fff; font-size: 9px; padding: 1px 5px; border-radius: 10px; display: none;">0</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-fieldwork">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('fieldwork', this)">
                                <i class="fa-solid fa-map-location-dot" style="width: 14px; color: #10b981;"></i>
                                <span>Field Work &amp; Missions</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-cap">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('cap', this)">
                                <i class="fa-solid fa-circle-check" style="width: 14px;"></i>
                                <span>Corrective Action (CAP)</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-pdp">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('pdp', this)">
                                <i class="fa-solid fa-bullseye" style="width: 14px;"></i>
                                <span>PDP System (150 Pts)</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-training">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('training', this)">
                                <i class="fa-solid fa-graduation-cap" style="width: 14px;"></i>
                                <span>Training Academy</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-states" style="display: none;">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('states', this)">
                                <i class="fa-solid fa-building-flag" style="width: 14px;"></i>
                                <span>State &amp; Clusters (STL)</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-policies">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('policies', this)">
                                <i class="fa-solid fa-file-contract" style="width: 14px;"></i>
                                <span>Policy Management</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-lessons">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('lessons', this)">
                                <i class="fa-solid fa-book-bookmark" style="width: 14px;"></i>
                                <span>Lessons Learned</span>
                            </a>
                        </li>
                        <li class="submenu-item" id="subitem-ai">
                            <a href="javascript:void(0)" onclick="loadIdentifySubmodule('ai', this)">
                                <i class="fa-solid fa-robot" style="width: 14px;"></i>
                                <span>AI Staff Helpdesk</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div style="padding: 16px 20px; font-size: 11px; color: rgba(255,255,255,0.5); border-top: 1px solid rgba(255,255,255,0.1);">
                Identify Version: <strong>v3.4.2</strong><br>
                SSO Bridge: <strong>Connected</strong>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="identify-main">
            <div class="identify-topbar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="font-weight: 700; font-size: 15px; color: #0f172a;" id="topbarModuleTitle">
                        Staff Compliance &amp; Governance Hub
                    </div>
                    <span style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                        Standalone Module
                    </span>
                </div>

                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="text-align: right;">
                        <div style="font-size: 13px; font-weight: 700;" id="identifyUserName">Authenticated User</div>
                        <div style="font-size: 11px; color: var(--text-muted);" id="identifyUserDept">Identity Resolved via Host SSO</div>
                    </div>
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: #02367B; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;" id="identifyUserAvatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
            </div>

            <!-- Embedded Compliance Frame -->
            <div class="module-frame-container">
                <iframe id="complianceFrame" src="/staff-embed"></iframe>
            </div>
        </div>
    </div>

    <script>
        // Dynamic Identity Resolution from Host Session / SSO
        function resolveHostIdentity() {
            var urlParams = new URLSearchParams(window.location.search);
            var paramUser = urlParams.get('user');
            var paramRole = urlParams.get('role');
            var paramName = urlParams.get('name');
            var paramDept = urlParams.get('dept');
            var paramState = urlParams.get('state');

            if (window.ATTENDIFY_HOST_USER && typeof window.ATTENDIFY_HOST_USER === 'object') {
                return window.ATTENDIFY_HOST_USER;
            }

            if (paramName || paramUser) {
                var displayName = paramName || paramUser;
                var initials = displayName.split(' ').map(function(n) { return n[0]; }).join('').substring(0, 2).toUpperCase();
                return {
                    name: displayName,
                    id: urlParams.get('id') || 'CCCRN-SSO-USER',
                    dept: paramDept || 'CCCRN Operational Services',
                    state: paramState || 'National Office',
                    avatar: initials || 'AU',
                    role: paramRole || 'staff'
                };
            }

            // Standard authenticated session resolved dynamically
            return {
                name: 'Authenticated Staff',
                id: 'CCCRN-STF-ACTIVE',
                dept: 'Operations & Public Health Support',
                state: 'Nigeria Country Office',
                avatar: 'AS',
                role: 'staff'
            };
        }

        var CURRENT_HOST_USER = resolveHostIdentity();

        function syncHostUserDisplay(user) {
            if (!user) return;
            CURRENT_HOST_USER = user;

            var nameEl = document.getElementById('identifyUserName');
            var deptEl = document.getElementById('identifyUserDept');
            var avatarEl = document.getElementById('identifyUserAvatar');
            var topNameEl = document.getElementById('activeHostUserName');
            var topRoleEl = document.getElementById('activeHostUserRole');

            if (nameEl) nameEl.innerText = user.name || 'Authenticated User';
            if (deptEl) deptEl.innerText = user.dept || 'Identity Resolved via Host SSO';
            if (avatarEl) {
                if (user.avatar && user.avatar.length <= 3) {
                    avatarEl.innerText = user.avatar;
                } else {
                    avatarEl.innerHTML = '<i class="fa-solid fa-user"></i>';
                }
            }
            if (topNameEl) topNameEl.innerText = user.name || 'Authenticated User';
            if (topRoleEl) topRoleEl.innerText = (user.role ? user.role.toUpperCase() : 'SSO ACTIVE');

            var statesItem = document.getElementById('subitem-states');
            if (statesItem) statesItem.style.display = (user.role === 'stl') ? 'block' : 'none';

            // Forward dynamic identity to embedded compliance module
            dispatchIdentityToFrame(user);
        }

        function dispatchIdentityToFrame(user) {
            var frame = document.getElementById('complianceFrame');
            if (frame && frame.contentWindow) {
                frame.contentWindow.postMessage({
                    action: 'SET_USER_CONTEXT',
                    payload: user
                }, '*');

                if (frame.contentWindow.switchStaffRolePerspective && user.role) {
                    frame.contentWindow.switchStaffRolePerspective(user.role);
                }
            }
        }

        function toggleComplianceSidebarMenu() {
            var menu = document.getElementById('complianceSubmenuList');
            var chevron = document.getElementById('complianceSidebarChevron');
            if (!menu || !chevron) return;

            if (menu.style.display === 'none') {
                menu.style.display = 'block';
                chevron.classList.add('expanded');
            } else {
                menu.style.display = 'none';
                chevron.classList.remove('expanded');
            }
        }

        function loadIdentifySubmodule(key, btn) {
            document.querySelectorAll('.submenu-item').forEach(function(el) {
                el.classList.remove('active');
            });
            if (btn) btn.closest('.submenu-item').classList.add('active');

            // Trigger tab switch inside embedded frame
            var frame = document.getElementById('complianceFrame');
            if (frame && frame.contentWindow) {
                if (frame.contentWindow.switchStaffMainTab) {
                    frame.contentWindow.switchStaffMainTab(key);
                } else {
                    frame.contentWindow.postMessage({
                        action: 'SWITCH_TAB',
                        payload: key
                    }, '*');
                }
            }
        }

        // When embedded iframe finishes loading, send dynamic identity context
        var frame = document.getElementById('complianceFrame');
        if (frame) {
            frame.addEventListener('load', function() {
                dispatchIdentityToFrame(CURRENT_HOST_USER);
            });
        }

        // Listen for external SSO identity message from parent or container
        window.addEventListener('message', function(event) {
            if (!event.data) return;
            if (event.data.action === 'SET_HOST_USER' || event.data.action === 'SET_USER_CONTEXT') {
                syncHostUserDisplay(event.data.payload);
            }
        });

        
        function toggleHostNotifDropdown() {
            var drop = document.getElementById('identifyHostNotifDropdown');
            if (drop) {
                drop.style.display = (drop.style.display === 'none' || drop.style.display === '') ? 'block' : 'none';
            }
        }

        function checkHostNotifications() {
            fetch('/api/backend/data')
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var count = 0;
                    var items = [];
                    if (data.leave_requests && data.leave_requests.length > 0) {
                        data.leave_requests.forEach(function(l) {
                            if (l.status === 'Approved') {
                                count++;
                                items.push({ title: 'Leave Approved (' + l.id + ')', text: 'Approved for ' + l.days + ' days by HR', icon: 'fa-calendar-check', color: '#059669' });
                            }
                        });
                    }
                    if (data.caps && data.caps.length > 0) {
                        data.caps.forEach(function(c) {
                            if (!c.hasEvidence) {
                                count++;
                                items.push({ title: 'CAP Action Pending: ' + c.id, text: c.title, icon: 'fa-triangle-exclamation', color: '#dc2626' });
                            }
                        });
                    }
                    if (data.field_work && data.field_work.length > 0) {
                        data.field_work.forEach(function(fw) {
                            items.push({ title: 'Field Mission Active: ' + fw.ref, text: fw.destination + ' (' + fw.activity_type + ')', icon: 'fa-map-pin', color: '#0284c7' });
                        });
                    }

                    var badge = document.getElementById('identifyHostNotifBadge');
                    var countEl = document.getElementById('identifyHostNotifCount');
                    var listEl = document.getElementById('identifyHostNotifList');
                    var compBadge = document.getElementById('identifyComplaintsBadge');

                    if (badge) {
                        badge.innerText = count;
                        badge.style.display = count > 0 ? 'inline-block' : 'none';
                    }
                    if (countEl) countEl.innerText = count + ' Active';

                    if (compBadge && data.complaints) {
                        compBadge.innerText = data.complaints.length;
                        compBadge.style.display = data.complaints.length > 0 ? 'inline-block' : 'none';
                    }

                    if (listEl && items.length > 0) {
                        var html = '';
                        items.forEach(function(it) {
                            html += '<div style="padding: 6px 0; border-bottom: 1px solid #f1f5f9; display: flex; gap: 8px; align-items: flex-start;">' +
                                '<i class="fa-solid ' + it.icon + '" style="color: ' + it.color + '; margin-top: 2px;"></i>' +
                                '<div><strong style="color: #0f172a;">' + it.title + '</strong><div style="color: #64748b; font-size: 10px;">' + it.text + '</div></div>' +
                            '</div>';
                        });
                        listEl.innerHTML = html;
                    }
                }).catch(function(e) {});
        }

        setInterval(checkHostNotifications, 3000);
        checkHostNotifications();

        document.addEventListener('DOMContentLoaded', function() {
            syncHostUserDisplay(CURRENT_HOST_USER);
        });
    </script>
</body>
</html>