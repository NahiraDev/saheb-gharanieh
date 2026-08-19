<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Menu photos on the `public` disk (storage/app/public → public/storage, linked
 * by `php artisan storage:link`).
 *
 * Everything the panel does to an image goes through here, so there is exactly
 * one place that knows where files live and one place that deletes them.
 */
class ImageStore
{
    public const DISK = 'public';

    /** Store an upload and return the path to save on the model. */
    public static function put(UploadedFile $file, string $folder = 'products'): string
    {
        return $file->store($folder, self::DISK);
    }

    /** Put a new image in place of an old one, cleaning up the file it replaces. */
    public static function replace(?string $current, UploadedFile $file, string $folder = 'products'): string
    {
        self::forget($current);

        return self::put($file, $folder);
    }

    /**
     * Delete a stored file. A full URL is left untouched: the seeder may point at
     * a remote image, and that is not ours to remove.
     */
    public static function forget(?string $path): void
    {
        if (blank($path) || Str::startsWith($path, ['http://', 'https://', '//'])) {
            return;
        }

        Storage::disk(self::DISK)->delete($path);
    }
}
