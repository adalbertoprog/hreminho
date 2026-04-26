@extends('layouts.app')
@section('title','Presenças')
@section('page-title','Presenças')

@section('styles')
<style>
.toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px}
.toolbar h2{font-size:1.25rem;font-weight:700}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--accent);color:#fff;border:none;padding:9px 20px;border-radius:9px;font-size:.875rem;font-weight:600;cursor:pointer;transition:.15s}
.btn-primary:hover{background:#4f46e5}
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
.badge-present{background:rgba(34,197,94,.15);color:#22c55e}
.badge-late{background:rgba(245,158,11,.15);color:#f59e0b}
.badge-absent{background:rgba(239,68,68,.12);color:#ef4444}
.badge-holiday{background:rgba(99,102,241,.15);color:var(--accent-light)}
.badge-on_leave{background:rgba(6,182,212,.15);color:#06b6d4}
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
.overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:center;justify-content:center}
.overlay.open{display:flex}
.modal{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:26px;width:100%;max-width:500px;box-shadow:0 24px 80px rgba(0,0,0,.5)}
.modal-title{font-size:1.05rem;font-weight:700;margin-bottom:18px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.full{grid-column:1/-1}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:5px}
.fg input,.fg select{width:100%;background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:9px;padding:9px 12px;color:var(--text-primary);font-size:.875rem;font-family:inherit}
.fg input:focus,.fg select:focus{outline:none;border-color:var(--accent)}
.fg select option{background:var(--bg-card)}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}
.btn-cancel{padding:9px 20px;border-radius:9px;background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-size:.875rem;font-weight:600}
.confirm-modal{max-width:370px;text-align:center}
.confirm-modal p{color:var(--text-muted);font-size:.9rem;margin:8px 0 18px}
.btn-danger{padding:9px 20px;border-radius:9px;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:.875rem;font-weight:600}
.btn-danger:hover{background:#dc2626}
</style>
@endsection

@section('content')
<div class="toolbar">
    <h2>📅 Presenças</h2>
    <button class="btn-primary" onclick="openCreate()">+ Registar Presença</button>
</div>

<div class="filters">
    <input id="fDate"   type="date" class="f-input" style="max-width:170px" placeholder="Data">
    <select id="fEmp"   class="f-input" style="max-width:210px"><option value="">Todos os funcionários</option></select>
    <select id="fStatus" class="f-input" style="max-width:160px">
        <option value="">Todos os status</option>
        <option value="present">Presente</option>
        <option value="late">Atrasado</option>
        <option value="absent">Ausente</option>
        <option value="holiday">Feriado</option>
        <option value="on_leave">De Licença</option>
    </select>
    <button class="btn-filter" onclick="applyFilters()">Filtrar</button>
    <button class="btn-reset"  onclick="resetFilters()">✕ Limpar</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Funcionário</th><th>Data</th><th>Entrada</th><th>Saída</th><th>Status</th><th>Ações</th></tr></thead>
            <tbody id="attBody"><tr class="state-row"><td colspan="6"><span class="spinner"></span>A carregar...</td></tr></tbody>
        </table>
    </div>
    <div class="pag" id="pagBar" style="display:none">
        <span class="pag-info" id="pagInfo"></span>
        <div class="pag-btns" id="pagBtns"></div>
    </div>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<!-- Modal Criar/Editar -->
<div class="overlay" id="formOverlay">
<div class="modal">
    <div class="modal-title" id="formTitle">Registar Presença</div>
    <form id="attForm" onsubmit="submitForm(event)">
        <div class="form-grid">
            <div class="fg full"><label>Funcionário *</label><select name="employee_id" id="empSel" required><option value="">— Selecionar —</option></select></div>
            <div class="fg"><label>Data *</label><input name="date" type="date" required></div>
            <div class="fg"><label>Status *</label>
                <select name="status" required>
                    <option value="present">Presente</option>
                    <option value="late">Atrasado</option>
                    <option value="absent">Ausente</option>
                    <option value="holiday">Feriado</option>
                    <option value="on_leave">De Licença</option>
                </select>
            </div>
            <div class="fg"><label>Hora de Entrada</label><input name="check_in" type="time"></div>
            <div class="fg"><label>Hora de Saída</label><input name="check_out" type="time"></div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeOverlay('formOverlay')">Cancelar</button>
            <button type="submit" class="btn-primary" id="submitBtn">Registar</button>
        </div>
    </form>
</div>
</div>

<!-- Modal Excluir -->
<div class="overlay" id="delOverlay">
<div class="modal confirm-modal">
    <div style="font-size:2.5rem">🗑️</div>
    <div class="modal-title" style="margin-top:10px">Excluir Registo</div>
    <p id="delMsg">Tem certeza?</p>
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
let editId=null, deleteId=null, employees=[], page=1, filters={};

const statusLabel = {present:'Presente',late:'Atrasado',absent:'Ausente',holiday:'Feriado',on_leave:'Licença'};
const statusClass  = {present:'badge-present',late:'badge-late',absent:'badge-absent',holiday:'badge-holiday',on_leave:'badge-on_leave'};

async function apiFetch(method, path, body) {
    const opts={method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}};
    if(body) opts.body=JSON.stringify(body);
    const r=await fetch(API+path,opts);
    if(!r.ok){const e=await r.json().catch(()=>({message:'Erro'}));throw e;}
    return r.status===204?null:r.json();
}

async function boot() {
    const emp = await apiFetch('GET','/employees?per_page=200').catch(()=>({data:[]}));
    employees = emp.data??[];
    const sel = document.getElementById('empSel');
    const fEmp = document.getElementById('fEmp');
    employees.forEach(e=>{
        sel.innerHTML  += `<option value="${e.id}">${e.full_name} (${e.code})</option>`;
        fEmp.innerHTML += `<option value="${e.id}">${e.full_name}</option>`;
    });
    loadTable();
}

async function loadTable() {
    const tbody=document.getElementById('attBody');
    tbody.innerHTML='<tr class="state-row"><td colspan="6"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('pagBar').style.display='none';
    const q=new URLSearchParams({page,per_page:15,...filters});
    try {
        const res=await fetch(`${API}/attendances?${q}`,{headers:{Accept:'application/json'}});
        const json=await res.json();
        renderTable(json.data??[]);
        renderPag(json.meta);
    } catch(e){ tbody.innerHTML='<tr class="state-row"><td colspan="6">⚠️ Erro ao carregar.</td></tr>'; }
}

function renderTable(rows) {
    const tbody=document.getElementById('attBody');
    if(!rows.length){tbody.innerHTML='<tr class="state-row"><td colspan="6">Nenhum registo encontrado.</td></tr>';return;}
    tbody.innerHTML=rows.map(a=>{
        const d=a.date?new Date(a.date+'T00:00:00').toLocaleDateString('pt-PT'):'—';
        return `<tr>
            <td style="font-weight:600">${a.employee?.full_name??'—'}<br><span style="font-size:.74rem;color:var(--text-muted)">${a.employee?.code??''}</span></td>
            <td style="color:var(--text-muted)">${d}</td>
            <td>${a.check_in?a.check_in.slice(0,5):'—'}</td>
            <td>${a.check_out?a.check_out.slice(0,5):'—'}</td>
            <td><span class="badge ${statusClass[a.status]??''}">${statusLabel[a.status]??a.status}</span></td>
            <td style="white-space:nowrap">
                <button class="btn-sm btn-edit" onclick='openEdit(${JSON.stringify(a)})'>✏️ Editar</button>
                <button class="btn-sm btn-del"  onclick="openDelete(${a.id})">🗑</button>
            </td>
        </tr>`;
    }).join('');
}

function renderPag(meta){
    if(!meta)return;
    document.getElementById('pagBar').style.display='flex';
    document.getElementById('pagInfo').textContent=`${meta.from??0}–${meta.to??0} de ${meta.total}`;
    const btns=document.getElementById('pagBtns'); btns.innerHTML='';
    const prev=document.createElement('button'); prev.textContent='‹'; prev.disabled=meta.current_page<=1; prev.onclick=()=>{page--;loadTable();}; btns.appendChild(prev);
    const start=Math.max(1,meta.current_page-3),end=Math.min(meta.last_page,start+6);
    for(let i=start;i<=end;i++){const b=document.createElement('button');b.textContent=i;if(i===meta.current_page)b.classList.add('active');b.onclick=(()=>{const p=i;return()=>{page=p;loadTable();}})();btns.appendChild(b);}
    const next=document.createElement('button'); next.textContent='›'; next.disabled=meta.current_page>=meta.last_page; next.onclick=()=>{page++;loadTable();}; btns.appendChild(next);
}

function applyFilters(){
    filters={};
    const d=document.getElementById('fDate').value;
    const e=document.getElementById('fEmp').value;
    const s=document.getElementById('fStatus').value;
    if(d)filters.date=d; if(e)filters.employee_id=e; if(s)filters.status=s;
    page=1; loadTable();
}
function resetFilters(){document.getElementById('fDate').value='';document.getElementById('fEmp').value='';document.getElementById('fStatus').value='';filters={};page=1;loadTable();}

function openOverlay(id){document.getElementById(id).classList.add('open');}
function closeOverlay(id){document.getElementById(id).classList.remove('open');}

function openCreate(){
    editId=null;
    document.getElementById('attForm').reset();
    document.getElementById('formTitle').textContent='➕ Registar Presença';
    document.getElementById('submitBtn').textContent='Registar';
    // default date = today
    document.getElementById('attForm').querySelector('[name="date"]').value=new Date().toISOString().slice(0,10);
    openOverlay('formOverlay');
}
function openEdit(a){
    editId=a.id;
    document.getElementById('attForm').reset();
    const form=document.getElementById('attForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    set('employee_id',a.employee_id); set('date',a.date); set('status',a.status);
    set('check_in',a.check_in?a.check_in.slice(0,5):''); set('check_out',a.check_out?a.check_out.slice(0,5):'');
    document.getElementById('formTitle').textContent='✏️ Editar Presença';
    document.getElementById('submitBtn').textContent='Guardar';
    openOverlay('formOverlay');
}
async function submitForm(e){
    e.preventDefault();
    const btn=document.getElementById('submitBtn'); btn.disabled=true; btn.textContent='A guardar...';
    const data={};
    new FormData(document.getElementById('attForm')).forEach((v,k)=>{if(v!=='')data[k]=v;});
    try{
        if(editId) await apiFetch('PUT',`/attendances/${editId}`,data);
        else       await apiFetch('POST','/attendances',data);
        toast(editId?'Presença atualizada!':'Presença registada!','ok');
        closeOverlay('formOverlay'); loadTable();
    }catch(err){toast(err.message??'Erro.','err');}
    finally{btn.disabled=false;btn.textContent=editId?'Guardar':'Registar';}
}
function openDelete(id){deleteId=id;document.getElementById('delMsg').textContent='Tem certeza que deseja excluir este registo?';openOverlay('delOverlay');}
async function confirmDelete(){
    try{await apiFetch('DELETE',`/attendances/${deleteId}`);toast('Registo excluído.','ok');closeOverlay('delOverlay');loadTable();}
    catch(err){toast(err.message??'Erro.','err');}
}
function toast(msg,type='ok'){const w=document.getElementById('toastWrap');const t=document.createElement('div');t.className=`toast toast-${type}`;t.textContent=msg;w.appendChild(t);setTimeout(()=>t.remove(),3500);}
document.querySelectorAll('.overlay').forEach(o=>{o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');});});
boot();
</script>
@endsection
