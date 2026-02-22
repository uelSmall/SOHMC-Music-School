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

    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $colors['bg'] }} {{ $colors['text'] }}">
        {{ ucfirst(str_replace('_', ' ', $status)) }}
    </span>

    <div class="flex gap-2">
        @if ($status !== 'completed')
            <button
                wire:click="incrementStatus"
                class="rounded px-2 py-1 text-xs text-white transition-colors"
                style="background:#6A1B9A;"
                title="Mark as next status"
            >
                Next
            </button>
        @endif

        @if ($status === 'assigned')
            <button
                wire:click="markAsStarted"
                class="rounded px-2 py-1 text-xs text-white transition-colors"
                style="background:#4A1370;"
            >
                Start
            </button>
        @endif

        @if ($status !== 'completed')
            <button
                wire:click="markAsCompleted"
                class="text-xs px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded transition-colors"
            >
                Done
            </button>
        @endif
    </div>
</div>
