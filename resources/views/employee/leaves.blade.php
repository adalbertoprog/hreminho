@extends('layouts.app')

@section('title', 'Licenças e Férias')
@section('page-title', 'Licenças e Férias')

@section('styles')
<style>
.lv-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.lv-header h1 { font-size:1.2rem; font-weight:700; margin:0; }

/* Summary cards */
.stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:14px; margin-bottom:28px; }
.stat-card { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:16px 12px; text-align:center; }
.stat-card .num { font-size:1.7rem; font-weight:800; }
.stat-card .lbl { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--text-muted); margin-top:4px; }

/* Table */
.lv-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.lv-table th { padding:10px 14px; text-align:left; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--text-muted); border-bottom:1px solid var(--border); }
.lv-table td { padding:10px 14px; border-bottom:1px solid rgba(255,255,255,.04); }
.lv-table tr:last-child td { border-bottom:none; }
.lv-table tr:hover td { background:rgba(255,255,255,.02); }

/* Badges */
.badge { display:inline-block; padding:3px 10px; border-radius:6px; font-size:.72rem; font-weight:700; }
.badge-vacation { background:rgba(8,145,178,.15); color:#0891b2; }
.badge-sick     { background:rgba(217,119,6,.15);  color:#d97706; }
.badge-unpaid   { background:rgba(124,58,237,.15); color:#7c3aed; }
.badge-pending  { background:rgba(245,158,11,.15); color:#f59e0b; }
.badge-approved { background:rgba(34,197,94,.15);  color:#22c55e; }
.badge-rejected { background:rgba(239,68,68,.15);  color:#ef4444; }

/* Modal */
.overlay { display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.6); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:14px; }
.overlay.open { display:flex; }
.modal { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:28px; width:100%; max-width:500px; box-shadow:0 24px 80px rgba(0,0,0,.5); }
.modal h3 { font-size:1rem; font-weight:700; margin:0 0 20px; }
.fg { margin-bottom:14px; }
.fg label { display:block; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-muted); margin-bottom:5px; }
.fg input, .fg select, .fg textarea {
    width:100%; background:var(--bg-input,var(--bg-sidebar)); border:1px solid var(--border);
    color:var(--text-primary); padding:9px 12px; border-radius:8px; font-size:.875rem;
    outline:none; transition:border-color .15s; font-family:inherit; resize:vertical;
}
.fg input:focus, .fg select:focus, .fg textarea:focus { border-color:var(--accent); }
.modal-foot { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.btn-cancel { padding:8px 18px; border-radius:8px; background:rgba(255,255,255,.06); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; font-size:.875rem; font-weight:600; }
.btn-primary { padding:8px 20px; border-radius:8px; background:var(--accent); border:none; color:#fff; cursor:pointer; font-size:.875rem; font-weight:600; transition:background .15s; }
.btn-primary:hover { background:var(--accent-light); }
.btn-primary:disabled { opacity:.5; cursor:not-allowed; }
.btn-danger  { padding:4px 10px; border-radius:6px; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:#ef4444; cursor:pointer; font-size:.76rem; font-weight:600; transition:.15s; }
.btn-danger:hover { background:rgba(239,68,68,.22); }

/* Comment block */
.comment-block { margin-top:6px; font-size:.78rem; color:var(--text-muted); font-style:italic; }

/* Toast */
.toast-wrap { position:fixed; bottom:24px; right:24px; display:flex; flex-direction:column; gap:8px; z-index:9999; pointer-events:none; }
.toast { padding:10px 18px; border-radius:10px; font-size:.86rem; font-weight:600; opacity:0; transform:translateY(8px); transition:.3s; pointer-events:none; max-width:320px; }
.toast.show { opacity:1; transform:none; }
.toast.ok  { background:#166534; color:#dcfce7; border:1px solid #22c55e44; }
.toast.err { background:#7f1d1d; color:#fee2e2; border:1px solid #ef444444; }
</style>
@endsection

@section('content')

@if(! $employee)
<div style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);border-radius:12px;padding:20px 24px;margin-bottom:24px">
    <strong style="color:#f59e0b">⚠️ Conta não associada.</strong>
    <span style="color:var(--text-muted);font-size:.9rem;margin-left:8px">Associe a sua conta a um registo de funcionário no <a href="{{ route('employee.dashboard') }}" style="color:var(--accent-light)">Início</a> para poder submeter pedidos.</span>
</div>
@endif

<div class="lv-header">
    <h1>🏖️ Licenças e Férias</h1>
    @if($employee)
    <button class="btn-primary" onclick="openModal()">+ Novo Pedido</button>
    @endif
</div>

{{-- Resumo --}}
@if($employee)
@php
    $approved = $leaves->where('status','approved')->count();
    $pending  = $leaves->where('status','pending')->count();
    $rejected = $leaves->where('status','rejected')->count();
    $total    = $leaves->count();
@endphp
<div class="stat-grid">
    <div class="stat-card"><div class="num" style="color:#22c55e">{{ $approved }}</div><div class="lbl">Aprovados</div></div>
    <div class="stat-card"><div class="num" style="color:#f59e0b">{{ $pending }}</div><div class="lbl">Pendentes</div></div>
    <div class="stat-card"><div class="num" style="color:#ef4444">{{ $rejected }}</div><div class="lbl">Rejeitados</div></div>
    <div class="stat-card"><div class="num" style="color:var(--text-primary)">{{ $total }}</div><div class="lbl">Total</div></div>
</div>
@endif

{{-- Lista --}}
@if($leaves->isEmpty())
<div style="padding:48px 20px;text-align:center;color:var(--text-muted)">
    <div style="font-size:2.5rem;margin-bottom:12px">📭</div>
    <p>Nenhum pedido de licença registado.</p>
    @if($employee)<p style="font-size:.85rem">Clique em <strong>+ Novo Pedido</strong> para submeter o primeiro.</p>@endif
</div>
@else
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden">
<table class="lv-table">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Dias</th>
            <th>Motivo</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="lvBody">
        @php
            $typeLabels   = ['vacation'=>'Férias','sick'=>'Doença','unpaid'=>'Não rem.'];
            $statusLabels = ['pending'=>'Pendente','approved'=>'Aprovado','rejected'=>'Rejeitado'];
        @endphp
        @foreach($leaves as $lv)
        <tr data-id="{{ $lv->id }}" data-status="{{ $lv->status }}">
            <td><span class="badge badge-{{ $lv->leave_type }}">{{ $typeLabels[$lv->leave_type] ?? $lv->leave_type }}</span></td>
            <td style="font-weight:500">{{ $lv->start_date?->format('d/m/Y') }}</td>
            <td style="font-weight:500">{{ $lv->end_date?->format('d/m/Y') }}</td>
            <td style="color:var(--text-muted)">
                @if($lv->start_date && $lv->end_date)
                    {{ $lv->start_date->diffInDays($lv->end_date) + 1 }}
                @else —
                @endif
            </td>
            <td style="color:var(--text-muted);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                {{ $lv->reason ?: '—' }}
            </td>
            <td>
                <span class="badge badge-{{ $lv->status }}">{{ $statusLabels[$lv->status] ?? $lv->status }}</span>
                @if($lv->manager_comment)
                    <div class="comment-block">💬 {{ $lv->manager_comment }}</div>
                @endif
            </td>
            <td>
                @if($lv->status === 'pending')
                <button class="btn-danger" onclick="cancelLeave({{ $lv->id }})">Cancelar</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endif

{{-- Modal novo pedido --}}
<div class="overlay" id="leaveOverlay">
<div class="modal">
    <h3>➕ Novo Pedido de Licença</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div class="fg" style="grid-column:1/-1">
            <label>Tipo *</label>
            <select id="lvType">
                <option value="vacation">🌴 Férias</option>
                <option value="sick">🤒 Doença</option>
                <option value="unpaid">💼 Não remunerado</option>
            </select>
        </div>
        <div class="fg">
            <label>Data de início *</label>
            <input type="date" id="lvStart">
        </div>
        <div class="fg">
            <label>Data de fim *</label>
            <input type="date" id="lvEnd">
        </div>
    </div>
    <div class="fg">
        <label>Motivo (opcional)</label>
        <textarea id="lvReason" rows="3" placeholder="Descreva brevemente o motivo do pedido..."></textarea>
    </div>
    <div class="modal-foot">
        <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
        <button class="btn-primary" id="lvSaveBtn" onclick="submitLeave()">Submeter Pedido</button>
    </div>
</div>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

async function apiFetch(method, url, body = null) {
    const opts = {
        method,
        credentials: 'same-origin',
        headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF },
    };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch('/api/v1' + url, opts);
    const json = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(json.message || `Erro ${res.status}`);
    return json;
}

function toast(msg, type = 'ok') {
    const wrap = document.getElementById('toastWrap');
    const el   = document.createElement('div');
    el.className = `toast ${type}`;
    el.textContent = msg;
    wrap.appendChild(el);
    requestAnimationFrame(() => { requestAnimationFrame(() => el.classList.add('show')); });
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 350); }, 3500);
}

function openModal() {
    // default start = today, end = today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('lvStart').value = today;
    document.getElementById('lvEnd').value   = today;
    document.getElementById('lvReason').value = '';
    document.getElementById('lvType').value  = 'vacation';
    document.getElementById('leaveOverlay').classList.add('open');
    setTimeout(() => document.getElementById('lvStart').focus(), 80);
}
function closeModal() {
    document.getElementById('leaveOverlay').classList.remove('open');
}

async function submitLeave() {
    const btn   = document.getElementById('lvSaveBtn');
    const start = document.getElementById('lvStart').value;
    const end   = document.getElementById('lvEnd').value;
    if (!start || !end) { toast('Preencha as datas.', 'err'); return; }
    if (end < start)    { toast('A data de fim não pode ser anterior ao início.', 'err'); return; }

    btn.disabled = true;
    try {
        const res = await apiFetch('POST', '/employee-portal/leaves', {
            leave_type: document.getElementById('lvType').value,
            start_date: start,
            end_date:   end,
            reason:     document.getElementById('lvReason').value.trim() || null,
        });
        toast('✓ Pedido submetido com sucesso!', 'ok');
        closeModal();
        setTimeout(() => location.reload(), 900);
    } catch(e) { toast(e.message || 'Erro ao submeter.', 'err'); }
    finally { btn.disabled = false; }
}

async function cancelLeave(id) {
    if (!confirm('Cancelar este pedido de licença?')) return;
    try {
        await apiFetch('DELETE', `/employee-portal/leaves/${id}`);
        toast('Pedido cancelado.', 'ok');
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) row.remove();
    } catch(e) { toast(e.message || 'Erro ao cancelar.', 'err'); }
}

document.getElementById('leaveOverlay').addEventListener('click', e => {
    if (e.target === document.getElementById('leaveOverlay')) closeModal();
});

// end >= start constraint
document.getElementById('lvStart').addEventListener('change', () => {
    const s = document.getElementById('lvStart').value;
    const e = document.getElementById('lvEnd');
    if (e.value < s) e.value = s;
    e.min = s;
});
</script>
@endsection
