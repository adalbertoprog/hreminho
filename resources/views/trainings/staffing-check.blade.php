@extends('layouts.app')
@section('title', 'Simulador de Disponibilidade')
@section('page-title', 'Simulador de Disponibilidade')

@section('styles')
<style>
/* ── Layout ── */
.sc-wrap { display:grid; grid-template-columns:380px 1fr; gap:24px; align-items:start; }
@media(max-width:900px){ .sc-wrap { grid-template-columns:1fr; } }

/* ── Formulário ── */
.sc-form-card { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:24px; position:sticky; top:24px; }
.sc-form-title { font-size:1rem; font-weight:700; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
.fg { margin-bottom:14px; }
.fg label { display:block; font-size:.75rem; font-weight:600; color:var(--text-muted); margin-bottom:5px; text-transform:uppercase; letter-spacing:.5px; }
.fg input, .fg select { width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border); border-radius:9px; padding:9px 12px; color:var(--text-primary); font-size:.875rem; font-family:inherit; }
.fg input:focus, .fg select:focus { outline:none; border-color:var(--accent); }
.fg select option { background:var(--bg-card); }

/* Requisitos */
.req-list { display:flex; flex-direction:column; gap:10px; margin-bottom:12px; }
.req-row { display:grid; grid-template-columns:1fr 80px 32px; gap:8px; align-items:center; }
.req-row select, .req-row input { background:rgba(255,255,255,.05); border:1px solid var(--border); border-radius:8px; padding:7px 10px; color:var(--text-primary); font-size:.82rem; font-family:inherit; width:100%; }
.req-row input { text-align:center; }
.req-row input:focus, .req-row select:focus { outline:none; border-color:var(--accent); }
.btn-remove-req { background:rgba(239,68,68,.12); border:none; color:#ef4444; border-radius:7px; width:32px; height:32px; cursor:pointer; font-size:1rem; display:flex; align-items:center; justify-content:center; transition:.15s; flex-shrink:0; }
.btn-remove-req:hover { background:rgba(239,68,68,.25); }
.btn-add-req { width:100%; background:rgba(99,102,241,.1); border:1px dashed rgba(99,102,241,.4); border-radius:9px; padding:8px; color:var(--accent-light); font-size:.82rem; font-weight:600; cursor:pointer; transition:.15s; }
.btn-add-req:hover { background:rgba(99,102,241,.18); }
.btn-check { width:100%; background:var(--accent); color:#fff; border:none; border-radius:10px; padding:11px; font-size:.9rem; font-weight:700; cursor:pointer; transition:.15s; margin-top:6px; display:flex; align-items:center; justify-content:center; gap:8px; }
.btn-check:hover { background:#4f46e5; }
.btn-check:disabled { opacity:.5; cursor:not-allowed; }

/* ── Resultados ── */
.sc-results { display:flex; flex-direction:column; gap:16px; }
.sc-empty { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:60px 24px; text-align:center; color:var(--text-muted); }
.sc-empty .empty-icon { font-size:2.5rem; display:block; margin-bottom:12px; }

/* Banner global */
.sc-banner { border-radius:14px; padding:20px 24px; display:flex; align-items:center; gap:16px; }
.sc-banner.ok      { background:rgba(34,197,94,.1);  border:1px solid rgba(34,197,94,.25); }
.sc-banner.warning { background:rgba(245,158,11,.1); border:1px solid rgba(245,158,11,.25); }
.sc-banner.gap     { background:rgba(239,68,68,.1);  border:1px solid rgba(239,68,68,.25); }
.sc-banner-icon { font-size:2rem; flex-shrink:0; }
.sc-banner-title { font-size:1.05rem; font-weight:700; margin-bottom:2px; }
.sc-banner.ok      .sc-banner-title { color:#22c55e; }
.sc-banner.warning .sc-banner-title { color:#f59e0b; }
.sc-banner.gap     .sc-banner-title { color:#ef4444; }
.sc-banner-sub { font-size:.85rem; color:var(--text-muted); }

/* Cards de resultado por formação */
.res-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.res-card-head { padding:16px 20px; display:flex; align-items:center; gap:14px; border-bottom:1px solid var(--border); }
.res-status-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.res-status-dot.ok      { background:#22c55e; box-shadow:0 0 6px #22c55e55; }
.res-status-dot.warning { background:#f59e0b; box-shadow:0 0 6px #f59e0b55; }
.res-status-dot.gap     { background:#ef4444; box-shadow:0 0 6px #ef444455; }
.res-card-title { font-weight:700; font-size:.95rem; flex:1; min-width:0; }
.res-card-badges { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }

.badge-pill { display:inline-flex; align-items:center; gap:5px; border-radius:20px; padding:4px 11px; font-size:.75rem; font-weight:700; border:1px solid; white-space:nowrap; }
.badge-ok      { background:rgba(34,197,94,.12);  color:#22c55e; border-color:rgba(34,197,94,.3); }
.badge-warning { background:rgba(245,158,11,.12); color:#f59e0b; border-color:rgba(245,158,11,.3); }
.badge-gap     { background:rgba(239,68,68,.12);  color:#ef4444; border-color:rgba(239,68,68,.3); }
.badge-neutral { background:rgba(148,163,184,.1); color:var(--text-muted); border-color:rgba(148,163,184,.2); }

/* Barra de progresso de disponibilidade */
.avail-bar-wrap { padding:16px 20px; border-bottom:1px solid rgba(255,255,255,.05); }
.avail-bar-labels { display:flex; justify-content:space-between; font-size:.78rem; color:var(--text-muted); margin-bottom:6px; }
.avail-bar-labels strong { color:var(--text-primary); }
.avail-bar-bg { background:rgba(255,255,255,.06); border-radius:99px; height:8px; overflow:hidden; }
.avail-bar-fill { height:100%; border-radius:99px; transition:width .5s ease; }
.avail-bar-fill.ok      { background:linear-gradient(90deg,#22c55e,#16a34a); }
.avail-bar-fill.warning { background:linear-gradient(90deg,#f59e0b,#d97706); }
.avail-bar-fill.gap     { background:linear-gradient(90deg,#ef4444,#dc2626); }

/* Secções de técnicos */
.res-sections { padding:16px 20px; display:flex; flex-direction:column; gap:14px; }
.sec-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.7px; margin-bottom:8px; display:flex; align-items:center; gap:6px; }
.sec-label.ok      { color:#22c55e; }
.sec-label.warning { color:#f59e0b; }
.sec-label.gap     { color:#ef4444; }
.sec-label.muted   { color:var(--text-muted); }

.emp-chips { display:flex; flex-wrap:wrap; gap:6px; }
.emp-chip { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.05); border:1px solid var(--border); border-radius:8px; padding:5px 10px; font-size:.78rem; }
.emp-chip .chip-code { font-family:monospace; font-size:.72rem; color:var(--text-muted); }
.emp-chip .chip-dept { font-size:.68rem; color:var(--text-muted); }
.emp-chip.chip-warn { border-color:rgba(245,158,11,.3); background:rgba(245,158,11,.07); }
.emp-chip.chip-warn .chip-expiry { color:#f59e0b; font-weight:700; font-size:.7rem; }
.emp-chip.chip-expired { border-color:rgba(239,68,68,.3); background:rgba(239,68,68,.07); }

/* Alerta de renovação */
.renewal-alert { background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.25); border-radius:10px; padding:12px 14px; font-size:.82rem; color:#f59e0b; display:flex; gap:10px; align-items:flex-start; }
.gap-alert { background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); border-radius:10px; padding:12px 14px; font-size:.82rem; color:#ef4444; display:flex; gap:10px; align-items:flex-start; }
.alert-icon { font-size:1rem; flex-shrink:0; margin-top:1px; }

/* Spinner */
.spin { display:inline-block; width:16px; height:16px; border:2px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:spin .6s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }

/* Tooltip */
[title] { cursor:help; }

/* Toast de validação inline */
.sc-toast { display:flex; align-items:center; gap:10px; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); border-radius:10px; padding:10px 14px; font-size:.83rem; color:#ef4444; font-weight:500; margin-top:10px; animation:fadeIn .2s ease; }
.sc-toast.hidden { display:none; }
@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:none; } }

/* Botão exportar PDF */
.btn-export-pdf { display:inline-flex; align-items:center; gap:7px; background:rgba(99,102,241,.12); border:1px solid rgba(99,102,241,.3); border-radius:9px; padding:8px 14px; color:var(--accent-light); font-size:.82rem; font-weight:600; cursor:pointer; transition:.15s; }
.btn-export-pdf:hover { background:rgba(99,102,241,.22); }
.btn-export-pdf:disabled { opacity:.4; cursor:not-allowed; }
</style>
@endsection

@section('content')
<div style="margin-bottom:20px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;margin-bottom:4px">🔍 Simulador de Disponibilidade Técnica</h1>
        <p style="color:var(--text-muted);font-size:.88rem">Verifique se a empresa tem técnicos certificados suficientes para uma empreitada, e identifique lacunas de formação com tempo suficiente para as colmatar.</p>
    </div>
    <button class="btn-export-pdf" id="btn-pdf" onclick="exportPDF()" disabled title="Execute uma simulação primeiro">
        📄 Exportar PDF
    </button>
</div>

<div class="sc-wrap">

    {{-- ── FORMULÁRIO ── --}}
    <div class="sc-form-card">
        <div class="sc-form-title">⚙️ Parâmetros da Simulação</div>

        <div class="fg">
            <label>Início da empreitada *</label>
            <input type="date" id="f-start" required>
        </div>
        <div class="fg">
            <label>Fim da empreitada *</label>
            <input type="date" id="f-end" required>
        </div>

        <div class="fg" style="margin-bottom:8px">
            <label>Requisitos de certificação</label>
            <p style="font-size:.75rem;color:var(--text-muted);margin-bottom:10px">Para cada área de formação, indique o nº de técnicos certificados necessários.</p>
            <div class="req-list" id="req-list"></div>
            <button class="btn-add-req" onclick="addRequirement()">+ Adicionar formação</button>
        </div>

        <div class="sc-toast hidden" id="sc-toast"><span>⚠️</span><span id="sc-toast-msg"></span></div>

        <button class="btn-check" id="btn-check" onclick="runCheck()">
            <span id="btn-check-text">🔍 Verificar Disponibilidade</span>
        </button>
        <button id="btn-clear" onclick="clearResults()" style="display:none;width:100%;margin-top:8px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:9px;font-size:.85rem;font-weight:600;color:#ef4444;cursor:pointer;transition:.15s" onmouseover="this.style.background='rgba(239,68,68,.15)'" onmouseout="this.style.background='rgba(239,68,68,.08)'">
            ✕ Limpar resultados
        </button>
    </div>

    {{-- ── RESULTADOS ── --}}
    <div class="sc-results" id="results">
        <div class="sc-empty">
            <span class="empty-icon">📋</span>
            <p style="font-size:.9rem;font-weight:600;margin-bottom:6px">Configure os parâmetros e clique em verificar</p>
            <p style="font-size:.8rem">O sistema irá analisar a disponibilidade de técnicos certificados para o período indicado.</p>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const TRAININGS = @json($trainings);

// ── Req rows ─────────────────────────────────────────────────────────────
let reqCount = 0;

function addRequirement(trainingId = '', qty = 1) {
    reqCount++;
    const id = 'req-' + reqCount;
    const options = TRAININGS.map(t =>
        `<option value="${t.id}" ${t.id == trainingId ? 'selected' : ''}>${escHtml(t.title)}</option>`
    ).join('');

    const row = document.createElement('div');
    row.className = 'req-row';
    row.id = id;
    row.innerHTML = `
        <select title="Formação requerida">
            <option value="">— Formação —</option>
            ${options}
        </select>
        <input type="number" min="1" max="999" value="${qty}" title="Nº de técnicos necessários" placeholder="Qtd.">
        <button class="btn-remove-req" onclick="removeReq('${id}')" title="Remover">✕</button>
    `;
    document.getElementById('req-list').appendChild(row);
}

function removeReq(id) {
    document.getElementById(id)?.remove();
}

function getRequirements() {
    return [...document.querySelectorAll('#req-list .req-row')].map(row => ({
        training_id: parseInt(row.querySelector('select').value) || null,
        quantity:    parseInt(row.querySelector('input').value)  || 1,
    })).filter(r => r.training_id);
}

// ── Validação inline ──────────────────────────────────────────────────────
function showFormError(msg) {
    const toast = document.getElementById('sc-toast');
    document.getElementById('sc-toast-msg').textContent = msg;
    toast.classList.remove('hidden');
    clearTimeout(showFormError._t);
    showFormError._t = setTimeout(() => toast.classList.add('hidden'), 4000);
}
function clearFormError() {
    document.getElementById('sc-toast').classList.add('hidden');
}

// ── Verificação ───────────────────────────────────────────────────────────
async function runCheck() {
    const start = document.getElementById('f-start').value;
    const end   = document.getElementById('f-end').value;
    const reqs  = getRequirements();

    if (!start || !end) { showFormError('Indique as datas de início e fim da empreitada.'); return; }
    if (!reqs.length)   { showFormError('Adicione pelo menos uma formação aos requisitos.'); return; }
    clearFormError();

    const btn  = document.getElementById('btn-check');
    const btnT = document.getElementById('btn-check-text');
    btn.disabled = true;
    btnT.innerHTML = '<span class="spin"></span> A analisar...';

    try {
        const res = await fetch('/api/v1/staffing-check', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ start_date: start, end_date: end, requirements: reqs }),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Erro');
        renderResults(json);
    } catch(e) {
        document.getElementById('results').innerHTML =
            `<div class="sc-empty"><span class="empty-icon">⚠️</span><p>${e.message}</p></div>`;
    } finally {
        btn.disabled = false;
        btnT.innerHTML = '🔍 Verificar Disponibilidade';
    }
}

// ── Renderização ──────────────────────────────────────────────────────────
function fmtDate(d) {
    if (!d) return '—';
    const [y, m, day] = d.split('-');
    return `${day}/${m}/${y}`;
}

function escHtml(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Limpar resultados ─────────────────────────────────────────────────────
function clearResults() {
    _lastData = null;
    document.getElementById('results').innerHTML = `
        <div class="sc-empty">
            <span class="empty-icon">📋</span>
            <p style="font-size:.9rem;font-weight:600;margin-bottom:6px">Configure os parâmetros e clique em verificar</p>
            <p style="font-size:.8rem">O sistema irá analisar a disponibilidade de técnicos certificados para o período indicado.</p>
        </div>`;
    document.getElementById('btn-pdf').disabled = true;
    document.getElementById('btn-clear').style.display = 'none';
}

// ── Exportar PDF ──────────────────────────────────────────────────────────
let _lastData = null;

function exportPDF() {
    if (!_lastData) return;
    const { global_status, total_gap, start_date, end_date, duration_days, results } = _lastData;

    const periodStr   = `${fmtDate(start_date)} → ${fmtDate(end_date)} (${duration_days} dia${duration_days===1?'':'s'})`;
    const statusLabel = { ok: 'Disponibilidade Total ✅', warning: 'Disponível com Alertas ⚠️', gap: `Insuficiente — ${total_gap} técnico(s) em falta 🚨` };
    const statusColor = { ok: '#16a34a', warning: '#d97706', gap: '#dc2626' };
    const statusBg    = { ok: '#f0fdf4', warning: '#fffbeb', gap: '#fef2f2' };
    const statusBorder= { ok: '#86efac', warning: '#fcd34d', gap: '#fca5a5' };

    // Gerar HTML dos cards
    let cardsHtml = '';
    for (const r of results) {
        const pct   = Math.min(100, Math.round((r.available / r.needed) * 100));
        const barColor = { ok:'#22c55e', warning:'#f59e0b', gap:'#ef4444' }[r.status];
        const allQual  = [...r.qualified, ...r.no_expiry];

        const chipStyle = (cls) => cls === 'chip-warn'
            ? 'background:#fffbeb;border:1px solid #fcd34d;'
            : cls === 'chip-expired'
            ? 'background:#fef2f2;border:1px solid #fca5a5;'
            : 'background:#f9fafb;border:1px solid #e5e7eb;';

        const qualChips = allQual.length
            ? allQual.map(e => `<span style="display:inline-flex;align-items:center;gap:5px;border-radius:6px;padding:3px 8px;font-size:8.5pt;margin:2px;${chipStyle('')}">
                ${escHtml(e.name)}
                <span style="font-size:7.5pt;color:#6b7280;font-family:monospace">${escHtml(e.code)}</span>
                ${e.expiry ? `<span style="font-size:7pt;color:#6b7280">✓ ${fmtDate(e.expiry)}</span>` : '<span style="font-size:7pt;color:#6b7280">∞</span>'}
              </span>`).join('')
            : '<span style="font-size:8pt;color:#9ca3af">Nenhum técnico qualificado.</span>';

        const expiringChips = r.expiring_during.map(e => `<span style="display:inline-flex;align-items:center;gap:5px;border-radius:6px;padding:3px 8px;font-size:8.5pt;margin:2px;${chipStyle('chip-warn')}">
            ${escHtml(e.name)}
            <span style="font-size:7.5pt;color:#6b7280;font-family:monospace">${escHtml(e.code)}</span>
            <span style="font-size:7pt;color:#d97706;font-weight:700">expira ${fmtDate(e.expiry)}</span>
          </span>`).join('');

        const expiredChips = r.expired_before.map(e => `<span style="display:inline-flex;align-items:center;gap:5px;border-radius:6px;padding:3px 8px;font-size:8.5pt;margin:2px;${chipStyle('chip-expired')}">
            ${escHtml(e.name)}
            <span style="font-size:7.5pt;color:#6b7280;font-family:monospace">${escHtml(e.code)}</span>
            <span style="font-size:7pt;color:#ef4444">expirou ${fmtDate(e.expiry)}</span>
          </span>`).join('');

        const rColor  = { ok:'#16a34a', warning:'#d97706', gap:'#dc2626' }[r.status];
        const rLabel  = { ok:'Disponível', warning:'Expira durante a obra', gap:'Insuficiente' }[r.status];
        const dotColor = { ok:'#22c55e', warning:'#f59e0b', gap:'#ef4444' }[r.status];

        cardsHtml += `
        <div style="border:1px solid #e5e7eb;border-radius:8px;margin-bottom:14px;page-break-inside:avoid;overflow:hidden;">
            <div style="padding:12px 16px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:10px;">
                <div style="width:10px;height:10px;border-radius:50%;background:${dotColor};flex-shrink:0"></div>
                <div style="font-weight:700;font-size:11pt;flex:1">${escHtml(r.training_title)}</div>
                <span style="border-radius:20px;padding:3px 10px;font-size:8pt;font-weight:700;border:1px solid;background:${statusBg[r.status]};color:${rColor};border-color:${statusBorder[r.status]}">${rLabel}</span>
                <span style="border-radius:20px;padding:3px 10px;font-size:8pt;font-weight:700;border:1px solid #e5e7eb;background:#f9fafb;color:#555">${r.available}/${r.needed} técnicos</span>
            </div>
            <div style="padding:10px 16px;border-bottom:1px solid #f3f4f6;">
                <div style="display:flex;justify-content:space-between;font-size:8pt;color:#6b7280;margin-bottom:5px;">
                    <span>Disponibilidade</span>
                    <strong style="color:#111">${r.available} de ${r.needed} necessários${r.gap>0?' (faltam '+r.gap+')':''}</strong>
                </div>
                <div style="background:#e5e7eb;border-radius:99px;height:7px;overflow:hidden;">
                    <div style="height:100%;border-radius:99px;width:${pct}%;background:${barColor}"></div>
                </div>
            </div>
            <div style="padding:12px 16px;">
                <div style="font-size:8pt;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#16a34a;margin-bottom:6px;">✅ Técnicos disponíveis (${allQual.length})</div>
                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:${r.expiring_during.length||r.gap||r.expired_before.length?'12px':'0'}">${qualChips}</div>
                ${r.expiring_during.length ? `
                <div style="font-size:8pt;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#d97706;margin-bottom:6px;">⚠️ Expira durante a obra (${r.expiring_during.length})</div>
                <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:6px;padding:7px 10px;font-size:8pt;color:#d97706;margin-bottom:8px;">💡 Certificado válido no início mas expira antes do fim da obra. Considere renovar atempadamente.</div>
                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:${r.gap||r.expired_before.length?'12px':'0'}">${expiringChips}</div>` : ''}
                ${r.gap > 0 ? `
                <div style="font-size:8pt;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#dc2626;margin-bottom:6px;">🚨 Técnicos em falta (${r.gap})</div>
                <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:6px;padding:7px 10px;font-size:8pt;color:#dc2626;">📅 Faltam <strong>${r.gap} técnico(s)</strong> certificados. Têm <strong>${r.days_until_start} dia(s)</strong> até ao início da obra.</div>` : ''}
                ${r.expired_before.length ? `
                <div style="font-size:8pt;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-top:12px;margin-bottom:6px;">❌ Certificado expirado antes da obra (${r.expired_before.length})</div>
                <div style="display:flex;flex-wrap:wrap;gap:4px">${expiredChips}</div>` : ''}
            </div>
        </div>`;
    }

    const html = `<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Relatório de Disponibilidade Técnica</title>
<style>
  * { -webkit-print-color-adjust:exact; print-color-adjust:exact; box-sizing:border-box; }
  body { font-family:system-ui,sans-serif; font-size:10pt; color:#111; background:#fff; margin:0; padding:24px 28px; }
  @page { margin:18mm 14mm; }
  @media print { body { padding:0; } }
</style>
</head>
<body>
  <div style="border-bottom:3px solid #6366f1;padding-bottom:14px;margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
      <img src="${window.location.origin}/images/logo.jpg" alt="HRElectrominho" style="height:48px;width:auto;object-fit:contain;flex-shrink:0">
      <div style="flex:1">
        <h1 style="margin:0 0 3px;font-size:14pt;font-weight:800;color:#111">Relatório de Disponibilidade Técnica</h1>
        <p style="margin:0;font-size:8.5pt;color:#6b7280">Período: ${periodStr} &nbsp;·&nbsp; Gerado em: ${new Date().toLocaleString('pt-PT')}</p>
      </div>
    </div>
  </div>

  <div style="background:${statusBg[global_status]};border:1px solid ${statusBorder[global_status]};border-radius:8px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:14px;">
    <div style="font-size:11pt;font-weight:700;color:${statusColor[global_status]}">${statusLabel[global_status]}</div>
    <div style="font-size:9pt;color:#6b7280">Período analisado: ${periodStr}</div>
  </div>

  ${cardsHtml}

  <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e5e7eb;font-size:8pt;color:#9ca3af;display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <div style="display:flex;align-items:center;gap:8px;">
      <img src="${window.location.origin}/images/logo.jpg" alt="" style="height:18px;width:auto;opacity:.5">
      <span>HRElectrominho — Relatório de Disponibilidade Técnica</span>
    </div>
    <span>${new Date().toLocaleDateString('pt-PT')}</span>
  </div>
</body>
</html>`;

    const win = window.open('', '_blank', 'width=900,height=700');
    win.document.write(html);
    win.document.close();
    win.focus();
    setTimeout(() => { win.print(); }, 400);
}

function renderResults(data) {
    _lastData = data;
    document.getElementById('btn-pdf').disabled = false;
    document.getElementById('btn-clear').style.display = 'block';

    const { global_status, total_gap, start_date, end_date, duration_days, results } = data;

    const bannerMap = {
        ok:      { icon:'✅', title:'Empresa com disponibilidade total', color:'ok' },
        warning: { icon:'⚠️', title:'Disponível, mas com certificações a expirar durante a obra', color:'warning' },
        gap:     { icon:'🚨', title:`Faltam ${total_gap} técnico(s) certificado(s)`, color:'gap' },
    };
    const b = bannerMap[global_status];

    const periodStr = `${fmtDate(start_date)} → ${fmtDate(end_date)} (${duration_days} dia${duration_days===1?'':'s'})`;

    let html = `
    <div class="sc-banner ${b.color}">
        <div class="sc-banner-icon">${b.icon}</div>
        <div>
            <div class="sc-banner-title">${b.title}</div>
            <div class="sc-banner-sub">Período analisado: ${periodStr}</div>
        </div>
    </div>`;

    for (const r of results) {
        html += renderCard(r);
    }

    document.getElementById('results').innerHTML = html;
}

function renderCard(r) {
    const pct   = Math.min(100, Math.round((r.available / r.needed) * 100));
    const color = r.status;

    const statusLabel = { ok: 'Disponível', warning: 'Expira durante a obra', gap: 'Insuficiente' };
    const statusBadge = `<span class="badge-pill badge-${color}">${statusLabel[r.status]}</span>`;

    // Barra de disponibilidade
    const barWidth = pct + '%';

    // Técnicos qualificados (inclui sem validade)
    const allQualified = [...r.qualified, ...r.no_expiry];
    const qualifiedHtml = allQualified.length
        ? allQualified.map(e => `
            <div class="emp-chip">
                <span>${escHtml(e.name)}</span>
                <span class="chip-code">${escHtml(e.code)}</span>
                ${e.department ? `<span class="chip-dept">· ${escHtml(e.department)}</span>` : ''}
                ${e.expiry ? `<span class="chip-dept" title="Válido até ${fmtDate(e.expiry)}">✓ ${fmtDate(e.expiry)}</span>` : '<span class="chip-dept">∞ Sem validade</span>'}
            </div>`).join('')
        : '<span style="font-size:.8rem;color:var(--text-muted)">Nenhum técnico qualificado disponível.</span>';

    // Expira durante a obra
    let expiringHtml = '';
    if (r.expiring_during.length) {
        const chips = r.expiring_during.map(e => `
            <div class="emp-chip chip-warn">
                <span>${escHtml(e.name)}</span>
                <span class="chip-code">${escHtml(e.code)}</span>
                <span class="chip-expiry">expira ${fmtDate(e.expiry)}</span>
            </div>`).join('');
        expiringHtml = `
        <div>
            <div class="sec-label warning">⚠️ Expira durante a obra (${r.expiring_during.length})</div>
            <div class="renewal-alert" style="margin-bottom:10px">
                <span class="alert-icon">💡</span>
                <span>Estes técnicos estão disponíveis no início mas o certificado expira antes do fim da obra. Considere renovar a formação atempadamente.</span>
            </div>
            <div class="emp-chips">${chips}</div>
        </div>`;
    }

    // Gap — quem falta
    let gapHtml = '';
    if (r.gap > 0) {
        const daysLeft = r.days_until_start;
        const urgency  = daysLeft < 30 ? '🔴 Urgente' : daysLeft < 90 ? '🟠 Atenção' : '🟡 Planeado';
        gapHtml = `
        <div>
            <div class="sec-label gap">🚨 Técnicos em falta (${r.gap})</div>
            <div class="gap-alert">
                <span class="alert-icon">📅</span>
                <div>
                    <strong>Faltam ${r.gap} técnico(s) certificado(s).</strong>
                    Têm <strong>${daysLeft} dia(s)</strong> até ao início da obra para organizar formação.
                    <span style="margin-left:6px">${urgency}</span>
                </div>
            </div>
        </div>`;
    }

    // Técnicos com certificado expirado
    let expiredHtml = '';
    if (r.expired_before.length) {
        const chips = r.expired_before.map(e => `
            <div class="emp-chip chip-expired">
                <span>${escHtml(e.name)}</span>
                <span class="chip-code">${escHtml(e.code)}</span>
                <span style="font-size:.7rem;color:#f87171">expirou ${fmtDate(e.expiry)}</span>
            </div>`).join('');
        expiredHtml = `
        <div>
            <div class="sec-label muted">❌ Certificado expirado antes da obra (${r.expired_before.length})</div>
            <div class="emp-chips">${chips}</div>
        </div>`;
    }

    return `
    <div class="res-card">
        <div class="res-card-head">
            <div class="res-status-dot ${color}"></div>
            <div class="res-card-title">${escHtml(r.training_title)}</div>
            <div class="res-card-badges">
                ${statusBadge}
                <span class="badge-pill badge-neutral" title="Necessários / Disponíveis">${r.available}/${r.needed} técnicos</span>
            </div>
        </div>
        <div class="avail-bar-wrap">
            <div class="avail-bar-labels">
                <span>Disponibilidade</span>
                <strong>${r.available} de ${r.needed} necessários${r.gap > 0 ? ` (faltam ${r.gap})` : ''}</strong>
            </div>
            <div class="avail-bar-bg">
                <div class="avail-bar-fill ${color}" style="width:${barWidth}"></div>
            </div>
        </div>
        <div class="res-sections">
            <div>
                <div class="sec-label ok">✅ Técnicos disponíveis (${allQualified.length})</div>
                <div class="emp-chips">${qualifiedHtml}</div>
            </div>
            ${expiringHtml}
            ${gapHtml}
            ${expiredHtml}
        </div>
    </div>`;
}

// ── Init ──────────────────────────────────────────────────────────────────
(function initFromParams() {
    const params = new URLSearchParams(window.location.search);
    const pStart = params.get('start');
    const pEnd   = params.get('end');
    const pName  = params.get('name');

    const today = new Date();
    const in30  = new Date(today); in30.setDate(in30.getDate() + 30);

    document.getElementById('f-start').value = pStart || today.toISOString().slice(0,10);
    document.getElementById('f-end').value   = pEnd   || in30.toISOString().slice(0,10);

    // Banner de contexto quando vem de uma obra
    if (pName || pStart) {
        const name = pName ? decodeURIComponent(pName) : null;
        const banner = document.createElement('div');
        banner.id = 'obra-context-banner';
        banner.style.cssText = 'background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);border-radius:12px;padding:12px 18px;display:flex;align-items:center;gap:12px;margin-bottom:20px;font-size:.875rem;';
        banner.innerHTML = `
            <span style="font-size:1.3rem;flex-shrink:0">🏗️</span>
            <div style="flex:1;min-width:0">
                <strong style="color:#818cf8">Pré-preenchido a partir de uma obra</strong>
                ${name ? `<span style="color:var(--text-muted)"> · ${escHtml(name)}</span>` : ''}
                ${pStart ? `<div style="color:var(--text-muted);font-size:.8rem;margin-top:2px">Período: ${fmtDate(pStart)}${pEnd ? ' → ' + fmtDate(pEnd) : ''}</div>` : ''}
            </div>
            <button onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1rem;flex-shrink:0" title="Fechar">✕</button>
        `;
        const wrap = document.querySelector('.sc-wrap');
        wrap.parentElement.insertBefore(banner, wrap);
    }

    // Um requisito inicial vazio
    addRequirement();
})();
</script>
@endsection
