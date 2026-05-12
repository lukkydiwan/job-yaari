<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – JobYaari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 60%, #2196F3 100%);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1rem;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-box {
            width: 56px; height: 56px;
            background: #1565C0;
            border-radius: 14px;
            display: grid; place-items: center;
            margin: 0 auto 0.75rem;
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
        }
        .login-logo h1 { font-family: 'Sora', sans-serif; font-size: 1.4rem; font-weight: 700; color: #1e293b; }
        .login-logo p { color: #94a3b8; font-size: 0.85rem; margin-top: 0.25rem; }

        .form-group { margin-bottom: 1.1rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.4rem; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.875rem; }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            padding: 0.65rem 0.9rem 0.65rem 2.5rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: #1565C0; }
        .error-msg { color: #e24b4a; font-size: 0.78rem; margin-top: 0.3rem; }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: #1565C0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.925rem;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        .btn-login:hover { background: #1976D2; }

        .login-hint {
            margin-top: 1.5rem;
            padding: 0.9rem;
            background: #e3f2fd;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #1565c0;
        }
        .login-hint strong { font-weight: 600; }
        .login-hint p { margin-bottom: 0.25rem; }

        .back-link { display: block; text-align: center; margin-top: 1.25rem; font-size: 0.8rem; color: #94a3b8; }
        .back-link a { color: #1565c0; text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <div class="logo-box">JY</div>
        <h1>JobYaari Admin</h1>
        <p>Sign in to manage your blogs</p>
    </div>

    @if($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1rem; border: 1px solid #fca5a5;">
            <i class="fas fa-times-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@jobyaari.com" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Sign In
        </button>
    </form>

    <div class="login-hint">
        <p><strong><i class="fas fa-info-circle"></i> Default Credentials</strong></p>
        <p>Email: <strong>admin@jobyaari.com</strong></p>
        <p>Password: <strong>password</strong></p>
        <p style="margin-top: 0.35rem; color: #e24b4a; font-size: 0.75rem;">⚠ Change these after first login</p>
    </div>

    <span class="back-link"><a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Back to Site</a></span>
</div>
</body>
</html>
