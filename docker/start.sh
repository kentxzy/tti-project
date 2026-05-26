#!/usr/bin/env bash

chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true
php artisan migrate --force

# Seed only if orders table is empty
php artisan tinker --execute="
if (\App\Models\Order::count() === 0) {
    \Artisan::call('db:seed', ['--force' => true]);
    echo 'Seeding done';
} else {
    echo 'Skipping seed, data exists';
}
"

apache2-foreground