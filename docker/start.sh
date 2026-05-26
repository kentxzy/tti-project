#!/usr/bin/env bash

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