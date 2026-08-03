<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => 'SOHMC Identity Mark',
                'caption' => 'The visual signature of our school community.',
                'sort_order' => 1,
                'image' => 'logo-square.jpg',
            ],
            [
                'title' => 'Main School Branding',
                'caption' => 'Primary logo lockup used across performances and events.',
                'sort_order' => 2,
                'image' => 'logo-with-text.jpg',
            ],
            [
                'title' => 'Sounds of Harmony Emblem',
                'caption' => 'A recognizable symbol of the SOHMC learning environment.',
                'sort_order' => 3,
                'image' => 'sohm-logo-original.jpg',
            ],
        ];

        foreach ($items as $itemData) {
            $imagePath = public_path('img/'.$itemData['image']);

            if (! file_exists($imagePath)) {
                continue;
            }

            $galleryItem = GalleryItem::updateOrCreate(
                ['title' => $itemData['title']],
                [
                    'caption' => $itemData['caption'],
                    'status' => 1,
                    'sort_order' => $itemData['sort_order'],
                ]
            );

            $galleryItem->clearMediaCollection('gallery');
            $galleryItem->addMedia($imagePath)
                ->preservingOriginal()
                ->toMediaCollection('gallery');
        }
    }
}
