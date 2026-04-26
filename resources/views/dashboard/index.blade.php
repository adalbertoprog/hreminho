@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, var(--bg-card) 0%, rgba(99,102,241,0.12) 100%);
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: 16px; padding: 28px 32px;
        margin-bottom: 28px;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
    }
    .welcome-banner h2 { font-size: 1.35rem; font-weight: 700; margin-bottom: 4px; }
    .welcome-banner p  { color: var(--text-muted); font-size: 0.9rem; }
    .welcome-badge {
        background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3);
        color: var(--accent-light); padding: 6px 16px; border-radius: 100px;
        font-size: 0.8rem; font-weight: 600;
    }

    /* ── Stats ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 16px; margin-bottom: 28px;
    }
    .stat-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 14px; padding: 20px 22px;
        transition: all 0.2s;
    }
    .stat-card:hover { border-color: rgba(99,102,241,0.35); transform: translateY(-2px); }
    .stat-card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .stat-badge { font-size: 0.7rem; font-weight: 600; padding: 3px 8px; border-radius: 6px; }
    .badge-up   { background: rgba(34,197,94,0.15);  color: #22c55e; }
    .badge-warn { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .badge-info { background: rgba(99,102,241,0.15); color: var(--accent-light); }
    .stat-value { font-size: 2rem; font-weight: 800; letter-spacing: -1px; margin-bottom: 4px; }
    .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }

    /* ── Cards genéricos ── */
    .card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 14px; overflow: hidden;
    }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid var(--border);
    }
    .card-header h3 { font-size: 0.93rem; font-weight: 700; }
    .card-header a  { font-size: 0.78rem; color: var(--accent-light); text-decoration: none; }
    .card-header a:hover { text-decoration: underline; }
    .card-body { padding: 8px 0; }
    .card-chart { padding: 20px; }

    /* ── Grids de layout ── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    @media (max-width: 1100px) { .grid-3 { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 800px)  { .grid-2, .grid-3 { grid-template-columns: 1fr; } }

    /* ── Listas ── */
    .list-item {
        display: flex; align-items: center; gap: 12px;
        padding: 9px 20px; transition: background 0.15s;
    }
    .list-item:hover { background: rgba(255,255,255,0.03); }
    .list-avatar {
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700; color: #fff;
    }
    .list-info { flex: 1; min-width: 0; }
    .list-info strong { display: block; font-size: 0.85rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .list-info span   { font-size: 0.76rem; color: var(--text-muted); }
    .list-meta { font-size: 0.76rem; color: var(--text-muted); text-align: right; flex-shrink: 0; }

    .status-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; margin-right: 4px; }
    .dot-green  { background: #22c55e; }
    .dot-yellow { background: #f59e0b; }
    .dot-red    { background: #ef4444; }
    .empty-state { padding: 28px; text-align: center; color: var(--text-muted); font-size: 0.875rem; }

    /* ── Quick Actions ── */
    .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 14px; }
    .quick-btn {
        display: flex; align-items: center; gap: 9px;
        background: rgba(255,255,255,0.04); border: 1px solid var(--border);
        border-radius: 10px; padding: 11px 13px;
        text-decoration: none; color: var(--text-primary);
        font-size: 0.8rem; font-weight: 500; transition: all 0.15s;
    }
    .quick-btn:hover { border-color: var(--accent); background: rgba(99,102,241,0.08); color: var(--accent-light); }

    /* ── Chart containers ── */
    .chart-wrap { position: relative; }
    .chart-wrap canvas { max-height: 260px; }
    .chart-legend { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; justify-content: center; }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: var(--text-muted); }
    .legend-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

    /* ── Training progress bars ── */
    .training-row { padding: 10px 20px; }
    .training-row + .training-row { border-top: 1px solid var(--border); }
    .training-row-top { display: flex; justify-content: space-between; margin-bottom: 6px; }
    .training-row-top span:first-child { font-size: 0.83rem; font-weight: 500; }
    .training-row-top span:last-child  { font-size: 0.78rem; color: var(--text-muted); }
    .progress-bar { height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 3px; transition: width 1s ease; }
</style>
@endsection

@section('content')

{{-- Banner --}}
<div class="welcome-banner">
    <div>
        <h2>Olá, {{ auth()->user()->name }} 👋</h2>
        <p>Aqui está o resumo do seu sistema de RH hoje.</p>
    </div>
    <div class="welcome-badge">{{ ucfirst(auth()->user()->role) }}</div>
</div>

{{-- Stats cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon" style="background:rgba(99,102,241,0.15)">👥</div>
            <span class="stat-badge badge-info">Total</span>
        </div>
        <div class="stat-value">{{ $stats['total_employees'] }}</div>
        <div class="stat-label">Funcionários Ativos</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon" style="background:rgba(34,197,94,0.15)">✅</div>
            <span class="stat-badge badge-up">Hoje</span>
        </div>
        <div class="stat-value">{{ $stats['present_today'] }}</div>
        <div class="stat-label">Presentes Hoje</div>
        @php
            $workforce = $stats['total_employees'] > 0
                ? round($stats['present_today'] / $stats['total_employees'] * 100, 1)
                : 0;
        @endphp
        <div style="margin-top:10px">
            <div style="display:flex;justify-content:space-between;font-size:0.72rem;color:var(--text-muted);margin-bottom:4px">
                <span>Força de trabalho</span>
                <span style="color:{{ $workforce >= 80 ? '#22c55e' : ($workforce >= 60 ? '#f59e0b' : '#ef4444') }};font-weight:700">{{ $workforce }}%</span>
            </div>
            <div style="height:5px;background:rgba(255,255,255,0.08);border-radius:3px;overflow:hidden">
                <div style="height:100%;width:{{ $workforce }}%;border-radius:3px;background:{{ $workforce >= 80 ? '#22c55e' : ($workforce >= 60 ? '#f59e0b' : '#ef4444') }};transition:width 1s ease"></div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon" style="background:rgba(245,158,11,0.15)">⏳</div>
            <span class="stat-badge badge-warn">Pendente</span>
        </div>
        <div class="stat-value">{{ $stats['pending_leaves'] }}</div>
        <div class="stat-label">Pedidos de Férias</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon" style="background:rgba(6,182,212,0.15)">🎓</div>
            <span class="stat-badge badge-info">Ativos</span>
        </div>
        <div class="stat-value">{{ $stats['active_trainings'] }}</div>
        <div class="stat-label">Formações</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon" style="background:rgba(239,68,68,0.15)">🌴</div>
            <span class="stat-badge badge-warn">Hoje</span>
        </div>
        <div class="stat-value">{{ $stats['on_leave_today'] }}</div>
        <div class="stat-label">De Férias/Licença</div>
    </div>
</div>

{{-- Linha 1: Donut departamento + Barras horizontais setor --}}
<div class="grid-2" style="margin-bottom:20px;">

    {{-- Gráfico 1: Funcionários por Departamento (Donut) --}}
    <div class="card">
        <div class="card-header">
            <h3>👥 Funcionários por Departamento</h3>
        </div>
        <div class="card-chart">
            <div class="chart-wrap" style="display:flex; align-items:center; justify-content:center;">
                <canvas id="chartDept" style="max-height:240px; max-width:240px;"></canvas>
            </div>
            <div class="chart-legend" id="legendDept"></div>
        </div>
    </div>

    {{-- Gráfico 2: Funcionários por Setor (Barras horizontais) --}}
    <div class="card">
        <div class="card-header">
            <h3>🏭 Funcionários por Setor</h3>
        </div>
        <div class="card-chart">
            <div class="chart-wrap">
                <canvas id="chartSector" style="max-height:260px;"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- Linha 2: Funcionários por Formação (largura total) --}}
<div style="margin-bottom:20px;">
    <div class="card">
        <div class="card-header">
            <h3>🎓 Funcionários por Formação — Top 10</h3>
        </div>
        <div class="card-chart">
            <div class="chart-wrap">
                <canvas id="chartTrainingEmp" style="max-height:280px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Linha 3: Evolução mensal (largura total) --}}
<div style="margin-bottom:20px;">
    <div class="card">
        <div class="card-header">
            <h3>📈 Evolução de Formações — Últimos 6 Meses</h3>
        </div>
        <div class="card-chart">
            <div class="chart-wrap">
                <canvas id="chartCompletion" style="max-height:220px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Linha 4: Top trainings + Listas --}}
<div class="grid-2">

    {{-- Barras de progresso: Top formações --}}
    <div class="card">
        <div class="card-header">
            <h3>🏆 Top Formações — Taxa de Conclusão</h3>
        </div>
        <div style="padding: 8px 0 4px;">
            @foreach($chart_top_trainings['labels'] as $i => $title)
            @php $rate = $chart_top_trainings['rates'][$i]; @endphp
            <div class="training-row">
                <div class="training-row-top">
                    <span>{{ $title }}</span>
                    <span>{{ $chart_top_trainings['completed'][$i] }}/{{ $chart_top_trainings['totals'][$i] }} &nbsp;·&nbsp; <strong style="color:var(--accent-light)">{{ $rate }}%</strong></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ $rate }}%; background: {{ $rate >= 70 ? '#22c55e' : ($rate >= 40 ? '#f59e0b' : '#ef4444') }}"></div>
                </div>
            </div>
            @endforeach
            @if(empty($chart_top_trainings['labels']))
                <div class="empty-state">Sem dados de formação.</div>
            @endif
        </div>
    </div>

    {{-- Férias pendentes + Ações rápidas --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        <div class="card">
            <div class="card-header">
                <h3>⏳ Férias Pendentes</h3>
                <a href="{{ route('leaves.index') }}">Ver todos →</a>
            </div>
            <div class="card-body">
                @forelse($pending_leaves as $leave)
                <div class="list-item">
                    <div class="list-avatar" style="background:#f59e0b">
                        {{ strtoupper(substr($leave->employee->first_name ?? 'U', 0, 1) . substr($leave->employee->last_name ?? 'K', 0, 1)) }}
                    </div>
                    <div class="list-info">
                        <strong>{{ $leave->employee->first_name ?? '—' }} {{ $leave->employee->last_name ?? '' }}</strong>
                        <span>{{ ucfirst($leave->leave_type) }} · {{ $leave->start_date->format('d/m') }}–{{ $leave->end_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="list-meta" style="color:#f59e0b;font-weight:600">Pendente</div>
                </div>
                @empty
                <div class="empty-state">Sem pedidos pendentes ✅</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>⚡ Ações Rápidas</h3></div>
            <div class="quick-actions">
                <a href="{{ route('employees.index') }}"  class="quick-btn">➕ Novo Funcionário</a>
                <a href="{{ route('attendances.index') }}" class="quick-btn">📅 Registar Presença</a>
                <a href="{{ route('leaves.index') }}"     class="quick-btn">🌴 Nova Licença</a>
                <a href="{{ route('trainings.index') }}"  class="quick-btn">🎓 Nova Formação</a>
                <a href="{{ route('departments.index') }}" class="quick-btn">🏢 Departamentos</a>
                <a href="{{ route('positions.index') }}"  class="quick-btn">💼 Cargos</a>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const palette  = ['#6366f1','#8b5cf6','#06b6d4','#22c55e','#f59e0b','#ef4444','#ec4899','#14b8a6','#f97316','#a855f7'];
const palette2 = palette.map(c => c + 'cc'); // semi-transparent variant

Chart.defaults.color       = '#94a3b8';
Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';
Chart.defaults.font.family = 'Inter, sans-serif';

// ── Gráfico 1: Donut — Funcionários por Departamento ──
const deptData = @json($chart_dept);
new Chart(document.getElementById('chartDept'), {
    type: 'doughnut',
    data: {
        labels: deptData.labels,
        datasets: [{
            data: deptData.data,
            backgroundColor: palette,
            borderColor: '#1a1d27',
            borderWidth: 3,
            hoverOffset: 8,
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.label}: ${c.parsed} funcionários` } }
        }
    }
});
const legendDept = document.getElementById('legendDept');
deptData.labels.forEach((label, i) => {
    legendDept.innerHTML += `<div class="legend-item"><div class="legend-dot" style="background:${palette[i % palette.length]}"></div>${label}</div>`;
});

// ── Gráfico 2: Barras horizontais — Funcionários por Setor ──
const sectorData = @json($chart_sector);
new Chart(document.getElementById('chartSector'), {
    type: 'bar',
    data: {
        labels: sectorData.labels,
        datasets: [{
            label: 'Funcionários',
            data: sectorData.data,
            backgroundColor: palette.map(c => c + 'bb'),
            borderColor: palette,
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.parsed.x} funcionários` } }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { stepSize: 1 }
            },
            y: { grid: { display: false } }
        }
    }
});

// ── Gráfico 3: Barras — Funcionários por Formação (Top 10) ──
const trEmpData = @json($chart_training_employees);
new Chart(document.getElementById('chartTrainingEmp'), {
    type: 'bar',
    data: {
        labels: trEmpData.labels,
        datasets: [{
            label: 'Funcionários',
            data: trEmpData.data,
            backgroundColor: palette.map(c => c + 'bb'),
            borderColor: palette,
            borderWidth: 1,
            borderRadius: 7,
            borderSkipped: false,
        }]
    },
    options: {
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.parsed.y} funcionário(s)` } }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { stepSize: 1 }
            },
                   x: { grid: { display: false } }
        }
    }
});

// Grafico 4: Linha — Evolucao de Formacoes (6 meses)
const completionData = @json($chart_completion);
new Chart(document.getElementById('chartCompletion'), {
    type: 'line',
    data: {
        labels: completionData.labels,
        datasets: [
            {
                label: 'Inscritos',
                data: completionData.enrolled,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.12)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointRadius: 4,
            },
            {
                label: 'Concluidos',
                data: completionData.completed,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.10)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#22c55e',
                pointRadius: 4,
            }
        ]
    },
    options: {
        plugins: {
            legend: { labels: { boxWidth: 12, padding: 16 } },
            tooltip: {}
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { stepSize: 1 }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endsection
