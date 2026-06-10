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
</script>
@endsection
