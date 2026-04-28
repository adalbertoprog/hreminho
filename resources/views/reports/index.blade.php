@extends('layouts.app')

@section('title', 'Relatórios')
@section('page-title', 'Relatórios')

@section('styles')
<style>
/* ── Tabs ── */
.report-tabs { display: flex; gap: 4px; margin-bottom: 24px; background: var(--bg-card); padding: 6px; border-radius: 12px; border: 1px solid var(--border); width: fit-content; }
.report-tab { padding: 8px 20px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; border: none; background: transparent; color: var(--text-muted); transition: all 0.15s; white-space: nowrap; }
.report-tab.active { background: var(--accent); color: #fff; }
.report-tab:not(.active):hover { background: rgba(255,255,255,0.05); color: var(--text-primary); }

/* ── Filter bar ── */
.filter-bar { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; }
.filter-group { display: flex; flex-direction: column; gap: 4px; }
.filter-group label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); }
.filter-group input, .filter-group select { background: var(--bg-dark); border: 1px solid var(--border); color: var(--text-primary); border-radius: 8px; padding: 7px 12px; font-size: 0.85rem; font-family: inherit; min-width: 150px; }
.filter-group input:focus, .filter-group select:focus { outline: none; border-color: var(--accent); }
.filter-actions { display: flex; gap: 8px; margin-left: auto; align-items: flex-end; }

/* ── Card header ── */
.report-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
.report-header h2 { font-size: 1rem; font-weight: 600; }
.report-count { font-size: 0.8rem; color: var(--text-muted); }
.btn-actions { display: flex; gap: 8px; }

/* ── Buttons ── */
.btn { padding: 8px 16px; border-radius: 8px; font-size: 0.82rem; font-weight: 500; cursor: pointer; border: none; font-family: inherit; display: inline-flex; align-items: center; gap: 6px; transition: all 0.15s; }
.btn-primary { background: var(--accent); color: #fff; }
.btn-primary:hover { background: var(--accent-light); }
.btn-secondary { background: var(--bg-card); color: var(--text-muted); border: 1px solid var(--border); }
.btn-secondary:hover { color: var(--text-primary); border-color: rgba(255,255,255,0.2); }
.btn-success { background: rgba(34,197,94,0.15); color: var(--success); border: 1px solid rgba(34,197,94,0.3); }
.btn-success:hover { background: rgba(34,197,94,0.25); }
.btn-pdf { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
.btn-pdf:hover { background: rgba(239,68,68,0.25); }

/* ── Table ── */
.table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid var(--border); }
table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
thead th { background: rgba(255,255,255,0.04); color: var(--text-muted); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; padding: 11px 14px; text-align: left; white-space: nowrap; }
tbody tr { border-top: 1px solid var(--border); transition: background 0.1s; }
tbody tr:hover { background: rgba(255,255,255,0.03); }
tbody td { padding: 11px 14px; color: var(--text-primary); vertical-align: middle; }
.badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.badge-present { background: rgba(34,197,94,0.15); color: var(--success); }
.badge-absent  { background: rgba(239,68,68,0.15); color: var(--danger); }
.badge-late    { background: rgba(245,158,11,0.15); color: var(--warning); }

/* ── Summary cards ── */
.summary-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; margin-bottom: 20px; }
.summary-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 14px 16px; }
.summary-card .s-name { font-size: 0.82rem; font-weight: 600; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.summary-card .s-stats { display: flex; gap: 12px; font-size: 0.78rem; color: var(--text-muted); }
.summary-card .s-stats span strong { color: var(--text-primary); }
.summary-card .s-rate { margin-top: 8px; font-size: 0.78rem; }
.progress-bar { height: 5px; background: var(--border); border-radius: 3px; margin-top: 4px; overflow: hidden; }
.progress-bar-fill { height: 100%; border-radius: 3px; background: var(--accent); transition: width 0.4s; }

/* ── KPI strip ── */
.emp-kpi-strip { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-bottom: 24px; }
.emp-kpi { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 16px 18px; display: flex; flex-direction: column; gap: 4px; transition: border-color 0.2s; }
.emp-kpi:hover { border-color: rgba(99,102,241,0.4); }
.emp-kpi .kpi-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); }
.emp-kpi .kpi-value { font-size: 1.65rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
.emp-kpi .kpi-sub   { font-size: 0.72rem; color: var(--text-muted); margin-top: 2px; }

/* ── Search bar ── */
.emp-search-bar { display: flex; gap: 10px; margin-bottom: 16px; align-items: center; flex-wrap: wrap; }
.emp-search-input { flex: 1; min-width: 200px; background: var(--bg-card); border: 1px solid var(--border); color: var(--text-primary); border-radius: 10px; padding: 9px 14px 9px 38px; font-size: 0.88rem; font-family: inherit; }
.emp-search-input:focus { outline: none; border-color: var(--accent); }
.emp-search-wrap { position: relative; flex: 1; min-width: 200px; }
.emp-search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); opacity: 0.35; pointer-events: none; }
.emp-sort-select { background: var(--bg-card); border: 1px solid var(--border); color: var(--text-primary); border-radius: 10px; padding: 9px 14px; font-size: 0.85rem; font-family: inherit; cursor: pointer; }
.emp-sort-select:focus { outline: none; border-color: var(--accent); }
.emp-view-toggle { display: flex; background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
.emp-view-btn { padding: 8px 12px; border: none; background: transparent; color: var(--text-muted); cursor: pointer; transition: all 0.15s; font-size: 0.9rem; }
.emp-view-btn.active { background: var(--accent); color: #fff; }

/* ── Employee card grid ── */
#e-cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; }
#e-cards-grid.list-view { grid-template-columns: 1fr; }
.emp-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; transition: border-color 0.2s, box-shadow 0.2s; }
.emp-card:hover { border-color: rgba(99,102,241,0.35); box-shadow: 0 4px 24px rgba(0,0,0,0.25); }
.emp-card-header { padding: 16px 18px; display: flex; align-items: center; gap: 14px; cursor: pointer; user-select: none; }
.emp-avatar { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; flex-shrink: 0; letter-spacing: -0.5px; }
.emp-card-info { flex: 1; min-width: 0; }
.emp-card-name { font-size: 0.92rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.emp-card-meta { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.emp-card-code { font-family: monospace; font-size: 0.72rem; color: var(--text-muted); background: rgba(255,255,255,0.05); padding: 2px 7px; border-radius: 5px; margin-left: 6px; }
.emp-card-right { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
.emp-badge-count { background: var(--accent); color: #fff; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
.emp-chevron { color: var(--text-muted); transition: transform 0.25s; font-size: 0.75rem; }
.emp-card.open .emp-chevron { transform: rotate(180deg); }
.emp-card-progress { padding: 0 18px 14px; display: flex; align-items: center; gap: 10px; }
.emp-prog-bar { flex: 1; height: 4px; background: var(--border); border-radius: 3px; overflow: hidden; }
.emp-prog-fill { height: 100%; border-radius: 3px; transition: width 0.5s ease; }
.emp-prog-label { font-size: 0.7rem; color: var(--text-muted); white-space: nowrap; }
.emp-trainings-body { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
.emp-card.open .emp-trainings-body { max-height: 800px; }
.emp-trainings-inner { border-top: 1px solid var(--border); padding: 14px 18px 16px; display: flex; flex-direction: column; gap: 8px; }
.emp-training-row { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.05); transition: background 0.15s; }
.emp-training-row:hover { background: rgba(99,102,241,0.08); }
.emp-training-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(99,102,241,0.15); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
.emp-training-info { flex: 1; min-width: 0; }
.emp-training-title { font-size: 0.82rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.emp-training-date  { font-size: 0.72rem; color: var(--text-muted); margin-top: 1px; }
.emp-training-score { font-size: 0.75rem; font-weight: 700; padding: 2px 9px; border-radius: 12px; flex-shrink: 0; }
.score-high { background: rgba(34,197,94,0.15); color: var(--success); }
.score-mid  { background: rgba(245,158,11,0.15); color: var(--warning); }
.score-low  { background: rgba(239,68,68,0.15);  color: var(--danger); }
.score-none { background: rgba(255,255,255,0.06); color: var(--text-muted); }
.emp-empty { grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--text-muted); font-size: 0.9rem; }
.emp-empty span { font-size: 2rem; display: block; margin-bottom: 12px; opacity: 0.4; }
.emp-skeleton { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; height: 90px; overflow: hidden; position: relative; }
.emp-skeleton::after { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.04) 50%, transparent 100%); animation: shimmer 1.4s infinite; }
@keyframes shimmer { from { transform: translateX(-100%); } to { transform: translateX(100%); } }
.state-msg { text-align: center; padding: 60px 20px; color: var(--text-muted); font-size: 0.9rem; }

/* ── Modal ── */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 200; align-items: center; justify-content: center; }
.modal-overlay.open { display: flex; }
.modal-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 28px; width: 100%; max-width: 440px; }
.modal-box h3 { font-size: 1rem; font-weight: 700; margin-bottom: 18px; }
.form-group { margin-bottom: 14px; }
.form-group label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.7px; }
.form-group input { width: 100%; background: var(--bg-dark); border: 1px solid var(--border); color: var(--text-primary); border-radius: 8px; padding: 9px 13px; font-size: 0.88rem; font-family: inherit; }
.form-group input:focus { outline: none; border-color: var(--accent); }
.modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }

/* ════════════════════════════════════════════
   PDF / PRINT STYLES
════════════════════════════════════════════ */
.print-header { display: none; }
#e-print-table { display: none; }
.print-footer  { display: none; }

@media print {
    @page { size: A4; margin: 18mm 14mm 22mm 14mm; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    .sidebar, .topbar, .report-tabs, .filter-bar,
    .btn-actions, .modal-overlay, .emp-search-bar,
    .emp-view-toggle, .emp-sort-select, .emp-kpi-strip,
    .v-kpi-strip, .report-header { display: none !important; }

    .main-content { margin-left: 0 !important; padding: 0 !important; }
    body, html { background: #fff !important; color: #1a1a2e !important; font-family: 'Inter', Arial, sans-serif !important; }

    .print-header { display: block !important; }
    .print-footer  { display: flex  !important; }

    .table-wrap { page-break-inside: avoid; overflow: visible !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; margin-bottom: 20px !important; }
    .emp-card   { page-break-inside: avoid; }

    table { width: 100% !important; border-collapse: collapse !important; font-size: 10.5px !important; }
    thead th { background: #6366f1 !important; color: #fff !important; font-size: 9.5px !important; font-weight: 700 !important; text-transform: uppercase !important; letter-spacing: 0.6px !important; padding: 9px 11px !important; text-align: left !important; }
    tbody tr { border-top: 1px solid #e5e7eb !important; }
    tbody tr:nth-child(even) { background: #f8f9fc !important; }
    tbody td { padding: 8px 11px !important; color: #1a1a2e !important; vertical-align: middle !important; }

    .badge { font-size: 9px !important; padding: 2px 8px !important; border-radius: 12px !important; font-weight: 700 !important; }
    .badge-present { background: #dcfce7 !important; color: #166534 !important; }
    .badge-absent  { background: #fee2e2 !important; color: #991b1b !important; }
    .badge-late    { background: #fef3c7 !important; color: #92400e !important; }

    .summary-grid { display: grid !important; grid-template-columns: repeat(3,1fr) !important; gap: 10px !important; margin-bottom: 18px !important; }
    .summary-card { background: #f8f9fc !important; border: 1px solid #d1d5db !important; border-radius: 8px !important; padding: 12px 14px !important; break-inside: avoid !important; }
    .summary-card .s-name  { color: #111 !important; font-size: 0.82rem !important; font-weight: 700 !important; }
    .summary-card .s-stats { color: #555 !important; font-size: 0.75rem !important; }
    .summary-card .s-stats strong { color: #111 !important; }
    .summary-card .s-rate  { color: #555 !important; font-size: 0.75rem !important; }
    .progress-bar      { background: #e5e7eb !important; }
    .progress-bar-fill { background: #6366f1 !important; }

    #t-list .table-wrap > div:first-child { background: #eef2ff !important; border-bottom: 1px solid #c7d2fe !important; padding: 10px 14px !important; }

    /* Tab employees: só mostrar tabela plana quando a classe está ativa */
    #e-print-table                          { display: none  !important; }
    body.printing-employees #e-cards-grid   { display: none  !important; }
    body.printing-employees #e-print-table  { display: table !important; }
}

/* ── Validity badges (relatório) ── */
.vbadge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.75rem; font-weight:700; }
.vbadge-valid    { background:rgba(34,197,94,0.15);  color:var(--success); }
.vbadge-expiring { background:rgba(245,158,11,0.15); color:var(--warning); }
.vbadge-expired  { background:rgba(239,68,68,0.15);  color:var(--danger);  }
/* KPIs de validade */
.v-kpi-strip { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:12px; margin-bottom:24px; }
.v-kpi { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:16px 18px; display:flex; flex-direction:column; gap:4px; cursor:pointer; transition:border-color .2s; }
.v-kpi:hover { border-color:rgba(99,102,241,.4); }
.v-kpi.active-filter { border-color:var(--accent)!important; box-shadow:0 0 0 2px rgba(99,102,241,.25); }
.v-kpi .kpi-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); }
.v-kpi .kpi-value { font-size:1.65rem; font-weight:800; line-height:1; }
.v-kpi .kpi-sub   { font-size:.72rem; color:var(--text-muted); margin-top:2px; }
@media print {
    .vbadge { font-size:9px!important; padding:2px 8px!important; border-radius:12px!important; font-weight:700!important; }
    .vbadge-valid    { background:#dcfce7!important; color:#166534!important; }
    .vbadge-expiring { background:#fef3c7!important; color:#92400e!important; }
    .vbadge-expired  { background:#fee2e2!important; color:#991b1b!important; }
    tr.row-expired  { background:#fff5f5!important; }
    tr.row-expiring { background:#fffbeb!important; }
}
/* ── Print Header ── */
.print-header { display: none; margin-bottom: 24px; border-bottom: 2px solid #6366f1; padding-bottom: 14px; }
.print-header-top { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
.print-header-logo { display: flex; align-items: center; gap: 12px; }
.print-header-logo img { height: 48px; width: auto; object-fit: contain; }
.print-header-logo-text { font-size: 1.25rem; font-weight: 800; color: #1a1a2e; letter-spacing: -0.5px; line-height: 1.2; }
.print-header-logo-text span { color: #6366f1; }
.print-header-meta { text-align: right; font-size: 11px; color: #6b7280; line-height: 1.7; }
.print-header-meta strong { color: #1a1a2e; font-size: 13px; font-weight: 700; display: block; margin-bottom: 2px; }
.print-header-divider { margin-top: 14px; display: flex; align-items: center; gap: 10px; }
.print-header-divider-label { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6366f1; white-space: nowrap; padding: 3px 10px; background: #eef2ff; border-radius: 4px; }
.print-header-divider-line { flex: 1; height: 1px; background: #e0e7ff; }

/* ── Print Footer ── */
.print-footer { display: none; margin-top: 32px; padding-top: 10px; border-top: 1px solid #e0e7ff; justify-content: space-between; align-items: center; font-size: 9.5px; color: #9ca3af; }
.print-footer-left { display: flex; align-items: center; gap: 8px; }
.print-footer-left img { height: 20px; width: auto; opacity: 0.6; }
.print-footer-right { font-size: 9px; color: #c1c7d4; }
</style>
@endsection

@section('content')

{{-- Print Header --}}
<div class="print-header" id="printHeaderBlock">
    <div class="print-header-top">
        <div class="print-header-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="HRElectrominho Logo">
            <div class="print-header-logo-text">HR<span>Electrominho</span></div>
        </div>
        <div class="print-header-meta">
            <strong id="printTitle">Relatório</strong>
            <span id="printDate"></span>
            <span id="printFilters"></span>
        </div>
    </div>
    <div class="print-header-divider">
        <span class="print-header-divider-label" id="printTabLabel">Relatório</span>
        <div class="print-header-divider-line"></div>
    </div>
</div>

{{-- Hidden flat table for Employees PDF --}}
<table id="e-print-table" style="display:none;width:100%;border-collapse:collapse">
    <thead>
        <tr>
            <th style="background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left">Código</th>
            <th style="background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left">Funcionário</th>
            <th style="background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left">Função</th>
            <th style="background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left">Setor</th>
            <th style="background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:center">Nº Form.</th>
            <th style="background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left">Formações Concluídas</th>
        </tr>
    </thead>
    <tbody id="e-print-tbody"></tbody>
</table>

{{-- Tabs --}}
<div class="report-tabs">
    <button class="report-tab active" onclick="switchTab('employees')">👥 Funcionários com Formações</button>
    <button class="report-tab" onclick="switchTab('trainings')">📚 Formações por Funcionários</button>
    <button class="report-tab" onclick="switchTab('attendance')">📅 Assiduidade</button>
    <button class="report-tab" onclick="switchTab('validity')">⏳ Validade de Formações</button>
</div>

{{-- TAB 1: Funcionários com Formações --}}
<div id="tab-employees">
    <div class="filter-bar">
        <div class="filter-group">
            <label>Setor</label>
            <select id="e-sector"><option value="">Todos</option></select>
        </div>
        <div class="filter-group">
            <label>Função</label>
            <select id="e-position"><option value="">Todas</option></select>
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary" onclick="loadEmployees()">🔍 Filtrar</button>
            <button class="btn btn-secondary" onclick="resetEmployees()">↺ Limpar</button>
            <button class="btn btn-pdf" onclick="exportPdf('employees')">📄 PDF</button>
            <button class="btn btn-success" onclick="openEmail('employees')">✉️ Email</button>
        </div>
    </div>
    <div class="emp-kpi-strip" id="e-kpi-strip">
        <div class="emp-kpi"><span class="kpi-label">Funcionários</span><span class="kpi-value" id="kpi-total">—</span><span class="kpi-sub">com formações</span></div>
        <div class="emp-kpi"><span class="kpi-label">Total Formações</span><span class="kpi-value" id="kpi-trainings">—</span><span class="kpi-sub">registos concluídos</span></div>
        <div class="emp-kpi"><span class="kpi-label">Média por Func.</span><span class="kpi-value" id="kpi-avg">—</span><span class="kpi-sub">formações/pessoa</span></div>
        <div class="emp-kpi"><span class="kpi-label">Mais Formações</span><span class="kpi-value" id="kpi-top" style="font-size:1rem;padding-top:4px">—</span><span class="kpi-sub" id="kpi-top-sub"></span></div>
    </div>
    <div class="emp-search-bar">
        <div class="emp-search-wrap">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input class="emp-search-input" id="e-search" type="text" placeholder="Pesquisar funcionário, função ou setor…" oninput="renderCards()">
        </div>
        <select class="emp-sort-select" id="e-sort" onchange="renderCards()">
            <option value="name">Ordenar: Nome</option>
            <option value="total_desc">Mais formações</option>
            <option value="total_asc">Menos formações</option>
            <option value="sector">Setor</option>
        </select>
        <div class="emp-view-toggle">
            <button class="emp-view-btn active" id="btn-grid" onclick="setView('grid')" title="Grelha">⊞</button>
            <button class="emp-view-btn" id="btn-list" onclick="setView('list')" title="Lista">☰</button>
        </div>
        <span style="font-size:0.8rem;color:var(--text-muted);align-self:center" id="e-count">—</span>
    </div>
    <div id="e-cards-grid"></div>
</div>

{{-- TAB 2: Formações por Funcionários --}}
<div id="tab-trainings" style="display:none">
    <div class="filter-bar">
        <div class="filter-group"><label>Formação</label><select id="t-training"><option value="">Todas</option></select></div>
        <div class="filter-group"><label>Setor</label><select id="t-sector"><option value="">Todos</option></select></div>
        <div class="filter-actions">
            <button class="btn btn-primary" onclick="loadTrainings()">🔍 Filtrar</button>
            <button class="btn btn-secondary" onclick="resetTrainings()">↺ Limpar</button>
        </div>
    </div>
    <div class="report-header">
        <div><h2>Formações e Funcionários que as Possuem</h2><span class="report-count" id="t-count">—</span></div>
        <div class="btn-actions">
            <button class="btn btn-pdf" onclick="exportPdf('trainings')">📄 Exportar PDF</button>
            <button class="btn btn-success" onclick="openEmail('trainings')">✉️ Enviar por Email</button>
        </div>
    </div>
    <div id="t-list" class="state-msg">A carregar…</div>
</div>

{{-- TAB 3: Assiduidade --}}
<div id="tab-attendance" style="display:none">
    <div class="filter-bar">
        <div class="filter-group"><label>Funcionário</label><select id="a-employee" style="min-width:180px"><option value="">Todos</option></select></div>
        <div class="filter-group"><label>Setor</label><select id="a-sector"><option value="">Todos</option></select></div>
        <div class="filter-group">
            <label>Estado</label>
            <select id="a-status">
                <option value="">Todos</option>
                <option value="present">Presente</option>
                <option value="absent">Ausente</option>
                <option value="late">Atrasado</option>
            </select>
        </div>
        <div class="filter-group"><label>De</label><input type="date" id="a-from"></div>
        <div class="filter-group"><label>Até</label><input type="date" id="a-to"></div>
        <div class="filter-actions">
            <button class="btn btn-primary" onclick="loadAttendance()">🔍 Filtrar</button>
            <button class="btn btn-secondary" onclick="resetAttendance()">↺ Limpar</button>
        </div>
    </div>
    <div class="report-header">
        <div><h2>Relatório de Assiduidade</h2><span class="report-count" id="a-count">—</span></div>
        <div class="btn-actions">
            <button class="btn btn-pdf" onclick="exportPdf('attendance')">📄 Exportar PDF</button>
            <button class="btn btn-success" onclick="openEmail('attendance')">✉️ Enviar por Email</button>
        </div>
    </div>
    <div class="summary-grid" id="a-summary"></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Funcionário</th><th>Setor</th><th>Data</th><th>Entrada</th><th>Saída</th><th>Estado</th></tr></thead>
            <tbody id="a-tbody"><tr><td colspan="6" class="state-msg">A carregar…</td></tr></tbody>
        </table>
    </div>
</div>

{{-- TAB 4: Validade de Formações --}}
<div id="tab-validity" style="display:none">
    <div class="filter-bar">
        <div class="filter-group">
            <label>Estado de Validade</label>
            <select id="v-status">
                <option value="">Todos</option>
                <option value="expired">⚠️ Expirada</option>
                <option value="expiring">🔔 A Expirar (30 dias)</option>
                <option value="valid">✅ Válida</option>
            </select>
        </div>
        <div class="filter-group"><label>Funcionário</label><select id="v-employee" style="min-width:190px"><option value="">Todos</option></select></div>
        <div class="filter-group"><label>Formação</label><select id="v-training" style="min-width:190px"><option value="">Todas</option></select></div>
        <div class="filter-group"><label>Setor</label><select id="v-sector"><option value="">Todos</option></select></div>
        <div class="filter-actions">
            <button class="btn btn-primary" onclick="loadValidity()">🔍 Filtrar</button>
            <button class="btn btn-secondary" onclick="resetValidity()">↺ Limpar</button>
            <button class="btn btn-pdf" onclick="exportPdf('validity')">📄 Exportar PDF</button>
            <button class="btn btn-success" onclick="openEmail('validity')">✉️ Enviar por Email</button>
        </div>
    </div>

    {{-- KPIs clicáveis --}}
    <div class="v-kpi-strip" id="v-kpi-strip">
        <div class="v-kpi" id="vkpi-all"      onclick="vFilterKpi('')">
            <span class="kpi-label">Total c/ Validade</span>
            <span class="kpi-value" id="vkpi-total">—</span>
            <span class="kpi-sub">registos com validade definida</span>
        </div>
        <div class="v-kpi" id="vkpi-expired"  onclick="vFilterKpi('expired')"  style="border-color:rgba(239,68,68,.3)">
            <span class="kpi-label" style="color:#ef4444">⚠️ Expiradas</span>
            <span class="kpi-value" id="vkpi-expired-val" style="color:#ef4444">—</span>
            <span class="kpi-sub">precisam de renovação</span>
        </div>
        <div class="v-kpi" id="vkpi-expiring" onclick="vFilterKpi('expiring')" style="border-color:rgba(245,158,11,.3)">
            <span class="kpi-label" style="color:#f59e0b">🔔 A Expirar</span>
            <span class="kpi-value" id="vkpi-expiring-val" style="color:#f59e0b">—</span>
            <span class="kpi-sub">nos próximos 30 dias</span>
        </div>
        <div class="v-kpi" id="vkpi-valid"    onclick="vFilterKpi('valid')"    style="border-color:rgba(34,197,94,.3)">
            <span class="kpi-label" style="color:#22c55e">✅ Válidas</span>
            <span class="kpi-value" id="vkpi-valid-val" style="color:#22c55e">—</span>
            <span class="kpi-sub">dentro do prazo</span>
        </div>
    </div>

    <div class="report-header">
        <div><h2>Controlo de Validade de Formações</h2><span class="report-count" id="v-count">—</span></div>
    </div>

    <div class="table-wrap">
        <table id="v-table">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Código</th>
                    <th>Setor</th>
                    <th>Função</th>
                    <th>Formação</th>
                    <th>Fornecedor</th>
                    <th>Data de Fim</th>
                    <th>Validade</th>
                    <th>Expira em</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="v-tbody">
                <tr><td colspan="10" class="state-msg">A carregar…</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Print Footer --}}
<div class="print-footer">
    <div class="print-footer-left">
        <img src="{{ asset('images/logo.jpg') }}" alt="HREminho">
        <span>HREminho — Sistema de Gestão de Recursos Humanos</span>
    </div>
    <div class="print-footer-right">Documento gerado automaticamente · Confidencial</div>
</div>

{{-- Email Modal --}}
<div class="modal-overlay" id="emailModal">
    <div class="modal-box">
        <h3>✉️ Enviar Relatório por Email</h3>
        <div class="form-group"><label>Endereço de Email</label><input type="email" id="emailAddr" placeholder="exemplo@empresa.com"></div>
        <div class="form-group"><label>Assunto (opcional)</label><input type="text" id="emailSubject" placeholder="Relatório HREminho — {{ now()->format('d/m/Y') }}"></div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeEmail()">Cancelar</button>
            <button class="btn btn-success" id="sendEmailBtn" onclick="sendEmail()">✉️ Enviar</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let currentTab = 'employees';

async function loadDropdowns() {
    const [sectors, trainings, positions, employees] = await Promise.all([
        fetch('/api/v1/sectors?all=1').then(r => r.json()),
        fetch('/api/v1/trainings?all=1').then(r => r.json()),
        fetch('/api/v1/positions?all=1').then(r => r.json()),
        fetch('/api/v1/employees?per_page=9999').then(r => r.json()),
    ]);
    const sectorList   = sectors.data   ?? sectors;
    const trainingList = trainings.data ?? trainings;
    const positionList = positions.data ?? positions;
    const employeeList = employees.data ?? employees;

    ['e-sector','t-sector','a-sector','v-sector'].forEach(id => {
        const el = document.getElementById(id);
        sectorList.forEach(s => el.add(new Option(s.sector, s.id)));
    });
    ['t-training','v-training'].forEach(id => {
        const el = document.getElementById(id);
        trainingList.forEach(t => el.add(new Option(t.title, t.id)));
    });
    const ePos = document.getElementById('e-position');
    positionList.forEach(p => ePos.add(new Option(p.position, p.id)));
    ['a-employee','v-employee'].forEach(id => {
        const el = document.getElementById(id);
        employeeList.forEach(e => {
            const label = (e.full_name || (e.first_name + ' ' + e.last_name).trim()) + (e.code ? ' (' + e.code + ')' : '');
            el.add(new Option(label.trim(), e.id));
        });
    });
}

function switchTab(tab) {
    currentTab = tab;
    ['employees','trainings','attendance','validity'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === tab ? '' : 'none';
    });
    document.querySelectorAll('.report-tab').forEach((btn, i) => {
        btn.classList.toggle('active', ['employees','trainings','attendance','validity'][i] === tab);
    });
    if (tab === 'employees'  && !document.getElementById('e-count').dataset.loaded) loadEmployees();
    if (tab === 'trainings'  && !document.getElementById('t-count').dataset.loaded) loadTrainings();
    if (tab === 'attendance' && !document.getElementById('a-count').dataset.loaded) loadAttendance();
    if (tab === 'validity'   && !document.getElementById('v-count').dataset.loaded) loadValidity();
}

function qs(params) {
    return Object.entries(params).filter(([,v]) => v).map(([k,v]) => `${k}=${encodeURIComponent(v)}`).join('&');
}
function fmt(d) { return d ? new Date(d + 'T00:00:00').toLocaleDateString('pt-PT') : '—'; }
function statusBadge(s) {
    const map = { present:'badge-present', absent:'badge-absent', late:'badge-late' };
    const lbl = { present:'Presente', absent:'Ausente', late:'Atrasado' };
    return `<span class="badge ${map[s] ?? ''}">${lbl[s] ?? s}</span>`;
}

let empData = [];
const AVATAR_COLORS = [
    ['rgba(99,102,241,0.25)','#818cf8'],['rgba(236,72,153,0.2)','#f472b6'],
    ['rgba(34,197,94,0.2)','#4ade80'],['rgba(245,158,11,0.2)','#fbbf24'],
    ['rgba(59,130,246,0.2)','#60a5fa'],['rgba(239,68,68,0.2)','#f87171'],
    ['rgba(20,184,166,0.2)','#2dd4bf'],['rgba(168,85,247,0.2)','#c084fc'],
];
function avatarColor(name) {
    let h = 0;
    for (let i = 0; i < name.length; i++) h = (h * 31 + name.charCodeAt(i)) & 0xffff;
    return AVATAR_COLORS[h % AVATAR_COLORS.length];
}
function initials(name) {
    return name.split(' ').filter(Boolean).slice(0,2).map(w => w[0]).join('').toUpperCase();
}
function scoreClass(s) {
    if (s === null || s === undefined || s === '') return 'score-none';
    const n = parseFloat(s);
    if (n >= 75) return 'score-high';
    if (n >= 50) return 'score-mid';
    return 'score-low';
}
function scoreLabel(s) {
    if (s === null || s === undefined || s === '') return '—';
    return parseFloat(s) + 'pt';
}
function showSkeletons() {
    document.getElementById('e-cards-grid').innerHTML = Array(6).fill('<div class="emp-skeleton"></div>').join('');
}

async function loadEmployees() {
    showSkeletons();
    document.getElementById('e-count').textContent = '—';
    document.getElementById('e-count').dataset.loaded = '';
    const params = qs({ sector_id: document.getElementById('e-sector').value, position_id: document.getElementById('e-position').value });
    const res = await fetch('/api/v1/reports/employees-trainings?' + params).then(r => r.json());
    empData = res.data || [];
    const totalTrainings = empData.reduce((s,e) => s + (e.total_completed||0), 0);
    const avg = empData.length ? (totalTrainings / empData.length).toFixed(1) : '—';
    const top = empData.length ? [...empData].sort((a,b) => b.total_completed - a.total_completed)[0] : null;
    document.getElementById('kpi-total').textContent     = empData.length;
    document.getElementById('kpi-trainings').textContent = totalTrainings;
    document.getElementById('kpi-avg').textContent       = avg;
    if (top) {
        document.getElementById('kpi-top').textContent     = top.name.split(' ')[0];
        document.getElementById('kpi-top-sub').textContent = top.total_completed + ' formações';
    }
    document.getElementById('e-count').dataset.loaded = '1';
    renderCards();
}

function renderCards() {
    const grid   = document.getElementById('e-cards-grid');
    const search = (document.getElementById('e-search').value || '').toLowerCase().trim();
    const sort   = document.getElementById('e-sort').value;
    let data = empData.filter(e => {
        if (!search) return true;
        return (e.name||'').toLowerCase().includes(search)
            || (e.sector||'').toLowerCase().includes(search)
            || (e.position||'').toLowerCase().includes(search)
            || (e.code||'').toLowerCase().includes(search);
    });
    if (sort === 'name')       data.sort((a,b) => a.name.localeCompare(b.name));
    if (sort === 'total_desc') data.sort((a,b) => b.total_completed - a.total_completed);
    if (sort === 'total_asc')  data.sort((a,b) => a.total_completed - b.total_completed);
    if (sort === 'sector')     data.sort((a,b) => (a.sector||'').localeCompare(b.sector||''));
    document.getElementById('e-count').textContent = data.length + ' funcionário(s)';
    const maxT = data.length ? Math.max(...data.map(e => e.total_completed||0)) : 1;
    if (!data.length) { grid.innerHTML = '<div class="emp-empty"><span>👥</span>Nenhum funcionário encontrado.</div>'; return; }
    grid.innerHTML = data.map((e, idx) => {
        const [bgColor, textColor] = avatarColor(e.name);
        const pct = maxT > 0 ? Math.round((e.total_completed / maxT) * 100) : 0;
        const progColor = pct >= 75 ? 'var(--success)' : pct >= 40 ? 'var(--warning)' : 'var(--accent)';
        const trainingsHtml = (e.trainings||[]).map(t => `
            <div class="emp-training-row">
                <div class="emp-training-icon">🎓</div>
                <div class="emp-training-info">
                    <div class="emp-training-title">${t.title}</div>
                    <div class="emp-training-date">${fmt(t.completed_at)}</div>
                </div>
                <span class="emp-training-score ${scoreClass(t.score)}">${scoreLabel(t.score)}</span>
            </div>`).join('');
        return `<div class="emp-card" id="emp-card-${idx}">
            <div class="emp-card-header" onclick="toggleCard(${idx})">
                <div class="emp-avatar" style="background:${bgColor};color:${textColor}">${initials(e.name)}</div>
                <div class="emp-card-info">
                    <div class="emp-card-name">${e.name}<span class="emp-card-code">${e.code}</span></div>
                    <div class="emp-card-meta">${e.position} · ${e.sector}</div>
                </div>
                <div class="emp-card-right">
                    <span class="emp-badge-count">${e.total_completed} formação${e.total_completed !== 1 ? 'ões' : ''}</span>
                    <span class="emp-chevron">▼</span>
                </div>
            </div>
            <div class="emp-card-progress">
                <div class="emp-prog-bar"><div class="emp-prog-fill" style="width:${pct}%;background:${progColor}"></div></div>
                <span class="emp-prog-label">${pct}%</span>
            </div>
            <div class="emp-trainings-body">
                <div class="emp-trainings-inner">
                    ${trainingsHtml || '<div style="color:var(--text-muted);font-size:0.82rem;text-align:center;padding:8px 0">Sem formações registadas</div>'}
                </div>
            </div>
        </div>`;
    }).join('');
}
function toggleCard(idx) { document.getElementById('emp-card-' + idx).classList.toggle('open'); }
function setView(v) {
    document.getElementById('e-cards-grid').classList.toggle('list-view', v === 'list');
    document.getElementById('btn-grid').classList.toggle('active', v === 'grid');
    document.getElementById('btn-list').classList.toggle('active', v === 'list');
}
function resetEmployees() {
    ['e-sector','e-position'].forEach(id => { document.getElementById(id).value = ''; });
    document.getElementById('e-search').value = '';
    loadEmployees();
}

async function loadTrainings() {
    const list = document.getElementById('t-list');
    list.innerHTML = '<div class="state-msg">A carregar…</div>';
    const params = qs({ training_id: document.getElementById('t-training').value, sector_id: document.getElementById('t-sector').value });
    const res = await fetch('/api/v1/reports/training-employees?' + params).then(r => r.json());
    const count = document.getElementById('t-count');
    count.textContent = res.total + ' formação(ões)';
    count.dataset.loaded = '1';
    if (!res.data || !res.data.length) { list.innerHTML = '<div class="state-msg">Sem resultados.</div>'; return; }
    list.innerHTML = res.data.map(t => `
        <div class="table-wrap" style="margin-bottom:20px">
            <div style="padding:12px 16px;background:rgba(99,102,241,0.08);border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                <div>
                    <span style="font-weight:700;font-size:.92rem">📚 ${t.title}</span>
                    <span style="color:var(--text-muted);font-size:.78rem;margin-left:10px">${t.provider}</span>
                </div>
                <span style="background:rgba(99,102,241,0.15);color:var(--accent-light);padding:3px 12px;border-radius:20px;font-size:.75rem;font-weight:700">${t.total} funcionário(s)</span>
            </div>
            <table>
                <thead><tr><th>Código</th><th>Funcionário</th><th>Função</th><th>Setor</th><th>Pontuação</th><th>Concluído em</th></tr></thead>
                <tbody>${t.employees.map(e => `<tr>
                    <td><span style="font-family:monospace;font-size:.78rem;color:var(--text-muted)">${e.code}</span></td>
                    <td>${e.name}</td><td>${e.position}</td><td>${e.sector}</td>
                    <td>${e.score ?? '—'}</td><td>${fmt(e.completed_at)}</td>
                </tr>`).join('')}</tbody>
            </table>
        </div>`).join('');
}
function resetTrainings() {
    ['t-training','t-sector'].forEach(id => { document.getElementById(id).value = ''; });
    loadTrainings();
}

async function loadAttendance() {
    const tbody = document.getElementById('a-tbody');
    tbody.innerHTML = '<tr><td colspan="6" class="state-msg">A carregar…</td></tr>';
    const params = qs({ employee_id: document.getElementById('a-employee').value, sector_id: document.getElementById('a-sector').value, status: document.getElementById('a-status').value, date_from: document.getElementById('a-from').value, date_to: document.getElementById('a-to').value });
    const res = await fetch('/api/v1/reports/attendance?' + params).then(r => r.json());
    const count = document.getElementById('a-count');
    count.textContent = res.total + ' registo(s)';
    count.dataset.loaded = '1';
    const sumGrid = document.getElementById('a-summary');
    if (res.summary && res.summary.length) {
        sumGrid.innerHTML = res.summary.map(s => `
            <div class="summary-card">
                <div class="s-name" title="${s.employee}">${s.employee}</div>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:6px">${s.sector} · ${s.position}</div>
                <div class="s-stats">
                    <span>✅ <strong>${s.present}</strong> pres.</span>
                    <span>❌ <strong>${s.absent}</strong> aus.</span>
                    <span>⏰ <strong>${s.late}</strong> atras.</span>
                </div>
                <div class="s-rate" style="color:var(--text-muted)">Taxa: <strong style="color:${s.rate>=80?'var(--success)':s.rate>=60?'var(--warning)':'var(--danger)'}">${s.rate}%</strong></div>
                <div class="progress-bar"><div class="progress-bar-fill" style="width:${s.rate}%;background:${s.rate>=80?'var(--success)':s.rate>=60?'var(--warning)':'var(--danger)'}"></div></div>
            </div>`).join('');
        sumGrid.style.display = '';
    } else {
        sumGrid.innerHTML = '';
        sumGrid.style.display = 'none';
    }
    if (!res.data.length) { tbody.innerHTML = '<tr><td colspan="6" class="state-msg">Sem resultados.</td></tr>'; return; }
    tbody.innerHTML = res.data.map(r => `<tr>
        <td>${r.employee}</td><td>${r.sector}</td><td>${fmt(r.date)}</td>
        <td>${r.check_in}</td><td>${r.check_out}</td><td>${statusBadge(r.status)}</td>
    </tr>`).join('');
}
function resetAttendance() {
    ['a-employee','a-sector','a-status','a-from','a-to'].forEach(id => { document.getElementById(id).value = ''; });
    loadAttendance();
}

/* ══════════════════════════════════════════
   TAB VALIDADE
══════════════════════════════════════════ */
let validityData = [];
let vActiveKpi   = '';

const vLabel = { valid:'✅ Válida', expiring:'🔔 A expirar', expired:'⚠️ Expirada' };
const vClass  = { valid:'vbadge-valid', expiring:'vbadge-expiring', expired:'vbadge-expired' };
const vRowCls = { expired:'row-expired', expiring:'row-expiring', valid:'' };

async function loadValidity() {
    const tbody = document.getElementById('v-tbody');
    tbody.innerHTML = '<tr><td colspan="10" class="state-msg">A carregar…</td></tr>';
    document.getElementById('v-count').textContent = '—';
    document.getElementById('v-count').dataset.loaded = '';

    const params = qs({
        validity_status : document.getElementById('v-status').value,
        employee_id     : document.getElementById('v-employee').value,
        training_id     : document.getElementById('v-training').value,
        sector_id       : document.getElementById('v-sector').value,
    });

    const res = await fetch('/api/v1/reports/validity?' + params).then(r => r.json());
    validityData = res.data || [];

    // Atualizar KPIs
    const kpi = res.kpi || {};
    document.getElementById('vkpi-total').textContent        = kpi.total    ?? 0;
    document.getElementById('vkpi-expired-val').textContent  = kpi.expired  ?? 0;
    document.getElementById('vkpi-expiring-val').textContent = kpi.expiring ?? 0;
    document.getElementById('vkpi-valid-val').textContent    = kpi.valid    ?? 0;

    document.getElementById('v-count').textContent  = res.total + ' registo(s)';
    document.getElementById('v-count').dataset.loaded = '1';

    renderValidity(validityData);
}

function renderValidity(rows) {
    const tbody = document.getElementById('v-tbody');
    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="10" class="state-msg">Nenhum registo encontrado.</td></tr>';
        return;
    }
    tbody.innerHTML = rows.map(r => {
        const vs    = r.validity_status;
        const badge = vs ? `<span class="vbadge ${vClass[vs]??''}">${vLabel[vs]??vs}</span>` : '—';
        const rowCls= vRowCls[vs] ?? '';
        return `<tr class="${rowCls}">
            <td style="font-weight:600">${r.employee}</td>
            <td><span style="font-family:monospace;font-size:.78rem;color:var(--text-muted)">${r.employee_code}</span></td>
            <td>${r.sector}</td>
            <td>${r.position}</td>
            <td>${r.training}</td>
            <td style="color:var(--text-muted);font-size:.82rem">${r.provider}</td>
            <td style="color:var(--text-muted)">${fmt(r.end_date)}</td>
            <td style="font-size:.82rem">${r.validity_months ? r.validity_months + ' mês' + (r.validity_months > 1 ? 'es' : '') : '—'}</td>
            <td style="font-weight:600">${fmt(r.expiry_date)}</td>
            <td>${badge}</td>
        </tr>`;
    }).join('');
}

function vFilterKpi(status) {
    // Toggle: clicar novamente no mesmo KPI limpa o filtro
    vActiveKpi = vActiveKpi === status ? '' : status;
    document.getElementById('v-status').value = vActiveKpi;
    // Destacar KPI ativo
    const kpiMap = { '':'vkpi-all', expired:'vkpi-expired', expiring:'vkpi-expiring', valid:'vkpi-valid' };
    document.querySelectorAll('.v-kpi').forEach(el => el.classList.remove('active-filter'));
    document.getElementById(kpiMap[vActiveKpi])?.classList.add('active-filter');
    loadValidity();
}

function resetValidity() {
    ['v-status','v-employee','v-training','v-sector'].forEach(id => { document.getElementById(id).value = ''; });
    vActiveKpi = '';
    document.querySelectorAll('.v-kpi').forEach(el => el.classList.remove('active-filter'));
    loadValidity();
}

function buildValidityPrintTable(rows) {
    const thStyle = 'background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left';
    const header  = `<tr>
        <th style="${thStyle}">Funcionário</th>
        <th style="${thStyle}">Cód.</th>
        <th style="${thStyle}">Setor</th>
        <th style="${thStyle}">Função</th>
        <th style="${thStyle}">Formação</th>
        <th style="${thStyle}">Fornecedor</th>
        <th style="${thStyle}">Data Fim</th>
        <th style="${thStyle}">Val. (m)</th>
        <th style="${thStyle}">Expira em</th>
        <th style="${thStyle}">Estado</th>
    </tr>`;
    const bgMap   = { expired:'#fff5f5', expiring:'#fffbeb', valid:'', '':'' };
    const badgeMap= {
        valid    : 'background:#dcfce7;color:#166534',
        expiring : 'background:#fef3c7;color:#92400e',
        expired  : 'background:#fee2e2;color:#991b1b',
    };
    const tbody   = rows.map((r, i) => {
        const bg  = bgMap[r.validity_status ?? ''] || (i % 2 === 1 ? '#f8f9fc' : '');
        const vs  = r.validity_status;
        const bdg = vs
            ? `<span style="${badgeMap[vs]??''};padding:2px 8px;border-radius:12px;font-size:9px;font-weight:700">${vLabel[vs]}</span>`
            : '—';
        const td  = `padding:8px 11px;border-top:1px solid #e5e7eb;color:#1a1a2e`;
        return `<tr style="background:${bg}">
            <td style="${td};font-weight:600">${r.employee}</td>
            <td style="${td};font-family:monospace;font-size:9px;color:#6b7280">${r.employee_code}</td>
            <td style="${td}">${r.sector}</td>
            <td style="${td}">${r.position}</td>
            <td style="${td};font-weight:600">${r.training}</td>
            <td style="${td};color:#6b7280;font-size:9px">${r.provider}</td>
            <td style="${td}">${fmt(r.end_date)}</td>
            <td style="${td};text-align:center">${r.validity_months ?? '—'}</td>
            <td style="${td};font-weight:600">${fmt(r.expiry_date)}</td>
            <td style="${td}">${bdg}</td>
        </tr>`;
    }).join('');
    return `<table style="width:100%;border-collapse:collapse;font-size:10.5px"><thead>${header}</thead><tbody>${tbody}</tbody></table>`;
}

function exportPdf(tab) {
    const titles = { employees:'Funcionários com Formações', trainings:'Formações por Funcionários', attendance:'Relatório de Assiduidade', validity:'Validade de Formações' };
    document.getElementById('printTitle').textContent    = titles[tab];
    document.getElementById('printTabLabel').textContent = titles[tab];
    const now = new Date();
    document.getElementById('printDate').textContent = 'Gerado em ' + now.toLocaleDateString('pt-PT', {day:'2-digit',month:'long',year:'numeric'}) + ' às ' + now.toLocaleTimeString('pt-PT', {hour:'2-digit',minute:'2-digit'});
    let fp = [];
    if (tab === 'employees') {
        const sec = document.getElementById('e-sector'), pos = document.getElementById('e-position');
        if (sec.value) fp.push('Setor: ' + sec.options[sec.selectedIndex].text);
        if (pos.value) fp.push('Função: ' + pos.options[pos.selectedIndex].text);
    } else if (tab === 'trainings') {
        const tr = document.getElementById('t-training'), sec = document.getElementById('t-sector');
        if (tr.value)  fp.push('Formação: ' + tr.options[tr.selectedIndex].text);
        if (sec.value) fp.push('Setor: ' + sec.options[sec.selectedIndex].text);
    } else {
        const emp = document.getElementById('a-employee'), sec = document.getElementById('a-sector'), sta = document.getElementById('a-status');
        const from = document.getElementById('a-from').value, to = document.getElementById('a-to').value;
        if (emp.value) fp.push('Funcionário: ' + emp.options[emp.selectedIndex].text);
        if (sec.value) fp.push('Setor: ' + sec.options[sec.selectedIndex].text);
        if (sta.value) fp.push('Estado: ' + sta.options[sta.selectedIndex].text);
        if (from) fp.push('De: ' + fmt(from));
        if (to)   fp.push('Até: ' + fmt(to));
    }
    document.getElementById('printFilters').textContent = fp.length ? ' · ' + fp.join(' · ') : '';

    // Popular tabela plana para o PDF de funcionários
    if (tab === 'employees') {
        const search = (document.getElementById('e-search').value || '').toLowerCase().trim();
        const sort   = document.getElementById('e-sort').value;
        let data = empData.filter(e => !search || (e.name||'').toLowerCase().includes(search) || (e.sector||'').toLowerCase().includes(search) || (e.position||'').toLowerCase().includes(search) || (e.code||'').toLowerCase().includes(search));
        if (sort === 'name')       data.sort((a,b) => a.name.localeCompare(b.name));
        if (sort === 'total_desc') data.sort((a,b) => b.total_completed - a.total_completed);
        if (sort === 'total_asc')  data.sort((a,b) => a.total_completed - b.total_completed);
        if (sort === 'sector')     data.sort((a,b) => (a.sector||'').localeCompare(b.sector||''));
        document.getElementById('e-print-tbody').innerHTML = data.map((e,i) => {
            const names = (e.trainings||[]).map(t => t.title + ((t.score !== null && t.score !== undefined && t.score !== '') ? ` (${parseFloat(t.score)}pt)` : '')).join('; ');
            const bg = i % 2 === 1 ? 'background:#f8f9fc' : '';
            return `<tr style="${bg}">
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;font-family:monospace;font-size:9.5px;color:#6b7280">${e.code}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;font-weight:600;color:#1a1a2e">${e.name}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;color:#1a1a2e">${e.position}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;color:#1a1a2e">${e.sector}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;text-align:center"><span style="background:#e0e7ff;color:#4338ca;padding:2px 9px;border-radius:12px;font-size:9px;font-weight:700">${e.total_completed}</span></td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;color:#374151;font-size:9.5px">${names||'—'}</td>
            </tr>`;
        }).join('');
        document.body.classList.add('printing-employees');
    } else if (tab === 'validity') {
        document.body.classList.remove('printing-employees');
        const vs  = document.getElementById('v-status');
        const ve  = document.getElementById('v-employee');
        const vt  = document.getElementById('v-training');
        const vsc = document.getElementById('v-sector');
        if (vs.value)  fp.push('Estado: ' + vs.options[vs.selectedIndex].text);
        if (ve.value)  fp.push('Funcionário: ' + ve.options[ve.selectedIndex].text);
        if (vt.value)  fp.push('Formação: ' + vt.options[vt.selectedIndex].text);
        if (vsc.value) fp.push('Setor: ' + vsc.options[vsc.selectedIndex].text);
        // Injetar tabela de validade numa div temporária para impressão
        let vPrintDiv = document.getElementById('v-print-block');
        if (!vPrintDiv) { vPrintDiv = document.createElement('div'); vPrintDiv.id = 'v-print-block'; document.body.appendChild(vPrintDiv); }
        vPrintDiv.innerHTML = buildValidityPrintTable(validityData);
        vPrintDiv.style.display = 'block';
        document.getElementById('v-table').style.display = 'none';
        setTimeout(() => {
            window.print();
            setTimeout(() => { vPrintDiv.style.display = 'none'; document.getElementById('v-table').style.display = ''; }, 500);
        }, 300);
        document.getElementById('printFilters').textContent = fp.length ? ' · ' + fp.join(' · ') : '';
        return; // já chama window.print() acima
    } else {
        document.body.classList.remove('printing-employees');
    }

    setTimeout(() => window.print(), 300);
}

let emailTab = '';
function openEmail(tab) {
    emailTab = tab;
    document.getElementById('emailAddr').value = '';
    document.getElementById('emailSubject').value = '';
    document.getElementById('emailModal').classList.add('open');
}
function closeEmail() { document.getElementById('emailModal').classList.remove('open'); }

async function sendEmail() {
    const email = document.getElementById('emailAddr').value.trim();
    if (!email) { alert('Insira um endereço de email válido.'); return; }
    const tabMap   = { employees:'tab-employees', trainings:'tab-trainings', attendance:'tab-attendance', validity:'tab-validity' };
    const typeMap  = { employees:'employees_trainings', trainings:'training_employees', attendance:'attendance', validity:'validity' };
    const titleMap = { employees:'Funcionários com Formações', trainings:'Formações por Funcionários', attendance:'Relatório de Assiduidade', validity:'Validade de Formações' };
    let tableHtml;
    if (emailTab === 'trainings') {
        tableHtml = document.getElementById('t-list')?.innerHTML ?? '<p>Sem dados</p>';
    } else if (emailTab === 'validity') {
        tableHtml = buildValidityPrintTable(validityData);
    } else {
        const tabEl = document.getElementById(tabMap[emailTab]);
        tableHtml = tabEl ? (tabEl.querySelector('table')?.outerHTML ?? '<p>Sem dados</p>') : '<p>Sem dados</p>';
    }
    const subject = document.getElementById('emailSubject').value.trim() || `HREminho — ${titleMap[emailTab]} — ${new Date().toLocaleDateString('pt-PT')}`;
    const html = `<html><body style="font-family:Arial,sans-serif;color:#333">
        <h2 style="color:#6366f1">HREminho — ${titleMap[emailTab]}</h2>
        <p style="color:#888;font-size:13px">Gerado em ${new Date().toLocaleDateString('pt-PT')}</p>
        <style>table{border-collapse:collapse;width:100%}th{background:#f0f0f0;padding:8px;text-align:left;font-size:12px}td{padding:8px;border-bottom:1px solid #eee;font-size:12px}</style>
        ${tableHtml}
        <p style="margin-top:24px;color:#aaa;font-size:11px">— HREminho</p>
    </body></html>`;
    const btn = document.getElementById('sendEmailBtn');
    btn.disabled = true; btn.textContent = 'A enviar…';
    try {
        const res = await fetch('/api/v1/reports/send-email', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ email, type: typeMap[emailTab], subject, html }),
        }).then(r => r.json());
        closeEmail();
        showToast(res.message || 'Email enviado!', 'success');
    } catch(e) {
        showToast('Erro ao enviar email.', 'error');
    } finally {
        btn.disabled = false; btn.textContent = '✉️ Enviar';
    }
}

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.textContent = msg;
    Object.assign(t.style, { position:'fixed', bottom:'24px', right:'24px', zIndex:'999', background: type === 'success' ? 'var(--success)' : 'var(--danger)', color:'#fff', padding:'12px 20px', borderRadius:'10px', fontSize:'0.875rem', fontWeight:'600', boxShadow:'0 4px 20px rgba(0,0,0,0.3)', opacity:'0', transition:'opacity 0.25s' });
    document.body.appendChild(t);
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3500);
}

// Restaurar UI após impressão (inclui cancelamento do diálogo)
window.addEventListener('afterprint', () => {
    document.body.classList.remove('printing-employees');
    const vb = document.getElementById('v-print-block');
    if (vb) vb.style.display = 'none';
    const vt = document.getElementById('v-table');
    if (vt) vt.style.display = '';
});


loadDropdowns().then(() => { switchTab('employees'); });
</script>
@endsection
