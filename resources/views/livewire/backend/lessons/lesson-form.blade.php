<div>
    <form wire:submit="save" class="space-y-6 rounded shadow p-6" enctype="multipart/form-data">
        <h2 class="text-xl font-bold">{{ $lesson ? 'Edit Lesson' : 'Create Lesson' }}</h2>

        <div class="space-y-2">
            <label for="title" class="block font-semibold">Title</label>
            <input
                type="text"
                id="title"
                wire:model.live="title"
                placeholder="Lesson title"
                class="w-full rounded border border-gray-300 px-3 py-2 @error('title') border-red-500 @enderror"
            />
            @error('title')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="description" class="block font-semibold">Description</label>
            <textarea
                id="description"
                wire:model.live="description"
                placeholder="Brief lesson description"
                rows="2"
                class="w-full rounded border border-gray-300 px-3 py-2 @error('description') border-red-500 @enderror"
            ></textarea>
            @error('description')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="content" class="block font-semibold">Content</label>
            <textarea
                id="content"
                wire:model.live="content"
                rows="8"
                class="w-full rounded border border-gray-300 px-3 py-2 font-mono text-sm @error('content') border-red-500 @enderror"
            ></textarea>
            <p class="text-xs text-gray-500">What to enter: lesson notes, key steps, exercises, or take-home tasks.</p>
            @error('content')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            @if (! $isTeacher)
                <div class="space-y-2">
                    <label for="teacher_id" class="block font-semibold">Teacher</label>
                    <select
                        id="teacher_id"
                        wire:model.live="teacher_id"
                        class="w-full rounded border border-gray-300 px-3 py-2 @error('teacher_id') border-red-500 @enderror"
                    >
                        <option value="">Select a teacher</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div class="space-y-2">
                <label for="status" class="block font-semibold">Status</label>
                <select
                    id="status"
                    wire:model.live="status"
                    class="w-full rounded border border-gray-300 px-3 py-2 @error('status') border-red-500 @enderror"
                >
                    @foreach ($statuses as $stat)
                        <option value="{{ $stat->value }}">{{ ucfirst($stat->value) }}</option>
                    @endforeach
                </select>
                @error('status')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label for="published_at" class="block font-semibold">Published At (optional)</label>
                <input
                    type="date"
                    id="published_at"
                    wire:model.live="published_at"
                    class="w-full rounded border border-gray-300 px-3 py-2 @error('published_at') border-red-500 @enderror"
                />
                @error('published_at')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="order" class="block font-semibold">Order</label>
                <input
                    type="number"
                    id="order"
                    wire:model.live="order"
                    placeholder="Auto"
                    min="1"
                    class="w-full rounded border border-gray-300 px-3 py-2 @error('order') border-red-500 @enderror"
                />
                <p class="text-xs text-gray-500">Leave empty to auto-place at the end. If order conflicts, lessons are re-ordered automatically.</p>
                @error('order')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label for="file_path" class="block font-semibold">Upload File (optional)</label>
            <input
                type="file"
                id="file_path"
                wire:model="file_path"
                class="w-full rounded border border-gray-300 px-3 py-2 file:mr-4 file:rounded file:border-0 file:bg-[#A6128D] file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-[#8C0375] @error('file_path') border-red-500 @enderror"
            />
            @if ($lesson && $lesson->file_path)
                <p class="text-sm text-gray-600">Current file: <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="text-[#A6128D] hover:underline">View</a></p>
            @endif
            @error('file_path')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="student_ids" class="block font-semibold">Assign Students (optional)</label>
            <div class="max-h-40 overflow-y-auto border border-gray-300 rounded p-3 space-y-2">
                @foreach ($students as $student)
                    <label class="flex items-center space-x-2">
                        <input
                            type="checkbox"
                            value="{{ $student->id }}"
                            wire:model.live="student_ids"
                            class="rounded"
                        />
                        <span>{{ $student->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('student_ids')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex gap-4 pt-4">
            <button
                type="submit"
                class="rounded bg-[#A6128D] px-6 py-2 text-white hover:bg-[#8C0375]"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
            >
                <span wire:loading.remove>{{ $lesson ? 'Update' : 'Create' }} Lesson</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route($routePrefix.'.lessons.index') }}" class="rounded bg-gray-300 px-6 py-2 hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

