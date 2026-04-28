<!DOCTYPE html>
<html lang="pt" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HREminho') — Sistema de Gestão de RH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Aplicar tema antes de renderizar para evitar flash --}}
    <script>
        (function() {
            var theme = document.cookie.match(/(?:^|;\s*)theme=([^;]+)/);
            document.documentElement.setAttribute('data-theme', theme ? theme[1] : 'light');
        })();
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Tema Claro (padrão) ── */
        :root, [data-theme="light"] {
            --bg-dark:      #f0f2f5;
            --bg-card:      #ffffff;
            --bg-card2:     #f8fafc;
            --accent:       #6366f1;
            --accent-light: #4f46e5;
            --accent-glow:  rgba(99,102,241,0.15);
            --text-primary: #1e293b;
            --text-muted:   #64748b;
            --border:       rgba(0,0,0,0.08);
            --success:      #16a34a;
            --warning:      #d97706;
            --navbar-bg:    rgba(240,242,245,0.9);
            --scrollbar:    rgba(0,0,0,0.15);
        }

        /* ── Tema Escuro ── */
        [data-theme="dark"] {
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
            --navbar-bg:    rgba(15,17,23,0.85);
            --scrollbar:    rgba(255,255,255,0.15);
        }

        html, body { height: 100%; font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); transition: background 0.3s, color 0.3s; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }

        /* Navbar */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; height: 64px;
            background: var(--navbar-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            transition: background 0.3s, border-color 0.3s;
        }

        /* Botão de tema na navbar */
        .theme-toggle {
            background: none; border: 1px solid var(--border);
            cursor: pointer; color: var(--text-muted);
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; font-size: 1rem; margin-left: 8px;
        }
        .theme-toggle:hover { color: var(--text-primary); border-color: var(--accent); background: rgba(99,102,241,0.08); }
        .theme-icon-dark  { display: none; }
        .theme-icon-light { display: block; }
        [data-theme="dark"] .theme-icon-dark  { display: block; }
        [data-theme="dark"] .theme-icon-light { display: none; }
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
        {{-- Botão alternar tema --}}
        <button class="theme-toggle" id="theme-toggle" title="Alternar tema">
            <svg class="theme-icon-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg class="theme-icon-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m8.485-8.485l-.707.707M4.222 4.222l-.707.707M21 12h-1M4 12H3m16.485 4.485l-.707-.707M4.929 19.071l-.707-.707M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
            </svg>
        </button>
    </div>
</nav>

@yield('content')

@yield('scripts')
<script>
    document.getElementById('theme-toggle').addEventListener('click', function() {
        var current = document.documentElement.getAttribute('data-theme') || 'light';
        var next = current === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', next);
        var expires = new Date(Date.now() + 365 * 864e5).toUTCString();
        document.cookie = 'theme=' + next + '; path=/; expires=' + expires + '; SameSite=Lax';
    });
</script>
</body>
</html>
