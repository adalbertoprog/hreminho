@extends('layouts.app')
@section('title', 'Funcionários')
@section('page-title', 'Funcionários')

@section('styles')
<style>
/* ── Toolbar ── */
.toolbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:20px; }
.toolbar h2 { font-size:1.25rem; font-weight:700; }
.btn-primary { display:inline-flex; align-items:center; gap:7px; background:var(--accent); color:#fff; border:none; padding:9px 20px; border-radius:9px; font-size:.875rem; font-weight:600; cursor:pointer; transition:.15s; }
.btn-primary:hover { background:#4f46e5; }
.btn-docsem { display:inline-flex; align-items:center; gap:7px; background:rgba(99,102,241,.12); color:var(--accent-light); border:1px solid rgba(99,102,241,.25); padding:9px 16px; border-radius:9px; font-size:.875rem; font-weight:600; cursor:pointer; transition:.15s; }
.btn-docsem:hover { background:rgba(99,102,241,.22); border-color:var(--accent); }

/* ── Filters ── */
.filters { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
.f-input { flex:1; min-width:160px; background:var(--bg-card); border:1px solid var(--border); border-radius:9px; padding:9px 13px; color:var(--text-primary); font-size:.86rem; font-family:inherit; }
.f-input:focus { outline:none; border-color:var(--accent); }
.btn-filter { padding:9px 18px; border-radius:9px; background:rgba(99,102,241,.15); border:1px solid rgba(99,102,241,.3); color:var(--accent-light); cursor:pointer; font-size:.86rem; font-weight:600; }
.btn-reset  { padding:9px 14px; border-radius:9px; background:rgba(255,255,255,.05); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; font-size:.86rem; }
.btn-reset:hover { color:var(--text-primary); }

/* ── Sort header ── */
thead th.sortable { cursor:pointer; user-select:none; }
thead th.sortable:hover { color:var(--text-primary); }
thead th.sortable .sort-icon { margin-left:4px; opacity:.35; font-style:normal; font-size:.7rem; }
thead th.sortable.sort-asc  .sort-icon,
thead th.sortable.sort-desc .sort-icon { opacity:1; color:var(--accent-light); }

/* ── Card / Table ── */
.card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:.875rem; }
thead th { padding:11px 16px; text-align:left; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted); border-bottom:1px solid var(--border); background:rgba(255,255,255,.02); white-space:nowrap; }
tbody td { padding:11px 16px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover { background:rgba(255,255,255,.025); }

/* Avatar */
.avatar { width:33px; height:33px; border-radius:50%; background:linear-gradient(135deg,var(--accent),#a78bfa); display:inline-flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700; color:#fff; flex-shrink:0; overflow:hidden; }
.avatar img { width:100%; height:100%; object-fit:cover; }
.emp-name { font-weight:600; font-size:.875rem; }
.emp-sub  { font-size:.74rem; color:var(--text-muted); }

/* Status badge */
.badge { display:inline-block; padding:3px 9px; border-radius:6px; font-size:.73rem; font-weight:700; }
.badge-active     { background:rgba(34,197,94,.15);  color:#22c55e; }
.badge-inactive   { background:rgba(245,158,11,.15); color:#f59e0b; }
.badge-terminated { background:rgba(239,68,68,.12);  color:#ef4444; }

/* Row actions */
.btn-sm { padding:4px 11px; border-radius:7px; font-size:.76rem; font-weight:600; cursor:pointer; border:none; transition:.15s; }
.btn-view   { background:rgba(6,182,212,.12);   color:#06b6d4; }
.btn-view:hover { background:rgba(6,182,212,.25); }
.btn-edit   { background:rgba(99,102,241,.15); color:var(--accent-light); }
.btn-edit:hover { background:rgba(99,102,241,.3); }
.btn-del    { background:rgba(239,68,68,.12); color:#ef4444; }
.btn-del:hover { background:rgba(239,68,68,.25); }

/* Paginação */
.pag { display:flex; align-items:center; justify-content:space-between; padding:13px 16px; border-top:1px solid var(--border); flex-wrap:wrap; gap:8px; }
.pag-info { font-size:.8rem; color:var(--text-muted); }
.pag-btns { display:flex; gap:4px; }
.pag-btns button { min-width:30px; height:30px; border-radius:7px; border:1px solid var(--border); background:rgba(255,255,255,.03); color:var(--text-muted); cursor:pointer; font-size:.8rem; font-weight:600; transition:.15s; }
.pag-btns button:hover:not(:disabled) { border-color:var(--accent); color:var(--accent-light); }
.pag-btns button.active { background:var(--accent); color:#fff; border-color:var(--accent); }
.pag-btns button:disabled { opacity:.35; cursor:not-allowed; }

/* Empty / Loading */
.state-row td { text-align:center; padding:48px; color:var(--text-muted); }
.spinner { display:inline-block; width:20px; height:20px; border:2px solid var(--border); border-top-color:var(--accent); border-radius:50%; animation:spin .7s linear infinite; margin-right:8px; vertical-align:middle; }
@keyframes spin { to { transform:rotate(360deg); } }

/* Toast */
.toast-wrap { position:fixed; bottom:24px; right:24px; z-index:999; display:flex; flex-direction:column; gap:8px; }
.toast { padding:12px 18px; border-radius:10px; font-size:.86rem; font-weight:500; box-shadow:0 8px 32px rgba(0,0,0,.4); animation:slideIn .25s ease; }
.toast-ok  { background:rgba(34,197,94,.15);  color:#22c55e; border:1px solid rgba(34,197,94,.25); }
.toast-err { background:rgba(239,68,68,.15);  color:#ef4444; border:1px solid rgba(239,68,68,.25); }
@keyframes slideIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }

/* ── Modal ── */
.overlay { display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.65); backdrop-filter:blur(4px); align-items:flex-start; justify-content:center; padding:28px 14px; overflow-y:auto; }
.overlay.open { display:flex; }
.modal { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:26px; width:100%; max-width:640px; box-shadow:0 24px 80px rgba(0,0,0,.5); margin:auto; }
.modal-title { font-size:1.05rem; font-weight:700; margin-bottom:20px; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:13px; }
.full { grid-column:1/-1; }
.section-sep { grid-column:1/-1; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); padding-top:6px; border-top:1px solid var(--border); }
.fg label { display:block; font-size:.78rem; font-weight:600; color:var(--text-muted); margin-bottom:5px; }
.fg input, .fg select, .fg textarea {
    width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border);
    border-radius:9px; padding:9px 12px; color:var(--text-primary); font-size:.875rem; font-family:inherit;
}
.fg input:focus, .fg select:focus, .fg textarea:focus { outline:none; border-color:var(--accent); }
.fg select option { background:var(--bg-card); }
.err-msg { color:#ef4444; font-size:.76rem; margin-top:3px; display:none; }
.modal-foot { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.btn-cancel { padding:9px 20px; border-radius:9px; background:rgba(255,255,255,.06); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; font-size:.875rem; font-weight:600; }
.btn-cancel:hover { color:var(--text-primary); }

/* Confirm modal */
.confirm-modal { max-width:380px; text-align:center; }
.confirm-modal p { color:var(--text-muted); font-size:.9rem; margin:8px 0 20px; }
.btn-danger { padding:9px 20px; border-radius:9px; background:#ef4444; color:#fff; border:none; cursor:pointer; font-size:.875rem; font-weight:600; }
.btn-danger:hover { background:#dc2626; }

/* Associação rápida */
.btn-link  { background:rgba(34,197,94,.12); color:#22c55e; }
.btn-link:hover  { background:rgba(34,197,94,.25); }
.btn-unlink  { background:rgba(245,158,11,.12); color:#f59e0b; }
.btn-unlink:hover  { background:rgba(245,158,11,.25); }
.assoc-quick-modal { max-width:420px; }
.assoc-quick-modal .sub { font-size:.82rem; color:var(--text-muted); margin:-12px 0 18px; }
.assoc-quick-modal select { width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border); border-radius:9px; padding:9px 12px; color:var(--text-primary); font-size:.875rem; font-family:inherit; margin-bottom:4px; }
.assoc-quick-modal select option { background:var(--bg-card); }
.assoc-quick-modal .hint { font-size:.74rem; color:var(--text-muted); margin-bottom:18px; }

/* ── Profile Card (hover) ── */
.emp-name-wrap { position:relative; display:inline-block; cursor:default; }
.emp-name-wrap .emp-name { text-decoration:underline dotted rgba(255,255,255,.2); }

.profile-card {
    position:fixed; z-index:500;
    width:320px;
    background:var(--bg-sidebar);
    border:1px solid rgba(255,255,255,.12);
    border-radius:16px;
    box-shadow:0 20px 60px rgba(0,0,0,.6);
    pointer-events:none;
    opacity:0; transform:translateY(6px) scale(.97);
    transition:opacity .18s ease, transform .18s ease;
    overflow:hidden;
}
.profile-card.visible { opacity:1; transform:translateY(0) scale(1); }

.pc-banner {
    height:64px;
    background:linear-gradient(135deg, var(--accent) 0%, #a78bfa 100%);
    position:relative;
}
.pc-avatar-wrap {
    position:absolute; bottom:-24px; left:20px;
    width:52px; height:52px; border-radius:50%;
    border:3px solid var(--bg-sidebar);
    background:linear-gradient(135deg, var(--accent), #a78bfa);
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; font-weight:800; color:#fff;
    overflow:hidden;
}
.pc-avatar-wrap img { width:100%; height:100%; object-fit:cover; }

.pc-body { padding:32px 20px 18px; }
.pc-name { font-size:.95rem; font-weight:700; margin-bottom:2px; }
.pc-sub  { font-size:.75rem; color:var(--text-muted); margin-bottom:14px; }

.pc-info { display:grid; grid-template-columns:1fr 1fr; gap:8px 14px; margin-bottom:14px; }
.pc-field label { display:block; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted); margin-bottom:2px; }
.pc-field span  { font-size:.8rem; color:var(--text-primary); }

.pc-divider { border:none; border-top:1px solid var(--border); margin:10px 0 12px; }

.pc-trainings-title { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted); margin-bottom:8px; }
.pc-training-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:5px 0; border-bottom:1px solid rgba(255,255,255,.04); font-size:.78rem;
}
.pc-training-item:last-child { border-bottom:none; }
.pc-tr-name { color:var(--text-primary); font-weight:500; flex:1; min-width:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pc-tr-status {
    flex-shrink:0; padding:2px 7px; border-radius:20px; font-size:.68rem; font-weight:700; margin-left:8px;
}
.pc-tr-enrolled  { background:rgba(99,102,241,.2);  color:var(--accent-light); }
.pc-tr-completed { background:rgba(34,197,94,.15);  color:#22c55e; }
.pc-tr-failed    { background:rgba(239,68,68,.12);  color:#ef4444; }
.pc-tr-empty     { font-size:.78rem; color:var(--text-muted); font-style:italic; }
.pc-loading      { font-size:.78rem; color:var(--text-muted); text-align:center; padding:10px 0; }

/* ── Training Panel Modal ── */
.btn-train { background:rgba(34,197,94,.12); color:#22c55e; }
.btn-train:hover { background:rgba(34,197,94,.25); }
.training-modal { max-width:700px; }
.training-header-info { display:flex; align-items:center; gap:12px; margin-bottom:22px; padding-bottom:16px; border-bottom:1px solid var(--border); }
.training-header-info .avatar-lg { width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,var(--accent),#a78bfa); display:flex; align-items:center; justify-content:center; font-size:.9rem; font-weight:700; color:#fff; flex-shrink:0; }
.training-header-info h4 { font-size:1rem; font-weight:700; margin-bottom:2px; }
.training-header-info span { font-size:.8rem; color:var(--text-muted); }
.tr-table { width:100%; border-collapse:collapse; font-size:.83rem; }
.tr-table thead th { padding:9px 12px; text-align:left; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted); border-bottom:1px solid var(--border); background:var(--nav-hover); }
.tr-table tbody td { padding:10px 12px; border-bottom:1px solid var(--border); vertical-align:middle; }
.tr-table tbody tr:last-child td { border-bottom:none; }
.tr-table tbody tr:hover td { background:var(--nav-hover); }
.score-bar-wrap { display:flex; align-items:center; gap:8px; }
.score-bar { flex:1; height:6px; border-radius:3px; background:var(--border); overflow:hidden; }
.score-bar-fill { height:100%; border-radius:3px; background:var(--accent); }
.tr-empty { text-align:center; padding:40px; color:var(--text-muted); }
.badge-enrolled  { background:rgba(99,102,241,.2);  color:var(--accent-light); }
.badge-completed { background:rgba(34,197,94,.15);  color:#22c55e; }
.badge-failed    { background:rgba(239,68,68,.12);  color:#ef4444; }
.tr-loading { text-align:center; padding:40px; color:var(--text-muted); }

/* ── View Modal ── */
.view-modal { max-width:780px; }
.view-banner {
    margin:-26px -26px 0;
    height:90px;
    background:linear-gradient(135deg, var(--accent) 0%, #a78bfa 100%);
    border-radius:16px 16px 0 0;
    position:relative;
    margin-bottom:52px;
}
.view-avatar-wrap {
    position:absolute; bottom:-36px; left:28px;
    width:72px; height:72px; border-radius:50%;
    border:4px solid var(--bg-card);
    background:linear-gradient(135deg,var(--accent),#a78bfa);
    display:flex; align-items:center; justify-content:center;
    font-size:1.4rem; font-weight:800; color:#fff;
    overflow:hidden; flex-shrink:0;
}
.view-avatar-wrap img { width:100%; height:100%; object-fit:cover; }
.view-header-actions { position:absolute; bottom:-36px; right:0; display:flex; gap:8px; }
.view-name { font-size:1.15rem; font-weight:800; margin-bottom:2px; }
.view-sub  { font-size:.82rem; color:var(--text-muted); margin-bottom:18px; }

.view-section-title {
    font-size:.68rem; font-weight:700; text-transform:uppercase;
    letter-spacing:1px; color:var(--text-muted);
    margin:20px 0 12px; padding-bottom:6px;
    border-bottom:1px solid var(--border);
}
.view-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px 20px; margin-bottom:4px; }
.view-grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:12px 20px; }
.view-field label { display:block; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:var(--text-muted); margin-bottom:3px; }
.view-field span  { font-size:.875rem; color:var(--text-primary); font-weight:500; }

.view-tabs { display:flex; gap:4px; margin:20px 0 14px; border-bottom:1px solid var(--border); }
.view-tab {
    padding:7px 16px; border-radius:8px 8px 0 0; font-size:.82rem; font-weight:600;
    cursor:pointer; border:none; background:none; color:var(--text-muted);
    border-bottom:2px solid transparent; margin-bottom:-1px; transition:.15s;
}
.view-tab.active { color:var(--accent-light); border-bottom-color:var(--accent); }
.view-tab:hover:not(.active) { color:var(--text-primary); background:var(--nav-hover); }
.view-tab-panel { display:none; }
.view-tab-panel.active { display:block; }

/* ── Stats Employees ── */
.emp-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:18px; }
.emp-stat-card {
    background:var(--bg-card); border:1px solid var(--border); border-radius:12px;
    padding:16px 20px; display:flex; align-items:center; gap:14px; transition:.2s;
}
.emp-stat-card:hover { border-color:rgba(99,102,241,.3); transform:translateY(-1px); }
.emp-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
.emp-stat-icon.all    { background:rgba(99,102,241,.15); }
.emp-stat-icon.active { background:rgba(34,197,94,.15); }
.emp-stat-icon.inactive { background:rgba(239,68,68,.12); }
.emp-stat-num { font-size:1.7rem; font-weight:800; letter-spacing:-1px; line-height:1; }
.emp-stat-num.all    { color:var(--accent-light); }
.emp-stat-num.active { color:#22c55e; }
.emp-stat-num.inactive { color:#ef4444; }
.emp-stat-label { font-size:.78rem; color:var(--text-muted); font-weight:500; margin-top:3px; }
@media (max-width:600px) { .emp-stats { grid-template-columns:1fr; } }

/* Bulk users modal */
.bulk-modal { max-width:460px; }
.bulk-modal .info-box {
    background:rgba(99,102,241,.08); border:1px solid rgba(99,102,241,.2);
    border-radius:10px; padding:14px 16px; font-size:.85rem; color:var(--text-muted);
    margin-bottom:18px; line-height:1.6;
}
.bulk-modal .info-box strong { color:var(--text-primary); }
.bulk-result { display:none; margin-top:16px; }
.bulk-result .stat-row { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid var(--border); font-size:.875rem; }
.bulk-result .stat-row:last-child { border-bottom:none; }
.bulk-result .stat-row .val { font-weight:700; }
.bulk-result .val.ok  { color:#22c55e; }
.bulk-result .val.warn { color:#f59e0b; }
.bulk-result .val.err  { color:#ef4444; }
</style>
@endsection

@section('content')

<div class="toolbar">
    <h2>👥 Funcionários</h2>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        {{-- Botão de sincronização com DocsElectro-Minho --}}
        <button class="btn-docsem" onclick="openDocsEmSync()" title="Sincronizar com DocsElectro-Minho">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            DocsElectro-Minho
        </button>
        <button class="btn-docsem" onclick="openBulkUsersModal()" title="Criar contas para todos os funcionários activos">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Gerar Acessos
        </button>
        <button class="btn-primary" onclick="openCreate()">+ Novo Funcionário</button>
    </div>
</div>

{{-- Modal de sincronização DocsElectro-Minho --}}
<div class="overlay" id="docsEmOverlay" style="display:none;" onclick="if(event.target===this)closeDocsEmSync()">
    <div class="modal" style="max-width:480px;width:100%;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:1rem;">Sincronizar com DocsElectro-Minho</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">Enviar funcionários para o sistema documental</div>
                </div>
            </div>
            <button onclick="closeDocsEmSync()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.3rem;line-height:1;">✕</button>
        </div>

        {{-- Estado da ligação --}}
        <div id="docsEmStatus" style="padding:12px 14px;border-radius:10px;background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.15);margin-bottom:18px;font-size:.84rem;display:flex;align-items:center;gap:8px;">
            <span id="docsEmStatusDot" style="width:8px;height:8px;border-radius:50%;background:#94a3b8;flex-shrink:0;"></span>
            <span id="docsEmStatusText">A verificar ligação…</span>
        </div>

        {{-- Opções --}}
        <form method="POST" action="{{ route('docsem.sync') }}" id="docsEmForm">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="font-size:.83rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:8px;">Funcionários a sincronizar</label>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <label style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:9px;border:1px solid var(--border);cursor:pointer;font-size:.875rem;" id="optActive">
                        <input type="radio" name="status" value="active" checked onchange="updateDocsEmOption(this)">
                        <div>
                            <div style="font-weight:600;">Apenas ativos</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">Sincroniza todos os funcionários com estado "active"</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:9px;border:1px solid var(--border);cursor:pointer;font-size:.875rem;" id="optAll">
                        <input type="radio" name="status" value="all" onchange="updateDocsEmOption(this)">
                        <div>
                            <div style="font-weight:600;">Todos os funcionários</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">Inclui ativos, inativos e terminados</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Resultado da última sincronização --}}
            @if(session('docsem_sucesso'))
                <div style="padding:10px 14px;border-radius:9px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#22c55e;font-size:.83rem;margin-bottom:14px;">
                    {{ session('docsem_sucesso') }}
                </div>
            @endif
            @if(session('docsem_erro'))
                <div style="padding:10px 14px;border-radius:9px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#ef4444;font-size:.83rem;margin-bottom:14px;">
                    {{ session('docsem_erro') }}
                </div>
            @endif

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:8px;">
                <button type="button" onclick="closeDocsEmSync()" style="padding:9px 18px;border-radius:9px;border:1px solid var(--border);background:transparent;color:var(--text-muted);cursor:pointer;font-size:.875rem;font-weight:600;">
                    Cancelar
                </button>
                <button type="submit" id="docsEmSubmitBtn" style="padding:9px 20px;border-radius:9px;background:var(--accent);border:none;color:#fff;cursor:pointer;font-size:.875rem;font-weight:600;display:flex;align-items:center;gap:7px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Sincronizar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stats cards -->
<div class="emp-stats" id="empStatsRow">
    <div class="emp-stat-card">
        <div class="emp-stat-icon all">👥</div>
        <div>
            <div class="emp-stat-num all" id="statTotal">—</div>
            <div class="emp-stat-label">Total de Funcionários</div>
        </div>
    </div>
    <div class="emp-stat-card">
        <div class="emp-stat-icon active">✅</div>
        <div>
            <div class="emp-stat-num active" id="statActive">—</div>
            <div class="emp-stat-label">Funcionários Ativos</div>
        </div>
    </div>
    <div class="emp-stat-card">
        <div class="emp-stat-icon inactive">🔴</div>
        <div>
            <div class="emp-stat-num inactive" id="statInactive">—</div>
            <div class="emp-stat-label">Funcionários Inativos</div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="filters" id="filterBar">
    <input id="fSearch" class="f-input" placeholder="🔍 Nome, email ou código..." oninput="debouncedSearch()" onkeydown="if(event.key==='Enter'){clearTimeout(_searchTimer);applyFilters();}">
    <select id="fDept"   class="f-input" style="max-width:180px"><option value="">Todos os depts.</option></select>
    <select id="fSector" class="f-input" style="max-width:180px"><option value="">Todos os setores</option></select>
    <select id="fPos"    class="f-input" style="max-width:180px"><option value="">Todas as funções</option></select>
    <select id="fStatus" class="f-input" style="max-width:140px">
        <option value="">Todos estados</option>
        <option value="active">Ativo</option>
        <option value="inactive">Inativo</option>
        <option value="terminated">Desligado</option>
    </select>
    <button class="btn-filter" onclick="applyFilters()">Filtrar</button>
    <button class="btn-reset"  onclick="resetFilters()">✕ Limpar</button>
</div>

<!-- Tabela -->
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="sortable" id="thName" onclick="setSort('name')">
                        Funcionário <i class="sort-icon" id="siName">⇅</i>
                    </th>
                    <th class="sortable" id="thCode" onclick="setSort('code')">
                        Código <i class="sort-icon" id="siCode">⇅</i>
                    </th>
                    <th>Setor</th>
                    <th>Função</th>
                    <th>Admissão</th>
                    <th>Idade</th>
                    <th>Anos de casa</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="empBody">
                <tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr>
            </tbody>
        </table>
    </div>
    <div class="pag" id="pagBar" style="display:none">
        <span class="pag-info" id="pagInfo"></span>
        <div class="pag-btns" id="pagBtns"></div>
    </div>
</div>

<!-- Toast container -->
<div class="toast-wrap" id="toastWrap"></div>

<!-- ══ Modal: Criar / Editar ══ -->
<div class="overlay" id="formOverlay">
<div class="modal">
    <div class="modal-title" id="formTitle">Novo Funcionário</div>
    <form id="empForm" onsubmit="submitForm(event)">
        <div class="form-grid">
            <div class="section-sep">Dados Pessoais</div>
            <div class="fg"><label>Código *</label><input name="code" required placeholder="FUN-001"></div>
            <div class="fg"><label>Género</label>
                <select name="gender">
                    <option value="">— Selecionar —</option>
                    <option value="male">Masculino</option>
                    <option value="female">Feminino</option>
                    <option value="other">Outro</option>
                </select>
            </div>
            <div class="fg"><label>Primeiro Nome *</label><input name="first_name" required></div>
            <div class="fg"><label>Último Nome *</label><input name="last_name" required></div>
            <div class="fg"><label>Email *</label><input name="email" type="email" required></div>
            <div class="fg"><label>Telefone</label><input name="phone"></div>
            <div class="fg"><label>Data de Nascimento</label><input name="date_of_birth" type="date"></div>
            <div class="fg"><label>Nacionalidade</label><input name="nationality"></div>
            <div class="fg full"><label>Morada</label><input name="address"></div>
            <div class="fg full"><label>Local de trabalho</label><input name="work_location" placeholder="Ex: Viana do Castelo, Guimarães"></div>
            <div class="fg"><label>Hora de entrada (override) <span id="checkInGlobalHint" style="font-weight:400;color:var(--text-muted);font-size:.78rem"></span></label><input name="expected_check_in" type="time" placeholder="Padrão global"></div>
            <div class="fg full">
                <label>Foto de Perfil</label>
                <div style="display:flex;align-items:center;gap:14px;margin-top:4px">
                    <div id="photoPreview" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#a78bfa);display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:700;color:#fff;overflow:hidden;flex-shrink:0">
                        <span id="photoInitials">?</span>
                        <img id="photoImg" src="" style="display:none;width:100%;height:100%;object-fit:cover">
                    </div>
                    <div style="flex:1">
                        <input type="file" id="photoFile" accept="image/*" style="display:none" onchange="handlePhotoChange(this)">
                        <button type="button" class="btn-cancel" style="font-size:.78rem;padding:6px 14px" onclick="document.getElementById('photoFile').click()">📷 Escolher foto</button>
                        <button type="button" class="btn-cancel" style="font-size:.78rem;padding:6px 10px;margin-left:6px" onclick="clearPhoto()" id="photoClearBtn" style="display:none">✕</button>
                        <div style="font-size:.72rem;color:var(--text-muted);margin-top:5px">JPG, JPEG ou PNG · máx. 2MB</div>
                    </div>
                </div>
            </div>

            <div class="section-sep">Contrato & Função</div>
            <div class="fg"><label>Departamento *</label><select name="department_id" id="fDeptModal" required><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Função *</label><select name="position_id" id="fPosModal" required><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Setor</label><select name="sector_id" id="fSecModal"><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Estado</label>
                <select name="status">
                    <option value="active">Ativo</option>
                    <option value="inactive">Inativo</option>
                    <option value="terminated">Desligado</option>
                </select>
            </div>
            <div class="fg"><label>Data de Admissão *</label><input name="hire_date" type="date" required></div>
            <div class="fg">
                <label>Tipo de Contrato</label>
                <select name="contract_type">
                    <option value="">— Selecionar —</option>
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="freelance">Freelance</option>
                </select>
            </div>
            <div class="fg"><label>Data de Término</label><input name="end_date" type="date"></div>
            <div class="fg full">
                <label>Utilizador do sistema <small style="font-weight:400;color:var(--text-muted)">(para acesso ao portal)</small></label>
                <select name="user_id" id="fUserModal">
                    <option value="">— Sem utilizador associado —</option>
                </select>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('formOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="formSubmitBtn">Criar Funcionário</button>
        </div>
    </form>
</div>
</div>

<!-- ══ Profile Card (hover) ══ -->
<div id="profileCard" class="profile-card">
    <div class="pc-banner">
        <div class="pc-avatar-wrap" id="pcAvatar"></div>
    </div>
    <div class="pc-body">
        <div class="pc-name" id="pcName">—</div>
        <div class="pc-sub"  id="pcSub">—</div>
        <div class="pc-info">
            <div class="pc-field">
                <label>Data de nascimento</label>
                <span id="pcDob">—</span>
            </div>
            <div class="pc-field">
                <label>Idade</label>
                <span id="pcAge">—</span>
            </div>
            <div class="pc-field">
                <label>Anos de casa</label>
                <span id="pcTenure">—</span>
            </div>
            <div class="pc-field">
                <label>Tipo de contrato</label>
                <span id="pcContract">—</span>
            </div>
            <div class="pc-field">
                <label>Local de trabalho</label>
                <span id="pcWorkLocation">—</span>
            </div>
            <div class="pc-field" style="grid-column:1/-1">
                <label>Morada</label>
                <span id="pcAddress" style="white-space:normal;line-height:1.4">—</span>
            </div>
        </div>
        <hr class="pc-divider">
        <div class="pc-trainings-title">🎓 Formações</div>
        <div id="pcTrainings"><div class="pc-loading">A carregar…</div></div>
    </div>
</div>

<!-- ══ Modal: Formações do Funcionário ══ -->
<div class="overlay" id="trainOverlay">
<div class="modal training-modal">
    <div class="modal-title">🎓 Formações do Funcionário</div>
    <div class="training-header-info">
        <div class="avatar-lg" id="trAvatar"></div>
        <div>
            <h4 id="trName">—</h4>
            <span id="trMeta">—</span>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="tr-table">
            <thead>
                <tr>
                    <th>Formação</th>
                    <th>Provedor</th>
                    <th>Estado</th>
                    <th>Pontuação</th>
                    <th>Início</th>
                    <th>Conclusão</th>
                </tr>
            </thead>
            <tbody id="trBody">
                <tr><td colspan="6" class="tr-loading">⏳ A carregar…</td></tr>
            </tbody>
        </table>
    </div>
    <div class="modal-foot">
        <button class="btn-cancel" onclick="closeOverlay('trainOverlay')">Fechar</button>
    </div>
</div>
</div>

<!-- ══ Modal: Ver Funcionário ══ -->
<div class="overlay" id="viewOverlay">
<div class="modal view-modal">

    <!-- Banner + Avatar -->
    <div class="view-banner">
        <div class="view-avatar-wrap" id="vAvatar"></div>
        <div class="view-header-actions">
            <button class="btn-sm btn-edit" id="vEditBtn" onclick="">✏️ Editar</button>
        </div>
    </div>

    <!-- Nome / sub -->
    <div class="view-name" id="vName">—</div>
    <div class="view-sub"  id="vSub">—</div>

    <!-- Tabs -->
    <div class="view-tabs">
        <button class="view-tab active" onclick="switchTab('vTabInfo',this)">📋 Informação</button>
        <button class="view-tab"        onclick="switchTab('vTabTrainings',this)">🎓 Formações</button>
    </div>

    <!-- Tab: Info -->
    <div class="view-tab-panel active" id="vTabInfo">
        <div class="view-section-title">Dados Pessoais</div>
        <div class="view-grid">
            <div class="view-field"><label>Código</label><span id="vCode">—</span></div>
            <div class="view-field"><label>Género</label><span id="vGender">—</span></div>
            <div class="view-field"><label>Data de Nascimento</label><span id="vDob">—</span></div>
            <div class="view-field"><label>Idade</label><span id="vAge">—</span></div>
            <div class="view-field"><label>Nacionalidade</label><span id="vNationality">—</span></div>
            <div class="view-field"><label>Telefone</label><span id="vPhone">—</span></div>
            <div class="view-field" style="grid-column:1/-1"><label>Morada</label><span id="vAddress">—</span></div>
        </div>

        <div class="view-section-title">Contrato & Função</div>
        <div class="view-grid">
            <div class="view-field"><label>Departamento</label><span id="vDept">—</span></div>
            <div class="view-field"><label>Setor</label><span id="vSector">—</span></div>
            <div class="view-field"><label>Função       </label><span id="vPosition">—</span></div>
            <div class="view-field"><label>Data de Admissão</label><span id="vHireDate">—</span></div>
            <div class="view-field"><label>Idade</label><span id="vAgeContract">—</span></div>
            <div class="view-field"><label>Anos de Casa</label><span id="vTenure">—</span></div>
            <div class="view-field"><label>Estado</label><span id="vStatus">—</span></div>
            <div class="view-field"><label>Tipo de Contrato</label><span id="vContract">—</span></div>
            <div class="view-field"><label>Data de Término</label><span id="vEndDate">—</span></div>
            <div class="view-field"><label>Local de Trabalho</label><span id="vWorkLocation">—</span></div>
            <div class="view-field"><label>Email</label><span id="vEmail">—</span></div>
        </div>
    </div>

    <!-- Tab: Formações -->
    <div class="view-tab-panel" id="vTabTrainings">
        <div id="vTrainingsContent" style="margin-top:6px">
            <div class="tr-loading">⏳ A carregar formações…</div>
        </div>
    </div>

    <div class="modal-foot">
        <button class="btn-cancel" onclick="closeOverlay('viewOverlay')">Fechar</button>
        <button class="btn-sm" id="vDownloadBtn" style="padding:9px 18px;border-radius:9px;background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);color:var(--accent-light);cursor:pointer;font-size:.86rem;font-weight:600;display:inline-flex;align-items:center;gap:7px;" onclick="downloadFicha()">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
            Descarregar Ficha
        </button>
    </div>
</div>
</div>

<!-- ══ Modal: Gerar acessos em massa ══ -->
<div class="overlay" id="bulkUsersOverlay">
<div class="modal bulk-modal">
    <div class="modal-title">🔑 Gerar Acessos ao Portal</div>
    <div class="info-box">
        Esta acção vai criar uma conta de acesso para <strong>todos os funcionários activos</strong> que ainda não têm utilizador associado.<br><br>
        • Login: <strong>código do funcionário</strong> (ex: FUN0488)<br>
        • Password inicial: <strong>12345678</strong><br><br>
        Funcionários que já têm conta não são afectados.
    </div>
    <div class="bulk-result" id="bulkResult">
        <div class="stat-row"><span>✅ Contas criadas</span><span class="val ok" id="brCreated">—</span></div>
        <div class="stat-row"><span>🔗 Contas ligadas (email)</span><span class="val warn" id="brLinked">—</span></div>
        <div class="stat-row"><span>❌ Erros</span><span class="val err" id="brErrors">—</span></div>
    </div>
    <div class="modal-foot" id="bulkFooter">
        <button type="button" class="btn-cancel" onclick="closeOverlay('bulkUsersOverlay')">Cancelar</button>
        <button type="button" class="btn-primary" id="bulkRunBtn" onclick="runBulkUsers()">▶ Executar</button>
    </div>
</div>
</div>

<!-- ══ Modal: Associação rápida ao portal ══ -->
<div class="overlay" id="quickAssocOverlay">
<div class="modal assoc-quick-modal">
    <div class="modal-title">🔗 Associar ao Portal</div>
    <p class="sub" id="qaSubtitle">Selecione o utilizador do sistema a associar a este funcionário.</p>
    <select id="qaUserSelect">
        <option value="">— Sem utilizador associado —</option>
    </select>
    <p class="hint">Apenas utilizadores com perfil <strong>Employee</strong> são listados.</p>
    <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeOverlay('quickAssocOverlay')">Cancelar</button>
        <button type="button" class="btn-primary" id="qaSubmitBtn" onclick="submitQuickAssoc()">Guardar</button>
    </div>
</div>
</div>

<!-- ══ Modal: Confirmar exclusão ══ -->
<div class="overlay" id="delOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem">🗑️</div>
    <div class="modal-title" style="margin-top:10px">Excluir Funcionário</div>
    <p id="delMsg">Tem certeza que deseja excluir este funcionário? Esta ação não pode ser desfeita.</p>
    <div class="modal-foot" style="justify-content:center">
        <button class="btn-cancel" onclick="closeOverlay('delOverlay')">Cancelar</button>
        <button class="btn-danger" onclick="confirmDelete()">Excluir</button>
    </div>
</div>
</div>

@endsection

@section('scripts')
<script>
window.EMP_CONFIG = {
    docsemPingUrl:    '{{ route("docsem.ping") }}',
    openDocsEmOnLoad: {{ (session('docsem_sucesso') || session('docsem_erro')) ? 'true' : 'false' }},
    logoUri: 'data:image/jpeg;base64,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ZMqgcgk+iQvzLLo3YQQ29dQYZYyCaDIOwRDmkxNitdqAxiBq1xKEQrEoYsGEoKMSOwYBBg3bOvwh9UFARuHUjtiXAUKDjAGwUr5l2mKjh84/BgMm7c9jnmEmRWx5h4cKmEOaL3lPUsCIQ4dBcGDtA8BJAOmD/A/kAPMhiASJQQmLV0JOsNKY+AhROIKIcHofpCozoLbOyoZCVgk0E7LlZloR2QYHDvLhjvadQQj3Kd0NQ2tEAuExEWECZUzrHcTzsiDlZEE2xKD7k0gzALEZxCUhjYLPaZRHZbLCBeRUEAWOMgKvFwAtdqAqH6xU1/uoG4EI9HzDLtlOp7opDVEbUhw4ZvWhQHxAbEKFsQm2RLNehL7Kj8hepJ/mmDjkQCNHQeGIhQ3Bl6s4SFgIzk8IDEDFrO4CJGeLnvyzufONGPfY5Snaadx0AmidRPqaKnV5jjpMh3A7EOGT/wCLAcwjQSkcwkYuZKzZa6/EHggACHKhyEABbgAfpFyVw0UuJTiRXgXXzMuQSAgvf6vYQB50l5d/u4OAncAIJHXEsgxgCuyeO4BB72rCXiNtEB4H+YtCphYqkGxWZv8Ae/dFczSsFLw6QABdh3/Wn//aAAwDAQACAAMAAAAQ888888888888888888888888888888888888888888887x+8888888887gxE888888888qr3h8888888888uNe888888AQ0qEcOym66888FEGT7YvDd888888888888888888888888888888888888888888888888888888//EACQRAQABAwMEAgMAAAAAAAAAAAERACFRMWGRMEFQgUChweHw/9oACAEDAQE/EPGDL1di7+jdgrXEPb6FeBoI0j0i0AC8VMJNCol1JgNYNDYpjQhMWoCMDRNhI+wRzbpAr2JzUHogSZEIbt3JtFGyGEaAPo0oAaRK2UgOFeM9OYLkPyMj7Ldq0FrYB5v9RUWoP67l3+Kz2omppOFThU4VOFThRv5f/8QAIxEBAQABAwIHAQAAAAAAAAAAAREAITAxUHEQIEFRYYGR8P/aAAgBAgEBPxDpiDTLORwRKbSwuW5K48DNF/MaRkZ9znaSiZbhj2xpaBJObg3rTsvpttNNHKcuACHnN5nhcGtTL7M+F++CtcY6sDP5OVNeer//xAAoEAEBAAICAgICAQMFAAAAAAABEQAhMUFRYXGBQJGhIHDwEDBQscH/2gAIAQEAAT8Q/tzpgA0HjyrwBtdGFnI8nyIDsXEcin5Qr6q0+XOSEgGPdLfnnrDV/Eka/XgUfNp+KL3I1WgcE0XWnjdIEIf6sGFx4FshxCVwyJhooTbTavYfR+GqblfHk+EP1mxZx4sWQqiS1zM7mJQ+WOg2vXeHnIR5VAEgYTUTUz/JlMXS7lzY+jpdbxVRsvHjD1XoyA/ACa9v4aDJQ26IPblPW8uYW6q8FL3zTx1LkiNAqS8l1iKjP8H1BNAIo3ygeOwcAY96Y4otRBIGofBRfWGmhbw6l+GnpVAojyJhJAC6ooewhyj1q/51/wCYRKeAgfWCwB4CYImxDHSWdco61LvH/FX2qVrtqZeg/Ed6Imv4fYefpprCmupZetwOKq8uB5v7n4Io/vAXXHR6Rn6Z7yO2sB49r1G3+D/hX5rCKgkGhuusezq04c2wLB1vpBBZsAwosMEpAm03h7w67TmquCzFpUpIS7yzl5go/rYHI5up/QwTiCGlI4bdJZlsUNa+S8p7w86JwUPvEW0gLm2GynehT2A8UmmYkPsdVnkk7JVy04YLIEcMbFLSmP8Aqh74WrvOOUMB1tR0oCNDhRtKbMZfwKaqt8tOeNYUQNQ92QoRpmD0qwkEnIx1Q8pkw6pJaiURRPJzaQL22uy5J1cKUzwiErso+T2LJkhRXkhMoEWzUwcFmloibia9T+je4tArT7TXxjSbOR8Fy8B04cw8irUpp9ZfNyKW5yV+8tOhOmNrUAfrCnRjGmgjCA6fYDkJ2hETkAMNvuaxzDjx9JGNmXxFcT06A9hlNY9v6QKJSGvwICEaO9vxe0rjtHmQXZOXEQhaQaAA8RrbLTWO8LuTw5VDR1v2A2FS6AMYFV7JUrOWi/DELPLTSvodDN/KDSGXoUOUZyyg4JCIookYN94fLd6VNg2OHVwbb1RES0RR1XdgMaXHbQFlShs0x4W7FFRZZeqk6s/qUBVgd4mEyoEDe79P6cHW2M0eXPHvD0MKINM3dSo7VOTnrIYkCBtxfGNAxMCoCz5g/rBUIpB2+v2fvB1BErprw+cSSAKKEJzv1lfEuhobUz4SUq1ePjEArUYnlPHvF1gJWJuTf2fszY0xVmTe/wCH9OAC0tOLJT7P3iMsaAB8a/2SZsCMzaPyCpYs3yfUPduS8E0DViSk7nbmz4aKMFk35w6mMYJQE8GfeA0x6VmzQYKLdZESkUEAFAMhNajzzlEOHFBwyS6rW/RgGlmIQm4PIupymWdo5RQAAbpseMVHNSiAb2Irh+bkO2mBRoWqg4LCYrgteHgd3e2+f5x265oRJOudd+31EZlLBKMpxY9f+3TiGGoGiXbRbvecvAAIIENGtf20/9k=',
};
</script>
@vite(['resources/js/pages/employees.js'])
@endsection
