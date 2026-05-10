export const storageKey = 'skillweave_onboarding';

export const stepTips = [
    'Choose the outcome first. The next options adapt to it.',
    'Choose your current level. You can speed up later.',
    'Pick at least one area for the first dashboard.',
    'Set a rhythm that fits a normal week.',
];

export const selectedValues = (inputs) => inputs
    .filter((input) => input.checked)
    .map((input) => input.value);

const hasRepeatedCharacters = (value) => /(.)\1{3,}/i.test(value.replace(/\s+/g, ''));
const hasLetters = (value) => /[a-z]/i.test(value);
const hasEnoughWords = (value) => value.trim().split(/\s+/).filter(Boolean).length >= 2;

export const isMeaningfulText = (value) => {
    const text = value.trim();

    return text.length >= 5
        && hasLetters(text)
        && hasEnoughWords(text)
        && !/^\d+$/.test(text)
        && !hasRepeatedCharacters(text);
};

export const validateOnboarding = (fields) => ({
    goalTypeReady: fields.goalType.some((input) => input.checked),
    goalReady: fields.goalOption.some((input) => input.checked) || isMeaningfulText(fields.goal.value),
    languageReady: fields.language.value.trim().length >= 2 && hasLetters(fields.language.value),
    skillReady: fields.skill.some((input) => input.checked),
    interestsReady: selectedValues(fields.interests).length > 0,
});

const findGoal = (config, goal) => config.goalTypes
    .flatMap((type) => type.goals)
    .find((option) => option.label === goal);

const findInterestRule = (config, interests) => config.interestRules
    .find((rule) => interests.includes(rule.interest));

/**
 * @typedef {Object} GoalOption
 * @property {string} label
 * @property {string[]} stack
 * @property {number} durationMonths
 * @property {string} difficulty
 */

/**
 * @typedef {Object} OnboardingConfig
 * @property {Array<{id: string, goals: GoalOption[]}>} goalTypes
 * @property {Array<{interest: string, beginnerStack: string[], note: string}>} interestRules
 * @property {Record<string, number>} skillAdjustments
 * @property {Record<string, number>} timeAdjustments
 * @property {Record<string, number>} paceAdjustments
 */

/**
 * @typedef {Object} OnboardingProfile
 * @property {string} goalType
 * @property {string} goal
 * @property {string} skill
 * @property {string[]} interests
 * @property {string} time
 * @property {string} pace
 */

export const buildProfile = (fields, form) => ({
    goalType: form.querySelector('[name="goal_type"]:checked')?.value || '',
    goal: fields.goal.value.trim(),
    skill: form.querySelector('[name="skill_level"]:checked')?.value || 'Beginner',
    interests: selectedValues(fields.interests),
    time: form.querySelector('[data-summary="time"]').value || '45',
    pace: form.querySelector('[data-summary="pace"]').value || 'Steady',
});

/**
 * Generates a personalized roadmap recommendation from config and user choices.
 * @param {OnboardingConfig} config
 * @param {OnboardingProfile} profile
 * @returns {{duration: string, stack: string, workload: string, difficulty: string, note: string}}
 */
export const generateRecommendation = (config, profile) => {
    const selectedGoal = findGoal(config, profile.goal);
    const interestRule = findInterestRule(config, profile.interests);
    const baseDuration = selectedGoal?.durationMonths || 3;
    const adjustment = (config.skillAdjustments[profile.skill] || 0)
        + (config.timeAdjustments[profile.time] || 0)
        + (config.paceAdjustments[profile.pace] || 0);
    const duration = Math.max(1, baseDuration + adjustment);
    const durationText = duration === 1 ? '1 month' : `${duration} months`;
    const stack = profile.skill === 'Beginner' && interestRule
        ? interestRule.beginnerStack
        : (selectedGoal?.stack || ['Foundations', 'Practice', 'Projects']);
    const workload = Number(profile.time) <= 30
        ? 'Light daily plan'
        : (profile.pace === 'Fast track' ? 'High-intensity plan' : 'Steady weekly plan');
    const difficulty = selectedGoal?.difficulty || (profile.skill === 'Beginner' ? 'Light' : 'Moderate');
    let note = interestRule?.note || 'Roadmap starts with the shortest useful foundation path.';

    if (profile.skill === 'Advanced') {
        note = 'Skipping basics and prioritizing projects, reviews, and checkpoints.';
    } else if (profile.skill === 'Beginner' && Number(profile.time) <= 30) {
        note = interestRule?.note
            ? `${interestRule.note} Workload is reduced for 30-minute sessions.`
            : 'Starting with short fundamentals and lighter daily practice.';
    }

    return {
        duration: durationText,
        stack: stack.join(' -> '),
        workload,
        difficulty,
        note,
    };
};
