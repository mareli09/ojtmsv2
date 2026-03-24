<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OJTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* BRAND COLORS */
        :root{
            --ojtms-primary:#000156;
            --ojtms-accent:#f4b61b;
            --ojtms-light:#edeef0;
            --ojtms-dark:#0a0a29;
        }

        /* NAVBAR STYLES */
        .navbar-ojtms{
            background-color:var(--ojtms-primary);
            box-shadow: 0 2px 10px rgba(0,1,86,0.3);
        }

        .navbar-ojtms .nav-link{
            color:rgba(255,255,255,.85);
            font-weight:500;
            transition: all 0.3s;
        }

        .navbar-ojtms .nav-link:hover,
        .navbar-ojtms .nav-link.active{
            color:var(--ojtms-accent);
            text-decoration:underline;
            text-underline-offset:4px;
        }

        .navbar-ojtms .navbar-brand{
            color:var(--ojtms-accent);
            font-weight:bold;
            font-size:1.5rem;
        }

        /* BODY STYLES */
        body {
            background: linear-gradient(135deg, var(--ojtms-primary) 0%, var(--ojtms-dark) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,1,86,.3);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            border-top: 5px solid var(--ojtms-accent);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--ojtms-primary);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-header i {
            color: var(--ojtms-accent);
            font-size: 2rem;
        }

        .login-header p {
            color: var(--ojtms-primary);
            font-size: 14px;
            font-weight: 600;
        }

        .form-label {
            color: var(--ojtms-primary);
            font-weight: 600;
        }

        .form-control {
            border: 2px solid var(--ojtms-light);
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: var(--ojtms-accent);
            box-shadow: 0 0 0 0.2rem rgba(244, 182, 27, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--ojtms-primary) 0%, var(--ojtms-dark) 100%);
            border: none;
            padding: 10px;
            font-weight: 600;
            color: var(--ojtms-accent);
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--ojtms-dark) 0%, var(--ojtms-primary) 100%);
            color: var(--ojtms-accent);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,1,86,0.2);
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .login-footer a {
            color: var(--ojtms-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            color: var(--ojtms-accent);
            text-decoration: underline;
        }

        .alert {
            background-color: var(--ojtms-light);
            border-color: var(--ojtms-primary);
            color: var(--ojtms-primary);
        }

        .test-accounts {
            text-align: center;
            font-size: 12px;
            color: #999;
            background-color: var(--ojtms-light);
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .test-accounts strong {
            color: var(--ojtms-primary);
        }

        .test-accounts p {
            margin: 5px 0;
            color: var(--ojtms-primary);
        }

        hr {
            border-color: var(--ojtms-light);
        }
    </style>
</head>
<body>
    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-ojtms px-4">
        <a class="navbar-brand" href="/">
            <i class="fas fa-graduation-cap me-2"></i>OJTMS
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#announcements">Announcements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/login" style="background:var(--ojtms-accent); color:var(--ojtms-primary); border-radius:5px; padding:5px 15px; font-weight:600;">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <i class="bi bi-briefcase"></i>
                <h1>OJTMS</h1>
                <p>OJT Monitoring System</p>
            </div>

            @if ($errors->any())
                <div class="alert">
                    @foreach ($errors->all() as $error)
                        <small>{{ $error }}</small><br>
                    @endforeach
                </div>
            @endif

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="test-accounts">
                <p><strong>🧪 Test Accounts</strong> <small>(click to fill)</small></p>
                <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 8px;">
                    @php
                        $accounts = isset($testAccounts) && $testAccounts->count() > 0
                            ? $testAccounts->values()
                            : collect([
                                (object)['role' => 'admin',   'username' => 'admin'],
                                (object)['role' => 'faculty',  'username' => 'juandelacru'],
                                (object)['role' => 'student',  'username' => 'johnsmith'],
                            ]);
                        $roleColors = ['admin' => '#6c3483', 'faculty' => '#1a5276', 'student' => '#145a32'];
                    @endphp
                    @foreach($accounts as $account)
                    @php $color = $roleColors[$account->role] ?? '#333'; @endphp
                    <button type="button"
                        onclick="fillCredentials('{{ $account->username }}', 'password')"
                        style="background: white; border: 1px solid #ddd; border-left: 4px solid {{ $color }}; border-radius: 5px; padding: 7px 12px; text-align: left; cursor: pointer; font-size: 13px; transition: background 0.2s;"
                        onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='white'">
                        <span style="color: {{ $color }}; font-weight: 700;">{{ ucfirst($account->role) }}</span>
                        &nbsp;&mdash;&nbsp;
                        <code style="color: #333;">{{ $account->username }}</code>
                        <span style="color: #999;"> / password</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <hr>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-login w-100">Login</button>
            </form>

            <div class="login-footer">
                <p><a href="/recover">Forgot password?</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <script>
        function fillCredentials(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.getElementById('username').focus();
        }
    </script>
</body>
</html>
