<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identify — Staff Compliance Module</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --accent: #02367B;
            --accent-light: rgba(2, 54, 123, 0.08);
            --danger: #dc2626;
            --success: #059669;
            --warning: #d97706;
            --text: #0f172a;
            --text-dim: #334155;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --surface2: #f8fafc;
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: var(--text);
            padding: 16px;
            overflow-x: hidden;
            width: 100%;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 16px;
        }

        .card-header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: #012454;
            border-color: #012454;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-dim);
            border-color: var(--border);
        }

        .btn-outline:hover {
            background: var(--surface2);
            border-color: var(--text-muted);
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 11px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pill-open { background: #fee2e2; color: #991b1b; }
        .pill-progress { background: #fef3c7; color: #92400e; }
        .pill-closed { background: #dcfce7; color: #166534; }

        .tab {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
            font-weight: 700;
        }

        .modal-overlay {
            display: none !important;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 10000;
            backdrop-filter: blur(2px);
        }

        .modal-overlay.active {
            display: flex !important;
        }

        .modal-dialog {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            overflow: hidden;
            border: 1px solid var(--border);
            padding: 20px;
        }

        .modal-header {
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 20px;
            color: var(--text-muted);
            cursor: pointer;
            line-height: 1;
        }

        /* Zero Horizontal Scroll */
        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
    </style>
</head>
<body>

    <!-- Inject the full standalone staff compliance component -->
    @include('modules.staff')

    <script>
        function openModal(id) {
    var el = document.getElementById(id);
    if (el) {
        el.classList.add('active');
        el.style.display = 'flex';
    }
}

function closeModal(id) {
    var el = document.getElementById(id);
    if (el) {
        el.classList.remove('active');
        el.style.display = 'none';
    }
}

        // Host Application Communication (Identify / Attendify message listener)
        window.addEventListener('message', function(event) {
            if (event.data && event.data.action === 'SET_USER_CONTEXT') {
                var ctx = event.data.payload;
                if (ctx) {
                    window.ATTENDIFY_STAFF_CONTEXT = ctx;
                    if (window.initStaffModule) window.initStaffModule();
                }
            } else if (event.data && event.data.action === 'SWITCH_TAB') {
                var tabKey = event.data.payload;
                if (typeof switchStaffMainTab === 'function') {
                    var tabBtn = document.querySelector('.staff-main-tab[onclick*="' + tabKey + '"]');
                    switchStaffMainTab(tabKey, tabBtn);
                }
            }
        });
    </script>
</body>
</html>
