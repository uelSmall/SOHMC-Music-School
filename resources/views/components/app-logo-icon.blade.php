@php
    $class = trim('soh-logo-mark object-contain ' . $attributes->get('class'));
@endphp

<img
    {{ $attributes->except(['variant', 'class'])->merge(['class' => $class]) }}
    src="{{ asset('img/sohmc-piano-icon.png') }}"
    alt="SOHMC piano icon"
/>
