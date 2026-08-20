@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-2xl font-bold tracking-tight" style="color:#0D0D0D; font-family: 'Sora', sans-serif;">{{ $title }}</h1>
    <p class="mt-2 text-sm text-gray-500">{{ $description }}</p>
</div>
