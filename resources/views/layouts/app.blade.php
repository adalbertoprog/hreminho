<!DOCTYPE html>
<html lang="pt" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — HREminho</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
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
        }
        html, body { height: 100%; font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

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
        .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--text-primary); }
        .nav-item.active { background: rgba(99,102,241,0.15); color: var(--accent-light); }
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
            background: rgba(15,17,23,0.8); backdrop-filter: blur(8px);
            position: sticky; top: 0; z-index: 40;
        }
        .topbar-title { font-size: 1rem; font-weight: 600; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-date { font-size: 0.82rem; color: var(--text-muted); }

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
            <span>HR<em>Eminho</em></span>
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
                <span class="nav-icon">🎓</span> Treinamentos
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

    {{-- ── Main Content ── --}}
    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
            <div class="topbar-right">
                <span class="topbar-date" id="current-date"></span>
            </div>
        </header>

        <main class="page-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
    const d = new Date();
    document.getElementById('current-date').textContent = d.toLocaleDateString('pt-PT', {weekday:'long', year:'numeric', month:'long', day:'numeric'});
</script>
@yield('scripts')
</body>
</html>
