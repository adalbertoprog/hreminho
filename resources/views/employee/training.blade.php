@extends('layouts.app')

@section('title', $training->title)
@section('page-title', $training->title)

@section('styles')
<style>
.back-link {
    display:inline-flex; align-items:center; gap:7px;
    color:var(--text-muted); font-size:.85rem; text-decoration:none;
    margin-bottom:22px; transition:color .15s;
}
.back-link:hover { color:var(--accent-light); }

.training-layout { display:grid; grid-template-columns:1fr 380px; gap:24px; }
@media(max-width:900px){ .training-layout{ grid-template-columns:1fr; } }

/* ── Vídeo ── */
.video-card {
    background:var(--bg-card); border:1px solid var(--border);
    border-radius:14px; overflow:hidden; margin-bottom:20px;
}
.video-header { padding:16px 20px 0; }
.video-title { font-size:1rem; font-weight:700; margin-bottom:4px; }
.video-desc  { font-size:.83rem; color:var(--text-muted); padding:0 20px 16px; }
.video-wrapper {
    position:relative; padding-bottom:56.25%;
    background:#000; width:100%;
}
.video-wrapper iframe,
.video-wrapper video {
    position:absolute; inset:0; width:100%; height:100%; border:none;
}
.video-tabs {
    display:flex; gap:2px; padding:10px 12px 0; border-bottom:1px solid var(--border);
    overflow-x:auto;
}
.video-tab {
    padding:8px 14px; border-radius:8px 8px 0 0; font-size:.8rem; font-weight:500;
    cursor:pointer; border:none; background:none; color:var(--text-muted);
    transition:all .15s; white-space:nowrap;
}
.video-tab.active { background:var(--nav-active); color:var(--accent-light); }
.video-tab:hover:not(.active) { background:var(--nav-hover); color:var(--text-primary); }

/* No video */
.no-video {
    padding:48px 20px; text-align:center; color:var(--text-muted);
}
.no-video .icon { font-size:2.5rem; margin-bottom:12px; }

/* ── Sidebar info + histórico ── */
.info-card {
    background:var(--bg-card); border:1px solid var(--border);
    border-radius:14px; padding:20px; margin-bottom:18px;
}
.card-title {
    font-size:.72rem; font-weight:700; text-transform:uppercase;
    letter-spacing:1px; color:var(--text-muted); margin-bottom:14px;
}
.info-row { display:flex; justify-content:space-between; padding:6px 0; }
.info-row .label { font-size:.78rem; color:var(--text-muted); }
.info-row .value { font-size:.82rem; font-weight:500; }

.attempt-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:8px 0; border-bottom:1px solid var(--border);
    font-size:.82rem;
}
.attempt-item:last-child { border-bottom:none; }
.badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:2px 9px; border-radius:20px; font-size:.7rem; font-weight:600;
}
.badge-passed { background:rgba(34,197,94,.15); color:var(--success); }
.badge-failed { background:rgba(239,68,68,.15); color:var(--danger); }

/* ── Quiz ── */
.quiz-card {
    background:var(--bg-card); border:1px solid var(--border);
    border-radius:14px; overflow:hidden;
}
.quiz-header {
    padding:20px 24px 16px; border-bottom:1px solid var(--border);
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
}
.quiz-header h2 { font-size:1rem; font-weight:700; }
.passing-badge {
    background:rgba(245,158,11,.12); color:var(--warning);
    padding:4px 12px; border-radius:20px; font-size:.75rem; font-weight:600;
}
.quiz-body { padding:24px; }
.question-block { margin-bottom:28px; }
.question-text {
    font-size:.9rem; font-weight:600; margin-bottom:12px;
    display:flex; gap:10px;
}
.question-num {
    flex-shrink:0; width:24px; height:24px; border-radius:50%;
    background:var(--accent); color:#fff; font-size:.72rem; font-weight:700;
    display:flex; align-items:center; justify-content:center;
}
.options-list { display:flex; flex-direction:column; gap:8px; padding-left:34px; }
.option-label {
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; border-radius:8px; cursor:pointer;
    border:1px solid var(--border); transition:all .15s;
    font-size:.875rem;
}
.option-label:hover { border-color:var(--accent); background:var(--accent-glow); }
.option-label input[type=radio] { accent-color:var(--accent); width:16px; height:16px; flex-shrink:0; }
.option-label.selected { border-color:var(--accent); background:var(--accent-glow); }

/* Result states */
.option-label.correct  { border-color:var(--success); background:rgba(34,197,94,.1); }
.option-label.wrong    { border-color:var(--danger);  background:rgba(239,68,68,.1); }
.option-label.correct-hint { border-color:var(--success); opacity:.7; }

.quiz-submit {
    margin-top:8px; padding-left:34px;
}
.btn-submit {
    padding:11px 28px; border-radius:9px; border:none;
    background:var(--accent); color:#fff; font-size:.9rem; font-weight:600;
    cursor:pointer; transition:opacity .15s;
}
.btn-submit:hover { opacity:.85; }
.btn-submit:disabled { opacity:.5; cursor:not-allowed; }

/* Result banner */
.result-banner {
    border-radius:12px; padding:20px 24px; margin-bottom:24px;
    display:flex; align-items:center; gap:16px;
}
.result-banner.passed { background:rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.3); }
.result-banner.failed { background:rgba(239,68,68,.1);  border:1px solid rgba(239,68,68,.25); }
.result-icon { font-size:2rem; }
.result-text h3 { font-size:1rem; font-weight:700; margin-bottom:4px; }
.result-text p  { font-size:.85rem; color:var(--text-muted); }
.btn-retry {
    margin-left:auto; padding:8px 20px; border-radius:8px;
    background:var(--bg-card); border:1px solid var(--border);
    color:var(--text-primary); font-size:.85rem; font-weight:500; cursor:pointer;
    transition:all .15s;
}
.btn-retry:hover { border-color:var(--accent); color:var(--accent-light); }

/* No quiz */
.no-quiz {
    padding:40px 24px; text-align:center; color:var(--text-muted);
}
.no-quiz .icon { font-size:2.2rem; margin-bottom:10px; }

/* Toast */
.toast-wrap { position:fixed; bottom:24px; right:24px; z-index:999; display:flex; flex-direction:column; gap:8px; }
.toast {
    padding:12px 18px; border-radius:10px; font-size:.85rem; font-weight:500;
    color:#fff; max-width:320px; animation:slideIn .25s ease;
}
.toast.success { background:#16a34a; }
.toast.error   { background:#dc2626; }
@keyframes slideIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

/* ── Quiz lock ── */
.quiz-lock {
    background:var(--bg-card);
    border:2px dashed var(--border);
    border-radius:16px;
    padding:40px 24px;
    text-align:center;
    margin-bottom:20px;
}
.quiz-lock .lock-icon { font-size:2.8rem; margin-bottom:12px; }
.quiz-lock .lock-title { font-size:1.05rem; font-weight:700; margin:0 0 6px; }
.quiz-lock .lock-desc { font-size:.875rem; color:var(--text-muted); margin:0 0 16px; }
.lock-progress-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px; }
.lock-progress-list li {
    display:flex; align-items:center; gap:10px;
    font-size:.83rem; padding:8px 14px;
    background:rgba(255,255,255,.04); border-radius:8px;
}
.lock-progress-list li .check { font-size:1rem; width:20px; text-align:center; }
.lock-progress-list li.done { color:var(--success); }
.lock-progress-list li.done .check::before { content:'✓'; }
.lock-progress-list li:not(.done) .check::before { content:'○'; color:var(--text-muted); }
</style>
@endsection

@section('content')

<a href="{{ route('employee.dashboard') }}#formacoes" class="back-link">
    ← Voltar às formações
</a>

<div class="training-layout">

    {{-- ── Coluna principal: vídeo + questionário ── --}}
    <div>

        {{-- Vídeo --}}
        @if($training->has_video)
        @if($training->videos->isNotEmpty())
        <div class="video-card">
            @if($training->videos->count() > 1)
            <div class="video-tabs" id="videoTabs">
                @foreach($training->videos as $i => $video)
                <button class="video-tab {{ $i === 0 ? 'active' : '' }}"
                        onclick="switchVideo({{ $i }}, this)">
                    {{ $video->title }}
                </button>
                @endforeach
            </div>
            @endif

            @foreach($training->videos as $i => $video)
            <div class="video-panel" id="video-{{ $i }}" style="{{ $i > 0 ? 'display:none' : '' }}">
                <div class="video-header">
                    <p class="video-title">{{ $video->title }}</p>
                </div>
                <div class="video-wrapper">
                    @php
                        $url = $video->url;
                        // Detect YouTube
                        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $m);
                        $ytId = $m[1] ?? null;
                        // Detect Vimeo
                        preg_match('/vimeo\.com\/(\d+)/', $url, $vm);
                        $vimeoId = $vm[1] ?? null;
                    @endphp
                    @if($ytId)
                        <iframe id="yt-{{ $i }}"
                                src="https://www.youtube.com/embed/{{ $ytId }}?enablejsapi=1"
                                allowfullscreen
                                data-video-idx="{{ $i }}"
                                data-type="youtube"></iframe>
                    @elseif($vimeoId)
                        <iframe id="vm-{{ $i }}"
                                src="https://player.vimeo.com/video/{{ $vimeoId }}"
                                allowfullscreen
                                data-video-idx="{{ $i }}"
                                data-type="vimeo"></iframe>
                    @else
                        <video id="mp-{{ $i }}"
                               controls
                               src="{{ $url }}"
                               data-video-idx="{{ $i }}"
                               data-type="mp4"></video>
                    @endif
                </div>
                @if($video->description)
                <p class="video-desc">{{ $video->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="video-card">
            <div class="no-video">
                <div class="icon">🎬</div>
                <p>Ainda não existe vídeo para esta formação.</p>
            </div>
        </div>
        @endif
        @endif {{-- has_video --}}

        {{-- Questionário --}}
        @if($training->has_quiz)
        @if($training->quiz)
        @php
            $quiz     = $training->quiz;
            $lastPass = $attempts->firstWhere('passed', true);
            $lastAttempt = $attempts->first();
        @endphp
        {{-- Lock overlay shown until all videos are watched --}}
        @if($training->has_video && $training->videos->isNotEmpty())
        <div id="quizLock" class="quiz-lock">
            <div class="lock-icon">🔒</div>
            <p class="lock-title">Questionário bloqueado</p>
            <p class="lock-desc">Assiste ao(s) vídeo(s) até ao fim para desbloquear o questionário.</p>
            <div id="lockProgress"></div>
        </div>
        @endif

        <div class="quiz-card" id="quizCard" @if($training->has_video && $training->videos->isNotEmpty() && $attempts->isEmpty()) style="display:none" @endif>
            <div class="quiz-header">
                <h2>📝 {{ $quiz->title }}</h2>
                <span class="passing-badge">Nota mínima: {{ $quiz->passing_score }}%</span>
            </div>
            <div class="quiz-body">

                {{-- Result banner (hidden initially) --}}
                <div id="resultBanner" style="display:none"></div>

                @if($quiz->description)
                <p style="font-size:.875rem;color:var(--text-muted);margin-bottom:20px">{{ $quiz->description }}</p>
                @endif

                @if($attempts->isNotEmpty())
                {{-- Show last attempt result --}}
                @php $last = $attempts->first(); @endphp
                <div class="result-banner {{ $last->passed ? 'passed' : 'failed' }}" id="lastResultBanner">
                    <span class="result-icon">{{ $last->passed ? '🏆' : '😕' }}</span>
                    <div class="result-text">
                        <h3>{{ $last->passed ? 'Aprovado!' : 'Não aprovado' }} — {{ $last->score }}%</h3>
                        <p>
                            @if($last->passed)
                                Parabéns! Concluíste esta formação com sucesso.
                            @else
                                Precisas de {{ $quiz->passing_score }}% para aprovação. Revê o vídeo e tenta novamente.
                            @endif
                        </p>
                    </div>
                    @if(!$last->passed)
                    <button class="btn-retry" onclick="showQuiz()">Repetir questionário</button>
                    @endif
                </div>
                @endif

                <form id="quizForm" style="{{ $attempts->isNotEmpty() ? 'display:none' : '' }}">
                    @foreach($quiz->questions as $qi => $question)
                    <div class="question-block" data-question-id="{{ $question->id }}">
                        <p class="question-text">
                            <span class="question-num">{{ $qi + 1 }}</span>
                            {{ $question->question }}
                        </p>
                        <div class="options-list">
                            @foreach($question->options as $option)
                            <label class="option-label" data-option-id="{{ $option->id }}">
                                <input type="radio"
                                       name="q_{{ $question->id }}"
                                       value="{{ $option->id }}"
                                       onchange="updateOptionStyle(this)">
                                {{ $option->text }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <div class="quiz-submit">
                        <button type="button" class="btn-submit" id="submitBtn" onclick="submitQuiz()">
                            Submeter respostas
                        </button>
                    </div>
                </form>

            </div>
        </div>

        @else
        <div class="quiz-card">
            <div class="no-quiz">
                <div class="icon">📋</div>
                <p>Esta formação ainda não tem questionário associado.</p>
            </div>
        </div>
        @endif
        @endif {{-- has_quiz --}}

    </div>

    {{-- ── Sidebar: info + histórico ── --}}
    <div>

        <div class="info-card">
            <p class="card-title">Sobre a Formação</p>
            @if($training->provider)
            <div class="info-row">
                <span class="label">Fornecedor</span>
                <span class="value">{{ $training->provider }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Vídeos</span>
                <span class="value">{{ $training->videos->count() }}</span>
            </div>
            <div class="info-row">
                <span class="label">Questionário</span>
                <span class="value">{{ $training->quiz ? 'Sim' : 'Não' }}</span>
            </div>
            @if($training->quiz)
            <div class="info-row">
                <span class="label">Perguntas</span>
                <span class="value">{{ $training->quiz->questions->count() }}</span>
            </div>
            <div class="info-row">
                <span class="label">Nota mínima</span>
                <span class="value">{{ $training->quiz->passing_score }}%</span>
            </div>
            @endif
            @if($training->description)
            <p style="font-size:.8rem;color:var(--text-muted);margin-top:12px;line-height:1.5">{{ $training->description }}</p>
            @endif
        </div>

        @if($attempts->isNotEmpty())
        <div class="info-card">
            <p class="card-title">Histórico de tentativas</p>
            @foreach($attempts->take(5) as $attempt)
            <div class="attempt-item">
                <div>
                    <div style="font-weight:500">Tentativa #{{ $loop->iteration }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted)">{{ $attempt->completed_at?->format('d/m/Y H:i') }}</div>
                </div>
                <span class="badge {{ $attempt->passed ? 'badge-passed' : 'badge-failed' }}">
                    {{ $attempt->score }}%
                </span>
            </div>
            @endforeach
        </div>
        @endif

    </div>

</div>

<div class="toast-wrap" id="toasts"></div>

@endsection

@section('scripts')
{{-- YouTube IFrame API --}}
@if($training->has_video)
@foreach($training->videos as $i => $v)
@php preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $v->url, $ym); @endphp
@if(!empty($ym[1]))
<script src="https://www.youtube.com/iframe_api"></script>
@break
@endif
@endforeach

@foreach($training->videos as $i => $v)
@php preg_match('/vimeo\.com\/(\d+)/', $v->url, $vm2); @endphp
@if(!empty($vm2[1]))
<script src="https://player.vimeo.com/api/player.js"></script>
@break
@endif
@endforeach
@endif

<script>
const TRAINING_ID = {{ $training->id }};
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ── Video watch tracking ──
const totalVideos = {{ $training->has_video ? $training->videos->count() : 0 }};
const watchedVideos = new Set(); // indices of watched videos
const videoTitles = {!! $training->has_video ? $training->videos->map(fn($v,$i) => [$i, $v->title])->values()->toJson() : '[]' !!};

function markWatched(idx) {
    if (watchedVideos.has(idx)) return;
    watchedVideos.add(idx);
    updateLockUI();
}

function updateLockUI() {
    const lockEl  = document.getElementById('quizLock');
    const quizEl  = document.getElementById('quizCard');
    if (!lockEl || !quizEl) return;

    // Update progress list
    const progEl = document.getElementById('lockProgress');
    if (progEl && videoTitles.length) {
        progEl.innerHTML = '<ul class="lock-progress-list">' +
            videoTitles.map(([idx, title]) =>
                '<li class="' + (watchedVideos.has(idx) ? 'done' : '') + '">' +
                '<span class="check"></span>' + title + '</li>'
            ).join('') +
        '</ul>';
    }

    if (watchedVideos.size >= totalVideos) {
        // All watched — unlock
        lockEl.style.display = 'none';
        quizEl.style.display = '';
        quizEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        toast('Vídeo(s) concluído(s)! Podes agora responder ao questionário.', 'success');
    }
}

// Init lock UI on load
document.addEventListener('DOMContentLoaded', function() {
    if (totalVideos > 0) updateLockUI();
});

// ── MP4 native video: listen for 'ended' ──
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('video[data-type="mp4"]').forEach(function(el) {
        el.addEventListener('ended', function() {
            markWatched(parseInt(el.dataset.videoIdx));
        });
    });
});

// ── YouTube IFrame API ──
const ytPlayers = {};
function onYouTubeIframeAPIReady() {
    document.querySelectorAll('iframe[data-type="youtube"]').forEach(function(el) {
        const idx = parseInt(el.dataset.videoIdx);
        ytPlayers[idx] = new YT.Player(el.id, {
            events: {
                onStateChange: function(event) {
                    if (event.data === YT.PlayerState.ENDED) {
                        markWatched(idx);
                    }
                }
            }
        });
    });
}

// ── Vimeo Player SDK ──
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('iframe[data-type="vimeo"]').forEach(function(el) {
        const idx = parseInt(el.dataset.videoIdx);
        if (typeof Vimeo !== 'undefined') {
            const player = new Vimeo.Player(el);
            player.on('ended', function() { markWatched(idx); });
        } else {
            // Fallback: Vimeo SDK not loaded yet, retry
            window.addEventListener('load', function() {
                if (typeof Vimeo !== 'undefined') {
                    const player = new Vimeo.Player(el);
                    player.on('ended', function() { markWatched(idx); });
                }
            });
        }
    });
});

// ── Tabs de vídeo ──
function switchVideo(idx, btn) {
    document.querySelectorAll('.video-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.video-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('video-' + idx).style.display = '';
    btn.classList.add('active');
}

// ── Estilo de opção selecionada ──
function updateOptionStyle(input) {
    const block = input.closest('.question-block');
    block.querySelectorAll('.option-label').forEach(l => l.classList.remove('selected'));
    input.closest('.option-label').classList.add('selected');
}

// ── Mostrar formulário de nova tentativa ──
function showQuiz() {
    document.getElementById('lastResultBanner')?.remove();
    document.getElementById('quizForm').style.display = '';
}

// ── Submeter questionário ──
async function submitQuiz() {
    const btn = document.getElementById('submitBtn');

    // Recolher respostas
    const blocks = document.querySelectorAll('.question-block');
    const answers = [];
    let unanswered = false;

    blocks.forEach(block => {
        const qid    = parseInt(block.dataset.questionId);
        const checked = block.querySelector('input[type=radio]:checked');
        if (!checked) { unanswered = true; return; }
        answers.push({ question_id: qid, option_id: parseInt(checked.value) });
    });

    if (unanswered) {
        return toast('Por favor responde a todas as perguntas.', 'error');
    }

    btn.disabled = true;
    btn.textContent = 'A enviar…';

    try {
        const res  = await fetch(`/api/v1/quiz/${TRAINING_ID}/attempt`, {
        credentials:'same-origin',
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify({ answers }),
        });
        const json = await res.json();

        if (!res.ok) {
            toast(json.message || 'Erro ao submeter.', 'error');
            btn.disabled = false;
            btn.textContent = 'Submeter respostas';
            return;
        }

        const d = json.data;
        showResult(d);

    } catch(e) {
        toast('Erro de ligação. Tenta novamente.', 'error');
        btn.disabled = false;
        btn.textContent = 'Submeter respostas';
    }
}

function showResult(d) {
    // Hide form
    document.getElementById('quizForm').style.display = 'none';

    // Build result banner
    const banner = document.getElementById('resultBanner');
    const passed = d.passed;
    banner.className = 'result-banner ' + (passed ? 'passed' : 'failed');
    banner.innerHTML = `
        <span class="result-icon">${passed ? '🏆' : '😕'}</span>
        <div class="result-text">
            <h3>${passed ? 'Aprovado!' : 'Não aprovado'} — ${d.score}%</h3>
            <p>${d.correct} de ${d.total} respostas correctas.
            ${passed
                ? ' Parabéns! Concluíste esta formação com sucesso.'
                : ` Precisas de ${d.passing_score}% para aprovação.`}
            </p>
        </div>
        ${!passed ? `<button class="btn-retry" onclick="retryQuiz()">Repetir questionário</button>` : ''}
    `;
    banner.style.display = 'flex';

    // Update sidebar history (reload page for simplicity)
    setTimeout(() => location.reload(), 2500);
}

function retryQuiz() {
    const banner = document.getElementById('resultBanner');
    banner.style.display = 'none';

    // Reset form
    const form = document.getElementById('quizForm');
    form.querySelectorAll('input[type=radio]').forEach(r => r.checked = false);
    form.querySelectorAll('.option-label').forEach(l => {
        l.classList.remove('selected','correct','wrong','correct-hint');
    });
    const btn = document.getElementById('submitBtn');
    btn.disabled = false;
    btn.textContent = 'Submeter respostas';
    form.style.display = '';
}

// ── Toast ──
function toast(msg, type = 'success') {
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.textContent = msg;
    document.getElementById('toasts').appendChild(el);
    setTimeout(() => el.remove(), 3500);
}
</script>
@endsection
