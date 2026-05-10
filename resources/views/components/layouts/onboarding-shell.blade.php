@props([
    'title' => 'Onboarding | SkillWeave',
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .step-panel { display: none; }
        .step-panel.is-active { display: block; animation: stepIn 0.32s cubic-bezier(0.22, 1, 0.36, 1); }
        .step-dot.is-active { background: #2563eb; transform: scale(1.15); }
        .step-dot.is-complete { background: #10b981; }
        @keyframes stepIn {
            from { opacity: 0; transform: translateX(14px) scale(0.985); filter: blur(2px); }
            to { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }
        @keyframes pulseLine {
            0%, 100% { opacity: 0.45; transform: scaleX(0.72); }
            50% { opacity: 1; transform: scaleX(1); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-100 to-slate-50 text-slate-900">
    {{ $slot }}
</body>
</html>
