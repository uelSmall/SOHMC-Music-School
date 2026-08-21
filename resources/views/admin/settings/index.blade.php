<x-layouts.admin :title="'Settings'">
    <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-sm text-gray-500">Manage your site settings.</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.store') }}" class="space-y-6">
            @csrf

            @foreach($settingFields as $group => $config)
                <div class="soh-card p-6">
                    <h2 class="mb-1 text-lg font-semibold text-gray-900">{{ $config['title'] }}</h2>
                    <p class="mb-5 text-sm text-gray-500">{{ $config['desc'] }}</p>

                    <div class="space-y-5">
                        @foreach($config['elements'] as $field)
                            @php $value = $settings[$field['name']] ?? $field['value'] ?? ''; @endphp

                            @if($field['type'] === 'textarea')
                                <div>
                                    <label for="{{ $field['name'] }}" class="mb-1 block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                                    <textarea name="{{ $field['name'] }}" id="{{ $field['name'] }}" rows="4" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-mono transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">{{ old($field['name'], $value) }}</textarea>
                                    @if(!empty($field['help'])) <p class="mt-1 text-xs text-gray-400">{{ $field['help'] }}</p> @endif
                                    @error($field['name']) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                            @elseif($field['type'] === 'checkbox')
                                <div class="flex items-center gap-3">
                                    <input type="hidden" name="{{ $field['name'] }}" value="0" />
                                    <input type="checkbox" name="{{ $field['name'] }}" id="{{ $field['name'] }}" value="1" {{ old($field['name'], $value) == '1' ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-[#A6128D] focus:ring-[#A6128D]/20" />
                                    <label for="{{ $field['name'] }}" class="text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                                </div>

                            @elseif($field['type'] === 'radio' && !empty($field['options']))
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                                    <div class="flex gap-4">
                                        @foreach($field['options'] as $optVal => $optLabel)
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="{{ $field['name'] }}" value="{{ $optVal }}" {{ old($field['name'], $value) == $optVal ? 'checked' : '' }} class="border-gray-300 text-[#A6128D] focus:ring-[#A6128D]/20" />
                                                <span class="text-sm text-gray-700">{{ $optLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @if(!empty($field['help'])) <p class="mt-1 text-xs text-gray-400">{{ $field['help'] }}</p> @endif
                                </div>

                            @else
                                <div>
                                    <label for="{{ $field['name'] }}" class="mb-1 block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                                    <input type="{{ $field['type'] === 'email' ? 'email' : 'text' }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}" value="{{ old($field['name'], $value) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                                    @if(!empty($field['help'])) <p class="mt-1 text-xs text-gray-400">{{ $field['help'] }}</p> @endif
                                    @error($field['name']) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" class="soh-btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
