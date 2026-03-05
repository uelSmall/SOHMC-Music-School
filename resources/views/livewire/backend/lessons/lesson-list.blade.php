<div>
    @if ($routePrefix === 'teacher')
        <section class="soh-card space-y-4 p-5 sm:p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex gap-2">
                    <button wire:click="sort('title')" class="rounded-md border border-[color:var(--soh-gray)] bg-[var(--soh-surface)] px-3 py-2 text-xs font-semibold text-[color:var(--soh-black)]">
                        Title {{ $sortBy === 'title' ? ($sortDir === 'asc' ? '↑' : '↓') : '' }}
                    </button>
                    <button wire:click="sort('created_at')" class="rounded-md border border-[color:var(--soh-gray)] bg-[var(--soh-surface)] px-3 py-2 text-xs font-semibold text-[color:var(--soh-black)]">
                        Created {{ $sortBy === 'created_at' ? ($sortDir === 'asc' ? '↑' : '↓') : '' }}
                    </button>
                </div>

                <a href="{{ route($routePrefix.'.lessons.create') }}" class="soh-btn-primary">
                    Create Lesson
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search your lessons..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-[color:var(--soh-purple)]"
                />

                <select wire:model.live="statusFilter" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-[color:var(--soh-purple)]">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
        </section>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->lessons as $lesson)
                <article class="soh-card relative overflow-hidden border-[color:rgb(217_145_205_/_0.35)] p-0 shadow-sm">
                    <span class="absolute top-0 bottom-0 left-0 w-1.5 bg-[var(--soh-gray)]"></span>
                    <div class="min-h-[88px] border-b border-[color:var(--soh-gray)] bg-[color:rgb(140_3_117_/_0.92)] p-4 sm:p-5">
                        <h3 class="line-clamp-2 text-lg font-semibold leading-snug text-white sm:text-xl">{{ $lesson->title }}</h3>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-white/80">{{ ucfirst($lesson->status->value) }}</p>
                    </div>

                    <div class="space-y-3 p-5 sm:p-6">
                        <p class="text-sm text-gray-700 line-clamp-3">{{ $lesson->description ?? 'No description added yet.' }}</p>

                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>Students: <strong class="text-black">{{ $lesson->students->count() }}</strong></span>
                            <span>Created: <strong class="text-black">{{ $lesson->created_at->format('M d, Y') }}</strong></span>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 border-t border-gray-200 pt-3">
                            <a href="{{ route($routePrefix.'.lessons.show', $lesson) }}" class="soh-btn-outline inline-flex h-9 items-center px-3.5 text-xs">
                                View
                            </a>

                            <a href="{{ route($routePrefix.'.lessons.edit', $lesson) }}" class="soh-btn-primary inline-flex h-9 items-center px-3.5 text-xs">
                                Edit
                            </a>

                            @if ($lesson->file_path)
                                <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="soh-btn-outline inline-flex h-9 items-center px-3.5 text-xs">
                                    View Material
                                </a>
                            @endif

                            <button wire:click="delete({{ $lesson->id }})" wire:confirm="Delete this lesson?" class="inline-flex h-9 items-center rounded-md border border-red-300 px-3.5 text-xs font-semibold text-red-700 transition-all duration-200 hover:bg-red-50">
                                Delete
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-[color:var(--soh-gray)] bg-[var(--soh-surface)] px-6 py-12 text-center">
                    <p class="text-lg font-semibold text-black">No lessons found</p>
                    <p class="mt-1 text-sm text-gray-600">Create your first lesson to get started.</p>
                    <a href="{{ route($routePrefix.'.lessons.create') }}" class="soh-btn-primary mt-4 inline-flex">Create Lesson</a>
                </div>
            @endforelse
        </div>

        @if ($this->lessons->count())
            <div class="mt-6">
                {{ $this->lessons->links() }}
            </div>
        @endif
    @else
        <div class="mb-6 space-y-4">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <h1 class="text-2xl font-bold">Lessons</h1>
                <a href="{{ route($routePrefix.'.lessons.create') }}" class="soh-btn-primary">
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
                                        <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="soh-link text-sm">
                                            View File
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">No file</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($lesson->students->count() > 0)
                                        <span class="inline-block rounded border border-[color:var(--soh-gray)] bg-[var(--soh-surface)] px-2 py-1 text-[color:var(--soh-purple)]">
                                            {{ $lesson->students->count() }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $lesson->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route($routePrefix.'.lessons.show', $lesson) }}" class="soh-link mr-3">
                                        View
                                    </a>
                                    <a href="{{ route($routePrefix.'.lessons.edit', $lesson) }}" class="soh-link">
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
                <p class="text-gray-500">No lessons found. <a href="{{ route($routePrefix.'.lessons.create') }}" class="soh-link">Create one</a></p>
            </div>
        @endif
    @endif
</div>
