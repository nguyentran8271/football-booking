#!/bin/bash

cd /var/www/html

# Fix storage permissions
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Storage link
php artisan storage:link 2>/dev/null || true

# Clear caches
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Run migrations in background (don't block Apache startup)
(
    sleep 5
    php artisan migrate --force 2>&1 || echo "Migration failed"

    php artisan tinker --execute="
    try {
        if(!\App\Models\User::where('role','admin')->exists()) {
            \Artisan::call('db:seed', ['--force' => true]);
            echo 'Seeded';
        } else {
            echo 'Already seeded';
        }
    } catch(\Exception \$e) { echo 'Seed error: '.\$e->getMessage(); }
    " 2>/dev/null || true

    php artisan tinker --execute="
    try {
        if(\App\Models\User::where('role','user')->count() < 10) {
            \Artisan::call('db:seed', ['--class' => 'ProductionDataSeeder', '--force' => true]);
        }
    } catch(\Exception \$e) { echo 'Error: '.\$e->getMessage(); }
    " 2>/dev/null || true

    php artisan tinker --execute="
    try {
        if(\App\Models\Review::count() < 10) {
            \Artisan::call('db:seed', ['--class' => 'AllReviewsSeeder', '--force' => true]);
        }
    } catch(\Exception \$e) { echo 'Error: '.\$e->getMessage(); }
    " 2>/dev/null || true

) &

exec "$@"
