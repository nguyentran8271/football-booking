<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Paginator::defaultView('vendor.pagination.custom');

        // Helper để lấy URL ảnh đúng (Cloudinary hoặc local storage)
        \Illuminate\Support\Facades\Blade::directive('imageUrl', function ($expression) {
            return "<?php echo \App\Services\UploadService::url($expression) ?? asset('images/default-field.jpg'); ?>";
        });
    }
}
