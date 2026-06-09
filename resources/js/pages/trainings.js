/**
 * trainings.js — Lógica da página de Formações
 * Depende de window.TRAIN_CONFIG injectado pelo Blade:
 *   { logoUrl }
 */

const API  = '/api/v1';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let currentTab='enrollments';
let enrollEditId=null, trainingEditId=null, deleteTarget=null;
let enrollPage=1, catalogPage=1, enrollFilters={}, catalogFilters={};
let employees=[], trainings=[];
let catalogSort='title_asc'; // title_asc | title_desc | inscricoes_asc | inscricoes_desc
let enrollMap={}, trainingMap={};
let resAllRows = [], resSummaryData = null, resCurrentTrainingTitle = '';

/* ── Estado multiselect funcionários (modal inscrição) ── */
let enrollSelectedEmps = {}, enrollEmpFocusIdx = -1;

const statusLabel   = {enrolled:'Inscrito', completed:'Concluído', failed:'Reprovado'};
const statusClass   = {enrolled:'badge-enrolled', completed:'badge-completed', failed:'badge-failed'};
const validityLabel = {valid:'✅ Válida', expiring:'🔔 A expirar', expired:'⚠️ Expirada'};
const validityClass = {valid:'badge-valid', expiring:'badge-expiring', expired:'badge-expired'};

async function apiFetch(method,path,body){
    const opts={method,credentials:'same-origin',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}};
    if(body)opts.body=JSON.stringify(body);
    const r=await fetch(API+path,opts);
    if(!r.ok){const e=await r.json().catch(()=>({message:'Erro'}));throw e;}
    return r.status===204?null:r.json();
}

async function boot(){
    const [emp,tr]=await Promise.all([
        apiFetch('GET','/employees?all=true').catch(()=>({data:[]})),
        apiFetch('GET','/trainings?per_page=200').catch(()=>({data:[]})),
    ]);
    employees = emp.data??[];
    trainings = tr.data??[];

    const enrollDd = document.getElementById('enrollEmpDropdown');
    const enrollEmpty = document.getElementById('enrollEmpEmpty');
    employees.forEach(e=>{
        const div = document.createElement('div');
        div.className = 'emp-opt';
        div.dataset.id    = e.id;
        div.dataset.label = `${e.full_name} (${e.code})`;
        div.innerHTML = `<span>${e.full_name} <span style="color:var(--text-muted);font-size:.82rem">(${e.code})</span></span><span class="emp-opt-check">✓</span>`;
        div.addEventListener('click', () => enrollToggleEmp(div));
        enrollDd.insertBefore(div, enrollEmpty);
    });
    cbInit();
    trainings.forEach(t=>{
        const o=`<option value="${t.id}">${t.title}</option>`;
        document.getElementById('trainingSelEnroll').innerHTML+=o;
        document.getElementById('fTraining').innerHTML+=`<option value="${t.id}">${t.title}</option>`;
    });
    loadEnrollments();
    loadAlerts();
}

/* ── Alertas de validade ─────────────────────────── */
async function loadAlerts(){
    try{
        const [rExp, rExpiring]=await Promise.all([
            fetch(`${API}/enrollments?per_page=1&validity_status=expired`,{headers:{Accept:'application/json'}}).then(r=>r.json()),
            fetch(`${API}/enrollments?per_page=1&validity_status=expiring`,{headers:{Accept:'application/json'}}).then(r=>r.json()),
        ]);
        const nExp   = rExp.meta?.total??0;
        const nExpir = rExpiring.meta?.total??0;
        document.getElementById('cntExpired').textContent  = nExp;
        document.getElementById('cntExpiring').textContent = nExpir;
        document.getElementById('alertBar').style.display  = (nExp>0||nExpir>0)?'flex':'none';
    }catch(e){}
}

function filterByValidity(v){
    document.getElementById('fValidityStatus').value=v;
    enrollFilters={validity_status:v};
    enrollPage=1;
    loadEnrollments();
    if(currentTab!=='enrollments') switchTab('enrollments');
}

/* ── Tabs ── */
function switchTab(tab){
    currentTab=tab;
    document.getElementById('tabEnroll').classList.toggle('active',    tab==='enrollments');
    document.getElementById('tabCatalog').classList.toggle('active',   tab==='catalog');
    document.getElementById('tabMandatory').classList.toggle('active', tab==='mandatory');
    document.getElementById('tableEnroll').style.display    = tab==='enrollments'?'':'none';
    document.getElementById('tableCatalog').style.display   = tab==='catalog'?'':'none';
    document.getElementById('tableMandatory').style.display = tab==='mandatory'?'':'none';
    document.getElementById('filterEnroll').style.display   = tab==='enrollments'?'flex':'none';
    document.getElementById('filterCatalog').style.display  = tab==='catalog'?'flex':'none';
    document.getElementById('alertBar').style.display       = tab==='enrollments'?'':'none';
    document.getElementById('btnNewTraining').style.display  = tab==='catalog'?'inline-flex':'none';
    document.getElementById('btnNewEnroll').style.display    = tab==='enrollments'?'inline-flex':'none';
    document.getElementById('btnNewMandatory').style.display = tab==='mandatory'?'inline-flex':'none';
    document.getElementById('catalogStatRow').style.display  = tab==='catalog'?'':'none';
    if(tab==='catalog')   loadCatalog();
    if(tab==='mandatory') loadMandatory();
}

/* ── Enrollments ── */
async function loadEnrollments(){
    const tbody=document.getElementById('enrollBody');
    tbody.innerHTML='<tr class="state-row"><td colspan="9"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('enrollPagBar').style.display='none';
    const q=new URLSearchParams({page:enrollPage,per_page:15,...enrollFilters});
    try{
        const res=await fetch(`${API}/enrollments?${q}`,{credentials:'same-origin',headers:{Accept:'application/json'}});
        const json=await res.json();
        renderEnrollments(json.data??[]);
        renderPag(json.meta,'enrollPagBar','enrollPagInfo','enrollPagBtns',p=>{enrollPage=p;loadEnrollments();});
    }catch(e){tbody.innerHTML='<tr class="state-row"><td colspan="9">⚠️ Erro ao carregar.</td></tr>';}
}

function renderEnrollments(rows){
    const tbody=document.getElementById('enrollBody');
    if(!rows.length){tbody.innerHTML='<tr class="state-row"><td colspan="9">Nenhuma inscrição encontrada.</td></tr>';return;}
    enrollMap={};
    rows.forEach(e=>enrollMap[e.id]=e);
    tbody.innerHTML=rows.map(e=>{
        const sd=e.start_date?new Date(e.start_date+'T00:00:00').toLocaleDateString('pt-PT'):'—';
        const ed=e.end_date  ?new Date(e.end_date  +'T00:00:00').toLocaleDateString('pt-PT'):'—';
        const score=e.score!=null
            ?`<div>${e.score}%<div class="score-bar"><div class="score-fill" style="width:${e.score}%;background:${e.score>=70?'#22c55e':e.score>=40?'#f59e0b':'#ef4444'}"></div></div></div>`
            :'—';

        // Coluna validade (meses)
        const validityCell = e.validity_months
            ? `${e.validity_months} mês${e.validity_months>1?'es':''}`
            : `<span style="color:var(--text-muted)">—</span>`;

        // Coluna expiração com badge de estado
        let expiryCell = `<span style="color:var(--text-muted)">—</span>`;
        if(e.expiry_date){
            const expiryFmt = new Date(e.expiry_date+'T00:00:00').toLocaleDateString('pt-PT');
            const vs  = e.validity_status;
            const cls = validityClass[vs]??'badge-noexp';
            const lbl = validityLabel[vs]??'';
            expiryCell=`<div style="line-height:1.6">
                <span style="font-size:.82rem;color:var(--text-muted)">${expiryFmt}</span><br>
                <span class="badge ${cls}">${lbl}</span>
            </div>`;
        }

        return `<tr>
            <td style="font-weight:600">${e.employee?.full_name??'—'}</td>
            <td>${e.training?.title??'—'}</td>
            <td><span class="badge ${statusClass[e.status]??''}">${statusLabel[e.status]??e.status}</span></td>
            <td style="font-size:.82rem">${score}</td>
            <td style="color:var(--text-muted)">${sd}</td>
            <td style="color:var(--text-muted)">${ed}</td>
            <td style="font-size:.82rem">${validityCell}</td>
            <td>${expiryCell}</td>
            <td style="white-space:nowrap">
                <button class="btn-sm btn-edit" onclick="openEditEnroll(${e.id})">✏️</button>
                <button class="btn-sm btn-del"  onclick="openDelete('enrollment',${e.id})">🗑</button>
            </td>
        </tr>`;
    }).join('');
}

/* ── Catalog Sort ── */
function setCatalogSort(col){
    // toggle asc/desc se já estiver nesta coluna
    const cur=catalogSort;
    if(cur===col+'_asc')  catalogSort=col+'_desc';
    else if(cur===col+'_desc') catalogSort=col+'_asc';
    else catalogSort=col+'_asc';
    catalogPage=1;
    updateCatalogSortHeaders();
    loadCatalog();
}
function updateCatalogSortHeaders(){
    const cols={title:'sortTitleTh',inscricoes:'sortInscTh'};
    const arrows={title:'sortTitleArrow',inscricoes:'sortInscArrow'};
    Object.entries(cols).forEach(([col,thId])=>{
        const th=document.getElementById(thId);
        const ar=document.getElementById(arrows[col]);
        if(!th)return;
        th.classList.remove('sort-asc','sort-desc');
        if(catalogSort===col+'_asc'){th.classList.add('sort-asc');if(ar)ar.textContent='↑';}
        else if(catalogSort===col+'_desc'){th.classList.add('sort-desc');if(ar)ar.textContent='↓';}
        else{if(ar)ar.textContent='⇅';}
    });
}

/* ── Catalog ── */
async function loadCatalog(){
    const tbody=document.getElementById('catalogBody');
    tbody.innerHTML='<tr class="state-row"><td colspan="5"><span class="spinner"></span>A carregar...</td></tr>';
    document.getElementById('catalogPagBar').style.display='none';
    const q=new URLSearchParams({page:catalogPage,per_page:15,sort:catalogSort,...catalogFilters});
    try{
        const res=await fetch(`${API}/trainings?${q}`,{credentials:'same-origin',headers:{Accept:'application/json'}});
        const json=await res.json();
        renderCatalog(json.data??[]);
        updateCatalogSortHeaders();
        renderPag(json.meta,'catalogPagBar','catalogPagInfo','catalogPagBtns',p=>{catalogPage=p;loadCatalog();});
        const el=document.getElementById('statTotalTrainings');
        if(el&&json.meta?.total!=null) el.textContent=json.meta.total;
    }catch(e){tbody.innerHTML='<tr class="state-row"><td colspan="5">⚠️ Erro ao carregar.</td></tr>';}
}

function renderCatalog(rows){
    const tbody=document.getElementById('catalogBody');
    if(!rows.length){tbody.innerHTML='<tr class="state-row"><td colspan="5">Nenhuma formação no catálogo.</td></tr>';return;}
    trainingMap={};
    rows.forEach(t=>trainingMap[t.id]=t);
    tbody.innerHTML=rows.map(t=>`<tr>
        <td style="color:var(--text-muted)">${t.id}</td>
        <td style="font-weight:600">
            ${t.title}
            ${t.has_video?'<span style="font-size:.7rem;background:rgba(99,102,241,.18);color:var(--accent-light);border-radius:6px;padding:2px 7px;margin-left:6px">🎬 Vídeo</span>':''}
            ${t.has_quiz?'<span style="font-size:.7rem;background:rgba(34,197,94,.15);color:#4ade80;border-radius:6px;padding:2px 7px;margin-left:4px">📝 Quiz</span>':''}
        </td>
        <td>${t.provider}</td>
        <td><span class="badge-count">${t.employee_trainings_count??0}</span></td>
        <td style="white-space:nowrap">
            <button class="btn-sm btn-edit" onclick="openEditTraining(${t.id})">✏️ Editar</button>
            ${(t.has_video||t.has_quiz)?`<button class="btn-sm" style="background:rgba(99,102,241,.15);color:var(--accent-light)" onclick="openContentModal(${t.id},${t.has_video},${t.has_quiz})">🎬 Conteúdo</button>`:''}
            ${t.has_quiz?`<button class="btn-sm" style="background:rgba(34,197,94,.12);color:#4ade80" onclick="openResultsModal(${t.id})">📊 Resultados</button>`:''}
            <button class="btn-sm btn-del"  onclick="openDelete('training',${t.id})">🗑</button>
        </td>
    </tr>`).join('');
}

/* ── Paginação genérica ── */
function renderPag(meta,barId,infoId,btnsId,onPage){
    if(!meta)return;
    document.getElementById(barId).style.display='flex';
    document.getElementById(infoId).textContent=`${meta.from??0}–${meta.to??0} de ${meta.total}`;
    const btns=document.getElementById(btnsId);btns.innerHTML='';
    const prev=document.createElement('button');prev.textContent='‹';prev.disabled=meta.current_page<=1;prev.onclick=()=>onPage(meta.current_page-1);btns.appendChild(prev);
    const start=Math.max(1,meta.current_page-3),end=Math.min(meta.last_page,start+6);
    for(let i=start;i<=end;i++){const b=document.createElement('button');b.textContent=i;if(i===meta.current_page)b.classList.add('active');b.onclick=(()=>{const p=i;return()=>onPage(p);})();btns.appendChild(b);}
    const next=document.createElement('button');next.textContent='›';next.disabled=meta.current_page>=meta.last_page;next.onclick=()=>onPage(meta.current_page+1);btns.appendChild(next);
}

/* ── Filters ── */
function applyFilters(){
    enrollFilters={};
    const t=document.getElementById('fTraining').value;
    const e=document.getElementById('fEmpEnroll').value;
    const s=document.getElementById('fEnrollStatus').value;
    const v=document.getElementById('fValidityStatus').value;
    if(t)enrollFilters.training_id=t;
    if(e)enrollFilters.employee_id=e;
    if(s)enrollFilters.status=s;
    if(v)enrollFilters.validity_status=v;
    enrollPage=1;loadEnrollments();
}
function resetFilters(){
    ['fTraining','fEnrollStatus','fValidityStatus'].forEach(id=>document.getElementById(id).value='');
    cbClear();
    enrollFilters={};enrollPage=1;loadEnrollments();
}

/* ── Combobox funcionários ── */
let cbItems=[], cbFocusIdx=-1;
function cbInit(){
    cbItems=[{id:'',label:'Todos os funcionários'},...employees.map(e=>({id:String(e.id),label:e.full_name+(e.code?' ('+e.code+')':'')}))];
    const inp=document.getElementById('cbEmpInput');
    inp.addEventListener('input',cbFilter);
    inp.addEventListener('click',cbOpen);
    // Fechar ao clicar fora — repor label anterior se não houve nova seleção
    document.addEventListener('click',function(ev){
        if(!document.getElementById('cbEmpWrap').contains(ev.target)){
            document.getElementById('cbEmpDropdown').classList.remove('open');
            const inp=document.getElementById('cbEmpInput');
            if(inp.dataset.prev!==undefined && inp.value!==inp.dataset.prev){
                inp.removeEventListener('input',cbFilter);
                inp.value=inp.dataset.prev;
                inp.addEventListener('input',cbFilter);
                delete inp.dataset.prev;
            }
        }
    });
}
function cbRenderDropdown(q){
    const dd=document.getElementById('cbEmpDropdown');
    const filtered=q?cbItems.filter(i=>i.label.toLowerCase().includes(q.toLowerCase())):cbItems;
    if(!filtered.length){dd.innerHTML='<div class="cb-empty">Sem resultados</div>';cbFocusIdx=-1;return;}
    const cur=document.getElementById('fEmpEnroll').value;
    dd.innerHTML=filtered.map(i=>`<div class="cb-option${i.id===cur?' selected':''}" data-id="${i.id}">${i.label}</div>`).join('');
    // Ligar click em cada opção
    dd.querySelectorAll('.cb-option').forEach(function(opt){
        opt.addEventListener('click',function(ev){
            ev.stopPropagation();
            const id=opt.dataset.id;
            const label=opt.textContent;
            document.getElementById('fEmpEnroll').value=id;
            // Atualizar input sem disparar oninput
            const inp=document.getElementById('cbEmpInput');
            inp.removeEventListener('input',cbFilter);
            inp.value=id?label:'';
            delete inp.dataset.prev;
            inp.addEventListener('input',cbFilter);
            document.getElementById('cbEmpDropdown').classList.remove('open');
            applyFilters();
        });
    });
    cbFocusIdx=-1;
}
function cbFilter(){
    const q=document.getElementById('cbEmpInput').value;
    if(!q){document.getElementById('fEmpEnroll').value='';applyFilters();}
    cbRenderDropdown(q);
    document.getElementById('cbEmpDropdown').classList.add('open');
}
function cbOpen(){
    // Limpar o input para permitir nova pesquisa, guardando o label atual
    const inp=document.getElementById('cbEmpInput');
    inp.dataset.prev=inp.value;
    inp.removeEventListener('input',cbFilter);
    inp.value='';
    inp.addEventListener('input',cbFilter);
    cbRenderDropdown('');
    document.getElementById('cbEmpDropdown').classList.add('open');
}
function cbClear(){
    document.getElementById('fEmpEnroll').value='';
    const inp=document.getElementById('cbEmpInput');
    inp.removeEventListener('input',cbFilter);
    inp.value='';
    inp.addEventListener('input',cbFilter);
}
function cbKeydown(e){
    const dd=document.getElementById('cbEmpDropdown');
    const opts=[...dd.querySelectorAll('.cb-option')];
    if(!opts.length)return;
    if(e.key==='ArrowDown'){e.preventDefault();cbFocusIdx=Math.min(cbFocusIdx+1,opts.length-1);opts.forEach((o,i)=>o.classList.toggle('focused',i===cbFocusIdx));}
    else if(e.key==='ArrowUp'){e.preventDefault();cbFocusIdx=Math.max(cbFocusIdx-1,0);opts.forEach((o,i)=>o.classList.toggle('focused',i===cbFocusIdx));}
    else if(e.key==='Enter'){if(cbFocusIdx>=0)opts[cbFocusIdx].click();}
    else if(e.key==='Escape'){dd.classList.remove('open');}
}
function applyCatalogFilters(){catalogFilters={};const s=document.getElementById('fCatalogSearch').value.trim();if(s)catalogFilters.search=s;catalogPage=1;loadCatalog();}
function resetCatalogFilters(){document.getElementById('fCatalogSearch').value='';catalogFilters={};catalogPage=1;loadCatalog();}

/* ── Bloqueio de pontuação se formação ainda não terminou ── */
function updateScoreState(){
    const endVal  = document.getElementById('endDateInput').value;
    const scoreEl = document.getElementById('scoreInput');
    const hintEl  = document.getElementById('scoreHint');
    const certWrap = document.getElementById('certUploadWrap');
    if(!endVal){
        scoreEl.disabled = false;
        hintEl.textContent = '';
        if(certWrap) certWrap.style.display = 'none';
        return;
    }
    const today = new Date(); today.setHours(0,0,0,0);
    const endDate = new Date(endVal + 'T00:00:00');
    if(endDate > today){
        scoreEl.disabled = true;
        scoreEl.dataset.blocked = '1';
        hintEl.textContent = '⚠️ Não é possível atribuir pontuação — a formação ainda não foi concluída.';
        hintEl.style.color = '#f59e0b';
        if(certWrap) certWrap.style.display = 'none';
    } else {
        scoreEl.disabled = false;
        delete scoreEl.dataset.blocked;
        hintEl.textContent = '';
        if(certWrap) certWrap.style.display = '';
    }
}

/* ── Cálculo dinâmico de expiração no modal ── */
function updateExpiryHint(){
    const endVal    = document.getElementById('endDateInput').value;
    const monthsVal = parseInt(document.getElementById('validityInput').value);
    const hint      = document.getElementById('expiryHint');
    if(!endVal || !monthsVal || monthsVal < 1){
        hint.textContent='— preencha fim e validade';
        hint.className='validity-hint';
        return;
    }
    const expiry = new Date(endVal);
    expiry.setMonth(expiry.getMonth()+monthsVal);
    const today  = new Date(); today.setHours(0,0,0,0);
    const diff   = Math.round((expiry-today)/(1000*60*60*24));
    const fmt    = expiry.toLocaleDateString('pt-PT');
    if(diff < 0){
        hint.textContent=`Expirou em ${fmt}`;
        hint.className='validity-hint expired';
    }else if(diff <= 30){
        hint.textContent=`Expira em ${fmt} (faltam ${diff} dias)`;
        hint.className='validity-hint expiring';
    }else{
        hint.textContent=`Válida até ${fmt}`;
        hint.className='validity-hint valid';
    }
}

/* ── Overlays ── */
function openOverlay(id){document.getElementById(id).classList.add('open');}
function closeOverlay(id){document.getElementById(id).classList.remove('open');}


/* ── Emp-picker do modal de inscrição (/trainings) ── */
function enrollOpenEmpDropdown() {
    document.getElementById('enrollEmpDropdown').classList.add('open');
    enrollFilterEmpOptions();
}
function enrollCloseEmpDropdown() {
    document.getElementById('enrollEmpDropdown').classList.remove('open');
    document.getElementById('enrollEmpSearch').value = '';
    document.querySelectorAll('#enrollEmpDropdown .emp-opt').forEach(o => o.style.display = '');
    document.getElementById('enrollEmpEmpty').style.display = 'none';
    enrollEmpFocusIdx = -1;
}
function enrollFilterEmpOptions() {
    const q = document.getElementById('enrollEmpSearch').value.toLowerCase().trim();
    const opts = document.querySelectorAll('#enrollEmpDropdown .emp-opt');
    let visible = 0;
    opts.forEach(o => { const m = !q || o.dataset.label.toLowerCase().includes(q); o.style.display = m ? '' : 'none'; if (m) visible++; });
    document.getElementById('enrollEmpEmpty').style.display = visible === 0 ? '' : 'none';
    enrollEmpFocusIdx = -1;
}
function enrollToggleEmp(optEl) {
    const id = optEl.dataset.id, label = optEl.dataset.label;
    if (enrollSelectedEmps[id]) { delete enrollSelectedEmps[id]; optEl.classList.remove('selected'); }
    else { enrollSelectedEmps[id] = label; optEl.classList.add('selected'); }
    enrollRenderChips();
    document.getElementById('enrollEmpSearch').focus();
}
function enrollRemoveEmp(id) {
    delete enrollSelectedEmps[id];
    const opt = document.querySelector(`#enrollEmpDropdown .emp-opt[data-id="${id}"]`);
    if (opt) opt.classList.remove('selected');
    enrollRenderChips();
}
function enrollRenderChips() {
    const container = document.getElementById('enrollEmpChips');
    container.querySelectorAll('.emp-chip').forEach(c => c.remove());
    const search = document.getElementById('enrollEmpSearch');
    Object.entries(enrollSelectedEmps).forEach(([id, label]) => {
        const chip = document.createElement('span');
        chip.className = 'emp-chip'; chip.dataset.id = id;
        chip.innerHTML = `${label} <button type="button" onclick="enrollRemoveEmp('${id}')" title="Remover">✕</button>`;
        container.insertBefore(chip, search);
    });
    const count = Object.keys(enrollSelectedEmps).length;
    document.getElementById('enrollEmpCountLabel').textContent = count > 0 ? `(${count} selecionado${count > 1 ? 's' : ''})` : '';
}
function enrollEmpKeydown(e) {
    const dd = document.getElementById('enrollEmpDropdown');
    const opts = [...dd.querySelectorAll('.emp-opt:not([style*="display: none"])')];
    if (e.key === 'ArrowDown') { e.preventDefault(); enrollEmpFocusIdx = Math.min(enrollEmpFocusIdx + 1, opts.length - 1); opts.forEach((o, i) => o.classList.toggle('focused', i === enrollEmpFocusIdx)); if (opts[enrollEmpFocusIdx]) opts[enrollEmpFocusIdx].scrollIntoView({ block: 'nearest' }); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); enrollEmpFocusIdx = Math.max(enrollEmpFocusIdx - 1, 0); opts.forEach((o, i) => o.classList.toggle('focused', i === enrollEmpFocusIdx)); if (opts[enrollEmpFocusIdx]) opts[enrollEmpFocusIdx].scrollIntoView({ block: 'nearest' }); }
    else if (e.key === 'Enter') { e.preventDefault(); if (enrollEmpFocusIdx >= 0 && opts[enrollEmpFocusIdx]) enrollToggleEmp(opts[enrollEmpFocusIdx]); }
    else if (e.key === 'Escape') { enrollCloseEmpDropdown(); }
    else if (e.key === 'Backspace' && e.target.value === '') { const ids = Object.keys(enrollSelectedEmps); if (ids.length) enrollRemoveEmp(ids[ids.length - 1]); }
}
function enrollResetEmpPicker() {
    enrollSelectedEmps = {};
    document.querySelectorAll('#enrollEmpDropdown .emp-opt').forEach(o => o.classList.remove('selected', 'focused'));
    enrollRenderChips();
    enrollCloseEmpDropdown();
}
function enrollSetSingleEmp(id, label) {
    enrollResetEmpPicker();
    if (!id) return;
    enrollSelectedEmps[id] = label;
    const opt = document.querySelector(`#enrollEmpDropdown .emp-opt[data-id="${id}"]`);
    if (opt) opt.classList.add('selected');
    enrollRenderChips();
}
document.addEventListener('click', function(e) {
    const picker = document.getElementById('enrollEmpPicker');
    if (picker && !picker.contains(e.target)) enrollCloseEmpDropdown();
});

function openCreateEnroll(){
    enrollEditId=null;
    document.getElementById('enrollForm').reset();
    document.getElementById('expiryHint').textContent='— preencha fim e validade';
    document.getElementById('expiryHint').className='validity-hint';
    document.getElementById('scoreInput').disabled=false;
    document.getElementById('scoreHint').textContent='';
    document.getElementById('enrollTitle').textContent='➕ Nova Inscrição';
    document.getElementById('enrollSubmitBtn').textContent='Inscrever';
    document.getElementById('sessionSelWrap').style.display='none';
    document.getElementById('sessionSelEnroll').innerHTML='<option value="">— Sem sessão associada —</option>';
    enrollResetEmpPicker();
    // Reset certificado
    document.getElementById('certUploadWrap').style.display = 'none';
    document.getElementById('certCurrentWrap').style.display = 'none';
    document.getElementById('certFileInput').value = '';
    document.getElementById('certFileName').textContent = '';
    openOverlay('enrollOverlay');
    setTimeout(()=>document.getElementById('enrollEmpSearch').focus(), 120);
}
async function loadSessionsForEnroll() {
    const trainingId = document.getElementById('trainingSelEnroll').value;
    const wrap = document.getElementById('sessionSelWrap');
    const sel  = document.getElementById('sessionSelEnroll');
    sel.innerHTML = '<option value="">— Sem sessão associada —</option>';
    if (!trainingId) { wrap.style.display = 'none'; return; }
    try {
        const res = await apiFetch('GET', `/training-sessions?training_id=${trainingId}`);
        const sessions = (res.data ?? []).filter(s => s.status !== 'cancelled');
        if (!sessions.length) { wrap.style.display = 'none'; return; }
        sessions.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            const loc = s.location ? ` · ${s.location}` : '';
            const slots = s.estimated_participants ? ` (${s.enrolled_count}/${s.estimated_participants} inscritos)` : '';
            opt.textContent = `${s.planned_date_fmt}${s.planned_end_fmt && s.planned_end_fmt !== s.planned_date_fmt ? ' → ' + s.planned_end_fmt : ''}${loc}${slots}`;
            sel.appendChild(opt);
        });
        wrap.style.display = '';
    } catch(e) { wrap.style.display = 'none'; }
}

function openEditEnroll(id){
    const e=enrollMap[id];if(!e)return;
    enrollEditId=e.id;
    document.getElementById('enrollForm').reset();
    document.getElementById('sessionSelWrap').style.display='none';
    const form=document.getElementById('enrollForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    enrollSetSingleEmp(String(e.employee_id), e.employee?.full_name ?? '---');
    set('training_id',e.training_id);set('status',e.status);
    set('score',e.score);set('start_date',e.start_date);set('end_date',e.end_date);
    set('validity_months',e.validity_months);set('notes',e.notes);
    // Carregar sessões e depois seleccionar a correcta
    loadSessionsForEnroll().then(() => {
        if (e.training_session_id) {
            document.getElementById('sessionSelEnroll').value = e.training_session_id;
        }
    });
    document.getElementById('enrollTitle').textContent='✏️ Editar Inscrição';
    document.getElementById('enrollSubmitBtn').textContent='Guardar';
    // Certificado
    const certWrap = document.getElementById('certUploadWrap');
    const certCurrentWrap = document.getElementById('certCurrentWrap');
    const certLink = document.getElementById('certCurrentLink');
    document.getElementById('certFileInput').value = '';
    document.getElementById('certFileName').textContent = '';
    if(e.certificate_url){
        certCurrentWrap.style.display = '';
        certLink.href = e.certificate_url;
    } else {
        certCurrentWrap.style.display = 'none';
    }
    // Mostrar campo de certificado directamente se a formação já terminou
    if(e.end_date){
        const today = new Date(); today.setHours(0,0,0,0);
        const endDate = new Date(e.end_date + 'T00:00:00');
        certWrap.style.display = endDate <= today ? '' : 'none';
    } else {
        certWrap.style.display = 'none';
    }
    setTimeout(()=>{updateExpiryHint();updateScoreState();},50);
    openOverlay('enrollOverlay');
}
async function submitEnroll(ev){
    ev.preventDefault();
    const empIds = Object.keys(enrollSelectedEmps);
    if(!enrollEditId && empIds.length === 0){
        toast('Seleciona pelo menos um funcionário.','err');
        document.getElementById('enrollEmpSearch').focus();
        return;
    }
    const btn=document.getElementById('enrollSubmitBtn');btn.disabled=true;
    const base={};
    const scoreBlocked = document.getElementById('scoreInput').dataset.blocked === '1';
    new FormData(document.getElementById('enrollForm')).forEach((v,k)=>{if(v!=='')base[k]=v;});
    if(scoreBlocked) delete base.score;
    try{
        if(enrollEditId){
            btn.textContent='A guardar...';
            base.employee_id = empIds[0];
            await apiFetch('PUT',`/enrollments/${enrollEditId}`,base);
            // Upload de certificado se seleccionado
            if(document.getElementById('certFileInput').files.length){
                try {
                    await uploadCertificate(enrollEditId);
                } catch(certErr){
                    toast((certErr?.message ?? 'Erro ao carregar certificado.') + ' Os restantes dados foram guardados.', 'err');
                }
            }
            toast('Inscrição atualizada!','ok');
        } else {
            btn.textContent = empIds.length > 1 ? `A inscrever ${empIds.length}...` : 'A inscrever...';
            const results = await Promise.allSettled(
                empIds.map(id => apiFetch('POST','/enrollments',{...base, employee_id: id}))
            );
            const ok  = results.filter(r=>r.status==='fulfilled').length;
            const err = results.filter(r=>r.status==='rejected').length;
            // Upload de certificado para cada inscrição criada com sucesso
            const hasCert = document.getElementById('certFileInput').files.length > 0;
            if(hasCert && ok > 0){
                const created = results.filter(r=>r.status==='fulfilled').map(r=>r.value?.data?.id).filter(Boolean);
                await Promise.allSettled(created.map(id => uploadCertificate(id)));
            }
            if(ok > 0 && err === 0) toast(`${ok} inscrição(ões) criada(s) com sucesso!`,'ok');
            else if(ok > 0)         toast(`${ok} criada(s), ${err} com erro.`,'ok');
            else {
                // Mostrar o erro da primeira falha
                const firstErr = results.find(r=>r.status==='rejected')?.reason;
                toast(firstErr?.message ?? 'Erro ao criar inscrições.','err');
                return; // não fechar modal nem recarregar
            }
        }
        closeOverlay('enrollOverlay');
        loadEnrollments();
        loadAlerts();
    }catch(err){toast(err.message??'Erro.','err');}
    finally{btn.disabled=false;btn.textContent=enrollEditId?'Guardar':'Inscrever';}
}

function openCreateTraining(){
    trainingEditId=null;document.getElementById('trainingForm').reset();
    document.getElementById('trainingTitle').textContent='➕ Nova Formação';
    document.getElementById('trainingSubmitBtn').textContent='Criar';
    openOverlay('trainingOverlay');
}
function openEditTraining(id){
    const t=trainingMap[id];if(!t)return;
    trainingEditId=t.id;document.getElementById('trainingForm').reset();
    const form=document.getElementById('trainingForm');
    const set=(n,v)=>{const el=form.querySelector(`[name="${n}"]`);if(el)el.value=v??'';};
    set('title',t.title);set('provider',t.provider);set('description',t.description);
    const chkVideo=form.querySelector('[name="has_video"]');
    const chkQuiz=form.querySelector('[name="has_quiz"]');
    if(chkVideo) chkVideo.checked=!!t.has_video;
    if(chkQuiz)  chkQuiz.checked=!!t.has_quiz;
    document.getElementById('trainingTitle').textContent='✏️ Editar Formação';
    document.getElementById('trainingSubmitBtn').textContent='Guardar';
    openOverlay('trainingOverlay');
}
async function submitTraining(ev){
    ev.preventDefault();
    const btn=document.getElementById('trainingSubmitBtn');btn.disabled=true;btn.textContent='A guardar...';
    const form=document.getElementById('trainingForm');
    const data={};new FormData(form).forEach((v,k)=>{if(v!=='')data[k]=v;});
    data.has_video=form.querySelector('[name="has_video"]').checked;
    data.has_quiz=form.querySelector('[name="has_quiz"]').checked;
    try{
        if(trainingEditId) await apiFetch('PUT',`/trainings/${trainingEditId}`,data);
        else               await apiFetch('POST','/trainings',data);
        toast(trainingEditId?'Formação atualizada!':'Formação criada!','ok');
        closeOverlay('trainingOverlay');loadCatalog();
    }catch(err){toast(err.message??'Erro.','err');}
    finally{btn.disabled=false;btn.textContent=trainingEditId?'Guardar':'Criar';}
}

/* ── Delete ── */
function openDelete(type,id){
    deleteTarget={type,id};
    document.getElementById('delMsg').textContent=type==='training'
        ?'Tem certeza que deseja excluir esta formação? Todas as inscrições também serão removidas.'
        :'Tem certeza que deseja excluir esta inscrição?';
    openOverlay('delOverlay');
}
async function confirmDelete(){
    const {type,id}=deleteTarget;
    try{
        if(type==='training') await apiFetch('DELETE',`/trainings/${id}`);
        else                  await apiFetch('DELETE',`/enrollments/${id}`);
        toast('Excluído com sucesso.','ok');
        closeOverlay('delOverlay');
        if(type==='training') loadCatalog(); else loadEnrollments();
        loadAlerts();
    }catch(err){toast(err.message??'Erro.','err');}
}

function toast(msg,type='ok'){
    const w=document.getElementById('toastWrap');
    const t=document.createElement('div');
    t.className=`toast toast-${type}`;t.textContent=msg;
    w.appendChild(t);setTimeout(()=>t.remove(),3500);
}
document.querySelectorAll('.overlay').forEach(o=>{
    o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');});
});
boot();

/* ══ Gestão de Conteúdo (vídeos + quiz) ══ */
let ctTrainingId = null;

function ctSwitch(tab) {
    document.getElementById('ctVideo').style.display = tab === 'video' ? '' : 'none';
    document.getElementById('ctQuiz').style.display  = tab === 'quiz'  ? '' : 'none';
    document.getElementById('ctTabVideo').classList.toggle('active', tab === 'video');
    document.getElementById('ctTabQuiz').classList.toggle('active',  tab === 'quiz');
}

async function openContentModal(trainingId, hasVideo, hasQuiz) {
    ctTrainingId = trainingId;
    const t = trainingMap[trainingId] || {};
    document.getElementById('contentTrainingTitle').textContent = t.title || '';

    // Show/hide tabs based on flags
    const tabVideo = document.getElementById('ctTabVideo');
    const tabQuiz  = document.getElementById('ctTabQuiz');
    tabVideo.style.display = hasVideo ? '' : 'none';
    tabQuiz.style.display  = hasQuiz  ? '' : 'none';

    // Switch to the first available tab
    if (hasVideo) ctSwitch('video');
    else if (hasQuiz) ctSwitch('quiz');

    // clear video add form
    ['vTitle','vUrl','vDesc'].forEach(id => document.getElementById(id).value = '');
    if (document.getElementById('vFile')) document.getElementById('vFile').value = '';
    setVideoMode('url');
    openOverlay('contentOverlay');
    const tasks = [];
    if (hasVideo) tasks.push(loadVideos());
    if (hasQuiz)  tasks.push(loadQuiz());
    await Promise.all(tasks);
}

/* ── Videos ── */
async function loadVideos() {
    document.getElementById('videoList').innerHTML = '<p style="color:var(--text-muted);font-size:.83rem">A carregar...</p>';
    try {
        const j = await apiFetch('GET', `/trainings/${ctTrainingId}/videos`);
        renderVideoList(j.data || []);
    } catch(e) {
        document.getElementById('videoList').innerHTML = '<p style="color:var(--danger);font-size:.83rem">Erro ao carregar vídeos.</p>';
    }
}

function renderVideoList(videos) {
    const el = document.getElementById('videoList');
    if (!videos.length) {
        el.innerHTML = '<p style="color:var(--text-muted);font-size:.83rem;padding:8px 0">Ainda não há vídeos nesta formação.</p>';
        return;
    }
    el.innerHTML = videos.map((v,i) => `
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border)">
            <span style="font-size:1.2rem">▶</span>
            <div style="flex:1;min-width:0">
                <div style="font-weight:600;font-size:.875rem">${v.title}</div>
                <div style="font-size:.75rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${v.url}</div>
            </div>
            <button class="btn-icon-del" onclick="deleteVideo(${v.id})" title="Eliminar">🗑</button>
        </div>
    `).join('');
}

// ── Video mode toggle ──
let videoMode = 'url'; // 'url' or 'file'
function setVideoMode(mode) {
    videoMode = mode;
    document.getElementById('vModeUrl').classList.toggle('active', mode === 'url');
    document.getElementById('vModeFile').classList.toggle('active', mode === 'file');
    document.getElementById('vUrlField').style.display  = mode === 'url'  ? '' : 'none';
    document.getElementById('vFileField').style.display = mode === 'file' ? '' : 'none';
}

async function addVideo() {
    const title = document.getElementById('vTitle').value.trim();
    const desc  = document.getElementById('vDesc').value.trim();
    const btn   = document.getElementById('vAddBtn');

    if (!title) return toast('O título é obrigatório.', 'err');

    btn.disabled = true;
    btn.textContent = 'A guardar...';

    try {
        if (videoMode === 'url') {
            const url = document.getElementById('vUrl').value.trim();
            if (!url) { toast('A URL é obrigatória.', 'err'); return; }
            await apiFetch('POST', `/trainings/${ctTrainingId}/videos`, { title, url, description: desc || null });
            toast('Vídeo adicionado.', 'ok');
        } else {
            const fileInput = document.getElementById('vFile');
            if (!fileInput.files.length) { toast('Selecciona um ficheiro.', 'err'); return; }
            const file = fileInput.files[0];
            const formData = new FormData();
            formData.append('title', title);
            formData.append('video_file', file);
            if (desc) formData.append('description', desc);

            // XHR for progress bar
            await uploadVideoWithProgress(formData);
            toast('Vídeo enviado com sucesso.', 'ok');
        }

        ['vTitle','vUrl','vDesc'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('vFile').value = '';
        setVideoMode('url');
        loadVideos();
    } catch(e) {
        toast(e.message || 'Erro ao adicionar vídeo.', 'err');
    } finally {
        btn.disabled = false;
        btn.textContent = '＋ Adicionar Vídeo';
    }
}

function uploadVideoWithProgress(formData) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        const progWrap = document.getElementById('vUploadProgress');
        const progBar  = document.getElementById('vProgressBar');
        const progText = document.getElementById('vProgressText');

        progWrap.style.display = '';
        progBar.style.width = '0%';
        progText.textContent = 'A enviar...';

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const pct = Math.round((e.loaded / e.total) * 100);
                progBar.style.width = pct + '%';
                progText.textContent = pct + '%';
            }
        });

        xhr.addEventListener('load', () => {
            progWrap.style.display = 'none';
            if (xhr.status >= 200 && xhr.status < 300) {
                resolve(JSON.parse(xhr.responseText));
            } else {
                try {
                    const err = JSON.parse(xhr.responseText);
                    reject(new Error(err.message || 'Erro no upload.'));
                } catch { reject(new Error('Erro no upload.')); }
            }
        });

        xhr.addEventListener('error', () => {
            progWrap.style.display = 'none';
            reject(new Error('Erro de ligacao.'));
        });

        xhr.open('POST', `/api/v1/trainings/${ctTrainingId}/videos`);
        xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.withCredentials = true;
        xhr.send(formData);
    });
}

async function deleteVideo(videoId) {
    if (!confirm('Eliminar este vídeo?')) return;
    try {
        await apiFetch('DELETE', `/videos/${videoId}`);
        toast('Vídeo eliminado.','ok');
        loadVideos();
    } catch(e) { toast(e.message || 'Erro.','err'); }
}

/* ── Quiz ── */
let quizQuestions = []; // [{type,question,options:[{text,is_correct}]}]
let quizExists    = false;

async function loadQuiz() {
    quizQuestions = [];
    quizExists    = false;
    try {
        const j = await apiFetch('GET', `/trainings/${ctTrainingId}/quiz`);
        const q = j.data;
        quizExists = true;
        document.getElementById('qTitle').value = q.title || '';
        document.getElementById('qDesc').value  = q.description || '';
        document.getElementById('qPass').value  = q.passing_score ?? 70;
        quizQuestions = (q.questions || []).map(qq => ({
            type: qq.type,
            question: qq.question,
            options: (qq.options || []).map(o => ({ text: o.text, is_correct: o.is_correct }))
        }));
    } catch(e) {
        // 404 = no quiz yet, that's fine
        document.getElementById('qTitle').value = '';
        document.getElementById('qDesc').value  = '';
        document.getElementById('qPass').value  = 70;
    }
    renderQuestions();
}

function renderQuestions() {
    const el = document.getElementById('questionsList');
    if (!quizQuestions.length) {
        el.innerHTML = '<p style="color:var(--text-muted);font-size:.83rem;padding:8px 0 12px">Ainda não há perguntas. Clica em «＋ Adicionar Pergunta».</p>';
        return;
    }
    el.innerHTML = quizQuestions.map((q, qi) => `
        <div class="q-block" id="qblock-${qi}">
            <div class="q-block-header">
                <span style="font-size:.8rem;font-weight:700;color:var(--text-muted)">Pergunta ${qi+1} — ${q.type === 'mc' ? 'Múltipla escolha' : 'Verdadeiro / Falso'}</span>
                <button class="btn-icon-del" onclick="removeQuestion(${qi})">🗑</button>
            </div>
            <div class="fg" style="margin-bottom:10px">
                <label style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px">Enunciado *</label>
                <input type="text" value="${escHtml(q.question)}" oninput="quizQuestions[${qi}].question=this.value" placeholder="Texto da pergunta">
            </div>
            <div>
                <p style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">
                    Opções <small style="font-weight:400;text-transform:none">(assinala a correcta)</small>
                </p>
                ${q.options.map((o,oi) => `
                <div class="q-opt-row">
                    <input type="radio" name="correct-${qi}" ${o.is_correct?'checked':''} onchange="setCorrect(${qi},${oi})">
                    <span class="opt-correct-label">Correcta</span>
                    <input type="text" value="${escHtml(o.text)}" oninput="quizQuestions[${qi}].options[${oi}].text=this.value" placeholder="Texto da opção">
                    ${q.options.length > 2 ? `<button class="btn-icon-del" style="font-size:.85rem" onclick="removeOption(${qi},${oi})">✕</button>` : ''}
                </div>`).join('')}
                ${q.type === 'mc' && q.options.length < 5 ? `
                <button onclick="addOption(${qi})" class="btn-cancel" style="font-size:.78rem;padding:5px 12px;margin-top:4px">＋ Opção</button>` : ''}
            </div>
        </div>
    `).join('');
}

function escHtml(s) {
    return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function addQuestion() {
    openOverlay('qTypeOverlay');
}

function pickQuestionType(t) {
    closeOverlay('qTypeOverlay');
    const opts = t === 'tf'
        ? [{ text: 'Verdadeiro', is_correct: true }, { text: 'Falso', is_correct: false }]
        : [{ text: '', is_correct: true }, { text: '', is_correct: false }, { text: '', is_correct: false }];
    quizQuestions.push({ type: t, question: '', options: opts });
    renderQuestions();
    setTimeout(() => {
        const last = document.getElementById('qblock-' + (quizQuestions.length - 1));
        if (last) last.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 50);
}

function removeQuestion(qi) {
    quizQuestions.splice(qi, 1);
    renderQuestions();
}

function setCorrect(qi, oi) {
    quizQuestions[qi].options.forEach((o,i) => o.is_correct = (i === oi));
    renderQuestions();
}

function addOption(qi) {
    quizQuestions[qi].options.push({ text: '', is_correct: false });
    renderQuestions();
}

function removeOption(qi, oi) {
    const opts = quizQuestions[qi].options;
    if (opts.length <= 2) return;
    const wasCorrect = opts[oi].is_correct;
    opts.splice(oi, 1);
    if (wasCorrect && opts.length) opts[0].is_correct = true;
    renderQuestions();
}

async function saveQuiz() {
    const title = document.getElementById('qTitle').value.trim();
    const desc  = document.getElementById('qDesc').value.trim();
    const pass  = parseInt(document.getElementById('qPass').value) || 70;

    if (!title) return toast('O título do questionário é obrigatório.','err');
    if (!quizQuestions.length) return toast('Adiciona pelo menos uma pergunta.','err');

    for (let i=0; i<quizQuestions.length; i++) {
        const q = quizQuestions[i];
        if (!q.question.trim()) return toast(`Pergunta ${i+1}: o enunciado está vazio.`,'err');
        if (!q.options.some(o => o.is_correct)) return toast(`Pergunta ${i+1}: nenhuma opção está marcada como correcta.`,'err');
        for (let j=0; j<q.options.length; j++) {
            if (!q.options[j].text.trim()) return toast(`Pergunta ${i+1}, opção ${j+1}: texto em branco.`,'err');
        }
    }

    const payload = {
        title,
        description: desc || null,
        passing_score: pass,
        questions: quizQuestions.map((q,qi) => ({
            question: q.question,
            type:     q.type,
            order:    qi+1,
            options:  q.options.map((o,oi) => ({ text: o.text, is_correct: o.is_correct, order: oi+1 }))
        }))
    };

    try {
        const method = quizExists ? 'PUT' : 'POST';
        await apiFetch(method, `/trainings/${ctTrainingId}/quiz`, payload);
        quizExists = true;
        toast('Questionário guardado com sucesso.','ok');
    } catch(e) {
        toast(e.message || 'Erro ao guardar questionário.','err');
    }
}

/* ══════════════════════════════════════════
   Modal de Resultados
══════════════════════════════════════════ */

async function openResultsModal(trainingId) {
    const t = trainings.find(x => x.id === trainingId);
    resCurrentTrainingTitle = t ? t.title : '…';
    document.getElementById('resModalTitle').textContent = resCurrentTrainingTitle;
    // Reset
    document.getElementById('resSummary').innerHTML  = '<span style="color:var(--text-muted);font-size:.85rem">A carregar…</span>';
    document.getElementById('resTableBody').innerHTML = '<tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">A carregar…</td></tr>';
    document.getElementById('resSearch').value = '';
    document.getElementById('resStatusFilter').value = '';
    document.getElementById('resCount').textContent = '';
    resAllRows = []; resSummaryData = null;
    openOverlay('resultsOverlay');

    try {
        const res = await apiFetch('GET', `/trainings/${trainingId}/quiz/results`);
        resAllRows = res.data ?? [];
        resSummaryData = res.summary ?? null;
        renderResultsSummary(resSummaryData);
        renderResultsTable(resAllRows);
    } catch(e) {
        document.getElementById('resSummary').innerHTML = `<span style="color:var(--danger)">${e.message||'Erro ao carregar resultados.'}</span>`;
    }
}

function filterResultsTable() {
    const q      = document.getElementById('resSearch').value.trim().toLowerCase();
    const status = document.getElementById('resStatusFilter').value;

    const filtered = resAllRows.filter(r => {
        const matchText = !q ||
            r.name.toLowerCase().includes(q) ||
            r.code.toLowerCase().includes(q);
        const matchStatus = !status ||
            (status === 'passed' && r.passed) ||
            (status === 'failed' && !r.passed);
        return matchText && matchStatus;
    });

    renderResultsTable(filtered);
}

function renderResultsSummary(s) {
    if (!s || s.total === 0) {
        document.getElementById('resSummary').innerHTML =
            '<span style="color:var(--text-muted);font-size:.85rem">Ainda nenhum funcionário realizou este questionário.</span>';
        return;
    }
    const pct = s.total > 0 ? Math.round((s.passed / s.total) * 100) : 0;
    document.getElementById('resSummary').innerHTML = `
        <div style="display:flex;gap:16px;flex-wrap:wrap">
            <div style="background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);border-radius:10px;padding:14px 20px;min-width:120px;text-align:center">
                <div style="font-size:1.8rem;font-weight:800;color:var(--accent-light)">${s.total}</div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">Participantes</div>
            </div>
            <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);border-radius:10px;padding:14px 20px;min-width:120px;text-align:center">
                <div style="font-size:1.8rem;font-weight:800;color:#22c55e">${s.passed} <span style="font-size:1rem;font-weight:500">(${pct}%)</span></div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">Aprovados</div>
            </div>
            <div style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:10px;padding:14px 20px;min-width:120px;text-align:center">
                <div style="font-size:1.8rem;font-weight:800;color:#f59e0b">${s.avg_score ?? '—'}${s.avg_score!=null?'%':''}</div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">Nota média</div>
            </div>
            <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.15);border-radius:10px;padding:14px 20px;min-width:120px;text-align:center">
                <div style="font-size:1.1rem;font-weight:700;color:var(--text-muted);margin-top:4px">${s.passing_score}%</div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">Nota mínima</div>
            </div>
        </div>`;
}

function renderResultsTable(rows) {
    const body = document.getElementById('resTableBody');
    const counter = document.getElementById('resCount');
    if (!rows || rows.length === 0) {
        body.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted)">Sem resultados' + (resAllRows.length > 0 ? ' para o filtro aplicado.' : ' ainda.') + '</td></tr>';
        counter.textContent = resAllRows.length > 0 ? `0 de ${resAllRows.length}` : '';
        return;
    }
    counter.textContent = rows.length < resAllRows.length ? `${rows.length} de ${resAllRows.length}` : `${rows.length} funcionário${rows.length !== 1 ? 's' : ''}`;
    body.innerHTML = rows.map(r => {
        const passedBadge = r.passed
            ? '<span style="background:rgba(34,197,94,.15);color:#22c55e;padding:2px 9px;border-radius:20px;font-size:.72rem;font-weight:700">✓ Aprovado</span>'
            : '<span style="background:rgba(239,68,68,.12);color:#ef4444;padding:2px 9px;border-radius:20px;font-size:.72rem;font-weight:700">✗ Reprovado</span>';
        const date = r.last_attempt
            ? new Date(r.last_attempt).toLocaleDateString('pt-PT', {day:'2-digit',month:'2-digit',year:'numeric'})
            : '—';
        const scoreColor = r.passed ? '#22c55e' : '#ef4444';
        return `<tr>
            <td>
                <div style="font-weight:600;font-size:.875rem">${r.name}</div>
                <div style="font-size:.73rem;color:var(--text-muted);font-family:monospace">${r.code}</div>
            </td>
            <td style="text-align:center;font-weight:700;color:${scoreColor};font-size:1rem">${r.best_score}%</td>
            <td style="text-align:center">${passedBadge}</td>
            <td style="text-align:center;color:var(--text-muted);font-size:.82rem">${r.attempts}</td>
            <td style="text-align:center;color:var(--text-muted);font-size:.82rem">${date}</td>
        </tr>`;
    }).join('');
}

function exportResultsPDF() {
    if (!resAllRows.length) { alert('Sem dados para exportar.'); return; }
    const s      = resSummaryData;
    const pct    = s && s.total > 0 ? Math.round((s.passed / s.total) * 100) : 0;
    const nowObj = new Date();
    const nowFmt = nowObj.toLocaleDateString('pt-PT', {day:'2-digit', month:'long', year:'numeric'})
                 + ' às ' + nowObj.toLocaleTimeString('pt-PT', {hour:'2-digit', minute:'2-digit'});
    const logoUrl = window.TRAIN_CONFIG.logoUrl;

    const summaryHtml = s ? `
        <div class="summary">
            <div class="stat">        <div class="val">${s.total}</div>                                             <div class="lbl">Participantes</div></div>
            <div class="stat green">  <div class="val">${s.passed} <span class="val-sub">(${pct}%)</span></div>   <div class="lbl">Aprovados</div></div>
            <div class="stat amber">  <div class="val">${s.avg_score != null ? s.avg_score + '%' : '—'}</div>     <div class="lbl">Nota média</div></div>
            <div class="stat red">    <div class="val">${s.passing_score}%</div>                                   <div class="lbl">Nota mínima de aprovação</div></div>
        </div>` : '';

    const rowsHtml = resAllRows.map((r, i) => {
        const date = r.last_attempt
            ? new Date(r.last_attempt).toLocaleDateString('pt-PT', {day:'2-digit',month:'2-digit',year:'numeric'})
            : '—';
        return `<tr class="${i % 2 === 0 ? 'even' : ''}">
            <td><strong>${r.name}</strong><br><span class="code">${r.code}</span></td>
            <td class="center ${r.passed ? 'pass-score' : 'fail-score'}">${r.best_score}%</td>
            <td class="center">${r.passed
                ? '<span class="badge pass">✓ Aprovado</span>'
                : '<span class="badge fail">✗ Reprovado</span>'}</td>
            <td class="center muted">${r.attempts}</td>
            <td class="center muted">${date}</td>
        </tr>`;
    }).join('');

    const html = `<!DOCTYPE html><html lang="pt">
<head>
<meta charset="UTF-8">
<title>Resultados — ${escHtml(resCurrentTrainingTitle)}</title>
<style>
  @page { size: A4; margin: 18mm 14mm 24mm 14mm; }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

  /* ── Cabeçalho ── */
  .ph { border-bottom: 2px solid #6366f1; padding-bottom: 14px; margin-bottom: 22px; }
  .ph-top { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
  .ph-logo { display: flex; align-items: center; gap: 11px; }
  .ph-logo img { height: 44px; width: auto; object-fit: contain; }
  .ph-logo-text { font-size: 1.2rem; font-weight: 800; color: #1a1a2e; letter-spacing: -.5px; line-height: 1.2; }
  .ph-logo-text span { color: #6366f1; }
  .ph-meta { text-align: right; font-size: 10.5px; color: #6b7280; line-height: 1.75; }
  .ph-meta strong { color: #1a1a2e; font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 1px; }
  .ph-divider { margin-top: 13px; display: flex; align-items: center; gap: 10px; }
  .ph-divider-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6366f1; white-space: nowrap; padding: 3px 10px; background: #eef2ff; border-radius: 4px; }
  .ph-divider-line { flex: 1; height: 1px; background: #e0e7ff; }

  /* ── KPIs ── */
  .summary { display: flex; gap: 14px; margin-bottom: 22px; flex-wrap: wrap; }
  .stat { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; padding: 11px 16px; min-width: 108px; text-align: center; }
  .stat.green { background: #f0fdf4; border-color: #bbf7d0; }
  .stat.amber { background: #fffbeb; border-color: #fde68a; }
  .stat.red   { background: #fff1f2; border-color: #fecdd3; }
  .val { font-size: 20px; font-weight: 800; color: #1e293b; }
  .val-sub { font-size: 13px; font-weight: 500; }
  .stat.green .val { color: #16a34a; }
  .stat.amber .val { color: #d97706; }
  .stat.red   .val { color: #dc2626; }
  .lbl { font-size: 9.5px; color: #64748b; margin-top: 2px; text-transform: uppercase; letter-spacing: .5px; }

  /* ── Tabela ── */
  table { width: 100%; border-collapse: collapse; font-size: 11px; }
  thead th { background: #6366f1; color: #fff; padding: 9px 11px; text-align: left; font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; }
  thead th.center { text-align: center; }
  tbody td { padding: 8px 11px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
  tbody tr.even td { background: #f8fafc; }
  .center { text-align: center; }
  .muted  { color: #64748b; }
  .code   { font-family: monospace; font-size: 10px; color: #94a3b8; }
  .pass-score { color: #16a34a; font-weight: 700; }
  .fail-score { color: #dc2626; font-weight: 700; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9.5px; font-weight: 700; }
  .badge.pass { background: #dcfce7; color: #16a34a; }
  .badge.fail { background: #fee2e2; color: #dc2626; }

  /* ── Rodapé fixo ── */
  .pf { position: fixed; bottom: 0; left: 0; right: 0; padding: 7px 14mm; background: #fff; border-top: 1px solid #e0e7ff; display: flex; justify-content: space-between; align-items: center; font-size: 9.5px; color: #9ca3af; }
  .pf-left { display: flex; align-items: center; gap: 8px; }
  .pf-left img { height: 18px; width: auto; opacity: .55; }
  .pf-right { font-size: 9px; color: #c1c7d4; }

  @media print {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
</style>
</head>
<body>

  <!-- Cabeçalho -->
  <div class="ph">
    <div class="ph-top">
      <div class="ph-logo">
        <img src="${logoUrl}" alt="HRElectrominho">
        <div class="ph-logo-text">HR<span>Electrominho</span></div>
      </div>
      <div class="ph-meta">
        <strong>Resultados do Questionário</strong>
        <span style="font-size:14px;font-weight:700;color:#1a1a2e">${escHtml(resCurrentTrainingTitle)}</span><br>
        <span>Gerado em ${nowFmt}</span>
      </div>
    </div>
    <div class="ph-divider">
      <span class="ph-divider-label">Melhor tentativa por funcionário</span>
      <div class="ph-divider-line"></div>
    </div>
  </div>

  <!-- KPIs -->
  ${summaryHtml}

  <!-- Tabela -->
  <table>
    <thead>
      <tr>
        <th>Funcionário</th>
        <th class="center">Nota</th>
        <th class="center">Estado</th>
        <th class="center">Tentativas</th>
        <th class="center">Última tentativa</th>
      </tr>
    </thead>
    <tbody>${rowsHtml}</tbody>
  </table>

  <!-- Rodapé -->
  <div class="pf">
    <div class="pf-left">
      <img src="${logoUrl}" alt="HREminho">
      <span>HREminho — Sistema de Gestão de Recursos Humanos</span>
    </div>
    <div class="pf-right">Documento gerado automaticamente — Confidencial</div>
  </div>

</body></html>`;

    const w = window.open('', '_blank');
    w.document.write(html);
    w.document.close();
    w.focus();
    setTimeout(() => w.print(), 400);
}

/* ══════════════════════════════════════════
   Tab: Formações Obrigatórias
══════════════════════════════════════════ */
let mandatoryRules = [];
let departments    = [];
let positions      = [];

async function loadMandatory() {
    document.getElementById('mandatoryBody').innerHTML =
        '<tr class="state-row"><td colspan="7"><span class="spinner"></span>A carregar...</td></tr>';

    try {
        const [rules, depts, pos] = await Promise.all([
            apiFetch('GET', '/mandatory-trainings'),
            apiFetch('GET', '/departments?all=true').catch(() => ({ data: [] })),
            apiFetch('GET', '/positions?all=true').catch(() => ({ data: [] })),
        ]);
        mandatoryRules = rules.data ?? [];
        departments    = depts.data ?? [];
        positions      = pos.data  ?? [];

        // Preencher selects do modal com deps/cargos
        populateMandatorySelects();
        renderMandatoryTable(mandatoryRules);
        renderMandatorySummary(mandatoryRules);
    } catch(e) {
        document.getElementById('mandatoryBody').innerHTML =
            '<tr class="state-row"><td colspan="7">⚠️ Erro ao carregar regras.</td></tr>';
    }
}

function renderMandatorySummary(rules) {
    const el = document.getElementById('mandatorySummary');
    if (!rules.length) { el.innerHTML = ''; return; }
    const total   = rules.length;
    const ok      = rules.filter(r => r.rate >= 100).length;
    const warn    = rules.filter(r => r.rate >= 50 && r.rate < 100).length;
    const crit    = rules.filter(r => r.rate < 50).length;
    const missing = rules.reduce((s, r) => s + r.missing, 0);
    el.innerHTML = `
        <div style="background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);border-radius:10px;padding:12px 18px;text-align:center;min-width:110px">
            <div style="font-size:1.6rem;font-weight:800;color:var(--accent-light)">${total}</div>
            <div style="font-size:.74rem;color:var(--text-muted);margin-top:2px">Regras activas</div>
        </div>
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);border-radius:10px;padding:12px 18px;text-align:center;min-width:110px">
            <div style="font-size:1.6rem;font-weight:800;color:#22c55e">${ok}</div>
            <div style="font-size:.74rem;color:var(--text-muted);margin-top:2px">100% cumpridas</div>
        </div>
        <div style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:10px;padding:12px 18px;text-align:center;min-width:110px">
            <div style="font-size:1.6rem;font-weight:800;color:#f59e0b">${warn}</div>
            <div style="font-size:.74rem;color:var(--text-muted);margin-top:2px">Parciais</div>
        </div>
        <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.15);border-radius:10px;padding:12px 18px;text-align:center;min-width:110px">
            <div style="font-size:1.6rem;font-weight:800;color:#ef4444">${crit}</div>
            <div style="font-size:.74rem;color:var(--text-muted);margin-top:2px">Críticas (&lt;50%)</div>
        </div>
        <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.12);border-radius:10px;padding:12px 18px;text-align:center;min-width:110px">
            <div style="font-size:1.6rem;font-weight:800;color:#ef4444">${missing}</div>
            <div style="font-size:.74rem;color:var(--text-muted);margin-top:2px">Em falta (total)</div>
        </div>`;
}

function renderMandatoryTable(rules) {
    const body = document.getElementById('mandatoryBody');
    if (!rules.length) {
        body.innerHTML = '<tr class="state-row"><td colspan="7">Nenhuma regra definida. Clique em "🔒 Nova Regra" para começar.</td></tr>';
        return;
    }
    body.innerHTML = rules.map(r => {
        const rateColor = r.rate >= 100 ? '#22c55e' : r.rate >= 50 ? '#f59e0b' : '#ef4444';
        const rateBg    = r.rate >= 100 ? 'rgba(34,197,94,.12)' : r.rate >= 50 ? 'rgba(245,158,11,.12)' : 'rgba(239,68,68,.10)';
        const scopeBadge = r.target_type === 'all'
            ? '<span style="background:rgba(99,102,241,.12);color:var(--accent-light);padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">Todos</span>'
            : r.target_type === 'department'
                ? `<span style="background:rgba(6,182,212,.12);color:#06b6d4;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">Dept.</span> ${escHtml(r.target_name)}`
                : `<span style="background:rgba(168,85,247,.12);color:#a855f7;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">Cargo</span> ${escHtml(r.target_name)}`;
        const deadline = r.deadline_days ? `${r.deadline_days}d` : '<span style="color:var(--text-muted)">—</span>';
        const bar = `<div style="height:5px;background:rgba(255,255,255,.07);border-radius:4px;margin-top:4px;overflow:hidden">
            <div style="height:100%;width:${r.rate}%;background:${rateColor};border-radius:4px;transition:width .5s"></div></div>`;
        return `<tr>
            <td style="font-weight:600">${escHtml(r.training_title)}</td>
            <td>${scopeBadge}</td>
            <td>${deadline}</td>
            <td style="text-align:center;color:var(--text-muted);font-size:.82rem">${r.done}/${r.total}</td>
            <td style="min-width:100px">
                <div style="display:flex;justify-content:space-between;font-size:.78rem">
                    <span style="color:${rateColor};font-weight:700">${r.rate}%</span>
                    ${r.missing > 0 ? `<button onclick="openGaps(${r.id})" style="background:${rateBg};border:none;color:${rateColor};padding:1px 8px;border-radius:5px;font-size:.71rem;font-weight:700;cursor:pointer">${r.missing} em falta</button>` : '<span style="color:#22c55e;font-size:.72rem">✓ Completo</span>'}
                </div>
                ${bar}
            </td>
            <td style="font-size:.8rem;color:var(--text-muted)">${r.notes ? escHtml(r.notes) : '—'}</td>
            <td style="text-align:center">
                <button class="btn-sm btn-edit" onclick="editMandatory(${r.id})">✏️</button>
                <button class="btn-sm btn-del"  onclick="deleteMandatory(${r.id})" style="margin-left:4px">🗑️</button>
            </td>
        </tr>`;
    }).join('');
}

function populateMandatorySelects() {
    // Formações
    const tSel = document.getElementById('mTrainingId');
    tSel.innerHTML = '<option value="">— Selecionar formação —</option>';
    trainings.forEach(t => { tSel.innerHTML += `<option value="${t.id}">${escHtml(t.title)}</option>`; });
}

function onTargetTypeChange() {
    const type = document.getElementById('mTargetType').value;
    const wrap  = document.getElementById('mTargetIdWrap');
    const label = document.getElementById('mTargetIdLabel');
    const sel   = document.getElementById('mTargetId');
    if (type === 'all') { wrap.style.display = 'none'; return; }
    wrap.style.display = '';
    label.textContent  = type === 'department' ? 'Departamento *' : 'Cargo *';
    const items = type === 'department' ? departments : positions;
    const key   = type === 'department' ? 'department' : 'position';
    sel.innerHTML = '<option value="">— Selecionar —</option>';
    items.forEach(i => { sel.innerHTML += `<option value="${i.id}">${escHtml(i[key])}</option>`; });
    sel.required = true;
}

function openMandatoryModal(rule = null) {
    document.getElementById('mandatoryId').value          = rule ? rule.id : '';
    document.getElementById('mandatoryModalTitle').textContent = rule ? '✏️ Editar Regra' : '🔒 Nova Regra Obrigatória';
    document.getElementById('mTrainingId').value          = rule ? rule.training_id : '';
    document.getElementById('mTargetType').value          = rule ? rule.target_type : 'all';
    document.getElementById('mDeadlineDays').value        = rule?.deadline_days ?? '';
    document.getElementById('mNotes').value               = rule?.notes ?? '';

    // Bloquear campos de formação/âmbito na edição
    document.getElementById('mTrainingId').disabled = !!rule;
    document.getElementById('mTargetType').disabled = !!rule;

    onTargetTypeChange();
    if (rule && rule.target_id) document.getElementById('mTargetId').value = rule.target_id;
    document.getElementById('mTargetId').disabled = !!rule;

    openOverlay('mandatoryOverlay');
}

function editMandatory(id) {
    const rule = mandatoryRules.find(r => r.id === id);
    if (rule) openMandatoryModal(rule);
}

async function submitMandatory(e) {
    e.preventDefault();
    const id   = document.getElementById('mandatoryId').value;
    const type = document.getElementById('mTargetType').value;
    const body = id ? {
        deadline_days: parseInt(document.getElementById('mDeadlineDays').value) || null,
        notes:         document.getElementById('mNotes').value.trim() || null,
    } : {
        training_id:   parseInt(document.getElementById('mTrainingId').value),
        target_type:   type,
        target_id:     type !== 'all' ? parseInt(document.getElementById('mTargetId').value) || null : null,
        deadline_days: parseInt(document.getElementById('mDeadlineDays').value) || null,
        notes:         document.getElementById('mNotes').value.trim() || null,
    };
    try {
        if (id) {
            await apiFetch('PUT', `/mandatory-trainings/${id}`, body);
            toast('Regra actualizada.', 'ok');
        } else {
            await apiFetch('POST', '/mandatory-trainings', body);
            toast('Regra criada com sucesso.', 'ok');
        }
        closeOverlay('mandatoryOverlay');
        loadMandatory();
    } catch(err) {
        toast(err.message || 'Erro ao guardar.', 'err');
    }
}

async function deleteMandatory(id) {
    const rule = mandatoryRules.find(r => r.id === id);
    if (!confirm(`Remover obrigatoriedade de "${rule?.training_title}"?`)) return;
    try {
        await apiFetch('DELETE', `/mandatory-trainings/${id}`);
        toast('Regra removida.', 'ok');
        loadMandatory();
    } catch(e) {
        toast('Erro ao remover.', 'err');
    }
}

async function openGaps(ruleId) {
    const rule = mandatoryRules.find(r => r.id === ruleId);
    document.getElementById('gapsModalTitle').textContent = rule?.training_title ?? '…';
    document.getElementById('gapsSummary').innerHTML = '';
    document.getElementById('gapsBody').innerHTML =
        '<tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted)">A carregar…</td></tr>';
    openOverlay('gapsOverlay');
    try {
        const res = await apiFetch('GET', `/mandatory-trainings/${ruleId}/gaps`);
        const s = res.summary;
        const rateColor = s.rate >= 100 ? '#22c55e' : s.rate >= 50 ? '#f59e0b' : '#ef4444';
        document.getElementById('gapsSummary').innerHTML = `
            <div style="background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);border-radius:9px;padding:10px 16px;text-align:center;min-width:90px">
                <div style="font-size:1.4rem;font-weight:800;color:var(--accent-light)">${s.total}</div>
                <div style="font-size:.72rem;color:var(--text-muted)">Abrangidos</div>
            </div>
            <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);border-radius:9px;padding:10px 16px;text-align:center;min-width:90px">
                <div style="font-size:1.4rem;font-weight:800;color:#22c55e">${s.done}</div>
                <div style="font-size:.72rem;color:var(--text-muted)">Concluíram</div>
            </div>
            <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.15);border-radius:9px;padding:10px 16px;text-align:center;min-width:90px">
                <div style="font-size:1.4rem;font-weight:800;color:#ef4444">${s.missing}</div>
                <div style="font-size:.72rem;color:var(--text-muted)">Em falta</div>
            </div>
            <div style="background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:9px;padding:10px 16px;text-align:center;min-width:90px">
                <div style="font-size:1.4rem;font-weight:800;color:${rateColor}">${s.rate}%</div>
                <div style="font-size:.72rem;color:var(--text-muted)">Cumprimento</div>
            </div>`;
        if (!res.data.length) {
            document.getElementById('gapsBody').innerHTML =
                '<tr><td colspan="4" style="text-align:center;padding:24px;color:#22c55e">✅ Todos os funcionários abrangidos já realizaram esta formação.</td></tr>';
            return;
        }
        document.getElementById('gapsBody').innerHTML = res.data.map(e => `<tr>
            <td>
                <div style="font-weight:600">${escHtml(e.full_name)}</div>
                <div style="font-size:.73rem;color:var(--text-muted);font-family:monospace">${e.code}</div>
            </td>
            <td style="color:var(--text-muted);font-size:.83rem">${e.department ?? '—'}</td>
            <td style="color:var(--text-muted);font-size:.83rem">${e.position ?? '—'}</td>
            <td style="text-align:center;color:var(--text-muted);font-size:.82rem">${e.hire_date ?? '—'}</td>
        </tr>`).join('');
    } catch(e) {
        document.getElementById('gapsBody').innerHTML =
            '<tr><td colspan="4" style="text-align:center;padding:24px;color:#ef4444">Erro ao carregar dados.</td></tr>';
    }
}

/* ── Expor funções para o escopo global (necessário para onclick inline no HTML) ── */
/* ── Certificado ── */
function onCertFileChange(event){
    const file = event.target.files[0];
    document.getElementById('certFileName').textContent = file ? file.name : '';
}

async function uploadCertificate(enrollmentId){
    const fileInput = document.getElementById('certFileInput');
    if(!fileInput.files.length) return;
    const form = new FormData();
    form.append('certificate', fileInput.files[0]);
    const r = await fetch(`/api/v1/enrollments/${enrollmentId}/certificate`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: form,
    });
    if(!r.ok){ const e = await r.json().catch(()=>({message:'Erro ao carregar certificado'})); throw e; }
    return r.json();
}

async function removeCertificate(){
    if(!enrollEditId) return;
    if(!confirm('Remover o certificado desta inscrição?')) return;
    try {
        await apiFetch('PUT', `/enrollments/${enrollEditId}`, { certificate_path: null });
        document.getElementById('certCurrentWrap').style.display = 'none';
        document.getElementById('certCurrentLink').href = '#';
        toast('Certificado removido.', 'ok');
    } catch(e){ toast(e.message ?? 'Erro ao remover certificado.', 'err'); }
}


Object.assign(window, {
    switchTab,
    ctSwitch,
    resetFilters,
    applyFilters,
    filterResultsTable,
    exportResultsPDF,
    openCreateTraining,
    confirmDelete,
    closeOverlay,
    setVideoMode,
    addVideo,
    addQuestion,
    pickQuestionType,
    saveQuiz,
    openCreateEnroll,
    openEditEnroll,
    submitEnroll,
    enrollRemoveEmp,
    enrollFilterEmpOptions,
    enrollEmpKeydown,
    enrollOpenEmpDropdown,
    applyCatalogFilters,
    resetCatalogFilters,
    setCatalogSort,
    openMandatoryModal,
    onTargetTypeChange,
    loadSessionsForEnroll,
    updateExpiryHint,
    cbKeydown,
    filterByValidity,
    submitMandatory,
    submitTraining,
    updateScoreState,
    onCertFileChange,
    removeCertificate,
});
