@php
    $class = trim('soh-logo-mark object-contain ' . $attributes->get('class'));
@endphp

<img
    {{ $attributes->except(['variant', 'class'])->merge(['class' => $class]) }}
    src="{{ asset('img/sohmc-logo-icon.jpg') }}"
    alt="SOHMC logo icon"
/>
