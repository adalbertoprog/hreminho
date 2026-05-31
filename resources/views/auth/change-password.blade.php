@extends('layouts.guest')

@section('title', 'Alterar Palavra-passe')

@section('styles')
<style>
    .change-pwd-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .change-pwd-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2.5rem;
        width: 100%;
        max-width: 440px;
    }
    .change-pwd-card h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text);
    }
    .change-pwd-card p.subtitle {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 1.8rem;
    }
    .form-group {
        margin-bottom: 1.2rem;
    }
    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
    }
    .form-group input {
        width: 100%;
        padding: 0.65rem 0.9rem;
        background: var(--input-bg, var(--bg));
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-size: 0.95rem;
        box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none;
        border-color: var(--accent);
    }
    .alert-warning {
        background: rgba(234,179,8,0.12);
        border: 1px solid rgba(234,179,8,0.4);
        color: #ca8a04;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        font-size: 0.87rem;
        margin-bottom: 1.5rem;
    }
    .error-msg {
        color: #f87171;
        font-size: 0.82rem;
        margin-top: 0.3rem;
    }
    .btn-submit {
        width: 100%;
        padding: 0.75rem;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 0.5rem;
    }
    .btn-submit:hover { opacity: 0.9; }
    .logout-link {
        display: block;
        text-align: center;
        margin-top: 1.2rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .logout-link a { color: var(--accent); text-decoration: none; }
</style>
@endsection

@section('content')
<div class="change-pwd-page">
    <div class="change-pwd-card">
        <h1>🔒 Alterar Palavra-passe</h1>
        <p class="subtitle">Por segurança, deve definir uma nova palavra-passe antes de continuar.</p>

        @if(session('warning'))
            <div class="alert-warning">{{ session('warning') }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="forced" value="1">

            <div class="form-group">
                <label for="current_password">Palavra-passe actual</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password" required>
                @error('current_password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nova palavra-passe</label>
                <input type="password" id="password" name="password" autocomplete="new-password" required>
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar nova palavra-passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn-submit">Guardar e continuar</button>
        </form>

        <span class="logout-link">
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <a href="#" onclick="this.closest('form').submit()">Terminar sessão</a>
            </form>
        </span>
    </div>
</div>
@endsection
