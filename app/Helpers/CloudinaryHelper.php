<?php

namespace App\Helpers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryHelper
{
    /**
     * Upload file lên Cloudinary, trả về URL
     */
    public static function upload($file, string $folder = 'football-booking'): string
    {
        $result = Cloudinary::upload($file->getRealPath(), [
            'folder' => $folder,
        ]);

        return $result->getSecurePath();
    }

    /**
     * Xóa ảnh trên Cloudinary theo URL
     */
    public static function delete(?string $url): void
    {
        if (!$url || !str_contains($url, 'cloudinary.com')) {
            return;
        }

        // Extract public_id từ URL
        preg_match('/upload\/(?:v\d+\/)?(.+)\.\w+$/', $url, $matches);
        if (!empty($matches[1])) {
            Cloudinary::destroy($matches[1]);
        }
    }
}
