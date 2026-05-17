export const storageKey = 'skillweave_onboarding_v2';

export const stepTips = [
    'Start with the destination so recommendations can branch intelligently.',
    'We calibrate depth using your current level and real experience.',
    'Signal both interest and strengths so the dashboard can separate confidence from ambition.',
    'A realistic study rhythm beats an aggressive plan you cannot keep.',
    'Learning preferences shape the way projects, reviews, and checkpoints are delivered.',
    'Review the profile before the one-time assessment is generated.',
];

export const selectedValues = (inputs) => inputs
    .filter((input) => input.checked)
    .map((input) => input.value);

const hasLetters = (value) => /[a-z]/i.test(value);

export const validateOnboarding = (fields) => ({
    goalTypeReady: fields.goalType.some((input) => input.checked),
    goalReady: fields.goalOption.some((input) => input.checked) || fields.goal.value.trim().length >= 3,
    targetRoleReady: fields.targetRole.value.trim().length >= 3,
    skillReady: fields.skill.some((input) => input.checked),
    experienceReady: Number(fields.experienceYears.value) >= 0,
    interestsReady: selectedValues(fields.interests).length > 0,
    strengthsReady: selectedValues(fields.strengths).length > 0,
    languageReady: fields.language.value.trim().length >= 2 && hasLetters(fields.language.value),
    routineReady: Number(fields.dailyTime.value) >= 15
        && Number(fields.weeklyDays.value) >= 1
        && fields.studyWindow.value.trim().length >= 2,
    preferenceReady: fields.format.value.trim().length >= 2
        && fields.pace.value.trim().length >= 2
        && fields.projectPreference.value.trim().length >= 2
        && fields.supportStyle.value.trim().length >= 2,
    motivationReady: fields.motivation.value.trim().length >= 3,
});

const findGoal = (config, goal) => config.goalTypes
    .flatMap((type) => type.goals)
    .find((option) => option.label === goal);

const findInterestRule = (config, interests) => config.interestRules
    .find((rule) => interests.includes(rule.interest));

export const buildProfile = (fields, form) => ({
    goalType: form.querySelector('[name="goal_type"]:checked')?.value || '',
    goal: fields.goal.value.trim(),
    targetRole: fields.targetRole.value.trim(),
    skill: form.querySelector('[name="skill_level"]:checked')?.value || 'Beginner',
    experienceYears: fields.experienceYears.value || '0',
    interests: selectedValues(fields.interests),
    strengths: selectedValues(fields.strengths),
    time: fields.dailyTime.value || '45',
    weeklyDays: fields.weeklyDays.value || '5',
    studyWindow: fields.studyWindow.value || 'Evening',
    pace: fields.pace.value || 'Steady',
    format: fields.format.value || 'Projects first',
    motivation: fields.motivation.value || '',
});

export const generateRecommendation = (config, profile) => {
    const selectedGoal = findGoal(config, profile.goal);
    const interestRule = findInterestRule(config, profile.interests);
    const baseDuration = selectedGoal?.durationMonths || 3;
    const durationShift = (config.skillAdjustments[profile.skill] || 0)
        + (config.timeAdjustments[profile.time] || 0)
        + (config.paceAdjustments[profile.pace] || 0);
    const duration = Math.max(1, baseDuration + durationShift);
    const durationText = duration === 1 ? '1 month' : `${duration} months`;
    const stack = profile.skill === 'Beginner' && interestRule
        ? interestRule.beginnerStack
        : (selectedGoal?.stack || ['Foundations', 'Practice', 'Projects']);
    const workload = Number(profile.time) <= 30
        ? 'Light daily load'
        : (profile.pace === 'Fast track' ? 'High-output sprint' : 'Balanced weekly rhythm');
    const difficulty = selectedGoal?.difficulty || (profile.skill === 'Advanced' ? 'High' : 'Moderate');
    const note = interestRule?.note
        || 'Your plan starts with the shortest route to useful momentum.';

    return {
        duration: durationText,
        stack: stack.join(' -> '),
        workload,
        difficulty,
        note,
    };
};
