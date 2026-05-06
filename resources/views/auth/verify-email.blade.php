<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email | SkillWeave</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lexend:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-brand { font-family: 'Lexend', sans-serif; }
        .bg-brand { background-color: #0056D2; }
        .text-brand { color: #0056D2; }
        
        .glass-overlay {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .animate-pulse-soft { animation: pulse-soft 3s infinite; }
    </style>
</head>
<body class="bg-white min-h-screen">

    <div class="flex flex-col md:flex-row min-h-screen w-full">
        
        <!-- Left Side: Full Page Hero Image (Hidden on Mobile) -->
        <div class="hidden md:block md:w-1/2 lg:w-3/5 relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=1400&q=80" 
                 alt="Learning and growth" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <!-- Branding Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#0056D2]/95 via-[#0056D2]/70 to-black/50 flex flex-col justify-end p-12 lg:p-20">
                <div class="glass-overlay p-8 rounded-[2rem] max-w-lg mb-8">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h1 class="font-brand text-white text-5xl mb-4 tracking-tight">SkillWeave</h1>
                    <p class="text-blue-50 text-lg font-light leading-relaxed">
                        Your adaptive journey is just one click away. We use high-fidelity verification to keep your personalized learning data safe and secure.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side: Professional Verification Action -->
        <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center bg-white px-8 py-12 md:px-12 lg:px-20">
            <div class="w-full max-w-md mx-auto">
                
                <!-- Mobile Logo -->
                <div class="md:hidden mb-10 text-center">
                     <h1 class="font-brand text-brand text-4xl tracking-tighter uppercase">SkillWeave</h1>
                </div>

                <!-- Icon and Heading -->
                <div class="text-center mb-10">
                    <div class="inline-flex relative mb-6">
                        <div class="bg-blue-50 p-6 rounded-3xl">
                            <svg class="w-12 h-12 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="absolute -top-1 -right-1 block h-5 w-5 rounded-full bg-amber-400 border-4 border-white animate-pulse"></span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">Confirm your email</h2>
                    <p class="text-slate-500 font-medium">We've sent a verification link to your inbox. Please check your email to continue.</p>
                </div>

                <!-- Laravel Session Status -->
                @if (session('message'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 shadow-sm">
                        <div class="bg-emerald-500 rounded-full p-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-emerald-800 uppercase tracking-tight">New link has been dispatched!</p>
                    </div>
                @endif

                <!-- Warning Panel -->
                <div class="mb-8 p-5 bg-slate-50 border border-slate-100 rounded-2xl flex items-start gap-4">
                    <div class="text-2xl">⏳</div>
                    <div>
                        <p class="text-xs font-black text-slate-900 uppercase tracking-widest mb-1">Security Notice</p>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">
                            Verification links are valid for <span class="text-slate-900 font-bold">5 minutes</span>. If you don't see the email, check your spam folder or request a new one below.
                        </p>
                    </div>
                </div>

                <!-- Resend Action -->
                <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
                    @csrf
                    <button type="submit" class="w-full bg-brand text-white font-bold py-4 rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-0.5 transition-all active:scale-[0.98] uppercase tracking-widest text-xs">
                        Resend Verification Email
                    </button>
                </form>

                <!-- Footer Sign Out -->
                <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-slate-400 hover:text-brand transition-colors uppercase tracking-widest flex items-center justify-center gap-2 mx-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Use a different account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>