<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - AngananSoft</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Oswald:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: #0f172a;
            color: #ffffff;
            padding: 32px 30px;
            text-align: center;
            border-bottom: 3px solid #26c6da;
        }

        .brand-logo {
            font-family: 'Oswald', sans-serif;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .brand-logo span {
            color: #26c6da;
        }

        .brand-subtitle {
            font-size: 13px;
            color: #94a3b8;
        }

        .login-body {
            padding: 32px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            font-family: inherit;
            transition: all 0.2s;
        }

        .input-group input:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 13px;
            color: #64748b;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #ffffff;
            border: none;
            padding: 13px;
            border-radius: 6px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .credentials-box {
            background: #f0fdf4;
            border: 1px dashed #86efac;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 22px;
            font-size: 12.5px;
            color: #166534;
        }

        .credentials-box strong {
            display: block;
            margin-bottom: 4px;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .back-link a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #0284c7;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <div class="brand-logo">Anganan<span>Soft</span></div>
            <p class="brand-subtitle">Panel Masuk Admin & Dashboard Posting</p>
        </div>

        <div class="login-body">
            @if($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                </div>
            @endif

            @if(app()->isLocal())
            <div class="credentials-box">
                <strong><i class="fa-solid fa-key"></i> Akun Login Default (Mode Lokal):</strong>
                <div>Email: <code>admin@anganansoft.com</code></div>
                <div>Password: <code>frans2341</code></div>
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <div class="input-group">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', app()->isLocal() ? 'admin@anganansoft.com' : '') }}" placeholder="admin@anganansoft.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <div class="remember-row">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="checkbox" name="remember" checked> Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Dashboard
                </button>
            </form>

            <div class="back-link">
                <a href="{{ route('blog.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman Utama Website</a>
            </div>
        </div>
    </div>

</body>
</html>
