<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Upload file - dùng Cloudinary trên production, local storage trên local
     */
    public static function upload(UploadedFile $file, string $folder): string
    {
        if (config('app.env') === 'production') {
            $result = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                $file->getRealPath(),
                ['folder' => 'football-booking/' . $folder]
            );
            return $result->getSecurePath();
        }

        return $file->store($folder, 'public');
    }

    /**
     * Xóa file - xóa trên Cloudinary hoặc local
     */
    public static function delete(?string $path): void
    {
        if (!$path) return;

        if (str_contains($path, 'cloudinary.com')) {
            preg_match('/upload\/(?:v\d+\/)?(.+)\.\w+$/', $path, $matches);
            if (!empty($matches[1])) {
                \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::destroy($matches[1]);
            }
        } else {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Lấy URL hiển thị ảnh
     */
    public static function url(?string $path): ?string
    {
        if (!$path) return null;
        if (str_contains($path, 'cloudinary.com') || str_starts_with($path, 'http')) {
            return $path;
        }
        return asset('storage/' . $path);
    }
}
