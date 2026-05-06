<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | SkillWeave</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lexend:wght@600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-brand { font-family: 'Lexend', sans-serif; }
        .bg-coursera { background-color: #0056D2; }
        .text-coursera { color: #0056D2; }
        .error-message { color: #dc2626; font-size: 13px; margin-top: 6px; font-weight: 500; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

    <!-- Professional Rectangular Card -->
    <div class="w-full max-w-lg bg-white shadow-2xl border border-slate-200 rounded-lg overflow-hidden">
        
        <!-- Branding Header -->
        <div class="bg-slate-50 border-b border-slate-200 py-5 px-8 flex justify-between items-center">
            <h1 class="font-brand text-coursera text-xl tracking-tight uppercase">SkillWeave</h1>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Account Recovery</span>
        </div>

        <div class="p-8 lg:p-12">
            
            <header class="text-center mb-10">
                <h2 class="text-3xl font-bold text-slate-900 mb-3 tracking-tight">Forgot Password?</h2>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Enter the email address associated with your account. We will send you a secure link to reset your password and resume your learning path.
                </p>
            </header>

            <!-- Laravel Session Status (Styled professionally as a Success Alert) -->
            @if (session('status'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-xs font-bold text-emerald-700 uppercase tracking-tight">
                        {{ session('status') }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Input Section -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="name@example.com" value="{{ old('email') }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#0056D2]/10 focus:border-[#0056D2] outline-none transition-all placeholder:text-slate-300">
                    
                    <!-- Laravel Error Handling -->
                    @error('email') 
                        <p class="error-message flex items-center gap-1.5 ml-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                <!-- Action Button -->
                <button type="submit" class="w-full bg-coursera text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 hover:shadow-blue-400 transition-all active:scale-[0.99] mt-2 uppercase tracking-widest text-xs">
                    Send Reset Link
                </button>
            </form>

            <!-- Footer Return Link -->
            <div class="mt-12 text-center border-t border-slate-50 pt-8">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-coursera transition-colors uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Return to Sign In
                </a>
            </div>
        </div>
        
        <!-- Bottom Decorative Brand Bar -->
        <div class="h-1.5 bg-coursera"></div>
    </div>

</body>
</html>