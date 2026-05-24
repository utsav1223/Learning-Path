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
    <script>tailwind.config = { darkMode: 'class' };</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Manrope', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-950 dark:bg-slate-950 dark:text-slate-100">
    <main class="min-h-screen">
        <header class="border-b border-slate-200 bg-white/90 px-4 py-4 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-[1500px] flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-950 text-lg font-extrabold text-white dark:bg-blue-600">S</span>
                    <div>
                        <p class="text-lg font-extrabold">SkillWeave</p>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">One-time assessment</p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-3 lg:flex lg:items-center">
                    <button type="button" data-theme-toggle class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-700 transition hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        Theme
                    </button>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-extrabold text-amber-700 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-300">
                        Time left: <span data-assessment-timer>20:00</span>
                    </div>
                    <div class="rounded-xl bg-slate-950 px-4 py-3 text-sm font-extrabold text-white dark:bg-slate-800">
                        {{ $attempt->total_questions }} questions
                    </div>
                </div>
            </div>
        </header>

        <section class="mx-auto max-w-[1500px] px-4 py-5 sm:px-6 lg:px-8">
            <div class="grid gap-5 xl:grid-cols-[22rem_minmax(0,1fr)]">
                <aside class="space-y-5 xl:sticky xl:top-5 xl:self-start">
                    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">Assessment workspace</p>
                        <h1 class="mt-3 text-2xl font-extrabold">{{ $attempt->selected_goal }}</h1>
                        <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-400">{{ implode(' -> ', $recommendedStack) }}</p>

                        <div class="mt-6 rounded-xl bg-slate-50 p-4 dark:bg-slate-950">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Progress</p>
                                <p class="text-sm font-extrabold text-slate-600 dark:text-slate-300" data-assessment-progress-label>Question 1 of {{ $questions->count() }}</p>
                            </div>
                            <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                <div class="h-full rounded-full bg-blue-600 transition-all duration-300" data-assessment-progress-bar style="width: 4%"></div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">Question map</p>
                                <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-400">Each selected answer locks immediately.</p>
                            </div>
                        </div>
                        <div class="mt-5 grid grid-cols-5 gap-2 sm:grid-cols-8 xl:grid-cols-5" data-assessment-markers>
                            @foreach ($questions as $index => $question)
                                <button type="button" data-question-marker="{{ $index }}" class="rounded-xl border border-slate-200 bg-white px-0 py-3 text-xs font-extrabold text-slate-500 transition dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">Rules</p>
                        <div class="mt-4 grid gap-3">
                            <div class="rounded-xl bg-slate-50 p-4 text-sm font-semibold leading-6 text-slate-600 dark:bg-slate-950 dark:text-slate-300">Your first selected option is final for that question. Choose carefully before clicking.</div>
                            <div class="rounded-xl bg-slate-50 p-4 text-sm font-semibold leading-6 text-slate-600 dark:bg-slate-950 dark:text-slate-300">Previous questions cannot be reopened after moving forward.</div>
                            <div class="rounded-xl bg-slate-50 p-4 text-sm font-semibold leading-6 text-slate-600 dark:bg-slate-950 dark:text-slate-300">The result is submitted automatically when time ends.</div>
                        </div>
                    </section>
                </aside>

                <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <form method="POST" action="{{ route('assessment.store') }}" class="flex min-h-[calc(100vh-10rem)] flex-col" data-assessment-form>
                        @csrf
                        <input type="hidden" name="auto_submitted" value="0" data-auto-submitted>

                        <div class="flex-1 p-5 sm:p-6 lg:p-8">
                            @foreach ($questions as $index => $question)
                                <section class="{{ $index === 0 ? '' : 'hidden' }} h-full" data-question-panel data-question-id="{{ $question->id }}">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full bg-slate-950 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.16em] text-white dark:bg-slate-700">{{ $question->technology }}</span>
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.16em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $question->topic }}</span>
                                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ $question->difficulty }}</span>
                                        </div>
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-extrabold text-slate-600 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300">Question {{ $index + 1 }} / {{ $questions->count() }}</div>
                                    </div>

                                    <div class="mt-7 rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950 sm:p-6">
                                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Prompt</p>
                                        <h2 class="mt-3 text-xl font-extrabold leading-tight tracking-tight text-slate-950 dark:text-slate-100 sm:text-2xl lg:text-3xl">{{ $question->question }}</h2>
                                    </div>

                                    <div class="mt-6 grid gap-3 lg:grid-cols-2">
                                        @foreach ($question->shuffled_options as $optionIndex => $option)
                                            <label class="cursor-pointer" data-option-label>
                                                <input type="radio" name="question_{{ $question->id }}_choice" value="{{ $option }}" class="peer sr-only">
                                                <span class="flex h-full min-h-20 items-start gap-4 rounded-xl border border-slate-200 bg-white p-4 font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:hover:border-blue-500 dark:hover:bg-blue-950/20 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-950/40">
                                                    <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-slate-100 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ chr(65 + $optionIndex) }}</span>
                                                    <span class="min-w-0 flex-1 pt-1">{{ $option }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <input type="hidden" name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}">
                                    <div class="mt-4 hidden rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm font-semibold leading-6 text-blue-700 dark:border-blue-900 dark:bg-blue-950/30 dark:text-blue-300" data-selection-note>
                                        Answer locked. Move to the next question when you are ready.
                                    </div>
                                    <div class="mt-6 hidden rounded-xl border p-4 text-sm font-semibold leading-6" data-result-box></div>
                                </section>
                            @endforeach
                        </div>

                        <div class="border-t border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950 sm:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm font-semibold leading-6 text-slate-500 dark:text-slate-400">Select an option to unlock the next step.</p>
                                <div class="grid gap-3 sm:flex sm:flex-row">
                                    <button type="button" data-assessment-next class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700 disabled:pointer-events-none">
                                        Next question
                                    </button>
                                    <button type="submit" data-submit-assessment class="hidden rounded-xl bg-emerald-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-700 disabled:pointer-events-none">
                                        Submit assessment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const lockedUrl = window.location.href;
            const historyBufferSize = 12;
            let refillQueued = false;
            const lockedState = (index) => ({ skillweaveAssessmentLocked: true, index });
            const refillHistoryBuffer = () => {
                window.history.replaceState(lockedState(0), '', lockedUrl);
                for (let index = 1; index <= historyBufferSize; index += 1) {
                    window.history.pushState(lockedState(index), '', lockedUrl);
                }
            };
            refillHistoryBuffer();
            window.addEventListener('popstate', () => {
                if (refillQueued) return;
                refillQueued = true;
                window.setTimeout(() => {
                    refillHistoryBuffer();
                    refillQueued = false;
                }, 0);
            });
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) refillHistoryBuffer();
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
            const progressStorageKey = 'skillweave_assessment_progress_{{ $attempt->id }}';
            const assessmentDurationMs = 20 * 60 * 1000;

            let currentIndex = 0;
            let highestUnlockedIndex = 0;
            let isAutoSubmitting = false;
            const questionStates = {};
            const selectedAnswers = {};

            themeToggle?.addEventListener('click', () => {
                const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                document.documentElement.classList.toggle('dark', nextTheme === 'dark');
                localStorage.setItem('skillweave-theme', nextTheme);
            });

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
                if (isAutoSubmitting) return;
                isAutoSubmitting = true;
                autoSubmittedInput.value = '1';
                submitButton.disabled = true;
                nextButton.disabled = true;
                submitButton.textContent = 'Submitting...';
                localStorage.removeItem(timerStorageKey);
                localStorage.removeItem(progressStorageKey);
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
                    marker.className = 'rounded-xl border px-0 py-3 text-xs font-extrabold transition';

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
                const selectionNote = panel.querySelector('[data-selection-note]');
                selectionNote?.classList.remove('hidden');
                resultBox.textContent = isCorrect
                    ? `Correct. ${question.explanation}`
                    : `Wrong. Correct answer: ${question.correct_answer}. ${question.explanation}`;
                resultBox.className = `mt-6 rounded-xl border p-4 text-sm font-semibold leading-6 ${isCorrect
                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300'
                    : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-800 dark:bg-rose-950/30 dark:text-rose-300'}`;
                return isCorrect;
            };

            const lockQuestionOptions = (panel) => {
                panel.querySelectorAll('input[type="radio"]').forEach((radio) => {
                    radio.disabled = true;
                });

                panel.querySelectorAll('[data-option-label]').forEach((label) => {
                    label.classList.add('cursor-not-allowed');
                    label.querySelector('span')?.classList.add('opacity-70');
                });
            };

            const saveAssessmentProgress = () => {
                localStorage.setItem(progressStorageKey, JSON.stringify({
                    currentIndex,
                    highestUnlockedIndex,
                    questionStates,
                    selectedAnswers,
                }));
            };

            const applySavedSelections = () => {
                panels.forEach((panel, panelIndex) => {
                    const questionId = panel.dataset.questionId;
                    const savedAnswer = selectedAnswers[questionId];

                    if (!savedAnswer) {
                        return;
                    }

                    const hiddenAnswer = panel.querySelector(`input[name="answers[${questionId}]"]`);
                    const selectedRadio = Array.from(panel.querySelectorAll(`input[name="question_${questionId}_choice"]`))
                        .find((radio) => radio.value === savedAnswer);

                    if (hiddenAnswer) {
                        hiddenAnswer.value = savedAnswer;
                    }

                    if (selectedRadio) {
                        selectedRadio.checked = true;
                    }

                    if (questionStates[panelIndex]?.answered) {
                        showResult(panel, questions[panelIndex], savedAnswer);
                        lockQuestionOptions(panel);
                    }
                });
            };

            const restoreAssessmentProgress = () => {
                try {
                    const savedProgress = JSON.parse(localStorage.getItem(progressStorageKey) || '{}');

                    if (!savedProgress || typeof savedProgress !== 'object') {
                        return;
                    }

                    currentIndex = Math.max(0, Math.min(Number(savedProgress.currentIndex) || 0, panels.length - 1));
                    highestUnlockedIndex = Math.max(0, Math.min(Number(savedProgress.highestUnlockedIndex) || 0, panels.length - 1));
                    Object.assign(questionStates, savedProgress.questionStates || {});
                    Object.assign(selectedAnswers, savedProgress.selectedAnswers || {});
                    applySavedSelections();
                } catch (error) {
                    localStorage.removeItem(progressStorageKey);
                }
            };

            panels.forEach((panel, panelIndex) => {
                const questionId = panel.dataset.questionId;
                panel.querySelectorAll(`input[name="question_${questionId}_choice"]`).forEach((input) => {
                    input.addEventListener('change', function () {
                        if (panelIndex < currentIndex) return;
                        if (questionStates[panelIndex]?.answered) return;

                        const question = questions[panelIndex];
                        const isCorrect = showResult(panel, question, input.value);
                        panel.querySelector(`input[name="answers[${questionId}]"]`).value = input.value;
                        selectedAnswers[questionId] = input.value;
                        questionStates[panelIndex] = { answered: true, correct: isCorrect };
                        lockQuestionOptions(panel);
                        highestUnlockedIndex = Math.max(highestUnlockedIndex, Math.min(panelIndex + 1, panels.length - 1));
                        saveAssessmentProgress();
                        renderState();
                    });
                });
            });

            nextButton.addEventListener('click', function () {
                if (!questionStates[currentIndex]?.answered || currentIndex === panels.length - 1) return;
                currentIndex += 1;
                saveAssessmentProgress();
                renderState();
            });

            markers.forEach((marker) => {
                marker.addEventListener('click', function () {
                    const targetIndex = Number(marker.dataset.questionMarker);
                    if (targetIndex === currentIndex || targetIndex > highestUnlockedIndex || targetIndex < currentIndex) return;
                    currentIndex = targetIndex;
                    saveAssessmentProgress();
                    renderState();
                });
            });

            form.addEventListener('submit', function (event) {
                if (!isAutoSubmitting && !questionStates[currentIndex]?.answered) {
                    event.preventDefault();
                    return;
                }
                localStorage.removeItem(timerStorageKey);
                localStorage.removeItem(progressStorageKey);
            });

            restoreAssessmentProgress();
            renderState();
            startAssessmentTimer();
        });
    </script>
</body>
</html>
