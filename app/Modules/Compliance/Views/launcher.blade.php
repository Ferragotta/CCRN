<!-- Attendify Sidebar Launcher Widget (Expandable) -->
@php
    $role = $context['role'] ?? 'staff';
@endphp

<li class="nav-item compliance-module-group">
    <a href="javascript:void(0)" class="nav-link compliance-launcher" onclick="toggleComplianceMenu(this)">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Compliance & Ethics</span>
        </div>
        <i class="fa-solid fa-chevron-down caret-icon"></i>
    </a>
    
    <ul class="compliance-submenu" style="display: none;">
        <!-- Baseline Staff Capabilities -->
        <li>
            <a href="javascript:void(0)" onclick="loadComplianceModule('pdp')"><i class="fa-solid fa-bullseye"></i> PDP</a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadComplianceModule('training')"><i class="fa-solid fa-graduation-cap"></i> Training</a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadComplianceModule('complaints')"><i class="fa-solid fa-hand-paper"></i> Complaints</a>
        </li>

        <!-- HR Capabilities -->
        @if($role == 'hr' || $role == 'doc')
        <li>
            <a href="javascript:void(0)" onclick="loadComplianceModule('hr-dashboard')"><i class="fa-solid fa-users-gear"></i> HR Admin</a>
        </li>
        @endif

        <!-- Compliance Capabilities -->
        @if($role == 'compliance' || $role == 'doc')
        <li>
            <a href="javascript:void(0)" onclick="loadComplianceModule('investigations')"><i class="fa-solid fa-magnifying-glass"></i> Investigations</a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadComplianceModule('risk')"><i class="fa-solid fa-triangle-exclamation"></i> Risk Register</a>
        </li>
        @endif

        <!-- DoC Capabilities -->
        @if($role == 'doc')
        <li>
            <a href="javascript:void(0)" onclick="loadComplianceModule('command')"><i class="fa-solid fa-globe"></i> Command Center</a>
        </li>
        @endif
    </ul>
</li>

<style>
    .compliance-launcher {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        color: var(--text, #333);
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.2s;
    }
    .compliance-launcher:hover {
        background-color: var(--surface2, #f3f4f6);
    }
    .compliance-launcher i {
        color: var(--accent, #02367B);
        font-size: 1.1em;
    }
    .compliance-launcher .caret-icon {
        font-size: 0.8em;
        transition: transform 0.3s;
    }
    .compliance-launcher.expanded .caret-icon {
        transform: rotate(180deg);
    }
    
    .compliance-submenu {
        list-style: none;
        padding: 0;
        margin: 5px 0 0 0;
        background: rgba(0,0,0,0.02);
        border-radius: 6px;
    }
    .compliance-submenu li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 15px 8px 35px;
        color: var(--text-muted, #666);
        text-decoration: none;
        font-size: 0.9em;
        transition: all 0.2s;
    }
    .compliance-submenu li a:hover, .compliance-submenu li a.active {
        color: var(--accent, #02367B);
        background: rgba(2, 54, 123, 0.05);
    }
</style>

<script>
    window.toggleComplianceMenu = function(element) {
        element.classList.toggle('expanded');
        const submenu = element.nextElementSibling;
        if (submenu.style.display === 'none' || submenu.style.display === '') {
            submenu.style.display = 'block';
        } else {
            submenu.style.display = 'none';
        }
    };

    window.loadComplianceModule = function(moduleName) {
        // Highlight active sub-menu item
        document.querySelectorAll('.compliance-submenu li a').forEach(a => a.classList.remove('active'));
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }
        
        // This function should be defined in the host application to handle the routing
        // For the test harness, it will call a global function
        if (typeof window.hostAppLoadComplianceModule === 'function') {
            window.hostAppLoadComplianceModule(moduleName);
        } else {
            // Fallback for real app
            window.location.href = `/attendify/modules/compliance/${moduleName}`;
        }
    };
</script>
