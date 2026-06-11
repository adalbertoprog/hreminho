@extends('layouts.app')
@section('title','Calendário')
@section('page-title','Calendário')

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
/* Event type filter chips */
.type-filters{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;align-items:center}
.type-chip{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:.78rem;font-weight:700;cursor:pointer;border:2px solid transparent;transition:.15s;user-select:none}
.type-chip.active{border-color:currentColor;opacity:1}
.type-chip:not(.active){opacity:.45}
.type-chip-training{background:rgba(99,102,241,.15);color:#818cf8}
.type-chip-leave{background:rgba(8,145,178,.15);color:#22d3ee}
.type-chip-attendance{background:rgba(239,68,68,.12);color:#f87171}
/* Legend additions */
.legend-dot-vacation{background:#0891b2}.legend-dot-sick{background:#d97706}.legend-dot-unpaid{background:#7c3aed}
.legend-dot-absent{background:#ef4444}.legend-dot-late{background:#f59e0b}
.legend-dot-project{background:#059669}.legend-dot-team{background:#10b981}
/* Project detail */
.proj-detail-teams{margin-top:14px}
.proj-team-card{background:var(--bg-dark);border:1px solid var(--border);border-radius:10px;padding:12px 14px;margin-bottom:8px}
.proj-team-name{font-weight:700;font-size:.9rem;margin-bottom:6px;display:flex;align-items:center;gap:8px}
.proj-team-leader{font-size:.78rem;color:var(--text-muted);margin-bottom:8px}
.proj-members{display:flex;flex-direction:column;gap:4px}
.proj-member{display:flex;justify-content:space-between;align-items:center;font-size:.81rem;padding:4px 8px;border-radius:6px;background:rgba(255,255,255,.04)}
.proj-member-name{font-weight:600}
.proj-member-role{font-size:.74rem;color:var(--text-muted);margin-top:1px}
.proj-member-code{font-family:monospace;font-size:.74rem;color:var(--text-muted)}
.proj-veh-row{display:flex;align-items:center;gap:8px;font-size:.81rem;padding:4px 8px;border-radius:6px;background:rgba(255,255,255,.04);margin-top:4px}
.section-mini-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin:8px 0 4px}
/* Leave detail */
.leave-type-badge{display:inline-block;padding:3px 10px;border-radius:6px;font-size:.76rem;font-weight:700}
.badge-vacation{background:rgba(8,145,178,.15);color:#22d3ee}
.badge-sick{background:rgba(217,119,6,.15);color:#fbbf24}
.badge-unpaid{background:rgba(124,58,237,.15);color:#a78bfa}
.badge-pending{background:rgba(245,158,11,.15);color:#f59e0b}
.badge-approved{background:rgba(34,197,94,.15);color:#22c55e}
.badge-rejected{background:rgba(239,68,68,.12);color:#ef4444}
/* project status badges */
.badge{display:inline-block;padding:3px 10px;border-radius:6px;font-size:.76rem;font-weight:700}
.badge-active{background:rgba(5,150,105,.15);color:#34d399}
.badge-planned{background:rgba(99,102,241,.15);color:#a5b4fc}
.badge-completed{background:rgba(107,114,128,.18);color:#9ca3af}
.badge-cancelled{background:rgba(239,68,68,.12);color:#ef4444}
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>📅 Calendário</h2>
    <button class="btn-primary" id="calCreateBtn">+ Nova Inscrição</button>
</div>

<div class="type-filters">
    <span style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px">Mostrar:</span>
    <span class="type-chip type-chip-training"   data-type="trainings"   onclick="toggleType(this)">📚 Formações</span>
    <span class="type-chip type-chip-leave"      data-type="leaves"      onclick="toggleType(this)">🏖️ Licenças</span>
    <span class="type-chip type-chip-attendance" data-type="attendances" onclick="toggleType(this)">⚠️ Ausências/Atrasos</span>
    <span class="type-chip type-chip-project active" data-type="projects" onclick="toggleType(this)" style="background:rgba(5,150,105,.15);color:#34d399">🏗️ Obras/Equipas</span>
</div>

<div class="filters">
    {{-- Formações --}}
    <span id="fStatusWrap">
        <select id="fStatus" class="f-input" style="min-width:160px" onchange="reloadEvents()">
            <option value="">Todos os status</option>
            <option value="enrolled">Inscrito</option>
            <option value="completed">Concluído</option>
            <option value="failed">Reprovado</option>
        </select>
    </span>
    <span id="fTrainingWrap">
        <select id="fTraining" class="f-input" style="min-width:200px;max-width:280px" onchange="reloadEvents()">
            <option value="">Todas as formações</option>
            @foreach($trainings as $t)
                <option value="{{ $t->id }}">{{ $t->title }}</option>
            @endforeach
        </select>
    </span>
    {{-- Funcionários (partilhado: formações + licenças + presenças) --}}
    <span id="fEmployeeWrap">
        <select id="fEmployee" class="f-input" style="min-width:200px;max-width:280px" onchange="reloadEvents()">
            <option value="">Todos os funcionários</option>
            @foreach($employees as $e)
                <option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }} ({{ $e->code }})</option>
            @endforeach
        </select>
    </span>
    {{-- Obras --}}
    <span id="fProjectWrap">
        <select id="fProject" class="f-input" style="min-width:200px;max-width:280px" onchange="reloadEvents()">
            <option value="">Todas as obras</option>
            @foreach($projects as $p)
                <option value="{{ $p->id }}">{{ $p->name }}{{ $p->reference ? ' ('.$p->reference.')' : '' }}</option>
            @endforeach
        </select>
    </span>
    <button class="btn-reset" onclick="clearFilters()">✕ Limpar</button>
</div>

<div class="legend">
    <span style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px">Legenda:</span>
    {{-- trainings --}}
    <div class="legend-item" data-type="trainings"><div class="legend-dot legend-dot-enrolled"></div> Inscrito</div>
    <div class="legend-item" data-type="trainings"><div class="legend-dot legend-dot-completed"></div> Concluído</div>
    <div class="legend-item" data-type="trainings"><div class="legend-dot legend-dot-failed"></div> Reprovado</div>
    {{-- leaves --}}
    <span class="legend-sep" data-type="leaves" style="margin-left:4px"></span>
    <div class="legend-item" data-type="leaves"><div class="legend-dot legend-dot-vacation"></div> Férias</div>
    <div class="legend-item" data-type="leaves"><div class="legend-dot legend-dot-sick"></div> Doença</div>
    <div class="legend-item" data-type="leaves"><div class="legend-dot legend-dot-unpaid"></div> N.Rem.</div>
    {{-- attendances --}}
    <span class="legend-sep" data-type="attendances" style="margin-left:4px"></span>
    <div class="legend-item" data-type="attendances"><div class="legend-dot legend-dot-absent"></div> Ausente</div>
    <div class="legend-item" data-type="attendances"><div class="legend-dot legend-dot-late"></div> Atrasado</div>
    {{-- projects --}}
    <span class="legend-sep" data-type="projects" style="margin-left:4px"></span>
    <div class="legend-item" data-type="projects"><div class="legend-dot legend-dot-project"></div> Obra</div>
    <div class="legend-item" data-type="projects"><div class="legend-dot legend-dot-team"></div> Equipa</div>
    <span class="cal-hint" id="calHint">💡 Clica num dia para criar uma inscrição</span>
</div>

<div class="cal-meta" id="calMeta" style="display:none">
    A mostrar <strong id="eventCount">0</strong><span class="meta-noun"> evento(s) no período visível.</span>
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

{{-- Modal Detalhe Licença --}}
<div class="overlay" id="leaveDetailOverlay">
<div class="modal">
    <div class="modal-header">
        <div class="modal-title" id="ldTitle">—</div>
        <button class="modal-close" onclick="closeOverlay('leaveDetailOverlay')">✕</button>
    </div>
    <div class="detail-grid">
        <div class="detail-item"><label>Funcionário</label><span id="ldEmployee">—</span></div>
        <div class="detail-item"><label>Código</label><span id="ldCode">—</span></div>
        <div class="detail-item"><label>Tipo</label><span id="ldType">—</span></div>
        <div class="detail-item"><label>Estado</label><span id="ldStatus">—</span></div>
        <div class="detail-item"><label>Início</label><span id="ldStart">—</span></div>
        <div class="detail-item"><label>Fim</label><span id="ldEnd">—</span></div>
        <div class="detail-item full" id="ldReasonRow"><label>Motivo</label><span id="ldReason">—</span></div>
        <div class="detail-item full" id="ldCommentRow" style="display:none"><label>Comentário</label><span id="ldComment">—</span></div>
    </div>
    <div class="detail-actions">
        <button class="btn-edit-detail" onclick="goToLeave()">✏️ Ver em Licenças</button>
    </div>
</div>
</div>

{{-- Modal Detalhe Presença --}}
<div class="overlay" id="attDetailOverlay">
<div class="modal">
    <div class="modal-header">
        <div class="modal-title" id="adTitle">—</div>
        <button class="modal-close" onclick="closeOverlay('attDetailOverlay')">✕</button>
    </div>
    <div class="detail-grid">
        <div class="detail-item"><label>Funcionário</label><span id="adEmployee">—</span></div>
        <div class="detail-item"><label>Código</label><span id="adCode">—</span></div>
        <div class="detail-item"><label>Data</label><span id="adDate">—</span></div>
        <div class="detail-item"><label>Estado</label><span id="adStatus">—</span></div>
        <div class="detail-item" id="adCheckInRow"><label>Entrada</label><span id="adCheckIn">—</span></div>
        <div class="detail-item" id="adCheckOutRow"><label>Saída</label><span id="adCheckOut">—</span></div>
        <div class="detail-item full" id="adNotesRow" style="display:none"><label>Notas</label><span id="adNotes">—</span></div>
    </div>
    <div class="detail-actions">
        <button class="btn-edit-detail" onclick="goToAttendances()">✏️ Ver em Presenças</button>
    </div>
</div>
</div>

{{-- Modal Detalhe Obra/Equipa --}}
<div class="overlay" id="projDetailOverlay">
<div class="modal" style="max-width:560px">
    <div class="modal-header">
        <div class="modal-title" id="pdTitle">—</div>
        <button class="modal-close" onclick="closeOverlay('projDetailOverlay')">✕</button>
    </div>
    <div class="detail-grid" id="pdMeta">
        <div class="detail-item"><label>Cliente</label><span id="pdClient">—</span></div>
        <div class="detail-item"><label>Localização</label><span id="pdLocation">—</span></div>
        <div class="detail-item"><label>Início</label><span id="pdStart">—</span></div>
        <div class="detail-item"><label>Fim Previsto</label><span id="pdEnd">—</span></div>
        <div class="detail-item" id="pdRefRow"><label>Centro de Custos</label><span id="pdRef">—</span></div>
        <div class="detail-item"><label>Estado</label><span id="pdStatus">—</span></div>
    </div>
    <div class="proj-detail-teams" id="pdTeams"></div>
    <div class="detail-actions">
        <button class="btn-edit-detail" onclick="window.location.href='/projects'">🏗️ Ver em Obras e Equipas</button>
    </div>
</div>
</div>

<div class="toast-wrap" id="toastWrap"></div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
@vite('resources/js/pages/calendar.js')
@endsection
