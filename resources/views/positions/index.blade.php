@extends('layouts.app')
@section('title', 'Funcões')
@section('page-title', 'Funcões')

@section('styles')
<style>
.toolbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:20px; }
.toolbar h2 { font-size:1.25rem; font-weight:700; }
.btn-primary { display:inline-flex; align-items:center; gap:7px; background:var(--accent); color:#fff; border:none; padding:9px 20px; border-radius:9px; font-size:.875rem; font-weight:600; cursor:pointer; transition:.15s; }
.btn-primary:hover { background:#4f46e5; }

.card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:.875rem; }
thead th { padding:11px 16px; text-align:left; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted); border-bottom:1px solid var(--border); background:rgba(255,255,255,.02); white-space:nowrap; }
tbody td { padding:13px 16px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover { background:rgba(255,255,255,.025); }

.badge-count { display:inline-flex; align-items:center; justify-content:center; background:rgba(99,102,241,.15); color:var(--accent-light); border-radius:6px; font-size:.74rem; font-weight:700; min-width:28px; height:22px; padding:0 8px; }

.btn-sm { padding:4px 11px; border-radius:7px; font-size:.76rem; font-weight:600; cursor:pointer; border:none; transition:.15s; }
.btn-edit { background:rgba(99,102,241,.15); color:var(--accent-light); }
.btn-edit:hover { background:rgba(99,102,241,.3); }
.btn-del  { background:rgba(239,68,68,.12); color:#ef4444; }
.btn-del:hover { background:rgba(239,68,68,.25); }

.state-row td { text-align:center; padding:48px; color:var(--text-muted); }
.spinner { display:inline-block; width:18px; height:18px; border:2px solid var(--border); border-top-color:var(--accent); border-radius:50%; animation:spin .7s linear infinite; margin-right:8px; vertical-align:middle; }
@keyframes spin { to { transform:rotate(360deg); } }

.toast-wrap { position:fixed; bottom:24px; right:24px; z-index:999; display:flex; flex-direction:column; gap:8px; }
.toast { padding:12px 18px; border-radius:10px; font-size:.86rem; font-weight:500; box-shadow:0 8px 32px rgba(0,0,0,.4); animation:slideIn .25s ease; }
.toast-ok  { background:rgba(34,197,94,.15); color:#22c55e; border:1px solid rgba(34,197,94,.25); }
.toast-err { background:rgba(239,68,68,.15); color:#ef4444; border:1px solid rgba(239,68,68,.25); }
@keyframes slideIn { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none} }

.overlay { display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.65); backdrop-filter:blur(4px); align-items:center; justify-content:center; }
.overlay.open { display:flex; }
.modal { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:26px; width:100%; max-width:460px; box-shadow:0 24px 80px rgba(0,0,0,.5); }
.modal-title { font-size:1.05rem; font-weight:700; margin-bottom:18px; }
.fg { margin-bottom:14px; }
.fg label { display:block; font-size:.78rem; font-weight:600; color:var(--text-muted); margin-bottom:5px; }
.fg input, .fg textarea { width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border); border-radius:9px; padding:9px 12px; color:var(--text-primary); font-size:.875rem; font-family:inherit; }
.fg input:focus, .fg textarea:focus { outline:none; border-color:var(--accent); }
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
    <h2>💼 Funcões</h2>
    <button class="btn-primary" onclick="openCreate()">+ Nova Função</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Função</th>
                    <th>Descrição</th>
                    <th>Funcionários</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="posBody">
                <tr class="state-row"><td colspan="6"><span class="spinner"></span>A carregar...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<!-- Modal Criar/Editar -->
<div class="overlay" id="formOverlay">
<div class="modal">
    <div class="modal-title" id="formTitle">Nova Função</div>
    <form id="posForm" onsubmit="submitForm(event)">
        <div class="fg"><label>Nome da Função *</label><input name="position" required placeholder="Ex: Analista de RH"></div>
        <div class="fg"><label>Descrição</label><textarea name="description" rows="3" placeholder="Descreva as responsabilidades..."></textarea></div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('formOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="submitBtn">Criar Função</button>
        </div>
    </form>
</div>
</div>

<!-- Modal Excluir -->
<div class="overlay" id="delOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem">🗑️</div>
    <div class="modal-title" style="margin-top:10px">Excluir Função</div>
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
    loadTable();
}

async function loadTable() {
    const tbody = document.getElementById('posBody');
    tbody.innerHTML = '<tr class="state-row"><td colspan="6"><span class="spinner"></span>A carregar...</td></tr>';
    try {
        const res = await api('GET', '/positions?per_page=200');
        const rows = res.data ?? [];
        if(!rows.length) { tbody.innerHTML='<tr class="state-row"><td colspan="6">Nenhum cargo encontrado.</td></tr>'; return; }
        tbody.innerHTML = rows.map(p => {
            const count   = employees.filter(e => e.position?.id === p.id).length;
            const created = p.created_at ? new Date(p.created_at).toLocaleDateString('pt-PT') : '—';
            return `<tr>
                <td style="color:var(--text-muted)">${p.id}</td>
                <td style="font-weight:600">${p.position}</td>
                <td style="color:var(--text-muted)">${p.description ?? '—'}</td>
                <td><span class="badge-count">${count}</span></td>
                <td style="color:var(--text-muted)">${created}</td>
                <td style="white-space:nowrap">
                    <button class="btn-sm btn-edit" onclick='openEdit(${JSON.stringify(p)})'>✏️ Editar</button>
                    <button class="btn-sm btn-del"  onclick="openDelete(${p.id},'${p.position.replace(/'/g,"\\'")}')">🗑</button>
                </td>
            </tr>`;
        }).join('');
    } catch(e) {
        tbody.innerHTML='<tr class="state-row"><td colspan="6">⚠️ Erro ao carregar dados.</td></tr>';
    }
}

function openOverlay(id) { document.getElementById(id).classList.add('open'); }
function closeOverlay(id){ document.getElementById(id).classList.remove('open'); }

function openCreate() {
    editId=null;
    document.getElementById('posForm').reset();
    document.getElementById('formTitle').textContent='➕ Nova Função';
    document.getElementById('submitBtn').textContent='Criar Função';
    openOverlay('formOverlay');
}
function openEdit(p) {
    editId=p.id;
    document.getElementById('posForm').reset();
    const form=document.getElementById('posForm');
    form.querySelector('[name="position"]').value   = p.position    ?? '';
    form.querySelector('[name="description"]').value= p.description ?? '';
    document.getElementById('formTitle').textContent='✏️ Editar Função';
    document.getElementById('submitBtn').textContent='Guardar Alterações';
    openOverlay('formOverlay');
}
async function submitForm(e) {
    e.preventDefault();
    const btn=document.getElementById('submitBtn');
    btn.disabled=true; btn.textContent='A guardar...';
    const data={};
    new FormData(document.getElementById('posForm')).forEach((v,k)=>{ if(v!=='') data[k]=v; });
    try {
        if(editId) { await api('PUT',`/positions/${editId}`,data); toast('Função atualizada!','ok'); }
        else       { await api('POST','/positions',data);          toast('Função criada!','ok'); }
        closeOverlay('formOverlay');
        loadTable();
    } catch(err) { toast(err.message??'Erro.','err'); }
    finally { btn.disabled=false; btn.textContent=editId?'Guardar Alterações':'Criar Função'; }
}
function openDelete(id,name) {
    deleteId=id;
    document.getElementById('delMsg').textContent=`Tem certeza que deseja excluir a função «${name}»?`;
    openOverlay('delOverlay');
}
async function confirmDelete() {
    try { await api('DELETE',`/positions/${deleteId}`); toast('Função excluída.','ok'); closeOverlay('delOverlay'); loadTable(); }
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
