<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | SkillWeave</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F8FAFC;
            background-image: radial-gradient(at 100% 100%, rgba(0, 86, 210, 0.05) 0, transparent 50%), 
                              radial-gradient(at 0% 0%, rgba(0, 86, 210, 0.03) 0, transparent 50%);
        }
        .bg-brand { background-color: #0056D2; }
        .text-brand { color: #0056D2; }
        
        .error-message { 
            animation: slideIn 0.2s ease-out;
            color: #ef4444; 
            font-size: 12px; 
            margin-top: 6px; 
            font-weight: 600; 
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-[500px] bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] border border-slate-100 overflow-hidden relative">
        
        <!-- Top accent bar -->
        <div class="h-1.5 bg-brand w-full"></div>

        <div class="p-8 md:p-12">
            
            <!-- Icon Header -->
            <div class="flex justify-center mb-8">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <header class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">New Password</h2>
                <p class="text-slate-500 text-sm leading-relaxed max-w-[320px] mx-auto font-medium">
                    Choose a strong, unique password to secure your SkillWeave progress.
                </p>
            </header>

            <!-- Success Alert -->
            @if (session('status'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3">
                    <div class="bg-emerald-500 rounded-full p-1">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-tight">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" onsubmit="return validateResetForm()" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Read-only Email -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Account Email</label>
                    <input type="email" name="email" id="email" value="{{ request()->email }}" readonly
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-400 font-bold outline-none cursor-not-allowed text-sm">
                </div>

                <!-- Password Fields -->
                <div class="space-y-5">
                    <div class="relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 text-xs">Create New Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" required
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-brand focus:bg-white outline-none transition-all pr-12 text-sm">
                        <button type="button" onclick="togglePass('password', 'eye1')" class="absolute right-4 top-[38px] text-slate-400 hover:text-brand transition-colors">
                            <svg id="eye1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>

                    <div class="relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 text-xs">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="confirmPassword" placeholder="••••••••" required
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-brand focus:bg-white outline-none transition-all pr-12 text-sm">
                        <button type="button" onclick="togglePass('confirmPassword', 'eye2')" class="absolute right-4 top-[38px] text-slate-400 hover:text-brand transition-colors">
                            <svg id="eye2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Modern Strength Meter -->
                <div class="px-1">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password Strength</span>
                        <span id="strengthText" class="text-[10px] font-black uppercase tracking-widest text-red-500">Weak</span>
                    </div>
                    <div class="flex gap-1.5" id="strengthBar">
                        <div class="h-1.5 flex-1 rounded-full bg-slate-100 transition-all duration-300"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-slate-100 transition-all duration-300"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-slate-100 transition-all duration-300"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-slate-100 transition-all duration-300"></div>
                    </div>
                </div>

                <div id="jsPasswordError" class="error-message px-1"></div>

                <button type="submit" class="w-full bg-brand text-white font-bold py-4.5 rounded-2xl shadow-xl shadow-blue-100 hover:shadow-blue-200 hover:-translate-y-0.5 transition-all active:scale-[0.98] uppercase tracking-widest text-xs py-4">
                    Update Password
                </button>
            </form>

            <div class="mt-10 text-center">
                <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 text-[11px] font-black text-slate-400 hover:text-brand transition-all uppercase tracking-widest">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Login
                </a>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="bg-slate-50 py-4 px-8 border-t border-slate-100 flex justify-between items-center">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Secure Account Update</span>
            <div class="flex gap-1">
                <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById("password");
        const strengthText = document.getElementById("strengthText");
        const strengthBars = document.getElementById("strengthBar").children;
        const jsError = document.getElementById("jsPasswordError");

        passwordInput.addEventListener("input", function () {
            const password = passwordInput.value;
            let score = 0;

            if (password.length >= 8) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            // Update Bars and Text
            const colors = ['bg-red-400', 'bg-orange-400', 'bg-amber-400', 'bg-emerald-500'];
            const labels = ['Weak', 'Fair', 'Good', 'Strong'];
            const textColors = ['text-red-500', 'text-orange-500', 'text-amber-500', 'text-emerald-500'];

            for (let i = 0; i < 4; i++) {
                strengthBars[i].className = 'h-1.5 flex-1 rounded-full transition-all duration-300 ' + (i < score ? colors[score-1] : 'bg-slate-100');
            }
            
            strengthText.innerText = score > 0 ? labels[score-1] : 'Weak';
            strengthText.className = 'text-[10px] font-black uppercase tracking-widest ' + (score > 0 ? textColors[score-1] : 'text-red-500');

            jsError.innerText = (password.length > 0 && password.length < 8) ? "Password must be at least 8 characters" : "";
        });

        function validateResetForm() {
            const password = passwordInput.value;
            const confirm = document.getElementById('confirmPassword').value;

            if (password.length < 8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
                jsError.innerText = "Use 8+ chars with Uppercase & Numbers";
                return false;
            }
            if (password !== confirm) {
                jsError.innerText = "Passwords do not match";
                return false;
            }
            return true;
        }

        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = "password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>
</html>