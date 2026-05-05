#!/bin/bash
set -e

# Start PHP-FPM
php-fpm -D

# Wait for MySQL
echo "Waiting for database..."
until php artisan db:monitor --max=1 2>/dev/null; do
  sleep 2
done

# Laravel setup
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link

# Start Nginx
exec nginx -g "daemon off;"
