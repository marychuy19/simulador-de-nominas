#!/bin/sh
set -ex

echo "==> [entrypoint] Starting docker-entrypoint.sh"

# Verify the frankenphp binary is available
echo "==> [entrypoint] Checking for frankenphp binary..."
if ! command -v frankenphp > /dev/null 2>&1; then
    echo "ERROR: frankenphp binary not found in PATH" >&2
    exit 1
fi
echo "==> [entrypoint] frankenphp found at: $(command -v frankenphp)"

# Verify the Caddyfile exists and is readable
echo "==> [entrypoint] Checking Caddyfile at /etc/caddy/Caddyfile..."
if [ ! -f /etc/caddy/Caddyfile ]; then
    echo "ERROR: /etc/caddy/Caddyfile does not exist" >&2
    exit 1
fi
if [ ! -r /etc/caddy/Caddyfile ]; then
    echo "ERROR: /etc/caddy/Caddyfile is not readable" >&2
    exit 1
fi
echo "==> [entrypoint] Caddyfile found and readable"
echo "==> [entrypoint] Caddyfile contents:"
cat /etc/caddy/Caddyfile

# Generate APP_KEY if not set (prevents Laravel from crashing on boot)
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is not set. Generating a temporary key..."
    php artisan key:generate --force
fi

# Cache Laravel configuration, routes, and views for production performance
echo "==> [entrypoint] Running php artisan config:cache..."
php artisan config:cache
echo "==> [entrypoint] Running php artisan route:cache..."
php artisan route:cache
echo "==> [entrypoint] Running php artisan view:cache..."
php artisan view:cache

# Run any pending migrations (optional — remove if you prefer manual migrations)
# php artisan migrate --force

echo "==> [entrypoint] All pre-flight checks passed. Handing off to FrankenPHP..."

# Hand off to FrankenPHP as PID 1
exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
