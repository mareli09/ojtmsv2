<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student') - OJTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --ojtms-primary: #000156;
            --ojtms-accent:  #f4b61b;
            --ojtms-light:   #edeef0;
            --ojtms-dark:    #0a0a29;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--ojtms-light);
        }

        /* NAVBAR */
        .navbar-student {
            background-color: var(--ojtms-primary);
            box-shadow: 0 2px 10px rgba(0,1,86,0.2);
            padding: 15px 25px;
        }
        .navbar-student .navbar-brand {
            color: var(--ojtms-accent);
            font-weight: bold;
            font-size: 1.5rem;
        }

        /* LAYOUT */
        .student-wrapper {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,1,86,0.1);
            overflow-y: auto;
            flex-shrink: 0;
        }

        .sidebar .nav-link {
            color: var(--ojtms-primary);
            padding: 12px 20px;
            margin-bottom: 5px;
            border-left: 4px solid transparent;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .sidebar .nav-link:hover {
            background-color: var(--ojtms-light);
            border-left-color: var(--ojtms-accent);
            color: var(--ojtms-primary);
        }
        .sidebar .nav-link.active {
            background-color: var(--ojtms-light);
            border-left-color: var(--ojtms-accent);
            color: var(--ojtms-primary);
            font-weight: 600;
        }
        .sidebar .nav-divider {
            border-top: 1px solid var(--ojtms-light);
            margin: 8px 15px;
        }
        .sidebar .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #aaa;
            letter-spacing: 1px;
            padding: 8px 20px 4px;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* CARDS */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,1,86,0.08);
            transition: all 0.3s;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,1,86,0.12);
        }
        .card-header {
            background-color: var(--ojtms-light);
            border-bottom: 2px solid var(--ojtms-accent);
            color: var(--ojtms-primary);
            font-weight: 600;
            padding: 15px 20px;
        }

        /* BUTTONS */
        .btn-primary {
            background-color: var(--ojtms-primary);
            border: none;
            color: var(--ojtms-accent);
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: var(--ojtms-dark);
            color: var(--ojtms-accent);
        }
        .btn-secondary {
            background-color: var(--ojtms-light);
            border: 2px solid var(--ojtms-primary);
            color: var(--ojtms-primary);
            font-weight: 600;
        }
        .btn-secondary:hover {
            background-color: var(--ojtms-primary);
            color: var(--ojtms-accent);
        }

        h1, h2 { color: var(--ojtms-primary); }
        h2 { font-weight: 700; margin-bottom: 25px; }
        h2 i { color: var(--ojtms-accent); margin-right: 10px; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--ojtms-light); }
        ::-webkit-scrollbar-thumb { background: var(--ojtms-accent); border-radius: 4px; }

        @media (max-width: 768px) {
            .student-wrapper { flex-direction: column; }
            .sidebar {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                padding: 10px;
            }
            .sidebar .nav-link {
                flex: 1;
                min-width: 130px;
                margin: 3px;
                padding: 8px 10px;
                text-align: center;
                border-left: none;
                border-bottom: 3px solid transparent;
                font-size: 13px;
            }
            .sidebar .nav-link:hover,
            .sidebar .nav-link.active {
                border-left: none;
                border-bottom-color: var(--ojtms-accent);
            }
            .sidebar .nav-divider,
            .sidebar .nav-section-label { display: none; }
            .main-content { padding: 15px; }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-student">
        <a class="navbar-brand" href="/student/dashboard">
            <i class="fas fa-graduation-cap me-2"></i>OJTMS Student
        </a>
    </nav>

    @php
        $navUser = session('user');
        $unreadCount = 0;
        if ($navUser) {
            $readIds = \App\Models\AnnouncementRead::where('user_id', $navUser->id)->pluck('announcement_id');
            $unreadCount = \App\Models\Announcement::active()->whereNotIn('id', $readIds)->count();
        }
    @endphp

    <div class="student-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="nav-section-label">Main</div>
            <a href="/student/dashboard"     class="nav-link {{ request()->is('student/dashboard')    ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/student/checklist"     class="nav-link {{ request()->is('student/checklist*')   ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Checklist
            </a>

            <div class="nav-divider"></div>
            <div class="nav-section-label">Communication</div>
            <a href="/student/announcements" class="nav-link {{ request()->is('student/announcements*') ? 'active' : '' }}" style="position:relative;">
                <i class="fas fa-bullhorn"></i> Announcements
                @if($unreadCount > 0)
                <span class="badge bg-danger ms-auto" style="font-size:10px; padding:3px 6px; border-radius:10px;">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="/student/incident-report" class="nav-link {{ request()->is('student/incident-report*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i> Incident Report
            </a>

            <div class="nav-divider"></div>
            <div class="nav-section-label">Support</div>
            <a href="/student/faq"           class="nav-link {{ request()->is('student/faq*')         ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i> FAQ
            </a>
            <a href="/student/chatbot"       class="nav-link {{ request()->is('student/chatbot*')     ? 'active' : '' }}">
                <i class="fas fa-robot"></i> Chatbot AI
            </a>

            <div class="nav-divider"></div>
            <div class="nav-section-label">Account</div>
            <a href="/student/profile"       class="nav-link {{ request()->is('student/profile*')     ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> Profile Settings
            </a>
            <a href="/logout"                class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
