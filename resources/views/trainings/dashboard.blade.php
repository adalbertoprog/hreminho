@extends('layouts.app')
@section('title', 'Dashboard de Formações')
@section('page-title', 'Dashboard de Formações')

@section('styles')
<style>
/* ── Layout base ── */
.section-title { font-size: .82rem; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--text-muted); margin: 28px 0 14px; display: flex; align-items: center; gap: 8px; }
.section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 8px; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px; }
@media(max-width:1200px){ .grid-4{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:900px) { .grid-3,.grid-2{ grid-template-columns: 1fr; } }
@media(max-width:600px) { .grid-4{ grid-template-columns: 1fr 1fr; } }

/* ── KPI cards ── */
.kpi { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 20px 22px; position: relative; overflow: hidden; transition: transform .2s, border-color .2s; }
.kpi:hover { transform: translateY(-2px); border-color: rgba(99,102,241,.35); }
.kpi-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; margin-bottom: 14px; }
.kpi-value { font-size: 2rem; font-weight: 800; letter-spacing: -1px; line-height: 1; margin-bottom: 4px; }
.kpi-label { font-size: .78rem; color: var(--text-muted); font-weight: 500; }
.kpi-sub { font-size: .72rem; margin-top: 6px; font-weight: 600; padding: 2px 8px; border-radius: 6px; display: inline-block; }
.kpi-purple .kpi-icon { background: rgba(99,102,241,.15); }
.kpi-green  .kpi-icon { background: rgba(34,197,94,.15); }
.kpi-amber  .kpi-icon { background: rgba(245,158,11,.15); }
.kpi-red    .kpi-icon { background: rgba(239,68,68,.12); }
.kpi-blue   .kpi-icon { background: rgba(6,182,212,.15); }
.kpi-purple .kpi-value { color: var(--accent-light); }
.kpi-green  .kpi-value { color: #22c55e; }
.kpi-amber  .kpi-value { color: #f59e0b; }
.kpi-red    .kpi-value { color: #ef4444; }
.kpi-blue   .kpi-value { color: #06b6d4; }
.sub-purple { background: rgba(99,102,241,.12); color: var(--accent-light); }
.sub-green  { background: rgba(34,197,94,.12);  color: #22c55e; }
.sub-amber  { background: rgba(245,158,11,.12); color: #f59e0b; }
.sub-red    { background: rgba(239,68,68,.10);  color: #ef4444; }

/* ── Card genérico ── */
.card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
.card-header { display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; border-bottom: 1px solid var(--border); }
.card-header h3 { font-size: .9rem; font-weight: 700; }
.card-header a { font-size: .78rem; color: var(--accent-light); text-decoration: none; }
.card-header a:hover { text-decoration: underline; }
.card-body { padding: 0; }
.card-chart { padding: 20px; }
.empty-state { padding: 32px; text-align: center; color: var(--text-muted); font-size: .85rem; }

/* ── Barras de progresso ── */
.prog-row { padding: 10px 20px; }
.prog-row + .prog-row { border-top: 1px solid rgba(255,255,255,.04); }
.prog-top { display: flex; justify-content: space-between; margin-bottom: 6px; font-size: .83rem; }
.prog-top span:last-child { color: var(--text-muted); font-size: .78rem; }
.prog-bar { height: 6px; background: rgba(255,255,255,.07); border-radius: 4px; overflow: hidden; }
.prog-fill { height: 100%; border-radius: 4px; transition: width .6s ease; }

/* ── Lista de itens ── */
.list-item { display: flex; align-items: center; gap: 12px; padding: 10px 20px; transition: background .15s; }
.list-item:hover { background: var(--nav-hover); }
.list-avatar { width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; color: #fff; }
.list-info { flex: 1; min-width: 0; }
.list-info strong { display: block; font-size: .85rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.list-info span { font-size: .74rem; color: var(--text-muted); }
.list-meta { font-size: .76rem; text-align: right; flex-shrink: 0; }

/* ── Badge ── */
.badge { display: inline-block; padding: 2px 9px; border-radius: 6px; font-size: .72rem; font-weight: 700; }
.badge-red    { background: rgba(239,68,68,.12);  color: #ef4444; }
.badge-amber  { background: rgba(245,158,11,.12); color: #f59e0b; }
.badge-green  { background: rgba(34,197,94,.12);  color: #22c55e; }
.badge-purple { background: rgba(99,102,241,.12); color: var(--accent-light); }

/* ── Tabela compacta ── */
.compact-table { width: 100%; border-collapse: collapse; font-size: .855rem; }
.compact-table thead th { padding: 9px 16px; text-align: left; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; color: var(--text-muted); border-bottom: 1px solid var(--border); background: rgba(255,255,255,.02); white-space: nowrap; }
.compact-table tbody td { padding: 10px 16px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: middle; }
.compact-table tbody tr:last-child td { border-bottom: none; }
.compact-table tbody tr:hover td { background: rgba(255,255,255,.025); }
</style>
@endsection

@section('content')

{{-- ── Cabeçalho ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px">
    <div>
        <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:3px">📊 Dashboard de Formações</h2>
        <p style="font-size:.82rem;color:var(--text-muted)">Visão estratégica para apoio à planificação — atualizado em tempo real</p>
    </div>
    <a href="{{ route('trainings.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:9px;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.25);color:var(--accent-light);font-size:.85rem;font-weight:600;text-decoration:none">
        🎓 Gerir Formações →
    </a>
</div>

{{-- ── KPIs ── --}}
<div class="section-title">Indicadores Gerais</div>
<div class="grid-4" style="margin-bottom:20px">
    <div class="kpi kpi-purple">
        <div class="kpi-icon">🎓</div>
        <div class="kpi-value">{{ $kpis['totalTrainings'] }}</div>
        <div class="kpi-label">Formações no catálogo</div>
        <span class="kpi-sub sub-purple">{{ $kpis['totalEnrollments'] }} inscrições</span>
    </div>
    <div class="kpi kpi-green">
        <div class="kpi-icon">✅</div>
        <div class="kpi-value">{{ $kpis['globalRate'] }}%</div>
        <div class="kpi-label">Taxa global de conclusão</div>
        <span class="kpi-sub sub-green">{{ $kpis['totalCompleted'] }} concluídas</span>
    </div>
    <div class="kpi kpi-red">
        <div class="kpi-icon">⚠️</div>
        <div class="kpi-value">{{ $kpis['expiredCount'] }}</div>
        <div class="kpi-label">Certificados expirados</div>
        <span class="kpi-sub sub-amber">{{ $kpis['expiringCount'] }} a expirar (30 dias)</span>
    </div>
    <div class="kpi kpi-amber">
        <div class="kpi-icon">👤</div>
        <div class="kpi-value">{{ $kpis['noTrainingCount'] }}</div>
        <div class="kpi-label">Funcionários sem formação</div>
        <span class="kpi-sub sub-red">ativos sem nenhuma inscrição</span>
    </div>
</div>

{{-- ── Evolução mensal + Dept completion ── --}}
<div class="section-title">Evolução e Cobertura</div>
<div class="grid-2">

    <div class="card">
        <div class="card-header">
            <h3>📈 Evolução Mensal — Últimos 12 Meses</h3>
        </div>
        <div class="card-chart">
            <canvas id="chartEvolution" style="max-height:230px"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>🏢 Taxa de Conclusão por Departamento</h3>
        </div>
        <div class="card-chart" style="padding-bottom:10px">
            <canvas id="chartDept" style="max-height:230px"></canvas>
        </div>
    </div>

</div>

{{-- ── Reprovações + Aprovações ── --}}
<div class="section-title">Análise de Resultados (Questionários)</div>
<div class="grid-2">

    <div class="card">
        <div class="card-header">
            <h3>❌ Formações com Mais Reprovações</h3>
        </div>
        <div class="card-body">
            @forelse($highFailTrainings as $t)
            <div class="prog-row">
                <div class="prog-top">
                    <span style="font-weight:600">{{ Str::limit($t['title'], 36) }}</span>
                    <span>{{ $t['failed'] }} reprov. &nbsp;·&nbsp; <strong style="color:#ef4444">{{ $t['fail_rate'] }}%</strong></span>
                </div>
                <div class="prog-bar">
                    <div class="prog-fill" style="width:{{ $t['fail_rate'] }}%;background:{{ $t['fail_rate'] >= 50 ? '#ef4444' : ($t['fail_rate'] >= 25 ? '#f59e0b' : '#22c55e') }}"></div>
                </div>
            </div>
            @empty
            <div class="empty-state">Nenhuma reprovação registada.</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>🏆 Top Formações — Taxa de Aprovação</h3>
        </div>
        <div class="card-body">
            @forelse($topByApproval as $t)
            <div class="prog-row">
                <div class="prog-top">
                    <span style="font-weight:600">{{ Str::limit($t['title'], 36) }}</span>
                    <span>{{ $t['passed'] }}/{{ $t['total'] }} &nbsp;·&nbsp; <strong style="color:{{ $t['rate'] >= 70 ? '#22c55e' : '#f59e0b' }}">{{ $t['rate'] }}%</strong></span>
                </div>
                <div class="prog-bar">
                    <div class="prog-fill" style="width:{{ $t['rate'] }}%;background:{{ $t['rate'] >= 70 ? '#22c55e' : ($t['rate'] >= 40 ? '#f59e0b' : '#ef4444') }}"></div>
                </div>
            </div>
            @empty
            <div class="empty-state">Sem dados de questionários.</div>
            @endforelse
        </div>
    </div>

</div>

{{-- ── Certificados a expirar + Sem formação ── --}}
<div class="section-title">Alertas e Lacunas</div>
<div class="grid-2">

    <div class="card">
        <div class="card-header">
            <h3>🔔 Certificados a Expirar (60 dias)</h3>
            <a href="{{ route('trainings.index') }}">Ver inscrições →</a>
        </div>
        <div class="card-body">
            @forelse($expiringEnrollments as $e)
            <div class="list-item">
                <div class="list-avatar" style="background:{{ $e['days_left'] <= 15 ? '#ef4444' : '#f59e0b' }}">
                    {{ strtoupper(substr($e['employee'], 0, 2)) }}
                </div>
                <div class="list-info">
                    <strong>{{ $e['employee'] }}</strong>
                    <span>{{ Str::limit($e['training'], 40) }}</span>
                </div>
                <div class="list-meta">
                    <span class="badge {{ $e['days_left'] <= 15 ? 'badge-red' : 'badge-amber' }}">
                        {{ $e['days_left'] }}d
                    </span><br>
                    <span style="font-size:.72rem;color:var(--text-muted)">{{ $e['expiry'] }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state">✅ Sem certificados a expirar nos próximos 60 dias.</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>👤 Funcionários Sem Nenhuma Formação</h3>
            @if($kpis['noTrainingCount'] > 8)
            <span style="font-size:.78rem;color:var(--text-muted)">+{{ $kpis['noTrainingCount'] - 8 }} mais</span>
            @endif
        </div>
        <div class="card-body">
            @forelse($noTrainingEmployees as $emp)
            <div class="list-item">
                <div class="list-avatar" style="background:rgba(99,102,241,.4)">
                    {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                </div>
                <div class="list-info">
                    <strong>{{ $emp->full_name }}</strong>
                    <span>{{ $emp->position->position ?? '—' }} · {{ $emp->department->department ?? '—' }}</span>
                </div>
                <div class="list-meta">
                    <span class="badge badge-purple">{{ $emp->code }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state">✅ Todos os funcionários têm pelo menos uma formação.</div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const palette = ['#6366f1','#22c55e','#f59e0b','#06b6d4','#ec4899','#a855f7','#14b8a6','#f97316'];
const isDark  = () => document.documentElement.getAttribute('data-theme') === 'dark';
const gridClr = () => isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.07)';
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color       = isDark() ? '#94a3b8' : '#64748b';

// ── Evolução mensal ──
const evo = @json($chartEvolution);
new Chart(document.getElementById('chartEvolution'), {
    type: 'line',
    data: {
        labels: evo.labels,
        datasets: [
            { label: 'Inscritos',  data: evo.enrolled,  borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,.1)', fill: true, tension: .4, pointRadius: 3, pointBackgroundColor: '#6366f1' },
            { label: 'Concluídos', data: evo.completed, borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.08)',  fill: true, tension: .4, pointRadius: 3, pointBackgroundColor: '#22c55e' },
            { label: 'Reprovados', data: evo.failed,    borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,.07)',  fill: true, tension: .4, pointRadius: 3, pointBackgroundColor: '#ef4444' },
        ]
    },
    options: {
        plugins: { legend: { labels: { boxWidth: 10, padding: 14 } } },
        scales: {
            y: { beginAtZero: true, grid: { color: gridClr() }, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

// ── Taxa de conclusão por departamento ──
const dept = @json($chartDept);
new Chart(document.getElementById('chartDept'), {
    type: 'bar',
    data: {
        labels: dept.labels,
        datasets: [{
            label: 'Taxa de conclusão (%)',
            data: dept.rates,
            backgroundColor: dept.rates.map(r => r >= 70 ? 'rgba(34,197,94,.75)' : r >= 40 ? 'rgba(245,158,11,.75)' : 'rgba(239,68,68,.7)'),
            borderColor:     dept.rates.map(r => r >= 70 ? '#22c55e' : r >= 40 ? '#f59e0b' : '#ef4444'),
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.parsed.x}% (${dept.totals[c.dataIndex]} inscrições)` } }
        },
        scales: {
            x: { beginAtZero: true, max: 100, grid: { color: gridClr() }, ticks: { callback: v => v + '%' } },
            y: { grid: { display: false } }
        }
    }
});
</script>
@endsection
