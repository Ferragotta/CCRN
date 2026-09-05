<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendify Test Harness</title>
    <!-- We simulate Attendify's base assets -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --attendify-brand: #0d9488; /* Teal brand color for host app */
            --attendify-sidebar: #134e4a;
            --attendify-bg: #f3f4f6;
            --accent: #02367B; /* ComplianceIQ brand */
            --danger: #dc2626;
            --success: #059669;
            --warning: #d97706;
            --text: #1f2937;
            --border: #e5e7eb;
        }
        
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--attendify-bg);
            color: var(--text);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Test Harness Simulation Bar */
        .simulator-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #111827;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            height: 30px;
            font-size: 0.9em;
        }
        .role-switchers button {
            background: #374151;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            margin-left: 5px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .role-switchers button:hover, .role-switchers button.active {
            background: var(--attendify-brand);
        }

        /* Attendify Layout */
        .attendify-layout {
            display: flex;
            width: 100%;
            margin-top: 50px; /* Push down for simulator bar */
            height: calc(100vh - 50px);
        }

        .attendify-sidebar {
            width: 250px;
            background: var(--attendify-sidebar);
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }
        .attendify-brand {
            padding: 0 20px 20px;
            font-size: 1.5em;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .attendify-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .attendify-nav li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: background 0.2s;
        }
        .attendify-nav li a:hover, .attendify-nav li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .attendify-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .attendify-topbar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-avatar {
            width: 35px;
            height: 35px;
            background: var(--attendify-brand);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .attendify-content {
            padding: 30px;
            flex: 1;
        }
        
        /* Module specific overrides */
        .compliance-wrapper {
            /* Styles specific to embedding the compliance module */
        }
    </style>
</head>
<body>

    <!-- SIMULATOR CONTROLS -->
    <div class="simulator-bar">
        <div><strong>Test Harness:</strong> Attendify Host Environment</div>
        <div class="role-switchers">
            <span style="margin-right: 10px;">Simulate Role:</span>
            <button onclick="switchRole('staff')" id="btn-staff">👤 Staff</button>
            <button onclick="switchRole('hr')" id="btn-hr">👔 HR Manager</button>
            <button onclick="switchRole('compliance')" id="btn-compliance">⚖️ Compliance</button>
            <button onclick="switchRole('doc')" id="btn-doc">👑 Super Admin</button>
        </div>
    </div>

    <div class="attendify-layout">
        <!-- SIMULATED ATTENDIFY SIDEBAR -->
        <div class="attendify-sidebar">
            <div class="attendify-brand">
                <i class="fa-solid fa-clock"></i> Attendify
            </div>
            <ul class="attendify-nav">
                <li><a href="#"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                <li><a href="#"><i class="fa-solid fa-calendar-check"></i> Attendance</a></li>
                <li><a href="#"><i class="fa-solid fa-plane-departure"></i> Leave Management</a></li>
                
                <!-- INJECTED COMPLIANCE LAUNCHER CONTAINER -->
                <div id="launcher-container" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                    <!-- Launcher will be injected here -->
                </div>
            </ul>
        </div>

        <div class="attendify-main">
            <!-- SIMULATED ATTENDIFY TOPBAR -->
            <div class="attendify-topbar">
                <div class="user-profile">
                    <div class="user-info" style="text-align: right;">
                        <div id="sim-user-name" style="font-weight: 600;">Fatima Bello</div>
                        <div id="sim-user-dept" style="font-size: 0.8em; color: #6b7280;">Clinical Services</div>
                    </div>
                    <div class="user-avatar" id="sim-user-avatar">FB</div>
                </div>
            </div>

            <!-- MAIN CONTENT AREA -->
            <div class="attendify-content">
                <!-- We fetch and inject the embedded module view here -->
                <div id="module-container">
                    Loading compliance module...
                </div>
            </div>
        </div>
    </div>

    <script>
        const mockUsers = {
            'staff': { name: 'Fatima Bello', dept: 'Clinical Services', initials: 'FB' },
            'hr': { name: 'Biodun Alade', dept: 'Human Resources', initials: 'BA' },
            'compliance': { name: 'Amaka Obi', dept: 'Compliance & Risk', initials: 'AO' },
            'doc': { name: 'Dr. Chika', dept: 'Executive', initials: 'DC' }
        };

        function switchRole(role) {
            // Update UI buttons
            document.querySelectorAll('.role-switchers button').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + role).classList.add('active');
            
            // Update Profile
            const user = mockUsers[role];
            document.getElementById('sim-user-name').innerText = user.name;
            document.getElementById('sim-user-dept').innerText = user.dept;
            document.getElementById('sim-user-avatar').innerText = user.initials;
            
            // Re-fetch the module with the new role
            fetchModule(role);
        }
        
        function fetchModule(role) {
            const container = document.getElementById('module-container');
            const launcherContainer = document.getElementById('launcher-container');
            
            container.innerHTML = 'Loading module context...';
            launcherContainer.innerHTML = 'Loading menu...';
            
            // Fetch Launcher
            fetch('/test-harness/render-launcher?role=' + role)
                .then(res => res.text())
                .then(html => {
                    launcherContainer.innerHTML = html;
                    
                    // We need to recolor the launcher for the dark sidebar
                    const link = launcherContainer.querySelector('.compliance-launcher');
                    if(link) {
                        link.style.color = 'white';
                        link.style.padding = '12px 20px';
                    }
                    const submenuLinks = launcherContainer.querySelectorAll('.compliance-submenu li a');
                    submenuLinks.forEach(a => {
                        a.style.color = 'rgba(255,255,255,0.7)';
                    });
                    
                    // Execute scripts within the injected launcher HTML so the toggle works
                    const scripts = launcherContainer.getElementsByTagName('script');
                    for (let i = 0; i < scripts.length; i++) {
                        // We attach it globally if it's a function declaration
                        eval(scripts[i].innerText);
                    }
                })
                .catch(err => console.error(err));
            
            // In the local test harness, we call a special route that renders the embed
            fetch('/test-harness/render-module?role=' + role)
                .then(res => res.text())
                .then(html => {
                    container.innerHTML = html;
                    // Execute scripts within the injected HTML
                    const scripts = container.getElementsByTagName('script');
                    for (let i = 0; i < scripts.length; i++) {
                        eval(scripts[i].innerText);
                    }
                })
                .catch(err => {
                    container.innerHTML = '<div style="color:red;">Error loading module: ' + err + '</div>';
                });
        }
        
        // Define global function that the launcher calls
        window.hostAppLoadComplianceModule = function(moduleName) {
            // For the test harness, if they click a side menu item, we can just switch the active submodule
            // Since the main module is an iframe (in our simulation), we could update the iframe
            // But let's just use the existing switch function in the embed template
            if (typeof switchComplianceSubmodule === 'function') {
                switchComplianceSubmodule(moduleName);
            } else {
                // If the iframe hasn't loaded or isn't accessible, just reload the container
                // with that module as default
            }
        };

        // Initialize
        switchRole('staff');
    </script>
</body>
</html>
