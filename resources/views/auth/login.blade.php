<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SkillWeave Adaptive Learning</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lexend:wght@600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-brand { font-family: 'Lexend', sans-serif; }
        .bg-coursera { background-color: #0056D2; }
        .text-coursera { color: #0056D2; }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; font-weight: 500; min-height: 18px; }
    </style>
</head>
<body class="bg-white min-h-screen">

    <div class="flex min-h-screen">
        
        <!-- Left Side: Value Proposition (Hidden on Mobile) -->
        <div class="hidden lg:flex w-1/2 relative bg-slate-900 overflow-hidden items-center justify-center">
            <!-- Background Image with Overlay -->
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80" 
                 alt="Learning" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-br from-[#0056D2]/80 to-transparent"></div>
            
            <div class="relative z-10 px-20 text-center">
                <h1 class="font-brand text-white text-6xl mb-6 tracking-tight">SkillWeave</h1>
                <h3 class="text-blue-100 text-2xl font-medium mb-6 leading-relaxed">
                    Personalized paths that evolve with your progress.
                </h3>
                <p class="text-blue-50/80 text-lg font-light max-w-md mx-auto">
                    We solve the static learning problem by dynamically tailoring resources to your unique needs.
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

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md">
                
                <!-- Brand Mobile Header -->
                <div class="lg:hidden text-center mb-10">
                    <h1 class="font-brand text-coursera text-4xl tracking-tight uppercase">SkillWeave</h1>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Welcome Back</h2>
                    <p class="text-slate-500 mt-2 font-medium">Please enter your details to sign in.</p>
                </div>

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
                    <span class="flex-shrink mx-4 text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">Or Email Login</span>
                    <div class="flex-grow border-t border-slate-100"></div>
                </div>

                <form method="POST" action="{{ route('login.post') }}" onsubmit="return validateLogin()" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" id="email" placeholder="name@example.com" value="{{ old('email') }}"
                               class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#0056D2]/10 focus:border-[#0056D2] outline-none transition-all placeholder:text-slate-300">
                        <p class="error" id="emailError">@error('email') {{ $message }} @enderror</p>
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <div class="flex justify-between items-center mb-2 ml-1">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400">Password</label>
                            <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-coursera hover:underline uppercase tracking-wider">Forgot Password?</a>
                        </div>
                        <div class="relative group">
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#0056D2]/10 focus:border-[#0056D2] outline-none transition-all placeholder:text-slate-300">
                            <button type="button" onclick="togglePass()" class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600 transition p-1">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <p class="error" id="passwordError">@error('password') {{ $message }} @enderror</p>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center ml-1">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-coursera border-slate-300 rounded focus:ring-coursera transition cursor-pointer">
                        <label for="remember" class="ml-3 text-xs font-semibold text-slate-500 cursor-pointer select-none">Keep me logged in</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="w-full bg-coursera text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 hover:shadow-blue-400 transition-all active:scale-[0.99] mt-2 uppercase tracking-widest text-xs">
                        Sign In to Account
                    </button>

                    <p class="text-center text-sm text-slate-500 mt-10 font-medium">
                        Don't have an account? <a href="{{ route('register') }}" class="text-coursera font-bold hover:underline underline-offset-4">Join Free</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = "password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        function validateLogin() {
            let valid = true;
            document.getElementById('emailError').innerText = '';
            document.getElementById('passwordError').innerText = '';

            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;

            if (!email.includes('@')) {
                document.getElementById('emailError').innerText = "Please enter a valid email address";
                valid = false;
            }

            if (password.length < 6) {
                document.getElementById('passwordError').innerText = "Password must be at least 6 characters";
                valid = false;
            }

            return valid;
        }
    </script>
</body>
</html>