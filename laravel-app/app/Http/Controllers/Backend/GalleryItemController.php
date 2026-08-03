<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryItemRequest;
use App\Http\Requests\UpdateGalleryItemRequest;
use App\Models\GalleryItem;
use Illuminate\Support\Facades\DB;

class GalleryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleryItems = GalleryItem::query()
            ->with('media')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('backend.gallery-items.index', compact('galleryItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.gallery-items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryItemRequest $request)
    {
        $data = $request->validated();

        $galleryItem = GalleryItem::create([
            'title' => $data['title'],
            'caption' => $data['caption'] ?? null,
            'status' => $data['status'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $galleryItem->addMedia($request->file('image'))->toMediaCollection('gallery');

        return redirect()
            ->route('backend.gallery-items.index')
            ->with('status', 'Gallery image created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(GalleryItem $galleryItem)
    {
        return view('backend.gallery-items.edit', compact('galleryItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryItemRequest $request, GalleryItem $galleryItem)
    {
        $data = $request->validated();

        $galleryItem->update([
            'title' => $data['title'],
            'caption' => $data['caption'] ?? null,
            'status' => $data['status'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        if ($request->hasFile('image')) {
            $galleryItem->clearMediaCollection('gallery');
            $galleryItem->addMedia($request->file('image'))->toMediaCollection('gallery');
        }

        return redirect()
            ->route('backend.gallery-items.index')
            ->with('status', 'Gallery image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GalleryItem $galleryItem)
    {
        $galleryItem->clearMediaCollection('gallery');
        $galleryItem->delete();

        return redirect()
            ->route('backend.gallery-items.index')
            ->with('status', 'Gallery image deleted successfully.');
    }

    public function move(GalleryItem $galleryItem, string $direction)
    {
        abort_unless(in_array($direction, ['up', 'down'], true), 404);

        DB::transaction(function () use ($galleryItem, $direction) {
            $comparison = $direction === 'up' ? '<' : '>';
            $sortDirection = $direction === 'up' ? 'desc' : 'asc';

            $adjacent = GalleryItem::query()
                ->where('id', '!=', $galleryItem->id)
                ->where('sort_order', $comparison, $galleryItem->sort_order)
                ->orderBy('sort_order', $sortDirection)
                ->first();

            if (! $adjacent) {
                return;
            }

            $currentOrder = $galleryItem->sort_order;
            $galleryItem->update(['sort_order' => $adjacent->sort_order]);
            $adjacent->update(['sort_order' => $currentOrder]);
        });

        return redirect()
            ->route('backend.gallery-items.index')
            ->with('status', 'Gallery order updated.');
    }
}
