@extends('layouts.guest')

@section('title', 'Entrar')

@section('navbar-actions')
    <a href="{{ url('/') }}" class="btn btn-ghost">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Voltar ao início
    </a>
@endsection

@section('styles')
<style>
    .login-page {
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    /* ── Painel Esquerdo ─── */
    .login-left {
        display: flex; align-items: center; justify-content: center;
        padding: 100px 60px 60px;
        background: radial-gradient(ellipse 80% 70% at 30% 50%, rgba(99,102,241,0.15) 0%, transparent 70%);
        position: relative; overflow: hidden;
    }
    .login-left::after {
        content: '';
        position: absolute; right: 0; top: 0; bottom: 0; width: 1px;
        background: linear-gradient(to bottom, transparent, var(--border), transparent);
    }
    .login-left-bg {
        position: absolute; inset: 0; z-index: 0;
        background-image: linear-gradient(var(--border) 1px, transparent 1px),
                          linear-gradient(90deg, var(--border) 1px, transparent 1px);
        background-size: 40px 40px;
        mask-image: radial-gradient(ellipse 80% 80% at 40% 50%, black 20%, transparent 80%);
    }
    .login-left-content { position: relative; z-index: 1; max-width: 440px; }
    .login-left-content .badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.35);
        color: var(--accent-light); font-size: 0.75rem; font-weight: 600;
        padding: 5px 14px; border-radius: 100px; letter-spacing: 0.5px;
        text-transform: uppercase; margin-bottom: 32px;
    }
    .login-left-content h2 { font-size: 2.4rem; font-weight: 800; letter-spacing: -1px; line-height: 1.15; margin-bottom: 20px; }
    .login-left-content h2 span { color: var(--accent-light); }
    .login-left-content p { color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 48px; }

    .feature-list { display: flex; flex-direction: column; gap: 18px; }
    .feature-item { display: flex; align-items: flex-start; gap: 14px; }
    .feature-item-icon {
        width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        background: var(--bg-card); border: 1px solid var(--border);
    }
    .feature-item-text strong { display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 2px; }
    .feature-item-text span { font-size: 0.82rem; color: var(--text-muted); }

    /* ── Painel Direito (Form) ─── */
    .login-right {
        display: flex; align-items: center; justify-content: center;
        padding: 100px 60px 60px;
        background: var(--bg-dark);
    }
    .login-form-wrap { width: 100%; max-width: 400px; }

    .login-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; }
    .login-logo img { height: 40px; width: auto; }
    .login-logo span { font-size: 1.3rem; font-weight: 800; color: var(--text-primary); }
    .login-logo span em { color: var(--accent-light); font-style: normal; }

    .login-form-wrap h1 { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 6px; }
    .login-form-wrap .subtitle { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 36px; }

    /* Form */
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; letter-spacing: 0.3px; text-transform: uppercase; }
    .form-control {
        width: 100%; padding: 12px 16px;
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 10px; color: var(--text-primary);
        font-size: 0.95rem; font-family: inherit;
        transition: all 0.2s; outline: none;
    }
    .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
    .form-control::placeholder { color: #4b5563; }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { font-size: 0.8rem; color: #ef4444; margin-top: 6px; }

    .form-check {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 28px;
    }
    .form-check input[type="checkbox"] {
        width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer;
    }
    .form-check label { font-size: 0.87rem; color: var(--text-muted); cursor: pointer; }

    .btn-submit {
        width: 100%; padding: 13px;
        background: var(--accent); color: #fff;
        border: none; border-radius: 10px;
        font-size: 0.95rem; font-weight: 600; font-family: inherit;
        cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit:hover { background: var(--accent-light); transform: translateY(-1px); box-shadow: 0 8px 24px var(--accent-glow); }
    .btn-submit:active { transform: translateY(0); }

    .alert-error {
        background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3);
        color: #fca5a5; border-radius: 10px; padding: 12px 16px;
        font-size: 0.87rem; margin-bottom: 24px;
        display: flex; align-items: center; gap: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-page { grid-template-columns: 1fr; }
        .login-left { display: none; }
        .login-right { padding: 80px 24px 40px; }
    }
</style>
@endsection

@section('content')
<div class="login-page">

    {{-- ── Painel Esquerdo ───────────────────────── --}}
    <div class="login-left">
        <div class="login-left-bg"></div>
        <div class="login-left-content">
            <div class="badge">✦ HREminho</div>
            <h2>Bem-vindo ao seu <span>painel de RH</span></h2>
            <p>Gerencie a sua equipa de forma simples, eficiente e segura — tudo num só lugar.</p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-item-icon">👥</div>
                    <div class="feature-item-text">
                        <strong>Gestão completa de funcionários</strong>
                        <span>Dados, contratos, cargos e departamentos</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-item-icon">📅</div>
                    <div class="feature-item-text">
                        <strong>Presenças e pontualidade</strong>
                        <span>Registo diário com relatórios detalhados</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-item-icon">🌴</div>
                    <div class="feature-item-text">
                        <strong>Férias e licenças</strong>
                        <span>Pedidos, aprovações e histórico completo</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-item-icon">🎓</div>
                    <div class="feature-item-text">
                        <strong>Treinamentos e certificados</strong>
                        <span>Controlo de formações e progresso</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Formulário de Login ────────────────────── --}}
    <div class="login-right">
        <div class="login-form-wrap">

            <div class="login-logo">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" onerror="this.style.display='none'">
                <span>HR<em>Eminho</em></span>
            </div>

            <h1>Entrar na conta</h1>
            <p class="subtitle">Insira as suas credenciais para aceder ao sistema.</p>

            {{-- Erro de autenticação --}}
            @if ($errors->any())
                <div class="alert-error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="seu@email.com"
                        value="{{ old('email') }}"
                        required autofocus autocomplete="email"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Palavra-passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required autocomplete="current-password"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Manter sessão iniciada</label>
                </div>

                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Entrar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
