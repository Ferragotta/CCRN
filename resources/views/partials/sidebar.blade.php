@php
    $role = session('user_role') ?? request()->cookie('auth_role') ?? (auth()->check() ? auth()->user()->role : 'hr');
    $userName = session('user_name') ?? (auth()->check() ? auth()->user()->name : ($role === 'superadmin' ? 'Super Administrator' : ($role === 'hr' ? 'HR Manager' : 'Director of Compliance')));
    $roleBadge = $role === 'superadmin' ? 'SUPER ADMIN' : ($role === 'doc' ? 'ADMIN (DoC)' : ($role === 'hr' ? 'HR ACCESS' : ($role === 'compliance' ? 'COMPLIANCE' : 'STAFF ACCESS')));
    $avatar = $role === 'superadmin' ? 'SA' : ($role === 'doc' ? 'DC' : ($role === 'hr' ? 'HR' : ($role === 'compliance' ? 'CO' : 'ST')));
    $path = request()->path();
@endphp

<div class="sidebar">
    <div class="logo" style="padding: 18px 20px 14px; border-bottom: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="/assets/images/logo.png" alt="CCCRN Logo" style="height: 36px; display: block;">
            <div>
                <div class="logo-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: var(--accent); display: flex; align-items: center; gap: 6px;">
                    <span>CCCRN ComplianceIQ</span>
                    <span style="background: var(--accent); color: #fff; font-size: 9px; padding: 2px 5px; border-radius: 4px; font-weight: 700;">PRO</span>
                </div>
                <div class="logo-sub" style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px;">
                    {{ $role === 'superadmin' ? 'Super Admin Master Console' : ($role === 'doc' ? 'Director Command Center' : ($role === 'hr' ? 'HR Command Portal' : 'Compliance Portal')) }}
                </div>
            </div>
        </div>
    </div>

    <div class="nav" style="flex: 1; padding: 10px 0; overflow-y: auto;">
        <!-- 1. Executive Section -->
        @if(in_array($role, ['doc', 'superadmin']))
            <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Executive</div>
            <a href="/dashboard" class="nav-item {{ $path === 'dashboard' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'dashboard' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'dashboard' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'dashboard' ? '700' : '500' }};">
                <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-table-columns"></i></span>
                <span>Dashboard</span>
            </a>
        @endif

        <!-- 2. Workforce & Operations -->
        <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Workforce & Operations</div>
        @if($role === 'staff')
            <div class="nav-item-accordion" style="margin-bottom: 2px;">
                <div class="nav-item {{ $path === 'staff' ? 'active' : '' }}" onclick="toggleStaffAccordionMenu()" style="display: flex; align-items: center; justify-content: space-between; padding: 9px 18px; font-size: 13px; color: var(--text-dim); cursor: pointer; border-left: 3px solid {{ $path === 'staff' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'staff' ? 'var(--accent-light)' : 'transparent' }}; font-weight: 700;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-shield-halved" style="color: #0d9488;"></i></span>
                        <span>Staff Compliance</span>
                        <span class="badge" style="background: #0d9488; color: #fff; font-size: 9px; padding: 1px 6px; border-radius: 10px;">IQ</span>
                    </div>
                    <i class="fa-solid fa-chevron-down" id="staffPortalAccordionChevron" style="font-size: 10px; color: var(--text-muted); transition: transform 0.2s ease;"></i>
                </div>
                <!-- Expanded Submodules -->
                <div id="staffPortalSubmenu" style="display: block; background: rgba(2, 54, 123, 0.03); padding: 4px 0 6px;">
                    <a href="/staff#leave" onclick="if(window.switchStaffMainTab){switchStaffMainTab('leave');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
                        <i class="fa-solid fa-calendar-check" style="width: 14px; color: var(--text-muted);"></i>
                        <span>My Leave</span>
                    </a>
                    <a href="/staff#complaints" onclick="if(window.switchStaffMainTab){switchStaffMainTab('complaints');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
                        <i class="fa-solid fa-inbox" style="width: 14px; color: var(--text-muted);"></i>
                        <span>Complaints</span>
                    </a>
                    <a href="/staff#cap" onclick="if(window.switchStaffMainTab){switchStaffMainTab('cap');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
                        <i class="fa-solid fa-circle-check" style="width: 14px; color: var(--text-muted);"></i>
                        <span>Corrective Action (CAP)</span>
                    </a>
                    <a href="/staff#pdp" onclick="if(window.switchStaffMainTab){switchStaffMainTab('pdp');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
                        <i class="fa-solid fa-bullseye" style="width: 14px; color: var(--text-muted);"></i>
                        <span>PDP System</span>
                    </a>
                    <a href="/staff#training" onclick="if(window.switchStaffMainTab){switchStaffMainTab('training');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
                        <i class="fa-solid fa-graduation-cap" style="width: 14px; color: var(--text-muted);"></i>
                        <span>Training Academy</span>
                    </a>
                    <a href="/staff#policies" onclick="if(window.switchStaffMainTab){switchStaffMainTab('policies');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
                        <i class="fa-solid fa-file-contract" style="width: 14px; color: var(--text-muted);"></i>
                        <span>Policy Management</span>
                    </a>
                    <a href="/staff#lessons" onclick="if(window.switchStaffMainTab){switchStaffMainTab('lessons');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
                        <i class="fa-solid fa-book-bookmark" style="width: 14px; color: var(--text-muted);"></i>
                        <span>Lessons Learned</span>
                    </a>
                    <a href="/staff#ai" onclick="if(window.switchStaffMainTab){switchStaffMainTab('ai');return false;}" style="display: flex; align-items: center; gap: 10px; padding: 7px 18px 7px 46px; font-size: 12px; color: var(--text-dim); text-decoration: none;">
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
        @endif
        @if(in_array($role, ['hr', 'supervisor', 'hod', 'doc', 'superadmin']))
            <a href="/leave-attendance" class="nav-item {{ $path === 'leave-attendance' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'leave-attendance' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'leave-attendance' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'leave-attendance' ? '700' : '500' }};">
                <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-calendar-check"></i></span>
                <span>Leave & Attendance</span>
                <span class="badge" style="margin-left: auto; background: var(--warning); color: #000; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">PRO</span>
            </a>
        @endif

        <!-- 3. Core Modules -->
        <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Core Modules</div>
        
        <a href="/complaints" class="nav-item {{ $path === 'complaints' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'complaints' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'complaints' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'complaints' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-inbox"></i></span>
            <span>Complaints</span>
            @if($role === 'hr')
                <span class="badge" style="margin-left: auto; background: #e2e8f0; color: #475569; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">VIEW ONLY</span>
            @else
                <span class="badge" style="margin-left: auto; background: var(--danger); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">7</span>
            @endif
        </a>

        <a href="/cap" class="nav-item {{ $path === 'cap' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'cap' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'cap' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'cap' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-circle-check"></i></span>
            <span>Corrective Action</span>
            @if($role === 'hr')
                <span class="badge" style="margin-left: auto; background: #e2e8f0; color: #475569; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">VIEW ONLY</span>
            @else
                <span class="badge" style="margin-left: auto; background: var(--warning); color: #000; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">3</span>
            @endif
        </a>

        @if(in_array($role, ['hr', 'doc', 'superadmin', 'staff']))
            <a href="/pdp" class="nav-item {{ $path === 'pdp' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'pdp' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'pdp' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'pdp' ? '700' : '500' }};">
                <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-bullseye"></i></span>
                <span>PDP Tracker</span>
            </a>
        @endif

        <!-- 4. People & Training -->
        <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">People & Training</div>
        
        <a href="/training" class="nav-item {{ $path === 'training' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'training' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'training' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'training' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-graduation-cap"></i></span>
            <span>Training</span>
        </a>

        <a href="/states" class="nav-item {{ $path === 'states' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'states' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'states' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'states' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-map-location-dot"></i></span>
            <span>States & Clusters</span>
        </a>

        <!-- 5. Governance -->
        <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Governance</div>
        
        @if($role !== 'hr')
        @if($role !== 'hr')
        <a href="/risk" class="nav-item {{ $path === 'risk' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'risk' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'risk' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'risk' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-triangle-exclamation"></i></span>
            <span>Risk Register</span>
        </a>
        @endif

        <a href="/policies" class="nav-item {{ $path === 'policies' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'policies' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'policies' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'policies' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-file-shield"></i></span>
            <span>Policy Management</span>
            @if($role === 'hr')
                <span class="badge" style="margin-left: auto; background: #ede9fe; color: #6d28d9; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">ALL ACCESS</span>
            @endif
        </a>

        <a href="/lessons" class="nav-item {{ $path === 'lessons' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'lessons' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'lessons' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'lessons' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-lightbulb"></i></span>
            <span>Lessons Learned</span>
            @if($role === 'hr')
                <span class="badge" style="margin-left: auto; background: #ede9fe; color: #6d28d9; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">ALL ACCESS</span>
            @endif
        </a>

        @if(in_array($role, ['doc', 'superadmin']))
            <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Intelligence</div>
            <a href="/reports" class="nav-item {{ $path === 'reports' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'reports' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'reports' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'reports' ? '700' : '500' }};">
                <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-chart-pie"></i></span>
                <span>Reports & Donor</span>
            </a>
            <a href="/ai" class="nav-item {{ $path === 'ai' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'ai' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'ai' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'ai' ? '700' : '500' }};">
                <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-robot"></i></span>
                <span>AI Assistant</span>
            </a>
        @endif

        <!-- 6. Special & Field Modules -->
        <div class="nav-section" style="padding: 12px 18px 4px; font-size: 9px; letter-spacing: 1.5px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Special & Operations</div>
        
        @if($role !== 'hr')
        <a href="/ai-review" class="nav-item {{ $path === 'ai-review' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'ai-review' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'ai-review' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'ai-review' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-brain"></i></span>
            <span>AI Compliance Review</span>
            <span class="badge" style="margin-left: auto; background: var(--accent2); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">NEW</span>
        </a>
        @endif

        <a href="/investigations" class="nav-item {{ $path === 'investigations' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'investigations' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'investigations' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'investigations' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-shield-halved"></i></span>
            <span>Investigations</span>
            @if($role === 'hr')
                <span class="badge" style="margin-left: auto; background: #e2e8f0; color: #475569; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">VIEW ONLY</span>
            @else
                <span class="badge" style="margin-left: auto; background: var(--accent2); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">NEW</span>
            @endif
        </a>

        <a href="/travel" class="nav-item {{ $path === 'travel' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 18px; font-size: 13px; color: var(--text-dim); text-decoration: none; border-left: 3px solid {{ $path === 'travel' ? 'var(--accent)' : 'transparent' }}; background: {{ $path === 'travel' ? 'var(--accent-light)' : 'transparent' }}; font-weight: {{ $path === 'travel' ? '700' : '500' }};">
            <span class="icon" style="width: 18px; text-align: center;"><i class="fa-solid fa-plane-departure"></i></span>
            <span>Travel & Tickets</span>
            <span class="badge" style="margin-left: auto; background: var(--accent2); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 12px; font-weight: 700;">NEW</span>
        </a>
    </div>

    <div class="sidebar-footer" style="padding: 14px 18px; border-top: 1px solid var(--border); background: var(--surface2);">
        <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
            <div class="avatar" style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--accent2), var(--accent)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0;">{{ $avatar }}</div>
            <div>
                <div class="user-name" style="font-size: 12px; font-weight: 700; color: var(--text);">{{ $userName }}</div>
                <div class="user-role" style="font-size: 10px; color: var(--accent); font-weight: 600; text-transform: uppercase;">{{ $roleBadge }}</div>
            </div>
            <form action="/logout" method="POST" style="margin-left: auto;">
                @csrf
                <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 4px;" title="Sign Out">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</div>
