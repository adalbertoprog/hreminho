@extends('layouts.app')

@section('title', 'O Meu Espaço')
@section('page-title', 'O Meu Espaço')

@section('styles')
<style>
/* ── Grid de perfil ── */
.profile-grid {
    display:grid; grid-template-columns:1fr 2fr; gap:24px; margin-bottom:32px;
}
@media(max-width:760px){ .profile-grid{ grid-template-columns:1fr; } }

.card {
    background:var(--bg-card); border:1px solid var(--border);
    border-radius:14px; padding:24px;
}
.card-title {
    font-size:.75rem; font-weight:700; text-transform:uppercase;
    letter-spacing:1px; color:var(--text-muted); margin-bottom:18px;
}

/* Perfil lateral */
.profile-avatar {
    width:80px; height:80px; border-radius:50%;
    background:linear-gradient(135deg,var(--accent),#a78bfa);
    display:flex; align-items:center; justify-content:center;
    font-size:2rem; font-weight:700; color:#fff; margin:0 auto 14px;
    overflow:hidden;
}
.profile-avatar img { width:100%; height:100%; object-fit:cover; }
.profile-name { text-align:center; font-size:1.05rem; font-weight:700; }
.profile-role { text-align:center; font-size:.82rem; color:var(--text-muted); margin-top:4px; }
.profile-badge {
    display:inline-flex; align-items:center; gap:5px;
    background:rgba(99,102,241,.15); color:var(--accent-light);
    padding:3px 12px; border-radius:20px; font-size:.72rem; font-weight:600;
    margin:10px auto 0; display:block; width:fit-content;
}
.divider { border:none; border-top:1px solid var(--border); margin:16px 0; }
.info-row { display:flex; justify-content:space-between; align-items:center; padding:6px 0; }
.info-row .label { font-size:.78rem; color:var(--text-muted); }
.info-row .value { font-size:.83rem; font-weight:500; text-align:right; max-width:60%; }

/* Detalhes */
.details-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
@media(max-width:600px){ .details-grid{ grid-template-columns:1fr; } }
.detail-item { }
.detail-item .label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--text-muted); margin-bottom:4px; }
.detail-item .value { font-size:.9rem; color:var(--text-primary); }

/* Formações */
.section-title { font-size:1.05rem; font-weight:700; margin-bottom:18px; }
.training-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:18px;
}
.training-card {
    background:var(--bg-card); border:1px solid var(--border);
    border-radius:12px; overflow:hidden; transition:transform .15s,box-shadow .15s;
    text-decoration:none; color:inherit; display:flex; flex-direction:column;
}
.training-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
.training-card.is-done { border-color:rgba(34,197,94,.35); }
.training-thumb {
    height:120px;
    background:linear-gradient(135deg,#6366f1 0%,#a78bfa 100%);
    display:flex; align-items:center; justify-content:center;
    font-size:2.5rem; position:relative;
}
.training-thumb.has-video::after {
    content:'▶';
    position:absolute; font-size:1.8rem; color:rgba(255,255,255,.9);
    background:rgba(0,0,0,.35); width:52px; height:52px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    padding-left:4px;
}
/* Etiqueta de estado no canto superior direito da thumbnail */
.status-pill {
    position:absolute; top:10px; right:10px;
    padding:3px 10px; border-radius:20px; font-size:.68rem; font-weight:700;
    letter-spacing:.3px; backdrop-filter:blur(4px);
}
.status-pill.done    { background:rgba(34,197,94,.85);  color:#fff; }
.status-pill.failed  { background:rgba(239,68,68,.85);  color:#fff; }
.status-pill.pending { background:rgba(245,158,11,.85); color:#fff; }
.status-pill.new     { background:rgba(99,102,241,.85); color:#fff; }

.training-body { padding:16px; flex:1; display:flex; flex-direction:column; gap:8px; }
.training-title { font-size:.9rem; font-weight:600; }
.training-provider { font-size:.78rem; color:var(--text-muted); }
.training-meta { display:flex; align-items:center; gap:8px; margin-top:auto; flex-wrap:wrap; }
.badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 10px; border-radius:20px; font-size:.7rem; font-weight:600;
}
.badge-passed   { background:rgba(34,197,94,.15);  color:var(--success); }
.badge-failed   { background:rgba(239,68,68,.15);  color:var(--danger); }
.badge-pending  { background:rgba(245,158,11,.15); color:var(--warning); }
.badge-no-quiz  { background:rgba(99,102,241,.1);  color:var(--accent-light); }
.badge-video    { background:rgba(99,102,241,.1);  color:var(--accent-light); }

/* Empty */
.empty-state { padding:48px 20px; text-align:center; color:var(--text-muted); }
.empty-state .icon { font-size:2.5rem; margin-bottom:12px; }

/* ── Banner de associação ── */
.assoc-banner {
    background:linear-gradient(135deg, rgba(99,102,241,.12), rgba(167,139,250,.08));
    border:1px solid rgba(99,102,241,.35);
    border-radius:14px; padding:24px 28px; margin-bottom:28px;
    display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;
}
.assoc-banner .assoc-icon { font-size:2.2rem; flex-shrink:0; margin-top:2px; }
.assoc-banner .assoc-content { flex:1; min-width:260px; }
.assoc-banner .assoc-title {
    font-size:1rem; font-weight:700; color:var(--text-primary); margin-bottom:4px;
}
.assoc-banner .assoc-sub {
    font-size:.85rem; color:var(--text-muted); margin-bottom:14px; line-height:1.5;
}
.assoc-form { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
.assoc-input {
    background:rgba(255,255,255,.07); border:1px solid var(--border);
    color:var(--text-primary); padding:9px 14px; border-radius:8px;
    font-size:.9rem; outline:none; transition:border-color .15s;
    font-family:inherit; letter-spacing:.05em; width:170px; text-transform:uppercase;
}
.assoc-input:focus { border-color:var(--accent); }
.assoc-input::placeholder { text-transform:none; letter-spacing:0; color:var(--text-muted); }
.assoc-btn {
    background:var(--accent); color:#fff; border:none; padding:9px 20px;
    border-radius:8px; font-size:.88rem; font-weight:600; cursor:pointer;
    transition:background .15s; white-space:nowrap;
}
.assoc-btn:hover { background:var(--accent-light); }
.assoc-btn:disabled { opacity:.5; cursor:not-allowed; }
.assoc-msg { font-size:.82rem; margin-top:10px; padding:7px 12px; border-radius:7px; display:none; }
.assoc-msg.error   { background:rgba(239,68,68,.12); color:var(--danger);  border:1px solid rgba(239,68,68,.25); }
.assoc-msg.success { background:rgba(34,197,94,.12);  color:var(--success); border:1px solid rgba(34,197,94,.25); }
</style>
@endsection

@section('content')

{{-- ── Banner de associação (só aparece se não houver funcionário ligado) ── --}}
@if(! $employee)
<div class="assoc-banner" id="assocBanner">
    <div class="assoc-icon">🔗</div>
    <div class="assoc-content">
        <p class="assoc-title">Ligue a sua conta ao seu registo de funcionário</p>
        <p class="assoc-sub">
            Para aceder ao seu perfil e formações, introduza o seu <strong>código de funcionário</strong>
            (ex: <code style="background:rgba(255,255,255,.08);padding:1px 6px;border-radius:4px">FUN0590</code>).
            Encontra este código no seu contrato ou pode solicitá-lo ao departamento de RH.
        </p>
        <div class="assoc-form">
            <input id="assocCode" class="assoc-input" type="text" placeholder="Ex: FUN0590"
                   maxlength="20" oninput="this.value = this.value.toUpperCase()">
            <button class="assoc-btn" id="assocBtn" onclick="submitAssociation()">
                Associar conta
            </button>
        </div>
        <div class="assoc-msg" id="assocMsg"></div>
    </div>
</div>
@endif

<div class="profile-grid">

    {{-- ── Cartão de perfil lateral ── --}}
    <div class="card" style="align-self:start">
        <p class="card-title">O Meu Perfil</p>

        <div class="profile-avatar">
            @if($employee && $employee->profile_photo_url)
                <img src="{{ $employee->profile_photo_url }}" alt="Foto">
            @else
                {{ strtoupper(substr($user->name, 0, 2)) }}
            @endif
        </div>
        <p class="profile-name">{{ $user->name }}</p>
        <p class="profile-role">
            @if($employee) {{ $employee->position->name ?? '—' }} @else — @endif
        </p>
        <span class="profile-badge">Funcionário</span>

        <hr class="divider">

        <div class="info-row">
            <span class="label">E-mail</span>
            <span class="value">{{ $user->email }}</span>
        </div>
        @if($employee)
        <div class="info-row">
            <span class="label">Código</span>
            <span class="value">{{ $employee->code }}</span>
        </div>
        <div class="info-row">
            <span class="label">Departamento</span>
            <span class="value">{{ $employee->department->department ?? '—' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Setor</span>
            <span class="value">{{ $employee->sector->sector ?? '—' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Admissão</span>
            <span class="value">{{ $employee->hire_date?->format('d/m/Y') ?? '—' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Estado</span>
            <span class="value">{{ ucfirst($employee->status ?? '—') }}</span>
        </div>
        @endif
    </div>

    {{-- ── Dados detalhados ── --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Informações pessoais --}}
        <div class="card">
            <p class="card-title">Informações Pessoais</p>
            @if($employee)
            <div class="details-grid">
                <div class="detail-item">
                    <div class="label">Nome completo</div>
                    <div class="value">{{ $employee->full_name }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Género</div>
                    <div class="value">{{ ucfirst($employee->gender ?? '—') }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Data de nascimento</div>
                    <div class="value">{{ $employee->date_of_birth?->format('d/m/Y') ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Nacionalidade</div>
                    <div class="value">{{ $employee->nationality ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Telefone</div>
                    <div class="value">{{ $employee->phone ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Localização</div>
                    <div class="value">{{ $employee->work_location ?? '—' }}</div>
                </div>
                <div class="detail-item" style="grid-column:1/-1">
                    <div class="label">Morada</div>
                    <div class="value">{{ $employee->address ?? '—' }}</div>
                </div>
            </div>
            @else
            <p style="color:var(--text-muted);font-size:.9rem">
                Nenhum registo de funcionário associado ao e-mail <strong>{{ $user->email }}</strong>.<br>
                Contacte o departamento de RH para ligar a sua conta.
            </p>
            @endif
        </div>

        {{-- Contrato --}}
        @if($employee)
        <div class="card">
            <p class="card-title">Contrato</p>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="label">Tipo de contrato</div>
                    <div class="value">{{ ucfirst($employee->contract_type ?? '—') }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Data de término</div>
                    <div class="value">{{ $employee->end_date?->format('d/m/Y') ?? 'Indeterminado' }}</div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ── Formações disponíveis ── --}}
<div id="formacoes">
    <p class="section-title">🎓 Formações Disponíveis</p>

    @if($trainings->isEmpty())
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>Ainda não existem formações disponíveis.</p>
        </div>
    @else
        <div class="training-grid">
            @foreach($trainings as $training)
            @php
                $hasVideo = (bool) $training->has_video;
                $hasQuiz  = (bool) $training->has_quiz;
                $attempt  = $quizStatuses[$training->id] ?? null;

                // Determinar estado geral do card
                if ($hasQuiz) {
                    if ($attempt?->passed)              { $statusClass = 'done';    $statusLabel = '✓ Concluído'; }
                    elseif ($attempt && !$attempt->passed) { $statusClass = 'failed';  $statusLabel = '✗ Reprovado'; }
                    else                                { $statusClass = 'pending'; $statusLabel = 'Por fazer'; }
                } elseif ($hasVideo) {
                    // Só vídeo, sem quiz — não há forma de saber se viu; mostrar "Disponível"
                    $statusClass = 'new'; $statusLabel = 'Disponível';
                } else {
                    $statusClass = 'new'; $statusLabel = 'Disponível';
                }
            @endphp
            <a href="{{ route('employee.training', $training) }}" class="training-card {{ $statusClass === 'done' ? 'is-done' : '' }}">
                <div class="training-thumb {{ $hasVideo ? 'has-video' : '' }}"
                     style="{{ $hasVideo ? '' : 'background:linear-gradient(135deg,#1e293b,#334155)' }}">
                    @if(!$hasVideo) 🎓 @endif
                    <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
                <div class="training-body">
                    <p class="training-title">{{ $training->title }}</p>
                    @if($training->provider)
                        <p class="training-provider">{{ $training->provider }}</p>
                    @endif
                    <div class="training-meta">
                        @if($hasVideo)
                            <span class="badge badge-video">▶ Vídeo</span>
                        @endif
                        @if($hasQuiz)
                            @if($attempt)
                                @if($attempt->passed)
                                    <span class="badge badge-passed">✓ Aprovado {{ $attempt->score }}%</span>
                                @else
                                    <span class="badge badge-failed">✗ {{ $attempt->score }}% — Repetir</span>
                                @endif
                            @else
                                <span class="badge badge-pending">📝 Questionário</span>
                            @endif
                        @else
                            <span class="badge badge-no-quiz">Sem questionário</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>


{{-- ── Presenças do mês ── --}}
@if($employee)
<div style="margin-top:32px">
    <p class="section-title">🕐 Presenças — {{ $attendanceSummary['month'] ?? '' }}</p>

    {{-- Resumo --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:14px;margin-bottom:20px">
        @php
            $summaryItems = [
                ['label'=>'Presentes','value'=>$attendanceSummary['present']??0,'color'=>'#22c55e'],
                ['label'=>'Atrasados','value'=>$attendanceSummary['late']??0,   'color'=>'#f59e0b'],
                ['label'=>'Ausentes', 'value'=>$attendanceSummary['absent']??0, 'color'=>'#ef4444'],
                ['label'=>'Licença',  'value'=>$attendanceSummary['on_leave']??0,'color'=>'#0891b2'],
            ];
        @endphp
        @foreach($summaryItems as $s)
        <div class="card" style="text-align:center;padding:16px 12px">
            <div style="font-size:1.6rem;font-weight:800;color:{{ $s['color'] }}">{{ $s['value'] }}</div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:4px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Lista últimas presenças --}}
    @if($recentAttendances->isEmpty())
        <p style="color:var(--text-muted);font-size:.88rem">Sem registos de presença.</p>
    @else
    <div class="card" style="padding:0;overflow:hidden">
        <table style="width:100%;border-collapse:collapse;font-size:.84rem">
            <thead>
                <tr style="background:rgba(255,255,255,.03)">
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Data</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Estado</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Entrada</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Saída</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusColors = ['present'=>'#22c55e','late'=>'#f59e0b','absent'=>'#ef4444','on_leave'=>'#0891b2','holiday'=>'#a78bfa'];
                    $statusLabels = ['present'=>'Presente','late'=>'Atrasado','absent'=>'Ausente','on_leave'=>'Licença','holiday'=>'Feriado'];
                @endphp
                @foreach($recentAttendances as $att)
                <tr style="border-top:1px solid var(--border)">
                    <td style="padding:10px 16px;font-weight:500">{{ $att->date?->format('d/m/Y') }}</td>
                    <td style="padding:10px 16px">
                        <span style="display:inline-block;padding:2px 10px;border-radius:6px;font-size:.74rem;font-weight:700;background:{{ $statusColors[$att->status]??'#6366f1' }}22;color:{{ $statusColors[$att->status]??'#818cf8' }}">
                            {{ $statusLabels[$att->status] ?? $att->status }}
                        </span>
                    </td>
                    <td style="padding:10px 16px;color:var(--text-muted)">{{ $att->check_in ? substr($att->check_in,0,5) : '—' }}</td>
                    <td style="padding:10px 16px;color:var(--text-muted)">{{ $att->check_out ? substr($att->check_out,0,5) : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- ── Licenças ── --}}
<div style="margin-top:32px">
    <p class="section-title">🏖️ Licenças e Férias</p>
    @if($leaves->isEmpty())
        <p style="color:var(--text-muted);font-size:.88rem">Sem pedidos de licença registados.</p>
    @else
    <div class="card" style="padding:0;overflow:hidden">
        <table style="width:100%;border-collapse:collapse;font-size:.84rem">
            <thead>
                <tr style="background:rgba(255,255,255,.03)">
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Tipo</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Início</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Fim</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted)">Estado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $leaveTypeLabels   = ['vacation'=>'Férias','sick'=>'Doença','unpaid'=>'Não rem.'];
                    $leaveTypeColors   = ['vacation'=>'#0891b2','sick'=>'#d97706','unpaid'=>'#7c3aed'];
                    $leaveStatusLabels = ['pending'=>'Pendente','approved'=>'Aprovado','rejected'=>'Rejeitado'];
                    $leaveStatusColors = ['pending'=>'#f59e0b','approved'=>'#22c55e','rejected'=>'#ef4444'];
                @endphp
                @foreach($leaves as $leave)
                <tr style="border-top:1px solid var(--border)">
                    <td style="padding:10px 16px">
                        <span style="display:inline-block;padding:2px 10px;border-radius:6px;font-size:.74rem;font-weight:700;background:{{ $leaveTypeColors[$leave->leave_type]??'#6366f1' }}22;color:{{ $leaveTypeColors[$leave->leave_type]??'#818cf8' }}">
                            {{ $leaveTypeLabels[$leave->leave_type] ?? $leave->leave_type }}
                        </span>
                    </td>
                    <td style="padding:10px 16px;color:var(--text-muted)">{{ $leave->start_date?->format('d/m/Y') }}</td>
                    <td style="padding:10px 16px;color:var(--text-muted)">{{ $leave->end_date?->format('d/m/Y') }}</td>
                    <td style="padding:10px 16px">
                        <span style="display:inline-block;padding:2px 10px;border-radius:6px;font-size:.74rem;font-weight:700;background:{{ $leaveStatusColors[$leave->status]??'#6366f1' }}22;color:{{ $leaveStatusColors[$leave->status]??'#818cf8' }}">
                            {{ $leaveStatusLabels[$leave->status] ?? $leave->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif


@if(! $employee)
<script>
async function submitAssociation() {
    const input = document.getElementById('assocCode');
    const btn   = document.getElementById('assocBtn');
    const msg   = document.getElementById('assocMsg');
    const code  = input.value.trim();

    if (! code) {
        showMsg('Por favor introduza o código de funcionário.', 'error');
        input.focus();
        return;
    }

    btn.disabled = true;
    btn.textContent = 'A verificar…';
    msg.style.display = 'none';

    try {
        const res = await fetch('/api/v1/employee-portal/associate', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ code }),
        });

        const data = await res.json();

        if (res.ok) {
            showMsg('✓ ' + data.message, 'success');
            // Reload após breve pausa para mostrar a mensagem de sucesso
            setTimeout(() => window.location.reload(), 1400);
        } else {
            showMsg(data.message || 'Erro ao associar. Tente novamente.', 'error');
            btn.disabled = false;
            btn.textContent = 'Associar conta';
        }
    } catch (e) {
        showMsg('Erro de rede. Verifique a ligação e tente novamente.', 'error');
        btn.disabled = false;
        btn.textContent = 'Associar conta';
    }
}

function showMsg(text, type) {
    const msg = document.getElementById('assocMsg');
    msg.textContent = text;
    msg.className = 'assoc-msg ' + type;
    msg.style.display = 'block';
}

// Submeter ao pressionar Enter
document.getElementById('assocCode').addEventListener('keydown', e => {
    if (e.key === 'Enter') submitAssociation();
});
</script>
@endif

@endsection
