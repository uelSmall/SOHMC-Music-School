@props([
    'items' => [],
    'homeRoute' => null,
    'homeLabel' => 'Home',
])

@php
    $homeRoute = $homeRoute ?? route('home');
@endphp

@if (! empty($items))
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li>
                <a href="{{ $homeRoute }}" class="soh-link text-sm">{{ $homeLabel }}</a>
            </li>

            @foreach ($items as $item)
                <li class="text-gray-400">/</li>

                @if (! empty($item['route']) && ! ($item['current'] ?? false))
                    <li>
                        <a href="{{ $item['route'] }}" class="soh-link text-sm">{{ $item['label'] }}</a>
                    </li>
                @else
                    <li class="font-semibold text-[color:var(--soh-black)]">{{ $item['label'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif