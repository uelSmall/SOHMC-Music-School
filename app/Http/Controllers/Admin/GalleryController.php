<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\SupabaseStorage;
use App\Support\VideoUrl;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleryItems = GalleryItem::with('media')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.gallery.index', compact('galleryItems'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:20480',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska,video/m4v,application/mp4|max:512000',
            'video_url' => 'nullable|url|max:500',
            'status' => 'required|in:0,1,2',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $item = GalleryItem::create([
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'video_url' => $this->resolveVideoUrl($request),
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('image')) {
            $item->addMedia($request->file('image'))->toMediaCollection('gallery');
        }

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery item created successfully.');
    }

    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.edit', ['galleryItem' => $gallery]);
    }

    public function update(Request $request, GalleryItem $gallery, SupabaseStorage $storage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:20480',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska,video/m4v,application/mp4|max:512000',
            'video_url' => 'nullable|url|max:500',
            'remove_video' => 'nullable',
            'status' => 'required|in:0,1,2',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $gallery->update([
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        if ($request->hasFile('video')) {
            $this->forgetVideo($gallery, $storage);
            $gallery->update(['video_url' => $storage->upload($request->file('video'))]);
        } elseif ($request->filled('video_url') && $request->input('video_url') !== $gallery->video_url) {
            $this->forgetVideo($gallery, $storage);
            $gallery->update(['video_url' => VideoUrl::normalize($request->input('video_url'))]);
        } elseif ($request->boolean('remove_video')) {
            $this->forgetVideo($gallery, $storage);
            $gallery->update(['video_url' => null]);
        }

        if ($request->hasFile('image')) {
            $gallery->clearMediaCollection('gallery');
            $gallery->addMedia($request->file('image'))->toMediaCollection('gallery');
        }

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery item updated successfully.');
    }

    public function destroy(GalleryItem $gallery, SupabaseStorage $storage)
    {
        $this->forgetVideo($gallery, $storage);
        $gallery->clearMediaCollection('gallery');
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery item deleted successfully.');
    }

    protected function resolveVideoUrl(Request $request): ?string
    {
        if ($request->hasFile('video')) {
            return app(SupabaseStorage::class)->upload($request->file('video'));
        }

        return $request->filled('video_url') ? VideoUrl::normalize($request->input('video_url')) : null;
    }

    protected function forgetVideo(GalleryItem $gallery, SupabaseStorage $storage): void
    {
        if (! $gallery->video_url || ! str_starts_with($gallery->video_url, config('services.supabase.url'))) {
            return;
        }

        $storage->delete($gallery->video_url);
    }
}
