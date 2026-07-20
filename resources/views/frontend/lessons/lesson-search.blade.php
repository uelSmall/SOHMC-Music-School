<div class="mx-auto max-w-7xl space-y-7">
    @php
        $isParentUser = auth()->user()->hasRole('parent');
    @endphp

    @if (session()->has('message'))
        <div class="rounded-xl border p-4 text-sm font-medium" style="border-color:#D991CD; background:#F2F2F2; color:#0D0D0D;">
            {{ session()->get('message') }}
        </div>
    @endif

    @unless ($isParentUser)
        <section class="soh-card space-y-4 p-5 sm:p-6 shadow-sm">
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Search Lessons</label>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search by title or description..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2"
                    style="--tw-ring-color:#A6128D;"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Instrument</label>
                    <select
                        wire:model.live="filterInstrument"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2"
                        style="--tw-ring-color:#A6128D;"
                    >
                        <option value="">All Instruments</option>
                        @foreach ($instruments as $instrument)
                            <option value="{{ $instrument }}">{{ ucfirst($instrument) }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($tab !== 'all')
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Status</label>
                        <select
                            wire:model.live="filterStatus"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2"
                            style="--tw-ring-color:#A6128D;"
                        >
                            <option value="">All Statuses</option>
                            @foreach ($assignmentStatuses as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </section>
    @endunless

    @unless ($isParentUser)
        <div class="border-b border-gray-200">
            <div class="flex gap-6 sm:gap-8">
                @foreach (['all' => 'All Lessons', 'assigned' => 'Assigned', 'completed' => 'Completed'] as $tabKey => $tabLabel)
                    <button
                        wire:click="$set('tab', '{{ $tabKey }}')"
                        class="border-b-2 px-2 py-2.5 text-sm font-semibold transition-all duration-200 {{ $tab === $tabKey ? '' : 'border-transparent text-gray-600 hover:text-gray-900' }}"
                        style="{{ $tab === $tabKey ? 'border-color:#A6128D; color:#A6128D;' : '' }}"
                    >
                        {{ $tabLabel }}
                    </button>
                @endforeach
            </div>
        </div>
    @endunless

    <div
        wire:loading.flex
        wire:target="search,filterInstrument,filterStatus,tab"
        class="items-center justify-center rounded-xl border px-4 py-3 text-sm font-medium"
        style="border-color:#D991CD; background:#F2F2F2; color:#0D0D0D;"
    >
        Updating lessons...
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3" wire:loading.remove wire:target="search,filterInstrument,filterStatus,tab">
        @forelse ($lessons as $lesson)
            @php
                $assignment = null;
                $leftBorderColor = '#D991CD';

                if (auth()->user()->hasRole('student')) {
                    $assignment = $lesson->assignedStudents->where('student_id', auth()->id())->first();

                    $leftBorderColor = match($assignment?->status?->value) {
                        'started' => '#A6128D',
                        'in_progress' => '#D991CD',
                        'completed' => '#8C0375',
                        'assigned' => '#D991CD',
                        default => '#D991CD',
                    };
                }

                $statusValue = $assignment?->status?->value;
                $progress = $this->progressPercentage($statusValue);

                $statusClass = match($statusValue) {
                    'started' => 'bg-[#D991CD] text-[#0D0D0D] ring-[#D991CD]',
                    'in_progress' => 'bg-[#A6128D] text-white ring-[#A6128D]',
                    'completed' => 'bg-[#8C0375] text-white ring-[#8C0375]',
                    default => 'bg-[#F2F2F2] text-[#0D0D0D] ring-[#D991CD]',
                };
            @endphp

            <article class="soh-card relative overflow-hidden p-0 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:scale-[1.01] hover:shadow-md" style="border-color:rgba(217,145,205,0.35);">
                <span class="absolute top-0 bottom-0 left-0 w-1.5" style="background:{{ $leftBorderColor }};"></span>
                <div class="min-h-[88px] p-4 sm:p-5" style="background:rgba(140,3,117,0.9); border-bottom:1px solid #D991CD;">
                    <h3 class="line-clamp-2 text-lg font-semibold leading-snug text-white sm:text-xl">{{ $lesson->title }}</h3>
                    @php
                        $categoryLabel = $lesson->instrument
                            ? ucfirst($lesson->instrument)
                            : (str_contains(strtolower($lesson->title), 'theory') ? 'Music Theory' : 'General');
                    @endphp
                    <p class="mt-1 text-sm font-medium" style="color:rgba(242,242,242,0.88);">🎼 {{ $categoryLabel }}</p>
                </div>

                <div class="space-y-3 p-5 sm:p-6">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold text-black">Teacher:</span> {{ $lesson->teacher->name ?? 'N/A' }}
                    </p>

                    <p class="line-clamp-3 text-sm leading-relaxed text-gray-700">{{ $lesson->description }}</p>

                    @if (auth()->user()->hasRole('student'))
                        <div class="min-h-[120px] space-y-3 border-t border-gray-200 pt-3">
                            @if ($assignment)
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Status</span>
                                    <span class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $assignment->status->value)) }}
                                    </span>
                                </div>

                                <div>
                                    <div class="h-2 w-full overflow-hidden rounded-full" style="background:#F2F2F2;">
                                        <div class="h-full rounded-full transition-all duration-300" style="width: {{ $progress }}%; background:linear-gradient(90deg,#D991CD 0%,#A6128D 100%);"></div>
                                    </div>
                                    <p class="mt-1 text-[11px] font-medium text-gray-600">Progress: {{ $progress }}%</p>
                                </div>

                                @if ($assignment->due_date)
                                    <p class="text-xs text-gray-600">Due: <span class="font-semibold text-black">{{ $assignment->due_date->format('M d, Y') }}</span></p>
                                @else
                                    <p class="text-xs text-gray-500">Due date not set</p>
                                @endif
                            @else
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Status</span>
                                    <span class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset" style="background:rgba(217,145,205,0.45); color:#0D0D0D; --tw-ring-color:rgba(166,18,141,0.28);">
                                        Pending
                                    </span>
                                </div>

                                <p class="text-xs text-gray-500">Status will appear once assigned.</p>
                            @endif
                        </div>
                    @endif

                    <div class="mt-1 flex flex-wrap items-center gap-2 border-t border-gray-200 pt-3">
                        <a
                            href="{{ route('lessons.show', $lesson) }}"
                            class="inline-flex h-9 items-center rounded-md bg-[#A6128D] px-3.5 text-xs font-semibold text-white transition-all duration-200 hover:bg-[#8C0375]"
                        >
                            View Lesson
                        </a>

                        @if ($lesson->file_path)
                            <a
                                href="{{ route('lessons.download', $lesson) }}"
                                class="inline-flex h-9 items-center rounded-md border border-[#A6128D] px-3.5 text-xs font-semibold text-[#A6128D] transition-all duration-200 hover:bg-[#F2F2F2]"
                            >
                                Download Material
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border px-6 py-12 text-center" style="border-color:#D991CD; background:#F2F2F2;">
                <p class="text-lg font-semibold text-black">No lessons found</p>
                <p class="mt-1 text-sm text-gray-600">{{ $isParentUser ? 'No child-linked lessons are available yet.' : 'Try adjusting your search or filter options.' }}</p>
            </div>
        @endforelse
    </div>
</div>
