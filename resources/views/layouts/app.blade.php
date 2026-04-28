<!DOCTYPE html>
<html lang="pt" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — HREminho</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
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
            --bg-sidebar:   #ffffff;
            --bg-card:      #ffffff;
            --accent:       #6366f1;
            --accent-light: #4f46e5;
            --accent-glow:  rgba(99,102,241,0.15);
            --text-primary: #1e293b;
            --text-muted:   #64748b;
            --border:       rgba(0,0,0,0.08);
            --success:      #16a34a;
            --warning:      #d97706;
            --danger:       #dc2626;
            --sidebar-w:    260px;
            --topbar-bg:    rgba(240,242,245,0.85);
            --nav-hover:    rgba(0,0,0,0.05);
            --nav-active:   rgba(99,102,241,0.12);
            --scrollbar:    rgba(0,0,0,0.15);
        }

        /* ── Tema Escuro ── */
        [data-theme="dark"] {
            --bg-dark:      #0f1117;
            --bg-sidebar:   #13151f;
            --bg-card:      #1a1d27;
            --accent:       #6366f1;
            --accent-light: #818cf8;
            --accent-glow:  rgba(99,102,241,0.2);
            --text-primary: #f1f5f9;
            --text-muted:   #94a3b8;
            --border:       rgba(255,255,255,0.08);
            --success:      #22c55e;
            --warning:      #f59e0b;
            --danger:       #ef4444;
            --sidebar-w:    260px;
            --topbar-bg:    rgba(15,17,23,0.8);
            --nav-hover:    rgba(255,255,255,0.05);
            --nav-active:   rgba(99,102,241,0.15);
            --scrollbar:    rgba(255,255,255,0.15);
        }

        html, body { height: 100%; font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); transition: background 0.3s, color 0.3s; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }

        /* ── Layout ── */
        .app-layout { display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 50;
            overflow-y: auto;
            transition: background 0.3s, border-color 0.3s;
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 16px; border-bottom: 1px solid var(--border);
            text-decoration: none;
        }
        .sidebar-logo img { height: 32px; width: auto; }
        .sidebar-logo span { font-size: 1.1rem; font-weight: 800; color: var(--text-primary); }
        .sidebar-logo span em { color: var(--accent-light); font-style: normal; }

        .sidebar-nav { flex: 1; padding: 16px 12px; }
        .nav-section-label {
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.5px; color: var(--text-muted);
            padding: 16px 8px 8px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 9px 12px; border-radius: 8px; margin-bottom: 2px;
            text-decoration: none; color: var(--text-muted);
            font-size: 0.875rem; font-weight: 500;
            transition: all 0.15s; cursor: pointer;
        }
        .nav-item:hover { background: var(--nav-hover); color: var(--text-primary); }
        .nav-item.active { background: var(--nav-active); color: var(--accent-light); }
        .nav-item.active .nav-icon { color: var(--accent-light); }
        .nav-icon { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink: 0; }

        .sidebar-user {
            padding: 16px; border-top: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #a78bfa);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-info strong { display: block; font-size: 0.85rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-info span { font-size: 0.75rem; color: var(--text-muted); }
        .logout-btn {
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); padding: 6px; border-radius: 6px;
            transition: all 0.15s; display: flex;
        }
        .logout-btn:hover { color: var(--danger); background: rgba(239,68,68,0.1); }

        /* ── Main ── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column; min-height: 100vh;
        }
        .topbar {
            height: 60px; padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
            background: var(--topbar-bg); backdrop-filter: blur(8px);
            position: sticky; top: 0; z-index: 40;
            transition: background 0.3s, border-color 0.3s;
        }
        .topbar-title { font-size: 1rem; font-weight: 600; color: var(--text-primary); }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-date { font-size: 0.82rem; color: var(--text-muted); }

        /* ── Botão de tema ── */
        .theme-toggle {
            background: none; border: 1px solid var(--border);
            cursor: pointer; color: var(--text-muted);
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; font-size: 1rem;
        }
        .theme-toggle:hover { color: var(--text-primary); border-color: var(--accent); background: var(--nav-hover); }
        .theme-icon-dark  { display: none; }
        .theme-icon-light { display: block; }
        [data-theme="dark"] .theme-icon-dark  { display: block; }
        [data-theme="dark"] .theme-icon-light { display: none; }

        .page-content { padding: 28px; flex: 1; }
    </style>
    @yield('styles')
</head>
<body>
<div class="app-layout">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" onerror="this.style.display='none'">
            <span>HR<em>Electrominho</em></span>
        </a>

        <nav class="sidebar-nav">
            <p class="nav-section-label">Principal</p>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Dashboard
            </a>

            <p class="nav-section-label">Gestão</p>
            <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> Funcionários
            </a>
            <a href="{{ route('departments.index') }}" class="nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <span class="nav-icon">🏢</span> Departamentos
            </a>
            <a href="{{ route('positions.index') }}" class="nav-item {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                <span class="nav-icon">💼</span> Cargos
            </a>
            <a href="{{ route('sectors.index') }}" class="nav-item {{ request()->routeIs('sectors.*') ? 'active' : '' }}">
                <span class="nav-icon">🏭</span> Setores
            </a>

            <p class="nav-section-label">Operações</p>
            <a href="{{ route('attendances.index') }}" class="nav-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                <span class="nav-icon">📅</span> Presenças
            </a>
            <a href="{{ route('leaves.index') }}" class="nav-item {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                <span class="nav-icon">🌴</span> Férias & Licenças
            </a>
            <a href="{{ route('trainings.index') }}" class="nav-item {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                <span class="nav-icon">🎓</span> Formações
            </a>

            <p class="nav-section-label">Relatórios</p>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Relatórios
            </a>

            @if(auth()->user()->role === 'admin')
            <p class="nav-section-label">Administração</p>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="nav-icon">🔐</span> Utilizadores
            </a>
            @endif
        </nav>

        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Terminar sessão">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>


    {{-- Main content --}}
    <main class="main-content">
        <div class="topbar">
            <span class="topbar-title">@yield('page-title')</span>
            <div class="topbar-right">
                <span class="topbar-date" id="topbar-date"></span>
                {{-- Botão alternar tema --}}
                <button class="theme-toggle" id="theme-toggle" title="Alternar tema">
                    {{-- Ícone mostrado no tema claro (clica para ir para escuro) --}}
                    <svg class="theme-icon-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    {{-- Ícone mostrado no tema escuro (clica para ir para claro) --}}
                    <svg class="theme-icon-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m8.485-8.485l-.707.707M4.222 4.222l-.707.707M21 12h-1M4 12H3m16.485 4.485l-.707-.707M4.929 19.071l-.707-.707M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="page-content">
            @yield('content')
        </div>
    </main>

</div>

@yield('scripts')
<script>
    // Data na topbar
    (function() {
        var el = document.getElementById('topbar-date');
        if (el) {
            el.textContent = new Date().toLocaleDateString('pt-PT', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }
    })();

    // Alternar tema e guardar em cookie (365 dias)
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
