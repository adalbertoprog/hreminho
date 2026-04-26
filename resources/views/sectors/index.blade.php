@extends('layouts.app')

@section('title', 'Setores')
@section('page-title', 'Setores')

@section('styles')
<style>
/* ── Filter bar ── */
.filter-bar { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:22px; align-items:center; }
.filter-bar input, .filter-bar select {
    background:var(--bg-card); border:1px solid var(--border);
    color:var(--text-primary); padding:8px 12px; border-radius:8px;
    font-size:.85rem; outline:none; transition:border-color .15s; font-family:inherit;
}
.filter-bar input:focus, .filter-bar select:focus { border-color:var(--accent); }
.filter-bar input { flex:1; min-width:200px; }
.btn {
    padding:8px 18px; border-radius:8px; font-size:.85rem; font-weight:600;
    border:none; cursor:pointer; transition:all .15s; display:inline-flex; align-items:center; gap:6px;
}
.btn-primary { background:var(--accent); color:#fff; }
.btn-primary:hover { background:var(--accent-light); }
.btn-sm { padding:5px 11px; font-size:.78rem; }
.btn-danger  { background:rgba(239,68,68,.15);  color:var(--danger); }
.btn-danger:hover  { background:rgba(239,68,68,.3); }
.btn-warning { background:rgba(245,158,11,.15); color:var(--warning); }
.btn-warning:hover { background:rgba(245,158,11,.3); }
.btn-ghost { background:rgba(255,255,255,.06); color:var(--text-muted); }
.btn-ghost:hover { background:rgba(255,255,255,.1); color:var(--text-primary); }

/* ── Table ── */
.card { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:.875rem; }
thead th {
    background:rgba(255,255,255,.03); color:var(--text-muted);
    font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px;
    padding:12px 16px; text-align:left; white-space:nowrap;
    border-bottom:1px solid var(--border);
}
tbody td { padding:13px 16px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover td { background:rgba(255,255,255,.025); }

.badge-dept {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:600;
    background:rgba(99,102,241,.2); color:var(--accent-light);
}
.count-pill {
    display:inline-flex; align-items:center; justify-content:center;
    min-width:28px; padding:2px 8px; border-radius:20px; font-size:.72rem; font-weight:700;
    background:rgba(255,255,255,.07); color:var(--text-muted);
}

/* ── Pagination ── */
.pagination { display:flex; gap:6px; align-items:center; padding:14px 16px; justify-content:flex-end; flex-wrap:wrap; }
.page-btn {
    background:var(--bg-card); border:1px solid var(--border); color:var(--text-muted);
    padding:5px 11px; border-radius:6px; font-size:.8rem; cursor:pointer; transition:all .15s;
}
.page-btn:hover:not(:disabled) { border-color:var(--accent); color:var(--accent-light); }
.page-btn.active { background:var(--accent); border-color:var(--accent); color:#fff; }
.page-btn:disabled { opacity:.4; cursor:not-allowed; }
.page-info { font-size:.8rem; color:var(--text-muted); margin-right:auto; padding:0 4px; }

/* ── Modal ── */
.modal-bg {
    position:fixed; inset:0; background:rgba(0,0,0,.7); z-index:200;
    display:none; align-items:center; justify-content:center; padding:20px;
}
.modal-bg.open { display:flex; }
.modal {
    background:var(--bg-card); border:1px solid var(--border); border-radius:14px;
    width:100%; max-width:520px; max-height:90vh; overflow-y:auto;
}
.modal-header {
    padding:20px 24px 16px; border-bottom:1px solid var(--border);
    display:flex; align-items:center; justify-content:space-between;
}
.modal-header h3 { font-size:1rem; font-weight:700; }
.modal-close { background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:1.2rem; padding:4px; }
.modal-close:hover { color:var(--text-primary); }
.modal-body { padding:20px 24px; }
.modal-footer { padding:16px 24px; border-top:1px solid var(--border); display:flex; gap:10px; justify-content:flex-end; }

.form-group { margin-bottom:16px; }
.form-group label { display:block; font-size:.8rem; font-weight:600; color:var(--text-muted); margin-bottom:6px; }
.form-group input, .form-group select {
    width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border);
    color:var(--text-primary); padding:9px 12px; border-radius:8px; font-size:.875rem; outline:none;
    transition:border-color .15s; font-family:inherit;
}
.form-group input:focus, .form-group select:focus { border-color:var(--accent); }
.form-group select option { background:var(--bg-card); }

/* ── Confirm modal ── */
.confirm-modal { max-width:400px; }
.confirm-body { padding:24px; text-align:center; }
.confirm-body .icon { font-size:2.5rem; margin-bottom:12px; }
.confirm-body p { color:var(--text-muted); font-size:.9rem; }
.confirm-body strong { color:var(--text-primary); }

/* ── Empty / Loading ── */
.empty-state { padding:60px 20px; text-align:center; color:var(--text-muted); }
.empty-state .icon { font-size:2.5rem; margin-bottom:12px; }

/* ── Toast ── */
.toast-wrap { position:fixed; bottom:24px; right:24px; z-index:999; display:flex; flex-direction:column; gap:8px; }
.toast { padding:12px 18px; border-radius:10px; font-size:.85rem; font-weight:500; color:#fff; max-width:320px; animation:slideIn .25s ease; }
.toast.success { background:#16a34a; }
.toast.error   { background:#dc2626; }
@keyframes slideIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
</style>
@endsection

@section('content')

{{-- Filter bar --}}
<div class="filter-bar">
    <input type="text" id="searchInput" placeholder="🔍  Pesquisar setor…" oninput="debounceLoad()">
    <select id="deptFilter" onchange="loadSectors(1)">
        <option value="">Todos os departamentos</option>
    </select>
    <button class="btn btn-primary" onclick="openCreate()">＋ Novo setor</button>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nome do setor</th>
                    <th>Departamento</th>
                    <th>Responsável</th>
                    <th>Funcionários</th>
                    <th style="width:120px">Ações</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr><td colspan="5"><div class="empty-state"><div class="icon">⏳</div>A carregar…</div></td></tr>
            </tbody>
        </table>
    </div>
    <div class="pagination" id="pagination"></div>
</div>

{{-- Create / Edit Modal --}}
<div class="modal-bg" id="formModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Novo setor</h3>
            <button class="modal-close" onclick="closeModal('formModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Nome do setor *</label>
                <input type="text" id="f_sector" placeholder="Ex: Contabilidade">
            </div>
            <div class="form-group">
                <label>Departamento *</label>
                <select id="f_department_id">
                    <option value="">Selecionar departamento…</option>
                </select>
            </div>
            <div class="form-group">
                <label>Responsável</label>
                <select id="f_manager_id">
                    <option value="">Sem responsável</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('formModal')">Cancelar</button>
            <button class="btn btn-primary" onclick="saveSector()">Guardar</button>
        </div>
    </div>
</div>

{{-- Delete Confirm Modal --}}
<div class="modal-bg" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="confirm-body">
            <div class="icon">🗑️</div>
            <h3 style="margin-bottom:8px">Eliminar setor?</h3>
            <p>Tens a certeza que queres eliminar <strong id="deleteName"></strong>?<br>Apenas é possível se não tiver funcionários.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('deleteModal')">Cancelar</button>
            <button class="btn btn-danger" onclick="confirmDelete()">Eliminar</button>
        </div>
    </div>
</div>

{{-- Toast container --}}
<div class="toast-wrap" id="toasts"></div>

@endsection

@section('scripts')
<script>
const API  = '/api/v1';
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let currentPage = 1;
let editingId   = null;
let deletingId  = null;
let debounceTimer;
let departments = [];
let employees   = [];

/* ── Boot ── */
async function boot() {
    const [dRes, eRes] = await Promise.all([
        fetch(`${API}/departments?per_page=200`, { headers:{ Accept:'application/json' } }),
        fetch(`${API}/employees?per_page=500&status=active`, { headers:{ Accept:'application/json' } }),
    ]);
    const dJson = await dRes.json();
    const eJson = await eRes.json();
    departments = dJson.data ?? [];
    employees   = eJson.data ?? [];

    // Populate filter dropdown
    const deptFilter = document.getElementById('deptFilter');
    departments.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.department;
        deptFilter.appendChild(opt);
    });

    // Populate modal department select
    const deptSel = document.getElementById('f_department_id');
    departments.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.department;
        deptSel.appendChild(opt);
    });

    // Populate modal manager select
    const mgrSel = document.getElementById('f_manager_id');
    employees.forEach(e => {
        const opt = document.createElement('option');
        opt.value = e.id;
        opt.textContent = e.full_name;
        mgrSel.appendChild(opt);
    });

    loadSectors(1);
}

/* ── Load ── */
function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadSectors(1), 350);
}

async function loadSectors(page = 1) {
    currentPage = page;
    const p = new URLSearchParams({ page, per_page: 15 });
    const s = document.getElementById('searchInput').value.trim();
    const d = document.getElementById('deptFilter').value;
    if (s) p.set('search', s);
    if (d) p.set('department_id', d);

    const res  = await fetch(`${API}/sectors?${p}`, { headers:{ Accept:'application/json' } });
    const json = await res.json();
    renderTable(json.data ?? []);
    renderPagination(json.meta);
}

/* ── Render table ── */
function renderTable(rows) {
    const tbody = document.getElementById('tableBody');
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="empty-state"><div class="icon">🏭</div>Nenhum setor encontrado.</div></td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(s => `
        <tr>
            <td style="font-weight:600">${s.sector}</td>
            <td>${s.department ? `<span class="badge-dept">🏢 ${s.department.department}</span>` : '—'}</td>
            <td style="color:var(--text-muted)">${s.manager ? s.manager.full_name : '<span style="color:var(--border)">—</span>'}</td>
            <td><span class="count-pill">👤 ${s.employees_count}</span></td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="openEdit(${s.id})">✏️</button>
                <button class="btn btn-danger btn-sm"  onclick="openDelete(${s.id}, '${s.sector.replace(/'/g,"\\'")}')">🗑️</button>
            </td>
        </tr>
    `).join('');
}

/* ── Pagination ── */
function renderPagination(meta) {
    const el = document.getElementById('pagination');
    if (!meta || meta.total <= 15) { el.innerHTML = ''; return; }
    const info = `<span class="page-info">Mostrando ${meta.from ?? 0}–${meta.to ?? 0} de ${meta.total}</span>`;
    let btns = '';
    btns += `<button class="page-btn" onclick="loadSectors(${meta.current_page-1})" ${meta.current_page===1?'disabled':''}>‹</button>`;
    for (let p = 1; p <= meta.last_page; p++) {
        if (meta.last_page > 7 && Math.abs(p - meta.current_page) > 1 && p !== 1 && p !== meta.last_page) {
            if (p === meta.current_page - 2 || p === meta.current_page + 2) btns += `<span style="color:var(--text-muted);padding:0 4px">…</span>`;
            continue;
        }
        btns += `<button class="page-btn ${p===meta.current_page?'active':''}" onclick="loadSectors(${p})">${p}</button>`;
    }
    btns += `<button class="page-btn" onclick="loadSectors(${meta.current_page+1})" ${meta.current_page===meta.last_page?'disabled':''}>›</button>`;
    el.innerHTML = info + btns;
}

/* ── Create ── */
function openCreate() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Novo setor';
    document.getElementById('f_sector').value        = '';
    document.getElementById('f_department_id').value = '';
    document.getElementById('f_manager_id').value    = '';
    openModal('formModal');
}

/* ── Edit ── */
async function openEdit(id) {
    editingId = id;
    document.getElementById('modalTitle').textContent = 'Editar setor';

    const res  = await fetch(`${API}/sectors/${id}`, { headers:{ Accept:'application/json' } });
    const json = await res.json();
    const s    = json.data;

    document.getElementById('f_sector').value        = s.sector;
    document.getElementById('f_department_id').value = s.department_id ?? '';
    document.getElementById('f_manager_id').value    = s.manager_id    ?? '';
    openModal('formModal');
}

/* ── Save ── */
async function saveSector() {
    const body = {
        sector:        document.getElementById('f_sector').value.trim(),
        department_id: document.getElementById('f_department_id').value,
        manager_id:    document.getElementById('f_manager_id').value || null,
    };

    const method = editingId ? 'PUT' : 'POST';
    const url    = editingId ? `${API}/sectors/${editingId}` : `${API}/sectors`;

    const res = await fetch(url, {
        method,
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, Accept:'application/json' },
        body: JSON.stringify(body),
    });
    const json = await res.json();

    if (!res.ok) {
        const msgs = json.errors ? Object.values(json.errors).flat().join(' ') : (json.message || 'Erro desconhecido.');
        return toast(msgs, 'error');
    }

    toast(editingId ? 'Setor atualizado.' : 'Setor criado.', 'success');
    closeModal('formModal');
    loadSectors(currentPage);
}

/* ── Delete ── */
function openDelete(id, name) {
    deletingId = id;
    document.getElementById('deleteName').textContent = name;
    openModal('deleteModal');
}

async function confirmDelete() {
    const res  = await fetch(`${API}/sectors/${deletingId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN':CSRF, Accept:'application/json' },
    });
    const json = await res.json();

    if (!res.ok) return toast(json.message || 'Erro ao eliminar.', 'error');

    toast('Setor eliminado.', 'success');
    closeModal('deleteModal');
    loadSectors(currentPage);
}

/* ── Helpers ── */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function toast(msg, type = 'success') {
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.textContent = msg;
    document.getElementById('toasts').appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

document.querySelectorAll('.modal-bg').forEach(bg => {
    bg.addEventListener('click', e => { if (e.target === bg) bg.classList.remove('open'); });
});

boot();
</script>
@endsection
