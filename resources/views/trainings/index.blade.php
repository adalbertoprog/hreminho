@extends('layouts.app')
@section('title','Formações')
@section('page-title','Formações')

@section('styles')
<style>
.emp-picker{border:1px solid var(--border);border-radius:9px;background:var(--bg-dark);transition:border-color .2s;position:relative}
.emp-picker:focus-within{border-color:var(--accent)}
.emp-chips{display:flex;flex-wrap:wrap;gap:5px;padding:7px 10px;min-height:40px;cursor:text;align-items:center}
.emp-chip{display:inline-flex;align-items:center;gap:5px;background:rgba(99,102,241,.18);color:var(--accent-light);border-radius:6px;padding:3px 8px;font-size:.78rem;font-weight:600;white-space:nowrap}
.emp-chip button{background:none;border:none;cursor:pointer;color:inherit;opacity:.7;font-size:.9rem;line-height:1;padding:0;display:flex;align-items:center}
.emp-chip button:hover{opacity:1}
.emp-search{border:none;outline:none;background:transparent;color:var(--text);font-size:.875rem;flex:1;min-width:160px}
.emp-dropdown{display:none;max-height:200px;overflow-y:auto;border-top:1px solid var(--border)}
.emp-dropdown.open{display:block}
.emp-opt{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;cursor:pointer;font-size:.875rem;transition:background .12s}
.emp-opt:hover,.emp-opt.focused{background:var(--bg-hover)}
.emp-opt.selected{color:var(--accent-light)}
.emp-opt-check{opacity:0;color:var(--accent-light);font-weight:700}
.emp-opt.selected .emp-opt-check{opacity:1}
.emp-empty{padding:10px 14px;font-size:.82rem;color:var(--text-muted)}
.emp-count{font-size:.74rem;color:var(--accent-light);font-weight:600;margin-left:6px}
.toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px}
.toolbar h2{font-size:1.25rem;font-weight:700}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--accent);color:#fff;border:none;padding:9px 20px;border-radius:9px;font-size:.875rem;font-weight:600;cursor:pointer;transition:.15s}
.btn-primary:hover{background:#4f46e5}
.tab-bar{display:flex;gap:4px;margin-bottom:20px;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:4px;width:fit-content}
.btn-mode{padding:6px 14px;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--text-muted);font-size:.82rem;cursor:pointer;transition:.15s}
.btn-mode.active{background:var(--accent);color:#fff;border-color:var(--accent)}
.btn-type-pick{display:flex;align-items:center;gap:14px;width:100%;padding:14px 16px;border-radius:12px;border:1px solid var(--border);background:rgba(255,255,255,.04);color:var(--text);cursor:pointer;transition:.15s;text-align:left}
.btn-type-pick:hover{border-color:var(--accent);background:rgba(99,102,241,.12)}
.tab-btn{padding:7px 18px;border-radius:7px;border:none;background:none;color:var(--text-muted);cursor:pointer;font-size:.86rem;font-weight:600;transition:.15s}
.tab-btn.active{background:var(--accent);color:#fff}
.filters{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
.f-input{flex:1;min-width:150px;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit}
.f-input:focus{outline:none;border-color:var(--accent)}
.btn-filter{padding:9px 18px;border-radius:9px;background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);color:var(--accent-light);cursor:pointer;font-size:.86rem;font-weight:600}
.btn-reset{padding:9px 14px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.86rem}
.card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.875rem}
thead th{padding:11px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);border-bottom:1px solid var(--border);background:rgba(255,255,255,.02);white-space:nowrap}
tbody td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
tbody tr:last-child td{border-bottom:none}
tbody tr:hover{background:rgba(255,255,255,.025)}
.badge{display:inline-block;padding:3px 9px;border-radius:6px;font-size:.73rem;font-weight:700}
.badge-enrolled{background:rgba(99,102,241,.15);color:var(--accent-light)}
.badge-completed{background:rgba(34,197,94,.15);color:#22c55e}
.badge-failed{background:rgba(239,68,68,.12);color:#ef4444}
.badge-count{display:inline-flex;align-items:center;justify-content:center;background:rgba(99,102,241,.15);color:var(--accent-light);border-radius:6px;font-size:.74rem;font-weight:700;min-width:28px;height:22px;padding:0 8px}
/* ── Sort header catálogo ── */
thead th.sortable{cursor:pointer;user-select:none;transition:color .15s}
thead th.sortable:hover{color:var(--text-primary)}
thead th.sortable .sort-arrow{margin-left:4px;opacity:.3;font-style:normal;font-size:.68rem}
thead th.sortable.sort-asc .sort-arrow,
thead th.sortable.sort-desc .sort-arrow{opacity:1;color:var(--accent-light)}
/* badges de validade */
.badge-valid   {background:rgba(34,197,94,.15);color:#22c55e}
.badge-expiring{background:rgba(245,158,11,.15);color:#f59e0b}
.badge-expired {background:rgba(239,68,68,.12);color:#ef4444}
.badge-noexp   {background:rgba(255,255,255,.07);color:var(--text-muted)}
.btn-sm{padding:4px 11px;border-radius:7px;font-size:.76rem;font-weight:600;cursor:pointer;border:none;transition:.15s}
.btn-edit{background:rgba(99,102,241,.15);color:var(--accent-light)}.btn-edit:hover{background:rgba(99,102,241,.3)}
.btn-del{background:rgba(239,68,68,.12);color:#ef4444}.btn-del:hover{background:rgba(239,68,68,.25)}
.pag{display:flex;align-items:center;justify-content:space-between;padding:13px 16px;border-top:1px solid var(--border);flex-wrap:wrap;gap:8px}
.pag-info{font-size:.8rem;color:var(--text-muted)}
.pag-btns{display:flex;gap:4px}
.pag-btns button{min-width:30px;height:30px;border-radius:7px;border:1px solid var(--border);background:rgba(255,255,255,.03);color:var(--text-muted);cursor:pointer;font-size:.8rem;font-weight:600;transition:.15s}
.pag-btns button:hover:not(:disabled){border-color:var(--accent);color:var(--accent-light)}
.pag-btns button.active{background:var(--accent);color:#fff;border-color:var(--accent)}
.pag-btns button:disabled{opacity:.35;cursor:not-allowed}
.state-row td{text-align:center;padding:48px;color:var(--text-muted)}
.spinner{display:inline-block;width:18px;height:18px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite;margin-right:8px;vertical-align:middle}
@keyframes spin{to{transform:rotate(360deg)}}
.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:8px}
.toast{padding:12px 18px;border-radius:10px;font-size:.86rem;font-weight:500;box-shadow:0 8px 32px rgba(0,0,0,.4);animation:slideIn .25s ease}
.toast-ok{background:rgba(34,197,94,.15);color:#22c55e;border:1px solid rgba(34,197,94,.25)}
.toast-err{background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.25)}
@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:flex-start;justify-content:center;padding:28px 14px;overflow-y:auto}
.overlay.open{display:flex}
.modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:26px;width:100%;max-width:560px;box-shadow:0 24px 80px rgba(0,0,0,.5);margin:auto}
.modal-title{font-size:1.05rem;font-weight:700;margin-bottom:18px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.full{grid-column:1/-1}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:5px}
.fg input,.fg select,.fg textarea{width:100%;background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:9px;padding:9px 12px;color:var(--text-primary);font-size:.875rem;font-family:inherit}
.fg input:focus,.fg select:focus,.fg textarea:focus{outline:none;border-color:var(--accent)}
.fg select option{background:var(--bg-card)}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}
.btn-cancel{padding:9px 20px;border-radius:9px;background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.875rem;font-weight:600}
.confirm-modal{max-width:370px;text-align:center}
.confirm-modal p{color:var(--text-muted);font-size:.9rem;margin:8px 0 18px}
.btn-danger{padding:9px 20px;border-radius:9px;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:.875rem;font-weight:600}
.btn-danger:hover{background:#dc2626}
.score-bar{height:5px;background:rgba(255,255,255,.08);border-radius:3px;margin-top:4px;overflow:hidden}
.score-fill{height:100%;border-radius:3px}
/* hint de validade no modal */
.validity-hint{margin-top:6px;font-size:.75rem;color:var(--text-muted);min-height:18px}
.validity-hint.expired {color:#ef4444}
.validity-hint.expiring{color:#f59e0b}
.validity-hint.valid   {color:#22c55e}
/* campo score desativado */
.fg input[disabled]{opacity:.45;cursor:not-allowed;background:rgba(255,255,255,.02)}
.score-hint{margin-top:5px;font-size:.74rem;color:var(--text-muted);min-height:16px}
/* Alertas de validade */
.alert-bar{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px}
.alert-chip{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;font-size:.8rem;font-weight:600;cursor:pointer;border:1px solid transparent;transition:.15s}
.alert-chip:hover{opacity:.8}
.chip-expired {background:rgba(239,68,68,.12);color:#ef4444;border-color:rgba(239,68,68,.25)}
.chip-expiring{background:rgba(245,158,11,.12);color:#f59e0b;border-color:rgba(245,158,11,.25)}
/* ── Combobox pesquisável ── */
.cb-wrap{position:relative;flex:1;min-width:150px;max-width:210px}
.cb-input{width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 32px 9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit;box-sizing:border-box;cursor:pointer}
.cb-input:focus{outline:none;border-color:var(--accent)}
.cb-arrow{position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:var(--text-muted);font-size:.7rem}
.cb-dropdown{display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;z-index:100;max-height:220px;overflow-y:auto;box-shadow:0 8px 32px rgba(0,0,0,.4)}
.cb-dropdown.open{display:block}
.cb-option{padding:9px 13px;font-size:.86rem;cursor:pointer;color:var(--text-primary);transition:background .1s}
.cb-option:hover,.cb-option.focused{background:rgba(99,102,241,.15)}
.cb-option.selected{color:var(--accent-light);font-weight:600}
.cb-empty{padding:10px 13px;font-size:.83rem;color:var(--text-muted);text-align:center}
/* ── Stat card formações ── */
.training-stat-row{margin-bottom:16px}
.training-stat-card{
    display:inline-flex;align-items:center;gap:14px;
    background:var(--bg-card);border:1px solid var(--border);border-radius:12px;
    padding:16px 24px;transition:.2s;
}
.training-stat-card:hover{border-color:rgba(99,102,241,.3);transform:translateY(-1px)}
.tsc-icon{width:40px;height:40px;border-radius:10px;background:rgba(99,102,241,.15);display:flex;align-items:center;justify-content:center;font-size:1.15rem;flex-shrink:0}
.tsc-num{font-size:1.7rem;font-weight:800;letter-spacing:-1px;line-height:1;color:var(--accent-light)}
.tsc-label{font-size:.78rem;color:var(--text-muted);font-weight:500;margin-top:3px}
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>🎓 Formações</h2>
    <div style="display:flex;gap:10px">
        <button class="btn-primary" id="btnNewTraining"  onclick="openCreateTraining()" style="display:none">+ Nova Formação</button>
        <button class="btn-primary" id="btnNewEnroll"    onclick="openCreateEnroll()">+ Nova Inscrição</button>
        <button class="btn-primary" id="btnNewMandatory" onclick="openMandatoryModal()" style="display:none">🔒 Nova Regra</button>
    </div>
</div>

<!-- Tabs -->
<div class="tab-bar">
    <button class="tab-btn active" id="tabEnroll"     onclick="switchTab('enrollments')">📋 Inscrições</button>
    <button class="tab-btn"        id="tabCatalog"    onclick="switchTab('catalog')">📚 Catálogo</button>
    <button class="tab-btn"        id="tabMandatory"  onclick="switchTab('mandatory')">🔒 Obrigatórias</button>
</div>

<!-- Alertas de validade (clicáveis — filtram a tabela) -->
<div class="alert-bar" id="alertBar" style="display:none">
    <div class="alert-chip chip-expired"  id="chipExpired"  onclick="filterByValidity('expired')">
        ⚠️ <span id="cntExpired">0</span> Expiradas
    </div>
    <div class="alert-chip chip-expiring" id="chipExpiring" onclick="filterByValidity('expiring')">
        🔔 <span id="cntExpiring">0</span> A Expirar (30 dias)
    </div>
</div>

<!-- Filtros Inscrições -->
<div class="filters" id="filterEnroll">
    <select id="fTraining" class="f-input" style="max-width:220px" onchange="applyFilters()"><option value="">Todas as formações</option></select>
    <div class="cb-wrap" id="cbEmpWrap">
        <input type="text" id="cbEmpInput" class="cb-input" placeholder="Todos os funcionários" autocomplete="off"
               onkeydown="cbKeydown(event)">
        <span class="cb-arrow">▼</span>
        <div class="cb-dropdown" id="cbEmpDropdown"></div>
    </div>
    <input type="hidden" id="fEmpEnroll" value="">
    <select id="fEnrollStatus" class="f-input" style="max-width:160px" onchange="applyFilters()">
        <option value="">Todos os status</option>
        <option value="enrolled">Inscrito</option>
        <option value="completed">Concluído</option>
        <option value="failed">Reprovado</option>
    </select>
    <select id="fValidityStatus" class="f-input" style="max-width:185px" onchange="applyFilters()">
        <option value="">Todas as validades</option>
        <option value="valid">✅ Válida</option>
        <option value="expiring">🔔 A Expirar (30 dias)</option>
        <option value="expired">⚠️ Expirada</option>
        <option value="none">— Sem validade def.</option>
    </select>
    <button class="btn-reset"  onclick="resetFilters()">✕ Limpar</button>
</div>

<!-- Card total formações (catálogo) -->
<div class="training-stat-row" id="catalogStatRow" style="display:none">
    <div class="training-stat-card">
        <div class="tsc-icon">📚</div>
        <div>
            <div class="tsc-num" id="statTotalTrainings">—</div>
            <div class="tsc-label">Formações no Catálogo</div>
        </div>
    </div>
</div>

<!-- Filtros Catálogo -->
<div class="filters" id="filterCatalog" style="display:none">
    <input id="fCatalogSearch" class="f-input" placeholder="🔍 Pesquisar título ou fornecedor...">
    <button class="btn-filter" onclick="applyCatalogFilters()">Filtrar</button>
    <button class="btn-reset"  onclick="resetCatalogFilters()">✕ Limpar</button>
</div>

<!-- Tabela Inscrições -->
<div class="card" id="tableEnroll">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Formação</th>
                    <th>Status</th>
                    <th>Pontuação</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Validade</th>
                    <th>Expira em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="enrollBody">
                <tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr>
            </tbody>
        </table>
    </div>
    <div class="pag" id="enrollPagBar" style="display:none">
        <span class="pag-info" id="enrollPagInfo"></span>
        <div class="pag-btns" id="enrollPagBtns"></div>
    </div>
</div>

<!-- Tabela Catálogo -->
<div class="card" id="tableCatalog" style="display:none">
    <div class="table-wrap">
        <table>
            <thead><tr>
                <th>#</th>
                <th class="sortable" id="sortTitleTh" onclick="setCatalogSort('title')">Título <em class="sort-arrow" id="sortTitleArrow">⇅</em></th>
                <th>Fornecedor</th>
                <th class="sortable" id="sortInscTh" onclick="setCatalogSort('inscricoes')">Inscrições <em class="sort-arrow" id="sortInscArrow">⇅</em></th>
                <th>Ações</th>
            </tr></thead>
            <tbody id="catalogBody"><tr class="state-row"><td colspan="5"><span class="spinner"></span>A carregar...</td></tr></tbody>
        </table>
    </div>
    <div class="pag" id="catalogPagBar" style="display:none">
        <span class="pag-info" id="catalogPagInfo"></span>
        <div class="pag-btns" id="catalogPagBtns"></div>
    </div>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<!-- Modal: Nova Inscrição / Editar Inscrição -->
<div class="overlay" id="enrollOverlay">
<div class="modal">
    <div class="modal-title" id="enrollTitle">Nova Inscrição</div>
    <form id="enrollForm" onsubmit="submitEnroll(event)">
        <div class="form-grid">
            <div class="fg full">
                <label>Funcionários * <span id="enrollEmpCountLabel" class="emp-count"></span></label>
                <div class="emp-picker" id="enrollEmpPicker">
                    <div class="emp-chips" id="enrollEmpChips" onclick="document.getElementById('enrollEmpSearch').focus()">
                        <input type="text" class="emp-search" id="enrollEmpSearch"
                               placeholder="Pesquisar e selecionar funcionários..."
                               autocomplete="off"
                               oninput="enrollFilterEmpOptions()"
                               onkeydown="enrollEmpKeydown(event)"
                               onfocus="enrollOpenEmpDropdown()">
                    </div>
                    <div class="emp-dropdown" id="enrollEmpDropdown">
                        <div class="emp-empty" id="enrollEmpEmpty" style="display:none">Sem resultados</div>
                    </div>
                </div>
            </div>
            <div class="fg full"><label>Formação *</label><select name="training_id" id="trainingSelEnroll" required onchange="loadSessionsForEnroll()"><option value="">— Selecionar —</option></select></div>
            <div class="fg full" id="sessionSelWrap" style="display:none">
                <label>Sessão planeada <span style="color:var(--text-muted);font-weight:400">(opcional)</span></label>
                <select name="training_session_id" id="sessionSelEnroll">
                    <option value="">— Sem sessão associada —</option>
                </select>
            </div>
            <div class="fg"><label>Status</label>
                <select name="status">
                    <option value="enrolled">Inscrito</option>
                    <option value="completed">Concluído</option>
                    <option value="failed">Reprovado</option>
                </select>
            </div>
            <div class="fg"><label>Data de Início</label><input name="start_date" type="date"></div>
            <div class="fg"><label>Data de Fim</label><input name="end_date" type="date" id="endDateInput" oninput="updateExpiryHint(); updateScoreState()"></div>
            <div class="fg full">
                <label>Pontuação (0–100)</label>
                <input name="score" id="scoreInput" type="number" min="0" max="100" step="0.1" placeholder="Ex: 85.5">
                <div class="score-hint" id="scoreHint"></div>
            </div>
            <div class="fg">
                <label>Validade (meses)</label>
                <input name="validity_months" type="number" min="1" max="120" step="1" id="validityInput"
                       placeholder="Ex: 12" oninput="updateExpiryHint()">
            </div>
            <div class="fg" style="display:flex;align-items:flex-end">
                <div style="width:100%">
                    <label>Data de expiração</label>
                    <div id="expiryHint" class="validity-hint" style="padding:9px 0;font-size:.85rem">— preencha fim e validade</div>
                </div>
            </div>
            <div class="fg full"><label>Notas</label><textarea name="notes" rows="2" placeholder="Opcional..."></textarea></div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('enrollOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="enrollSubmitBtn">Inscrever</button>
        </div>
    </form>
</div>
</div>

<!-- Modal: Nova Formação / Editar Formação -->
<div class="overlay" id="trainingOverlay">
<div class="modal">
    <div class="modal-title" id="trainingTitle">Nova Formação</div>
    <form id="trainingForm" onsubmit="submitTraining(event)">
        <div class="fg" style="margin-bottom:13px"><label>Título *</label><input name="title" required placeholder="Ex: Excel Avançado"></div>
        <div class="fg" style="margin-bottom:13px"><label>Fornecedor *</label><input name="provider" required placeholder="Ex: Udemy, Coursera..."></div>
        <div class="fg" style="margin-bottom:13px"><label>Descrição</label><textarea name="description" rows="3" placeholder="Descreva o conteúdo..."></textarea></div>
        <div style="display:flex;gap:24px;margin-bottom:16px;padding:12px 14px;background:rgba(255,255,255,.04);border-radius:10px;border:1px solid var(--border)">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.9rem">
                <input type="checkbox" name="has_video" value="1" style="width:16px;height:16px;accent-color:var(--accent)">
                🎬 Inclui vídeos
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.9rem">
                <input type="checkbox" name="has_quiz" value="1" style="width:16px;height:16px;accent-color:var(--accent)">
                📝 Inclui questionário
            </label>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('trainingOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="trainingSubmitBtn">Criar</button>
        </div>
    </form>
</div>
</div>

<!-- Modal Excluir -->
<div class="overlay" id="delOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem">🗑️</div>
    <div class="modal-title" style="margin-top:10px">Confirmar Exclusão</div>
    <p id="delMsg">Tem certeza?</p>
    <div class="modal-foot" style="justify-content:center">
        <button class="btn-cancel" onclick="closeOverlay('delOverlay')">Cancelar</button>
        <button class="btn-danger" onclick="confirmDelete()">Excluir</button>
    </div>
</div>
</div>

<!-- ══ Modal: Gerir Conteúdo (vídeos + questionário) ══ -->
<style>
.btn-icon-del{background:none;border:none;cursor:pointer;color:var(--danger);font-size:1rem;padding:4px 6px;border-radius:6px;transition:.15s}
.btn-icon-del:hover{background:rgba(239,68,68,.12)}
.q-block{background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:12px}
.q-block-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.q-opt-row{display:flex;align-items:center;gap:8px;margin-bottom:6px}
.q-opt-row input[type=text]{flex:1;background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:7px;padding:7px 10px;color:var(--text-primary);font-size:.83rem;font-family:inherit}
.q-opt-row input[type=text]:focus{outline:none;border-color:var(--accent)}
.q-opt-row input[type=radio]{accent-color:var(--accent);width:15px;height:15px;flex-shrink:0}
.q-opt-row .opt-correct-label{font-size:.72rem;color:var(--text-muted);white-space:nowrap}
</style>
<div class="overlay" id="contentOverlay">
<div class="modal" style="max-width:680px;max-height:90vh;overflow-y:auto">
    <div class="modal-title">🎬 Conteúdo — <span id="contentTrainingTitle"></span></div>

    <div class="tab-bar" style="margin-bottom:18px">
        <button class="tab-btn active" id="ctTabVideo" onclick="ctSwitch('video')">▶ Vídeos</button>
        <button class="tab-btn"        id="ctTabQuiz"  onclick="ctSwitch('quiz')">📝 Questionário</button>
    </div>

    <!-- Vídeos -->
    <div id="ctVideo">
        <div id="videoList" style="margin-bottom:14px"></div>
        <div style="border-top:1px solid var(--border);padding-top:14px">
            <p style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px">Adicionar vídeo</p>
            <!-- Toggle URL / Upload -->
            <div style="display:flex;gap:8px;margin-bottom:12px">
                <button id="vModeUrl" class="btn-mode active" onclick="setVideoMode('url')">🔗 URL externa</button>
                <button id="vModeFile" class="btn-mode" onclick="setVideoMode('file')">📁 Upload ficheiro</button>
            </div>
            <div class="form-grid" style="grid-template-columns:1fr">
                <div class="fg"><label>Título *</label><input id="vTitle" placeholder="Ex: Introdução à Segurança"></div>
                <!-- URL mode -->
                <div class="fg" id="vUrlField">
                    <label>URL * <small style="font-weight:400">(YouTube, Vimeo ou MP4 directo)</small></label>
                    <input id="vUrl" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <!-- File mode -->
                <div class="fg" id="vFileField" style="display:none">
                    <label>Ficheiro de vídeo * <small style="font-weight:400">(MP4, WebM — máx. 500 MB)</small></label>
                    <input id="vFile" type="file" accept="video/mp4,video/webm,video/ogg,video/quicktime"
                           style="padding:8px;background:var(--bg-input,rgba(255,255,255,.06));border:1px solid var(--border);border-radius:8px;width:100%;color:var(--text)">
                    <div id="vUploadProgress" style="display:none;margin-top:6px">
                        <div style="height:4px;background:var(--border);border-radius:4px;overflow:hidden">
                            <div id="vProgressBar" style="height:100%;background:var(--accent);width:0%;transition:width .2s"></div>
                        </div>
                        <span id="vProgressText" style="font-size:.75rem;color:var(--text-muted)">A enviar...</span>
                    </div>
                </div>
                <div class="fg"><label>Descrição</label><textarea id="vDesc" rows="2" style="resize:vertical" placeholder="Descrição opcional"></textarea></div>
            </div>
            <div class="modal-foot" style="border:none;padding:10px 0 0">
                <button id="vAddBtn" class="btn-primary" onclick="addVideo()">＋ Adicionar Vídeo</button>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-cancel" onclick="closeOverlay('contentOverlay')">Fechar</button>
        </div>
    </div>

    <!-- Questionário -->
    <div id="ctQuiz" style="display:none">
        <div class="form-grid" style="margin-bottom:16px">
            <div class="fg full"><label>Título do questionário *</label><input id="qTitle" placeholder="Ex: Avaliação de Segurança"></div>
            <div class="fg full"><label>Descrição / instruções</label><textarea id="qDesc" rows="2" style="resize:vertical" placeholder="Texto introdutório para o funcionário"></textarea></div>
            <div class="fg"><label>Nota mínima de aprovação (%)</label><input id="qPass" type="number" min="0" max="100" value="70"></div>
        </div>
        <div id="questionsList"></div>
        <button class="btn-primary" style="margin-bottom:16px;width:100%" onclick="addQuestion()">＋ Adicionar Pergunta</button>
        <div class="modal-foot">
            <button class="btn-cancel" onclick="closeOverlay('contentOverlay')">Fechar</button>
            <button class="btn-primary" onclick="saveQuiz()">💾 Guardar Questionário</button>
        </div>
    </div>
</div>
</div>

<!-- Modal: Tipo de pergunta -->
<div class="overlay" id="qTypeOverlay" style="z-index:1100">
<div class="modal" style="max-width:380px;text-align:center">
    <div class="modal-title" style="margin-bottom:6px">&#xFF0B; Adicionar Pergunta</div>
    <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:20px">Escolhe o tipo de pergunta</p>
    <div style="display:flex;flex-direction:column;gap:10px">
        <button class="btn-type-pick" onclick="pickQuestionType('mc')">
            <span style="font-size:1.4rem">&#128280;</span>
            <div>
                <div style="font-weight:700;text-align:left">Multipla escolha</div>
                <div style="font-size:.78rem;color:var(--text-muted);text-align:left">Varias opcoes, uma correcta</div>
            </div>
        </button>
        <button class="btn-type-pick" onclick="pickQuestionType('tf')">
            <span style="font-size:1.4rem">&#9989;</span>
            <div>
                <div style="font-weight:700;text-align:left">Verdadeiro / Falso</div>
                <div style="font-size:.78rem;color:var(--text-muted);text-align:left">Duas opcoes: Verdadeiro ou Falso</div>
            </div>
        </button>
    </div>
    <div style="margin-top:18px">
        <button class="btn-cancel" onclick="closeOverlay('qTypeOverlay')">Cancelar</button>
    </div>
</div>
</div>

<!-- ══ Modal: Resultados do questionário ══ -->
<div class="overlay" id="resultsOverlay">
<div class="modal" style="max-width:760px;max-height:90vh;overflow-y:auto">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
        <div class="modal-title" style="margin-bottom:0">📊 Resultados — <span id="resModalTitle"></span></div>
        <button onclick="closeOverlay('resultsOverlay')" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.3rem;line-height:1">✕</button>
    </div>
    <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:20px">Melhor tentativa por funcionário</p>

    <div id="resSummary" style="margin-bottom:24px"></div>

    <div style="margin-bottom:14px;display:flex;gap:10px;flex-wrap:wrap;align-items:center">
        <input id="resSearch" type="text" placeholder="🔍  Pesquisar por nome ou código…"
               oninput="filterResultsTable()"
               style="flex:1;min-width:200px;background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:8px;padding:8px 12px;color:var(--text-primary);font-size:.85rem;font-family:inherit;outline:none;transition:border-color .15s"
               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
        <select id="resStatusFilter" onchange="filterResultsTable()"
                style="background:var(--bg-card);border:1px solid var(--border);border-radius:8px;padding:8px 12px;color:var(--text-primary);font-size:.85rem;font-family:inherit;outline:none;cursor:pointer">
            <option value="">Todos</option>
            <option value="passed">✓ Aprovados</option>
            <option value="failed">✗ Reprovados</option>
        </select>
        <span id="resCount" style="font-size:.8rem;color:var(--text-muted);white-space:nowrap"></span>
    </div>

    <table style="width:100%;border-collapse:collapse;font-size:.875rem">
        <thead>
            <tr style="border-bottom:1px solid var(--border)">
                <th style="padding:9px 12px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Funcionário</th>
                <th style="padding:9px 12px;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Nota</th>
                <th style="padding:9px 12px;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Estado</th>
                <th style="padding:9px 12px;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Tentativas</th>
                <th style="padding:9px 12px;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Última tentativa</th>
            </tr>
        </thead>
        <tbody id="resTableBody">
            <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">A carregar…</td></tr>
        </tbody>
    </table>

    <div style="margin-top:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <button onclick="exportResultsPDF()" style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:9px;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#ef4444;font-size:.875rem;font-weight:600;cursor:pointer;transition:.15s" onmouseover="this.style.background='rgba(239,68,68,.22)'" onmouseout="this.style.background='rgba(239,68,68,.12)'">
            📄 Exportar PDF
        </button>
        <button class="btn-cancel" onclick="closeOverlay('resultsOverlay')">Fechar</button>
    </div>
</div>
</div>

{{-- ══ Tab: Formações Obrigatórias ══ --}}
<div id="tableMandatory" style="display:none">

    {{-- Sumário de cumprimento global --}}
    <div id="mandatorySummary" style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:20px"></div>

    {{-- Tabela de regras --}}
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Formação</th>
                        <th>Âmbito</th>
                        <th>Prazo (dias)</th>
                        <th style="text-align:center">Funcionários</th>
                        <th style="text-align:center">Cumprimento</th>
                        <th>Notas</th>
                        <th style="text-align:center">Ações</th>
                    </tr>
                </thead>
                <tbody id="mandatoryBody">
                    <tr class="state-row"><td colspan="7"><span class="spinner"></span>A carregar...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Nova/Editar Regra Obrigatória --}}
<div class="overlay" id="mandatoryOverlay">
<div class="modal" style="max-width:520px">
    <div class="modal-title" id="mandatoryModalTitle">🔒 Nova Regra Obrigatória</div>

    <form id="mandatoryForm" onsubmit="submitMandatory(event)">
        <input type="hidden" id="mandatoryId">

        <div class="form-grid">
            <div class="fg full">
                <label>Formação *</label>
                <select id="mTrainingId" required style="width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit">
                    <option value="">— Selecionar formação —</option>
                </select>
            </div>

            <div class="fg full">
                <label>Âmbito *</label>
                <select id="mTargetType" required onchange="onTargetTypeChange()" style="width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit">
                    <option value="all">Todos os funcionários</option>
                    <option value="department">Por Departamento</option>
                    <option value="position">Por Cargo</option>
                </select>
            </div>

            <div class="fg full" id="mTargetIdWrap" style="display:none">
                <label id="mTargetIdLabel">Departamento *</label>
                <select id="mTargetId" style="width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text-primary);font-size:.86rem;font-family:inherit">
                    <option value="">— Selecionar —</option>
                </select>
            </div>

            <div class="fg">
                <label>Prazo após contratação (dias)</label>
                <input type="number" id="mDeadlineDays" min="1" max="3650" placeholder="Ex: 90 (opcional)" class="f-input" style="width:100%">
            </div>

            <div class="fg full">
                <label>Notas</label>
                <input type="text" id="mNotes" maxlength="500" placeholder="Observações (opcional)" class="f-input" style="width:100%">
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px">
            <button type="button" class="btn-cancel" onclick="closeOverlay('mandatoryOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary">Guardar</button>
        </div>
    </form>
</div>
</div>

{{-- Modal: Lacunas (funcionários em falta) --}}
<div class="overlay" id="gapsOverlay">
<div class="modal" style="max-width:680px;max-height:90vh;overflow-y:auto">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="modal-title" style="margin-bottom:0">👤 Funcionários em Falta — <span id="gapsModalTitle"></span></div>
        <button onclick="closeOverlay('gapsOverlay')" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.3rem">✕</button>
    </div>

    <div id="gapsSummary" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px"></div>

    <table style="width:100%;border-collapse:collapse;font-size:.875rem">
        <thead>
            <tr style="border-bottom:1px solid var(--border)">
                <th style="padding:8px 12px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Funcionário</th>
                <th style="padding:8px 12px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Departamento</th>
                <th style="padding:8px 12px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Cargo</th>
                <th style="padding:8px 12px;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted)">Contratado em</th>
            </tr>
        </thead>
        <tbody id="gapsBody">
            <tr><td colspan="4" style="text-align:center;padding:28px;color:var(--text-muted)">A carregar…</td></tr>
        </tbody>
    </table>

    <div style="margin-top:16px;display:flex;justify-content:flex-end">
        <button class="btn-cancel" onclick="closeOverlay('gapsOverlay')">Fechar</button>
    </div>
</div>
</div>

@endsection


@section('scripts')
<script>
window.TRAIN_CONFIG = {
    logoUrl: '{{ asset("images/logo.jpg") }}',
};
</script>
@vite(['resources/js/pages/trainings.js'])
@endsection
