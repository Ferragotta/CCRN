<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | ComplianceIQ™ — CCCRN</title>
    
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            min-height: 100vh;
            width: 100%;
            margin: 0;
            padding: 0;
            background: radial-gradient(ellipse at 50% -20%, #0b2f64 0%, #031633 45%, #010a1a 100%);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #ffffff;
            overflow-x: hidden;
        }

        .login-page-container {
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 24px 20px;
            position: relative;
            z-index: 2;
            box-sizing: border-box;
        }

        /* Ambient Glowing Background Orbs */
        .orb-top-left {
            position: fixed;
            top: -15%;
            left: -10%;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(85, 226, 233, 0.18) 0%, rgba(2, 54, 123, 0.08) 50%, transparent 70%);
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }

        .orb-bottom-right {
            position: fixed;
            bottom: -20%;
            right: -10%;
            width: 750px;
            height: 750px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 119, 182, 0.22) 0%, rgba(2, 43, 97, 0.15) 50%, transparent 70%);
            filter: blur(110px);
            pointer-events: none;
            z-index: 0;
        }

        /* SVG Grid Canvas */
        .svg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.28;
            z-index: 0;
        }

        /* Top Header Bar */
        .top-header-bar {
            width: 100%;
            max-width: 1100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            z-index: 2;
        }

        .gateway-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(2, 34, 77, 0.65);
            border: 1px solid rgba(85, 226, 233, 0.28);
            padding: 6px 16px;
            border-radius: 24px;
            backdrop-filter: blur(10px);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 10px #10b981;
            display: inline-block;
        }

        .attendify-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(85, 226, 233, 0.3);
            padding: 6px 16px;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            color: #55E2E9;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        /* The Centered Form Card */
        .login-card-wrapper {
            width: 100%;
            max-width: 440px;
            margin: auto 0;
            z-index: 2;
        }

        .login-card { 
            width: 100%; 
            border: 1px solid rgba(255, 255, 255, 0.25); 
            border-radius: 18px; 
            background: #ffffff;
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.6), 0 0 40px rgba(0, 119, 182, 0.2);
            color: #1e293b;
            overflow: hidden;
        }

        .btn-primary { 
            background: #02367B; 
            border: none; 
            padding: 11px; 
            font-weight: 700; 
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-primary:hover, .btn-primary:focus { 
            background: #012454;
            transform: translateY(-1px);
        }

        .form-control, .form-select {
            padding: 9px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #02367B;
            box-shadow: 0 0 0 0.2rem rgba(2, 54, 123, 0.15);
        }

        /* Auth Nav Switcher Tabs */
        .auth-tab-nav {
            display: flex;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 20px;
        }
        .auth-tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.18s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .auth-tab-btn.active {
            background: #ffffff;
            color: #02367B;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Footer text */
        .page-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 11.5px;
            color: rgba(224, 242, 254, 0.6);
            z-index: 2;
        }
    </style>
</head>
<body>

    <!-- 1. Ambient Background Glowing Orbs -->
    <div class="orb-top-left"></div>
    <div class="orb-bottom-right"></div>

    <!-- 2. Architectural SVG Grid Canvas -->
    <svg class="svg-canvas">
        <defs>
            <pattern id="bladeGridPattern" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(186, 230, 253, 0.16)" stroke-width="0.8" />
                <circle cx="40" cy="0" r="1" fill="rgba(85, 226, 233, 0.4)" />
                <circle cx="0" cy="40" r="1" fill="rgba(85, 226, 233, 0.4)" />
            </pattern>
            <linearGradient id="bladeWaveGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#55E2E9" stop-opacity="0.0" />
                <stop offset="50%" stop-color="#55E2E9" stop-opacity="0.35" />
                <stop offset="100%" stop-color="#0077b6" stop-opacity="0.0" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#bladeGridPattern)" />
        <path d="M -100 250 C 300 120, 700 380, 1200 180 S 1800 320, 2200 160" fill="none" stroke="url(#bladeWaveGrad)" stroke-width="1.5" stroke-dasharray="4 6" />
    </svg>

    <!-- Main Container Flow -->
    <div class="login-page-container">
        <!-- Top Indicator Header -->
        <div class="top-header-bar">
            <div class="gateway-badge">
                <span class="status-dot"></span>
                <span style="font-size: 11px; font-weight: 700; color: #e0f2fe; letter-spacing: 0.5px;">
                    CCCRN SECURE ENTERPRISE GATEWAY &middot; FY2026
                </span>
            </div>
            <div class="attendify-pill">
                <i class="bi bi-shield-check me-1"></i> ComplianceIQ™
            </div>
        </div>

        <!-- The Centered Form Card -->
        <div class="login-card-wrapper">
            <div class="card login-card">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <img src="/assets/images/logo.png" alt="CCCRN Logo" style="width: 72px; margin-bottom: 8px;">
                        <h4 class="fw-bold mb-0" style="color: #02367B;" id="authCardHeading">Executive & Staff Access</h4>
                        <p class="text-muted small mb-0" style="font-size: 11.5px;">CCCRN ComplianceIQ™ &middot; Enterprise Portal</p>
                    </div>

                    <!-- Dual Tab Switcher: Sign In vs Register -->
                    <div class="auth-tab-nav">
                        <button type="button" class="auth-tab-btn active" id="tabBtnSignIn" onclick="switchAuthTab('signin')">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </button>
                        <button type="button" class="auth-tab-btn" id="tabBtnRegister" onclick="switchAuthTab('register')">
                            <i class="bi bi-person-plus-fill"></i> Register
                        </button>
                    </div>

                    <!-- Live Dynamic Notification Notice -->
                    <div id="authEmailSyncNotice" style="display: none; background: #f0fdf4; border-left: 3px solid #16a34a; padding: 8px 12px; border-radius: 6px; font-size: 11px; color: #15803d; margin-bottom: 14px;">
                        <i class="bi bi-envelope-check me-1"></i>
                        <span id="authEmailSyncNoticeText">Email cached: All alerts & notifications will route here.</span>
                    </div>

                    @if(session('error') || isset($error))
                    <div class="alert alert-danger py-2 px-3 small mb-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') ?? $error }}
                    </div>
                    @endif

                    <!-- 1. SIGN IN FORM -->
                    <form id="formSignIn" method="POST" action="/admin/login" onsubmit="handleSignInSubmit(event)">
                        @csrf
                        <input type="hidden" name="login_admin" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary mb-1">Official Email or Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" id="loginUsername" name="username" class="form-control border-start-0 ps-0" placeholder="Enter official email or username" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold text-secondary mb-0">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="loginPassword" class="form-control border-start-0 ps-0" placeholder="Enter password" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 shadow-sm py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to ComplianceIQ™
                        </button>
                    </form>

                    <!-- 2. REGISTRATION FORM (NO DoC / HR BUTTONS) -->
                    <form id="formRegisterOfficer" style="display: none;" onsubmit="handleOfficerRegister(event)">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small fw-bold text-secondary mb-1">Full Name *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" id="regOfficerName" class="form-control border-start-0 ps-0" placeholder="e.g. Dr. Chika Okafor" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold text-secondary mb-1">Official Email Address *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" id="regOfficerEmail" class="form-control border-start-0 ps-0" placeholder="e.g. yourname@cccrn.org" required>
                            </div>
                            <div style="font-size: 10px; color: #0284c7; margin-top: 3px;">
                                <i class="bi bi-lightning-charge-fill me-1"></i> JavaScript will cache this email so all notifications & alerts route directly to it.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold text-secondary mb-1">Official Phone (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                <input type="tel" id="regOfficerPhone" class="form-control border-start-0 ps-0" placeholder="+234 803 000 0000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary mb-1">Password *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" id="regOfficerPassword" class="form-control border-start-0 ps-0" placeholder="Create password" required>
                            </div>
                        </div>

                        <button type="submit" id="btnRegisterSubmit" class="btn btn-primary w-100 shadow-sm py-2" style="background: #02367B;">
                            <i class="bi bi-check-circle-fill me-1"></i> Complete Registration
                        </button>
                    </form>
                </div>
                
                <div class="card-footer bg-light text-center py-2 border-0" style="border-radius: 0 0 18px 18px;">
                    <p class="text-muted mb-0" style="font-size: 11px;">Center for Clinical Care and Clinical Research Nigeria &middot; <b>ComplianceIQ™</b></p>
                </div>
            </div>
        </div>

        <!-- Bottom Page Footer -->
        <div class="page-footer">
            &copy; 2026 CCCRN Nigeria. Authorized Personnel Only &middot; Secure 256-Bit TLS
        </div>
    </div>

    <!-- Caching & Registration JavaScript -->
    <script>
    function switchAuthTab(tab) {
        var tabSignIn = document.getElementById('tabBtnSignIn');
        var tabRegister = document.getElementById('tabBtnRegister');
        var formSignIn = document.getElementById('formSignIn');
        var formRegister = document.getElementById('formRegisterOfficer');
        var heading = document.getElementById('authCardHeading');

        if (tab === 'register') {
            tabSignIn.classList.remove('active');
            tabRegister.classList.add('active');
            formSignIn.style.display = 'none';
            formRegister.style.display = 'block';
            heading.innerText = 'Officer Registration';
        } else {
            tabRegister.classList.remove('active');
            tabSignIn.classList.add('active');
            formRegister.style.display = 'none';
            formSignIn.style.display = 'block';
            heading.innerText = 'Executive & Staff Access';
        }
    }

    // Handle Officer Registration: cache in localStorage & post to preview server
    function handleOfficerRegister(e) {
        e.preventDefault();
        var name = document.getElementById('regOfficerName').value.trim();
        var email = document.getElementById('regOfficerEmail').value.trim().toLowerCase();
        var phone = document.getElementById('regOfficerPhone').value.trim();
        var password = document.getElementById('regOfficerPassword').value;

        if (!email) {
            alert('Please enter a valid official email.');
            return;
        }

        // Infer role from email/context or cache universally
        var role = 'doc';
        if (email.includes('hr') || email.includes('people') || email.includes('talent')) {
            role = 'hr';
            localStorage.setItem('complianceiq_hr_email', email);
            localStorage.setItem('complianceiq_hr_name', name);
        } else {
            role = 'doc';
            localStorage.setItem('complianceiq_doc_email', email);
            localStorage.setItem('complianceiq_doc_name', name);
        }

        // Always cache primary officer email for notifications
        localStorage.setItem('cached_officer_email', email);
        localStorage.setItem('cached_officer_role', role);

        // Transmit registration to backend so notifications and future logins recognize this email
        fetch('/api/auth/register-officer', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                role: role,
                name: name,
                email: email,
                phone: phone,
                password: password
            })
        }).then(function(res) { return res.json(); }).then(function(data) {
            alert('Registration Successful!\n\nEmail Cached: ' + email + '\n\nAll system notifications & alerts are now directed to your email address.');
            window.location.href = '/dashboard';
        }).catch(function(err) {
            alert('Email Cached in browser: ' + email + '\nAll notifications will route to this email.');
            window.location.href = '/dashboard';
        });
    }

    // Handle Sign In: cache email in localStorage if entered
    function handleSignInSubmit(e) {
        var username = (document.getElementById('loginUsername').value || '').trim().toLowerCase();
        if (username) {
            if (username.includes('hr') || username.includes('people')) {
                localStorage.setItem('complianceiq_hr_email', username);
                localStorage.setItem('cached_officer_email', username);
                localStorage.setItem('cached_officer_role', 'hr');
            } else {
                localStorage.setItem('complianceiq_doc_email', username);
                localStorage.setItem('cached_officer_email', username);
                localStorage.setItem('cached_officer_role', 'doc');
            }
        }
    }

    // Auto-detect cached email on load and show friendly banner if present
    document.addEventListener('DOMContentLoaded', function() {
        var cachedEmail = localStorage.getItem('cached_officer_email');
        if (cachedEmail) {
            var notice = document.getElementById('authEmailSyncNotice');
            var noticeText = document.getElementById('authEmailSyncNoticeText');
            if (notice && noticeText) {
                notice.style.display = 'block';
                noticeText.innerText = 'Active Notification Email: ' + cachedEmail;
            }
            var loginInput = document.getElementById('loginUsername');
            if (loginInput && !loginInput.value) {
                loginInput.value = cachedEmail;
            }
        }
    });
    </script>

</body>
</html>
