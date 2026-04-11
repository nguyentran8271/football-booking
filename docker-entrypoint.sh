#!/bin/bash
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link || true

exec "$@"
