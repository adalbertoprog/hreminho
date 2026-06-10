@extends('layouts.app')

@section('title', 'Pedidos de Licença')
@section('page-title', 'Pedidos de Licença')

@section('styles')
<style>
.lv-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.lv-table th { padding:10px 14px; text-align:left; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--text-muted); border-bottom:1px solid var(--border); }
.lv-table td { padding:10px 14px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
.lv-table tr:last-child td { border-bottom:none; }
.lv-table tr:hover td { background:rgba(255,255,255,.02); }

.badge { display:inline-block; padding:3px 10px; border-radius:6px; font-size:.72rem; font-weight:700; }
.badge-vacation { background:rgba(8,145,178,.15); color:#0891b2; }
.badge-sick     { background:rgba(217,119,6,.15);  color:#d97706; }
.badge-unpaid   { background:rgba(124,58,237,.15); color:#7c3aed; }
.badge-pending  { background:rgba(245,158,11,.15); color:#f59e0b; }
.badge-approved { background:rgba(34,197,94,.15);  color:#22c55e; }
.badge-rejected { background:rgba(239,68,68,.15);  color:#ef4444; }

.btn-approve { padding:5px 12px; border-radius:6px; background:rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.25); color:#22c55e; cursor:pointer; font-size:.76rem; font-weight:600; transition:.15s; }
.btn-approve:hover { background:rgba(34,197,94,.25); }
.btn-reject  { padding:5px 12px; border-radius:6px; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:#ef4444; cursor:pointer; font-size:.76rem; font-weight:600; transition:.15s; }
.btn-reject:hover  { background:rgba(239,68,68,.22); }

.section-title { font-size:1rem; font-weight:700; margin:28px 0 14px; }

/* Modal */
.overlay { display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.6); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:14px; }
.overlay.open { display:flex; }
.modal { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:28px; width:100%; max-width:440px; box-shadow:0 24px 80px rgba(0,0,0,.5); }
.modal h3 { font-size:1rem; font-weight:700; margin:0 0 16px; }
.modal .info-row { display:flex; justify-content:space-between; padding:5px 0; border-bottom:1px solid rgba(255,255,255,.05); font-size:.85rem; }
.modal .info-row .lbl { color:var(--text-muted); }
.modal .info-row .val { font-weight:500; }
.modal textarea { width:100%; background:var(--bg-input,var(--bg-sidebar)); border:1px solid var(--border); color:var(--text-primary); padding:9px 12px; border-radius:8px; font-size:.875rem; outline:none; transition:border-color .15s; font-family:inherit; resize:vertical; margin-top:14px; }
.modal textarea:focus { border-color:var(--accent); }
.modal-foot { display:flex; justify-content:flex-end; gap:10px; margin-top:16px; }
.btn-cancel { padding:8px 18px; border-radius:8px; background:rgba(255,255,255,.06); border:1px solid var(--border); color:var(--text-muted); cursor:pointer; font-size:.875rem; font-weight:600; }
.btn-primary { padding:8px 20px; border-radius:8px; background:var(--accent); border:none; color:#fff; cursor:pointer; font-size:.875rem; font-weight:600; transition:background .15s; }
.btn-primary:hover { background:var(--accent-light); }
.btn-primary:disabled { opacity:.5; cursor:not-allowed; }

/* Toast */
.toast-wrap { position:fixed; bottom:24px; right:24px; display:flex; flex-direction:column; gap:8px; z-index:9999; pointer-events:none; }
.toast { padding:10px 18px; border-radius:10px; font-size:.86rem; font-weight:600; opacity:0; transform:translateY(8px); transition:.3s; pointer-events:none; max-width:320px; }
.toast.show { opacity:1; transform:none; }
.toast.ok  { background:#166534; color:#dcfce7; border:1px solid #22c55e44; }
.toast.err { background:#7f1d1d; color:#fee2e2; border:1px solid #ef444444; }
</style>
@endsection

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:12px">
    <h1 style="font-size:1.2rem;font-weight:700;margin:0">📋 Pedidos de Licença</h1>
    <span style="font-size:.82rem;color:var(--text-muted)">
        {{ $pending->count() }} pendente(s)
    </span>
</div>

{{-- Pedidos pendentes --}}
<p class="section-title">⏳ Pendentes</p>

@if($pending->isEmpty())
<div style="padding:32px 20px;text-align:center;color:var(--text-muted);background:var(--bg-card);border:1px solid var(--border);border-radius:12px">
    <div style="font-size:2rem;margin-bottom:8px">✅</div>
    <p>Não há pedidos pendentes de aprovação.</p>
</div>
@else
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden">
<table class="lv-table" id="pendingTable">
    <thead>
        <tr>
            <th>Funcionário</th>
            <th>Tipo</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Dias</th>
            <th>Motivo</th>
            <th>Acções</th>
        </tr>
    </thead>
    <tbody>
        @php $typeLabels = ['vacation'=>'Férias','sick'=>'Doença','unpaid'=>'Não rem.']; @endphp
        @foreach($pending as $lv)
        <tr id="row-{{ $lv->id }}">
            <td style="font-weight:500">{{ $lv->employee?->full_name ?? '—' }}</td>
            <td><span class="badge badge-{{ $lv->leave_type }}">{{ $typeLabels[$lv->leave_type] ?? $lv->leave_type }}</span></td>
            <td>{{ $lv->start_date?->format('d/m/Y') }}</td>
            <td>{{ $lv->end_date?->format('d/m/Y') }}</td>
            <td style="color:var(--text-muted)">
                {{ $lv->start_date && $lv->end_date ? $lv->start_date->diffInDays($lv->end_date) + 1 : '—' }}
            </td>
            <td style="color:var(--text-muted);max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $lv->reason ?: '—' }}</td>
            <td style="white-space:nowrap">
                <button class="btn-approve" onclick="openDecision({{ $lv->id }},'approve','{{ addslashes($lv->employee?->full_name) }}','{{ $lv->start_date?->format('d/m/Y') }}','{{ $lv->end_date?->format('d/m/Y') }}')">✓ Aprovar</button>
                <button class="btn-reject"  onclick="openDecision({{ $lv->id }},'reject', '{{ addslashes($lv->employee?->full_name) }}','{{ $lv->start_date?->format('d/m/Y') }}','{{ $lv->end_date?->format('d/m/Y') }}')">✗ Rejeitar</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endif

{{-- Histórico recente --}}
<p class="section-title">🕐 Histórico Recente</p>

@if($recent->isEmpty())
<p style="color:var(--text-muted);font-size:.88rem">Sem histórico de decisões.</p>
@else
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden">
<table class="lv-table">
    <thead>
        <tr>
            <th>Funcionário</th>
            <th>Tipo</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Estado</th>
            <th>Comentário</th>
        </tr>
    </thead>
    <tbody>
        @php $statusLabels = ['approved'=>'Aprovado','rejected'=>'Rejeitado']; @endphp
        @foreach($recent as $lv)
        <tr>
            <td style="font-weight:500">{{ $lv->employee?->full_name ?? '—' }}</td>
            <td><span class="badge badge-{{ $lv->leave_type }}">{{ $typeLabels[$lv->leave_type] ?? $lv->leave_type }}</span></td>
            <td>{{ $lv->start_date?->format('d/m/Y') }}</td>
            <td>{{ $lv->end_date?->format('d/m/Y') }}</td>
            <td><span class="badge badge-{{ $lv->status }}">{{ $statusLabels[$lv->status] ?? $lv->status }}</span></td>
            <td style="color:var(--text-muted);font-size:.8rem;font-style:italic">{{ $lv->manager_comment ?: '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endif

{{-- Modal decisão --}}
<div class="overlay" id="decisionOverlay">
<div class="modal">
    <h3 id="decisionTitle">Decisão sobre pedido</h3>
    <div style="margin-bottom:14px">
        <div class="info-row"><span class="lbl">Funcionário</span><span class="val" id="dEmp"></span></div>
        <div class="info-row"><span class="lbl">Período</span><span class="val" id="dPeriod"></span></div>
    </div>
    <textarea id="dComment" rows="3" placeholder="Comentário (opcional)..."></textarea>
    <div class="modal-foot">
        <button class="btn-cancel" onclick="closeDecision()">Cancelar</button>
        <button class="btn-primary" id="dConfirmBtn" onclick="confirmDecision()">Confirmar</button>
    </div>
</div>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
let decisionId = null, decisionAction = null;

async function apiFetch(method, url, body = null) {
    const opts = { method, credentials:'same-origin', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF} };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch('/api/v1' + url, opts);
    const json = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(json.message || `Erro ${res.status}`);
    return json;
}
function toast(msg, type = 'ok') {
    const wrap = document.getElementById('toastWrap');
    const el   = document.createElement('div');
    el.className = `toast ${type}`; el.textContent = msg; wrap.appendChild(el);
    requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('show')));
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 350); }, 3500);
}

function openDecision(id, action, emp, start, end) {
    decisionId = id; decisionAction = action;
    document.getElementById('decisionTitle').textContent = action === 'approve' ? '✓ Aprovar Pedido' : '✗ Rejeitar Pedido';
    document.getElementById('dConfirmBtn').textContent = action === 'approve' ? 'Aprovar' : 'Rejeitar';
    document.getElementById('dConfirmBtn').style.background = action === 'approve' ? '#16a34a' : '#dc2626';
    document.getElementById('dEmp').textContent    = emp;
    document.getElementById('dPeriod').textContent = `${start} → ${end}`;
    document.getElementById('dComment').value      = '';
    document.getElementById('decisionOverlay').classList.add('open');
    setTimeout(() => document.getElementById('dComment').focus(), 80);
}
function closeDecision() { document.getElementById('decisionOverlay').classList.remove('open'); }

async function confirmDecision() {
    const btn = document.getElementById('dConfirmBtn');
    btn.disabled = true;
    try {
        await apiFetch('PUT', `/employee-portal/leaves/${decisionId}/${decisionAction}`, {
            manager_comment: document.getElementById('dComment').value.trim() || null,
        });
        toast(decisionAction === 'approve' ? '✓ Pedido aprovado!' : '✗ Pedido rejeitado.', 'ok');
        closeDecision();
        // Remove row from pending table
        const row = document.getElementById(`row-${decisionId}`);
        if (row) {
            row.style.opacity = '0'; row.style.transition = '.3s';
            setTimeout(() => row.remove(), 350);
        }
    } catch(e) { toast(e.message || 'Erro ao processar.', 'err'); }
    finally { btn.disabled = false; }
}

document.getElementById('decisionOverlay').addEventListener('click', e => {
    if (e.target === document.getElementById('decisionOverlay')) closeDecision();
});
</script>
@endsection
