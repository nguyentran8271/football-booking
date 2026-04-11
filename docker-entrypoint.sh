#!/bin/bash
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Seed data nếu chưa có admin
php artisan tinker --execute="if(!\App\Models\User::where('role','admin')->exists()) { \Artisan::call('db:seed', ['--force' => true]); echo 'Seeded'; } else { echo 'Already seeded'; }"

# Clear and rebuild caches
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link || true

exec "$@"
