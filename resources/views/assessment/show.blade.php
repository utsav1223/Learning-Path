<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment | SkillWeave</title>
    <script>
        (() => {
            const theme = localStorage.getItem('skillweave-theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <main class="min-h-screen">
        <section class="border-b border-slate-200 bg-white/90 px-4 py-4 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-[1600px] flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-lg font-extrabold text-white dark:bg-blue-600">S</span>
                    <div>
                        <p class="text-lg font-extrabold">SkillWeave</p>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">One-time assessment</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" data-theme-toggle class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-extrabold text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        Toggle theme
                    </button>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-extrabold text-amber-700 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-300">
                        Time left: <span data-assessment-timer>20:00</span>
                    </div>
                    <div class="rounded-2xl bg-slate-950 px-4 py-2 text-sm font-extrabold text-white dark:bg-slate-800">
                        {{ $attempt->total_questions }} questions
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto flex min-h-[calc(100vh-89px)] max-w-[1600px] flex-col px-4 py-5 sm:px-6 lg:px-8 lg:py-6">
            <div class="grid flex-1 gap-5 xl:grid-cols-[18rem_minmax(0,1fr)]">
                <aside class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">Assessment rules</p>
                    <h1 class="mt-3 text-2xl font-extrabold">{{ $attempt->selected_goal }}</h1>
                    <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-400">{{ implode(' -> ', $recommendedStack) }}</p>

                    <div class="mt-6 rounded-[1.5rem] bg-slate-100 p-4 dark:bg-slate-800">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Progress</p>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="h-full rounded-full bg-blue-600 transition-all duration-300" data-assessment-progress-bar style="width: 4%"></div>
                        </div>
                        <p class="mt-3 text-sm font-bold text-slate-600 dark:text-slate-300" data-assessment-progress-label>Question 1 of {{ $questions->count() }}</p>
                    </div>

                    <div class="mt-6 grid grid-cols-5 gap-2 sm:grid-cols-6 xl:grid-cols-4" data-assessment-markers>
                        @foreach ($questions as $index => $question)
                            <button type="button" data-question-marker="{{ $index }}" class="rounded-2xl border border-slate-200 bg-white px-0 py-3 text-xs font-extrabold text-slate-500 transition dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-6 rounded-[1.5rem] border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-sm font-semibold leading-6 text-slate-600 dark:text-slate-300">Answers are evaluated immediately. Once you move to the next question, previous questions are locked.</p>
                    </div>
                </aside>

                <div class="flex flex-col rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:p-8">
                    <form method="POST" action="{{ route('assessment.store') }}" class="flex h-full flex-col" data-assessment-form>
                        @csrf
                        <input type="hidden" name="auto_submitted" value="0" data-auto-submitted>

                        <div class="flex-1">
                            @foreach ($questions as $index => $question)
                                <section class="{{ $index === 0 ? '' : 'hidden' }} h-full" data-question-panel data-question-id="{{ $question->id }}">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="inline-flex rounded-full bg-slate-950 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.18em] text-white dark:bg-slate-700">{{ $question->technology }}</span>
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $question->topic }}</span>
                                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ $question->difficulty }}</span>
                                    </div>

                                    <p class="mt-6 text-2xl font-extrabold tracking-tight sm:text-3xl">{{ $index + 1 }}. {{ $question->question }}</p>

                                    <div class="mt-8 grid gap-3">
                                        @foreach ($question->options as $option)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="question_{{ $question->id }}_choice" value="{{ $option }}" class="peer sr-only">
                                                <span class="flex items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-4 font-semibold text-slate-700 transition dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-950/40">
                                                    <span>{{ $option }}</span>
                                                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-2xl bg-white px-3 text-xs font-extrabold text-slate-500 dark:bg-slate-900 dark:text-slate-300">Pick</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <input type="hidden" name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}">

                                    <div class="mt-6 hidden rounded-[1.5rem] border p-4 text-sm font-semibold leading-6" data-result-box></div>
                                </section>
                            @endforeach
                        </div>

                        <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-end">
                            <button type="button" data-assessment-next class="rounded-3xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                Next question
                            </button>
                            <button type="submit" data-submit-assessment class="hidden rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-700">
                                Submit assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const lockedUrl = window.location.href;
            const historyBufferSize = 12;
            let refillQueued = false;

            const lockedState = (index) => ({
                skillweaveAssessmentLocked: true,
                index,
            });

            const refillHistoryBuffer = () => {
                window.history.replaceState(lockedState(0), '', lockedUrl);

                for (let index = 1; index <= historyBufferSize; index += 1) {
                    window.history.pushState(lockedState(index), '', lockedUrl);
                }
            };

            refillHistoryBuffer();
            window.addEventListener('popstate', () => {
                if (refillQueued) {
                    return;
                }

                refillQueued = true;
                window.setTimeout(() => {
                    refillHistoryBuffer();
                    refillQueued = false;
                }, 0);
            });

            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    refillHistoryBuffer();
                }
            });
        })();

        document.addEventListener('DOMContentLoaded', function () {
            const questions = @json($questionMeta);
            const form = document.querySelector('[data-assessment-form]');
            const panels = Array.from(document.querySelectorAll('[data-question-panel]'));
            const markers = Array.from(document.querySelectorAll('[data-question-marker]'));
            const progressBar = document.querySelector('[data-assessment-progress-bar]');
            const progressLabel = document.querySelector('[data-assessment-progress-label]');
            const nextButton = document.querySelector('[data-assessment-next]');
            const submitButton = document.querySelector('[data-submit-assessment]');
            const themeToggle = document.querySelector('[data-theme-toggle]');
            const timerLabel = document.querySelector('[data-assessment-timer]');
            const autoSubmittedInput = document.querySelector('[data-auto-submitted]');
            const timerStorageKey = 'skillweave_assessment_deadline_{{ $attempt->id }}';
            const assessmentDurationMs = 20 * 60 * 1000;

            let currentIndex = 0;
            let highestUnlockedIndex = 0;
            let isAutoSubmitting = false;
            const questionStates = {};

            const applyTheme = () => {
                const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                document.documentElement.classList.toggle('dark', nextTheme === 'dark');
                localStorage.setItem('skillweave-theme', nextTheme);
            };

            themeToggle?.addEventListener('click', applyTheme);

            const currentQuestion = () => questions[currentIndex];

            const formatRemainingTime = (milliseconds) => {
                const totalSeconds = Math.max(0, Math.ceil(milliseconds / 1000));
                const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                const seconds = String(totalSeconds % 60).padStart(2, '0');

                return `${minutes}:${seconds}`;
            };

            const getAssessmentDeadline = () => {
                const savedDeadline = Number(localStorage.getItem(timerStorageKey));

                if (savedDeadline > Date.now()) {
                    return savedDeadline;
                }

                const nextDeadline = Date.now() + assessmentDurationMs;
                localStorage.setItem(timerStorageKey, String(nextDeadline));

                return nextDeadline;
            };

            const submitExpiredAssessment = () => {
                if (isAutoSubmitting) {
                    return;
                }

                isAutoSubmitting = true;
                autoSubmittedInput.value = '1';
                submitButton.disabled = true;
                nextButton.disabled = true;
                submitButton.textContent = 'Submitting...';
                localStorage.removeItem(timerStorageKey);
                form.submit();
            };

            const startAssessmentTimer = () => {
                const deadline = getAssessmentDeadline();

                const tick = () => {
                    const remaining = deadline - Date.now();
                    timerLabel.textContent = formatRemainingTime(remaining);

                    if (remaining <= 0) {
                        submitExpiredAssessment();
                        return;
                    }

                    window.setTimeout(tick, 1000);
                };

                tick();
            };

            const renderState = () => {
                const percent = Math.round(((currentIndex + 1) / panels.length) * 100);
                progressBar.style.width = `${percent}%`;
                progressLabel.textContent = `Question ${currentIndex + 1} of ${panels.length}`;

                panels.forEach((panel, index) => {
                    panel.classList.toggle('hidden', index !== currentIndex);
                });

                markers.forEach((marker, index) => {
                    const state = questionStates[index];
                    const isActive = index === currentIndex;
                    const isLocked = index > highestUnlockedIndex;

                    marker.disabled = isLocked || index < currentIndex;
                    marker.className = 'rounded-2xl border px-0 py-3 text-xs font-extrabold transition';

                    if (state?.correct) {
                        marker.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-700', 'dark:bg-emerald-950/30', 'dark:text-emerald-300');
                    } else if (state && state.correct === false) {
                        marker.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-700', 'dark:bg-rose-950/30', 'dark:text-rose-300');
                    } else if (isActive) {
                        marker.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700', 'dark:bg-blue-950/30', 'dark:text-blue-300');
                    } else if (isLocked) {
                        marker.classList.add('border-slate-200', 'bg-slate-100', 'text-slate-400', 'dark:border-slate-700', 'dark:bg-slate-800', 'dark:text-slate-500');
                    } else {
                        marker.classList.add('border-slate-200', 'bg-white', 'text-slate-600', 'dark:border-slate-700', 'dark:bg-slate-800', 'dark:text-slate-300');
                    }
                });

                const state = questionStates[currentIndex];
                const ready = Boolean(state?.answered);
                const isLast = currentIndex === panels.length - 1;

                nextButton.disabled = !ready || isLast;
                nextButton.classList.toggle('opacity-50', !ready || isLast);
                nextButton.classList.toggle('cursor-not-allowed', !ready || isLast);
                nextButton.classList.toggle('hidden', isLast);
                submitButton.classList.toggle('hidden', !isLast);
                submitButton.disabled = !ready;
                submitButton.classList.toggle('opacity-50', !ready);
                submitButton.classList.toggle('cursor-not-allowed', !ready);
            };

            const showResult = (panel, question, selectedValue) => {
                const isCorrect = selectedValue === question.correct_answer;
                const resultBox = panel.querySelector('[data-result-box]');
                resultBox.textContent = isCorrect
                    ? `Correct. ${question.explanation}`
                    : `Wrong. Correct answer: ${question.correct_answer}. ${question.explanation}`;
                resultBox.className = `mt-6 rounded-[1.5rem] border p-4 text-sm font-semibold leading-6 ${isCorrect
                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300'
                    : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-800 dark:bg-rose-950/30 dark:text-rose-300'}`;

                return isCorrect;
            };

            panels.forEach((panel, panelIndex) => {
                const questionId = panel.dataset.questionId;
                panel.querySelectorAll(`input[name="question_${questionId}_choice"]`).forEach((input) => {
                    input.addEventListener('change', function () {
                        if (panelIndex < currentIndex) {
                            return;
                        }

                        const question = questions[panelIndex];
                        const isCorrect = showResult(panel, question, input.value);
                        panel.querySelector(`input[name="answers[${questionId}]"]`).value = input.value;
                        questionStates[panelIndex] = {
                            answered: true,
                            correct: isCorrect,
                        };
                        highestUnlockedIndex = Math.max(highestUnlockedIndex, Math.min(panelIndex + 1, panels.length - 1));
                        renderState();
                    });
                });
            });

            nextButton.addEventListener('click', function () {
                if (!questionStates[currentIndex]?.answered || currentIndex === panels.length - 1) {
                    return;
                }

                currentIndex += 1;
                renderState();
            });

            markers.forEach((marker) => {
                marker.addEventListener('click', function () {
                    const targetIndex = Number(marker.dataset.questionMarker);
                    if (targetIndex === currentIndex || targetIndex > highestUnlockedIndex || targetIndex < currentIndex) {
                        return;
                    }

                    currentIndex = targetIndex;
                    renderState();
                });
            });

            form.addEventListener('submit', function (event) {
                if (!isAutoSubmitting && !questionStates[currentIndex]?.answered) {
                    event.preventDefault();
                    return;
                }

                localStorage.removeItem(timerStorageKey);
            });

            renderState();
            startAssessmentTimer();
        });
    </script>
</body>
</html>
