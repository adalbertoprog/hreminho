@extends('layouts.app')
@section('title', 'Plano Anual de Formações')
@section('page-title', 'Plano Anual de Formações')

@section('styles')
<style>
/* ── Base ── */
.toolbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px; }
.btn-primary { display:inline-flex; align-items:center; gap:7px; background:var(--accent); color:#fff; border:none; padding:9px 20px; border-radius:9px; font-size:.875rem; font-weight:600; cursor:pointer; transition:.15s; }
.btn-primary:hover { background:#4f46e5; }
.btn-ghost { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:8px; background:rgba(255,255,255,.05); border:1px solid var(--border); color:var(--text-muted); font-size:.85rem; cursor:pointer; transition:.15s; }
.btn-ghost:hover { border-color:var(--accent); color:var(--accent-light); }
.btn-ghost.active { background:rgba(99,102,241,.12); border-color:rgba(99,102,241,.4); color:var(--accent-light); }

/* ── KPIs ── */
.kpi-row { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:24px; }
@media(max-width:1100px){ .kpi-row{ grid-template-columns:repeat(3,1fr); } }
@media(max-width:700px) { .kpi-row{ grid-template-columns:repeat(2,1fr); } }
.kpi { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:16px 18px; }
.kpi-val { font-size:1.8rem; font-weight:800; letter-spacing:-1px; }
.kpi-lbl { font-size:.76rem; color:var(--text-muted); margin-top:2px; }

/* ── Vista anual (grid de meses) ── */
.year-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
@media(max-width:1100px){ .year-grid{ grid-template-columns:repeat(3,1fr); } }
@media(max-width:700px) { .year-grid{ grid-template-columns:repeat(2,1fr); } }

.month-card { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; overflow:hidden; min-height:120px; cursor:pointer; transition:border-color .15s, transform .15s; }
.month-card:hover { border-color:rgba(99,102,241,.4); transform:translateY(-1px); }
.month-card.has-sessions { border-color:rgba(99,102,241,.3); }
.month-card.current-month { border-color:var(--accent); box-shadow:0 0 0 1px rgba(99,102,241,.3); }
.month-header { display:flex; align-items:center; justify-content:space-between; padding:10px 14px; border-bottom:1px solid var(--border); background:rgba(255,255,255,.02); }
.month-name { font-size:.85rem; font-weight:700; }
.month-count { font-size:.72rem; font-weight:700; padding:2px 7px; border-radius:5px; }
.month-body { padding:8px 12px; }
.session-chip { display:flex; align-items:center; gap:6px; padding:4px 0; font-size:.77rem; border-bottom:1px solid rgba(255,255,255,.04); }
.session-chip:last-child { border-bottom:none; }
.session-chip-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
.month-empty { padding:12px; text-align:center; color:var(--text-muted); font-size:.78rem; }
.month-more { font-size:.72rem; color:var(--accent-light); padding:4px 0; cursor:pointer; }

/* ── Vista de lista ── */
.card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.card-header { display:flex; align-items:center; justify-content:space-between; padding:14px 18px; border-bottom:1px solid var(--border); }
.card-header h3 { font-size:.9rem; font-weight:700; }
table { width:100%; border-collapse:collapse; font-size:.875rem; }
thead th { padding:10px 16px; text-align:left; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:var(--text-muted); border-bottom:1px solid var(--border); background:rgba(255,255,255,.02); white-space:nowrap; }
tbody td { padding:10px 16px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover td { background:rgba(255,255,255,.025); }
.btn-sm { padding:4px 10px; border-radius:6px; font-size:.75rem; font-weight:600; cursor:pointer; border:none; transition:.15s; }
.btn-edit { background:rgba(99,102,241,.15); color:var(--accent-light); }
.btn-edit:hover { background:rgba(99,102,241,.3); }
.btn-del { background:rgba(239,68,68,.12); color:#ef4444; }
.btn-del:hover { background:rgba(239,68,68,.25); }
.state-row td { text-align:center; padding:32px; color:var(--text-muted); }

/* ── Status badges ── */
.status-planned   { background:rgba(99,102,241,.12); color:var(--accent-light); }
.status-ongoing   { background:rgba(245,158,11,.12);  color:#f59e0b; }
.status-completed { background:rgba(34,197,94,.12);   color:#22c55e; }
.status-cancelled { background:rgba(239,68,68,.10);   color:#ef4444; text-decoration:line-through; }
.badge { display:inline-block; padding:2px 9px; border-radius:6px; font-size:.72rem; font-weight:700; }

/* ── Modal ── */
.overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1000; align-items:center; justify-content:center; }
.overlay.open { display:flex; }
.modal { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:28px; width:100%; max-width:540px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.4); }
.modal-title { font-size:1.1rem; font-weight:700; margin-bottom:20px; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.fg { display:flex; flex-direction:column; gap:5px; }
.fg.full { grid-column:1/-1; }
.fg label { font-size:.78rem; font-weight:600; color:var(--text-muted); }
.fg input, .fg select, .fg textarea { background:var(--bg-card); border:1px solid var(--border); border-radius:9px; padding:9px 13px; color:var(--text-primary); font-size:.86rem; font-family:inherit; outline:none; transition:border-color .15s; }
.fg input:focus, .fg select:focus, .fg textarea:focus { border-color:var(--accent); }
.btn-cancel { padding:9px 20px; border-radius:9px; background:rgba(255,255,255,.05); border:1px solid var(--border); color:var(--text-muted); font-size:.875rem; cursor:pointer; }

/* ── Filtros ── */
.filters { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px; }
.f-input { background:var(--bg-card); border:1px solid var(--border); border-radius:9px; padding:8px 13px; color:var(--text-primary); font-size:.85rem; font-family:inherit; outline:none; }
.f-input:focus { border-color:var(--accent); }

/* ── Execução / progresso ── */
.fill-bar-wrap { height:4px; background:rgba(255,255,255,.08); border-radius:2px; margin-top:5px; overflow:hidden; }
.fill-bar { height:100%; border-radius:2px; transition:width .4s; }
.exec-row { display:flex; align-items:center; justify-content:space-between; font-size:.72rem; color:var(--text-muted); margin-top:4px; }
.exec-badge { display:inline-flex; align-items:center; gap:4px; font-size:.72rem; font-weight:700; padding:1px 7px; border-radius:5px; }

/* ── Spinner ── */
.spinner { display:inline-block; width:14px; height:14px; border:2px solid var(--border); border-top-color:var(--accent); border-radius:50%; animation:spin .7s linear infinite; margin-right:6px; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>
@endsection

@section('content')

<div class="toolbar">
    <div>
        <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:3px">📅 Plano Anual de Formações</h2>
        <p style="font-size:.82rem;color:var(--text-muted)">Calendário de sessões planeadas, em curso e concluídas</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        {{-- Navegação de ano --}}
        <div style="display:flex;align-items:center;gap:6px;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:4px 6px">
            <button class="btn-ghost" id="btnPrevYear" onclick="changeYear(-1)" style="padding:5px 10px">‹</button>
            <span id="currentYearLabel" style="font-size:.95rem;font-weight:700;min-width:50px;text-align:center">{{ $currentYear }}</span>
            <button class="btn-ghost" id="btnNextYear" onclick="changeYear(1)"  style="padding:5px 10px">›</button>
        </div>
        {{-- Vista --}}
        <button class="btn-ghost active" id="btnViewYear" onclick="setView('year')">📅 Anual</button>
        <button class="btn-ghost"        id="btnViewList" onclick="setView('list')">☰ Lista</button>
        {{-- Nova sessão --}}
        <button class="btn-primary" onclick="openSessionModal()">+ Nova Sessão</button>
    </div>
</div>

{{-- KPIs --}}
<div class="kpi-row" id="kpiRow">
    <div class="kpi"><div class="kpi-val" id="kpiTotal" style="color:var(--accent-light)">—</div><div class="kpi-lbl">Total de sessões</div></div>
    <div class="kpi"><div class="kpi-val" id="kpiPlanned" style="color:var(--accent-light)">—</div><div class="kpi-lbl">Planeadas</div></div>
    <div class="kpi"><div class="kpi-val" id="kpiOngoing" style="color:#f59e0b">—</div><div class="kpi-lbl">Em curso</div></div>
    <div class="kpi"><div class="kpi-val" id="kpiCompleted" style="color:#22c55e">—</div><div class="kpi-lbl">Concluídas</div></div>
    <div class="kpi"><div class="kpi-val" id="kpiCancelled" style="color:#ef4444">—</div><div class="kpi-lbl">Canceladas</div></div>
</div>

{{-- Vista Anual --}}
<div id="viewYear">
    <div class="year-grid" id="yearGrid">
        <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted)">
            <span class="spinner"></span>A carregar…
        </div>
    </div>
</div>

{{-- Vista Lista --}}
<div id="viewList" style="display:none">
    <div class="filters">
        <select id="filterStatus" class="f-input" onchange="loadList()">
            <option value="">Todos os estados</option>
            <option value="planned">Planeadas</option>
            <option value="ongoing">Em curso</option>
            <option value="completed">Concluídas</option>
            <option value="cancelled">Canceladas</option>
        </select>
        <select id="filterTraining" class="f-input" onchange="loadList()">
            <option value="">Todas as formações</option>
            @foreach($trainings as $t)
            <option value="{{ $t->id }}">{{ $t->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="card">
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Formação</th>
                        <th>Data início</th>
                        <th>Data fim</th>
                        <th>Duração</th>
                        <th>Local</th>
                        <th style="text-align:center">Vagas</th>
                        <th style="text-align:center">Prev.</th>
                        <th style="text-align:center">Inscritos</th>
                        <th style="text-align:right">€/pessoa</th>
                        <th style="text-align:right">Custo prev.</th>
                        <th style="text-align:right">Custo real</th>
                        <th style="text-align:center">Estado</th>
                        <th style="text-align:center">Ações</th>
                    </tr>
                </thead>
                <tbody id="listBody">
                    <tr class="state-row"><td colspan="13"><span class="spinner"></span>A carregar…</td></tr>
                </tbody>
                <tfoot id="listFoot" style="display:none">
                    <tr style="border-top:2px solid var(--border)">
                        <td colspan="9" style="padding:10px 16px;font-size:.8rem;color:var(--text-muted);font-weight:600">Totais</td>
                        <td style="padding:10px 16px;text-align:right;font-weight:700;color:var(--accent-light);font-size:.82rem" id="footEstimated">—</td>
                        <td style="padding:10px 16px;text-align:right;font-weight:700;color:#22c55e;font-size:.82rem" id="footReal">—</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Modal de detalhe do mês --}}
<div class="overlay" id="monthOverlay">
<div class="modal" style="max-width:640px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="modal-title" style="margin-bottom:0" id="monthModalTitle">Janeiro</div>
        <button onclick="closeOverlay('monthOverlay')" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.3rem">✕</button>
    </div>
    <table style="width:100%;border-collapse:collapse;font-size:.855rem">
        <thead>
            <tr style="border-bottom:1px solid var(--border)">
                <th style="padding:7px 10px;text-align:left;font-size:.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted)">Formação</th>
                <th style="padding:7px 10px;text-align:center;font-size:.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted)">Data</th>
                <th style="padding:7px 10px;text-align:center;font-size:.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted)">Estado</th>
                <th style="padding:7px 10px;text-align:center;font-size:.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted)">Ações</th>
            </tr>
        </thead>
        <tbody id="monthDetailBody"></tbody>
    </table>
    <div style="margin-top:14px;display:flex;justify-content:space-between;align-items:center">
        <button class="btn-primary" style="font-size:.8rem;padding:7px 14px" onclick="closeOverlay('monthOverlay');openSessionModal()">+ Nova Sessão</button>
        <button class="btn-cancel" onclick="closeOverlay('monthOverlay')">Fechar</button>
    </div>
</div>
</div>

{{-- Modal criar/editar sessão --}}
<div class="overlay" id="sessionOverlay">
<div class="modal">
    <div class="modal-title" id="sessionModalTitle">Nova Sessão de Formação</div>
    <form id="sessionForm" onsubmit="submitSession(event)">
        <input type="hidden" id="sessionId">
        <div class="form-grid">
            <div class="fg full">
                <label>Formação *</label>
                <select id="sTrainingId" required>
                    <option value="">— Selecionar —</option>
                    @foreach($trainings as $t)
                    <option value="{{ $t->id }}">{{ $t->title }}{{ $t->provider ? ' ('.$t->provider.')' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label>Data de início *</label>
                <input type="date" id="sPlannedDate" required>
            </div>
            <div class="fg">
                <label>Data de fim</label>
                <input type="date" id="sPlannedEndDate">
            </div>
            <div class="fg">
                <label>Local / Modalidade</label>
                <input type="text" id="sLocation" placeholder="Ex: Sala A, Online, Externa…" maxlength="255">
            </div>
            <div class="fg">
                <label>Vagas máximas</label>
                <input type="number" id="sMaxParticipants" min="1" placeholder="Ilimitado">
            </div>
            <div class="fg">
                <label>Participantes previstos</label>
                <input type="number" id="sEstimatedParticipants" min="1" placeholder="Nº previsto">
            </div>
            <div class="fg">
                <label>Custo por pessoa (€)</label>
                <input type="number" id="sCostPerPerson" min="0" step="0.01" placeholder="0,00">
            </div>
            <div class="fg">
                <label>Custo total previsto</label>
                <input type="text" id="sEstimatedTotal" readonly placeholder="—" style="opacity:.6;cursor:default">
            </div>
            <div class="fg full">
                <label>Estado</label>
                <select id="sStatus">
                    <option value="planned">Planeada</option>
                    <option value="ongoing">Em curso</option>
                    <option value="completed">Concluída</option>
                    <option value="cancelled">Cancelada</option>
                </select>
            </div>
            <div class="fg full">
                <label>Notas</label>
                <textarea id="sNotes" rows="2" maxlength="2000" placeholder="Observações opcionais…"></textarea>
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px">
            <button type="button" class="btn-cancel" onclick="closeOverlay('sessionOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary">Guardar</button>
        </div>
    </form>
</div>
</div>

@endsection

@section('scripts')
<script>
const API  = '/api/v1';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let currentYear = {{ $currentYear }};
let currentView = 'year';
let annualData  = null;
let allSessions = [];

const statusLabel = { planned:'Planeada', ongoing:'Em curso', completed:'Concluída', cancelled:'Cancelada' };
const statusColor = { planned:'var(--accent-light)', ongoing:'#f59e0b', completed:'#22c55e', cancelled:'#ef4444' };
const statusDot   = { planned:'#6366f1', ongoing:'#f59e0b', completed:'#22c55e', cancelled:'#ef4444' };

async function apiFetch(method, path, body) {
    const opts = { method, credentials:'same-origin', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'} };
    if (body) opts.body = JSON.stringify(body);
    const r = await fetch(API + path, opts);
    if (!r.ok) { const e = await r.json().catch(() => ({ message:'Erro' })); throw e; }
    return r.status === 204 ? null : r.json();
}

function escHtml(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function toast(msg, type='ok') {
    const w = document.getElementById('toast-wrap') || (() => {
        const d = document.createElement('div');
        d.id = 'toast-wrap';
        d.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px';
        document.body.appendChild(d); return d;
    })();
    const t = document.createElement('div');
    t.style.cssText = `background:${type==='ok'?'#22c55e':'#ef4444'};color:#fff;padding:10px 18px;border-radius:10px;font-size:.85rem;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,.25)`;
    t.textContent = msg;
    w.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

function openOverlay(id)  { document.getElementById(id).classList.add('open'); }
function closeOverlay(id) { document.getElementById(id).classList.remove('open'); }

/* ── Navegação ── */
function changeYear(delta) {
    currentYear += delta;
    document.getElementById('currentYearLabel').textContent = currentYear;
    loadYear();
}

function setView(v) {
    currentView = v;
    document.getElementById('viewYear').style.display = v === 'year' ? '' : 'none';
    document.getElementById('viewList').style.display = v === 'list' ? '' : 'none';
    document.getElementById('btnViewYear').classList.toggle('active', v === 'year');
    document.getElementById('btnViewList').classList.toggle('active', v === 'list');
    if (v === 'list') loadList();
}

/* ── Carregamento anual ── */
async function loadYear() {
    document.getElementById('yearGrid').innerHTML =
        '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted)"><span class="spinner"></span>A carregar…</div>';
    try {
        const res = await apiFetch('GET', `/training-sessions/annual-summary?year=${currentYear}`);
        annualData = res;
        renderKpis(res.by_status, res.total);
        renderYearGrid(res.by_month);
    } catch(e) {
        document.getElementById('yearGrid').innerHTML =
            '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#ef4444">Erro ao carregar dados.</div>';
    }
}

function renderKpis(s, total) {
    document.getElementById('kpiTotal').textContent     = total;
    document.getElementById('kpiPlanned').textContent   = s.planned;
    document.getElementById('kpiOngoing').textContent   = s.ongoing;
    document.getElementById('kpiCompleted').textContent = s.completed;
    document.getElementById('kpiCancelled').textContent = s.cancelled;
}

function renderYearGrid(months) {
    const now = new Date();
    const grid = document.getElementById('yearGrid');
    grid.innerHTML = months.map(m => {
        const isCurrentMonth = (m.month === now.getMonth() + 1 && currentYear === now.getFullYear());
        const countColor = m.total > 0 ? 'var(--accent-light)' : 'var(--text-muted)';
        const countBg    = m.total > 0 ? 'rgba(99,102,241,.12)' : 'rgba(255,255,255,.05)';

        const chips = m.sessions.slice(0, 4).map(s => {
            const hasTarget = s.estimated_participants || s.max_participants;
            const target    = s.estimated_participants ?? s.max_participants;
            const fillPct   = s.fill_rate ?? 0;
            const fillColor = fillPct >= 100 ? '#22c55e' : fillPct >= 60 ? '#f59e0b' : 'var(--accent-light)';
            const enrollBadge = hasTarget
                ? `<span style="color:${fillColor};font-size:.68rem;flex-shrink:0;font-weight:700">${s.enrolled_count}/${target}</span>`
                : (s.enrolled_count > 0 ? `<span style="color:var(--text-muted);font-size:.68rem;flex-shrink:0">${s.enrolled_count} ins.</span>` : '');
            const bar = hasTarget
                ? `<div class="fill-bar-wrap"><div class="fill-bar" style="width:${fillPct}%;background:${fillColor}"></div></div>`
                : '';
            return `<div class="session-chip">
                <div class="session-chip-dot" style="background:${statusDot[s.status]}"></div>
                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text-primary)">${escHtml(s.training_title)}</span>
                ${enrollBadge}
                <span style="color:var(--text-muted);font-size:.7rem;flex-shrink:0;margin-left:4px">${s.planned_date_fmt}</span>
            </div>${bar}`;
        }).join('');

        const more = m.sessions.length > 4
            ? `<div class="month-more">+ ${m.sessions.length - 4} mais…</div>` : '';
        const empty = m.sessions.length === 0
            ? '<div class="month-empty">Sem sessões</div>' : '';

        return `<div class="month-card ${m.total>0?'has-sessions':''} ${isCurrentMonth?'current-month':''}" onclick="openMonthDetail(${m.month})">
            <div class="month-header">
                <span class="month-name">${escHtml(m.label)}</span>
                <span class="month-count" style="background:${countBg};color:${countColor}">${m.total}</span>
            </div>
            <div class="month-body">${chips}${more}${empty}</div>
        </div>`;
    }).join('');
}

/* ── Detalhe do mês ── */
function openMonthDetail(month) {
    const m = annualData?.by_month?.find(x => x.month === month);
    if (!m) return;
    document.getElementById('monthModalTitle').textContent = `${m.label} ${currentYear}`;
    document.getElementById('monthDetailBody').innerHTML = m.sessions.length === 0
        ? '<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-muted)">Sem sessões planeadas para este mês.</td></tr>'
        : m.sessions.map(s => {
            const target   = s.estimated_participants ?? s.max_participants;
            const fillPct  = s.fill_rate ?? 0;
            const fillColor= fillPct >= 100 ? '#22c55e' : fillPct >= 60 ? '#f59e0b' : 'var(--accent-light)';
            const execHtml = target
                ? `<div style="margin-top:4px">
                     <div style="display:flex;justify-content:space-between;font-size:.7rem;color:var(--text-muted);margin-bottom:2px">
                       <span>${s.enrolled_count} inscritos</span><span style="color:${fillColor};font-weight:700">${fillPct}%</span>
                     </div>
                     <div class="fill-bar-wrap"><div class="fill-bar" style="width:${fillPct}%;background:${fillColor}"></div></div>
                   </div>`
                : (s.enrolled_count > 0 ? `<div style="font-size:.7rem;color:var(--text-muted);margin-top:2px">${s.enrolled_count} inscrito(s)</div>` : '');
            return `<tr>
            <td>
                <div style="font-weight:600;font-size:.84rem">${escHtml(s.training_title)}</div>
                ${s.location ? `<div style="font-size:.72rem;color:var(--text-muted)">${escHtml(s.location)}</div>` : ''}
                ${execHtml}
            </td>
            <td style="text-align:center;font-size:.82rem;color:var(--text-muted)">
                ${s.planned_date_fmt}${s.planned_end_fmt && s.planned_end_fmt !== s.planned_date_fmt ? ' → ' + s.planned_end_fmt : ''}
            </td>
            <td style="text-align:center">
                <span class="badge status-${s.status}">${statusLabel[s.status]}</span>
            </td>
            <td style="text-align:center">
                <button class="btn-sm btn-edit" onclick="closeOverlay('monthOverlay');editSession(${s.id})">✏️</button>
                <button class="btn-sm btn-del"  onclick="closeOverlay('monthOverlay');deleteSession(${s.id})" style="margin-left:4px">🗑️</button>
            </td>
        </tr>`;
        }).join('');
    openOverlay('monthOverlay');
}

/* ── Vista lista ── */
async function loadList() {
    const tbody = document.getElementById('listBody');
    tbody.innerHTML = '<tr class="state-row"><td colspan="8"><span class="spinner"></span>A carregar…</td></tr>';
    const status     = document.getElementById('filterStatus').value;
    const trainingId = document.getElementById('filterTraining').value;
    const params = new URLSearchParams({ year: currentYear });
    if (status)     params.set('status', status);
    if (trainingId) params.set('training_id', trainingId);
    try {
        const res = await apiFetch('GET', `/training-sessions?${params}`);
        allSessions = res.data ?? [];
        if (!allSessions.length) {
            tbody.innerHTML = '<tr class="state-row"><td colspan="13">Sem sessões para os filtros seleccionados.</td></tr>';
            document.getElementById('listFoot').style.display = 'none';
            return;
        }
        tbody.innerHTML = allSessions.map(s => {
            const target    = s.estimated_participants ?? s.max_participants;
            const fillPct   = s.fill_rate ?? 0;
            const fillColor = fillPct >= 100 ? '#22c55e' : fillPct >= 60 ? '#f59e0b' : 'var(--accent-light)';
            const enrollCell = target
                ? `<div style="font-weight:700;color:${fillColor}">${s.enrolled_count}/${target}</div>
                   <div class="fill-bar-wrap" style="width:60px"><div class="fill-bar" style="width:${fillPct}%;background:${fillColor}"></div></div>`
                : `<span style="color:var(--text-muted)">${s.enrolled_count > 0 ? s.enrolled_count : '—'}</span>`;
            return `<tr>
            <td>
                <div style="font-weight:600">${escHtml(s.training_title)}</div>
                ${s.training_provider ? `<div style="font-size:.74rem;color:var(--text-muted)">${escHtml(s.training_provider)}</div>` : ''}
            </td>
            <td style="font-size:.83rem">${s.planned_date_fmt}</td>
            <td style="font-size:.83rem;color:var(--text-muted)">${s.planned_end_fmt ?? '—'}</td>
            <td style="text-align:center;color:var(--text-muted);font-size:.82rem">${s.duration_days}d</td>
            <td style="font-size:.82rem">${s.location ? escHtml(s.location) : '<span style="color:var(--text-muted)">—</span>'}</td>
            <td style="text-align:center;font-size:.82rem;color:var(--text-muted)">${s.max_participants ?? '<span style="color:var(--text-muted)">∞</span>'}</td>
            <td style="text-align:center;font-size:.82rem;color:var(--text-muted)">${s.estimated_participants ?? '<span style="color:var(--text-muted)">—</span>'}</td>
            <td style="text-align:center;font-size:.82rem">${enrollCell}</td>
            <td style="text-align:right;font-size:.82rem;color:var(--text-muted)">${s.cost_per_person !== null ? fmtEur(s.cost_per_person) : '<span style="color:var(--text-muted)">—</span>'}</td>
            <td style="text-align:right;font-size:.82rem;font-weight:${s.estimated_total !== null ? '600' : '400'};color:${s.estimated_total !== null ? 'var(--accent-light)' : 'var(--text-muted)'}">
                ${s.estimated_total !== null ? fmtEur(s.estimated_total) : '—'}
            </td>
            <td style="text-align:right;font-size:.82rem;font-weight:${s.real_total !== null ? '600' : '400'};color:${s.real_total !== null ? '#22c55e' : 'var(--text-muted)'}">
                ${s.real_total !== null ? fmtEur(s.real_total) : '—'}
            </td>
            <td style="text-align:center"><span class="badge status-${s.status}">${statusLabel[s.status]}</span></td>
            <td style="text-align:center">
                <button class="btn-sm btn-edit" onclick="editSession(${s.id})">✏️</button>
                <button class="btn-sm btn-del"  onclick="deleteSession(${s.id})" style="margin-left:4px">🗑️</button>
            </td>
        </tr>`;
        }).join('');

        // Totalizadores
        const grandEstimated = allSessions.reduce((sum, s) => sum + (s.estimated_total ?? 0), 0);
        const grandReal      = allSessions.reduce((sum, s) => sum + (s.real_total      ?? 0), 0);
        const hasAnyFinancial = allSessions.some(s => s.estimated_total !== null || s.real_total !== null);
        const foot = document.getElementById('listFoot');
        if (hasAnyFinancial) {
            document.getElementById('footEstimated').textContent = allSessions.some(s => s.estimated_total !== null) ? fmtEur(grandEstimated) : '—';
            document.getElementById('footReal').textContent      = allSessions.some(s => s.real_total      !== null) ? fmtEur(grandReal)      : '—';
            foot.style.display = '';
        } else {
            foot.style.display = 'none';
        }
    } catch(e) {
        tbody.innerHTML = '<tr class="state-row"><td colspan="13">⚠️ Erro ao carregar.</td></tr>';
    }
}

/* ── CRUD ── */
function fmtEur(v) {
    if (v === null || v === undefined) return '—';
    return new Intl.NumberFormat('pt-PT', { style:'currency', currency:'EUR' }).format(v);
}

function recalcTotal() {
    const pax  = parseFloat(document.getElementById('sEstimatedParticipants').value) || null;
    const cpp  = parseFloat(document.getElementById('sCostPerPerson').value);
    const total = (pax && !isNaN(cpp)) ? pax * cpp : null;
    document.getElementById('sEstimatedTotal').value = total !== null ? fmtEur(total) : '—';
}

function openSessionModal(session = null) {
    document.getElementById('sessionId').value          = session?.id ?? '';
    document.getElementById('sessionModalTitle').textContent = session ? '✏️ Editar Sessão' : '➕ Nova Sessão de Formação';
    document.getElementById('sTrainingId').value        = session?.training_id ?? '';
    document.getElementById('sPlannedDate').value       = session?.planned_date ?? `${currentYear}-01-01`;
    document.getElementById('sPlannedEndDate').value    = session?.planned_end_date ?? '';
    document.getElementById('sLocation').value          = session?.location ?? '';
    document.getElementById('sMaxParticipants').value   = session?.max_participants ?? '';
    document.getElementById('sEstimatedParticipants').value = session?.estimated_participants ?? '';
    document.getElementById('sCostPerPerson').value     = session?.cost_per_person ?? '';
    document.getElementById('sStatus').value            = session?.status ?? 'planned';
    document.getElementById('sNotes').value             = session?.notes ?? '';
    recalcTotal();
    openOverlay('sessionOverlay');
}

document.getElementById('sEstimatedParticipants').addEventListener('input', recalcTotal);
document.getElementById('sCostPerPerson').addEventListener('input', recalcTotal);

function editSession(id) {
    const s = allSessions.find(x => x.id === id)
           ?? annualData?.by_month?.flatMap(m => m.sessions)?.find(x => x.id === id);
    if (s) openSessionModal(s);
}

async function submitSession(e) {
    e.preventDefault();
    const id   = document.getElementById('sessionId').value;
    const body = {
        training_id:            parseInt(document.getElementById('sTrainingId').value),
        planned_date:           document.getElementById('sPlannedDate').value,
        planned_end_date:       document.getElementById('sPlannedEndDate').value || null,
        location:               document.getElementById('sLocation').value.trim() || null,
        max_participants:       parseInt(document.getElementById('sMaxParticipants').value) || null,
        estimated_participants: parseInt(document.getElementById('sEstimatedParticipants').value) || null,
        cost_per_person:        parseFloat(document.getElementById('sCostPerPerson').value) || null,
        status:                 document.getElementById('sStatus').value,
        notes:                  document.getElementById('sNotes').value.trim() || null,
    };
    try {
        if (id) {
            await apiFetch('PUT', `/training-sessions/${id}`, body);
            toast('Sessão actualizada.', 'ok');
        } else {
            await apiFetch('POST', '/training-sessions', body);
            toast('Sessão criada com sucesso.', 'ok');
        }
        closeOverlay('sessionOverlay');
        loadYear();
        if (currentView === 'list') loadList();
    } catch(err) {
        toast(err.message || 'Erro ao guardar.', 'err');
    }
}

async function deleteSession(id) {
    if (!confirm('Remover esta sessão do plano?')) return;
    try {
        await apiFetch('DELETE', `/training-sessions/${id}`);
        toast('Sessão removida.', 'ok');
        loadYear();
        if (currentView === 'list') loadList();
    } catch(e) {
        toast('Erro ao remover.', 'err');
    }
}

/* ── Init ── */
document.querySelectorAll('.overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
loadYear();
</script>
@endsection
