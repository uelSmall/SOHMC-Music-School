<?php

declare(strict_types=1);

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$relativePath = ltrim($requestPath, '/');

if ($relativePath !== '') {
    $publicRoot = realpath(__DIR__ . '/public');
    $assetPath = realpath(__DIR__ . '/public/' . $relativePath);

    if (
        $publicRoot !== false
        && $assetPath !== false
        && str_starts_with($assetPath, $publicRoot . DIRECTORY_SEPARATOR)
        && is_file($assetPath)
    ) {
        $extension = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));

        $mimeMap = [
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'json' => 'application/json',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'map' => 'application/json',
        ];

        $mimeType = $mimeMap[$extension] ?? (mime_content_type($assetPath) ?: 'application/octet-stream');

        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . (string) filesize($assetPath));

        readfile($assetPath);

        exit;
    }
}

require __DIR__ . '/public/index.php';
