@extends('layouts.app')

@section('title', 'Obras e Equipas')

@section('styles')
<style>
/* -- Base -- */
.btn { padding:8px 18px; border-radius:9px; font-size:.85rem; font-weight:600; cursor:pointer; border:none; font-family:inherit; display:inline-flex; align-items:center; gap:7px; transition:.15s; }
.btn-primary   { background:var(--accent); color:#fff; }
.btn-primary:hover { background:var(--accent-light); }
.btn-secondary { background:var(--bg-card); color:var(--text-muted); border:1px solid var(--border); }
.btn-secondary:hover { color:var(--text-primary); border-color:rgba(99,102,241,.35); }

/* -- Page header -- */
.page-hdr { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px; gap:16px; flex-wrap:wrap; }
.page-hdr h1 { font-size:1.55rem; font-weight:800; margin:0 0 3px; }
.page-hdr p  { color:var(--text-muted); font-size:.88rem; margin:0; }

/* -- Tabs -- */
.tabs-wrap { display:flex; gap:4px; background:var(--bg-dark); border-radius:12px; padding:4px; margin-bottom:24px; width:fit-content; }
.tab-btn { padding:8px 22px; background:none; border:none; border-radius:9px; color:var(--text-muted); font-size:.88rem; font-weight:600; cursor:pointer; transition:.15s; white-space:nowrap; }
.tab-btn:hover { color:var(--text-primary); background:rgba(255,255,255,.05); }
.tab-btn.active { background:var(--bg-card); color:var(--text-primary); box-shadow:0 1px 6px rgba(0,0,0,.12); }

/* -- Filter bar -- */
.filter-bar { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:20px; }
.f-input { flex:1; min-width:200px; background:var(--bg-card); border:1px solid var(--border); border-radius:9px; padding:9px 14px; color:var(--text-primary); font-size:.86rem; font-family:inherit; transition:.15s; }
.f-input:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
.f-select { background:var(--bg-card); border:1px solid var(--border); border-radius:9px; padding:9px 14px; color:var(--text-primary); font-size:.86rem; font-family:inherit; cursor:pointer; }
.f-select:focus { outline:none; border-color:var(--accent); }

/* -- Project cards -- */
.proj-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:20px 22px; transition:.15s; }
.proj-card:hover { border-color:rgba(99,102,241,.3); box-shadow:0 4px 20px rgba(0,0,0,.08); }
.proj-card-head { display:flex; justify-content:space-between; align-items:flex-start; gap:14px; }
.proj-card-title { font-size:1rem; font-weight:700; color:var(--text-primary); }
.proj-card-ref { font-size:.74rem; color:var(--text-muted); font-family:monospace; background:rgba(99,102,241,.1); padding:2px 7px; border-radius:5px; }
.proj-card-meta { display:flex; gap:14px; flex-wrap:wrap; font-size:.82rem; color:var(--text-muted); margin-top:7px; }
.proj-card-meta span { display:flex; align-items:center; gap:4px; }
.proj-card-notes { margin-top:8px; font-size:.82rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:600px; }
.proj-card-actions { display:flex; gap:7px; flex-shrink:0; }

/* -- Btn small -- */
.btn-sm { padding:5px 12px; border-radius:7px; font-size:.76rem; font-weight:600; cursor:pointer; border:none; font-family:inherit; transition:.15s; display:inline-flex; align-items:center; gap:5px; }
.btn-sec  { background:rgba(99,102,241,.1); color:var(--accent-light); }
.btn-sec:hover  { background:rgba(99,102,241,.2); }
.btn-del  { background:rgba(239,68,68,.1); color:#f87171; }
.btn-del:hover  { background:rgba(239,68,68,.2); }
.btn-teams { background:rgba(16,185,129,.1); color:#34d399; }
.btn-teams:hover { background:rgba(16,185,129,.2); }

/* -- Status badges -- */
.badge { display:inline-flex; align-items:center; padding:3px 11px; border-radius:12px; font-size:.71rem; font-weight:700; border:1px solid transparent; }

/* -- Stats row -- */
.stats-row { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:12px; margin-bottom:24px; }
.stat-card { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:16px 18px; }
.stat-card .stat-val { font-size:1.6rem; font-weight:800; }
.stat-card .stat-lbl { font-size:.78rem; color:var(--text-muted); margin-top:2px; }

/* -- Table -- */
.tbl-wrap { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.tbl-wrap table { width:100%; border-collapse:collapse; }
.tbl-wrap thead th { background:rgba(99,102,241,.06); padding:11px 16px; text-align:left; font-size:.76rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border); white-space:nowrap; }
.tbl-wrap tbody td { padding:12px 16px; font-size:.86rem; border-bottom:1px solid var(--border); color:var(--text-primary); vertical-align:middle; }
.tbl-wrap tbody tr:last-child td { border-bottom:none; }
.tbl-wrap tbody tr:hover td { background:rgba(99,102,241,.04); }

/* -- Empty state -- */
.empty-state { text-align:center; padding:60px 20px; color:var(--text-muted); }
.empty-state .empty-icon { font-size:2.5rem; display:block; margin-bottom:12px; }
.empty-state p { font-size:.9rem; margin:0; }

/* -- Modal -- */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:300; align-items:center; justify-content:center; padding:20px; }
.modal-overlay.open, .modal-overlay[style*="flex"] { display:flex; }
.modal-box { background:var(--bg-card); border:1px solid var(--border); border-radius:18px; width:100%; box-shadow:0 24px 80px rgba(0,0,0,.4); max-height:90vh; overflow-y:auto; }
.modal-hdr { display:flex; justify-content:space-between; align-items:center; padding:22px 26px 0; }
.modal-hdr h3 { font-size:1.05rem; font-weight:700; margin:0; }
.modal-close { background:none; border:none; cursor:pointer; font-size:1.2rem; color:var(--text-muted); padding:4px 8px; border-radius:6px; }
.modal-close:hover { color:var(--text-primary); background:rgba(255,255,255,.07); }
.modal-body { padding:20px 26px; }
.modal-ftr  { display:flex; gap:10px; justify-content:flex-end; padding:0 26px 22px; }
.form-row { display:grid; gap:14px; }
.form-row-2 { grid-template-columns:1fr 1fr; }
.form-row-full { grid-column:1/-1; }
.form-label { display:block; font-size:.78rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.04em; margin-bottom:5px; }
.form-input { width:100%; background:var(--bg-dark); border:1px solid var(--border); border-radius:9px; padding:9px 13px; color:var(--text-primary); font-size:.86rem; font-family:inherit; box-sizing:border-box; transition:.15s; }
.form-input:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
textarea.form-input { resize:vertical; min-height:70px; }

/* -- Teams panel (drawer) -- */
.drawer { position:fixed; top:0; right:-580px; width:560px; height:100vh; background:var(--bg-card); border-left:1px solid var(--border); z-index:250; transition:right .25s cubic-bezier(.4,0,.2,1); box-shadow:-8px 0 40px rgba(0,0,0,.2); display:flex; flex-direction:column; }
.drawer.open { right:0; }
.drawer-hdr { display:flex; justify-content:space-between; align-items:center; padding:22px 24px 14px; border-bottom:1px solid var(--border); flex-shrink:0; }
.drawer-hdr h2 { font-size:1rem; font-weight:700; margin:0; }
.drawer-hdr p  { font-size:.78rem; color:var(--text-muted); margin:2px 0 0; }
.drawer-tabs { display:flex; gap:3px; padding:12px 24px 0; border-bottom:1px solid var(--border); flex-shrink:0; background:var(--bg-card); }
.drawer-tab { padding:7px 16px; background:none; border:none; border-bottom:2px solid transparent; color:var(--text-muted); font-size:.83rem; font-weight:600; cursor:pointer; font-family:inherit; transition:.15s; white-space:nowrap; margin-bottom:-1px; }
.drawer-tab:hover { color:var(--text-primary); }
.drawer-tab.active { color:var(--accent-light); border-bottom-color:var(--accent); }
.drawer-body { padding:20px 24px; flex:1; overflow-y:auto; }
.drawer-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:249; }
.drawer-overlay.open { display:block; }

/* -- Team card -- */
.team-card { background:var(--bg-dark); border:1px solid var(--border); border-radius:12px; padding:16px 18px; margin-bottom:12px; }
.team-card-hdr { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
.team-card-name { font-weight:700; font-size:.95rem; }
.team-section-lbl { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:6px; display:flex; justify-content:space-between; align-items:center; }
.member-row { display:flex; justify-content:space-between; align-items:center; padding:7px 10px; background:var(--bg-card); border-radius:8px; margin-bottom:4px; font-size:.83rem; }
.member-name { font-weight:600; }
.member-meta { font-size:.75rem; color:var(--text-muted); margin-top:1px; }
.member-code { font-family:monospace; font-size:.74rem; color:var(--text-muted); margin-left:6px; }

/* -- Company rows -- */
.company-row { display:flex; justify-content:space-between; align-items:flex-start; padding:12px 14px; background:var(--bg-dark); border-radius:10px; margin-bottom:8px; border:1px solid var(--border); }
.company-row-info { flex:1; min-width:0; }
.company-row-name { font-weight:700; font-size:.88rem; margin-bottom:2px; }
.company-row-meta { font-size:.76rem; color:var(--text-muted); }
.company-row-actions { display:flex; gap:5px; flex-shrink:0; margin-left:10px; }

/* -- Search picker -- */
.picker-wrap { position:relative; }
.picker-results { position:absolute; top:100%; left:0; right:0; background:var(--bg-card); border:1px solid var(--border); border-radius:9px; z-index:10; max-height:220px; overflow-y:auto; box-shadow:0 8px 30px rgba(0,0,0,.2); margin-top:4px; }
.picker-item { padding:10px 14px; cursor:pointer; font-size:.85rem; border-bottom:1px solid var(--border); transition:.1s; }
.picker-item:last-child { border-bottom:none; }
.picker-item:hover { background:rgba(99,102,241,.08); }
.picker-item .pi-name { font-weight:600; }
.picker-item .pi-meta { font-size:.76rem; color:var(--text-muted); margin-top:1px; }

/* -- Toast -- */
.toast { position:fixed; bottom:24px; right:24px; z-index:400; padding:12px 20px; border-radius:10px; font-size:.87rem; font-weight:600; box-shadow:0 8px 30px rgba(0,0,0,.2); animation:slideUp .25s ease; }
.toast-success { background:#065f46; color:#6ee7b7; border:1px solid rgba(52,211,153,.3); }
.toast-error   { background:#7f1d1d; color:#fca5a5; border:1px solid rgba(239,68,68,.3); }
@keyframes slideUp { from { transform:translateY(20px); opacity:0; } to { transform:translateY(0); opacity:1; } }
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-hdr">
    <div>
        <h1>&#x1F3D7; Obras e Equipas</h1>
        <p>Gestao de obras, equipas e viaturas afectas</p>
    </div>
    <button class="btn btn-primary" onclick="openProjectModal()">
        <span>+</span> Nova Obra
    </button>
</div>

{{-- Stats --}}
<div class="stats-row" id="stats-row" style="display:none">
    <div class="stat-card">
        <div class="stat-val" id="stat-total">&#x2014;</div>
        <div class="stat-lbl">Total de Obras</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#10b981" id="stat-active">&#x2014;</div>
        <div class="stat-lbl">Em Curso</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#6366f1" id="stat-planned">&#x2014;</div>
        <div class="stat-lbl">Planeadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#6b7280" id="stat-done">&#x2014;</div>
        <div class="stat-lbl">Concluidas</div>
    </div>
</div>

{{-- Tabs --}}
<div class="tabs-wrap">
    <button class="tab-btn active" onclick="switchTab('projects')" id="tab-btn-projects">&#x1F3D7; Obras</button>
    <button class="tab-btn" onclick="switchTab('vehicles')" id="tab-btn-vehicles">&#x1F690; Viaturas</button>
</div>

{{-- TAB: OBRAS --}}
<div id="tab-projects">
    <div class="filter-bar">
        <input type="text" id="proj-search" class="f-input" placeholder="Pesquisar por nome, cliente ou referencia..." oninput="debounceProjects()">
        <select id="proj-status" class="f-select" onchange="loadProjects()">
            <option value="">Todos os estados</option>
            <option value="planned">Planeada</option>
            <option value="active">Em Curso</option>
            <option value="completed">Concluida</option>
            <option value="cancelled">Cancelada</option>
        </select>
    </div>
    <div id="proj-list" style="display:flex;flex-direction:column;gap:10px">
        <div class="empty-state"><span class="empty-icon">&#x1F3D7;</span><p>A carregar obras...</p></div>
    </div>
</div>

{{-- TAB: VIATURAS --}}
<div id="tab-vehicles" style="display:none">
    <div class="filter-bar">
        <input type="text" id="veh-search" class="f-input" placeholder="Matricula, marca, modelo..." oninput="debounceVehicles()">
        <select id="veh-status" class="f-select" onchange="loadVehicles()">
            <option value="">Todos os estados</option>
            <option value="active">Activa</option>
            <option value="maintenance">Em manutencao</option>
            <option value="inactive">Inactiva</option>
        </select>
        <button class="btn btn-primary" onclick="openVehicleModal()" style="flex-shrink:0">+ Nova Viatura</button>
    </div>
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>Matricula</th>
                    <th>Marca / Modelo</th>
                    <th>Ano</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Notas</th>
                    <th style="width:110px"></th>
                </tr>
            </thead>
            <tbody id="veh-tbody">
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">A carregar...</td></tr>
            </tbody>
        </table>
    </div>
</div>


{{-- DRAWER -- Equipas + Empresas --}}
<div id="drawer-overlay" class="drawer-overlay" onclick="closeDrawer()"></div>
<div id="teams-drawer" class="drawer">
    <div class="drawer-hdr">
        <div>
            <p style="font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin:0 0 2px">Obra</p>
            <h2 id="drawer-proj-name">&#x2014;</h2>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <button id="btn-sync-docsem" onclick="syncDocsemObra()" class="btn btn-secondary" style="display:none;font-size:.78rem;padding:5px 10px" title="Sincronizar dados e empresas com o DocsElectroMinho">
                &#x1F504; Sincronizar
            </button>
            <button onclick="closeDrawer()" class="modal-close" style="font-size:1.4rem">&#x2715;</button>
        </div>
    </div>
    <div class="drawer-tabs">
        <button class="drawer-tab active" id="dtab-teams" onclick="switchDrawerTab('teams')">&#x1F465; Equipas</button>
        <button class="drawer-tab" id="dtab-companies" onclick="switchDrawerTab('companies')">&#x1F3E2; Empresas Subcontratadas</button>
    </div>

    {{-- Equipas --}}
    <div id="dpanel-teams" class="drawer-body">
        <button class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:18px" onclick="openTeamModal()">
            + Nova Equipa
        </button>
        <div id="teams-list"></div>
    </div>

    {{-- Empresas --}}
    <div id="dpanel-companies" class="drawer-body" style="display:none">
        <button class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:18px" onclick="openCompanyModal()">
            + Associar Empresa
        </button>
        <div id="companies-list"></div>
    </div>
</div>


{{-- MODAIS --}}

{{-- Modal: Obra --}}
<div id="proj-modal" class="modal-overlay">
    <div class="modal-box" style="max-width:560px">
        <div class="modal-hdr"><h3 id="proj-modal-title">Nova Obra</h3><button class="modal-close" onclick="closeProjectModal()">&#x2715;</button></div>
        <div class="modal-body">
            {{-- Picker DocsEM --}}
            <div class="form-row-full" style="margin-bottom:16px">
                <label class="form-label">Ligar a obra do DocsElectroMinho <span style="color:var(--text-muted);font-weight:400">(opcional)</span></label>
                <div class="picker-wrap">
                    <input type="text" id="obra-search" class="form-input" placeholder="Pesquisar obra por nome ou c&#243;digo..."
                           autocomplete="off" oninput="searchObrasDocsem()" onfocus="searchObrasDocsem()">
                    <div id="obra-picker-results" class="picker-results" style="display:none"></div>
                </div>
                <input type="hidden" id="proj-docsem-id">
                <div id="obra-selected" style="display:none;margin-top:6px;padding:8px 12px;background:var(--bg-input);border:1px solid var(--border);border-radius:8px;font-size:.85rem;align-items:center;gap:8px">
                    <span style="flex:1" id="obra-selected-label"></span>
                    <button type="button" onclick="clearObraDocsem()" style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1rem;line-height:1">&#x2715;</button>
                </div>
                <div id="obra-docsem-notice" style="display:none;margin-top:6px;font-size:.8rem;color:var(--text-muted)">
                    &#x2139;&#xFE0F; Os campos abaixo foram preenchidos automaticamente. Pode edit&#225;-los antes de guardar.
                </div>
            </div>
            <div class="form-row form-row-2" style="margin-bottom:14px">
                <div class="form-row-full">
                    <label class="form-label">Nome da Obra *</label>
                    <input type="text" id="proj-name" class="form-input" placeholder="Ex: Moradia Silva - Remodelacao">
                </div>
                <div>
                    <label class="form-label">Centro de Custos</label>
                    <input type="text" id="proj-ref" class="form-input" placeholder="OBR-2026-001">
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <select id="proj-status-modal" class="form-input">
                        <option value="planned">Planeada</option>
                        <option value="active">Em Curso</option>
                        <option value="completed">Concluida</option>
                        <option value="cancelled">Cancelada</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Cliente</label>
                    <input type="text" id="proj-client" class="form-input" placeholder="Nome do cliente">
                </div>
                <div class="form-row-full">
                    <label class="form-label">Localizacao</label>
                    <input type="text" id="proj-location" class="form-input" placeholder="Morada, localidade...">
                </div>
                <div>
                    <label class="form-label">Data de Inicio</label>
                    <input type="date" id="proj-start" class="form-input">
                </div>
                <div>
                    <label class="form-label">Data Fim Previsto</label>
                    <input type="date" id="proj-end" class="form-input">
                </div>
                <div class="form-row-full">
                    <label class="form-label">Notas</label>
                    <textarea id="proj-notes" class="form-input" rows="3" placeholder="Informacoes adicionais sobre a obra..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button class="btn btn-secondary" onclick="closeProjectModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveProject()">Guardar Obra</button>
        </div>
    </div>
</div>

{{-- Modal: Viatura --}}
<div id="veh-modal" class="modal-overlay">
    <div class="modal-box" style="max-width:500px">
        <div class="modal-hdr"><h3 id="veh-modal-title">Nova Viatura</h3><button class="modal-close" onclick="closeVehicleModal()">&#x2715;</button></div>
        <div class="modal-body">
            <div class="form-row form-row-2">
                <div>
                    <label class="form-label">Matricula *</label>
                    <input type="text" id="veh-plate" class="form-input" placeholder="00-AA-00" style="text-transform:uppercase;font-family:monospace;font-weight:700;letter-spacing:.05em">
                </div>
                <div>
                    <label class="form-label">Tipo</label>
                    <select id="veh-type" class="form-input">
                        <option value="van">Carrinha</option>
                        <option value="truck">Camiao</option>
                        <option value="car">Automovel</option>
                        <option value="other">Outro</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Marca</label>
                    <input type="text" id="veh-brand" class="form-input" placeholder="Ex: Renault">
                </div>
                <div>
                    <label class="form-label">Modelo</label>
                    <input type="text" id="veh-model" class="form-input" placeholder="Ex: Master">
                </div>
                <div>
                    <label class="form-label">Ano</label>
                    <input type="number" id="veh-year" class="form-input" min="1950" max="{{ date('Y')+1 }}" placeholder="{{ date('Y') }}">
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <select id="veh-status-modal" class="form-input">
                        <option value="active">Activa</option>
                        <option value="maintenance">Em manutencao</option>
                        <option value="inactive">Inactiva</option>
                    </select>
                </div>
                <div class="form-row-full">
                    <label class="form-label">Notas</label>
                    <textarea id="veh-notes" class="form-input" rows="2" placeholder="Observacoes..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button class="btn btn-secondary" onclick="closeVehicleModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveVehicle()">Guardar</button>
        </div>
    </div>
</div>

{{-- Modal: Equipa --}}
<div id="team-modal" class="modal-overlay">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-hdr"><h3 id="team-modal-title">Nova Equipa</h3><button class="modal-close" onclick="closeTeamModal()">&#x2715;</button></div>
        <div class="modal-body">
            <div class="form-row" style="gap:14px">
                <div>
                    <label class="form-label">Nome da Equipa *</label>
                    <input type="text" id="team-name" class="form-input" placeholder="Ex: Equipa A">
                </div>
                <div>
                    <label class="form-label">Encarregado</label>
                    <select id="team-leader" class="form-input"></select>
                </div>
                <div>
                    <label class="form-label">Notas</label>
                    <textarea id="team-notes" class="form-input" rows="2" placeholder="Observacoes sobre a equipa..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button class="btn btn-secondary" onclick="closeTeamModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveTeam()">Guardar</button>
        </div>
    </div>
</div>

{{-- Modal: Adicionar Funcionario --}}
<div id="emp-modal" class="modal-overlay">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-hdr"><h3>Adicionar Funcionario</h3><button class="modal-close" onclick="closeEmpModal()">&#x2715;</button></div>
        <div class="modal-body">
            <div class="form-row" style="gap:14px">
                <div>
                    <label class="form-label">Funcionario *</label>
                    <select id="emp-select" class="form-input"></select>
                </div>
                <div>
                    <label class="form-label">Funcao na Equipa</label>
                    <input type="text" id="emp-role" class="form-input" placeholder="Ex: Electricista, Encarregado...">
                </div>
                <div class="form-row form-row-2" style="margin:0">
                    <div>
                        <label class="form-label">Data Entrada</label>
                        <input type="date" id="emp-start" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Data Saida</label>
                        <input type="date" id="emp-end" class="form-input">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button class="btn btn-secondary" onclick="closeEmpModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveEmployee()">Adicionar</button>
        </div>
    </div>
</div>

{{-- Modal: Adicionar Viatura a Equipa --}}
<div id="veh-team-modal" class="modal-overlay">
    <div class="modal-box" style="max-width:400px">
        <div class="modal-hdr"><h3>Adicionar Viatura</h3><button class="modal-close" onclick="closeVehTeamModal()">&#x2715;</button></div>
        <div class="modal-body">
            <div class="form-row" style="gap:14px">
                <div>
                    <label class="form-label">Viatura *</label>
                    <select id="veh-team-select" class="form-input"></select>
                </div>
                <div class="form-row form-row-2" style="margin:0">
                    <div>
                        <label class="form-label">Data Inicio</label>
                        <input type="date" id="veh-team-start" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Data Fim</label>
                        <input type="date" id="veh-team-end" class="form-input">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button class="btn btn-secondary" onclick="closeVehTeamModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveVehTeam()">Adicionar</button>
        </div>
    </div>
</div>

{{-- Modal: Associar Empresa Subcontratada --}}
<div id="company-modal" class="modal-overlay">
    <div class="modal-box" style="max-width:480px">
        <div class="modal-hdr">
            <h3 id="company-modal-title">Associar Empresa</h3>
            <button class="modal-close" onclick="closeCompanyModal()">&#x2715;</button>
        </div>
        <div class="modal-body">
            <div class="form-row" style="gap:14px">
                <div class="picker-wrap">
                    <label class="form-label">Empresa (DocsElectroMinho) *</label>
                    <input type="text" id="company-search" class="form-input" placeholder="Pesquisar por nome ou NIF..." autocomplete="off" oninput="searchEmpresas()" onfocus="searchEmpresas()">
                    <div id="company-picker-results" class="picker-results" style="display:none"></div>
                    <input type="hidden" id="company-id">
                    <input type="hidden" id="company-nif">
                    <div id="company-selected" style="display:none;margin-top:8px;padding:8px 12px;background:rgba(99,102,241,.08);border-radius:8px;font-size:.83rem;border:1px solid rgba(99,102,241,.2)"></div>
                </div>
                <div class="form-row form-row-2" style="margin:0">
                    <div>
                        <label class="form-label">Data Entrada</label>
                        <input type="date" id="company-entrada" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Data Saida</label>
                        <input type="date" id="company-saida" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label">Observacoes</label>
                    <textarea id="company-obs" class="form-input" rows="2" placeholder="Notas sobre esta associacao..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button class="btn btn-secondary" onclick="closeCompanyModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveCompany()">Associar</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@vite(['resources/js/pages/projects.js'])
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof loadProjects === 'function') loadProjects();
});
</script>
@endsection
