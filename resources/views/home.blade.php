@extends('layouts.guest')

@section('title', 'HRElectrominho')

@section('navbar-actions')
    <a href="{{ route('login') }}" class="btn btn-ghost">Entrar</a>
@endsection

@section('styles')
<style>
    /* ── Hero ───────────────────────────────────── */
    .hero {
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 80px 2rem 60px;
        position: relative; overflow: hidden;
    }
    .hero-bg {
        position: absolute; inset: 0; z-index: 0;
        background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(99,102,241,0.18) 0%, transparent 70%),
                    radial-gradient(ellipse 50% 40% at 80% 80%, rgba(139,92,246,0.1) 0%, transparent 60%);
    }
    .hero-grid {
        position: absolute; inset: 0; z-index: 0;
        background-image: linear-gradient(var(--border) 1px, transparent 1px),
                          linear-gradient(90deg, var(--border) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 30%, transparent 100%);
    }
    .hero-inner { position: relative; z-index: 1; max-width: 780px; margin: 0 auto; text-align: center; }

    .badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.35);
        color: var(--accent-light); font-size: 0.78rem; font-weight: 600;
        padding: 5px 14px; border-radius: 100px; letter-spacing: 0.5px;
        text-transform: uppercase; margin-bottom: 28px;
    }
    .badge-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--accent-light); animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

    .hero h1 {
        font-size: clamp(2.4rem, 6vw, 4rem);
        font-weight: 800; line-height: 1.1;
        letter-spacing: -1.5px; margin-bottom: 24px;
    }
    .hero h1 span {
        background: linear-gradient(135deg, var(--accent-light), #a78bfa);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .hero p { font-size: 1.15rem; color: var(--text-muted); max-width: 560px; margin: 0 auto 40px; line-height: 1.7; }
    .hero-cta { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
    .btn-lg { padding: 14px 32px; font-size: 1rem; border-radius: 10px; }
    .btn-lg svg { width: 18px; height: 18px; }

    /* Stats */
    .stats-row {
        display: flex; gap: 40px; justify-content: center; flex-wrap: wrap;
        margin-top: 72px; padding-top: 40px;
        border-top: 1px solid var(--border);
    }
    .stat-item { text-align: center; }
    .stat-item strong { display: block; font-size: 1.8rem; font-weight: 800; color: var(--text-primary); }
    .stat-item span { font-size: 0.82rem; color: var(--text-muted); font-weight: 500; }

    /* ── Features ───────────────────────────────── */
    .features { padding: 100px 2rem; background: rgba(0,0,0,0.2); }
    .section-header { text-align: center; margin-bottom: 64px; }
    .section-label { font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--accent-light); margin-bottom: 16px; }
    .section-header h2 { font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; letter-spacing: -1px; margin-bottom: 16px; }
    .section-header p { color: var(--text-muted); font-size: 1.05rem; max-width: 520px; margin: 0 auto; }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px; max-width: 1100px; margin: 0 auto;
    }
    .feature-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; padding: 32px 28px;
        transition: all 0.3s; position: relative; overflow: hidden;
    }
    .feature-card::before {
        content: ''; position: absolute; inset: 0; opacity: 0;
        background: linear-gradient(135deg, var(--accent-glow), transparent);
        transition: opacity 0.3s;
    }
    .feature-card:hover { border-color: rgba(99,102,241,0.4); transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
    .feature-card:hover::before { opacity: 1; }

    .feature-icon {
        width: 52px; height: 52px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px; font-size: 1.5rem;
    }
    .feature-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 10px; }
    .feature-card p { font-size: 0.88rem; color: var(--text-muted); line-height: 1.6; }

    /* ── CTA Footer ─────────────────────────────── */
    .cta-section {
        padding: 100px 2rem; text-align: center;
        background: radial-gradient(ellipse 70% 60% at 50% 50%, rgba(99,102,241,0.12) 0%, transparent 70%);
    }
    .cta-section h2 { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; letter-spacing: -1px; margin-bottom: 16px; }
    .cta-section p { color: var(--text-muted); margin-bottom: 40px; font-size: 1.05rem; }

    /* Footer */
    footer {
        text-align: center; padding: 28px 2rem;
        border-top: 1px solid var(--border);
        color: var(--text-muted); font-size: 0.82rem;
    }
</style>
@endsection

@section('content')

{{-- ── HERO ───────────────────────────────────────────── --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="hero-inner">

        <div class="badge">
            <span class="badge-dot"></span>
            Sistema de Gestão de RH
        </div>

        <h1>Administre pessoas com <span>inteligência e eficiência</span></h1>

        <p>HRElectrominho centraliza funcionários, presenças, férias, formações e muito mais em uma plataforma moderna e fácil de usar.</p>

        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Aceder ao sistema
            </a>
            <a href="#features" class="btn btn-ghost btn-lg">Ver funcionalidades</a>
        </div>

        <div class="stats-row">
            <div class="stat-item">
                <strong>10+</strong>
                <span>Módulos integrados</span>
            </div>
            <div class="stat-item">
                <strong>100%</strong>
                <span>Web-based</span>
            </div>
            <div class="stat-item">
                <strong>3</strong>
                <span>Níveis de acesso</span>
            </div>
            <div class="stat-item">
                <strong>Real-time</strong>
                <span>Dados em tempo real</span>
            </div>
        </div>
    </div>
</section>

{{-- ── FEATURES ───────────────────────────────────────── --}}
<section class="features" id="features">
    <div class="section-header">
        <p class="section-label">Funcionalidades</p>
        <h2>Tudo que o seu RH precisa</h2>
        <p>Módulos completos para gerir cada aspeto da sua equipa de forma centralizada.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(99,102,241,0.15); color: #818cf8;">👥</div>
            <h3>Gestão de Funcionários</h3>
            <p>Cadastro completo com dados pessoais, função, departamento, formações e histórico formativo.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(34,197,94,0.15); color: #22c55e;">📅</div>
            <h3>Controlo de Presenças</h3>
            <p>Registo diário de entradas e saídas, com suporte a feriados, atrasos e ausências.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(245,158,11,0.15); color: #f59e0b;">🌴</div>
            <h3>Gestão de Férias</h3>
            <p>Pedidos de férias, licenças médicas e ausências não remuneradas com fluxo de aprovação.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(239,68,68,0.15); color: #ef4444;">🎓</div>
            <h3>Formações</h3>
            <p>Controlo de formações, certificados, pontuações e progresso de cada colaborador.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(139,92,246,0.15); color: #a78bfa;">🏢</div>
            <h3>Departamentos e Setores</h3>
            <p>Estrutura organizacional completa com gestores, setores e hierarquias definidas.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(20,184,166,0.15); color: #14b8a6;">🔐</div>
            <h3>Controlo de Acessos</h3>
            <p>Três perfis de acesso — Admin, RH e Funcionário — com permissões distintas e seguras.</p>
        </div>
    </div>
</section>

{{-- ── CTA ─────────────────────────────────────────────── --}}
<section class="cta-section">
    <h2>Pronto para começar?</h2>
    <p>Aceda ao sistema agora mesmo e tenha controlo total da sua equipa.</p>
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Entrar no sistema
    </a>
</section>

<footer>
    &copy; {{ date('Y') }} HRElectrominho — Sistema de Gestão de Recursos Humanos
</footer>

@endsection
