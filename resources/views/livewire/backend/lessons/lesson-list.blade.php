<div>
    <div class="mb-6 space-y-4">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold">Lessons</h1>
            <a href="{{ route($routePrefix.'.lessons.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Create Lesson
            </a>
        </div>

        <div class="flex flex-col gap-4 md:flex-row">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search lessons..."
                class="flex-1 rounded border border-gray-300 px-3 py-2"
            />
            <select wire:model.live="statusFilter" class="rounded border border-gray-300 px-3 py-2">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
            </select>
        </div>
    </div>

    @if ($this->lessons->count())
        <div class="overflow-x-auto rounded shadow">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sort('title')" class="font-semibold hover:underline">
                                Title
                                @if ($sortBy === 'title')
                                    <span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">Teacher</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">File</th>
                        <th class="px-4 py-3 text-left">Students</th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sort('created_at')" class="font-semibold hover:underline">
                                Created
                                @if ($sortBy === 'created_at')
                                    <span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($this->lessons as $lesson)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $lesson->title }}</td>
                            <td class="px-4 py-3">{{ $lesson->teacher?->name ?? 'Unassigned' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block rounded px-2 py-1 text-xs font-semibold
                                    @if ($lesson->status->value === 'published') bg-green-100 text-green-800
                                    @elseif ($lesson->status->value === 'draft') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif
                                ">
                                    {{ ucfirst($lesson->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($lesson->file_path)
                                    <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                        View File
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">No file</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($lesson->students->count() > 0)
                                    <span class="inline-block rounded bg-blue-100 px-2 py-1 text-blue-800">
                                        {{ $lesson->students->count() }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $lesson->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route($routePrefix.'.lessons.edit', $lesson) }}" class="text-blue-600 hover:underline">
                                    Edit
                                </a>
                                <button wire:click="delete({{ $lesson->id }})" wire:confirm="Delete this lesson?" class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->lessons->links() }}
        </div>
    @else
        <div class="rounded bg-gray-50 p-8 text-center">
            <p class="text-gray-500">No lessons found. <a href="{{ route($routePrefix.'.lessons.create') }}" class="text-blue-600 hover:underline">Create one</a></p>
        </div>
    @endif
</div>
