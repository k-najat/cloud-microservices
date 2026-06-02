#!/bin/bash
set -e

# Fix storage permissions after volume mount
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations if DB is available
if php artisan migrate --force --no-interaction 2>/dev/null; then
    echo "Migrations OK"
else
    echo "Migrations skipped (DB not ready yet)"
fi

exec "$@"