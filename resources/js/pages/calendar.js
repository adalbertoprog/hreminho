/**
 * calendar.js — HREminho Calendar page
 *
 * CALENDAR_TYPES is the single source of truth for each event type:
 *   key        — matches the value sent in the `types` API param
 *   label      — chip / legend label
 *   icon       — emoji prefix for the chip
 *   chipClass  — CSS class for the toggle chip
 *   legends    — array of { dotClass, label } shown in the legend bar
 *   filters    — IDs of filter <select> elements that belong to this type
 *   defaultOn  — whether the type is active on first load
 */

const CALENDAR_TYPES = {
    trainings: {
        label: 'Formações',
        icon: '📚',
        chipClass: 'type-chip-training',
        defaultOn: true,
        legends: [
            { dotClass: 'legend-dot-enrolled',  label: 'Inscrito'   },
            { dotClass: 'legend-dot-completed', label: 'Concluído'  },
            { dotClass: 'legend-dot-failed',    label: 'Reprovado'  },
        ],
        filters: ['fStatus', 'fTraining'],
        // Called when user clicks/selects an empty day and this is the primary active type
        onDateClick: (dateStr) => openCreateForm(dateStr),
        onDateSelect: (startStr, endStr) => {
            openCreateForm(startStr);
            const endDate = new Date(endStr); endDate.setDate(endDate.getDate() - 1);
            setTimeout(() => { document.getElementById('fEndDate').value = endDate.toISOString().substring(0, 10); updateExpiryHint(); }, 50);
        },
    },
    leaves: {
        label: 'Licenças',
        icon: '🏖️',
        chipClass: 'type-chip-leave',
        defaultOn: true,
        legends: [
            { dotClass: 'legend-dot-vacation', label: 'Férias'  },
            { dotClass: 'legend-dot-sick',     label: 'Doença'  },
            { dotClass: 'legend-dot-unpaid',   label: 'N.Rem.'  },
        ],
        filters: [],
        onDateClick: () => { window.location.href = '/leaves'; },
    },
    attendances: {
        label: 'Ausências/Atrasos',
        icon: '⚠️',
        chipClass: 'type-chip-attendance',
        defaultOn: true,
        legends: [
            { dotClass: 'legend-dot-absent', label: 'Ausente'   },
            { dotClass: 'legend-dot-late',   label: 'Atrasado'  },
        ],
        filters: [],
        onDateClick: null, // read-only — no action on empty day click
    },
    projects: {
        label: 'Obras/Equipas',
        icon: '🏗️',
        chipClass: 'type-chip-project',
        defaultOn: true,
        legends: [
            { dotClass: 'legend-dot-project', label: 'Obra'    },
            { dotClass: 'legend-dot-team',    label: 'Equipa'  },
        ],
        filters: ['fProject'],
        onDateClick: () => { window.location.href = '/projects'; },
    },
};

// fEmployee is shown when any type that involves employees is active
const EMPLOYEE_FILTER_TYPES = ['trainings', 'leaves', 'attendances'];

/* ─────────────────────────────────────────────────────────── */
/* State                                                        */
/* ─────────────────────────────────────────────────────────── */
let calendar;
let currentEnrollId = null;
let pendingDeleteId = null;
let currentDetailId = null;
let _lastEventProps  = {};

// Single active type by default — exclusive mode
const activeTypes = new Set(['projects']);

/* ─────────────────────────────────────────────────────────── */
/* Legend + filter visibility                                   */
/* ─────────────────────────────────────────────────────────── */

/**
 * Re-render the legend bar to show only dots for active types.
 * Each legend item carries data-type so we can show/hide cheaply.
 */
function syncLegend() {
    document.querySelectorAll('.legend-item[data-type]').forEach(el => {
        el.style.display = activeTypes.has(el.dataset.type) ? '' : 'none';
    });
    // separators between groups — show if the type after them is active
    document.querySelectorAll('.legend-sep[data-type]').forEach(el => {
        el.style.display = activeTypes.has(el.dataset.type) ? '' : 'none';
    });
}

/**
 * Update the toolbar button label/visibility to match the primary active type.
 * - trainings active  → "+ Nova Inscrição"
 * - leaves only       → "+ Nova Licença" (links to /leaves)
 * - projects only     → "+ Nova Obra" (links to /projects)
 * - attendances only  → hide button (read-only)
 * - multiple types    → show the training button if trainings is active, else hide
 */
function syncToolbar() {
    const btn  = document.getElementById('calCreateBtn');
    const hint = document.getElementById('calHint');

    if (activeTypes.has('trainings')) {
        if (btn)  { btn.style.display = ''; btn.textContent = '+ Nova Inscrição'; btn.onclick = () => openCreateForm(null); }
        if (hint) hint.style.display = '';
    } else if (activeTypes.has('leaves') && activeTypes.size === 1) {
        if (btn)  { btn.style.display = ''; btn.textContent = '+ Nova Licença'; btn.onclick = () => { window.location.href = '/leaves'; }; }
        if (hint) hint.style.display = 'none';
    } else if (activeTypes.has('projects') && !activeTypes.has('leaves') && !activeTypes.has('attendances')) {
        if (btn)  { btn.style.display = ''; btn.textContent = '+ Nova Obra'; btn.onclick = () => { window.location.href = '/projects'; }; }
        if (hint) hint.style.display = 'none';
    } else {
        if (btn)  btn.style.display = 'none';
        if (hint) hint.style.display = 'none';
    }
}

/**
 * Show/hide filter <select> elements based on active types.
 * fEmployee is shown when any of EMPLOYEE_FILTER_TYPES is active.
 */
function syncFilters() {
    // Per-type filters
    Object.entries(CALENDAR_TYPES).forEach(([key, cfg]) => {
        const on = activeTypes.has(key);
        cfg.filters.forEach(id => {
            const wrap = document.getElementById(id + 'Wrap');
            if (wrap) wrap.style.display = on ? '' : 'none';
        });
    });

    // fEmployee — shared across several types
    const empWrap = document.getElementById('fEmployeeWrap');
    if (empWrap) {
        const show = EMPLOYEE_FILTER_TYPES.some(t => activeTypes.has(t));
        empWrap.style.display = show ? '' : 'none';
    }
}

/**
 * Update the "A mostrar X evento(s)" meta line with a type-aware label.
 */
function syncMeta(count) {
    const meta = document.getElementById('calMeta');
    if (!meta) return;
    if (count === 0) { meta.style.display = 'none'; return; }

    const types = [...activeTypes];
    let noun;
    if      (types.length === 1 && types[0] === 'trainings')   noun = 'formação(ões)';
    else if (types.length === 1 && types[0] === 'leaves')       noun = 'licença(s)';
    else if (types.length === 1 && types[0] === 'attendances')  noun = 'presença(s)';
    else if (types.length === 1 && types[0] === 'projects')     noun = 'obra(s)/equipa(s)';
    else                                                         noun = 'evento(s)';

    meta.style.display = 'flex';
    const strong = meta.querySelector('strong');
    if (strong) strong.textContent = count;
    const span = meta.querySelector('span.meta-noun');
    if (span) span.textContent = ' ' + noun + ' no período visível.';
}

/* ─────────────────────────────────────────────────────────── */
/* Chip toggle                                                  */
/* ─────────────────────────────────────────────────────────── */
function toggleType(chip) {
    const t = chip.dataset.type;
    if (activeTypes.has(t)) return; // already active — nothing to do
    // Exclusive mode: deactivate all others, activate only this one
    document.querySelectorAll('.type-chip').forEach(c => c.classList.remove('active'));
    activeTypes.clear();
    activeTypes.add(t);
    chip.classList.add('active');
    syncLegend();
    syncFilters();
    syncToolbar();
    reloadEvents();
}

/* ─────────────────────────────────────────────────────────── */
/* Overlays / toasts                                            */
/* ─────────────────────────────────────────────────────────── */
function openOverlay(id)  { document.getElementById(id).classList.add('open'); }
function closeOverlay(id) { document.getElementById(id).classList.remove('open'); }

function toast(msg, type = 'ok') {
    const w = document.getElementById('toastWrap');
    const t = document.createElement('div');
    t.className = `toast toast-${type}`;
    t.textContent = msg;
    w.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

/* ─────────────────────────────────────────────────────────── */
/* API helper                                                   */
/* ─────────────────────────────────────────────────────────── */
const CSRF = () => document.querySelector('meta[name="csrf-token"]').content;

async function apiFetch(method, path, body) {
    const opts = {
        method,
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF(), 'Accept': 'application/json' },
    };
    if (body) opts.body = JSON.stringify(body);
    const r = await fetch('/api/v1' + path, opts);
    if (!r.ok) { const e = await r.json().catch(() => ({ message: 'Erro' })); throw e; }
    return r.status === 204 ? null : r.json();
}

/* ─────────────────────────────────────────────────────────── */
/* Multi-select funcionários                                    */
/* ─────────────────────────────────────────────────────────── */
let selectedEmps = {}, empFocusIdx = -1;

function openEmpDropdown() {
    document.getElementById('empDropdown').classList.add('open');
    filterEmpOptions();
}
function closeEmpDropdown() {
    document.getElementById('empDropdown').classList.remove('open');
    document.getElementById('empSearch').value = '';
    document.querySelectorAll('#empDropdown .emp-opt').forEach(o => o.style.display = '');
    document.getElementById('empEmpty').style.display = 'none';
    empFocusIdx = -1;
}
function filterEmpOptions() {
    const q = document.getElementById('empSearch').value.toLowerCase().trim();
    const opts = document.querySelectorAll('#empDropdown .emp-opt');
    let visible = 0;
    opts.forEach(o => {
        const m = !q || o.dataset.label.toLowerCase().includes(q);
        o.style.display = m ? '' : 'none';
        if (m) visible++;
    });
    document.getElementById('empEmpty').style.display = visible === 0 ? '' : 'none';
    empFocusIdx = -1;
}
function toggleEmp(optEl) {
    const id = optEl.dataset.id, label = optEl.dataset.label;
    if (selectedEmps[id]) { delete selectedEmps[id]; optEl.classList.remove('selected'); }
    else { selectedEmps[id] = label; optEl.classList.add('selected'); }
    renderEmpChips();
    document.getElementById('empSearch').focus();
}
function removeEmp(id) {
    delete selectedEmps[id];
    const opt = document.querySelector(`#empDropdown .emp-opt[data-id="${id}"]`);
    if (opt) opt.classList.remove('selected');
    renderEmpChips();
}
function renderEmpChips() {
    const container = document.getElementById('empChips');
    container.querySelectorAll('.emp-chip').forEach(c => c.remove());
    const search = document.getElementById('empSearch');
    Object.entries(selectedEmps).forEach(([id, label]) => {
        const chip = document.createElement('span');
        chip.className = 'emp-chip';
        chip.dataset.id = id;
        chip.innerHTML = `${label} <button type="button" onclick="removeEmp('${id}')" title="Remover">✕</button>`;
        container.insertBefore(chip, search);
    });
    const count = Object.keys(selectedEmps).length;
    document.getElementById('empCountLabel').textContent = count > 0 ? `(${count} selecionado${count > 1 ? 's' : ''})` : '';
}
function empSearchKeydown(e) {
    const dd = document.getElementById('empDropdown');
    const opts = [...dd.querySelectorAll('.emp-opt:not([style*="display: none"])')];
    if (e.key === 'ArrowDown') {
        e.preventDefault(); empFocusIdx = Math.min(empFocusIdx + 1, opts.length - 1);
        opts.forEach((o, i) => o.classList.toggle('focused', i === empFocusIdx));
        if (opts[empFocusIdx]) opts[empFocusIdx].scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'ArrowUp') {
        e.preventDefault(); empFocusIdx = Math.max(empFocusIdx - 1, 0);
        opts.forEach((o, i) => o.classList.toggle('focused', i === empFocusIdx));
        if (opts[empFocusIdx]) opts[empFocusIdx].scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'Enter') {
        e.preventDefault(); if (empFocusIdx >= 0 && opts[empFocusIdx]) toggleEmp(opts[empFocusIdx]);
    } else if (e.key === 'Escape') {
        closeEmpDropdown();
    } else if (e.key === 'Backspace' && e.target.value === '') {
        const ids = Object.keys(selectedEmps); if (ids.length) removeEmp(ids[ids.length - 1]);
    }
}
function resetEmpPicker() {
    selectedEmps = {};
    document.querySelectorAll('#empDropdown .emp-opt').forEach(o => o.classList.remove('selected', 'focused'));
    renderEmpChips(); closeEmpDropdown();
}
function setEmpPickerSingle(id, label) {
    resetEmpPicker();
    if (!id) return;
    selectedEmps[id] = label;
    const opt = document.querySelector(`#empDropdown .emp-opt[data-id="${id}"]`);
    if (opt) opt.classList.add('selected');
    renderEmpChips();
}
document.addEventListener('click', function (e) {
    const picker = document.getElementById('empPicker');
    if (picker && !picker.contains(e.target)) closeEmpDropdown();
});

/* ─────────────────────────────────────────────────────────── */
/* Filtros                                                      */
/* ─────────────────────────────────────────────────────────── */
function buildParams() {
    const p = new URLSearchParams();
    const s    = document.getElementById('fStatus')?.value;
    const t    = document.getElementById('fTraining')?.value;
    const e    = document.getElementById('fEmployee')?.value;
    const proj = document.getElementById('fProject')?.value;
    if (s)    p.set('status', s);
    if (t)    p.set('training_id', t);
    if (e)    p.set('employee_id', e);
    if (proj) p.set('project_id', proj);
    p.set('types', [...activeTypes].join(','));
    return p;
}
function reloadEvents() { if (calendar) calendar.refetchEvents(); }
function clearFilters() {
    ['fStatus', 'fTraining', 'fEmployee', 'fProject'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    reloadEvents();
}

/* ─────────────────────────────────────────────────────────── */
/* Form helpers                                                 */
/* ─────────────────────────────────────────────────────────── */
function toggleScoreField() {
    const st = document.getElementById('fStatusForm').value;
    document.getElementById('scoreFieldWrap').style.display = (st === 'completed' || st === 'failed') ? '' : 'none';
}
function updateExpiryHint() {
    const endVal = document.getElementById('fEndDate').value;
    const months = parseInt(document.getElementById('fValidity').value);
    const hint   = document.getElementById('expiryHint');
    if (!endVal || !months || months < 1) {
        hint.textContent = '— preencha fim e validade'; hint.className = 'validity-hint'; return;
    }
    const expiry = new Date(endVal); expiry.setMonth(expiry.getMonth() + months);
    const today  = new Date(); today.setHours(0, 0, 0, 0);
    const diff   = Math.round((expiry - today) / 864e5);
    const fmt    = expiry.toLocaleDateString('pt-PT');
    if      (diff < 0)   { hint.textContent = `Expirou em ${fmt}`;                              hint.className = 'validity-hint expired';  }
    else if (diff <= 30) { hint.textContent = `Expira em ${fmt} (faltam ${diff} dias)`;         hint.className = 'validity-hint expiring'; }
    else                 { hint.textContent = `Válida até ${fmt}`;                               hint.className = 'validity-hint valid';    }
}

/* ─────────────────────────────────────────────────────────── */
/* Form criar / editar                                          */
/* ─────────────────────────────────────────────────────────── */
function openCreateForm(dateStr) {
    currentEnrollId = null;
    document.getElementById('enrollForm').reset();
    document.getElementById('formTitle').textContent = '➕ Nova Inscrição';
    document.getElementById('formSubmitBtn').textContent = 'Inscrever';
    document.getElementById('expiryHint').textContent = '— preencha fim e validade';
    document.getElementById('expiryHint').className = 'validity-hint';
    document.getElementById('scoreFieldWrap').style.display = 'none';
    resetEmpPicker();
    if (dateStr) document.getElementById('fStartDate').value = dateStr;
    openOverlay('formOverlay');
    setTimeout(() => document.getElementById('empSearch').focus(), 120);
}

function openEditForm(enrollment) {
    currentEnrollId = enrollment.id;
    document.getElementById('enrollForm').reset();
    document.getElementById('formTitle').textContent = '✏️ Editar Inscrição';
    document.getElementById('formSubmitBtn').textContent = 'Guardar';
    setEmpPickerSingle(String(enrollment.employee_id), enrollment.employee_label ?? '');
    const form = document.getElementById('enrollForm');
    const set = (n, v) => { const el = form.querySelector(`[name="${n}"]`); if (el) el.value = v ?? ''; };
    set('training_id', enrollment.training_id); set('status', enrollment.status);
    set('score', enrollment.score); set('start_date', enrollment.start_date);
    set('end_date', enrollment.end_date); set('validity_months', enrollment.validity_months);
    set('notes', enrollment.notes);
    toggleScoreField(); setTimeout(updateExpiryHint, 30);
    openOverlay('formOverlay');
}

/* ─────────────────────────────────────────────────────────── */
/* Submeter inscrição                                           */
/* ─────────────────────────────────────────────────────────── */
async function submitEnroll(ev) {
    ev.preventDefault();
    const empIds = Object.keys(selectedEmps);
    if (empIds.length === 0) { toast('Seleciona pelo menos um funcionário.', 'err'); document.getElementById('empSearch').focus(); return; }
    const btn = document.getElementById('formSubmitBtn'); btn.disabled = true;
    const base = {};
    new FormData(document.getElementById('enrollForm')).forEach((v, k) => { if (v !== '') base[k] = v; });
    try {
        if (currentEnrollId) {
            base.employee_id = empIds[0]; btn.textContent = 'A guardar...';
            await apiFetch('PUT', `/enrollments/${currentEnrollId}`, base);
            toast('Inscrição atualizada!', 'ok');
        } else {
            btn.textContent = empIds.length > 1 ? `A inscrever ${empIds.length}...` : 'A inscrever...';
            const results = await Promise.allSettled(empIds.map(id => apiFetch('POST', '/enrollments', { ...base, employee_id: id })));
            const ok  = results.filter(r => r.status === 'fulfilled').length;
            const err = results.filter(r => r.status === 'rejected').length;
            if      (ok > 0 && err === 0) toast(`${ok} inscrição(ões) criada(s) com sucesso!`, 'ok');
            else if (ok > 0)              toast(`${ok} criada(s), ${err} com erro.`, 'ok');
            else                          toast('Erro ao criar inscrições.', 'err');
        }
        closeOverlay('formOverlay'); closeOverlay('detailOverlay'); reloadEvents();
    } catch (err) { toast(err.message ?? 'Erro ao guardar.', 'err'); }
    finally { btn.disabled = false; btn.textContent = currentEnrollId ? 'Guardar' : 'Inscrever'; }
}

/* ─────────────────────────────────────────────────────────── */
/* Modal detalhe — Formação                                     */
/* ─────────────────────────────────────────────────────────── */
const statusLabel = { enrolled: 'Inscrito', completed: 'Concluído', failed: 'Reprovado' };
const statusPill  = { enrolled: 'pill-enrolled', completed: 'pill-completed', failed: 'pill-failed' };
const validLabel  = { valid: '✅ Válida', expiring: '🔔 A expirar (30 dias)', expired: '⚠️ Expirada' };
const validPill   = { valid: 'validity-valid', expiring: 'validity-expiring', expired: 'validity-expired' };

function openDetail(info) {
    const p = info.event.extendedProps; currentDetailId = info.event.id;
    document.getElementById('detailTraining').textContent = p.training || info.event.title;
    document.getElementById('detailEmployee').textContent = p.employee || '—';
    document.getElementById('detailCode').textContent     = p.employeeCode || '—';
    document.getElementById('detailProvider').textContent = p.provider || '—';
    document.getElementById('detailStart').textContent    = p.start_date || '—';
    document.getElementById('detailEnd').textContent      = p.end_date   || '—';
    document.getElementById('detailStatus').innerHTML     = `<span class="status-pill ${statusPill[p.status] ?? ''}">${statusLabel[p.status] ?? p.status}</span>`;
    const scoreRow = document.getElementById('detailScoreRow');
    if (p.score != null) {
        scoreRow.style.display = '';
        document.getElementById('detailScore').textContent = p.score + '%';
        const fill = document.getElementById('detailScoreFill');
        fill.style.width = p.score + '%';
        fill.style.background = p.score >= 70 ? '#22c55e' : p.score >= 40 ? '#f59e0b' : '#ef4444';
    } else scoreRow.style.display = 'none';
    const valRow = document.getElementById('detailValidityRow');
    if (p.validity_months) { valRow.style.display = ''; document.getElementById('detailValidity').textContent = p.validity_months + ' mês' + (p.validity_months > 1 ? 'es' : ''); }
    else valRow.style.display = 'none';
    const expRow = document.getElementById('detailExpiryRow');
    if (p.expiry_date) {
        expRow.style.display = '';
        const vs = p.validity_status;
        document.getElementById('detailExpiry').innerHTML = `${p.expiry_date} <span class="validity-pill ${validPill[vs] ?? ''}">${validLabel[vs] ?? ''}</span>`;
    } else expRow.style.display = 'none';
    const notesRow = document.getElementById('detailNotesRow');
    if (p.notes) { notesRow.style.display = ''; document.getElementById('detailNotes').textContent = p.notes; }
    else notesRow.style.display = 'none';
    openOverlay('detailOverlay');
}
function editFromDetail()   { if (!currentDetailId) return; closeOverlay('detailOverlay'); openEditForm(_lastEventProps); }
function deleteFromDetail() { if (!currentDetailId) return; pendingDeleteId = currentDetailId; closeOverlay('detailOverlay'); openOverlay('confirmOverlay'); }
async function confirmDelete() {
    if (!pendingDeleteId) return;
    try {
        await apiFetch('DELETE', `/enrollments/${pendingDeleteId}`);
        toast('Inscrição eliminada.', 'ok'); closeOverlay('confirmOverlay'); reloadEvents();
    } catch (err) { toast(err.message ?? 'Erro ao eliminar.', 'err'); }
    finally { pendingDeleteId = null; }
}

/* ─────────────────────────────────────────────────────────── */
/* Modal detalhe — Licença                                      */
/* ─────────────────────────────────────────────────────────── */
const leaveTypeLabel  = { vacation: 'Férias', sick: 'Doença', unpaid: 'Não remunerada' };
const leaveStatusLabel = { pending: 'Pendente', approved: 'Aprovado', rejected: 'Rejeitado' };
const leaveStatusClass = { pending: 'badge-pending', approved: 'badge-approved', rejected: 'badge-rejected' };
const leaveTypeBadge   = { vacation: 'badge-vacation', sick: 'badge-sick', unpaid: 'badge-unpaid' };

function openLeaveDetail(p) {
    document.getElementById('ldTitle').textContent    = (p.leave_type_label || 'Licença') + ' — ' + p.employee;
    document.getElementById('ldEmployee').textContent = p.employee || '—';
    document.getElementById('ldCode').textContent     = p.employeeCode || '—';
    document.getElementById('ldType').innerHTML       = `<span class="leave-type-badge ${leaveTypeBadge[p.leave_type] ?? ''}">${leaveTypeLabel[p.leave_type] ?? p.leave_type}</span>`;
    document.getElementById('ldStatus').innerHTML     = `<span class="leave-type-badge ${leaveStatusClass[p.status] ?? ''}">${leaveStatusLabel[p.status] ?? p.status}</span>`;
    document.getElementById('ldStart').textContent    = p.start_date || '—';
    document.getElementById('ldEnd').textContent      = p.end_date   || '—';
    document.getElementById('ldReason').textContent   = p.reason     || '—';
    const cmtRow = document.getElementById('ldCommentRow');
    if (p.manager_comment) { cmtRow.style.display = ''; document.getElementById('ldComment').textContent = p.manager_comment; }
    else cmtRow.style.display = 'none';
    openOverlay('leaveDetailOverlay');
}
function goToLeave() { window.location.href = '/leaves'; }

/* ─────────────────────────────────────────────────────────── */
/* Modal detalhe — Presença                                     */
/* ─────────────────────────────────────────────────────────── */
const attStatusLabel = { absent: 'Ausente', late: 'Atrasado' };
const attStatusClass = { absent: 'badge-rejected', late: 'badge-pending' };

function openAttDetail(p) {
    document.getElementById('adTitle').textContent    = (attStatusLabel[p.status] || p.status) + ' — ' + p.employee;
    document.getElementById('adEmployee').textContent = p.employee    || '—';
    document.getElementById('adCode').textContent     = p.employeeCode || '—';
    document.getElementById('adDate').textContent     = p.date        || '—';
    document.getElementById('adStatus').innerHTML     = `<span class="leave-type-badge ${attStatusClass[p.status] ?? ''}">${attStatusLabel[p.status] ?? p.status}</span>`;
    document.getElementById('adCheckIn').textContent  = p.check_in  || '—';
    document.getElementById('adCheckOut').textContent = p.check_out || '—';
    const notesRow = document.getElementById('adNotesRow');
    if (p.notes) { notesRow.style.display = ''; document.getElementById('adNotes').textContent = p.notes; }
    else notesRow.style.display = 'none';
    openOverlay('attDetailOverlay');
}
function goToAttendances() { window.location.href = '/attendances'; }

/* ─────────────────────────────────────────────────────────── */
/* Modal detalhe — Obra / Equipa                                */
/* ─────────────────────────────────────────────────────────── */
const statusLabelProj = { active: 'Em Curso', planned: 'Planeada', completed: 'Concluída', cancelled: 'Cancelada' };
const statusClassProj = { active: 'badge-active', planned: 'badge-planned', completed: 'badge-completed', cancelled: 'badge-cancelled' };

function openProjDetail(p, isTeam) {
    const teamsData = p.teams || [];
    const teams = isTeam ? [{
        id: p.team_id, name: p.team_name, leader: p.leader || null,
        employees: p.employees || [], vehicles: p.vehicles || [],
    }] : teamsData;

    document.getElementById('pdTitle').textContent    = isTeam ? `👷 ${p.team_name} — ${p.project}` : `🏗️ ${p.name}`;
    document.getElementById('pdClient').textContent   = p.client    || (isTeam ? '' : '—');
    document.getElementById('pdLocation').textContent = p.location  || (isTeam ? '' : '—');
    document.getElementById('pdStart').textContent    = p.start_date || '—';
    document.getElementById('pdEnd').textContent      = p.end_date   || '—';
    document.getElementById('pdRef').textContent      = p.reference  || '—';

    const statusVal = p.status || '';
    document.getElementById('pdStatus').innerHTML = statusVal
        ? `<span class="badge ${statusClassProj[statusVal] || ''}">${statusLabelProj[statusVal] || statusVal}</span>`
        : '—';

    const metaRows = document.querySelectorAll('#pdMeta .detail-item');
    if (isTeam) {
        metaRows[0].style.display = p.client   ? '' : 'none';
        metaRows[1].style.display = p.location ? '' : 'none';
        document.getElementById('pdRefRow').style.display = p.reference ? '' : 'none';
    } else {
        metaRows[0].style.display = '';
        metaRows[1].style.display = '';
        document.getElementById('pdRefRow').style.display = '';
    }

    const pdTeams = document.getElementById('pdTeams');
    if (!teams.length) {
        pdTeams.innerHTML = '<p style="color:var(--text-muted);font-size:.85rem">Sem equipas associadas.</p>';
    } else {
        pdTeams.innerHTML = teams.map(t => {
            const members = (t.employees || []).map(e => `
                <div class="proj-member">
                    <span class="proj-member-name">${e.name || ''}</span>
                    ${e.code ? `<span class="proj-member-code">${e.code}</span>` : ''}
                    ${e.role ? `<span class="proj-member-role">${e.role}</span>` : ''}
                </div>`).join('');
            const vehs = (t.vehicles || []).map(v => `
                <div class="proj-veh-row">🚗 ${v.plate}${v.label && v.label !== v.plate ? ' — ' + v.label : ''}</div>`).join('');
            return `<div class="proj-team-card">
                <div class="proj-team-name">${t.name || 'Equipa'}${t.leader ? ` <small style="font-weight:400;color:var(--text-muted)">– ${t.leader}</small>` : ''}</div>
                ${members ? `<div class="section-mini-label">Membros</div><div class="proj-members">${members}</div>` : '<p style="font-size:.8rem;color:var(--text-muted)">Sem membros.</p>'}
                ${vehs    ? `<div class="section-mini-label" style="margin-top:.6rem">Viaturas</div>${vehs}` : ''}
            </div>`;
        }).join('');
    }
    openOverlay('projDetailOverlay');
}

/* ─────────────────────────────────────────────────────────── */
/* FullCalendar init                                            */
/* ─────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    // Initial sync on load
    syncLegend();
    syncFilters();
    syncToolbar();

    calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        locale: 'pt', initialView: 'dayGridMonth', height: 'auto', firstDay: 1,
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' },
        buttonText: { today: 'Hoje', month: 'Mês', week: 'Semana', list: 'Lista' },
        noEventsText: 'Nenhum evento neste período.',
        eventDisplay: 'block', dayMaxEvents: 3, moreLinkText: n => `+${n} mais`,
        selectable: true, selectMirror: true,

        events: function (fetchInfo, successCallback, failureCallback) {
            const params = buildParams();
            params.set('start', fetchInfo.startStr.substring(0, 10));
            params.set('end',   fetchInfo.endStr.substring(0, 10));
            fetch('/calendar/events?' + params.toString(), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            })
            .then(r => r.json())
            .then(data => {
                syncMeta(data.length);
                successCallback(data);
            })
            .catch(err => { console.error(err); failureCallback(err); });
        },

        dateClick: function (info) {
            // Find the first active type that has a dateClick action
            const type = [...activeTypes].find(t => CALENDAR_TYPES[t]?.onDateClick);
            if (type) CALENDAR_TYPES[type].onDateClick(info.dateStr);
        },

        select: function (info) {
            // Prefer trainings for date-range selection (creating an enrollment with dates pre-filled)
            // Fall back to first active type with any action
            const type = activeTypes.has('trainings')
                ? 'trainings'
                : [...activeTypes].find(t => CALENDAR_TYPES[t]?.onDateClick);
            if (!type) return;
            const handler = CALENDAR_TYPES[type].onDateSelect ?? CALENDAR_TYPES[type].onDateClick;
            if (handler) handler(info.startStr, info.endStr);
        },

        eventClick: function (info) {
            const p = info.event.extendedProps;
            if (p.type === 'leave')       { openLeaveDetail(p); return; }
            if (p.type === 'attendance')  { openAttDetail(p);   return; }
            if (p.type === 'project')     { openProjDetail(p, false); return; }
            if (p.type === 'team')        { openProjDetail(p, true);  return; }
            // training (defa            if (p.type === 'team')        { openProjDetail(p, true);  return; }
            // training (default)
            _lastEventProps = {
                id: p.enrollment_id || info.event.id, employee_id: p.employee_id,
                employee_label: (p.employee || '') + (p.employeeCode ? ` (${p.employeeCode})` : ''),
                training_id: p.training_id, status: p.status, score: p.score,
                start_date: info.event.startStr?.substring(0, 10), end_date: p.end_date_raw,
                validity_months: p.validity_months, notes: p.notes,
            };
            openDetail(info);
        },

        eventMouseEnter: function (info) { info.el.style.transform = 'translateY(-1px)'; info.el.style.boxShadow = '0 4px 16px rgba(0,0,0,.3)'; },
        eventMouseLeave: function (info) { info.el.style.transform = ''; info.el.style.boxShadow = ''; },
    });
    calendar.render();
});

/* Global overlay close handlers */
document.querySelectorAll('.overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.overlay.open').forEach(o => o.classList.remove('open'));
});

/* Expose functions called from inline HTML attributes */
window.toggleType        = toggleType;
window.openCreateForm    = openCreateForm;
window.clearFilters      = clearFilters;
window.reloadEvents      = reloadEvents;
window.closeOverlay      = closeOverlay;
window.submitEnroll      = submitEnroll;
window.toggleScoreField  = toggleScoreField;
window.updateExpiryHint  = updateExpiryHint;
window.openEmpDropdown   = openEmpDropdown;
window.filterEmpOptions  = filterEmpOptions;
window.empSearchKeydown  = empSearchKeydown;
window.toggleEmp         = toggleEmp;
window.removeEmp         = removeEmp;
window.editFromDetail    = editFromDetail;
window.deleteFromDetail  = deleteFromDetail;
window.confirmDelete     = confirmDelete;
window.goToLeave         = goToLeave;
window.goToAttendances   = goToAttendances;
ult)
            _lastEventProps = {
                id: p.enrollment_id || info.event.id, employee_id: p.employee_id,
                employee_label: (p.employee || '') + (p.employeeCode ? ` (${p.employeeCode})` : ''),
                training_id: p.training_id, status: p.status, score: p.score,
                start_date: info.event.startStr?.substring(0, 10), end_date: p.end_date_raw,
                validity_months: p.validity_months, notes: p.notes,
            };
            openDetail(info);
        },

        eventMouseEnter: function (info) { info.el.style.transform = 'translateY(-1px)'; info.el.style.boxShadow = '0 4px 16px rgba(0,0,0,.3)'; },
        eventMouseLeave: function (info) { info.el.style.transform = ''; info.el.style.boxShadow = ''; },
    });
    calendar.render();
});

/* ─────────────────────────────────────────────────────────── */
/* Global overlay close handlers                               */
/* ─────────────────────────────────────────────────────────── */
document.querySelectorAll('.overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.overlay.open').forEach(o => o.classList.remove('open'));
});

/* Expose functions called from inline HTML attributes */
window.toggleType        = toggleType;
window.openCreateForm    = openCreateForm;
window.clearFilters      = clearFilters;
window.reloadEvents      = reloadEvents;
window.closeOverlay      = closeOverlay;
window.submitEnroll      = submitEnroll;
window.toggleScoreField  = toggleScoreField;
window.updateExpiryHint  = updateExpiryHint;
window.openEmpDropdown   = openEmpDropdown;
window.filterEmpOptions  = filterEmpOptions;
window.empSearchKeydown  = empSearchKeydown;
window.toggleEmp         = toggleEmp;
window.removeEmp         = removeEmp;
window.editFromDetail    = editFromDetail;
window.deleteFromDetail  = deleteFromDetail;
window.confirmDelete     = confirmDelete;
window.goToLeave         = goToLeave;
window.goToAttendances   = goToAttendances;
