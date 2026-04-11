<?php

if (!function_exists('storage_url')) {
    /**
     * Lấy URL ảnh đúng - hỗ trợ Cloudinary URL, local storage, và public images
     */
    function storage_url(?string $path, string $default = ''): string
    {
        if (!$path) return $default;

        // Cloudinary hoặc URL đầy đủ
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Ảnh trong public/images
        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        // Local storage
        return asset('storage/' . $path);
    }
}
