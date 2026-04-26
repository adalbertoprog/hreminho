@extends('layouts.app')

@section('title', 'Utilizadores')
@section('page-title', 'Utilizadores')

@section('styles')
<style>
/* ── Filter bar ── */
.filter-bar { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:22px; align-items:center; }
.filter-bar input, .filter-bar select {
    background:var(--bg-card); border:1px solid var(--border);
    color:var(--text-primary); padding:8px 12px; border-radius:8px;
    font-size:.85rem; outline:none; transition:border-color .15s;
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

.badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:600;
}
.badge-admin    { background:rgba(99,102,241,.2);  color:var(--accent-light); }
.badge-hr       { background:rgba(245,158,11,.15); color:var(--warning); }
.badge-employee { background:rgba(34,197,94,.15);  color:var(--success); }

.user-cell { display:flex; align-items:center; gap:10px; }
.user-avatar-sm {
    width:32px; height:32px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,var(--accent),#a78bfa);
    display:flex; align-items:center; justify-content:center;
    font-size:.75rem; font-weight:700; color:#fff;
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
.form-group input, .form-group select, .form-group textarea {
    width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border);
    color:var(--text-primary); padding:9px 12px; border-radius:8px; font-size:.875rem; outline:none;
    transition:border-color .15s; font-family:inherit;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:var(--accent); }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.hint { font-size:.73rem; color:var(--text-muted); margin-top:4px; }

/* ── Toast ── */
.toast-wrap { position:fixed; bottom:24px; right:24px; z-index:999; display:flex; flex-direction:column; gap:8px; }
.toast {
    padding:12px 18px; border-radius:10px; font-size:.85rem; font-weight:500;
    color:#fff; max-width:320px; animation:slideIn .25s ease;
}
.toast.success { background:#16a34a; }
.toast.error   { background:#dc2626; }
@keyframes slideIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

/* ── Empty ── */
.empty-state { padding:60px 20px; text-align:center; color:var(--text-muted); }
.empty-state .icon { font-size:2.5rem; margin-bottom:12px; }

/* ── Confirm modal ── */
.confirm-modal { max-width:400px; }
.confirm-body { padding:24px; text-align:center; }
.confirm-body .icon { font-size:2.5rem; margin-bottom:12px; }
.confirm-body p { color:var(--text-muted); font-size:.9rem; }
.confirm-body strong { color:var(--text-primary); }
</style>
@endsection

@section('content')

{{-- Filter bar --}}
<div class="filter-bar">
    <input type="text" id="searchInput" placeholder="🔍  Pesquisar por nome ou e-mail…" oninput="debounceLoad()">
    <select id="roleFilter" onchange="loadUsers(1)">
        <option value="">Todos os perfis</option>
        <option value="admin">Admin</option>
        <option value="hr">HR</option>
        <option value="employee">Employee</option>
    </select>
    <button class="btn btn-primary" onclick="openCreate()">＋ Novo utilizador</button>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Utilizador</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Criado em</th>
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
            <h3 id="modalTitle">Novo utilizador</h3>
            <button class="modal-close" onclick="closeModal('formModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label>Nome completo *</label>
                    <input type="text" id="f_name" placeholder="Ex: João Silva">
                </div>
            </div>
            <div class="form-group">
                <label>E-mail *</label>
                <input type="email" id="f_email" placeholder="joao@empresa.com">
            </div>
            <div class="form-group">
                <label>Perfil *</label>
                <select id="f_role">
                    <option value="">Selecionar perfil…</option>
                    <option value="admin">Admin</option>
                    <option value="hr">HR</option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label id="passwordLabel">Palavra-passe *</label>
                    <input type="password" id="f_password" placeholder="Mínimo 8 caracteres">
                </div>
                <div class="form-group">
                    <label>Confirmar palavra-passe</label>
                    <input type="password" id="f_password_confirmation" placeholder="Repetir palavra-passe">
                </div>
            </div>
            <p class="hint" id="passwordHint" style="display:none">Deixe em branco para manter a palavra-passe atual.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('formModal')">Cancelar</button>
            <button class="btn btn-primary" onclick="saveUser()">Guardar</button>
        </div>
    </div>
</div>

{{-- Delete Confirm Modal --}}
<div class="modal-bg" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="confirm-body">
            <div class="icon">🗑️</div>
            <h3 style="margin-bottom:8px">Eliminar utilizador?</h3>
            <p>Tens a certeza que queres eliminar <strong id="deleteName"></strong>?<br>Esta ação não pode ser desfeita.</p>
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
const API = '/api/v1/users';
let currentPage = 1;
let editingId   = null;
let deletingId  = null;
let debounceTimer;

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadUsers(1), 350);
}

function params(page) {
    const p = new URLSearchParams({ page, per_page: 15 });
    const s = document.getElementById('searchInput').value.trim();
    const r = document.getElementById('roleFilter').value;
    if (s) p.set('search', s);
    if (r) p.set('role', r);
    return p.toString();
}

async function loadUsers(page = 1) {
    currentPage = page;
    const res  = await fetch(`${API}?${params(page)}`);
    const json = await res.json();
    renderTable(json.data);
    renderPagination(json.meta);
}

function initials(name) {
    return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
}

function roleBadge(role) {
    const map = { admin: ['badge-admin','Admin'], hr: ['badge-hr','HR'], employee: ['badge-employee','Employee'] };
    const [cls, label] = map[role] || ['','–'];
    return `<span class="badge ${cls}">${label}</span>`;
}

function renderTable(rows) {
    const tbody = document.getElementById('tableBody');
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="empty-state"><div class="icon">🔐</div>Nenhum utilizador encontrado.</div></td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(u => `
        <tr>
            <td>
                <div class="user-cell">
                    <div class="user-avatar-sm">${initials(u.name)}</div>
                    <span>${u.name}</span>
                </div>
            </td>
            <td style="color:var(--text-muted)">${u.email}</td>
            <td>${roleBadge(u.role)}</td>
            <td style="color:var(--text-muted); font-size:.8rem">${u.created_at ? u.created_at.slice(0,10) : '–'}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="openEdit(${u.id})">✏️</button>
                <button class="btn btn-danger btn-sm"  onclick="openDelete(${u.id}, '${u.name.replace(/'/g,"\\'")}')">🗑️</button>
            </td>
        </tr>
    `).join('');
}

function renderPagination(meta) {
    const el = document.getElementById('pagination');
    if (!meta || meta.total <= 15) { el.innerHTML = ''; return; }
    const info = `<span class="page-info">Mostrando ${meta.from ?? 0}–${meta.to ?? 0} de ${meta.total}</span>`;
    let btns = '';
    btns += `<button class="page-btn" onclick="loadUsers(${meta.current_page-1})" ${meta.current_page===1?'disabled':''}>‹</button>`;
    for (let p = 1; p <= meta.last_page; p++) {
        if (meta.last_page > 7 && (p > 2 && p < meta.last_page - 1 && Math.abs(p - meta.current_page) > 1)) {
            if (p === 3 || p === meta.last_page - 2) btns += `<span style="color:var(--text-muted);padding:0 4px">…</span>`;
            continue;
        }
        btns += `<button class="page-btn ${p===meta.current_page?'active':''}" onclick="loadUsers(${p})">${p}</button>`;
    }
    btns += `<button class="page-btn" onclick="loadUsers(${meta.current_page+1})" ${meta.current_page===meta.last_page?'disabled':''}>›</button>`;
    el.innerHTML = info + btns;
}

function openCreate() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Novo utilizador';
    document.getElementById('passwordLabel').textContent = 'Palavra-passe *';
    document.getElementById('passwordHint').style.display = 'none';
    ['f_name','f_email','f_password','f_password_confirmation'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('f_role').value = '';
    openModal('formModal');
}

async function openEdit(id) {
    editingId = id;
    document.getElementById('modalTitle').textContent = 'Editar utilizador';
    document.getElementById('passwordLabel').textContent = 'Nova palavra-passe';
    document.getElementById('passwordHint').style.display = 'block';
    document.getElementById('f_password').value = '';
    document.getElementById('f_password_confirmation').value = '';

    const res  = await fetch(`${API}/${id}`);
    const json = await res.json();
    const u    = json.data;
    document.getElementById('f_name').value  = u.name;
    document.getElementById('f_email').value = u.email;
    document.getElementById('f_role').value  = u.role;
    openModal('formModal');
}

async function saveUser() {
    const body = {
        name:                  document.getElementById('f_name').value.trim(),
        email:                 document.getElementById('f_email').value.trim(),
        role:                  document.getElementById('f_role').value,
        password:              document.getElementById('f_password').value,
        password_confirmation: document.getElementById('f_password_confirmation').value,
    };

    const method = editingId ? 'PUT' : 'POST';
    const url    = editingId ? `${API}/${editingId}` : API;

    const res = await fetch(url, {
        method,
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf() },
        body: JSON.stringify(body),
    });
    const json = await res.json();
    if (!res.ok) {
        const msgs = json.errors ? Object.values(json.errors).flat().join(' ') : (json.message || 'Erro desconhecido.');
        return toast(msgs, 'error');
    }
    toast(editingId ? 'Utilizador atualizado.' : 'Utilizador criado.', 'success');
    closeModal('formModal');
    loadUsers(currentPage);
}

function openDelete(id, name) {
    deletingId = id;
    document.getElementById('deleteName').textContent = name;
    openModal('deleteModal');
}

async function confirmDelete() {
    const res = await fetch(`${API}/${deletingId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf() },
    });
    const json = await res.json();
    if (!res.ok) return toast(json.message || 'Erro ao eliminar.', 'error');
    toast('Utilizador eliminado.', 'success');
    closeModal('deleteModal');
    loadUsers(currentPage);
}

/* ── Helpers ── */
function csrf() { return document.querySelector('meta[name="csrf-token"]').getAttribute('content'); }
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function toast(msg, type = 'success') {
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.textContent = msg;
    document.getElementById('toasts').appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

/* ── Close modal on backdrop click ── */
document.querySelectorAll('.modal-bg').forEach(bg => {
    bg.addEventListener('click', e => { if (e.target === bg) bg.classList.remove('open'); });
});

/* ── Init ── */
loadUsers(1);
</script>
@endsection
