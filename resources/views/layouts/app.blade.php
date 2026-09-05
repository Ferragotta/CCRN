<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CCCRN ComplianceIQ — Compliance Management System' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Pro -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Master CSS Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

    <style>
        :root {
            --attendiq-navy: #02367B;
            --attendiq-deep: #006CA5;
            --attendiq-teal: #0496C7;
            --attendiq-aqua: #55E2E9;
            --bg: #f8fafc;
            --surface: #ffffff;
            --surface2: #f1f5f9;
            --border: #e2e8f0;
            --accent: #02367B;
            --accent-hover: #006CA5;
            --accent-light: rgba(2, 54, 123, 0.08);
            --accent2: #7c3aed;
            --text: #0f172a;
            --text-muted: #64748b;
            --text-dim: #334155;
            --danger: #dc2626;
            --warning: #d97706;
            --success: #059669;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .app { display: flex; min-height: 100vh; width: 100%; }

        /* Generous Sidebar & Spacing */
        .sidebar {
            width: 270px;
            background: #ffffff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.03);
        }

        .main {
            margin-left: 270px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--bg);
            overflow-x: hidden;
            width: calc(100% - 270px);
            box-sizing: border-box;
        }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 0 44px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .content {
            padding: 32px 40px 60px;
            flex: 1;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        /* Modern Card & Table styles */
        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            margin-bottom: 18px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--surface2);
        }

        .card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text);
        }

        .table-responsive { width: 100%; overflow-x: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; table-layout: fixed; }
        th {
            text-align: left;
            padding: 10px 14px;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            background: var(--surface2);
            font-weight: 700;
        }
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-dim);
            vertical-align: middle;
        }
        tr:hover td { background: rgba(2, 54, 123, 0.02); }

        /* Buttons & Pills */
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            font-family: 'Inter', sans-serif;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-primary { background: var(--accent); color: #ffffff; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-outline { background: transparent; color: var(--accent); border-color: var(--border); }
        .btn-outline:hover { border-color: var(--accent); background: var(--accent-light); }
        .btn-sm { padding: 4px 10px; font-size: 11px; }

        .pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 12px; font-size: 10px; font-weight: 700; }
        .pill-open { background: #fee2e2; color: #dc2626; }
        .pill-progress { background: #fef3c7; color: #b45309; }
        .pill-closed { background: #dcfce7; color: #166534; }
        .pill-critical { background: #fee2e2; color: #991b1b; }

        .tab {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-dim);
            transition: all 0.15s ease;
        }
        .tab.active {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-dialog {
            background: #ffffff;
            border-radius: 12px;
            width: 560px;
            max-width: 92%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: var(--text); }
        .modal-close { background: none; border: none; font-size: 16px; cursor: pointer; color: var(--text-muted); }
        .modal-body { padding: 20px; max-height: 75vh; overflow-y: auto; }
        .modal-footer { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px; background: var(--surface2); }
    </style>
</head>
<body>
    <div class="app">
        <!-- SIDEBAR -->
        @include('partials.sidebar')

        <!-- MAIN VIEW AREA -->
        <div class="main">
            <!-- TOPBAR -->
            @include('partials.topbar')

            <!-- CONTENT BODY -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.add('active');
        }
        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.remove('active');
        }
    </script>
</body>
</html>
