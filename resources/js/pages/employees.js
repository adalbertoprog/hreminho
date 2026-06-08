/**
 * employees.js — Lógica da página de Funcionários
 * Depende de window.EMP_CONFIG injectado pelo Blade:
 *   { docsemPingUrl, openDocsEmOnLoad }
 */

const API  = '/api/v1';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let state = { page: 1, search: '', department_id: '', sector_id: '', position_id: '', status: '', sort: 'name_asc' };
let editId = null, deleteId = null, viewEmpId = null, viewTrainings = [];
let _searchTimer = null;
let depts = [], positions = [], sectors = [];
let photoBase64 = null;
let systemUsers = [];
let qaEmpId = null;

function debouncedSearch() {
    clearTimeout(_searchTimer);
    _searchTimer = setTimeout(applyFilters, 350);
}

/* ── Photo ── */
function handlePhotoChange(input) {
    const file = input.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) { alert('Max 2MB'); input.value = ''; return; }
    const r = new FileReader();
    r.onload = e => { photoBase64 = e.target.result; setPhotoPreview(photoBase64, null); };
    r.readAsDataURL(file);
}

function clearPhoto() {
    photoBase64 = null;
    document.getElementById('photoFile').value = '';
    document.getElementById('photoImg').style.display = 'none';
    document.getElementById('photoImg').src = '';
    document.getElementById('photoInitials').style.display = '';
    document.getElementById('photoClearBtn').style.display = 'none';
}

function setPhotoPreview(src, initials) {
    const img = document.getElementById('photoImg');
    const ini = document.getElementById('photoInitials');
    const btn = document.getElementById('photoClearBtn');
    if (src) {
        img.src = src; img.style.display = 'block';
        ini.style.display = 'none'; btn.style.display = 'inline-flex';
    } else {
        img.style.display = 'none'; img.src = '';
        ini.textContent = initials || '?'; ini.style.display = '';
        btn.style.display = 'none';
    }
}

/* ── Helpers ── */
async function apiFetch(method, path, body) {
    const opts = { method, credentials: 'same-origin', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } };
    if (body) opts.body = JSON.stringify(body);
    const r = await fetch(API + path, opts);
    if (!r.ok) { const e = await r.json().catch(() => ({ message: 'Erro' })); throw e; }
    return r.status === 204 ? null : r.json();
}

function toast(msg, type = 'ok') {
    const w = document.getElementById('toastWrap');
    const t = document.createElement('div');
    t.className = `toast toast-${type}`;
    t.textContent = msg;
    w.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

function openOverlay(id) { document.getElementById(id).classList.add('open'); }
function closeOverlay(id) { document.getElementById(id).classList.remove('open'); }

/* ── Boot ── */
async function loadStats() {
    try {
        const [all, active, inactive] = await Promise.all([
            apiFetch('GET', '/employees?per_page=1').catch(() => null),
            apiFetch('GET', '/employees?per_page=1&status=active').catch(() => null),
            apiFetch('GET', '/employees?per_page=1&status=inactive').catch(() => null),
        ]);
        const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val ?? '—'; };
        set('statTotal',    all?.meta?.total     ?? all?.total     ?? '—');
        set('statActive',   active?.meta?.total  ?? active?.total  ?? '—');
        set('statInactive', inactive?.meta?.total ?? inactive?.total ?? '—');
    } catch (e) { /* silencioso */ }
}

async function boot() {
    const [d, p, s, u] = await Promise.all([
        apiFetch('GET', '/departments?per_page=200').catch(() => ({ data: [] })),
        apiFetch('GET', '/positions?per_page=200').catch(() => ({ data: [] })),
        apiFetch('GET', '/sectors?per_page=200').catch(() => ({ data: [] })),
        apiFetch('GET', '/users?per_page=200&role=employee').catch(() => ({ data: [] })),
    ]);
    depts = d.data ?? []; positions = p.data ?? []; sectors = s.data ?? []; systemUsers = u.data ?? [];

    ['fDept', 'fDeptModal'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        depts.forEach(x => el.innerHTML += `<option value="${x.id}">${x.department}</option>`);
    });
    ['fPos', 'fPosModal'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        positions.forEach(x => el.innerHTML += `<option value="${x.id}">${x.position}</option>`);
    });
    ['fSector', 'fSecModal'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        sectors.forEach(x => el.innerHTML += `<option value="${x.id}">${x.sector}</option>`);
    });
    const uSel = document.getElementById('fUserModal');
    if (uSel) systemUsers.forEach(u => uSel.innerHTML += `<option value="${u.id}">${u.name} (${u.email})</option>`);

    loadStats();
    loadEmployees();

    // Abrir modal DocsEM automaticamente se houve flash session
    if (window.EMP_CONFIG?.openDocsEmOnLoad) {
        openDocsEmSync();
    }
}

/* ── Load & Render ── */
async function loadEmployees() {
    const tbody = document.getElementById('empBody');
    tbody.innerHTML = '<tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('pagBar').style.display = 'none';
    const params = { page: state.page, per_page: 15 };
    if (state.search)        params.search        = state.search;
    if (state.department_id) params.department_id = state.department_id;
    if (state.position_id)   params.position_id   = state.position_id;
    if (state.sector_id)     params.sector_id     = state.sector_id;
    if (state.status)        params.status        = state.status;
    if (state.sort)          params.sort          = state.sort;
    try {
        const res  = await fetch(`${API}/employees?${new URLSearchParams(params)}`, { credentials: 'same-origin', headers: { Accept: 'application/json' } });
        const json = await res.json();
        renderTable(json.data ?? []); renderPag(json.meta); updateSortHeaders();
    } catch {
        tbody.innerHTML = '<tr class="state-row"><td colspan="9">Erro ao carregar.</td></tr>';
    }
}

const BG = ['#6366f1', '#8b5cf6', '#06b6d4', '#22c55e', '#f59e0b', '#ef4444', '#ec4899'];
const empMap = {};

function yearsAgo(dateStr) {
    if (!dateStr) return '—';
    const diff = (new Date() - new Date(dateStr)) / (1000 * 60 * 60 * 24 * 365.25);
    return diff < 1 ? '< 1 ano' : Math.floor(diff) + ' ano(s)';
}

function renderTable(rows) {
    const tbody = document.getElementById('empBody');
    if (!rows.length) { tbody.innerHTML = '<tr class="state-row"><td colspan="9">Nenhum funcionário encontrado.</td></tr>'; return; }
    rows.forEach(emp => empMap[emp.id] = emp);
    tbody.innerHTML = rows.map((emp, i) => {
        const ini = ((emp.first_name?.[0] ?? '') + (emp.last_name?.[0] ?? '')).toUpperCase();
        const bg  = BG[i % BG.length];
        const av  = emp.photo
            ? `<div class="avatar"><img src="${emp.photo}" alt="${ini}"></div>`
            : `<div class="avatar" style="background:${bg}">${ini}</div>`;
        const sb  = { active: '<span class="badge badge-active">Ativo</span>', inactive: '<span class="badge badge-inactive">Inativo</span>', terminated: '<span class="badge badge-terminated">Desligado</span>' }[emp.status] ?? `<span class="badge">${emp.status}</span>`;
        return `<tr>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    ${av}
                    <div>
                        <span class="emp-name-wrap"
                            onmouseenter="showHoverCard(event,${emp.id})"
                            onmouseleave="hideHoverCard()">
                            <span class="emp-name">${emp.full_name}</span>
                            ${emp.user_id ? '<span title="Utilizador do sistema associado" style="font-size:.7rem;background:rgba(34,197,94,.15);color:#4ade80;border-radius:6px;padding:1px 6px;margin-left:6px;vertical-align:middle">🔗 Portal</span>' : ''}
                        </span>
                    </div>
                </div>
            </td>
            <td><span style="font-family:monospace;font-size:.78rem;color:var(--text-muted)">${emp.code}</span></td>
            <td>${emp.sector?.sector ?? '—'}</td>
            <td>${emp.position?.position ?? '—'}</td>
            <td style="color:var(--text-muted);font-size:.82rem">${emp.hire_date ? new Date(emp.hire_date + 'T00:00:00').toLocaleDateString('pt-PT') : '—'}</td>
            <td style="font-size:.82rem;color:var(--text-muted)">${emp.date_of_birth ? Math.floor((new Date() - new Date(emp.date_of_birth + 'T00:00:00')) / (1000 * 60 * 60 * 24 * 365.25)) + ' anos' : '—'}</td>
            <td style="font-size:.82rem;color:var(--text-muted)">${yearsAgo(emp.hire_date)}</td>
            <td>${sb}</td>
            <td style="white-space:nowrap">
                <button class="btn-sm btn-view" onclick="openView(${emp.id})">👁 Ver</button>
                <button class="btn-sm btn-edit" onclick="openEdit(${emp.id})">✏️ Editar</button>
                ${emp.user_id
                    ? `<button class="btn-sm btn-unlink" onclick="openQuickAssoc(${emp.id})" title="Gerir associação ao portal">🔗</button>`
                    : `<button class="btn-sm btn-link"   onclick="openQuickAssoc(${emp.id})" title="Associar ao portal">🔗</button>`
                }
                <button class="btn-sm btn-del" onclick="openDeleteModal(${emp.id},'${ini}')">🗑</button>
            </td></tr>`;
    }).join('');
}

function renderPag(meta) {
    if (!meta) return;
    document.getElementById('pagBar').style.display = 'flex';
    document.getElementById('pagInfo').textContent = `${meta.from ?? 0}–${meta.to ?? 0} de ${meta.total}`;
    const btns = document.getElementById('pagBtns'); btns.innerHTML = '';
    const prev = document.createElement('button'); prev.textContent = '‹'; prev.disabled = meta.current_page <= 1;
    prev.onclick = () => { state.page = meta.current_page - 1; loadEmployees(); }; btns.appendChild(prev);
    const s2 = Math.max(1, meta.current_page - 3), e2 = Math.min(meta.last_page, s2 + 6);
    for (let i = s2; i <= e2; i++) {
        const b = document.createElement('button'); b.textContent = i;
        if (i === meta.current_page) b.classList.add('active');
        b.onclick = (p => () => { state.page = p; loadEmployees(); })(i); btns.appendChild(b);
    }
    const next = document.createElement('button'); next.textContent = '›'; next.disabled = meta.current_page >= meta.last_page;
    next.onclick = () => { state.page = meta.current_page + 1; loadEmployees(); }; btns.appendChild(next);
}

/* ── Sort ── */
function setSort(field) {
    const cur = state.sort;
    if (cur === field + '_asc')       state.sort = field + '_desc';
    else if (cur === field + '_desc') state.sort = field + '_asc';
    else                              state.sort = field + '_asc';
    state.page = 1; loadEmployees();
}

function updateSortHeaders() {
    const cols  = { name: 'thName', code: 'thCode' };
    const icons = { name: 'siName', code: 'siCode' };
    Object.keys(cols).forEach(field => {
        const th = document.getElementById(cols[field]);
        const si = document.getElementById(icons[field]);
        th.classList.remove('sort-asc', 'sort-desc');
        si.textContent = '⇅';
        if (state.sort === field + '_asc')  { th.classList.add('sort-asc');  si.textContent = '↑'; }
        if (state.sort === field + '_desc') { th.classList.add('sort-desc'); si.textContent = '↓'; }
    });
}

/* ── Filters ── */
function applyFilters() {
    state.search        = document.getElementById('fSearch').value.trim();
    state.department_id = document.getElementById('fDept').value;
    state.position_id   = document.getElementById('fPos').value;
    state.sector_id     = document.getElementById('fSector').value;
    state.status        = document.getElementById('fStatus').value;
    state.page = 1; loadEmployees();
}

function resetFilters() {
    ['fSearch', 'fDept', 'fPos', 'fSector', 'fStatus'].forEach(id => document.getElementById(id).value = '');
    state = { page: 1, search: '', department_id: '', sector_id: '', position_id: '', status: '', sort: 'name_asc' };
    loadEmployees();
}

/* ── Hover Card ── */
let hoverTimer = null, activeEmpId = null;

function showHoverCard(event, empId) {
    const emp = empMap[empId]; if (!emp) return;
    clearTimeout(hoverTimer);
    const card = document.getElementById('profileCard');
    const ini  = ((emp.first_name?.[0] ?? '') + (emp.last_name?.[0] ?? '')).toUpperCase();
    const av   = document.getElementById('pcAvatar');
    av.innerHTML = emp.photo ? `<img src="${emp.photo}" alt="${ini}">` : `<span>${ini}</span>`;
    document.getElementById('pcName').textContent = emp.full_name;
    document.getElementById('pcSub').textContent  = `${emp.position?.position ?? '—'} · ${emp.department?.department ?? '—'}`;
    document.getElementById('pcDob').textContent  = emp.date_of_birth ? new Date(emp.date_of_birth + 'T00:00:00').toLocaleDateString('pt-PT') : '—';
    document.getElementById('pcAge').textContent  = emp.date_of_birth ? Math.floor((new Date() - new Date(emp.date_of_birth)) / (1000 * 60 * 60 * 24 * 365.25)) + ' anos' : '—';
    document.getElementById('pcTenure').textContent       = yearsAgo(emp.hire_date);
    document.getElementById('pcContract').textContent     = emp.contract_type || '—';
    document.getElementById('pcWorkLocation').textContent = emp.work_location || '—';
    document.getElementById('pcAddress').textContent      = emp.address || '—';
    document.getElementById('pcTrainings').innerHTML      = '<div class="pc-loading">A carregar...</div>';
    positionCard(card, event);
    card.classList.add('visible');
    if (activeEmpId !== emp.id) {
        activeEmpId = emp.id;
        apiFetch('GET', `/enrollments?employee_id=${emp.id}&per_page=50`).then(res => {
            const rows = res.data ?? [];
            if (!rows.length) { document.getElementById('pcTrainings').innerHTML = '<div class="pc-tr-empty">Sem formações registadas.</div>'; return; }
            const sC = { enrolled: 'pc-tr-enrolled', completed: 'pc-tr-completed', failed: 'pc-tr-failed' };
            const sL = { enrolled: 'Inscrito', completed: 'Concluído', failed: 'Reprovado' };
            document.getElementById('pcTrainings').innerHTML = rows.slice(0, 4).map(r => `
                <div class="pc-training-item">
                    <span class="pc-tr-name">${r.training?.title ?? '—'}</span>
                    <span class="pc-tr-status ${sC[r.status] ?? ''}">${sL[r.status] ?? r.status}</span>
                </div>`).join('') + (rows.length > 4 ? `<div class="pc-tr-empty">+${rows.length - 4} mais...</div>` : '');
        }).catch(() => { document.getElementById('pcTrainings').innerHTML = '<div class="pc-tr-empty">Erro ao carregar.</div>'; });
    }
}

function positionCard(card, event) {
    const vw = window.innerWidth, vh = window.innerHeight;
    let x = event.clientX + 16, y = event.clientY + 16;
    const w = 320, h = 380;
    if (x + w > vw) x = event.clientX - w - 8;
    if (y + h > vh) y = vh - h - 8;
    card.style.left = x + 'px'; card.style.top = y + 'px';
}

function hideHoverCard() {
    hoverTimer = setTimeout(() => {
        document.getElementById('profileCard').classList.remove('visible');
        activeEmpId = null;
    }, 200);
}

/* ── Create / Edit ── */
function openCreate() {
    editId = null; document.getElementById('empForm').reset(); clearPhoto(); setPhotoPreview(null, '?');
    document.getElementById('formTitle').textContent     = 'Novo Funcionário';
    document.getElementById('formSubmitBtn').textContent = 'Criar Funcionário';
    openOverlay('formOverlay');
}

function openEdit(empId) {
    const emp = empMap[empId]; if (!emp) return;
    editId = emp.id; document.getElementById('empForm').reset();
    const form = document.getElementById('empForm');
    const set  = (n, v) => { const el = form.querySelector(`[name="${n}"]`); if (el) el.value = v ?? ''; };
    set('code', emp.code); set('first_name', emp.first_name); set('last_name', emp.last_name);
    set('email', emp.email); set('phone', emp.phone); set('date_of_birth', emp.date_of_birth);
    set('gender', emp.gender); set('nationality', emp.nationality); set('address', emp.address);
    set('work_location', emp.work_location); set('position_id', emp.position_id);
    set('department_id', emp.department_id); set('sector_id', emp.sector_id ?? '');
    set('hire_date', emp.hire_date); set('status', emp.status); set('contract_type', emp.contract_type); set('end_date', emp.end_date);
    set('user_id', emp.user_id ?? '');
    const ini = ((emp.first_name?.[0] ?? '') + (emp.last_name?.[0] ?? '')).toUpperCase();
    photoBase64 = null; setPhotoPreview(emp.photo ?? null, ini);
    document.getElementById('formTitle').textContent     = 'Editar Funcionário';
    document.getElementById('formSubmitBtn').textContent = 'Guardar Alterações';
    openOverlay('formOverlay');
}

async function submitForm(e) {
    e.preventDefault();
    const btn = document.getElementById('formSubmitBtn'); btn.disabled = true; btn.textContent = 'A guardar...';
    const data = {}; new FormData(document.getElementById('empForm')).forEach((v, k) => { if (v !== '') data[k] = v; });
    if (data.user_id) data.user_id = parseInt(data.user_id);
    else delete data.user_id;
    if (photoBase64) data.photo = photoBase64;
    try {
        if (editId) await apiFetch('PUT', `/employees/${editId}`, data);
        else        await apiFetch('POST', '/employees', data);
        toast(editId ? 'Funcionário atualizado!' : 'Funcionário criado!', 'ok');
        closeOverlay('formOverlay'); loadEmployees();
    } catch (err) {
        const msg = err.errors ? Object.values(err.errors).flat().join('\n') : (err.message ?? 'Erro.');
        toast(msg, 'err');
    } finally {
        btn.disabled = false; btn.textContent = editId ? 'Guardar Alterações' : 'Criar Funcionário';
    }
}

/* ── Delete ── */
function openDeleteModal(id, name) {
    deleteId = id;
    document.getElementById('delMsg').textContent = `Confirmar exclusão de "${name}"?`;
    openOverlay('delOverlay');
}

async function confirmDelete() {
    try { await apiFetch('DELETE', `/employees/${deleteId}`); toast('Funcionário excluído.', 'ok'); closeOverlay('delOverlay'); loadEmployees(); }
    catch (err) { toast(err.message ?? 'Erro.', 'err'); }
}

/* ── View Modal ── */
function switchTab(panelId, btn) {
    document.querySelectorAll('.view-tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.view-tab').forEach(b => b.classList.remove('active'));
    document.getElementById(panelId).classList.add('active');
    btn.classList.add('active');
}

const genderLabel = { male: 'Masculino', female: 'Feminino', other: 'Outro' };
const statusLabel  = { active: 'Ativo', inactive: 'Inativo', terminated: 'Desligado' };
const statusBadge  = {
    active:     '<span class="badge badge-active">Ativo</span>',
    inactive:   '<span class="badge badge-inactive">Inativo</span>',
    terminated: '<span class="badge badge-terminated">Desligado</span>',
};

async function openView(empId) {
    const emp = empMap[empId]; if (!emp) return;
    viewEmpId = empId; viewTrainings = [];
    document.querySelectorAll('.view-tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.view-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('vTabInfo').classList.add('active');
    document.querySelector('.view-tab').classList.add('active');
    document.getElementById('vTrainingsContent').innerHTML = '<div class="tr-loading">⏳ A carregar formações…</div>';
    const ini = ((emp.first_name?.[0] ?? '') + (emp.last_name?.[0] ?? '')).toUpperCase();
    const av  = document.getElementById('vAvatar');
    av.innerHTML = emp.photo ? `<img src="${emp.photo}" alt="${ini}">` : `<span>${ini}</span>`;
    document.getElementById('vEditBtn').onclick = () => { closeOverlay('viewOverlay'); openEdit(empId); };
    document.getElementById('vName').textContent = emp.full_name;
    document.getElementById('vSub').textContent  = [emp.position?.position, emp.department?.department].filter(Boolean).join(' · ') || '—';
    const fmt = d => d ? new Date(d + 'T00:00:00').toLocaleDateString('pt-PT') : '—';
    document.getElementById('vCode').textContent        = emp.code || '—';
    document.getElementById('vGender').textContent      = genderLabel[emp.gender] ?? (emp.gender || '—');
    document.getElementById('vDob').textContent         = fmt(emp.date_of_birth);
    document.getElementById('vAge').textContent         = emp.date_of_birth ? Math.floor((new Date() - new Date(emp.date_of_birth + 'T00:00:00')) / (1000 * 60 * 60 * 24 * 365.25)) + ' anos' : '—';
    document.getElementById('vNationality').textContent = emp.nationality || '—';
    document.getElementById('vPhone').textContent       = emp.phone || '—';
    document.getElementById('vAddress').textContent     = emp.address || '—';
    document.getElementById('vEmail').textContent       = emp.email || '—';
    document.getElementById('vDept').textContent         = emp.department?.department || '—';
    document.getElementById('vSector').textContent       = emp.sector?.sector || '—';
    document.getElementById('vPosition').textContent     = emp.position?.position || '—';
    document.getElementById('vHireDate').textContent     = fmt(emp.hire_date);
    document.getElementById('vAgeContract').textContent  = emp.date_of_birth ? Math.floor((new Date() - new Date(emp.date_of_birth + 'T00:00:00')) / (1000 * 60 * 60 * 24 * 365.25)) + ' anos' : '—';
    document.getElementById('vTenure').textContent       = yearsAgo(emp.hire_date);
    document.getElementById('vStatus').innerHTML         = statusBadge[emp.status] ?? `<span class="badge">${emp.status}</span>`;
    document.getElementById('vContract').textContent     = emp.contract_type || '—';
    document.getElementById('vEndDate').textContent      = fmt(emp.end_date);
    document.getElementById('vWorkLocation').textContent = emp.work_location || '—';
    openOverlay('viewOverlay');
    try {
        const res  = await apiFetch('GET', `/enrollments?employee_id=${empId}&per_page=100`);
        const rows = res.data ?? [];
        viewTrainings = rows;
        if (!rows.length) { document.getElementById('vTrainingsContent').innerHTML = `<div class="tr-empty">Sem formações registadas para este funcionário.</div>`; return; }
        const sC = { enrolled: 'badge-enrolled', completed: 'badge-completed', failed: 'badge-failed' };
        const sL = { enrolled: 'Inscrito', completed: 'Concluído', failed: 'Reprovado' };
        const tbody = rows.map(r => {
            const scoreHtml = r.score != null
                ? `<div class="score-bar-wrap"><div class="score-bar"><div class="score-bar-fill" style="width:${r.score}%"></div></div><span style="font-size:.75rem;color:var(--text-muted);min-width:28px">${r.score}%</span></div>`
                : '—';
            return `<tr>
                <td style="font-weight:500">${r.training?.title ?? '—'}</td>
                <td style="color:var(--text-muted);font-size:.8rem">${r.training?.provider ?? '—'}</td>
                <td><span class="badge ${sC[r.status] ?? ''}">${sL[r.status] ?? r.status}</span></td>
                <td style="min-width:110px">${scoreHtml}</td>
                <td style="color:var(--text-muted);font-size:.8rem">${r.start_date ? new Date(r.start_date + 'T00:00:00').toLocaleDateString('pt-PT') : '—'}</td>
                <td style="color:var(--text-muted);font-size:.8rem">${r.completion_date ? new Date(r.completion_date + 'T00:00:00').toLocaleDateString('pt-PT') : '—'}</td>
            </tr>`;
        }).join('');
        const completed = rows.filter(r => r.status === 'completed').length;
        const enrolled  = rows.filter(r => r.status === 'enrolled').length;
        const failed    = rows.filter(r => r.status === 'failed').length;
        document.getElementById('vTrainingsContent').innerHTML = `
            <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap">
                <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);border-radius:10px;padding:10px 18px;text-align:center">
                    <div style="font-size:1.4rem;font-weight:800;color:#22c55e">${completed}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Concluídas</div>
                </div>
                <div style="background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);border-radius:10px;padding:10px 18px;text-align:center">
                    <div style="font-size:1.4rem;font-weight:800;color:var(--accent-light)">${enrolled}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Em curso</div>
                </div>
                <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:10px 18px;text-align:center">
                    <div style="font-size:1.4rem;font-weight:800;color:#ef4444">${failed}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Reprovadas</div>
                </div>
            </div>
            <div style="overflow-x:auto">
                <table class="tr-table">
                    <thead><tr>
                        <th>Formação</th><th>Provedor</th><th>Estado</th><th>Pontuação</th><th>Início</th><th>Conclusão</th>
                    </tr></thead>
                    <tbody>${tbody}</tbody>
                </table>
            </div>`;
    } catch (e) {
        document.getElementById('vTrainingsContent').innerHTML = `<div class="tr-empty">Erro ao carregar formações.</div>`;
    }
}

/* ── Download Ficha do Funcionário ── */
function downloadFicha() {
    const emp = empMap[viewEmpId]; if (!emp) return;
    const fmt = d => d ? new Date(d + 'T00:00:00').toLocaleDateString('pt-PT') : '—';
    const age = d => d ? Math.floor((new Date() - new Date(d + 'T00:00:00')) / (1000 * 60 * 60 * 24 * 365.25)) + ' anos' : '—';
    const yrs = d => { if (!d) return '—'; const diff = (new Date() - new Date(d + 'T00:00:00')) / (1000 * 60 * 60 * 24 * 365.25); return diff < 1 ? '< 1 ano' : Math.floor(diff) + ' ano(s)'; };
    const gl  = { male: 'Masculino', female: 'Feminino', other: 'Outro' };
    const sl  = { active: 'Ativo', inactive: 'Inativo', terminated: 'Desligado' };
    const ctl = { 'full-time': 'Tempo Inteiro', 'part-time': 'Tempo Parcial', 'freelance': 'Freelance' };
    const sL  = { enrolled: 'Inscrito', completed: 'Concluído', failed: 'Reprovado' };
    const sColor = { enrolled: '#6366f1', completed: '#16a34a', failed: '#dc2626' };
    const sBg    = { enrolled: '#ede9fe', completed: '#dcfce7', failed: '#fee2e2' };
    const rows = viewTrainings;
    const nCompleted = rows.filter(r => r.status === 'completed').length;
    const nEnrolled  = rows.filter(r => r.status === 'enrolled').length;
    const nFailed    = rows.filter(r => r.status === 'failed').length;
    const trRows = rows.length ? rows.map(r => `
        <tr>
            <td>${r.training?.title ?? '—'}</td>
            <td>${r.training?.provider ?? '—'}</td>
            <td style="text-align:center"><span style="display:inline-block;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:${sBg[r.status] ?? '#f3f4f6'};color:${sColor[r.status] ?? '#374151'}">${sL[r.status] ?? r.status}</span></td>
            <td style="text-align:center">${r.score != null ? r.score + '%' : '—'}</td>
            <td style="text-align:center">${r.start_date ? fmt(r.start_date) : '—'}</td>
            <td style="text-align:center">${r.end_date ? fmt(r.end_date) : '—'}</td>
            <td style="text-align:center">${r.validity_months ?? '—'}</td>
            <td style="text-align:center">${r.expiry_date ? fmt(r.expiry_date) : '—'}</td>
        </tr>`).join('') : `<tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:20px">Sem formações registadas.</td></tr>`;
    const ini   = ((emp.first_name?.[0] ?? '') + (emp.last_name?.[0] ?? '')).toUpperCase();
    const today = new Date().toLocaleDateString('pt-PT');
    const LOGO_URI = window.EMP_CONFIG?.logoUri ?? '';
    const html = `<!DOCTYPE html><html lang="pt"><head><meta charset="UTF-8"><title>Ficha de Funcionário — ${emp.full_name}</title>
<style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Segoe UI',Arial,sans-serif;font-size:10pt;color:#1e293b;background:#fff}.page{max-width:760px;margin:0 auto;padding:28px 32px}.hdr{display:flex;align-items:center;justify-content:space-between;padding-bottom:16px;border-bottom:2px solid #6366f1;margin-bottom:22px}.hdr-left{display:flex;align-items:center;gap:16px}.hdr-avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a78bfa);display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:#fff;flex-shrink:0;overflow:hidden}.hdr-avatar img{width:100%;height:100%;object-fit:cover;border-radius:50%}.hdr-name{font-size:15pt;font-weight:800;color:#1e293b}.hdr-sub{font-size:9pt;color:#64748b;margin-top:3px}.hdr-right{text-align:right}.hdr-logo{display:block;height:110px;width:auto;margin-left:auto}.hdr-meta{font-size:7.5pt;color:#94a3b8;margin-top:5px;text-align:right}.badge-active{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#dcfce7;color:#16a34a}.badge-inactive{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#fef9c3;color:#a16207}.badge-terminated{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#fee2e2;color:#dc2626}.sec-title{font-size:7.5pt;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#6366f1;padding:8px 0 5px;border-bottom:1px solid #e2e8f0;margin-bottom:12px;margin-top:18px}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px 20px;margin-bottom:4px}.field label{display:block;font-size:7pt;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#94a3b8;margin-bottom:2px}.field span{font-size:9.5pt;color:#1e293b;font-weight:500}.field.full{grid-column:1/-1}.kpi-row{display:flex;gap:12px;margin-bottom:16px}.kpi{flex:1;border-radius:10px;padding:10px 14px;text-align:center}.kpi-num{font-size:18pt;font-weight:800;line-height:1}.kpi-lbl{font-size:7pt;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-top:3px;color:#64748b}.kpi-green{background:#f0fdf4;border:1px solid #bbf7d0}.kpi-green .kpi-num{color:#16a34a}.kpi-blue{background:#eff6ff;border:1px solid #bfdbfe}.kpi-blue .kpi-num{color:#2563eb}.kpi-red{background:#fef2f2;border:1px solid #fecaca}.kpi-red .kpi-num{color:#dc2626}.tr-table{width:100%;border-collapse:collapse;font-size:8.5pt}.tr-table thead th{padding:7px 10px;text-align:left;font-size:7pt;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#64748b;border-bottom:1.5px solid #e2e8f0;background:#f8fafc}.tr-table tbody td{padding:7px 10px;border-bottom:1px solid #f1f5f9;vertical-align:middle}.tr-table tbody tr:last-child td{border-bottom:none}.tr-table tbody tr:nth-child(even) td{background:#f8fafc}.doc-footer{margin-top:28px;padding-top:10px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;font-size:7.5pt;color:#94a3b8}@media print{body{-webkit-print-color-adjust:exact;print-color-adjust:exact}.page{padding:16px 20px}}</style></head>
<body><div class="page">
  <div class="hdr"><div class="hdr-left"><div class="hdr-avatar">${emp.photo ? `<img src="${emp.photo}" alt="${ini}">` : ini}</div><div><div class="hdr-name">${emp.full_name}</div><div class="hdr-sub">${[emp.position?.position, emp.department?.department, emp.sector?.sector].filter(Boolean).join(' · ') || '—'}</div></div></div>
  <div class="hdr-right"><img class="hdr-logo" src="${LOGO_URI}" alt="HREminho"><div class="hdr-meta">Ficha de Funcionário · Emitida aos ${today}</div></div></div>
  <div class="sec-title">Dados Pessoais</div>
  <div class="grid"><div class="field"><label>Código</label><span>${emp.code || '—'}</span></div><div class="field"><label>Género</label><span>${gl[emp.gender] || (emp.gender || '—')}</span></div><div class="field"><label>Data de Nascimento</label><span>${fmt(emp.date_of_birth)}</span></div><div class="field"><label>Idade</label><span>${age(emp.date_of_birth)}</span></div><div class="field"><label>Nacionalidade</label><span>${emp.nationality || '—'}</span></div><div class="field"><label>Telefone</label><span>${emp.phone || '—'}</span></div><div class="field full"><label>Morada</label><span>${emp.address || '—'}</span></div><div class="field full"><label>Email</label><span>${emp.email || '—'}</span></div></div>
  <div class="sec-title">Contrato &amp; Função</div>
  <div class="grid"><div class="field"><label>Departamento</label><span>${emp.department?.department || '—'}</span></div><div class="field"><label>Setor</label><span>${emp.sector?.sector || '—'}</span></div><div class="field"><label>Função</label><span>${emp.position?.position || '—'}</span></div><div class="field"><label>Data de Admissão</label><span>${fmt(emp.hire_date)}</span></div><div class="field"><label>Anos de Casa</label><span>${yrs(emp.hire_date)}</span></div><div class="field"><label>Estado</label><span>${emp.status ? `<span class="badge-${emp.status}">${sl[emp.status] ?? emp.status}</span>` : '—'}</span></div><div class="field"><label>Tipo de Contrato</label><span>${ctl[emp.contract_type] || (emp.contract_type || '—')}</span></div><div class="field"><label>Data de Término</label><span>${fmt(emp.end_date)}</span></div><div class="field"><label>Local de Trabalho</label><span>${emp.work_location || '—'}</span></div></div>
  <div class="sec-title">Formações</div>
  <div class="kpi-row"><div class="kpi kpi-green"><div class="kpi-num">${nCompleted}</div><div class="kpi-lbl">Concluídas</div></div><div class="kpi kpi-blue"><div class="kpi-num">${nEnrolled}</div><div class="kpi-lbl">Em Curso</div></div><div class="kpi kpi-red"><div class="kpi-num">${nFailed}</div><div class="kpi-lbl">Reprovadas</div></div></div>
  <table class="tr-table"><thead><tr><th>Formação</th><th>Provedor</th><th style="text-align:center">Estado</th><th style="text-align:center">Pontuação</th><th style="text-align:center">Início</th><th style="text-align:center">Conclusão</th><th style="text-align:center">Validade (m)</th><th style="text-align:center">Expira em</th></tr></thead><tbody>${trRows}</tbody></table>
  <div class="doc-footer"><span></span><span>HREminho — Sistema de Gestão de Recursos Humanos</span></div>
</div><script>window.onload=function(){window.print();}<\/script></body></html>`;
    const w = window.open('', '_blank', 'width=900,height=700');
    w.document.write(html); w.document.close();
}

/* ── DocsElectro-Minho Modal ── */
function openDocsEmSync() {
    document.getElementById('docsEmOverlay').style.display = 'flex';
    checkDocsEmStatus();
}
function closeDocsEmSync() {
    document.getElementById('docsEmOverlay').style.display = 'none';
}
async function checkDocsEmStatus() {
    const dot  = document.getElementById('docsEmStatusDot');
    const text = document.getElementById('docsEmStatusText');
    dot.style.background = '#94a3b8';
    text.textContent = 'A verificar ligação…';
    try {
        const res  = await fetch(window.EMP_CONFIG.docsemPingUrl, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();
        if (data.online) {
            dot.style.background = '#22c55e';
            text.textContent = '✓ DocsElectro-Minho está acessível e pronto a receber.';
        } else {
            dot.style.background = '#ef4444';
            text.textContent = '✗ Não foi possível ligar ao DocsElectro-Minho.';
            document.getElementById('docsEmSubmitBtn').disabled = true;
        }
    } catch (e) {
        dot.style.background = '#ef4444';
        text.textContent = '✗ Erro ao verificar ligação.';
    }
}
function updateDocsEmOption(radio) {
    document.getElementById('optActive').style.borderColor = 'var(--border)';
    document.getElementById('optAll').style.borderColor    = 'var(--border)';
    const parent = radio.closest('label');
    if (parent) parent.style.borderColor = 'var(--accent)';
}

/* ── Gerar acessos em massa ── */
function openBulkUsersModal() {
    document.getElementById('bulkResult').style.display = 'none';
    document.getElementById('bulkFooter').innerHTML =
        `<button type="button" class="btn-cancel" onclick="closeOverlay('bulkUsersOverlay')">Cancelar</button>
         <button type="button" class="btn-primary" id="bulkRunBtn" onclick="runBulkUsers()">▶ Executar</button>`;
    openOverlay('bulkUsersOverlay');
}

async function runBulkUsers() {
    const btn = document.getElementById('bulkRunBtn');
    btn.disabled = true; btn.textContent = 'A criar contas…';
    try {
        const res = await apiFetch('POST', '/employees/bulk-create-users', {});
        document.getElementById('brCreated').textContent = res.created;
        document.getElementById('brLinked').textContent  = res.linked;
        document.getElementById('brErrors').textContent  = res.errors?.length ?? 0;
        document.getElementById('bulkResult').style.display = 'block';
        document.getElementById('bulkFooter').innerHTML =
            `<button type="button" class="btn-primary" onclick="closeOverlay('bulkUsersOverlay');loadEmployees()">✓ Fechar e actualizar</button>`;
        toast(res.message, 'ok');
    } catch (err) {
        toast(err.message ?? 'Erro ao gerar acessos.', 'err');
        btn.disabled = false; btn.textContent = '▶ Executar';
    }
}

/* ── Associação rápida ── */
function openQuickAssoc(empId) {
    qaEmpId = empId;
    const emp = empMap[empId]; if (!emp) return;
    document.getElementById('qaSubtitle').innerHTML = `Funcionário: <strong>${emp.full_name}</strong> (${emp.code})`;
    const usedUserIds = new Set(Object.values(empMap).filter(e => e.user_id && e.id !== empId).map(e => e.user_id));
    const sel = document.getElementById('qaUserSelect');
    sel.innerHTML = '<option value="">— Sem utilizador associado —</option>';
    systemUsers.forEach(u => {
        if (usedUserIds.has(u.id)) return;
        const opt = document.createElement('option');
        opt.value = u.id; opt.textContent = `${u.name} (${u.email})`;
        if (u.id === emp.user_id) opt.selected = true;
        sel.appendChild(opt);
    });
    openOverlay('quickAssocOverlay');
}

async function submitQuickAssoc() {
    if (!qaEmpId) return;
    const btn    = document.getElementById('qaSubmitBtn');
    const sel    = document.getElementById('qaUserSelect');
    const userId = sel.value ? parseInt(sel.value) : null;
    btn.disabled = true; btn.textContent = 'A guardar…';
    try {
        await apiFetch('PUT', `/employees/${qaEmpId}`, { user_id: userId });
        toast(userId ? 'Utilizador associado com sucesso!' : 'Associação removida.', 'ok');
        closeOverlay('quickAssocOverlay'); loadEmployees();
    } catch (err) {
        const msg = err.errors ? Object.values(err.errors).flat().join('\n') : (err.message ?? 'Erro.');
        toast(msg, 'err');
    } finally {
        btn.disabled = false; btn.textContent = 'Guardar';
    }
}

/* ── Expor funções para o escopo global (necessário para onclick inline no HTML) ── */
Object.assign(window, {
    debouncedSearch,
    applyFilters,
    resetFilters,
    setSort,
    handlePhotoChange,
    clearPhoto,
    openCreate,
    openEdit,
    submitForm,
    openDeleteModal,
    confirmDelete,
    openView,
    switchTab,
    downloadFicha,
    showHoverCard,
    hideHoverCard,
    openDocsEmSync,
    closeDocsEmSync,
    checkDocsEmStatus,
    updateDocsEmOption,
    openBulkUsersModal,
    runBulkUsers,
    openQuickAssoc,
    submitQuickAssoc,
    openOverlay,
    closeOverlay,
});

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.overlay').forEach(o => o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); }));
    document.getElementById('profileCard').addEventListener('mouseenter', () => clearTimeout(hoverTimer));
    document.getElementById('profileCard').addEventListener('mouseleave', () => hideHoverCard());
    document.getElementById('docsEmForm').addEventListener('submit', function () {
        const btn = document.getElementById('docsEmSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span style="display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;"></span> A sincronizar…';
    });
    boot();
});
