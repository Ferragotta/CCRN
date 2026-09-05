<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compliance Specialist Gateway | CCCRN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 15% 15%, #064e3b 0%, #022c22 60%, #01140f 100%); margin: 0; padding: 24px 16px; font-family: 'Inter', sans-serif;">

<div style="width: 460px; max-width: 100%; background: #ffffff; border-radius: 16px; box-shadow: 0 25px 60px rgba(0,0,0,0.4); overflow: hidden;">
    <div style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); padding: 28px 24px; text-align: center; color: #ffffff;">
        <span style="background: rgba(255,255,255,0.2); border: 1px solid #6ee7b7; color: #a7f3d0; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">
            <i class="fa-solid fa-user-shield"></i> Officer Gateway
        </span>
        <img src="/assets/images/logo.png" alt="CCCRN Logo" style="height: 40px; filter: brightness(0) invert(1); margin: 12px auto 8px; display: block;">
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 19px; font-weight: 800;">Compliance Specialist Portal</div>
        <div style="font-size: 11px; opacity: 0.85; margin-top: 2px;">Triage, CAP Conversion & Risk Monitoring Gate</div>
    </div>

    <div style="padding: 28px 24px;">
        <div style="background: #ecfdf5; border-left: 3px solid #059669; padding: 10px 12px; border-radius: 4px; font-size: 11px; color: #065f46; margin-bottom: 18px;">
            <i class="fa-solid fa-key"></i> <strong>Officer Verification:</strong> Enter your designated Compliance Specialist email, password, and confidential Officer Security Key.
        </div>

        <form action="/compliance/login" method="POST">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">Specialist Email *</label>
                <input type="email" name="email" required value="compliance@cccrn.org" placeholder="compliance@cccrn.org" style="width: 100%; height: 38px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; outline: none; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">Password *</label>
                <input type="password" name="password" required value="Compliance@2026" placeholder="••••••••••••" style="width: 100%; height: 38px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; outline: none; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">Officer Security Key / Passcode *</label>
                <input type="password" name="security_key" required value="SPEC-8821" placeholder="Enter Specialist Key (SPEC-8821)" style="width: 100%; height: 38px; padding: 0 12px; border: 1px solid #059669; border-radius: 6px; font-size: 12px; outline: none; box-sizing: border-box; background: #f0fdf4; color: #065f46; font-weight: 700; letter-spacing: 1.5px;">
            </div>

            <button type="submit" class="btn" style="width: 100%; height: 42px; font-size: 13px; font-weight: 700; background: #059669; color: #ffffff; border-radius: 6px;">
                <i class="fa-solid fa-lock-open"></i> Authenticate & Enter Complaints Hub
            </button>
        </form>

        <div style="margin-top: 18px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 14px;">
            <a href="/login" style="font-size: 11px; color: #64748b; text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Return to Staff Portal
            </a>
        </div>
    </div>
</div>

</body>
</html>
