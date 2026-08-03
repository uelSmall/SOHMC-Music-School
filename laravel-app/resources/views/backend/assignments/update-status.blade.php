<div class="flex items-center gap-3">
    @php
        $statusColors = [
            'assigned' => ['bg' => '#F2F2F2', 'text' => '#0D0D0D', 'border' => '#D991CD'],
            'started' => ['bg' => '#D991CD', 'text' => '#0D0D0D', 'border' => '#D991CD'],
            'in_progress' => ['bg' => '#A6128D', 'text' => '#FFFFFF', 'border' => '#A6128D'],
            'completed' => ['bg' => '#8C0375', 'text' => '#FFFFFF', 'border' => '#8C0375'],
        ];
        $currentColor = $statusColors[$status] ?? $statusColors['assigned'];
    @endphp

    <select
        wire:change="updateStatus($event.target.value)"
        class="px-3 py-1 text-sm font-semibold rounded-full border cursor-pointer hover:opacity-90"
        style="background:{{ $currentColor['bg'] }}; color:{{ $currentColor['text'] }}; border-color:{{ $currentColor['border'] }};"
    >
        @foreach ($statusOptions as $value => $label)
            <option value="{{ $value }}" @selected($status === $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
