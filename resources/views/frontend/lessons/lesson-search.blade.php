<div class="space-y-6">
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
            {{ session()->get('message') }}
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <!-- Search Input -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Lessons</label>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by title or description..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <!-- Filter Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Instrument Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instrument</label>
                <select
                    wire:model.live="filterInstrument"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select
                        wire:model.live="filterStatus"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                    class="px-4 py-3 font-medium text-sm border-b-2 transition-colors {{ $tab === $tabKey ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}"
                >
                    {{ $tabLabel }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Lessons Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($lessons as $lesson)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <!-- Lesson Header -->
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="font-semibold text-gray-900 line-clamp-2">{{ $lesson->title }}</h3>
                    @if ($lesson->instrument)
                        <p class="text-xs text-gray-600 mt-1">📚 {{ ucfirst($lesson->instrument) }}</p>
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
                            <div class="mt-3 pt-3 border-t border-gray-200">
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
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No lessons found. Try adjusting your filters.</p>
            </div>
        @endforelse
    </div>
</div>
