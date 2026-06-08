/**
 * reports.js — Lógica da página de Relatórios
 * Depende de window.REPORT_CONFIG injectado pelo Blade:
 *   { currentYear }
 */

const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let currentTab = 'employees';

/* ══════════════════════════════════════════
   COMPONENTE MULTI-SELECT
══════════════════════════════════════════ */
class MultiSelect {
    constructor(wrapId, placeholder = 'Todos') {
        this.wrap        = document.getElementById(wrapId);
        this.placeholder = placeholder;
        this.selected    = new Set();
        this.items       = []; // [{value, label}]
        this._build();
    }
    _build() {
        this.wrap.innerHTML = `
            <div class="ms-trigger" tabindex="0">
                <span class="ms-label">${this.placeholder}</span>
                <span class="ms-arrow">▼</span>
            </div>
            <div class="ms-dropdown">
                <div class="ms-search-wrap">
                    <input class="ms-search" type="text" placeholder="Pesquisar…">
                </div>
                <div class="ms-list"></div>
                <div class="ms-footer">
                    <span class="ms-footer-info">0 selecionado(s)</span>
                    <button class="ms-clear" type="button">Limpar</button>
                </div>
            </div>`;
        this._trigger  = this.wrap.querySelector('.ms-trigger');
        this._dropdown = this.wrap.querySelector('.ms-dropdown');
        this._list     = this.wrap.querySelector('.ms-list');
        this._search   = this.wrap.querySelector('.ms-search');
        this._info     = this.wrap.querySelector('.ms-footer-info');
        this._clearBtn = this.wrap.querySelector('.ms-clear');

        this._trigger.addEventListener('click', e => { e.stopPropagation(); this.toggle(); });
        this._search.addEventListener('input', () => this._renderItems(this._search.value));
        this._clearBtn.addEventListener('click', () => { this.selected.clear(); this._renderItems(); this._updateTrigger(); });
        document.addEventListener('click', e => { if (!this.wrap.contains(e.target)) this.close(); });
        this._trigger.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.toggle(); } if (e.key === 'Escape') this.close(); });
    }
    setItems(items) {
        this.items = items;
        this._renderItems();
        this._updateTrigger();
    }
    _renderItems(query = '') {
        const q = query.toLowerCase();
        const filtered = q ? this.items.filter(i => i.label.toLowerCase().includes(q)) : this.items;
        if (!filtered.length) {
            this._list.innerHTML = '<div style="padding:10px 12px;font-size:.82rem;color:var(--text-muted)">Sem resultados</div>';
            return;
        }
        this._list.innerHTML = filtered.map(i => `
            <div class="ms-item ${this.selected.has(i.value) ? 'selected' : ''}" data-val="${i.value}">
                <input type="checkbox" ${this.selected.has(i.value) ? 'checked' : ''} tabindex="-1">
                <span>${i.label}</span>
            </div>`).join('');
        this._list.querySelectorAll('.ms-item').forEach(el => {
            el.addEventListener('click', e => {
                e.stopPropagation();
                const v = el.dataset.val;
                if (this.selected.has(v)) this.selected.delete(v);
                else this.selected.add(v);
                el.classList.toggle('selected', this.selected.has(v));
                el.querySelector('input').checked = this.selected.has(v);
                this._updateTrigger();
            });
        });
        this._info.textContent = this.selected.size + ' selecionado(s)';
    }
    _updateTrigger() {
        const n = this.selected.size;
        const label = this._trigger.querySelector('.ms-label');
        const badge = this._trigger.querySelector('.ms-badge');
        if (badge) badge.remove();
        if (n === 0) {
            label.textContent = this.placeholder;
        } else if (n === 1) {
            const item = this.items.find(i => i.value === [...this.selected][0]);
            label.textContent = item ? item.label : this.placeholder;
            this._appendBadge(n);
        } else {
            label.textContent = this.placeholder;
            this._appendBadge(n);
        }
        this._info.textContent = n + ' selecionado(s)';
    }
    _appendBadge(n) {
        const b = document.createElement('span');
        b.className = 'ms-badge';
        b.textContent = n;
        this._trigger.appendChild(b);
    }
    toggle() {
        if (this._dropdown.classList.contains('open')) this.close();
        else this.open();
    }
    open() {
        this._trigger.classList.add('open');
        this._dropdown.classList.add('open');
        this._search.value = '';
        this._renderItems();
        this._search.focus();
    }
    close() {
        this._trigger.classList.remove('open');
        this._dropdown.classList.remove('open');
    }
    getValues() { return [...this.selected]; }
    reset() { this.selected.clear(); this._renderItems(); this._updateTrigger(); }
}

/* Instâncias globais */
let msEPosition, msTTraining, msTPosition;


/* ══════════════════════════════════════════
   EXPORTAÇÃO EXCEL (SheetJS)
══════════════════════════════════════════ */
function exportExcel(tab) {
    const today = new Date().toLocaleDateString('pt-PT').replace(/\//g, '-');
    const titleMap = {
        employees:  'Funcionarios_com_Formacoes',
        trainings:  'Formacoes_por_Funcionarios',
        attendance: 'Assiduidade',
        validity:   'Validade_Formacoes',
    };
    const filename = `HREminho_${titleMap[tab]}_${today}.xlsx`;
    let wb, ws, data;

    if (tab === 'employees') {
        // Uma linha por funcionário, com as formações em colunas separadas por vírgula
        data = [['Código', 'Nome', 'Função', 'Setor', 'Total Formações', 'Formações Concluídas']];
        empData.forEach(e => {
            const formacoes = (e.trainings || []).map(t => t.title).join('; ');
            data.push([e.code || '—', e.name, e.position, e.sector, e.total_completed, formacoes]);
        });
        ws = XLSX.utils.aoa_to_sheet(data);
        ws['!cols'] = [{wch:10},{wch:30},{wch:25},{wch:20},{wch:10},{wch:60}];

    } else if (tab === 'trainings') {
        // Uma aba por formação seria ideal, mas para simplificar: linha por funcionário dentro de cada formação
        data = [['Formação', 'Fornecedor', 'Nome Funcionário', 'Código', 'Setor', 'Função', 'Pontuação', 'Data Conclusão']];
        trainingsData.forEach(t => {
            (t.employees || []).forEach(e => {
                data.push([t.title, t.provider || '—', e.name, e.code || '—', e.sector, e.position, e.score != null ? e.score : '—', e.completed_at ? new Date(e.completed_at).toLocaleDateString('pt-PT') : '—']);
            });
        });
        ws = XLSX.utils.aoa_to_sheet(data);
        ws['!cols'] = [{wch:40},{wch:25},{wch:30},{wch:10},{wch:20},{wch:25},{wch:10},{wch:16}];

    } else if (tab === 'attendance') {
        // Lê directamente as linhas da tabela DOM (dados já renderizados)
        const rows = document.querySelectorAll('#a-tbody tr');
        data = [['Funcionário', 'Setor', 'Data', 'Entrada', 'Saída', 'Estado']];
        rows.forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length >= 6) {
                data.push([
                    cells[0].textContent.trim(),
                    cells[1].textContent.trim(),
                    cells[2].textContent.trim(),
                    cells[3].textContent.trim(),
                    cells[4].textContent.trim(),
                    cells[5].textContent.trim(),
                ]);
            }
        });
        ws = XLSX.utils.aoa_to_sheet(data);
        ws['!cols'] = [{wch:30},{wch:20},{wch:14},{wch:10},{wch:10},{wch:12}];

    } else if (tab === 'validity') {
        data = [['Funcionário', 'Código', 'Setor', 'Função', 'Formação', 'Fornecedor', 'Data Fim', 'Validade (meses)', 'Data Expiração', 'Estado']];
        const vStatLabel = { valid: 'Válida', expiring: 'A expirar', expired: 'Expirada' };
        validityData.forEach(r => {
            data.push([
                r.employee,
                r.employee_code || '—',
                r.sector,
                r.position,
                r.training,
                r.provider || '—',
                r.end_date    ? new Date(r.end_date    + 'T00:00:00').toLocaleDateString('pt-PT') : '—',
                r.validity_months || '—',
                r.expiry_date ? new Date(r.expiry_date + 'T00:00:00').toLocaleDateString('pt-PT') : '—',
                vStatLabel[r.validity_status] || '—',
            ]);
        });
        ws = XLSX.utils.aoa_to_sheet(data);
        ws['!cols'] = [{wch:30},{wch:10},{wch:20},{wch:25},{wch:40},{wch:25},{wch:14},{wch:10},{wch:16},{wch:12}];
    }

    // Estilo do cabeçalho
    const headerRange = XLSX.utils.decode_range(ws['!ref']);
    for (let C = headerRange.s.c; C <= headerRange.e.c; C++) {
        const addr = XLSX.utils.encode_cell({r: 0, c: C});
        if (!ws[addr]) continue;
        ws[addr].s = {
            font: { bold: true, color: { rgb: 'FFFFFF' } },
            fill: { fgColor: { rgb: '6366F1' } },
            alignment: { horizontal: 'center' },
        };
    }

    wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, titleMap[tab].replace(/_/g, ' ').substring(0, 31));
    XLSX.writeFile(wb, filename);
    showToast('Ficheiro Excel gerado com sucesso!', 'success');
}

async function loadDropdowns() {
    const [sectors, trainings, positions, employees] = await Promise.all([
        fetch('/api/v1/sectors?all=1',{credentials:'same-origin'}).then(r => r.json()),
        fetch('/api/v1/trainings?all=1',{credentials:'same-origin'}).then(r => r.json()),
        fetch('/api/v1/positions?all=1',{credentials:'same-origin'}).then(r => r.json()),
        fetch('/api/v1/employees?all=true',{credentials:'same-origin'}).then(r => r.json()),
    ]);
    const sectorList   = sectors.data   ?? sectors;
    const trainingList = trainings.data ?? trainings;
    const positionList = positions.data ?? positions;
    const employeeList = employees.data ?? employees;

    ['e-sector','t-sector','a-sector','v-sector'].forEach(id => {
        const el = document.getElementById(id);
        sectorList.forEach(s => el.add(new Option(s.sector, s.id)));
    });

    // Multi-select: Formação (tab trainings)
    msTTraining = new MultiSelect('ms-t-training-wrap', 'Todas as formações');
    msTTraining.setItems(trainingList.map(t => ({ value: String(t.id), label: t.title })));

    // Multi-select: Função (tab trainings)
    msTPosition = new MultiSelect('ms-t-position-wrap', 'Todas as funções');
    msTPosition.setItems(positionList.map(p => ({ value: String(p.id), label: p.position })));

    // Multi-select: Função (tab employees)
    msEPosition = new MultiSelect('ms-e-position-wrap', 'Todas as funções');
    msEPosition.setItems(positionList.map(p => ({ value: String(p.id), label: p.position })));

    // Validade — selects simples
    const vt = document.getElementById('v-training');
    trainingList.forEach(t => vt.add(new Option(t.title, t.id)));

    ['a-employee','v-employee'].forEach(id => {
        const el = document.getElementById(id);
        employeeList.forEach(e => {
            const label = (e.full_name || (e.first_name + ' ' + e.last_name).trim()) + (e.code ? ' (' + e.code + ')' : '');
            el.add(new Option(label.trim(), e.id));
        });
    });
}

function switchTab(tab) {
    currentTab = tab;
    ['employees','trainings','attendance','validity','gaps'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === tab ? '' : 'none';
    });
    document.querySelectorAll('.report-tab').forEach((btn, i) => {
        btn.classList.toggle('active', ['employees','trainings','attendance','validity','gaps'][i] === tab);
    });
    if (tab === 'employees'  && !document.getElementById('e-count').dataset.loaded) loadEmployees();
    if (tab === 'trainings'  && !document.getElementById('t-count').dataset.loaded) loadTrainings();
    if (tab === 'attendance' && !document.getElementById('a-count').dataset.loaded) loadAttendance();
    if (tab === 'validity'   && !document.getElementById('v-count').dataset.loaded) loadValidity();
    if (tab === 'gaps'       && !gapsLoaded) loadGaps();
}

function qs(params) {
    const parts = [];
    for (const [k, v] of Object.entries(params)) {
        if (!v && v !== 0) continue;
        if (Array.isArray(v)) {
            v.forEach(item => parts.push(`${k}[]=${encodeURIComponent(item)}`));
        } else {
            parts.push(`${k}=${encodeURIComponent(v)}`);
        }
    }
    return parts.join('&');
}
function fmt(d) { return d ? new Date(d + 'T00:00:00').toLocaleDateString('pt-PT') : '—'; }
function statusBadge(s) {
    const map = { present:'badge-present', absent:'badge-absent', late:'badge-late' };
    const lbl = { present:'Presente', absent:'Ausente', late:'Atrasado' };
    return `<span class="badge ${map[s] ?? ''}">${lbl[s] ?? s}</span>`;
}

let empData = [];
const AVATAR_COLORS = [
    ['rgba(99,102,241,0.25)','#818cf8'],['rgba(236,72,153,0.2)','#f472b6'],
    ['rgba(34,197,94,0.2)','#4ade80'],['rgba(245,158,11,0.2)','#fbbf24'],
    ['rgba(59,130,246,0.2)','#60a5fa'],['rgba(239,68,68,0.2)','#f87171'],
    ['rgba(20,184,166,0.2)','#2dd4bf'],['rgba(168,85,247,0.2)','#c084fc'],
];
function avatarColor(name) {
    let h = 0;
    for (let i = 0; i < name.length; i++) h = (h * 31 + name.charCodeAt(i)) & 0xffff;
    return AVATAR_COLORS[h % AVATAR_COLORS.length];
}
function initials(name) {
    return name.split(' ').filter(Boolean).slice(0,2).map(w => w[0]).join('').toUpperCase();
}
function scoreClass(s) {
    if (s === null || s === undefined || s === '') return 'score-none';
    const n = parseFloat(s);
    if (n >= 75) return 'score-high';
    if (n >= 50) return 'score-mid';
    return 'score-low';
}
function scoreLabel(s) {
    if (s === null || s === undefined || s === '') return '—';
    return parseFloat(s) + 'pt';
}
function showSkeletons() {
    document.getElementById('e-cards-grid').innerHTML = Array(6).fill('<div class="emp-skeleton"></div>').join('');
}

async function loadEmployees() {
    showSkeletons();
    document.getElementById('e-count').textContent = '—';
    document.getElementById('e-count').dataset.loaded = '';
    const params = qs({
        sector_id:   document.getElementById('e-sector').value,
        position_id: msEPosition ? msEPosition.getValues() : [],
    });
    const res = await fetch('/api/v1/reports/employees-trainings?' + params, {credentials:'same-origin'}).then(r => r.json());
    empData = res.data || [];
    const totalTrainings = empData.reduce((s,e) => s + (e.total_completed||0), 0);
    const avg = empData.length ? (totalTrainings / empData.length).toFixed(1) : '—';
    const top = empData.length ? [...empData].sort((a,b) => b.total_completed - a.total_completed)[0] : null;
    document.getElementById('kpi-total').textContent     = empData.length;
    document.getElementById('kpi-trainings').textContent = totalTrainings;
    document.getElementById('kpi-avg').textContent       = avg;
    if (top) {
        document.getElementById('kpi-top').textContent     = top.name.split(' ')[0];
        document.getElementById('kpi-top-sub').textContent = top.total_completed + (top.total_completed !== 1 ? ' formações' : ' formação');
    }
    document.getElementById('e-count').dataset.loaded = '1';
    renderCards();
}

function renderCards() {
    const grid   = document.getElementById('e-cards-grid');
    const search = (document.getElementById('e-search').value || '').toLowerCase().trim();
    const sort   = document.getElementById('e-sort').value;
    let data = empData.filter(e => {
        if (!search) return true;
        return (e.name||'').toLowerCase().includes(search)
            || (e.sector||'').toLowerCase().includes(search)
            || (e.position||'').toLowerCase().includes(search)
            || (e.code||'').toLowerCase().includes(search);
    });
    if (sort === 'name')       data.sort((a,b) => a.name.localeCompare(b.name));
    if (sort === 'total_desc') data.sort((a,b) => b.total_completed - a.total_completed);
    if (sort === 'total_asc')  data.sort((a,b) => a.total_completed - b.total_completed);
    if (sort === 'sector')     data.sort((a,b) => (a.sector||'').localeCompare(b.sector||''));
    document.getElementById('e-count').textContent = data.length + ' funcionário(s)';
    const maxT = data.length ? Math.max(...data.map(e => e.total_completed||0)) : 1;
    if (!data.length) { grid.innerHTML = '<div class="emp-empty"><span>👥</span>Nenhum funcionário encontrado.</div>'; return; }
    grid.innerHTML = data.map((e, idx) => {
        const [bgColor, textColor] = avatarColor(e.name);
        const pct = maxT > 0 ? Math.round((e.total_completed / maxT) * 100) : 0;
        const progColor = pct >= 75 ? 'var(--success)' : pct >= 40 ? 'var(--warning)' : 'var(--accent)';
        const trainingsHtml = (e.trainings||[]).map(t => `
            <div class="emp-training-row">
                <div class="emp-training-icon">🎓</div>
                <div class="emp-training-info">
                    <div class="emp-training-title">${t.title}</div>
                    <div class="emp-training-date">${fmt(t.completed_at)}</div>
                </div>
                <span class="emp-training-score ${scoreClass(t.score)}">${scoreLabel(t.score)}</span>
            </div>`).join('');
        return `<div class="emp-card" id="emp-card-${idx}">
            <div class="emp-card-header" onclick="toggleCard(${idx})">
                <div class="emp-avatar" style="background:${bgColor};color:${textColor}">${initials(e.name)}</div>
                <div class="emp-card-info">
                    <div class="emp-card-name">${e.name}<span class="emp-card-code">${e.code}</span></div>
                    <div class="emp-card-meta">${e.position} · ${e.sector}</div>
                </div>
                <div class="emp-card-right">
                    <span class="emp-badge-count">${e.total_completed !== 1 ? e.total_completed + ' formações' : '1 formação'}</span>
                    <span class="emp-chevron">▼</span>
                </div>
            </div>
            <div class="emp-card-progress">
                <div class="emp-prog-bar"><div class="emp-prog-fill" style="width:${pct}%;background:${progColor}"></div></div>
                <span class="emp-prog-label">${pct}%</span>
            </div>
            <div class="emp-trainings-body">
                <div class="emp-trainings-inner">
                    ${trainingsHtml || '<div style="color:var(--text-muted);font-size:0.82rem;text-align:center;padding:8px 0">Sem formações registadas</div>'}
                </div>
            </div>
        </div>`;
    }).join('');
}
function toggleCard(idx) { document.getElementById('emp-card-' + idx).classList.toggle('open'); }
function setView(v) {
    document.getElementById('e-cards-grid').classList.toggle('list-view', v === 'list');
    document.getElementById('btn-grid').classList.toggle('active', v === 'grid');
    document.getElementById('btn-list').classList.toggle('active', v === 'list');
}
function resetEmployees() {
    document.getElementById('e-sector').value = '';
    if (msEPosition) msEPosition.reset();
    document.getElementById('e-search').value = '';
    loadEmployees();
}

let trainingsData = [];

async function loadTrainings() {
    const list = document.getElementById('t-list');
    list.innerHTML = '<div class="state-msg">A carregar…</div>';
    const params = qs({
        training_id:  msTTraining  ? msTTraining.getValues()  : [],
        position_id:  msTPosition  ? msTPosition.getValues()  : [],
        sector_id:    document.getElementById('t-sector').value,
    });
    const res = await fetch('/api/v1/reports/training-employees?' + params, {credentials:'same-origin'}).then(r => r.json());
    trainingsData = res.data || [];
    const count = document.getElementById('t-count');
    count.textContent = res.total !== 1 ? res.total + ' formações' : '1 formação';
    count.dataset.loaded = '1';
    if (!trainingsData.length) { list.innerHTML = '<div class="state-msg">Sem resultados.</div>'; return; }
    list.innerHTML = trainingsData.map(t => `
        <div class="table-wrap" style="margin-bottom:20px">
            <div style="padding:12px 16px;background:rgba(99,102,241,0.08);border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                <div>
                    <span style="font-weight:700;font-size:.92rem">📚 ${t.title}</span>
                    <span style="color:var(--text-muted);font-size:.78rem;margin-left:10px">${t.provider}</span>
                </div>
                <span style="background:rgba(99,102,241,0.15);color:var(--accent-light);padding:3px 12px;border-radius:20px;font-size:.75rem;font-weight:700">${t.total} funcionário(s)</span>
            </div>
            <table>
                <thead><tr><th>Código</th><th>Funcionário</th><th>Função</th><th>Setor</th><th>Pontuação</th><th>Concluído em</th></tr></thead>
                <tbody>${t.employees.map(e => `<tr>
                    <td><span style="font-family:monospace;font-size:.78rem;color:var(--text-muted)">${e.code}</span></td>
                    <td>${e.name}</td><td>${e.position}</td><td>${e.sector}</td>
                    <td>${e.score ?? '—'}</td><td>${fmt(e.completed_at)}</td>
                </tr>`).join('')}</tbody>
            </table>
        </div>`).join('');
}
function resetTrainings() {
    document.getElementById('t-sector').value = '';
    if (msTTraining) msTTraining.reset();
    if (msTPosition) msTPosition.reset();
    loadTrainings();
}

async function loadAttendance() {
    const tbody = document.getElementById('a-tbody');
    tbody.innerHTML = '<tr><td colspan="6" class="state-msg">A carregar…</td></tr>';
    const params = qs({ employee_id: document.getElementById('a-employee').value, sector_id: document.getElementById('a-sector').value, status: document.getElementById('a-status').value, date_from: document.getElementById('a-from').value, date_to: document.getElementById('a-to').value });
    const res = await fetch('/api/v1/reports/attendance?' + params, {credentials:'same-origin'}).then(r => r.json());
    const count = document.getElementById('a-count');
    count.textContent = res.total + ' registo(s)';
    count.dataset.loaded = '1';
    const sumGrid = document.getElementById('a-summary');
    if (res.summary && res.summary.length) {
        sumGrid.innerHTML = res.summary.map(s => `
            <div class="summary-card">
                <div class="s-name" title="${s.employee}">${s.employee}</div>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:6px">${s.sector} · ${s.position}</div>
                <div class="s-stats">
                    <span>✅ <strong>${s.present}</strong> pres.</span>
                    <span>❌ <strong>${s.absent}</strong> aus.</span>
                    <span>⏰ <strong>${s.late}</strong> atras.</span>
                </div>
                <div class="s-rate" style="color:var(--text-muted)">Taxa: <strong style="color:${s.rate>=80?'var(--success)':s.rate>=60?'var(--warning)':'var(--danger)'}">${s.rate}%</strong></div>
                <div class="progress-bar"><div class="progress-bar-fill" style="width:${s.rate}%;background:${s.rate>=80?'var(--success)':s.rate>=60?'var(--warning)':'var(--danger)'}"></div></div>
            </div>`).join('');
        sumGrid.style.display = '';
    } else {
        sumGrid.innerHTML = '';
        sumGrid.style.display = 'none';
    }
    if (!res.data.length) { tbody.innerHTML = '<tr><td colspan="6" class="state-msg">Sem resultados.</td></tr>'; return; }
    tbody.innerHTML = res.data.map(r => `<tr>
        <td>${r.employee}</td><td>${r.sector}</td><td>${fmt(r.date)}</td>
        <td>${r.check_in}</td><td>${r.check_out}</td><td>${statusBadge(r.status)}</td>
    </tr>`).join('');
}
function resetAttendance() {
    ['a-employee','a-sector','a-status','a-from','a-to'].forEach(id => { document.getElementById(id).value = ''; });
    loadAttendance();
}

/* ══════════════════════════════════════════
   TAB VALIDADE
══════════════════════════════════════════ */
let validityData = [];
let vActiveKpi   = '';
let gapsLoaded   = false;

const vLabel = { valid:'✅ Válida', expiring:'🔔 A expirar', expired:'⚠️ Expirada' };
const vClass  = { valid:'vbadge-valid', expiring:'vbadge-expiring', expired:'vbadge-expired' };
const vRowCls = { expired:'row-expired', expiring:'row-expiring', valid:'' };

async function loadValidity() {
    const tbody = document.getElementById('v-tbody');
    tbody.innerHTML = '<tr><td colspan="10" class="state-msg">A carregar…</td></tr>';
    document.getElementById('v-count').textContent = '—';
    document.getElementById('v-count').dataset.loaded = '';

    const params = qs({
        validity_status : document.getElementById('v-status').value,
        employee_id     : document.getElementById('v-employee').value,
        training_id     : document.getElementById('v-training').value,
        sector_id       : document.getElementById('v-sector').value,
    });

    const res = await fetch('/api/v1/reports/validity?' + params, {credentials:'same-origin'}).then(r => r.json());
    validityData = res.data || [];

    // Atualizar KPIs
    const kpi = res.kpi || {};
    document.getElementById('vkpi-total').textContent        = kpi.total    ?? 0;
    document.getElementById('vkpi-expired-val').textContent  = kpi.expired  ?? 0;
    document.getElementById('vkpi-expiring-val').textContent = kpi.expiring ?? 0;
    document.getElementById('vkpi-valid-val').textContent    = kpi.valid    ?? 0;

    document.getElementById('v-count').textContent  = res.total + ' registo(s)';
    document.getElementById('v-count').dataset.loaded = '1';

    renderValidity(validityData);
}

function renderValidity(rows) {
    const tbody = document.getElementById('v-tbody');
    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="10" class="state-msg">Nenhum registo encontrado.</td></tr>';
        return;
    }
    tbody.innerHTML = rows.map(r => {
        const vs    = r.validity_status;
        const badge = vs ? `<span class="vbadge ${vClass[vs]??''}">${vLabel[vs]??vs}</span>` : '—';
        const rowCls= vRowCls[vs] ?? '';
        return `<tr class="${rowCls}">
            <td style="font-weight:600">${r.employee}</td>
            <td><span style="font-family:monospace;font-size:.78rem;color:var(--text-muted)">${r.employee_code}</span></td>
            <td>${r.sector}</td>
            <td>${r.position}</td>
            <td>${r.training}</td>
            <td style="color:var(--text-muted);font-size:.82rem">${r.provider}</td>
            <td style="color:var(--text-muted)">${fmt(r.end_date)}</td>
            <td style="font-size:.82rem">${r.validity_months ? r.validity_months + ' mês' + (r.validity_months > 1 ? 'es' : '') : '—'}</td>
            <td style="font-weight:600">${fmt(r.expiry_date)}</td>
            <td>${badge}</td>
        </tr>`;
    }).join('');
}

function vFilterKpi(status) {
    // Toggle: clicar novamente no mesmo KPI limpa o filtro
    vActiveKpi = vActiveKpi === status ? '' : status;
    document.getElementById('v-status').value = vActiveKpi;
    // Destacar KPI ativo
    const kpiMap = { '':'vkpi-all', expired:'vkpi-expired', expiring:'vkpi-expiring', valid:'vkpi-valid' };
    document.querySelectorAll('.v-kpi').forEach(el => el.classList.remove('active-filter'));
    document.getElementById(kpiMap[vActiveKpi])?.classList.add('active-filter');
    loadValidity();
}

function resetValidity() {
    ['v-status','v-employee','v-training','v-sector'].forEach(id => { document.getElementById(id).value = ''; });
    vActiveKpi = '';
    document.querySelectorAll('.v-kpi').forEach(el => el.classList.remove('active-filter'));
    loadValidity();
}

/* ══════════════════════════════════════════
   TAB LACUNAS (GAP ANALYSIS)
══════════════════════════════════════════ */
async function loadGaps() {
    gapsLoaded = true;
    const content = document.getElementById('g-content');
    content.innerHTML = '<div class="state-msg">A analisar lacunas…</div>';
    ['g-kpi-mandatory','g-kpi-certs','g-kpi-none','g-kpi-plan'].forEach(id => {
        document.getElementById(id).textContent = '…';
    });

    const year = document.getElementById('g-year').value;
    const res  = await fetch(`/api/v1/reports/gaps?year=${year}`, {credentials:'same-origin'}).then(r => r.json());

    const mandatory = res.mandatory_gaps?.data   || [];
    const certs     = res.expired_certificates?.data || [];
    const none      = res.no_training?.data      || [];
    const plan      = res.plan_gaps?.data        || [];

    // Group mandatory gaps by rule (training + target scope)
    const mandatoryGrouped = Object.values(mandatory.reduce((acc, r) => {
        const key = `${r.training_id}-${r.target_type||'all'}-${r.target_id||''}`;
        if (!acc[key]) acc[key] = { training_title: r.training_title, target_name: r.target_name, target_type: r.target_type, target_id: r.target_id, employees: [] };
        acc[key].employees.push(r);
        return acc;
    }, {}));

    document.getElementById('g-kpi-mandatory').textContent = mandatory.length;
    document.getElementById('g-kpi-certs').textContent     = certs.length;
    document.getElementById('g-kpi-none').textContent      = none.length;
    document.getElementById('g-kpi-plan').textContent      = plan.length;
    document.getElementById('g-kpi-mandatory').dataset.loaded = '1';

    let html = '';

    /* --- Secção 1: Formações Obrigatórias em Falta --- */
    html += `<div class="gap-section">
        <div class="gap-section-header">
            <span class="gap-section-icon" style="color:#ef4444">⚠️</span>
            <div>
                <div class="gap-section-title">Formações Obrigatórias em Falta</div>
                <div class="gap-section-sub">${mandatoryGrouped.length} regra(s) com ${mandatory.length} funcionário(s) em incumprimento</div>
            </div>
        </div>`;
    if (!mandatoryGrouped.length) {
        html += `<div class="state-msg" style="padding:16px">✅ Sem lacunas em formações obrigatórias.</div>`;
    } else {
        mandatoryGrouped.forEach((rule, idx) => {
            const targetLabel = rule.target_name || 'Todos os funcionários';
            html += `<div class="gap-rule" id="gap-rule-${idx}">
                <div class="gap-rule-header" onclick="toggleGapRule(${idx})">
                    <span class="gap-rule-title">📚 ${rule.training_title}</span>
                    <span class="gap-rule-meta">${targetLabel}</span>
                    <span class="gap-badge gap-badge-danger">${rule.employees.length} em falta</span>
                    <span class="gap-rule-chevron">▼</span>
                </div>
                <div class="gap-rule-body">
                    <div class="gap-emp-list">
                        ${rule.employees.map(e => `<span class="gap-emp-chip">${e.employee_name} <em>${e.employee_code}</em></span>`).join('')}
                    </div>
                </div>
            </div>`;
        });
    }
    html += `</div>`;

    /* --- Secção 2: Certificados Expirados/A Expirar --- */
    html += `<div class="gap-section">
        <div class="gap-section-header">
            <span class="gap-section-icon" style="color:#f59e0b">🔔</span>
            <div>
                <div class="gap-section-title">Certificados Expirados ou a Expirar (30 dias)</div>
                <div class="gap-section-sub">${certs.length} registo(s) requerem atenção</div>
            </div>
        </div>`;
    if (!certs.length) {
        html += `<div class="state-msg" style="padding:16px">✅ Nenhum certificado expirado ou a expirar.</div>`;
    } else {
        html += `<div class="table-wrap"><table>
            <thead><tr><th>Funcionário</th><th>Código</th><th>Formação</th><th>Expira em</th><th>Dias</th><th>Estado</th></tr></thead>
            <tbody>${certs.map(r => {
                const cls = r.status === 'expired' ? 'row-expired' : 'row-expiring';
                const badge = r.status === 'expired'
                    ? '<span class="vbadge vbadge-expired">⚠️ Expirada</span>'
                    : '<span class="vbadge vbadge-expiring">🔔 A expirar</span>';
                const daysLabel = r.days_left < 0 ? `há ${Math.abs(r.days_left)} dias` : `em ${r.days_left} dia(s)`;
                return `<tr class="${cls}">
                    <td style="font-weight:600">${r.employee_name}</td>
                    <td><span style="font-family:monospace;font-size:.78rem;color:var(--text-muted)">${r.employee_code}</span></td>
                    <td>${r.training_title}</td>
                    <td style="font-weight:600">${fmt(r.expiry_date)}</td>
                    <td style="font-size:.82rem;color:var(--text-muted)">${daysLabel}</td>
                    <td>${badge}</td>
                </tr>`;
            }).join('')}</tbody>
        </table></div>`;
    }
    html += `</div>`;

    /* --- Secção 3: Funcionários sem Formações --- */
    html += `<div class="gap-section">
        <div class="gap-section-header">
            <span class="gap-section-icon" style="color:var(--accent-light)">🚫</span>
            <div>
                <div class="gap-section-title">Funcionários sem Nenhuma Formação</div>
                <div class="gap-section-sub">${none.length} funcionário(s) sem qualquer registo de formação</div>
            </div>
        </div>`;
    if (!none.length) {
        html += `<div class="state-msg" style="padding:16px">✅ Todos os funcionários têm pelo menos uma formação.</div>`;
    } else {
        html += `<div class="gap-emp-list" style="padding:12px 16px">
            ${none.map(e => `<span class="gap-emp-chip">${e.employee_name} <em>${e.employee_code}</em></span>`).join('')}
        </div>`;
    }
    html += `</div>`;

    /* --- Secção 4: Plano vs Execução --- */
    html += `<div class="gap-section">
        <div class="gap-section-header">
            <span class="gap-section-icon" style="color:#10b981">📅</span>
            <div>
                <div class="gap-section-title">Plano vs Execução — Sessões Abaixo de 70%</div>
                <div class="gap-section-sub">${plan.length} sessão(ões) com taxa de preenchimento insuficiente em ${year}</div>
            </div>
        </div>`;
    if (!plan.length) {
        html += `<div class="state-msg" style="padding:16px">✅ Todas as sessões atingiram pelo menos 70% de preenchimento.</div>`;
    } else {
        html += `<div class="table-wrap"><table>
            <thead><tr><th>Formação</th><th>Data</th><th>Local</th><th>Previsto</th><th>Inscrito</th><th>Taxa</th><th>Estado</th></tr></thead>
            <tbody>${plan.map(s => {
                const fillColor = (s.fill_rate ?? 0) >= 50 ? 'var(--warning)' : 'var(--danger)';
                return `<tr>
                    <td style="font-weight:600">${s.training_title}</td>
                    <td>${s.planned_date_fmt || fmt(s.planned_date)}</td>
                    <td style="color:var(--text-muted)">${s.location || '—'}</td>
                    <td style="text-align:center">${s.estimated_participants ?? s.max_participants ?? '—'}</td>
                    <td style="text-align:center">${s.enrolled_count}</td>
                    <td><span style="color:${fillColor};font-weight:700">${s.fill_rate ?? 0}%</span></td>
                    <td>${gapStatusBadge(s.status)}</td>
                </tr>`;
            }).join('')}</tbody>
        </table></div>`;
    }
    html += `</div>`;

    content.innerHTML = html;
}

function toggleGapRule(idx) {
    document.getElementById('gap-rule-' + idx)?.classList.toggle('open');
}

function gapStatusBadge(status) {
    const colors = {
        planned:   'background:rgba(99,102,241,.15);color:var(--accent-light)',
        ongoing:   'background:rgba(245,158,11,.15);color:#f59e0b',
        completed: 'background:rgba(34,197,94,.15);color:#22c55e',
        cancelled: 'background:rgba(156,163,175,.15);color:var(--text-muted)',
    };
    const label = { planned:'Planeada', ongoing:'Em curso', completed:'Concluída', cancelled:'Cancelada' };
    const style = colors[status] || colors.planned;
    return `<span style="${style};padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:700">${label[status]||status}</span>`;
}

function resetGaps() {
    gapsLoaded = false;
    document.getElementById('g-year').value = window.REPORT_CONFIG.currentYear;
    loadGaps();
}

function buildValidityPrintTable(rows) {
    const thStyle = 'background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left';
    const header  = `<tr>
        <th style="${thStyle}">Funcionário</th>
        <th style="${thStyle}">Cód.</th>
        <th style="${thStyle}">Setor</th>
        <th style="${thStyle}">Função</th>
        <th style="${thStyle}">Formação</th>
        <th style="${thStyle}">Fornecedor</th>
        <th style="${thStyle}">Data Fim</th>
        <th style="${thStyle}">Val. (m)</th>
        <th style="${thStyle}">Expira em</th>
        <th style="${thStyle}">Estado</th>
    </tr>`;
    const bgMap   = { expired:'#fff5f5', expiring:'#fffbeb', valid:'', '':'' };
    const badgeMap= {
        valid    : 'background:#dcfce7;color:#166534',
        expiring : 'background:#fef3c7;color:#92400e',
        expired  : 'background:#fee2e2;color:#991b1b',
    };
    const tbody   = rows.map((r, i) => {
        const bg  = bgMap[r.validity_status ?? ''] || (i % 2 === 1 ? '#f8f9fc' : '');
        const vs  = r.validity_status;
        const bdg = vs
            ? `<span style="${badgeMap[vs]??''};padding:2px 8px;border-radius:12px;font-size:9px;font-weight:700">${vLabel[vs]}</span>`
            : '—';
        const td  = `padding:8px 11px;border-top:1px solid #e5e7eb;color:#1a1a2e`;
        return `<tr style="background:${bg}">
            <td style="${td};font-weight:600">${r.employee}</td>
            <td style="${td};font-family:monospace;font-size:9px;color:#6b7280">${r.employee_code}</td>
            <td style="${td}">${r.sector}</td>
            <td style="${td}">${r.position}</td>
            <td style="${td};font-weight:600">${r.training}</td>
            <td style="${td};color:#6b7280;font-size:9px">${r.provider}</td>
            <td style="${td}">${fmt(r.end_date)}</td>
            <td style="${td};text-align:center">${r.validity_months ?? '—'}</td>
            <td style="${td};font-weight:600">${fmt(r.expiry_date)}</td>
            <td style="${td}">${bdg}</td>
        </tr>`;
    }).join('');
    return `<table style="width:100%;border-collapse:collapse;font-size:10.5px"><thead>${header}</thead><tbody>${tbody}</tbody></table>`;
}

function exportPdf(tab) {
    const titles = { employees:'Funcionários com Formações', trainings:'Formações por Funcionários', attendance:'Relatório de Assiduidade', validity:'Validade de Formações' };
    document.getElementById('printTitle').textContent    = titles[tab];
    document.getElementById('printTabLabel').textContent = titles[tab];
    const now = new Date();
    document.getElementById('printDate').textContent = 'Gerado em ' + now.toLocaleDateString('pt-PT', {day:'2-digit',month:'long',year:'numeric'}) + ' às ' + now.toLocaleTimeString('pt-PT', {hour:'2-digit',minute:'2-digit'});
    let fp = [];
    if (tab === 'employees') {
        const sec = document.getElementById('e-sector');
        if (sec.value) fp.push('Setor: ' + sec.options[sec.selectedIndex].text);
        if (msEPosition && msEPosition.getValues().length) {
            const labels = msEPosition.getValues().map(v => msEPosition.items.find(i=>i.value===v)?.label).filter(Boolean);
            if (labels.length) fp.push('Função: ' + labels.join(', '));
        }
    } else if (tab === 'trainings') {
        const sec = document.getElementById('t-sector');
        if (msTTraining && msTTraining.getValues().length) {
            const labels = msTTraining.getValues().map(v => msTTraining.items.find(i=>i.value===v)?.label).filter(Boolean);
            if (labels.length) fp.push('Formação: ' + labels.join(', '));
        }
        if (msTPosition && msTPosition.getValues().length) {
            const labels = msTPosition.getValues().map(v => msTPosition.items.find(i=>i.value===v)?.label).filter(Boolean);
            if (labels.length) fp.push('Função: ' + labels.join(', '));
        }
        if (sec.value) fp.push('Setor: ' + sec.options[sec.selectedIndex].text);
    } else {
        const emp = document.getElementById('a-employee'), sec = document.getElementById('a-sector'), sta = document.getElementById('a-status');
        const from = document.getElementById('a-from').value, to = document.getElementById('a-to').value;
        if (emp.value) fp.push('Funcionário: ' + emp.options[emp.selectedIndex].text);
        if (sec.value) fp.push('Setor: ' + sec.options[sec.selectedIndex].text);
        if (sta.value) fp.push('Estado: ' + sta.options[sta.selectedIndex].text);
        if (from) fp.push('De: ' + fmt(from));
        if (to)   fp.push('Até: ' + fmt(to));
    }
    document.getElementById('printFilters').textContent = fp.length ? ' · ' + fp.join(' · ') : '';

    // Popular tabela plana para o PDF de funcionários
    if (tab === 'employees') {
        const search = (document.getElementById('e-search').value || '').toLowerCase().trim();
        const sort   = document.getElementById('e-sort').value;
        let data = empData.filter(e => !search || (e.name||'').toLowerCase().includes(search) || (e.sector||'').toLowerCase().includes(search) || (e.position||'').toLowerCase().includes(search) || (e.code||'').toLowerCase().includes(search));
        if (sort === 'name')       data.sort((a,b) => a.name.localeCompare(b.name));
        if (sort === 'total_desc') data.sort((a,b) => b.total_completed - a.total_completed);
        if (sort === 'total_asc')  data.sort((a,b) => a.total_completed - b.total_completed);
        if (sort === 'sector')     data.sort((a,b) => (a.sector||'').localeCompare(b.sector||''));
        document.getElementById('e-print-tbody').innerHTML = data.map((e,i) => {
            const names = (e.trainings||[]).map(t => t.title + ((t.score !== null && t.score !== undefined && t.score !== '') ? ` (${parseFloat(t.score)}pt)` : '')).join('; ');
            const bg = i % 2 === 1 ? 'background:#f8f9fc' : '';
            return `<tr style="${bg}">
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;font-family:monospace;font-size:9.5px;color:#6b7280">${e.code}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;font-weight:600;color:#1a1a2e">${e.name}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;color:#1a1a2e">${e.position}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;color:#1a1a2e">${e.sector}</td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;text-align:center"><span style="background:#e0e7ff;color:#4338ca;padding:2px 9px;border-radius:12px;font-size:9px;font-weight:700">${e.total_completed}</span></td>
                <td style="padding:8px 11px;border-top:1px solid #e5e7eb;color:#374151;font-size:9.5px">${names||'—'}</td>
            </tr>`;
        }).join('');
        document.body.classList.add('printing-employees');
    } else if (tab === 'trainings') {
        document.body.classList.remove('printing-employees');
        // Gerar tabela plana a partir dos dados em memória (trainingsData)
        const thS = 'background:#6366f1;color:#fff;padding:9px 11px;font-size:9.5px;font-weight:700;text-transform:uppercase;text-align:left';
        const tdS = 'padding:8px 11px;border-top:1px solid #e5e7eb;color:#1a1a2e';
        let rows = '';
        trainingsData.forEach(t => {
            rows += `<tr><td colspan="6" style="background:#eef2ff;padding:10px 11px;border-top:2px solid #6366f1">
                <strong style="font-size:10.5px;color:#1a1a2e">📚 ${t.title}</strong>
                <span style="color:#6b7280;font-size:9.5px;margin-left:8px">${t.provider}</span>
                <span style="background:#e0e7ff;color:#4338ca;padding:2px 9px;border-radius:12px;font-size:9px;font-weight:700;float:right">${t.total} funcionário(s)</span>
            </td></tr>`;
            (t.employees || []).forEach((e, i) => {
                const bg = i % 2 === 1 ? 'background:#f8f9fc;' : '';
                rows += `<tr>
                    <td style="${bg}${tdS};font-family:monospace;font-size:9px;color:#6b7280">${e.code}</td>
                    <td style="${bg}${tdS};font-weight:600">${e.name}</td>
                    <td style="${bg}${tdS}">${e.position}</td>
                    <td style="${bg}${tdS}">${e.sector}</td>
                    <td style="${bg}${tdS};text-align:center">${e.score ?? '—'}</td>
                    <td style="${bg}${tdS}">${fmt(e.completed_at)}</td>
                </tr>`;
            });
        });
        const tableHtml = `<table style="width:100%;border-collapse:collapse;font-size:10.5px">
            <thead><tr>
                <th style="${thS}">Código</th><th style="${thS}">Funcionário</th>
                <th style="${thS}">Função</th><th style="${thS}">Setor</th>
                <th style="${thS}">Pontuação</th><th style="${thS}">Concluído em</th>
            </tr></thead>
            <tbody>${rows}</tbody>
        </table>`;
        const tPrintDiv = document.getElementById('t-print-block');
        tPrintDiv.innerHTML = tableHtml;
        document.getElementById('printFilters').textContent = fp.length ? ' · ' + fp.join(' · ') : '';
        document.body.classList.add('printing-trainings');
        setTimeout(() => {
            window.print();
            setTimeout(() => { document.body.classList.remove('printing-trainings'); }, 500);
        }, 300);
        return;
    } else if (tab === 'validity') {
        document.body.classList.remove('printing-employees');
        const vs  = document.getElementById('v-status');
        const ve  = document.getElementById('v-employee');
        const vt  = document.getElementById('v-training');
        const vsc = document.getElementById('v-sector');
        if (vs.value)  fp.push('Estado: ' + vs.options[vs.selectedIndex].text);
        if (ve.value)  fp.push('Funcionário: ' + ve.options[ve.selectedIndex].text);
        if (vt.value)  fp.push('Formação: ' + vt.options[vt.selectedIndex].text);
        if (vsc.value) fp.push('Setor: ' + vsc.options[vsc.selectedIndex].text);
        // Injetar tabela de validade no bloco fixo de impressão
        document.getElementById('v-print-block').innerHTML = buildValidityPrintTable(validityData);
        document.getElementById('printFilters').textContent = fp.length ? ' · ' + fp.join(' · ') : '';
        document.body.classList.add('printing-validity');
        setTimeout(() => {
            window.print();
            setTimeout(() => { document.body.classList.remove('printing-validity'); }, 500);
        }, 300);
        return; // já chama window.print() acima
    } else {
        document.body.classList.remove('printing-employees');
    }

    setTimeout(() => window.print(), 300);
}

let emailTab = '';
function openEmail(tab) {
    emailTab = tab;
    document.getElementById('emailAddr').value = '';
    document.getElementById('emailSubject').value = '';
    document.getElementById('emailModal').classList.add('open');
}
function closeEmail() { document.getElementById('emailModal').classList.remove('open'); }

async function sendEmail() {
    const email = document.getElementById('emailAddr').value.trim();
    if (!email) { alert('Insira um endereço de email válido.'); return; }
    const tabMap   = { employees:'tab-employees', trainings:'tab-trainings', attendance:'tab-attendance', validity:'tab-validity' };
    const typeMap  = { employees:'employees_trainings', trainings:'training_employees', attendance:'attendance', validity:'validity' };
    const titleMap = { employees:'Funcionários com Formações', trainings:'Formações por Funcionários', attendance:'Relatório de Assiduidade', validity:'Validade de Formações' };
    let tableHtml;
    if (emailTab === 'trainings') {
        tableHtml = document.getElementById('t-list')?.innerHTML ?? '<p>Sem dados</p>';
    } else if (emailTab === 'validity') {
        tableHtml = buildValidityPrintTable(validityData);
    } else {
        const tabEl = document.getElementById(tabMap[emailTab]);
        tableHtml = tabEl ? (tabEl.querySelector('table')?.outerHTML ?? '<p>Sem dados</p>') : '<p>Sem dados</p>';
    }
    const subject = document.getElementById('emailSubject').value.trim() || `HREminho — ${titleMap[emailTab]} — ${new Date().toLocaleDateString('pt-PT')}`;
    const html = `<html><body style="font-family:Arial,sans-serif;color:#333">
        <h2 style="color:#6366f1">HREminho — ${titleMap[emailTab]}</h2>
        <p style="color:#888;font-size:13px">Gerado em ${new Date().toLocaleDateString('pt-PT')}</p>
        <style>table{border-collapse:collapse;width:100%}th{background:#f0f0f0;padding:8px;text-align:left;font-size:12px}td{padding:8px;border-bottom:1px solid #eee;font-size:12px}</style>
        ${tableHtml}
        <p style="margin-top:24px;color:#aaa;font-size:11px">— HREminho</p>
    </body></html>`;
    const btn = document.getElementById('sendEmailBtn');
    btn.disabled = true; btn.textContent = 'A enviar…';
    try {
        const res = await fetch('/api/v1/reports/send-email', {
        credentials:'same-origin',
        method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ email, type: typeMap[emailTab], subject, html }),
        }).then(r => r.json());
        closeEmail();
        showToast(res.message || 'Email enviado!', 'success');
    } catch(e) {
        showToast('Erro ao enviar email.', 'error');
    } finally {
        btn.disabled = false; btn.textContent = '✉️ Enviar';
    }
}

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.textContent = msg;
    Object.assign(t.style, { position:'fixed', bottom:'24px', right:'24px', zIndex:'999', background: type === 'success' ? 'var(--success)' : 'var(--danger)', color:'#fff', padding:'12px 20px', borderRadius:'10px', fontSize:'0.875rem', fontWeight:'600', boxShadow:'0 4px 20px rgba(0,0,0,0.3)', opacity:'0', transition:'opacity 0.25s' });
    document.body.appendChild(t);
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3500);
}

// Restaurar UI após impressão (inclui cancelamento do diálogo)
window.addEventListener('afterprint', () => {
    document.body.classList.remove('printing-employees', 'printing-trainings', 'printing-validity');
});


/* ── Expor funções para o escopo global (necessário para onclick inline no HTML) ── */
Object.assign(window, {
    switchTab,
    setView,
    loadEmployees,
    resetEmployees,
    loadTrainings,
    resetTrainings,
    loadAttendance,
    resetAttendance,
    loadValidity,
    resetValidity,
    loadGaps,
    resetGaps,
    vFilterKpi,
    renderCards,
    exportPdf,
    exportExcel,
    openEmail,
    closeEmail,
    sendEmail,
});

loadDropdowns().then(() => { switchTab('employees'); });
