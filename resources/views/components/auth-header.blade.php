@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-2xl font-semibold" style="color:#0D0D0D;">{{ $title }}</h1>
    <h3 class="mt-1 text-sm" style="color:rgba(13,13,13,0.68);">{{ $description }}</h3>
</div>
