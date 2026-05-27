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
</style>
@endsection

@section('content')

<div class="profile-grid">

    {{-- ── Cartão de perfil lateral ── --}}
    <div class="card" style="align-self:start">
        <p class="card-title">O Meu Perfil</p>

        <div class="profile-avatar">
            @if($employee && $employee->profile_photo)
                <img src="data:image/jpeg;base64,{{ $employee->profile_photo }}" alt="Foto">
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
            @endphp
            <a href="{{ route('employee.training', $training) }}" class="training-card">
                <div class="training-thumb {{ $hasVideo ? 'has-video' : '' }}"
                     style="{{ $hasVideo ? '' : 'background:linear-gradient(135deg,#1e293b,#334155)' }}">
                    @if(!$hasVideo) 🎓 @endif
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

@endsection
