<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GalleryItem extends BaseModel
{
    protected $table = 'gallery_items';

    protected $fillable = [
        'title',
        'caption',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        parent::registerMediaConversions($media);

        $this->addMediaConversion('gallery-lg')
            ->width(1280)
            ->height(860)
            ->quality(75)
            ->sharpen(8);
    }
}
