<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - OJTMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{
            --ojtms-primary:#000156;
            --ojtms-accent:#f4b61b;
            --ojtms-light:#edeef0;
            --ojtms-dark:#0a0a29;
        }
        body { background-color: var(--ojtms-light); font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-ojtms{ background-color:var(--ojtms-primary); box-shadow: 0 2px 10px rgba(0,1,86,0.3); }
        .navbar-ojtms .navbar-brand{ color:var(--ojtms-accent); font-weight:bold; font-size:1.5rem; }
        .navbar-ojtms .nav-link{ color:rgba(255,255,255,.85); font-weight:500; }
        .navbar-ojtms .nav-link:hover{ color:var(--ojtms-accent); }

        .auth-wrapper {
            display: flex; align-items: center; justify-content: center;
            min-height: calc(100vh - 70px); padding: 30px 15px;
        }
        .auth-container {
            background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,1,86,.3);
            padding: 40px; max-width: 440px; width: 100%; border-top: 5px solid var(--ojtms-accent);
        }
        .auth-header { text-align: center; margin-bottom: 25px; }
        .auth-header i { color: var(--ojtms-accent); font-size: 2rem; }
        .auth-header h1 { color: var(--ojtms-primary); font-weight: 700; font-size: 1.6rem; margin: 10px 0 8px; }
        .auth-header p { color: #6d6c68; font-size: 14px; margin: 0; }

        .form-label { color: var(--ojtms-primary); font-weight: 600; }
        .form-control { border: 2px solid var(--ojtms-light); border-radius: 5px; }
        .form-control:focus { border-color: var(--ojtms-accent); box-shadow: 0 0 0 0.2rem rgba(244,182,27,.15); }

        .btn-login {
            background: linear-gradient(135deg, var(--ojtms-primary) 0%, var(--ojtms-dark) 100%);
            border: none; padding: 10px; font-weight: 600; color: var(--ojtms-accent); transition: all .3s;
        }
        .btn-login:hover { color: var(--ojtms-accent); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,1,86,.2); }

        .auth-footer { text-align: center; margin-top: 20px; font-size: 14px; }
        .auth-footer a { color: var(--ojtms-primary); text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { color: var(--ojtms-accent); text-decoration: underline; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-ojtms px-4">
        <a class="navbar-brand" href="/"><i class="fas fa-graduation-cap me-2"></i>OJTMS</a>
        <div class="ms-auto">
            <a class="nav-link d-inline" href="/login">Back to Login</a>
        </div>
    </nav>

    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-header">
                <i class="fas fa-lock-open"></i>
                <h1>Choose a New Password</h1>
                <p>Resetting the password for <strong>{{ $email }}</strong>.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <div><i class="fas fa-circle-exclamation me-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/reset-password">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="At least 6 characters" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation" placeholder="Repeat the new password" required>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    <i class="fas fa-key me-1"></i> Reset Password
                </button>
            </form>

            <div class="auth-footer">
                <p class="mb-0"><a href="/login">Back to login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
