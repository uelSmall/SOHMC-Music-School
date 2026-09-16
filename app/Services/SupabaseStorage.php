<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorage
{
    protected string $endpoint;

    protected string $apiKey;

    protected string $bucket;

    public function __construct()
    {
        $this->endpoint = rtrim((string) config('services.supabase.url'), '/');
        $this->apiKey = (string) (config('services.supabase.anon_key') ?: config('services.supabase.key'));
        $this->bucket = (string) config('services.supabase.video_bucket');
    }

    public function upload(UploadedFile $file): string
    {
        $key = date('Y/m').'/'.Str::uuid().'.'.$file->getClientOriginalExtension();

        $response = Http::withHeaders($this->headers())->withBody(
            $file->get(),
            $file->getMimeType()
        )->put(
            $this->endpoint.'/storage/v1/object/'.$this->bucket.'/'.$key
        );

        abort_unless($response->successful(), 400, 'Video upload to storage failed.');

        return $this->publicUrl($key);
    }

    public function delete(string $url): bool
    {
        $key = $this->keyFromUrl($url);

        if (! $key) {
            return false;
        }

        $response = Http::withHeaders($this->headers())->delete(
            $this->endpoint.'/storage/v1/object/'.$this->bucket.'/'.$key
        );

        return $response->successful();
    }

    protected function headers(): array
    {
        return [
            'apikey' => $this->apiKey,
            'Authorization' => 'Bearer '.$this->apiKey,
        ];
    }

    public function publicUrl(string $key): string
    {
        return $this->endpoint.'/storage/v1/object/public/'.$this->bucket.'/'.$key;
    }

    protected function keyFromUrl(string $url): ?string
    {
        $needle = '/storage/v1/object/public/'.$this->bucket.'/';
        $pos = strpos($url, $needle);

        if ($pos === false) {
            return null;
        }

        return substr($url, $pos + strlen($needle));
    }
}