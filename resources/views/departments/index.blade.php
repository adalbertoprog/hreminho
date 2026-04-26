@extends('layouts.app')
@section('title', 'Departamentos')
@section('page-title', 'Departamentos')

@section('styles')
<style>
.toolbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:20px; }
.toolbar h2 { font-size:1.25rem; font-weight:700; }
.btn-primary { display:inline-flex; align-items:center; gap:7px; background:var(--accent); color:#fff; border:none; padding:9px 20px; border-radius:9px; font-size:.875rem; font-weight:600; cursor:pointer; transition:.15s; }
.btn-primary:hover { background:#4f46e5; }

/* Table */
.card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:.875rem; }
thead th { padding:11px 16px; text-align:left; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted); border-bottom:1px solid var(--border); background:rgba(255,255,255,.02); white-space:nowrap; }
tbody td { padding:13px 16px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover { background:rgba(255,255,255,.025); }

.badge-count { display:inline-flex; align-items:center; justify-content:center; background:rgba(99,102,241,.15); color:var(--accent-light); border-radius:6px; font-size:.74rem; font-weight:700; min-width:28px; height:22px; padding:0 8px; }
.manager-tag { display:inline-flex; align-items:center; gap:5px; background:rgba(34,197,94,.1); color:#22c55e; border-radius:6px; padding:3px 9px; font-size:.77rem; font-weight:600; }

.btn-sm { padding:4px 11px; border-radius:7px; font-size:.76rem; font-weight:600; cursor:pointer; border:none; transition:.15s; }
.btn-edit { background:rgba(99,102,241,.15); color:var(--accent-light); }
.btn-edit:hover { background:rgba(99,102,241,.3); }
.btn-del  { background:rgba(239,68,68,.12); color:#ef4444; }
.btn-del:hover { background:rgba(239,68,68,.25); }

.state-row td { text-align:center; padding:48px; color:var(--text-muted); }
.spinner { display:inline-block; width:18px; height:18px; border:2px solid var(--border); border-top-color:var(--accent); border-radius:50%; animation:spin .7s linear infinite; margin-right:8px; vertical-align:middle; }
@keyframes spin { to { transform:rotate(360deg); } }

/* Toast */
.toast-wrap { position:fixed; bottom:24px; right:24px; z-index:999; display:flex; flex-direction:column; gap:8px; }
.toast { padding:12px 18px; border-radius:10px; font-size:.86rem; font-weight:500; box-shadow:0 8px 32px rgba(0,0,0,.4); animation:slideIn .25s ease; }
.toast-ok  { background:rgba(34,197,94,.15); color:#22c55e; border:1px solid rgba(34,197,94,.25); }
.toast-err { background:rgba(239,68,68,.15); color:#ef4444; border:1px solid rgba(239,68,68,.25); }
@keyframes slideIn { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none} }

/* Modal */
.overlay { display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.65); backdrop-filter:blur(4px); align-items:center; justify-content:center; }
.overlay.open { display:flex; }
.modal { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:26px; width:100%; max-width:480px; box-shadow:0 24px 80px rgba(0,0,0,.5); }
.modal-title { font-size:1.05rem; font-weight:700; margin-bottom:18px; }
.fg { margin-bottom:14px; }
.fg label { display:block; font-size:.78rem; font-weight:600; color:var(--text-muted); margin-bottom:5px; }
.fg input, .fg select, .fg textarea { width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border); border-radius:9px; padding:9px 12px; color:var(--text-primary); font-size:.875rem; font-family:inherit; }
.fg input:focus, .fg select:focus, .fg textarea:focus { outline:none; border-color:var(--accent); }
.fg select option { background:var(--bg-card); }
.modal-foot { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.btn-cancel { padding:9px 20px; border-radius:9px; background:rgba(255,255,255,.06); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; font-size:.875rem; font-weight:600; }
.btn-cancel:hover { color:var(--text-primary); }
.confirm-modal { max-width:370px; text-align:center; }
.confirm-modal p { color:var(--text-muted); font-size:.9rem; margin:8px 0 18px; }
.btn-danger { padding:9px 20px; border-radius:9px; background:#ef4444; color:#fff; border:none; cursor:pointer; font-size:.875rem; font-weight:600; }
.btn-danger:hover { background:#dc2626; }
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>🏢 Departamentos</h2>
    <button class="btn-primary" onclick="openCreate()">+ Novo Departamento</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Departamento</th>
                    <th>Descrição</th>
                    <th>Gestor</th>
                    <th>Funcionários</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="deptBody">
                <tr class="state-row"><td colspan="7"><span class="spinner"></span>A carregar...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<!-- Modal Criar/Editar -->
<div class="overlay" id="formOverlay">
<div class="modal">
    <div class="modal-title" id="formTitle">Novo Departamento</div>
    <form id="deptForm" onsubmit="submitForm(event)">
        <div class="fg"><label>Nome do Departamento *</label><input name="department" required placeholder="Ex: Recursos Humanos"></div>
        <div class="fg"><label>Descrição</label><textarea name="description" rows="3" placeholder="Descreva o departamento..."></textarea></div>
        <div class="fg"><label>Gestor / Responsável</label><select name="manager_id" id="managerSel"><option value="">— Nenhum —</option></select></div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('formOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="submitBtn">Criar</button>
        </div>
    </form>
</div>
</div>

<!-- Modal Excluir -->
<div class="overlay" id="delOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem">🗑️</div>
    <div class="modal-title" style="margin-top:10px">Excluir Departamento</div>
    <p id="delMsg"></p>
    <div class="modal-foot" style="justify-content:center">
        <button class="btn-cancel" onclick="closeOverlay('delOverlay')">Cancelar</button>
        <button class="btn-danger" onclick="confirmDelete()">Excluir</button>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
const API  = '/api/v1';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let editId=null, deleteId=null, employees=[];

async function api(method, path, body) {
    const opts = { method, headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'} };
    if(body) opts.body = JSON.stringify(body);
    const r = await fetch(API + path, opts);
    if(!r.ok) { const e = await r.json().catch(()=>({message:'Erro'})); throw e; }
    return r.status===204 ? null : r.json();
}

async function boot() {
    const emp = await api('GET', '/employees?per_page=200').catch(()=>({data:[]}));
    employees = emp.data ?? [];
    const sel = document.getElementById('managerSel');
    employees.forEach(e => {
        const o = document.createElement('option');
        o.value=e.id; o.textContent=`${e.first_name} ${e.last_name}`;
        sel.appendChild(o);
    });
    loadTable();
}

async function loadTable() {
    const tbody = document.getElementById('deptBody');
    tbody.innerHTML = '<tr class="state-row"><td colspan="7"><span class="spinner"></span>A carregar...</td></tr>';
    try {
        const res = await api('GET', '/departments?per_page=200');
        const rows = res.data ?? [];
        if(!rows.length) { tbody.innerHTML='<tr class="state-row"><td colspan="7">Nenhum departamento encontrado.</td></tr>'; return; }
        tbody.innerHTML = rows.map(d => {
            const mgr = d.manager ? `<span class="manager-tag">👤 ${d.manager.full_name}</span>` : '<span style="color:var(--text-muted)">—</span>';
            const created = d.created_at ? new Date(d.created_at).toLocaleDateString('pt-PT') : '—';
            // count employees
            const count = employees.filter(e => e.department?.id === d.id).length;
            return `<tr>
                <td style="color:var(--text-muted)">${d.id}</td>
                <td style="font-weight:600">${d.department}</td>
                <td style="color:var(--text-muted)">${d.description ?? '—'}</td>
                <td>${mgr}</td>
                <td><span class="badge-count">${count}</span></td>
                <td style="color:var(--text-muted)">${created}</td>
                <td style="white-space:nowrap">
                    <button class="btn-sm btn-edit" onclick='openEdit(${JSON.stringify(d)})'>✏️ Editar</button>
                    <button class="btn-sm btn-del"  onclick="openDelete(${d.id},'${d.department.replace(/'/g,"\\'")}')">🗑</button>
                </td>
            </tr>`;
        }).join('');
    } catch(e) {
        tbody.innerHTML='<tr class="state-row"><td colspan="7">⚠️ Erro ao carregar dados.</td></tr>';
    }
}

function openOverlay(id) { document.getElementById(id).classList.add('open'); }
function closeOverlay(id){ document.getElementById(id).classList.remove('open'); }

function openCreate() {
    editId=null;
    document.getElementById('deptForm').reset();
    document.getElementById('formTitle').textContent='➕ Novo Departamento';
    document.getElementById('submitBtn').textContent='Criar Departamento';
    openOverlay('formOverlay');
}
function openEdit(d) {
    editId=d.id;
    document.getElementById('deptForm').reset();
    const form=document.getElementById('deptForm');
    form.querySelector('[name="department"]').value  = d.department  ?? '';
    form.querySelector('[name="description"]').value = d.description ?? '';
    document.getElementById('managerSel').value = d.manager?.id ?? '';
    document.getElementById('formTitle').textContent='✏️ Editar Departamento';
    document.getElementById('submitBtn').textContent='Guardar Alterações';
    openOverlay('formOverlay');
}
async function submitForm(e) {
    e.preventDefault();
    const btn=document.getElementById('submitBtn');
    btn.disabled=true; btn.textContent='A guardar...';
    const data={};
    new FormData(document.getElementById('deptForm')).forEach((v,k)=>{ if(v!=='') data[k]=v; });
    try {
        if(editId) { await api('PUT',`/departments/${editId}`,data); toast('Departamento atualizado!','ok'); }
        else       { await api('POST','/departments',data);          toast('Departamento criado!','ok'); }
        closeOverlay('formOverlay');
        loadTable();
    } catch(err) { toast(err.message??'Erro.','err'); }
    finally { btn.disabled=false; btn.textContent=editId?'Guardar Alterações':'Criar Departamento'; }
}
function openDelete(id,name) {
    deleteId=id;
    document.getElementById('delMsg').textContent=`Tem certeza que deseja excluir «${name}»?`;
    openOverlay('delOverlay');
}
async function confirmDelete() {
    try { await api('DELETE',`/departments/${deleteId}`); toast('Departamento excluído.','ok'); closeOverlay('delOverlay'); loadTable(); }
    catch(err) { toast(err.message??'Erro ao excluir.','err'); }
}
function toast(msg,type='ok') {
    const w=document.getElementById('toastWrap');
    const t=document.createElement('div'); t.className=`toast toast-${type}`; t.textContent=msg;
    w.appendChild(t); setTimeout(()=>t.remove(),3500);
}
document.querySelectorAll('.overlay').forEach(o=>{ o.addEventListener('click',e=>{ if(e.target===o) o.classList.remove('open'); }); });
boot();
</script>
@endsection
