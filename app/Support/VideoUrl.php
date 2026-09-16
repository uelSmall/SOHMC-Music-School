<?php

namespace App\Support;

class VideoUrl
{
    public static function normalize(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);

        if (str_contains($host, 'youtu.be')) {
            return 'https://www.youtube.com/embed/'.ltrim($path, '/');
        }

        if (str_contains($host, 'youtube.com')) {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            $id = $query['v'] ?? null;

            if (! $id && preg_match('#/(?:embed|shorts|live)/([A-Za-z0-9_-]{6,})#', $url, $match)) {
                $id = $match[1];
            }

            return $id ? 'https://www.youtube.com/embed/'.$id : null;
        }

        if (str_contains($host, 'vimeo.com') && preg_match('#/(?:video/)?([0-9]{6,})#', $url, $match)) {
            return 'https://player.vimeo.com/video/'.$match[1];
        }

        return $url;
    }

    public static function isEmbed(?string $url): bool
    {
        if (! $url) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return str_contains($host, 'youtu.be')
            || str_contains($host, 'youtube.com')
            || str_contains($host, 'vimeo.com');
    }
}