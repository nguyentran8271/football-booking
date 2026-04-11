#!/bin/bash
set -e

cd /var/www/html

# Generate app key if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Cache config & routes
php artisan config:cache
php artisan route:cache

# Storage link
php artisan storage:link || true

exec "$@"
