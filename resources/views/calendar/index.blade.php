@extends('layouts.app')
@section('title','Calendário de Formações')
@section('page-title','Calendário de Formações')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<style>
/* ── Toolbar ── */
.toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px}
.toolbar h2{font-size:1.25rem;font-weight:700}

/* ── Filtros ── */
.filters{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px;align-items:center}
.f-input{background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit}
.f-input:focus{outline:none;border-color:var(--accent)}
.btn-reset{padding:9px 14px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.86rem;font-weight:600;transition:.15s}
.btn-reset:hover{border-color:var(--accent);color:var(--text-primary)}

/* ── Legenda ── */
.legend{display:flex;flex-wrap:wrap;gap:16px;margin-bottom:16px;align-items:center}
.legend-item{display:flex;align-items:center;gap:6px;font-size:.8rem;font-weight:600;color:var(--text-muted)}
.legend-dot{width:12px;height:12px;border-radius:3px;flex-shrink:0}
.legend-dot-enrolled {background:#6366f1}
.legend-dot-completed{background:#16a34a}
.legend-dot-failed   {background:#dc2626}

/* ── Card Calendário ── */
.cal-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:20px;overflow:hidden}

/* ── FullCalendar overrides (tema) ── */
#calendar{--fc-border-color:var(--border);--fc-today-bg-color:var(--accent-glow);--fc-page-bg-color:var(--bg-card);--fc-neutral-bg-color:var(--bg-dark);--fc-list-event-hover-bg-color:rgba(99,102,241,.08)}
#calendar .fc-button{background:var(--accent)!important;border-color:var(--accent)!important;font-weight:600;border-radius:8px!important;box-shadow:none!important;font-family:inherit;font-size:.83rem;padding:6px 14px}
#calendar .fc-button:hover{background:#4f46e5!important;border-color:#4f46e5!important}
#calendar .fc-button-active{background:#4338ca!important;border-color:#4338ca!important}
#calendar .fc-button:focus{box-shadow:0 0 0 2px var(--accent-glow)!important}
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

/* ── Modal de detalhe ── */
.overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:14px}
.overlay.open{display:flex}
.modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;width:100%;max-width:500px;box-shadow:0 24px 80px rgba(0,0,0,.5)}
.modal-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:20px}
.modal-title{font-size:1.05rem;font-weight:700;line-height:1.3}
.modal-close{background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.3rem;line-height:1;padding:2px;transition:color .15s;flex-shrink:0}
.modal-close:hover{color:var(--text-primary)}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.detail-item label{display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);margin-bottom:3px}
.detail-item span{font-size:.88rem;font-weight:500;color:var(--text-primary)}
.detail-item.full{grid-column:1/-1}
.status-pill{display:inline-block;padding:3px 10px;border-radius:6px;font-size:.76rem;font-weight:700}
.pill-enrolled {background:rgba(99,102,241,.15);color:#818cf8}
.pill-completed{background:rgba(34,197,94,.15);color:#22c55e}
.pill-failed   {background:rgba(239,68,68,.12);color:#ef4444}
.validity-pill{display:inline-block;padding:3px 10px;border-radius:6px;font-size:.76rem;font-weight:700}
.validity-valid   {background:rgba(34,197,94,.15);color:#22c55e}
.validity-expiring{background:rgba(245,158,11,.15);color:#f59e0b}
.validity-expired {background:rgba(239,68,68,.12);color:#ef4444}
.score-bar{height:6px;background:rgba(255,255,255,.08);border-radius:4px;margin-top:5px;overflow:hidden}
.score-fill{height:100%;border-radius:4px}
.modal-divider{border:none;border-top:1px solid var(--border);margin:18px 0}

/* ── Spinner ── */
.cal-spinner{display:flex;align-items:center;justify-content:center;height:400px;color:var(--text-muted);gap:10px}
.spinner{width:20px;height:20px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

/* ── Contador de eventos ── */
.cal-meta{display:flex;align-items:center;gap:12px;font-size:.82rem;color:var(--text-muted);margin-bottom:12px}
.cal-meta strong{color:var(--text-primary)}
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>📅 Calendário de Formações</h2>
</div>

<!-- Filtros -->
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

    <button class="btn-reset" onclick="clearFilters()">✕ Limpar filtros</button>
</div>

<!-- Legenda -->
<div class="legend">
    <span style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px">Legenda:</span>
    <div class="legend-item"><div class="legend-dot legend-dot-enrolled"></div> Inscrito</div>
    <div class="legend-item"><div class="legend-dot legend-dot-completed"></div> Concluído</div>
    <div class="legend-item"><div class="legend-dot legend-dot-failed"></div> Reprovado</div>
</div>

<!-- Contador -->
<div class="cal-meta" id="calMeta" style="display:none">
    A mostrar <strong id="eventCount">0</strong> formação(ões) no período visível.
</div>

<!-- Calendário -->
<div class="cal-card">
    <div id="calendar"></div>
</div>

<!-- Modal Detalhe -->
<div class="overlay" id="detailOverlay">
<div class="modal">
    <div class="modal-header">
        <div class="modal-title" id="detailTraining">—</div>
        <button class="modal-close" onclick="closeDetail()">✕</button>
    </div>
    <div class="detail-grid">
        <div class="detail-item">
            <label>Funcionário</label>
            <span id="detailEmployee">—</span>
        </div>
        <div class="detail-item">
            <label>Código</label>
            <span id="detailCode">—</span>
        </div>
        <div class="detail-item">
            <label>Fornecedor</label>
            <span id="detailProvider">—</span>
        </div>
        <div class="detail-item">
            <label>Status</label>
            <span id="detailStatus">—</span>
        </div>
        <div class="detail-item">
            <label>Data de Início</label>
            <span id="detailStart">—</span>
        </div>
        <div class="detail-item">
            <label>Data de Fim</label>
            <span id="detailEnd">—</span>
        </div>
        <div class="detail-item" id="detailScoreRow" style="display:none">
            <label>Pontuação</label>
            <span id="detailScore">—</span>
            <div class="score-bar"><div class="score-fill" id="detailScoreFill"></div></div>
        </div>
        <div class="detail-item" id="detailValidityRow" style="display:none">
            <label>Validade</label>
            <span id="detailValidity">—</span>
        </div>
        <div class="detail-item" id="detailExpiryRow" style="display:none">
            <label>Expira em</label>
            <span id="detailExpiry">—</span>
        </div>
        <div class="detail-item full" id="detailNotesRow" style="display:none">
            <label>Notas</label>
            <span id="detailNotes">—</span>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
let calendar;

const statusLabel = {enrolled:'Inscrito', completed:'Concluído', failed:'Reprovado'};
const statusPill  = {enrolled:'pill-enrolled', completed:'pill-completed', failed:'pill-failed'};
const validLabel  = {valid:'✅ Válida', expiring:'🔔 A expirar (30 dias)', expired:'⚠️ Expirada'};
const validPill   = {valid:'validity-valid', expiring:'validity-expiring', expired:'validity-expired'};

function buildParams() {
    const params = new URLSearchParams();
    const status   = document.getElementById('fStatus').value;
    const training = document.getElementById('fTraining').value;
    const employee = document.getElementById('fEmployee').value;
    if (status)   params.set('status', status);
    if (training) params.set('training_id', training);
    if (employee) params.set('employee_id', employee);
    return params;
}

function reloadEvents() {
    if (calendar) calendar.refetchEvents();
}

function clearFilters() {
    document.getElementById('fStatus').value   = '';
    document.getElementById('fTraining').value = '';
    document.getElementById('fEmployee').value = '';
    reloadEvents();
}

function closeDetail() {
    document.getElementById('detailOverlay').classList.remove('open');
}

function openDetail(info) {
    const p = info.event.extendedProps;

    document.getElementById('detailTraining').textContent  = info.event.extendedProps.training || info.event.title;
    document.getElementById('detailEmployee').textContent  = p.employee  || '—';
    document.getElementById('detailCode').textContent      = p.employeeCode || '—';
    document.getElementById('detailProvider').textContent  = p.provider  || '—';
    document.getElementById('detailStart').textContent     = p.start_date || '—';
    document.getElementById('detailEnd').textContent       = p.end_date   || '—';

    // Status
    const statusEl = document.getElementById('detailStatus');
    statusEl.innerHTML = `<span class="status-pill ${statusPill[p.status]??''}">${statusLabel[p.status]??p.status}</span>`;

    // Pontuação
    const scoreRow = document.getElementById('detailScoreRow');
    if (p.score != null) {
        scoreRow.style.display = '';
        document.getElementById('detailScore').textContent = p.score + '%';
        const fill = document.getElementById('detailScoreFill');
        fill.style.width = p.score + '%';
        fill.style.background = p.score >= 70 ? '#22c55e' : p.score >= 40 ? '#f59e0b' : '#ef4444';
    } else {
        scoreRow.style.display = 'none';
    }

    // Validade
    const valRow = document.getElementById('detailValidityRow');
    if (p.validity_months) {
        valRow.style.display = '';
        document.getElementById('detailValidity').textContent = p.validity_months + ' mês' + (p.validity_months > 1 ? 'es' : '');
    } else {
        valRow.style.display = 'none';
    }

    // Data de expiração
    const expRow = document.getElementById('detailExpiryRow');
    if (p.expiry_date) {
        expRow.style.display = '';
        const vs = p.validity_status;
        document.getElementById('detailExpiry').innerHTML =
            `${p.expiry_date} <span class="validity-pill ${validPill[vs]??''}">${validLabel[vs]??''}</span>`;
    } else {
        expRow.style.display = 'none';
    }

    // Notas
    const notesRow = document.getElementById('detailNotesRow');
    if (p.notes) {
        notesRow.style.display = '';
        document.getElementById('detailNotes').textContent = p.notes;
    } else {
        notesRow.style.display = 'none';
    }

    document.getElementById('detailOverlay').classList.add('open');
}

document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(el, {
        locale: 'pt',
        initialView: 'dayGridMonth',
        height: 'auto',
        firstDay: 1, // Segunda-feira
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today:     'Hoje',
            month:     'Mês',
            week:      'Semana',
            list:      'Lista',
        },
        noEventsText: 'Nenhuma formação neste período.',
        eventDisplay: 'block',
        dayMaxEvents: 3,
        moreLinkText: function(n) { return '+' + n + ' mais'; },

        events: function(fetchInfo, successCallback, failureCallback) {
            const params = buildParams();
            params.set('start', fetchInfo.startStr.substring(0, 10));
            params.set('end',   fetchInfo.endStr.substring(0, 10));

            fetch('/calendar/events?' + params.toString(), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                // Atualizar contador
                const meta = document.getElementById('calMeta');
                document.getElementById('eventCount').textContent = data.length;
                meta.style.display = data.length > 0 ? 'flex' : 'none';
                successCallback(data);
            })
            .catch(err => {
                console.error(err);
                failureCallback(err);
            });
        },

        eventClick: function(info) {
            openDetail(info);
        },

        eventMouseEnter: function(info) {
            info.el.style.transform = 'translateY(-1px)';
            info.el.style.boxShadow = '0 4px 16px rgba(0,0,0,.3)';
        },
        eventMouseLeave: function(info) {
            info.el.style.transform = '';
            info.el.style.boxShadow = '';
        },
    });

    calendar.render();
});

// Fechar modal ao clicar fora
document.getElementById('detailOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDetail();
});
</script>
@endsection
