<div class="flex items-center gap-2">
    @php
        $statusColors = [
            'assigned' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
            'started' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        ];
        $colors = $statusColors[$status] ?? $statusColors['assigned'];
    @endphp

    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $colors['bg'] }} {{ $colors['text'] }}">
        {{ ucfirst(str_replace('_', ' ', $status)) }}
    </span>

    <div class="flex gap-2">
        @if ($status === 'assigned')
            <button
                wire:click="markAsStarted"
                class="rounded-full px-3 py-1 text-xs font-semibold text-white transition-all hover:opacity-90"
                style="background:#A6128D;"
            >
                Start Lesson
            </button>
        @endif

        @if ($status === 'started')
            <button
                wire:click="markAsInProgress"
                class="rounded-full px-3 py-1 text-xs font-semibold text-white transition-all hover:opacity-90"
                style="background:#8C0375;"
            >
                In Progress
            </button>
        @endif

        @if (in_array($status, ['started', 'in_progress']))
            <button
                wire:click="markAsCompleted"
                class="rounded-full bg-green-500 px-3 py-1 text-xs font-semibold text-white transition-all hover:bg-green-600"
            >
                Mark Complete
            </button>
        @endif
    </div>
</div>
