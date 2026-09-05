<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Resources Management Gateway | CCCRN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 15% 15%, #451a03 0%, #291002 60%, #150801 100%); margin: 0; padding: 24px 16px; font-family: 'Inter', sans-serif;">

<div style="width: 460px; max-width: 100%; background: #ffffff; border-radius: 16px; box-shadow: 0 25px 60px rgba(0,0,0,0.4); overflow: hidden;">
    <div style="background: linear-gradient(135deg, #b45309 0%, #d97706 100%); padding: 28px 24px; text-align: center; color: #ffffff;">
        <span style="background: rgba(255,255,255,0.2); border: 1px solid #fde68a; color: #fef3c7; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">
            <i class="fa-solid fa-users-gear"></i> HR Management Gate
        </span>
        <img src="/assets/images/logo.png" alt="CCCRN Logo" style="height: 40px; filter: brightness(0) invert(1); margin: 12px auto 8px; display: block;">
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 19px; font-weight: 800;">Human Resources Gateway</div>
        <div style="font-size: 11px; opacity: 0.85; margin-top: 2px;">Institutional PDP Roster, Performance Ledger & Training Records</div>
    </div>

    <div style="padding: 28px 24px;">
        <div style="background: #fffbeb; border-left: 3px solid #d97706; padding: 10px 12px; border-radius: 4px; font-size: 11px; color: #92400e; margin-bottom: 18px;">
            <i class="fa-solid fa-shield"></i> <strong>HR Access:</strong> Enter HR official credentials and confidential Staff Roster Key to open the Master Performance Ledger.
        </div>

        <form action="/hr/login" method="POST" onsubmit="cacheHrEmail(event)">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">HR Manager Email *</label>
                <input type="email" id="hrEmailInput" name="email" required value="hr@cccrn.org" placeholder="hr@cccrn.org" style="width: 100%; height: 38px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; outline: none; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">Password *</label>
                <input type="password" name="password" required value="HR@CCCRN2026" placeholder="••••••••••••" style="width: 100%; height: 38px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; outline: none; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">HR Confidential Security Key *</label>
                <input type="password" name="security_key" required value="HR-7742" placeholder="Enter HR Key (HR-7742)" style="width: 100%; height: 38px; padding: 0 12px; border: 1px solid #d97706; border-radius: 6px; font-size: 12px; outline: none; box-sizing: border-box; background: #fffbeb; color: #92400e; font-weight: 700; letter-spacing: 1.5px;">
            </div>

            <button type="submit" class="btn" style="width: 100%; height: 42px; font-size: 13px; font-weight: 700; background: #d97706; color: #ffffff; border-radius: 6px;">
                <i class="fa-solid fa-lock-open"></i> Authenticate & Enter PDP Tracker Hub
            </button>
        </form>

        <div style="margin-top: 18px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 14px;">
            <a href="/login" style="font-size: 11px; color: #64748b; text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Return to Staff Portal
            </a>
        </div>
    </div>
</div>


<script>
function cacheHrEmail() {
    var email = (document.getElementById('hrEmailInput').value || '').trim().toLowerCase();
    if (email) {
        localStorage.setItem('complianceiq_hr_email', email);
        localStorage.setItem('cached_officer_email', email);
        localStorage.setItem('cached_officer_role', 'hr');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var cached = localStorage.getItem('complianceiq_hr_email');
    if (cached && document.getElementById('hrEmailInput')) {
        document.getElementById('hrEmailInput').value = cached;
    }
});
</script>
</body>
</html>
