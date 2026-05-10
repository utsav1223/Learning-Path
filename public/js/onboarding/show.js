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

const highlightMatch = (value, term) => {
    if (!term) {
        return value;
    }

    const pattern = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'ig');

    return value.replace(pattern, '<mark class="rounded bg-violet-100 px-1 text-violet-800">$1</mark>');
};

const debounce = (callback, delay = 150) => {
    let timer;

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
    const steps = Array.from(document.querySelectorAll('[data-step-jump]'));
    const eyebrow = document.getElementById('step-eyebrow');
    const progressLabel = document.getElementById('progress-label');
    const progressBar = document.getElementById('progress-bar');
    const stepDots = Array.from(document.querySelectorAll('[data-step-dots] .step-dot'));
    const previousButton = document.querySelector('[data-prev-step]');
    const nextButton = document.querySelector('[data-next-step]');
    const submitButton = document.querySelector('[data-submit-step]');
    const stepTip = document.querySelector('[data-step-tip]');
    const actionHint = document.querySelector('[data-action-hint]');
    const saveState = document.querySelector('[data-save-state]');
    const mobileSummary = document.querySelector('[data-mobile-summary]');
    const goalTypes = document.querySelector('[data-goal-types]');
    const goalOptions = document.querySelector('[data-goal-options]');
    const goalOptionsShell = document.querySelector('[data-goal-options-shell]');
    const goalSearch = document.querySelector('[data-goal-search]');
    const selectedGoal = document.querySelector('[data-selected-goal]');
    const smartPanel = document.querySelector('[data-smart-panel]');
    const tabletSmartPanel = document.querySelector('[data-tablet-smart-panel]');
    const smartPanelText = Array.from(document.querySelectorAll('[data-smart-panel-text]'));
    const previewDrawer = document.querySelector('[data-preview-drawer]');
    const openPreviewDrawerButton = document.querySelector('[data-open-preview-drawer]');
    const closePreviewDrawerButton = document.querySelector('[data-close-preview-drawer]');
    const generatingOverlay = document.querySelector('[data-generating-overlay]');
    const generatingMessage = document.querySelector('[data-generating-message]');
    let currentStep = 0;
    let highestCompletedStep = 0;
    let activeGoalTypeId = '';
    let goalSearchTerm = '';
    let draftRestored = false;
    let hasInteractedWithSteps = false;

    const fields = {
        goal: form.querySelector('[name="learning_goal"]'),
        language: form.querySelector('[name="preferred_language"]'),
        interests: Array.from(form.querySelectorAll('[name="interests[]"]')),
        skill: Array.from(form.querySelectorAll('[name="skill_level"]')),
        get goalType() {
            return Array.from(form.querySelectorAll('[name="goal_type"]'));
        },
        get goalOption() {
            return Array.from(form.querySelectorAll('[name="goal_option"]'));
        },
    };

    const setFieldState = (name, isValid, message = '') => {
        const messageElement = form.querySelector(`[data-error-for="${name}"]`);
        const group = form.querySelector(`[data-field="${name}"]`);

        if (messageElement) {
            messageElement.textContent = message;
            messageElement.classList.toggle('hidden', isValid);
        }

        if (group) {
            group.classList.toggle('is-invalid', !isValid);
            group.classList.toggle('is-valid', isValid);
        }
    };

    const validateAll = () => {
        const validation = validateOnboarding(fields);

        setFieldState('goal_type', validation.goalTypeReady, validation.goalTypeReady ? 'Goal type selected.' : 'Choose what you want to achieve.');
        setFieldState('learning_goal', validation.goalReady, validation.goalReady ? 'Goal selected.' : 'Choose a specific goal.');
        setFieldState('preferred_language', validation.languageReady, validation.languageReady ? 'Looks good.' : 'Add a preferred language.');
        setFieldState('skill_level', validation.skillReady, validation.skillReady ? 'Level selected.' : 'Choose a skill level.');
        setFieldState('interests', validation.interestsReady, validation.interestsReady ? 'Focus selected.' : 'Choose at least one focus area.');

        return validation;
    };

    const isStepReady = (stepIndex) => {
        const validation = validateAll();

        if (stepIndex === 0) {
            return validation.goalTypeReady && validation.goalReady;
        }

        if (stepIndex === 1) {
            return validation.skillReady;
        }

        if (stepIndex === 2) {
            return validation.interestsReady;
        }

        return validation.languageReady;
    };

    const syncChoiceIndicators = () => {
        form.querySelectorAll('.choice input').forEach((input) => {
            const check = input.parentElement.querySelector('[data-check]');

            if (check) {
                check.textContent = input.checked ? '✓' : '+';
            }
        });
    };

    const persistDraft = () => {
        const profile = buildProfile(fields, form);

        try {
        localStorage.setItem(
                storageKey,
                JSON.stringify({
                    goalType: profile.goalType,
                    goal: fields.goal.value,
                    interests: profile.interests,
                    skill: profile.skill,
                    time: profile.time,
                    pace: profile.pace,
                    language: fields.language.value,
                }),
            );
        } catch (error) {
            saveState.textContent = 'Draft local only';
        }
    };

    const updateButtons = () => {
        const ready = isStepReady(currentStep);

        nextButton.disabled = !ready;
        nextButton.classList.toggle('opacity-45', !ready);
        nextButton.classList.toggle('cursor-not-allowed', !ready);
        submitButton.disabled = !ready;
        submitButton.classList.toggle('opacity-45', !ready);
        submitButton.classList.toggle('cursor-not-allowed', !ready);
    };

    const closePreviewDrawer = () => {
        if (!previewDrawer) {
            return;
        }

        previewDrawer.classList.add('hidden');
        previewDrawer.classList.remove('flex');
        previewDrawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    };

    const openPreviewDrawer = () => {
        if (!previewDrawer) {
            return;
        }

        previewDrawer.classList.remove('hidden');
        previewDrawer.classList.add('flex');
        previewDrawer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
    };

    const goNext = () => {
        if (isStepReady(currentStep)) {
            hasInteractedWithSteps = true;
            highestCompletedStep = Math.max(highestCompletedStep, currentStep);
            showStep(currentStep + 1);
        }
    };

    const showStep = (index) => {
        currentStep = Math.max(0, Math.min(index, panels.length - 1));
        const percent = Math.round(((currentStep + 1) / panels.length) * 100);

        panels.forEach((panel, panelIndex) => {
            panel.classList.toggle('is-active', panelIndex === currentStep);
        });

        if (hasInteractedWithSteps) {
            const activePanel = panels[currentStep];
            const firstField = activePanel.querySelector('input:not([type="hidden"]), select, textarea');

            if (firstField) {
                firstField.focus({ preventScroll: true });
            }
        }

        steps.forEach((step) => {
            const stepIndex = Number(step.dataset.stepJump);
            const isLocked = stepIndex > highestCompletedStep + 1;

            step.classList.toggle('is-active', stepIndex === currentStep);
            step.classList.toggle('is-complete', stepIndex <= highestCompletedStep && stepIndex < currentStep);
            step.disabled = isLocked;
            step.classList.toggle('opacity-45', isLocked);
            step.classList.toggle('cursor-not-allowed', isLocked);

            const status = step.querySelector('[data-step-status]');

            if (status) {
                status.textContent = stepIndex <= highestCompletedStep && stepIndex < currentStep
                    ? 'Done'
                    : (stepIndex === currentStep ? 'Active' : (isLocked ? 'Locked' : 'Next'));
            }
        });

        stepDots.forEach((dot, dotIndex) => {
            dot.classList.toggle('is-active', dotIndex === currentStep);
            dot.classList.toggle('is-complete', dotIndex <= highestCompletedStep && dotIndex < currentStep);
        });

        eyebrow.textContent = `Step ${currentStep + 1} of ${panels.length}`;
        progressLabel.textContent = `${percent}%`;
        progressBar.style.width = `${percent}%`;
        stepTip.textContent = stepTips[currentStep];
        previousButton.classList.toggle('opacity-40', currentStep === 0);
        previousButton.disabled = currentStep === 0;
        nextButton.classList.toggle('hidden', currentStep === panels.length - 1);
        submitButton.classList.toggle('hidden', currentStep !== panels.length - 1);
        actionHint.textContent = currentStep === panels.length - 1 ? 'Review, then submit' : 'Takes about 1 minute';

        updateButtons();
    };

    const renderGoalOptions = (goalTypeId) => {
        const type = config.goalTypes.find((item) => item.id === goalTypeId);
        activeGoalTypeId = goalTypeId || '';

        goalOptions.innerHTML = '';

        if (!type) {
            goalOptions.innerHTML = '<p class="rounded-xl bg-slate-50 p-4 text-sm font-medium text-slate-500 ring-1 ring-dashed ring-slate-200">Start with a direction. We will build the shortest useful roadmap from there.</p>';
            goalOptionsShell.classList.add('hidden');
            return;
        }

        goalOptionsShell.classList.remove('hidden');

        const filteredGoals = type.goals.filter((goal) => {
            const haystack = `${goal.label} ${goal.badge || ''} ${goal.stack.join(' ')}`.toLowerCase();

            return haystack.includes(goalSearchTerm.toLowerCase());
        });

        if (filteredGoals.length === 0) {
            goalOptions.innerHTML = '<p class="rounded-xl bg-slate-50 p-4 text-sm font-medium text-slate-500 ring-1 ring-dashed ring-slate-200">No matching goals yet. Try a broader search.</p>';
            return;
        }

        filteredGoals.forEach((goal, goalIndex) => {
            const isFeatured = goalIndex === 0;
            const label = document.createElement('label');
            label.className = `choice cursor-pointer ${isFeatured ? 'goal-feature sm:col-span-2' : ''}`;
            label.innerHTML = `
                <input type="radio" name="goal_option" value="${goal.label}" class="sr-only">
                <span class="block rounded-xl bg-slate-50 p-3 ring-1 ring-slate-200 ${isFeatured ? 'sm:p-5' : 'sm:p-4'}">
                    <span class="flex items-center justify-between gap-3">
                        <span class="flex items-center gap-2">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full ${isFeatured ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700'} text-xs font-bold">${goal.label.slice(0, 2).toUpperCase()}</span>
                            <span>
                                ${isFeatured ? '<span class="mb-1 inline-flex rounded-full bg-blue-600 px-2 py-1 text-xs font-bold text-white">Best match</span>' : ''}
                                <span class="block text-sm font-semibold">${highlightMatch(goal.label, goalSearchTerm)}</span>
                            </span>
                        </span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-sm font-bold shadow-sm" data-check>+</span>
                    </span>
                    <span class="mt-3 inline-flex rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">${goal.badge || 'Recommended'}</span>
                    <span class="mt-2 hidden text-xs font-normal leading-5 text-slate-500 sm:block">${highlightMatch(goal.stack.join(' -> '), goalSearchTerm)}</span>
                </span>
            `;

            label.style.animation = `stepIn 0.32s cubic-bezier(0.22, 1, 0.36, 1) ${goalIndex * 35}ms both`;

            goalOptions.appendChild(label);

            const input = label.querySelector('input');

            input.checked = fields.goal.value === goal.label;
            input.addEventListener('change', () => {
                fields.goal.value = input.value;
                selectedGoal.textContent = input.value;
                highestCompletedStep = Math.max(highestCompletedStep, 0);
                smartPanel?.classList.remove('hidden');
                tabletSmartPanel?.classList.remove('hidden');
                updatePreview();
            });
        });

        syncChoiceIndicators();
    };

    const renderGoalTypes = () => {
        goalTypes.innerHTML = '';

        config.goalTypes.forEach((goalType) => {
            const label = document.createElement('label');
            label.className = 'choice cursor-pointer';
            label.innerHTML = `
                <input type="radio" name="goal_type" value="${goalType.id}" class="sr-only">
                <span class="block rounded-xl border border-slate-200 bg-slate-50 p-3 sm:p-4">
                    <span class="flex items-center justify-between gap-3">
                        <span class="flex items-center gap-2">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">${goalType.summary.slice(0, 2).toUpperCase()}</span>
                            <span class="block text-sm font-semibold">${goalType.label}</span>
                        </span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-sm font-bold shadow-sm" data-check>+</span>
                    </span>
                    <span class="mt-2 block text-xs font-medium uppercase tracking-[0.18em] text-slate-400">${goalType.summary}</span>
                </span>
            `;

            goalTypes.appendChild(label);

            const input = label.querySelector('input');

            input.addEventListener('change', () => {
                fields.goal.value = '';
                selectedGoal.textContent = 'Choose a specific goal';
                goalSearchTerm = '';
                goalSearch.value = '';
                renderGoalOptions(input.value);
                updatePreview();
            });
        });
    };

    const selectGoalFromValue = (goalValue) => {
        const normalizedGoal = goalValue.toLowerCase();
        const matchingType = config.goalTypes.find((goalType) => goalType.goals.some((goal) => goal.label.toLowerCase() === normalizedGoal));
        const matchingGoal = matchingType?.goals.find((goal) => goal.label.toLowerCase() === normalizedGoal);

        if (!matchingType) {
            return false;
        }

        const typeInput = form.querySelector(`[name="goal_type"][value="${matchingType.id}"]`);

        if (typeInput) {
            typeInput.checked = true;
            renderGoalOptions(matchingType.id);
        }

        fields.goal.value = matchingGoal.label;
        selectedGoal.textContent = matchingGoal.label;

        const goalInput = form.querySelector(`[name="goal_option"][value="${matchingGoal.label}"]`);

        if (goalInput) {
            goalInput.checked = true;
        }

        return true;
    };

    const updatePreview = () => {
        const profile = buildProfile(fields, form);
        const recommendation = generateRecommendation(config, profile);
        const interestText = profile.interests.length ? profile.interests.join(', ') : 'Choose one';
        const goalText = profile.goal || 'Choose a goal';

        document.querySelectorAll('[data-preview="goal"]').forEach((node) => {
            node.textContent = goalText;
        });
        document.querySelectorAll('[data-preview="skill"]').forEach((node) => {
            node.textContent = profile.skill;
        });
        document.querySelectorAll('[data-preview="interests"]').forEach((node) => {
            node.textContent = interestText;
        });
        document.querySelectorAll('[data-preview="duration"]').forEach((node) => {
            node.textContent = recommendation.duration;
        });
        document.querySelectorAll('[data-preview="stack"]').forEach((node) => {
            node.textContent = recommendation.stack;
        });
        document.querySelectorAll('[data-preview="workload"]').forEach((node) => {
            node.textContent = recommendation.workload;
        });
        document.querySelectorAll('[data-preview="difficulty"]').forEach((node) => {
            node.textContent = recommendation.difficulty;
        });
        document.querySelectorAll('[data-preview="note"]').forEach((node) => {
            node.textContent = recommendation.note;
        });

        if (generatingMessage) {
            generatingMessage.textContent = recommendation.note;
        }

        smartPanelText.forEach((node) => {
            node.textContent = profile.goal
                ? `${recommendation.stack}. ${recommendation.note}`
                : 'Choose a goal to unlock a smarter starting plan.';
        });
        smartPanel?.classList.toggle('hidden', !profile.goal);

        if (selectedGoal) {
            selectedGoal.textContent = goalText;
        }

        if (mobileSummary) {
            mobileSummary.textContent = `${goalText} · ${profile.skill} · ${interestText}`;
        }

        persistDraft();
        saveState.textContent = 'Draft updated';
        window.clearTimeout(updatePreview.saveTimer);
        updatePreview.saveTimer = window.setTimeout(() => {
            saveState.textContent = 'Draft ready';
        }, 900);

        syncChoiceIndicators();
        updateButtons();
    };

    const restoreDraft = () => {
        let draft = {};

        try {
            draft = JSON.parse(localStorage.getItem(storageKey) || '{}');
        } catch (error) {
            draft = {};
        }

        if (draft.goalType) {
            const typeInput = form.querySelector(`[name="goal_type"][value="${draft.goalType}"]`);

            if (typeInput) {
                typeInput.checked = true;
                renderGoalOptions(draft.goalType);
            }
        }

        if (draft.goal) {
            if (!selectGoalFromValue(draft.goal)) {
                fields.goal.value = draft.goal;
                selectedGoal.textContent = draft.goal;
            }
        } else if (fields.goal.value) {
            selectGoalFromValue(fields.goal.value);
        }

        if (draft.skill) {
            const skillInput = form.querySelector(`[name="skill_level"][value="${draft.skill}"]`);
            if (skillInput) {
                skillInput.checked = true;
            }
        }

        if (draft.interests) {
            fields.interests.forEach((input) => {
                input.checked = draft.interests.includes(input.value);
            });
        }

        if (draft.language) {
            fields.language.value = draft.language;
        }

        if (draft.time) {
            form.querySelector('[data-summary="time"]').value = draft.time;
        }

        if (draft.pace) {
            form.querySelector('[data-summary="pace"]').value = draft.pace;
        }

        draftRestored = Boolean(draft.goal || draft.goalType || draft.interests?.length);

        if (draftRestored) {
            stepTip.textContent = 'Welcome back. Your draft is ready to continue.';
            saveState.textContent = 'Draft restored';
            highestCompletedStep = fields.goal.value ? 0 : highestCompletedStep;
        }
    };

    steps.forEach((step) => {
        step.addEventListener('click', () => {
            const stepIndex = Number(step.dataset.stepJump);
            const canMoveForward = stepIndex <= highestCompletedStep + 1 && isStepReady(currentStep);

            if (stepIndex <= currentStep || canMoveForward) {
                hasInteractedWithSteps = true;
                showStep(stepIndex);
            }
        });
    });

    openPreviewDrawerButton?.addEventListener('click', openPreviewDrawer);
    closePreviewDrawerButton?.addEventListener('click', closePreviewDrawer);
    previewDrawer?.addEventListener('click', (event) => {
        if (event.target === previewDrawer) {
            closePreviewDrawer();
        }
    });

    previousButton.addEventListener('click', () => {
        hasInteractedWithSteps = true;
        showStep(currentStep - 1);
    });

    nextButton.addEventListener('click', () => {
        goNext();
    });

    form.addEventListener('submit', (event) => {
        if (!isStepReady(currentStep)) {
            event.preventDefault();
            return;
        }

        if (!form.dataset.readyToSubmit) {
            event.preventDefault();
            generatingOverlay.classList.remove('hidden');
            generatingOverlay.classList.add('flex');
            form.dataset.readyToSubmit = 'true';
            window.setTimeout(() => form.submit(), 850);
        }
    });

    form.querySelectorAll('input, select, textarea').forEach((field) => {
        field.addEventListener('input', updatePreview);
        field.addEventListener('change', updatePreview);
        field.addEventListener('blur', updateButtons);
    });

    form.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter' || event.isComposing || event.target.matches('textarea')) {
            return;
        }

        event.preventDefault();

        if (currentStep < panels.length - 1) {
            goNext();
        } else if (isStepReady(currentStep)) {
            form.requestSubmit();
        }
    });

    const renderGoalOptionsFromSearch = debounce(() => {
        goalSearchTerm = goalSearch.value.trim();
        renderGoalOptions(activeGoalTypeId);
    });

    goalSearch.addEventListener('input', renderGoalOptionsFromSearch);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && currentStep > 0) {
            if (previewDrawer && !previewDrawer.classList.contains('hidden')) {
                closePreviewDrawer();
                return;
            }

            hasInteractedWithSteps = true;
            showStep(currentStep - 1);
        }
    });

    renderGoalTypes();
    restoreDraft();
    updatePreview();
    showStep(0);

    if (draftRestored) {
        stepTip.textContent = 'Welcome back. Your draft is ready to continue.';
    }
});
