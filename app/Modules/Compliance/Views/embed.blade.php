<div class="compliance-module-container">
    <div class="compliance-header">
        <div class="header-left">
            <h2><i class="fa-solid fa-shield-halved"></i> ComplianceIQ</h2>
            <span class="role-badge role-{{ $context['role'] ?? 'staff' }}">
                {{ ucfirst($context['role'] ?? 'staff') }} Portal
            </span>
        </div>
        <div class="header-right">
            <span class="user-greeting">Welcome, {{ $context['name'] ?? 'User' }}</span>
        </div>
    </div>

    <div class="compliance-nav-chips">
        <!-- Baseline Staff Capabilities -->
        <a href="#pdp" class="chip {{ $activeSubmodule == 'pdp' ? 'active' : '' }}" onclick="switchComplianceSubmodule('pdp')"><i class="fa-solid fa-bullseye"></i> PDP</a>
        <a href="#training" class="chip {{ $activeSubmodule == 'training' ? 'active' : '' }}" onclick="switchComplianceSubmodule('training')"><i class="fa-solid fa-graduation-cap"></i> Training</a>
        <a href="#complaints" class="chip {{ $activeSubmodule == 'complaints' ? 'active' : '' }}" onclick="switchComplianceSubmodule('complaints')"><i class="fa-solid fa-hand-paper"></i> Complaints</a>
        
        <!-- HR Capabilities -->
        @if(in_array('hr', (array)($context['role'] ?? [])) || in_array('doc', (array)($context['role'] ?? [])))
            <a href="#hr-dashboard" class="chip {{ $activeSubmodule == 'hr-dashboard' ? 'active' : '' }}" onclick="switchComplianceSubmodule('hr-dashboard')"><i class="fa-solid fa-users-gear"></i> HR Admin</a>
        @endif
        
        <!-- Compliance Capabilities -->
        @if(in_array('compliance', (array)($context['role'] ?? [])) || in_array('doc', (array)($context['role'] ?? [])))
            <a href="#investigations" class="chip {{ $activeSubmodule == 'investigations' ? 'active' : '' }}" onclick="switchComplianceSubmodule('investigations')"><i class="fa-solid fa-magnifying-glass"></i> Investigations</a>
            <a href="#risk" class="chip {{ $activeSubmodule == 'risk' ? 'active' : '' }}" onclick="switchComplianceSubmodule('risk')"><i class="fa-solid fa-triangle-exclamation"></i> Risk Register</a>
        @endif

        <!-- DoC Capabilities -->
        @if(in_array('doc', (array)($context['role'] ?? [])))
            <a href="#command" class="chip {{ $activeSubmodule == 'command' ? 'active' : '' }}" onclick="switchComplianceSubmodule('command')"><i class="fa-solid fa-globe"></i> Command Center</a>
        @endif
    </div>

    <div class="compliance-content-area" id="compliance-content-area">
        <!-- Content will be loaded here based on active submodule -->
        <div class="embedded-submodule-placeholder">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Loading {{ ucfirst($activeSubmodule ?? 'dashboard') }} module...</p>
        </div>
    </div>
</div>

<style>
    .compliance-module-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        padding: 20px;
        min-height: 600px;
    }
    .compliance-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border, #e5e7eb);
    }
    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .header-left h2 {
        margin: 0;
        color: var(--accent, #02367B);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .role-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
        text-transform: uppercase;
    }
    .role-staff { background: #e0f2fe; color: #0284c7; }
    .role-hr { background: #fdf4ff; color: #c026d3; }
    .role-compliance { background: #fffbeb; color: #d97706; }
    .role-doc { background: #fef2f2; color: #dc2626; }
    
    .compliance-nav-chips {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    .chip {
        padding: 8px 16px;
        border-radius: 20px;
        background: var(--surface2, #f3f4f6);
        color: var(--text, #333);
        text-decoration: none;
        font-size: 0.9em;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .chip:hover, .chip.active {
        background: var(--accent, #02367B);
        color: white;
    }
    
    .compliance-content-area {
        padding: 20px;
        background: var(--surface, #f9fafb);
        border-radius: 8px;
        border: 1px solid var(--border, #e5e7eb);
        min-height: 400px;
    }
    
    .embedded-submodule-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--text-muted, #6b7280);
        margin-top: 50px;
    }
</style>

<script>
    function switchComplianceSubmodule(moduleName) {
        // Update active chip
        document.querySelectorAll('.compliance-nav-chips .chip').forEach(c => c.classList.remove('active'));
        document.querySelector(`.chip[href="#${moduleName}"]`)?.classList.add('active');
        
        const contentArea = document.getElementById('compliance-content-area');
        contentArea.innerHTML = `<div class="embedded-submodule-placeholder">Loading ${moduleName}...</div>`;
        
        // In a real app, this would fetch the blade view or component via AJAX
        // For the mock, we simulate loading delay then inject the iframe/content
        setTimeout(() => {
            // For the test harness, we'll embed the standalone module view in an iframe
            // to ensure CSS isolation, or fetch the HTML. We use an iframe for robust simulation.
            contentArea.innerHTML = `<iframe src="/modules/${moduleName}?role={{ $context['role'] ?? 'staff' }}" 
                                     style="width:100%; height:800px; border:none;" 
                                     onload="this.style.height = this.contentWindow.document.documentElement.scrollHeight + 'px';"></iframe>`;
        }, 500);
    }

    // Auto-load initial submodule
    document.addEventListener('DOMContentLoaded', () => {
        const initialModule = '{{ $activeSubmodule ?? 'pdp' }}';
        if(initialModule && initialModule !== 'dashboard') {
            switchComplianceSubmodule(initialModule);
        } else {
            // default to first available
            const firstChip = document.querySelector('.compliance-nav-chips .chip');
            if(firstChip) {
                switchComplianceSubmodule(firstChip.getAttribute('href').replace('#', ''));
            }
        }
    });
</script>
