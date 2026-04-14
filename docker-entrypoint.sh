#!/bin/sh
set -ex

echo "==> Starting docker-entrypoint.sh"

# Pre-flight: verify frankenphp binary exists
echo "==> Checking for frankenphp binary..."
command -v frankenphp || { echo "ERROR: frankenphp binary not found in PATH"; exit 1; }

# Pre-flight: verify Caddyfile exists and is readable
echo "==> Checking for Caddyfile at /etc/caddy/Caddyfile..."
[ -f /etc/caddy/Caddyfile ] || { echo "ERROR: Caddyfile not found at /etc/caddy/Caddyfile"; exit 1; }
[ -r /etc/caddy/Caddyfile ] || { echo "ERROR: Caddyfile is not readable"; exit 1; }

echo "==> Caddyfile contents:"
cat /etc/caddy/Caddyfile

# Generate APP_KEY if not set (prevents Laravel from crashing on boot)
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is not set. Generating a temporary key..."
    php artisan key:generate --force
    echo "==> key:generate done"
fi

# Cache Laravel configuration, routes, and views for production performance
echo "==> Running config:cache..."
php artisan config:cache
echo "==> config:cache done"

echo "==> Running route:cache..."
php artisan route:cache
echo "==> route:cache done"

echo "==> Running view:cache..."
php artisan view:cache
echo "==> view:cache done"

# Run any pending migrations (optional — remove if you prefer manual migrations)
# php artisan migrate --force

# Hand off to FrankenPHP as PID 1
echo "==> Handing off to FrankenPHP..."
exec /usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
