@extends('layouts.app')

@section('title', 'DocsElectro-Minho')
@section('page-title', 'Integração DocsElectro-Minho')

@section('styles')
<style>
.ds-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
@media (max-width: 768px) { .ds-grid { grid-template-columns: 1fr; } }

.ds-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 22px 24px;
}
.ds-card-title {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
    margin-bottom: 14px;
}

/* ── Status badge ── */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
}
.status-online  { background: rgba(34,197,94,0.12); color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
.status-offline { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
.status-warn    { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
.dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dot-green   { background: #4ade80; box-shadow: 0 0 6px #4ade80; }
.dot-red     { background: #f87171; }
.dot-yellow  { background: #fbbf24; }

/* ── KPI Strip ── */
.kpi-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
.kpi-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 16px 18px; }
.kpi-box .kpi-val { font-size: 1.8rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
.kpi-box .kpi-lbl { font-size: 0.75rem; color: var(--text-muted); }

/* ── Sync section ── */
.sync-section { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 22px 24px; margin-bottom: 24px; }
.sync-title { font-size: 0.95rem; font-weight: 700; margin-bottom: 6px; }
.sync-desc { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 18px; line-height: 1.5; }
.sync-actions { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }

/* ── Buttons ── */
.btn { padding: 9px 18px; border-radius: 9px; font-size: 0.83rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; display: inline-flex; align-items: center; gap: 7px; transition: all 0.15s; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-primary { background: var(--accent); color: #fff; }
.btn-primary:hover:not(:disabled) { opacity: 0.88; }
.btn-secondary { background: transparent; color: var(--text-muted); border: 1px solid var(--border); }
.btn-secondary:hover:not(:disabled) { color: var(--text-primary); border-color: rgba(255,255,255,0.2); }
.btn-danger { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
.btn-danger:hover:not(:disabled) { background: rgba(239,68,68,0.22); }

/* ── Alert banners ── */
.alert { border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; font-size: 0.85rem; display: flex; align-items: flex-start; gap: 10px; }
.alert-success { background: rgba(34,197,94,0.1);  color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
.alert-error   { background: rgba(239,68,68,0.1);  color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
.alert-warn    { background: rgba(245,158,11,0.1); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
.alert-info    { background: rgba(99,102,241,0.1); color: var(--accent-light); border: 1px solid rgba(99,102,241,0.2); }

/* ── Config table ── */
.cfg-table { width: 100%; font-size: 0.83rem; border-collapse: collapse; }
.cfg-table tr { border-bottom: 1px solid var(--border); }
.cfg-table tr:last-child { border-bottom: none; }
.cfg-table td { padding: 9px 0; vertical-align: top; }
.cfg-table td:first-child { color: var(--text-muted); width: 45%; }
.cfg-table td:last-child { font-weight: 500; }
.tag-ok   { color: #4ade80; }
.tag-miss { color: #f87171; }

/* ── Ping button ── */
#pingResult { font-size: 0.8rem; color: var(--text-muted); }

/* ── Spinner ── */
@keyframes spin { to { transform: rotate(360deg); } }
.spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
</style>
@endsection

@section('content')
<div style="max-width: 900px;">

    {{-- Alertas de flash --}}
    @if(session('docsem_sucesso'))
        <div class="alert alert-success">✅ {{ session('docsem_sucesso') }}</div>
    @endif
    @if(session('docsem_erro'))
        <div class="alert alert-error">⚠️ {{ session('docsem_erro') }}</div>
    @endif

    {{-- KPIs --}}
    <div class="kpi-strip">
        <div class="kpi-box">
            <div class="kpi-val" style="color: var(--accent-light)">{{ $totalAtivos }}</div>
            <div class="kpi-lbl">Funcionários ativos</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-val" style="color: var(--text-muted)">{{ $totalAll - $totalAtivos }}</div>
            <div class="kpi-lbl">Inativos / Desligados</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-val" style="color: var(--text-primary)">{{ $totalAll }}</div>
            <div class="kpi-lbl">Total de funcionários</div>
        </div>
    </div>

    {{-- Grid estado + configuração --}}
    <div class="ds-grid">

        {{-- Estado da integração --}}
        <div class="ds-card">
            <div class="ds-card-title">Estado da Integração</div>

            @if(! $configurado)
                <span class="status-badge status-warn">
                    <span class="dot dot-yellow"></span> Não configurada
                </span>
                <div class="alert alert-warn" style="margin-top: 16px;">
                    A integração não está configurada. Defina <code>DOCSEM_API_URL</code> e <code>DOCSEM_API_TOKEN</code> no ficheiro <code>.env</code>.
                </div>
            @elseif($online)
                <span class="status-badge status-online">
                    <span class="dot dot-green"></span> Online
                </span>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 12px;">
                    A API do DocsElectro-Minho está acessível e a responder.
                </p>
            @else
                <span class="status-badge status-offline">
                    <span class="dot dot-red"></span> Inacessível
                </span>
                <div class="alert alert-error" style="margin-top: 16px;">
                    A API está configurada mas não responde. Verifique a URL e o servidor de destino.
                </div>
            @endif

            <div style="margin-top: 16px;">
                <button class="btn btn-secondary" onclick="pingApi()" id="pingBtn">
                    🔄 Testar ligação
                </button>
                <span id="pingResult" style="margin-left: 10px;"></span>
            </div>
        </div>

        {{-- Configuração --}}
        <div class="ds-card">
            <div class="ds-card-title">Configuração (.env)</div>
            <table class="cfg-table">
                <tr>
                    <td>DOCSEM_API_URL</td>
                    <td>
                        @if(config('services.docselectrominho.url'))
                            <span class="tag-ok">✓ Definida</span>
                        @else
                            <span class="tag-miss">✗ Em falta</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>DOCSEM_API_TOKEN</td>
                    <td>
                        @if(config('services.docselectrominho.token'))
                            <span class="tag-ok">✓ Definido</span>
                        @else
                            <span class="tag-miss">✗ Em falta</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Integração ativa</td>
                    <td>
                        @if(config('services.docselectrominho.enabled'))
                            <span class="tag-ok">✓ Sim</span>
                        @else
                            <span class="tag-miss">✗ Desativada</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Sistema</td>
                    <td style="color: var(--text-primary)">DocsElectro-Minho</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Sincronização em lote --}}
    <div class="sync-section">
        <div class="sync-title">Sincronização de Funcionários</div>
        <div class="sync-desc">
            Envia os dados dos funcionários do HREminho para o DocsElectro-Minho.
            Os funcionários são criados ou atualizados automaticamente.
            A sincronização processa em lotes de 100.
        </div>

        @if(! $configurado)
            <div class="alert alert-warn">A integração não está configurada. A sincronização não está disponível.</div>
        @else
            <div class="sync-actions">
                <form method="POST" action="{{ route('docsem.sync') }}" id="syncActiveForm">
                    @csrf
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="btn btn-primary" id="syncActiveBtn"
                            onclick="setSyncing('syncActiveBtn')">
                        ▶ Sincronizar Ativos ({{ $totalAtivos }})
                    </button>
                </form>

                <form method="POST" action="{{ route('docsem.sync') }}" id="syncAllForm">
                    @csrf
                    <input type="hidden" name="status" value="all">
                    <button type="submit" class="btn btn-secondary" id="syncAllBtn"
                            onclick="setSyncing('syncAllBtn')"
                            {{ $totalAll === 0 ? 'disabled' : '' }}>
                        ▶ Sincronizar Todos ({{ $totalAll }})
                    </button>
                </form>
            </div>

            <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 14px;">
                💡 Pode também sincronizar funcionários individualmente a partir da página de <a href="{{ route('employees.index') }}" style="color: var(--accent-light)">Funcionários</a>.
            </p>
        @endif
    </div>

    {{-- Instruções de configuração --}}
    @if(! $configurado)
    <div class="ds-card">
        <div class="ds-card-title">Como Configurar</div>
        <p style="font-size: 0.84rem; color: var(--text-muted); margin-bottom: 14px;">
            Adicione as seguintes variáveis ao ficheiro <code>.env</code> do projeto:
        </p>
        <pre style="background: var(--bg-dark); border: 1px solid var(--border); border-radius: 8px; padding: 14px 16px; font-size: 0.8rem; color: var(--text-primary); overflow-x: auto; line-height: 1.7;">DOCSEM_API_URL=https://docselectrominho.exemplo.pt/api
DOCSEM_API_TOKEN=o-seu-token-aqui
DOCSEM_ENABLED=true</pre>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 12px;">
            Após alterar o <code>.env</code>, execute <code>php artisan config:clear</code> para recarregar as configurações.
        </p>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
async function pingApi() {
    const btn    = document.getElementById('pingBtn');
    const result = document.getElementById('pingResult');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> A testar…';
    result.textContent = '';
    try {
        const res = await fetch('{{ route("docsem.ping") }}', {credentials:'same-origin'}).then(r => r.json());
        result.textContent = res.online ? '✅ Online' : '❌ Sem resposta';
        result.style.color = res.online ? '#4ade80' : '#f87171';
    } catch(e) {
        result.textContent = '❌ Erro de ligação';
        result.style.color = '#f87171';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '🔄 Testar ligação';
    }
}

function setSyncing(btnId) {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> A sincronizar…';
}
</script>
@endsection
