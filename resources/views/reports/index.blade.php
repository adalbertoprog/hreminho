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

/* ── Multi-select ── */
.ms-wrap { position: relative; min-width: 190px; }
.ms-trigger {
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    background: var(--bg-dark); border: 1px solid var(--border); color: var(--text-primary);
    border-radius: 8px; padding: 7px 12px; font-size: 0.85rem; font-family: inherit;
    cursor: pointer; user-select: none; min-width: 190px; white-space: nowrap;
    transition: border-color .15s;
}
.ms-trigger:hover, .ms-trigger.open { border-color: var(--accent); }
.ms-trigger .ms-arrow { font-size: .65rem; opacity: .5; flex-shrink: 0; transition: transform .2s; }
.ms-trigger.open .ms-arrow { transform: rotate(180deg); }
.ms-label { flex: 1; overflow: hidden; text-overflow: ellipsis; }
.ms-badge { background: var(--accent); color: #fff; font-size: .68rem; font-weight: 700; padding: 1px 7px; border-radius: 10px; flex-shrink: 0; }
.ms-dropdown {
    display: none; position: absolute; top: calc(100% + 4px); left: 0; z-index: 300;
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,.35); min-width: 100%; max-width: 340px;
    overflow: hidden;
}
.ms-dropdown.open { display: block; }
.ms-search-wrap { padding: 8px 10px; border-bottom: 1px solid var(--border); }
.ms-search { width: 100%; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 6px; padding: 5px 9px; font-size: .82rem; font-family: inherit; color: var(--text-primary); }
.ms-search:focus { outline: none; border-color: var(--accent); }
.ms-list { max-height: 220px; overflow-y: auto; padding: 4px 0; }
.ms-item {
    display: flex; align-items: center; gap: 9px;
    padding: 7px 12px; font-size: .84rem; cursor: pointer;
    transition: background .1s; color: var(--text-primary);
}
.ms-item:hover { background: rgba(99,102,241,.1); }
.ms-item.selected { background: rgba(99,102,241,.08); color: var(--accent-light); }
.ms-item input[type=checkbox] { accent-color: var(--accent); width: 14px; height: 14px; flex-shrink: 0; cursor: pointer; }
.ms-footer { padding: 7px 10px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; gap: 8px; }
.ms-footer-info { font-size: .74rem; color: var(--text-muted); }
.ms-clear { font-size: .74rem; color: var(--accent-light); background: none; border: none; cursor: pointer; padding: 0; }
.ms-clear:hover { text-decoration: underline; }

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
.emp-trainings-body { max-height: 0; overflow: hidden; transition: max-height 0.5s ease; }
.emp-card.open .emp-trainings-body { max-height: 10000px; }
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
#t-print-block, #v-print-block { display: none; }
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
    .print-footer  { display: flex !important; position: fixed !important; bottom: 0 !important; left: 0 !important; right: 0 !important; margin: 0 !important; padding: 8px 14mm !important; background: #fff !important; }

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

    /* Tab trainings: tabela gerada dinamicamente */
    #t-print-block { display: none !important; }
    body.printing-trainings #t-print-block { display: block !important; }
    body.printing-trainings #t-list        { display: none  !important; }

    /* Tab validity: tabela gerada dinamicamente */
    #v-print-block { display: none !important; }
    body.printing-validity #v-print-block { display: block !important; }
    body.printing-validity #v-table       { display: none  !important; }
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
/* ── Gap Analysis ── */
.gap-section { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; margin-bottom:20px; overflow:hidden; }
.gap-section-header { display:flex; align-items:flex-start; gap:14px; padding:16px 20px; border-bottom:1px solid var(--border); background:rgba(99,102,241,0.04); }
.gap-section-icon { font-size:1.4rem; margin-top:2px; }
.gap-section-title { font-size:.9rem; font-weight:700; color:var(--text-primary); }
.gap-section-sub { font-size:.78rem; color:var(--text-muted); margin-top:2px; }
.gap-rule { border-bottom:1px solid var(--border); }
.gap-rule:last-child { border-bottom:none; }
.gap-rule-header { display:flex; flex-wrap:wrap; align-items:center; gap:10px; padding:12px 20px; cursor:pointer; user-select:none; transition:background .15s; }
.gap-rule-header:hover { background:rgba(99,102,241,.04); }
.gap-rule-title { font-weight:600; font-size:.87rem; flex:1; min-width:120px; }
.gap-rule-meta { font-size:.78rem; color:var(--text-muted); }
.gap-rule-chevron { font-size:.7rem; color:var(--text-muted); transition:transform .2s; margin-left:auto; flex-shrink:0; }
.gap-rule.open .gap-rule-chevron { transform:rotate(180deg); }
.gap-rule-body { display:none; padding:0 20px 12px; }
.gap-rule.open .gap-rule-body { display:block; }
.gap-badge { display:inline-block; padding:2px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.gap-badge-danger { background:rgba(239,68,68,.15); color:var(--danger); }
.gap-emp-list { display:flex; flex-wrap:wrap; gap:6px; padding:4px 0; }
.gap-emp-chip { display:inline-flex; align-items:center; gap:5px; background:rgba(99,102,241,.1); color:var(--text-primary); padding:3px 10px; border-radius:20px; font-size:.75rem; }
.gap-emp-chip em { color:var(--text-muted); font-style:normal; font-family:monospace; font-size:.7rem; }
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

{{-- Hidden print blocks for trainings/validity PDFs --}}
<div id="t-print-block"></div>
<div id="v-print-block"></div>

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
    <button class="report-tab" onclick="switchTab('gaps')" id="tab-btn-gaps">🔍 Lacunas</button>
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
            <div class="ms-wrap" id="ms-e-position-wrap"></div>
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary" onclick="loadEmployees()">🔍 Filtrar</button>
            <button class="btn btn-secondary" onclick="resetEmployees()">↺ Limpar</button>
            <button class="btn btn-pdf" onclick="exportPdf('employees')">📄 PDF</button>
            <button class="btn btn-success" onclick="exportExcel('employees')">📊 Excel</button>
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
        <div class="filter-group">
            <label>Formação</label>
            <div class="ms-wrap" id="ms-t-training-wrap"></div>
        </div>
        <div class="filter-group">
            <label>Função</label>
            <div class="ms-wrap" id="ms-t-position-wrap"></div>
        </div>
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
            <button class="btn btn-success" onclick="exportExcel('trainings')">📊 Exportar Excel</button>
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
            <button class="btn btn-success" onclick="exportExcel('attendance')">📊 Exportar Excel</button>
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
            <button class="btn btn-success" onclick="exportExcel('validity')">📊 Exportar Excel</button>
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

{{-- TAB 5: Lacunas --}}
<div id="tab-gaps" style="display:none">
    <div class="filter-bar">
        <div class="filter-group">
            <label>Ano (Plano)</label>
            <select id="g-year">
                @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary" onclick="loadGaps()">🔍 Analisar</button>
            <button class="btn btn-secondary" onclick="resetGaps()">↺ Limpar</button>
        </div>
    </div>

    {{-- KPI strip --}}
    <div class="v-kpi-strip" id="g-kpi-strip" style="margin-bottom:24px">
        <div class="v-kpi" style="border-color:rgba(239,68,68,.3)">
            <span class="kpi-label" style="color:#ef4444">⚠️ Obrigatórias</span>
            <span class="kpi-value" id="g-kpi-mandatory" style="color:#ef4444">—</span>
            <span class="kpi-sub">funcionários em falta</span>
        </div>
        <div class="v-kpi" style="border-color:rgba(245,158,11,.3)">
            <span class="kpi-label" style="color:#f59e0b">🔔 Certificados</span>
            <span class="kpi-value" id="g-kpi-certs" style="color:#f59e0b">—</span>
            <span class="kpi-sub">expirados ou a expirar</span>
        </div>
        <div class="v-kpi" style="border-color:rgba(16,185,129,.3)">
            <span class="kpi-label" style="color:#10b981">📅 Plano vs Execução</span>
            <span class="kpi-value" id="g-kpi-plan" style="color:#10b981">—</span>
            <span class="kpi-sub">sessões abaixo de 70%</span>
        </div>
    </div>

    <div id="g-content">
        <div class="state-msg">Clique em "Analisar" para carregar as lacunas.</div>
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
window.REPORT_CONFIG = {
    currentYear: {{ now()->year }},
};
</script>
@vite(['resources/js/pages/reports.js'])
@endsection
