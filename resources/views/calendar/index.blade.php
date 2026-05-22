@extends('layouts.app')
@section('title','Calendário de Formações')
@section('page-title','Calendário de Formações')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<style>
.toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px}
.toolbar h2{font-size:1.25rem;font-weight:700}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--accent);color:#fff;border:none;padding:9px 20px;border-radius:9px;font-size:.875rem;font-weight:600;cursor:pointer;transition:.15s}
.btn-primary:hover{background:#4f46e5}
.filters{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px;align-items:center}
.f-input{background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit}
.f-input:focus{outline:none;border-color:var(--accent)}
.btn-reset{padding:9px 14px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.86rem;font-weight:600;transition:.15s}
.btn-reset:hover{border-color:var(--accent);color:var(--text-primary)}
.legend{display:flex;flex-wrap:wrap;gap:16px;margin-bottom:16px;align-items:center}
.legend-item{display:flex;align-items:center;gap:6px;font-size:.8rem;font-weight:600;color:var(--text-muted)}
.legend-dot{width:12px;height:12px;border-radius:3px;flex-shrink:0}
.legend-dot-enrolled{background:#6366f1}.legend-dot-completed{background:#16a34a}.legend-dot-failed{background:#dc2626}
.cal-hint{font-size:.78rem;color:var(--text-muted);margin-left:auto}
/* Multi-select */
.emp-picker{border:1px solid var(--border);border-radius:9px;background:var(--bg-dark);transition:border-color .2s;position:relative}
.emp-picker:focus-within{border-color:var(--accent)}
.emp-chips{display:flex;flex-wrap:wrap;gap:5px;padding:7px 10px;min-height:40px;cursor:text;align-items:center}
.emp-chip{display:inline-flex;align-items:center;gap:5px;background:rgba(99,102,241,.18);color:var(--accent-light);border-radius:6px;padding:3px 8px;font-size:.78rem;font-weight:600;white-space:nowrap}
.emp-chip button{background:none;border:none;cursor:pointer;color:inherit;opacity:.7;font-size:.9rem;line-height:1;padding:0;display:flex;align-items:center}
.emp-chip button:hover{opacity:1}
.emp-search{border:none;outline:none;background:transparent;color:var(--text-primary);font-size:.86rem;font-family:inherit;min-width:120px;flex:1;padding:2px 4px}
.emp-search::placeholder{color:var(--text-muted)}
.emp-dropdown{display:none;max-height:200px;overflow-y:auto;border-top:1px solid var(--border)}
.emp-dropdown.open{display:block}
.emp-opt{padding:8px 12px;font-size:.85rem;cursor:pointer;color:var(--text-primary);transition:background .1s;display:flex;align-items:center;justify-content:space-between;gap:8px}
.emp-opt:hover,.emp-opt.focused{background:rgba(99,102,241,.12)}
.emp-opt.selected{color:var(--accent-light)}
.emp-opt-check{font-size:.8rem;color:var(--accent-light);opacity:0;flex-shrink:0}
.emp-opt.selected .emp-opt-check{opacity:1}
.emp-empty{padding:10px 12px;font-size:.83rem;color:var(--text-muted);text-align:center;display:none}
.emp-count{font-size:.74rem;color:var(--accent-light);font-weight:600;margin-left:6px}
/* Calendar */
.cal-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:20px;overflow:hidden}
#calendar{--fc-border-color:var(--border);--fc-today-bg-color:var(--accent-glow);--fc-page-bg-color:var(--bg-card);--fc-neutral-bg-color:var(--bg-dark)}
#calendar .fc-button{background:var(--accent)!important;border-color:var(--accent)!important;font-weight:600;border-radius:8px!important;box-shadow:none!important;font-family:inherit;font-size:.83rem;padding:6px 14px}
#calendar .fc-button:hover{background:#4f46e5!important;border-color:#4f46e5!important}
#calendar .fc-button-active{background:#4338ca!important;border-color:#4338ca!important}
#calendar .fc-toolbar-title{font-size:1.05rem;font-weight:700;color:var(--text-primary)}
#calendar .fc-col-header-cell{background:rgba(255,255,255,.02);font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)}
#calendar .fc-day-today .fc-daygrid-day-number{color:var(--accent-light);font-weight:700}
#calendar .fc-event{border-radius:5px!important;font-size:.76rem;font-weight:600;cursor:pointer;transition:opacity .15s}
#calendar .fc-event:hover{opacity:.85}
#calendar .fc-daygrid-day-number{color:var(--text-muted);font-size:.82rem}
#calendar .fc-list-event-title{font-size:.86rem}
#calendar .fc-list-day-text,#calendar .fc-list-day-side-text{font-size:.82rem;color:var(--text-muted)}
#calendar .fc-scrollgrid{border-color:var(--border)!important}
#calendar td,#calendar th{border-color:var(--border)!important}
#calendar .fc-list-table td{border-color:var(--border)!important}
#calendar .fc-list-empty{color:var(--text-muted);font-size:.9rem}
#calendar .fc-daygrid-day:hover .fc-daygrid-day-frame{background:rgba(99,102,241,.05);cursor:pointer}
/* Overlays */
.overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:14px}
.overlay.open{display:flex}
.modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;width:100%;max-width:520px;box-shadow:0 24px 80px rgba(0,0,0,.5);max-height:90vh;overflow-y:auto}
.modal-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:20px}
.modal-title{font-size:1.05rem;font-weight:700;line-height:1.3}
.modal-close{background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.3rem;line-height:1;padding:2px;transition:color .15s;flex-shrink:0}
.modal-close:hover{color:var(--text-primary)}
/* Detail */
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.detail-item label{display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);margin-bottom:3px}
.detail-item span{font-size:.88rem;font-weight:500;color:var(--text-primary)}
.detail-item.full{grid-column:1/-1}
.status-pill{display:inline-block;padding:3px 10px;border-radius:6px;font-size:.76rem;font-weight:700}
.pill-enrolled{background:rgba(99,102,241,.15);color:#818cf8}
.pill-completed{background:rgba(34,197,94,.15);color:#22c55e}
.pill-failed{background:rgba(239,68,68,.12);color:#ef4444}
.validity-pill{display:inline-block;padding:3px 10px;border-radius:6px;font-size:.76rem;font-weight:700}
.validity-valid{background:rgba(34,197,94,.15);color:#22c55e}
.validity-expiring{background:rgba(245,158,11,.15);color:#f59e0b}
.validity-expired{background:rgba(239,68,68,.12);color:#ef4444}
.score-bar{height:6px;background:rgba(255,255,255,.08);border-radius:4px;margin-top:5px;overflow:hidden}
.score-fill{height:100%;border-radius:4px}
.detail-actions{display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)}
.btn-edit-detail{flex:1;padding:9px;border-radius:9px;background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.25);color:var(--accent-light);font-size:.86rem;font-weight:600;cursor:pointer;transition:.15s}
.btn-edit-detail:hover{background:rgba(99,102,241,.3)}
.btn-del-detail{padding:9px 16px;border-radius:9px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#ef4444;font-size:.86rem;font-weight:600;cursor:pointer;transition:.15s}
.btn-del-detail:hover{background:rgba(239,68,68,.22)}
/* Form */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.full{grid-column:1/-1}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:5px;text-transform:uppercase;letter-spacing:.4px}
.fg select,.fg input,.fg textarea{width:100%;background:var(--bg-dark);border:1px solid var(--border);border-radius:9px;padding:9px 12px;color:var(--text-primary);font-size:.875rem;font-family:inherit;transition:border-color .2s}
.fg select:focus,.fg input:focus,.fg textarea:focus{outline:none;border-color:var(--accent)}
.fg select option{background:var(--bg-card)}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}
.btn-cancel{padding:9px 20px;border-radius:9px;background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.875rem;font-weight:600}
.btn-cancel:hover{background:rgba(255,255,255,.1)}
.validity-hint{margin-top:5px;font-size:.75rem;color:var(--text-muted);min-height:16px}
.validity-hint.expired{color:#ef4444}.validity-hint.expiring{color:#f59e0b}.validity-hint.valid{color:#22c55e}
/* Confirm */
.confirm-modal{max-width:360px;text-align:center}
.confirm-modal p{color:var(--text-muted);font-size:.9rem;margin:8px 0 20px}
.confirm-modal .modal-foot{justify-content:center}
.btn-danger{padding:9px 22px;border-radius:9px;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:.875rem;font-weight:600}
.btn-danger:hover{opacity:.85}
/* Toast / Meta */
.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:8px}
.toast{padding:12px 18px;border-radius:10px;font-size:.86rem;font-weight:500;box-shadow:0 8px 32px rgba(0,0,0,.4);animation:slideIn .25s ease}
.toast-ok{background:rgba(34,197,94,.15);color:#22c55e;border:1px solid rgba(34,197,94,.25)}
.toast-err{background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.25)}
@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.cal-meta{display:flex;align-items:center;gap:12px;font-size:.82rem;color:var(--text-muted);margin-bottom:12px}
.cal-meta strong{color:var(--text-primary)}
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>📅 Calendário de Formações</h2>
    <button class="btn-primary" onclick="openCreateForm(null)">+ Nova Inscrição</button>
</div>

<div class="filters">
    <select id="fStatus" class="f-input" style="min-width:160px" onchange="reloadEvents()">
        <option value="">Todos os status</option>
        <option value="enrolled">Inscrito</option>
        <option value="completed">Concluído</option>
        <option value="failed">Reprovado</option>
    </select>
    <select id="fTraining" class="f-input" style="min-width:200px;max-width:280px" onchange="reloadEvents()">
        <option value="">Todas as formações</option>
        @foreach($trainings as $t)
            <option value="{{ $t->id }}">{{ $t->title }}</option>
        @endforeach
    </select>
    <select id="fEmployee" class="f-input" style="min-width:200px;max-width:280px" onchange="reloadEvents()">
        <option value="">Todos os funcionários</option>
        @foreach($employees as $e)
            <option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }} ({{ $e->code }})</option>
        @endforeach
    </select>
    <button class="btn-reset" onclick="clearFilters()">✕ Limpar</button>
</div>

<div class="legend">
    <span style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px">Legenda:</span>
    <div class="legend-item"><div class="legend-dot legend-dot-enrolled"></div> Inscrito</div>
    <div class="legend-item"><div class="legend-dot legend-dot-completed"></div> Concluído</div>
    <div class="legend-item"><div class="legend-dot legend-dot-failed"></div> Reprovado</div>
    <span class="cal-hint">💡 Clica num dia para criar uma inscrição</span>
</div>

<div class="cal-meta" id="calMeta" style="display:none">
    A mostrar <strong id="eventCount">0</strong> formação(ões) no período visível.
</div>

<div class="cal-card"><div id="calendar"></div></div>

{{-- Modal Detalhe --}}
<div class="overlay" id="detailOverlay">
<div class="modal">
    <div class="modal-header">
        <div class="modal-title" id="detailTraining">—</div>
        <button class="modal-close" onclick="closeOverlay('detailOverlay')">✕</button>
    </div>
    <div class="detail-grid">
        <div class="detail-item"><label>Funcionário</label><span id="detailEmployee">—</span></div>
        <div class="detail-item"><label>Código</label><span id="detailCode">—</span></div>
        <div class="detail-item"><label>Fornecedor</label><span id="detailProvider">—</span></div>
        <div class="detail-item"><label>Status</label><span id="detailStatus">—</span></div>
        <div class="detail-item"><label>Data de Início</label><span id="detailStart">—</span></div>
        <div class="detail-item"><label>Data de Fim</label><span id="detailEnd">—</span></div>
        <div class="detail-item" id="detailScoreRow" style="display:none">
            <label>Pontuação</label><span id="detailScore">—</span>
            <div class="score-bar"><div class="score-fill" id="detailScoreFill"></div></div>
        </div>
        <div class="detail-item" id="detailValidityRow" style="display:none"><label>Validade</label><span id="detailValidity">—</span></div>
        <div class="detail-item" id="detailExpiryRow" style="display:none"><label>Expira em</label><span id="detailExpiry">—</span></div>
        <div class="detail-item full" id="detailNotesRow" style="display:none"><label>Notas</label><span id="detailNotes">—</span></div>
    </div>
    <div class="detail-actions">
        <button class="btn-edit-detail" onclick="editFromDetail()">✏️ Editar</button>
        <button class="btn-del-detail"  onclick="deleteFromDetail()">🗑 Eliminar</button>
    </div>
</div>
</div>

{{-- Modal Formulário --}}
<div class="overlay" id="formOverlay">
<div class="modal">
    <div class="modal-header">
        <div class="modal-title" id="formTitle">➕ Nova Inscrição</div>
        <button class="modal-close" onclick="closeOverlay('formOverlay')">✕</button>
    </div>
    <form id="enrollForm" onsubmit="submitEnroll(event)">
        <div class="form-grid">
            <div class="fg full">
                <label>Funcionários * <span id="empCountLabel" class="emp-count"></span></label>
                <div class="emp-picker" id="empPicker">
                    <div class="emp-chips" id="empChips" onclick="document.getElementById('empSearch').focus()">
                        <input type="text" class="emp-search" id="empSearch"
                               placeholder="Pesquisar e selecionar funcionários..."
                               autocomplete="off"
                               oninput="filterEmpOptions()"
                               onkeydown="empSearchKeydown(event)"
                               onfocus="openEmpDropdown()">
                    </div>
                    <div class="emp-dropdown" id="empDropdown">
                        @foreach($employees as $e)
                        <div class="emp-opt"
                             data-id="{{ $e->id }}"
                             data-label="{{ $e->first_name }} {{ $e->last_name }} ({{ $e->code }})"
                             onclick="toggleEmp(this)">
                            <span>{{ $e->first_name }} {{ $e->last_name }}
                                <span style="color:var(--text-muted);font-size:.82rem">({{ $e->code }})</span>
                            </span>
                            <span class="emp-opt-check">✓</span>
                        </div>
                        @endforeach
                        <div class="emp-empty" id="empEmpty">Sem resultados</div>
                    </div>
                </div>
            </div>
            <div class="fg full">
                <label>Formação *</label>
                <select name="training_id" id="fTrainingForm" required>
                    <option value="">— Selecionar —</option>
                    @foreach($trainings as $t)
                        <option value="{{ $t->id }}">{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label>Status</label>
                <select name="status" id="fStatusForm" onchange="toggleScoreField()">
                    <option value="enrolled">Inscrito</option>
                    <option value="completed">Concluído</option>
                    <option value="failed">Reprovado</option>
                </select>
            </div>
            <div class="fg" id="scoreFieldWrap" style="display:none">
                <label>Pontuação (0–100)</label>
                <input name="score" type="number" min="0" max="100" step="0.1" placeholder="Ex: 85.5">
            </div>
            <div class="fg">
                <label>Data de Início</label>
                <input name="start_date" id="fStartDate" type="date">
            </div>
            <div class="fg">
                <label>Data de Fim</label>
                <input name="end_date" id="fEndDate" type="date" oninput="updateExpiryHint()">
            </div>
            <div class="fg">
                <label>Validade (meses)</label>
                <input name="validity_months" id="fValidity" type="number" min="1" max="120" placeholder="Ex: 12" oninput="updateExpiryHint()">
            </div>
            <div class="fg" style="display:flex;align-items:flex-end">
                <div style="width:100%">
                    <label>Expira em</label>
                    <div id="expiryHint" class="validity-hint" style="padding:8px 0">— preencha fim e validade</div>
                </div>
            </div>
            <div class="fg full">
                <label>Notas</label>
                <textarea name="notes" rows="2" placeholder="Opcional..."></textarea>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('formOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="formSubmitBtn">Inscrever</button>
        </div>
    </form>
</div>
</div>

{{-- Modal Confirmar --}}
<div class="overlay" id="confirmOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem;margin-bottom:10px">🗑️</div>
    <div class="modal-title">Confirmar Eliminação</div>
    <p>Tem a certeza que deseja eliminar esta inscrição?</p>
    <div class="modal-foot">
        <button class="btn-cancel" onclick="closeOverlay('confirmOverlay')">Cancelar</button>
        <button class="btn-danger" onclick="confirmDelete()">Eliminar</button>
    </div>
</div>
</div>

<div class="toast-wrap" id="toastWrap"></div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
const API  = '/api/v1';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let calendar, currentEnrollId = null, pendingDeleteId = null, currentDetailId = null, _lastEventProps = {};
const statusLabel = {enrolled:'Inscrito',completed:'Concluído',failed:'Reprovado'};
const statusPill  = {enrolled:'pill-enrolled',completed:'pill-completed',failed:'pill-failed'};
const validLabel  = {valid:'✅ Válida',expiring:'🔔 A expirar (30 dias)',expired:'⚠️ Expirada'};
const validPill   = {valid:'validity-valid',expiring:'validity-expiring',expired:'validity-expired'};

function openOverlay(id)  { document.getElementById(id).classList.add('open'); }
function closeOverlay(id) { document.getElementById(id).classList.remove('open'); }

function toast(msg, type='ok') {
    const w=document.getElementById('toastWrap'), t=document.createElement('div');
    t.className=`toast toast-${type}`; t.textContent=msg; w.appendChild(t);
    setTimeout(()=>t.remove(), 3500);
}

async function apiFetch(method, path, body) {
    const opts={method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}};
    if(body) opts.body=JSON.stringify(body);
    const r=await fetch(API+path,opts);
    if(!r.ok){const e=await r.json().catch(()=>({message:'Erro'}));throw e;}
    return r.status===204?null:r.json();
}

/* ── Multi-select funcionários ── */
let selectedEmps={}, empFocusIdx=-1;

function openEmpDropdown() {
    document.getElementById('empDropdown').classList.add('open');
    filterEmpOptions();
}
function closeEmpDropdown() {
    document.getElementById('empDropdown').classList.remove('open');
    document.getElementById('empSearch').value='';
    document.querySelectorAll('#empDropdown .emp-opt').forEach(o=>o.style.display='');
    document.getElementById('empEmpty').style.display='none';
    empFocusIdx=-1;
}
function filterEmpOptions() {
    const q=document.getElementById('empSearch').value.toLowerCase().trim();
    const opts=document.querySelectorAll('#empDropdown .emp-opt');
    let visible=0;
    opts.forEach(o=>{const m=!q||o.dataset.label.toLowerCase().includes(q);o.style.display=m?'':'none';if(m)visible++;});
    document.getElementById('empEmpty').style.display=visible===0?'':'none';
    empFocusIdx=-1;
}
function toggleEmp(optEl) {
    const id=optEl.dataset.id, label=optEl.dataset.label;
    if(selectedEmps[id]){delete selectedEmps[id];optEl.classList.remove('selected');}
    else{selectedEmps[id]=label;optEl.classList.add('selected');}
    renderEmpChips();
    document.getElementById('empSearch').focus();
}
function removeEmp(id) {
    delete selectedEmps[id];
    const opt=document.querySelector(`#empDropdown .emp-opt[data-id="${id}"]`);
    if(opt) opt.classList.remove('selected');
    renderEmpChips();
}
function renderEmpChips() {
    const container=document.getElementById('empChips');
    container.querySelectorAll('.emp-chip').forEach(c=>c.remove());
    const search=document.getElementById('empSearch');
    Object.entries(selectedEmps).forEach(([id,label])=>{
        const chip=document.createElement('span');
        chip.className='emp-chip'; chip.dataset.id=id;
        chip.innerHTML=`${label} <button type="button" onclick="removeEmp('${id}')" title="Remover">✕</button>`;
        container.insertBefore(chip, search);
    });
    const count=Object.keys(selectedEmps).length;
    document.getElementById('empCountLabel').textContent=count>0?`(${count} selecionado${count>1?'s':''})`:'';
}
function empSearchKeydown(e) {
    const dd=document.getElementById('empDropdown');
    const opts=[...dd.querySelectorAll('.emp-opt:not([style*="display: none"])')];
    if(e.key==='ArrowDown'){e.preventDefault();empFocusIdx=Math.min(empFocusIdx+1,opts.length-1);opts.forEach((o,i)=>o.classList.toggle('focused',i===empFocusIdx));if(opts[empFocusIdx])opts[empFocusIdx].scrollIntoView({block:'nearest'});}
    else if(e.key==='ArrowUp'){e.preventDefault();empFocusIdx=Math.max(empFocusIdx-1,0);opts.forEach((o,i)=>o.classList.toggle('focused',i===empFocusIdx));if(opts[empFocusIdx])opts[empFocusIdx].scrollIntoView({block:'nearest'});}
    else if(e.key==='Enter'){e.preventDefault();if(empFocusIdx>=0&&opts[empFocusIdx])toggleEmp(opts[empFocusIdx]);}
    else if(e.key==='Escape'){closeEmpDropdown();}
    else if(e.key==='Backspace'&&e.target.value===''){const ids=Object.keys(selectedEmps);if(ids.length)removeEmp(ids[ids.length-1]);}
}
function resetEmpPicker() {
    selectedEmps={};
    document.querySelectorAll('#empDropdown .emp-opt').forEach(o=>o.classList.remove('selected','focused'));
    renderEmpChips(); closeEmpDropdown();
}
function setEmpPickerSingle(id, label) {
    resetEmpPicker();
    if(!id) return;
    selectedEmps[id]=label;
    const opt=document.querySelector(`#empDropdown .emp-opt[data-id="${id}"]`);
    if(opt) opt.classList.add('selected');
    renderEmpChips();
}
document.addEventListener('click', function(e){
    const picker=document.getElementById('empPicker');
    if(picker&&!picker.contains(e.target)) closeEmpDropdown();
});

/* ── Filtros calendário ── */
function buildParams() {
    const p=new URLSearchParams();
    const s=document.getElementById('fStatus').value, t=document.getElementById('fTraining').value, e=document.getElementById('fEmployee').value;
    if(s)p.set('status',s); if(t)p.set('training_id',t); if(e)p.set('employee_id',e);
    return p;
}
function reloadEvents(){if(calendar)calendar.refetchEvents();}
function clearFilters(){['fStatus','fTraining','fEmployee'].forEach(id=>document.getElementById(id).value='');reloadEvents();}

/* ── Form helpers ── */
function toggleScoreField(){
    const st=document.getElementById('fStatusForm').value;
    document.getElementById('scoreFieldWrap').style.display=(st==='completed'||st==='failed')?'':'none';
}
function updateExpiryHint(){
    const endVal=document.getElementById('fEndDate').value, months=parseInt(document.getElementById('fValidity').value), hint=document.getElementById('expiryHint');
    if(!endVal||!months||months<1){hint.textContent='— preencha fim e validade';hint.className='validity-hint';return;}
    const expiry=new Date(endVal); expiry.setMonth(expiry.getMonth()+months);
    const today=new Date();today.setHours(0,0,0,0);
    const diff=Math.round((expiry-today)/864e5), fmt=expiry.toLocaleDateString('pt-PT');
    if(diff<0){hint.textContent=`Expirou em ${fmt}`;hint.className='validity-hint expired';}
    else if(diff<=30){hint.textContent=`Expira em ${fmt} (faltam ${diff} dias)`;hint.className='validity-hint expiring';}
    else{hint.textContent=`Válida até ${fmt}`;hint.className='validity-hint valid';}
}

/* ── Abrir form criar ── */
function openCreateForm(dateStr) {
    currentEnrollId=null;
    document.getElementById('enrollForm').reset();
    document.getElementById('formTitle').textContent='➕ Nova Inscrição';
    document.getElementById('formSubmitBtn').textContent='Inscrever';
    document.getElementById('expiryHint').textContent='— preencha fim e validade';
    document.getElementById('expiryHint').className='validity-hint';
    document.getElementById('scoreFieldWrap').style.display='none';
    resetEmpPicker();
    if(dateStr) document.getElementById('fStartDate').value=dateStr;
    openOverlay('formOverlay');
    setTimeout(()=>document.getElementById('empSearch').focus(), 120);
}

/* ── Abrir form editar ── */
function openEditForm(enrollment) {
    currentEnrollId=enrollment.id;
    document.getElementById('enrollForm').reset();
    document.getElementById('formTitle').textContent='✏️ Editar Inscrição';
    document.getElementById('formSubmitBtn').textContent='Guardar';
    setEmpPickerSingle(String(enrollment.employee_id), enrollment.employee_label??'');
    const form=document.getElementById('enrollForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    set('training_id',enrollment.training_id); set('status',enrollment.status);
    set('score',enrollment.score); set('start_date',enrollment.start_date);
    set('end_date',enrollment.end_date); set('validity_months',enrollment.validity_months);
    set('notes',enrollment.notes);
    toggleScoreField(); setTimeout(updateExpiryHint,30);
    openOverlay('formOverlay');
}

/* ── Submeter form ── */
async function submitEnroll(ev) {
    ev.preventDefault();
    const empIds=Object.keys(selectedEmps);
    if(empIds.length===0){toast('Seleciona pelo menos um funcionário.','err');document.getElementById('empSearch').focus();return;}
    const btn=document.getElementById('formSubmitBtn'); btn.disabled=true;
    const base={};
    new FormData(document.getElementById('enrollForm')).forEach((v,k)=>{if(v!=='')base[k]=v;});
    try {
        if(currentEnrollId) {
            base.employee_id=empIds[0]; btn.textContent='A guardar...';
            await apiFetch('PUT',`/enrollments/${currentEnrollId}`,base);
            toast('Inscrição atualizada!','ok');
        } else {
            btn.textContent=empIds.length>1?`A inscrever ${empIds.length}...`:'A inscrever...';
            const results=await Promise.allSettled(empIds.map(id=>apiFetch('POST','/enrollments',{...base,employee_id:id})));
            const ok=results.filter(r=>r.status==='fulfilled').length;
            const err=results.filter(r=>r.status==='rejected').length;
            if(ok>0&&err===0) toast(`${ok} inscrição(ões) criada(s) com sucesso!`,'ok');
            else if(ok>0)     toast(`${ok} criada(s), ${err} com erro.`,'ok');
            else              toast('Erro ao criar inscrições.','err');
        }
        closeOverlay('formOverlay'); closeOverlay('detailOverlay'); reloadEvents();
    } catch(err){toast(err.message??'Erro ao guardar.','err');}
    finally{btn.disabled=false;btn.textContent=currentEnrollId?'Guardar':'Inscrever';}
}

/* ── Modal detalhe ── */
function openDetail(info) {
    const p=info.event.extendedProps; currentDetailId=info.event.id;
    document.getElementById('detailTraining').textContent=p.training||info.event.title;
    document.getElementById('detailEmployee').textContent=p.employee||'—';
    document.getElementById('detailCode').textContent=p.employeeCode||'—';
    document.getElementById('detailProvider').textContent=p.provider||'—';
    document.getElementById('detailStart').textContent=p.start_date||'—';
    document.getElementById('detailEnd').textContent=p.end_date||'—';
    document.getElementById('detailStatus').innerHTML=`<span class="status-pill ${statusPill[p.status]??''}">${statusLabel[p.status]??p.status}</span>`;
    const scoreRow=document.getElementById('detailScoreRow');
    if(p.score!=null){scoreRow.style.display='';document.getElementById('detailScore').textContent=p.score+'%';const fill=document.getElementById('detailScoreFill');fill.style.width=p.score+'%';fill.style.background=p.score>=70?'#22c55e':p.score>=40?'#f59e0b':'#ef4444';}
    else scoreRow.style.display='none';
    const valRow=document.getElementById('detailValidityRow');
    if(p.validity_months){valRow.style.display='';document.getElementById('detailValidity').textContent=p.validity_months+' mês'+(p.validity_months>1?'es':'');}
    else valRow.style.display='none';
    const expRow=document.getElementById('detailExpiryRow');
    if(p.expiry_date){expRow.style.display='';const vs=p.validity_status;document.getElementById('detailExpiry').innerHTML=`${p.expiry_date} <span class="validity-pill ${validPill[vs]??''}">${validLabel[vs]??''}</span>`;}
    else expRow.style.display='none';
    const notesRow=document.getElementById('detailNotesRow');
    if(p.notes){notesRow.style.display='';document.getElementById('detailNotes').textContent=p.notes;}
    else notesRow.style.display='none';
    openOverlay('detailOverlay');
}
function editFromDetail(){if(!currentDetailId)return;closeOverlay('detailOverlay');openEditForm(_lastEventProps);}
function deleteFromDetail(){if(!currentDetailId)return;pendingDeleteId=currentDetailId;closeOverlay('detailOverlay');openOverlay('confirmOverlay');}
async function confirmDelete(){
    if(!pendingDeleteId)return;
    try{await apiFetch('DELETE',`/enrollments/${pendingDeleteId}`);toast('Inscrição eliminada.','ok');closeOverlay('confirmOverlay');reloadEvents();}
    catch(err){toast(err.message??'Erro ao eliminar.','err');}
    finally{pendingDeleteId=null;}
}

/* ── FullCalendar ── */
document.addEventListener('DOMContentLoaded', function(){
    calendar=new FullCalendar.Calendar(document.getElementById('calendar'),{
        locale:'pt', initialView:'dayGridMonth', height:'auto', firstDay:1,
        headerToolbar:{left:'prev,next today',center:'title',right:'dayGridMonth,timeGridWeek,listMonth'},
        buttonText:{today:'Hoje',month:'Mês',week:'Semana',list:'Lista'},
        noEventsText:'Nenhuma formação neste período.',
        eventDisplay:'block', dayMaxEvents:3, moreLinkText:n=>`+${n} mais`,
        selectable:true, selectMirror:true,

        events:function(fetchInfo,successCallback,failureCallback){
            const params=buildParams();
            params.set('start',fetchInfo.startStr.substring(0,10));
            params.set('end',fetchInfo.endStr.substring(0,10));
            fetch('/calendar/events?'+params.toString(),{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
            .then(r=>r.json())
            .then(data=>{
                const meta=document.getElementById('calMeta');
                document.getElementById('eventCount').textContent=data.length;
                meta.style.display=data.length>0?'flex':'none';
                successCallback(data);
            })
            .catch(err=>{console.error(err);failureCallback(err);});
        },

        dateClick:function(info){openCreateForm(info.dateStr);},

        select:function(info){
            openCreateForm(info.startStr);
            const endDate=new Date(info.endStr); endDate.setDate(endDate.getDate()-1);
            setTimeout(()=>{document.getElementById('fEndDate').value=endDate.toISOString().substring(0,10);updateExpiryHint();},50);
        },

        eventClick:function(info){
            const p=info.event.extendedProps;
            _lastEventProps={
                id:info.event.id, employee_id:p.employee_id,
                employee_label:(p.employee||'')+(p.employeeCode?` (${p.employeeCode})`:''),
                training_id:p.training_id, status:p.status, score:p.score,
                start_date:info.event.startStr?.substring(0,10), end_date:p.end_date_raw,
                validity_months:p.validity_months, notes:p.notes,
            };
            openDetail(info);
        },

        eventMouseEnter:function(info){info.el.style.transform='translateY(-1px)';info.el.style.boxShadow='0 4px 16px rgba(0,0,0,.3)';},
        eventMouseLeave:function(info){info.el.style.transform='';info.el.style.boxShadow='';},
    });
    calendar.render();
});

document.querySelectorAll('.overlay').forEach(o=>{o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');});});
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.overlay.open').forEach(o=>o.classList.remove('open'));});
</script>
@endsection
