#!/bin/bash
set -e

echo "==> Starting ecovasos-store..."

# Create log directories
mkdir -p /var/log/php /var/log/supervisor

# Wait for MySQL
echo "==> Waiting for MySQL..."
MAX_TRIES=60
TRIES=0
until php artisan db:show --quiet 2>/dev/null; do
    TRIES=$((TRIES + 1))
    if [ "$TRIES" -ge "$MAX_TRIES" ]; then
        echo "ERROR: MySQL did not become ready in time."
        exit 1
    fi
    echo "   MySQL not ready yet ($TRIES/$MAX_TRIES)..."
    sleep 2
done
echo "==> MySQL is ready."

# Ensure storage structure
echo "==> Setting up storage directories..."
mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    storage/app/public \
    bootstrap/cache

# Run migrations if requested
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "==> Running migrations..."
    php artisan migrate --force
fi

# Cache configuration
echo "==> Caching config and routes..."
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Storage link
echo "==> Linking storage..."
rm -f public/storage
php artisan storage:link

# Fix permissions
echo "==> Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
