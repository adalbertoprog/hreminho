@extends('layouts.app')
@section('title','As Minhas Obras')
@section('page-title','As Minhas Obras')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px}
.page-header h2{font-size:1.2rem;font-weight:700}

/* Stats strip */
.stats-strip{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:24px}
.stat-card{flex:1;min-width:130px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:14px 18px}
.stat-card .val{font-size:1.5rem;font-weight:800;line-height:1}
.stat-card .lbl{font-size:.75rem;color:var(--text-muted);margin-top:4px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
.stat-card.green .val{color:#34d399}
.stat-card.indigo .val{color:#818cf8}
.stat-card.gray .val{color:#9ca3af}

/* Project cards */
.proj-list{display:flex;flex-direction:column;gap:16px;margin-bottom:32px}
.proj-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden;transition:.15s}
.proj-card:hover{border-color:rgba(99,102,241,.4);box-shadow:0 4px 20px rgba(0,0,0,.15)}
.proj-card-header{display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border)}
.proj-status-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.proj-status-dot.active{background:#059669}
.proj-status-dot.planned{background:#6366f1}
.proj-status-dot.completed{background:#6b7280}
.proj-status-dot.cancelled{background:#ef4444}
.proj-card-title{font-weight:700;font-size:.95rem;flex:1}
.proj-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:6px;font-size:.74rem;font-weight:700}
.badge-active{background:rgba(5,150,105,.15);color:#34d399}
.badge-planned{background:rgba(99,102,241,.15);color:#a5b4fc}
.badge-completed{background:rgba(107,114,128,.18);color:#9ca3af}
.badge-cancelled{background:rgba(239,68,68,.12);color:#ef4444}
.proj-card-body{padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px 20px}
.proj-meta-item label{display:block;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:2px}
.proj-meta-item span{font-size:.85rem;font-weight:500}

/* Team section inside card */
.proj-team-section{padding:0 20px 16px}
.team-header{display:flex;align-items:center;gap:8px;font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:10px}
.team-box{background:var(--bg-dark);border:1px solid var(--border);border-radius:10px;padding:12px 14px;margin-bottom:8px}
.team-box-title{font-weight:700;font-size:.88rem;margin-bottom:8px;display:flex;align-items:center;gap:8px}
.team-role-pill{background:rgba(99,102,241,.15);color:#a5b4fc;padding:2px 8px;border-radius:5px;font-size:.72rem;font-weight:700}
.team-members{display:flex;flex-wrap:wrap;gap:6px;margin-top:6px}
.member-chip{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:6px;padding:3px 9px;font-size:.78rem}
.member-chip.me{background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.3);color:#a5b4fc;font-weight:700}
.veh-chip{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:6px;padding:3px 9px;font-size:.78rem;color:var(--text-muted)}

/* Empty state */
.empty-state{text-align:center;padding:60px 20px;color:var(--text-muted)}
.empty-state .icon{font-size:3rem;margin-bottom:12px}
.empty-state p{font-size:.9rem}

/* Calendar */
.section-title{font-size:1rem;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.cal-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:20px;margin-bottom:32px}
#empCalendar{--fc-border-color:var(--border);--fc-today-bg-color:var(--accent-glow);--fc-page-bg-color:var(--bg-card);--fc-neutral-bg-color:var(--bg-dark)}
#empCalendar .fc-button{background:var(--accent)!important;border-color:var(--accent)!important;font-weight:600;border-radius:8px!important;box-shadow:none!important;font-family:inherit;font-size:.83rem;padding:6px 14px}
#empCalendar .fc-button:hover{background:#4f46e5!important;border-color:#4f46e5!important}
#empCalendar .fc-button-active{background:#4338ca!important;border-color:#4338ca!important}
#empCalendar .fc-toolbar-title{font-size:1rem;font-weight:700;color:var(--text-primary)}
#empCalendar .fc-col-header-cell{background:rgba(255,255,255,.02);font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)}
#empCalendar .fc-day-today .fc-daygrid-day-number{color:var(--accent-light);font-weight:700}
#empCalendar .fc-event{border-radius:5px!important;font-size:.75rem;font-weight:600;cursor:pointer;transition:opacity .15s}
#empCalendar .fc-event:hover{opacity:.85}
#empCalendar .fc-daygrid-day-number{color:var(--text-muted);font-size:.82rem}
#empCalendar td,#empCalendar th{border-color:var(--border)!important}
#empCalendar .fc-scrollgrid{border-color:var(--border)!important}
#empCalendar .fc-list-empty{color:var(--text-muted);font-size:.9rem}

/* Event detail overlay */
.overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:14px}
.overlay.open{display:flex}
.modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;width:100%;max-width:480px;box-shadow:0 24px 80px rgba(0,0,0,.5);max-height:90vh;overflow-y:auto}
.modal-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:18px}
.modal-title{font-size:1rem;font-weight:700;line-height:1.3}
.modal-close{background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.3rem;line-height:1;padding:2px;transition:color .15s;flex-shrink:0}
.modal-close:hover{color:var(--text-primary)}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.detail-item label{display:block;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);margin-bottom:2px}
.detail-item span{font-size:.86rem;font-weight:500}
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>🏗️ As Minhas Obras</h2>
</div>

@if(! $employee)
<div class="empty-state">
    <div class="icon">🔗</div>
    <p>A sua conta ainda não está associada a um funcionário.<br>Aceda ao dashboard para associar.</p>
</div>
@elseif($teams->isEmpty())
<div class="empty-state">
    <div class="icon">🏗️</div>
    <p>Ainda não está associado a nenhuma equipa ou obra.</p>
</div>
@else

{{-- Stats --}}
@php
    $projects   = $teams->map(fn($t) => $t->project)->filter()->unique('id');
    $active     = $projects->where('status','active')->count();
    $planned    = $projects->where('status','planned')->count();
    $completed  = $projects->where('status','completed')->count();
    $statusLabels = ['active'=>'Em Curso','planned'=>'Planeada','completed'=>'Concluída','cancelled'=>'Cancelada'];
@endphp
<div class="stats-strip">
    <div class="stat-card green"><div class="val">{{ $active }}</div><div class="lbl">Em Curso</div></div>
    <div class="stat-card indigo"><div class="val">{{ $planned }}</div><div class="lbl">Planeadas</div></div>
    <div class="stat-card gray"><div class="val">{{ $completed }}</div><div class="lbl">Concluídas</div></div>
    <div class="stat-card"><div class="val">{{ $teams->count() }}</div><div class="lbl">Equipas</div></div>
</div>

{{-- Calendário --}}
<p class="section-title">📅 Calendário de Obras</p>
<div class="cal-card">
    <div id="empCalendar"></div>
</div>

{{-- Lista de obras por equipa --}}
<p class="section-title">📋 Detalhe das Equipas</p>
<div class="proj-list">
@foreach($teams as $team)
@php $proj = $team->project; @endphp
@if($proj)
<div class="proj-card">
    <div class="proj-card-header">
        <span class="proj-status-dot {{ $proj->status }}"></span>
        <span class="proj-card-title">{{ $proj->name }}</span>
        @if($proj->reference)<span style="font-size:.78rem;color:var(--text-muted);font-family:monospace">{{ $proj->reference }}</span>@endif
        <span class="proj-badge badge-{{ $proj->status }}">{{ $statusLabels[$proj->status] ?? $proj->status }}</span>
    </div>
    <div class="proj-card-body">
        @if($proj->client)
        <div class="proj-meta-item"><label>Cliente</label><span>{{ $proj->client }}</span></div>
        @endif
        @if($proj->location)
        <div class="proj-meta-item"><label>Localização</label><span>{{ $proj->location }}</span></div>
        @endif
        @if($proj->start_date)
        <div class="proj-meta-item"><label>Início</label><span>{{ $proj->start_date->format('d/m/Y') }}</span></div>
        @endif
        @if($proj->end_date)
        <div class="proj-meta-item"><label>Fim Previsto</label><span>{{ $proj->end_date->format('d/m/Y') }}</span></div>
        @endif
        @if($proj->notes)
        <div class="proj-meta-item" style="grid-column:1/-1"><label>Notas</label><span>{{ $proj->notes }}</span></div>
        @endif
    </div>
    <div class="proj-team-section">
        <div class="team-header">👷 Equipa</div>
        <div class="team-box">
            <div class="team-box-title">
                {{ $team->name }}
                @if($team->leader)
                    <span style="font-size:.8rem;color:var(--text-muted);font-weight:400">— Líder: {{ $team->leader->first_name }} {{ $team->leader->last_name }}</span>
                @endif
                @php
                    $myPivot = $team->employees->firstWhere('id', $employee->id);
                    $myRole  = $myPivot?->pivot?->role;
                @endphp
                @if($myRole)<span class="team-role-pill">{{ $myRole }}</span>@endif
            </div>
            {{-- Membros --}}
            @if($team->employees->isNotEmpty())
            <div class="team-members">
                @foreach($team->employees as $member)
                <span class="member-chip {{ $member->id === $employee->id ? 'me' : '' }}">
                    {{ $member->first_name }} {{ $member->last_name }}
                    <span style="opacity:.6;font-size:.72rem">({{ $member->code }})</span>
                    @if($member->id === $employee->id)<span style="font-size:.7rem">✓ Eu</span>@endif
                </span>
                @endforeach
            </div>
            @endif
            {{-- Viaturas --}}
            @if($team->vehicles->isNotEmpty())
            <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:6px">
                @foreach($team->vehicles as $veh)
                <span class="veh-chip">🚗 {{ $veh->plate }}{{ $veh->brand ? ' — '.$veh->brand.' '.($veh->model ?? '') : '' }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endif
@endforeach
</div>

@endif {{-- end if teams not empty --}}

{{-- Event detail overlay --}}
<div class="overlay" id="projEventOverlay">
<div class="modal">
    <div class="modal-header">
        <div class="modal-title" id="pevTitle">—</div>
        <button class="modal-close" onclick="document.getElementById('projEventOverlay').classList.remove('open')">✕</button>
    </div>
    <div class="detail-grid">
        <div class="detail-item"><label>Equipa</label><span id="pevTeam">—</span></div>
        <div class="detail-item"><label>Estado</label><span id="pevStatus">—</span></div>
        <div class="detail-item"><label>Início</label><span id="pevStart">—</span></div>
        <div class="detail-item"><label>Fim Previsto</label><span id="pevEnd">—</span></div>
        <div class="detail-item" id="pevClientRow"><label>Cliente</label><span id="pevClient">—</span></div>
        <div class="detail-item" id="pevLocRow"><label>Localização</label><span id="pevLocation">—</span></div>
        <div class="detail-item" id="pevRoleRow"><label>A minha função</label><span id="pevRole">—</span></div>
    </div>
</div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
const statusLabelMap = {active:'Em Curso', planned:'Planeada', completed:'Concluída', cancelled:'Cancelada'};

document.addEventListener('DOMContentLoaded', function () {
    const calEl = document.getElementById('empCalendar');
    if (!calEl) return;

    const cal = new FullCalendar.Calendar(calEl, {
        locale: 'pt',
        initialView: 'dayGridMonth',
        height: 'auto',
        firstDay: 1,
        hiddenDays: [0, 6],
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' },
        buttonText: { today: 'Hoje', month: 'Mês', list: 'Lista' },
        noEventsText: 'Sem obras neste período.',
        eventDisplay: 'block',
        dayMaxEvents: 3,
        moreLinkText: n => `+${n} mais`,

        events: function (fetchInfo, success, failure) {
            const params = new URLSearchParams({
                start: fetchInfo.startStr.substring(0, 10),
                end:   fetchInfo.endStr.substring(0, 10),
            });
            fetch('/employee/projects/events?' + params, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
            .then(r => r.json())
            .then(success)
            .catch(failure);
        },

        eventClick: function (info) {
            const p = info.event.extendedProps;
            const overlay = document.getElementById('projEventOverlay');
            document.getElementById('pevTitle').textContent    = '🏗️ ' + (p.project_name || info.event.title);
            document.getElementById('pevTeam').textContent     = p.team_name   || '—';
            document.getElementById('pevStart').textContent    = p.start_date  || '—';
            document.getElementById('pevEnd').textContent      = p.end_date    || '—';
            document.getElementById('pevStatus').textContent   = statusLabelMap[p.status] || p.status || '—';

            const clientRow = document.getElementById('pevClientRow');
            if (p.client) { clientRow.style.display = ''; document.getElementById('pevClient').textContent = p.client; }
            else clientRow.style.display = 'none';

            const locRow = document.getElementById('pevLocRow');
            if (p.location) { locRow.style.display = ''; document.getElementById('pevLocation').textContent = p.location; }
            else locRow.style.display = 'none';

            const roleRow = document.getElementById('pevRoleRow');
            if (p.my_role) { roleRow.style.display = ''; document.getElementById('pevRole').textContent = p.my_role; }
            else roleRow.style.display = 'none';

            overlay.classList.add('open');
        },

        eventMouseEnter: info => { info.el.style.opacity = '.85'; info.el.style.transform = 'translateY(-1px)'; },
        eventMouseLeave: info => { info.el.style.opacity = '';    info.el.style.transform = ''; },
    });
    cal.render();
});

// Close overlay on backdrop click or Escape
document.addEventListener('click', e => {
    const o = document.getElementById('projEventOverlay');
    if (o && e.target === o) o.classList.remove('open');
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('projEventOverlay')?.classList.remove('open');
});
</script>
@endsection
