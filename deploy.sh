#!/bin/bash
set -e

# ─── Flags ────────────────────────────────────────────────────────────────────
RUN_MIGRATIONS=false
for arg in "$@"; do
    case $arg in
        --migrate) RUN_MIGRATIONS=true ;;
    esac
done

COMPOSE_FILE="docker-compose.production.yml"
ENV_FILE=".env.production"
IMAGE_NAME="verdenova"
NGINX_CONF_SRC="nginx-server.conf"
NGINX_CONF_DST="/etc/nginx/sites-available/verdenova"
NGINX_ENABLED="/etc/nginx/sites-enabled/verdenova"

# ─── Checks ───────────────────────────────────────────────────────────────────
if [ "$(id -u)" -ne 0 ]; then
    echo "ERROR: This script must be run as root (sudo ./deploy.sh)."
    exit 1
fi

if [ ! -f "$ENV_FILE" ]; then
    echo "ERROR: $ENV_FILE not found."
    echo "       Copy .env.production.example to .env.production and fill in the values."
    exit 1
fi

echo "============================================================"
echo "  verdenova deploy"
echo "  Migrations: $RUN_MIGRATIONS"
echo "============================================================"

# ─── Git pull ─────────────────────────────────────────────────────────────────
echo ""
echo "==> Pulling latest code..."
git pull

# ─── Nginx host setup ─────────────────────────────────────────────────────────
echo ""
echo "==> Configuring host nginx..."

if ! command -v nginx &>/dev/null; then
    echo "    Installing nginx..."
    apt-get update -q && apt-get install -y nginx
fi

cp "$NGINX_CONF_SRC" "$NGINX_CONF_DST"

if [ ! -L "$NGINX_ENABLED" ]; then
    ln -s "$NGINX_CONF_DST" "$NGINX_ENABLED"
    echo "    Symlink created: $NGINX_ENABLED"
fi

if [ -f "/etc/nginx/sites-enabled/default" ]; then
    rm /etc/nginx/sites-enabled/default
    echo "    Removed default site."
fi

nginx -t
systemctl reload nginx
echo "    nginx reloaded."

# ─── Docker build ─────────────────────────────────────────────────────────────
echo ""
echo "==> Building Docker image..."
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" build app

# ─── Stop old containers ──────────────────────────────────────────────────────
echo ""
echo "==> Stopping old containers..."
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" down || true

# ─── Start containers ─────────────────────────────────────────────────────────
echo ""
echo "==> Starting containers..."
export RUN_MIGRATIONS="$RUN_MIGRATIONS"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" up -d

# ─── Post-start ───────────────────────────────────────────────────────────────
echo ""
echo "==> Waiting for app to start (10s)..."
sleep 10

echo ""
echo "==> Running artisan cache commands..."
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app \
    php artisan config:clear

docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app \
    php artisan config:cache

# ─── Status ───────────────────────────────────────────────────────────────────
echo ""
echo "============================================================"
echo "  Deploy complete!"
echo "============================================================"
echo ""
echo "Container status:"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" ps

echo ""
echo "Recent logs:"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" logs --tail=20 app

APP_URL=$(grep -E '^APP_URL=' "$ENV_FILE" | cut -d= -f2- | tr -d '"'"'" | head -1)
ADMIN_URL=$(grep -E '^APP_ADMIN_URL=' "$ENV_FILE" | cut -d= -f2- | tr -d '"'"'" | head -1)
echo ""
echo "URLs:"
echo "  Store: ${APP_URL:-https://verdenova.com.br}"
echo "  Admin: ${APP_URL:-https://verdenova.com.br}/${ADMIN_URL:-admin}"
echo ""
echo "Useful commands:"
echo "  Logs:    docker compose -f $COMPOSE_FILE --env-file $ENV_FILE logs -f app"
echo "  Shell:   docker compose -f $COMPOSE_FILE --env-file $ENV_FILE exec app bash"
echo "  Artisan: docker compose -f $COMPOSE_FILE --env-file $ENV_FILE exec app php artisan"
