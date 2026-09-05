<div class="topbar" style="position: relative;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div class="topbar-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--text);">
            Executive Command Center
        </div>
    </div>

    <!-- Perfectly Inlined Controls -->
    <div style="display: flex; align-items: center; gap: 12px;">
        <!-- Live Operations Badge -->
        <div style="display: flex; align-items: center; gap: 6px; background: rgba(5, 150, 105, 0.08); padding: 6px 12px; border-radius: 20px; border: 1px solid rgba(5, 150, 105, 0.2); font-size: 11px; color: var(--success); font-weight: 600; white-space: nowrap;">
            <span style="width: 7px; height: 7px; border-radius: 50%; background: var(--success);"></span>
            Live Operations
        </div>

        <!-- Global Search -->
        <div style="position: relative; width: 260px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 11px; color: var(--text-muted); font-size: 12px;"></i>
            <input type="text" placeholder="Search incidents, CAPs, staff..." style="width: 100%; height: 36px; padding: 0 12px 0 34px; background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; font-size: 12px; outline: none; box-sizing: border-box; color: var(--text);">
        </div>

        <!-- Active Notification Bell Wrapper -->
        <div style="position: relative;" id="topbarNotificationWrapper">
            <button id="topbarNotificationBellBtn" onclick="toggleTopbarNotifications(event)" style="width: 36px; height: 36px; border-radius: 6px; border: 1px solid var(--border); background: var(--surface); color: var(--text-dim); display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; transition: all 0.15s ease;" title="Notifications & Alerts">
                <i class="fa-solid fa-bell" style="font-size: 15px; color: var(--accent);"></i>
                <span id="topbarNotificationBadge" style="position: absolute; top: -3px; right: -3px; min-width: 16px; height: 16px; padding: 0 4px; background: #dc2626; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 800; display: none; align-items: center; justify-content: center; border: 2px solid #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                    0
                </span>
            </button>

            <!-- Notification Dropdown Menu -->
            <div id="topbarNotificationDropdown" style="display: none; position: absolute; right: 0; top: 46px; width: 375px; max-width: 90vw; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1); z-index: 1000; overflow: hidden;">
                <!-- Header -->
                <div style="padding: 12px 16px; background: var(--surface2); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-bell" style="color: #02367B;"></i>
                        <span id="topbarDropdownTitle" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 13px; color: var(--text);">Operational Notifications &amp; Alerts</span>
                    </div>
                    <span id="topbarUnreadBadge" style="background: #e0f2fe; color: #02367B; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 12px;">
                        0 Action Items
                    </span>
                </div>

                <!-- Notification Relay Target Ribbon -->
                <div id="topbarNotificationRelayRibbon" style="padding: 7px 14px; background: #eff6ff; border-bottom: 1px solid #bfdbfe; font-size: 10.5px; color: #1e40af; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 6px; overflow: hidden;">
                        <i class="fa-solid fa-paper-plane" style="color: #0284c7; flex-shrink: 0;"></i>
                        <span style="flex-shrink: 0;">Alerts Routed To:</span>
                        <strong id="topbarRelayEmailBadge" style="color: #02367B; text-decoration: underline; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">director@cccrn.org</strong>
                    </div>
                    <span style="background: #dbeafe; color: #1e40af; font-size: 9px; font-weight: 700; padding: 1px 5px; border-radius: 4px; flex-shrink: 0;">LIVE RELAY</span>
                </div>

                <div id="topbarNotificationList" style="max-height: 380px; overflow-y: auto; padding: 6px 0;">
                    <!-- Injected dynamically by syncTopbarNotificationsFromBackend -->
                </div>

                <!-- Footer -->
                <div style="padding: 10px 14px; background: #f8fafc; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <a href="javascript:void(0)" onclick="markAllNotificationsRead()" style="font-size: 11px; color: #0077b6; text-decoration: none; font-weight: 600;">
                        <i class="fa-solid fa-check-double me-1"></i> Mark all read
                    </a>
                    <a href="javascript:void(0)" onclick="handleQuickDeskLink()" style="font-size: 11px; color: #02367B; text-decoration: none; font-weight: 700;">
                        Open Desk <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
var TOPBAR_NOTIFICATIONS = [];
var READ_NOTIFICATION_IDS = {};

function getCachedOfficerEmail() {
    var role = window.CURRENT_USER_ROLE || 'doc';
    if (role === 'superadmin') {
        return 'superadmin@cccrn.org';
    } else if (role === 'hr') {
        return localStorage.getItem('complianceiq_hr_email') || localStorage.getItem('cached_officer_email') || 'hr@cccrn.org';
    } else {
        return localStorage.getItem('complianceiq_doc_email') || localStorage.getItem('cached_officer_email') || 'director@cccrn.org';
    }
}

function updateRelayBannerEmail() {
    var emailEl = document.getElementById('topbarRelayEmailBadge');
    if (emailEl) {
        emailEl.innerText = getCachedOfficerEmail();
    }
    var titleEl = document.getElementById('topbarDropdownTitle');
    if (titleEl) {
        var role = window.CURRENT_USER_ROLE || 'doc';
        if (role === 'superadmin') {
            titleEl.innerText = 'Super Admin Master Command Alerts';
        } else if (role === 'hr') {
            titleEl.innerText = 'HR Notifications & Alerts';
        } else {
            titleEl.innerText = 'DoC Executive Command Alerts';
        }
    }
}

function handleQuickDeskLink() {
    var role = window.CURRENT_USER_ROLE || 'doc';
    toggleTopbarNotifications();
    if (typeof switchPanel === 'function') {
        if (role === 'hr') {
            switchPanel('leave-attendance');
        } else {
            switchPanel('complaints');
        }
    }
}

function toggleTopbarNotifications(e) {
    if (e) e.stopPropagation();
    var dropdown = document.getElementById('topbarNotificationDropdown');
    if (!dropdown) return;
    var isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) {
        updateRelayBannerEmail();
        syncTopbarNotificationsFromBackend();
    }
}

function renderTopbarNotifications() {
    var listContainer = document.getElementById('topbarNotificationList');
    var badge = document.getElementById('topbarNotificationBadge');
    var unreadBadge = document.getElementById('topbarUnreadBadge');
    if (!listContainer) return;

    var unreadCount = TOPBAR_NOTIFICATIONS.filter(function(n) { return n.unread && !READ_NOTIFICATION_IDS[n.id]; }).length;
    if (badge) {
        badge.innerText = unreadCount;
        badge.style.display = unreadCount > 0 ? 'flex' : 'none';
    }
    if (unreadBadge) {
        unreadBadge.innerText = unreadCount + ' Action Item' + (unreadCount !== 1 ? 's' : '');
    }

    if (TOPBAR_NOTIFICATIONS.length === 0) {
        listContainer.innerHTML = '<div style="padding: 28px 16px; text-align: center; color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-bell-slash me-1" style="font-size: 16px; color: var(--border);"></i><br><span style="margin-top: 6px; display: inline-block;">No pending alerts. All modules in good standing.</span></div>';
        return;
    }

    var html = '';
    TOPBAR_NOTIFICATIONS.forEach(function(n) {
        var isUnread = n.unread && !READ_NOTIFICATION_IDS[n.id];
        var unreadBg = isUnread ? 'background: rgba(2, 54, 123, 0.04); font-weight: 600;' : 'background: #ffffff;';
        html += '<div onclick="handleNotificationClick(\'' + n.id + '\', \'' + n.actionPanel + '\')" style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; cursor: pointer; transition: background 0.15s ease; ' + unreadBg + '" onmouseover="this.style.background=\'#f8fafc\'" onmouseout="this.style.background=\'' + (isUnread ? 'rgba(2, 54, 123, 0.04)' : '#ffffff') + '\'">' +
            '<div style="width: 32px; height: 32px; border-radius: 50%; background: ' + n.iconBg + '; color: ' + n.iconColor + '; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 13px;">' +
                '<i class="fa-solid ' + n.icon + '"></i>' +
            '</div>' +
            '<div style="flex: 1; min-width: 0;">' +
                '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">' +
                    '<strong style="font-size: 12px; color: var(--text);">' + n.title + '</strong>' +
                    '<span style="font-size: 10px; color: var(--text-muted); flex-shrink: 0;">' + n.time + '</span>' +
                '</div>' +
                '<div style="font-size: 11px; color: var(--text-dim); line-height: 1.35; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + n.desc + '</div>' +
            '</div>' +
            (isUnread ? '<span style="width: 7px; height: 7px; border-radius: 50%; background: #0077b6; align-self: center; flex-shrink: 0;"></span>' : '') +
        '</div>';
    });
    listContainer.innerHTML = html;
}

function handleNotificationClick(id, panel) {
    READ_NOTIFICATION_IDS[id] = true;
    var notif = TOPBAR_NOTIFICATIONS.find(function(n) { return n.id === id; });
    if (notif) notif.unread = false;
    updateRelayBannerEmail();
    renderTopbarNotifications();
    var dropdown = document.getElementById('topbarNotificationDropdown');
    if (dropdown) dropdown.style.display = 'none';

    if (panel && typeof switchPanel === 'function') {
        switchPanel(panel);
    }
}

function markAllNotificationsRead() {
    TOPBAR_NOTIFICATIONS.forEach(function(n) {
        n.unread = false;
        READ_NOTIFICATION_IDS[n.id] = true;
    });
    renderTopbarNotifications();
}

function syncTopbarNotificationsFromBackend() {
    fetch('/api/backend/data')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (!data) return;
            var newItems = [];

            // 1. Complaints requiring DoC Triage & Action
            if (data.complaints && Array.isArray(data.complaints)) {
                var openComplaints = data.complaints.filter(function(c) {
                    var s = (c.status || '').toLowerCase();
                    return s.includes('open') || s.includes('triage');
                });
                if (openComplaints.length > 0) {
                    var topC = openComplaints[0];
                    newItems.push({
                        id: 'live-comp-alert-' + topC.id,
                        title: 'Complaint Logged (' + openComplaints.length + ' Pending)',
                        desc: topC.id + ' — ' + (topC.title || topC.category) + ' (' + (topC.severity || 'Medium') + ')',
                        time: topC.date || 'Today',
                        type: 'complaint',
                        icon: 'fa-triangle-exclamation',
                        iconBg: '#fee2e2',
                        iconColor: '#dc2626',
                        actionPanel: 'complaints',
                        unread: true
                    });
                }
            }

            // 2. CAP Remediation Evidence Awaiting Verification
            if (data.caps && Array.isArray(data.caps)) {
                var pendingEvidence = data.caps.filter(function(cp) {
                    var s = (cp.status || '').toLowerCase();
                    return s.includes('evidence') || cp.hasEvidence;
                });
                if (pendingEvidence.length > 0) {
                    var topCap = pendingEvidence[0];
                    newItems.push({
                        id: 'live-cap-alert-' + topCap.id,
                        title: 'CAP Evidence Awaiting Sign-off (' + pendingEvidence.length + ')',
                        desc: topCap.id + ' — Proof submitted for ' + (topCap.title || topCap.issue || 'finding'),
                        time: 'Review',
                        type: 'cap',
                        icon: 'fa-circle-check',
                        iconBg: '#fef3c7',
                        iconColor: '#d97706',
                        actionPanel: 'cap',
                        unread: true
                    });
                }
            }

            // 3. Field Work Missions (For Super Admin oversight)
            if (data.field_work && Array.isArray(data.field_work) && data.field_work.length > 0) {
                var topFw = data.field_work[0];
                newItems.push({
                    id: 'live-fw-alert-' + topFw.ref,
                    title: 'Active Field Mission (' + data.field_work.length + ')',
                    desc: topFw.staff_name + ' deployed to ' + topFw.destination + ' (' + topFw.activity_type + ')',
                    time: 'Active',
                    type: 'fieldwork',
                    icon: 'fa-route',
                    iconBg: '#ede9fe',
                    iconColor: '#7c3aed',
                    actionPanel: 'travel',
                    unread: true
                });
            }

            // 4. Pending Staff Leave Requests (For HR, DoC & Super Admin)
            if (data.leave_requests && Array.isArray(data.leave_requests)) {
                var pendingLeaves = data.leave_requests.filter(function(r) {
                    return r.status && r.status.includes('Pending');
                });
                if (pendingLeaves.length > 0) {
                    var topReq = pendingLeaves[0];
                    newItems.push({
                        id: 'live-leave-alert-' + topReq.id,
                        title: 'Staff Leave Verification (' + pendingLeaves.length + ')',
                        desc: topReq.staff_name + ' applied for ' + topReq.category + ' (' + (topReq.days || 1) + 'd)',
                        time: 'Pending',
                        type: 'leave',
                        icon: 'fa-calendar-check',
                        iconBg: '#e0f2fe',
                        iconColor: '#02367B',
                        actionPanel: 'leave-attendance',
                        unread: true
                    });
                }
            }

            // Preserve read states
            newItems.forEach(function(item) {
                if (READ_NOTIFICATION_IDS[item.id]) {
                    item.unread = false;
                }
            });

            TOPBAR_NOTIFICATIONS = newItems;
            renderTopbarNotifications();
        }).catch(function(e) {});
}

// Close dropdown on outside click
document.addEventListener('click', function(e) {
    var wrapper = document.getElementById('topbarNotificationWrapper');
    var dropdown = document.getElementById('topbarNotificationDropdown');
    if (dropdown && wrapper && !wrapper.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    updateRelayBannerEmail();
    renderTopbarNotifications();
    syncTopbarNotificationsFromBackend();
    setInterval(syncTopbarNotificationsFromBackend, 3000);
});
</script>
