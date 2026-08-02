<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
    <div class="flex justify-center">
        @include('frontend.includes.messages')
    </div>

    <div>
        <div class="mb-10 md:grid md:grid-cols-3 md:gap-6">
            <div class="sm:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-xl font-semibold leading-6 text-gray-900">
                        @lang('Change Password')
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        @lang('Use the following form to change your account password!')
                    </p>

                    <div class="pt-4 text-center">
                        <a href="{{ route('frontend.users.profile') }}" class="soh-btn-outline w-full" wire:navigate>
                            @lang('View Profile')
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:col-span-2 md:mt-0">
                <form wire:submit="updatePassword">
                    <div class="soh-card mb-8 p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    @lang('Password')
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    wire:model="password"
                                    type="password"
                                    id="password"
                                    class="soh-input"
                                    required
                                />
                                @error('password')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    @lang('Confirm Password')
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    wire:model="password_confirmation"
                                    type="password"
                                    id="password_confirmation"
                                    class="soh-input"
                                    required
                                />
                                @error('password_confirmation')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 text-end">
                                <button
                                    class="soh-btn-primary w-full"
                                    type="submit"
                                >
                                    @lang('Update Password')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden sm:block" aria-hidden="true">
            <div class="mb-10 py-4">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <div class="mb-10 mt-10 sm:mt-0">
            <div class="grid grid-cols-1 sm:grid-cols-3 sm:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            @lang("Edit Profile")
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            @lang("Update account information.")
                        </p>
                    </div>
                </div>
                <div class="mt-5 sm:col-span-2 md:mt-0">
                    <div class="soh-card mb-8 p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 text-center">
                                <a href="{{ route('frontend.users.profileEdit') }}" class="soh-btn-outline w-full" wire:navigate>
                                    @lang('Edit Profile')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
