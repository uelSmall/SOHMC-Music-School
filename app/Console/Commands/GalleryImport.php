<?php

namespace App\Console\Commands;

use App\Models\GalleryItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GalleryImport extends Command
{
    protected $signature = 'gallery:import
                            {--path= : Path to the folder containing images}
                            {--status=1 : Status for imported items (0=inactive, 1=active, 2=pending)}
                            {--dry-run : Preview what would be imported without making changes}';

    protected $description = 'Bulk import images into the gallery from a folder';

    protected $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    public function handle(): int
    {
        $path = $this->option('path') ?: storage_path('app/gallery-import');

        if (! is_dir($path)) {
            $this->error("Folder not found: {$path}");
            $this->line('');
            $this->line('Create it with: mkdir -p ' . $path);
            $this->line('Then drop your photos in there and run this command again.');

            return self::FAILURE;
        }

        $files = collect(File::files($path))->filter(function ($file) {
            return in_array(strtolower($file->getExtension()), $this->allowedExtensions);
        })->values();

        if ($files->isEmpty()) {
            $this->warn('No image files found in: ' . $path);
            $this->line('Supported formats: ' . implode(', ', $this->allowedExtensions));

            return self::FAILURE;
        }

        $this->info("Found {$files->count()} image(s) to import.");
        $this->line('');

        if ($this->option('dry-run')) {
            $this->line('<fg=yellow>DRY RUN — no changes will be made</fg=yellow>');
            $this->line('');

            foreach ($files as $index => $file) {
                $title = $this->generateTitle($file->getFilename());
                $this->line("  " . ($index + 1) . ". {$file->getFilename()} → \"{$title}\"");
            }

            $this->line('');
            $this->line("Would import: {$files->count()} item(s)");

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($files->count());
        $bar->start();

        $imported = 0;
        $skipped = 0;

        foreach ($files as $file) {
            try {
                $title = $this->generateTitle($file->getFilename());
                $sortOrder = $imported + $skipped + 1;

                $item = GalleryItem::create([
                    'title' => $title,
                    'caption' => null,
                    'status' => (int) $this->option('status'),
                    'sort_order' => $sortOrder,
                ]);

                $item->addMedia($file->getRealPath())
                    ->usingName($file->getFilename())
                    ->toMediaCollection('gallery');

                $imported++;
            } catch (\Exception $e) {
                $skipped++;
                $this->newLine();
                $this->error("Skipped: {$file->getFilename()} — {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Import complete!");
        $this->line("  Imported: {$imported}");
        if ($skipped > 0) {
            $this->line("  Skipped:  {$skipped}");
        }
        $this->line('');
        $this->line('<fg=green>View your gallery at: /gallery</fg=green>');
        $this->line('<fg=yellow>Manage in admin at: /admin/gallery-items</fg=yellow>');

        return self::SUCCESS;
    }

    protected function generateTitle(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);

        $name = preg_replace('/^(DSC|IMG|IMGNA|PANO|WP)_?(-?\d*)?/i', '', $name);
        $name = preg_replace('/-WA\d+$/i', '', $name);
        $name = preg_replace('/[\-_]+/', ' ', $name);
        $name = preg_replace('/\d{10,}/', '', $name);
        $name = trim($name);

        if ($name === '' || preg_match('/^\d+$/', $name)) {
            $name = 'Photo ' . $name;
        }

        return Str::title($name);
    }
}
