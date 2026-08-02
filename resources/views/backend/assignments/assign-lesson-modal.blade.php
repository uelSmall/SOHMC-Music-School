<div class="space-y-4">
    <!-- Open Modal Button -->
    <button
        wire:click="openModal"
        class="px-4 py-2 text-white font-medium rounded-lg transition-colors bg-[#A6128D] hover:bg-[#8C0375]"
    >
        + Assign Lesson
    </button>

    <!-- Modal -->
    @if ($isOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 px-6 py-4 border-b border-gray-200 bg-white">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900">Assign Lesson</h2>
                        <button
                            wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-4 space-y-6">
                    <!-- Lesson Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Lesson</label>
                        <select
                            wire:model="selectedLessonId"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent focus:ring-2"
                            style="--tw-ring-color:#A6128D;"
                        >
                            <option value="">-- Choose a lesson --</option>
                            @foreach ($lessons as $lesson)
                                <option value="{{ $lesson->id }}">
                                    {{ $lesson->title }} ({{ $lesson->teacher->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('selectedLessonId')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date (Optional)</label>
                        <input
                            type="date"
                            wire:model="dueDate"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent focus:ring-2"
                            style="--tw-ring-color:#A6128D;"
                        />
                        @error('dueDate')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Students Multi-Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Select Students</label>
                        <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-300 rounded-lg p-4 bg-gray-50">
                            @forelse ($students as $student)
                                <label class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                    <input
                                        type="checkbox"
                                        wire:model="selectedStudentIds"
                                        value="{{ $student->id }}"
                                        class="w-4 h-4 border-gray-300 rounded focus:ring-2"
                                        style="--tw-ring-color:#A6128D; accent-color:#A6128D;"
                                    />
                                    <span class="text-sm text-gray-700">{{ $student->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $student->email }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No students found.</p>
                            @endforelse
                        </div>
                        @error('selectedStudentIds')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3 justify-end">
                    <button
                        wire:click="closeModal"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="assignLesson"
                        class="px-4 py-2 text-white font-medium rounded-lg transition-colors bg-[#A6128D] hover:bg-[#8C0375]"
                    >
                        Assign to {{ count($selectedStudentIds) }} {{ count($selectedStudentIds) === 1 ? 'Student' : 'Students' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
