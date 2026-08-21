<x-layouts.admin :title="'Gallery'">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gallery</h1>
                <p class="text-sm text-gray-500">Manage gallery photos.</p>
            </div>
            <a href="{{ route('admin.gallery.create') }}" class="soh-btn-primary">Add Photo</a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($galleryItems as $item)
                <div class="soh-card overflow-hidden">
                    <div class="aspect-video bg-gray-100">
                        @if($item->getFirstMediaUrl('gallery', 'thumb'))
                            <img src="{{ $item->getFirstMediaUrl('gallery', 'thumb') }}" alt="{{ $item->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-gray-400">No image</div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate font-semibold text-gray-900">{{ $item->title }}</h3>
                                @if($item->caption)
                                    <p class="mt-1 truncate text-sm text-gray-500">{{ $item->caption }}</p>
                                @endif
                            </div>
                            @if($item->status == 1)
                                <span class="shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">Active</span>
                            @elseif($item->status == 0)
                                <span class="shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600">Inactive</span>
                            @else
                                <span class="shrink-0 rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-700">Pending</span>
                            @endif
                        </div>
                        <div class="mt-3 flex items-center gap-2">
                            <a href="{{ route('admin.gallery.edit', $item) }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-[#A6128D] hover:bg-[#A6128D]/5">Edit</a>
                            <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                    <p class="text-gray-400">No gallery items yet.</p>
                    <a href="{{ route('admin.gallery.create') }}" class="mt-3 inline-block soh-btn-primary">Add your first photo</a>
                </div>
            @endforelse
        </div>

        @if($galleryItems->hasPages())
            <div class="px-4">{{ $galleryItems->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
