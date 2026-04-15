#!/bin/bash
set -e

cd /var/www/html

# Wait for DB and run migrations with retry
for i in 1 2 3 4 5; do
    php artisan migrate --force && break || echo "Migration attempt $i failed, retrying in 5s..." && sleep 5
done

# Seed data nếu chưa có admin
php artisan tinker --execute="if(!\App\Models\User::where('role','admin')->exists()) { \Artisan::call('db:seed', ['--force' => true]); echo 'Seeded'; } else { echo 'Already seeded'; }"

# Seed production data nếu chưa có đủ users
php artisan tinker --execute="if(\App\Models\User::where('role','user')->count() < 10) { \Artisan::call('db:seed', ['--class' => 'ProductionDataSeeder', '--force' => true]); echo 'Production data seeded'; } else { echo 'Production data exists'; }"

# Seed reviews nếu chưa có
php artisan tinker --execute="if(\App\Models\Review::count() < 10) { \Artisan::call('db:seed', ['--class' => 'AllReviewsSeeder', '--force' => true]); echo 'Reviews seeded'; } else { echo 'Reviews exist'; }"

# Seed bookings cho chủ sân mẫu nếu chưa có
php artisan tinker --execute="
\$owner = \App\Models\User::where('email','owner@gmail.com')->first();
if(\$owner) {
    \$fieldIds = \$owner->fields->pluck('id')->toArray();
    if(\$fieldIds && \App\Models\Booking::whereIn('field_id',\$fieldIds)->count() < 10) {
        \Artisan::call('db:seed', ['--class' => 'SampleOwnerBookingsSeeder', '--force' => true]);
        echo 'Owner bookings seeded';
    } else { echo 'Owner bookings exist'; }
} else { echo 'Owner not found'; }"

# Clear all caches
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan view:clear

# Storage link
php artisan storage:link || true

exec "$@"
