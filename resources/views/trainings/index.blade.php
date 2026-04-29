@extends('layouts.app')
@section('title','Formações')
@section('page-title','Formações')

@section('styles')
<style>
.toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px}
.toolbar h2{font-size:1.25rem;font-weight:700}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--accent);color:#fff;border:none;padding:9px 20px;border-radius:9px;font-size:.875rem;font-weight:600;cursor:pointer;transition:.15s}
.btn-primary:hover{background:#4f46e5}
.tab-bar{display:flex;gap:4px;margin-bottom:20px;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:4px;width:fit-content}
.tab-btn{padding:7px 18px;border-radius:7px;border:none;background:none;color:var(--text-muted);cursor:pointer;font-size:.86rem;font-weight:600;transition:.15s}
.tab-btn.active{background:var(--accent);color:#fff}
.filters{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
.f-input{flex:1;min-width:150px;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit}
.f-input:focus{outline:none;border-color:var(--accent)}
.btn-filter{padding:9px 18px;border-radius:9px;background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);color:var(--accent-light);cursor:pointer;font-size:.86rem;font-weight:600}
.btn-reset{padding:9px 14px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.86rem}
.card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.875rem}
thead th{padding:11px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);border-bottom:1px solid var(--border);background:rgba(255,255,255,.02);white-space:nowrap}
tbody td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
tbody tr:last-child td{border-bottom:none}
tbody tr:hover{background:rgba(255,255,255,.025)}
.badge{display:inline-block;padding:3px 9px;border-radius:6px;font-size:.73rem;font-weight:700}
.badge-enrolled{background:rgba(99,102,241,.15);color:var(--accent-light)}
.badge-completed{background:rgba(34,197,94,.15);color:#22c55e}
.badge-failed{background:rgba(239,68,68,.12);color:#ef4444}
.badge-count{display:inline-flex;align-items:center;justify-content:center;background:rgba(99,102,241,.15);color:var(--accent-light);border-radius:6px;font-size:.74rem;font-weight:700;min-width:28px;height:22px;padding:0 8px}
/* badges de validade */
.badge-valid   {background:rgba(34,197,94,.15);color:#22c55e}
.badge-expiring{background:rgba(245,158,11,.15);color:#f59e0b}
.badge-expired {background:rgba(239,68,68,.12);color:#ef4444}
.badge-noexp   {background:rgba(255,255,255,.07);color:var(--text-muted)}
.btn-sm{padding:4px 11px;border-radius:7px;font-size:.76rem;font-weight:600;cursor:pointer;border:none;transition:.15s}
.btn-edit{background:rgba(99,102,241,.15);color:var(--accent-light)}.btn-edit:hover{background:rgba(99,102,241,.3)}
.btn-del{background:rgba(239,68,68,.12);color:#ef4444}.btn-del:hover{background:rgba(239,68,68,.25)}
.pag{display:flex;align-items:center;justify-content:space-between;padding:13px 16px;border-top:1px solid var(--border);flex-wrap:wrap;gap:8px}
.pag-info{font-size:.8rem;color:var(--text-muted)}
.pag-btns{display:flex;gap:4px}
.pag-btns button{min-width:30px;height:30px;border-radius:7px;border:1px solid var(--border);background:rgba(255,255,255,.03);color:var(--text-muted);cursor:pointer;font-size:.8rem;font-weight:600;transition:.15s}
.pag-btns button:hover:not(:disabled){border-color:var(--accent);color:var(--accent-light)}
.pag-btns button.active{background:var(--accent);color:#fff;border-color:var(--accent)}
.pag-btns button:disabled{opacity:.35;cursor:not-allowed}
.state-row td{text-align:center;padding:48px;color:var(--text-muted)}
.spinner{display:inline-block;width:18px;height:18px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite;margin-right:8px;vertical-align:middle}
@keyframes spin{to{transform:rotate(360deg)}}
.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:8px}
.toast{padding:12px 18px;border-radius:10px;font-size:.86rem;font-weight:500;box-shadow:0 8px 32px rgba(0,0,0,.4);animation:slideIn .25s ease}
.toast-ok{background:rgba(34,197,94,.15);color:#22c55e;border:1px solid rgba(34,197,94,.25)}
.toast-err{background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.25)}
@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:flex-start;justify-content:center;padding:28px 14px;overflow-y:auto}
.overlay.open{display:flex}
.modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:26px;width:100%;max-width:560px;box-shadow:0 24px 80px rgba(0,0,0,.5);margin:auto}
.modal-title{font-size:1.05rem;font-weight:700;margin-bottom:18px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.full{grid-column:1/-1}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:5px}
.fg input,.fg select,.fg textarea{width:100%;background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:9px;padding:9px 12px;color:var(--text-primary);font-size:.875rem;font-family:inherit}
.fg input:focus,.fg select:focus,.fg textarea:focus{outline:none;border-color:var(--accent)}
.fg select option{background:var(--bg-card)}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}
.btn-cancel{padding:9px 20px;border-radius:9px;background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.875rem;font-weight:600}
.confirm-modal{max-width:370px;text-align:center}
.confirm-modal p{color:var(--text-muted);font-size:.9rem;margin:8px 0 18px}
.btn-danger{padding:9px 20px;border-radius:9px;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:.875rem;font-weight:600}
.btn-danger:hover{background:#dc2626}
.score-bar{height:5px;background:rgba(255,255,255,.08);border-radius:3px;margin-top:4px;overflow:hidden}
.score-fill{height:100%;border-radius:3px}
/* hint de validade no modal */
.validity-hint{margin-top:6px;font-size:.75rem;color:var(--text-muted);min-height:18px}
.validity-hint.expired {color:#ef4444}
.validity-hint.expiring{color:#f59e0b}
.validity-hint.valid   {color:#22c55e}
/* Alertas de validade */
.alert-bar{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px}
.alert-chip{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;font-size:.8rem;font-weight:600;cursor:pointer;border:1px solid transparent;transition:.15s}
.alert-chip:hover{opacity:.8}
.chip-expired {background:rgba(239,68,68,.12);color:#ef4444;border-color:rgba(239,68,68,.25)}
.chip-expiring{background:rgba(245,158,11,.12);color:#f59e0b;border-color:rgba(245,158,11,.25)}
/* ── Combobox pesquisável ── */
.cb-wrap{position:relative;flex:1;min-width:150px;max-width:210px}
.cb-input{width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 32px 9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit;box-sizing:border-box;cursor:pointer}
.cb-input:focus{outline:none;border-color:var(--accent)}
.cb-arrow{position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:var(--text-muted);font-size:.7rem}
.cb-dropdown{display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;z-index:100;max-height:220px;overflow-y:auto;box-shadow:0 8px 32px rgba(0,0,0,.4)}
.cb-dropdown.open{display:block}
.cb-option{padding:9px 13px;font-size:.86rem;cursor:pointer;color:var(--text-primary);transition:background .1s}
.cb-option:hover,.cb-option.focused{background:rgba(99,102,241,.15)}
.cb-option.selected{color:var(--accent-light);font-weight:600}
.cb-empty{padding:10px 13px;font-size:.83rem;color:var(--text-muted);text-align:center}
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>🎓 Formações</h2>
    <div style="display:flex;gap:10px">
        <button class="btn-primary" id="btnNewTraining" onclick="openCreateTraining()" style="display:none">+ Nova Formação</button>
        <button class="btn-primary" id="btnNewEnroll"   onclick="openCreateEnroll()">+ Nova Inscrição</button>
    </div>
</div>

<!-- Tabs -->
<div class="tab-bar">
    <button class="tab-btn active" id="tabEnroll"   onclick="switchTab('enrollments')">📋 Inscrições</button>
    <button class="tab-btn"        id="tabCatalog"  onclick="switchTab('catalog')">📚 Catálogo</button>
</div>

<!-- Alertas de validade (clicáveis — filtram a tabela) -->
<div class="alert-bar" id="alertBar" style="display:none">
    <div class="alert-chip chip-expired"  id="chipExpired"  onclick="filterByValidity('expired')">
        ⚠️ <span id="cntExpired">0</span> Expiradas
    </div>
    <div class="alert-chip chip-expiring" id="chipExpiring" onclick="filterByValidity('expiring')">
        🔔 <span id="cntExpiring">0</span> A Expirar (30 dias)
    </div>
</div>

<!-- Filtros Inscrições -->
<div class="filters" id="filterEnroll">
    <select id="fTraining" class="f-input" style="max-width:220px" onchange="applyFilters()"><option value="">Todas as formações</option></select>
    <div class="cb-wrap" id="cbEmpWrap">
        <input type="text" id="cbEmpInput" class="cb-input" placeholder="Todos os funcionários" autocomplete="off"
               onkeydown="cbKeydown(event)">
        <span class="cb-arrow">▼</span>
        <div class="cb-dropdown" id="cbEmpDropdown"></div>
    </div>
    <input type="hidden" id="fEmpEnroll" value="">
    <select id="fEnrollStatus" class="f-input" style="max-width:160px" onchange="applyFilters()">
        <option value="">Todos os status</option>
        <option value="enrolled">Inscrito</option>
        <option value="completed">Concluído</option>
        <option value="failed">Reprovado</option>
    </select>
    <select id="fValidityStatus" class="f-input" style="max-width:185px" onchange="applyFilters()">
        <option value="">Todas as validades</option>
        <option value="valid">✅ Válida</option>
        <option value="expiring">🔔 A Expirar (30 dias)</option>
        <option value="expired">⚠️ Expirada</option>
        <option value="none">— Sem validade def.</option>
    </select>
    <button class="btn-reset"  onclick="resetFilters()">✕ Limpar</button>
</div>

<!-- Filtros Catálogo -->
<div class="filters" id="filterCatalog" style="display:none">
    <input id="fCatalogSearch" class="f-input" placeholder="🔍 Pesquisar título ou fornecedor...">
    <button class="btn-filter" onclick="applyCatalogFilters()">Filtrar</button>
    <button class="btn-reset"  onclick="resetCatalogFilters()">✕ Limpar</button>
</div>

<!-- Tabela Inscrições -->
<div class="card" id="tableEnroll">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Formação</th>
                    <th>Status</th>
                    <th>Pontuação</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Validade</th>
                    <th>Expira em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="enrollBody">
                <tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr>
            </tbody>
        </table>
    </div>
    <div class="pag" id="enrollPagBar" style="display:none">
        <span class="pag-info" id="enrollPagInfo"></span>
        <div class="pag-btns" id="enrollPagBtns"></div>
    </div>
</div>

<!-- Tabela Catálogo -->
<div class="card" id="tableCatalog" style="display:none">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Título</th><th>Fornecedor</th><th>Inscrições</th><th>Ações</th></tr></thead>
            <tbody id="catalogBody"><tr class="state-row"><td colspan="5"><span class="spinner"></span>A carregar...</td></tr></tbody>
        </table>
    </div>
    <div class="pag" id="catalogPagBar" style="display:none">
        <span class="pag-info" id="catalogPagInfo"></span>
        <div class="pag-btns" id="catalogPagBtns"></div>
    </div>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<!-- Modal: Nova Inscrição / Editar Inscrição -->
<div class="overlay" id="enrollOverlay">
<div class="modal">
    <div class="modal-title" id="enrollTitle">Nova Inscrição</div>
    <form id="enrollForm" onsubmit="submitEnroll(event)">
        <div class="form-grid">
            <div class="fg full"><label>Funcionário *</label><select name="employee_id" id="empSelEnroll" required><option value="">— Selecionar —</option></select></div>
            <div class="fg full"><label>Formação *</label><select name="training_id" id="trainingSelEnroll" required><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Status</label>
                <select name="status">
                    <option value="enrolled">Inscrito</option>
                    <option value="completed">Concluído</option>
                    <option value="failed">Reprovado</option>
                </select>
            </div>
            <div class="fg"><label>Pontuação (0–100)</label><input name="score" type="number" min="0" max="100" step="0.1" placeholder="Ex: 85.5"></div>
            <div class="fg"><label>Data de Início</label><input name="start_date" type="date"></div>
            <div class="fg"><label>Data de Fim</label><input name="end_date" type="date" id="endDateInput" oninput="updateExpiryHint()"></div>
            <div class="fg">
                <label>Validade (meses)</label>
                <input name="validity_months" type="number" min="1" max="120" step="1" id="validityInput"
                       placeholder="Ex: 12" oninput="updateExpiryHint()">
            </div>
            <div class="fg" style="display:flex;align-items:flex-end">
                <div style="width:100%">
                    <label>Data de expiração</label>
                    <div id="expiryHint" class="validity-hint" style="padding:9px 0;font-size:.85rem">— preencha fim e validade</div>
                </div>
            </div>
            <div class="fg full"><label>Notas</label><textarea name="notes" rows="2" placeholder="Opcional..."></textarea></div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('enrollOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="enrollSubmitBtn">Inscrever</button>
        </div>
    </form>
</div>
</div>

<!-- Modal: Nova Formação / Editar Formação -->
<div class="overlay" id="trainingOverlay">
<div class="modal">
    <div class="modal-title" id="trainingTitle">Nova Formação</div>
    <form id="trainingForm" onsubmit="submitTraining(event)">
        <div class="fg" style="margin-bottom:13px"><label>Título *</label><input name="title" required placeholder="Ex: Excel Avançado"></div>
        <div class="fg" style="margin-bottom:13px"><label>Fornecedor *</label><input name="provider" required placeholder="Ex: Udemy, Coursera..."></div>
        <div class="fg" style="margin-bottom:13px"><label>Descrição</label><textarea name="description" rows="3" placeholder="Descreva o conteúdo..."></textarea></div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('trainingOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="trainingSubmitBtn">Criar</button>
        </div>
    </form>
</div>
</div>

<!-- Modal Excluir -->
<div class="overlay" id="delOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem">🗑️</div>
    <div class="modal-title" style="margin-top:10px">Confirmar Exclusão</div>
    <p id="delMsg">Tem certeza?</p>
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
let currentTab='enrollments';
let enrollEditId=null, trainingEditId=null, deleteTarget=null;
let enrollPage=1, catalogPage=1, enrollFilters={}, catalogFilters={};
let employees=[], trainings=[];
let enrollMap={}, trainingMap={};

const statusLabel   = {enrolled:'Inscrito', completed:'Concluído', failed:'Reprovado'};
const statusClass   = {enrolled:'badge-enrolled', completed:'badge-completed', failed:'badge-failed'};
const validityLabel = {valid:'✅ Válida', expiring:'🔔 A expirar', expired:'⚠️ Expirada'};
const validityClass = {valid:'badge-valid', expiring:'badge-expiring', expired:'badge-expired'};

async function apiFetch(method,path,body){
    const opts={method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}};
    if(body)opts.body=JSON.stringify(body);
    const r=await fetch(API+path,opts);
    if(!r.ok){const e=await r.json().catch(()=>({message:'Erro'}));throw e;}
    return r.status===204?null:r.json();
}

async function boot(){
    const [emp,tr]=await Promise.all([
        apiFetch('GET','/employees?per_page=200').catch(()=>({data:[]})),
        apiFetch('GET','/trainings?per_page=200').catch(()=>({data:[]})),
    ]);
    employees = emp.data??[];
    trainings = tr.data??[];

    employees.forEach(e=>{
        const o=`<option value="${e.id}">${e.full_name} (${e.code})</option>`;
        document.getElementById('empSelEnroll').innerHTML+=o;
    });
    cbInit();
    trainings.forEach(t=>{
        const o=`<option value="${t.id}">${t.title}</option>`;
        document.getElementById('trainingSelEnroll').innerHTML+=o;
        document.getElementById('fTraining').innerHTML+=`<option value="${t.id}">${t.title}</option>`;
    });
    loadEnrollments();
    loadAlerts();
}

/* ── Alertas de validade ─────────────────────────── */
async function loadAlerts(){
    try{
        const [rExp, rExpiring]=await Promise.all([
            fetch(`${API}/enrollments?per_page=1&validity_status=expired`,{headers:{Accept:'application/json'}}).then(r=>r.json()),
            fetch(`${API}/enrollments?per_page=1&validity_status=expiring`,{headers:{Accept:'application/json'}}).then(r=>r.json()),
        ]);
        const nExp   = rExp.meta?.total??0;
        const nExpir = rExpiring.meta?.total??0;
        document.getElementById('cntExpired').textContent  = nExp;
        document.getElementById('cntExpiring').textContent = nExpir;
        document.getElementById('alertBar').style.display  = (nExp>0||nExpir>0)?'flex':'none';
    }catch(e){}
}

function filterByValidity(v){
    document.getElementById('fValidityStatus').value=v;
    enrollFilters={validity_status:v};
    enrollPage=1;
    loadEnrollments();
    if(currentTab!=='enrollments') switchTab('enrollments');
}

/* ── Tabs ── */
function switchTab(tab){
    currentTab=tab;
    document.getElementById('tabEnroll').classList.toggle('active',tab==='enrollments');
    document.getElementById('tabCatalog').classList.toggle('active',tab==='catalog');
    document.getElementById('tableEnroll').style.display   = tab==='enrollments'?'':'none';
    document.getElementById('tableCatalog').style.display  = tab==='catalog'?'':'none';
    document.getElementById('filterEnroll').style.display  = tab==='enrollments'?'flex':'none';
    document.getElementById('filterCatalog').style.display = tab==='catalog'?'flex':'none';
    document.getElementById('alertBar').style.display      = tab==='enrollments'?'':'none';
    document.getElementById('btnNewTraining').style.display= tab==='catalog'?'inline-flex':'none';
    document.getElementById('btnNewEnroll').style.display  = tab==='enrollments'?'inline-flex':'none';
    if(tab==='catalog') loadCatalog();
}

/* ── Enrollments ── */
async function loadEnrollments(){
    const tbody=document.getElementById('enrollBody');
    tbody.innerHTML='<tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('enrollPagBar').style.display='none';
    const q=new URLSearchParams({page:enrollPage,per_page:15,...enrollFilters});
    try{
        const res=await fetch(`${API}/enrollments?${q}`,{headers:{Accept:'application/json'}});
        const json=await res.json();
        renderEnrollments(json.data??[]);
        renderPag(json.meta,'enrollPagBar','enrollPagInfo','enrollPagBtns',p=>{enrollPage=p;loadEnrollments();});
    }catch(e){tbody.innerHTML='<tr class="state-row"><td colspan="9">⚠️ Erro ao carregar.</td></tr>';}
}

function renderEnrollments(rows){
    const tbody=document.getElementById('enrollBody');
    if(!rows.length){tbody.innerHTML='<tr class="state-row"><td colspan="9">Nenhuma inscrição encontrada.</td></tr>';return;}
    enrollMap={};
    rows.forEach(e=>enrollMap[e.id]=e);
    tbody.innerHTML=rows.map(e=>{
        const sd=e.start_date?new Date(e.start_date+'T00:00:00').toLocaleDateString('pt-PT'):'—';
        const ed=e.end_date  ?new Date(e.end_date  +'T00:00:00').toLocaleDateString('pt-PT'):'—';
        const score=e.score!=null
            ?`<div>${e.score}%<div class="score-bar"><div class="score-fill" style="width:${e.score}%;background:${e.score>=70?'#22c55e':e.score>=40?'#f59e0b':'#ef4444'}"></div></div></div>`
            :'—';

        // Coluna validade (meses)
        const validityCell = e.validity_months
            ? `${e.validity_months} mês${e.validity_months>1?'es':''}`
            : `<span style="color:var(--text-muted)">—</span>`;

        // Coluna expiração com badge de estado
        let expiryCell = `<span style="color:var(--text-muted)">—</span>`;
        if(e.expiry_date){
            const expiryFmt = new Date(e.expiry_date+'T00:00:00').toLocaleDateString('pt-PT');
            const vs  = e.validity_status;
            const cls = validityClass[vs]??'badge-noexp';
            const lbl = validityLabel[vs]??'';
            expiryCell=`<div style="line-height:1.6">
                <span style="font-size:.82rem;color:var(--text-muted)">${expiryFmt}</span><br>
                <span class="badge ${cls}">${lbl}</span>
            </div>`;
        }

        return `<tr>
            <td style="font-weight:600">${e.employee?.full_name??'—'}</td>
            <td>${e.training?.title??'—'}</td>
            <td><span class="badge ${statusClass[e.status]??''}">${statusLabel[e.status]??e.status}</span></td>
            <td style="font-size:.82rem">${score}</td>
            <td style="color:var(--text-muted)">${sd}</td>
            <td style="color:var(--text-muted)">${ed}</td>
            <td style="font-size:.82rem">${validityCell}</td>
            <td>${expiryCell}</td>
            <td style="white-space:nowrap">
                <button class="btn-sm btn-edit" onclick="openEditEnroll(${e.id})">✏️</button>
                <button class="btn-sm btn-del"  onclick="openDelete('enrollment',${e.id})">🗑</button>
            </td>
        </tr>`;
    }).join('');
}

/* ── Catalog ── */
async function loadCatalog(){
    const tbody=document.getElementById('catalogBody');
    tbody.innerHTML='<tr class="state-row"><td colspan="6"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('catalogPagBar').style.display='none';
    const q=new URLSearchParams({page:catalogPage,per_page:15,...catalogFilters});
    try{
        const res=await fetch(`${API}/trainings?${q}`,{headers:{Accept:'application/json'}});
        const json=await res.json();
        renderCatalog(json.data??[]);
        renderPag(json.meta,'catalogPagBar','catalogPagInfo','catalogPagBtns',p=>{catalogPage=p;loadCatalog();});
    }catch(e){tbody.innerHTML='<tr class="state-row"><td colspan="6">⚠️ Erro ao carregar.</td></tr>';}
}

function renderCatalog(rows){
    const tbody=document.getElementById('catalogBody');
    if(!rows.length){tbody.innerHTML='<tr class="state-row"><td colspan="5">Nenhuma formação no catálogo.</td></tr>';return;}
    trainingMap={};
    rows.forEach(t=>trainingMap[t.id]=t);
    tbody.innerHTML=rows.map(t=>`<tr>
        <td style="color:var(--text-muted)">${t.id}</td>
        <td style="font-weight:600">${t.title}</td>
        <td>${t.provider}</td>
        <td><span class="badge-count">${t.employee_trainings_count??0}</span></td>
        <td style="white-space:nowrap">
            <button class="btn-sm btn-edit" onclick="openEditTraining(${t.id})">✏️ Editar</button>
            <button class="btn-sm btn-del"  onclick="openDelete('training',${t.id})">🗑</button>
        </td>
    </tr>`).join('');
}

/* ── Paginação genérica ── */
function renderPag(meta,barId,infoId,btnsId,onPage){
    if(!meta)return;
    document.getElementById(barId).style.display='flex';
    document.getElementById(infoId).textContent=`${meta.from??0}–${meta.to??0} de ${meta.total}`;
    const btns=document.getElementById(btnsId);btns.innerHTML='';
    const prev=document.createElement('button');prev.textContent='‹';prev.disabled=meta.current_page<=1;prev.onclick=()=>onPage(meta.current_page-1);btns.appendChild(prev);
    const start=Math.max(1,meta.current_page-3),end=Math.min(meta.last_page,start+6);
    for(let i=start;i<=end;i++){const b=document.createElement('button');b.textContent=i;if(i===meta.current_page)b.classList.add('active');b.onclick=(()=>{const p=i;return()=>onPage(p);})();btns.appendChild(b);}
    const next=document.createElement('button');next.textContent='›';next.disabled=meta.current_page>=meta.last_page;next.onclick=()=>onPage(meta.current_page+1);btns.appendChild(next);
}

/* ── Filters ── */
function applyFilters(){
    enrollFilters={};
    const t=document.getElementById('fTraining').value;
    const e=document.getElementById('fEmpEnroll').value;
    const s=document.getElementById('fEnrollStatus').value;
    const v=document.getElementById('fValidityStatus').value;
    if(t)enrollFilters.training_id=t;
    if(e)enrollFilters.employee_id=e;
    if(s)enrollFilters.status=s;
    if(v)enrollFilters.validity_status=v;
    enrollPage=1;loadEnrollments();
}
function resetFilters(){
    ['fTraining','fEnrollStatus','fValidityStatus'].forEach(id=>document.getElementById(id).value='');
    cbClear();
    enrollFilters={};enrollPage=1;loadEnrollments();
}

/* ── Combobox funcionários ── */
let cbItems=[], cbFocusIdx=-1;
function cbInit(){
    cbItems=[{id:'',label:'Todos os funcionários'},...employees.map(e=>({id:String(e.id),label:e.full_name+(e.code?' ('+e.code+')':'')}))];
    const inp=document.getElementById('cbEmpInput');
    inp.addEventListener('input',cbFilter);
    inp.addEventListener('click',cbOpen);
    // Fechar ao clicar fora — repor label anterior se não houve nova seleção
    document.addEventListener('click',function(ev){
        if(!document.getElementById('cbEmpWrap').contains(ev.target)){
            document.getElementById('cbEmpDropdown').classList.remove('open');
            const inp=document.getElementById('cbEmpInput');
            if(inp.dataset.prev!==undefined && inp.value!==inp.dataset.prev){
                inp.removeEventListener('input',cbFilter);
                inp.value=inp.dataset.prev;
                inp.addEventListener('input',cbFilter);
                delete inp.dataset.prev;
            }
        }
    });
}
function cbRenderDropdown(q){
    const dd=document.getElementById('cbEmpDropdown');
    const filtered=q?cbItems.filter(i=>i.label.toLowerCase().includes(q.toLowerCase())):cbItems;
    if(!filtered.length){dd.innerHTML='<div class="cb-empty">Sem resultados</div>';cbFocusIdx=-1;return;}
    const cur=document.getElementById('fEmpEnroll').value;
    dd.innerHTML=filtered.map(i=>`<div class="cb-option${i.id===cur?' selected':''}" data-id="${i.id}">${i.label}</div>`).join('');
    // Ligar click em cada opção
    dd.querySelectorAll('.cb-option').forEach(function(opt){
        opt.addEventListener('click',function(ev){
            ev.stopPropagation();
            const id=opt.dataset.id;
            const label=opt.textContent;
            document.getElementById('fEmpEnroll').value=id;
            // Atualizar input sem disparar oninput
            const inp=document.getElementById('cbEmpInput');
            inp.removeEventListener('input',cbFilter);
            inp.value=id?label:'';
            delete inp.dataset.prev;
            inp.addEventListener('input',cbFilter);
            document.getElementById('cbEmpDropdown').classList.remove('open');
            applyFilters();
        });
    });
    cbFocusIdx=-1;
}
function cbFilter(){
    const q=document.getElementById('cbEmpInput').value;
    if(!q){document.getElementById('fEmpEnroll').value='';applyFilters();}
    cbRenderDropdown(q);
    document.getElementById('cbEmpDropdown').classList.add('open');
}
function cbOpen(){
    // Limpar o input para permitir nova pesquisa, guardando o label atual
    const inp=document.getElementById('cbEmpInput');
    inp.dataset.prev=inp.value;
    inp.removeEventListener('input',cbFilter);
    inp.value='';
    inp.addEventListener('input',cbFilter);
    cbRenderDropdown('');
    document.getElementById('cbEmpDropdown').classList.add('open');
}
function cbClear(){
    document.getElementById('fEmpEnroll').value='';
    const inp=document.getElementById('cbEmpInput');
    inp.removeEventListener('input',cbFilter);
    inp.value='';
    inp.addEventListener('input',cbFilter);
}
function cbKeydown(e){
    const dd=document.getElementById('cbEmpDropdown');
    const opts=[...dd.querySelectorAll('.cb-option')];
    if(!opts.length)return;
    if(e.key==='ArrowDown'){e.preventDefault();cbFocusIdx=Math.min(cbFocusIdx+1,opts.length-1);opts.forEach((o,i)=>o.classList.toggle('focused',i===cbFocusIdx));}
    else if(e.key==='ArrowUp'){e.preventDefault();cbFocusIdx=Math.max(cbFocusIdx-1,0);opts.forEach((o,i)=>o.classList.toggle('focused',i===cbFocusIdx));}
    else if(e.key==='Enter'){if(cbFocusIdx>=0)opts[cbFocusIdx].click();}
    else if(e.key==='Escape'){dd.classList.remove('open');}
}
function applyCatalogFilters(){catalogFilters={};const s=document.getElementById('fCatalogSearch').value.trim();if(s)catalogFilters.search=s;catalogPage=1;loadCatalog();}
function resetCatalogFilters(){document.getElementById('fCatalogSearch').value='';catalogFilters={};catalogPage=1;loadCatalog();}

/* ── Cálculo dinâmico de expiração no modal ── */
function updateExpiryHint(){
    const endVal    = document.getElementById('endDateInput').value;
    const monthsVal = parseInt(document.getElementById('validityInput').value);
    const hint      = document.getElementById('expiryHint');
    if(!endVal || !monthsVal || monthsVal < 1){
        hint.textContent='— preencha fim e validade';
        hint.className='validity-hint';
        return;
    }
    const expiry = new Date(endVal);
    expiry.setMonth(expiry.getMonth()+monthsVal);
    const today  = new Date(); today.setHours(0,0,0,0);
    const diff   = Math.round((expiry-today)/(1000*60*60*24));
    const fmt    = expiry.toLocaleDateString('pt-PT');
    if(diff < 0){
        hint.textContent=`Expirou em ${fmt}`;
        hint.className='validity-hint expired';
    }else if(diff <= 30){
        hint.textContent=`Expira em ${fmt} (faltam ${diff} dias)`;
        hint.className='validity-hint expiring';
    }else{
        hint.textContent=`Válida até ${fmt}`;
        hint.className='validity-hint valid';
    }
}

/* ── Overlays ── */
function openOverlay(id){document.getElementById(id).classList.add('open');}
function closeOverlay(id){document.getElementById(id).classList.remove('open');}

function openCreateEnroll(){
    enrollEditId=null;
    document.getElementById('enrollForm').reset();
    document.getElementById('expiryHint').textContent='— preencha fim e validade';
    document.getElementById('expiryHint').className='validity-hint';
    document.getElementById('enrollTitle').textContent='➕ Nova Inscrição';
    document.getElementById('enrollSubmitBtn').textContent='Inscrever';
    openOverlay('enrollOverlay');
}
function openEditEnroll(id){
    const e=enrollMap[id];if(!e)return;
    enrollEditId=e.id;
    document.getElementById('enrollForm').reset();
    const form=document.getElementById('enrollForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    set('employee_id',e.employee_id);set('training_id',e.training_id);set('status',e.status);
    set('score',e.score);set('start_date',e.start_date);set('end_date',e.end_date);
    set('validity_months',e.validity_months);set('notes',e.notes);
    document.getElementById('enrollTitle').textContent='✏️ Editar Inscrição';
    document.getElementById('enrollSubmitBtn').textContent='Guardar';
    setTimeout(updateExpiryHint,50);
    openOverlay('enrollOverlay');
}
async function submitEnroll(ev){
    ev.preventDefault();
    const btn=document.getElementById('enrollSubmitBtn');btn.disabled=true;btn.textContent='A guardar...';
    const data={};
    new FormData(document.getElementById('enrollForm')).forEach((v,k)=>{if(v!=='')data[k]=v;});
    try{
        if(enrollEditId) await apiFetch('PUT',`/enrollments/${enrollEditId}`,data);
        else             await apiFetch('POST','/enrollments',data);
        toast(enrollEditId?'Inscrição atualizada!':'Inscrição criada!','ok');
        closeOverlay('enrollOverlay');
        loadEnrollments();
        loadAlerts();
    }catch(err){toast(err.message??'Erro.','err');}
    finally{btn.disabled=false;btn.textContent=enrollEditId?'Guardar':'Inscrever';}
}

function openCreateTraining(){
    trainingEditId=null;document.getElementById('trainingForm').reset();
    document.getElementById('trainingTitle').textContent='➕ Nova Formação';
    document.getElementById('trainingSubmitBtn').textContent='Criar';
    openOverlay('trainingOverlay');
}
function openEditTraining(id){
    const t=trainingMap[id];if(!t)return;
    trainingEditId=t.id;document.getElementById('trainingForm').reset();
    const form=document.getElementById('trainingForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    set('title',t.title);set('provider',t.provider);set('description',t.description);
    document.getElementById('trainingTitle').textContent='✏️ Editar Formação';
    document.getElementById('trainingSubmitBtn').textContent='Guardar';
    openOverlay('trainingOverlay');
}
async function submitTraining(ev){
    ev.preventDefault();
    const btn=document.getElementById('trainingSubmitBtn');btn.disabled=true;btn.textContent='A guardar...';
    const data={};new FormData(document.getElementById('trainingForm')).forEach((v,k)=>{if(v!=='')data[k]=v;});
    try{
        if(trainingEditId) await apiFetch('PUT',`/trainings/${trainingEditId}`,data);
        else               await apiFetch('POST','/trainings',data);
        toast(trainingEditId?'Formação atualizada!':'Formação criada!','ok');
        closeOverlay('trainingOverlay');loadCatalog();
    }catch(err){toast(err.message??'Erro.','err');}
    finally{btn.disabled=false;btn.textContent=trainingEditId?'Guardar':'Criar';}
}

/* ── Delete ── */
function openDelete(type,id){
    deleteTarget={type,id};
    document.getElementById('delMsg').textContent=type==='training'
        ?'Tem certeza que deseja excluir esta formação? Todas as inscrições também serão removidas.'
        :'Tem certeza que deseja excluir esta inscrição?';
    openOverlay('delOverlay');
}
async function confirmDelete(){
    const {type,id}=deleteTarget;
    try{
        if(type==='training') await apiFetch('DELETE',`/trainings/${id}`);
        else                  await apiFetch('DELETE',`/enrollments/${id}`);
        toast('Excluído com sucesso.','ok');
        closeOverlay('delOverlay');
        if(type==='training') loadCatalog(); else loadEnrollments();
        loadAlerts();
    }catch(err){toast(err.message??'Erro.','err');}
}

function toast(msg,type='ok'){
    const w=document.getElementById('toastWrap');
    const t=document.createElement('div');
    t.className=`toast toast-${type}`;t.textContent=msg;
    w.appendChild(t);setTimeout(()=>t.remove(),3500);
}
document.querySelectorAll('.overlay').forEach(o=>{
    o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');});
});
boot();
</script>
@endsection
