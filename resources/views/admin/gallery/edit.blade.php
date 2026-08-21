<x-layouts.admin :title="'Edit Photo'">
    <div class="mx-auto max-w-2xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Photo</h1>
            <p class="text-sm text-gray-500">Update &ldquo;{{ $galleryItem->title }}&rdquo;</p>
        </div>

        <form method="POST" action="{{ route('admin.gallery.update', $galleryItem) }}" enctype="multipart/form-data" class="soh-card space-y-6 p-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $galleryItem->title) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="caption" class="mb-1 block text-sm font-medium text-gray-700">Caption <span class="text-gray-400">(optional)</span></label>
                <textarea name="caption" id="caption" rows="3" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">{{ old('caption', $galleryItem->caption) }}</textarea>
                @error('caption') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            @if($galleryItem->getFirstMediaUrl('gallery', 'thumb'))
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Current Image</label>
                    <img src="{{ $galleryItem->getFirstMediaUrl('gallery', 'thumb') }}" alt="{{ $galleryItem->title }}" class="h-40 w-auto rounded-xl object-cover" />
                </div>
            @endif

            <div>
                <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Replace Image <span class="text-gray-400">(leave blank to keep current)</span></label>
                <input type="file" name="image" id="image" accept="image/*" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition file:mr-3 file:rounded-lg file:border-0 file:bg-[#A6128D]/10 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-[#A6128D] hover:file:bg-[#A6128D]/20 focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="status" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">
                        <option value="1" {{ old('status', $galleryItem->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $galleryItem->status) == '0' ? 'selected' : '' }}>Inactive</option>
                        <option value="2" {{ old('status', $galleryItem->status) == '2' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="mb-1 block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $galleryItem->sort_order) }}" min="0" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.gallery.index') }}" class="soh-btn-outline">Cancel</a>
                <button type="submit" class="soh-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
