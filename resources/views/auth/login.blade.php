<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-bold uppercase tracking-wider text-slate-500">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl text-sm transition-all outline-none shadow-sm placeholder-slate-400 font-medium"
                    placeholder="name@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-center">
                <label for="password" class="text-xs font-bold uppercase tracking-wider text-slate-500">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl text-sm transition-all outline-none shadow-sm placeholder-slate-400"
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="w-4.5 h-4.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/30">
            <label for="remember_me" class="ml-2 text-sm text-slate-600 font-medium select-none">{{ __('Remember me') }}</label>
        </div>

        <div>
            <button type="submit" class="w-full flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/10 hover:shadow-emerald-600/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 text-sm">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
