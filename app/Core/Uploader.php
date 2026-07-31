<?php
namespace App\Core;

/**
 * Secure file uploader
 */
class Uploader
{
    public static function image(array $file, string $subdir = 'misc'): ?string
    {
        return self::store($file, $subdir, config('allowed_images', []));
    }

    public static function file(array $file, string $subdir = 'misc'): ?string
    {
        return self::store($file, $subdir, config('allowed_files', []));
    }

    public static function store(array $file, string $subdir, array $allowed): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload error code: ' . $file['error']);
        }

        $maxBytes = ((int) config('upload_max_mb', 10)) * 1024 * 1024;
        if (($file['size'] ?? 0) > $maxBytes) {
            throw new \RuntimeException('File exceeds max size');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            throw new \RuntimeException('File type not allowed: ' . $ext);
        }

        // MIME validation for images
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $info = @getimagesize($file['tmp_name']);
            if ($info === false) {
                throw new \RuntimeException('Invalid image file');
            }
        }

        $subdir = trim($subdir, '/');
        $dir = storage_path('uploads/' . $subdir);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $dir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('Failed to move uploaded file');
        }

        // Return relative path used by upload_url()
        return $subdir . '/' . $filename;
    }

    public static function delete(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }
        $full = storage_path('uploads/' . ltrim($relativePath, '/'));
        if (is_file($full)) {
            @unlink($full);
        }
    }
}
