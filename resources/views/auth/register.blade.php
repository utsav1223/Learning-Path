<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join SkillWeave | Adaptive Learning Paths</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lexend:wght@600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-brand { font-family: 'Lexend', sans-serif; }
        .bg-coursera { background-color: #0056D2; }
        .text-coursera { color: #0056D2; }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; font-weight: 500; min-height: 18px; }

        /* Smooth Goal Selection */
        .goal-chip input[type="radio"]:checked + div {
            border-color: #0056D2;
            background-color: #eff6ff;
            color: #0056D2;
            box-shadow: 0 0 0 1px #0056D2;
        }

        /* Modern Strength Bar Styling */
        .strength-bar-container {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }
        .strength-segment {
            height: 4px;
            flex: 1;
            background-color: #e2e8f0;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-white min-h-screen">

    <div class="flex min-h-screen">
        
        <!-- Left Side: Value Proposition (Hidden on Mobile) -->
        <div class="hidden lg:flex w-1/2 relative bg-slate-900 overflow-hidden items-center justify-center">
            <!-- Background Image with Overlay -->
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80" 
                 alt="Learning" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-br from-[#0056D2]/80 to-transparent"></div>
            
            <div class="relative z-10 px-20 text-center">
                <h1 class="font-brand text-white text-6xl mb-6 tracking-tight">SkillWeave</h1>
                <h3 class="text-blue-100 text-2xl font-medium mb-6 leading-relaxed">
                    Personalized paths for unique progress.
                </h3>
                <p class="text-blue-50/80 text-lg font-light max-w-md mx-auto">
                    We solved the problem of static learning. Our dynamic recommendation engine tailors your experience based on your individual needs and real-time progress.
                </p>
                
                <div class="mt-12 flex justify-center gap-4">
                    <div class="flex -space-x-2">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=A&background=random" alt="">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=B&background=random" alt="">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=C&background=random" alt="">
                    </div>
                    <p class="text-white text-sm flex items-center">Join 10k+ active learners</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md mx-auto">
                
                <!-- Brand Mobile Header -->
                <div class="lg:hidden text-center mb-10">
                    <h1 class="font-brand text-coursera text-4xl tracking-tight uppercase">SkillWeave</h1>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Create Account</h2>
                    <p class="text-slate-500 mt-2 font-medium">Join SkillWeave to unlock your adaptive learning journey.</p>
                </div>

                <form method="POST" action="{{ route('register.post') }}" onsubmit="return validateForm()" class="space-y-5">
                    @csrf

                    <!-- Google OAuth -->
                    <a href="{{ route('google.login') }}" class="block mb-6">
                        <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all active:scale-[0.98] shadow-sm">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="text-sm font-semibold text-slate-700 tracking-wide">Continue with Google</span>
                        </button>
                    </a>

                    <div class="relative flex items-center mb-8">
                        <div class="flex-grow border-t border-slate-100"></div>
                        <span class="flex-shrink mx-4 text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">Or Email</span>
                        <div class="flex-grow border-t border-slate-100"></div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-2 ml-1">Full Name</label>
                            <input type="text" name="name" id="name" placeholder="Alex Rivera" value="{{ old('name') }}"
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#0056D2]/10 focus:border-[#0056D2] outline-none transition-all placeholder:text-slate-300">
                            <p class="error" id="nameError">@error('name') {{ $message }} @enderror</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-2 ml-1">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="alex@example.com" value="{{ old('email') }}"
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#0056D2]/10 focus:border-[#0056D2] outline-none transition-all placeholder:text-slate-300">
                            <p class="error" id="emailError">@error('email') {{ $message }} @enderror</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="relative">
                                <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-2 ml-1">Password</label>
                                <input type="password" name="password" id="password" placeholder="••••••••"
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#0056D2]/10 focus:border-[#0056D2] outline-none transition-all placeholder:text-slate-300">
                                <button type="button" onclick="togglePass('password', 'eye1')" class="absolute right-3 top-[38px] text-slate-400 hover:text-slate-600 transition p-1">
                                    <svg id="eye1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            <div class="relative">
                                <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-2 ml-1">Confirm</label>
                                <input type="password" name="password_confirmation" id="confirmPassword" placeholder="••••••••"
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#0056D2]/10 focus:border-[#0056D2] outline-none transition-all placeholder:text-slate-300">
                                <button type="button" onclick="togglePass('confirmPassword', 'eye2')" class="absolute right-3 top-[38px] text-slate-400 hover:text-slate-600 transition p-1">
                                    <svg id="eye2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Strength Meter Bar -->
                        <div class="mt-1">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-1 ml-1">Password Strength</span>
                                <span id="strengthText" class="text-[10px] font-bold uppercase text-red-500 tracking-[0.1em]">Too Weak</span>
                            </div>
                            <div class="strength-bar-container">
                                <div class="strength-segment" id="seg1"></div>
                                <div class="strength-segment" id="seg2"></div>
                                <div class="strength-segment" id="seg3"></div>
                                <div class="strength-segment" id="seg4"></div>
                            </div>
                            <p class="error" id="passwordError">@error('password') {{ $message }} @enderror</p>
                        </div>
                    </div>

                    <div>
                        <p class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-2 ml-1">Primary Goal</p>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                            <label class="goal-chip cursor-pointer">
                                <input type="radio" name="goal" value="job" class="hidden">
                                <div class="px-2 py-3 border border-slate-200 rounded-xl text-[10px] font-bold transition-all text-center uppercase tracking-widest">Job</div>
                            </label>
                            <label class="goal-chip cursor-pointer">
                                <input type="radio" name="goal" value="exam" class="hidden">
                                <div class="px-2 py-3 border border-slate-200 rounded-xl text-[10px] font-bold transition-all text-center uppercase tracking-widest">Exam</div>
                            </label>
                            <label class="goal-chip cursor-pointer">
                                <input type="radio" name="goal" value="skill" class="hidden">
                                <div class="px-2 py-3 border border-slate-200 rounded-xl text-[10px] font-bold transition-all text-center uppercase tracking-widest">Skill</div>
                            </label>
                            <label class="goal-chip cursor-pointer">
                                <input type="radio" name="goal" value="project" class="hidden">
                                <div class="px-2 py-3 border border-slate-200 rounded-xl text-[10px] font-bold transition-all text-center uppercase tracking-widest">Project</div>
                            </label>
                        </div>
                        <p class="error" id="goalError">@error('goal') {{ $message }} @enderror</p>
                    </div>

                    <button type="submit" class="w-full bg-coursera text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 hover:shadow-blue-400 transition-all active:scale-[0.99] mt-4 uppercase tracking-widest text-xs">
                        Create My Account
                    </button>

                    <p class="text-center text-sm text-slate-500 mt-10 font-medium">
                        Already have an account? <a href="{{ route('login') }}" class="text-coursera font-bold hover:underline underline-offset-4">Log in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById("password");
        const strengthText = document.getElementById("strengthText");
        const errorText = document.getElementById("passwordError");
        const segments = [document.getElementById("seg1"), document.getElementById("seg2"), document.getElementById("seg3"), document.getElementById("seg4")];

        passwordInput.addEventListener("input", function () {
            const val = passwordInput.value;
            let score = 0;
            if (val.length >= 8) score++;
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            segments.forEach((seg, i) => {
                if (i < score) {
                    if (score === 1) { seg.style.backgroundColor = "#ef4444"; strengthText.innerText = "Too Weak"; strengthText.style.color = "#ef4444"; }
                    else if (score === 2) { seg.style.backgroundColor = "#f59e0b"; strengthText.innerText = "Weak"; strengthText.style.color = "#f59e0b"; }
                    else if (score === 3) { seg.style.backgroundColor = "#3b82f6"; strengthText.innerText = "Medium"; strengthText.style.color = "#3b82f6"; }
                    else { seg.style.backgroundColor = "#10b981"; strengthText.innerText = "Strong"; strengthText.style.color = "#10b981"; }
                } else {
                    seg.style.backgroundColor = "#e2e8f0";
                }
            });

            errorText.innerText = (val.length > 0 && val.length < 8) ? "Password must be at least 8 characters" : "";
        });

        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            input.type = input.type === "password" ? "text" : "password";
        }

        function validateForm() {
            let valid = true;
            document.querySelectorAll('.error').forEach(e => e.innerText = '');
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirmPassword').value;
            const goal = document.querySelector('input[name="goal"]:checked');

            if (!name.trim()) { document.getElementById('nameError').innerText = "Name is required"; valid = false; }
            if (!email.includes("@")) { document.getElementById('emailError').innerText = "Valid email is required"; valid = false; }
            if (password.length < 8) { document.getElementById('passwordError').innerText = "Password too short"; valid = false; }
            else if (password !== confirm) { document.getElementById('passwordError').innerText = "Passwords match error"; valid = false; }
            if (!goal) { document.getElementById('goalError').innerText = "Select a goal"; valid = false; }

            return valid;
        }
    </script>
</body>
</html>
