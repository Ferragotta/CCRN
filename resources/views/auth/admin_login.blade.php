<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | ComplianceIQ™</title>
    
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100vh;
            max-height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body { 
            background: radial-gradient(ellipse at 50% -20%, #0b2f64 0%, #031633 45%, #010a1a 100%);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            position: relative;
        }

        .orb-top-left {
            position: absolute;
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
            position: absolute;
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

        .orb-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 850px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(ellipse, rgba(124, 58, 237, 0.07) 0%, rgba(2, 54, 123, 0.12) 40%, transparent 75%);
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        .svg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.28;
            z-index: 0;
        }

        .top-indicator-bar {
            position: absolute;
            top: 24px;
            left: 32px;
            right: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            pointer-events: none;
            z-index: 1;
        }

        .gateway-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(2, 34, 77, 0.65);
            border: 1px solid rgba(85, 226, 233, 0.28);
            padding: 6px 14px;
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
            display: none;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 6px 14px;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
        }
        @media (min-width: 768px) {
            .attendify-pill { display: flex; }
        }

        .login-card-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 70px -15px rgba(0, 0, 0, 0.65), 0 0 50px rgba(0, 119, 182, 0.22);
            border-radius: 20px;
        }

        .login-card { 
            width: 100%; 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            border-radius: 20px; 
            background: #ffffff;
        }

        .btn-primary { 
            background: #02367B; 
            border: none; 
            padding: 12px; 
            font-weight: 700; 
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-primary:hover, .btn-primary:focus { 
            background: #012454;
            transform: translateY(-1px);
        }

        .form-control {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            border-color: #02367B;
            box-shadow: 0 0 0 0.2rem rgba(2, 54, 123, 0.15);
        }

        .bottom-footer {
            position: absolute;
            bottom: 18px;
            left: 0;
            right: 0;
            text-align: center;
            pointer-events: none;
            z-index: 1;
            color: #64748b;
            font-size: 11px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            padding: 0 20px;
        }
    </style>
</head>
<body>

    <!-- 1. Ambient Background Glowing Orbs -->
    <div class="orb-top-left"></div>
    <div class="orb-bottom-right"></div>
    <div class="orb-center"></div>

    <!-- 2. Architectural SVG Grid Canvas -->
    <svg class="svg-canvas">
        <defs>
            <pattern id="bladeGridPatternAdmin" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(186, 230, 253, 0.16)" stroke-width="0.8" />
                <circle cx="40" cy="0" r="1" fill="rgba(85, 226, 233, 0.4)" />
                <circle cx="0" cy="40" r="1" fill="rgba(85, 226, 233, 0.4)" />
            </pattern>
            <linearGradient id="bladeWaveGradAdmin" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#55E2E9" stop-opacity="0.0" />
                <stop offset="50%" stop-color="#55E2E9" stop-opacity="0.35" />
                <stop offset="100%" stop-color="#0077b6" stop-opacity="0.0" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#bladeGridPatternAdmin)" />
        <path d="M -100 250 C 300 120, 700 380, 1200 180 S 1800 320, 2200 160" fill="none" stroke="url(#bladeWaveGradAdmin)" stroke-width="1.5" stroke-dasharray="4 6" />
    </svg>

    <!-- 3. Top Floating Indicator Bar -->
    <div class="top-indicator-bar">
        <div class="gateway-badge">
            <span class="status-dot"></span>
            <span style="font-size: 11px; font-weight: 700; color: #e0f2fe; letter-spacing: 0.4px;">
                CCCRN SECURE GATEWAY · FY2026 AUDIT CYCLE
            </span>
        </div>
        <div class="attendify-pill" style="border-color: rgba(85, 226, 233, 0.3); color: #55E2E9; font-weight: 800; letter-spacing: 0.5px;">
            <i class="bi bi-shield-check me-1"></i> ComplianceIQ
        </div>
    </div>

    <!-- 4. The Form Card (Center Stage) -->
    <div class="login-card-wrapper">
        <div class="card login-card shadow-lg">
            <div class="card-body p-4 p-sm-4">
                <div class="text-center mb-4">
                    <img src="/assets/images/logo.png" alt="CCCRN Logo" style="width: 70px; margin-bottom: 10px;">
                    <h4 class="fw-bold" style="color: #111827;">Admin Portal</h4>
                    <p class="text-muted small mb-0">ComplianceIQ™ | Attendify Pro™</p>
                </div>

                <div id="loginErrorAlert" class="alert alert-danger py-2 px-3 small d-none" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> <span id="loginErrorMessage">Invalid username/email or password.</span>
                </div>

                <form method="POST" action="/admin/login">
                    <input type="hidden" name="login_admin" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username or Email</label>
                        <input type="text" name="username" class="form-control" placeholder="e.g. director@cccrn.org" required autofocus>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        Unlock Dashboard <i class="bi bi-shield-lock ms-2"></i>
                    </button>
                </form>
            </div>
            
            <div class="card-footer bg-light text-center py-3 border-0" style="border-radius: 0 0 20px 20px;">
                <p class="small text-muted mb-0">Created and published by <b>FIAY</b></p>
            </div>
        </div>
    </div>

</body>
</html>
