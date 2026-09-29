#!/bin/bash
set -e

# Ensure SQLite file exists if using sqlite
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        touch /var/www/html/database/database.sqlite
    fi
    chmod 777 /var/www/html/database/database.sqlite
fi
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force
php artisan optimize

# Start Laravel server with PORT provided by Render
PORT=${PORT:-10000}
echo "Starting Digital Mart BD on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
