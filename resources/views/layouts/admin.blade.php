<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - OJTMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    
    <style>
        /* BRAND COLORS */
        :root{
            --ojtms-primary:#000156;
            --ojtms-accent:#f4b61b;
            --ojtms-light:#edeef0;
            --ojtms-dark:#0a0a29;
        }

        /* BODY & LAYOUT */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--ojtms-light);
        }

        /* NAVBAR */
        .navbar-admin {
            background-color: var(--ojtms-primary);
            box-shadow: 0 2px 10px rgba(0,1,86,0.2);
            padding: 15px 25px;
        }

        .navbar-admin .navbar-brand {
            color: var(--ojtms-accent);
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-admin .navbar-brand i {
            margin-right: 8px;
        }

        .navbar-admin .nav-link {
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            transition: all 0.3s;
            margin-left: 10px;
        }

        .navbar-admin .nav-link:hover {
            color: var(--ojtms-accent);
        }

        .navbar-admin .logout-btn {
            background-color: var(--ojtms-accent);
            color: var(--ojtms-primary);
            font-weight: 600;
            padding: 6px 15px;
            border-radius: 5px;
        }

        .navbar-admin .logout-btn:hover {
            background-color: #e0a910;
            color: var(--ojtms-primary);
        }

        /* ADMIN CONTAINER */
        .admin-wrapper {
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
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,1,86,0.12);
        }

        .card-header {
            background-color: var(--ojtms-light);
            border-bottom: 2px solid var(--ojtms-accent);
            color: var(--ojtms-primary);
            font-weight: 600;
            padding: 15px 20px;
        }

        /* STAT CARDS */
        .stat-card {
            text-align: center;
            padding: 25px 20px;
        }

        .stat-card h5 {
            color: var(--ojtms-primary);
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .stat-card h3 {
            font-weight: bold;
            font-size: 2.5rem;
        }

        .stat-card.stat-users h3 {
            color: var(--ojtms-primary);
        }

        .stat-card.stat-admins h3 {
            color: #667eea;
        }

        .stat-card.stat-faculty h3 {
            color: var(--ojtms-accent);
        }

        .stat-card.stat-students h3 {
            color: #27ae60;
        }

        /* BUTTONS */
        .btn-primary {
            background-color: var(--ojtms-primary);
            border: none;
            color: var(--ojtms-accent);
            font-weight: 600;
            transition: all 0.3s;
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

        /* HEADING */
        h1, h2 {
            color: var(--ojtms-primary);
        }

        h2 {
            font-weight: 700;
            margin-bottom: 25px;
        }

        h2 i {
            color: var(--ojtms-accent);
            margin-right: 10px;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--ojtms-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--ojtms-accent);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--ojtms-primary);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .admin-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                padding: 10px;
            }

            .sidebar .nav-link {
                flex: 1;
                min-width: 150px;
                margin: 5px;
                padding: 10px;
                text-align: center;
                border-left: none;
                border-bottom: 3px solid transparent;
            }

            .sidebar .nav-link:hover,
            .sidebar .nav-link.active {
                border-left: none;
                border-bottom-color: var(--ojtms-accent);
            }

            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin">
        <a class="navbar-brand" href="/admin/dashboard">
            <i class="fas fa-chart-line"></i>OJTMS Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav">
                <a class="nav-link" href="/">
                    <i class="fas fa-home"></i> Back to Site
                </a>
                <a class="nav-link logout-btn" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- ===== ADMIN WRAPPER ===== -->
    <div class="admin-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar">
            @yield('sidebar')
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    @stack('scripts')
</body>
</html>
