// ── helpers ──────────────────────────────────────────────────────────────
const csrf = () => document.querySelector('meta[name="csrf-token"]').content;

async function apiFetch(method, path, body = null) {
    const opts = {
        method,
        credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json', 'Accept': 'application/json' },
    };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch('/api/v1' + path, opts);
    if (res.status === 204) return null;
    const json = await res.json();
    if (!res.ok) throw new Error(json.message || Object.values(json.errors || {})[0]?.[0] || 'Erro');
    return json;
}

function toast(msg, type = 'success') {
    const el = document.createElement('div');
    el.className = 'toast toast-' + type;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}

function fmtDate(d) {
    if (!d) return '—';
    const [y, m, day] = d.split('-');
    return `${day}/${m}/${y}`;
}

const STATUS_PROJ = {
    planned:   { label: 'Planeada',        color: '#6366f1' },
    active:    { label: 'Em Curso',         color: '#10b981' },
    completed: { label: 'Concluída',        color: '#6b7280' },
    cancelled: { label: 'Cancelada',        color: '#ef4444' },
};
const STATUS_VEH = {
    active:      { label: 'Activa',         color: '#10b981' },
    maintenance: { label: 'Em manutenção',  color: '#f59e0b' },
    inactive:    { label: 'Inactiva',       color: '#6b7280' },
};
const TYPE_VEH = { van: '🚐 Carrinha', truck: '🚚 Camião', car: '🚗 Automóvel', other: '🔧 Outro' };

function badge(text, color) {
    return `<span class="badge" style="background:${color}22;color:${color};border-color:${color}44">${text}</span>`;
}

// debounce helpers
let _projTimer, _vehTimer;
function debounceProjects() { clearTimeout(_projTimer); _projTimer = setTimeout(loadProjects, 300); }
function debounceVehicles() { clearTimeout(_vehTimer);  _vehTimer  = setTimeout(loadVehicles,  300); }

// ── tab switch ────────────────────────────────────────────────────────────
function switchTab(tab) {
    ['projects', 'vehicles'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === tab ? 'block' : 'none';
        document.getElementById('tab-btn-' + t).classList.toggle('active', t === tab);
    });
    if (tab === 'vehicles') loadVehicles();
}

// ══════════════════════════════════════════════════════════════════════════
// OBRAS
// ══════════════════════════════════════════════════════════════════════════
let projEditId       = null;
let allEmployees     = [];
let allVehicles      = [];
let _obraResults     = [];
let _obraSearchTimer = null;

async function loadProjects() {
    const search = document.getElementById('proj-search').value;
    const status = document.getElementById('proj-status').value;
    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (status) params.set('status', status);

    try {
        const res = await apiFetch('GET', '/projects?' + params);
        updateStats(res.data);
        renderProjects(res.data);
    } catch (e) {
        toast(e.message, 'error');
    }
}

function updateStats(projects) {
    const row = document.getElementById('stats-row');
    if (!row) return;
    row.style.display = 'grid';
    document.getElementById('stat-total').textContent   = projects.length;
    document.getElementById('stat-active').textContent  = projects.filter(p => p.status === 'active').length;
    document.getElementById('stat-planned').textContent = projects.filter(p => p.status === 'planned').length;
    document.getElementById('stat-done').textContent    = projects.filter(p => p.status === 'completed').length;
}

function renderProjects(projects) {
    const container = document.getElementById('proj-list');
    if (!projects.length) {
        container.innerHTML = `<div class="empty-state"><span class="empty-icon">🏗️</span><p>Nenhuma obra encontrada.</p></div>`;
        return;
    }

    container.innerHTML = projects.map(p => {
        const s = STATUS_PROJ[p.status] || STATUS_PROJ.planned;
        const teamsInfo = p.teams_count === 1 ? '1 equipa' : `${p.teams_count} equipas`;
        const companiesInfo = p.companies_count > 0
            ? `<span>&#x1F3E2; ${p.companies_count} empresa${p.companies_count === 1 ? '' : 's'} subcontratada${p.companies_count === 1 ? '' : 's'}</span>`
            : '';
        const nameEsc = p.name.replace(/\\/g,'\\\\').replace(/'/g,"\\'");
        const dataAttr = encodeURIComponent(JSON.stringify(p));
        const pStart = p.start_date || '';
        const pEnd   = p.end_date   || '';
        const docsemBadge = p.docsem_obra_id
            ? `<span class="badge" style="background:#6366f122;color:#818cf8;border-color:#6366f144;font-size:.7rem">&#x1F517; DocsEM</span>`
            : '';
        return `
        <div class="proj-card">
            <div class="proj-card-head">
                <div style="flex:1;min-width:0">
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px">
                        <span class="proj-card-title">${p.name}</span>
                        ${p.reference ? `<span class="proj-card-ref">${p.reference}</span>` : ''}
                        ${badge(s.label, s.color)}
                        ${docsemBadge}
                    </div>
                    <div class="proj-card-meta">
                        ${p.client    ? `<span>👤 ${p.client}</span>` : ''}
                        ${p.location  ? `<span>📍 ${p.location}</span>` : ''}
                        ${p.start_date ? `<span>📅 ${fmtDate(p.start_date)}${p.end_date ? ' → ' + fmtDate(p.end_date) : ''}</span>` : ''}
                        <span>&#x1F465; ${teamsInfo}</span>
                        ${companiesInfo}
                    </div>
                    ${p.notes ? `<div class="proj-card-notes">${p.notes}</div>` : ''}
                </div>
                <div class="proj-card-actions">
                    <button class="btn-sm btn-teams" onclick="openDrawer(${p.id}, '${nameEsc}', ${p.docsem_obra_id || 'null'})" title="Ver equipas da obra">&#x1F465; Equipas</button>
                    <button class="btn-sm" onclick="openStaffingCheck('${pStart}','${pEnd}',encodeURIComponent('${nameEsc}'))" title="Simular disponibilidade de técnicos para esta obra" style="background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.25)">🔍 Disponibilidade</button>
                    <button class="btn-sm btn-sec" onclick="openProjectModal(decodeURIComponent('${dataAttr}'))" title="Editar obra">✏️</button>
                    <button class="btn-sm btn-del" onclick="deleteProject(${p.id})" title="Eliminar obra">🗑</button>
                </div>
            </div>
        </div>`;
    }).join('');
}

function openProjectModal(raw = null) {
    const p = raw ? (typeof raw === 'string' ? JSON.parse(raw) : raw) : null;
    projEditId = p?.id || null;
    document.getElementById('proj-modal-title').textContent = p ? 'Editar Obra' : 'Nova Obra';
    document.getElementById('proj-name').value           = p?.name        || '';
    document.getElementById('proj-ref').value            = p?.reference   || '';
    document.getElementById('proj-client').value         = p?.client      || '';
    document.getElementById('proj-location').value       = p?.location    || '';
    document.getElementById('proj-start').value          = p?.start_date  || '';
    document.getElementById('proj-end').value            = p?.end_date    || '';
    document.getElementById('proj-status-modal').value   = p?.status      || 'planned';
    document.getElementById('proj-notes').value          = p?.notes       || '';
    // Picker DocsEM
    document.getElementById('proj-docsem-id').value      = p?.docsem_obra_id || '';
    document.getElementById('obra-search').value         = '';
    document.getElementById('obra-picker-results').style.display = 'none';
    const selEl = document.getElementById('obra-selected');
    const noticeEl = document.getElementById('obra-docsem-notice');
    if (p?.docsem_obra_id) {
        document.getElementById('obra-selected-label').textContent = p.name + (p.reference ? ' (' + p.reference + ')' : '');
        selEl.style.display    = 'flex';
        noticeEl.style.display = 'none';
    } else {
        selEl.style.display    = 'none';
        noticeEl.style.display = 'none';
    }
    document.getElementById('proj-modal').style.display  = 'flex';
}

function closeProjectModal() {
    document.getElementById('proj-modal').style.display = 'none';
    _obraResults = [];
    document.getElementById('obra-picker-results').style.display = 'none';
}

// ── Picker de obras DocsEM ────────────────────────────────────────────────

function searchObrasDocsem() {
    clearTimeout(_obraSearchTimer);
    const q = document.getElementById('obra-search').value.trim();
    if (q.length === 0) {
        _obraSearchTimer = setTimeout(() => _doSearchObras(''), 200);
        return;
    }
    if (q.length < 2) {
        document.getElementById('obra-picker-results').style.display = 'none';
        return;
    }
    _obraSearchTimer = setTimeout(() => _doSearchObras(q), 350);
}

async function _doSearchObras(q) {
    const resultsEl = document.getElementById('obra-picker-results');
    resultsEl.innerHTML = '<div class="picker-item" style="color:var(--text-muted)">A pesquisar...</div>';
    resultsEl.style.display = 'block';
    try {
        const url = '/docsem/obras?per_page=50' + (q ? '&search=' + encodeURIComponent(q) : '');
        const res = await apiFetch('GET', url);
        _obraResults = res.data || [];
        if (!_obraResults.length) {
            resultsEl.innerHTML = '<div class="picker-item" style="color:var(--text-muted)">Nenhuma obra encontrada.</div>';
            return;
        }
        const STATUS_DOCSEM = { em_curso: 'Em Curso', planeamento: 'Planeamento', concluida: 'Concluída', suspensa: 'Suspensa' };
        resultsEl.innerHTML = _obraResults.map((o, i) => `
            <div class="picker-item" onclick="selectObraDocsem(${i})">
                <div class="pi-name">${o.nome}${o.codigo ? ' <span style="color:var(--text-muted);font-size:.8em">(${o.codigo})</span>' : ''}</div>
                <div class="pi-meta">${o.localidade || ''}${o.area_servico ? ' &middot; ' + o.area_servico : ''} &middot; ${STATUS_DOCSEM[o.estado] || o.estado}</div>
            </div>`).join('');
    } catch (err) {
        resultsEl.innerHTML = `<div class="picker-item" style="color:#f87171">${err.message}</div>`;
    }
}

function selectObraDocsem(index) {
    const o = _obraResults[index];
    if (!o) return;

    const STATUS_MAP = { em_curso: 'active', planeamento: 'planned', concluida: 'completed', suspensa: 'cancelled', cancelada: 'cancelled' };

    // Preencher campos automaticamente
    document.getElementById('proj-docsem-id').value        = o.id;
    document.getElementById('proj-name').value             = o.nome;
    document.getElementById('proj-location').value         = [o.localidade, o.distrito].filter(Boolean).join(', ');
    document.getElementById('proj-start').value            = o.data_inicio       || '';
    document.getElementById('proj-end').value              = o.data_fim_prevista || '';
    document.getElementById('proj-status-modal').value     = STATUS_MAP[o.estado] || 'planned';
    if (o.codigo) document.getElementById('proj-ref').value = o.codigo;

    // Mostrar chip seleccionado
    document.getElementById('obra-selected-label').textContent = o.nome + (o.codigo ? ' (' + o.codigo + ')' : '');
    document.getElementById('obra-selected').style.display     = 'flex';
    document.getElementById('obra-picker-results').style.display = 'none';
    document.getElementById('obra-search').value               = '';
    document.getElementById('obra-docsem-notice').style.display = 'block';
}

function clearObraDocsem() {
    document.getElementById('proj-docsem-id').value            = '';
    document.getElementById('obra-selected').style.display     = 'none';
    document.getElementById('obra-docsem-notice').style.display = 'none';
    document.getElementById('obra-search').value               = '';
    document.getElementById('obra-picker-results').style.display = 'none';
    _obraResults = [];
}

async function saveProject() {
    const docsemId = parseInt(document.getElementById('proj-docsem-id').value) || null;
    const body = {
        name:            document.getElementById('proj-name').value.trim(),
        reference:       document.getElementById('proj-ref').value.trim()      || null,
        client:          document.getElementById('proj-client').value.trim()   || null,
        location:        document.getElementById('proj-location').value.trim() || null,
        start_date:      document.getElementById('proj-start').value           || null,
        end_date:        document.getElementById('proj-end').value             || null,
        status:          document.getElementById('proj-status-modal').value,
        notes:           document.getElementById('proj-notes').value.trim()    || null,
        docsem_obra_id:  docsemId,
    };
    if (!body.name) { toast('O nome da obra é obrigatório.', 'error'); return; }

    try {
        if (projEditId) {
            await apiFetch('PUT', `/projects/${projEditId}`, body);
            toast('Obra actualizada.');
        } else {
            await apiFetch('POST', '/projects', body);
            toast('Obra criada.');
        }
        closeProjectModal();
        loadProjects();
    } catch (e) {
        toast(e.message, 'error');
    }
}

async function deleteProject(id) {
    if (!confirm('Eliminar esta obra? As equipas associadas também serão eliminadas.')) return;
    try {
        await apiFetch('DELETE', `/projects/${id}`);
        toast('Obra eliminada.');
        loadProjects();
    } catch (e) {
        toast(e.message, 'error');
    }
}

// ══════════════════════════════════════════════════════════════════════════
// VIATURAS
// ══════════════════════════════════════════════════════════════════════════
let vehEditId = null;

async function loadVehicles() {
    const search = document.getElementById('veh-search').value;
    const status = document.getElementById('veh-status').value;
    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (status) params.set('status', status);

    try {
        const res = await apiFetch('GET', '/vehicles?' + params);
        allVehicles = res.data;
        renderVehicles(res.data);
    } catch (e) {
        toast(e.message, 'error');
    }
}

function renderVehicles(vehicles) {
    const tbody = document.getElementById('veh-tbody');
    if (!vehicles.length) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="empty-state"><span class="empty-icon">🚐</span><p>Nenhuma viatura encontrada.</p></div></td></tr>';
        return;
    }
    tbody.innerHTML = vehicles.map(v => {
        const s = STATUS_VEH[v.status] || STATUS_VEH.active;
        const dataAttr = encodeURIComponent(JSON.stringify(v));
        return `<tr>
            <td><span style="font-family:monospace;font-weight:700;letter-spacing:.04em">${v.plate}</span></td>
            <td>${[v.brand, v.model].filter(Boolean).join(' ') || '—'}</td>
            <td>${v.year || '—'}</td>
            <td style="color:var(--text-muted)">${TYPE_VEH[v.type] || v.type}</td>
            <td>${badge(s.label, s.color)}</td>
            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text-muted);font-size:.82rem">${v.notes || '—'}</td>
            <td>
                <button class="btn-sm btn-sec" onclick="openVehicleModal(decodeURIComponent('${dataAttr}'))" title="Editar viatura">✏️</button>
                <button class="btn-sm btn-del" onclick="deleteVehicle(${v.id})" title="Eliminar viatura">🗑</button>
            </td>
        </tr>`;
    }).join('');
}

function openVehicleModal(raw = null) {
    const v = raw ? (typeof raw === 'string' ? JSON.parse(raw) : raw) : null;
    vehEditId = v?.id || null;
    document.getElementById('veh-modal-title').textContent   = v ? 'Editar Viatura' : 'Nova Viatura';
    document.getElementById('veh-plate').value               = v?.plate  || '';
    document.getElementById('veh-brand').value               = v?.brand  || '';
    document.getElementById('veh-model').value               = v?.model  || '';
    document.getElementById('veh-year').value                = v?.year   || '';
    document.getElementById('veh-type').value                = v?.type   || 'van';
    document.getElementById('veh-status-modal').value        = v?.status || 'active';
    document.getElementById('veh-notes').value               = v?.notes  || '';
    document.getElementById('veh-modal').style.display       = 'flex';
}

function closeVehicleModal() {
    document.getElementById('veh-modal').style.display = 'none';
}

async function saveVehicle() {
    const body = {
        plate:  document.getElementById('veh-plate').value.trim().toUpperCase(),
        brand:  document.getElementById('veh-brand').value.trim() || null,
        model:  document.getElementById('veh-model').value.trim() || null,
        year:   parseInt(document.getElementById('veh-year').value) || null,
        type:   document.getElementById('veh-type').value,
        status: document.getElementById('veh-status-modal').value,
        notes:  document.getElementById('veh-notes').value.trim() || null,
    };
    if (!body.plate) { toast('A matrícula é obrigatória.', 'error'); return; }

    try {
        if (vehEditId) {
            await apiFetch('PUT', `/vehicles/${vehEditId}`, body);
            toast('Viatura actualizada.');
        } else {
            await apiFetch('POST', '/vehicles', body);
            toast('Viatura criada.');
        }
        closeVehicleModal();
        loadVehicles();
    } catch (e) {
        toast(e.message, 'error');
    }
}

async function deleteVehicle(id) {
    if (!confirm('Eliminar esta viatura?')) return;
    try {
        await apiFetch('DELETE', `/vehicles/${id}`);
        toast('Viatura eliminada.');
        loadVehicles();
    } catch (e) {
        toast(e.message, 'error');
    }
}

// ══════════════════════════════════════════════════════════════════════════
// DRAWER — Equipas
// ══════════════════════════════════════════════════════════════════════════
let currentProjectId   = null;
let currentProjectName = '';
let teamEditId         = null;

function openDrawer(projectId, projectName, docsemObraId) {
    currentProjectId   = projectId;
    currentProjectName = projectName;
    document.getElementById('drawer-proj-name').textContent = projectName;
    document.getElementById('teams-drawer').classList.add('open');
    document.getElementById('drawer-overlay').classList.add('open');
    // Mostrar botao sync apenas se obra ligada ao DocsEM
    const btnSync = document.getElementById('btn-sync-docsem');
    if (btnSync) {
        btnSync.style.display = docsemObraId ? 'inline-flex' : 'none';
        btnSync.dataset.docsemId = docsemObraId || '';
    }
    // Always reset to teams tab
    switchDrawerTab('teams');
}

function closeDrawer() {
    document.getElementById('teams-drawer').classList.remove('open');
    document.getElementById('drawer-overlay').classList.remove('open');
    currentProjectId = null;
}

async function loadTeams() {
    try {
        const res = await apiFetch('GET', `/projects/${currentProjectId}/teams`);
        renderTeams(res.data);
    } catch (e) {
        toast(e.message, 'error');
    }
}

function renderTeams(teams) {
    const container = document.getElementById('teams-list');
    if (!teams.length) {
        container.innerHTML = `<div class="empty-state"><span class="empty-icon">👥</span><p>Nenhuma equipa. Crie a primeira!</p></div>`;
        return;
    }

    container.innerHTML = teams.map(t => {
        const tData = encodeURIComponent(JSON.stringify(t));
        const empRows = t.employees.map(e => `
            <div class="member-row">
                <div>
                    <span class="member-name">${e.name}</span>
                    <span class="member-code">${e.code}</span>
                    ${e.role ? `<span style="font-size:.74rem;color:var(--text-muted)"> · ${e.role}</span>` : ''}
                    ${e.start_date ? `<div class="member-meta">${fmtDate(e.start_date)}${e.end_date ? ' → ' + fmtDate(e.end_date) : ' →'}</div>` : ''}
                </div>
                <button class="btn-sm btn-del" onclick="removeEmp(${t.id}, ${e.id})" title="Remover funcionário da equipa">✕</button>
            </div>`).join('');

        const vehRows = t.vehicles.map(v => `
            <div class="member-row">
                <div>
                    <span style="font-weight:700;font-family:monospace;font-size:.84rem">${v.plate}</span>
                    <span class="member-meta">${[v.brand, v.model].filter(Boolean).join(' ')}</span>
                    ${v.start_date ? `<div class="member-meta">${fmtDate(v.start_date)}${v.end_date ? ' → ' + fmtDate(v.end_date) : ' →'}</div>` : ''}
                </div>
                <button class="btn-sm btn-del" onclick="removeVeh(${t.id}, ${v.id})" title="Remover viatura da equipa">✕</button>
            </div>`).join('');

        return `
        <div class="team-card">
            <div class="team-card-hdr">
                <span class="team-card-name">${t.name}</span>
                <div style="display:flex;gap:6px">
                    <button class="btn-sm btn-sec" onclick="openTeamModal(decodeURIComponent('${tData}'))" title="Editar equipa">✏️</button>
                    <button class="btn-sm btn-del" onclick="deleteTeam(${t.id})" title="Eliminar equipa">🗑</button>
                </div>
            </div>
            ${t.leader ? `<div style="font-size:.8rem;color:var(--text-muted);margin-bottom:10px">👷 <strong>${t.leader.name}</strong></div>` : ''}

            <div style="margin-bottom:10px">
                <div class="team-section-lbl">
                    <span>👷 Funcionários (${t.employees.length})</span>
                    <button class="btn-sm btn-sec" onclick="openEmpModal(${t.id})">+ Adicionar</button>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px">
                    ${empRows || '<p style="font-size:.81rem;color:var(--text-muted);padding:4px 0;margin:0">Nenhum funcionário</p>'}
                </div>
            </div>

            <div>
                <div class="team-section-lbl">
                    <span>🚐 Viaturas (${t.vehicles.length})</span>
                    <button class="btn-sm btn-sec" onclick="openVehTeamModal(${t.id})">+ Adicionar</button>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px">
                    ${vehRows || '<p style="font-size:.81rem;color:var(--text-muted);padding:4px 0;margin:0">Nenhuma viatura</p>'}
                </div>
            </div>
        </div>`;
    }).join('');
}

// ── Modal Equipa ─────────────────────────────────────────────────────────
function openTeamModal(raw = null) {
    const t = raw ? (typeof raw === 'string' ? JSON.parse(raw) : raw) : null;
    teamEditId = t?.id || null;
    document.getElementById('team-modal-title').textContent = t ? 'Editar Equipa' : 'Nova Equipa';
    document.getElementById('team-name').value  = t?.name  || '';
    document.getElementById('team-notes').value = t?.notes || '';

    const leaderSel = document.getElementById('team-leader');
    const chiefs = allEmployees.filter(e => {
        const pos = (e.position?.position || '').toUpperCase();
        return pos.includes('CHEFE DE EQUIPA');
    });
    leaderSel.innerHTML = '<option value="">— Sem encarregado —</option>' +
        chiefs.map(e => `<option value="${e.id}" ${t?.leader?.id == e.id ? 'selected' : ''}>${e.full_name} (${e.code})</option>`).join('');

    document.getElementById('team-modal').style.display = 'flex';
}

function closeTeamModal() {
    document.getElementById('team-modal').style.display = 'none';
}

async function saveTeam() {
    const body = {
        name:      document.getElementById('team-name').value.trim(),
        leader_id: document.getElementById('team-leader').value || null,
        notes:     document.getElementById('team-notes').value.trim() || null,
    };
    if (!body.name) { toast('O nome da equipa é obrigatório.', 'error'); return; }

    try {
        if (teamEditId) {
            await apiFetch('PUT', `/projects/${currentProjectId}/teams/${teamEditId}`, body);
            toast('Equipa actualizada.');
        } else {
            await apiFetch('POST', `/projects/${currentProjectId}/teams`, body);
            toast('Equipa criada.');
        }
        closeTeamModal();
        loadTeams();
    } catch (e) {
        toast(e.message, 'error');
    }
}

async function deleteTeam(teamId) {
    if (!confirm('Eliminar esta equipa?')) return;
    try {
        await apiFetch('DELETE', `/projects/${currentProjectId}/teams/${teamId}`);
        toast('Equipa eliminada.');
        loadTeams();
    } catch (e) {
        toast(e.message, 'error');
    }
}

// ── Modal Funcionário na Equipa ──────────────────────────────────────────
let currentTeamIdForEmp = null;

function openEmpModal(teamId) {
    currentTeamIdForEmp = teamId;
    const sel = document.getElementById('emp-select');
    sel.innerHTML = allEmployees.map(e => `<option value="${e.id}">${e.full_name} — ${e.code}</option>`).join('');
    document.getElementById('emp-role').value  = '';
    document.getElementById('emp-start').value = '';
    document.getElementById('emp-end').value   = '';
    document.getElementById('emp-modal').style.display = 'flex';
}

function closeEmpModal() {
    document.getElementById('emp-modal').style.display = 'none';
}

async function saveEmployee() {
    const body = {
        employee_id: parseInt(document.getElementById('emp-select').value),
        role:        document.getElementById('emp-role').value.trim()  || null,
        start_date:  document.getElementById('emp-start').value        || null,
        end_date:    document.getElementById('emp-end').value          || null,
    };

    try {
        await apiFetch('POST', `/projects/${currentProjectId}/teams/${currentTeamIdForEmp}/employees`, body);
        toast('Funcionário adicionado.');
        closeEmpModal();
        loadTeams();
    } catch (e) {
        toast(e.message, 'error');
    }
}

async function removeEmp(teamId, employeeId) {
    if (!confirm('Remover este funcionário da equipa?')) return;
    try {
        await apiFetch('DELETE', `/projects/${currentProjectId}/teams/${teamId}/employees`, { employee_id: employeeId });
        toast('Funcionário removido.');
        loadTeams();
    } catch (e) {
        toast(e.message, 'error');
    }
}

// ── Modal Viatura na Equipa ──────────────────────────────────────────────
let currentTeamIdForVeh = null;

function openVehTeamModal(teamId) {
    currentTeamIdForVeh = teamId;
    const sel = document.getElementById('veh-team-select');
    sel.innerHTML = allVehicles.length
        ? allVehicles.map(v => `<option value="${v.id}">${v.plate}${v.brand ? ' — ' + v.brand + ' ' + (v.model || '') : ''}</option>`).join('')
        : '<option value="">Nenhuma viatura disponível</option>';
    document.getElementById('veh-team-start').value = '';
    document.getElementById('veh-team-end').value   = '';
    document.getElementById('veh-team-modal').style.display = 'flex';
}

function closeVehTeamModal() {
    document.getElementById('veh-team-modal').style.display = 'none';
}

async function saveVehTeam() {
    const body = {
        vehicle_id: parseInt(document.getElementById('veh-team-select').value),
        start_date: document.getElementById('veh-team-start').value || null,
        end_date:   document.getElementById('veh-team-end').value   || null,
    };
    if (!body.vehicle_id) { toast('Seleccione uma viatura.', 'error'); return; }

    try {
        await apiFetch('POST', `/projects/${currentProjectId}/teams/${currentTeamIdForVeh}/vehicles`, body);
        toast('Viatura adicionada.');
        closeVehTeamModal();
        loadTeams();
    } catch (e) {
        toast(e.message, 'error');
    }
}

async function removeVeh(teamId, vehicleId) {
    if (!confirm('Remover esta viatura da equipa?')) return;
    try {
        await apiFetch('DELETE', `/projects/${currentProjectId}/teams/${teamId}/vehicles`, { vehicle_id: vehicleId });
        toast('Viatura removida.');
        loadTeams();
    } catch (e) {
        toast(e.message, 'error');
    }
}

// ══════════════════════════════════════════════════════════════════════════
// DRAWER TABS
// ══════════════════════════════════════════════════════════════════════════
let currentDrawerTab  = 'teams';
let companyEditId     = null;
let docsemResults     = [];
let _empSearchTimer   = null;
let _companyCache     = {}; // id -> company object

function switchDrawerTab(tab) {
    currentDrawerTab = tab;
    ['teams', 'companies'].forEach(t => {
        document.getElementById('dtab-' + t).classList.toggle('active', t === tab);
        document.getElementById('dpanel-' + t).style.display = t === tab ? 'block' : 'none';
    });
    if (tab === 'teams')     loadTeams();
    if (tab === 'companies') loadCompanies();
}


async function loadCompanies() {
    const container = document.getElementById('companies-list');
    container.innerHTML = '<p style="color:var(--text-muted);font-size:.85rem">A carregar...</p>';
    try {
        const res = await apiFetch('GET', `/projects/${currentProjectId}/companies`);
        renderCompanies(res.data);
    } catch (e) {
        container.innerHTML = `<p style="color:#f87171;font-size:.85rem">${e.message}</p>`;
    }
}

function renderCompanies(companies) {
    const container = document.getElementById('companies-list');
    _companyCache = {};
    if (!companies.length) {
        container.innerHTML = '<div class="empty-state"><span class="empty-icon">&#x1F3E2;</span><p>Nenhuma empresa associada.</p></div>';
        return;
    }
    companies.forEach(c => { _companyCache[c.id] = c; });
    container.innerHTML = companies.map(c => `
        <div class="company-row">
            <div class="company-row-info">
                <div class="company-row-name">${c.empresa_nome}</div>
                <div class="company-row-meta">
                    ${c.empresa_nif ? 'NIF ' + c.empresa_nif : ''}
                    ${c.data_entrada ? ' &middot; Entrada: ' + fmtDate(c.data_entrada) : ''}
                    ${c.data_saida   ? ' &middot; Sa&iacute;da: ' + fmtDate(c.data_saida) : ''}
                    ${c.employees_count > 0 ? ' &middot; &#x1F477; ' + c.employees_count + ' t&eacute;cnico' + (c.employees_count === 1 ? '' : 's') : ''}
                    ${c.observacoes  ? '<br><span>' + c.observacoes + '</span>' : ''}
                </div>
            </div>
            <div class="company-row-actions">
                <button class="btn-sm btn-sec" onclick="openCompanyModal(getCompanyCache(${c.id}))" title="Editar datas e observa&ccedil;&otilde;es">&#x270F;</button>
                <button class="btn-sm btn-del" onclick="removeCompany(${c.id})" title="Remover empresa da obra">&#x1F5D1;</button>
            </div>
        </div>`).join('');
}

function openCompanyModal(rawJson) {
    const c = rawJson ? (typeof rawJson === 'string' ? JSON.parse(rawJson) : rawJson) : null;
    companyEditId = c ? c.id : null;
    document.getElementById('company-modal-title').textContent = c ? 'Editar Associacao' : 'Associar Empresa';
    document.getElementById('company-search').value  = c ? c.empresa_nome : '';
    document.getElementById('company-id').value      = c ? c.docsem_empresa_id : '';
    document.getElementById('company-nif').value     = c ? (c.empresa_nif || '') : '';
    document.getElementById('company-entrada').value = c ? (c.data_entrada || '') : '';
    document.getElementById('company-saida').value   = c ? (c.data_saida   || '') : '';
    document.getElementById('company-obs').value     = c ? (c.observacoes  || '') : '';
    document.getElementById('company-picker-results').style.display = 'none';
    if (c) {
        document.getElementById('company-selected').style.display = 'block';
        document.getElementById('company-selected').textContent   = c.empresa_nome + (c.empresa_nif ? ' (NIF: ' + c.empresa_nif + ')' : '');
        document.getElementById('company-search').style.display   = 'none';
    } else {
        document.getElementById('company-selected').style.display = 'none';
        document.getElementById('company-search').style.display   = '';
    }
    document.getElementById('company-modal').style.display = 'flex';
}

function closeCompanyModal() {
    document.getElementById('company-modal').style.display = 'none';
    document.getElementById('company-picker-results').style.display = 'none';
    companyEditId = null;
    docsemResults = [];
}

function searchEmpresas() {
    clearTimeout(_empSearchTimer);
    const q = document.getElementById('company-search').value.trim();
    if (q.length === 0) {
        // Show all on focus/empty
        _empSearchTimer = setTimeout(() => _doSearchEmpresas(''), 200);
        return;
    }
    if (q.length < 2) {
        document.getElementById('company-picker-results').style.display = 'none';
        return;
    }
    _empSearchTimer = setTimeout(() => _doSearchEmpresas(q), 350);
}

async function _doSearchEmpresas(q) {
    const resultsEl = document.getElementById('company-picker-results');
    resultsEl.innerHTML = '<div class="picker-item" style="color:var(--text-muted)">A pesquisar...</div>';
    resultsEl.style.display = 'block';
    try {
        const res = await apiFetch('GET', `/docsem/empresas?search=${encodeURIComponent(q)}`);
        const list = res.data || [];
        docsemResults = list;
        if (!list.length) {
            resultsEl.innerHTML = '<div class="picker-item" style="color:var(--text-muted)">Nenhuma empresa encontrada.</div>';
            return;
        }
        resultsEl.innerHTML = list.map((e, i) => `
            <div class="picker-item" onclick="selectEmpresa(${i})">
                <strong>${e.nome}</strong>
                ${e.nif ? `<span style="color:var(--text-muted);font-size:.8rem"> NIF ${e.nif}</span>` : ''}
            </div>`).join('');
    } catch (err) {
        resultsEl.innerHTML = `<div class="picker-item" style="color:#f87171">${err.message}</div>`;
    }
}

function selectEmpresa(index) {
    const e = docsemResults[index];
    if (!e) return;
    document.getElementById('company-id').value     = e.id;
    document.getElementById('company-nif').value    = e.nif || '';
    document.getElementById('company-search').style.display = 'none';
    document.getElementById('company-selected').style.display = 'block';
    document.getElementById('company-selected').textContent  = e.nome + (e.nif ? ` (NIF: ${e.nif})` : '');
    document.getElementById('company-picker-results').style.display = 'none';
    // Pre-fill name field for new entries
    if (!companyEditId) {
        document.getElementById('company-search').value = e.nome;
    }
}

async function saveCompany() {
    const docsemId = parseInt(document.getElementById('company-id').value) || null;
    const nif      = document.getElementById('company-nif').value.trim()     || null;
    const entrada  = document.getElementById('company-entrada').value         || null;
    const saida    = document.getElementById('company-saida').value           || null;
    const obs      = document.getElementById('company-obs').value.trim()      || null;

    if (!docsemId && !companyEditId) {
        toast('Seleccione uma empresa da lista.', 'error');
        return;
    }

    try {
        if (companyEditId) {
            await apiFetch('PUT', `/projects/${currentProjectId}/companies/${companyEditId}`, {
                data_entrada: entrada, data_saida: saida, observacoes: obs,
            });
            toast('Empresa actualizada.');
        } else {
            await apiFetch('POST', `/projects/${currentProjectId}/companies`, {
                docsem_empresa_id: docsemId,
                empresa_nif:       nif,
                data_entrada:      entrada,
                data_saida:        saida,
                observacoes:       obs,
            });
            toast('Empresa associada.');
        }
        closeCompanyModal();
        loadCompanies();
    } catch (e) {
        toast(e.message, 'error');
    }
}

async function removeCompany(companyId) {
    if (!confirm('Remover esta empresa da obra?')) return;
    try {
        await apiFetch('DELETE', `/projects/${currentProjectId}/companies/${companyId}`);
        toast('Empresa removida.');
        loadCompanies();
    } catch (e) {
        toast(e.message, 'error');
    }
}

// ── Sincronizar obra com DocsEM ──────────────────────────────────────────

async function syncDocsemObra() {
    const btn = document.getElementById('btn-sync-docsem');
    const origText = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.innerHTML = '&#x23F3; A sincronizar...'; }
    try {
        const res = await apiFetch('POST', `/projects/${currentProjectId}/sync-docsem`);
        const n = res.empresas_sincronizadas ?? 0;
        toast(`Sincronizado: ${n} empresa(s) importada(s).`, 'success');
        // Reload companies tab to show newly imported ones
        loadCompanies();
        // Reload project list so card updates
        loadProjects();
    } catch (e) {
        toast(e.message, 'error');
    } finally {
        if (btn) { btn.disabled = false; btn.innerHTML = origText; }
    }
}

// ══════════════════════════════════════════════════════════════════════════
// window.* exports — necessário para Vite ESM + onclick inline
// ══════════════════════════════════════════════════════════════════════════
function openStaffingCheck(startDate, endDate, encodedName = '') {
    const params = new URLSearchParams({ start: startDate, end: endDate });
    if (encodedName) params.set('name', encodedName);
    window.location.href = '/trainings/staffing-check?' + params.toString();
}

window.switchTab             = switchTab;
window.openStaffingCheck      = openStaffingCheck;
window.loadProjects          = loadProjects;
window.openProjectModal      = openProjectModal;
window.closeProjectModal     = closeProjectModal;
window.saveProject           = saveProject;
window.deleteProject         = deleteProject;
window.openDrawer            = openDrawer;
window.closeDrawer           = closeDrawer;
window.switchDrawerTab       = switchDrawerTab;
window.openTeamModal         = openTeamModal;
window.closeTeamModal        = closeTeamModal;
window.saveTeam              = saveTeam;
window.deleteTeam            = deleteTeam;
window.openEmpModal          = openEmpModal;
window.closeEmpModal         = closeEmpModal;
window.saveEmployee          = saveEmployee;
window.removeEmp             = removeEmp;
window.openVehTeamModal      = openVehTeamModal;
window.closeVehTeamModal     = closeVehTeamModal;
window.saveVehTeam           = saveVehTeam;
window.removeVeh             = removeVeh;
window.loadVehicles          = loadVehicles;
window.openVehicleModal      = openVehicleModal;
window.closeVehicleModal     = closeVehicleModal;
window.saveVehicle           = saveVehicle;
window.deleteVehicle         = deleteVehicle;
window.openCompanyModal      = openCompanyModal;
window.closeCompanyModal     = closeCompanyModal;
window.saveCompany           = saveCompany;
window.removeCompany         = removeCompany;
window.searchEmpresas        = searchEmpresas;
window.selectEmpresa         = selectEmpresa;
window.getCompanyCache       = (id) => _companyCache[id];
window.searchObrasDocsem     = searchObrasDocsem;
window.selectObraDocsem      = selectObraDocsem;
window.clearObraDocsem       = clearObraDocsem;
window.syncDocsemObra        = syncDocsemObra;

// Inicialização — compatível com defer e módulos
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadProjects);
} else {
    loadProjects();
}
te === 'loading') {
    document.addEventListener('DOMContentLoaded', load