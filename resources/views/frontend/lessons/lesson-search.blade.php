<div class="space-y-6">
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session()->get('message') }}
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="soh-card space-y-4 p-6">
        <!-- Search Input -->
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Search Lessons</label>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by title or description..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2"
                style="--tw-ring-color:#6A1B9A;"
            />
        </div>

        <!-- Filter Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Instrument Filter -->
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Instrument</label>
                <select
                    wire:model.live="filterInstrument"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2"
                    style="--tw-ring-color:#6A1B9A;"
                >
                    <option value="">All Instruments</option>
                    @foreach ($instruments as $instrument)
                        <option value="{{ $instrument }}">
                            {{ ucfirst($instrument) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter (for assigned tab) -->
            @if ($tab !== 'all')
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                    <select
                        wire:model.live="filterStatus"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2"
                        style="--tw-ring-color:#6A1B9A;"
                    >
                        <option value="">All Statuses</option>
                        @foreach ($assignmentStatuses as $statusKey => $statusLabel)
                            <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <div class="flex gap-8">
            @foreach (['all' => 'All Lessons', 'assigned' => 'Assigned', 'completed' => 'Completed'] as $tabKey => $tabLabel)
                <button
                    wire:click="$set('tab', '{{ $tabKey }}')"
                    class="border-b-2 px-4 py-3 text-sm font-medium transition-colors {{ $tab === $tabKey ? 'text-purple-700' : 'border-transparent text-gray-600 hover:text-gray-900' }}"
                    style="{{ $tab === $tabKey ? 'border-color:#6A1B9A;' : '' }}"
                >
                    {{ $tabLabel }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Lessons Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($lessons as $lesson)
            <div class="soh-card overflow-hidden transition hover:-translate-y-0.5 hover:shadow-lg">
                <!-- Lesson Header -->
                <div class="p-4" style="background:linear-gradient(90deg,#f4ebfb,#f8f8fb);">
                    <h3 class="line-clamp-2 font-semibold text-black">{{ $lesson->title }}</h3>
                    @if ($lesson->instrument)
                        <p class="mt-1 text-xs text-gray-600">📚 {{ ucfirst($lesson->instrument) }}</p>
                    @endif
                </div>

                <!-- Lesson Body -->
                <div class="p-4 space-y-3">
                    <!-- Teacher -->
                    <p class="text-sm text-gray-600">
                        <strong>Teacher:</strong> {{ $lesson->teacher->name ?? 'N/A' }}
                    </p>

                    <!-- Description -->
                    <p class="text-sm text-gray-700 line-clamp-3">{{ $lesson->description }}</p>

                    <!-- Assignment Status Badge (if applicable) -->
                    @if (auth()->user()->hasRole('student'))
                        @php
                            $assignment = $lesson->assignedStudents
                                ->where('student_id', auth()->id())
                                ->first();
                        @endphp

                        @if ($assignment)
                            <div class="mt-3 border-t border-gray-200 pt-3">
                                @php
                                    $statusColors = [
                                        'assigned' => 'gray',
                                        'started' => 'blue',
                                        'in_progress' => 'yellow',
                                        'completed' => 'green',
                                    ];
                                    $color = $statusColors[$assignment->status->value] ?? 'gray';
                                @endphp
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $color === 'gray' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $color === 'blue' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $color === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $assignment->status->value)) }}
                                </span>

                                @if ($assignment->due_date)
                                    <p class="text-xs text-gray-600 mt-2">
                                        Due: <strong>{{ $assignment->due_date->format('M d, Y') }}</strong>
                                    </p>
                                @endif
                            </div>
                        @endif
                    @endif

                    <div class="mt-4 flex items-center gap-2 border-t border-gray-200 pt-3">
                        <a
                            href="{{ route('lessons.show', $lesson) }}"
                            class="inline-flex items-center rounded-md bg-purple-700 px-3 py-2 text-xs font-semibold text-white hover:bg-purple-800"
                        >
                            View Lesson
                        </a>

                        @if ($lesson->file_path)
                            <a
                                href="{{ route('lessons.download', $lesson) }}"
                                class="inline-flex items-center rounded-md border border-purple-700 px-3 py-2 text-xs font-semibold text-purple-700 hover:bg-purple-50"
                            >
                                Download Material
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No lessons found. Try adjusting your filters.</p>
            </div>
        @endforelse
    </div>
</div>
