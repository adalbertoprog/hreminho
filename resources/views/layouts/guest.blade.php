<!DOCTYPE html>
<html lang="pt" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HREminho') — Sistema de Gestão de RH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-dark:      #0f1117;
            --bg-card:      #1a1d27;
            --bg-card2:     #20243a;
            --accent:       #6366f1;
            --accent-light: #818cf8;
            --accent-glow:  rgba(99,102,241,0.25);
            --text-primary: #f1f5f9;
            --text-muted:   #94a3b8;
            --border:       rgba(255,255,255,0.08);
            --success:      #22c55e;
            --warning:      #f59e0b;
        }

        html, body { height: 100%; font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 3px; }

        /* Navbar */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; height: 64px;
            background: rgba(15,17,23,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .navbar-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .navbar-brand img { height: 36px; width: auto; }
        .navbar-brand span { font-size: 1.2rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
        .navbar-brand span em { color: var(--accent-light); font-style: normal; }
        .navbar-actions { display: flex; gap: 12px; align-items: center; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
        .btn-ghost { background: transparent; color: var(--text-muted); border: 1px solid var(--border); }
        .btn-ghost:hover { color: var(--text-primary); border-color: var(--accent); }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-light); transform: translateY(-1px); box-shadow: 0 4px 20px var(--accent-glow); }
    </style>
    @yield('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ url('/') }}" class="navbar-brand">
        <img src="{{ asset('images/logo.jpg') }}" alt="HREminho Logo" onerror="this.style.display='none'">
        <span>HR<em>Eminho</em></span>
    </a>
    <div class="navbar-actions">
        @yield('navbar-actions')
    </div>
</nav>

@yield('content')

@yield('scripts')
</body>
</html>
