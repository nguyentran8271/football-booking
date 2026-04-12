#!/bin/bash
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Seed data nếu chưa có admin
php artisan tinker --execute="if(!\App\Models\User::where('role','admin')->exists()) { \Artisan::call('db:seed', ['--force' => true]); echo 'Seeded'; } else { echo 'Already seeded'; }"

# Seed production data nếu chưa có đủ users
php artisan tinker --execute="if(\App\Models\User::where('role','user')->count() < 10) { \Artisan::call('db:seed', ['--class' => 'ProductionDataSeeder', '--force' => true]); echo 'Production data seeded'; } else { echo 'Production data exists'; }"

# Seed reviews nếu chưa có
php artisan tinker --execute="if(\App\Models\Review::count() < 10) { \Artisan::call('db:seed', ['--class' => 'AllReviewsSeeder', '--force' => true]); echo 'Reviews seeded'; } else { echo 'Reviews exist'; }"

# Clear and rebuild caches
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link || true

exec "$@"
