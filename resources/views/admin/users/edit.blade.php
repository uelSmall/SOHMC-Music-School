<x-layouts.admin :title="'Edit User'">
    <div class="mx-auto max-w-2xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="text-sm text-gray-500">Update {{ $user->name }}&apos;s account.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="soh-card space-y-6 p-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="first_name" class="mb-1 block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', explode(' ', $user->name)[0] ?? '') }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="last_name" class="mb-1 block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', explode(' ', $user->name)[1] ?? '') }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">New Password <span class="text-gray-400">(leave blank to keep current)</span></label>
                    <input type="password" name="password" id="password" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Roles</label>
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 transition hover:border-[#A6128D]/50 hover:bg-[#A6128D]/5">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }} class="rounded border-gray-300 text-[#A6128D] focus:ring-[#A6128D]/20" />
                            <span>
                                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ \App\Models\Role::badgeFor($role->name) }}">{{ \App\Models\Role::labelFor($role->name) }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('roles') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Parents / Guardians <span class="text-xs font-normal text-gray-400">(for student accounts)</span></label>
                @if($parents->isEmpty())
                    <p class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-500">No parent accounts yet. Create a parent account first, then link it here.</p>
                @else
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach($parents as $parent)
                            <label class="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 transition hover:border-[#A6128D]/50 hover:bg-[#A6128D]/5">
                                <input type="checkbox" name="parents[]" value="{{ $parent->id }}" {{ in_array($parent->id, old('parents', $userParents)) ? 'checked' : '' }} class="rounded border-gray-300 text-[#A6128D] focus:ring-[#A6128D]/20" />
                                <span class="text-sm">
                                    <span class="block font-medium text-gray-800">{{ $parent->name }}</span>
                                    <span class="block text-xs text-gray-500">{{ $parent->email }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
                @error('parents') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.users.index') }}" class="soh-btn-outline">Cancel</a>
                <button type="submit" class="soh-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
