<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Create Your Account')"
        :description="__('Join SOHMC and start your musical journey')"
    />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-5">
        {{-- Name --}}
        <div class="flex flex-col gap-1.5">
            <label for="name" class="text-sm font-semibold text-gray-800">Full Name</label>
            <input
                wire:model="name"
                type="text"
                id="name"
                required
                placeholder="Your full name"
                class="rounded-xl border border-[#A6128D]/20 bg-[#F2F2F2] px-4 py-3 text-sm text-[#0D0D0D] outline-none transition-all duration-200 focus:border-[#A6128D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]"
            />
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

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

        {{-- Role --}}
        <div class="flex flex-col gap-1.5">
            <label for="role" class="text-sm font-semibold text-gray-800">I am a</label>
            <select
                wire:model="role"
                id="role"
                required
                class="rounded-xl border border-[#A6128D]/20 bg-[#F2F2F2] px-4 py-3 text-sm text-gray-700 outline-none transition-all duration-200 focus:border-[#A6128D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]"
            >
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="parent">Parent</option>
            </select>
            @error('role')
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
                placeholder="Create a password"
                class="rounded-xl border border-[#A6128D]/20 bg-[#F2F2F2] px-4 py-3 text-sm text-[#0D0D0D] outline-none transition-all duration-200 focus:border-[#A6128D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]"
            />
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="flex flex-col gap-1.5">
            <label for="password_confirmation" class="text-sm font-semibold text-gray-800">Confirm Password</label>
            <input
                wire:model="password_confirmation"
                type="password"
                id="password_confirmation"
                required
                placeholder="Confirm your password"
                class="rounded-xl border border-[#A6128D]/20 bg-[#F2F2F2] px-4 py-3 text-sm text-[#0D0D0D] outline-none transition-all duration-200 focus:border-[#A6128D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]"
            />
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-[#A6128D] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#A6128D]/25 transition-all duration-200 hover:bg-[#8C0375] hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#D991CD] focus:ring-offset-2"
        >
            {{ __("Create account") }}
        </button>
    </form>

    <p class="text-center text-sm text-gray-500">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-[#A6128D] hover:text-[#8C0375] transition-colors">
            {{ __('Log in') }}
        </a>
    </p>
</div>
