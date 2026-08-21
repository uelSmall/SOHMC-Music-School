<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
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
            'image' => 'required|image|max:10240',
            'status' => 'required|in:0,1,2',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $item = GalleryItem::create([
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('image')) {
            $item->addMedia($request->file('image'))->toMediaCollection('gallery');
        }

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery item created successfully.');
    }

    public function edit(GalleryItem $galleryItem)
    {
        return view('admin.gallery.edit', ['galleryItem' => $galleryItem]);
    }

    public function update(Request $request, GalleryItem $galleryItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:10240',
            'status' => 'required|in:0,1,2',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $galleryItem->update([
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        if ($request->hasFile('image')) {
            $galleryItem->clearMediaCollection('gallery');
            $galleryItem->addMedia($request->file('image'))->toMediaCollection('gallery');
        }

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery item updated successfully.');
    }

    public function destroy(GalleryItem $galleryItem)
    {
        $galleryItem->clearMediaCollection('gallery');
        $galleryItem->delete();

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery item deleted successfully.');
    }
}
