<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;

class GalleryController extends Controller
{
    public function index()
    {
        $galleryItems = GalleryItem::query()
            ->active()
            ->with('media')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('frontend.gallery', compact('galleryItems'));
    }
}
