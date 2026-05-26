#!/usr/bin/env bash
chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan migrate --force

# Only seed if orders table is empty
ORDER_COUNT=$(php artisan tinker --execute="echo \App\Models\Order::count();" 2>/dev/null | tail -1)
if [ "$ORDER_COUNT" = "0" ]; then
    php artisan db:seed --force
fi

apache2-foreground