#!/bin/bash
set -e

echo "==> Starting verdenova..."

# Create log directories
mkdir -p /var/log/php /var/log/supervisor

# Wait for MySQL
echo "==> Waiting for MySQL..."
MAX_TRIES=60
TRIES=0
until php -r "
\$pdo = new PDO(
    'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . getenv('DB_DATABASE'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD')
);
echo 'ok';
" 2>/dev/null | grep -q ok; do
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

# Cache: clear bootstrap caches directly (avoids booting the full framework
# before the DB is seeded, which causes Core::getCurrentChannelCode() to throw).
echo "==> Clearing bootstrap caches..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-v7.php
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/services.php
find storage/framework/views -name "*.php" -delete 2>/dev/null || true
find storage/framework/cache/data -mindepth 1 -delete 2>/dev/null || true

# Storage link (no artisan needed)
echo "==> Linking storage..."
rm -f public/storage
ln -sfn "$(pwd)/storage/app/public" "$(pwd)/public/storage"

# Fix permissions
echo "==> Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
