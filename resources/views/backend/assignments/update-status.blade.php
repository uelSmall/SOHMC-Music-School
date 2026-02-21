<div class="flex items-center gap-3">
    @php
        $statusColors = [
            'assigned' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
            'started' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        ];
        $currentColor = $statusColors[$status] ?? $statusColors['assigned'];
    @endphp

    <select
        wire:change="updateStatus($event.target.value)"
        class="px-3 py-1 text-sm font-semibold rounded-full border-0 {{ $currentColor['bg'] }} {{ $currentColor['text'] }} cursor-pointer hover:opacity-80"
    >
        @foreach ($statusOptions as $value => $label)
            <option value="{{ $value }}" @selected($status === $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
