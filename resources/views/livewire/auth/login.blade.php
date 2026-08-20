<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Welcome Back')"
        :description="__('Sign in to access your dashboard')"
    />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-5">
        {{-- Email --}}
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-semibold text-gray-800">Email Address</label>
            <input
                wire:model="email"
                type="email"
                id="email"
                required
                placeholder="you@example.com"
                class="rounded-xl border border-[#A6128D]/20 bg-[#F2F2F2] px-4 py-3 text-sm text-[#0D0D0D] outline-none transition-all duration-200 focus:border-[#A6128D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]"
            />
            @error('email')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="flex flex-col gap-1.5">
            <label for="password" class="text-sm font-semibold text-gray-800">Password</label>
            <input
                wire:model="password"
                type="password"
                id="password"
                required
                placeholder="Enter your password"
                class="rounded-xl border border-[#A6128D]/20 bg-[#F2F2F2] px-4 py-3 text-sm text-[#0D0D0D] outline-none transition-all duration-200 focus:border-[#A6128D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]"
            />
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-[#A6128D] focus:ring-[#D991CD]">
                <span class="text-sm text-gray-600">Remember me</span>
            </label>

            @if (Route::has("password.request"))
                <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-semibold text-[#A6128D] hover:text-[#8C0375] transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-[#A6128D] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#A6128D]/25 transition-all duration-200 hover:bg-[#8C0375] hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#D991CD] focus:ring-offset-2"
        >
            {{ __("Log in") }}
        </button>
    </form>

    @if (Route::has("register"))
        <p class="text-center text-sm text-gray-500">
            {{ __('Don\'t have an account?') }}
            <a href="{{ route('register') }}" wire:navigate class="font-semibold text-[#A6128D] hover:text-[#8C0375] transition-colors">
                {{ __("Sign up") }}
            </a>
        </p>
    @endif
</div>
