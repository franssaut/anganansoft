<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan Server | AngananSoft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #26c6da;
            --primary-dark: #00acc1;
            --dark: #222222;
            --bg: #f5f6f8;
            --border: #e5e7eb;
            --text: #374151;
            --text-heading: #111827;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Open Sans', system-ui, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background: #fff;
            border-bottom: 2px solid var(--primary);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo {
            font-family: 'Oswald', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
            text-transform: uppercase;
        }
        .logo span { color: var(--primary); }
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }
        .error-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 50px 30px;
            text-align: center;
            max-width: 560px;
            width: 100%;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        .error-code {
            font-size: 80px;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            color: #ef4444;
            line-height: 1;
            margin-bottom: 12px;
        }
        .error-title {
            font-size: 22px;
            font-family: 'Oswald', sans-serif;
            color: var(--text-heading);
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .error-desc {
            color: #6b7280;
            font-size: 14.5px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            padding: 10px 26px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-home:hover {
            background: var(--primary-dark);
        }
        .footer {
            background: var(--dark);
            color: #9ca3af;
            text-align: center;
            padding: 14px 20px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">Anganan<span>Soft</span></a>
    </header>

    <main class="main-container">
        <div class="error-card">
            <div class="error-code">500</div>
            <h1 class="error-title">Terjadi Kesalahan Server</h1>
            <p class="error-desc">
                Maaf, server kami sedang mengalami kendala sementara saat memproses permintaan Anda atau sedang melakukan penyesuaian database. Silakan muat ulang halaman dalam beberapa saat.
            </p>
            <a href="/" class="btn-home">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
        </div>
    </main>

    <footer class="footer">
        &copy; {{ date('Y') }} AngananSoft. All Rights Reserved.
    </footer>
</body>
</html>
