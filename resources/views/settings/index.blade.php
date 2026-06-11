@extends('layouts.app')

@section('title', 'Definições do Sistema')
@section('page-title', 'Definições do Sistema')

@section('styles')
<style>
.settings-wrap { max-width: 720px; }
.settings-section {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 12px; padding: 28px 32px; margin-bottom: 24px;
}
.settings-section h2 {
    font-size: 1rem; font-weight: 700; color: var(--text-primary);
    margin: 0 0 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 8px;
}
.setting-row {
    display: grid; grid-template-columns: 1fr 180px; gap: 16px;
    align-items: start; padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,.05);
}
.setting-row:last-child { border-bottom: none; padding-bottom: 0; }
.setting-label { font-size: .88rem; font-weight: 600; color: var(--text-primary); margin-bottom: 3px; }
.setting-desc  { font-size: .78rem; color: var(--text-muted); line-height: 1.4; }
.setting-input {
    background: var(--bg-input, var(--bg-sidebar)); border: 1px solid var(--border);
    color: var(--text-primary); padding: 8px 12px; border-radius: 8px;
    font-size: .88rem; outline: none; width: 100%; transition: border-color .15s;
    text-align: center;
}
.setting-input:focus { border-color: var(--accent); }
.btn {
    padding: 8px 20px; border-radius: 8px; font-size: .88rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 6px;
}
.btn-primary { background: var(--accent); color: #fff; }
.btn-primary:hover { background: var(--accent-light); }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; }
.toast-wrap { position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
.toast {
    padding:10px 18px; border-radius:10px; font-size:.85rem; font-weight:500;
    animation: fadeIn .2s ease; box-shadow:0 4px 20px rgba(0,0,0,.35);
}
.toast.ok  { background:#22c55e; color:#fff; }
.toast.err { background:#ef4444; color:#fff; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
/* ── Permissions ── */
.perm-wrap { max-width: 900px; }
.perm-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.perm-table th {
    padding: 9px 14px; text-align: center; font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px; color: var(--text-muted);
    border-bottom: 2px solid var(--border);
}
.perm-table th.lh { text-align: left; }
.perm-table td { padding: 9px 14px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: middle; }
.perm-table tr:last-child td { border-bottom: none; }
.perm-table tr:hover td { background: rgba(255,255,255,.02); }
.perm-group-hd td {
    background: rgba(99,102,241,.07); font-size: .72rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .8px; color: var(--accent-light);
    padding: 7px 14px;
}
.perm-label { font-weight: 500; color: var(--text-primary); }
.perm-toggle {
    display: flex; align-items: center; justify-content: center;
}
.perm-toggle input[type=checkbox] { display: none; }
.perm-toggle label {
    width: 36px; height: 20px; border-radius: 10px;
    background: rgba(255,255,255,.12); border: 1px solid var(--border);
    cursor: pointer; transition: background .2s, border-color .2s; position: relative;
    display: block;
}
.perm-toggle label::after {
    content: ''; position: absolute; top: 3px; left: 3px;
    width: 12px; height: 12px; border-radius: 50%;
    background: var(--text-muted); transition: transform .2s, background .2s;
}
.perm-toggle input:checked + label { background: var(--accent); border-color: var(--accent); }
.perm-toggle input:checked + label::after { transform: translateX(16px); background: #fff; }
.perm-toggle.fixed label { cursor: not-allowed; opacity: .5; }
.perm-role-hd { color: var(--text-primary); font-weight: 700; }
.perm-save-bar { display: flex; align-items: center; gap: 12px; justify-content: flex-end; margin-top: 20px; }
.perm-dirty-msg { font-size: .82rem; color: var(--warning, #f59e0b); display: none; }
/* Holidays */
.holiday-table { width:100%; border-collapse:collapse; font-size:.85rem; margin-top:16px; }
.holiday-table th { padding:8px 12px; text-align:left; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--text-muted); border-bottom:1px solid var(--border); }
.holiday-table td { padding:9px 12px; border-bottom:1px solid rgba(255,255,255,.04); color:var(--text-primary); }
.holiday-table tr:last-child td { border-bottom:none; }
.holiday-table tr:hover td { background:rgba(255,255,255,.02); }
.btn-sm { padding:4px 10px; border-radius:6px; font-size:.76rem; font-weight:600; cursor:pointer; border:1px solid; transition:.15s; }
.btn-sm-edit { background:rgba(99,102,241,.12); border-color:rgba(99,102,241,.25); color:var(--accent-light); }
.btn-sm-edit:hover { background:rgba(99,102,241,.25); }
.btn-sm-del  { background:rgba(239,68,68,.1); border-color:rgba(239,68,68,.2); color:#ef4444; }
.btn-sm-del:hover { background:rgba(239,68,68,.22); }
.badge-nat   { display:inline-block; padding:2px 8px; border-radius:5px; font-size:.72rem; font-weight:700; background:rgba(99,102,241,.15); color:#818cf8; }
.badge-loc   { display:inline-block; padding:2px 8px; border-radius:5px; font-size:.72rem; font-weight:700; background:rgba(245,158,11,.15); color:#f59e0b; }
.badge-rec   { display:inline-block; padding:2px 8px; border-radius:5px; font-size:.72rem; font-weight:700; background:rgba(34,197,94,.12); color:#22c55e; }
.hol-form { display:grid; grid-template-columns:1fr 1fr 1fr auto auto; gap:10px; align-items:end; margin-bottom:16px; }
@media(max-width:700px){ .hol-form{ grid-template-columns:1fr 1fr; } }
.hol-input { background:var(--bg-input,var(--bg-sidebar)); border:1px solid var(--border); color:var(--text-primary); padding:8px 12px; border-radius:8px; font-size:.85rem; width:100%; outline:none; transition:border-color .15s; }
.hol-input:focus { border-color:var(--accent); }
.hol-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-muted); margin-bottom:4px; }
.yr-filter { display:flex; gap:8px; align-items:center; margin-bottom:12px; }
.overlay { display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.65); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:14px; }
.overlay.open { display:flex; }
.modal-hol { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:28px; width:100%; max-width:480px; box-shadow:0 24px 80px rgba(0,0,0,.5); }
.modal-hol h3 { font-size:1rem; font-weight:700; margin:0 0 20px; }
.modal-hol .fg { margin-bottom:14px; }
.modal-hol label { display:block; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-muted); margin-bottom:5px; }
.modal-hol input, .modal-hol select { width:100%; background:var(--bg-input,var(--bg-sidebar)); border:1px solid var(--border); color:var(--text-primary); padding:8px 12px; border-radius:8px; font-size:.875rem; outline:none; transition:border-color .15s; }
.modal-hol input:focus, .modal-hol select:focus { border-color:var(--accent); }
.modal-foot { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.btn-cancel { padding:8px 18px; border-radius:8px; background:rgba(255,255,255,.06); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; font-size:.875rem; font-weight:600; }
</style>
@endsection

@section('content')
<div class="settings-wrap">

    <div id="attendanceSection" class="settings-section">
        <h2>⏱️ Presenças</h2>
        <div id="attendanceFields">
            <div style="color:var(--text-muted);font-size:.85rem">A carregar...</div>
        </div>
        <div style="margin-top:20px;text-align:right">
            <button class="btn btn-primary" id="saveBtn" onclick="saveSettings()" disabled>💾 Guardar</button>
        </div>
    </div>


    @can('admin-only')
    <div class="settings-section perm-wrap">
        <h2>🔐 Permissões por Perfil</h2>
        <p style="font-size:.82rem;color:var(--text-muted);margin:-10px 0 18px">
            Define o que cada perfil pode fazer. O perfil <strong>Admin</strong> tem sempre acesso total.
            Os perfis <strong>HR</strong> e <strong>Manager</strong> têm um conjunto base que pode ser ajustado aqui.
        </p>
        <div id="permTableWrap">
            <div style="color:var(--text-muted);font-size:.85rem">A carregar permissões...</div>
        </div>
        <div class="perm-save-bar">
            <span class="perm-dirty-msg" id="permDirtyMsg">⚠️ Alterações por guardar</span>
            <button class="btn btn-primary" id="permSaveBtn" onclick="savePermissions()" disabled>💾 Guardar Permissões</button>
        </div>
    </div>
    @endcan

    <div class="settings-section" style="max-width:900px">
        <h2>📅 Feriados</h2>

        <div class="yr-filter">
            <label style="font-size:.78rem;font-weight:600;color:var(--text-muted)">Ano:</label>
            <select class="hol-input" id="holYear" style="width:110px" onchange="loadHolidays()">
                <option value="">Todos</option>
            </select>
            <button class="btn btn-primary" onclick="openHolModal()">+ Adicionar Feriado</button>
        </div>

        <div id="holTableWrap">
            <div style="color:var(--text-muted);font-size:.85rem">A carregar...</div>
        </div>
    </div>

</div>

{{-- Modal feriado --}}
<div class="overlay" id="holOverlay">
<div class="modal-hol">
    <h3 id="holModalTitle">➕ Novo Feriado</h3>
    <div class="fg">
        <label>Nome *</label>
        <input id="holName" type="text" placeholder="Ex: Dia da Liberdade" maxlength="100">
    </div>
    <div class="fg">
        <label>Data *</label>
        <input id="holDate" type="date">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div class="fg">
            <label>Tipo</label>
            <select id="holType">
                <option value="national">Nacional</option>
                <option value="local">Local</option>
            </select>
        </div>
        <div class="fg" style="display:flex;flex-direction:column;justify-content:flex-end">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;text-transform:none;letter-spacing:0;font-size:.85rem;font-weight:500;color:var(--text-primary)">
                <input type="checkbox" id="holRepeats" style="width:auto;margin:0"> Repete anualmente
            </label>
        </div>
    </div>
    <div class="modal-foot">
        <button class="btn-cancel" onclick="closeHolModal()">Cancelar</button>
        <button class="btn btn-primary" id="holSaveBtn" onclick="saveHoliday()">Guardar</button>
    </div>
</div>
</div>

<div class="toast-wrap" id="toastWrap"></div>
@endsection

@section('scripts')
<script>
const LABELS = {
    attendance_default_check_in:       { label: 'Hora de entrada prevista',      desc: 'Hora padrão de entrada dos funcionários.', type: 'time' },
    attendance_late_tolerance_minutes: { label: 'Tolerância de atraso (minutos)', desc: 'Minutos de tolerância após a hora de entrada antes de marcar atraso.', type: 'number' },
    attendance_default_check_out:      { label: 'Hora de saída prevista',         desc: 'Hora padrão de saída dos funcionários.', type: 'time' },
    attendance_lunch_duration_minutes: { label: 'Duração do almoço (minutos)',    desc: 'Duração padrão do intervalo de almoço.', type: 'number' },
};

let currentSettings = {};

async function apiFetch(method, url, body) {
    const opts = {
        method,
        credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    };
    if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
    const r = await fetch('/api/v1' + url, opts);
    if (!r.ok) { const e = await r.json().catch(() => ({})); throw new Error(e.message || `Erro ${r.status}`); }
    return r.status === 204 ? null : r.json();
}

function toast(msg, type = 'ok') {
    const el = document.createElement('div');
    el.className = `toast ${type}`; el.textContent = msg;
    document.getElementById('toastWrap').appendChild(el);
    setTimeout(() => el.remove(), 3000);
}

function renderAttendanceFields(group) {
    const container = document.getElementById('attendanceFields');
    container.innerHTML = '';
    group.forEach(s => {
        const meta = LABELS[s.key] || {};
        const inputType = meta.type || 'text';
        const row = document.createElement('div');
        row.className = 'setting-row';
        row.innerHTML = `
            <div>
                <div class="setting-label">${s.label}</div>
                ${s.description ? `<div class="setting-desc">${s.description}</div>` : ''}
            </div>
            <input class="setting-input" type="${inputType}" data-key="${s.key}" value="${s.value ?? ''}"
                   ${inputType === 'number' ? 'min="0" max="240" step="1"' : ''}>
        `;
        container.appendChild(row);
        currentSettings[s.key] = s.value;
    });
    document.getElementById('saveBtn').disabled = false;
}

async function loadSettings() {
    try {
        const res = await apiFetch('GET', '/settings');
        const attendance = res.data?.attendance;
        if (attendance) renderAttendanceFields(attendance);
    } catch (e) {
        toast(e.message || 'Erro ao carregar configurações.', 'err');
    }
}

async function saveSettings() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true; btn.textContent = 'A guardar...';
    const inputs = document.querySelectorAll('.setting-input[data-key]');
    const settings = Array.from(inputs).map(el => ({ key: el.dataset.key, value: el.value }));
    try {
        await apiFetch('PUT', '/settings', { settings });
        toast('Configurações guardadas com sucesso!', 'ok');
    } catch (e) {
        toast(e.message || 'Erro ao guardar.', 'err');
    } finally {
        btn.disabled = false; btn.textContent = '💾 Guardar';
    }
}

loadSettings();

/* ── Holidays ──────────────────────────────────────────── */
let holEditId = null;
const typeLabel = {national:'Nacional', local:'Local'};

function populateYearFilter() {
    const sel = document.getElementById('holYear');
    const cur = new Date().getFullYear();
    for (let y = cur + 1; y >= cur - 3; y--) {
        const opt = document.createElement('option');
        opt.value = y; opt.textContent = y;
        if (y === cur) opt.selected = true;
        sel.appendChild(opt);
    }
}

async function loadHolidays() {
    const yr = document.getElementById('holYear').value;
    const wrap = document.getElementById('holTableWrap');
    wrap.innerHTML = '<div style="color:var(--text-muted);font-size:.85rem">A carregar...</div>';
    try {
        const res = await apiFetch('GET', '/holidays' + (yr ? `?year=${yr}` : ''));
        const rows = res.data ?? [];
        if (!rows.length) { wrap.innerHTML = '<p style="color:var(--text-muted);font-size:.85rem;margin-top:8px">Nenhum feriado encontrado.</p>'; return; }
        wrap.innerHTML = `<table class="holiday-table">
            <thead><tr>
                <th>Nome</th><th>Data</th><th>Tipo</th><th>Recorrente</th><th></th>
            </tr></thead>
            <tbody>${rows.map(h => `<tr>
                <td style="font-weight:500">${h.name}</td>
                <td style="color:var(--text-muted)">${h.date_formatted}</td>
                <td><span class="${h.type==='national'?'badge-nat':'badge-loc'}">${typeLabel[h.type]??h.type}</span></td>
                <td>${h.repeats_yearly ? '<span class="badge-rec">Sim</span>' : '<span style="color:var(--text-muted);font-size:.78rem">Não</span>'}</td>
                <td style="white-space:nowrap;text-align:right">
                    <button class="btn-sm btn-sm-edit" onclick='openHolModal(${JSON.stringify(h)})'>✏️</button>
                    <button class="btn-sm btn-sm-del"  onclick="deleteHoliday(${h.id})">🗑</button>
                </td>
            </tr>`).join('')}</tbody>
        </table>`;
    } catch(e) { toast(e.message || 'Erro ao carregar feriados.', 'err'); }
}

function openHolModal(h = null) {
    holEditId = h ? h.id : null;
    document.getElementById('holModalTitle').textContent = h ? '✏️ Editar Feriado' : '➕ Novo Feriado';
    document.getElementById('holName').value    = h?.name ?? '';
    document.getElementById('holDate').value    = h?.date ?? '';
    document.getElementById('holType').value    = h?.type ?? 'national';
    document.getElementById('holRepeats').checked = h ? !!h.repeats_yearly : true;
    document.getElementById('holOverlay').classList.add('open');
    setTimeout(() => document.getElementById('holName').focus(), 80);
}

function closeHolModal() {
    document.getElementById('holOverlay').classList.remove('open');
}

async function saveHoliday() {
    const name = document.getElementById('holName').value.trim();
    const date = document.getElementById('holDate').value;
    if (!name || !date) { toast('Preencha o nome e a data.', 'err'); return; }
    const btn = document.getElementById('holSaveBtn');
    btn.disabled = true;
    const body = {
        name,
        date,
        type:           document.getElementById('holType').value,
        repeats_yearly: document.getElementById('holRepeats').checked,
    };
    try {
        if (holEditId) await apiFetch('PUT',  `/holidays/${holEditId}`, body);
        else           await apiFetch('POST', '/holidays', body);
        toast(holEditId ? 'Feriado actualizado!' : 'Feriado adicionado!', 'ok');
        closeHolModal();
        loadHolidays();
    } catch(e) { toast(e.message || 'Erro ao guardar.', 'err'); }
    finally { btn.disabled = false; }
}

async function deleteHoliday(id) {
    if (!confirm('Eliminar este feriado?')) return;
    try {
        await apiFetch('DELETE', `/holidays/${id}`);
        toast('Feriado eliminado.', 'ok');
        loadHolidays();
    } catch(e) { toast(e.message || 'Erro ao eliminar.', 'err'); }
}

document.getElementById('holOverlay').addEventListener('click', e => {
    if (e.target === document.getElementById('holOverlay')) closeHolModal();
});

populateYearFilter();
loadHolidays();

// ── Permissões ────────────────────────────────────────────────────────────
@can('admin-only')
const GROUP_LABELS = {
    employees:   '👥 Funcionários',
    attendances: '📅 Presenças',
    leaves:      '🏖️ Licenças',
    projects:    '🏗️ Obras / Equipas / Viaturas',
    reports:     '📊 Relatórios',
    trainings:   '🎓 Formações',
};
const ROLE_LABELS = { hr: 'HR', manager: 'Manager' };
let permMatrix = {};
let permDirty  = false;

async function loadPermissions() {
    try {
        const r = await fetch('/settings/permissions', {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });
        permMatrix = await r.json();
        renderPermTable();
    } catch(e) {
        document.getElementById('permTableWrap').innerHTML =
            '<p style="color:var(--text-muted);font-size:.85rem">Erro ao carregar permissões.</p>';
    }
}

function renderPermTable() {
    const wrap = document.getElementById('permTableWrap');
    if (!wrap) return;

    // Agrupar permissões por grupo
    const groups = {};
    Object.entries(permMatrix).forEach(([key, def]) => {
        if (!groups[def.group]) groups[def.group] = [];
        groups[def.group].push({ key, ...def });
    });

    let html = '<table class="perm-table">';
    html += '<thead><tr>';
    html += '<th class="lh" style="width:50%">Permissão</th>';
    ['hr', 'manager'].forEach(role => {
        html += `<th class="perm-role-hd">${ROLE_LABELS[role]}</th>`;
    });
    html += '</tr></thead><tbody>';

    Object.entries(groups).forEach(([group, perms]) => {
        html += `<tr class="perm-group-hd"><td colspan="3">${GROUP_LABELS[group] || group}</td></tr>`;
        perms.forEach(({ key, label, values, configurable }) => {
            html += '<tr>';
            html += `<td class="perm-label">${label}</td>`;
            ['hr', 'manager'].forEach(role => {
                const checked    = values[role] ? 'checked' : '';
                const isConfig   = configurable.includes(role);
                const fixedClass = isConfig ? '' : ' fixed';
                const disabled   = isConfig ? '' : 'disabled';
                const uid        = `perm_${role}_${key}`;
                html += `<td>
                    <div class="perm-toggle${fixedClass}">
                        <input type="checkbox" id="${uid}" data-role="${role}" data-perm="${key}"
                               ${checked} ${disabled} onchange="permChanged()">
                        <label for="${uid}" title="${isConfig ? 'Clica para alterar' : 'Não configurável'}"></label>
                    </div>
                </td>`;
            });
            html += '</tr>';
        });
    });

    html += '</tbody></table>';
    wrap.innerHTML = html;
}

function permChanged() {
    permDirty = true;
    document.getElementById('permSaveBtn').disabled    = false;
    document.getElementById('permDirtyMsg').style.display = 'inline';
}

async function savePermissions() {
    const btn = document.getElementById('permSaveBtn');
    btn.disabled = true;
    btn.textContent = '⏳ A guardar...';

    const permissions = {};
    document.querySelectorAll('#permTableWrap input[type=checkbox][data-role]').forEach(el => {
        if (!el.disabled) {
            permissions[`${el.dataset.role}.${el.dataset.perm}`] = el.checked;
        }
    });

    try {
        const r = await fetch('/settings/permissions', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ permissions }),
        });
        const json = await r.json();
        if (!r.ok) throw new Error(json.message || 'Erro');
        permDirty = false;
        document.getElementById('permDirtyMsg').style.display = 'none';
        toast(json.message || 'Permissões guardadas!', 'ok');
    } catch(e) {
        toast('Erro ao guardar: ' + e.message, 'err');
    } finally {
        btn.disabled    = false;
        btn.textContent = '💾 Guardar Permissões';
    }
}

loadPermissions();
@endcan
</script>
@endsection
