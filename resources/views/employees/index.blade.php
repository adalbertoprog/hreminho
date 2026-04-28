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

/* ── Filters ── */
.filters { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
.f-input { flex:1; min-width:160px; background:var(--bg-card); border:1px solid var(--border); border-radius:9px; padding:9px 13px; color:var(--text-primary); font-size:.86rem; font-family:inherit; }
.f-input:focus { outline:none; border-color:var(--accent); }
.btn-filter { padding:9px 18px; border-radius:9px; background:rgba(99,102,241,.15); border:1px solid rgba(99,102,241,.3); color:var(--accent-light); cursor:pointer; font-size:.86rem; font-weight:600; }
.btn-reset  { padding:9px 14px; border-radius:9px; background:rgba(255,255,255,.05); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; font-size:.86rem; }
.btn-reset:hover { color:var(--text-primary); }

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
</style>
@endsection

@section('content')

<div class="toolbar">
    <h2>👥 Funcionários</h2>
    <button class="btn-primary" onclick="openCreate()">+ Novo Funcionário</button>
</div>

<!-- Filtros -->
<div class="filters" id="filterBar">
    <input id="fSearch" class="f-input" placeholder="🔍 Nome, email ou código..." onkeydown="if(event.key==='Enter')applyFilters()">
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
                    <th>Funcionário</th>
                    <th>Setor</th>
                    <th>Departamento</th>
                    <th>Função</th>
                    <th>Admissão</th>
                    <th>Anos de casa</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="empBody">
                <tr class="state-row"><td colspan="8"><span class="spinner"></span>A carregar...</td></tr>
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
                        <div style="font-size:.72rem;color:var(--text-muted);margin-top:5px">JPG, PNG ou GIF · máx. 2MB</div>
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
            <div class="fg"><label>Tipo de Contrato</label><input name="contract_type" placeholder="full-time, part-time..."></div>
            <div class="fg"><label>Data de Término</label><input name="end_date" type="date"></div>
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
const API  = '/api/v1';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let state = {page:1,search:'',department_id:'',sector_id:'',position_id:'',status:''};
let editId=null, deleteId=null;
let depts=[], positions=[], sectors=[];
let photoBase64=null;

/* ── Photo ── */
function handlePhotoChange(input){
    const file=input.files[0]; if(!file) return;
    if(file.size>2*1024*1024){alert('Max 2MB');input.value='';return;}
    const r=new FileReader();
    r.onload=e=>{photoBase64=e.target.result;setPhotoPreview(photoBase64,null);};
    r.readAsDataURL(file);
}
function clearPhoto(){
    photoBase64=null;
    document.getElementById('photoFile').value='';
    document.getElementById('photoImg').style.display='none';
    document.getElementById('photoImg').src='';
    document.getElementById('photoInitials').style.display='';
    document.getElementById('photoClearBtn').style.display='none';
}
function setPhotoPreview(src,initials){
    const img=document.getElementById('photoImg');
    const ini=document.getElementById('photoInitials');
    const btn=document.getElementById('photoClearBtn');
    if(src){img.src=src;img.style.display='block';ini.style.display='none';btn.style.display='inline-flex';}
    else{img.style.display='none';img.src='';ini.textContent=initials||'?';ini.style.display='';btn.style.display='none';}
}

/* ── Helpers ── */
async function apiFetch(method,path,body){
    const opts={method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}};
    if(body)opts.body=JSON.stringify(body);
    const r=await fetch(API+path,opts);
    if(!r.ok){const e=await r.json().catch(()=>({message:'Erro'}));throw e;}
    return r.status===204?null:r.json();
}
function toast(msg,type='ok'){
    const w=document.getElementById('toastWrap');
    const t=document.createElement('div');t.className=`toast toast-${type}`;t.textContent=msg;
    w.appendChild(t);setTimeout(()=>t.remove(),3500);
}
function openOverlay(id){document.getElementById(id).classList.add('open');}
function closeOverlay(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');}));

/* ── Boot ── */
async function boot(){
    const [d,p,s]=await Promise.all([
        apiFetch('GET','/departments?per_page=200').catch(()=>({data:[]})),
        apiFetch('GET','/positions?per_page=200').catch(()=>({data:[]})),
        apiFetch('GET','/sectors?per_page=200').catch(()=>({data:[]})),
    ]);
    depts=d.data??[];positions=p.data??[];sectors=s.data??[];
    ['fDept','fDeptModal'].forEach(id=>{const el=document.getElementById(id);if(!el)return;depts.forEach(x=>el.innerHTML+=`<option value="${x.id}">${x.department}</option>`);});
    ['fPos','fPosModal'].forEach(id=>{const el=document.getElementById(id);if(!el)return;positions.forEach(x=>el.innerHTML+=`<option value="${x.id}">${x.position}</option>`);});
    ['fSector','fSecModal'].forEach(id=>{const el=document.getElementById(id);if(!el)return;sectors.forEach(x=>el.innerHTML+=`<option value="${x.id}">${x.sector}</option>`);});
    loadEmployees();
}

/* ── Load & Render ── */
async function loadEmployees(){
    const tbody=document.getElementById('empBody');
    tbody.innerHTML='<tr class="state-row"><td colspan="8"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('pagBar').style.display='none';
    const params={page:state.page,per_page:15};
    if(state.search)        params.search=state.search;
    if(state.department_id) params.department_id=state.department_id;
    if(state.position_id)   params.position_id=state.position_id;
    if(state.sector_id)     params.sector_id=state.sector_id;
    if(state.status)        params.status=state.status;
    try{
        const res=await fetch(`${API}/employees?${new URLSearchParams(params)}`,{headers:{Accept:'application/json'}});
        const json=await res.json();
        renderTable(json.data??[]);renderPag(json.meta);
    }catch{tbody.innerHTML='<tr class="state-row"><td colspan="8">Erro ao carregar.</td></tr>';}
}

const BG=['#6366f1','#8b5cf6','#06b6d4','#22c55e','#f59e0b','#ef4444','#ec4899'];
const empMap={};
function yearsAgo(dateStr){
    if(!dateStr)return'—';
    const diff=(new Date()-new Date(dateStr))/(1000*60*60*24*365.25);
    return diff<1?'< 1 ano':Math.floor(diff)+' ano(s)';
}
function renderTable(rows){
    const tbody=document.getElementById('empBody');
    if(!rows.length){tbody.innerHTML='<tr class="state-row"><td colspan="8">Nenhum funcionário encontrado.</td></tr>';return;}
    rows.forEach(emp=>empMap[emp.id]=emp);
    tbody.innerHTML=rows.map((emp,i)=>{
        const ini=((emp.first_name?.[0]??'')+(emp.last_name?.[0]??'')).toUpperCase();
        const bg=BG[i%BG.length];
        const av=emp.photo
            ?`<div class="avatar"><img src="${emp.photo}" alt="${ini}"></div>`
            :`<div class="avatar" style="background:${bg}">${ini}</div>`;
        const sb={active:'<span class="badge badge-active">Ativo</span>',inactive:'<span class="badge badge-inactive">Inativo</span>',terminated:'<span class="badge badge-terminated">Desligado</span>'}[emp.status]??`<span class="badge">${emp.status}</span>`;
        return`<tr>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    ${av}
                    <div>
                        <span class="emp-name-wrap"
                            onmouseenter="showHoverCard(event,${emp.id})"
                            onmouseleave="hideHoverCard()">
                            <span class="emp-name">${emp.full_name}</span>
                        </span>
                        <div class="emp-sub">${emp.code}</div>
                    </div>
                </div>
            </td>
            <td>${emp.sector?.sector??'—'}</td>
            <td>${emp.department?.department??'—'}</td>
            <td>${emp.position?.position??'—'}</td>
            <td style="color:var(--text-muted);font-size:.82rem">${emp.hire_date?new Date(emp.hire_date+'T00:00:00').toLocaleDateString('pt-PT'):'—'}</td>
            <td style="font-size:.82rem;color:var(--text-muted)">${yearsAgo(emp.hire_date)}</td>
            <td>${sb}</td>
            <td style="white-space:nowrap">
                <button class="btn-sm btn-view" onclick="openView(${emp.id})">👁 Ver</button>
                <button class="btn-sm btn-edit" onclick="openEdit(${emp.id})">✏️ Editar</button>
                <button class="btn-sm btn-del"  onclick="openDeleteModal(${emp.id},'${ini}')">🗑</button>
            </td></tr>`;
    }).join('');
}

function renderPag(meta){
    if(!meta)return;
    document.getElementById('pagBar').style.display='flex';
    document.getElementById('pagInfo').textContent=`${meta.from??0}–${meta.to??0} de ${meta.total}`;
    const btns=document.getElementById('pagBtns');btns.innerHTML='';
    const prev=document.createElement('button');prev.textContent='‹';prev.disabled=meta.current_page<=1;
    prev.onclick=()=>{state.page=meta.current_page-1;loadEmployees();};btns.appendChild(prev);
    const s2=Math.max(1,meta.current_page-3),e2=Math.min(meta.last_page,s2+6);
    for(let i=s2;i<=e2;i++){const b=document.createElement('button');b.textContent=i;if(i===meta.current_page)b.classList.add('active');b.onclick=(p=>()=>{state.page=p;loadEmployees();})(i);btns.appendChild(b);}
    const next=document.createElement('button');next.textContent='›';next.disabled=meta.current_page>=meta.last_page;
    next.onclick=()=>{state.page=meta.current_page+1;loadEmployees();};btns.appendChild(next);
}

/* ── Filters ── */
function applyFilters(){
    state.search=document.getElementById('fSearch').value.trim();
    state.department_id=document.getElementById('fDept').value;
    state.position_id=document.getElementById('fPos').value;
    state.sector_id=document.getElementById('fSector').value;
    state.status=document.getElementById('fStatus').value;
    state.page=1;loadEmployees();
}
function resetFilters(){
    ['fSearch','fDept','fPos','fSector','fStatus'].forEach(id=>document.getElementById(id).value='');
    state={page:1,search:'',department_id:'',sector_id:'',position_id:'',status:''};loadEmployees();
}
document.getElementById('fSearch').addEventListener('keydown',e=>{if(e.key==='Enter')applyFilters();});

/* ── Hover Card ── */
let hoverTimer=null, activeEmpId=null;
function showHoverCard(event,empId){const emp=empMap[empId];if(!emp)return;
    clearTimeout(hoverTimer);
    const card=document.getElementById('profileCard');
    // Avatar
    const ini=((emp.first_name?.[0]??'')+(emp.last_name?.[0]??'')).toUpperCase();
    const av=document.getElementById('pcAvatar');
    av.innerHTML=emp.photo?`<img src="${emp.photo}" alt="${ini}">`:`<span>${ini}</span>`;
    // Info
    document.getElementById('pcName').textContent=emp.full_name;
    document.getElementById('pcSub').textContent=`${emp.position?.position??'—'} · ${emp.department?.department??'—'}`;
    document.getElementById('pcDob').textContent=emp.date_of_birth?new Date(emp.date_of_birth+'T00:00:00').toLocaleDateString('pt-PT'):'—';
    document.getElementById('pcAge').textContent=emp.date_of_birth?Math.floor((new Date()-new Date(emp.date_of_birth))/(1000*60*60*24*365.25))+' anos':'—';
    document.getElementById('pcTenure').textContent=yearsAgo(emp.hire_date);
    document.getElementById('pcContract').textContent=emp.contract_type||'—';
    document.getElementById('pcWorkLocation').textContent=emp.work_location||'—';
    document.getElementById('pcAddress').textContent=emp.address||'—';
    document.getElementById('pcTrainings').innerHTML='<div class="pc-loading">A carregar...</div>';
    // Position
    positionCard(card,event);
    card.classList.add('visible');
    // Load trainings
    if(activeEmpId!==emp.id){
        activeEmpId=emp.id;
        apiFetch('GET',`/enrollments?employee_id=${emp.id}&per_page=50`).then(res=>{
            const rows=res.data??[];
            if(!rows.length){document.getElementById('pcTrainings').innerHTML='<div class="pc-tr-empty">Sem formações registadas.</div>';return;}
            const sC={enrolled:'pc-tr-enrolled',completed:'pc-tr-completed',failed:'pc-tr-failed'};
            const sL={enrolled:'Inscrito',completed:'Concluído',failed:'Reprovado'};
            document.getElementById('pcTrainings').innerHTML=rows.slice(0,4).map(r=>`
                <div class="pc-training-item">
                    <span class="pc-tr-name">${r.training?.title??'—'}</span>
                    <span class="pc-tr-status ${sC[r.status]??''}">${sL[r.status]??r.status}</span>
                </div>`).join('')+(rows.length>4?`<div class="pc-tr-empty">+${rows.length-4} mais...</div>`:'');
        }).catch(()=>{document.getElementById('pcTrainings').innerHTML='<div class="pc-tr-empty">Erro ao carregar.</div>';});
    }
}
function positionCard(card,event){
    const vw=window.innerWidth,vh=window.innerHeight;
    let x=event.clientX+16,y=event.clientY+16;
    const w=320,h=380;
    if(x+w>vw)x=event.clientX-w-8;
    if(y+h>vh)y=vh-h-8;
    card.style.left=x+'px';card.style.top=y+'px';
}
function hideHoverCard(){
    hoverTimer=setTimeout(()=>{
        document.getElementById('profileCard').classList.remove('visible');
        activeEmpId=null;
    },200);
}
document.getElementById('profileCard').addEventListener('mouseenter',()=>clearTimeout(hoverTimer));
document.getElementById('profileCard').addEventListener('mouseleave',()=>hideHoverCard());

/* ── Create / Edit ── */
function openCreate(){
    editId=null;document.getElementById('empForm').reset();clearPhoto();setPhotoPreview(null,'?');
    document.getElementById('formTitle').textContent='Novo Funcionário';
    document.getElementById('formSubmitBtn').textContent='Criar Funcionário';
    openOverlay('formOverlay');
}
function openEdit(empId){const emp=empMap[empId];if(!emp)return;
    editId=emp.id;document.getElementById('empForm').reset();
    const form=document.getElementById('empForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    set('code',emp.code);set('first_name',emp.first_name);set('last_name',emp.last_name);
    set('email',emp.email);set('phone',emp.phone);set('date_of_birth',emp.date_of_birth);
    set('gender',emp.gender);set('nationality',emp.nationality);set('address',emp.address);
    set('work_location',emp.work_location);set('position_id',emp.position_id);
    set('department_id',emp.department_id);set('sector_id',emp.sector_id??'');
    set('hire_date',emp.hire_date);set('status',emp.status);set('contract_type',emp.contract_type);set('end_date',emp.end_date);
    const ini=((emp.first_name?.[0]??'')+(emp.last_name?.[0]??'')).toUpperCase();
    photoBase64=null;setPhotoPreview(emp.photo??null,ini);
    document.getElementById('formTitle').textContent='Editar Funcionário';
    document.getElementById('formSubmitBtn').textContent='Guardar Alterações';
    openOverlay('formOverlay');
}
async function submitForm(e){
    e.preventDefault();
    const btn=document.getElementById('formSubmitBtn');btn.disabled=true;btn.textContent='A guardar...';
    const data={};new FormData(document.getElementById('empForm')).forEach((v,k)=>{if(v!=='')data[k]=v;});
    if(photoBase64)data.photo=photoBase64;
    try{
        if(editId)await apiFetch('PUT',`/employees/${editId}`,data);
        else      await apiFetch('POST','/employees',data);
        toast(editId?'Funcionário atualizado!':'Funcionário criado!','ok');
        closeOverlay('formOverlay');loadEmployees();
    }catch(err){
        const msg=err.errors?Object.values(err.errors).flat().join('\n'):(err.message??'Erro.');
        toast(msg,'err');
    }finally{btn.disabled=false;btn.textContent=editId?'Guardar Alterações':'Criar Funcionário';}
}

/* ── Delete ── */
function openDeleteModal(id,name){
    deleteId=id;
    document.getElementById('delMsg').textContent=`Confirmar exclusão de "${name}"?`;
    openOverlay('delOverlay');
}
async function confirmDelete(){
    try{await apiFetch('DELETE',`/employees/${deleteId}`);toast('Funcionário excluído.','ok');closeOverlay('delOverlay');loadEmployees();}
    catch(err){toast(err.message??'Erro.','err');}
}

/* ── View Modal ── */
function switchTab(panelId, btn){
    document.querySelectorAll('.view-tab-panel').forEach(p=>p.classList.remove('active'));
    document.querySelectorAll('.view-tab').forEach(b=>b.classList.remove('active'));
    document.getElementById(panelId).classList.add('active');
    btn.classList.add('active');
}

const genderLabel = {male:'Masculino', female:'Feminino', other:'Outro'};
const statusLabel  = {active:'Ativo', inactive:'Inativo', terminated:'Desligado'};
const statusBadge  = {
    active:     '<span class="badge badge-active">Ativo</span>',
    inactive:   '<span class="badge badge-inactive">Inativo</span>',
    terminated: '<span class="badge badge-terminated">Desligado</span>',
};

async function openView(empId){
    const emp = empMap[empId];
    if(!emp) return;

    // Reset tabs
    document.querySelectorAll('.view-tab-panel').forEach(p=>p.classList.remove('active'));
    document.querySelectorAll('.view-tab').forEach(b=>b.classList.remove('active'));
    document.getElementById('vTabInfo').classList.add('active');
    document.querySelector('.view-tab').classList.add('active');
    document.getElementById('vTrainingsContent').innerHTML='<div class="tr-loading">⏳ A carregar formações…</div>';

    // Avatar
    const ini = ((emp.first_name?.[0]??'')+(emp.last_name?.[0]??'')).toUpperCase();
    const av  = document.getElementById('vAvatar');
    if(emp.photo){
        av.innerHTML=`<img src="${emp.photo}" alt="${ini}">`;
    } else {
        av.innerHTML=`<span>${ini}</span>`;
    }

    // Botão editar no topo
    document.getElementById('vEditBtn').onclick = ()=>{ closeOverlay('viewOverlay'); openEdit(empId); };

    // Cabeçalho
    document.getElementById('vName').textContent = emp.full_name;
    document.getElementById('vSub').textContent  = [emp.position?.position, emp.department?.department].filter(Boolean).join(' · ') || '—';

    // Dados pessoais
    const fmt = d => d ? new Date(d+'T00:00:00').toLocaleDateString('pt-PT') : '—';
    document.getElementById('vCode').textContent        = emp.code || '—';
    document.getElementById('vGender').textContent      = genderLabel[emp.gender] ?? (emp.gender || '—');
    document.getElementById('vDob').textContent         = fmt(emp.date_of_birth);
    document.getElementById('vAge').textContent         = emp.date_of_birth ? Math.floor((new Date()-new Date(emp.date_of_birth+'T00:00:00'))/(1000*60*60*24*365.25))+' anos' : '—';
    document.getElementById('vNationality').textContent = emp.nationality || '—';
    document.getElementById('vPhone').textContent       = emp.phone || '—';
    document.getElementById('vAddress').textContent     = emp.address || '—';
    document.getElementById('vEmail').textContent       = emp.email || '—';

    // Contrato
    document.getElementById('vDept').textContent         = emp.department?.department || '—';
    document.getElementById('vSector').textContent       = emp.sector?.sector || '—';
    document.getElementById('vPosition').textContent     = emp.position?.position || '—';
    document.getElementById('vHireDate').textContent     = fmt(emp.hire_date);
    document.getElementById('vTenure').textContent       = yearsAgo(emp.hire_date);
    document.getElementById('vStatus').innerHTML         = statusBadge[emp.status] ?? `<span class="badge">${emp.status}</span>`;
    document.getElementById('vContract').textContent     = emp.contract_type || '—';
    document.getElementById('vEndDate').textContent      = fmt(emp.end_date);
    document.getElementById('vWorkLocation').textContent = emp.work_location || '—';

    openOverlay('viewOverlay');

    // Carregar formações em background
    try{
        const res  = await apiFetch('GET',`/enrollments?employee_id=${empId}&per_page=100`);
        const rows = res.data ?? [];
        if(!rows.length){
            document.getElementById('vTrainingsContent').innerHTML=`<div class="tr-empty">Sem formações registadas para este funcionário.</div>`;
            return;
        }
        const sC={enrolled:'badge-enrolled',completed:'badge-completed',failed:'badge-failed'};
        const sL={enrolled:'Inscrito',completed:'Concluído',failed:'Reprovado'};
        const tbody = rows.map(r=>{
            const scoreHtml = r.score != null
                ? `<div class="score-bar-wrap"><div class="score-bar"><div class="score-bar-fill" style="width:${r.score}%"></div></div><span style="font-size:.75rem;color:var(--text-muted);min-width:28px">${r.score}%</span></div>`
                : '—';
            return `<tr>
                <td style="font-weight:500">${r.training?.title??'—'}</td>
                <td style="color:var(--text-muted);font-size:.8rem">${r.training?.provider??'—'}</td>
                <td><span class="badge ${sC[r.status]??''}">${sL[r.status]??r.status}</span></td>
                <td style="min-width:110px">${scoreHtml}</td>
                <td style="color:var(--text-muted);font-size:.8rem">${r.start_date?new Date(r.start_date+'T00:00:00').toLocaleDateString('pt-PT'):'—'}</td>
                <td style="color:var(--text-muted);font-size:.8rem">${r.completion_date?new Date(r.completion_date+'T00:00:00').toLocaleDateString('pt-PT'):'—'}</td>
            </tr>`;
        }).join('');

        const completed = rows.filter(r=>r.status==='completed').length;
        const enrolled  = rows.filter(r=>r.status==='enrolled').length;
        const failed    = rows.filter(r=>r.status==='failed').length;

        document.getElementById('vTrainingsContent').innerHTML = `
            <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap">
                <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);border-radius:10px;padding:10px 18px;text-align:center">
                    <div style="font-size:1.4rem;font-weight:800;color:#22c55e">${completed}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Concluídas</div>
                </div>
                <div style="background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);border-radius:10px;padding:10px 18px;text-align:center">
                    <div style="font-size:1.4rem;font-weight:800;color:var(--accent-light)">${enrolled}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Em curso</div>
                </div>
                <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:10px 18px;text-align:center">
                    <div style="font-size:1.4rem;font-weight:800;color:#ef4444">${failed}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Reprovadas</div>
                </div>
            </div>
            <div style="overflow-x:auto">
                <table class="tr-table">
                    <thead><tr>
                        <th>Formação</th><th>Provedor</th><th>Estado</th><th>Pontuação</th><th>Início</th><th>Conclusão</th>
                    </tr></thead>
                    <tbody>${tbody}</tbody>
                </table>
            </div>`;
    } catch(e){
        document.getElementById('vTrainingsContent').innerHTML=`<div class="tr-empty">Erro ao carregar formações.</div>`;
    }
}
boot();
</script>
@endsection
