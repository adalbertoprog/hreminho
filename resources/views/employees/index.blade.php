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
.avatar { width:33px; height:33px; border-radius:50%; background:linear-gradient(135deg,var(--accent),#a78bfa); display:inline-flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700; color:#fff; flex-shrink:0; }
.emp-name { font-weight:600; font-size:.875rem; }
.emp-sub  { font-size:.74rem; color:var(--text-muted); }

/* Status badge */
.badge { display:inline-block; padding:3px 9px; border-radius:6px; font-size:.73rem; font-weight:700; }
.badge-active     { background:rgba(34,197,94,.15);  color:#22c55e; }
.badge-inactive   { background:rgba(245,158,11,.15); color:#f59e0b; }
.badge-terminated { background:rgba(239,68,68,.12);  color:#ef4444; }

/* Row actions */
.btn-sm { padding:4px 11px; border-radius:7px; font-size:.76rem; font-weight:600; cursor:pointer; border:none; transition:.15s; }
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
.tr-table thead th { padding:9px 12px; text-align:left; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted); border-bottom:1px solid var(--border); background:rgba(255,255,255,.02); }
.tr-table tbody td { padding:10px 12px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
.tr-table tbody tr:last-child td { border-bottom:none; }
.tr-table tbody tr:hover td { background:rgba(255,255,255,.02); }
.score-bar-wrap { display:flex; align-items:center; gap:8px; }
.score-bar { flex:1; height:6px; border-radius:3px; background:rgba(255,255,255,.08); overflow:hidden; }
.score-bar-fill { height:100%; border-radius:3px; background:var(--accent); }
.tr-empty { text-align:center; padding:40px; color:var(--text-muted); }
.badge-enrolled  { background:rgba(99,102,241,.2);  color:var(--accent-light); }
.badge-completed { background:rgba(34,197,94,.15);  color:#22c55e; }
.badge-failed    { background:rgba(239,68,68,.12);  color:#ef4444; }
.tr-loading { text-align:center; padding:40px; color:var(--text-muted); }
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
    <select id="fPos"    class="f-input" style="max-width:180px"><option value="">Todos os cargos</option></select>
    <select id="fStatus" class="f-input" style="max-width:140px">
        <option value="">Todos status</option>
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
                    <th>Cargo</th>
                    <th>Admissão</th>
                    <th>Anos de casa</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="empBody">
                <tr class="state-row"><td colspan="7"><span class="spinner"></span>A carregar...</td></tr>
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
            <div class="fg"><label>Código *</label><input name="code" required placeholder="EMP-001"></div>
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
            <div class="fg full"><label>Local de trabalho</label><input name="work_location" placeholder="Ex: Sede, Filial Norte, Remoto…"></div>

            <div class="section-sep">Contrato & Função</div>
            <div class="fg"><label>Departamento *</label><select name="department_id" id="fDeptModal" required><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Cargo *</label><select name="position_id" id="fPosModal" required><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Setor</label><select name="sector_id" id="fSecModal"><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Status</label>
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

<!-- ══ Modal: Treinamentos do Funcionário ══ -->
<div class="overlay" id="trainOverlay">
<div class="modal training-modal">
    <div class="modal-title">🎓 Treinamentos do Funcionário</div>
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
                    <th>Treinamento</th>
                    <th>Provedor</th>
                    <th>Status</th>
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
const API   = '/api/v1';
const CSRF  = document.querySelector('meta[name="csrf-token"]').content;

let state = { page:1, search:'', department_id:'', sector_id:'', position_id:'', status:'' };
let editId = null, deleteId = null;
let depts=[], positions=[], sectors=[];

/* ── Boot ── */
async function boot() {
    const [d, p, s] = await Promise.all([
        api('GET', '/departments?per_page=200'),
        api('GET', '/positions?per_page=200'),
        api('GET', '/sectors?all=1').catch(()=>({data:[]})),
    ]);
    depts     = d.data ?? [];
    positions = p.data ?? [];
    sectors   = s.data ?? [];
    fillSelect('fDept',    depts,     'id','department', 'Todos os depts.');
    fillSelect('fSector',  sectors,   'id','sector',     'Todos os setores');
    fillSelect('fPos',     positions, 'id','position',   'Todos os cargos');
    fillSelect('fDeptModal', depts,   'id','department', '— Selecionar —');
    fillSelect('fPosModal',  positions,'id','position',  '— Selecionar —');
    fillSelect('fSecModal',  sectors,  'id','sector',    '— Selecionar —');
    loadTable();
}

/* ── API helper ── */
async function api(method, path, body) {
    const opts = { method, headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'} };
    if(body) opts.body = JSON.stringify(body);
    const r = await fetch(API + path, opts);
    if(!r.ok) { const e = await r.json().catch(()=>({message:'Erro'})); throw e; }
    return r.status===204 ? null : r.json();
}

/* ── Load table ── */
async function loadTable() {
    const tbody = document.getElementById('empBody');
    tbody.innerHTML = '<tr class="state-row"><td colspan="7"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('pagBar').style.display='none';
    const q = new URLSearchParams({ page:state.page, per_page:15 });
    if(state.search)        q.set('search',        state.search);
    if(state.department_id) q.set('department_id', state.department_id);
    if(state.sector_id)     q.set('sector_id',     state.sector_id);
    if(state.position_id)   q.set('position_id',   state.position_id);
    if(state.status)        q.set('status',        state.status);

    try {
        const res = await fetch(`${API}/employees?${q}`, {headers:{Accept:'application/json'}});
        const json = await res.json();
        renderTable(json.data ?? []);
        renderPag(json.meta);
    } catch(e) {
        tbody.innerHTML = '<tr class="state-row"><td colspan="7">⚠️ Erro ao carregar dados.</td></tr>';
    }
}

function renderTable(rows) {
    const tbody = document.getElementById('empBody');
    if(!rows.length) { tbody.innerHTML='<tr class="state-row"><td colspan="7">Nenhum funcionário encontrado.</td></tr>'; return; }
    tbody.innerHTML = rows.map(emp => {
        const initials = (emp.first_name[0]??'') + (emp.last_name[0]??'');
        const badgeClass = { active:'badge-active', inactive:'badge-inactive', terminated:'badge-terminated' }[emp.status] ?? 'badge-inactive';
        const badgeLabel = { active:'Ativo', inactive:'Inativo', terminated:'Desligado' }[emp.status] ?? emp.status;
        const hireDate = emp.hire_date ? new Date(emp.hire_date+'T00:00:00').toLocaleDateString('pt-PT') : '—';
        const tenure = emp.hire_date ? Math.floor((Date.now() - new Date(emp.hire_date+'T00:00:00').getTime()) / (1000*60*60*24*365.25)) : null;
        const tenureLabel = tenure !== null ? (tenure === 1 ? '1 ano' : tenure + ' anos') : '—';
        const empJson = JSON.stringify(emp).replace(/"/g,'&quot;');
        return `<tr>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    <div class="avatar">${initials.toUpperCase()}</div>
                    <div>
                        <div class="emp-name-wrap" onmouseenter="showProfile(event, this)" onmouseleave="hideProfile()" data-emp="${empJson}">
                            <div class="emp-name">${emp.full_name}</div>
                        </div>
                        <div class="emp-sub">${emp.code}</div>
                    </div>
                </div>
            </td>
            <td style="color:var(--text-muted)">${emp.sector?.sector ?? '—'}</td>
            <td>${emp.department?.department ?? '—'}</td>
            <td>${emp.position?.position ?? '—'}</td>
            <td style="color:var(--text-muted)">${hireDate}</td>
            <td style="color:var(--text-muted)">${tenureLabel}</td>
            <td><span class="badge ${badgeClass}">${badgeLabel}</span></td>
            <td style="white-space:nowrap">
                <button class="btn-sm btn-train" onclick="openTrainings(${emp.id},'${emp.full_name.replace(/'/g,"\\'")}','${(emp.first_name[0]??'')+(emp.last_name[0]??'')}')">🎓</button>
                <button class="btn-sm btn-edit"  onclick='openEdit(${JSON.stringify(emp)})'>✏️ Editar</button>
                <button class="btn-sm btn-del"   onclick="openDelete(${emp.id},'${emp.full_name.replace(/'/g,"\\'")}')">🗑</button>
            </td>
        </tr>`;
    }).join('');
}

function renderPag(meta) {
    if(!meta) return;
    const bar = document.getElementById('pagBar');
    bar.style.display='flex';
    document.getElementById('pagInfo').textContent = `Exibindo ${meta.from ?? 0}–${meta.to ?? 0} de ${meta.total} funcionários`;
    const btns = document.getElementById('pagBtns');
    btns.innerHTML='';
    const prev = document.createElement('button');
    prev.textContent='‹'; prev.disabled = meta.current_page<=1;
    prev.onclick=()=>{ state.page--; loadTable(); };
    btns.appendChild(prev);

    const total = Math.min(meta.last_page, 7);
    const start = Math.max(1, meta.current_page - 3);
    const end   = Math.min(meta.last_page, start + total - 1);
    for(let i=start;i<=end;i++){
        const b=document.createElement('button');
        b.textContent=i; if(i===meta.current_page) b.classList.add('active');
        b.onclick=(()=>{ const p=i; return()=>{ state.page=p; loadTable(); }; })();
        btns.appendChild(b);
    }
    const next=document.createElement('button');
    next.textContent='›'; next.disabled = meta.current_page>=meta.last_page;
    next.onclick=()=>{ state.page++; loadTable(); };
    btns.appendChild(next);
}

/* ── Filters ── */
function applyFilters() {
    state.search        = document.getElementById('fSearch').value.trim();
    state.department_id = document.getElementById('fDept').value;
    state.sector_id     = document.getElementById('fSector').value;
    state.position_id   = document.getElementById('fPos').value;
    state.status        = document.getElementById('fStatus').value;
    state.page = 1;
    loadTable();
}
function resetFilters() {
    document.getElementById('fSearch').value='';
    document.getElementById('fDept').value='';
    document.getElementById('fSector').value='';
    document.getElementById('fPos').value='';
    document.getElementById('fStatus').value='';
    state={ page:1, search:'', department_id:'', sector_id:'', position_id:'', status:'' };
    loadTable();
}

/* ── Modal helpers ── */
function fillSelect(id, items, valKey, labelKey, placeholder) {
    const sel = document.getElementById(id);
    if(!sel) return;
    const cur = sel.value;
    sel.innerHTML = `<option value="">${placeholder}</option>` + items.map(i=>`<option value="${i[valKey]}">${i[labelKey]}</option>`).join('');
    sel.value = cur;
}
function openOverlay(id) { document.getElementById(id).classList.add('open'); }
function closeOverlay(id){ document.getElementById(id).classList.remove('open'); }

function clearForm() {
    document.getElementById('empForm').reset();
}

function openCreate() {
    editId = null;
    clearForm();
    document.getElementById('formTitle').textContent='➕ Novo Funcionário';
    document.getElementById('formSubmitBtn').textContent='Criar Funcionário';
    openOverlay('formOverlay');
}

function openEdit(emp) {
    editId = emp.id;
    clearForm();
    const form = document.getElementById('empForm');
    const set = (name,val) => { const el=form.querySelector(`[name="${name}"]`); if(el) el.value=val??''; };
    set('code',          emp.code);
    set('first_name',    emp.first_name);
    set('last_name',     emp.last_name);
    set('email',         emp.email);
    set('phone',         emp.phone);
    set('date_of_birth', emp.date_of_birth);
    set('gender',        emp.gender);
    set('nationality',   emp.nationality);
    set('address',       emp.address);
    set('work_location', emp.work_location);
    set('hire_date',     emp.hire_date);
    set('end_date',      emp.end_date);
    set('contract_type', emp.contract_type);
    set('status',        emp.status);
    set('department_id', emp.department?.id ?? '');
    set('position_id',   emp.position?.id ?? '');
    set('sector_id',     emp.sector?.id ?? '');
    document.getElementById('formTitle').textContent='✏️ Editar Funcionário';
    document.getElementById('formSubmitBtn').textContent='Guardar Alterações';
    openOverlay('formOverlay');
}

async function submitForm(e) {
    e.preventDefault();
    const btn = document.getElementById('formSubmitBtn');
    btn.disabled=true; btn.textContent='A guardar...';
    const form = document.getElementById('empForm');
    const data = {};
    new FormData(form).forEach((v,k)=>{ if(v!=='') data[k]=v; });
    try {
        if(editId) {
            await api('PUT', `/employees/${editId}`, data);
            toast('Funcionário atualizado!', 'ok');
        } else {
            await api('POST', '/employees', data);
            toast('Funcionário criado!', 'ok');
        }
        closeOverlay('formOverlay');
        loadTable();
    } catch(err) {
        toast(err.message ?? 'Erro ao guardar.', 'err');
    } finally {
        btn.disabled=false; btn.textContent = editId ? 'Guardar Alterações' : 'Criar Funcionário';
    }
}

function openDelete(id, name) {
    deleteId = id;
    document.getElementById('delMsg').textContent = `Tem certeza que deseja excluir «${name}»? Esta ação não pode ser desfeita.`;
    openOverlay('delOverlay');
}
async function confirmDelete() {
    try {
        await api('DELETE', `/employees/${deleteId}`);
        toast('Funcionário excluído.', 'ok');
        closeOverlay('delOverlay');
        loadTable();
    } catch(err) {
        toast(err.message ?? 'Erro ao excluir.', 'err');
    }
}

/* ── Toast ── */
function toast(msg, type='ok') {
    const wrap=document.getElementById('toastWrap');
    const t=document.createElement('div');
    t.className=`toast toast-${type}`; t.textContent=msg;
    wrap.appendChild(t);
    setTimeout(()=>t.remove(), 3500);
}

/* ── Profile Card ── */
let profileHideTimer = null;
let profileCache = {};

function calcAge(dob) {
    if (!dob) return '—';
    const d = new Date(dob + 'T00:00:00');
    const diff = Date.now() - d.getTime();
    return Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25)) + ' anos';
}

function calcTenure(hire) {
    if (!hire) return '—';
    const d = new Date(hire + 'T00:00:00');
    const years  = Math.floor((Date.now() - d.getTime()) / (1000 * 60 * 60 * 24 * 365.25));
    const months = Math.floor(((Date.now() - d.getTime()) % (1000 * 60 * 60 * 24 * 365.25)) / (1000 * 60 * 60 * 24 * 30.44));
    if (years === 0) return months + ' mes' + (months !== 1 ? 'es' : '');
    return years + ' ano' + (years !== 1 ? 's' : '') + (months > 0 ? ` e ${months} mes${months !== 1 ? 'es' : ''}` : '');
}

function fmtDate(d) {
    if (!d) return '—';
    return new Date(d + 'T00:00:00').toLocaleDateString('pt-PT');
}

function positionCard(card, anchorEl) {
    const rect = anchorEl.getBoundingClientRect();
    const cardW = 320, cardH = 480, margin = 10;
    let left = rect.right + margin;
    let top  = rect.top;
    if (left + cardW > window.innerWidth - margin) left = rect.left - cardW - margin;
    if (top + cardH > window.innerHeight - margin)  top  = window.innerHeight - cardH - margin;
    if (top < margin) top = margin;
    card.style.left = left + 'px';
    card.style.top  = top  + 'px';
}

async function showProfile(e, el) {
    clearTimeout(profileHideTimer);
    const emp  = JSON.parse(el.dataset.emp);
    const card = document.getElementById('profileCard');

    // Fill static data immediately
    const initials = (emp.first_name[0] ?? '') + (emp.last_name[0] ?? '');
    document.getElementById('pcAvatar').textContent  = initials.toUpperCase();
    document.getElementById('pcName').textContent    = emp.full_name;
    document.getElementById('pcSub').textContent     = (emp.position?.position ?? '') + (emp.department?.department ? ' · ' + emp.department.department : '');
    document.getElementById('pcDob').textContent     = fmtDate(emp.date_of_birth);
    document.getElementById('pcAge').textContent     = calcAge(emp.date_of_birth);
    document.getElementById('pcTenure').textContent  = calcTenure(emp.hire_date);
    document.getElementById('pcContract').textContent     = emp.contract_type   || '—';
    document.getElementById('pcWorkLocation').textContent = emp.work_location   || '—';
    document.getElementById('pcAddress').textContent      = emp.address         || '—';
    document.getElementById('pcTrainings').innerHTML = '<div class="pc-loading">⏳ A carregar…</div>';

    positionCard(card, el);
    card.classList.add('visible');

    // Load trainings (cached)
    if (!profileCache[emp.id]) {
        try {
            const res  = await fetch(`${API}/enrollments?employee_id=${emp.id}&per_page=200`, { headers:{ Accept:'application/json' } });
            const json = await res.json();
            profileCache[emp.id] = json.data ?? [];
        } catch { profileCache[emp.id] = []; }
    }

    const rows = profileCache[emp.id];
    const statusMap = {
        enrolled:  ['pc-tr-enrolled',  'Inscrito'],
        completed: ['pc-tr-completed', 'Concluído'],
        failed:    ['pc-tr-failed',    'Reprovado'],
    };
    const trHtml = rows.length
        ? rows.slice(0, 6).map(r => {
            const [cls, label] = statusMap[r.status] ?? ['', r.status];
            return `<div class="pc-training-item">
                <span class="pc-tr-name">${r.training?.title ?? '—'}</span>
                <span class="pc-tr-status ${cls}">${label}</span>
            </div>`;
          }).join('') + (rows.length > 6 ? `<div class="pc-tr-empty" style="margin-top:6px">+${rows.length - 6} mais…</div>` : '')
        : '<div class="pc-tr-empty">Sem formações registadas.</div>';

    document.getElementById('pcTrainings').innerHTML = trHtml;
}

function hideProfile() {
    profileHideTimer = setTimeout(() => {
        document.getElementById('profileCard').classList.remove('visible');
    }, 200);
}

/* ── Treinamentos do Funcionário ── */
async function openTrainings(empId, empName, empInitials) {
    document.getElementById('trAvatar').textContent = empInitials.toUpperCase();
    document.getElementById('trName').textContent   = empName;
    document.getElementById('trMeta').textContent   = 'A carregar treinamentos…';
    document.getElementById('trBody').innerHTML     = '<tr><td colspan="6" class="tr-loading">⏳ A carregar…</td></tr>';
    openOverlay('trainOverlay');

    try {
        const res  = await fetch(`${API}/enrollments?employee_id=${empId}&per_page=200`, { headers:{ Accept:'application/json' } });
        const json = await res.json();
        const rows = json.data ?? [];
        document.getElementById('trMeta').textContent = `${rows.length} treinamento(s) registado(s)`;
        renderTrainings(rows);
    } catch(e) {
        document.getElementById('trMeta').textContent = 'Erro ao carregar.';
        document.getElementById('trBody').innerHTML   = '<tr><td colspan="6" class="tr-empty">⚠️ Erro ao carregar os treinamentos.</td></tr>';
    }
}

function renderTrainings(rows) {
    const tbody = document.getElementById('trBody');
    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="tr-empty">🎓 Nenhum treinamento registado para este funcionário.</td></tr>';
        return;
    }

    const statusMap = {
        enrolled:  ['badge badge-enrolled',  'Inscrito'],
        completed: ['badge badge-completed', 'Concluído'],
        failed:    ['badge badge-failed',    'Reprovado'],
    };

    tbody.innerHTML = rows.map(r => {
        const [badgeCls, badgeLabel] = statusMap[r.status] ?? ['badge', r.status ?? '—'];
        const score = r.score !== null && r.score !== undefined
            ? `<div class="score-bar-wrap">
                 <div class="score-bar"><div class="score-bar-fill" style="width:${r.score}%"></div></div>
                 <span style="font-size:.75rem;color:var(--text-muted);white-space:nowrap">${r.score}%</span>
               </div>`
            : '<span style="color:var(--text-muted)">—</span>';
        const fmt = d => d ? new Date(d + 'T00:00:00').toLocaleDateString('pt-PT') : '—';
        return `<tr>
            <td style="font-weight:600">${r.training?.title ?? '—'}</td>
            <td style="color:var(--text-muted)">${r.training?.provider ?? '—'}</td>
            <td><span class="${badgeCls}">${badgeLabel}</span></td>
            <td style="min-width:120px">${score}</td>
            <td style="color:var(--text-muted);white-space:nowrap">${fmt(r.start_date)}</td>
            <td style="color:var(--text-muted);white-space:nowrap">${fmt(r.end_date)}</td>
        </tr>`;
    }).join('');
}

/* ── Close on backdrop ── */
document.querySelectorAll('.overlay').forEach(o=>{
    o.addEventListener('click',e=>{ if(e.target===o) o.classList.remove('open'); });
});

boot();
</script>
@endsection
