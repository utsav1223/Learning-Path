import {
    buildProfile,
    generateRecommendation,
    selectedValues,
    stepTips,
    storageKey,
    validateOnboarding,
} from './state.js';

const loadConfig = async () => {
    const response = await fetch(new URL('./config/onboarding.json', import.meta.url));

    if (!response.ok) {
        throw new Error('Unable to load onboarding config.');
    }

    return response.json();
};

const debounce = (callback, delay = 150) => {
    let timer = 0;

    return (...args) => {
        window.clearTimeout(timer);
        timer = window.setTimeout(() => callback(...args), delay);
    };
};

document.addEventListener('DOMContentLoaded', async () => {
    const form = document.querySelector('[data-onboarding-form]');

    if (!form) {
        return;
    }

    const config = await loadConfig();
    const panels = Array.from(document.querySelectorAll('[data-step-panel]'));
    const timelineSteps = Array.from(document.querySelectorAll('[data-step-jump]'));
    const progressBar = document.querySelector('[data-progress-bar]');
    const progressLabel = document.querySelector('[data-progress-label]');
    const stepCounter = document.querySelector('[data-step-counter]');
    const stepTip = document.querySelector('[data-step-tip]');
    const nextButton = document.querySelector('[data-next-step]');
    const previousButton = document.querySelector('[data-prev-step]');
    const submitButton = document.querySelector('[data-submit-step]');
    const submitSpinner = document.querySelector('[data-submit-spinner]');
    const submitLabel = document.querySelector('[data-submit-label]');
    const goalTypes = document.querySelector('[data-goal-types]');
    const goalOptions = document.querySelector('[data-goal-options]');
    const goalSearch = document.querySelector('[data-goal-search]');
    const selectedGoal = document.querySelector('[data-selected-goal]');
    const generatingOverlay = document.querySelector('[data-generating-overlay]');
    const themeToggle = document.querySelector('[data-theme-toggle]');
    const summaryNodes = {
        goal: document.querySelector('[data-summary-goal]'),
        role: document.querySelector('[data-summary-role]'),
        level: document.querySelector('[data-summary-level]'),
        interests: document.querySelector('[data-summary-interests]'),
        pace: document.querySelector('[data-summary-pace]'),
        duration: document.querySelector('[data-summary-duration]'),
        stack: document.querySelector('[data-summary-stack]'),
        note: document.querySelector('[data-summary-note]'),
        strengths: document.querySelector('[data-summary-strengths]'),
    };

    let currentStep = 0;
    let activeGoalType = '';
    let goalSearchTerm = '';
    let highestUnlockedStep = 0;

    themeToggle?.addEventListener('click', () => {
        const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
        document.documentElement.classList.toggle('dark', nextTheme === 'dark');
        window.localStorage.setItem('skillweave-theme', nextTheme);
    });

    const fields = {
        goal: form.querySelector('[name="learning_goal"]'),
        targetRole: form.querySelector('[name="target_role"]'),
        experienceYears: form.querySelector('[name="experience_years"]'),
        language: form.querySelector('[name="preferred_language"]'),
        dailyTime: form.querySelector('[name="daily_learning_time"]'),
        weeklyDays: form.querySelector('[name="weekly_days"]'),
        studyWindow: form.querySelector('[name="preferred_study_window"]'),
        pace: form.querySelector('[name="learning_pace"]'),
        format: form.querySelector('[name="learning_format"]'),
        motivation: form.querySelector('[name="motivation"]'),
        projectPreference: form.querySelector('[name="project_preference"]'),
        supportStyle: form.querySelector('[name="support_style"]'),
        interests: Array.from(form.querySelectorAll('[name="interests[]"]')),
        strengths: Array.from(form.querySelectorAll('[name="strengths[]"]')),
        skill: Array.from(form.querySelectorAll('[name="skill_level"]')),
        get goalType() {
            return Array.from(form.querySelectorAll('[name="goal_type"]'));
        },
        get goalOption() {
            return Array.from(form.querySelectorAll('[name="goal_option"]'));
        },
    };

    const setFieldState = (name, isValid, message = '') => {
        const group = form.querySelector(`[data-field="${name}"]`);
        const errorNode = form.querySelector(`[data-error-for="${name}"]`);

        if (group) {
            group.classList.toggle('ring-rose-200', !isValid);
            group.classList.toggle('bg-rose-50', !isValid);
            group.classList.toggle('ring-emerald-200', isValid);
        }

        if (errorNode) {
            errorNode.textContent = message;
            errorNode.classList.toggle('hidden', isValid);
        }
    };

    const syncCheckIndicators = () => {
        form.querySelectorAll('[data-chip]').forEach((chip) => {
            const input = chip.querySelector('input');
            const indicator = chip.querySelector('[data-chip-indicator]');

            if (input && indicator) {
                indicator.textContent = input.checked ? 'OK' : '+';
            }
        });
    };

    const persistDraft = () => {
        const profile = buildProfile(fields, form);
        const payload = {
            goalType: profile.goalType,
            goal: profile.goal,
            targetRole: profile.targetRole,
            skill: profile.skill,
            experienceYears: profile.experienceYears,
            interests: profile.interests,
            strengths: profile.strengths,
            time: profile.time,
            weeklyDays: profile.weeklyDays,
            studyWindow: profile.studyWindow,
            pace: profile.pace,
            format: profile.format,
            motivation: profile.motivation,
            language: fields.language.value,
            projectPreference: fields.projectPreference.value,
            supportStyle: fields.supportStyle.value,
        };

        window.localStorage.setItem(storageKey, JSON.stringify(payload));
    };

    const applyValidation = () => {
        const validation = validateOnboarding(fields);

        setFieldState('goal_choice', validation.goalTypeReady && validation.goalReady, 'Choose a goal type and one specific goal.');
        setFieldState('target_role', validation.targetRoleReady, 'Add the role or outcome you want this path to target.');
        setFieldState('experience', validation.skillReady && validation.experienceReady, 'Select your current level and experience.');
        setFieldState('interests', validation.interestsReady, 'Choose at least one interest area.');
        setFieldState('strengths', validation.strengthsReady, 'Choose at least one current strength.');
        setFieldState('routine', validation.routineReady, 'Set your time, weekly days, and study window.');
        setFieldState('preferred_language', validation.languageReady, 'Add a preferred language.');
        setFieldState('preferences', validation.preferenceReady, 'Complete your learning preference settings.');
        setFieldState('motivation', validation.motivationReady, 'Choose the motivation that fits best.');

        return validation;
    };

    const isStepReady = (stepIndex) => {
        const validation = applyValidation();

        if (stepIndex === 0) {
            return validation.goalTypeReady && validation.goalReady && validation.targetRoleReady;
        }

        if (stepIndex === 1) {
            return validation.skillReady && validation.experienceReady;
        }

        if (stepIndex === 2) {
            return validation.interestsReady && validation.strengthsReady;
        }

        if (stepIndex === 3) {
            return validation.routineReady;
        }

        if (stepIndex === 4) {
            return validation.languageReady && validation.preferenceReady;
        }

        return validation.motivationReady;
    };

    const updateButtons = () => {
        const ready = isStepReady(currentStep);

        nextButton.disabled = !ready;
        nextButton.classList.toggle('opacity-50', !ready);
        nextButton.classList.toggle('cursor-not-allowed', !ready);
        submitButton.disabled = !ready;
        submitButton.classList.toggle('opacity-50', !ready);
        submitButton.classList.toggle('cursor-not-allowed', !ready);
    };

    const updateProgress = () => {
        const percent = Math.round(((currentStep + 1) / panels.length) * 100);

        progressBar.style.width = `${percent}%`;
        progressLabel.textContent = `${percent}% complete`;
        stepCounter.textContent = `Step ${currentStep + 1} of ${panels.length}`;
        stepTip.textContent = stepTips[currentStep];

        timelineSteps.forEach((step, index) => {
            const status = step.querySelector('[data-step-status]');
            const marker = step.querySelector('[data-step-marker]');
            const line = step.querySelector('[data-step-line]');
            const isComplete = index < currentStep;
            const isActive = index === currentStep;
            const isUpcoming = index > currentStep;

            const isLocked = index > highestUnlockedStep;
            step.classList.toggle('opacity-70', isUpcoming);
            step.disabled = isLocked;
            step.classList.toggle('cursor-not-allowed', isLocked);

            if (status) {
                status.textContent = isComplete ? 'Complete' : (isActive ? 'In progress' : 'Locked');
                status.classList.toggle('text-emerald-600', isComplete);
                status.classList.toggle('text-blue-600', isActive);
                status.classList.toggle('text-slate-400', isUpcoming);
            }

            if (marker) {
                marker.textContent = isComplete ? 'OK' : `${index + 1}`;
                marker.classList.toggle('border-emerald-500', isComplete);
                marker.classList.toggle('bg-emerald-500', isComplete);
                marker.classList.toggle('border-blue-600', isActive);
                marker.classList.toggle('bg-blue-600', isActive);
                marker.classList.toggle('border-slate-300', !isComplete && !isActive);
                marker.classList.toggle('bg-white', !isComplete && !isActive);
                marker.classList.toggle('text-white', isComplete || isActive);
                marker.classList.toggle('text-slate-600', !isComplete && !isActive);
            }

            if (line) {
                line.classList.toggle('bg-emerald-500', isComplete);
                line.classList.toggle('bg-blue-200', isActive);
                line.classList.toggle('bg-slate-200', isUpcoming);
            }
        });

        previousButton.disabled = currentStep === 0;
        previousButton.classList.toggle('opacity-40', currentStep === 0);
        nextButton.classList.toggle('hidden', currentStep === panels.length - 1);
        submitButton.classList.toggle('hidden', currentStep !== panels.length - 1);
    };

    const showStep = (index) => {
        currentStep = Math.max(0, Math.min(index, panels.length - 1));

        panels.forEach((panel, panelIndex) => {
            panel.classList.toggle('hidden', panelIndex !== currentStep);
        });

        updateProgress();
        updateButtons();
    };

    const renderGoalOptions = (goalTypeId) => {
        const type = config.goalTypes.find((item) => item.id === goalTypeId);
        goalOptions.innerHTML = '';
        activeGoalType = goalTypeId;

        if (!type) {
            goalOptions.innerHTML = '<div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-sm font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">Choose a direction to load recommended goals and the matching skill stack.</div>';
            return;
        }

        const filtered = type.goals.filter((goal) => {
            const haystack = `${goal.label} ${goal.badge || ''} ${(goal.stack || []).join(' ')}`.toLowerCase();
            return haystack.includes(goalSearchTerm.toLowerCase());
        });

        filtered.forEach((goal, index) => {
            const label = document.createElement('label');
            label.className = 'cursor-pointer';
            label.innerHTML = `
                <input type="radio" name="goal_option" value="${goal.label}" class="peer sr-only">
                <span class="block rounded-xl border border-slate-200 bg-white p-5 transition duration-200 dark:border-slate-700 dark:bg-slate-900 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-950/40">
                    <span class="flex items-start justify-between gap-4">
                        <span>
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-800 dark:text-slate-300">${goal.badge || 'Recommended'}</span>
                            <span class="mt-3 block text-lg font-extrabold text-slate-900 dark:text-slate-100">${goal.label}</span>
                            <span class="mt-2 block text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">${goal.fit || goal.stack.join(' -> ')}</span>
                        </span>
                        <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg bg-slate-100 px-3 text-xs font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">${goal.durationMonths} mo</span>
                    </span>
                    <span class="mt-4 grid gap-2 sm:grid-cols-3">
                        ${(goal.projects || []).slice(0, 3).map((project) => `<span class="rounded-lg bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 dark:bg-slate-800/80 dark:text-slate-300">${project}</span>`).join('')}
                    </span>
                    <span class="mt-4 block text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">${goal.stack.join(' -> ')}</span>
                </span>
            `;

            if (index === 0) {
                label.classList.add('md:col-span-2');
            }

            const input = label.querySelector('input');

            input.checked = fields.goal.value === goal.label;
            input.addEventListener('change', () => {
                fields.goal.value = goal.label;
                selectedGoal.textContent = goal.label;
                updatePreview();
            });

            goalOptions.appendChild(label);
        });
    };

    const renderGoalTypes = () => {
        goalTypes.innerHTML = '';

        config.goalTypes.forEach((goalType) => {
            const label = document.createElement('label');
            label.className = 'cursor-pointer';
            label.innerHTML = `
                <input type="radio" name="goal_type" value="${goalType.id}" class="peer sr-only">
                <span class="block rounded-xl border border-slate-200 bg-white p-4 transition duration-200 dark:border-slate-700 dark:bg-slate-900 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-950/40">
                    <span class="flex items-center justify-between gap-3">
                        <span>
                            <span class="block text-base font-extrabold text-slate-900 dark:text-slate-100">${goalType.label}</span>
                            <span class="mt-1 block text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">${goalType.summary}</span>
                        </span>
                        <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg bg-slate-100 px-3 text-xs font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">${goalType.goals.length}</span>
                    </span>
                    <span class="mt-3 block text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">${goalType.description || ''}</span>
                    <span class="mt-3 flex flex-wrap gap-2">
                        ${(goalType.outcomes || []).slice(0, 3).map((outcome) => `<span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-800 dark:text-slate-300">${outcome}</span>`).join('')}
                    </span>
                </span>
            `;

            const input = label.querySelector('input');

            input.addEventListener('change', () => {
                fields.goal.value = '';
                selectedGoal.textContent = 'Choose a specific goal';
                goalSearchTerm = '';
                goalSearch.value = '';
                renderGoalOptions(input.value);
                updatePreview();
            });

            goalTypes.appendChild(label);
        });
    };

    const restoreDraft = () => {
        try {
            const draft = JSON.parse(window.localStorage.getItem(storageKey) || '{}');

            if (draft.goalType) {
                const typeInput = form.querySelector(`[name="goal_type"][value="${draft.goalType}"]`);
                if (typeInput) {
                    typeInput.checked = true;
                    renderGoalOptions(draft.goalType);
                }
            }

            if (draft.goal) {
                fields.goal.value = draft.goal;
                selectedGoal.textContent = draft.goal;
                const optionInput = form.querySelector(`[name="goal_option"][value="${draft.goal}"]`);
                if (optionInput) {
                    optionInput.checked = true;
                }
            }

            if (draft.targetRole) {
                fields.targetRole.value = draft.targetRole;
            }

            if (draft.skill) {
                const skillInput = form.querySelector(`[name="skill_level"][value="${draft.skill}"]`);
                if (skillInput) {
                    skillInput.checked = true;
                }
            }

            if (draft.experienceYears) {
                fields.experienceYears.value = draft.experienceYears;
            }

            if (Array.isArray(draft.interests)) {
                fields.interests.forEach((input) => {
                    input.checked = draft.interests.includes(input.value);
                });
            }

            if (Array.isArray(draft.strengths)) {
                fields.strengths.forEach((input) => {
                    input.checked = draft.strengths.includes(input.value);
                });
            }

            if (draft.time) {
                fields.dailyTime.value = draft.time;
            }

            if (draft.weeklyDays) {
                fields.weeklyDays.value = draft.weeklyDays;
            }

            if (draft.studyWindow) {
                fields.studyWindow.value = draft.studyWindow;
            }

            if (draft.pace) {
                fields.pace.value = draft.pace;
            }

            if (draft.format) {
                fields.format.value = draft.format;
            }

            if (draft.motivation) {
                fields.motivation.value = draft.motivation;
            }

            if (draft.language) {
                fields.language.value = draft.language;
            }

            if (draft.projectPreference) {
                fields.projectPreference.value = draft.projectPreference;
            }

            if (draft.supportStyle) {
                fields.supportStyle.value = draft.supportStyle;
            }
        } catch (error) {
            window.localStorage.removeItem(storageKey);
        }
    };

    const updatePreview = () => {
        const profile = buildProfile(fields, form);
        const recommendation = generateRecommendation(config, profile);

        summaryNodes.goal.textContent = profile.goal || 'Choose a goal';
        summaryNodes.role.textContent = profile.targetRole || 'Add your target role';
        summaryNodes.level.textContent = `${profile.skill} | ${profile.experienceYears} yrs`;
        summaryNodes.interests.textContent = profile.interests.length ? profile.interests.join(', ') : 'Pick your focus areas';
        summaryNodes.pace.textContent = `${profile.time} min | ${profile.weeklyDays} days | ${profile.studyWindow}`;
        summaryNodes.duration.textContent = recommendation.duration;
        summaryNodes.stack.textContent = recommendation.stack;
        summaryNodes.note.textContent = recommendation.note;
        summaryNodes.strengths.textContent = profile.strengths.length ? profile.strengths.join(', ') : 'Highlight what is already working';

        persistDraft();
        syncCheckIndicators();
        updateButtons();
    };

    renderGoalTypes();
    restoreDraft();
    if (!activeGoalType) {
        renderGoalOptions('');
    }
    updatePreview();
    showStep(0);

    goalSearch.addEventListener('input', debounce(() => {
        goalSearchTerm = goalSearch.value.trim();
        renderGoalOptions(activeGoalType);
    }));

    timelineSteps.forEach((step) => {
        step.addEventListener('click', () => {
            const index = Number(step.dataset.stepJump);
            if (index <= highestUnlockedStep) {
                showStep(index);
            }
        });
    });

    nextButton.addEventListener('click', () => {
        if (isStepReady(currentStep)) {
            highestUnlockedStep = Math.max(highestUnlockedStep, currentStep + 1);
            showStep(currentStep + 1);
        }
    });

    previousButton.addEventListener('click', () => showStep(currentStep - 1));

    form.querySelectorAll('input, select, textarea').forEach((field) => {
        field.addEventListener('input', updatePreview);
        field.addEventListener('change', updatePreview);
    });

    form.addEventListener('submit', (event) => {
        if (!isStepReady(currentStep)) {
            event.preventDefault();
            return;
        }

        generatingOverlay.classList.remove('hidden');
        generatingOverlay.classList.add('flex');
        submitButton.disabled = true;
        submitButton.classList.add('cursor-wait', 'opacity-80');
        submitSpinner?.classList.remove('hidden');
        submitSpinner?.classList.add('inline-block', 'animate-spin');
        if (submitLabel) {
            submitLabel.textContent = 'Starting assessment...';
        }
    });
});
