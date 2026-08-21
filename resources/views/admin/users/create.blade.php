<x-layouts.admin :title="'Create User'">
    <div class="mx-auto max-w-2xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create User</h1>
            <p class="text-sm text-gray-500">Add a new user account.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="soh-card space-y-6 p-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="first_name" class="mb-1 block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="last_name" class="mb-1 block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Roles</label>
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 transition hover:border-[#A6128D]/50 hover:bg-[#A6128D]/5">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-[#A6128D] focus:ring-[#A6128D]/20" />
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($role->name) }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.users.index') }}" class="soh-btn-outline">Cancel</a>
                <button type="submit" class="soh-btn-primary">Create User</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
