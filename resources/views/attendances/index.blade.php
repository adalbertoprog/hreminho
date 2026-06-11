@extends('layouts.app')
@section('title','Presenças')
@section('page-title','Presenças')

@section('styles')
<style>
/* ── Toolbar & Tabs ── */
.toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:14px}
.toolbar h2{font-size:1.25rem;font-weight:700}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--accent);color:#fff;border:none;padding:9px 20px;border-radius:9px;font-size:.875rem;font-weight:600;cursor:pointer;transition:.15s}
.btn-primary:hover{background:#4f46e5}
.tabs{display:flex;gap:0;margin-bottom:18px;border-bottom:1px solid var(--border)}
.tab-btn{padding:9px 22px;background:none;border:none;border-bottom:2px solid transparent;color:var(--text-muted);font-size:.9rem;font-weight:600;cursor:pointer;margin-bottom:-1px;transition:.15s}
.tab-btn:hover{color:var(--text-primary)}
.tab-btn.active{border-bottom-color:var(--accent);color:var(--accent-light)}

/* ── Filters ── */
.filters{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:10px;align-items:center}
.f-input{flex:1;min-width:130px;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:8px 12px;color:var(--text-primary);font-size:.86rem;font-family:inherit}
.f-input:focus{outline:none;border-color:var(--accent)}
.f-label{font-size:.78rem;font-weight:600;color:var(--text-muted);white-space:nowrap}
.quick-filters{display:flex;gap:6px;margin-bottom:16px;flex-wrap:wrap}
.btn-quick{padding:6px 14px;border-radius:8px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.8rem;font-weight:600;transition:.15s}
.btn-quick:hover{border-color:var(--accent);color:var(--accent-light)}
.btn-quick.active{background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.4);color:var(--accent-light)}
.btn-filter{padding:8px 18px;border-radius:9px;background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);color:var(--accent-light);cursor:pointer;font-size:.86rem;font-weight:600}
.btn-reset{padding:8px 14px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.86rem}

/* ── Summary bar ── */
.summary-bar{display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap}
.sum-card{flex:1;min-width:120px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px}
.sum-icon{font-size:1.4rem;line-height:1}
.sum-body{}
.sum-val{font-size:1.25rem;font-weight:800;line-height:1.1}
.sum-lbl{font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-top:2px}
.sum-present .sum-val{color:#22c55e}
.sum-late    .sum-val{color:#f59e0b}
.sum-absent  .sum-val{color:#ef4444}
.sum-leave   .sum-val{color:#06b6d4}
.sum-today-lbl{font-size:.68rem;font-weight:700;color:var(--text-muted);letter-spacing:.5px;margin-bottom:8px;text-transform:uppercase;grid-column:1/-1}

/* ── Card / Table ── */
.card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.875rem}
thead th{padding:11px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);border-bottom:1px solid var(--border);background:rgba(255,255,255,.02);white-space:nowrap}
tbody td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
tbody tr:last-child td{border-bottom:none}
tbody tr:hover{background:rgba(255,255,255,.025)}
/* Incomplete row highlight (#29) */
tbody tr.row-incomplete td{background:rgba(245,158,11,.04)}
tbody tr.row-incomplete:hover td{background:rgba(245,158,11,.08)}
tbody tr.row-incomplete td:first-child{border-left:3px solid rgba(245,158,11,.6)}
tbody tr.row-leave td{background:rgba(6,182,212,.03)}
tbody tr.row-leave:hover td{background:rgba(6,182,212,.07)}
tbody tr.row-leave td:first-child{border-left:3px solid rgba(6,182,212,.5)}
.leave-tag{display:inline-block;margin-left:6px;font-size:.68rem;font-weight:600;color:#06b6d4;vertical-align:middle}
.incomplete-dot{display:inline-block;width:6px;height:6px;border-radius:50%;background:#f59e0b;margin-left:5px;vertical-align:middle;title:'Sem hora de saída'}
.badge{display:inline-block;padding:3px 9px;border-radius:6px;font-size:.73rem;font-weight:700}
.badge-present{background:rgba(34,197,94,.15);color:#22c55e}
.badge-late{background:rgba(245,158,11,.15);color:#f59e0b}
.badge-absent{background:rgba(239,68,68,.12);color:#ef4444}
.badge-holiday{background:rgba(99,102,241,.15);color:var(--accent-light)}
.badge-on_leave{background:rgba(6,182,212,.15);color:#06b6d4}
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

/* ── Toast ── */
.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:8px}
.toast{padding:12px 18px;border-radius:10px;font-size:.86rem;font-weight:500;box-shadow:0 8px 32px rgba(0,0,0,.4);animation:slideIn .25s ease}
.toast-ok{background:rgba(34,197,94,.15);color:#22c55e;border:1px solid rgba(34,197,94,.25)}
.toast-err{background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.25)}
@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}

/* ── Modals ── */
.overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:center;justify-content:center}
.overlay.open{display:flex}
.modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:26px;width:100%;max-width:500px;box-shadow:0 24px 80px rgba(0,0,0,.5)}
.modal-title{font-size:1.05rem;font-weight:700;margin-bottom:18px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.full{grid-column:1/-1}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:5px}
.fg input,.fg select{width:100%;background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:9px;padding:9px 12px;color:var(--text-primary);font-size:.875rem;font-family:inherit}
.fg input:focus,.fg select:focus{outline:none;border-color:var(--accent)}
.fg select option{background:var(--bg-card)}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}
.btn-cancel{padding:9px 20px;border-radius:9px;background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.875rem;font-weight:600}
.confirm-modal{max-width:370px;text-align:center}
.confirm-modal p{color:var(--text-muted);font-size:.9rem;margin:8px 0 18px}
.btn-danger{padding:9px 20px;border-radius:9px;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:.875rem;font-weight:600}
.btn-danger:hover{background:#dc2626}

/* ── Weekly View ── */
.week-nav{display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.week-nav-btns{display:flex;gap:6px}
.btn-week-nav{padding:7px 16px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.85rem;font-weight:600;transition:.15s}
.btn-week-nav:hover{border-color:var(--accent);color:var(--accent-light)}
.week-label{font-size:.95rem;font-weight:700;color:var(--text-primary);min-width:220px;text-align:center}
.btn-week-today{padding:7px 14px;border-radius:9px;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.3);color:var(--accent-light);cursor:pointer;font-size:.82rem;font-weight:600}
.week-table-wrap{overflow-x:auto}
.week-table{width:100%;border-collapse:collapse;font-size:.875rem;min-width:700px}
.week-table thead th{padding:10px 14px;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);border-bottom:1px solid var(--border);background:rgba(255,255,255,.02);white-space:nowrap}
.week-table thead th:first-child{text-align:left;min-width:180px}
.week-table thead th.today-col{color:var(--accent-light);background:rgba(99,102,241,.06)}
.week-table tbody td{padding:9px 14px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle;text-align:center}
.week-table tbody td:first-child{text-align:left;font-weight:600;white-space:nowrap}
.week-table tbody tr:last-child td{border-bottom:none}
.week-table tbody tr:hover td{background:rgba(255,255,255,.02)}
.week-table tbody td.today-col{background:rgba(99,102,241,.04)}
.cell-status{display:inline-flex;align-items:center;justify-content:center;min-width:90px;padding:5px 11px;border-radius:8px;font-size:.75rem;font-weight:700;cursor:pointer;user-select:none;transition:all .15s;border:1px solid transparent}
.cell-status:hover{filter:brightness(1.2);transform:scale(1.04)}
.cell-status.saving{opacity:.5;cursor:wait}
.cell-none{background:rgba(255,255,255,.04);color:var(--text-muted);border-color:rgba(255,255,255,.07)}
.cell-present{background:rgba(34,197,94,.15);color:#22c55e;border-color:rgba(34,197,94,.2)}
.cell-late{background:rgba(245,158,11,.15);color:#f59e0b;border-color:rgba(245,158,11,.2)}
.cell-absent{background:rgba(239,68,68,.12);color:#ef4444;border-color:rgba(239,68,68,.2)}
.cell-on_leave{background:rgba(6,182,212,.15);color:#06b6d4;border-color:rgba(6,182,212,.2)}
.week-empty{text-align:center;padding:48px;color:var(--text-muted)}
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>📅 Presenças</h2>
    <button class="btn-primary" id="btnRegister" onclick="openCreate()">+ Registar Presença</button>
</div>

{{-- Tabs --}}
<div class="tabs">
    <button class="tab-btn active" id="tabLista"  onclick="switchTab('lista')">📋 Lista</button>
    <button class="tab-btn"        id="tabSemana" onclick="switchTab('semana')">📆 Semana</button>
</div>

{{-- ══ LIST VIEW ══ --}}
<div id="viewLista">

{{-- Quick filters (#27) --}}
<div class="quick-filters">
    <button class="btn-quick" id="qAll"   onclick="quickFilter('all')">Todos</button>
    <button class="btn-quick" id="qToday" onclick="quickFilter('today')">Hoje</button>
    <button class="btn-quick" id="qWeek"  onclick="quickFilter('week')">Esta semana</button>
    <button class="btn-quick" id="qMonth" onclick="quickFilter('month')">Este mês</button>
</div>

{{-- Advanced filters (#27: De/Até) --}}
<div class="filters">
    <span class="f-label">De</span>
    <input id="fFrom"   type="date" class="f-input" style="max-width:160px">
    <span class="f-label">Até</span>
    <input id="fTo"     type="date" class="f-input" style="max-width:160px">
    <select id="fEmp"   class="f-input" style="max-width:210px"><option value="">Todos os funcionários</option></select>
    <select id="fStatus" class="f-input" style="max-width:160px">
        <option value="">Todos os status</option>
        <option value="present">Presente</option>
        <option value="late">Atrasado</option>
        <option value="absent">Ausente</option>
        <option value="holiday">Feriado</option>
        <option value="on_leave">De Licença</option>
    </select>
    <button class="btn-filter" onclick="applyFilters()">Filtrar</button>
    <button class="btn-reset"  onclick="resetFilters()">✕ Limpar</button>
</div>

{{-- Summary bar (#28) --}}
<div class="summary-bar" id="summaryBar" style="display:none">
    <p class="sum-today-lbl" style="width:100%;margin:0 0 4px">Hoje</p>
    <div class="sum-card sum-present"><div class="sum-icon">✅</div><div class="sum-body"><div class="sum-val" id="sumPresent">–</div><div class="sum-lbl">Presentes</div></div></div>
    <div class="sum-card sum-late">   <div class="sum-icon">⏰</div><div class="sum-body"><div class="sum-val" id="sumLate">–</div><div class="sum-lbl">Atrasados</div></div></div>
    <div class="sum-card sum-absent"> <div class="sum-icon">❌</div><div class="sum-body"><div class="sum-val" id="sumAbsent">–</div><div class="sum-lbl">Ausentes</div></div></div>
    <div class="sum-card sum-leave">  <div class="sum-icon">🏖️</div><div class="sum-body"><div class="sum-val" id="sumLeave">–</div><div class="sum-lbl">Licença/Férias</div></div></div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr>
                <th>Funcionário</th><th>Data</th><th>Entrada</th>
                <th>Saída Almoço</th><th>Entrada Almoço</th><th>Saída</th>
                <th>Total</th><th>Status</th><th>Ações</th>
            </tr></thead>
            <tbody id="attBody"><tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr></tbody>
        </table>
    </div>
    <div class="pag" id="pagBar" style="display:none">
        <span class="pag-info" id="pagInfo"></span>
        <div class="pag-btns" id="pagBtns"></div>
    </div>
</div>
</div>{{-- /viewLista --}}

{{-- ══ WEEK VIEW ══ --}}
<div id="viewSemana" style="display:none">
<div class="week-nav">
    <div class="week-nav-btns">
        <button class="btn-week-nav" onclick="weekOffset--;loadWeek()">‹ Anterior</button>
        <button class="btn-week-nav" onclick="weekOffset++;loadWeek()">Seguinte ›</button>
    </div>
    <span class="week-label" id="weekLabel">—</span>
    <button class="btn-week-today" onclick="weekOffset=0;loadWeek()">Hoje</button>
    <span id="weekSaving" style="font-size:.8rem;color:var(--text-muted);margin-left:4px"></span>
</div>
<div class="card">
    <div class="week-table-wrap">
        <table class="week-table" id="weekTable">
            <thead id="weekHead"></thead>
            <tbody id="weekBody"><tr><td colspan="6" class="week-empty"><span class="spinner"></span>A carregar...</td></tr></tbody>
        </table>
    </div>
</div>
</div>{{-- /viewSemana --}}

<div class="toast-wrap" id="toastWrap"></div>

<!-- Modal Criar/Editar -->
<div class="overlay" id="formOverlay">
<div class="modal">
    <div class="modal-title" id="formTitle">Registar Presença</div>
    <form id="attForm" onsubmit="submitForm(event)">
        <div class="form-grid">
            <div class="fg full"><label>Funcionário *</label><select name="employee_id" id="empSel" required onchange="updateStatusPreview()"><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Data *</label><input name="date" type="date" required></div>
            <div class="fg"><label>Hora de Entrada</label><input name="check_in" type="time" oninput="updateStatusPreview()"></div>
            <div class="fg"><label>Saída p/ Almoço</label><input name="lunch_out" type="time"></div>
            <div class="fg"><label>Regresso do Almoço</label><input name="lunch_in" type="time"></div>
            <div class="fg"><label>Hora de Saída</label><input name="check_out" type="time"></div>
            <div class="fg">
                <label>Status</label>
                <select name="status" onchange="updateStatusPreview()">
                    <option value="">— Automático —</option>
                    <option value="holiday">Feriado</option>
                    <option value="on_leave">De Licença</option>
                </select>
                <div id="statusPreview" style="margin-top:5px;font-size:.82rem;color:var(--text-muted)"></div>
            </div>
            <div class="fg full"><label>Notas</label><input name="notes" type="text" placeholder="Opcional..." maxlength="500"></div>
        </div>
        <div id="conflictWarn" style="display:none;margin:12px 0 0;padding:12px 14px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.4);border-radius:8px;font-size:.83rem;color:var(--text-primary)">
            <strong>⚠️ Atenção:</strong> <span id="conflictMsg"></span>
            <div style="margin-top:8px;display:flex;gap:8px">
                <button type="button" class="btn-cancel" style="padding:5px 12px;font-size:.8rem" onclick="hideConflict()">Cancelar</button>
                <button type="button" class="btn-primary" style="padding:5px 12px;font-size:.8rem;background:var(--warning,#f59e0b)" id="forceBtn" onclick="submitForm(null,true)">Confirmar mesmo assim</button>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('formOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="submitBtn">Registar</button>
        </div>
    </form>
</div>
</div>

<!-- Modal Excluir -->
<div class="overlay" id="delOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem">🗑️</div>
    <div class="modal-title" style="margin-top:10px">Excluir Registo</div>
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
let editId=null, deleteId=null, employees=[], page=1, filters={};
let globalCheckIn='09:00', globalTolerance=0;
let currentTab='lista';
let activeQuick='all';

// ── Week state ──
let weekOffset=0;
let weekRecords={};

const statusLabel = {present:'Presente',late:'Atrasado',absent:'Ausente',holiday:'Feriado',on_leave:'Licença'};
const statusClass  = {present:'badge-present',late:'badge-late',absent:'badge-absent',holiday:'badge-holiday',on_leave:'badge-on_leave'};
const STATUS_CYCLE = [null,'present','late','absent','on_leave'];
const CELL_CLASS   = {present:'cell-present',late:'cell-late',absent:'cell-absent',on_leave:'cell-on_leave'};
const CELL_LABEL   = {present:'Presente',late:'Atrasado',absent:'Ausente',on_leave:'Licença'};
const DAY_NAMES    = ['Seg','Ter','Qua','Qui','Sex'];

async function apiFetch(method, path, body) {
    const opts={method,credentials:'same-origin',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}};
    if(body) opts.body=JSON.stringify(body);
    const r=await fetch(API+path,opts);
    if(!r.ok){const e=await r.json().catch(()=>({message:'Erro'}));throw e;}
    return r.status===204?null:r.json();
}

async function boot() {
    const cfg = await apiFetch('GET','/settings').catch(()=>({data:{}}));
    const att = cfg.data?.attendance ?? [];
    const getVal = key => att.find(s=>s.key===key)?.value;
    globalCheckIn   = getVal('attendance_default_check_in')       ?? '09:00';
    globalTolerance = parseInt(getVal('attendance_late_tolerance_minutes') ?? '0', 10);

    const emp = await apiFetch('GET','/employees-for-attendance?per_page=500').catch(()=>({data:[]}));
    employees = emp.data??[];
    const sel  = document.getElementById('empSel');
    const fEmp = document.getElementById('fEmp');
    employees.forEach(e=>{
        sel.innerHTML  += `<option value="${e.id}">${e.full_name} (${e.code})</option>`;
        fEmp.innerHTML += `<option value="${e.id}">${e.full_name}</option>`;
    });
    quickFilter('all');
}

// ══════════════════════════════════════════════
// TAB SWITCHING
// ══════════════════════════════════════════════
function switchTab(tab) {
    currentTab = tab;
    document.getElementById('viewLista').style.display  = tab==='lista'  ? '' : 'none';
    document.getElementById('viewSemana').style.display = tab==='semana' ? '' : 'none';
    document.getElementById('tabLista').classList.toggle('active',  tab==='lista');
    document.getElementById('tabSemana').classList.toggle('active', tab==='semana');
    document.getElementById('btnRegister').style.display = tab==='lista' ? '' : 'none';
    if(tab==='semana') loadWeek();
}

// ══════════════════════════════════════════════
// QUICK FILTERS (#27)
// ══════════════════════════════════════════════
function isoDate(d){ return d.toISOString().slice(0,10); }

function quickFilter(q) {
    activeQuick = q;
    ['qAll','qToday','qWeek','qMonth'].forEach(id=>document.getElementById(id).classList.remove('active'));
    document.getElementById({all:'qAll',today:'qToday',week:'qWeek',month:'qMonth'}[q]).classList.add('active');

    const today = new Date();
    let from='', to='';
    if(q==='today'){
        from = to = isoDate(today);
    } else if(q==='week'){
        const dow = today.getDay();
        const mon = new Date(today); mon.setDate(today.getDate()-(dow===0?6:dow-1));
        const fri = new Date(mon); fri.setDate(mon.getDate()+4);
        from = isoDate(mon); to = isoDate(fri);
    } else if(q==='month'){
        from = isoDate(new Date(today.getFullYear(), today.getMonth(), 1));
        to   = isoDate(new Date(today.getFullYear(), today.getMonth()+1, 0));
    }
    document.getElementById('fFrom').value = from;
    document.getElementById('fTo').value   = to;
    applyFilters();
}

// ══════════════════════════════════════════════
// LIST VIEW
// ══════════════════════════════════════════════
async function loadTable() {
    const tbody=document.getElementById('attBody');
    tbody.innerHTML='<tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('pagBar').style.display='none';
    document.getElementById('summaryBar').style.display='none';
    const q=new URLSearchParams({page,per_page:15,...filters});
    try {
        const res=await fetch(`${API}/attendances?${q}`,{credentials:'same-origin',headers:{Accept:'application/json'}});
        const json=await res.json();
        renderTable(json.data??[]);
        renderPag(json.meta);
        // Summary: load all records for current filter (no pagination) to count
        loadSummary();
    } catch(e){ tbody.innerHTML='<tr class="state-row"><td colspan="9">⚠️ Erro ao carregar.</td></tr>'; }
}

async function loadSummary() {
    try {
        const today = new Date().toISOString().slice(0, 10);
        // Se não há filtro de data activo, mostrar sempre os contadores de hoje
        const summaryFilters = (filters.date_from || filters.date_to)
            ? {...filters}
            : {date_from: today, date_to: today};

        const q = new URLSearchParams({...summaryFilters, per_page: 500, page: 1});
        // Manter filtro de funcionário se activo
        if (filters.employee_id) q.set('employee_id', filters.employee_id);

        const res = await fetch(`${API}/attendances?${q}`,{credentials:'same-origin',headers:{Accept:'application/json'}});
        const json = await res.json();
        const allRows = json.data ?? [];
        const lastPage = json.meta?.last_page ?? 1;
        if (lastPage > 1) {
            const extra = await Promise.all(
                Array.from({length: lastPage - 1}, (_, i) => {
                    const qp = new URLSearchParams({...summaryFilters, per_page: 500, page: i + 2});
                    return fetch(`${API}/attendances?${qp}`,{credentials:'same-origin',headers:{Accept:'application/json'}})
                        .then(r => r.json()).then(j => j.data ?? []);
                })
            );
            extra.forEach(batch => allRows.push(...batch));
        }
        const counts = {present:0, late:0, absent:0, on_leave:0, holiday:0};
        allRows.forEach(r => { counts[r.status] = (counts[r.status]??0) + 1; });
        const lbl = document.querySelector('.sum-today-lbl');
        if (lbl) {
            if (!filters.date_from && !filters.date_to) {
                lbl.textContent = 'Hoje';
            } else {
                lbl.textContent = summaryFilters.date_from === summaryFilters.date_to
                    ? summaryFilters.date_from
                    : `${summaryFilters.date_from} – ${summaryFilters.date_to}`;
            }
        }
        document.getElementById('sumPresent').textContent = counts.present;
        document.getElementById('sumLate').textContent    = counts.late;
        document.getElementById('sumAbsent').textContent  = counts.absent;
        document.getElementById('sumLeave').textContent   = counts.on_leave + (counts.holiday ?? 0);
        document.getElementById('summaryBar').style.display = 'flex';
    } catch(e){}
}

function renderTable(rows) {
    const tbody=document.getElementById('attBody');
    if(!rows.length){tbody.innerHTML='<tr class="state-row"><td colspan="9">Nenhum registo encontrado.</td></tr>';return;}
    tbody.innerHTML=rows.map(a=>{
        const d=a.date?new Date(a.date+'T00:00:00').toLocaleDateString('pt-PT'):'—';
        // (#29) Incomplete: has check_in but no check_out, and status is present/late
        const incomplete = a.check_in && !a.check_out && ['present','late'].includes(a.status);
        // Leave-generated record
        const fromLeave = !!a.leave_id;
        const rowCls = incomplete ? 'row-incomplete' : (fromLeave ? 'row-leave' : '');
        const dot    = incomplete ? '<span class="incomplete-dot" title="Sem hora de saída"></span>' : '';
        const leaveTag = fromLeave ? '<span class="leave-tag" title="Gerado automaticamente por licença">📋 Licença</span>' : '';
        const actions = fromLeave
            ? `<span style="font-size:.75rem;color:var(--text-muted);font-style:italic">Auto</span>`
            : `<button class="btn-sm btn-edit" onclick='openEdit(${JSON.stringify(a)})'>✏️ Editar</button>
               <button class="btn-sm btn-del"  onclick="openDelete(${a.id})">🗑</button>`;
        return `<tr class="${rowCls}">
            <td style="font-weight:600">${a.employee?.full_name??'—'}<br><span style="font-size:.74rem;color:var(--text-muted)">${a.employee?.code??''}</span></td>
            <td style="color:var(--text-muted)">${d}</td>
            <td>${a.check_in??'—'}</td>
            <td>${a.lunch_out??'—'}</td>
            <td>${a.lunch_in??'—'}</td>
            <td>${a.check_out??'—'}${dot}</td>
            <td>${a.worked_hours_formatted??'—'}</td>
            <td><span class="badge ${statusClass[a.status]??''}">${statusLabel[a.status]??a.status}</span>${leaveTag}</td>
            <td style="white-space:nowrap">${actions}</td>
        </tr>`;
    }).join('');
}

function renderPag(meta){
    if(!meta)return;
    document.getElementById('pagBar').style.display='flex';
    document.getElementById('pagInfo').textContent=`${meta.from??0}–${meta.to??0} de ${meta.total}`;
    const btns=document.getElementById('pagBtns'); btns.innerHTML='';
    const prev=document.createElement('button'); prev.textContent='‹'; prev.disabled=meta.current_page<=1; prev.onclick=()=>{page--;loadTable();}; btns.appendChild(prev);
    const start=Math.max(1,meta.current_page-3),end=Math.min(meta.last_page,start+6);
    for(let i=start;i<=end;i++){const b=document.createElement('button');b.textContent=i;if(i===meta.current_page)b.classList.add('active');b.onclick=(()=>{const p=i;return()=>{page=p;loadTable();}})();btns.appendChild(b);}
    const next=document.createElement('button'); next.textContent='›'; next.disabled=meta.current_page>=meta.last_page; next.onclick=()=>{page++;loadTable();}; btns.appendChild(next);
}

function applyFilters(){
    filters={};
    const from=document.getElementById('fFrom').value;
    const to  =document.getElementById('fTo').value;
    const e   =document.getElementById('fEmp').value;
    const s   =document.getElementById('fStatus').value;
    if(from) filters.date_from=from;
    if(to)   filters.date_to=to;
    if(e)    filters.employee_id=e;
    if(s)    filters.status=s;
    page=1; loadTable();
}

function resetFilters(){
    document.getElementById('fFrom').value='';
    document.getElementById('fTo').value='';
    document.getElementById('fEmp').value='';
    document.getElementById('fStatus').value='';
    ['qAll','qToday','qWeek','qMonth'].forEach(id=>document.getElementById(id).classList.remove('active'));
    document.getElementById('qAll').classList.add('active');
    filters={}; page=1; loadTable();
}

// ══════════════════════════════════════════════
// WEEK VIEW
// ══════════════════════════════════════════════
function getWeekDates(offset) {
    const today=new Date();
    const dow=today.getDay();
    const monday=new Date(today);
    monday.setDate(today.getDate()-(dow===0?6:dow-1)+offset*7);
    monday.setHours(0,0,0,0);
    const days=[];
    for(let i=0;i<5;i++){const d=new Date(monday);d.setDate(monday.getDate()+i);days.push(d);}
    return days;
}
function fmt(d){return d.toISOString().slice(0,10);}
function fmtDisplay(d){return d.toLocaleDateString('pt-PT',{day:'2-digit',month:'short'});}
function isToday(d){return fmt(d)===fmt(new Date());}

async function loadWeek() {
    const days=getWeekDates(weekOffset);
    const from=fmt(days[0]), to=fmt(days[4]);
    document.getElementById('weekLabel').textContent=`${fmtDisplay(days[0])} – ${fmtDisplay(days[4])} ${days[0].getFullYear()}`;
    const head=document.getElementById('weekHead');
    head.innerHTML='<tr><th style="text-align:left">Funcionário</th>'+
        days.map((d,i)=>`<th class="${isToday(d)?'today-col':''}">${DAY_NAMES[i]}<br><span style="font-weight:400;font-size:.8rem">${fmtDisplay(d)}</span></th>`).join('')+
        '</tr>';
    const tbody=document.getElementById('weekBody');
    if(!employees.length){tbody.innerHTML='<tr><td colspan="6" class="week-empty">Nenhum funcionário disponível.</td></tr>';return;}
    tbody.innerHTML='<tr><td colspan="6" class="week-empty"><span class="spinner"></span>A carregar...</td></tr>';
    try {
        const res=await fetch(`${API}/attendances?date_from=${from}&date_to=${to}&per_page=500`,{credentials:'same-origin',headers:{Accept:'application/json'}});
        const json=await res.json();
        weekRecords={};
        (json.data??[]).forEach(r=>{weekRecords[`${r.employee_id}_${r.date}`]=r;});
        renderWeekGrid(days);
    } catch(e){tbody.innerHTML='<tr><td colspan="6" class="week-empty">⚠️ Erro ao carregar.</td></tr>';}
}

function renderWeekGrid(days) {
    const tbody=document.getElementById('weekBody');
    tbody.innerHTML=employees.map(emp=>{
        const cells=days.map(d=>{
            const key=`${emp.id}_${fmt(d)}`;
            const rec=weekRecords[key];
            const status=rec?.status??null;
            const fromLeave=!!rec?.leave_id;
            const cls=status?(CELL_CLASS[status]??'cell-none'):'cell-none';
            const lbl=status?(CELL_LABEL[status]??status):'—';
            // Cells from approved leave: show label but disable cycling
            if(fromLeave){
                return `<td class="${isToday(d)?'today-col':''}">
                    <span class="cell-status ${cls}" style="cursor:default;opacity:.85" title="Gerado por licença aprovada">${lbl} 📋</span>
                </td>`;
            }
            return `<td class="${isToday(d)?'today-col':''}">
                <span class="cell-status ${cls}" data-emp="${emp.id}" data-date="${fmt(d)}" onclick="cycleStatus(this)">${lbl}</span>
            </td>`;
        }).join('');
        return `<tr><td><span style="font-weight:600">${emp.full_name}</span><br><span style="font-size:.73rem;color:var(--text-muted)">${emp.code??''}</span></td>${cells}</tr>`;
    }).join('');
}

async function cycleStatus(el) {
    if(el.classList.contains('saving')) return;
    const empId=el.dataset.emp, date=el.dataset.date, key=`${empId}_${date}`;
    const rec=weekRecords[key];
    const curStatus=rec?.status??null;
    const nextStatus=STATUS_CYCLE[(STATUS_CYCLE.indexOf(curStatus)+1)%STATUS_CYCLE.length];
    const prevHtml=el.innerHTML, prevClass=el.className;
    el.classList.add('saving');
    try {
        if(nextStatus===null){
            if(rec?.id){await apiFetch('DELETE',`/attendances/${rec.id}`);delete weekRecords[key];}
            el.className='cell-status cell-none'; el.textContent='—';
        } else if(rec?.id){
            const updated=await apiFetch('PUT',`/attendances/${rec.id}`,{employee_id:empId,date,status:nextStatus,check_in:rec.check_in??null,force:true});
            weekRecords[key]=updated.data??{...rec,status:nextStatus};
            el.className=`cell-status ${CELL_CLASS[nextStatus]??'cell-none'}`; el.textContent=CELL_LABEL[nextStatus]??nextStatus;
        } else {
            const created=await apiFetch('POST','/attendances',{employee_id:empId,date,status:nextStatus,force:true});
            weekRecords[key]=created.data;
            el.className=`cell-status ${CELL_CLASS[nextStatus]??'cell-none'}`; el.textContent=CELL_LABEL[nextStatus]??nextStatus;
        }
        const sv=document.getElementById('weekSaving'); sv.textContent='✓ Guardado'; setTimeout(()=>sv.textContent='',1200);
    } catch(err){el.className=prevClass;el.innerHTML=prevHtml;toast(err.message??'Erro ao guardar.','err');}
    finally{el.classList.remove('saving');}
}

// ══════════════════════════════════════════════
// LIST MODAL
// ══════════════════════════════════════════════
function openOverlay(id){document.getElementById(id).classList.add('open');}
function closeOverlay(id){document.getElementById(id).classList.remove('open');}

function openCreate(){
    editId=null;
    document.getElementById('attForm').reset();
    document.getElementById('formTitle').textContent='➕ Registar Presença';
    document.getElementById('submitBtn').textContent='Registar';
    document.getElementById('statusPreview').textContent='';
    hideConflict();
    document.getElementById('attForm').querySelector('[name="date"]').value=new Date().toISOString().slice(0,10);
    openOverlay('formOverlay');
}
function openEdit(a){
    editId=a.id;
    document.getElementById('attForm').reset();
    const form=document.getElementById('attForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    set('employee_id',a.employee_id); set('date',a.date); set('notes',a.notes??'');
    set('check_in',a.check_in??''); set('lunch_out',a.lunch_out??''); set('lunch_in',a.lunch_in??''); set('check_out',a.check_out??'');
    set('status',['holiday','on_leave'].includes(a.status)?a.status:'');
    updateStatusPreview();
    document.getElementById('formTitle').textContent='✏️ Editar Presença';
    document.getElementById('submitBtn').textContent='Guardar';
    openOverlay('formOverlay');
}
function updateStatusPreview(){
    const checkIn=document.getElementById('attForm').querySelector('[name="check_in"]').value;
    const statusSel=document.getElementById('attForm').querySelector('[name="status"]').value;
    const preview=document.getElementById('statusPreview');
    if(statusSel){preview.textContent='';return;}
    if(!checkIn){preview.textContent='⚪ Sem entrada → Ausente';preview.style.color='#ef4444';return;}
    const empId=document.getElementById('attForm').querySelector('[name="employee_id"]').value;
    const emp=employees.find(e=>String(e.id)===String(empId));
    const expected=emp?.expected_check_in?emp.expected_check_in.slice(0,5):globalCheckIn;
    const toMins=t=>{const[h,m]=t.split(':').map(Number);return h*60+m;};
    const limit=toMins(expected)+globalTolerance;
    const labelLimit=expected+(globalTolerance>0?` +${globalTolerance}min`:'');
    if(toMins(checkIn)<=limit){preview.textContent=`🟢 Presente (limite ${labelLimit})`;preview.style.color='#22c55e';}
    else{preview.textContent=`🟡 Atrasado (limite ${labelLimit})`;preview.style.color='#f59e0b';}
}
function hideConflict(){document.getElementById('conflictWarn').style.display='none';}
async function submitForm(e,force=false){
    if(e)e.preventDefault();
    hideConflict();
    const btn=document.getElementById('submitBtn');btn.disabled=true;btn.textContent='A guardar...';
    const data={};
    new FormData(document.getElementById('attForm')).forEach((v,k)=>{if(v!=='')data[k]=v;});
    if(force)data.force=true;
    try{
        if(editId)await apiFetch('PUT',`/attendances/${editId}`,data);
        else      await apiFetch('POST','/attendances',data);
        toast(editId?'Presença atualizada!':'Presença registada!','ok');
        closeOverlay('formOverlay'); loadTable();
    }catch(err){
        if(err.conflict){
            const sl={on_leave:'De Licença',absent:'Ausente',holiday:'Feriado',present:'Presente',late:'Atrasado'};
            document.getElementById('conflictMsg').textContent=
                `Este funcionário já tem um registo para esta data com status "${sl[err.existing?.status]??err.existing?.status??''}". Deseja criar igualmente?`;
            document.getElementById('conflictWarn').style.display='block';
        } else {toast(err.message??'Erro.','err');}
    }
    finally{btn.disabled=false;btn.textContent=editId?'Guardar':'Registar';}
}
function openDelete(id){deleteId=id;document.getElementById('delMsg').textContent='Tem certeza que deseja excluir este registo?';openOverlay('delOverlay');}
async function confirmDelete(){
    try{await apiFetch('DELETE',`/attendances/${deleteId}`);toast('Registo excluído.','ok');closeOverlay('delOverlay');loadTable();}
    catch(err){toast(err.message??'Erro.','err');}
}
function toast(msg,type='ok'){const w=document.getElementById('toastWrap');const t=document.createElement('div');t.className=`toast toast-${type}`;t.textContent=msg;w.appendChild(t);setTimeout(()=>t.remove(),3500);}
document.querySelectorAll('.overlay').forEach(o=>{o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');});});

Object.assign(window,{switchTab,quickFilter,openCreate,openEdit,submitForm,hideConflict,applyFilters,resetFilters,openDelete,confirmDelete,cycleStatus,loadWeek});
boot();
</script>
@endsection
