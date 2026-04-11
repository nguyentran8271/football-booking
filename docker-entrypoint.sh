#!/bin/bash
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Cache config & routes
php artisan config:cache
php artisan route:cache

# Storage link
php artisan storage:link || true

exec "$@"
