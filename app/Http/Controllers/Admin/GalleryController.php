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

    public function storePhoto(Request $request)
    {
        $validated = $request->validate([
            'photo_title' => ['nullable', 'string', 'max:255'],
            'photo_caption' => ['nullable', 'string', 'max:1000'],
            'photo_images' => ['required', 'array'],
            'photo_images.*' => ['required', 'image', 'max:20480'],
            'photo_status' => ['required', 'in:0,1,2'],
        ]);

        $files = $request->file('photo_images');
        $sharedTitle = $validated['photo_title'] ?? null;

        foreach ($files as $file) {
            $item = GalleryItem::create([
                'title' => $sharedTitle ?? titleFromFilename($file->getClientOriginalName()),
                'caption' => $validated['photo_caption'] ?? null,
                'video_url' => null,
                'status' => $validated['photo_status'],
                'sort_order' => 0,
                'created_by' => auth()->id(),
            ]);

            $item->addMedia($file)->toMediaCollection('gallery');
        }

        $count = count($files);

        return redirect()->route('admin.gallery.index')->with('status', "{$count} photo".(($count === 1) ? '' : 's').' uploaded successfully.');
    }

    public function storePhotoAjax(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:20480'],
            'photo_title' => ['nullable', 'string', 'max:255'],
            'photo_caption' => ['nullable', 'string', 'max:1000'],
            'photo_status' => ['required', 'in:0,1,2'],
        ]);

        $file = $request->file('photo');

        try {
            $item = GalleryItem::create([
                'title' => $validated['photo_title'] ?? titleFromFilename($file->getClientOriginalName()),
                'caption' => $validated['photo_caption'] ?? null,
                'video_url' => null,
                'status' => $validated['photo_status'],
                'sort_order' => 0,
                'created_by' => auth()->id(),
            ]);

            $item->addMedia($file)->toMediaCollection('gallery');
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true, 'id' => $item->id, 'title' => $item->title]);
    }

    public function logPhotoBatch(Request $request)
    {
        $count = max(0, min((int) $request->input('count', 0), 200));

        if ($count > 0) {
            log_activity(
                'uploaded '.$count.' photo(s) to the gallery',
                null,
                route('admin.gallery.index')
            );
        }

        return response()->json(['ok' => true]);
    }

    public function storeVideo(Request $request)
    {
        $validated = $request->validate([
            'video_title' => ['required', 'string', 'max:255'],
            'video_caption' => ['nullable', 'string', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska,video/m4v,application/mp4', 'max:512000'],
            'video_link' => ['nullable', 'url', 'max:500'],
            'video_status' => ['required', 'in:0,1,2'],
        ]);

        if (! $request->hasFile('video_file') && ! $request->filled('video_link')) {
            return back()
                ->withErrors(['video_file' => 'Please upload a video file or paste a YouTube/Vimeo link.'])
                ->withInput();
        }

        $videoUrl = $request->hasFile('video_file')
            ? app(SupabaseStorage::class)->upload($request->file('video_file'))
            : VideoUrl::normalize($request->input('video_link'));

        $item = GalleryItem::create([
            'title' => $validated['video_title'],
            'caption' => $validated['video_caption'] ?? null,
            'video_url' => $videoUrl,
            'status' => $validated['video_status'],
            'sort_order' => 0,
            'created_by' => auth()->id(),
        ]);

        log_activity(
            'uploaded video "'.$item->title.'" to the gallery',
            $item,
            route('admin.gallery.index')
        );

        return redirect()->route('admin.gallery.index')->with('status', 'Video added successfully.');
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
