<x-layouts.admin :title="'Gallery'">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gallery</h1>
            <p class="text-sm text-gray-500">Upload photos or videos below — they appear on the public Gallery page as soon as they're Active.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Upload Photo --}}
            <div class="soh-card overflow-hidden">
                <div class="border-b border-gray-100 bg-gradient-to-br from-[#A6128D]/5 to-transparent px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#A6128D]/10 text-[#A6128D]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                        </span>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Upload a Photo</h2>
                            <p class="text-sm text-gray-500">Pictures from concerts, classes and events.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.gallery.store-photo') }}" enctype="multipart/form-data" class="space-y-4 p-6">
                    @csrf

                    <div>
                        <label for="photo_title" class="mb-1 block text-sm font-medium text-gray-700">Title <span class="text-gray-400">(optional — photo names are used if left blank)</span></label>
                        <input type="text" name="photo_title" id="photo_title" value="{{ old('photo_title') }}" placeholder="e.g. Spring Concert 2026" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                        @error('photo_title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="photo_caption" class="mb-1 block text-sm font-medium text-gray-700">Caption <span class="text-gray-400">(optional)</span></label>
                        <textarea name="photo_caption" id="photo_caption" rows="2" placeholder="A short description" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">{{ old('photo_caption') }}</textarea>
                        @error('photo_caption') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="photo_images" class="mb-1 block text-sm font-medium text-gray-700">Choose Photos <span class="text-gray-400">(hold Ctrl/Cmd or Shift to select many at once)</span></label>
                        <input type="file" name="photo_images[]" id="photo_images" accept="image/*" multiple required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition file:mr-3 file:rounded-lg file:border-0 file:bg-[#A6128D]/10 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-[#A6128D] hover:file:bg-[#A6128D]/20 focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                        @error('photo_images') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        @error('photo_images.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="photo_status" class="mb-1 block text-sm font-medium text-gray-700">Visibility</label>
                        <select name="photo_status" id="photo_status" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">
                            <option value="1" {{ old('photo_status', '1') == '1' ? 'selected' : '' }}>Visible on the site (Active)</option>
                            <option value="0" {{ old('photo_status') == '0' ? 'selected' : '' }}>Hidden (Draft)</option>
                        </select>
                        @error('photo_status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="soh-btn-primary w-full">Upload Photo(s)</button>
                </form>
            </div>

            {{-- Upload Video --}}
            <div class="soh-card overflow-hidden">
                <div class="border-b border-gray-100 bg-gradient-to-br from-[#A6128D]/5 to-transparent px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#A6128D]/10 text-[#A6128D]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="m22 8-6 4 6 4V8Z"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>
                        </span>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Upload a Video</h2>
                            <p class="text-sm text-gray-500">Upload a file or paste a YouTube/Vimeo link.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.gallery.store-video') }}" enctype="multipart/form-data" class="space-y-4 p-6">
                    @csrf

                    <div>
                        <label for="video_title" class="mb-1 block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="video_title" id="video_title" value="{{ old('video_title') }}" placeholder="e.g. Student Recital Highlight" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                        @error('video_title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video_caption" class="mb-1 block text-sm font-medium text-gray-700">Caption <span class="text-gray-400">(optional)</span></label>
                        <textarea name="video_caption" id="video_caption" rows="2" placeholder="A short description" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">{{ old('video_caption') }}</textarea>
                        @error('video_caption') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video_file" class="mb-1 block text-sm font-medium text-gray-700">Choose a Video File <span class="text-gray-400">(mp4/webm/ogg/mov, up to 500MB)</span></label>
                        <input type="file" name="video_file" id="video_file" accept="video/*" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition file:mr-3 file:rounded-lg file:border-0 file:bg-[#A6128D]/10 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-[#A6128D] hover:file:bg-[#A6128D]/20 focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                        @error('video_file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="h-px flex-1 bg-gray-200"></span>
                        <span>or paste a link instead</span>
                        <span class="h-px flex-1 bg-gray-200"></span>
                    </div>

                    <div>
                        <label for="video_link" class="mb-1 block text-sm font-medium text-gray-700">Video Link <span class="text-gray-400">(YouTube or Vimeo)</span></label>
                        <input type="url" name="video_link" id="video_link" value="{{ old('video_link') }}" placeholder="https://youtube.com/watch?v=..." class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                        @error('video_link') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video_status" class="mb-1 block text-sm font-medium text-gray-700">Visibility</label>
                        <select name="video_status" id="video_status" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">
                            <option value="1" {{ old('video_status', '1') == '1' ? 'selected' : '' }}>Visible on the site (Active)</option>
                            <option value="0" {{ old('video_status') == '0' ? 'selected' : '' }}>Hidden (Draft)</option>
                        </select>
                        @error('video_status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="soh-btn-primary w-full">Upload Video</button>
                </form>
            </div>
        </div>

        {{-- Uploaded items --}}
        <div>
            <h2 class="mb-3 text-lg font-semibold text-gray-900">Uploaded Photos &amp; Videos</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($galleryItems as $item)
                    <div class="soh-card overflow-hidden">
                        <div class="aspect-video bg-gray-100">
                            @if($item->video_url)
                                @if($item->getFirstMediaUrl('gallery', 'thumb'))
                                    <img src="{{ $item->getFirstMediaUrl('gallery', 'thumb') }}" alt="{{ $item->title }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full items-center justify-center bg-black text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10 text-white/70"><path d="M8 5.14v14l11-7-11-7Z"/></svg>
                                    </div>
                                @endif
                            @elseif($item->getFirstMediaUrl('gallery', 'thumb'))
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
                                <div class="flex shrink-0 items-center gap-1.5">
                                    @if($item->video_url)
                                        <span class="rounded-full bg-[#A6128D]/10 px-2 py-0.5 text-xs font-semibold text-[#A6128D]">Video</span>
                                    @endif
                                    @if($item->status == 1)
                                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">Active</span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600">Draft</span>
                                    @endif
                                </div>
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
                        <p class="text-gray-400">Nothing here yet.</p>
                        <p class="mt-1 text-sm text-gray-400">Use the upload boxes above to add your first photo or video.</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if($galleryItems->hasPages())
            <div class="px-4">{{ $galleryItems->links() }}</div>
        @endif
    </div>
</x-layouts.admin>