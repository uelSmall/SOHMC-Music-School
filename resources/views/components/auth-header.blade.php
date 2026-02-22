@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-2xl font-semibold text-black">{{ $title }}</h1>
    <h3 class="mt-1 text-sm text-gray-600">{{ $description }}</h3>
</div>
